<?php
header("Content-Type: application/json");

include "conn.php";

$stmt = $conn->prepare("SELECT id_users, name, email FROM users");
$stmt->execute();

$users = $stmt->fetchAll(PDO::FETCH_ASSOC);

echo json_encode([
    "status" => true,
    "data" => $users
]);
?>
