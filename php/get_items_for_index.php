<?php
header("Content-Type: application/json");

require_once("db.php");

$result = fetchItems($conn);

$items = formatItemCard($result);

echo json_encode($items);

$conn->close();

function fetchItems($conn) {
    $sql = "SELECT id, title, price, category, created_at, photos 
        FROM items 
        ORDER BY created_at DESC";

    $result = $conn->query($sql);

    return $result;
}

function formatItemCard($result) {
    $items = [];

    while ($row = $result->fetch_assoc()) {

        $photos = json_decode($row['photos'], true);

        $row['photo'] = $photos[0] ?? null; // first image = cover photo

        $items[] = $row;
    }

    return $items;
}
?>