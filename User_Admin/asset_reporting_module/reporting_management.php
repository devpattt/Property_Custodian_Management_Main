<?php
session_start();

include '../../connection.php';  

$query = "SELECT r.id, u.username AS teacher, r.asset, r.report_type, r.status, r.description, r.date_reported, r.evidence, c.username AS custodian FROM bcp_sms4_reports r JOIN bcp_sms4_admins u ON r.reported_by = u.id LEFT JOIN bcp_sms4_admins c ON r.assigned_to = c.id ORDER BY r.date_reported DESC";
$results = $conn->query($query);
?>
<!DOCTYPE html>
<html lang="en">

<head>
  <meta charset="utf-8">
  <meta content="width=device-width, initial-scale=1.0" name="viewport">
  <title>Records Management</title>
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
<?php
include '../../components/nav-bar.php';
?>
  <main id="main" class="main">

    <div class="pagetitle">
      <h1>Records Management</h1>
      <nav>
        <ol class="breadcrumb">
          <li class="breadcrumb-item"><a href="<?=BASE_URL?>User_Admin/dashboard.php">Home</a></li>
          <li class="breadcrumb-item">Lost, Damaged or Unserviceable Items</li>
          <li class="breadcrumb-item active">Report Management</li>
        </ol>
      </nav>
    </div><!-- End Page Title -->

    <section class="section">
    <div class="row">
        <div class="col-lg-12">
        <div class="card">
            <div class="card-body">
            <h5 class="card-title">All Reports</h5>
            <p><em>Below is a list of all lost, damaged, or replacement requests. You can <b>search, sort, and filter</b> the records, and use actions to manage them.</em></p>
            
            <table class="table datatable">
                <thead>
                <tr>
                    <th>ID</th>
                    <th>Item Name</th>
                    <th>Report Type</th>
                    <th>Reported By</th>
                    <th>Date</th>
                    <th>Status</th>
                    <th>Action</th>
                </tr>
                </thead>
                <tbody>
                <?php while ($row = $results->fetch_assoc()): ?>
                <tr>
                    <td><?= $row['id']?></td>
                    <td><?= htmlspecialchars($row['asset'])?></td>
                    <td><?= htmlspecialchars($row['report_type'])?></td>
                    <td><?= htmlspecialchars($row['teacher'])?></td>
                    <td><?= date('Y/m/d', strtotime($row['date_reported']))?></td>
                        <td>
                            <?php if (!empty($row['status'])): ?>
                                <?php 
                                $statusClass = match($row['status']) {
                                    'Resolved'    => 'badge bg-success',
                                    'In-Progress' => 'badge bg-warning',
                                    'Pending'     => 'badge bg-primary',
                                    'Rejected'    => 'badge bg-danger',
                                    default       => 'badge bg-secondary'
                                };
                                ?>
                                <span class="<?= $statusClass; ?>"><?= htmlspecialchars($row['status']); ?></span>
                            <?php else: ?>
                                <span class="text-muted">—</span> 
                            <?php endif; ?>
                        </td>
                    <td>
                    <button class="btn btn-sm btn-info view-btn" data-id="<?= $row['id']; ?>">View</button>
                    </td>
                </tr>
                <?php endwhile; ?>
                <?php if (empty($results)): ?>
                <tr>
                    <td colspan="6" class="text-center">No reports found</td>
                </tr>
                <?php endif; ?>
                </tbody>
            </table>
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
                "responsive": true,
                "columnDefs": [
                    {
                        "targets": 4, 
                        "type": "date"
                    },
                    {
                        "targets": 5, 
                        "orderable": true
                    }
                ]
            });
        }
    });
    </script>
  </main>

        <!-- View Report Modal -->
        <div class="modal fade" id="viewReportModal" tabindex="-1" aria-hidden="true">
        <div class="modal-dialog modal-md modal-dialog-centered">
            <div class="modal-content border-0 shadow rounded-4">
    
            <div class="modal-header bg-light border-0 rounded-top-4">
                <h6 class="modal-title fw-semibold text-dark">
                <i class="bi bi-file-earmark-text me-2 text-primary"></i> Report Details
                </h6>
                <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
            </div>

            <div class="modal-body p-3" id="report-details">
                <p class="text-center text-muted small">Loading...</p>
            </div>       
            </div>
        </div>
        </div>

        <script>
        document.addEventListener("DOMContentLoaded", function () {
            document.querySelectorAll(".view-btn").forEach(button => {
                button.addEventListener("click", function () {
                    let reportId = this.getAttribute("data-id");

                    fetch("fetch_reports.php?id=" + reportId) 
                        .then(response => response.text())
                        .then(data => {
                            document.getElementById("report-details").innerHTML = data;
                            new bootstrap.Modal(document.getElementById("viewReportModal")).show();
                        });
                });
            });
        });
        </script>




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