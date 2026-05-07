<?php
header("Content-Type: application/json; charset=UTF-8");

try {
    // 1. الاتصال بقاعدة البيانات
    $conn = new PDO(
        "mysql:host=mysql-cc1c3ad-qabwsb02-598d.k.aivencloud.com;port=12495;dbname=defaultdb;charset=utf8mb4",
        "avnadmin",
        getenv("DB_PASSWORD"),
        [PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION]
    );

    // 2. استلام وتنظيف البيانات
    $name     = trim($_POST['name'] ?? '');
    $email    = trim($_POST['email'] ?? '');
    $phn      = trim($_POST['phn'] ?? '');
    $password = trim($_POST['password'] ?? '');

    // 3. التحقق من المدخلات
    if (empty($name) || empty($email) || empty($password)) {
        throw new Exception("جميع الحقول المطلوبة يجب ملؤها");
    }

    if (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
        throw new Exception("صيغة البريد الإلكتروني غير صحيحة");
    }

    // 4. منع تكرار البريد الإلكتروني
    $check = $conn->prepare("SELECT id_users FROM users WHERE email = ?");
    $check->execute([$email]);

    if ($check->fetch()) {
        throw new Exception("البريد الإلكتروني مسجل مسبقاً");
    }

    // 5. تشفير كلمة المرور 🛡️
    $hashedPassword = password_hash($password, PASSWORD_DEFAULT);

    // 6. عملية الإدخال
    $stmt = $conn->prepare("INSERT INTO users (name, email, phn, password) VALUES (?, ?, ?, ?)");
    $stmt->execute([$name, $email, $phn, $hashedPassword]);

    echo json_encode([
        "status"  => true,
        "message" => "تم إنشاء الحساب بنجاح"
    ]);

} catch (PDOException $e) {
    // خطأ في قاعدة البيانات
    http_response_code(500);
    echo json_encode(["status" => false, "error" => "خطأ في قاعدة البيانات: " . $e->getMessage()]);
} catch (Exception $e) {
    // أخطاء منطقية (مثل البريد المتكرر أو الحقول الفارغة)
    http_response_code(400);
    echo json_encode(["status" => false, "error" => $e->getMessage()]);
}
?>
