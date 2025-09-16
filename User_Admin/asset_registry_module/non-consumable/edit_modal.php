<!-- Edit Modal -->
<div id="editModal" class="modal">
  <div class="modal-content">
    <span class="close" onclick="closeModal('editModal')">&times;</span>
    <h2>Edit Asset</h2>
    <p class="note">Note: If you input a number make sure when you add the Active, In Repair, and Disposed it should be equal to Quantity, if not your input will be invalid.</p>

    <form method="POST" action="edit_botton_list.php">
      <input type="hidden" name="asset_tag" id="editAssetTag">

      <div class="form-group">
        <label for="editActive">Active:</label>
        <input type="number" name="active" id="editActive">
      </div>

      <div class="form-group">
        <label for="editInRepair">In Repair:</label>
        <input type="number" name="in_repair" id="editInRepair">
      </div>

      <div class="form-group">
        <label for="editDisposed">Disposed:</label>
        <input type="number" name="disposed" id="editDisposed">
      </div>

      <button type="submit" class="btn-save">Save Changes</button>
    </form>
  </div>
</div>

<!-- Example of calling message modal -->
<?php
if (!function_exists("renderMessageModal")) {
    function renderMessageModal($id, $title, $message) {
        ?>
        <div id="<?= $id ?>" class="modal" style="display:block;">
            <div class="modal-content">
                <span class="close" onclick="window.location.href='list_assets.php'">&times;</span>
                <h2><?= $title ?></h2>
                <p><?= $message ?></p>
                <button class="close-btn" onclick="window.location.href='list_assets.php'">OK</button>
            </div>
        </div>
        <?php
    }
}
?>
