<?php
header("Content-Type: application/json; charset=UTF-8");

$host = "PUT_REAL_HOST_HERE";
$port = "12495";
$dbname = "defaultdb";
$user = "avnadmin";
$pass = getenv("DB_PASSWORD");

try {

    $conn = new PDO(
        "mysql:host=$host;port=$port;dbname=$dbname;charset=utf8mb4",
        $user,
        $pass,
        [
            PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION
        ]
    );

    echo json_encode([
        "status" => true,
        "message" => "Database connected successfully"
    ]);

} catch (PDOException $e) {

    echo json_encode([
        "status" => false,
        "error" => $e->getMessage()
    ]);
}
?>
