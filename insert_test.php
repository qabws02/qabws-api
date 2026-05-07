<?php
header("Content-Type: application/json");

$host = "mysql-cc1c3ad-qabwsb02-598d.k.aivencloud.com";
$port = 12495;
$db   = "defaultdb";
$user = "avnadmin";
$pass = getenv("DB_PASSWORD");

try {
    $conn = new PDO(
        "mysql:host=$host;port=$port;dbname=$db;charset=utf8mb4",
        $user,
        $pass,
        [PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION]
    );

    $sql = "INSERT INTO users (name, email, phn, password)
            VALUES ('Ahmed', 'ahmed@test.com', '777777777', '123456')";

    $conn->exec($sql);

    echo json_encode([
        "status" => true,
        "message" => "Test user inserted"
    ]);

} catch (PDOException $e) {
    echo json_encode([
        "status" => false,
        "error" => $e->getMessage()
    ]);
}
?>
