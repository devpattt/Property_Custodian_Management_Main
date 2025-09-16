<?php
session_start();
    include '../connection.php';
    
    if($_SERVER['REQUEST_METHOD'] === 'POST'){
        $teacher_id = $_SESSION['user_id'];
        $item_name = $_POST['item_name'];
        $report_type = $_POST['report_type'];
        $description = $_POST['description'];
        $photo = null;
        
        if(isset($_FILES['photo']) && $FILES['photo']['error'] == 0){
            $target_dir = "uploads/";
            if(!is_dir($target_dir)){
                mkdir($target_dir, 0777, true);
            }
            $photo = time() . "_" . basename($_FILES['photo']['name']);
            $target_file = $target_dir . $photo;
            
            if(!move_uploaded_file($_FILES['photo']['top_name'], $target_file)){
                $photo = null;
            }
        }

        $stmt = $conn->prepare("INSERT INTO bcp_sms4_reports (reported_by, asset, report_type, description, evidence) VALUES(?, ?, ?, ?, ?)");
        $stmt->bind_param("issss", $teacher_id, $item_name, $report_type, $description, $photo);

        if($stmt->execute()){
            header("Location: track_reports.php?success=1");
            exit;
        } else {
            echo "Error: " . $conn->error;
        }

    }
?>

