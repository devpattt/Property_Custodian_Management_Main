<?php
?>
<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <title>Assign Equipment</title>
  <link rel="stylesheet" href="../../../../css/assign_trans/assign/assign_trans.css">
  <link rel="stylesheet" href="../../../../css/assign_trans/assign/modal.css">
  <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
  <script src="https://code.jquery.com/ui/1.13.2/jquery-ui.min.js"></script>
  <link rel="stylesheet" href="https://code.jquery.com/ui/1.13.2/themes/base/jquery-ui.css">
  <script src="/CustodianManagement/js/auto_suggest/assign_trans/non_consumable/search_user.js"></script>
  <script src="/CustodianManagement/js/auto_suggest/assign_trans/consumable/search_asset.js"></script>
  <link rel="stylesheet" href="../../../../css/auto_suggest/auto_suggest.css">
</head>
<body>
  <div class="container">
    <h2>Assign Equipment</h2>
    <form action="assign_process.php" method="POST">
      <label>Item Name:</label>
      <input type="text" id="equipmentName" name="equipment_name" placeholder="Enter Item Name" required>

      <label>Item Tag:</label>
      <input type="text" id="equipmentId" name="equipment_id" placeholder="Enter Item Tag" required>

      <label>Item Category:</label>
      <input type="text" id="equipmentCategory" name="equipmentCategory" placeholder="Auto filled Category">

      <label>Expiration:</label>
      <input type="text" id="expiration" name="expiration" placeholder="Auto filled Expiration">

      <label>Box:</label>
      <input type="number" name="box" placeholder="Enter box quantity" min="0">

      <label>Quantity:</label>
      <input type="number" name="quantity" placeholder="Enter Quantity" min="0">

      <label>Name:</label>
      <input type="text" id="userName" name="name" placeholder="Enter Name">

      <label>Employee ID:</label>
      <input type="text" id="userId" name="user_id" placeholder="Enter Employee ID">

      <label>Department:</label>
      <input type="text" id="userDept" name="department" placeholder="Enter Department">

      <label>Remarks:</label>
      <textarea name="remarks"></textarea>

      <button type="submit">Assign Equipment</button>
    </form>
  </div>

  <!-- Success Modal -->
<div id="successModal" class="modal">
  <div class="modal-content">
    <h3>Assign Successfully!</h3>
    <p>Reference: <strong id="reference_no"></strong></p>
    <button class="close-btn" onclick="closeModal('successModal')">OK</button>
  </div>
</div>

<!-- Merge Modal -->
<div id="mergeModal" class="modal">
  <div class="modal-content">
    <h3>Assignment Merged!</h3>
    <p>Quantity has been updated for custodian.</p>
    <p>Reference: <strong id="reference_no_merge"></strong></p>
    <button class="close-btn" onclick="closeModal('mergeModal')">OK</button>
  </div>
</div>

<!-- Error Modal -->
<div id="errorModal" class="modal">
  <div class="modal-content error">
    <h3>Error</h3>
    <p id="error_msg"></p>
    <button class="close-btn" onclick="closeModal('errorModal')">OK</button>
  </div>
</div>

<script>
  function closeModal(id) {
    document.getElementById(id).style.display = "none";
    window.history.replaceState({}, document.title, "assign.php");
  }

  // ✅ Success check
  <?php if (isset($_GET['success']) && isset($_GET['reference'])): ?>
    document.getElementById("reference_no").textContent = "<?= $_GET['reference'] ?>";
    document.getElementById("successModal").style.display = "block";
  <?php endif; ?>

  // ✅ Merge check
  <?php if (isset($_GET['merge']) && isset($_GET['reference'])): ?>
    document.getElementById("reference_no_merge").textContent = "<?= $_GET['reference'] ?>";
    document.getElementById("mergeModal").style.display = "block";
  <?php endif; ?>

  // ❌ Error check
  <?php if (isset($_GET['error']) && isset($_GET['msg'])): ?>
    document.getElementById("error_msg").textContent = "<?= htmlspecialchars($_GET['msg']) ?>";
    document.getElementById("errorModal").style.display = "block";
  <?php endif; ?>
</script>

</body>
</html>
