<?php
include '../connection.php';

$data = [];

$sql_inprogress = "SELECT COUNT(*) AS total FROM bcp_sms4_reports WHERE status = 'In-Progress'";
$result_inprogress = $conn->query($sql_inprogress);
$data['inprogress'] = $result_inprogress->fetch_assoc()['total'] ?? 0;

$sql_completed = "SELECT COUNT(*) AS total FROM bcp_sms4_reports WHERE status = 'Resolved'";
$result_completed = $conn->query($sql_completed);
$data['completed'] = $result_completed->fetch_assoc()['total'] ?? 0;

$sql_schedules = "SELECT COUNT(*) AS total FROM bcp_sms4_scheduling";
$result_schedules = $conn->query($sql_schedules);
$data['schedules'] = $result_schedules->fetch_assoc()['total'] ?? 0;


header('Content-Type: application/json');
echo json_encode($data);
