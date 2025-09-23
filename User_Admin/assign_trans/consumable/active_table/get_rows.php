<?php
include "../../../../connection.php"; 

$sql = "SELECT COUNT(*) AS total FROM bcp_sms4_assign_consumable";
$result = $conn->query($sql);
$row = $result->fetch_assoc();
$total_assets = $row['total'];
?>