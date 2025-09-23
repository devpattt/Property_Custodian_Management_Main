<?php
include '../connection.php';

$sql = "
    SELECT DATE(date_reported) as report_date, report_type, COUNT(*) as total
    FROM bcp_sms4_reports
    GROUP BY DATE(date_reported), report_type
    ORDER BY report_date ASC
";

$result = $conn->query($sql);

$data = [
    'Lost' => [],
    'Damaged' => [],
    'Repair/Replacement' => []
];
$dates = [];

if ($result && $result->num_rows > 0) {
    while ($row = $result->fetch_assoc()) {
        $date = $row['report_date'];
        if (!in_array($date, $dates)) {
            $dates[] = $date;
        }
        $data[$row['report_type']][$date] = (int)$row['total'];
    }
}

$conn->close();

$final = [
    'dates' => $dates,
    'series' => []
];

foreach ($data as $type => $values) {
    $seriesData = [];
    foreach ($dates as $d) {
        $seriesData[] = $values[$d] ?? 0;
    }
    $final['series'][] = [
        'name' => $type,
        'data' => $seriesData
    ];
}

header('Content-Type: application/json');
echo json_encode($final);
