<?php
include '../../connection.php';
session_start();

$auditorName = $_SESSION['user_name'] ?? $_SESSION['username'] ?? 'Admin';

// Schedule audit
if (isset($_POST['schedule_audit'])) {
    $audit_date = $_POST['audit_date'];
    $department_id = $_POST['department_id']; 
    $custodian_id = $_POST['custodian_id'];

    $stmt = $conn->prepare("INSERT INTO bcp_sms4_audit (audit_date, department_id, custodian_id, status) VALUES (?, ?, ?, 'Scheduled')");
    $stmt->bind_param("sii", $audit_date, $department_id, $custodian_id);
    $stmt->execute();
    $stmt->close();

    $_SESSION['toast_message'] = [
        'message' => 'Audit scheduled successfully!',
        'type' => 'success'
    ];

    header("Location: " . $_SERVER['PHP_SELF']);
    exit;
}

if (isset($_SESSION['toast_message'])) {
    $toast = $_SESSION['toast_message'];
    echo "<script>
        window.addEventListener('load', function() {
            showToast('{$toast['message']}', '{$toast['type']}');
        });
    </script>";
    unset($_SESSION['toast_message']);
}

// Start audit
if (isset($_POST['start_audit_id'])) {
    $audit_id = intval($_POST['start_audit_id']);

    $stmt = $conn->prepare("UPDATE bcp_sms4_audit SET status = 'Ongoing' WHERE id = ?");
    $stmt->bind_param("i", $audit_id);
    $stmt->execute();
    $stmt->close();

    $stmt2 = $conn->prepare("SELECT department_id, custodian_id FROM bcp_sms4_audit WHERE id = ? LIMIT 1");
    $stmt2->bind_param("i", $audit_id);
    $stmt2->execute();
    $audit = $stmt2->get_result()->fetch_assoc();
    $stmt2->close();

    if ($audit) {
        $_SESSION['current_audit'] = $audit_id;
        $_SESSION['current_department'] = $audit['department_id'];
        $_SESSION['current_custodian'] = $audit['custodian_id'];

        $_SESSION['toast_message'] = [
            'message' => "Audit #{$audit_id} started!",
            'type' => 'info'
        ];
    } else {
        $_SESSION['toast_message'] = [
            'message' => "Audit not found!",
            'type' => 'danger'
        ];
    }
    
    header("Location: " . $_SERVER['PHP_SELF']);
    exit;
}

// End audit 
if (isset($_POST['end_audit'])) {
    $audit_id = intval($_POST['audit_id']);

    $stmt = $conn->prepare("SELECT department_id, audit_date, custodian_id FROM bcp_sms4_audit WHERE id = ?");
    $stmt->bind_param("i", $audit_id);
    $stmt->execute();
    $audit = $stmt->get_result()->fetch_assoc();
    $stmt->close();

    if ($audit) {
        try {
            $conn->begin_transaction();

            $started_date = date("Y-m-d H:i:s", strtotime($audit['audit_date']));

            $stmt2 = $conn->prepare("INSERT INTO bcp_sms4_audit_history 
                                     (audit_id, department_id, started_date, completed_date, status, remarks) 
                                     VALUES (?, ?, ?, NOW(), 'Completed', ?)");
            $remarks = "Audit completed by {$auditorName} for department {$audit['department_id']}";
            $stmt2->bind_param("iiss", $audit_id, $audit['department_id'], $started_date, $remarks);
            $stmt2->execute();
            $history_id = $stmt2->insert_id;
            $stmt2->close();

            if (isset($_POST['status']) && is_array($_POST['status'])) {
                foreach ($_POST['status'] as $issuance_id => $status) {
                    if (in_array($status, ['Disposed', 'Mismatch', 'Missing'])) {
                        $condition = $_POST['asset_condition'][$issuance_id] ?? 'Good';
                        $remarks_text = $_POST['remarks'][$issuance_id] ?? '';

                        $issStmt = $conn->prepare("
                            SELECT i.asset_id, i.consumable_id, i.quantity, it.item_name
                            FROM bcp_sms4_issuance i
                            LEFT JOIN bcp_sms4_items it ON i.item_id = it.item_id
                            WHERE i.id = ?
                        ");
                        $issStmt->bind_param("i", $issuance_id);
                        $issStmt->execute();
                        $issData = $issStmt->get_result()->fetch_assoc();
                        $issStmt->close();

                        if ($issData) {
                            $asset_identifier = $issData['asset_id'] ?: $issData['consumable_id'] ?: 0;
 
                            $findStmt = $conn->prepare("
                                INSERT INTO bcp_sms4_audit_findings 
                                (history_id, asset_id, asset_name, quantity, finding_status, asset_condition, remarks) 
                                VALUES (?, ?, ?, ?, ?, ?, ?)
                            ");
                            $findStmt->bind_param(
                                "iisisss", 
                                $history_id, 
                                $asset_identifier,
                                $issData['item_name'],
                                $issData['quantity'],
                                $status,
                                $condition,
                                $remarks_text
                            );
                            $findStmt->execute();
                            $findStmt->close();
                        }
                    }
                }
            }

            $updateAuditStmt = $conn->prepare("UPDATE bcp_sms4_audit SET status = 'Completed' WHERE id = ?");
            $updateAuditStmt->bind_param("i", $audit_id);
            $updateAuditStmt->execute();
            $updateAuditStmt->close();

            $conn->commit();

            unset($_SESSION['current_audit']);
            unset($_SESSION['current_department']);

            $_SESSION['toast_message'] = [
                'message' => "Audit #{$audit_id} completed successfully!",
                'type' => 'success'
            ];

            header("Location: " . $_SERVER['PHP_SELF']);
            exit;

        } catch (Exception $e) {
            $conn->rollback();
            $_SESSION['toast_message'] = [
                'message' => 'Error ending audit: ' . $e->getMessage(),
                'type' => 'danger'
            ];
            header("Location: " . $_SERVER['PHP_SELF']);
            exit;
        }
    }
}

$upcoming_audits = $conn->query("SELECT COUNT(*) as total FROM bcp_sms4_audit WHERE audit_date >= CURDATE() AND status != 'Completed'")->fetch_assoc()['total'] ?? 0;

$last_discrepancies = $conn->query("
    SELECT COUNT(*) as total 
    FROM bcp_sms4_audit_findings 
    WHERE finding_status IN ('Disposed', 'Mismatch', 'Missing')
")->fetch_assoc()['total'] ?? 0;

$pending_replacements = $conn->query("SELECT COUNT(*) as total FROM bcp_sms4_procurement WHERE status = 'Pending'")->fetch_assoc()['total'] ?? 0;

$current_audit_label = 'None';
if (isset($_SESSION['current_audit'])) {
    $aid = intval($_SESSION['current_audit']);
    $r = $conn->query("SELECT a.id, a.audit_date, d.dept_name, ad.fullname 
                       FROM bcp_sms4_audit a
                       LEFT JOIN bcp_sms4_departments d ON a.department_id = d.id
                       LEFT JOIN bcp_sms4_admins ad ON a.custodian_id = ad.id
                       WHERE a.id = {$aid}")->fetch_assoc();
    if ($r) {
        $current_audit_label = "Audit #{$r['id']} — {$r['audit_date']} ({$r['dept_name']} / {$r['fullname']})";
    } else {
        unset($_SESSION['current_audit']);
        unset($_SESSION['current_department']);
    }
}

if (isset($_POST['finding_id'])) {
    $finding_id = intval($_POST['finding_id']);
    
    if (isset($_POST['mark_disposal'])) {
        $stmt = $conn->prepare("UPDATE bcp_sms4_audit_findings SET finding_status = 'Disposed' WHERE id = ?");
        $stmt->bind_param("i", $finding_id);
        $stmt->execute();
        $stmt->close();
        
        $_SESSION['toast_message'] = [
            'message' => "Finding #{$finding_id} marked as disposed",
            'type' => 'danger'
        ];
        
        header("Location: " . $_SERVER['PHP_SELF']);
        exit;
    }
}
?>