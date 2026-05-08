<?php
header("Content-Type: application/json; charset=UTF-8");
header("Access-Control-Allow-Origin: *");
header("Access-Control-Allow-Methods: POST");
header("Access-Control-Allow-Headers: Content-Type, Access-Control-Allow-Headers, Authorization, X-Requested-With");

// إعدادات PHPMailer (تأكد من تحميل المجلد في نفس المسار)
use PHPMailer\PHPMailer\PHPMailer;
use PHPMailer\PHPMailer\Exception;
require 'PHPMailer/src/Exception.php';
require 'PHPMailer/src/PHPMailer.php';
require 'PHPMailer/src/SMTP.php';

// بيانات قاعدة البيانات (Aiven Cloud)
$host = "mysql-cc1c3ad-qabwsb02-598d.k.aivencloud.com";
$port = "12495";
$dbname = "defaultdb";
$user = "avnadmin";
$pass = getenv("DB_PASSWORD"); // استبدلها بكلمة المرور الحقيقية

try {
    $pdo = new PDO("mysql:host=$host;port=$port;dbname=$dbname;charset=utf8mb4", $user, $pass);
    $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);

    $action = $_GET['action'] ?? ''; // نحدد العملية عبر الرابط

    // 1. مرحلة إرسال الكود
    if ($action == 'send') {
        $email = $_POST['email'] ?? '';
        if (empty($email)) die(json_encode(["status" => "error", "message" => "الإيميل مطلوب"]));

        $otp = rand(100000, 999999);
        
        // حفظ أو تحديث الكود في القاعدة
        $stmt = $pdo->prepare("REPLACE INTO otp_verification (email, otp) VALUES (?, ?)");
        $stmt->execute([$email, $otp]);

        // إرسال الإيميل عبر PHPMailer
        $mail = new PHPMailer(true);
        $mail->isSMTP();
        $mail->Host       = 'smtp.gmail.com';
        $mail->SMTPAuth   = true;
        $mail->Username   = 'your_email@gmail.com'; // بريدك
        $mail->Password   = 'your_app_password';    // كود التطبيقات من جوجل
        $mail->SMTPSecure = PHPMailer::ENCRYPTION_STARTTLS;
        $mail->Port       = 587;

        $mail->setFrom('noreply@yourdomain.com', 'Verification System');
        $mail->addAddress($email);
        $mail->isHTML(true);
        $mail->Subject = 'رمز التحقق الخاص بك';
        $mail->Body    = "<div style='text-align:center; font-family:Arial;'><h2>كود التحقق</h2><h1 style='color:blue;'>$otp</h1></div>";

        $mail->send();
        echo json_encode(["status" => "success", "message" => "تم إرسال الكود بنجاح"]);
    } 
    
    // 2. مرحلة التحقق من الكود
    else if ($action == 'verify') {
        $email = $_POST['email'] ?? '';
        $otp = $_POST['otp'] ?? '';

        $stmt = $pdo->prepare("SELECT * FROM otp_verification WHERE email = ? AND otp = ?");
        $stmt->execute([$email, $otp]);
        
        if ($stmt->fetch()) {
            // حذف الكود بعد النجاح لزيادة الأمان
            $pdo->prepare("DELETE FROM otp_verification WHERE email = ?")->execute([$email]);
            echo json_encode(["status" => "success", "message" => "تم التحقق بنجاح"]);
        } else {
            echo json_encode(["status" => "error", "message" => "الكود الذي أدخلته خاطئ"]);
        }
    }

} catch (Exception $e) {
    echo json_encode(["status" => "error", "message" => $e->getMessage()]);
}
