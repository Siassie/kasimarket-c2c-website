<?php
include 'db.php';
session_start();

if (!isset($_SESSION['user_id'])) {
    die("User not logged in");
}

$admin_id = $_SESSION['user_id'];
$item_id = 1;
$admin_review = 1;

// // Optional: Check for duplicate title
// $stmt_check = $conn->prepare("SELECT id FROM items WHERE title = ?");
// $stmt_check->bind_param("s", $title);
// $stmt_check->execute();
// $stmt_check->store_result();

// if ($stmt_check->num_rows > 0) {
//     die("Error: An item with this title already exists.");
// }
// $stmt_check->close();

// Insert item
$stmt = $conn->prepare("UPDATE items SET admin_review = ?, admin_id = ? WHERE id = ?");
$stmt->bind_param("iii", $admin_review, $admin_id, $item_id);

if ($stmt->execute()) {
    header("Location: ../html/admin_panel.html"); // Redirect after success
    exit();
} else {
    echo "Error: " . $stmt->error;
}

$stmt->close();
$conn->close();
?>
