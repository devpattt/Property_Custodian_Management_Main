<?php
include '../connection.php';
$top_items = [];

/* --------- Non-Consumable Assets --------- */
$sql_assets = "
    SELECT a.equipment_name AS name, SUM(a.quantity) AS issued_qty
    FROM bcp_sms4_assign_history a
    GROUP BY a.equipment_name
    ORDER BY issued_qty DESC
    LIMIT 5
";
$res_assets = $conn->query($sql_assets);
if($res_assets){
    while($row = $res_assets->fetch_assoc()){
        $top_items[] = [
            'name' => $row['name'],
            'unit' => 'pcs', 
            'issued' => $row['issued_qty'],
            'type' => 'Asset'
        ];
    }
}

/* --------- Consumable Items --------- */
$sql_consumables = "
    SELECT i.item_name AS name, c.unit, SUM(c.quantity) AS issued_qty
    FROM bcp_sms4_consumable c
    JOIN bcp_sms4_items i ON c.item_id = i.item_id
    GROUP BY c.item_id
    ORDER BY issued_qty DESC
    LIMIT 5
";
$res_consu = $conn->query($sql_consumables);
if($res_consu){
    while($row = $res_consu->fetch_assoc()){
        $top_items[] = [
            'name' => $row['name'],
            'unit' => $row['unit'],
            'issued' => $row['issued_qty'],
            'type' => 'Consumable'
        ];
    }
}

return $top_items;
?>
