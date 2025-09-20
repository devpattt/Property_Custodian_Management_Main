<?php
session_start();
include "../connection.php";

if($_SERVER['REQUEST_METHOD'] === 'POST'){
    $teacher_id = $_SESSION['user_id'];
    $asset_id = $_POST['asset_id'];
    $report_type = $_POST['report_type'];
    $description = $_POST['description'];
    $photo = null;

    // Handle optional photo upload
    if(isset($_FILES['photo']) && $_FILES['photo']['error'] == 0){
        $target_dir = "../uploads/";
        if(!is_dir($target_dir)){
            mkdir($target_dir, 0777, true);
        }
        $photo = time() . "_" . basename($_FILES['photo']['name']);
        $target_file = $target_dir . $photo;
        if(!move_uploaded_file($_FILES['photo']['tmp_name'], $target_file)){
            $photo = null;
        }
    }

    // Insert report
    $stmt = $conn->prepare("INSERT INTO bcp_sms4_reports (reported_by, asset_id, report_type, description, evidence, status) VALUES(?, ?, ?, ?, ?, 'Pending')");
    $stmt->bind_param("iisss", $teacher_id, $asset_id, $report_type, $description, $photo);

    if($stmt->execute()){
        header("Location: track_reports.php?success=1");
        exit;
    } else {
        echo "Error: " . $conn->error;
    }
}
?>
