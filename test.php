<?php
// This script processes form data and shows a message.

// Access POST variables from the form.
$name = $_POST['item-title'] ?? 'Guest';  // Use 'Guest' if no name provided.

echo "Hello, " . htmlspecialchars($name) . "! Your form was submitted.";
?>