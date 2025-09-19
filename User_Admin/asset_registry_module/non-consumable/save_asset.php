<?php
error_reporting(E_ALL);
ini_set('display_errors', 1);
include "../../../connection.php";
include "code_generator.php";

if ($_SERVER["REQUEST_METHOD"] === "POST") {
    $delivery_id = $_POST["delivery_id"] ?? null;
    $name = $_POST["name"] ?? '';
    $category = $_POST["category"] ?? '';
    $quantity = $_POST["quantity"] ?? 0;

    date_default_timezone_set("Asia/Manila"); 
    $client_time = date("Y-m-d H:i:s");

    $sql = "INSERT INTO bcp_sms4_asset (name, category, quantity, created_at, asset_tag) 
            VALUES ('$name', '$category', '$quantity', '$client_time', NULL)";

    if ($conn->query($sql) === TRUE) {
        $id = $conn->insert_id;
        $asset_tag = generateAssetTag($id);
        $conn->query("UPDATE bcp_sms4_asset SET asset_tag='$asset_tag' WHERE id=$id");

        header("Location: non-consumable.php?success=1&tag=$asset_tag");
        exit();
    } else {
        echo "Error: " . $conn->error;
    }
} else {
    echo "Invalid Request";
}
