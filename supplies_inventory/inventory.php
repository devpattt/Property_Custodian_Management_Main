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
  <header id="header" class="header fixed-top d-flex align-items-center">

    <div class="d-flex align-items-center justify-content-between">
      <i class="bi bi-list toggle-sidebar-btn"></i>
    </div>

    <nav class="header-nav ms-auto">
      <ul class="d-flex align-items-center">

        <li class="nav-item dropdown pe-3">

          <a class="nav-link nav-profile d-flex align-items-center pe-0" href="#" data-bs-toggle="dropdown">
            <img src="../assets/img/default_profile.png" alt="Profile" class="rounded-circle">
            <span class="d-none d-md-block dropdown-toggle ps-2">Juan Delacruz</span>
          </a>

          <ul class="dropdown-menu dropdown-menu-end dropdown-menu-arrow profile">
            <li class="dropdown-header">
              <h6>Administrator</h6>
            </li>
            <li>
              <hr class="dropdown-divider">
            </li>

            <li>
              <a class="dropdown-item d-flex align-items-center" href="users-profile.html">
                <i class="bi bi-person"></i>
                <span>My Profile</span>
              </a>
            </li>
            <li>
              <hr class="dropdown-divider">
            </li>

            <li>
              <a class="dropdown-item d-flex align-items-center" href="users-profile.html">
                <i class="bi bi-gear"></i>
                <span>Account Settings</span>
              </a>
            </li>
            <li>
              <hr class="dropdown-divider">
            </li>

            <li>
              <a class="dropdown-item d-flex align-items-center" href="pages-faq.html">
                <i class="bi bi-question-circle"></i>
                <span>Need Help?</span>
              </a>
            </li>
            <li>
              <hr class="dropdown-divider">
            </li>

            <li>
              <a class="dropdown-item d-flex align-items-center" href="#">
                <i class="bi bi-box-arrow-right"></i>
                <span>Sign Out</span>
              </a>
            </li>

          </ul>
        </li>
      </ul>
    </nav>
  </header>

  <!-- ======= Sidebar ======= -->
  <aside id="sidebar" class="sidebar">

    <ul class="sidebar-nav" id="sidebar-nav">
        <div class="flex items-center justify-center w-full h-16 bg-transparent">
            <img src="../assets/img/bagong_silang_logo.png" 
                alt="Logo" 
                style="width: 120px; height: auto; margin: 0 auto; display: block;">
        </div>


    <hr class="sidebar-divider">

      <li class="nav-item">
        <a class="nav-link " href="../dashboard.php">
          <i class="bi bi-grid"></i>
          <span>Reporting & Analytics</span>
        </a>
      </li>

      <hr class="sidebar-divider">
      <li class="nav-item">
        <a class="nav-link collapsed" data-bs-target="#components-nav" data-bs-toggle="collapse" href="#">
          <i class="bi bi-menu-button-wide"></i><span>Asset Registry & Tagging </span><i class="bi bi-chevron-down ms-auto"></i>
        </a>
        <ul id="components-nav" class="nav-content collapse " data-bs-parent="#sidebar-nav">
          <li>
            <a href="components-spinners.html">
              <i class="bi bi-circle"></i><span>Spinners</span>
            </a>
          </li>
          <li>
            <a href="components-tooltips.html">
              <i class="bi bi-circle"></i><span>Tooltips</span>
            </a>
          </li>
        </ul>
      </li>

      <li class="nav-item">
        <a class="nav-link collapsed" data-bs-target="#forms-nav" data-bs-toggle="collapse" href="#">
          <i class="bi bi-journal-text"></i><span>Property Issuance & Acknowledgment</span><i class="bi bi-chevron-down ms-auto"></i>
        </a>
        <ul id="forms-nav" class="nav-content collapse " data-bs-parent="#sidebar-nav">
          <li>
            <a href="forms-elements.html">
              <i class="bi bi-circle"></i><span>Form Elements</span>
            </a>
          </li>
          <li>
            <a href="forms-layouts.html">
              <i class="bi bi-circle"></i><span>Form Layouts</span>
            </a>
          </li>
          <li>
            <a href="forms-editors.html">
              <i class="bi bi-circle"></i><span>Form Editors</span>
            </a>
          </li>
          <li>
            <a href="forms-validation.html">
              <i class="bi bi-circle"></i><span>Form Validation</span>
            </a>
          </li>
        </ul>
      </li>

      <li class="nav-item">
        <a class="nav-link collapsed" data-bs-target="#tables-nav" data-bs-toggle="collapse" href="#">
          <i class="bi bi-layout-text-window-reverse"></i><span>Supplies Inventory Management </span><i class="bi bi-chevron-down ms-auto"></i>
        </a>
        <ul id="tables-nav" class="nav-content collapse show" data-bs-parent="#sidebar-nav">
          <li>
            <a href="inventory.php" class="active">
              <i class="bi bi-circle"></i><span>Inventory</span>
            </a>
          </li>
        </ul>
      </li>

      <li class="nav-item">
        <a class="nav-link collapsed" data-bs-target="#charts-nav" data-bs-toggle="collapse" href="#">
          <i class="bi bi-bar-chart"></i><span>Custodian Assignment & Transfer</span><i class="bi bi-chevron-down ms-auto"></i>
        </a>
        <ul id="charts-nav" class="nav-content collapse " data-bs-parent="#sidebar-nav">
          <li>
            <a href="charts-chartjs.html">
              <i class="bi bi-circle"></i><span>Chart.js</span>
            </a>
          </li>
          <li>
            <a href="charts-apexcharts.html">
              <i class="bi bi-circle"></i><span>ApexCharts</span>
            </a>
          </li>
          <li>
            <a href="charts-echarts.html">
              <i class="bi bi-circle"></i><span>ECharts</span>
            </a>
          </li>
        </ul>
      </li>

      <li class="nav-item">
        <a class="nav-link collapsed" data-bs-target="#icons-nav" data-bs-toggle="collapse" href="#">
          <i class="bi bi-journal-text"></i><span>Preventive Maintenance Scheduling </span><i class="bi bi-chevron-down ms-auto"></i>
        </a>
        <ul id="icons-nav" class="nav-content collapse" data-bs-parent="#sidebar-nav">
          <li>
            <a href="schedule_maintenance.php">
              <i class="bi bi-circle"></i><span>Schedule Maintenance</span>
            </a>
          </li>
         <li>
            <a href="records_of_maintenance.php">
              <i class="bi bi-circle"></i><span>Maintenance Records</span>
            </a>
          </li>
        </ul>
      </li>

        <li class="nav-item">
        <a class="nav-link collapsed" data-bs-target="#tables-nav" data-bs-toggle="collapse" href="#">
          <i class="bi bi-layout-text-window-reverse"></i><span>Lost, Damaged, or Unserviceable Items</span><i class="bi bi-chevron-down ms-auto"></i>
        </a>
        <ul id="tables-nav" class="nav-content collapse " data-bs-parent="#sidebar-nav">
          <li>
            <a href="reporting_management.php">
              <i class="bi bi-circle"></i><span>Report Management</span>
            </a>
          </li>
          <li>
            <a href="tables-data.html">
              <i class="bi bi-circle"></i><span>Data Tables</span>
            </a>
          </li>
        </ul>
      </li>

    <li class="nav-item">
        <a class="nav-link collapsed" data-bs-target="#tables-nav" data-bs-toggle="collapse" href="#">
          <i class="bi bi-layout-text-window-reverse"></i><span>Property Audit & Physical Inventory</span><i class="bi bi-chevron-down ms-auto"></i>
        </a>
        <ul id="tables-nav" class="nav-content collapse " data-bs-parent="#sidebar-nav">
          <li>
            <a href="tables-general.html">
              <i class="bi bi-circle"></i><span>General Tables</span>
            </a>
          </li>
          <li>
            <a href="tables-data.html">
              <i class="bi bi-circle"></i><span>Data Tables</span>
            </a>
          </li>
        </ul>
      </li>

      <li class="nav-item">
        <a class="nav-link collapsed" data-bs-target="#tables-nav" data-bs-toggle="collapse" href="#">
          <i class="bi bi-layout-text-window-reverse"></i><span>Procurement Coordination</span><i class="bi bi-chevron-down ms-auto"></i>
        </a>
        <ul id="tables-nav" class="nav-content collapse " data-bs-parent="#sidebar-nav">
          <li>
            <a href="tables-general.html">
              <i class="bi bi-circle"></i><span>General Tables</span>
            </a>
          </li>
          <li>
            <a href="tables-data.html">
              <i class="bi bi-circle"></i><span>Data Tables</span>
            </a>
          </li>
        </ul>
      </li>
      <li class="nav-item">
        <a class="nav-link collapsed" data-bs-target="#tables-nav" data-bs-toggle="collapse" href="#">
          <i class="bi bi-layout-text-window-reverse"></i><span>User Roles & Access Control</span><i class="bi bi-chevron-down ms-auto"></i>
        </a>
        <ul id="tables-nav" class="nav-content collapse " data-bs-parent="#sidebar-nav">
          <li>
            <a href="tables-general.html">
              <i class="bi bi-circle"></i><span>General Tables</span>
            </a>
          </li>
          <li>
            <a href="tables-data.html">
              <i class="bi bi-circle"></i><span>Data Tables</span>
            </a>
          </li>
        </ul>
      </li>

      <hr class="sidebar-divider">

      <li class="nav-heading">Pages</li>

      <li class="nav-item">
        <a class="nav-link collapsed" href="users-profile.html">
          <i class="bi bi-person"></i>
          <span>Profile</span>
        </a>
      </li>

      <li class="nav-item">
        <a class="nav-link collapsed" href="pages-faq.html">
          <i class="bi bi-question-circle"></i>
          <span>F.A.Q</span>
        </a>
      </li>

      <li class="nav-item">
        <a class="nav-link collapsed" href="pages-contact.html">
          <i class="bi bi-envelope"></i>
          <span>Contact</span>
        </a>
      </li>

      <li class="nav-item">
        <a class="nav-link collapsed" href="pages-register.html">
          <i class="bi bi-card-list"></i>
          <span>Register</span>
        </a>
      </li>

      <li class="nav-item">
        <a class="nav-link collapsed" href="pages-login.html">
          <i class="bi bi-box-arrow-in-right"></i>
          <span>Login</span>
        </a>
      </li>

      <li class="nav-item">
        <a class="nav-link collapsed" href="pages-error-404.html">
          <i class="bi bi-dash-circle"></i>
          <span>Error 404</span>
        </a>
      </li>

      <li class="nav-item">
        <a class="nav-link collapsed" href="pages-blank.html">
          <i class="bi bi-file-earmark"></i>
          <span>Blank</span>
        </a>
      </li>

    </ul>

  </aside><!-- End Sidebar-->

  <main id="main" class="main">

    <div class="pagetitle">
      <h1>Supplies Inventory</h1>
      <nav>
        <ol class="breadcrumb">
          <li class="breadcrumb-item"><a href="dashboard.php">Home</a></li>
          <li class="breadcrumb-item">Supplies Inventory</li>
          <li class="breadcrumb-item active">Inventory</li>
        </ol>
      </nav>
    </div><!-- End Page Title -->


    <?php
        include '../connection.php'; 
        // Active assets (status: active > 0)
        $active_assets = 0;
        $result = $conn->query("SELECT COUNT(*) as total FROM bcp_sms4_asset WHERE active > 0");
        if ($result && $row = $result->fetch_assoc()) {
            $active_assets = $row['total'];
        }

        // Unassigned assets (quantity > 0 but not assigned)
        $unassigned_assets = 0;
        $result = $conn->query("
            SELECT COUNT(*) as total 
            FROM bcp_sms4_asset a
            WHERE a.active = 0 AND a.in_repair = 0 AND a.disposed = 0
        ");
        if ($result && $row = $result->fetch_assoc()) {
            $unassigned_assets = $row['total'];
        }

        // Disposed assets
        $disposed_assets = 0;
        $result = $conn->query("SELECT COUNT(*) as total FROM bcp_sms4_asset WHERE disposed > 0");
        if ($result && $row = $result->fetch_assoc()) {
            $disposed_assets = $row['total'];
        }

        // Need Repair (was Low Stock before)
        $need_repair = 0;
        $result = $conn->query("SELECT COUNT(*) as total FROM bcp_sms4_asset WHERE in_repair > 0");
        if ($result && $row = $result->fetch_assoc()) {
            $need_repair = $row['total'];
        }
        ?>




  <section class="section dashboard">
      <div class="row">

<!-- Active Assets -->
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
          <span class="text-success small">🟢 In service</span>
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
          <span class="text-warning small">🟡 Waiting assignment</span>
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
          <span class="text-danger small">🔴 Retired</span>
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
                    <th>Tag</th>
                    <th>Name</th>
                    <th>Category</th>
                    <th>Qty</th>
                    <th>Active</th>
                    <th>In Repair</th>
                    <th>Disposed</th>
                    <th>Purchase Date</th>
                  </tr>
                </thead>
                    <tbody>
                    <?php
                    $assets = $conn->query("SELECT * FROM bcp_sms4_asset ORDER BY id DESC");
                    if ($assets && $assets->num_rows > 0):
                        while ($row = $assets->fetch_assoc()):
                    ?>
                        <tr>
                            <td><?= $row['id'] ?></td>
                            <td><?= $row['asset_tag'] ?></td>
                            <td><?= $row['name'] ?></td>
                            <td><?= $row['category'] ?></td>
                            <td><?= $row['quantity'] ?></td>
                            <td><?= $row['active'] ?></td>
                            <td><?= $row['in_repair'] ?></td>
                            <td><?= $row['disposed'] ?></td>
                            <td><?= $row['purchase_date'] ?></td>
                        </tr>
                    <?php
                        endwhile;
                    else:
                    ?>
                        <tr><td colspan="9">No assets found</td></tr>
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
                    <th>Tag</th>
                    <th>Name</th>
                    <th>Category</th>
                    <th>Box</th>
                    <th>Quantity</th>
                    <th>Expiration</th>
                    <th>Added Date</th>
                  </tr>
                </thead>
              <tbody>
                    <?php
                    $consumables = $conn->query("SELECT * FROM bcp_sms4_consumable ORDER BY id DESC");
                    if ($consumables && $consumables->num_rows > 0):
                        while ($row = $consumables->fetch_assoc()):
                    ?>
                        <tr>
                            <td><?= $row['id'] ?></td>
                            <td><?= $row['asset_tag'] ?></td>
                            <td><?= $row['name'] ?></td>
                            <td><?= $row['category'] ?></td>
                            <td><?= $row['box'] ?></td>
                            <td><?= $row['quantity'] ?></td>
                            <td><?= $row['expiration'] ?></td>
                            <td><?= $row['add_date'] ?></td>
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