<?php
header("Content-Type: application/json");

$host = "mysql-cc1c3ad-qabwsb02-598d.k.aivencloud.com";
$port = 12495;
$db   = "defaultdb";
$user = "avnadmin";
$pass = "PUT_YOUR_PASSWORD_HERE";

try {
    $conn = new PDO(
        "mysql:host=$host;port=$port;dbname=$db;charset=utf8mb4",
        $user,
        $pass,
        [
            PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION
        ]
    );

} catch (PDOException $e) {
    die(json_encode([
        "status" => false,
        "error" => $e->getMessage()
    ]));
}
?>
