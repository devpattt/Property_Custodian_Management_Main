<?php
session_start();
$userRole = $_SESSION['user_type'] ?? 'guest';
if (isset($_SESSION['otp'])) {
    $entered_otp = '';
    for ($i = 1; $i <= 6; $i++) {
        $entered_otp .= $_POST['otp' . $i]; 
    }

    if ($_SESSION['otp'] == $entered_otp) {
        $_SESSION['loggedin'] = true; 
        unset($_SESSION['otp']); 
        if($userRole === 'admin'){
            header('Location: ../User_Admin/dashboard.php'); 
        } elseif($userRole === 'teacher'){
            header('Location: ../User_Teachers/dashboard_teacher.php');
        } elseif($userRole === 'custodian'){
            header('Location: ../User_Custodians/dashboard_custodians.php');
        } else {
            header('Location: ../dashboard.php');
        }
        exit();
    } else {
        $_SESSION['otp_error'] = "Invalid OTP. Please try again.";
        $_SESSION['show_otp_modal'] = true; 
        header('Location: index.php');
        exit();
    }
} else {
    $_SESSION['otp_error'] = "No OTP sent!";
    $_SESSION['show_otp_modal'] = true; 
    header('Location: index.php');
    exit();
}