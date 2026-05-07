<?php
header("Content-Type: application/json");

$conn = new PDO(
  "mysql:host=HOST;port=PORT;dbname=defaultdb;charset=utf8mb4",
  "avnadmin",
  getenv("DB_PASSWORD")
);

$name = $_POST['name'];
$email = $_POST['email'];
$phn = $_POST['phn'];
$password = $_POST['password'];

$sql = "INSERT INTO users (name, email, phn, password)
        VALUES (?, ?, ?, ?)";

$stmt = $conn->prepare($sql);
$ok = $stmt->execute([$name, $email, $phn, $password]);

echo json_encode([
  "status" => $ok
]);
?>
