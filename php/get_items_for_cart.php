<?php
header("Content-Type: application/json");
require_once("db.php");
session_start();

// =============================
// SESSION CHECK - IF NO USER LOGGED IN, RETURN ERROR ELSE GET USER ID
// BEACUSE ONLY LOGGED IN USERS CAN VIEW CART AND THE CART IS TIED TO THE USER ID
// =============================
if (!isset($_SESSION['user_id'])) {
    die(json_encode(["error" => "User not logged in"]));
}

$user_id = $_SESSION['user_id'];


$sql = "SELECT id, item_id, quantity 
        FROM cart 
        WHERE user_id = ?
        ORDER BY created_at DESC";

$stmt = $conn->prepare($sql);
$stmt->bind_param("i", $user_id);
$stmt->execute();
$result = $stmt->get_result();

$items = [];

$items_for_cart = [];

while ($row = $result->fetch_assoc()) {

    $id = $row['id'];
    $item_id = $row['item_id'];
    $quantity = $row['quantity'];

    $items[] = [
        "id" => $id,
        "item_id" => $item_id,
        "quantity" => $quantity
    ];
}

$item_sql = "SELECT title, price, item_condition, photos 
             FROM items 
             WHERE id = ?";

foreach ($items as &$cart_item) {

    $item_id = $cart_item['item_id'];

    $stmt = $conn->prepare($item_sql);
    $stmt->bind_param("i", $item_id);
    $stmt->execute();
    $item_result = $stmt->get_result();

    if ($item_row = $item_result->fetch_assoc()) {

        $cart_item['title'] = $item_row['title'];
        $cart_item['price'] = $item_row['price'];
        $cart_item['condition'] = $item_row['item_condition'];

        $photos = json_decode($item_row['photos'], true);
        $cart_item['photo'] = $photos[0] ?? null;

        $items_for_cart[] = $cart_item;
    }
}

echo json_encode($items_for_cart);

$conn->close();
?>