<?php
header("Content-Type: application/json; charset=UTF-8");

// بيانات الاتصال الخاصة بك
$host = "mysql-cc1c3ad-qabwsb02-598d.k.aivencloud.com";
$port = "12495";
$dbname = "defaultdb";
$user = "avnadmin";
$pass = getenv("DB_PASSWORD"); // استبدلها بكلمة المرور الحقيقية

try {
    $conn = new PDO(
        "mysql:host=$host;port=$port;dbname=$dbname;charset=utf8mb4",
        $user,
        $pass,
        [PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION]
    );

    // كود إنشاء الجدول
    $sql = "CREATE TABLE IF NOT EXISTS otp_verification (
        id INT AUTO_INCREMENT PRIMARY KEY,
        email VARCHAR(255) NOT NULL,
        otp VARCHAR(6) NOT NULL,
        created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP
    ) ENGINE=InnoDB;";

    $conn->exec($sql);
    echo json_encode(["status" => "success", "message" => "تم إنشاء جدول otp_verification بنجاح"]);

} catch (PDOException $e) {
    echo json_encode(["status" => "error", "message" => "فشل الاتصال أو الإنشاء: " . $e->getMessage()]);
}
?>
