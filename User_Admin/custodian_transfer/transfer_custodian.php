<?php
session_start();
include "../../connection.php";

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $issuance_id = intval($_POST['issuance_id']);
    $to_teacher_id = intval($_POST['to_teacher_id']);
    $remarks = trim($_POST['remarks'] ?? '');
    $admin_id = intval($_SESSION['user_id']); // logged in admin

    // 1. Get current custodian
    $stmt = $conn->prepare("SELECT teacher_id FROM bcp_sms4_issuance WHERE id = ?");
    $stmt->bind_param("i", $issuance_id);
    $stmt->execute();
    $stmt->bind_result($from_teacher_id);
    $stmt->fetch();
    $stmt->close();

    if (!$from_teacher_id) {
        die("Invalid issuance ID");
    }

    // 2. Update issuance with new custodian
    $stmt = $conn->prepare("UPDATE bcp_sms4_issuance SET teacher_id = ? WHERE id = ?");
    $stmt->bind_param("ii", $to_teacher_id, $issuance_id);
    $stmt->execute();
    $stmt->close();

    // 3. Insert into transfer history
    $stmt = $conn->prepare("
        INSERT INTO bcp_sms4_custodian_transfers 
            (issuance_id, from_teacher_id, to_teacher_id, transferred_by, remarks) 
        VALUES (?, ?, ?, ?, ?)
    ");
    $stmt->bind_param("iiiis", $issuance_id, $from_teacher_id, $to_teacher_id, $admin_id, $remarks);
    $stmt->execute();
    $stmt->close();

    header("Location: issuance_list.php?transfer=success");
    exit;
}
?>
<!DOCTYPE html>
<html lang="en">

<head>
  <meta charset="utf-8">
  <meta content="width=device-width, initial-scale=1.0" name="viewport">
  <title>Transfer Custodian</title>
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
  <link rel="stylesheet" href="../../css/table_size.css">
  <link rel="stylesheet" href="../../asset/css/assign_modal.css">
  <link rel="stylesheet" href="../../css/asset_reg/list_assets.css">
  <script src="../../assets/js/assign_trans/history/history.js"></script>
  <script src="../../assets/js/assign_trans/consumable/active.js"></script>

<body>
  <?php
    include '../../components/nav-bar.php';
  ?>

  <main id="main" class="main">
    
    <div class="pagetitle">
      <h1>Transfer Custodian</h1>
      <nav>
        <ol class="breadcrumb">
          <li class="breadcrumb-item"><a href="<?=BASE_URL?>User_Admin/dashboard.php">Home</a></li>
          <li class="breadcrumb-item">Transfer Custodian</li>
        </ol>
      </nav>
    </div>
    
    <section>
    <button class="btn btn-warning btn-sm" 
        data-bs-toggle="modal" 
        data-bs-target="#transferModal" 
        data-issuance="<?= $row['id'] ?>">
  Transfer
</button>
<div class="modal fade" id="transferModal" tabindex="-1">
  <div class="modal-dialog">
    <form action="transfer_custodian.php" method="POST">
      <div class="modal-content">
        <div class="modal-header">
          <h5 class="modal-title">Transfer Custodian</h5>
          <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
        </div>
        <div class="modal-body">
          <input type="hidden" name="issuance_id" id="issuance_id">
          <div class="mb-3">
            <label for="to_teacher_id" class="form-label">New Custodian</label>
            <select name="to_teacher_id" class="form-select" required>
              <?php
              $teachers = $conn->query("SELECT id, fullname FROM bcp_sms4_admins WHERE role = 'teacher'");
              while ($t = $teachers->fetch_assoc()) {
                  echo "<option value='{$t['id']}'>{$t['fullname']}</option>";
              }
              ?>
            </select>
          </div>
          <div class="mb-3">
            <label for="remarks" class="form-label">Remarks</label>
            <textarea name="remarks" class="form-control"></textarea>
          </div>
        </div>
        <div class="modal-footer">
          <button type="submit" class="btn btn-primary">Confirm Transfer</button>
        </div>
      </div>
    </form>
  </div>
</div>
    </section>
    </main>

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
<script>
    var transferModal = document.getElementById('transferModal');
transferModal.addEventListener('show.bs.modal', function (event) {
  var button = event.relatedTarget;
  var issuanceId = button.getAttribute('data-issuance');
  transferModal.querySelector('#issuance_id').value = issuanceId;
});
</script>