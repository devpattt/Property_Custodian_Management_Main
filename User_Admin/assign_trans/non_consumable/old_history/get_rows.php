<?php
include "../../../connect/connection.php"; // your DB connection

// Count assets
$sql = "SELECT COUNT(*) AS total FROM bcp_sms4_assign_old_history";
$result = $conn->query($sql);
$row = $result->fetch_assoc();
$total_assets = $row['total'];
//echo "Total assets: " . $total_assets;

// Default query (show all if no filter)

$where = "";

// Check filter
if (isset($_GET['filter'])) {
    $filter = $_GET['filter'];

    if ($filter == "day") {
        $where = "WHERE DATE(end_date) = CURDATE()";
    } elseif ($filter == "week") {
        $where = "WHERE YEARWEEK(end_date, 1) = YEARWEEK(CURDATE(), 1)";
    } elseif ($filter == "month") {
        $month = isset($_GET['month']) ? (int)$_GET['month'] : (int)date("m");
        $year  = isset($_GET['year'])  ? (int)$_GET['year']  : (int)date("Y");
        $where = "WHERE MONTH(end_date) = $month AND YEAR(end_date) = $year";
    } elseif ($filter == "year") {
        $year = isset($_GET['year']) ? (int)$_GET['year'] : (int)date("Y");
        $where = "WHERE YEAR(end_date) = $year";
    }
}



?>