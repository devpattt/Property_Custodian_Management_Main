<?php
include '../connection.php';

$dates = [];
$incidentReports = [];
$assignedItems = [];
$unassignedItems = [];

for ($i = 6; $i >= 0; $i--) {
    $date = date('Y-m-d', strtotime("-$i days"));
    $dates[] = $date;

    $res = $conn->query("SELECT COUNT(*) as cnt FROM bcp_sms4_reports WHERE DATE(date_reported) = '$date'");
    $row = $res->fetch_assoc();
    $incidentReports[] = (int)$row['cnt'];

    $res2 = $conn->query("SELECT COUNT(*) as cnt FROM bcp_sms4_assign_history WHERE DATE(assigned_date) = '$date'");
    $row2 = $res2->fetch_assoc();
    $assignedItems[] = (int)$row2['cnt'];

    $res3 = $conn->query("SELECT COUNT(*) as cnt FROM bcp_sms4_asset WHERE assigned_to IS NULL AND DATE(date_registered) = '$date'");
    $row3 = $res3->fetch_assoc();
    $unassigned = (int)$row3['cnt'];

    $res4 = $conn->query("SELECT COUNT(*) as cnt FROM bcp_sms4_consumable WHERE DATE(date_received) = '$date'");
    $row4 = $res4->fetch_assoc();
    $unassigned += (int)$row4['cnt'];

    $unassignedItems[] = $unassigned;
}

echo json_encode([
    'dates' => $dates,
    'incidentReports' => $incidentReports,
    'assignedItems' => $assignedItems,
    'unassignedItems' => $unassignedItems
]);
?>
