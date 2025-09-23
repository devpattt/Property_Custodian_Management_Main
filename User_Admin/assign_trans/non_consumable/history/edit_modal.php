<!DOCTYPE html>
<html>
<head>
    <title>Edit Asset</title>
    <link rel="stylesheet" href="../../../../css/asset_reg/modal.css">
    <script>
        // Function to close modal
        function closeModal(id) {
            document.getElementById(id).style.display = "none";
        }

        // Function to open modal
        function openModal(id) {
            document.getElementById(id).style.display = "block";
        }
    </script>
</head>
<body>

    <!-- Edit Modal -->
    <div id="editModal" class="modal">
        <div class="modal-content">
            <span class="close" onclick="closeModal('editModal')">&times;</span>
            <h2>Edit Asset</h2>
            <p class="input">Note: Here you can change the value base on the input</p>

            <form method="POST" action="history.php">
                <input type="hidden" name="reference_no" id="editReferenceNo">

                <label class="input1">Quantity:</label><br><br>
                <input type="number" name="quantity" id="editQuantity"><br><br>

                <button type="submit" class="close-btn">Save Changes</button>
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
                <span class="close" onclick="window.location.href='history.php'">&times;</span>
                <h2><?= $title ?></h2>
                <p><?= $message ?></p>
                <button class="close-btn" onclick="window.location.href='history.php'">OK</button>
            </div>
        </div>
        <?php
    }
}
?>

</body>
</html>
