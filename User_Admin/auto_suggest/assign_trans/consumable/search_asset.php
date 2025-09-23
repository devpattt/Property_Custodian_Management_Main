<?php
include "../../../connect/connection.php";

$term = isset($_GET['term']) ? $_GET['term'] : '';

$sql = "SELECT asset_tag, name, category, expiration 
        FROM bcp_sms4_consumable 
        WHERE name LIKE ? OR asset_tag LIKE ? 
        LIMIT 20";
$stmt = $conn->prepare($sql);
$likeTerm = "%" . $term . "%";
$stmt->bind_param("ss", $likeTerm, $likeTerm);
$stmt->execute();
$res = $stmt->get_result();

$data = [];
while ($row = $res->fetch_assoc()) {
    $data[] = [
        "label" => $row['name'] . " (" . $row['asset_tag'] . ")", 
        "value" => $row['name'],   // What fills into Item Name input
        "asset_tag" => $row['asset_tag'],
        "category" => $row['category'],
        "expiration" => $row['expiration']
    ];
}

echo json_encode($data);
?>