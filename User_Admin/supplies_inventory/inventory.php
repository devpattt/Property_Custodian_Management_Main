<?php
session_start();
?>

<!DOCTYPE html>
<html lang="en">

<head>
  <meta charset="utf-8">
  <meta content="width=device-width, initial-scale=1.0" name="viewport">
  <title>Inventory</title>
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
  <link href="../../assets/css/schedule_maintenance.css" rel="stylesheet">
    <link href="../../assets/css/style.css" rel="stylesheet">
</head>
<body>
  <?php include '../../components/nav-bar.php' ?>
  <main id="main" class="main">

    <div class="pagetitle">
      <h1>Supplies Inventory</h1>
      <nav>
        <ol class="breadcrumb">
          <li class="breadcrumb-item"><a href="<?=BASE_URL?>User_Admin/dashboard.php">Home</a></li>
          <li class="breadcrumb-item">Supplies Inventory</li>
          <li class="breadcrumb-item active">Inventory</li>
        </ol>
      </nav>
    </div><!-- End Page Title -->


    <?php
        include '../../connection.php'; 
        // Active assets (status: active > 0)
        $active_assets = 0;
        $result = $conn->query("SELECT COUNT(*) as total FROM bcp_sms4_asset WHERE status = 'In-Use' ");
        if ($result && $row = $result->fetch_assoc()) {
            $active_assets = $row['total'];
        }

        // Unassigned assets (quantity > 0 but not assigned)
        $unassigned_assets = 0;
        $result = $conn->query("
            SELECT COUNT(*) as total 
            FROM bcp_sms4_asset a
            WHERE a.status = 'In-Storage' AND a.status = 'Damaged' AND a.status = 'Disposed'
        ");
        if ($result && $row = $result->fetch_assoc()) {
            $unassigned_assets = $row['total'];
        }

        // Disposed assets
        $disposed_assets = 0;
        $result = $conn->query("SELECT COUNT(*) as total FROM bcp_sms4_asset WHERE status = 'Disposed' ");
        if ($result && $row = $result->fetch_assoc()) {
            $disposed_assets = $row['total'];
        }

        // Need Repair (was Low Stock before)
        $need_repair = 0;
        $result = $conn->query("SELECT COUNT(*) as total FROM bcp_sms4_asset WHERE status = 'Damaged'");
        if ($result && $row = $result->fetch_assoc()) {
            $need_repair = $row['total'];
        }
        ?>
  <section class="section dashboard">
      <div class="row">

<div class="col-xxl-3 col-md-6">
  <div class="card info-card">
    <div class="card-body">
      <h5 class="card-title">Active Assets</h5>
      <div class="d-flex align-items-center">
        <div class="card-icon rounded-circle d-flex align-items-center justify-content-center">
          <i class="bi bi-check-circle text-success"></i>
        </div>
        <div class="ps-3">
          <h6><?php echo $active_assets; ?></h6>
          <span class="text-success small">🟢 In Use</span>
        </div>
      </div>
    </div>
  </div>
</div>

<!-- Unassigned Assets -->
<div class="col-xxl-3 col-md-6">
  <div class="card info-card">
    <div class="card-body">
      <h5 class="card-title">Unassigned Assets</h5>
      <div class="d-flex align-items-center">
        <div class="card-icon rounded-circle d-flex align-items-center justify-content-center">
          <i class="bi bi-box-seam text-warning"></i>
        </div>
        <div class="ps-3">
          <h6><?php echo $unassigned_assets; ?></h6>
          <span class="text-warning small">🟡 In Storage</span>
        </div>
      </div>
    </div>
  </div>
</div>

<!-- Disposed Assets -->
<div class="col-xxl-3 col-md-6">
  <div class="card info-card">
    <div class="card-body">
      <h5 class="card-title">Disposed/Retired</h5>
      <div class="d-flex align-items-center">
        <div class="card-icon rounded-circle d-flex align-items-center justify-content-center">
          <i class="bi bi-trash text-danger"></i>
        </div>
        <div class="ps-3">
          <h6><?php echo $disposed_assets; ?></h6>
          <span class="text-danger small">🔴 Disposed</span>
        </div>
      </div>
    </div>
  </div>
</div>

<!-- Need Repair -->
<div class="col-xxl-3 col-md-6">
  <div class="card info-card">
    <div class="card-body">
      <h5 class="card-title">Need Repair</h5>
      <div class="d-flex align-items-center">
        <div class="card-icon rounded-circle d-flex align-items-center justify-content-center bg-warning bg-opacity-10">
          <i class="bi bi-tools text-warning"></i>
        </div>
        <div class="ps-3">
          <h6><?php echo $need_repair; ?></h6>
          <span class="text-warning small">🛠️ Needs repair</span>
        </div>
      </div>
    </div>
  </div>
</div>

<section class="section">
  <div class="row">
    <div class="col-lg-12">

      <div class="card">
        <div class="card-body">

          <!-- Tabs -->
          <ul class="nav nav-tabs" id="assetTabs" role="tablist">
            <li class="nav-item" role="presentation">
              <button class="nav-link active" id="assets-tab" data-bs-toggle="tab" data-bs-target="#assets" type="button" role="tab">Assets</button>
            </li>
            <li class="nav-item" role="presentation">
              <button class="nav-link" id="consumables-tab" data-bs-toggle="tab" data-bs-target="#consumables" type="button" role="tab">Consumables</button>
            </li>
            <li class="nav-item" role="presentation">
              <button class="nav-link" id="assignments-tab" data-bs-toggle="tab" data-bs-target="#assignments" type="button" role="tab">Custodian Assignments</button>
            </li>
          </ul>

          <div class="tab-content mt-3" id="assetTabsContent">

            <!-- Assets Table -->
            <div class="tab-pane fade show active" id="assets" role="tabpanel">
              <h5 class="card-title">Assets</h5>
              <table class="table datatable">
                <thead>
                  <tr>
                    <th>ID</th>
                    <th>Property Tag</th>
                    <th>Item Name</th>
                    <th>Category</th>
                    <th>Status</th>
                    <th>Date Registered</th>
                  </tr>
                </thead>
                <tbody>
                  <?php
                  $assets = $conn->query("
                    SELECT a.asset_id, a.property_tag, a.status, a.date_registered,
                          i.item_name, i.category
                    FROM bcp_sms4_asset a
                    JOIN bcp_sms4_items i ON a.item_id = i.item_id
                    ORDER BY a.asset_id DESC
                  ");
                  if ($assets && $assets->num_rows > 0):
                      while ($row = $assets->fetch_assoc()):
                  ?>
                      <tr>
                          <td><?= $row['asset_id'] ?></td>
                          <td><?= $row['property_tag'] ?></td>
                          <td><?= $row['item_name'] ?></td>
                          <td><?= $row['category'] ?></td>
                          <td><?= $row['status'] ?></td>
                          <td><?= $row['date_registered'] ?></td>
                      </tr>
                  <?php
                      endwhile;
                  else:
                  ?>
                      <tr><td colspan="7">No assets found</td></tr>
                  <?php endif; ?>
                </tbody>
              </table>
            </div>


            <!-- Consumables Table -->
            <div class="tab-pane fade" id="consumables" role="tabpanel">
            <h5 class="card-title">Consumables</h5>
            <table class="table datatable">
              <thead>
                <tr>
                  <th>ID</th>
                  <th>Item Name</th>
                  <th>Category</th>
                  <th>Unit</th>
                  <th>Quantity</th>
                  <th>Status</th>
                  <th>Expiration</th>
                  <th>Date Received</th>
                </tr>
              </thead>
              <tbody>
                <?php
                $consumables = $conn->query("
                  SELECT c.id, c.unit, c.quantity, c.status, c.expiration, c.date_received,
                        i.item_name, i.category
                  FROM bcp_sms4_consumable c
                  JOIN bcp_sms4_items i ON c.item_id = i.item_id
                  ORDER BY c.id DESC
                ");
                if ($consumables && $consumables->num_rows > 0):
                    while ($row = $consumables->fetch_assoc()):
                ?>
                    <tr>
                        <td><?= $row['id'] ?></td>
                        <td><?= $row['item_name'] ?></td>
                        <td><?= $row['category'] ?></td>
                        <td><?= $row['unit'] ?></td>
                        <td><?= $row['quantity'] ?></td>
                        <td><?= $row['status'] ?></td>
                        <td><?= $row['expiration'] ?></td>
                        <td><?= $row['date_received'] ?></td>
                    </tr>
                <?php
                    endwhile;
                else:
                ?>
                    <tr><td colspan="8">No consumables found</td></tr>
                <?php endif; ?>
              </tbody>
            </table>
          </div>

            <!-- Assignments Table -->
            <div class="tab-pane fade" id="assignments" role="tabpanel">
              <h5 class="card-title">Custodian Assignments</h5>
              <table class="table datatable">
                <thead>
                  <tr>
                    <th>Reference</th>
                    <th>Equipment ID</th>
                    <th>Name</th>
                    <th>Quantity</th>
                    <th>Custodian</th>
                    <th>Department</th>
                    <th>Assigned Date</th>
                    <th>End Date</th>
                    <th>Assigned By</th>
                    <th>Remarks</th>
                  </tr>
                </thead>
                    <tbody>
                    <?php
                    $assignments = $conn->query("SELECT * FROM bcp_sms4_assign_history ORDER BY id DESC");
                    if ($assignments && $assignments->num_rows > 0):
                        while ($row = $assignments->fetch_assoc()):
                    ?>
                        <tr>
                            <td><?= $row['reference_no'] ?></td>
                            <td><?= $row['equipment_id'] ?></td>
                            <td><?= $row['equipment_name'] ?></td>
                            <td><?= $row['quantity'] ?></td>
                            <td><?= $row['custodian_name'] ?></td>
                            <td><?= $row['department_code'] ?></td>
                            <td><?= $row['assigned_date'] ?></td>
                            <td><?= $row['end_date'] ?></td>
                            <td><?= $row['assigned_by'] ?></td>
                            <td><?= $row['remarks'] ?></td>
                        </tr>
                    <?php
                        endwhile;
                    else:
                    ?>
                        <tr><td colspan="10">No assignments found</td></tr>
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