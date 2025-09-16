<?php
header('Content-Type: application/json'); 
include 'connection.php';  

$sql = "SELECT start_date, asset, type, frequency, personnel FROM bcp_sms4_scheduling ORDER BY start_date"; 
$result = $conn->query($sql);  

$schedules = []; 
if ($result->num_rows > 0) {     
    while($row = $result->fetch_assoc()) {         
        $schedules[] = array(
            'start_date' => $row['start_date'],
            'asset' => $row['asset'],
            'type' => $row['type'],
            'frequency' => $row['frequency'],
            'personnel' => $row['personnel']
        );     
    } 
}  

echo json_encode($schedules); 
$conn->close(); 
?>
