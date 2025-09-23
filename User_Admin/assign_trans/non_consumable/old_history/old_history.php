<?php
include "get_rows.php"; // get total rows

// Old history
$sql1 = "SELECT * FROM bcp_sms4_assign_old_history $where ORDER BY reference_no DESC";
$result1 = $conn->query($sql1);
$total_old = $result1->num_rows;

// Non-consumable Added history
$sql2 = "SELECT * FROM bcp_sms4_assign_non_add_hstry $where ORDER BY reference_no DESC";
$result2 = $conn->query($sql2);
$total_non_add = $result2->num_rows;
?>
<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <title>Custodian History</title>
  <link rel="stylesheet" href="../../../../css/table_size.css">
  <link rel="stylesheet" href="../../../../css/asset_reg/list_assets.css">
  <script src="../../../../js/assign_trans/history/history.js"></script>
  <style>
    .filter-links { margin: 10px 0; }
    .filter-links a, .filter-links button {
      margin-right: 10px;
      padding: 6px 12px;
      background: #047f04;
      color: white;
      border-radius: 5px;
      text-decoration: none;
      font-size: 14px;
      cursor: pointer;
    }
    .filter-links a:hover, .filter-links button:hover { background: #047f04; }
    .hidden-col { display: none; opacity: 0; max-width: 0; overflow: hidden; white-space: nowrap; transition: all 0.4s ease; }
    .hidden-col.show { display: table-cell; opacity: 1; max-width: 200px; }
    .toggle-arrow { cursor: pointer; margin-left: 5px; font-weight: bold; display: inline-block; transition: transform 0.3s ease, color 0.3s ease; color: #444; }
    .toggle-arrow:hover { color: #047f04; transform: scale(1.2); }
    /* Tabs */
    .tabs { margin: 20px 0; }
    .tabs button {
      margin-right: 10px;
      padding: 6px 12px;
      background: #444;
      color: white;
      border-radius: 5px;
      border: none;
      cursor: pointer;
    }
    .tabs button.active { background: #047f04; }
    .tab-content { display: none; }
    .tab-content.active { display: block; }
  </style>
</head>
<body>
  <div class="container">
    <h2>Custodian History</h2>

    <!-- ✅ Filter Links -->
    <div class="filter-links">
      <a href="?filter=day">Today</a>
      <a href="?filter=week">This Week</a>
      <a href="?filter=month&month=<?=date("m")?>&year=<?=date("Y")?>">This Month</a>
      <a href="?filter=year&year=<?=date("Y")?>">This Year</a>

      <!-- Month/Year Selector -->
      <form method="GET">
        <input type="hidden" name="filter" value="month">
        <label for="month">Month:</label>
        <select name="month" id="month">
          <?php for ($m = 1; $m <= 12; $m++): ?>
            <option value="<?= $m ?>" <?= (isset($_GET['month']) && $_GET['month'] == $m) ? 'selected' : '' ?>>
              <?= date("F", mktime(0, 0, 0, $m, 1)) ?>
            </option>
          <?php endfor; ?>
        </select>
        <label for="year">Year:</label>
        <select name="year" id="year">
          <?php for ($y = date("Y"); $y >= 2000; $y--): ?>
            <option value="<?= $y ?>" <?= (isset($_GET['year']) && $_GET['year'] == $y) ? 'selected' : '' ?>>
              <?= $y ?>
            </option>
          <?php endfor; ?>
        </select>
        <button type="submit">Filter</button>
      </form>

      <form method="GET">
        <input type="hidden" name="filter" value="year">
        <label for="year">Select Year:</label>
        <select name="year" id="year">
          <?php for ($y = date("Y"); $y >= 2000; $y--): ?>
            <option value="<?= $y ?>" <?= (isset($_GET['year']) && $_GET['year'] == $y) ? 'selected' : '' ?>>
              <?= $y ?>
            </option>
          <?php endfor; ?>
        </select>
        <button type="submit">Filter</button>
      </form>
    </div>

    <input type="text" id="search" placeholder="Search..." onkeyup="searchTable()">
    <div class="card">
      <h2 id="totalRows"><?= $total_old ?> Total Rows</h2>
    </div>

    <!-- ✅ Tabs -->
    <div class="tabs">
      <button class="active" onclick="showTab('old', this)">Old History</button>
      <button onclick="showTab('nonadd', this)">Non-Consumable Added History</button>
    </div>

    <!-- ✅ Old History Table -->
    <div id="old" class="tab-content active">
      <p>Transfered History</p>
      <table>
        <thead>
          <tr>
            <th>Reference No</th>
            <th>Equipment ID</th>
            <th>Equipment Name</th>
            <th>Old Quantity</th>
            <th>Transfer Quantity</th>
            <th>Custodian ID</th>
            <th>Custodian Name</th>
            <th>
              Department 
              <span class="toggle-arrow" onclick="toggleColumns(this)">&#9654;</span>
            </th>
            <th class="hidden-col">New Custodian ID</th>
            <th class="hidden-col">New Custodian Name</th>
            <th class="hidden-col">New Department</th>
            <th class="hidden-col">Assigned Date</th>
            <th class="hidden-col">End Date</th>
            <th class="hidden-col">Remarks</th>
            <th class="hidden-col">Assigned By</th>
          </tr>
        </thead>
        <tbody>
          <?php while($row = $result1->fetch_assoc()): ?>
            <tr>
              <td><?= $row['reference_no'] ?></td>
              <td><?= $row['equipment_id'] ?></td>
              <td><?= $row['equipment_name'] ?></td>
              <td><?= $row['quantity'] ?></td>
              <td><?= $row['transfer_quan'] ?></td>
              <td><?= $row['custodian_id'] ?></td>
              <td><?= $row['custodian_name'] ?></td>
              <td><?= $row['department_code'] ?></td>
              <td class="hidden-col"><?= $row['new_id'] ?></td>
              <td class="hidden-col"><?= $row['new_name'] ?></td>
              <td class="hidden-col"><?= $row['new_dep'] ?></td>
              <td class="hidden-col"><?= $row['assign_date'] ?></td>
              <td class="hidden-col"><?= $row['end_date'] ?></td>
              <td class="hidden-col"><?= $row['remarks'] ?></td>
              <td class="hidden-col"><?= $row['assign_by'] ?></td>
            </tr>
          <?php endwhile; ?>
        </tbody>
      </table>
    </div>

    <!-- ✅ Non-Consumable Added History Table -->
    <div id="nonadd" class="tab-content">
      <p>Non-Consumable Added History</p>
      <table>
        <thead>
          <tr>
            <th>Reference No</th>
            <th>Equipment ID</th>
            <th>Equipment Name</th>
            <th>Category</th>
            <th>Quantity</th>
            <th>Custodian ID</th>
            <th>Custodian Name</th>
            <th>Department</th>
            <th>Assigned Date</th>
            <th>Remarks</th>
            <th>Assigned By</th>
          </tr>
        </thead>
        <tbody>
          <?php while($row = $result2->fetch_assoc()): ?>
            <tr>
              <td><?= $row['reference_no'] ?></td>
              <td><?= $row['equipment_id'] ?></td>
              <td><?= $row['equipment_name'] ?></td>
              <td><?= $row['category'] ?></td>
              <td><?= $row['quantity'] ?></td>
              <td><?= $row['custodian_id'] ?></td>
              <td><?= $row['custodian_name'] ?></td>
              <td><?= $row['department_code'] ?></td>
              <td><?= $row['assigned_date'] ?></td>
              <td><?= $row['remarks'] ?></td>
              <td><?= $row['assigned_by'] ?></td>
            </tr>
          <?php endwhile; ?>
        </tbody>
      </table>
    </div>
  </div>

  <script>
    function toggleColumns(el) {
      let table = el.closest("table");
      let hiddenCells = table.querySelectorAll(".hidden-col");
      let isHidden = !hiddenCells[0].classList.contains("show");

      hiddenCells.forEach(cell => {
          if (isHidden) {
              cell.style.display = "table-cell";
              setTimeout(() => cell.classList.add("show"), 10);
          } else {
              cell.classList.remove("show");
              setTimeout(() => cell.style.display = "none", 400);
          }
      });

      el.style.transform = isHidden ? "rotate(90deg)" : "rotate(0deg)";
    }

    function showTab(tabId, btn) {
      document.querySelectorAll(".tab-content").forEach(el => el.classList.remove("active"));
      document.querySelectorAll(".tabs button").forEach(el => el.classList.remove("active"));

      document.getElementById(tabId).classList.add("active");
      btn.classList.add("active");

      // ✅ Update total rows dynamically
      let totalRows = {
        old: <?= $total_old ?>,
        nonadd: <?= $total_non_add ?>
      };
      document.getElementById("totalRows").textContent = totalRows[tabId] + " Total Rows";
    }
  </script>
</body>
</html>
