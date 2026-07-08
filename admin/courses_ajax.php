<?php
session_start();
require_once '../config/db.php';

header('Content-Type: application/json');

if (!isset($_SESSION['role']) || $_SESSION['role'] !== 'admin') {
    echo json_encode(['success' => false, 'message' => 'Unauthorized.']);
    exit;
}

$action = $_REQUEST['action'] ?? '';

if ($action === 'get' && isset($_GET['id'])) {
    $id = (int)$_GET['id'];
    $stmt = $conn->prepare("SELECT id, course_name, instructor_name, level, description FROM courses WHERE id = ?");
    $stmt->bind_param("i", $id);
    $stmt->execute();
    $result = $stmt->get_result()->fetch_assoc();
    if ($result) {
        echo json_encode(['success' => true, 'data' => $result]);
    } else {
        echo json_encode(['success' => false, 'message' => 'Course not found.']);
    }
    exit;
}

if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
    echo json_encode(['success' => false, 'message' => 'Invalid method.']);
    exit;
}

if ($action === 'create') {
    $name = trim($_POST['course_name'] ?? '');
    if (!$name) {
        echo json_encode(['success' => false, 'message' => 'Course name is required.']);
        exit;
    }
    $instructor = trim($_POST['instructor_name'] ?? '');
    $level = trim($_POST['level'] ?? '');
    $description = trim($_POST['description'] ?? '');
    $stmt = $conn->prepare("INSERT INTO courses (course_name, instructor_name, level, description) VALUES (?, ?, ?, ?)");
    $stmt->bind_param("ssss", $name, $instructor, $level, $description);
    $stmt->execute();
    echo json_encode(['success' => true]);
    exit;
}

if ($action === 'update') {
    $id = (int)$_POST['id'];
    $name = trim($_POST['course_name'] ?? '');
    if (!$name || !$id) {
        echo json_encode(['success' => false, 'message' => 'Invalid data.']);
        exit;
    }
    $instructor = trim($_POST['instructor_name'] ?? '');
    $level = trim($_POST['level'] ?? '');
    $description = trim($_POST['description'] ?? '');
    $stmt = $conn->prepare("UPDATE courses SET course_name = ?, instructor_name = ?, level = ?, description = ? WHERE id = ?");
    $stmt->bind_param("ssssi", $name, $instructor, $level, $description, $id);
    $stmt->execute();
    echo json_encode(['success' => true]);
    exit;
}

if ($action === 'delete') {
    $id = (int)$_POST['id'];
    if (!$id) {
        echo json_encode(['success' => false, 'message' => 'Invalid ID.']);
        exit;
    }
    $stmt = $conn->prepare("DELETE FROM courses WHERE id = ?");
    $stmt->bind_param("i", $id);
    $stmt->execute();
    echo json_encode(['success' => true]);
    exit;
}

echo json_encode(['success' => false, 'message' => 'Unknown action.']);