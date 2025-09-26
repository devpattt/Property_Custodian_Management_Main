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
          <li class="breadcrumb-item"><a href="<?=BASE_URL?>User_Admin/users/users.php">User Roles & Access Control</a></li>
          <li class="breadcrumb-item active">Roles & Access Control</li>
        </ol>
      </nav>
    </div><!-- End Page Title -->


    <!-- Maintenance Table -->
    <?php
    $host = 'localhost';
    $dbname = 'bcp_sms4_pcm';
    $username = 'root'; 
    $password = ''; 

    try {
        $pdo = new PDO("mysql:host=$host;dbname=$dbname", $username, $password);
        $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
        $stmt = $pdo->query("SELECT user_type, username, fullname, email FROM bcp_sms4_admins ORDER BY id DESC");
        $schedules = $stmt->fetchAll(PDO::FETCH_ASSOC);
        
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
                            <em>Below is a list of all registered administrators. 
                            You can <b>search, sort, and filter</b> the records to quickly find details 
                            such as user type, name, or email. 
                            Use this table to see all the user access and keep track of admin accounts.</em>
                        </p>

                        <br>
                        <table class="table datatable">
                            <thead>
                                <tr>
                                    <th>User Type</th>
                                    <th>Username</th>
                                    <th>Fullname</th>
                                    <th>Email</th>
                                </tr>
                            </thead>
                            <tbody>
                                <?php foreach ($schedules as $schedule): ?>
                                <tr>
                                    <td><?php echo htmlspecialchars($schedule['user_type']); ?></td>
                                    <td><?php echo htmlspecialchars($schedule['username']); ?></td>
                                    <td><?php echo htmlspecialchars($schedule['fullname']); ?></td>
                                    <td><?php echo htmlspecialchars($schedule['email']); ?></td>
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