<?php
session_set_cookie_params(['path' => '/']);
session_start();

if (!isset($_SESSION['role']) || $_SESSION['role'] !== 'admin') {
    echo json_encode(['error' => 'Unauthorized']);
    exit();
}

header('Content-Type: application/json; charset=utf-8');
require_once '../config/db.php';

$action = $_GET['action'] ?? 'fetch';

if ($action === 'mark_read') {
    $raw_id = $_GET['id'] ?? '';
    if (is_numeric($raw_id)) {
        $id = intval($raw_id);
        $conn->query("UPDATE admin_notifications SET is_read = 1 WHERE id = $id");
    }
    echo json_encode(['success' => true]);
    exit;
}

if ($action === 'mark_all_read') {
    $conn->query("UPDATE admin_notifications SET is_read = 1 WHERE is_read = 0");
    echo json_encode(['success' => true]);
    exit;
}

// Fetch pending enrollments
$pending_list = [];
$pending_result = $conn->query("
    SELECT e.id, e.created_at, u.name AS user_name, c.course_name, m.name AS module_name 
    FROM enrollments e
    JOIN users u ON e.user_id = u.id
    JOIN modules m ON e.module_id = m.id
    JOIN courses c ON m.course_id = c.id
    WHERE LOWER(e.status) = 'pending'
    ORDER BY e.created_at DESC 
    LIMIT 10
");
if ($pending_result) {
    while ($row = $pending_result->fetch_assoc()) {
        $pending_list[] = [
            'id' => 'pending_' . $row['id'],
            'type' => 'enrollment',
            'message' => $row['user_name'] . ' enrolled in ' . $row['course_name'] . ' - ' . $row['module_name'],
            'link' => 'enrollments.php',
            'time_ago' => getTimeAgo($row['created_at']),
            'is_read' => 0,
            'created_at' => $row['created_at']
        ];
    }
}

// Fetch from admin_notifications table
$notif_list = [];
$notif_result = $conn->query("SELECT id, type, message, link, is_read, created_at FROM admin_notifications ORDER BY created_at DESC LIMIT 20");
if ($notif_result) {
    while ($row = $notif_result->fetch_assoc()) {
        $notif_list[] = [
            'id' => $row['id'],
            'type' => $row['type'],
            'message' => $row['message'],
            'link' => $row['link'],
            'time_ago' => getTimeAgo($row['created_at']),
            'is_read' => (int)$row['is_read'],
            'created_at' => $row['created_at']
        ];
    }
}

$all = array_merge($pending_list, $notif_list);
usort($all, function($a, $b) {
    return strtotime($b['created_at']) - strtotime($a['created_at']);
});
$all = array_slice($all, 0, 10);

$unread_pending = (int)($conn->query("SELECT COUNT(*) FROM enrollments WHERE LOWER(status) = 'pending'")->fetch_row()[0] ?? 0);
$unread_notif = (int)($conn->query("SELECT COUNT(*) FROM admin_notifications WHERE is_read = 0")->fetch_row()[0] ?? 0);

echo json_encode([
    'count' => $unread_pending + $unread_notif,
    'notifications' => $all
]);
exit();

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
