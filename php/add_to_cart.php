<?php
include 'db.php';
session_start();

// =============================
// SESSION CHECK - IF NO USER LOGGED IN, RETURN ERROR ELSE GET USER ID
// BEACUSE ONLY LOGGED IN USERS CAN ADD TO CART AND THE CART IS TIED TO THE USER ID
// =============================
if (!isset($_SESSION['user_id'])) {
    die(json_encode(["error" => "User not logged in"]));
}

$user_id = $_SESSION['user_id'];


// =============================
// INPUTS - GET ITEM ID FROM URL PARAMETER
// =============================

$encoded = null;

if (isset($_POST['id'])) {
    $encoded = $_POST['id'];
} else {
    die(json_encode(["error" => "Item ID not provided"]));
}

if (!is_numeric($encoded)) {
    die(json_encode(["error" => "Invalid item ID"]));
}

if (!$user_id || !$encoded) {
    die(json_encode(["error" => "Missing user ID or item ID"]));
} else {
    $stmt = $conn->prepare("
        INSERT INTO cart
        (user_id, item_id) 
        VALUES (?, ?)
    ");

    if (!$stmt) {
        die(json_encode(["error" => "Prepare failed: " . $conn->error]));
    }

    $stmt->bind_param(
        "ii",
        $user_id,
        $encoded
    );

    if ($stmt->execute()) {
        $stmt->close();
        echo json_encode(["success" => "Item added to cart"]);
    } else {
        $stmt->close();
        die(json_encode(["error" => "Failed to add item to cart: " . $stmt->error]));
    }
}

?>