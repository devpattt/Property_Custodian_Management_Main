<?php
include '../../connection.php';

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $id     = intval($_POST['procurement_id']);
    $status = $_POST['status'];

    $stmt = $conn->prepare("UPDATE bcp_sms4_procurement SET status = ? WHERE procurement_id = ?");
    $stmt->bind_param("si", $status, $id);
    $stmt->execute();

    if (strtolower($status) === 'completed') {
        $query = "SELECT 
                    p.procurement_id,
                    p.quantity,
                    i.item_id,
                    i.item_name,
                    i.category,
                    i.item_type,
                    i.unit
                  FROM bcp_sms4_procurement p
                  JOIN bcp_sms4_items i ON p.item_id = i.item_id
                  WHERE p.procurement_id = ?";
        $stmt2 = $conn->prepare($query);
        $stmt2->bind_param("i", $id);
        $stmt2->execute();
        $result = $stmt2->get_result();
        $row = $result->fetch_assoc();

        if ($row) {
            $itemId   = $row['item_id'];
            $itemName = $row['item_name'];
            $itemType = strtolower($row['item_type']);
            $qty      = intval($row['quantity']);
            $unit     = $row['unit'];

            if ($itemType === 'consumable') {
                $stmt3 = $conn->prepare("
                    INSERT INTO bcp_sms4_consumable 
                    (item_id, unit, quantity, status, expiration, date_received) 
                    VALUES (?, ?, ?, 'Available', DATE_ADD(NOW(), INTERVAL 1 YEAR), NOW())
                ");
                $stmt3->bind_param("isi", $itemId, $unit, $qty);
                $stmt3->execute();
            } else {
                for ($i = 1; $i <= $qty; $i++) {
                    $propertyTag = generateTag($conn);

                    $stmt4 = $conn->prepare("INSERT INTO bcp_sms4_asset (item_id, property_tag, date_registered) VALUES (?, ?, NOW())");
                    $stmt4->bind_param("is", $itemId, $propertyTag);
                    $stmt4->execute();
                }
            }
        }
    }

    header("Location: procurement.php?success=1");
    exit;
}
    
function generateTag($conn) {
    $prefix = "ASSET-" . date("Y"); 
    $sql = "SELECT COUNT(*) as count FROM bcp_sms4_asset WHERE property_tag LIKE '$prefix%'";
    $result = $conn->query($sql);
    $row = $result->fetch_assoc();
    $next = $row['count'] + 1;

    return sprintf("%s-%04d", $prefix, $next); 
}
?>
