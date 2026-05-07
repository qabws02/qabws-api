<?php
header("Content-Type: application/json");

try {

    // الاتصال بقاعدة البيانات
    $conn = new PDO(
        "mysql:host=mysql-cc1c3ad-qabwsb02-598d.k.aivencloud.com;port=12495;dbname=defaultdb;charset=utf8mb4",
        "avnadmin",
        getenv("DB_PASSWORD"),
        [
            PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION
        ]
    );

    // بيانات من Flutter أو Postman
    $name = $_POST['name'] ?? '';
    $email = $_POST['email'] ?? '';
    $phn = $_POST['phn'] ?? '';
    $password = $_POST['password'] ?? '';

    // إدخال البيانات
    $sql = "INSERT INTO users (name, email, phn, password)
            VALUES (?, ?, ?, ?)";

    $stmt = $conn->prepare($sql);
    $ok = $stmt->execute([$name, $email, $phn, $password]);

    // النتيجة
    echo json_encode([
        "status" => $ok,
        "message" => $ok ? "User inserted successfully" : "Insert failed"
    ]);

} catch (PDOException $e) {

    // عرض الخطأ الحقيقي
    echo json_encode([
        "status" => false,
        "error" => $e->getMessage()
    ]);
}
?>
