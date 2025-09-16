<?php
include 'connection.php';

$activities = [];
$sql = "SELECT module, action, description, created_at 
        FROM bcp_sms4_activity 
        ORDER BY created_at DESC 
        LIMIT 20";

$result = $conn->query($sql);

if($result && $result->num_rows > 0){
    while($row = $result->fetch_assoc()){
        $activities[] = $row;
    }
}

// Return JSON
header('Content-Type: application/json');
echo json_encode($activities);

$conn->close();
?>
