<?php
session_start();
include '../connection.php';

if($_SERVER['REQUEST_METHOD'] === 'POST'){
    $report_id = intval($_POST['report_id']);
    $status = $_POST['status'];
    $custodian_id = (int) $_SESSION['user_id'];

    $stmt = $conn->prepare("UPDATE bcp_sms4_reports SET status = ? WHERE id = ? AND assigned_to = ?");
    $stmt->bind_param("sii", $status, $report_id, $custodian_id);

    if($stmt->execute()){
        header("Location: custodian_reports.php?success=1");
        exit;
    } else {
        echo "Error Updating report.";
    }
}
?>