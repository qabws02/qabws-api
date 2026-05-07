<?php
header("Content-Type: application/json");

try {

    include "conn.php";

    $name = trim($_POST['name'] ?? '');
    $email = trim($_POST['email'] ?? '');
    $phn = trim($_POST['phn'] ?? '');

    if ($name == '' || $email == '') {
        echo json_encode([
            "status" => false,
            "error" => "Missing required fields"
        ]);
        exit;
    }

    // منع تكرار الإيميل
    $check = $conn->prepare("SELECT id_users FROM users WHERE email = ?");
    $check->execute([$email]);

    if ($check->fetch()) {
        echo json_encode([
            "status" => false,
            "error" => "Email already exists"
        ]);
        exit;
    }

    $imageName = "";

    // إذا توجد صورة
    if (isset($_FILES['img']) && $_FILES['img']['error'] == 0) {

        $uploadDir = "uploads/";

        // إنشاء المجلد إذا غير موجود
        if (!is_dir($uploadDir)) {
            mkdir($uploadDir, 0777, true);
        }

        $extension = pathinfo($_FILES['img']['name'], PATHINFO_EXTENSION);

        $imageName = time() . "_" . uniqid() . "." . $extension;

        $targetPath = $uploadDir . $imageName;

        if (!move_uploaded_file($_FILES['img']['tmp_name'], $targetPath)) {
            echo json_encode([
                "status" => false,
                "error" => "Failed to upload image"
            ]);
            exit;
        }
    }

    // حفظ المستخدم
    $stmt = $conn->prepare("
        INSERT INTO users (name, email, phn, img)
        VALUES (?, ?, ?, ?)
    ");

    $ok = $stmt->execute([$name, $email, $phn, $imageName]);

    echo json_encode([
        "status" => true,
        "message" => "User added successfully",
        "image" => $imageName
    ]);

} catch (PDOException $e) {

    echo json_encode([
        "status" => false,
        "error" => $e->getMessage()
    ]);
}
?>
