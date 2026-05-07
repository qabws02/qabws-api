<?php
header("Content-Type: application/json; charset=UTF-8");
header("Access-Control-Allow-Origin: *"); // للسماح بالطلبات من Flutter

try {
    // يفضل استخدام متغيرات البيئة أو ملف إعدادات خارجي
    $host = "mysql-cc1c3ad-qabwsb02-598d.k.aivencloud.com";
    $port = "12495";
    $dbname = "defaultdb";
    $user = "avnadmin";
    $pass = getenv("DB_PASSWORD") ?: "YOUR_FALLBACK_PASSWORD"; // تأكد من ضبط متغير البيئة

    $conn = new PDO(
        "mysql:host=$host;port=$port;dbname=$dbname;charset=utf8mb4",
        $user,
        $pass,
        [
            PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION,
            PDO::ATTR_DEFAULT_FETCH_MODE => PDO::FETCH_ASSOC,
            PDO::ATTR_EMULATE_PREPARES => false,
        ]
    );

    // استقبال البيانات وتنظيفها
    $name     = filter_input(INPUT_POST, 'name', FILTER_SANITIZE_STRING);
    $email    = filter_input(INPUT_POST, 'email', FILTER_VALIDATE_EMAIL);
    $phn      = filter_input(INPUT_POST, 'phn', FILTER_SANITIZE_STRING);
    $password = $_POST['password'] ?? '';

    // التحقق من الحقول الإلزامية
    if (!$name || !$email || empty($password)) {
        throw new Exception("جميع الحقول مطلوبة، ويرجى التأكد من صحة البريد الإلكتروني.");
    }

    // التحقق من تكرار البريد الإلكتروني
    $check = $conn->prepare("SELECT id_users FROM users WHERE email = ? LIMIT 1");
    $check->execute([$email]);
    if ($check->fetch()) {
        throw new Exception("البريد الإلكتروني مسجل مسبقاً.");
    }

    // تشفير كلمة المرور
    $hashedPassword = password_hash($password, PASSWORD_DEFAULT);

    // عملية الإدخال
    $stmt = $conn->prepare("INSERT INTO users (name, email, phn, password) VALUES (?, ?, ?, ?)");
    $stmt->execute([$name, $email, $phn, $hashedPassword]);

    echo json_encode([
        "status" => true,
        "message" => "تم إنشاء الحساب بنجاح ✨"
    ]);

} catch (Exception $e) {
    // طباعة رسالة خطأ صديقة للمستخدم
    http_response_code(400);
    echo json_encode([
        "status" => false,
        "error" => $e->getMessage()
    ]);
} catch (PDOException $e) {
    // أخطاء قاعدة البيانات - لا نطبع $e->getMessage() في الإنتاج الفعلي للأمان
    http_response_code(500);
    echo json_encode([
        "status" => false,
        "error" => "حدث خطأ داخلي في الخادم، يرجى المحاولة لاحقاً."
    ]);
}
?>
