<?php
include '../../connection.php';

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $deliveryId = $_POST['delivery_id'];
    $category   = $_POST['category'];
    $item       = $_POST['item'];
    $qty        = $_POST['qty'];

    if (in_array(strtolower($category), ['consumables', 'consumable'])) {
        $stmt = $conn->prepare("INSERT INTO consumable_assets (name, category, quantity) VALUES (?, ?, ?)");
        $stmt->bind_param("ssi", $item, $category, $qty);
        $stmt->execute();

        header("Location: ../asset_registry_module/consumable/list_assets.php?success=1");
    } else {
        $stmt = $conn->prepare("INSERT INTO non_consumable_assets (name, category, quantity) VALUES (?, ?, ?)");
        $stmt->bind_param("ssi", $item, $category, $qty);
        $stmt->execute();

        header("Location: ../asset_registry_module/non-consumable/list_assets.php?success=1");
    }
}
?>
