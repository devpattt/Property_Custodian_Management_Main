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
$user_id   = $_SESSION['user_id'];

$stmt = $conn->prepare("
    SELECT r.*, 
           a.property_tag,
           i.item_name AS asset_item_name,
           ic.item_name AS consumable_item_name
    FROM bcp_sms4_reports r
    LEFT JOIN bcp_sms4_asset a ON r.asset_id = a.asset_id
    LEFT JOIN bcp_sms4_items i ON a.item_id = i.item_id
    LEFT JOIN bcp_sms4_consumable c ON r.id = c.id
    LEFT JOIN bcp_sms4_items ic ON c.item_id = ic.item_id
    WHERE r.id = ? AND r.assigned_to = ?
");
if (!$stmt) {
    exit("<p class='text-danger'>Database prepare error: " . $conn->error . "</p>");
}

$stmt->bind_param("ii", $report_id, $user_id);
if (!$stmt->execute()) {
    exit("<p class='text-danger'>Database execute error: " . $stmt->error . "</p>");
}

$report = $stmt->get_result()->fetch_assoc();
if (!$report) {
    exit("<p class='text-danger'>Report not found or not assigned to you.</p>");
}

if (!defined('BASE_URL')) {
    $base_url = (isset($_SERVER['HTTPS']) ? 'https' : 'http') . '://' . $_SERVER['HTTP_HOST'] . '/Property_Custodian_Management/';
} else {
    $base_url = BASE_URL;
}
?>

<form method="post" action="<?= $base_url ?>User_Custodians/update_status.php">
    <input type="hidden" name="report_id" value="<?= $report['id'] ?>">

    <div class="mb-3">
        <p><b>Item:</b>
            <?php if ($report['property_tag']): ?>
                <?= htmlspecialchars($report['asset_item_name']) ?> - <?= htmlspecialchars($report['asset_item_name']) ?>
            <?php elseif ($report['consumable_item_name']): ?>
                <?= htmlspecialchars($report['consumable_item_name']) ?>
            <?php else: ?>
                <em>Unknown Item</em>
            <?php endif; ?>
        </p>
        <p><b>Description:</b> <?= htmlspecialchars($report['description']) ?></p>
        <p><b>Type:</b> <?= ucfirst($report['report_type']) ?></p>
        <p><b>Current Status:</b> <?= ucfirst($report['status']) ?></p>
    </div>

    <div class="mb-3">
        <label for="status" class="form-label">Update Status:</label>
        <select name="status" id="status" class="form-select" required>
            <option value="Pending"     <?= $report['status'] == "Pending" ? "selected" : "" ?>>Pending</option>
            <option value="In-Progress" <?= $report['status'] == "In-Progress" ? "selected" : "" ?>>In-Progress</option>
            <option value="Resolved"    <?= $report['status'] == "Resolved" ? "selected" : "" ?>>Resolved</option>
            <option value="Rejected"    <?= $report['status'] == "Rejected" ? "selected" : "" ?>>Rejected</option>
        </select>
    </div>

    <div class="mb-3">
        <button type="submit" class="btn btn-primary">Update Report</button>
        <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancel</button>
    </div>
</form>
