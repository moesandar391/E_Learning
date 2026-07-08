<?php
session_set_cookie_params(['path' => '/']);
session_start();

if (!isset($_SESSION['user_id'])) {
    echo json_encode(['error' => 'Unauthorized']);
    exit();
}

header('Content-Type: application/json; charset=utf-8');
require_once '../config/db.php';
require_once '../includes/notification_helper.php';

$user_id = $_SESSION['user_id'];
$action = $_GET['action'] ?? 'fetch';

if ($action === 'fetch') {
    $notifications = get_user_notifications($user_id, 10);
    $unread_count = get_unread_notification_count($user_id);
    echo json_encode([
        'count' => $unread_count,
        'notifications' => $notifications
    ]);
} elseif ($action === 'mark_read') {
    $notification_id = isset($_GET['id']) ? intval($_GET['id']) : 0;
    if ($notification_id) {
        mark_notification_read($notification_id, $user_id);
    }
    echo json_encode(['success' => true]);
} elseif ($action === 'mark_all_read') {
    mark_all_notifications_read($user_id);
    echo json_encode(['success' => true]);
} else {
    echo json_encode(['error' => 'Invalid action']);
}
