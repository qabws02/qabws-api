<?php
header("Content-Type: application/json; charset=utf-8");

try {

    include "conn.php";

    $stmt = $conn->prepare("SELECT id_users, name, email, phn FROM users ORDER BY id_users DESC");
    $stmt->execute();

    $data = $stmt->fetchAll(PDO::FETCH_ASSOC);

    echo json_encode([
        "status" => true,
        "data" => $data
    ], JSON_UNESCAPED_UNICODE);

} catch (PDOException $e) {

    echo json_encode([
        "status" => false,
        "error" => $e->getMessage()
    ]);
}
?>
