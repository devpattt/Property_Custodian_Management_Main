<?php
include "../../../connection.php";
include "edit_botton_list.php"; // function edit button
include "get_rows.php"; // to get $total_assets
include "delete_asset.php"; // function delete button

// Fetch consumables + item details
$result = $conn->query("
    SELECT 
        c.id,
        c.item_id,
        c.unit,
        c.quantity,
        c.status,
        c.expiration,
        c.date_received,
        i.item_name,
        i.category
    FROM bcp_sms4_consumable c
    JOIN bcp_sms4_items i ON c.item_id = i.item_id
    ORDER BY c.id DESC
");
?>

<!DOCTYPE html>
<html>
<head>
    <title>Asset List</title>
    <script src="../../../assets/js/asset_reg_tagging/consumable/list_assets.js"></script>
    <script src="../../../assets/js/asset_reg_tagging/search_table.js"></script>

    
</head>
<body>

<section class="section">
  <div class="row">
    <div class="col-lg-12">

      <div class="card">
        <div class="card-body">
         <!-- <h5 class="card-title">Asset Count/Box <?= $total_assets ?></h5>-->

          <!-- Table with stripped rows -->
          <table class="table datatable">
            <thead>
              <tr>
                <th>Id</th>
                <th>Item Name</th>
                <th>Category</th>
                <th>Unit</th>
                <th>Quantity</th>
                <th>Expiration</th>
                <th>Date Added</th>
                <th>Action</th>
              </tr>
            </thead>
            <tbody>
            <?php while($row = $result->fetch_assoc()): ?>
              <tr>
                <td><?= $row['id'] ?></td>
                <td><?= htmlspecialchars($row['item_name']) ?></td>
                <td><?= htmlspecialchars($row['category']) ?></td>
                <td><?= htmlspecialchars($row['unit']) ?></td>
                <td><?= $row['quantity'] ?></td>
                <td><?= $row['expiration'] ?? 'N/A' ?></td>
                <td><?= $row['date_received'] ?></td>
                <td style="display:flex; gap:8px;">
                  <button type="button" class="btn btn-warning btn-sm"
                    data-id="<?= $row['id'] ?>"
                    data-item_id="<?= $row['item_id'] ?>"
                    data-quantity="<?= $row['quantity'] ?>" 
                    data-expiration="<?= $row['expiration'] ?>" 
                    onclick="openEditModal(this)">
                    Edit
                  </button>
                  <form method="POST" action="delete_asset.php" style="margin:0;" onsubmit="return confirmDelete(this);">
                    <input type="hidden" name="id" value="<?= $row['id'] ?>">
                    <button type="submit" class="btn btn-danger btn-sm">Drop</button>
                  </form>
                </td>
              </tr>
            <?php endwhile; ?>
          </tbody>
          </table>
          <!-- End Table with stripped rows -->

        </div>
      </div>
 
    </div>
  </div>
</section>

<!-- Add New Asset Button -->
<button type="button" class="btn btn-primary mb-3" onclick="openModal('registerAssetModal')">
  + Add New Asset
</button>

<!-- Register Asset Modal -->
<div id="registerAssetModal" class="modal">
  <div class="modal-content">
    <span class="close" onclick="closeModal('registerAssetModal')">&times;</span>
    <h2>Register New Asset</h2>
    <p class="note">
     Note: The <em>Box</em> and <em>Quantity</em> fields are optional. Any incorrect entries can be corrected in the <b>List of School Consumable Assets section.</b>
    </p>

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
        <label for="box">Box</label>
        <input type="number" id="box" name="box">
      </div>

      <div class="form-group">
        <label for="quantity">Quantity</label>
        <input type="number" id="quantity" name="quantity">
      </div>

      <div class="form-group">
        <label for="expiration">Expiration</label>
        <input type="date" id="expiration" name="expiration" required>
      </div>

      <button type="submit" class="btn-save">Save Asset</button>
    </form>
  </div>
</div>

<script>
  function openModal(id) {
    document.getElementById(id).style.display = "block";
  }
  function closeModal(id) {
    document.getElementById(id).style.display = "none";
  }
</script>


<!-- Confirm Delete Modal -->
<div id="confirmModal" class="modal" style="display:none;">
  <div class="modal-content">
    <p>Are you sure you want to drop this asset?</p>
    <button id="confirmYes" class="btn btn-danger">Yes</button>
    <button id="confirmNo" class="btn btn-secondary">No</button>
  </div>
</div>

<script>
function confirmDelete(form) {
    document.getElementById("confirmModal").style.display = "block";

    document.getElementById("confirmYes").onclick = function() {
        form.submit();
    };

    document.getElementById("confirmNo").onclick = function() {
        document.getElementById("confirmModal").style.display = "none";
    };

    return false; 
}
</script>


<!-- Example confirm modal -->
<div id="confirmModal" class="modal">
  <div class="modal-content">
    <p>Are you sure you want to delete this asset?</p>
    <button id="confirmYes">Yes</button>
    <button id="confirmNo">No</button>
  </div>
</div>

<!-- Toast container -->
<div id="toast-container"></div>

<link rel="stylesheet" href="../../assets/css/toast.css">
<script src="../../assets/js/toast.js"></script>

</body>
</html>