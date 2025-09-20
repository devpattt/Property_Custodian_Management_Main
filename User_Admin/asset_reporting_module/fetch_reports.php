<?php
session_start();
include '../../connection.php';  

if(!isset($_GET['id'])){
    echo "<div class='alert alert-warning'>No report ID provided.</div>";
    exit;
}

$report_id = intval($_GET['id']);

// Join reports with asset and item to fetch asset tag and item name
$stmt = $conn->prepare("
    SELECT r.*, u.username AS teacher, c.username AS custodian,
           a.property_tag, i.item_name
    FROM bcp_sms4_reports r
    JOIN bcp_sms4_admins u ON r.reported_by = u.id
    LEFT JOIN bcp_sms4_admins c ON r.assigned_to = c.id
    LEFT JOIN bcp_sms4_asset a ON r.asset_id = a.asset_id
    LEFT JOIN bcp_sms4_items i ON a.item_id = i.item_id
    WHERE r.id = ?
");
$stmt->bind_param("i", $report_id);
$stmt->execute();
$report = $stmt->get_result()->fetch_assoc();
?>
   <?php if($report): ?>
<div class="card border-0 shadow-sm">
<div class="card-body">
<table class="table table-bordered">
    <tr>
        <th>Asset Tag</th>
        <td><?= htmlspecialchars($report['property_tag'] ?? 'N/A') ?></td>
    </tr>
    <tr>
        <th>Item Name</th>
        <td><?= htmlspecialchars($report['item_name'] ?? 'N/A') ?></td>
    </tr>
    <tr>
        <th>Report Type</th>
        <td><?= htmlspecialchars($report['report_type']) ?></td>
    </tr>
    <tr>
        <th>Reported By</th>
        <td><?= htmlspecialchars($report['teacher']) ?></td>
    </tr>
    <tr>
        <th>Date Reported</th>
        <td><?= htmlspecialchars($report['date_reported']) ?></td>
    </tr>
    <tr>
        <th>Status</th>
        <td>
            <?php 
            $statusClass = match($report['status']) {
                'Resolved'    => 'badge bg-success',
                'In-Progress' => 'badge bg-warning',
                'Pending'     => 'badge bg-primary',
                'Rejected'    => 'badge bg-danger',
                default       => 'badge bg-secondary'
            };
            ?>
            <span class="<?= $statusClass ?>"><?= htmlspecialchars($report['status']) ?></span>
        </td>
    </tr>
    <?php if (!empty($report['description'])): ?>
    <tr>
        <th>Description</th>
        <td><?= nl2br(htmlspecialchars($report['description'])) ?></td>
    </tr>
    <?php endif; ?>
    <tr>
        <th>Action</th>
        <td colspan="2">
            <form method="post" action="get_reports.php">
                <input type="hidden" name="report_id" value="<?= $report['id'] ?>">
                <select name="assigned_to" required class="form-select mb-2">
                    <option value="">-- Assign Custodian --</option>
                    <?php
                    $custodians = $conn->query("SELECT id, username FROM bcp_sms4_admins WHERE user_type = 'custodian'");
                    while ($c = $custodians->fetch_assoc()):
                    ?>
                        <option value="<?= $c['id'] ?>"><?= htmlspecialchars($c['username'])?></option>
                    <?php endwhile; ?>
                </select>
                <div class="d-flex gap-2">
                    <button name="action" value="approve" class="btn btn-sm btn-success">Approve</button>
                    <button name="action" value="reject" class="btn btn-sm btn-danger">Reject</button>
                </div>
            </form>
        </td>
    </tr>
</table>
</div>
</div>
<?php else: ?>
<div class='alert alert-warning'>No report found.</div>
<?php endif; ?>
    
