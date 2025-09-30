<?php
session_start();
include '../../connection.php';

if (isset($_POST['report_id'], $_POST['action'])) {
    $report_id = $_POST['report_id'];

    if ($_POST['action'] === 'approve' && !empty($_POST['assigned_to'])) {
        $custodian_id = $_POST['assigned_to'];
        $status = 'In-Progress';

        $stmt = $conn->prepare("UPDATE bcp_sms4_reports SET status = ?, assigned_to = ? WHERE id = ?");
        $stmt->bind_param("sii", $status, $custodian_id, $report_id);

        if ($stmt->execute()) {
            $_SESSION['toast_success'] = "Approve Successfully!";
        }

    } elseif ($_POST['action'] === 'reject') {
        $status = 'Rejected';
        $stmt = $conn->prepare("UPDATE bcp_sms4_reports SET status = ? WHERE id = ?");
        $stmt->bind_param("si", $status, $report_id);

        if ($stmt->execute()) {
            $_SESSION['toast_error'] = "Report Rejected!";
        }
    }
    $stmt->execute();
}

header("Location: reporting_management.php");
exit;
?>
