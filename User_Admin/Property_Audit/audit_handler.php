<?php
include '../../connection.php';
session_start();

$auditorName = $_SESSION['user_name'] ?? $_SESSION['username'] ?? 'Admin';

// Schedule audit
if (isset($_POST['schedule_audit'])) {
    $audit_date = $_POST['audit_date'];
    $department_id = $_POST['department_id']; 
    $custodian_id = $_POST['custodian_id'];

    // Insert into database
    $stmt = $conn->prepare("INSERT INTO bcp_sms4_audit (audit_date, department_id, custodian_id, status) VALUES (?, ?, ?, 'Scheduled')");
    $stmt->bind_param("sii", $audit_date, $department_id, $custodian_id);
    $stmt->execute();
    $stmt->close();

    // Set a session message for the toast
    $_SESSION['toast_message'] = [
        'message' => 'Audit scheduled successfully!',
        'type' => 'success'
    ];

    // Redirect immediately to avoid resubmission on reload
    header("Location: " . $_SERVER['PHP_SELF']);
    exit;
}

// Then, in your page, display the toast if session is set
if (isset($_SESSION['toast_message'])) {
    $toast = $_SESSION['toast_message'];
    echo "<script>
        window.addEventListener('load', function() {
            showToast('{$toast['message']}', '{$toast['type']}');
        });
    </script>";
    unset($_SESSION['toast_message']);
}


if (isset($_POST['start_audit_id'])) {
    $audit_id = intval($_POST['start_audit_id']);

    // 1. Update audit status to Ongoing
    $stmt = $conn->prepare("UPDATE bcp_sms4_audit SET status = 'Ongoing' WHERE id = ?");
    $stmt->bind_param("i", $audit_id);
    $stmt->execute();
    $stmt->close();

    // 2. Fetch department_id and custodian_id for this audit
    $stmt2 = $conn->prepare("SELECT department_id, custodian_id FROM bcp_sms4_audit WHERE id = ? LIMIT 1");
    $stmt2->bind_param("i", $audit_id);
    $stmt2->execute();
    $audit = $stmt2->get_result()->fetch_assoc();
    $stmt2->close();

    if ($audit) {
        // 3. Set session variables
        $_SESSION['current_audit'] = $audit_id;
        $_SESSION['current_department'] = $audit['department_id'];
        $_SESSION['current_custodian'] = $audit['custodian_id'];

        // ✅ Store toast in session instead of echoing inline
        $_SESSION['toast_message'] = [
            'message' => "Audit #{$audit_id} started!",
            'type' => 'info'
        ];
    } else {
        $_SESSION['toast_message'] = [
            'message' => "Audit not found!",
            'type' => 'danger'
        ];
    }
    error_log("Start audit triggered for ID {$audit_id}");
    // ✅ Redirect to reload page cleanly
    header("Location: " . $_SERVER['PHP_SELF']);
    exit;
}



// End audit
if (isset($_POST['end_audit'])) {
    $audit_id = intval($_POST['audit_id']);

    $stmt = $conn->prepare("SELECT department_id, audit_date, custodian_id FROM bcp_sms4_audit WHERE id = ?");
    $stmt->bind_param("i", $audit_id);
    $stmt->execute();
    $audit = $stmt->get_result()->fetch_assoc();
    $stmt->close();

    if ($audit) {
        try {
            $conn->begin_transaction();

            $started_date = date("Y-m-d H:i:s", strtotime($audit['audit_date']));

            $stmt2 = $conn->prepare("INSERT INTO bcp_sms4_audit_history 
                                     (audit_id, department_id, started_date, completed_date, status, remarks) 
                                     VALUES (?, ?, ?, NOW(), 'Completed', ?)");
            $remarks = "Audit completed by {$auditorName} for department {$audit['department_id']}";
            $stmt2->bind_param("iiss", $audit_id, $audit['department_id'], $started_date, $remarks);
            $stmt2->execute();
            $history_id = $stmt2->insert_id;
            $stmt2->close();

            $updateAuditStmt = $conn->prepare("UPDATE bcp_sms4_audit SET status = 'Completed' WHERE id = ?");
            $updateAuditStmt->bind_param("i", $audit_id);
            $updateAuditStmt->execute();
            $updateAuditStmt->close();

            $conn->commit();

            unset($_SESSION['current_audit']);
            unset($_SESSION['current_department']);

            echo "<script>
                window.addEventListener('load', function() {
                    showToast('Audit #{$audit_id} completed successfully!', 'success');
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

// Counts
$upcoming_audits = $conn->query("SELECT COUNT(*) as total FROM bcp_sms4_audit WHERE audit_date >= CURDATE() AND status != 'Completed'")->fetch_assoc()['total'] ?? 0;
$last_discrepancies = $conn->query("SELECT COUNT(*) as total FROM bcp_sms4_audit_discrepancies WHERE resolved = 0")->fetch_assoc()['total'] ?? 0;
$pending_replacements = $conn->query("SELECT COUNT(*) as total FROM bcp_sms4_procurement WHERE status = 'Pending'")->fetch_assoc()['total'] ?? 0;

// Current audit label
$current_audit_label = 'None';
if (isset($_SESSION['current_audit'])) {
    $aid = intval($_SESSION['current_audit']);
    $r = $conn->query("SELECT a.id, a.audit_date, d.dept_name, ad.fullname 
                       FROM bcp_sms4_audit a
                       LEFT JOIN bcp_sms4_departments d ON a.department_id = d.id
                       LEFT JOIN bcp_sms4_admins ad ON a.custodian_id = ad.id
                       WHERE a.id = {$aid}")->fetch_assoc();
    if ($r) {
        $current_audit_label = "Audit #{$r['id']} — {$r['audit_date']} ({$r['dept_name']} / {$r['fullname']})";
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