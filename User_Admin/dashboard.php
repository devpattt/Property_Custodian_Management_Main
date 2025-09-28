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
  include '../components/nav-bar.php';
  include 'dashboard_stats.php';
?>
  <main id="main" class="main">

    <div class="pagetitle">
      <h1>Dashboard</h1>
      <nav>
        <ol class="breadcrumb">
          <li class="breadcrumb-item"><a href="dashboard.php">Home</a></li>
          <li class="breadcrumb-item active">Dashboard</li>
        </ol>
      </nav>
    </div>

    <section class="section dashboard">
      <div class="row">
        <div class="col-lg-8">
          <div class="row">

            <!--Card 1-->
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
                  <h5 class="card-title">Pending Orders</h5>

                  <div class="d-flex align-items-center">
                    <div class="card-icon rounded-circle d-flex align-items-center justify-content-center">
                      <i class="bi bi-box"></i>
                    </div>
                    <div class="ps-3">
                      <h6><?php echo $pending; ?></h6>
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
                  <h5 class="card-title">In Transit</h5>

                  <div class="d-flex align-items-center">
                    <div class="card-icon rounded-circle d-flex align-items-center justify-content-center">
                      <i class="bi bi-truck"></i>
                    </div>
                    <div class="ps-3">
                      <h6><?php echo $transit; ?></h6>
                    </div>
                  </div>
                </div>

              </div>
            </div>

            <!--Card 3 -->
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
                  <h5 class="card-title">Low Stocks</h5>

                  <div class="d-flex align-items-center">
                    <div class="card-icon rounded-circle d-flex align-items-center justify-content-center">
                      <i class="bi bi-exclamation-triangle-fill"></i>
                    </div>
                    <div class="ps-3">
                      <h6><?php echo $low_stocks; ?></h6>
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
                  <h5 class="card-title">Reports <span>/Today</span></h5>
                  <div id="reportsChart"></div>
                  
                      <script>
                      document.addEventListener("DOMContentLoaded", () => {
                          fetch('fetch_reports.php')
                          .then(res => res.json())
                          .then(data => {
                              new ApexCharts(document.querySelector("#reportsChart"), {
                                  series: [
                                      { name: 'Incident Reports', data: data.incidentReports },
                                      { name: 'Assigned Items', data: data.assignedItems },
                                      { name: 'Unassigned Items', data: data.unassignedItems }
                                  ],
                                  chart: { height: 350, type: 'area', toolbar: { show: false } },
                                  markers: { size: 4 },
                                  colors: ['#ff771d', '#2eca6a', '#4154f1'],
                                  fill: {
                                      type: "gradient",
                                      gradient: { shadeIntensity: 1, opacityFrom: 0.3, opacityTo: 0.4, stops: [0, 90, 100] }
                                  },
                                  dataLabels: { enabled: false },
                                  stroke: { curve: 'smooth', width: 2 },
                                  xaxis: { type: 'datetime', categories: data.dates },
                                  tooltip: { x: { format: 'dd/MM/yy' } }
                              }).render();
                          });
                      });
                      </script>


                </div>
              </div>
            </div>

            <!-- Most Issued Items -->
            <div class="col-12">
              <div class="card top-selling overflow-auto">

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

                <div class="card-body pb-0">
                  <h5 class="card-title">Most Issued Items</h5>

                <?php
                  $top_items = include 'fetch_top_items.php';
                  ?>

                  <table class="table table-borderless">
                    <thead>
                      <tr>
                        <th scope="col">Product</th>
                        <th scope="col">Unit</th>
                        <th scope="col">Quantity Issued</th>
                        <th scope="col">Type</th>
                      </tr>
                    </thead>
                    <tbody>
                      <?php foreach($top_items as $item): ?>
                        <tr>
                          <td><?php echo htmlspecialchars($item['name']); ?></td>
                          <td><?php echo htmlspecialchars($item['unit']); ?></td>
                          <td class="fw-bold"><?php echo $item['issued']; ?></td>
                          <td><?php echo $item['type']; ?></td>
                        </tr>
                      <?php endforeach; ?>
                    </tbody>
                  </table>
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

        $sql1 = "SELECT id, asset, type, frequency, personnel, start_date, status 
                FROM bcp_sms4_scheduling";
        $result1 = $conn->query($sql1);

        $events = [];
        while ($row = $result1->fetch_assoc()) {
            $events[] = [
                "date" => $row['start_date'],
                "title" => $row['asset'],
                "details" => "Type: {$row['type']}<br>Frequency: {$row['frequency']}<br>Personnel: {$row['personnel']}<br>Status: <b>{$row['status']}</b>",
                "category" => "maintenance"
            ];
        }

        $sql2 = "SELECT id, audit_date, department_code, custodian, status 
                FROM bcp_sms4_audit";
        $result2 = $conn->query($sql2);

        while ($row = $result2->fetch_assoc()) {
            $events[] = [
                "date" => $row['audit_date'],
                "title" => "Audit - {$row['department_code']}",
                "details" => "Department: {$row['department_code']}<br>Custodian: {$row['custodian']}<br>Status: <b>{$row['status']}</b>",
                "category" => "audit"
            ];
        }
        ?>

        <!-- Logbi -->
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

          <div class="card-body pb-0">
            <h5 class="card-title">Stocks</h5>
            <div id="trafficChart" style="min-height: 400px;" class="echart"></div>
            <script>
              document.addEventListener("DOMContentLoaded", () => {
                fetch("get_stocks.php")
                  .then(response => response.json())
                  .then(data => {
                    echarts.init(document.querySelector("#trafficChart")).setOption({
                      tooltip: {
                        trigger: 'item'
                      },
                      legend: {
                        top: '5%',
                        left: 'center'
                      },
                      series: [{
                        name: 'Stocks',
                        type: 'pie',
                        radius: ['40%', '70%'],
                        avoidLabelOverlap: false,
                        label: { show: false },       
                        labelLine: { show: false },    
                        data: [
                          { value: data.assets, name: 'Non-Consumable Assets' },
                          { value: data.consumables, name: 'Consumable Assets' }
                        ]
                      }]
                    });
                  })
                  .catch(error => console.error("Error fetching stocks:", error));
              });
            </script>
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
          div.style.color = "#fff";
          div.style.borderRadius = "50%";
          div.style.cursor = "pointer";

          const tooltip = document.createElement("div");
          tooltip.classList.add("tooltip-box");

          if (dayEvents.some(ev => ev.category === "maintenance")) {
            div.style.background = "#0dcaf0"; 
          }
          if (dayEvents.some(ev => ev.category === "audit")) {
            div.style.background = "#fd7e14";
          }

          dayEvents.forEach(ev => {
            tooltip.innerHTML += `<strong>${ev.title}</strong><br>${ev.details}<hr>`;
          });

          div.appendChild(tooltip);
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