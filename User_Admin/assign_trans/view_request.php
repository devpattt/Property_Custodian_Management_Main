<?php
session_start();
include '../../connection.php';

$query = "
    SELECT 
      r.request_id,
      r.request_type,
      r.quantity      AS requested_qty,
      r.status,
      r.date_requested,
      r.notes,
      u.username      AS teacher,
      d.dept_name     AS department,   -- from departments table
      a.property_tag,
      i1.item_name    AS asset_item,
      c.quantity      AS stock_qty,
      i2.item_name    AS consumable_item
    FROM bcp_sms4_requests r
    JOIN bcp_sms4_admins u ON r.teacher_id = u.id
    JOIN bcp_sms4_departments d ON r.department_id = d.id 
    LEFT JOIN bcp_sms4_asset a ON r.asset_id = a.asset_id
    LEFT JOIN bcp_sms4_items i1 ON a.item_id = i1.item_id
    LEFT JOIN bcp_sms4_consumable c ON r.consumable_id = c.id
    LEFT JOIN bcp_sms4_items i2 ON c.item_id = i2.item_id
    ORDER BY r.date_requested DESC
";


$result = $conn->query($query);
?>
?>

<!DOCTYPE html>
<html lang="en">

<head>
  <meta charset="utf-8">
  <meta content="width=device-width, initial-scale=1.0" name="viewport">
  <title>View Request</title>
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
      <h1>View Request</h1>
      <nav>
        <ol class="breadcrumb">
          <li class="breadcrumb-item"><a href="<?=BASE_URL?>User_Admin/dashboard.php">Home</a></li>
          <li class="breadcrumb-item">Property Issuance and Acknowledgement</li>
          <li class="breadcrumb-item active">Requests</li>
        </ol>
      </nav>
    </div>
    
    <section>
    <?php if(isset($_GET['updated'])): ?>
      <div class="alert alert-success">Request processed successfully!</div>
    <?php endif; ?>

    <section class="section">
      <div class="row">
        <div class="col-lg-12">
          <div class="card">
            <div class="card-body">
                     <br>
              <p>
                <em>Below is a list of all equipment and consumable requests. 
                You can <b>search, sort, and filter</b> the records to quickly 
                review requests from teachers, track stock usage, and monitor approvals.</em>
              </p>
              <table class="table datatable table-bordered table-striped">
                <thead>
                  <tr>
                    <th>ID</th>
                    <th>Teacher</th>
                    <th>Department</th>
                    <th>Item</th>
                    <th>Type</th>
                    <th>Requested Qty</th>
                    <th>Stock (if consumable)</th>
                    <th>Status</th>
                    <th>Date Requested</th>
                    <th>Notes</th>
                    <th>Action</th>
                  </tr>
                </thead>
                <tbody>
                  <?php while($row = $result->fetch_assoc()): ?>
                  <tr>
                    <td><?= htmlspecialchars($row['request_id']) ?></td>
                    <td><?= htmlspecialchars($row['teacher']) ?></td>
                    <td><?= htmlspecialchars($row['department']) ?></td>
                    <td>
                      <?php if ($row['request_type'] === 'Asset'): ?>
                        <?= htmlspecialchars($row['property_tag'] ?? 'N/A') ?> — <?= htmlspecialchars($row['asset_item'] ?? 'Unknown') ?>
                      <?php else: ?>
                        <?= htmlspecialchars($row['consumable_item'] ?? 'Unknown') ?>
                      <?php endif; ?>
                    </td>
                    <td><?= htmlspecialchars($row['request_type']) ?></td>
                    <td><?= htmlspecialchars($row['requested_qty']) ?></td>
                    <td>
                      <?php 
                        if ($row['request_type'] === 'Consumable') {
                            echo htmlspecialchars($row['stock_qty'] ?? '0');
                        } else {
                            echo '—';
                        }
                      ?>
                    </td>
                    <td>
                      <?php if ($row['status'] === 'Pending'): ?>
                        <span class="badge bg-warning text-dark"><?= htmlspecialchars($row['status']) ?></span>
                      <?php elseif ($row['status'] === 'Approved'): ?>
                        <span class="badge bg-success"><?= htmlspecialchars($row['status']) ?></span>
                      <?php elseif ($row['status'] === 'Rejected'): ?>
                        <span class="badge bg-danger"><?= htmlspecialchars($row['status']) ?></span>
                      <?php else: ?>
                        <span class="badge bg-secondary"><?= htmlspecialchars($row['status']) ?></span>
                      <?php endif; ?>
                    </td>
                    <td><?= htmlspecialchars($row['date_requested']) ?></td>
                    <td><?= nl2br(htmlspecialchars($row['notes'])) ?></td>
                    <td>
                      <?php if ($row['status'] === 'Pending'): ?>
                        <form method="post" action="process_request.php" style="display:inline;">
                          <input type="hidden" name="request_id" value="<?= $row['request_id'] ?>">
                          <button name="action" value="approve" class="btn btn-sm btn-success">Approve</button>
                        </form>
                        <form method="post" action="process_request.php" style="display:inline;">
                          <input type="hidden" name="request_id" value="<?= $row['request_id'] ?>">
                          <button name="action" value="reject" class="btn btn-sm btn-danger">Reject</button>
                        </form>
                      <?php else: ?>
                        <em>No action</em>
                      <?php endif; ?>
                    </td>
                  </tr>
                  <?php endwhile; ?>

                  <?php if ($result->num_rows === 0): ?>
                  <tr>
                    <td colspan="10" class="text-center">No requests found</td>
                  </tr>
                  <?php endif; ?>
                </tbody>
              </table>

            </div>
          </div>
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