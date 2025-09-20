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
if ($_SERVER['REQUEST_METHOD'] === 'POST') {

    if (isset($_POST['schedule_audit'])) {
        $audit_date = $_POST['audit_date'];
        $department = $_POST['department'];
        $custodian = $_POST['custodian'];

        $stmt = $conn->prepare("INSERT INTO bcp_sms4_audit (audit_date, department, custodian, status) VALUES (?, ?, ?, 'Scheduled')");
        $stmt->bind_param("sss", $audit_date, $department, $custodian);
        $stmt->execute();
        $stmt->close();
    }

    if (isset($_POST['start_audit_id'])) {
        $aid = intval($_POST['start_audit_id']);
        $stmt = $conn->prepare("UPDATE bcp_sms4_audit SET status = 'Ongoing' WHERE id = ?");
        $stmt->bind_param("i", $aid);
        $stmt->execute();
        $stmt->close();
        $res = $conn->query("SELECT department FROM bcp_sms4_audit WHERE id = {$aid} LIMIT 1");
        $dep = $res && $res->num_rows > 0 ? $res->fetch_assoc()['department'] : null;

        $_SESSION['current_audit'] = $aid;
        $_SESSION['current_department'] = $dep;
        header("Location: " . $_SERVER['PHP_SELF'] . "#session");
        exit;
    }

    if (isset($_POST['save_progress'])) {
        $audit_id = isset($_SESSION['current_audit']) ? intval($_SESSION['current_audit']) : 0;
        if (!$audit_id) {
            echo "<div class='alert alert-warning'>Please start an audit first (Upcoming Audits → Start).</div>";
        } else {
            $ar = $conn->query("SELECT audit_date FROM bcp_sms4_audit WHERE id = {$audit_id}")->fetch_assoc();
            $audit_date = $ar ? $ar['audit_date'] : date('Y-m-d');

            if (isset($_POST['found']) || isset($_POST['condition']) || isset($_POST['remarks'])) {
                $foundArr = isset($_POST['found']) ? $_POST['found'] : [];
                $condArr = isset($_POST['condition']) ? $_POST['condition'] : [];
                $remArr = isset($_POST['remarks']) ? $_POST['remarks'] : [];

                $insAsset = $conn->prepare("SELECT audit_id FROM bcp_sms4_asset_audit WHERE asset_id = ? AND audit_date = ?");
                $insertAsset = $conn->prepare("INSERT INTO bcp_sms4_asset_audit (asset_id, audit_date, auditor, findings, result) VALUES (?, ?, ?, ?, ?)");
                $updateAsset = $conn->prepare("UPDATE bcp_sms4_asset_audit SET auditor = ?, findings = ?, result = ? WHERE asset_id = ? AND audit_date = ?");
                foreach ($condArr as $asset_id => $condition) {
                    $asset_id = intval($asset_id);
                    $found = isset($foundArr[$asset_id]) ? 1 : 0;
                    $remarks = isset($remArr[$asset_id]) ? trim($remArr[$asset_id]) : '';

                    $resultVal = (strtolower($condition) === 'good') ? 'Match' : 'Mismatch';
                    $findingsText = ($found ? "Found" : "Not Found") . ($remarks ? " - {$remarks}" : " -");

                    $insAsset->bind_param("is", $asset_id, $audit_date);
                    $insAsset->execute();
                    $insAsset->store_result();
                    if ($insAsset->num_rows > 0) {
  
                        $updateAsset->bind_param("sssis", $auditorName, $findingsText, $resultVal, $asset_id, $audit_date);
                        $updateAsset->execute();
                    } else {
                        $insertAsset->bind_param("issss", $asset_id, $audit_date, $auditorName, $findingsText, $resultVal);
                        $insertAsset->execute();
                    }
                }
                $insAsset->close(); $insertAsset->close(); $updateAsset->close();
            }

            if (isset($_POST['found_c']) || isset($_POST['condition_c']) || isset($_POST['remarks_c'])) {
                $foundArr = isset($_POST['found_c']) ? $_POST['found_c'] : [];
                $condArr = isset($_POST['condition_c']) ? $_POST['condition_c'] : [];
                $remArr = isset($_POST['remarks_c']) ? $_POST['remarks_c'] : [];

                $insCons = $conn->prepare("SELECT audit_id FROM bcp_sms4_consumable_audit WHERE consumable_id = ? AND audit_date = ?");
                $insertCons = $conn->prepare("INSERT INTO bcp_sms4_consumable_audit (consumable_id, audit_date, auditor, findings, result) VALUES (?, ?, ?, ?, ?)");
                $updateCons = $conn->prepare("UPDATE bcp_sms4_consumable_audit SET auditor = ?, findings = ?, result = ? WHERE consumable_id = ? AND audit_date = ?");
                foreach ($condArr as $cons_id => $condition) {
                    $cons_id = intval($cons_id);
                    $found = isset($foundArr[$cons_id]) ? 1 : 0;
                    $remarks = isset($remArr[$cons_id]) ? trim($remArr[$cons_id]) : '';
                    $resultVal = (strtolower($condition) === 'ok') ? 'OK' : (in_array($condition, ['Low Stock','Expired','Missing']) ? $condition : 'Pending');
                    $findingsText = ($found ? "Found" : "Not Found") . ($remarks ? " - {$remarks}" : " -");
                    $insCons->bind_param("is", $cons_id, $audit_date);
                    $insCons->execute();
                    $insCons->store_result();
                    if ($insCons->num_rows > 0) {
                        $updateCons->bind_param("sssis", $auditorName, $findingsText, $resultVal, $cons_id, $audit_date);
                        $updateCons->execute();
                    } else {
                        $insertCons->bind_param("issss", $cons_id, $audit_date, $auditorName, $findingsText, $resultVal);
                        $insertCons->execute();
                    }
                }
                $insCons->close(); $insertCons->close(); $updateCons->close();
            }

            echo "<div class='alert alert-success'>Progress saved for audit #{$audit_id}.</div>";
        }
    }

    if (isset($_POST['submit_audit'])) {
        $audit_id = isset($_SESSION['current_audit']) ? intval($_SESSION['current_audit']) : 0;
        if (!$audit_id) {
            echo "<div class='alert alert-warning'>Please start an audit first (Upcoming Audits → Start).</div>";
        } else {
            $ar = $conn->query("SELECT audit_date FROM bcp_sms4_audit WHERE id = {$audit_id}")->fetch_assoc();
            $audit_date = $ar ? $ar['audit_date'] : date('Y-m-d');

            if (isset($_POST['condition'])) {
                $foundArr = isset($_POST['found']) ? $_POST['found'] : [];
                $condArr = $_POST['condition'];
                $remArr = isset($_POST['remarks']) ? $_POST['remarks'] : [];

                $selectAsset = $conn->prepare("SELECT audit_id FROM bcp_sms4_asset_audit WHERE asset_id = ? AND audit_date = ?");
                $insertAsset = $conn->prepare("INSERT INTO bcp_sms4_asset_audit (asset_id, audit_date, auditor, findings, result) VALUES (?, ?, ?, ?, ?)");
                $updateAsset = $conn->prepare("UPDATE bcp_sms4_asset_audit SET auditor = ?, findings = ?, result = ? WHERE asset_id = ? AND audit_date = ?");
                foreach ($condArr as $asset_id => $condition) {
                    $asset_id = intval($asset_id);
                    $found = isset($foundArr[$asset_id]) ? 1 : 0;
                    $remarks = isset($remArr[$asset_id]) ? trim($remArr[$asset_id]) : '';
                    $resultVal = (strtolower($condition) === 'good') ? 'Match' : 'Mismatch';
                    $findingsText = ($found ? "Found" : "Not Found") . ($remarks ? " - {$remarks}" : " -");

                    $selectAsset->bind_param("is", $asset_id, $audit_date);
                    $selectAsset->execute();
                    $selectAsset->store_result();
                    if ($selectAsset->num_rows > 0) {
                        $updateAsset->bind_param("sssis", $auditorName, $findingsText, $resultVal, $asset_id, $audit_date);
                        $updateAsset->execute();
                    } else {
                        $insertAsset->bind_param("issss", $asset_id, $audit_date, $auditorName, $findingsText, $resultVal);
                        $insertAsset->execute();
                    }

                    if ($resultVal !== 'Match') {
                        $assetRow = $conn->query("SELECT asset_tag FROM bcp_sms4_asset WHERE id = {$asset_id}")->fetch_assoc();
                        $asset_tag = $assetRow ? $assetRow['asset_tag'] : ("ID-{$asset_id}");
                        $issue = ucfirst($condition) . ($remarks ? " - {$remarks}" : '');

                        $exists = $conn->query("SELECT id FROM bcp_sms4_audit_discrepancies WHERE asset_tag = '" . $conn->real_escape_string($asset_tag) . "' AND issue = '" . $conn->real_escape_string($issue) . "' AND resolved = 0");
                        if (!$exists || $exists->num_rows == 0) {
                            $conn->query("INSERT INTO bcp_sms4_discrepancies (asset_tag, issue, resolved, created_at) VALUES ('" . $conn->real_escape_string($asset_tag) . "', '" . $conn->real_escape_string($issue) . "', 0, NOW())");
                        }
                    }
                }
                $selectAsset->close(); $insertAsset->close(); $updateAsset->close();
            }

            if (isset($_POST['condition_c'])) {
                $foundArr = isset($_POST['found_c']) ? $_POST['found_c'] : [];
                $condArr = $_POST['condition_c'];
                $remArr = isset($_POST['remarks_c']) ? $_POST['remarks_c'] : [];

                $selectCons = $conn->prepare("SELECT audit_id FROM bcp_sms4_consumable_audit WHERE consumable_id = ? AND audit_date = ?");
                $insertCons = $conn->prepare("INSERT INTO bcp_sms4_consumable_audit (consumable_id, audit_date, auditor, findings, result) VALUES (?, ?, ?, ?, ?)");
                $updateCons = $conn->prepare("UPDATE bcp_sms4_consumable_audit SET auditor = ?, findings = ?, result = ? WHERE consumable_id = ? AND audit_date = ?");
                foreach ($condArr as $cons_id => $condition) {
                    $cons_id = intval($cons_id);
                    $found = isset($foundArr[$cons_id]) ? 1 : 0;
                    $remarks = isset($remArr[$cons_id]) ? trim($remArr[$cons_id]) : '';
                    $resultVal = (strtolower($condition) === 'ok') ? 'OK' : (in_array($condition, ['Low Stock','Expired','Missing']) ? $condition : 'Pending');
                    $findingsText = ($found ? "Found" : "Not Found") . ($remarks ? " - {$remarks}" : " -");

                    $selectCons->bind_param("is", $cons_id, $audit_date);
                    $selectCons->execute();
                    $selectCons->store_result();
                    if ($selectCons->num_rows > 0) {
                        $updateCons->bind_param("sssis", $auditorName, $findingsText, $resultVal, $cons_id, $audit_date);
                        $updateCons->execute();
                    } else {
                        $insertCons->bind_param("issss", $cons_id, $audit_date, $auditorName, $findingsText, $resultVal);
                        $insertCons->execute();
                    }

                    if ($resultVal !== 'OK') {
                        $consRow = $conn->query("SELECT asset_tag FROM bcp_sms4_consumable WHERE id = {$cons_id}")->fetch_assoc();
                        $asset_tag = $consRow ? $consRow['asset_tag'] : ("C-{$cons_id}");
                        $issue = ucfirst($condition) . ($remarks ? " - {$remarks}" : '');
                        $exists = $conn->query("SELECT id FROM bcp_sms4_audit_discrepancies WHERE asset_tag = '" . $conn->real_escape_string($asset_tag) . "' AND issue = '" . $conn->real_escape_string($issue) . "' AND resolved = 0");
                        if (!$exists || $exists->num_rows == 0) {
                            $conn->query("INSERT INTO bcp_sms4_audit_discrepancies (asset_tag, issue, resolved, created_at) VALUES ('" . $conn->real_escape_string($asset_tag) . "', '" . $conn->real_escape_string($issue) . "', 0, NOW())");
                        }
                    }
                }
                $selectCons->close(); $insertCons->close(); $updateCons->close();
            }

            $stmt = $conn->prepare("UPDATE bcp_sms4_audit SET status = 'Completed' WHERE id = ?");
            $stmt->bind_param("i", $audit_id);
            $stmt->execute();
            $stmt->close();

            unset($_SESSION['current_audit']);

            echo "<div class='alert alert-success'>Audit #{$audit_id} submitted and completed.</div>";
        }
    }
}

            $upcoming_audits = 0;
            $result = $conn->query("SELECT COUNT(*) as total FROM bcp_sms4_audit WHERE audit_date >= CURDATE()");
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
                $r = $conn->query("SELECT id, audit_date, department, custodian FROM bcp_sms4_audit WHERE id = {$aid}")->fetch_assoc();
                if ($r) {
                    $current_audit_label = "Audit #{$r['id']} — {$r['audit_date']} ({$r['department']} / {$r['custodian']})";
                } else {
                    unset($_SESSION['current_audit']);
                }
            }
            ?>

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
                            <input type="text" name="department" class="form-control" required>
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
                          <td><?= htmlspecialchars($row['department']) ?></td>
                          <td><?= htmlspecialchars($row['custodian']) ?></td>
                          <td><?= $row['status'] ?></td>
                          <td>
                            <?php if ($row['status'] !== 'Ongoing' && $row['status'] !== 'Completed'): ?>
                              <form method="POST" style="display:inline">
                                <input type="hidden" name="start_audit_id" value="<?= $row['id'] ?>">
                                <button class="btn btn-sm btn-success" type="submit">Start Audit</button>
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

            <form method="POST" action="">
                <input type="hidden" name="audit_id" value="<?php echo isset($_SESSION['current_audit']) ? intval($_SESSION['current_audit']) : 0; ?>">

                <table class="table datatable">
                <thead>
                    <tr>
                    <th>Reference No</th>
                    <th>Asset Tag</th>
                    <th>Description</th>
                    <th>Category (Dept)</th>
                    <th>Quantity</th>
                    <th>Custodian</th>
                    <th>Status</th>
                    <th>Condition</th>
                    <th>Remarks</th>
                    </tr>
                </thead>
                <tbody>
                    <?php
                    $currentDept = isset($_SESSION['current_department']) ? $conn->real_escape_string($_SESSION['current_department']) : '';

                    if ($currentDept) {
                        $assets = $conn->query("
                            SELECT id, reference_no, equipment_id, equipment_name, quantity, custodian_name, department_code
                            FROM bcp_sms4_assign_history
                            WHERE department_code = '{$currentDept}'
                            ORDER BY id ASC
                        ");
                    } else {
                        $assets = $conn->query("SELECT id, reference_no, equipment_id, equipment_name, quantity, custodian_name, department_code FROM bcp_sms4_assign_history ORDER BY id ASC");
                    }

                    if ($assets && $assets->num_rows > 0):
                    while ($row = $assets->fetch_assoc()):
                    ?>
                    <tr>
                    <td><?php echo htmlspecialchars($row['reference_no']); ?></td>
                    <td><?php echo htmlspecialchars($row['equipment_id']); ?></td>
                    <td><?php echo htmlspecialchars($row['equipment_name']); ?></td>
                    <td><?php echo htmlspecialchars($row['department_code']); ?></td>
                    <td><?php echo htmlspecialchars($row['quantity']); ?></td>
                    <td><?php echo htmlspecialchars($row['custodian_name']); ?></td>
                    <td>
                        <select name="status[<?php echo $row['id']; ?>]">
                        <option value="Valid">Valid</option>
                        <option value="Disposed">Disposed</option>
                        <option value="Mismatch">Mismatch</option>
                        </select>
                    </td>
                    <td>
                        <select name="condition[<?php echo $row['id']; ?>]">
                        <option value="Good">Good</option>
                        <option value="Fair">Fair</option>
                        <option value="Damaged">Damaged</option>
                        <option value="Lost">Lost</option>
                        </select>
                    </td>
                    <td>
                        <input type="text" name="remarks[<?php echo $row['id']; ?>]" placeholder="Remarks">
                    </td>
                    </tr>
                    <?php endwhile; endif; ?>
                </tbody>
                </table>

                <div class="d-flex gap-2">
                <button type="submit" name="save_progress" class="btn btn-success mt-2">Save Progress</button>
                <button type="submit" name="submit_audit" class="btn btn-primary mt-2">Submit Audit</button>
                </div>
            </form>
            </div>


              <!-- Discrepancies -->
              <div class="tab-pane fade" id="discrepancies" role="tabpanel">
                <h5 class="card-title">Discrepancy List</h5>
                <table class="table datatable">
                  <thead>
                    <tr>
                      <th>Asset Tag</th>
                      <th>Issue</th>
                      <th>Action</th>
                    </tr>
                  </thead>
                  <tbody>
                    <?php
                    $discrepancies = $conn->query("SELECT * FROM bcp_sms4_discrepancies WHERE resolved = 0");
                    if ($discrepancies && $discrepancies->num_rows > 0):
                      while ($row = $discrepancies->fetch_assoc()):
                    ?>
                    <tr>
                      <td><?= htmlspecialchars($row['asset_tag']) ?></td>
                      <td><?= htmlspecialchars($row['issue']) ?></td>
                      <td>
                        <button class="btn btn-warning btn-sm">File Report</button>
                        <button class="btn btn-info btn-sm">Request Replacement</button>
                        <button class="btn btn-danger btn-sm">Mark Disposal</button>
                      </td>
                    </tr>
                    <?php endwhile; else: ?>
                      <tr><td colspan="3">No discrepancies found</td></tr>
                    <?php endif; ?>
                  </tbody>
                </table>
              </div>

              <!-- Reports -->
              <div class="tab-pane fade" id="reports" role="tabpanel">
                <h5 class="card-title">Audit Reports</h5>
                <table class="table datatable">
                  <thead>
                    <tr>
                      <th>Report ID</th>
                      <th>Date</th>
                      <th>Department</th>
                      <th>Auditor</th>
                      <th>Summary</th>
                      <th>Export</th>
                    </tr>
                  </thead>
                  <tbody>
                    <?php
                    $reports = $conn->query("SELECT * FROM bcp_sms4_audit_reports ORDER BY date DESC");
                    if ($reports && $reports->num_rows > 0):
                      while ($row = $reports->fetch_assoc()):
                    ?>
                    <tr>
                      <td><?= htmlspecialchars($row['report_id']) ?></td>
                      <td><?= htmlspecialchars($row['date']) ?></td>
                      <td><?= htmlspecialchars($row['department']) ?></td>
                      <td><?= htmlspecialchars($row['auditor']) ?></td>
                      <td><?= htmlspecialchars($row['summary']) ?></td>
                      <td>
                        <button class="btn btn-secondary btn-sm">PDF</button>
                        <button class="btn btn-secondary btn-sm">Excel</button>
                      </td>
                    </tr>
                    <?php endwhile; else: ?>
                      <tr><td colspan="6">No reports available</td></tr>
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
