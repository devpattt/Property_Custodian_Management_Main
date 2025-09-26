<?php
session_start();
include '../connection.php';  

$custodian_id = $_SESSION['user_id'];

$stmt = $conn->prepare("
    SELECT r.id, 
           u.username AS teacher, 
           r.report_type, 
           r.description, 
           r.status, 
           r.date_reported,
           a.property_tag,
           i.item_name AS asset_item_name,
           ic.item_name AS consumable_item_name
    FROM bcp_sms4_reports r
    JOIN bcp_sms4_admins u ON r.reported_by = u.id
    LEFT JOIN bcp_sms4_asset a ON r.asset_id = a.asset_id
    LEFT JOIN bcp_sms4_items i ON a.item_id = i.item_id
    LEFT JOIN bcp_sms4_consumable c ON r.id = c.id
    LEFT JOIN bcp_sms4_items ic ON c.item_id = ic.item_id
    WHERE r.assigned_to = ? AND r.status = 'In-Progress'
    ORDER BY r.date_reported DESC
");
$stmt->bind_param("i", $custodian_id);
$stmt->execute();
$result = $stmt->get_result();
?>


<!DOCTYPE html>
<html lang="en">

<head>
  <meta charset="utf-8">
  <meta content="width=device-width, initial-scale=1.0" name="viewport">
  <title>View Assigned Reports</title>
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
      <h1>View Assigned Reports</h1>
      <nav>
        <ol class="breadcrumb">
          <li class="breadcrumb-item"><a href="<?=BASE_URL?>User_Custodians/dashboard_custodians.php">Home</a></li>
          <li class="breadcrumb-item active">View Assigned Reports</li>
        </ol>
      </nav>
    </div><!-- End Page Title -->

    <section class="section dashboard">
      <div class="row">
        <div class="col-lg-12">
          <div class="card">
            <div class="card-body">
              <h5 class="card-title">Reports Table</h5>
              <?php if(isset($_GET['updated'])): ?>
                <div class="alert alert-success">
                  Item Updated Successfully!!!
                </div>
              <?php endif; ?>
              <table class="table datatable">
                <thead>
                  <tr>
                    <th>ID</th>
                    <th>Teacher</th>
                    <th>Item</th>
                    <th>Type</th>
                    <th>Status</th>
                    <th>Date</th>
                    <th>Action</th>
                  </tr>
                </thead>
              <tbody>
                  <?php while ($row = $result->fetch_assoc()): ?>
                  <tr>
                    <td><?= $row['id'] ?></td>
                    <td><?= htmlspecialchars($row['teacher']) ?></td>
                <td>
                  <?php if ($row['property_tag']): ?>
                    <?= htmlspecialchars($row['property_tag']) ?> - <?= htmlspecialchars($row['asset_item_name']) ?>
                  <?php elseif ($row['consumable_item_name']): ?>
                    <?= htmlspecialchars($row['consumable_item_name']) ?>
                  <?php else: ?>
                    <em>Unknown Item</em>
                  <?php endif; ?>
                </td>

                    <td><?= ucfirst($row['report_type']) ?></td>
                    <td>
                      <?php if ($row['status'] == 'pending'): ?>
                        <span class="badge bg-warning text-dark"><?= ucfirst($row['status']) ?></span>
                      <?php elseif ($row['status'] == 'approved'): ?>
                        <span class="badge bg-success"><?= ucfirst($row['status']) ?></span>
                      <?php else: ?>
                        <span class="badge bg-secondary"><?= ucfirst($row['status']) ?></span>
                      <?php endif; ?>
                    </td>
                    <td><?= $row['date_reported'] ?></td>
                    <td>
                      <button 
                        type="button" 
                        class="btn btn-sm btn-primary update-btn" 
                        data-id="<?= $row['id']?>"
                        data-bs-toggle="modal" 
                        data-bs-target="#updateModal">
                        Update
                      </button>
                    </td>
                  </tr>
                  <?php endwhile; ?>
                </tbody>

              </table>
            </div>
          </div>
        </div>
      </div>
    </section>

<div class="modal fade" id="updateModal" tabindex="-1" aria-hidden="true">
  <div class="modal-dialog modal-lg">
    <div class="modal-content">
      <div class="modal-header">
        <h5 class="modal-title">Update Report</h5>
        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
      </div>
      <div class="modal-body">
        <div id="updateFormContent">
          <p class="text-center text-muted">Loading form...</p>
        </div>
      </div>
    </div>
  </div>
</div>

<script>
document.addEventListener("DOMContentLoaded", function() {
  const updateButtons = document.querySelectorAll(".update-btn");
  const updateFormContent = document.getElementById("updateFormContent");

  updateButtons.forEach(btn => {
    btn.addEventListener("click", function() {
      const reportId = this.getAttribute("data-id");
      updateFormContent.innerHTML = "<p class='text-center text-muted'>Loading form...</p>";
      fetch("show_update_report.php?id=" + reportId)
        .then(response => {
          console.log("Response status:", response.status); 
          if (!response.ok) {
            throw new Error(`HTTP error! status: ${response.status}`);
          }
          return response.text();
        })
        .then(data => {
          console.log("Response data:", data); 
          updateFormContent.innerHTML = data;
        })
        .catch(error => {
          console.error("Fetch error:", error); 
          updateFormContent.innerHTML = "<p class='text-danger'>Failed to load form: " + error.message + "</p>";
        });
    });
  });
});
</script>
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