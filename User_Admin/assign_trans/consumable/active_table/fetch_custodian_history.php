<?php
session_start();
include "../../../../connection.php";

header('Content-Type: application/json');

$issuance_id = intval($_POST['issuance_id'] ?? 0);

if ($issuance_id <= 0) {
    echo json_encode([]);
    exit;
}

// Fetch transfer history safely
$query = "
SELECT DISTINCT
    ct.id,
    ct.transfer_date,
    ct.remarks,
    f.fullname AS from_teacher,
    t.fullname AS to_teacher,
    a.fullname AS transferred_by
FROM bcp_sms4_custodian_transfers ct
LEFT JOIN bcp_sms4_admins f ON ct.from_teacher_id = f.id
LEFT JOIN bcp_sms4_admins t ON ct.to_teacher_id = t.id
LEFT JOIN bcp_sms4_admins a ON ct.transferred_by = a.id
WHERE ct.issuance_id = ?
ORDER BY ct.transfer_date DESC
";

$stmt = $conn->prepare($query);
$stmt->bind_param("i", $issuance_id);
$stmt->execute();
$result = $stmt->get_result();

$history = [];
while ($row = $result->fetch_assoc()) {
    $history[] = [
        'id' => $row['id'],
        'from_teacher' => $row['from_teacher'] ?? '-',
        'to_teacher' => $row['to_teacher'] ?? '-',
        'transfer_date' => date('Y/m/d H:i', strtotime($row['transfer_date'])),
        'transferred_by' => $row['transferred_by'] ?? '-',
        'remarks' => $row['remarks'] ?? '-'
    ];
}

echo json_encode($history);
