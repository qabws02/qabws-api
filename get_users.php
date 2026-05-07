<?php
header("Content-Type: application/json");
include "conn.php";

$sql = "SELECT id_users, name, email, phn, img, addrss FROM users";
$result = $conn->query($sql);

$users = [];

if ($result->num_rows > 0) {
    while ($row = $result->fetch_assoc()) {
        $users[] = $row;
    }

    echo json_encode([
        "status" => "success",
        "data" => $users
    ]);
} else {
    echo json_encode([
        "status" => "empty",
        "data" => []
    ]);
}
?>
