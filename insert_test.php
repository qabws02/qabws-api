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

    $name = trim($_POST['name'] ?? '');
    $email = trim($_POST['email'] ?? '');
    $phn = trim($_POST['phn'] ?? '');
    $password = trim($_POST['password'] ?? '');

    // التحقق
    if ($name == '' || $email == '' || $password == '') {
        echo json_encode([
            "status" => false,
            "error" => "All fields required"
        ]);
        exit;
    }

    // منع التكرار
    $check = $conn->prepare("SELECT id_users FROM users WHERE email = ?");
    $check->execute([$email]);

    if ($check->fetch()) {
        echo json_encode([
            "status" => false,
            "error" => "Email already exists"
        ]);
        exit;
    }

    // إدخال
    $stmt = $conn->prepare("
        INSERT INTO users (name, email, phn, password)
        VALUES (?, ?, ?, ?)
    ");

    $ok = $stmt->execute([$name, $email, $phn, $password]);

    echo json_encode([
        "status" => true,
        "message" => "User created successfully"
    ]);

} catch (PDOException $e) {

    echo json_encode([
        "status" => false,
        "error" => $e->getMessage()
    ]);
}
?>
