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

        $user_id = $_SESSION['user_id'];  // Store user ID in a variable for testing

        $stmt = $conn->prepare("SELECT role FROM users WHERE id = ?");
        $stmt->bind_param("i", $user_id);
        $stmt->execute();

        $result = $stmt->get_result();

        if ($result->num_rows === 1) {
            if ($result->fetch_assoc()['role'] === 'admin') {
                header("Location: admin_panel.html");
                exit();
            } else {
                header("Location: index.html");
                exit();
            }
        }
        exit();

    } else {
        echo "Incorrect password.";
    }

} else {
    echo "User not found.";
}

$stmt->close();
$conn->close();
?>
