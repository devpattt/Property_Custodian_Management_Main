<?php
session_start();  
?>

<!DOCTYPE html>
<html lang="en">

<head>
  <meta charset="utf-8">
  <meta content="width=device-width, initial-scale=1.0" name="viewport">
  <title>Teacher Dashboard</title>
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
    include  "../components/nav-bar.php";
  ?>
  <main id="main" class="main">

    <div class="pagetitle">
      <h1>Teacher Dashboard</h1>
      <nav>
        <ol class="breadcrumb">
          <li class="breadcrumb-item"><a href="<?=BASE_URL?>User_Teachers/dashboard_teacher.php">Home</a></li>
          <li class="breadcrumb-item active">Teacher Dashboard</li>
        </ol>
      </nav>
    </div>

    <section class="section dashboard">
      <div class="row">
        <div class="col-lg-12">
          <div class="row">

            <!-- Card 1 -->
            <div class="col-xxl-4 col-md-6">
              <div class="card info-card sales-card">

                <div class="filter">
                  <a class="icon" href="#" data-bs-toggle="dropdown"><i class="bi bi-three-dots"></i></a>
                  <ul class="dropdown-menu dropdown-menu-end dropdown-menu-arrow">
                    <li class="dropdown-header text-start">
                      <h6>Filter</h6>
                    </li>
                    <li><a class="dropdown-item" href="#">Today</a></li>
                    <li><a class="dropdown-item" href="#">This Month</a></li>
                    <li><a class="dropdown-item" href="#">This Year</a></li>
                  </ul>
                </div>

                <div class="card-body">
                  <h5 class="card-title">Pending Report</h5>

                  <div class="d-flex align-items-center">
                    <div class="card-icon rounded-circle d-flex align-items-center justify-content-center">
                      <i class="bi bi-flag"></i>
                    </div>
                    <div class="ps-3">
                      <h6 id="pendingCount">0</h6>
                    </div>
                  </div>
                </div>

              </div>
            </div>

            <!--Card 2 -->
            <div class="col-xxl-4 col-md-6">
              <div class="card info-card revenue-card">

                <div class="filter">
                  <a class="icon" href="#" data-bs-toggle="dropdown"><i class="bi bi-three-dots"></i></a>
                  <ul class="dropdown-menu dropdown-menu-end dropdown-menu-arrow">
                    <li class="dropdown-header text-start">
                      <h6>Filter</h6>
                    </li>

                    <li><a class="dropdown-item" href="#">Today</a></li>
                    <li><a class="dropdown-item" href="#">This Month</a></li>
                    <li><a class="dropdown-item" href="#">This Year</a></li>
                  </ul>
                </div>

                <div class="card-body">
                  <h5 class="card-title">In-Progress Report</h5>

                  <div class="d-flex align-items-center">
                    <div class="card-icon rounded-circle d-flex align-items-center justify-content-center">
                      <i class="bi bi-arrow-clockwise"></i>
                    </div>
                    <div class="ps-3">
                      <h6 id="inProgressCount">0</h6>
                    </div>
                  </div>
                </div>

              </div>
            </div>

            <!-- Card 3 -->
            <div class="col-xxl-4 col-xl-12">

              <div class="card info-card customers-card">

                <div class="filter">
                  <a class="icon" href="#" data-bs-toggle="dropdown"><i class="bi bi-three-dots"></i></a>
                  <ul class="dropdown-menu dropdown-menu-end dropdown-menu-arrow">
                    <li class="dropdown-header text-start">
                      <h6>Filter</h6>
                    </li>

                    <li><a class="dropdown-item" href="#">Today</a></li>
                    <li><a class="dropdown-item" href="#">This Month</a></li>
                    <li><a class="dropdown-item" href="#">This Year</a></li>
                  </ul>
                </div>

                <div class="card-body">
                  <h5 class="card-title">Resolved Report</h5>

                  <div class="d-flex align-items-center">
                    <div class="card-icon rounded-circle d-flex align-items-center justify-content-center">
                      <i class="bi bi-check-circle"></i>
                    </div>
                    <div class="ps-3">
                      <h6 id="resolvedCount">0</h6>
                    </div>
                  </div>
                </div>
              </div>

            </div>
                <script>
                  fetch("Reports/fetch_report_counts.php")
                    .then(response => response.json())
                    .then(data => {
                      document.getElementById("pendingCount").innerText = data["Pending"] ?? 0;
                      document.getElementById("inProgressCount").innerText = data["In-Progress"] ?? 0;
                      document.getElementById("resolvedCount").innerText = data["Resolved"] ?? 0;
                    })
                    .catch(error => console.error("Error fetching counts:", error));

                  fetch("Reports/fetch_reports_by_type.php")
                    .then(response => response.json())
                    .then(data => {
                    })
                    .catch(error => console.error("Chart fetch error:", error));
                </script>

            <!-- Reports -->
            <div class="col-12">
              <div class="card">

                <div class="filter">
                  <a class="icon" href="#" data-bs-toggle="dropdown"><i class="bi bi-three-dots"></i></a>
                  <ul class="dropdown-menu dropdown-menu-end dropdown-menu-arrow">
                    <li class="dropdown-header text-start">
                      <h6>Filter</h6>
                    </li>
                    <li><a class="dropdown-item" href="#">Today</a></li>
                    <li><a class="dropdown-item" href="#">This Month</a></li>
                    <li><a class="dropdown-item" href="#">This Year</a></li>
                  </ul>
                </div>
                <div class="card-body">
                  <h5 class="card-title">Reports <span>/Today</span></h5>

                  <div id="reportsChart"></div>
                <script>
                  fetch("Reports/fetch_report_counts.php")
                    .then(response => response.json())
                    .then(data => {
                      document.getElementById("pendingCount").innerText = data["Pending"] ?? 0;
                      document.getElementById("inProgressCount").innerText = data["In-Progress"] ?? 0;
                      document.getElementById("resolvedCount").innerText = data["Resolved"] ?? 0;
                    })
                    .catch(error => console.error("Error fetching counts:", error));

                  fetch("Reports/fetch_reports_by_type.php")
                    .then(response => response.json())
                    .then(data => {
                      new ApexCharts(document.querySelector("#reportsChart"), {
                        series: data.series,
                        chart: {
                          height: 350,
                          type: 'area',
                          toolbar: { show: false }
                        },
                        markers: { size: 4 },
                        colors: ['#4154f1', '#ff771d', '#2eca6a'],
                        fill: {
                          type: "gradient",
                          gradient: {
                            shadeIntensity: 1,
                            opacityFrom: 0.3,
                            opacityTo: 0.4,
                            stops: [0, 90, 100]
                          }
                        },
                        dataLabels: { enabled: false },
                        stroke: { curve: 'smooth', width: 2 },
                        xaxis: {
                          categories: data.dates,
                          type: 'datetime'
                        },
                        tooltip: {
                          x: { format: 'yyyy-MM-dd' }
                        }
                      }).render();
                    })
                    .catch(error => console.error("Chart fetch error:", error));

                  </script>
                </div>

              </div>
            </div>
        </div>  
      </div>
    </section>
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
  
  <script>
  document.addEventListener("DOMContentLoaded", function() {
      const activityContainer = document.querySelector('.activity');

      fetch('fetch_activity.php')
      .then(response => response.json())
      .then(data => {
          activityContainer.innerHTML = ''; 

          if(data.length === 0){
              activityContainer.innerHTML = '<div class="activity-item d-flex">No recent activity.</div>';
              return;
          }
          data.forEach(activity => {
              const diff = Math.floor((new Date() - new Date(activity.created_at)) / 1000);
              let timeLabel = '';
              if(diff < 3600) timeLabel = Math.floor(diff/60) + ' min';
              else if(diff < 86400) timeLabel = Math.floor(diff/3600) + ' hrs';
              else timeLabel = Math.floor(diff/86400) + ' day(s)';

              let badgeClass = 'bg-secondary';
              switch(activity.module){
                  case 'Maintenance': badgeClass='bg-warning'; break;
                  case 'Assets': badgeClass='bg-primary'; break;
                  case 'Supplies': badgeClass='bg-success'; break;
                  case 'Audit': badgeClass='bg-info'; break;
                  case 'Procurement': badgeClass='bg-secondary'; break;
                  case 'User Roles': badgeClass='bg-dark'; break;
              }

              const div = document.createElement('div');
              div.classList.add('activity-item', 'd-flex');
              div.innerHTML = `
                  <div class="activite-label">${timeLabel}</div>
                  <i class='bi bi-circle-fill activity-badge ${badgeClass} align-self-start'></i>
                  <div class="activity-content">${activity.description}</div>
              `;
              activityContainer.appendChild(div);
          });
      })
      .catch(err => console.error('Error fetching activity:', err));
  });
  </script>
</body>
</html>