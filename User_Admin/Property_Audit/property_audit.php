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
  <link href="../../assets/css/auditmodal.css" rel="stylesheet">
</head>
<body>
  <?php
  include 'audit_handler.php';
  include '../../components/nav-bar.php';
  ?>
  <?php
    include '../../connection.php';

    $auditorName = $_SESSION['user_name'] ?? $_SESSION['username'] ?? 'Admin';

    if (isset($_POST['schedule_audit'])) {
        $audit_date = $_POST['audit_date'];
        $department_id = $_POST['department_id']; 
        $custodian_id = $_POST['custodian_id'];

        $stmt = $conn->prepare("INSERT INTO bcp_sms4_audit (audit_date, department_id, custodian_id, status) VALUES (?, ?, ?, 'Scheduled')");
        $stmt->bind_param("sii", $audit_date, $department_id, $custodian_id);
        $stmt->execute();
        $stmt->close();

        $_SESSION['toast_message'] = [
            'message' => 'Audit scheduled successfully!',
            'type' => 'success'
        ];

        header("Location: " . $_SERVER['PHP_SELF']);
        exit;
    }

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

        $stmt = $conn->prepare("UPDATE bcp_sms4_audit SET status = 'Ongoing' WHERE id = ?");
        $stmt->bind_param("i", $audit_id);
        $stmt->execute();
        $stmt->close();

        $stmt2 = $conn->prepare("SELECT department_id, custodian_id FROM bcp_sms4_audit WHERE id = ? LIMIT 1");
        $stmt2->bind_param("i", $audit_id);
        $stmt2->execute();
        $audit = $stmt2->get_result()->fetch_assoc();
        $stmt2->close();

        if ($audit) {
            $_SESSION['current_audit'] = $audit_id;
            $_SESSION['current_department'] = $audit['department_id'];
            $_SESSION['current_custodian'] = $audit['custodian_id'];

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

    $upcoming_audits = $conn->query("SELECT COUNT(*) as total FROM bcp_sms4_audit WHERE audit_date >= CURDATE() AND status != 'Completed'")->fetch_assoc()['total'] ?? 0;
    $last_discrepancies = $conn->query("
    SELECT COUNT(*) as total 
    FROM bcp_sms4_audit_findings 
    WHERE finding_status IN ('Disposed', 'Mismatch', 'Missing')
")->fetch_assoc()['total'] ?? 0;
    $pending_replacements = $conn->query("SELECT COUNT(*) as total FROM bcp_sms4_procurement WHERE status = 'Pending'")->fetch_assoc()['total'] ?? 0;


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
        <div class="toast-container" id="toastContainer"></div>
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

      <!-- REUSABLE CONFIRMATION MODAL -->
        <div class="modal fade" id="confirmModal" tabindex="-1">
          <div class="modal-dialog modal-dialog-centered">
            <div class="modal-content" style="border:none !important; border-radius:10px !important; box-shadow:0 6px 20px rgba(0,0,0,0.25) !important; overflow:hidden !important; padding:0 !important; margin:0 !important;">

              <div class="modal-header" id="confirmModalHeader" 
                  style="background:#198754; color:#fff; border:none; padding:1rem 1.25rem; margin:0;">
                <h5 class="modal-title" id="confirmModalTitle" style="margin:0; font-weight:600;"></h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" style="filter:invert(1);"></button>
              </div>
              
              <div class="modal-body" id="confirmModalBody" 
                  style="padding:1.25rem; font-size:0.95rem; color:#333; margin:0;">
              </div>
              
              <div class="modal-footer" style="border:none; padding:1rem 1.25rem; margin:0;">
                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancel</button>
                <button type="button" class="btn btn-success" id="confirmModalBtn"></button>
              </div>
            </div>
          </div>
        </div>

        <script>
          function showToast(message, type = 'success') {
            const icons = {
              success: '<i class="bi bi-check-circle"></i>',   
              info: '<i class="bi bi-info-circle"></i>',       
              warning: '<i class="bi bi-exclamation-triangle"></i>', 
              danger: '<i class="bi bi-x-circle"></i>'      
            };

          const titles = {
            success: 'Success',
            info: 'Information',
            warning: 'Warning',
            danger: 'Action Completed'
          };

          const toast = document.createElement('div');
          toast.className = `custom-toast toast-${type}`;
          toast.innerHTML = `
            <div class="toast-icon">${icons[type]}</div>
            <div class="toast-content">
              <div class="toast-title">${titles[type]}</div>
              <div class="toast-message">${message}</div>
            </div>
            <button class="toast-close" onclick="this.parentElement.remove()">×</button>
          `;

          document.getElementById('toastContainer').appendChild(toast);

          setTimeout(() => {
            toast.style.transition = 'opacity 0.3s, transform 0.3s';
            toast.style.opacity = '0';
            toast.style.transform = 'translateX(400px)';
            setTimeout(() => toast.remove(), 300);
          }, 4000);
        }

        function showConfirmModal(config) {
          const modal = new bootstrap.Modal(document.getElementById('confirmModal'));
          const header = document.getElementById('confirmModalHeader');
          const title = document.getElementById('confirmModalTitle');
          const body = document.getElementById('confirmModalBody');
          const btn = document.getElementById('confirmModalBtn');
          const closeBtn = header.querySelector('.btn-close');

          header.className = 'modal-header';
          if (config.type === 'danger') {
            header.classList.add('bg-danger', 'text-white');
            closeBtn.classList.add('btn-close-white');
          } else if (config.type === 'warning') {
            header.classList.add('bg-warning', 'text-dark');
            closeBtn.classList.remove('btn-close-white');
          } else if (config.type === 'success') {
            header.classList.add('bg-success', 'text-white');
            closeBtn.classList.add('btn-close-white');
          } else {
            header.classList.add('bg-primary', 'text-white');
            closeBtn.classList.add('btn-close-white');
          }

          title.textContent = config.title || 'Confirm Action';
          body.innerHTML = config.message || 'Are you sure?';

          btn.textContent = config.btnText || 'Confirm';
          btn.className = 'btn btn-' + (config.type || 'primary');

          btn.onclick = function() {
            modal.hide();
            if (config.onConfirm) {
              config.onConfirm();
            }
          };

          modal.show();
        }
        </script>

      <section class="section dashboard">
            <div class="row">
            <div class="col-md-6">
                <div class="card info-card">
                <div class="card-body">
                    <h5 class="card-title">Upcoming Audits</h5>
                    <div class="d-flex align-items-center">
                    <div class="card-icon rounded-circle d-flex align-items-center justify-content-center">
                        <i class="bi bi-calendar-event text-primary"></i>
                    </div>
                    <div class="ps-3">
                        <h6><?php echo $upcoming_audits; ?></h6>
                        <span class="text-primary small">Scheduled</span>
                    </div>
                    </div>
                </div>
                </div>
            </div>
            <div class="col-md-6">
                <div class="card info-card">
                <div class="card-body">
                    <h5 class="card-title">Discrepancies</h5>
                    <div class="d-flex align-items-center">
                    <div class="card-icon rounded-circle d-flex align-items-center justify-content-center">
                        <i class="bi bi-exclamation-triangle text-danger"></i>
                    </div>
                    <div class="ps-3">
                        <h6><?php echo $last_discrepancies; ?></h6>
                        <span class="text-danger small">Issues Found</span>
                    </div>
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
                <button class="btn btn-primary mb-3" data-bs-toggle="modal" data-bs-target="#scheduleAuditModal">Schedule New Audit</button>
                
                <!-- Schedule Audit Modal -->
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
                            <select name="department_id" class="form-control" required>
                                <option value="">-- Select Department --</option>
                                <?php
                                $departments = $conn->query("SELECT id, dept_name FROM bcp_sms4_departments ORDER BY dept_name ASC");
                                while($dept = $departments->fetch_assoc()){
                                    echo "<option value='{$dept['id']}'>{$dept['dept_name']}</option>";
                                }
                                ?>
                            </select>
                          </div>
                          <div class="mb-3">
                            <label>Custodian</label>
                              <select name="custodian_id" class="form-control" required>
                                  <option value="">-- Select Custodian --</option>
                                  <?php
                                  $custodians = $conn->query("SELECT id, fullname FROM bcp_sms4_admins WHERE user_type='custodian' ORDER BY fullname ASC");
                                  while ($cust = $custodians->fetch_assoc()) {
                                      echo "<option value='{$cust['id']}'>{$cust['fullname']}</option>";
                                  }
                                  ?>
                              </select>
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
                        $audits = $conn->query("
                            SELECT a.id, a.audit_date, a.status,
                                  d.dept_name, 
                                  ad.fullname AS custodian_name
                            FROM bcp_sms4_audit a
                            JOIN bcp_sms4_departments d ON a.department_id = d.id
                            JOIN bcp_sms4_admins ad ON a.custodian_id = ad.id
                            ORDER BY a.audit_date DESC
                        ");
                    if ($audits && $audits->num_rows > 0):
                        while ($row = $audits->fetch_assoc()):
                    ?>
                        <tr>
                          <td><?= $row['id'] ?></td>
                          <td><?= $row['audit_date'] ?></td>
                          <td><?= htmlspecialchars($row['dept_name'] ?? 'N/A') ?></td>
                          <td><?= htmlspecialchars($row['custodian_name'] ?? 'N/A') ?></td>
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
                              <form method="POST" id="startAuditForm<?= $row['id'] ?>" style="display:none;">
                                <input type="hidden" name="start_audit_id" value="<?= $row['id'] ?>">
                              </form>
                              <button class="btn btn-sm btn-success" onclick="showConfirmModal({
                                type: 'success',
                                title: 'Confirm Start Audit',
                                message: `
                                  <p>Are you sure you want to start this audit?</p>
                                  <ul class='list-unstyled'>
                                    <li><strong>Audit ID:</strong> <?= $row['id'] ?></li>
                                    <li><strong>Date:</strong> <?= $row['audit_date'] ?></li>
                                    <li><strong>Department:</strong> <?= htmlspecialchars($row['dept_name']) ?></li>
                                    <li><strong>Custodian:</strong> <?= htmlspecialchars($row['custodian_name']) ?></li>
                                  </ul>
                                `,
                                btnText: 'Yes, Start Audit',
                                onConfirm: function() {
                                  document.getElementById('startAuditForm<?= $row['id'] ?>').submit();
                                }
                              })">Start Audit</button>
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

        <!-- Audit Session Tab -->
          <div class="tab-pane fade" id="session" role="tabpanel">
              <h5 class="card-title">Audit Session</h5>

          <?php if (isset($_SESSION['current_audit'])): ?>
              <div class="alert alert-info">
                  <strong>Current Audit:</strong> <?php echo htmlspecialchars($current_audit_label); ?>
              </div>

              <form method="POST" action="" id="endAuditForm">
                  <input type="hidden" name="audit_id" value="<?php echo intval($_SESSION['current_audit']); ?>">
                  <input type="hidden" name="end_audit" value="1">

                  <table class="table datatable">
                      <thead>
                          <tr>
                              <th>Reference No</th>
                              <th>Asset/Consumable ID</th>
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
                          $auditId = intval($_SESSION['current_audit'] ?? 0);
                          $currentDeptId = intval($_SESSION['current_department'] ?? 0);

                          if ($currentDeptId > 0) {
                              $stmt = $conn->prepare("
                                  SELECT 
                                      i.id, 
                                      i.reference_no, 
                                      i.asset_id, 
                                      i.consumable_id, 
                                      it.item_name, 
                                      i.quantity, 
                                      d.dept_name
                                  FROM bcp_sms4_issuance i
                                  LEFT JOIN bcp_sms4_departments d ON i.department_id = d.id
                                  LEFT JOIN bcp_sms4_items it ON i.item_id = it.item_id
                                  WHERE i.department_id = ?
                                  ORDER BY i.id ASC
                              ");

                              if ($stmt) {
                                  $stmt->bind_param("i", $currentDeptId);
                                  $stmt->execute();
                                  $result = $stmt->get_result();
                                  $stmt->close();
                              } else {
                                  die("SQL Error: " . $conn->error);
                              }
                          } else {
                              $result = $conn->query("
                                  SELECT i.id, i.reference_no, i.asset_id, i.consumable_id, it.item_name, i.quantity, d.dept_name
                                  FROM bcp_sms4_issuance i
                                  LEFT JOIN bcp_sms4_departments d ON i.department_id = d.id
                                  LEFT JOIN bcp_sms4_items it ON i.item_id = it.item_id
                                  ORDER BY i.id ASC
                              ");
                          }

                          if ($result && $result->num_rows > 0):
                              while ($row = $result->fetch_assoc()):
                                  $tag = $row['asset_id'] ?: $row['consumable_id'] ?: 'N/A';
                          ?>
                          <tr>
                              <td><?= htmlspecialchars($row['reference_no']); ?></td>
                              <td><?= htmlspecialchars($tag); ?></td>
                              <td><?= htmlspecialchars($row['item_name']); ?></td>
                              <td><?= htmlspecialchars($row['dept_name'] ?: 'N/A'); ?></td>
                              <td><?= htmlspecialchars($row['quantity']); ?></td>
                              <td>
                                  <select name="status[<?= $row['id']; ?>]" class="form-select form-select-sm status-dropdown">
                                      <option value="Valid">Valid</option>
                                      <option value="Disposed">Disposed</option>
                                      <option value="Mismatch">Mismatch</option>
                                      <option value="Missing">Missing</option>
                                  </select>
                              </td>
                              <td>
                                  <select name="asset_condition[<?= $row['id']; ?>]" class="form-select form-select-sm">
                                      <option value="Good">Good</option>
                                      <option value="Fair">Fair</option>
                                      <option value="Damaged">Damaged</option>
                                      <option value="Lost">Lost</option>
                                  </select>
                              </td>
                              <td>
                                  <input type="text" name="remarks[<?= $row['id']; ?>]" class="form-control form-control-sm" placeholder="Enter remarks">
                              </td>
                          </tr>
                          <?php endwhile; else: ?>
                          <tr>
                              <td colspan="8" class="text-center">No assets found for this department</td>
                          </tr>
                          <?php endif; ?>
                      </tbody>
                  </table>

                  <div class="d-flex gap-2">
                      <button type="button" class="btn btn-danger mt-2" onclick="showConfirmModal({
                        type: 'danger',
                        title: 'Confirm End Audit',
                        message: `
                          <div class='alert alert-warning'>
                            <i class='bi bi-exclamation-triangle-fill'></i> 
                            <strong>Warning:</strong> This action cannot be undone!
                          </div>
                          <p>Are you sure you want to end this audit session?</p>
                          <p class='mb-0'><strong>Current Audit:</strong> <?php echo htmlspecialchars($current_audit_label); ?></p>
                        `,
                        btnText: 'Yes, End Audit',
                        onConfirm: function() {
                          document.getElementById('endAuditForm').submit();
                        }
                      })">
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

        <!-- Discrepancies Tab -->
        <div class="tab-pane fade" id="discrepancies" role="tabpanel">
          <h5 class="card-title">Discrepancy List</h5>
          
          <?php if (isset($_SESSION['current_audit'])): ?>
            <div class="alert alert-info">
              <strong>Showing discrepancies for:</strong> <?php echo htmlspecialchars($current_audit_label); ?>
            </div>
          <?php endif; ?>
          
          <table class="table datatable">
            <thead>
              <tr>
                <th>Finding ID</th>
                <th>Asset ID</th>
                <th>Asset Name</th>
                <th>Quantity</th>
                <th>Issue</th>
                <th>Condition</th>
                <th>Remarks</th>
                <th>Recorded On</th>
                <th>Action</th>
              </tr>
            </thead>    
            <tbody>
              <?php
              if (isset($_SESSION['current_audit'])) {
                  $current_audit_id = intval($_SESSION['current_audit']);

                  $historyQuery = $conn->query("
                      SELECT id FROM bcp_sms4_audit_history 
                      WHERE audit_id = {$current_audit_id} 
                      ORDER BY id DESC LIMIT 1
                  ");
                  
                  if ($historyQuery && $historyQuery->num_rows > 0) {
                      $historyRow = $historyQuery->fetch_assoc();
                      $history_id = $historyRow['id'];
                      
                      $findings = $conn->query("
                          SELECT * FROM bcp_sms4_audit_findings 
                          WHERE history_id = {$history_id}
                          AND finding_status IN ('Disposed', 'Mismatch', 'Missing')
                          ORDER BY recorded_on DESC
                      ");
                  } else {
                      $findings = null;
                  }
              } else {
                  $findings = $conn->query("
                      SELECT * FROM bcp_sms4_audit_findings 
                      WHERE finding_status IN ('Disposed', 'Mismatch', 'Missing')
                      ORDER BY recorded_on DESC
                  ");
              }
              
              if ($findings && $findings->num_rows > 0):
                while ($row = $findings->fetch_assoc()):
              ?>
              <tr>
                <td><?= htmlspecialchars($row['id']) ?></td>
                <td><?= htmlspecialchars($row['asset_id']) ?></td>
                <td><?= htmlspecialchars($row['asset_name']) ?></td>
                <td><?= htmlspecialchars($row['quantity']) ?></td>
                <td>
                    <span class="badge bg-<?= 
                        $row['finding_status'] == 'Missing' ? 'danger' : 
                        ($row['finding_status'] == 'Mismatch' ? 'warning' : 'secondary') 
                    ?>">
                        <?= htmlspecialchars($row['finding_status']) ?>
                    </span>
                </td>
                <td><?= htmlspecialchars($row['asset_condition']) ?></td>
                <td><?= htmlspecialchars($row['remarks']) ?></td>
                <td><?= date('M d, Y H:i', strtotime($row['recorded_on'])) ?></td>
                <td>
                    <button class="btn btn-warning btn-sm" 
                            onclick="generateDiscrepancyReport(
                              '<?= $row['id'] ?>',
                              '<?= htmlspecialchars($row['asset_id']) ?>',
                              '<?= htmlspecialchars($row['asset_name']) ?>',
                              '<?= htmlspecialchars($row['finding_status']) ?>',
                              '<?= htmlspecialchars($row['asset_condition']) ?>',
                              '<?= htmlspecialchars($row['remarks']) ?>',
                              '<?= date('M d, Y H:i', strtotime($row['recorded_on'])) ?>'
                            )">
                        Generate Report
                    </button>
                </td>
              </tr>
              <?php endwhile; else: ?>
              <tr>
                <td colspan="9" class="text-center">
                  <?php if (isset($_SESSION['current_audit'])): ?>
                    No discrepancies found for the current audit session
                  <?php else: ?>
                    No discrepancies found
                  <?php endif; ?>
                </td>
              </tr>
              <?php endif; ?> 
            </tbody>
          </table>
        </div>
        
    <script>
    function generateDiscrepancyReport(id, assetId, assetName, status, condition, remarks, recorded) {
        let html = `
          <div style="width: 100%; max-width: 800px; margin: auto; font-family: Arial, sans-serif; color: #000;">
            
          <!-- Header -->
          <div style="display: flex; justify-content: space-between; align-items: center; border-bottom: 2px solid #000; padding-bottom: 10px; margin-bottom: 20px;">
            
            <!-- Left/Text Section -->
            <div style="text-align: center; flex: 1;">
              <h1 style="margin: 0; font-size: 20pt;">AUDIT DISCREPANCY REPORT</h1>
              <p style="margin: 5px 0;">Property Custodian Management System</p>
              <p style="margin: 0; font-size: 10pt;">Report No: ADR-${String(id).padStart(4, '0')}</p>
              <p style="margin: 0; font-size: 10pt;">Generated by: <?= htmlspecialchars($_SESSION['username'] ?? 'Admin') ?></p>
              <p style="margin: 0; font-size: 10pt;">Generated on: ${new Date().toLocaleDateString()} ${new Date().toLocaleTimeString()}</p>
            </div>
            
            <!-- Right/Logo Section -->
            <div>
              <img src="../../assets/img/bagong_silang_logo.png" style="height:80px; width:auto;">
            </div>
          </div>

            <!-- Discrepancy Details -->
            <h2 style="font-size: 14pt; border-bottom: 1px solid #000; padding-bottom: 5px;">Discrepancy Details</h2>
            <table style="width: 100%; border-collapse: collapse; margin-bottom: 20px;">
              <tr>
                <td style="font-weight: bold; width: 30%; padding: 8px; border: 1px solid #000; background-color: #f2f2f2;">Finding ID</td>
                <td style="padding: 8px; border: 1px solid #000;">${id}</td>
              </tr>
              <tr>
                <td style="font-weight: bold; padding: 8px; border: 1px solid #000; background-color: #f2f2f2;">Asset ID</td>
                <td style="padding: 8px; border: 1px solid #000;">${assetId}</td>
              </tr>
              <tr>
                <td style="font-weight: bold; padding: 8px; border: 1px solid #000; background-color: #f2f2f2;">Asset Name</td>
                <td style="padding: 8px; border: 1px solid #000;">${assetName}</td>
              </tr>
              <tr>
                <td style="font-weight: bold; padding: 8px; border: 1px solid #000; background-color: #f2f2f2;">Finding Status</td>
                <td style="padding: 8px; border: 1px solid #000;">${status}</td>
              </tr>
              <tr>
                <td style="font-weight: bold; padding: 8px; border: 1px solid #000; background-color: #f2f2f2;">Asset Condition</td>
                <td style="padding: 8px; border: 1px solid #000;">${condition}</td>
              </tr>
              <tr>
                <td style="font-weight: bold; padding: 8px; border: 1px solid #000; background-color: #f2f2f2;">Date Recorded</td>
                <td style="padding: 8px; border: 1px solid #000;">${recorded}</td>
              </tr>
            </table>

            <!-- Problem Description -->
            <h2 style="font-size: 14pt; border-bottom: 1px solid #000; padding-bottom: 5px;">Remarks & Details</h2>
            <div style="border: 1px solid #000; padding: 15px; background-color: #fafafa; min-height: 100px; margin-bottom: 20px;">
              ${remarks || 'No remarks provided'}
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
                    $history = $conn->query("
                        SELECT ah.audit_id, ah.started_date, ah.completed_date, ah.status, 
                              d.dept_name 
                        FROM bcp_sms4_audit_history ah
                        LEFT JOIN bcp_sms4_departments d ON ah.department_id = d.id
                        ORDER BY ah.completed_date DESC
                    ");
                    if ($history && $history->num_rows > 0):
                      while ($row = $history->fetch_assoc()):
                    ?>
                    <tr>
                      <td><?= htmlspecialchars($row['audit_id']) ?></td>
                      <td><?= htmlspecialchars($row['dept_name']) ?></td>
                      <td><?= date('M d, Y H:i', strtotime($row['started_date'])) ?></td>
                      <td><?= date('M d, Y H:i', strtotime($row['completed_date'])) ?></td>
                      <td>
                        <span class="badge bg-success"><?= htmlspecialchars($row['status']) ?></span>
                      </td>
                      <td>
                        <button class="btn btn-primary btn-sm"
                          onclick="generateAuditReport(
                            '<?= htmlspecialchars($row['audit_id']) ?>',
                            '<?= htmlspecialchars($row['dept_name']) ?>',
                            '<?= date('M d, Y H:i', strtotime($row['started_date'])) ?>',
                            '<?= date('M d, Y H:i', strtotime($row['completed_date'])) ?>',
                            '<?= htmlspecialchars($row['status']) ?>'
                          )">
                          Export PDF
                        </button>
                      </td>
                    </tr>
                    <?php endwhile; else: ?>
                      <tr><td colspan="6">No audit history available</td></tr>
                    <?php endif; ?>
                  </tbody>
                </table>
              </div>

              <script>
              function generateAuditReport(auditId, department, started, completed, status) {
                let html = `
                  <html>
                    <head>
                      <title>Audit Report</title>
                      <style>
                        @page { size: A4; margin: 20mm; }
                        body { font-family: Arial, sans-serif; color: #000; margin: 0; }
                        .container { max-width: 800px; margin: auto; }
                        h1, h2 { margin: 0 0 10px 0; }
                        table { width: 100%; border-collapse: collapse; margin-bottom: 20px; }
                        td { padding: 8px; border: 1px solid #000; vertical-align: top; }
                        td.header { background-color: #f2f2f2; font-weight: bold; width: 30%; }
                        .section { margin-bottom: 20px; }
                        .footer { text-align: center; font-size: 10pt; margin-top: 30px; }
                      </style>
                    </head>
                    <body>
                      <div class="container">
                        <!-- Header -->
                        <div style="display: flex; justify-content: space-between; align-items: center; border-bottom: 2px solid #000; padding-bottom: 10px; margin-bottom: 20px;">
                          <div style="text-align: left;">
                            <h1 style="margin:0;">AUDIT REPORT</h1>
                            <p style="margin:0;">Property Custodian Management System</p>
                            <p style="margin:0;">Audit Reference: AR-${String(auditId).padStart(4, '0')}</p>
                            <p style="margin:0;">Generated by: <?= htmlspecialchars($_SESSION['username'] ?? 'Admin') ?></p>
                            <p style="margin:0;">Generated on: ${new Date().toLocaleDateString()} ${new Date().toLocaleTimeString()}</p>
                          </div>
                          <div>
                            <img src="../../assets/img/bagong_silang_logo.png" style="height:80px; width:auto;">
                          </div>
                        </div>

                        <!-- Audit Details -->
                        <div class="section">
                          <h2>Audit Details</h2>
                          <table>
                            <tr><td class="header">Audit ID</td><td>${auditId}</td></tr>
                            <tr><td class="header">Department</td><td>${department}</td></tr>
                            <tr><td class="header">Started Date</td><td>${started}</td></tr>
                            <tr><td class="header">Completed Date</td><td>${completed}</td></tr>
                            <tr><td class="header">Status</td><td>${status}</td></tr>
                          </table>
                        </div>

                        <!-- Footer -->
                        <div class="footer">
                          This is an official audit record generated by the Property Custodian Management System.
                        </div>
                      </div>
                    </body>
                  </html>
                `;

                let printWindow = window.open('', '', 'width=900,height=700');
                printWindow.document.write(html);
                printWindow.document.close();
                printWindow.focus();
                setTimeout(() => { printWindow.print(); printWindow.close(); }, 250);
              }
              </script>

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