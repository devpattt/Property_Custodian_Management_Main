<?php
session_start();
include "../../../../connection.php";

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $issuance_id = intval($_POST['issuance_id']);
    $to_teacher_id = intval($_POST['to_teacher_id']);
    $remarks = trim($_POST['remarks'] ?? '');
    $admin_id = intval($_SESSION['user_id']); // logged in admin

    // 1. Get current custodian
    $stmt = $conn->prepare("SELECT teacher_id FROM bcp_sms4_issuance WHERE id = ?");
    $stmt->bind_param("i", $issuance_id);
    $stmt->execute();
    $stmt->bind_result($from_teacher_id);
    $stmt->fetch();
    $stmt->close();

    if (!$from_teacher_id) {
        die("Invalid issuance ID");
    }

    // 2. Update issuance with new custodian
    $stmt = $conn->prepare("UPDATE bcp_sms4_issuance SET teacher_id = ? WHERE id = ?");
    $stmt->bind_param("ii", $to_teacher_id, $issuance_id);
    $stmt->execute();
    $stmt->close();

    // 3. Insert into transfer history
    $stmt = $conn->prepare("
        INSERT INTO bcp_sms4_custodian_transfers 
            (issuance_id, from_teacher_id, to_teacher_id, transferred_by, remarks) 
        VALUES (?, ?, ?, ?, ?)
    ");
    $stmt->bind_param("iiiis", $issuance_id, $from_teacher_id, $to_teacher_id, $admin_id, $remarks);
    $stmt->execute();
    $stmt->close();

    header("Location: active.php?transfer=success");
    exit;
}
?>



 
