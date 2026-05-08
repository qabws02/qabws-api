<?php
header("Content-Type: application/json; charset=UTF-8");

// بيانات الاتصال
$host = "mysql-cc1c3ad-qabwsb02-598d.k.aivencloud.com";
$port = "12495";
$dbname = "defaultdb";
$user = "avnadmin";
$pass = getenv("DB_PASSWORD");

try {

    // الاتصال بقاعدة البيانات
    $conn = new PDO(
        "mysql:host=$host;port=$port;dbname=$dbname;charset=utf8mb4",
        $user,
        $pass,
        [
            PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION
        ]
    );

    // إنشاء جدول OTP بشكل صحيح وآمن
    $sql = "
    CREATE TABLE IF NOT EXISTS otp_verification (
        id INT AUTO_INCREMENT PRIMARY KEY,
        email VARCHAR(255) NOT NULL,
        otp VARCHAR(6) NOT NULL,
        created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
        expires_at DATETIME DEFAULT (NOW() + INTERVAL 10 MINUTE),
        verified TINYINT(1) DEFAULT 0,

        INDEX idx_email (email),
        INDEX idx_otp (otp)
    ) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;
    ";

    $conn->exec($sql);

    echo json_encode([
        "status" => true,
        "message" => "تم إنشاء جدول otp_verification بنجاح"
    ], JSON_UNESCAPED_UNICODE);

} catch (PDOException $e) {

    echo json_encode([
        "status" => false,
        "error" => $e->getMessage()
    ], JSON_UNESCAPED_UNICODE);
}
?>
