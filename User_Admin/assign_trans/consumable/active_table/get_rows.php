<?php
include "../../../connect/connection.php"; // your DB connection

// Count assets
$sql = "SELECT COUNT(*) AS total FROM bcp_sms4_assign_consumable";
$result = $conn->query($sql);
$row = $result->fetch_assoc();
$total_assets = $row['total'];
//echo "Total assets: " . $total_assets;
?>