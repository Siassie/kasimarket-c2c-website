<?php
include 'db.php';
session_start();

// =============================
// SESSION CHECK
// =============================
if (!isset($_SESSION['user_id'])) {
    die(json_encode(["error" => "User not logged in"]));
}

$user_id = $_SESSION['user_id'];

// =============================
// INPUTS
// =============================
$title       = $_POST['item-title'] ?? null;
$price       = $_POST['item-price'] ?? null;
$category    = $_POST['item-category'] ?? null;
$condition   = $_POST['item-condition'] ?? null;
$description = $_POST['item-description'] ?? null;
$location    = $_POST['item-location'] ?? null;

$uploadDir = "../uploads/items/";

// =============================
// FUNCTIONS / METHODS
// =============================

/**
 * Insert item into DB (without photos)
 * Returns inserted item ID or false on error
 */
function insertItem($conn, $title, $description, $price, $category, $condition, $location, $user_id) {
    $stmt = $conn->prepare("
        INSERT INTO items
        (title, item_description, price, category, item_condition, item_location, user_id) 
        VALUES (?, ?, ?, ?, ?, ?, ?)
    ");
    if (!$stmt) {
        die(json_encode(["error" => "Prepare failed: " . $conn->error]));
    }

    $stmt->bind_param(
        "ssdsssi",
        $title,
        $description,
        $price,
        $category,
        $condition,
        $location,
        $user_id
    );

    if ($stmt->execute()) {
        $itemId = $conn->insert_id;
        $stmt->close();
        return $itemId;
    } else {
        $stmt->close();
        return false;
    }
}

/**
 * Upload photos and return array of filenames
 */
function uploadPhotos($files, $uploadDir, $itemId) {
    $photoPaths = [];

    if (empty($files['name'][0])) {
        return $photoPaths;
    }

    foreach ($files['tmp_name'] as $key => $tmp_name) {
        if ($files['error'][$key] !== 0) continue;

        $ext = pathinfo($files['name'][$key], PATHINFO_EXTENSION);
        $fileName = $itemId . "_" . ($key+1) . "." . $ext; // e.g., 15_1.jpg
        $targetFile = $uploadDir . $fileName;

        if (move_uploaded_file($tmp_name, $targetFile)) {
            $photoPaths[] = $fileName;
        }
    }

    return $photoPaths;
}

/**
 * Update item row with photos JSON
 */
function updateItemPhotos($conn, $itemId, $photoPaths) {
    if (empty($photoPaths)) return true; // nothing to update

    $photosJson = json_encode($photoPaths);
    $stmt = $conn->prepare("UPDATE items SET photos = ? WHERE id = ?");
    if (!$stmt) {
        die(json_encode(["error" => "Prepare failed: " . $conn->error]));
    }

    $stmt->bind_param("si", $photosJson, $itemId);

    $success = $stmt->execute();
    $stmt->close();

    return $success;
}

// =============================
// MAIN FLOW
// =============================
$itemId = insertItem($conn, $title, $description, $price, $category, $condition, $location, $user_id);

if (!$itemId) {
    die(json_encode(["error" => "Failed to insert item"]));
}

// Upload photos
$photoPaths = uploadPhotos($_FILES['item-photos'], $uploadDir, $itemId);

// Update item with photo paths
updateItemPhotos($conn, $itemId, $photoPaths);

header("Location: ../html/index.html");
exit();

$conn->close();
?>