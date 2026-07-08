<?php
function create_admin_notification($message, $link = '', $type = 'general') {
    global $conn;
    if (!$conn) {
        require_once __DIR__ . '/../config/db.php';
    }
    $stmt = $conn->prepare(
        "INSERT INTO admin_notifications (type, message, link, is_read, created_at) VALUES (?, ?, ?, 0, NOW())"
    );
    $stmt->bind_param("sss", $type, $message, $link);
    return $stmt->execute();
}
