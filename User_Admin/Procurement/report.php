<?php
require_once "../../connection.php";

if (!isset($_GET['type'])) {
    http_response_code(400); 
    die("Invalid report type"); 
}

$type = $_GET['type'];
$date_condition = "";
$report_title_suffix = ""; 
$params = [];
$param_types = "";

function formatDate($dateString) {
    return date("F j, Y", strtotime($dateString));
}

switch ($type) {
    case "today":
        $date_condition = "DATE(p.created_at) = DATE(NOW())"; 
        $report_title_suffix = formatDate("today");
        break;

    case "week":
        $date_condition = "YEARWEEK(p.created_at, 1) = YEARWEEK(CURDATE(), 1)";
 
        $start_of_week = date("Y-m-d", strtotime('monday this week'));
        $end_of_week = date("Y-m-d", strtotime('sunday this week'));

        $report_title_suffix = formatDate($start_of_week) . " to " . formatDate($end_of_week);
        break;

    case "month":
        if (isset($_GET['month_select']) && preg_match('/^\d{4}-\d{2}$/', $_GET['month_select'])) {
            $month_year = $_GET['month_select'];
            list($year, $month) = explode('-', $month_year); 
            
            $date_condition = "YEAR(p.created_at) = ? AND MONTH(p.created_at) = ?";
            $params[] = $year;
            $params[] = $month;
            $param_types = "ss";

            $report_title_suffix = date("F, Y", strtotime($month_year . "-01")); 
            
        } else {

            $date_condition = "YEAR(p.created_at) = YEAR(CURDATE()) AND MONTH(p.created_at) = MONTH(CURDATE())";
            $report_title_suffix = date("F, Y");
        }
        break;

    case "year":
        if (isset($_GET['year_select']) && preg_match('/^\d{4}$/', $_GET['year_select'])) {
            $year = $_GET['year_select'];
            
            $date_condition = "YEAR(p.created_at) = ?";
            $params[] = $year;
            $param_types = "s";
  
            $report_title_suffix = "Year of " . htmlspecialchars($year);
        } else {
            $current_year = date("Y");
            $date_condition = "YEAR(p.created_at) = YEAR(CURDATE())";
            $report_title_suffix = "Year of " . $current_year;
        }
        break;
        
    default:
        http_response_code(400); 
        die("Invalid report type");
}

$sql_query = "SELECT 
                p.*, 
                i.item_name, 
                i.category 
              FROM bcp_sms4_procurement p
              JOIN bcp_sms4_items i ON p.item_id = i.item_id
              WHERE {$date_condition}
              ORDER BY p.procurement_id DESC";

if (!empty($params)) {
    $stmt = $conn->prepare($sql_query);
    $stmt->bind_param($param_types, ...$params);
    $stmt->execute();
    $result = $stmt->get_result();
} else {
    $result = $conn->query($sql_query);
}

$statusMap = [
    "Pending" => "Pending",
    "Approved" => "In-Transit",
    "Rejected" => "Delivered",
    "Completed" => "Completed"
];
?>
<div id="report-content-to-print">
    <h2 class="text-center mb-4">Procurement Report (<?= htmlspecialchars($report_title_suffix) ?>)</h2> 
    
    <table class="table table-bordered table-striped">
        <thead>
            <tr>
                <th>ID</th>
                <th>Item Name</th> 
                <th>Category</th>
                <th>Quantity</th>
                <th>Requested By</th>
                <th>Approved By</th>
                <th>Expected Date</th>
                <th>Status</th>
                <th>Created At</th>
            </tr>
        </thead>
        <tbody>
            <?php if ($result && $result->num_rows > 0): ?>
                <?php while ($row = $result->fetch_assoc()): ?>
                    <tr>
                        <td><?= htmlspecialchars($row['procurement_id']) ?></td>
                        <td><?= htmlspecialchars($row['item_name']) ?></td>
                        <td><?= htmlspecialchars($row['category']) ?></td>
                        <td><?= htmlspecialchars($row['quantity']) ?></td>
                        <td><?= htmlspecialchars($row['requested_by']) ?></td>
                        <td><?= htmlspecialchars($row['approved_by']) ?></td>
                        <td><?= htmlspecialchars($row['expected_date']) ?></td>
                        <td><?= htmlspecialchars($statusMap[$row['status']] ?? $row['status']) ?></td>
                        <td><?= htmlspecialchars($row['created_at']) ?></td>
                    </tr>
                <?php endwhile; ?>
            <?php else: ?>
                <tr>
                    <td colspan="9" class="text-center">No procurement records found for this period.</td>
                </tr>
            <?php endif; ?>
        </tbody>
    </table>
</div>