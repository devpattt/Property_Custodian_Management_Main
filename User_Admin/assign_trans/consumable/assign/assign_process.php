<?php
include "../../../connect/connection.php";
include "reference_generator.php"; // <-- your generator

if ($_SERVER["REQUEST_METHOD"] !== "POST") exit;

// Collect input
$equipment_id   = $_POST['equipment_id'] ?? '';
$equipment_name = $_POST['equipment_name'] ?? '';
$category       = $_POST['equipmentCategory'] ?? '';
$expiration     = $_POST['expiration'] ?? '';
$box_req        = isset($_POST['box']) && $_POST['box'] !== "" ? (int)$_POST['box'] : 0;
$qty_req        = isset($_POST['quantity']) && $_POST['quantity'] !== "" ? (int)$_POST['quantity'] : 0;
$custodian_id   = $_POST['user_id'] ?? '';
$custodian_name = $_POST['name'] ?? '';
$department     = $_POST['department'] ?? '';
$remarks        = $_POST['remarks'] ?? '';
$assigned_by    = "admin";

// Guard
if ($box_req <= 0 && $qty_req <= 0) {
    header("Location: assign.php?error=1&msg=" . urlencode("You must enter Box or Quantity."));
    exit;
}

// Load stock
$sql = "SELECT * FROM bcp_sms4_consumable WHERE asset_tag = ? LIMIT 1";
$stmt = $conn->prepare($sql);
$stmt->bind_param("s", $equipment_id);
$stmt->execute();
$res = $stmt->get_result();
$item = $res->fetch_assoc();

if (!$item) {
    header("Location: assign.php?error=1&msg=" . urlencode("Invalid Equipment ID."));
    exit;
}

$current_box = (int)$item['box'];
$current_qty = (int)$item['quantity'];
$per_box     = (int)$item['per_box'];

if ($per_box <= 0) {
    header("Location: assign.php?error=1&msg=" . urlencode("Invalid per_box value."));
    exit;
}

$req_total     = ($box_req * $per_box) + $qty_req;
$current_total = ($current_box * $per_box) + $current_qty;

if ($req_total > $current_total) {
    header("Location: assign.php?error=1&msg=" . urlencode("Not enough stock. Available $current_total units."));
    exit;
}

// Update stock
$new_total = $current_total - $req_total;
$new_box   = intdiv($new_total, $per_box);
$new_qty   = $new_total % $per_box;

$sql = "UPDATE bcp_sms4_consumable SET box = ?, quantity = ? WHERE id = ?";
$stmt = $conn->prepare($sql);
$stmt->bind_param("iii", $new_box, $new_qty, $item['id']);
$stmt->execute();

// Check if custodian already has this item
$sql = "SELECT * FROM bcp_sms4_assign_consumable WHERE equipment_id = ? AND custodian_id = ? LIMIT 1";
$stmt = $conn->prepare($sql);
$stmt->bind_param("ss", $equipment_id, $custodian_id);
$stmt->execute();
$res = $stmt->get_result();
$exist = $res->fetch_assoc();

// Generate reference
if ($exist) {
    $ref_no = $exist['reference_no']; // reuse reference for merges
} else {
    $ref_no = generateReferenceNo($item['id']); // new one
}
$assigned_date = date("Y-m-d H:i:s");

if ($exist) {
    // --- Merge assignment ---
    $exist_total = ($exist['box'] * $per_box) + $exist['quantity'];
    $new_assign_total = $exist_total + $req_total;

    $upd_box = intdiv($new_assign_total, $per_box);
    $upd_qty = $new_assign_total % $per_box;

    $sql = "UPDATE bcp_sms4_assign_consumable
            SET box = ?, quantity = ?, assigned_date = NOW()
            WHERE id = ?";
    $stmt = $conn->prepare($sql);
    $stmt->bind_param("iii", $upd_box, $upd_qty, $exist['id']);
    $stmt->execute();

    // Log history (only the newly added part)
    $sql = "INSERT INTO bcp_sms4_assign_con_add_hstry
            (reference_no, equipment_id, equipment_name, category, expiration,
             box, quantity, custodian_id, custodian_name, department_code,
             assigned_date, remarks, assigned_by)
            VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?)";
    $stmt = $conn->prepare($sql);
    $stmt->bind_param(
        "sssssiissssss",
        $ref_no,
        $equipment_id,
        $equipment_name,
        $category,
        $expiration,
        $box_req,
        $qty_req,
        $custodian_id,
        $custodian_name,
        $department,
        $assigned_date,
        $remarks,
        $assigned_by
    );
    $stmt->execute();

    header("Location: assign.php?merge=1&reference=" . urlencode($ref_no));
    exit;

} else {
    // --- New assignment ---
    $assign_box = intdiv($req_total, $per_box);
    $assign_qty = $req_total % $per_box;

    $sql = "INSERT INTO bcp_sms4_assign_consumable
            (reference_no, equipment_id, equipment_name, category, expiration,
             box, quantity, per_box,
             custodian_id, custodian_name, department_code,
             assigned_date, remarks, assigned_by)
            VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?)";
    $stmt = $conn->prepare($sql);
    $stmt->bind_param(
        "sssssiiissssss",
        $ref_no,
        $equipment_id,
        $equipment_name,
        $category,
        $expiration,
        $assign_box,
        $assign_qty,
        $per_box,
        $custodian_id,
        $custodian_name,
        $department,
        $assigned_date,
        $remarks,
        $assigned_by
    );
    $stmt->execute();

    // Log history
    $sql = "INSERT INTO bcp_sms4_assign_con_add_hstry
            (reference_no, equipment_id, equipment_name, category, expiration,
             box, quantity, custodian_id, custodian_name, department_code,
             assigned_date, remarks, assigned_by)
            VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?)";
    $stmt = $conn->prepare($sql);
    $stmt->bind_param(
        "sssssiissssss",
        $ref_no,
        $equipment_id,
        $equipment_name,
        $category,
        $expiration,
        $box_req,
        $qty_req,
        $custodian_id,
        $custodian_name,
        $department,
        $assigned_date,
        $remarks,
        $assigned_by
    );
    $stmt->execute();

    header("Location: assign.php?success=1&reference=" . urlencode($ref_no));
    exit;
}
?>
