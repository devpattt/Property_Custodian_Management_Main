<?php
include "../../../connection.php";
include "edit_botton_list.php"; // function edit button
include "get_rows.php";         // get $total_assets
include "delete_asset.php";     // function delete button
$result = $conn->query("SELECT * FROM bcp_sms4_asset ORDER BY created_at DESC");
?>
<!DOCTYPE html>
<html>
<head>
    <title>Asset List</title>  
    <script src="../../../assets/js/asset_reg_tagging/non_consumable/list_assets.js"></script>
    <script src="../../../assets/js/asset_reg_tagging/search_table.js"></script>
</head>
<body>

<section class="section">
  <div class="row">
    <div class="col-lg-12">

      <div class="card">
        <div class="card-body">

          <!-- Table with stripped rows -->
          <table class="table datatable">
            <thead>
              <tr>
                <th>Tag</th>
                <th>Name</th>
                <th>Category</th>
                <th>Quantity</th>
                <th>Active</th>
                <th>In Repair</th>
                <th>Disposed</th>
                <th data-type="date" data-format="YYYY/MM/DD">Date Added</th>
                <th>Action</th>
              </tr>
            </thead>
            <tbody>
              <?php while($row = $result->fetch_assoc()): ?>
                <tr>
                  <td><?= $row['asset_tag'] ?></td>
                  <td><?= $row['name'] ?></td>
                  <td><?= $row['category'] ?></td>
                  <td><?= $row['quantity'] ?></td>
                  <td><?= $row['active'] ?></td>
                  <td><?= $row['in_repair'] ?></td>
                  <td><?= $row['disposed'] ?></td>
                  <td><?= $row['created_at'] ?></td>
                  <td style="display:flex; gap:8px;">
                       <button type="button" class="btn btn-warning btn-sm"
                          data-asset_tag="<?= $row['asset_tag'] ?>"
                          data-active="<?= $row['active'] ?>"
                          data-in_repair="<?= $row['in_repair'] ?>" 
                          data-disposed="<?= $row['disposed'] ?>" 
                          onclick="openEditModal(this)">
                          Edit
                      </button> 
                      <form method="POST" action="delete_asset.php" style="margin:0;" onsubmit="return confirmDelete(this);">
                          <input type="hidden" name="asset_tag" value="<?= $row['asset_tag'] ?>">
                          <button type="submit"  class="btn btn-danger btn-sm">Drop</button>
                      </form>
                  </td>
                </tr>
              <?php endwhile; ?>
            </tbody>
          </table>
          <!-- End Table -->

        </div>
      </div>

    </div>
  </div>
</section>

<!-- Open Register Asset Modal Button -->
<button type="button" class="btn btn-primary" onclick="openModal('registerAssetModal')">
  + Add New Asset
</button>

<!-- Register Asset Modal -->
<div id="registerAssetModal" class="modal">
  <div class="modal-content">
    <span class="close" onclick="closeModal('registerAssetModal')">&times;</span>
    <h2>Register New Asset</h2>
    <p class="note">Note: Please fill in all fields carefully to add a new asset accurately.</p>

    <form method="POST" action="save_asset.php">
      <div class="form-group">
        <label for="name">Asset Name</label>
        <input type="text" id="name" name="name" required>
      </div>

      <div class="form-group">
        <label for="category">Category</label>
        <input type="text" id="category" name="category" required>
      </div>

      <div class="form-group">
        <label for="quantity">Quantity</label>
        <input type="number" id="quantity" name="quantity" required>
      </div>

      <button type="submit" class="btn-save" onclick="saveClientTime()">Save Asset</button>
    </form>
  </div>
</div>

<!-- Success Modal -->
<div id="successModal" class="modal" style="display:<?= $success ? 'block' : 'none' ?>;">
  <div class="modal-content">
    <span class="close" onclick="closeModal('successModal')">&times;</span>
    <h3>Asset Added Successfully!</h3>
    <p>Tag: <strong id="assetTag"><?= $tag ?></strong></p>
    <button class="btn-save" onclick="closeModal('successModal')">OK</button>
  </div>
</div>

<!-- Toast Container -->
<div id="toast-container"></div>

<script>
  function openModal(id) {
    document.getElementById(id).style.display = "block";
  }
  function closeModal(id) {
    document.getElementById(id).style.display = "none";
    if (id === 'successModal') {
      // clean up URL after closing success modal
      window.history.replaceState({}, document.title, "register_asset.php");
    }
  }
</script>

</body>
</html>



<script>
function confirmDelete(form) {
    // Show a custom modal instead of local confirm()
    document.getElementById("confirmModal").style.display = "block";

    // Handle confirm button
    document.getElementById("confirmYes").onclick = function() {
        form.submit(); // now submit the form
    };

    // Cancel just closes the modal
    document.getElementById("confirmNo").onclick = function() {
        document.getElementById("confirmModal").style.display = "none";
    };

    return false; // prevent immediate submit
}
</script>

<!-- Confirm Delete Modal -->
<div id="confirmModal" class="modal">
  <div class="modal-content">
    <p>Are you sure you want to delete this asset?</p>
    <button id="confirmYes">Yes</button>
    <button id="confirmNo">No</button>
  </div>
</div>

</body>
</html>
