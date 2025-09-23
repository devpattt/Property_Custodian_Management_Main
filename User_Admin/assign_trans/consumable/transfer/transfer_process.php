<!DOCTYPE html>
<html lang="en">
    <head>
        <link rel="stylesheet" href="../../../../css/asset_reg/modal.css">
</head>

<?php
include "../../../connect/connection.php"; 
include "modal.php"; 

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $reference_no        = $_POST['reference_no']; 
    $box                 = isset($_POST['box']) && $_POST['box'] !== "" ? (int)$_POST['box'] : 0; 
    $quantity            = isset($_POST['quantity']) && $_POST['quantity'] !== "" ? (int)$_POST['quantity'] : 0; 
    $new_custodian_id    = $_POST['user_id'];      
    $new_custodian_name  = $_POST['name'];          
    $new_department      = $_POST['department'];    
    $remarks             = $_POST['remarks'];
    $assigned_by         = "admin"; // TODO: replace with session user

    if ($box <= 0 && $quantity <= 0) {
        renderMessageModal("errorModal", "Error", "You must enter at least Box or Quantity.");
        exit();
    }

    // 1. Get old custodian assignment record
    $sql = "SELECT * 
            FROM bcp_sms4_assign_consumable 
            WHERE reference_no = ? 
            ORDER BY assigned_date DESC LIMIT 1";
    $stmt = $conn->prepare($sql);
    $stmt->bind_param("s", $reference_no);
    $stmt->execute();
    $result = $stmt->get_result();
    $row = $result->fetch_assoc();

    if (!$row) {
        renderMessageModal("errorModal", "Failed", "Invalid reference number");
        exit();
    }

    $equipment_id   = $row['equipment_id'];
    $equipment_name = $row['equipment_name'];
    $current_box    = (int)$row['box'];
    $current_qty    = (int)$row['quantity'];
    $per_box        = (int)$row['per_box'];

    // 2. Validate stock with crack-box logic
    $total_current = ($current_box * $per_box) + $current_qty;
    $total_needed  = ($box * $per_box) + $quantity;

    if ($total_needed > $total_current) {
        renderMessageModal("errorModal", "Error", 
            "Not enough stock to transfer. Available total: $total_current, Requested total: $total_needed"
        );
        exit();
    }

    // 3. Deduct from old custodian
    $total_remaining = $total_current - $total_needed;
    $new_box_old = intdiv($total_remaining, $per_box);
    $new_qty_old = $total_remaining % $per_box;

    $sql = "UPDATE bcp_sms4_assign_consumable 
            SET box = ?, quantity = ?, assigned_date = NOW()
            WHERE id = ?";
    $stmt = $conn->prepare($sql);
    $stmt->bind_param("iii", $new_box_old, $new_qty_old, $row['id']);
    $stmt->execute();

    // 4. Archive transaction (always)
    $sql = "INSERT INTO bcp_sms4_assign_con_trans_hstry 
            (reference_no, equipment_id, equipment_name, category, expiration, 
            box, quantity, trans_box, trans_quantity, 
            custodian_id, custodian_name, department_code, 
            new_id, new_name, new_dep, 
            assigned_date, end_date, remarks, assigned_by) 
            VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, NOW(), ?, ?)";
    $stmtArchive = $conn->prepare($sql);
    $stmtArchive->bind_param(
        "sssssiiiisssssssss",
        $row['reference_no'],
        $row['equipment_id'],
        $row['equipment_name'],
        $row['category'],
        $row['expiration'],
        $row['box'],
        $row['quantity'],
        $box,
        $quantity,
        $row['custodian_id'],
        $row['custodian_name'],
        $row['department_code'],
        $new_custodian_id,
        $new_custodian_name,
        $new_department,
        $row['assigned_date'],
        $remarks,
        $assigned_by
    );
    $stmtArchive->execute();

    // 5. Insert / Update new custodian record
    $sql = "SELECT * FROM bcp_sms4_assign_consumable 
            WHERE equipment_id = ? AND custodian_id = ? LIMIT 1";
    $stmt = $conn->prepare($sql);
    $stmt->bind_param("ss", $equipment_id, $new_custodian_id);
    $stmt->execute();
    $resNew = $stmt->get_result();
    $existingNew = $resNew->fetch_assoc();

    if ($existingNew) {
        // Update existing record for new custodian
        $total_new_existing = ($existingNew['box'] * $per_box) + $existingNew['quantity'];
        $total_new_after = $total_new_existing + $total_needed;

        $upd_box = intdiv($total_new_after, $per_box);
        $upd_qty = $total_new_after % $per_box;

        $sql = "UPDATE bcp_sms4_assign_consumable 
                SET box = ?, quantity = ?, assigned_date = NOW(), remarks = ?, assigned_by = ?
                WHERE id = ?";
        $stmt = $conn->prepare($sql);
        $stmt->bind_param("iissi", $upd_box, $upd_qty, $remarks, $assigned_by, $existingNew['id']);
        $stmt->execute();
    } else {
        // ✅ Generate NEW reference_no for the new custodian
        $sqlRef = "INSERT INTO bcp_sms4_assign_consumable 
                (reference_no, equipment_id, equipment_name, category, expiration, 
                    box, quantity, per_box, custodian_id, custodian_name, department_code, 
                    assigned_date, remarks, assigned_by) 
                VALUES (NULL, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, NOW(), ?, ?)";
        $stmt = $conn->prepare($sqlRef);
        $stmt->bind_param(
            "sssssiisssss",
            $equipment_id,
            $equipment_name,
            $row['category'],
            $row['expiration'],
            $box,
            $quantity,
            $per_box,
            $new_custodian_id,
            $new_custodian_name,
            $new_department,
            $remarks,
            $assigned_by
        );
        $stmt->execute();

        // Get inserted id
        $new_id = $conn->insert_id;

        // Generate proper reference_no (REF-YYYYMMDD-ID)
        $new_reference_no = "REF-" . date("Ymd") . "-" . strtoupper(substr(uniqid(), -5));
        $sqlUpdate = "UPDATE bcp_sms4_assign_consumable SET reference_no = ? WHERE id = ?";
        $stmt = $conn->prepare($sqlUpdate);
        $stmt->bind_param("si", $new_reference_no, $new_id);
        $stmt->execute();
    }

    renderMessageModal("successModal", "Success", "Equipment transferred successfully!");
}
?>
