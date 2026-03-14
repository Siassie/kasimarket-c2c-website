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

$encoded = null;

if (isset($_POST['id'])) {
    $encoded = $_POST['id'];
} else {
    die(json_encode(["error" => "Item ID not provided"]));
}

$exists = check_if_item_already_in_cart($conn, $user_id, $encoded);

if ($exists) {
    $updated = update_cart_item($conn, $user_id, $encoded);
    if ($updated) {
        echo json_encode(["success" => "Item quantity updated in cart"]);
    } else {
        echo json_encode(["error" => "Failed to update item quantity in cart"]);
    }
} else {
    $inserted = insert_item_into_cart($conn, $user_id, $encoded);
    if ($inserted) {
        echo json_encode(["success" => "Item added to cart"]);
    } else {
        echo json_encode(["error" => "Failed to add item to cart"]);
    }
}

// =============================
// CHECK IF ITEM ALREADY IN CART
// =============================
function check_if_item_already_in_cart($conn, $user_id, $item_id) {
    $stmt = $conn->prepare("SELECT id FROM cart WHERE user_id = ? AND item_id = ?");
    if (!$stmt) {
        die(json_encode(["error" => "Prepare failed: " . $conn->error]));
    }

    $stmt->bind_param("ii", $user_id, $item_id);
    $stmt->execute();
    $result = $stmt->get_result();

    $exists = $result->num_rows > 0;

    $stmt->close();

    return $exists;
}

// =============================
// INSERT ITEM INTO CART IF NOT ALREADY IN CART
// =============================
function insert_item_into_cart($conn, $user_id, $item_id) {
    $stmt = $conn->prepare("INSERT INTO cart (user_id, item_id, quantity) VALUES (?, ?, ?)");
    if (!$stmt) {
        die(json_encode(["error" => "Prepare failed: " . $conn->error]));
    }

    $quantity = 1; // variable instead of literal
    $stmt->bind_param("iii", $user_id, $item_id, $quantity);

    if ($stmt->execute()) {
        $stmt->close();
        return true;
    } else {
        $stmt->close();
        return false;
    }
}

// =============================
// UPDATE QUANTITY IF ITEM ALREADY IN CART
// =============================
function update_cart_item($conn, $user_id, $item_id) {
    $stmt = $conn->prepare("UPDATE cart SET quantity = quantity + 1 WHERE user_id = ? AND item_id = ?");
    if (!$stmt) {
        die(json_encode(["error" => "Prepare failed: " . $conn->error]));
    }

    $stmt->bind_param("ii", $user_id, $item_id);

    if ($stmt->execute()) {
        $stmt->close();
        return true;
    } else {
        $stmt->close();
        return false;
    }
}
?>