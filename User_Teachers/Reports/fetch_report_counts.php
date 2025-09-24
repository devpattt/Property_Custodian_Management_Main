<?php

ini_set('display_errors', 0);
error_reporting(0);

include '../../connection.php';

$sql = "
    SELECT status, COUNT(*) as total
    FROM bcp_sms4_reports
    GROUP BY status
";

$result = $conn->query($sql);

$counts = [
    'Pending'     => 0,
    'In-Progress' => 0,
    'Resolved'    => 0
];

if ($result && $result->num_rows > 0) {
    while ($row = $result->fetch_assoc()) {
        if (isset($counts[$row['status']])) {
            $counts[$row['status']] = $row['total'];
        }
    }
}

$conn->close();

header('Content-Type: application/json');
echo json_encode($counts);
