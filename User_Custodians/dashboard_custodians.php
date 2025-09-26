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
  <link href="../assets/css/calendar.css" rel="stylesheet">
</head>

<body>
  <?php
    include  "../components/nav-bar.php";
  ?>
  <main id="main" class="main">

    <div class="pagetitle">
      <h1>Dashboard</h1>
      <nav>
        <ol class="breadcrumb">
          <li class="breadcrumb-item"><a href="dashboard_custodians.php">Home</a></li>
          <li class="breadcrumb-item active">Dashboard</li>
        </ol>
      </nav>
    </div>

    <section class="section dashboard">
      <div class="row">
        <div class="col-lg-8">
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
                  <h5 class="card-title">In Progress Reports</h5>

                  <div class="d-flex align-items-center">
                    <div class="card-icon rounded-circle d-flex align-items-center justify-content-center">
                      <i class="bi bi-cart"></i>
                    </div>
                    <div class="ps-3">
                      <h6>145</h6>
                    </div>
                  </div>
                </div>

              </div>
            </div>

            <!-- Card 2 -->
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
                  <h5 class="card-title">Completed Assignment<span>| This Month</span></h5>

                  <div class="d-flex align-items-center">
                    <div class="card-icon rounded-circle d-flex align-items-center justify-content-center">
                      <i class="bi bi-currency-dollar"></i>
                    </div>
                    <div class="ps-3">
                      <h6>3,264</h6>
                    </div>
                  </div>
                </div>

              </div>
            </div>

            <!-- Card 2-->
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
                  <h5 class="card-title">Maintenance Schedules</h5>

                  <div class="d-flex align-items-center">
                    <div class="card-icon rounded-circle d-flex align-items-center justify-content-center">
                      <i class="bi bi-people"></i>
                    </div>
                    <div class="ps-3">
                      <h6>1244</h6>
                    </div>
                  </div>

                </div>
              </div>

            </div>

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
                  <h5 class="card-title">Reports <span> | Today</span></h5>

                  <!-- Line Chart -->
                  <div id="reportsChart"></div>

                  <script>
                    document.addEventListener("DOMContentLoaded", () => {
                      new ApexCharts(document.querySelector("#reportsChart"), {
                        series: [{
                          name: 'Sales',
                          data: [31, 40, 28, 51, 42, 82, 56],
                        }, {
                          name: 'Revenue',
                          data: [11, 32, 45, 32, 34, 52, 41]
                        }, {
                          name: 'Customers',
                          data: [15, 11, 32, 18, 9, 24, 11]
                        }],
                        chart: {
                          height: 350,
                          type: 'area',
                          toolbar: {
                            show: false
                          },
                        },
                        markers: {
                          size: 4
                        },
                        colors: ['#4154f1', '#2eca6a', '#ff771d'],
                        fill: {
                          type: "gradient",
                          gradient: {
                            shadeIntensity: 1,
                            opacityFrom: 0.3,
                            opacityTo: 0.4,
                            stops: [0, 90, 100]
                          }
                        },
                        dataLabels: {
                          enabled: false
                        },
                        stroke: {
                          curve: 'smooth',
                          width: 2
                        },
                        xaxis: {
                          type: 'datetime',
                          categories: ["2018-09-19T00:00:00.000Z", "2018-09-19T01:30:00.000Z", "2018-09-19T02:30:00.000Z", "2018-09-19T03:30:00.000Z", "2018-09-19T04:30:00.000Z", "2018-09-19T05:30:00.000Z", "2018-09-19T06:30:00.000Z"]
                        },
                        tooltip: {
                          x: {
                            format: 'dd/MM/yy HH:mm'
                          },
                        }
                      }).render();
                    });
                  </script>
                </div>

              </div>
            </div>
         </div>
        </div>
        <div class="col-lg-4">

     
        <!-- Calendar  -->
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
              <h5 class="card-title">Calendar <span>| Maintenance Schedules</span></h5>

              <div class="calendar-card">
                <div class="calendar-header d-flex justify-content-between align-items-center mb-2">
                  <button class="btn btn-sm btn-outline-secondary" onclick="prevMonth()">&#8592;</button>
                  <div id="monthYear" class="fw-bold"></div>
                  <button class="btn btn-sm btn-outline-secondary" onclick="nextMonth()">&#8594;</button>
                </div>
                <div class="calendar-grid d-grid text-center" id="calendar" 
                    style="grid-template-columns: repeat(7, 1fr); gap: 5px;"></div>
              </div>
            </div>
          </div>

          <?php
          include '../connection.php';

          $sql = "SELECT id, asset, type, frequency, personnel, start_date, status 
                  FROM bcp_sms4_scheduling";
          $result = $conn->query($sql);

          $events = [];
          while ($row = $result->fetch_assoc()) {
              $events[] = [
                  "date" => $row['start_date'],
                  "asset" => $row['asset'],
                  "type" => $row['type'],
                  "frequency" => $row['frequency'],
                  "personnel" => $row['personnel'],
                  "status" => $row['status']
              ];
          }
          ?>
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

   <script>
    const calendar = document.getElementById("calendar");
    const monthYear = document.getElementById("monthYear");
    let currentDate = new Date();

    const events = <?php echo json_encode($events); ?>;

    function renderCalendar(date) {
      calendar.innerHTML = "";

      const year = date.getFullYear();
      const month = date.getMonth();
      const firstDay = new Date(year, month, 1);
      const lastDay = new Date(year, month + 1, 0);
      const firstDayIndex = firstDay.getDay();
      const daysInMonth = lastDay.getDate();

      const monthNames = [
        "January","February","March","April","May","June",
        "July","August","September","October","November","December"
      ];
      monthYear.innerText = `${monthNames[month]} ${year}`;

      const weekdays = ["Sun","Mon","Tue","Wed","Thu","Fri","Sat"];
      weekdays.forEach(day => {
        const div = document.createElement("div");
        div.textContent = day;
        div.classList.add("fw-bold");
        calendar.appendChild(div);
      });

      for (let i = 0; i < firstDayIndex; i++) {
        const empty = document.createElement("div");
        calendar.appendChild(empty);
      }

      for (let d = 1; d <= daysInMonth; d++) {
        const div = document.createElement("div");
        div.textContent = d;
        div.classList.add("p-2", "calendar-day");

        const today = new Date();
        const dateStr = `${year}-${String(month + 1).padStart(2,'0')}-${String(d).padStart(2,'0')}`;
        const dayEvents = events.filter(ev => ev.date === dateStr);

        if (d === today.getDate() && month === today.getMonth() && year === today.getFullYear()) {
          div.style.background = "#198754";
          div.style.color = "#fff";
          div.style.borderRadius = "50%";
          div.style.fontWeight = "bold";
        }

        if (dayEvents.length > 0) {
          div.style.background = "#0d6efd";
          div.style.color = "#fff";
          div.style.borderRadius = "50%";
          div.style.cursor = "pointer";

          const tooltip = document.createElement("div");
          tooltip.classList.add("tooltip-box");

          dayEvents.forEach(ev => {
            tooltip.innerHTML += `
              <strong>${ev.asset}</strong>
              Type: ${ev.type}<br>
              Frequency: ${ev.frequency}<br>
              Personnel: ${ev.personnel}<br>
              Status: <b>${ev.status}</b><hr>
            `;
          });

          div.appendChild(tooltip);
        }

        if (d === today.getDate() && month === today.getMonth() && year === today.getFullYear()) {
          div.style.background = "#198754";
          div.style.color = "#fff";
        }

        calendar.appendChild(div);
      }
    }

    function prevMonth() {
      currentDate.setMonth(currentDate.getMonth() - 1);
      renderCalendar(currentDate);
    }

    function nextMonth() {
      currentDate.setMonth(currentDate.getMonth() + 1);
      renderCalendar(currentDate);
    }

    renderCalendar(currentDate);
  </script>
</body>
</html>