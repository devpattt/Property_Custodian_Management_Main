<?php
include '../connection.php';

$filter = $_GET['filter'] ?? 'month'; 

$where = "";
if ($filter === "today") {
    $where = "WHERE DATE(created_at) = CURDATE()";
} elseif ($filter === "month") {
    $where = "WHERE MONTH(created_at) = MONTH(CURDATE()) AND YEAR(created_at) = YEAR(CURDATE())";
} elseif ($filter === "year") {
    $where = "WHERE YEAR(created_at) = YEAR(CURDATE())";
}

$sql = "
    SELECT report_type, COUNT(*) as total
    FROM bcp_sms4_reports
    $where
    GROUP BY report_type
";

$result = $conn->query($sql);

$data = [
    'Lost' => 0,
    'Damaged' => 0,
    'Repair/Replacement' => 0
];

if ($result && $result->num_rows > 0) {
    while ($row = $result->fetch_assoc()) {
        $data[$row['report_type']] = (int)$row['total'];
    }
}

$conn->close();

header('Content-Type: application/json');
echo json_encode($data);
