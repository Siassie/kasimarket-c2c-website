<?php
// add_item.php - processes the form

include 'db.php'; // connect to database

// Get POST data safely
$name = $_POST['name'] ?? '';
$surname = $_POST['surname'] ?? '';
$email = $_POST['email'] ?? '';
$password = $_POST['password'] ?? '';
// $role = $_POST['role'] ?? '';

// Prepare SQL statement to prevent SQL injection
$stmt = $conn->prepare("INSERT INTO users (name, surname, email, password) VALUES (?, ?, ?, ?)");
$stmt->bind_param("ssss", $name, $surname, $email, $password);

// Execute and check
if ($stmt->execute()) {
    echo "User successfully added!";
} else {
    echo "Error: " . $stmt->error;
}

$stmt->close();
$conn->close();
?>
