<?php
session_start();
require_once '../config/db.php';

header('Content-Type: application/json');

if (!isset($_SESSION['role']) || $_SESSION['role'] !== 'admin') {
    echo json_encode(['success' => false, 'message' => 'Unauthorized.']);
    exit;
}

$action = $_POST['action'] ?? '';

if ($action === 'get_details') {
    $user_id = intval($_POST['id'] ?? 0);
    if (!$user_id) {
        echo json_encode(['success' => false, 'message' => 'Invalid student ID.']);
        exit;
    }

    $user = $conn->query("SELECT id, name, email, phone, gender, created_at FROM users WHERE id = $user_id")->fetch_assoc();
    if (!$user) {
        echo json_encode(['success' => false, 'message' => 'Student not found.']);
        exit;
    }

    $enrollments = $conn->query("
        SELECT m.id, m.name AS module_name, c.course_name, e.status AS enroll_status,
               e.enroll_date,
               COUNT(l.id) AS total_lessons,
               COUNT(lp.id) AS completed_lessons
        FROM enrollments e
        JOIN modules m ON e.module_id = m.id
        JOIN courses c ON m.course_id = c.id
        LEFT JOIN lessons l ON m.id = l.module_id
        LEFT JOIN lesson_progress lp ON l.id = lp.lesson_id AND lp.user_id = $user_id AND lp.completed = 1
        WHERE e.user_id = $user_id AND e.status = 'confirmed'
        GROUP BY m.id, m.name, c.course_name, e.status, e.enroll_date
        ORDER BY e.created_at DESC
    ")->fetch_all(MYSQLI_ASSOC);

    echo json_encode(['success' => true, 'data' => ['user' => $user, 'enrollments' => $enrollments]]);
    exit;
}

if ($action === 'delete') {
    $user_id = intval($_POST['id'] ?? 0);
    if (!$user_id) {
        echo json_encode(['success' => false, 'message' => 'Invalid student ID.']);
        exit;
    }

    $conn->query("DELETE FROM users WHERE id = $user_id");

    if ($conn->affected_rows > 0) {
        echo json_encode(['success' => true, 'message' => 'Student deleted.']);
    } else {
        echo json_encode(['success' => false, 'message' => 'Student not found.']);
    }
    exit;
}

echo json_encode(['success' => false, 'message' => 'Unknown action.']);
