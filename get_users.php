<?php
include 'db.php'; // adjust path if needed

header('Content-Type: application/json');

$sql = "SELECT id, name, surname, email, password, role, created_at FROM users";
$result = $conn->query($sql);

$items = [];

if ($result->num_rows > 0) {
    while ($row = $result->fetch_assoc()) {
        $items[] = $row;
    }
}

echo json_encode($items);

$conn->close();
