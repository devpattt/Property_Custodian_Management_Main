<!DOCTYPE html>
<html lang="en">
    <head>
        <link rel="stylesheet" href="../../../../css/asset_reg/modal.css">
</head>

<?php
include "../../../connect/connection.php"; // main DB connection
include "modal.php"; // for modal

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $reference_no        = $_POST['reference_no']; 
    $quantity            = (int)$_POST['quantity']; 
    $new_custodian_id    = $_POST['user_id'];      
    $new_custodian_name  = $_POST['name'];          
    $department          = $_POST['department'];    
    $remarks             = $_POST['remarks'];
    $assigned_by         = "admin"; // TODO: replace with session user

    // 1. Verify reference exists and get equipment info
    $sql = "SELECT * 
            FROM bcp_sms4_assign_history 
            WHERE reference_no = ? 
            ORDER BY assigned_date DESC LIMIT 1";
    $stmt = $conn->prepare($sql);
    $stmt->bind_param("s", $reference_no);
    $stmt->execute();
    $result = $stmt->get_result();
    $row = $result->fetch_assoc();

    if (!$row) {
        renderMessageModal(
              "successModal",
              "Failed",
              "Invalid reference number"
           );
           exit();
    }

    $equipment_id   = $row['equipment_id'];
    $equipment_name = $row['equipment_name'];
    $current_qty    = (int)$row['quantity'];

    // 2. Always archive old record (with end_date = NOW)
    $sql = "INSERT INTO bcp_sms4_assign_old_history
        (reference_no, equipment_id, equipment_name, quantity, custodian_id, custodian_name, department_code, assign_date, 
        end_date, remarks, assign_by, transfer_quan, new_id, new_name, new_dep) 
        VALUES (?, ?, ?, ?, ?, ?, ?, ?, NOW(), ?, ?, '$quantity', '$new_custodian_id', '$new_custodian_name', '$department')";
    $stmtArchive = $conn->prepare($sql);
    $stmtArchive->bind_param(
        "sssissssss",
        $row['reference_no'],
        $row['equipment_id'],
        $row['equipment_name'],
        $row['quantity'],
        $row['custodian_id'],
        $row['custodian_name'],
        $row['department_code'],
        $row['assigned_date'],
        $row['remarks'],
        $row['assigned_by']
    );
    $stmtArchive->execute();

    // 3. Handle transfer logic
    if ($current_qty > $quantity) {
        // --- Partial Transfer ---
        // Reduce quantity from old custodian
        $sql = "UPDATE bcp_sms4_assign_history
                SET quantity = quantity - ?
                WHERE equipment_id = ? AND reference_no = ?";
        $stmt = $conn->prepare($sql);
        $stmt->bind_param("iss", $quantity, $equipment_id, $reference_no);
        $stmt->execute();

        // Check if new custodian already has this equipment
        $sql = "SELECT id, quantity 
                FROM bcp_sms4_assign_history
                WHERE equipment_id = ? AND custodian_id = ? AND end_date IS NULL
                LIMIT 1";
        $stmt = $conn->prepare($sql);
        $stmt->bind_param("ss", $equipment_id, $new_custodian_id);
        $stmt->execute();
        $result = $stmt->get_result();

        if ($result->num_rows > 0) {
            // Merge quantities
            $row2 = $result->fetch_assoc();
            $new_qty = $row2['quantity'] + $quantity;

            $sql = "UPDATE bcp_sms4_assign_history
                    SET quantity = ?, remarks = ?, assigned_date = NOW()
                    WHERE id = ?";
            $stmt = $conn->prepare($sql);
            $stmt->bind_param("isi", $new_qty, $remarks, $row2['id']);
            $stmt->execute();

            renderMessageModal(
              "successModal",
              "Success",
              "Partial transfer merged: Custodian already had this equipment, quantity updated to $new_qty"
           );


        } else {
            // Insert new row
            $new_reference = "REF-" . date("Ymd") . "-" . strtoupper(substr(uniqid(), -5));

            $sql = "INSERT INTO bcp_sms4_assign_history 
                        (reference_no, equipment_id, equipment_name, quantity, custodian_id, custodian_name, department_code, assigned_date, remarks, assigned_by) 
                    VALUES (?, ?, ?, ?, ?, ?, ?, NOW(), ?, ?)";
            $stmt = $conn->prepare($sql);
            $stmt->bind_param(
                "sssisssss",
                $new_reference,
                $equipment_id,
                $equipment_name,
                $quantity,
                $new_custodian_id,
                $new_custodian_name,
                $department,
                $remarks,
                $assigned_by
            );
            $stmt->execute();

            renderMessageModal(
              "successModal",
              "Success",
              "Partial transfer successful. New Reference: " . $new_reference
           );

        }

    } elseif ($current_qty == $quantity) {
        // --- Full Transfer ---
        // 1. Move old row completely (it's already archived above)
        $sql = "DELETE FROM bcp_sms4_assign_history
                WHERE equipment_id = ? AND custodian_id = ? AND reference_no = ?";
        $stmt = $conn->prepare($sql);
        $stmt->bind_param("sss", $equipment_id, $row['custodian_id'], $reference_no);
        $stmt->execute();

        // 2. Check if new custodian already has this equipment
        $sql = "SELECT id, quantity 
                FROM bcp_sms4_assign_history
                WHERE equipment_id = ? AND custodian_id = ? AND end_date IS NULL
                LIMIT 1";
        $stmt = $conn->prepare($sql);
        $stmt->bind_param("ss", $equipment_id, $new_custodian_id);
        $stmt->execute();
        $result = $stmt->get_result();

        if ($result->num_rows > 0) {
            // Merge
            $row2 = $result->fetch_assoc();
            $new_qty = $row2['quantity'] + $quantity;

            $sql = "UPDATE bcp_sms4_assign_history
                    SET quantity = ?, remarks = ?, assigned_date = NOW()
                    WHERE id = ?";
            $stmt = $conn->prepare($sql);
            $stmt->bind_param("isi", $new_qty, $remarks, $row2['id']);
            $stmt->execute();

             renderMessageModal(
              "successModal",
              "Success",
              "Full transfer merged: Custodian already had this equipment, quantity updated to $new_qty"
           );


        } else {
            // Insert new row for new custodian
            $new_reference = "REF-" . date("Ymd") . "-" . strtoupper(substr(uniqid(), -5));

            $sql = "INSERT INTO bcp_sms4_assign_history 
                        (reference_no, equipment_id, equipment_name, quantity, custodian_id, custodian_name, department_code, assigned_date, remarks, assigned_by) 
                    VALUES (?, ?, ?, ?, ?, ?, ?, NOW(), ?, ?)";
            $stmt = $conn->prepare($sql);
            $stmt->bind_param(
                "sssisssss",
                $new_reference,
                $equipment_id,
                $equipment_name,
                $quantity,
                $new_custodian_id,
                $new_custodian_name,
                $department,
                $remarks,
                $assigned_by
            );
            $stmt->execute();

            renderMessageModal(
              "successModal",
              "Success",
              "Full transfer successful. New Reference: " . $new_reference
           );

        }
    }

    // Cleanup
    $stmt->close();
    $conn->close();
}
?>
