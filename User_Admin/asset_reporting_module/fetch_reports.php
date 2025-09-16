<?php
session_start();
include '../../connection.php';  

if(!isset($_GET['id'])){
    header("Location: reporting_management.php");
    exit;
}

$report_id = intval($_GET['id']);
$stmt = $conn->prepare("SELECT r.*, u.username AS teacher, c.username AS custodian FROM bcp_sms4_reports r JOIN bcp_sms4_admins u ON r.reported_by = u.id LEFT JOIN bcp_sms4_admins c ON r.assigned_to = c.id WHERE r.id = ?");
$stmt->bind_param("i", $report_id);
$stmt->execute();
$report = $stmt->get_result()->fetch_assoc();
?>
    <?php if($report):?>
    <div class="card border-0 shadow-sm">
    <div class="card-body">
    <table class="table table-bordered">
    <tr><th>Item Name</th><td> <?= htmlspecialchars($report['asset'])?></td></tr>
    <tr><th>Report Type</th><td><?=htmlspecialchars($report['report_type'])?></td></tr>
    <tr><th>Reported By</th><td><?=htmlspecialchars($report['teacher'])?></td></tr>
    <tr><th>Date Reported</th><td><?=htmlspecialchars($report['date_reported'])?></td></tr>
    <tr><th>Status</th><td><span class="badge bg-primary"><?=htmlspecialchars($report['status'])?></span></td></tr>
        <?php if (!empty($report['description'])): ?>
        <tr><th>Description</th><td><?=nl2br(htmlspecialchars($report['description']))?></td></tr>
        <?php endif;?>
    <tr><th>Action</th>
    <td colspan="2">
        <form method="post" action="get_reports.php">
            <input type="hidden" name="report_id" value="<?= $report['id'] ?>">
            <select name="assigned_to" required>
                <option value="">-- Assign Custodian --</option>
                <?php
                $custodians = $conn->query("SELECT id, username FROM bcp_sms4_admins WHERE user_type = 'custodian'");
                while ($c = $custodians->fetch_assoc()):
                ?>
                    <option value="<?= $c['id'] ?>"><?= htmlspecialchars($c['username'])?></option>
                <?php endwhile; ?>
            </select>
            <div style="display: flex;">
            <button name="action" value="approve" class="btn btn-sm btn-success">Approve</button>
            <button name="action" value="reject" class="btn btn-sm btn-danger">Reject</button>
            </div>
        </form>
    </td>
    </tr>
    <?php else:?>
    <div class='alert alert-warning'>No report found.</div>
    <?php endif;?>
    
