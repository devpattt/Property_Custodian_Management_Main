<?php
include '../connection.php';

header('Content-Type: application/json');

$sql = "SELECT DATE(date_reported) as report_date, COUNT(*) as total_reports
        FROM bcp_sms4_reports
        WHERE MONTH(date_reported) = MONTH(CURDATE())
          AND YEAR(date_reported) = YEAR(CURDATE())
        GROUP BY DATE(date_reported)
        ORDER BY report_date ASC";

$result = $conn->query($sql);

$dates = [];
$counts = [];

if ($result && $result->num_rows > 0) {
    while ($row = $result->fetch_assoc()) {
        $dates[] = $row['report_date'];
        $counts[] = (int)$row['total_reports'];
    }
}

echo json_encode([
    "dates" => $dates,
    "counts" => $counts
]);
