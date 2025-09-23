<?php
include "transfer_process.php"; // process file
?>
<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <title>Transfer Equipment</title>
  <link rel="stylesheet" href="../../../../css/assign_trans/assign/assign_trans.css">
  <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
  <script src="https://code.jquery.com/ui/1.13.2/jquery-ui.min.js"></script>
  <link rel="stylesheet" href="https://code.jquery.com/ui/1.13.2/themes/base/jquery-ui.css">
  <script src="/CustodianManagement/js/auto_suggest/assign_trans/non_consumable/search_user.js"></script>
  <link rel="stylesheet" href="../../../../css/auto_suggest/auto_suggest.css">
</head>
<body>
  <div class="container">
    <h2>Transfer Equipment</h2>
    <form action="transfer_process.php" method="POST">
      <label>Reference No:</label>
      <input type="text" name="reference_no" placeholder="Enter the  Ref that needs to trasfer. Ex: REF-20250910-XYZ12" required>

      <label>Box:</label>
      <input type="number" name="box" placeholder="Enter box quantity" min="0">

      <label>Quantity:</label>
      <input type="number" name="quantity" placeholder="Enter Quantity" min="0">

      <label>New Custodian:</label>
      <input type="text" id="userName" name="name" placeholder="Enter New Custodian Name">

      <label>New Employee ID:</label>
      <input type="text" id="userId" name="user_id" placeholder="Enter Employee ID">

      <label>Department:</label>
      <input type="text" id="userDept" name="department" placeholder="Enter Department">

      <label>Reason:</label>
      <textarea name="remarks" placeholder="Enter the Reason why this item needs to transfer..."></textarea>

      <button type="submit">Transfer Equipment</button>
    </form>
  </div>
</body>
</html>

