<?php
include "../../../connect/connection.php"; // DB connection

// Detect which table is being requested
$table = isset($_GET['table']) ? $_GET['table'] : "trans"; 

// Map tab → table name
if ($table === "add") {
    $db_table = "bcp_sms4_assign_con_add_hstry";
    $date_col = "assigned_date"; // this table has assigned_date, no end_date
} else {
    $db_table = "bcp_sms4_assign_con_trans_hstry";
    $date_col = "end_date"; // this one uses end_date
}

// Default filter
$where = "";

// Apply filters
if (isset($_GET['filter'])) {
    $filter = $_GET['filter'];

    if ($filter == "day") {
        $where = "WHERE DATE($date_col) = CURDATE()";
    } elseif ($filter == "week") {
        $where = "WHERE YEARWEEK($date_col, 1) = YEARWEEK(CURDATE(), 1)";
    } elseif ($filter == "month") {
        $month = isset($_GET['month']) ? (int)$_GET['month'] : (int)date("m");
        $year  = isset($_GET['year'])  ? (int)$_GET['year']  : (int)date("Y");
        $where = "WHERE MONTH($date_col) = $month AND YEAR($date_col) = $year";
    } elseif ($filter == "year") {
        $year = isset($_GET['year']) ? (int)$_GET['year'] : (int)date("Y");
        $where = "WHERE YEAR($date_col) = $year";
    }
}

// Count rows for the correct table
$sql = "SELECT COUNT(*) AS total FROM $db_table $where";
$result = $conn->query($sql);
$row = $result->fetch_assoc();
$total_assets = $row ? $row['total'] : 0;
?>
