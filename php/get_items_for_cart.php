<?php
header("Content-Type: application/json");
require_once("db.php");
session_start();

// =============================
// SESSION CHECK
// =============================
if (!isset($_SESSION['user_id'])) {
    echo json_encode(["error" => "User not logged in"]);
    exit;
}

$user_id = $_SESSION['user_id'];

$items_for_cart = fetchCartItems($conn, $user_id);

echo json_encode($items_for_cart);

$conn->close();


// =============================
// FETCH CART ITEMS WITH ITEM INFO
// =============================
function fetchCartItems($conn, $user_id) {

    $sql = "
            SELECT 
                cart.id,
                cart.item_id,
                cart.quantity,
                items.title,
                items.price,
                items.item_condition,
                items.photos
            FROM cart
            JOIN items ON cart.item_id = items.id
            WHERE cart.user_id = ?
            ORDER BY cart.created_at DESC
            ";

    $stmt = $conn->prepare($sql);

    if (!$stmt) {
        return ["error" => $conn->error];
    }

    $stmt->bind_param("i", $user_id);
    $stmt->execute();

    $result = $stmt->get_result();

    $items = [];

    while ($row = $result->fetch_assoc()) {

        $photos = json_decode($row['photos'], true);

        $items[] = [
            "id" => $row["id"],
            "item_id" => $row["item_id"],
            "quantity" => $row["quantity"],
            "title" => $row["title"],
            "price" => $row["price"],
            "condition" => $row["item_condition"],
            "photo" => $photos[0] ?? null
        ];
    }

    return $items;
}
?>
