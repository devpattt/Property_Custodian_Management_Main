<?php
session_start();
include '../connection.php';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $teacher_id  = $_SESSION['user_id'];  
    $item_id     = intval($_POST['item_id']);   
    $report_type = $_POST['report_type'];
    $description = $_POST['description'];
    $photo       = null;

    // Handle file upload
    if (isset($_FILES['photo']) && $_FILES['photo']['error'] === 0) {
        $target_dir = __DIR__ . "/uploads/";  // absolute path to be safe
        if (!is_dir($target_dir)) {
            mkdir($target_dir, 0777, true);
        }
        $photo = time() . "_" . basename($_FILES['photo']['name']);
        $target_file = $target_dir . $photo;

        if (!move_uploaded_file($_FILES['photo']['tmp_name'], $target_file)) {
            $photo = null; // reset if upload failed
        }
    }

    // Insert into reports table
    $stmt = $conn->prepare("
        INSERT INTO bcp_sms4_reports (reported_by, item_id, report_type, description, evidence) 
        VALUES (?, ?, ?, ?, ?)
    ");
    $stmt->bind_param("iisss", $teacher_id, $item_id, $report_type, $description, $photo);

    if ($stmt->execute()) {
        header("Location: track_reports.php?success=1");
        exit;
    } else {
        echo "Error: " . $conn->error;
    }
}
?>
