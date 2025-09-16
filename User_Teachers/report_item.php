<?php
session_start();
?>
<!DOCTYPE html>
<html lang="en">

<head>
  <meta charset="utf-8">
  <meta content="width=device-width, initial-scale=1.0" name="viewport">
  <title>Report Item</title>
  <meta content="" name="description">
  <meta content="" name="keywords">
  <link href="../assets/img/bagong_silang_logo.png" rel="icon">
  <link href="../assets/img/apple-touch-icon.png" rel="apple-touch-icon">
  <link rel="preconnect" href="https://fonts.googleapis.com">
  <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
  <link href="https://fonts.googleapis.com/css2?family=Inter:ital,opsz,wght@0,14..32,100..900;1,14..32,100..900&display=swap" rel="stylesheet">
  <link href="../assets/vendor/bootstrap/css/bootstrap.min.css" rel="stylesheet">
  <link href="../assets/vendor/bootstrap-icons/bootstrap-icons.css" rel="stylesheet">
  <link href="../assets/vendor/boxicons/css/boxicons.min.css" rel="stylesheet">
  <link href="../assets/vendor/quill/quill.snow.css" rel="stylesheet">
  <link href="../assets/vendor/quill/quill.bubble.css" rel="stylesheet">
  <link href="../assets/vendor/remixicon/remixicon.css" rel="stylesheet">
  <link href="../assets/vendor/simple-datatables/style.css" rel="stylesheet">
  <link href="../assets/css/style.css" rel="stylesheet">
</head>

<body>
  <?php
    include "../components/nav-bar.php";
  ?>
  <main id="main" class="main">
    <div class="pagetitle">
      <h1>Report Item</h1>
      <nav>
        <ol class="breadcrumb">
          <li class="breadcrumb-item"><a href="<?=BASE_URL?>User_Teachers/dashboard_teacher.php">Home</a></li>
          <li class="breadcrumb-item active">Report Item</li>
        </ol>
      </nav>
    </div>

<section class="section dashboard">
  <form method="post" action="<?=BASE_URL?>User_Teachers/save_report.php" enctype="multipart/form-data" class="report-form">
    <h2>Report Lost/Damaged Item</h2>

    <div class="form-group">
      <label for="item_name">Item Name</label>
      <input type="text" id="item_name" name="item_name" required>
    </div>

    <div class="form-group">
      <label for="report_type">Report Type:</label>
      <select id="report_type" name="report_type" required>
        <option value="Lost">Lost</option>
        <option value="Damaged">Damaged</option>
        <option value="Repair/Replacement">Repair</option>
      </select>
    </div>

    <div class="form-group">
      <label for="description">Description:</label>
      <textarea id="description" name="description" rows="4"></textarea>
    </div>

    <div class="form-group">
      <label for="photo">Upload Photo (Optional):</label>
      <input type="file" id="photo" name="photo" accept="image/*">
    </div>

    <button type="submit" class="btn-submit">Submit Report</button>
  </form>
</section>

<style>
.report-form {
  max-width: 500px;
  margin: 30px auto;
  padding: 20px;
  background: #fff;
  border-radius: 12px;
  box-shadow: 0px 4px 10px rgba(0,0,0,0.1);
  font-family: Arial, sans-serif;
}

.report-form h2 {
  text-align: center;
  margin-bottom: 20px;
  color: #333;
}

.form-group {
  margin-bottom: 15px;
}

.form-group label {
  display: block;
  font-weight: bold;
  margin-bottom: 6px;
  color: #444;
}

.form-group input[type="text"],
.form-group select,
.form-group textarea,
.form-group input[type="file"] {
  width: 100%;
  padding: 10px;
  border: 1px solid #ccc;
  border-radius: 8px;
  font-size: 14px;
}

.form-group textarea {
  resize: vertical;
}

.btn-submit {
  width: 100%;
  padding: 12px;
  background: #007bff;
  color: white;
  border: none;
  border-radius: 8px;
  font-size: 16px;
  cursor: pointer;
  transition: background 0.3s ease;
}

.btn-submit:hover {
  background: #0056b3;
}
</style>


  </main>
  <a href="#" class="back-to-top d-flex align-items-center justify-content-center"><i class="bi bi-arrow-up-short"></i></a>
  <script src="../assets/vendor/apexcharts/apexcharts.min.js"></script>
  <script src="../assets/vendor/bootstrap/js/bootstrap.bundle.min.js"></script>
  <script src="../assets/vendor/chart.js/chart.umd.js"></script>
  <script src="../assets/vendor/echarts/echarts.min.js"></script>
  <script src="../assets/vendor/quill/quill.js"></script>
  <script src="../assets/vendor/simple-datatables/simple-datatables.js"></script>
  <script src="../assets/vendor/tinymce/tinymce.min.js"></script>
  <script src="../assets/vendor/php-email-form/validate.js"></script>
  <script src="../assets/js/main.js"></script>
</body>
</html>