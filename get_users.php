<?php
include "conn.php";

$stmt = $conn->query("SELECT * FROM users");
$data = $stmt->fetchAll(PDO::FETCH_ASSOC);

echo json_encode([
    "status" => true,
    "data" => $data
]);
?>
