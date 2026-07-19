<?php
session_start();
require_once '../config/db.php';

header('Content-Type: application/json');

if (!isset($_SESSION['role']) || $_SESSION['role'] !== 'admin') {
    echo json_encode(['success' => false, 'message' => 'Unauthorized.']);
    exit;
}

$action = $_POST['action'] ?? '';

if ($action === 'delete') {
    $enroll_id = intval($_POST['id'] ?? 0);
    if (!$enroll_id) {
        echo json_encode(['success' => false, 'message' => 'Invalid enrollment ID.']);
        exit;
    }

    $conn->query("DELETE FROM certificates WHERE enroll_id = $enroll_id");
    $conn->query("DELETE FROM enrollments WHERE id = $enroll_id");

    if ($conn->affected_rows > 0) {
        echo json_encode(['success' => true, 'message' => 'Enrollment deleted.']);
    } else {
        echo json_encode(['success' => false, 'message' => 'Enrollment not found.']);
    }
    exit;
}

echo json_encode(['success' => false, 'message' => 'Unknown action.']);
// Inside enrollments_ajax.php
if ($_POST['action'] == 'reject') {
    $id = $_POST['id'];
    $reason = $conn->real_escape_string($_POST['reason']);
    
    // Update the status to 'Rejected' and save the reason
    $sql = "UPDATE enrollments SET status = 'Rejected', rejection_reason = '$reason' WHERE id = '$id'";
    
    if ($conn->query($sql)) {
        echo json_encode(['success' => true]);
    } else {
        echo json_encode(['success' => false, 'message' => 'Database error']);
    }
}