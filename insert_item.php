<?php
include 'db.php';
session_start();

if (!isset($_SESSION['user_id'])) {
    die("User not logged in");
}

$user_id = $_SESSION['user_id'];

$title = $_POST['item-title'] ?? '';
$price = $_POST['item-price'] ?? '';
$category = $_POST['item-category'] ?? '';
$condition= $_POST['item-condition'] ?? '';
$description = $_POST['item-description'] ?? '';
$location = $_POST['item-location'] ?? '';

// Optional: Check for duplicate title
$stmt_check = $conn->prepare("SELECT id FROM items WHERE title = ?");
$stmt_check->bind_param("s", $title);
$stmt_check->execute();
$stmt_check->store_result();

if ($stmt_check->num_rows > 0) {
    die("Error: An item with this title already exists.");
}
$stmt_check->close();

// Insert item
$stmt = $conn->prepare("INSERT INTO items (title, price, category, item_condition, item_description, item_location, user_id) VALUES (?, ?, ?, ?, ?, ?, ?)");
$stmt->bind_param("sdssssi", $title, $price, $category, $condition, $description, $location, $user_id);

if ($stmt->execute()) {
    header("Location: index.html"); // Redirect after success
    exit();
} else {
    echo "Error: " . $stmt->error;
}

$stmt->close();
$conn->close();
?>
