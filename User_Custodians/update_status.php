<?php
session_start();
include '../connection.php';

if (!isset($_SESSION['user_id'])) {
    exit("Unauthorized access.");
}

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $report_id = intval($_POST['report_id']);
    $status    = $_POST['status'];
    $user_id   = $_SESSION['user_id'];

    $valid_statuses = ['Pending', 'In-Progress', 'Resolved', 'Rejected'];
    if (!in_array($status, $valid_statuses)) {
        header("Location: custodian_reports.php?updated=0");
        exit;
    }

    $stmt = $conn->prepare("UPDATE bcp_sms4_reports SET status = ?, date_reported = NOW() WHERE id = ? AND assigned_to = ?");
    $stmt->bind_param("sii", $status, $report_id, $user_id);

    if ($stmt->execute()) {
        header("Location: custodian_reports.php?updated=1");
        exit;
    } else {
        header("Location: custodian_reports.php?updated=0"); 
        exit;
    }
}
?>
