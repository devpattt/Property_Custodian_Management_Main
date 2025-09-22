<?php
include '../connection.php';

$sql_assets = "SELECT COUNT(*) AS total_assets FROM bcp_sms4_asset";
$res_assets = $conn->query($sql_assets);
$row_assets = $res_assets->fetch_assoc();
$total_assets = $row_assets['total_assets'] ?? 0;

$sql_consumables = "SELECT COUNT(*) AS total_consumables FROM bcp_sms4_consumable";
$res_consumables = $conn->query($sql_consumables);
$row_consumables = $res_consumables->fetch_assoc();
$total_consumables = $row_consumables['total_consumables'] ?? 0;

echo json_encode([
    "assets" => $total_assets,
    "consumables" => $total_consumables
]);
