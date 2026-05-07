<?php
header("Content-Type: application/json");

try {

    $conn = new PDO(
        "mysql:host=mysql-cc1c3ad-qabwsb02-598d.k.aivencloud.com;port=12495;dbname=defaultdb;charset=utf8mb4",
        "avnadmin",
        getenv("DB_PASSWORD"),
        [
            PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION
        ]
    );

    $name = $_POST['name'] ?? '';
    $email = $_POST['email'] ?? '';
    $phn = $_POST['phn'] ?? '';
    $password = $_POST['password'] ?? '';

    $sql = "INSERT INTO users (name, email, phn, password)
            VALUES (?, ?, ?, ?)";

    $stmt = $conn->prepare($sql);
    $ok = $stmt->execute([$name, $email, $phn, $password]);

    echo json_encode([
        "status" => $ok
    ]);

} catch (PDOException $e) {

    echo json_encode([
        "status" => false,
        "error" => $e->getMessage()
    ]);
}
?>
