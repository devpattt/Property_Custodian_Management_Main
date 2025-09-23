<?php
include "../../../connect/connection.php"; 
include "reference_generator.php";      

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $equipment_id   = strtoupper(trim($_POST['equipment_id']));   // asset_tag
    $equipment_name = $_POST['equipment_name']; 
    $quantity       = (int)$_POST['quantity'];
    $custodian_id   = $_POST['user_id'];
    $custodian_name = $_POST['name'];
    $department     = $_POST['department'];
    $remarks        = $_POST['remarks'];
    $assigned_by    = "admin"; 
    $category       = "Non-Consumable"; // default category since not in frontend

    // 1. Validate stock availability
    $sql = "SELECT quantity, active 
            FROM bcp_sms4_asset 
            WHERE UPPER(TRIM(asset_tag)) = ?";
    $stmt = $conn->prepare($sql);
    $stmt->bind_param("s", $equipment_id);
    $stmt->execute();
    $result = $stmt->get_result();
    $asset = $result->fetch_assoc();

    if (!$asset) {
        header("Location: assign.php?error=1&msg=" . urlencode("Asset not found in inventory: $equipment_id"));
        exit();
    }

    if ($asset['quantity'] < $quantity) {
        header("Location: assign.php?error=1&msg=" . urlencode("Not enough stock. Current stock: " . $asset['quantity']));
        exit();
    }

    // 2. Update inventory
    $sql = "UPDATE bcp_sms4_asset 
            SET quantity = quantity - ?, active = active + ? 
            WHERE asset_tag = ?";
    $stmt = $conn->prepare($sql);
    $stmt->bind_param("iis", $quantity, $quantity, $equipment_id);
    $stmt->execute();

    // 3. Check if custodian already has this equipment
    $sql = "SELECT id, quantity, reference_no 
            FROM bcp_sms4_assign_history 
            WHERE equipment_id = ? AND custodian_id = ? AND department_code = ?";
    $stmt = $conn->prepare($sql);
    $stmt->bind_param("sss", $equipment_id, $custodian_id, $department);
    $stmt->execute();
    $existing = $stmt->get_result()->fetch_assoc();

    if ($existing) {
        // Merge
        $new_quantity = $existing['quantity'] + $quantity;

        $sql = "UPDATE bcp_sms4_assign_history 
                SET quantity = ?, remarks = CONCAT(remarks, '; ', ?), assigned_date = NOW()
                WHERE id = ?";
        $stmt = $conn->prepare($sql);
        $stmt->bind_param("isi", $new_quantity, $remarks, $existing['id']);
        $stmt->execute();

        $reference_no = $existing['reference_no']; // reuse old ref

        // 📌 Archive into non-add history
        $sql = "INSERT INTO bcp_sms4_assign_non_add_hstry
                (reference_no, equipment_id, equipment_name, category, quantity,
                 custodian_id, custodian_name, department_code, assigned_date,
                 remarks, assigned_by)
                VALUES (?, ?, ?, ?, ?, ?, ?, ?, NOW(), ?, ?)";
        $stmt = $conn->prepare($sql);
        $stmt->bind_param(
            "ssssisssss",
            $reference_no,
            $equipment_id,
            $equipment_name,
            $category,
            $quantity,
            $custodian_id,
            $custodian_name,
            $department,
            $remarks,
            $assigned_by
        );
        $stmt->execute();

        header("Location: assign.php?merge=1&reference=$reference_no");
        exit();
    } else {
        // Insert new assignment
        $sql = "INSERT INTO bcp_sms4_assign_history 
                (reference_no, equipment_id, equipment_name, quantity, custodian_id, custodian_name, department_code,
                assigned_date, remarks, assigned_by) 
                VALUES (NULL, ?, ?, ?, ?, ?, ?, NOW(), ?, ?)";
        $stmt = $conn->prepare($sql);
        $stmt->bind_param(
            "ssisssss",
            $equipment_id,
            $equipment_name,
            $quantity,
            $custodian_id,
            $custodian_name,
            $department,
            $remarks,
            $assigned_by
        );
        $stmt->execute();

        $id = $conn->insert_id;

        // Generate reference number and update
        $reference_no = generateReferenceNo($id);
        $sql = "UPDATE bcp_sms4_assign_history SET reference_no = ? WHERE id = ?";
        $stmt = $conn->prepare($sql);
        $stmt->bind_param("si", $reference_no, $id);
        $stmt->execute();

        // 📌 Archive into non-add history
        $sql = "INSERT INTO bcp_sms4_assign_non_add_hstry
                (reference_no, equipment_id, equipment_name, category, quantity,
                 custodian_id, custodian_name, department_code, assigned_date,
                 remarks, assigned_by)
                VALUES (?, ?, ?, ?, ?, ?, ?, ?, NOW(), ?, ?)";
        $stmt = $conn->prepare($sql);
        $stmt->bind_param(
            "ssssisssss",
            $reference_no,
            $equipment_id,
            $equipment_name,
            $category,
            $quantity,
            $custodian_id,
            $custodian_name,
            $department,
            $remarks,
            $assigned_by
        );
        $stmt->execute();

        header("Location: assign.php?success=1&reference=$reference_no");
        exit();
    }
}
?>
