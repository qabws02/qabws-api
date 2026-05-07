<?php
header("Content-Type: application/json");

try {

    $conn = new PDO(
        "mysql:host=mysql-cc1c3ad-qabwsb02-598d.k.aivencloud.com;port=12495;dbname=defaultdb;charset=utf8mb4",
        "avnadmin",
        getenv("DB_PASSWORD"),
        [PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION]
    );

    $name = $_POST['name'] ?? '';
    $email = $_POST['email'] ?? '';
    $phn = $_POST['phn'] ?? '';
    $password = $_POST['password'] ?? '';

    if (empty($email) || empty($name)) {
        echo json_encode([
            "status" => false,
            "error" => "Missing required fields"
        ]);
        exit;
    }

    // check duplicate email
    $check = $conn->prepare("SELECT id_users FROM users WHERE email = ?");
    $check->execute([$email]);

    if ($check->rowCount() > 0) {
        echo json_encode([
            "status" => false,
            "error" => "Email already exists"
        ]);
        exit;
    }

    // insert
    $stmt = $conn->prepare("
        INSERT INTO users (name, email, phn, password)
        VALUES (?, ?, ?, ?)
    ");

    $ok = $stmt->execute([$name, $email, $phn, $password]);

    echo json_encode([
        "status" => $ok,
        "message" => "User created"
    ]);

} catch (PDOException $e) {

    echo json_encode([
        "status" => false,
        "error" => $e->getMessage()
    ]);
}
?>
