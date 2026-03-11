<?php
include 'db.php';
session_start();

if (!isset($_SESSION['user_id'])) {
    die("User not logged in");
}

$user_id = $_SESSION['user_id'];

$title = $_POST['item-title'];
$price = $_POST['item-price'];
$category = $_POST['item-category'];
$condition = $_POST['item-condition'];
$description = $_POST['item-description'];
$location = $_POST['item-location'];

$uploadDir = "../uploads/items/";
$photoPaths = [];

if (!empty($_FILES['item-photos']['name'][0])) {

    foreach ($_FILES['item-photos']['tmp_name'] as $key => $tmp_name) {

        if ($_FILES['item-photos']['error'][$key] === 0) {

            $ext = pathinfo($_FILES['item-photos']['name'][$key], PATHINFO_EXTENSION);
            $fileName = time() . "_" . uniqid() . "." . $ext;

            $targetFile = $uploadDir . $fileName;

            if (move_uploaded_file($tmp_name, $targetFile)) {
                $photoPaths[] = $fileName;
            }
        }
    }
}

$photosJson = json_encode($photoPaths);

$stmt = $conn->prepare("
INSERT INTO items 
(title, item_description, price, category, item_condition, item_location, user_id, photos) 
VALUES (?, ?, ?, ?, ?, ?, ?, ?)
");

$stmt->bind_param(
    "ssdsssis",
    $title,
    $description,
    $price,
    $category,
    $condition,
    $location,
    $user_id,
    $photosJson
);

if ($stmt->execute()) {
    header("Location: ../html/index.html");
    exit();
} else {
    echo "Error: " . $stmt->error;
}

$stmt->close();
$conn->close();
?>