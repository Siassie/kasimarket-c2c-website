<?php
session_start();
include 'db.php';

$email = $_POST['email'] ?? '';
$password = $_POST['password'] ?? '';

// Prepare statement
$stmt = $conn->prepare("SELECT * FROM users WHERE email = ?");
$stmt->bind_param("s", $email);
$stmt->execute();

$result = $stmt->get_result();

if ($result->num_rows === 1) {

    $user = $result->fetch_assoc();

    // Plain text password check
    if ($password === $user['password']) {

        $_SESSION['user_id'] = $user['id'];
        $_SESSION['user_name'] = $user['name'];
        $_SESSION['user_surname'] = $user['surname'];
        $_SESSION['user_email'] = $user['email'];

        echo "Login successful!<br>";
        echo "SESSION USER ID: " . $_SESSION['user_id'];  // testing

    } else {
        echo "Incorrect password.";
    }

} else {
    echo "User not found.";
}

$stmt->close();
$conn->close();
?>
