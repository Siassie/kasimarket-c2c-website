<?php
include 'db.php'; // adjust path if needed

header('Content-Type: application/json');

$sql = "SELECT id, title, price, item_description, category, item_condition, item_location, user_id, admin_review, admin_id, created_at FROM items";
$result = $conn->query($sql);

$items = [];

if ($result->num_rows > 0) {
    while ($row = $result->fetch_assoc()) {
        $items[] = $row;
    }
}

echo json_encode($items);

$conn->close();
