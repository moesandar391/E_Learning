<?php
session_start();
if (!isset($_SESSION['user_id'])) {
    echo json_encode(['success' => false, 'message' => 'Unauthorized']);
    exit();
}

require_once '../config/db.php';

if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_FILES['profile_image'])) {
    $file = $_FILES['profile_image'];
    $allowed_types = ['image/jpeg', 'image/png', 'image/gif', 'image/webp'];

    if (!in_array($file['type'], $allowed_types)) {
        echo json_encode(['success' => false, 'message' => 'Invalid file type. Allowed: JPG, PNG, GIF, WebP']);
        exit();
    }

    if ($file['size'] > 5 * 1024 * 1024) {
        echo json_encode(['success' => false, 'message' => 'File too large (max 5MB)']);
        exit();
    }

    $ext = pathinfo($file['name'], PATHINFO_EXTENSION);
    $filename = 'profile_' . $_SESSION['user_id'] . '_' . time() . '.' . $ext;
    $upload_dir = __DIR__ . '/profile_images/';

    if (!is_dir($upload_dir)) {
        mkdir($upload_dir, 0755, true);
    }

    $destination = $upload_dir . $filename;

    if (move_uploaded_file($file['tmp_name'], $destination)) {
        $db_path = 'profile_images/' . $filename;
        $stmt = $conn->prepare("UPDATE users SET profile_image = ? WHERE id = ?");
        $stmt->bind_param("si", $db_path, $_SESSION['user_id']);

        if ($stmt->execute()) {
            echo json_encode(['success' => true, 'path' => $db_path]);
        } else {
            echo json_encode(['success' => false, 'message' => 'Database error']);
        }
    } else {
        echo json_encode(['success' => false, 'message' => 'Failed to save file']);
    }
} else {
    echo json_encode(['success' => false, 'message' => 'Invalid request']);
}
