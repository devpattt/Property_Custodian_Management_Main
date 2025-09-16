<!DOCTYPE html>
<html lang="en">

<head>
  <meta charset="utf-8">
  <meta content="width=device-width, initial-scale=1.0" name="viewport">
  <title>Dashboard</title>
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
  <link href="../assets/css/schedule_maintenance.css" rel="stylesheet">
</head>

<body>
<?php
  include '../components/nav-bar.php';
?>
  <main id="main" class="main">

    <div class="pagetitle">
      <h1>Schedule Maintenance</h1>
      <nav>
        <ol class="breadcrumb">
          <li class="breadcrumb-item"><a href="dashboard.php">Home</a></li>
          <li class="breadcrumb-item">Preventive Maintenance Scheduling</li>
          <li class="breadcrumb-item active">Schedule Maintenance</li>
        </ol>
      </nav>
    </div>
  </main>
</head>
<body>

<div class="maint-container">
  <!-- Left: Form -->
  <div class="maint-card">
    <h2>Schedule Maintenance</h2>

    <label for="asset" class="maint-label">Select Asset</label>
    <select id="asset" class="maint-select">
      <option value="">-- Choose an asset --</option>
      <option value="Air Conditioner">Classroom & Teaching Equipment</option>
      <option value="Projector">Office & Administrative Equipmentr</option>
      <option value="Computer">IT & Networking Equipment</option>
      <option value="Computer">Maintenance & Facility Equipment</option>
      <option value="Computer">Sports & Recreation Equipment</option>
      <option value="Computer">Laboratory & Research Equipment</option>
    </select>

    <label for="type" class="maint-label">Maintenance Type</label>
    <select id="type" class="maint-select">
      <option value="">-- Select type --</option>
      <option value="Cleaning">Cleaning</option>
      <option value="Calibration">Calibration</option>
      <option value="Inspection">Inspection</option>
      <option value="Replacement">Parts Replacement</option>
    </select>

    <label for="frequency" class="maint-label">Frequency</label>
    <select id="frequency" class="maint-select">
      <option value="">-- Select frequency --</option>
      <option value="Daily">Daily</option>
      <option value="Weekly">Weekly</option>
      <option value="Monthly">Monthly</option>
      <option value="Quarterly">Quarterly</option>
      <option value="Yearly">Yearly</option>
    </select>

    <label for="personnel" class="maint-label">Assign Responsible Personnel/Provider</label>
    <input type="text" id="personnel" class="maint-input" placeholder="Enter name or provider">

    <label for="date" class="maint-label">Start Date</label>
    <input type="date" id="date" class="maint-input">

    <button class="maint-button" onclick="saveSchedule()">Save Schedule</button>

    <div class="maint-message" id="message">✅ Maintenance schedule saved successfully!</div>
  </div>

  <!-- Right: Calendar -->
  <div class="calendar-card">
    <div class="calendar-header">
      <button onclick="prevMonth()">&#8592;</button>
      <div id="monthYear"></div>
      <button onclick="nextMonth()">&#8594;</button>
    </div>
    <div class="calendar-grid" id="calendar">
    </div>
  </div>
</div>
</body>
</html>

 <!-- Toast -->
    <div id="toast"></div>

    <!-- Alert Modal -->
    <div id="modalAlert" class="modal">
        <div class="modal-content">
            <span class="close" onclick="closeModal()">&times;</span>
            <p id="modalMessage"></p>
            <button class="confirm-btn" onclick="closeModal()">OK</button>
        </div>
    </div>

    <!-- Confirmation Modal -->
    <div id="confirmModal" class="modal">
        <div class="modal-content">
            <span class="close" onclick="closeConfirmModal()">&times;</span>
            <h3>Confirm Schedule</h3>
            <p id="confirmMessage"></p>
            <button class="confirm-btn" onclick="confirmSchedule()">Confirm</button>
            <button class="cancel-btn" onclick="closeConfirmModal()">Cancel</button>
        </div>
    </div>

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
  <script src="../assets/js/schedule_maintenance.js"></script>

</body>
</html>