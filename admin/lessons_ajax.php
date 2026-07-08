<?php
session_start();
require_once '../config/db.php';

header('Content-Type: application/json');

if (!isset($_SESSION['role']) || $_SESSION['role'] !== 'admin') {
    echo json_encode(['success' => false, 'message' => 'Unauthorized.']);
    exit;
}

 $action = $_GET['action'] ?? $_POST['action'] ?? '';

if ($action === 'get_modules' && isset($_GET['course_id'])) {
    $courseId = (int)$_GET['course_id'];
    $stmt = $conn->prepare("SELECT id, name FROM modules WHERE course_id = ? ORDER BY name ASC");
    $stmt->bind_param("i", $courseId);
    $stmt->execute();
    $result = $stmt->get_result()->fetch_all(MYSQLI_ASSOC);
    echo json_encode(['success' => true, 'data' => $result]);
    exit;
}

if ($action === 'get' && isset($_GET['id'])) {
    $id = (int)$_GET['id'];
    $stmt = $conn->prepare("
        SELECT l.id, l.module_id, l.title, l.description, l.video, m.course_id
        FROM lessons l
        JOIN modules m ON l.module_id = m.id
        WHERE l.id = ?
    ");
    $stmt->bind_param("i", $id);
    $stmt->execute();
    $result = $stmt->get_result()->fetch_assoc();
    if ($result) {
        echo json_encode(['success' => true, 'data' => $result]);
    } else {
        echo json_encode(['success' => false, 'message' => 'Lesson not found.']);
    }
    exit;
}

if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
    echo json_encode(['success' => false, 'message' => 'Invalid method.']);
    exit;
}

if ($action === 'create') {
    $moduleId = (int)($_POST['module_id'] ?? 0);
    $title = trim($_POST['title'] ?? '');
    if (!$moduleId || !$title) {
        echo json_encode(['success' => false, 'message' => 'Module and title are required.']);
        exit;
    }
    $description = trim($_POST['description'] ?? '');
    $video = trim($_POST['video'] ?? '');
    $stmt = $conn->prepare("INSERT INTO lessons (module_id, title, description, video) VALUES (?, ?, ?, ?)");
    $stmt->bind_param("isss", $moduleId, $title, $description, $video);
    $stmt->execute();
    $lessonId = $conn->insert_id;

    $mod = $conn->prepare("SELECT name, course_id FROM modules WHERE id = ?");
    $mod->bind_param("i", $moduleId);
    $mod->execute();
    $modData = $mod->get_result()->fetch_assoc();

    if ($modData) {
        $enrolled = $conn->prepare("SELECT DISTINCT user_id FROM enrollments WHERE module_id = ?");
        $enrolled->bind_param("i", $moduleId);
        $enrolled->execute();
        $users = $enrolled->get_result();

        require_once __DIR__ . '/../includes/notification_helper.php';
        while ($u = $users->fetch_assoc()) {
            create_notification(
                $u['user_id'],
                'New lesson "' . $title . '" added to ' . $modData['name'],
                'lesson.php?module_id=' . $moduleId . '&lesson_id=' . $lessonId,
                'lesson'
            );
        }
    }

    echo json_encode(['success' => true]);
    exit;
}

if ($action === 'update') {
    $id = (int)($_POST['id'] ?? 0);
    $moduleId = (int)($_POST['module_id'] ?? 0);
    $title = trim($_POST['title'] ?? '');
    if (!$id || !$moduleId || !$title) {
        echo json_encode(['success' => false, 'message' => 'Invalid data.']);
        exit;
    }
    $description = trim($_POST['description'] ?? '');
    $video = trim($_POST['video'] ?? '');
    $stmt = $conn->prepare("UPDATE lessons SET module_id = ?, title = ?, description = ?, video = ? WHERE id = ?");
    $stmt->bind_param("isssi", $moduleId, $title, $description, $video, $id);
    $stmt->execute();
    echo json_encode(['success' => true]);
    exit;
}

if ($action === 'delete') {
    $id = (int)($_POST['id'] ?? 0);
    if (!$id) {
        echo json_encode(['success' => false, 'message' => 'Invalid ID.']);
        exit;
    }
    
    $stmt = $conn->prepare("SELECT video FROM lessons WHERE id = ?");
    $stmt->bind_param("i", $id);
    $stmt->execute();
    $lesson = $stmt->get_result()->fetch_assoc();
    
    $stmt = $conn->prepare("DELETE FROM lessons WHERE id = ?");
    $stmt->bind_param("i", $id);
    $stmt->execute();
    
    if ($lesson && $lesson['video']) {
        $filePath = __DIR__ . '/' . $lesson['video'];
        if (file_exists($filePath)) {
            unlink($filePath);
        }
    }
    
    echo json_encode(['success' => true]);
    exit;
}

echo json_encode(['success' => false, 'message' => 'Unknown action.']);