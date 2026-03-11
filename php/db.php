<?php
// db.php - database connection file

$servername = "localhost";  // your server name, usually localhost
$username = "root";         // default XAMPP username
$password = "";             // default XAMPP password is empty
$dbname = "kasimarket"; // your database name

// Create connection
$conn = new mysqli($servername, $username, $password, $dbname);

// Check connection
if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}
// echo "Connected successfully"; // optional for testing
?>
