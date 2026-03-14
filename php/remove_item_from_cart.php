<?php
include 'db.php';
session_start();

// =============================
// SESSION CHECK
// =============================
if (!isset($_SESSION['user_id'])) {
    die(json_encode(["error" => "User not logged in"]));
}

$data = json_decode(file_get_contents("php://input"), true);

$cart_id = $data["id"];

$stmt = $conn->prepare("DELETE FROM cart WHERE id = ?");
$stmt->bind_param("i", $cart_id);

if ($stmt->execute()) {
    echo json_encode(["success" => true]);
} else {
    echo json_encode(["error" => "Delete failed"]);
}
?>