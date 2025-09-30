<?php
session_start();
include '../../connection.php';

$teacher_id = $_SESSION['user_id'];

$assets = $conn->query("
    SELECT a.asset_id, a.property_tag, i.item_name
    FROM bcp_sms4_asset a
    JOIN bcp_sms4_items i ON a.item_id = i.item_id
    WHERE a.status = 'In-Storage'
");

$consumables = $conn->query("
    SELECT c.id, i.item_name, c.quantity
    FROM bcp_sms4_consumable c
    JOIN bcp_sms4_items i ON c.item_id = i.item_id
    WHERE c.quantity > 0
");

$departments = $conn->query("SELECT id, dept_name FROM bcp_sms4_departments ORDER BY dept_name");
?>

<!DOCTYPE html>
<html lang="en">

<head>
  <meta charset="utf-8">
  <meta content="width=device-width, initial-scale=1.0" name="viewport">
  <title>Teacher Request</title>
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
</head>

<body>
  <?php
    include  "../../components/nav-bar.php";
  ?>
  <main id="main" class="main">
    <div class="pagetitle">
      <h1>Teacher Requests</h1>
      <nav>
        <ol class="breadcrumb">
          <li class="breadcrumb-item"><a href="<?=BASE_URL?>User_Teachers/dashboard_teacher.php">Home</a></li>
          <li class="breadcrumb-item active">Teacher Requests</li>
        </ol>
      </nav>
    </div>
    <section>
        <h2>Request Property</h2>
    <form method="POST" action="submit_request.php">

      <div class="mb-3">
        <label class="form-label">Request Type</label>
        <select name="request_type" id="request_type" class="form-select" required>
          <option value="">-- Select Type --</option>
          <option value="Asset">Asset</option>
          <option value="Consumable">Consumable</option>
        </select>
      </div>

      <div class="mb-3" id="asset_field" style="display:none;">
        <label class="form-label">Select Asset</label>
        <select name="asset_id" class="form-select">
          <option value="">-- Select Asset --</option>
          <?php while($a = $assets->fetch_assoc()): ?>
            <option value="<?= $a['asset_id'] ?>">
              <?= $a['property_tag'] ?> - <?= $a['item_name'] ?>
            </option>
          <?php endwhile; ?>
        </select>
      </div>

      <div class="mb-3" id="consumable_field" style="display:none;">
        <label class="form-label">Select Consumable</label>
        <select name="consumable_id" class="form-select">
          <option value="">-- Select Consumable --</option>
          <?php while($c = $consumables->fetch_assoc()): ?>
            <option value="<?= $c['id'] ?>">
              <?= $c['item_name'] ?> (Stock: <?= $c['quantity'] ?>)
            </option>
          <?php endwhile; ?>
        </select>

        <label class="form-label mt-2">Quantity</label>
        <input type="number" name="quantity" class="form-control" min="1" value="1">
      </div>
      <div class="mb-3">
      <label class="form-label">Select Department</label>
      <select name="department_id" class="form-select" required>
        <option value="">-- Select Department --</option>
        <?php while($d = $departments->fetch_assoc()): ?>
          <option value="<?= $d['id'] ?>"><?= $d['dept_name'] ?></option>
        <?php endwhile; ?>
      </select>
    </div>

      <div class="mb-3">
        <label class="form-label">Notes / Purpose</label>
        <textarea name="notes" class="form-control"></textarea>
      </div>

      <button type="submit" class="btn btn-primary">Submit Request</button>
    </form>
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
  
    <script>
    document.getElementById("request_type").addEventListener("change", function() {
      document.getElementById("asset_field").style.display = this.value === "Asset" ? "block" : "none";
      document.getElementById("consumable_field").style.display = this.value === "Consumable" ? "block" : "none";
    });
  </script>
</body>
</html>