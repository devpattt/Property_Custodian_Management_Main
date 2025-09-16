<?php
//session_start();
include "../../../connection.php";
include "edit_modal.php";

if ($_SERVER["REQUEST_METHOD"] == "POST" && isset($_POST['asset_tag'])) {
    $asset_tag = $_POST['asset_tag'];

    $sql = "DELETE FROM bcp_sms4_consumable WHERE asset_tag = ?";
    $stmt = $conn->prepare($sql);
    $stmt->bind_param("s", $asset_tag);

    if ($stmt->execute()) {
    renderMessageModal(
        "successModal",
        "Success",
        "Asset deleted successfully!"
    );

    // show modal automatically
    echo "<script>
            document.addEventListener('DOMContentLoaded', function() {
                document.getElementById('successModal').style.display = 'block';
            });
          </script>";

        header("Location: list_assets.php?deleted=1&tag=" . urlencode($asset_tag));
        exit();
    } else {
        echo "Error deleting record: " . $conn->error;
    }

    $stmt->close();
    $conn->close();
}
?>