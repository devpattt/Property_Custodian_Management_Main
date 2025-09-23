<?php
include "../../../connect/connection.php"; // DB connection
header('Content-Type: application/json');

if (isset($_GET['term'])) {
    $term = "%" . $_GET['term'] . "%"; // search anywhere in the name

    $sql = "SELECT user_id, name, department 
            FROM bcp_sms4_user 
            WHERE name LIKE ?
            LIMIT 10";
    $stmt = $conn->prepare($sql);
    $stmt->bind_param("s", $term);
    $stmt->execute();
    $result = $stmt->get_result();

    $users = [];
    while ($row = $result->fetch_assoc()) {
        $users[] = [
            "label" => $row['name'] . " (" . $row['department'] . ")", // shows in dropdown
            "value" => $row['name'],        // goes into input
            "user_id" => $row['user_id'],   // extra data
            "department" => $row['department']
        ];
    }

    echo json_encode($users);
}
?>
