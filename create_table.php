<?php
$host = "mysql-cc1c3ad-qabwsb02-598d.k.aivencloud.com";
$port = "12495";
$db   = "defaultdb";
$user = "avnadmin";
$pass = getenv("qabws");

try {
    $conn = new PDO(
        "mysql:host=$host;port=$port;dbname=$db;charset=utf8mb4",
        $user,
        $pass,
        [
            PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION,
            PDO::MYSQL_ATTR_SSL_VERIFY_SERVER_CERT => false
        ]
    );

    $sql = "CREATE TABLE IF NOT EXISTS users (
        id_users INT AUTO_INCREMENT PRIMARY KEY,
        name VARCHAR(100) NOT NULL,
        email VARCHAR(100) UNIQUE NOT NULL,
        phn VARCHAR(20),
        date_time DATETIME DEFAULT CURRENT_TIMESTAMP,
        img VARCHAR(255),
        password VARCHAR(255) NOT NULL,
        addrss VARCHAR(255),
        blocked TINYINT(1) DEFAULT 0,
        first_login TINYINT(1) DEFAULT 1,
        code VARCHAR(10),
        reset_expire DATETIME
    )";

    $conn->exec($sql);

    echo json_encode([
        "status" => true,
        "message" => "Table users created successfully"
    ]);

} catch (PDOException $e) {
    echo json_encode([
        "status" => false,
        "error" => $e->getMessage()
    ]);
}
?>
