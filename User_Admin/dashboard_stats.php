<?php
include '../connection.php';  

$sql_pending = "SELECT COUNT(*) as total FROM bcp_sms4_procurement WHERE status = 'Pending'";
$result_pending = $conn->query($sql_pending);
$pending = ($result_pending && $result_pending->num_rows > 0) ? $result_pending->fetch_assoc()['total'] : 0;

$sql_transit = "SELECT COUNT(*) as total FROM bcp_sms4_procurement WHERE status = 'In-Transit'";
$result_transit = $conn->query($sql_transit);
$transit = ($result_transit && $result_transit->num_rows > 0) ? $result_transit->fetch_assoc()['total'] : 0;

$sql_low_consu = "SELECT COUNT(*) as total FROM bcp_sms4_consumable WHERE status = 'Low-Stock'";
$result_low_consu = $conn->query($sql_low_consu);
$low_consu = ($result_low_consu && $result_low_consu->num_rows > 0) ? $result_low_consu->fetch_assoc()['total'] : 0;

$sql_low_asset = "SELECT COUNT(*) as total FROM bcp_sms4_asset WHERE status IN ('Damaged','Lost','Disposed')";
$result_low_asset = $conn->query($sql_low_asset);
$low_asset = ($result_low_asset && $result_low_asset->num_rows > 0) ? $result_low_asset->fetch_assoc()['total'] : 0;

$low_stocks = $low_consu + $low_asset;
?>
