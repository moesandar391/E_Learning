<?php
session_start();
require_once '../config/db.php';
require_once '../includes/notification_helper.php';
require_once '../includes/admin_notification_helper.php';

header('Content-Type: application/json');

if (!isset($_SESSION['role']) || $_SESSION['role'] !== 'admin') {
    echo json_encode(['success' => false, 'message' => 'Unauthorized.']);
    exit;
}

 $action = $_POST['action'] ?? '';

if ($action === 'confirm' || $action === 'reject') {
    $enroll_id = intval($_POST['id'] ?? 0);
    if (!$enroll_id) {
        echo json_encode(['success' => false, 'message' => 'Invalid enrollment ID.']);
        exit;
    }

    $enroll = $conn->query("SELECT e.user_id, u.name AS user_name, m.name AS module_name, c.course_name 
        FROM enrollments e
        JOIN users u ON e.user_id = u.id
        JOIN modules m ON e.module_id = m.id
        JOIN courses c ON m.course_id = c.id
        WHERE e.id = $enroll_id")->fetch_assoc();

    if (!$enroll) {
        echo json_encode(['success' => false, 'message' => 'Enrollment not found.']);
        exit;
    }

    if ($action === 'confirm') {
        $conn->query("UPDATE enrollments SET status = 'confirmed' WHERE id = $enroll_id");
        $mod_id = $conn->query("SELECT module_id FROM enrollments WHERE id = $enroll_id")->fetch_row()[0];
        create_notification(
            $enroll['user_id'],
            'Enrollment Successful! You can now access "' . $enroll['module_name'] . '" lessons.',
            'my_learning.php',
            'enrollment'
        );
        echo json_encode(['success' => true, 'message' => 'Enrollment confirmed.']);
    } else {
        $reason = trim($_POST['reason'] ?? '');
        $conn->query("UPDATE enrollments SET status = 'rejected' WHERE id = $enroll_id");

        if ($reason) {
            $message = 'Your enrollment in "' . $enroll['module_name'] . '" was rejected: ' . $reason . '. Please contact support.';
        } else {
            $message = 'Your enrollment in "' . $enroll['module_name'] . '" was rejected. Please contact support.';
        }

        create_notification(
            $enroll['user_id'],
            $message,
            'contact.php?enrollment_id=' . $enroll_id,
            'enrollment'
        );
        echo json_encode(['success' => true, 'message' => 'Enrollment rejected.']);
    }
    exit;
}

echo json_encode(['success' => false, 'message' => 'Unknown action.']);