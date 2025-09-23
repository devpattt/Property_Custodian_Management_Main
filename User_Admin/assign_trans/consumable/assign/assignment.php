<?php
session_start();
include "../../../../connection.php"; 

$conn->query("DELETE FROM bcp_sms4_assign_consumable WHERE box = 0 AND quantity = 0");

$result = $conn->query("SELECT * FROM bcp_sms4_assign_consumable ORDER BY reference_no DESC");
?>

<!DOCTYPE html>
<html lang="en">

<head>
  <meta charset="utf-8">
  <meta content="width=device-width, initial-scale=1.0" name="viewport">
  <title>Dashboard</title>
  <meta content="" name="description">
  <meta content="" name="keywords">
  <link href="../../../../assets/img/bagong_silang_logo.png" rel="icon">
  <link href="../../../../assets/img/apple-touch-icon.png" rel="apple-touch-icon">
  <link rel="preconnect" href="https://fonts.googleapis.com">
  <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
  <link href="https://fonts.googleapis.com/css2?family=Inter:ital,opsz,wght@0,14..32,100..900;1,14..32,100..900&display=swap" rel="stylesheet">
  <link href="../../../../assets/vendor/bootstrap/css/bootstrap.min.css" rel="stylesheet">
  <link href="../../../../assets/vendor/bootstrap-icons/bootstrap-icons.css" rel="stylesheet">
  <link href="../../../../assets/vendor/boxicons/css/boxicons.min.css" rel="stylesheet">
  <link href="../../../../assets/vendor/quill/quill.snow.css" rel="stylesheet">
  <link href="../../../../assets/vendor/quill/quill.bubble.css" rel="stylesheet">
  <link href="../../../../assets/vendor/remixicon/remixicon.css" rel="stylesheet">
  <link href="../../../../assets/vendor/simple-datatables/style.css" rel="stylesheet">
  <link href="../../../../assets/css/style.css" rel="stylesheet">
  <link href="../../../../assets/css/schedule_maintenance.css" rel="stylesheet">
  <link rel="stylesheet" href="../../../../assets/css/assign.css">
  <link rel="stylesheet" href="../../../../assets/css/modal.css">
</head><style>
/* Modal background */
.modal {
  display: none;
  position: fixed;
  z-index: 1050;
  left: 0;
  top: 0;
  width: 100%;
  height: 100%;
  overflow: auto;
  background: rgba(0,0,0,0.5); /* dim background */
}

/* Center modal box */
.modal-content {
  background: #fff;
  margin: 5% auto;
  padding: 25px 30px;
  border-radius: 12px;
  width: 60%;               /* wider like the form */
  max-width: 800px;          /* same width feel as form */
  box-shadow: 0 6px 20px rgba(0,0,0,0.25);
  animation: fadeIn 0.3s ease-in-out;
  font-family: Arial, sans-serif;
}

/* Header */
.modal-content h3 {
  margin-bottom: 20px;
  color: #2c3e50;
  font-size: 22px;
  font-weight: 600;
  text-align: center;
}

/* Error modal special style */
.modal-content.error h3 {
  color: #c0392b;
}

/* Make modal content grid like the form */
.modal-grid {
  display: grid;
  grid-template-columns: 1fr 1fr; /* 2 columns */
  gap: 20px;
  margin-bottom: 20px;
}

/* Label styling */
.modal-content label {
  display: block;
  font-weight: 500;
  font-size: 15px;
  margin-bottom: 6px;
  color: #000;
  text-align: left;
}

/* Inputs */
.modal-content input,
.modal-content textarea {
  width: 100%;
  padding: 10px 12px;
  font-size: 15px;
  border: 1px solid #ccc;
  border-radius: 6px;
  outline: none;
  margin-bottom: 14px;
  transition: border-color 0.3s, box-shadow 0.3s;
}

.modal-content input:focus,
.modal-content textarea:focus {
  border-color: #3498db;
  box-shadow: 0 0 5px rgba(52,152,219,0.4);
}

/* Buttons */
.close-btn {
  display: inline-block;
  margin-top: 10px;
  padding: 10px 18px;
  background: #3498db;
  color: #fff;
  font-size: 14px;
  border-radius: 6px;
  border: none;
  cursor: pointer;
  transition: background 0.3s;
}
.close-btn:hover {
  background: #2980b9;
}

@keyframes fadeIn {
  from { opacity: 0; transform: translateY(-20px); }
  to { opacity: 1; transform: translateY(0); }
}
</style>



<body>
  <?php
    include '../../../../components/nav-bar.php'  
  ?>
<br>

<div class="container">
  <h2>Assign Equipment</h2>
  <form action="assign_process.php" method="POST" class="form-grid">

    <div class="form-column">
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
    </div>
>
    <div class="form-column">
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
    </div>

    <div class="form-submit">
      <button type="submit">Assign Equipment</button>
    </div>
  </form>
</div>

<div id="successModal" class="modal">
  <div class="modal-content">
    <h3>Assign Successfully!</h3>
    <div class="modal-grid">
      <div>
        <label>Reference No:</label>
        <input type="text" value="12345" readonly>
      </div>
      <div>
        <label>Status:</label>
        <input type="text" value="Completed" readonly>
      </div>
    </div>
    <button class="close-btn" onclick="closeModal('successModal')">OK</button>
  </div>
</div>


    <div id="mergeModal" class="modal">
    <div class="modal-content">
        <h3>Assignment Merged!</h3>
        <p>Quantity has been updated for custodian.</p>
        <p>Reference: <strong id="reference_no_merge"></strong></p>
        <button class="close-btn" onclick="closeModal('mergeModal')">OK</button>
    </div>
    </div>

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
    <?php if (isset($_GET['success']) && isset($_GET['reference'])): ?>
        document.getElementById("reference_no").textContent = "<?= $_GET['reference'] ?>";
        document.getElementById("successModal").style.display = "block";
    <?php endif; ?>

    <?php if (isset($_GET['merge']) && isset($_GET['reference'])): ?>
        document.getElementById("reference_no_merge").textContent = "<?= $_GET['reference'] ?>";
        document.getElementById("mergeModal").style.display = "block";
    <?php endif; ?>

    <?php if (isset($_GET['error']) && isset($_GET['msg'])): ?>
        document.getElementById("error_msg").textContent = "<?= htmlspecialchars($_GET['msg']) ?>";
        document.getElementById("errorModal").style.display = "block";
    <?php endif; ?>
    </script>


  <a href="#" class="back-to-top d-flex align-items-center justify-content-center"><i class="bi bi-arrow-up-short"></i></a>
  <script src="../../../../assets/vendor/apexcharts/apexcharts.min.js"></script>
  <script src="../../../../assets/vendor/bootstrap/js/bootstrap.bundle.min.js"></script>
  <script src="../../../../assets/vendor/chart.js/chart.umd.js"></script>
  <script src="../../../../assets/vendor/echarts/echarts.min.js"></script>
  <script src="../../../../assets/vendor/quill/quill.js"></script>
  <script src="../../../../assets/vendor/simple-datatables/simple-datatables.js"></script>
  <script src="../../../../assets/vendor/tinymce/tinymce.min.js"></script>
  <script src="../../../../assets/vendor/php-email-form/validate.js"></script>
  <script src="../../../../assets/js/main.js"></script>
  <script src="../../../../assets/js/assign_trans/history/history.js"></script>
  <script src="../../../../assets/js/assign_trans/consumable/active.js"></script>
  </body>
</html>