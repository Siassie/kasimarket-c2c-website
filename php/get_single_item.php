<?php

header("Content-Type: application/json");
require_once("db.php");

$id = $_GET['id'];

$stmt = $conn->prepare("SELECT * FROM items WHERE id = ?");
$stmt->bind_param("i", $id);

$stmt->execute();
$result = $stmt->get_result();

$item = $result->fetch_assoc();

echo json_encode($item);

$stmt->close();
$conn->close();

?>