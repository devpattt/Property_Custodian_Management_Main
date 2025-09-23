<?php
include "../../../connect/connection.php"; // process file
include "get_rows.php"; // get total rows
include "edit_button.php"; // to function edit button
include "edit_modal.php";   // to show modal

// 1. Delete rows with quantity = 0
$conn->query("DELETE FROM bcp_sms4_assign_history WHERE quantity = 0");

// 2. Fetch updated results
$result = $conn->query("SELECT * FROM bcp_sms4_assign_history ORDER BY reference_no DESC");
?>
<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <title>Custodian History</title>
  <link rel="stylesheet" href="../../../../css/table_size.css">
  <link rel="stylesheet" href="../../../../css/asset_reg/list_assets.css">
  <script src="../../../../js/assign_trans/history/history.js"></script>
  <script src="../../../../js/assign_trans/non_consumable/history.js"></script>
</head>
<body>
  <div class="container">
    <h2>Active Non-Consumable Custodian's Assigned</h2>
    <input type="text" id="search" placeholder="Search..." onkeyup="searchTable()">
    <div class="card">
         <h2><?= $total_assets ?> Total Rows</h2>
    </div>
    <p>Note: If the value of the <b>Quantity</b> reach 0, that row will automatically be deleted.</p>

    <table>
      <thead>
        <tr>
          <th>Reference No</th>
          <th>Equipment ID</th>
          <th>Equipment Name</th>
          <th>Quantity</th>
          <th>Custodian ID</th>
          <th>Custodian Name</th>
          <th>Department</th>
          <th>Assigned Date</th>
          <th>Remarks</th>
          <th>Assigned By</th>
          <th>Action</th>
        </tr>
      </thead>
      <tbody>
        <?php while($row = $result->fetch_assoc()): ?>
          <tr>
            <td><?= $row['reference_no'] ?></td>
            <td><?= $row['equipment_id'] ?></td>
            <td><?= $row['equipment_name'] ?></td>
            <td><?= $row['quantity'] ?></td>
            <td><?= $row['custodian_id'] ?></td>
            <td><?= $row['custodian_name'] ?></td>
            <td><?= $row['department_code'] ?></td>
            <td><?= $row['assigned_date'] ?></td>
            <td><?= $row['remarks'] ?></td>
            <td><?= $row['assigned_by'] ?></td>
            <td style="display:flex; gap:8px;">
              <button type="button" class="btn_table"
                data-reference_no="<?= $row['reference_no'] ?>"
                data-quantity="<?= $row['quantity'] ?>" 
                onclick="openEditModal(this)">
                Edit
              </button>
            </td>
          </tr>
        <?php endwhile; ?>
      </tbody>
    </table>
  </div>
</body>
</html>
