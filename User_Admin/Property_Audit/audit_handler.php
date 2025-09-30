    <?php
      include '../../connection.php';
      $auditorName = isset($_SESSION['user_name']) ? $_SESSION['user_name'] : (isset($_SESSION['username']) ? $_SESSION['username'] : 'Admin');

      if (isset($_POST['schedule_audit'])) {
          $audit_date = $_POST['audit_date'];
          $department_code = $_POST['department_code']; 
          $custodian = $_POST['custodian'];

          $stmt = $conn->prepare("INSERT INTO bcp_sms4_audit (audit_date, department_code, custodian, status) VALUES (?, ?, ?, 'Scheduled')");
          $stmt->bind_param("sss", $audit_date, $department_code, $custodian);
          $stmt->execute();
          $stmt->close();
          
          echo "<script>
              window.addEventListener('load', function() {
                  showToast('Audit scheduled successfully!', 'success');
              });
          </script>";
      }

      if (isset($_POST['start_audit_id'])) {
          $aid = intval($_POST['start_audit_id']);
          $stmt = $conn->prepare("UPDATE bcp_sms4_audit SET status = 'Ongoing' WHERE id = ?");
          $stmt->bind_param("i", $aid);
          $stmt->execute();
          $stmt->close();
          
          $res = $conn->query("SELECT department_code FROM bcp_sms4_audit WHERE id = {$aid} LIMIT 1");
          $dep = $res && $res->num_rows > 0 ? $res->fetch_assoc()['department_code'] : null;

          $_SESSION['current_audit'] = $aid;
          $_SESSION['current_department'] = $dep;
          
          echo "<script>
              window.addEventListener('load', function() {
                  showToast('Audit #{$aid} started!', 'info');
                  setTimeout(function() {
                      document.getElementById('session-tab').click();
                  }, 1000);
              });
          </script>";
      }

    if (isset($_POST['end_audit'])) {
        $audit_id = intval($_POST['audit_id']);

        $sql = "SELECT department_code, audit_date, custodian 
                FROM bcp_sms4_audit WHERE id = ?";
        $stmt = $conn->prepare($sql);

        if (!$stmt) {
            echo "<script>
                window.addEventListener('load', function() {
                    showToast('Error preparing audit query: " . addslashes($conn->error) . "', 'danger');
                });
            </script>";
        } else {
            $stmt->bind_param("i", $audit_id);
            $stmt->execute();
            $audit = $stmt->get_result()->fetch_assoc();
            $stmt->close();
        }

        if ($audit) {
        try {

            $conn->begin_transaction();

            $started_date = date("Y-m-d H:i:s", strtotime($audit['audit_date']));

            $insertHistory = "INSERT INTO bcp_sms4_audit_history 
                             (audit_id, department_code, started_date, completed_date, status, remarks) 
                             VALUES (?, ?, ?, NOW(), 'Completed', ?)";
            $stmt2 = $conn->prepare($insertHistory);

            if (!$stmt2) {
                throw new Exception("Failed to prepare history insert: " . $conn->error);
            }

            $remarks = "Audit completed by " . $auditorName . " for department " . $audit['department_code'];
            $stmt2->bind_param("isss", $audit_id, $audit['department_code'], $started_date, $remarks);

            if (!$stmt2->execute()) {
                throw new Exception("History insert failed: " . $stmt2->error);
            }
            $history_id = $stmt2->insert_id;
            $stmt2->close();

            if (isset($_POST['status']) && !empty($_POST['status'])) {
                $findingInsertStmt = $conn->prepare("INSERT INTO bcp_sms4_audit_findings 
                                                    (history_id, asset_id, asset_name, quantity, finding_status, asset_condition, remarks) 
                                                    VALUES (?, ?, ?, ?, ?, ?, ?)");

                foreach ($_POST['status'] as $asset_id => $finding_status) {
                    $asset_condition = $_POST['asset_condition'][$asset_id] ?? 'Good';
                    $remarks   = $_POST['remarks'][$asset_id] ?? '';

                    $assetQuery = $conn->prepare("SELECT item_name, quantity FROM bcp_sms4_issuance WHERE id = ?");
                    $assetQuery->bind_param("i", $asset_id);
                    $assetQuery->execute();
                    $asset = $assetQuery->get_result()->fetch_assoc();
                    $assetQuery->close();

                    if ($asset) {
                        $findingInsertStmt->bind_param(
                            "iisiiss", 
                            $history_id, 
                            $asset_id, 
                            $asset['item_name'], 
                            $asset['quantity'], 
                            $finding_status, 
                            $asset_condition, 
                            $remarks
                        );

                        if (!$findingInsertStmt->execute()) {
                            throw new Exception("Finding insert failed: " . $findingInsertStmt->error);
                        }

                        if ($finding_status !== 'Valid' || $asset_condition !== 'Good') {
                            $checkDiscTable = $conn->query("SHOW TABLES LIKE 'bcp_sms4_audit_discrepancies'");
                            if ($checkDiscTable->num_rows == 0) {
                                $createDiscTable = "CREATE TABLE bcp_sms4_audit_discrepancies (
                                    discrepancy_id INT AUTO_INCREMENT PRIMARY KEY,
                                    asset_tag VARCHAR(100),
                                    issue TEXT,
                                    resolved TINYINT DEFAULT 0,
                                    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP
                                )";
                                $conn->query($createDiscTable);
                            }

                         $discrepancyStmt = $conn->prepare("INSERT INTO bcp_sms4_audit_discrepancies 
                            (audit_type, audit_id, description, resolved, created_at) 
                            VALUES (?, ?, ?, 0, NOW())");

                        if ($discrepancyStmt) {
                            $audit_type = 'Asset'; 
                            $description = "Status: " . $finding_status . ", Condition: " . $asset_condition;
                            if (!empty($remarks)) {
                                $description .= " - " . $remarks;
                            }

                            $discrepancyStmt->bind_param("sis", $audit_type, $asset_id, $description);
                            $discrepancyStmt->execute();
                            $discrepancyStmt->close();
                        }
                        }
                    }
                }
                $findingInsertStmt->close();
            }

                  $updateAuditStmt = $conn->prepare("UPDATE bcp_sms4_audit SET status = 'Completed' WHERE id = ?");
                  $updateAuditStmt->bind_param("i", $audit_id);
                  $updateAuditStmt->execute();
                  $updateAuditStmt->close();
                  $conn->commit();

                  unset($_SESSION['current_audit']);
                  unset($_SESSION['current_department']);

                  echo "<script>
                      window.addEventListener('load', function() {
                          showToast('Audit #{$audit_id} completed successfully! Data moved to audit history.', 'success');
                      });
                  </script>";

              } catch (Exception $e) {
                  $conn->rollback();
                  echo "<script>
                      window.addEventListener('load', function() {
                          showToast('Error ending audit: " . addslashes($e->getMessage()) . "', 'danger');
                      });
                  </script>";
              }
          } else {
              echo "<script>
                  window.addEventListener('load', function() {
                      showToast('Audit not found!', 'danger');
                  });
              </script>";
          }
      }

      $upcoming_audits = 0;
      $result = $conn->query("SELECT COUNT(*) as total FROM bcp_sms4_audit WHERE audit_date >= CURDATE() AND status != 'Completed'");
      if ($result && $row = $result->fetch_assoc()) {
          $upcoming_audits = $row['total'];
      }

      $last_discrepancies = 0;
      $result = $conn->query("SELECT COUNT(*) as total FROM bcp_sms4_audit_discrepancies WHERE resolved = 0");
      if ($result && $row = $result->fetch_assoc()) {
          $last_discrepancies = $row['total'];
      }

      $pending_replacements = 0;
      $result = $conn->query("SELECT COUNT(*) as total FROM bcp_sms4_procurement WHERE status = 'Pending'");
      if ($result && $row = $result->fetch_assoc()) {
          $pending_replacements = $row['total'];
      }

      $current_audit_label = 'None';
      if (isset($_SESSION['current_audit'])) {
          $aid = intval($_SESSION['current_audit']);
          $r = $conn->query("SELECT id, audit_date, department_code, custodian FROM bcp_sms4_audit WHERE id = {$aid}")->fetch_assoc();
          if ($r) {
              $current_audit_label = "Audit #{$r['id']} — {$r['audit_date']} ({$r['department_code']} / {$r['custodian']})";
          } else {
              unset($_SESSION['current_audit']);
              unset($_SESSION['current_department']);
          }
      }

      if (isset($_POST['discrepancy_id'])) {
          $discrepancy_id = intval($_POST['discrepancy_id']);
          
          if (isset($_POST['file_report'])) {
              $stmt = $conn->prepare("UPDATE bcp_sms4_audit_discrepancies SET resolved = 1 WHERE discrepancy_id = ?");
              $stmt->bind_param("i", $discrepancy_id);
              $stmt->execute();
              $stmt->close();
              echo "<script>
                  window.addEventListener('load', function() {
                      showToast('Report filed for discrepancy #{$discrepancy_id}', 'success');
                  });
              </script>";
          }

          if (isset($_POST['request_replacement'])) {
              $stmt = $conn->prepare("INSERT INTO bcp_sms4_procurement (discrepancy_id, status, requested_on) VALUES (?, 'Pending', NOW())");
              $stmt->bind_param("i", $discrepancy_id);
              $stmt->execute();
              $stmt->close();
              echo "<script>
                  window.addEventListener('load', function() {
                      showToast('Replacement request created for discrepancy #{$discrepancy_id}', 'info');
                  });
              </script>";
          }

          if (isset($_POST['mark_disposal'])) {
              $stmt = $conn->prepare("UPDATE bcp_sms4_audit_discrepancies SET resolved = 1 WHERE discrepancy_id = ?");
              $stmt->bind_param("i", $discrepancy_id);
              $stmt->execute();
              $stmt->close();
              echo "<script>
                  window.addEventListener('load', function() {
                      showToast('Discrepancy #{$discrepancy_id} marked as disposed', 'danger');
                  });
              </script>";
          }
      }
      ?>