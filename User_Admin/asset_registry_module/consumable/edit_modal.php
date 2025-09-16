<!-- Edit Modal -->
<div id="editModal" class="modal">
  <div class="modal-content">
    <span class="close" onclick="closeModal('editModal')">&times;</span>
    <h2>Edit Asset</h2>
    <p class="note">Note: Update the fields below to change the asset details.</p>

    <form method="POST" action="edit_botton_list.php">
      <input type="hidden" name="asset_tag" id="editAssetTag">

      <div class="form-group">
        <label for="editBox">Box</label>
        <input type="number" name="box" id="editBox" required>
      </div>

      <div class="form-group">
        <label for="editQuantity">Quantity</label>
        <input type="number" name="quantity" id="editQuantity" required>
      </div>

      <div class="form-group">
        <label for="editExpiration">Expiration</label>
        <input type="date" name="expiration" id="editExpiration" required>
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

</body>
</html>