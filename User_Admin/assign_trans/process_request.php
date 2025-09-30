<?php
session_start();
include '../../connection.php';

if (!isset($_SESSION['user_id'])) {
    exit("Unauthorized");
}
$admin_id = intval($_SESSION['user_id']);

$request_id = intval($_POST['request_id'] ?? 0);
$action     = $_POST['action'] ?? '';

if ($request_id <= 0) {
    header("Location: view_request.php?updated=0&error=invalid_request");
    exit;
}

if ($action === 'approve') {

    $conn->begin_transaction();

    try {
        // Lock and fetch request
        $stmt = $conn->prepare("SELECT * FROM bcp_sms4_requests WHERE request_id = ? FOR UPDATE");
        $stmt->bind_param("i", $request_id);
        $stmt->execute();
        $req = $stmt->get_result()->fetch_assoc();

        if (!$req) throw new Exception("Request not found");

        $reference_no = 'ISS-' . date('YmdHis') . '-' . strtoupper(substr(uniqid(), -6));
        $request_type  = $req['request_type'];
        $requested_qty = intval($req['quantity']);
        $teacher_id    = intval($req['teacher_id']);

        $item_id = null;

// ======== HANDLE CONSUMABLE ========
if ($request_type === 'Consumable') {
    $consumable_id = intval($req['consumable_id']);

    $stmtC = $conn->prepare("
        SELECT c.id, c.quantity, i.item_id, i.item_name
        FROM bcp_sms4_consumable c
        JOIN bcp_sms4_items i ON c.item_id = i.item_id
        WHERE c.id = ? FOR UPDATE
    ");
    $stmtC->bind_param("i", $consumable_id);
    $stmtC->execute();
    $c = $stmtC->get_result()->fetch_assoc();
    if (!$c) throw new Exception("Consumable record not found");

    $stock = intval($c['quantity']);
    if ($stock < $requested_qty) {
        $conn->rollback();
        header("Location: view_request.php?updated=0&error=insufficient_stock");
        exit;
    }

    // Deduct stock
    $stmtUpd = $conn->prepare("UPDATE bcp_sms4_consumable SET quantity = quantity - ? WHERE id = ?");
    $stmtUpd->bind_param("ii", $requested_qty, $consumable_id);
    $stmtUpd->execute();

    $item_id       = $c['item_id'];
    $asset_id      = null;             // no asset here
    $consumable_id = $c['id'];
}

// ======== HANDLE ASSET ========
elseif ($request_type === 'Asset') {
    $asset_id = intval($req['asset_id']);

    $stmtA = $conn->prepare("
        SELECT a.asset_id, a.status, a.property_tag, i.item_id, i.item_name
        FROM bcp_sms4_asset a
        JOIN bcp_sms4_items i ON a.item_id = i.item_id
        WHERE a.asset_id = ? FOR UPDATE
    ");
    $stmtA->bind_param("i", $asset_id);
    $stmtA->execute();
    $a = $stmtA->get_result()->fetch_assoc();
    if (!$a) throw new Exception("Asset not found");

    $currentStatus = strtolower($a['status'] ?? '');
    $availableStatuses = ['in storage','in-storage','available','stored','in_storage'];

    if (!in_array($currentStatus, $availableStatuses)) {
        throw new Exception("Asset not available (status: {$a['status']})");
    }

    // Update asset to assigned
    $stmtUpd = $conn->prepare("UPDATE bcp_sms4_asset SET status = 'In-Use', assigned_to = ? WHERE asset_id = ?");
    $stmtUpd->bind_param("ii", $teacher_id, $asset_id);
    $stmtUpd->execute();

    $item_id       = $a['item_id'];
    $consumable_id = null;             // no consumable here
}

// ======== INSERT ISSUANCE ========
$dept_id = intval($req['department_id']);

$stmtIss = $conn->prepare("
    INSERT INTO bcp_sms4_issuance
      (reference_no, request_id, item_id, quantity, teacher_id, department_id, issued_by, assigned_date, asset_id, consumable_id)
    VALUES (?, ?, ?, ?, ?, ?, ?, NOW(), ?, ?)
");
$stmtIss->bind_param(
    "siiiiiiii",
    $reference_no,
    $request_id,
    $item_id,
    $requested_qty,
    $teacher_id,
    $dept_id,
    $admin_id,
    $asset_id,
    $consumable_id
);
$stmtIss->execute();


        // ======== UPDATE REQUEST ========
        $stmtReqUpd = $conn->prepare("UPDATE bcp_sms4_requests SET status = 'Approved' WHERE request_id = ?");
        $stmtReqUpd->bind_param("i", $request_id);
        $stmtReqUpd->execute();

        $conn->commit();
        header("Location: view_request.php?updated=1");
        exit;

    } catch (Exception $e) {
        $conn->rollback();
        error_log("process_request approve error: " . $e->getMessage());
        header("Location: view_request.php?updated=0&error=" . urlencode($e->getMessage()));
        exit;
    }

}
// ======== HANDLE REJECT ========
elseif ($action === 'reject') {
    $stmt = $conn->prepare("UPDATE bcp_sms4_requests SET status = 'Rejected' WHERE request_id = ?");
    $stmt->bind_param("i", $request_id);
    $stmt->execute();

    header("Location: view_request.php?updated=1");
    exit;
}
else {
    header("Location: view_request.php");
    exit;
}
