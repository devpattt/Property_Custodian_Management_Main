
<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <title>Assign Equipment</title>
  <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
  <script src="https://code.jquery.com/ui/1.13.2/jquery-ui.min.js"></script>
  <link rel="stylesheet" href="https://code.jquery.com/ui/1.13.2/themes/base/jquery-ui.css">
  <script src="/CustodianManagement/js/auto_suggest/assign_trans/non_consumable/search_user.js"></script>
  <script src="/CustodianManagement/js/auto_suggest/assign_trans/consumable/search_asset.js"></script>
  <link rel="stylesheet" href="../../../../css/auto_suggest/auto_suggest.css">

</head>
<style>
    /* Make the modal wider */
    #assignModal .modal-dialog {
      max-width: 900px;
    }

    /* Align form text to the left and make labels bold */
    #assignModal .assign-form label {
      text-align: left;
      font-weight: 500;
      display: block;
      margin-bottom: 0.25rem;
    }

    /* Make inputs fill their column nicely */
    #assignModal .assign-form .form-control {
      width: 100%;
      margin-bottom: 1rem;
    }

    /* Adjust spacing between form fields */
    #assignModal .assign-form .col-md-6 {
      display: flex;
      flex-direction: column;
      padding: 0 15px;
    }

    /* Better textarea sizing */
    #assignModal .assign-form textarea {
      resize: vertical;
      min-height: 80px;
    }

    /* Button styling */
    .btn-submit {
      min-width: 150px;
    }

    /* Modal header improvements */
    .modal-header {
      border-bottom: 2px solid #dee2e6;
    }

    .modal-title {
      font-weight: 600;
    }
  </style>

<body>

<div class="modal fade" id="assignModal" tabindex="-1" aria-labelledby="assignModalLabel" aria-hidden="true">
  <div class="modal-dialog modal-lg">
    <div class="modal-content">
      <div class="modal-header">
        <h5 class="modal-title" id="assignModalLabel">Assign Equipment</h5>
        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
      </div>
      <div class="modal-body">
        <form action="assign_process.php" method="POST" class="row g-3 assign-form">

          <!-- Left Column -->
          <div class="col-md-6">
            <label>Item Name:</label>
            <input type="text" class="form-control mb-2" name="equipment_name" required>

            <label>Item Tag:</label>
            <input type="text" class="form-control mb-2" name="equipment_id" required>

            <label>Item Category:</label>
            <input type="text" class="form-control mb-2" name="equipmentCategory" readonly>

            <label>Expiration:</label>
            <input type="text" class="form-control mb-2" name="expiration" readonly>

            <label>Box:</label>
            <input type="number" class="form-control mb-2" name="box" min="0">
          </div>

          <!-- Right Column -->
          <div class="col-md-6">
            <label>Quantity:</label>
            <input type="number" class="form-control mb-2" name="quantity" min="0">

            <label>Name:</label>
            <input type="text" class="form-control mb-2" name="name">

            <label>Employee ID:</label>
            <input type="text" class="form-control mb-2" name="user_id">

            <label>Department:</label>
            <input type="text" class="form-control mb-2" name="department">

            <label>Remarks:</label>
            <textarea class="form-control mb-3" name="remarks"></textarea>
          </div>

          <div class="col-12 text-center">
            <button type="submit" class="btn btn-primary px-4">Assign Equipment</button>
          </div>
        </form>
      </div>
    </div>
  </div>
</div>


<!-- Success Modal -->
<div class="modal fade" id="successModal" tabindex="-1" aria-labelledby="successModalLabel" aria-hidden="true">
  <div class="modal-dialog modal-md">
    <div class="modal-content">
      <div class="modal-header bg-success text-white">
        <h5 class="modal-title" id="successModalLabel">Assign Successfully!</h5>
        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
      </div>
      <div class="modal-body">
        <p>Reference: <strong id="reference_no"></strong></p>
      </div>
      <div class="modal-footer">
        <button type="button" class="btn btn-success" data-bs-dismiss="modal">OK</button>
      </div>
    </div>
  </div>
</div>

<!-- Merge Modal -->
<div class="modal fade" id="mergeModal" tabindex="-1" aria-labelledby="mergeModalLabel" aria-hidden="true">
  <div class="modal-dialog modal-md">
    <div class="modal-content">
      <div class="modal-header bg-primary text-white">
        <h5 class="modal-title" id="mergeModalLabel">Assignment Merged!</h5>
        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
      </div>
      <div class="modal-body">
        <p>Quantity has been updated for custodian.</p>
        <p>Reference: <strong id="reference_no_merge"></strong></p>
      </div>
      <div class="modal-footer">
        <button type="button" class="btn btn-primary" data-bs-dismiss="modal">OK</button>
      </div>
    </div>
  </div>
</div>

<!-- Error Modal -->
<div class="modal fade" id="errorModal" tabindex="-1" aria-labelledby="errorModalLabel" aria-hidden="true">
  <div class="modal-dialog modal-md">
    <div class="modal-content">
      <div class="modal-header bg-danger text-white">
        <h5 class="modal-title" id="errorModalLabel">Error</h5>
        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
      </div>
      <div class="modal-body">
        <p id="error_msg"></p>
      </div>
      <div class="modal-footer">
        <button type="button" class="btn btn-danger" data-bs-dismiss="modal">OK</button>
      </div>
    </div>
  </div>
</div>


<script>
  document.addEventListener("DOMContentLoaded", function () {

    <?php if (isset($_GET['success']) && isset($_GET['reference'])): ?>
      document.getElementById("reference_no").textContent = "<?= $_GET['reference'] ?>";
      new bootstrap.Modal(document.getElementById("successModal")).show();
    <?php endif; ?>

    <?php if (isset($_GET['merge']) && isset($_GET['reference'])): ?>
      document.getElementById("reference_no_merge").textContent = "<?= $_GET['reference'] ?>";
      new bootstrap.Modal(document.getElementById("mergeModal")).show();
    <?php endif; ?>

    <?php if (isset($_GET['error']) && isset($_GET['msg'])): ?>
      document.getElementById("error_msg").textContent = "<?= htmlspecialchars($_GET['msg']) ?>";
      new bootstrap.Modal(document.getElementById("errorModal")).show();
    <?php endif; ?>
  });
</script>


</body>
</html>
