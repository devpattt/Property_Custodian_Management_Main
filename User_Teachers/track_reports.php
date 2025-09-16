<?php
session_start();
include '../connection.php';

$teacher_id = $_SESSION['user_id'];
$sql = "SELECT id, asset, report_type, status, date_reported, evidence FROM bcp_sms4_reports WHERE reported_by = ?";
$stmt = $conn->prepare($sql);
$stmt->bind_param("i", $teacher_id);
$stmt->execute();
$result = $stmt->get_result();
?>
<!DOCTYPE html>
<html lang="en">

<head>
  <meta charset="utf-8">
  <meta content="width=device-width, initial-scale=1.0" name="viewport">
  <title>Track Reports</title>
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
    include "../components/nav-bar.php";
  ?>
  <main id="main" class="main">
    <div class="pagetitle">
      <h1>Track Reports</h1>
      <nav>
        <ol class="breadcrumb">
          <li class="breadcrumb-item"><a href="<?=BASE_URL?>User_Teachers/dashboard_teacher.php">Home</a></li>
          <li class="breadcrumb-item active">Track Reports</li>
        </ol>
      </nav>
    </div>

<section>
  <?php if(isset($_GET['success'])): ?>
    <div class="alert alert-success alert-dismissible fade show" role="alert">
      Lost Item Reported Successfully!!!
      <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
    </div>
  <?php endif; ?>

  <div class="card">
    <div class="card-body">
      <h5 class="card-title">Lost Items Report</h5>
      <p class="small">This table shows all lost items reported in the system.</p>

      <!-- Table with datatable -->
      <table class="table datatable">
        <thead>
          <tr>
            <th>ID</th>
            <th>Item</th>
            <th>Type</th>
            <th>Status</th>
            <th>Date</th>
            <th>Photo</th>
          </tr>
        </thead>
        <tbody>
          <?php while ($row = $result->fetch_assoc()): ?>
            <tr>
              <td><?= htmlspecialchars($row['id']) ?></td>
              <td><?= htmlspecialchars($row['asset']) ?></td>
              <td><?= htmlspecialchars($row['report_type']) ?></td>
              <td>
                <?php if($row['status'] == 'Pending'): ?>
                  <span class="badge bg-warning"><?= htmlspecialchars($row['status']) ?></span>
                <?php elseif($row['status'] == 'Resolved'): ?>
                  <span class="badge bg-success"><?= htmlspecialchars($row['status']) ?></span>
                <?php else: ?>
                  <span class="badge bg-secondary"><?= htmlspecialchars($row['status']) ?></span>
                <?php endif; ?>
              </td>
              <td><?= htmlspecialchars($row['date_reported']) ?></td>
              <td class="text-center">
                <?php if(!empty($row['evidence'])): ?>
                  <a href="uploads/<?= htmlspecialchars($row['evidence']) ?>" target="_blank" class="btn btn-sm btn-primary">View</a>
                <?php else: ?>
                  <span class="text-muted">No Photo</span>
                <?php endif; ?>
              </td>
            </tr>
          <?php endwhile; ?>
        </tbody>
      </table>
      <!-- End datatable -->

    </div>
  </div>
</section>
</main>

</body>
</html>

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