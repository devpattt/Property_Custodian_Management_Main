<?php
session_start();
include '../../connection.php';

$teacher_id   = $_SESSION['user_id'];
$request_type = $_POST['request_type'];
$asset_id     = !empty($_POST['asset_id']) ? intval($_POST['asset_id']) : NULL;
$consumable_id= !empty($_POST['consumable_id']) ? intval($_POST['consumable_id']) : NULL;
$quantity     = isset($_POST['quantity']) ? intval($_POST['quantity']) : 1;
$notes        = $_POST['notes'];

$stmt = $conn->prepare("
    INSERT INTO bcp_sms4_requests (teacher_id, asset_id, consumable_id, quantity, request_type, notes)
    VALUES (?, ?, ?, ?, ?, ?)
");
$stmt->bind_param("iiiiss", $teacher_id, $asset_id, $consumable_id, $quantity, $request_type, $notes);

if ($stmt->execute()) {
    header("Location: teacher_request.php?success=1");
} else {
    echo "Error: " . $stmt->error;
}
