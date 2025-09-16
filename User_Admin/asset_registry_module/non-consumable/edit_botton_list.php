<?php
include "../../../connection.php";
include "edit_modal.php";
?>
<!DOCTYPE html>
<html>
    <head>
        <link rel="stylesheet" href="../../../assets/css/modal.css">
    </head>
    <body>
<?php

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $asset_tag = $_POST['asset_tag'];
    $active = (int)$_POST['active'];
    $in_repair = (int)$_POST['in_repair'];
    $disposed = (int)$_POST['disposed'];

    // 🔹 First get the quantity for this asset
    $sql = "SELECT quantity FROM bcp_sms4_asset WHERE asset_tag = ?";
    $stmt = $conn->prepare($sql);
    $stmt->bind_param("s", $asset_tag);
    $stmt->execute();
    $result = $stmt->get_result();

    if ($row = $result->fetch_assoc()) {
        $quantity = (int)$row['quantity'];

        // 🔹 Check if the sum matches
        if (($active + $in_repair + $disposed) !== $quantity) {
            
            renderMessageModal(
               "errorModal",
               "Error",
               "Invalid input: values must add up to Quantity."
);
            exit();
        }

        // 🔹 If valid, update
        $update_sql = "UPDATE bcp_sms4_asset
                       SET active = ?, in_repair = ?, disposed = ?
                       WHERE asset_tag = ?";
        $update_stmt = $conn->prepare($update_sql);
        $update_stmt->bind_param("iiis", $active, $in_repair, $disposed, $asset_tag);

        if ($update_stmt->execute()) {
            renderMessageModal(
              "successModal",
              "Success",
              "Asset updated successfully!"
      );
            exit();
        } else {
            echo "Error updating record: " . $conn->error;
        }
    } else {
        echo "Asset not found!";
    }

    $stmt->close();
    $conn->close();
}
?>
</body>