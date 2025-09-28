<?php
session_start();
?>
<!DOCTYPE html>
<html lang="en">

<head>
  <meta charset="utf-8">
  <meta content="width=device-width, initial-scale=1.0" name="viewport">
  <title>Dashboard</title>
  <meta content="" name="description">
  <meta content="" name="keywords">
  <link href="../../assets/img/bagong_silang_logo.png" rel="icon">
  <link href="../../assets/img/apple-touch-icon.png" rel="apple-touch-icon">
  <link rel="preconnect" href="https://fonts.googleapis.com">
  <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
  <link href="https://fonts.googleapis.com/css2?family=Inter:ital,opsz,wght@0,14..32,100..900;1,14..32,100..900&display=swap" rel="stylesheet">
  <link href="../../assets/vendor/bootstrap/css/bootstrap.min.css" rel="stylesheet">
  <link href="../../assets/vendor/bootstrap-icons/bootstrap-icons.css" rel="stylesheet">
  <link href="../../assets/vendor/boxicons/css/boxicons.min.css" rel="stylesheet">
  <link href="../../assets/vendor/quill/quill.snow.css" rel="stylesheet">
  <link href="../../assets/vendor/quill/quill.bubble.css" rel="stylesheet">
  <link href="../../assets/vendor/remixicon/remixicon.css" rel="stylesheet">
  <link href="../../assets/vendor/simple-datatables/style.css" rel="stylesheet">
  <link href="../../assets/css/style.css" rel="stylesheet">
  <link href="../../assets/css/schedule_maintenance.css" rel="stylesheet">
</head>
<body>
    <?php include '../../components/nav-bar.php' ?>
    <main id="main" class="main">

      <div class="pagetitle">
        <h1>Audit Management</h1>
        <nav>
          <ol class="breadcrumb">
            <li class="breadcrumb-item"><a href="<?=BASE_URL?>User_Admin/dashboard.php">Home</a></li>
            <li class="breadcrumb-item">Audit</li>
            <li class="breadcrumb-item active">Overview</li>
          </ol>
        </nav>
      </div>

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
          
          echo "<div class='alert alert-success'>✅ Audit scheduled successfully!</div>";
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
          
          echo "<div class='alert alert-info'>🔄 Audit #{$aid} started!</div>";
          echo "<script>
              setTimeout(function() {
                  document.getElementById('session-tab').click();
              }, 1000);
          </script>";
      }

    if (isset($_POST['end_audit'])) {
        $audit_id = intval($_POST['audit_id']);

        $sql = "SELECT department_code, audit_date, custodian 
                FROM bcp_sms4_audit WHERE id = ?";
        $stmt = $conn->prepare($sql);

        if (!$stmt) {
            echo "<div class='alert alert-danger'>❌ Error preparing audit query: " . $conn->error . "</div>";
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
                                    id INT AUTO_INCREMENT PRIMARY KEY,
                                    asset_tag VARCHAR(100),
                                    issue TEXT,
                                    resolved TINYINT DEFAULT 0,
                                    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP
                                )";
                                $conn->query($createDiscTable);
                            }

                            $discrepancyStmt = $conn->prepare("INSERT INTO bcp_sms4_audit_discrepancies 
                                                               (asset_tag, issue, resolved, created_at) 
                                                               VALUES (?, ?, 0, NOW())");
                            if ($discrepancyStmt) {
                                $asset_tag = "ASSET-" . $asset_id; 
                                $issue = "Status: " . $finding_status . ", Condition: " . $asset_condition;
                                if (!empty($remarks)) {
                                    $issue .= " - " . $remarks;
                                }

                                $discrepancyStmt->bind_param("ss", $asset_tag, $issue);
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

                  echo "<div class='alert alert-success'>✅ Audit #{$audit_id} completed successfully! Data has been moved to audit history.</div>";

              } catch (Exception $e) {
                  $conn->rollback();
                  echo "<div class='alert alert-danger'>❌ Error ending audit: " . $e->getMessage() . "</div>";
              }
          } else {
              echo "<div class='alert alert-danger'>❌ Audit not found!</div>";
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
              echo "<div class='alert alert-success'>📄 Report filed for discrepancy #{$discrepancy_id}.</div>";
          }

          if (isset($_POST['request_replacement'])) {
              $stmt = $conn->prepare("INSERT INTO bcp_sms4_procurement (discrepancy_id, status, requested_on) VALUES (?, 'Pending', NOW())");
              $stmt->bind_param("i", $discrepancy_id);
              $stmt->execute();
              $stmt->close();
              echo "<div class='alert alert-info'>🔄 Replacement request created for discrepancy #{$discrepancy_id}.</div>";
          }

          if (isset($_POST['mark_disposal'])) {
              $stmt = $conn->prepare("UPDATE bcp_sms4_audit_discrepancies SET resolved = 1 WHERE discrepancy_id = ?");
              $stmt->bind_param("i", $discrepancy_id);
              $stmt->execute();
              $stmt->close();
              echo "<div class='alert alert-danger'>🗑️ Discrepancy #{$discrepancy_id} marked as disposed.</div>";
          }
      }
      ?>

      <script>
      document.addEventListener("DOMContentLoaded", function() {
        const toast = document.querySelectorAll('.toast-alert'); 
        toast.forEach(t => {
          setTimeout(() => {
            t.style.transition = "opacity 0.5s ease";
            t.style.opacity = 0;
            setTimeout(() => t.remove(), 500);
          }, 3000);
        });
      });
      </script>

    <section class="section dashboard">
        <div class="row">
        <div class="col-xxl-4 col-md-6">
            <div class="card info-card">
            <div class="card-body">
                <h5 class="card-title">Upcoming Audits</h5>
                <div class="d-flex align-items-center">
                <div class="card-icon rounded-circle d-flex align-items-center justify-content-center">
                    <i class="bi bi-calendar-event text-primary"></i>
                </div>
                <div class="ps-3">
                    <h6><?php echo $upcoming_audits; ?></h6>
                    <span class="text-primary small">📅 Scheduled</span>
                </div>
                </div>
            </div>
            </div>
        </div>
        <div class="col-xxl-4 col-md-6">
            <div class="card info-card">
            <div class="card-body">
                <h5 class="card-title">Discrepancies</h5>
                <div class="d-flex align-items-center">
                <div class="card-icon rounded-circle d-flex align-items-center justify-content-center">
                    <i class="bi bi-exclamation-triangle text-danger"></i>
                </div>
                <div class="ps-3">
                    <h6><?php echo $last_discrepancies; ?></h6>
                    <span class="text-danger small">⚠️ Issues Found</span>
                </div>
                </div>
            </div>
            </div>
        </div>
        <div class="col-xxl-4 col-md-6">
            <div class="card info-card">
            <div class="card-body">
                <h5 class="card-title">Pending Replacements</h5>
                <div class="d-flex align-items-center">
                <div class="card-icon rounded-circle d-flex align-items-center justify-content-center">
                    <i class="bi bi-arrow-repeat text-warning"></i>
                </div>
                <div class="ps-3">
                    <h6><?php echo $pending_replacements; ?></h6>
                    <span class="text-warning small">🔄 Awaiting action</span>
                </div>
                </div>
            </div>
            </div>
        </div>
        </div>
    </section>

    <section class="section">
        <div class="row">
        <div class="col-lg-12">
            <div class="card">
            <div class="card-body">
                <div class="d-flex justify-content-between align-items-center mb-2">
                <ul class="nav nav-tabs" id="auditTabs" role="tablist">
                    <li class="nav-item" role="presentation">
                    <button class="nav-link active" id="upcoming-tab" data-bs-toggle="tab" data-bs-target="#upcoming" type="button" role="tab">Upcoming Audits</button>
                    </li>
                    <li class="nav-item" role="presentation">
                    <button class="nav-link" id="session-tab" data-bs-toggle="tab" data-bs-target="#session" type="button" role="tab">Audit Sessions</button>
                    </li>
                    <li class="nav-item" role="presentation">
                    <button class="nav-link" id="discrepancies-tab" data-bs-toggle="tab" data-bs-target="#discrepancies" type="button" role="tab">Discrepancies</button>
                    </li>
                    <li class="nav-item" role="presentation">
                    <button class="nav-link" id="reports-tab" data-bs-toggle="tab" data-bs-target="#reports" type="button" role="tab">Reports</button>
                    </li>
                </ul>

                <div class="small text-muted">Current audit: <strong><?php echo htmlspecialchars($current_audit_label); ?></strong></div>
                </div>

                <div class="tab-content mt-3" id="auditTabsContent">

              <!-- Upcoming Audits -->
              <div class="tab-pane fade show active" id="upcoming" role="tabpanel">
                <h5 class="card-title">Schedule New Audit</h5>
                <button class="btn btn-primary mb-3" data-bs-toggle="modal" data-bs-target="#scheduleAuditModal">Schedule New Audit</button>
                <div class="modal fade" id="scheduleAuditModal" tabindex="-1">
                  <div class="modal-dialog">
                    <form method="POST" action="">
                      <div class="modal-content">
                        <div class="modal-header">
                          <h5 class="modal-title">Schedule Audit</h5>
                          <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                        </div>
                        <div class="modal-body">
                          <div class="mb-3">
                            <label>Date</label>
                            <input type="date" name="audit_date" class="form-control" required>
                          </div>
                        <div class="mb-3">
                            <label>Department</label>
                            <select name="department_code" class="form-control" required>
                              <option value="">-- Select Department --</option>
                              <option value="BSIT">BSIT</option>
                              <option value="CRIM">CRIM</option>
                              <option value="BADTRIP">BADTRIP</option>
                              <option value="BS PHCYHOLOGY">BS PHCYHOLOGY</option>
                            </select>
                          </div>
                          <div class="mb-3">
                            <label>Custodian</label>
                            <input type="text" name="custodian" class="form-control" required>
                          </div>
                        </div>
                        <div class="modal-footer">
                          <button type="submit" name="schedule_audit" class="btn btn-success">Save</button>
                          <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancel</button>
                        </div>
                      </div>
                    </form>
                  </div>
                </div>

                <table class="table datatable">
                  <thead>
                    <tr>
                      <th>Audit ID</th>
                      <th>Date</th>
                      <th>Department</th>
                      <th>Custodian</th>
                      <th>Status</th>
                      <th>Action</th>
                    </tr>
                  </thead>
                  <tbody>
                    <?php
                    $audits = $conn->query("SELECT * FROM bcp_sms4_audit ORDER BY audit_date DESC");
                    if ($audits && $audits->num_rows > 0):
                        while ($row = $audits->fetch_assoc()):
                    ?>
                        <tr>
                          <td><?= $row['id'] ?></td>
                          <td><?= $row['audit_date'] ?></td>
                          <td><?= htmlspecialchars($row['department_code']) ?></td>
                          <td><?= htmlspecialchars($row['custodian']) ?></td>
                          <td>
                            <?php if ($row['status'] == 'Ongoing'): ?>
                              <span class="badge bg-warning"><?= $row['status'] ?></span>
                            <?php elseif ($row['status'] == 'Completed'): ?>
                              <span class="badge bg-success"><?= $row['status'] ?></span>
                            <?php else: ?>
                              <span class="badge bg-secondary"><?= $row['status'] ?></span>
                            <?php endif; ?>
                          </td>
                          <td>
                            <?php if ($row['status'] == 'Scheduled'): ?>
                              <form method="POST" style="display:inline">
                                <input type="hidden" name="start_audit_id" value="<?= $row['id'] ?>">
                                <button class="btn btn-sm btn-success" type="submit" onclick="return confirm('Start this audit?')">Start Audit</button>
                              </form>
                            <?php else: ?>
                              <span class="small text-muted">—</span>
                            <?php endif; ?>
                          </td>
                        </tr>
                    <?php endwhile; else: ?>
                        <tr><td colspan="6">No audits scheduled</td></tr>
                    <?php endif; ?>
                  </tbody>
                </table>
              </div>

           <!-- Audit Session -->
            <div class="tab-pane fade" id="session" role="tabpanel">
            <h5 class="card-title">Audit Session</h5>

            <?php if (isset($_SESSION['current_audit'])): ?>
                <div class="alert alert-info">
                    <strong>Current Audit:</strong> <?php echo htmlspecialchars($current_audit_label); ?>
                </div>

                <form method="POST" action="" onsubmit="return confirm('Are you sure you want to end this audit? This action cannot be undone.');">
                    <input type="hidden" name="audit_id" value="<?php echo intval($_SESSION['current_audit']); ?>">

                    <table class="table datatable">
                    <thead>
                        <tr>
                        <th>Reference No</th>
                        <th>Asset Tag</th>
                        <th>Description</th>
                        <th>Department</th>
                        <th>Quantity</th>
                        <th>Status</th>
                        <th>Condition</th>
                        <th>Remarks</th>
                        </tr>
                    </thead>
                     <tbody>
                       <?php
                        $auditId = intval($_SESSION['current_audit']);
                        $currentDept = $_SESSION['current_department'] ?? '';

                        if ($currentDept) {
                            $assets = $conn->prepare("SELECT id, reference_no, equipment_id, item_name, quantity, department_code FROM bcp_sms4_issuance WHERE department_code = ? ORDER BY id ASC");
                            if ($assets) {
                                $assets->bind_param("s", $currentDept);
                                $assets->execute();
                                $result = $assets->get_result();
                                $assets->close();
                            } else {
                                echo "<div class='alert alert-danger'>Error preparing query: " . $conn->error . "</div>";
                                $result = false;
                            }
                        } else {
                            $result = $conn->query("SELECT id, reference_no, equipment_id, item_name, quantity, department_code FROM bcp_sms4_issuance ORDER BY id ASC");
                            if (!$result) {
                                echo "<div class='alert alert-danger'>Error executing query: " . $conn->error . "</div>";
                            }
                        }

                        if ($result && $result->num_rows > 0):
                            while ($row = $result->fetch_assoc()):
                        ?>
                        <tr>
                            <td><?php echo htmlspecialchars($row['reference_no']); ?></td>
                            <td><?php echo htmlspecialchars($row['equipment_id']); ?></td>
                            <td><?php echo htmlspecialchars($row['item_name']); ?></td>
                            <td><?php echo htmlspecialchars($row['department_code']); ?></td>
                            <td><?php echo htmlspecialchars($row['quantity']); ?></td>
                            <td>
                                <select name="status[<?php echo $row['id']; ?>]" class="form-select form-select-sm">
                                    <option value="Valid">Valid</option>
                                    <option value="Disposed">Disposed</option>
                                    <option value="Mismatch">Mismatch</option>
                                    <option value="Missing">Missing</option>
                                </select>
                            </td>
                            <td>
                                <select name="condition[<?php echo $row['id']; ?>]" class="form-select form-select-sm">
                                    <option value="Good">Good</option>
                                    <option value="Fair">Fair</option>
                                    <option value="Damaged">Damaged</option>
                                    <option value="Lost">Lost</option>
                                </select>
                            </td>
                            <td>
                                <input type="text" name="remarks[<?php echo $row['id']; ?>]" class="form-control form-control-sm" placeholder="Enter remarks">
                            </td>
                        </tr>
                        <?php 
                            endwhile; 
                        else: 
                        ?>
                            <tr>
                                <td colspan="8" class="text-center">No assets found for this department</td>
                            </tr>
                        <?php endif; ?>
                      </tbody>
                    </table>
                    <div class="d-flex gap-2">
                        <button type="submit" name="end_audit" class="btn btn-danger mt-2">
                            <i class="bi bi-stop-circle"></i> End Audit & Save to History
                        </button>
                        <a href="#" class="btn btn-secondary mt-2" onclick="window.location.reload()">
                            <i class="bi bi-arrow-clockwise"></i> Refresh
                        </a>
                    </div>
                </form>
                <?php else: ?>
                    <div class="alert alert-warning">
                        <strong>No Active Audit Session</strong><br>
                        Please start an audit from the "Upcoming Audits" tab to begin an audit session.
                    </div>
                <?php endif; ?>
            </div>

            <!-- Discrepancies -->
              <div class="tab-pane fade" id="discrepancies" role="tabpanel">
                <h5 class="card-title">Discrepancy List</h5>
                <table class="table datatable">
                  <thead>
                    <tr>
                      <th>Asset Tag</th>
                      <th>Issue</th>
                      <th>Created Date</th>
                      <th>Action</th>
                    </tr>
                  </thead>    
                  <tbody>
                    <?php
                    $discrepancies = $conn->query("SELECT * FROM bcp_sms4_audit_discrepancies WHERE resolved = 0 ORDER BY created_at DESC");
                    if ($discrepancies && $discrepancies->num_rows > 0):
                      while ($row = $discrepancies->fetch_assoc()):
                    ?>
                    <tr>
                      <td><?= htmlspecialchars($row['audit_id'] ?? 'N/A') ?></td>
                      <td><?= htmlspecialchars($row['description'] ?? 'No description') ?></td>
                      <td><?= date('M d, Y', strtotime($row['created_at'])) ?></td>
                      <td>
                          <button class="btn btn-warning btn-sm" 
                                  onclick="generateReport(
                                    '<?= $row['discrepancy_id'] ?>',
                                    '<?= htmlspecialchars($row['audit_type']) ?>',
                                    '<?= htmlspecialchars($row['audit_id']) ?>',
                                    '<?= htmlspecialchars($row['description']) ?>',
                                    '<?= date('M d, Y', strtotime($row['created_at'])) ?>',
                                    <?= $row['resolved'] ?>
                                  )">
                              Generate Report
                          </button>

                          <form method="POST" style="display:inline">
                            <input type="hidden" name="discrepancy_id" value="<?= $row['discrepancy_id'] ?>">
                            <button type="submit" name="request_replacement" class="btn btn-info btn-sm">Request Replacement</button>
                          </form>

                          <form method="POST" style="display:inline">
                            <input type="hidden" name="discrepancy_id" value="<?= $row['discrepancy_id'] ?>">
                            <button type="submit" name="mark_disposal" class="btn btn-danger btn-sm">Mark Disposal</button>
                          </form>
                      </td>
                    </tr>
                    <?php endwhile; else: ?>
                    <tr><td colspan="4">No discrepancies found</td></tr>
                    <?php endif; ?> 
                  </tbody>
                </table>
              </div>

              <script>
              function generateReport(id, type, audit, description, created, resolved) {
                  resolved = resolved == 1 ? "RESOLVED" : "UNRESOLVED";

                  let html = `
                    <div style="width: 100%; max-width: 800px; margin: auto; font-family: Arial, sans-serif; color: #000;">
                      
                      <!-- Header -->
                      <div style="text-align: center; border-bottom: 2px solid #000; padding-bottom: 10px; margin-bottom: 20px;">
                        <h1 style="margin: 0; font-size: 20pt;">AUDIT DISCREPANCY REPORT</h1>
                        <p style="margin: 5px 0;">Property Custodian Management System</p>
                        <p style="margin: 0; font-size: 10pt;">Report No: ADR-${String(id).padStart(4, '0')}</p>
                        <p style="margin: 0; font-size: 10pt;">Generated by: <?= htmlspecialchars($_SESSION['username'] ?? 'Admin') ?></p>
                        <p style="margin: 0; font-size: 10pt;">Generated on: ${new Date().toLocaleDateString()} ${new Date().toLocaleTimeString()}</p>
                      </div>

                      <!-- Discrepancy Details -->
                      <h2 style="font-size: 14pt; border-bottom: 1px solid #000; padding-bottom: 5px;">Discrepancy Details</h2>
                      <table style="width: 100%; border-collapse: collapse; margin-bottom: 20px;">
                        <tr>
                          <td style="font-weight: bold; width: 30%; padding: 8px; border: 1px solid #000; background-color: #f2f2f2;">Discrepancy ID</td>
                          <td style="padding: 8px; border: 1px solid #000;">${id}</td>
                        </tr>
                        <tr>
                          <td style="font-weight: bold; padding: 8px; border: 1px solid #000; background-color: #f2f2f2;">Audit Type</td>
                          <td style="padding: 8px; border: 1px solid #000;">${type}</td>
                        </tr>
                        <tr>
                          <td style="font-weight: bold; padding: 8px; border: 1px solid #000; background-color: #f2f2f2;">Audit ID</td>
                          <td style="padding: 8px; border: 1px solid #000;">${audit}</td>
                        </tr>
                        <tr>
                          <td style="font-weight: bold; padding: 8px; border: 1px solid #000; background-color: #f2f2f2;">Date Reported</td>
                          <td style="padding: 8px; border: 1px solid #000;">${created}</td>
                        </tr>
                        <tr>
                          <td style="font-weight: bold; padding: 8px; border: 1px solid #000; background-color: #f2f2f2;">Current Status</td>
                          <td style="padding: 8px; border: 1px solid #000;">${resolved}</td>
                        </tr>
                      </table>

                      <!-- Problem Description -->
                      <h2 style="font-size: 14pt; border-bottom: 1px solid #000; padding-bottom: 5px;">Problem Description</h2>
                      <div style="border: 1px solid #000; padding: 15px; background-color: #fafafa; min-height: 100px; margin-bottom: 20px;">
                        ${description}
                      </div>

                      <!-- Action Plan Placeholder -->
                      <h2 style="font-size: 14pt; border-bottom: 1px solid #000; padding-bottom: 5px;">Corrective Action Plan</h2>
                      <div style="border: 1px solid #000; padding: 15px; min-height: 100px; margin-bottom: 20px;">
                        □ Item Repair &nbsp;&nbsp; □ Item Replacement &nbsp;&nbsp; □ Item Disposal &nbsp;&nbsp; □ Further Investigation
                        <div style="margin-top: 15px; border-top: 1px dashed #000; height: 50px;"></div>
                      </div>

                      <!-- Signatures -->
                      <h2 style="font-size: 14pt; border-bottom: 1px solid #000; padding-bottom: 5px;">Authorization & Approval</h2>
                      <table style="width: 100%; margin-top: 10px; border-collapse: collapse;">
                        <tr>
                          <td style="width: 50%; border: 1px solid #000; text-align: center; padding: 20px;">
                            <div style="border-bottom: 2px solid #000; height: 50px; margin-bottom: 5px;"></div>
                            Property Custodian <br> Print Name & Signature <br> Date: ______________
                          </td>
                          <td style="width: 50%; border: 1px solid #000; text-align: center; padding: 20px;">
                            <div style="border-bottom: 2px solid #000; height: 50px; margin-bottom: 5px;"></div>
                            Supervisor/Manager <br> Print Name & Signature <br> Date: ______________
                          </td>
                        </tr>
                      </table>

                      <!-- Footer -->
                      <div style="text-align: center; margin-top: 30px; font-size: 9pt; color: #666;">
                        This is an official document generated by the Property Custodian Management System.
                      </div>
                    </div>
                  `;

                  let printWindow = window.open('', '_blank', 'width=900,height=700');
                  printWindow.document.write(`<html><head><title>Audit Discrepancy Report</title></head><body>${html}</body></html>`);
                  printWindow.document.close();
                  printWindow.focus();
                  printWindow.print();
                }
              </script>

              <!-- Reports -->
              <div class="tab-pane fade" id="reports" role="tabpanel">
                <h5 class="card-title">Audit History & Reports</h5>
                <table class="table datatable">
                  <thead>
                    <tr>
                      <th>Audit ID</th>
                      <th>Department</th>
                      <th>Started Date</th>
                      <th>Completed Date</th>
                      <th>Status</th>
                      <th>Actions</th>
                    </tr>
                  </thead>
                  <tbody>
                    <?php
                    $history = $conn->query("SELECT * FROM bcp_sms4_audit_history ORDER BY completed_date DESC");
                    if ($history && $history->num_rows > 0):
                      while ($row = $history->fetch_assoc()):
                    ?>
                    <tr>
                      <td><?= htmlspecialchars($row['audit_id']) ?></td>
                      <td><?= htmlspecialchars($row['department_code']) ?></td>
                      <td><?= date('M d, Y H:i', strtotime($row['started_date'])) ?></td>
                      <td><?= date('M d, Y H:i', strtotime($row['completed_date'])) ?></td>
                      <td>
                        <span class="badge bg-success"><?= htmlspecialchars($row['status']) ?></span>
                      </td>
                      <td>
                        <button class="btn btn-secondary btn-sm" onclick="viewAuditDetails(<?= $row['id'] ?>)">View Details</button>
                        <button class="btn btn-primary btn-sm">Export PDF</button>
                      </td>
                    </tr>
                    <?php endwhile; else: ?>
                      <tr><td colspan="6">No audit history available</td></tr>
                    <?php endif; ?>
                  </tbody>
                </table>
              </div>

            </div>
          </div>
        </div>

      </div>
    </div>
  </section>

  <script>
    $(document).ready(function() {
        if (!$.fn.DataTable.isDataTable('.datatable')) {
            $('.datatable').DataTable({
                "pageLength": 10,
                "lengthChange": true,
                "searching": true,
                "ordering": true,
                "info": true,
                "autoWidth": false,
                "responsive": true
            });
        }
    });

    function viewAuditDetails(historyId) {
        alert('View details for audit history ID: ' + historyId);
    }
  </script>
</main>
</body>
  <a href="#" class="back-to-top d-flex align-items-center justify-content-center"><i class="bi bi-arrow-up-short"></i></a>
  <script src="../../assets/vendor/apexcharts/apexcharts.min.js"></script>
  <script src="../../assets/vendor/bootstrap/js/bootstrap.bundle.min.js"></script>
  <script src="../../assets/vendor/chart.js/chart.umd.js"></script>
  <script src="../../assets/vendor/echarts/echarts.min.js"></script>
  <script src="../../assets/vendor/quill/quill.js"></script>
  <script src="../../assets/vendor/simple-datatables/simple-datatables.js"></script>
  <script src="../../assets/vendor/tinymce/tinymce.min.js"></script>
  <script src="../../assets/vendor/php-email-form/validate.js"></script>
  <script src="../../assets/js/main.js"></script>
  </body>
</html>