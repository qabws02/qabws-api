<?php

header("Content-Type: application/json; charset=utf8mb4");

$host = "sql304.infinityfree.com";
$db   = "if0_40839769_info39769";
$user = "if0_40839769";
$pass = "PIdBUzww9FqCAs";

try {
    $conn = new PDO(
        "mysql:host=$host;dbname=$db;charset=utf8mb4",
        $user,
        $pass
    );

    $conn->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);

} catch (PDOException $e) {
    http_response_code(500);

    echo json_encode([
        "status" => false,
        "message" => "Database connection failed",
        "error" => $e->getMessage()
    ]);

    exit();
}
?>
