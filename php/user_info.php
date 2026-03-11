<?php
session_start();
header('Content-Type: application/json');

if (isset($_SESSION['user_id'])) {
    echo json_encode([
        'name' => $_SESSION['user_name'],  // from login.php
        'surname' => $_SESSION['user_surname'] ?? '', // optional
        'email' => $_SESSION['user_email'] ?? ''      // optional
    ]);
} else {
    echo json_encode([
        'name' => 'Guest',
        'surname' => '',
        'email' => 'Please log in'
    ]);
}
?>