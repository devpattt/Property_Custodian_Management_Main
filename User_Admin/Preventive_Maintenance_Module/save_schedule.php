<?php
header('Content-Type: application/json');
include '../connection.php';

$data = json_decode(file_get_contents('php://input'), true);

$asset = $conn->real_escape_string($data['asset'] ?? '');
$type = $conn->real_escape_string($data['type'] ?? '');
$frequency = $conn->real_escape_string($data['frequency'] ?? '');
$personnel = $conn->real_escape_string($data['personnel'] ?? '');
$start_date = $conn->real_escape_string($data['date'] ?? '');

if (!$asset || !$type || !$frequency || !$personnel || !$start_date) {
    echo json_encode(["status" => "error", "message" => "All fields are required"]);
    exit;
}

$sql = "INSERT INTO bcp_sms4_scheduling (asset, type, frequency, personnel, start_date)
        VALUES ('$asset', '$type', '$frequency', '$personnel', '$start_date')";

if ($conn->query($sql)) {
    echo json_encode(["status" => "success", "message" => "Maintenance schedule saved successfully"]);
} else {
    echo json_encode(["status" => "error", "message" => "Error saving schedule: " . $conn->error]);
}

$conn->close();
?>
