<?php
function create_notification($user_id, $message, $link = '', $type = 'general') {
    global $conn;
    if (!$conn) {
        require_once __DIR__ . '/../config/db.php';
    }
    $stmt = $conn->prepare(
        "INSERT INTO notifications (user_id, message, link, type, is_read, created_at) VALUES (?, ?, ?, ?, 0, NOW())"
    );
    $stmt->bind_param("isss", $user_id, $message, $link, $type);
    return $stmt->execute();
}

function get_user_notifications($user_id, $limit = 10) {
    global $conn;
    if (!$conn) {
        require_once __DIR__ . '/../config/db.php';
    }
    $stmt = $conn->prepare(
        "SELECT id, message, link, type, is_read, created_at 
         FROM notifications 
         WHERE user_id = ? 
         ORDER BY created_at DESC 
         LIMIT ?"
    );
    $stmt->bind_param("ii", $user_id, $limit);
    $stmt->execute();
    $result = $stmt->get_result();
    $notifications = [];
    while ($row = $result->fetch_assoc()) {
        $row['time_ago'] = getTimeAgo($row['created_at']);
        $notifications[] = $row;
    }
    return $notifications;
}

function get_unread_notification_count($user_id) {
    global $conn;
    if (!$conn) {
        require_once __DIR__ . '/../config/db.php';
    }
    $stmt = $conn->prepare("SELECT COUNT(*) FROM notifications WHERE user_id = ? AND is_read = 0");
    $stmt->bind_param("i", $user_id);
    $stmt->execute();
    return (int) $stmt->get_result()->fetch_row()[0];
}

function mark_notification_read($notification_id, $user_id) {
    global $conn;
    if (!$conn) {
        require_once __DIR__ . '/../config/db.php';
    }
    $stmt = $conn->prepare("UPDATE notifications SET is_read = 1 WHERE id = ? AND user_id = ?");
    $stmt->bind_param("ii", $notification_id, $user_id);
    return $stmt->execute();
}

function mark_all_notifications_read($user_id) {
    global $conn;
    if (!$conn) {
        require_once __DIR__ . '/../config/db.php';
    }
    $stmt = $conn->prepare("UPDATE notifications SET is_read = 1 WHERE user_id = ? AND is_read = 0");
    $stmt->bind_param("i", $user_id);
    return $stmt->execute();
}

function getTimeAgo($datetime) {
    $now = new DateTime();
    $ago = new DateTime($datetime);
    $diff = $now->diff($ago);

    if ($diff->y > 0) return $diff->y . ' year' . ($diff->y > 1 ? 's' : '') . ' ago';
    if ($diff->m > 0) return $diff->m . ' month' . ($diff->m > 1 ? 's' : '') . ' ago';
    if ($diff->d > 0) return $diff->d . ' day' . ($diff->d > 1 ? 's' : '') . ' ago';
    if ($diff->h > 0) return $diff->h . ' hour' . ($diff->h > 1 ? 's' : '') . ' ago';
    if ($diff->i > 0) return $diff->i . ' minute' . ($diff->i > 1 ? 's' : '') . ' ago';
    return 'Just now';
}
