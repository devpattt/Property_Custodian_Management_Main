<?php
session_start();
include '../connection.php';
if (!isset($_SESSION['user_id'])) {
    exit("<p class='text-danger'>Session expired. Please login again.</p>");
}

if (!isset($_GET['id'])) {
    exit("<p class='text-danger'>Invalid request - No ID provided.</p>");
}

$report_id = intval($_GET['id']);
$user_id = $_SESSION['user_id'];
error_log("Report ID: $report_id, User ID: $user_id");

$stmt = $conn->prepare("SELECT * FROM bcp_sms4_reports WHERE id = ? AND assigned_to = ?");
if (!$stmt) {
    exit("<p class='text-danger'>Database prepare error: " . $conn->error . "</p>");
}

$stmt->bind_param("ii", $report_id, $user_id);
if (!$stmt->execute()) {
    exit("<p class='text-danger'>Database execute error: " . $stmt->error . "</p>");
}

$result = $stmt->get_result();
$report = $result->fetch_assoc();

if (!$report) {
    exit("<p class='text-danger'>Report not found or not assigned to you. Report ID: $report_id, User ID: $user_id</p>");
}

if (!defined('BASE_URL')) {
    $base_url = (isset($_SERVER['HTTPS']) ? 'https' : 'http') . '://' . $_SERVER['HTTP_HOST'] . '//';
} else {
    $base_url = BASE_URL;
}
?>

<form method="post" action="<?= $base_url ?>User_Custodians/update_status.php">
    <input type="hidden" name="id" value="<?= $report['id'] ?>">
    
    <div class="mb-3">
        <p><b>Item:</b> <?= htmlspecialchars($report['asset']) ?></p>
        <p><b>Description:</b> <?= htmlspecialchars($report['description']) ?></p>
        <p><b>Type:</b> <?= ucfirst($report['report_type']) ?></p>
        <p><b>Current Status:</b> <?= ucfirst($report['status']) ?></p>
    </div>

    <div class="mb-3">
        <label for="status" class="form-label">Update Status:</label>
        <select name="status" id="status" class="form-select" required>
            <option value="Pending" <?= $report['status'] == "Pending" ? "selected" : "" ?>>Pending</option>
            <option value="In-Progress" <?= $report['status'] == "In-Progress" ? "selected" : "" ?>>In-Progress</option>
            <option value="Resolved" <?= $report['status'] == "Resolved" ? "selected" : "" ?>>Resolved</option>
            <option value="Rejected" <?= $report['status'] == "Rejected" ? "selected" : "" ?>>Rejected</option>
        </select>
    </div>

    <div class="mb-3">
        <button type="submit" class="btn btn-primary">Update Report</button>
        <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancel</button>
    </div>
</form>