<?php

header("Content-Type: application/json; charset=utf8mb4");

// إعدادات قاعدة البيانات
$host = "sql304.infinityfree.com";
$user = "if0_40839769";
$pass = "PIdBUzww9FqCAs";
$db   = "if0_40839769_info39769";

// إنشاء الاتصال
$conn = new mysqli($host, $user, $pass, $db);

// التحقق من الخطأ
if ($conn->connect_error) {
    http_response_code(500);

    echo json_encode([
        "status" => false,
        "message" => "Database connection failed",
        "error" => $conn->connect_error
    ]);

    exit();
}

// ضبط الترميز
if (!$conn->set_charset("utf8mb4")) {
    http_response_code(500);

    echo json_encode([
        "status" => false,
        "message" => "Charset error",
        "error" => $conn->error
    ]);

    exit();
}

?>
