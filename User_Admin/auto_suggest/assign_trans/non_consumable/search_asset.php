<?php
include "../../../connect/connection.php";

$term = $_GET['term'] ?? '';

$sql = "SELECT asset_tag, name 
        FROM bcp_sms4_asset 
        WHERE name LIKE ? 
        LIMIT 10";

$stmt = $conn->prepare($sql);
$likeTerm = "%" . $term . "%";
$stmt->bind_param("s", $likeTerm);
$stmt->execute();
$result = $stmt->get_result();

$suggestions = [];
while ($row = $result->fetch_assoc()) {
    $suggestions[] = [
        "label" => $row['name'],      // what user sees in dropdown
        "value" => $row['name'],      // what gets filled in input
        "asset_tag" => $row['asset_tag'] // autofill asset tag
    ];
}

echo json_encode($suggestions);
?>
