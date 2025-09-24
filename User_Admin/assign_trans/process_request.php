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

    // Start transaction
    $conn->begin_transaction();

    try {
        // Lock and fetch request
        $stmt = $conn->prepare("SELECT * FROM bcp_sms4_requests WHERE request_id = ? FOR UPDATE");
        $stmt->bind_param("i", $request_id);
        $stmt->execute();
        $req = $stmt->get_result()->fetch_assoc();

        if (!$req) throw new Exception("Request not found");

        // Generate a unique reference number (guaranteed unique enough for practical use)
        $reference_no = 'ISS-' . date('YmdHis') . '-' . strtoupper(substr(uniqid(), -6));

        $request_type  = $req['request_type'];
        $requested_qty = intval($req['quantity']);
        $teacher_id    = intval($req['teacher_id']); // ensure request has teacher_id

        if ($request_type === 'Consumable') {
            // Lock consumable row and get stock + item_id
            $consumable_id = intval($req['consumable_id']);
            $stmtC = $conn->prepare("SELECT id, quantity, item_id FROM bcp_sms4_consumable WHERE id = ? FOR UPDATE");
            $stmtC->bind_param("i", $consumable_id);
            $stmtC->execute();
            $c = $stmtC->get_result()->fetch_assoc();
            if (!$c) throw new Exception("Consumable record not found");

            $stock = intval($c['quantity']);
            if ($stock < $requested_qty) {
                // Not enough stock: rollback and return message
                $conn->rollback();
                header("Location: view_request.php?updated=0&error=insufficient_stock");
                exit;
            }

            // Get consumable item_name from items table
            $stmtItem = $conn->prepare("SELECT item_name FROM bcp_sms4_items WHERE item_id = ?");
            $stmtItem->bind_param("i", $c['item_id']);
            $stmtItem->execute();
            $itemRow = $stmtItem->get_result()->fetch_assoc();
            $item_name = $itemRow['item_name'] ?? 'Consumable';

            // Deduct stock
            $stmtUpd = $conn->prepare("UPDATE bcp_sms4_consumable SET quantity = quantity - ? WHERE id = ?");
            $stmtUpd->bind_param("ii", $requested_qty, $consumable_id);
            $stmtUpd->execute();
            if ($stmtUpd->affected_rows === 0) throw new Exception("Failed to deduct consumable stock");

            // Insert issuance record
            $stmtIss = $conn->prepare("
                INSERT INTO bcp_sms4_issuance
                  (reference_no, request_id, equipment_id, item_name, category, quantity, teacher_id, issued_by, assigned_date)
                VALUES (?, ?, ?, ?, 'Consumable', ?, ?, ?, NOW())
            ");
            $equipment_id = $consumable_id;
            $stmtIss->bind_param(
                "siissii",
                $reference_no,
                $request_id,
                $equipment_id,
                $item_name,
                $requested_qty,
                $teacher_id,
                $admin_id
            );
            $stmtIss->execute();

        } elseif ($request_type === 'Asset') {
            // Lock asset row and check availability
            $asset_id = intval($req['asset_id']);
            $stmtA = $conn->prepare("SELECT asset_id, status, item_id, property_tag FROM bcp_sms4_asset WHERE asset_id = ? FOR UPDATE");
            $stmtA->bind_param("i", $asset_id);
            $stmtA->execute();
            $a = $stmtA->get_result()->fetch_assoc();
            if (!$a) throw new Exception("Asset not found");

            $currentStatus = strtolower($a['status'] ?? '');
            $availableStatuses = ['in storage','in-storage','available','stored','in_storage'];

            if (!in_array($currentStatus, $availableStatuses)) {
                throw new Exception("Asset not available for issuance (status: {$a['status']})");
            }

            // Get item_name from items master table
            $stmtItem = $conn->prepare("SELECT item_name FROM bcp_sms4_items WHERE item_id = ?");
            $stmtItem->bind_param("i", $a['item_id']);
            $stmtItem->execute();
            $itemRow = $stmtItem->get_result()->fetch_assoc();
            $item_name = $itemRow['item_name'] ?? ($a['property_tag'] ?? 'Asset');

            // Update asset: assign to teacher and change status
            $stmtUpd = $conn->prepare("UPDATE bcp_sms4_asset SET status = 'In-Use', assigned_to = ? WHERE asset_id = ?");
            $stmtUpd->bind_param("ii", $teacher_id, $asset_id);
            $stmtUpd->execute();
            if ($stmtUpd->affected_rows === 0) throw new Exception("Failed to update asset status/assignment");

            // Insert issuance record (quantity = 1 for asset)
            $quantity = 1;
            $stmtIss = $conn->prepare("
                INSERT INTO bcp_sms4_issuance
                  (reference_no, request_id, equipment_id, item_name, category, quantity, teacher_id, issued_by, assigned_date)
                VALUES (?, ?, ?, ?, 'Asset', ?, ?, ?, NOW())
            ");
            $stmtIss->bind_param(
                "siissii",
                $reference_no,
                $request_id,
                $asset_id,
                $item_name,
                $quantity,
                $teacher_id,
                $admin_id
            );
            $stmtIss->execute();

        } else {
            throw new Exception("Unknown request type");
        }

        // Update request status to Approved
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

} elseif ($action === 'reject') {
    $stmt = $conn->prepare("UPDATE bcp_sms4_requests SET status = 'Rejected' WHERE request_id = ?");
    $stmt->bind_param("i", $request_id);
    $stmt->execute();

    header("Location: view_request.php?updated=1");
    exit;
} else {
    header("Location: view_request.php");
    exit;
}
