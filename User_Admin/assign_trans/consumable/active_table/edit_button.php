<?php
session_start();
include "../../../connect/connection.php";
include "edit_modal.php";
?>
<!DOCTYPE html>
<html>
    <head>
        <link rel="stylesheet" href="../../../../css/asset_reg/modal.css">
    </head>
    <body>
<?php

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $reference_no = $_POST['reference_no'];
    $box = (int)$_POST['box'];
    $quantity = (int)$_POST['quantity'];
    $expiration = $_POST['expiration'];

    //  First get the quantity for this asset
    // $sql = "SELECT quantity FROM bcp_sms4_consumable WHERE asset_tag = ?";
    // $stmt = $conn->prepare($sql);
    // $stmt->bind_param("s", $asset_tag);
    // $stmt->execute();
    // $result = $stmt->get_result();

//     if ($row = $result->fetch_assoc()) {
//         $quantity = (int)$row['quantity'];

//         //  Check if the sum matches
//         if (($active + $in_repair + $disposed) !== $quantity) {
            
//             renderMessageModal(
//                "errorModal",
//                "Error",
//                "Invalid input: values must add up to Quantity."
// );
//             exit();
//         }

        //  If valid, update
        $update_sql = "UPDATE bcp_sms4_assign_consumable
                       SET box = ?, quantity = ?, expiration = ?
                       WHERE reference_no = ?";
        $update_stmt = $conn->prepare($update_sql);
        $update_stmt->bind_param("iiss", $box, $quantity, $expiration, $reference_no);

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
    // } else {
    //     echo "Asset not found!";
    // }

    $stmt->close();
    $conn->close();
}
?>
</body>
