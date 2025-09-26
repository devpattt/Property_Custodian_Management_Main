<?php
session_start();
?>
<!DOCTYPE html>
<html lang="en">

<head>
  <meta charset="utf-8">
  <meta content="width=device-width, initial-scale=1.0" name="viewport">
  <title>Dashboard</title>
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
    include '../../components/nav-bar.php'
  ?>

  <main id="main" class="main">

    <div class="pagetitle">
      <h1>Maintenance Records</h1>
      <nav>
        <ol class="breadcrumb">
          <li class="breadcrumb-item"><a href="<?=BASE_URL?>User_Admin/dashboard.php">Home</a></li>
          <li class="breadcrumb-item">Preventive Maintenance Scheduling</li>
          <li class="breadcrumb-item active">Maintenance Records</li>
        </ol>
      </nav>
    </div>

    <!-- Maintenance Table -->
    <?php
    $host = 'localhost';
    $dbname = 'bcp_sms4_pcm';
    $username = 'root'; 
    $password = ''; 

    try {
        $pdo = new PDO("mysql:host=$host;dbname=$dbname", $username, $password);
        $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
        $stmt = $pdo->query("SELECT asset, type, frequency, personnel, start_date FROM bcp_sms4_scheduling ORDER BY start_date DESC");
        $schedules = $stmt->fetchAll(PDO::FETCH_ASSOC);
        foreach ($schedules as &$schedule) {
            $startDate = new DateTime($schedule['start_date']);
            $today = new DateTime();
            if ($startDate > $today) {
                $schedule['status'] = 'Scheduled';
            } elseif ($startDate <= $today) {
                $daysDiff = $today->diff($startDate)->days;
                if ($daysDiff > 7) {
                    $schedule['status'] = 'Completed';
                } else {
                    $schedule['status'] = 'In Progress';
                }
            }
        }
        
    } catch(PDOException $e) {
        echo "Connection failed: " . $e->getMessage();
        $schedules = []; 
    }
    ?>

    <section class="section">
        <div class="row">
            <div class="col-lg-12">
                <div class="card">
                    <div class="card-body">
                        <h5 class="card-title"></h5>
                        <p>
                            <em>Below is a list of all scheduled and completed maintenance activities.
                            You can <b>search, sort, and filter</b> the records to quickly find details
                            about specific assets, service providers, or dates.
                            Use this table to track the status of preventive maintenance tasks and
                            review past maintenance history.</em>
                        </p>
                        <br>
                        <table class="table datatable">
                            <thead>
                                <tr>
                                    <th><b>A</b>sset</th>
                                    <th>Type</th>
                                    <th>Frequency</th>
                                    <th>Personnel</th>
                                    <th data-type="date" data-format="YYYY/MM/DD">Start Date</th>
                                    <th>Status</th>
                                </tr>
                            </thead>
                            <tbody>
                                <?php foreach ($schedules as $schedule): ?>
                                <tr>
                                    <td><?php echo htmlspecialchars($schedule['asset']); ?></td>
                                    <td><?php echo htmlspecialchars($schedule['type']); ?></td>
                                    <td><?php echo htmlspecialchars($schedule['frequency']); ?></td>
                                    <td><?php echo htmlspecialchars($schedule['personnel']); ?></td>
                                    <td><?php echo date('Y/m/d', strtotime($schedule['start_date'])); ?></td>
                                    <td>
                                        <?php 
                                        $statusClass = '';
                                        switch($schedule['status']) {
                                            case 'Completed':
                                                $statusClass = 'badge bg-success';
                                                break;
                                            case 'In Progress':
                                                $statusClass = 'badge bg-warning';
                                                break;
                                            case 'Scheduled':
                                                $statusClass = 'badge bg-primary';
                                                break;
                                            default:
                                                $statusClass = 'badge bg-secondary';
                                        }
                                        ?>
                                        <span class="<?php echo $statusClass; ?>"><?php echo htmlspecialchars($schedule['status']); ?></span>
                                    </td>
                                </tr>
                                <?php endforeach; ?>
                                <?php if (empty($schedules)): ?>
                                <tr>
                                    <td colspan="6" class="text-center">No maintenance schedules found</td>
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