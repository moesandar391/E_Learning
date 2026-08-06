<?php
session_start();
require_once '../config/db.php';

header('Content-Type: application/json');

if (!isset($_SESSION['user_id'])) {
    echo json_encode(['success' => false, 'message' => 'Unauthorized']);
    exit;
}

$action = $_POST['action'] ?? $_GET['action'] ?? '';

switch ($action) {

    case 'get_modules':
        $course_id = (int)($_GET['course_id'] ?? 0);
        $stmt = $conn->prepare("SELECT id, name FROM modules WHERE course_id = ? AND status = 'active' ORDER BY name ASC");
        $stmt->bind_param('i', $course_id);
        $stmt->execute();
        $result = $stmt->get_result();
        $modules = [];
        while ($row = $result->fetch_assoc()) {
            $modules[] = $row;
        }
        echo json_encode(['success' => true, 'data' => $modules]);
        break;

    case 'get':
        $id = (int)($_GET['id'] ?? 0);
        $stmt = $conn->prepare("
            SELECT q.id, q.module_id, q.quiz_title, q.passing_score, q.question_limit, q.time_limit,
                   q.random_questions, q.random_answers, q.status, m.course_id
            FROM quizzes q
            JOIN modules m ON q.module_id = m.id
            WHERE q.id = ?
        ");
        $stmt->bind_param('i', $id);
        $stmt->execute();
        if ($row = $stmt->get_result()->fetch_assoc()) {
            echo json_encode(['success' => true, 'data' => $row]);
        } else {
            echo json_encode(['success' => false, 'message' => 'Quiz not found']);
        }
        break;

    case 'create':
        $module_id       = (int)($_POST['module_id'] ?? 0);
        $quiz_title      = trim($_POST['quiz_title'] ?? '');
        $passing_score   = (int)($_POST['passing_score'] ?? 70);
        $question_limit  = (int)($_POST['question_limit'] ?? 100);
        $time_limit      = (int)($_POST['time_limit'] ?? 0);
        $random_q        = isset($_POST['random_questions']) ? 1 : 0;
        $random_a        = isset($_POST['random_answers']) ? 1 : 0;

        if (!$module_id || !$quiz_title) {
            echo json_encode(['success' => false, 'message' => 'Module and quiz title are required.']);
            exit;
        }
        $passing_score = max(1, min(100, $passing_score));
        $question_limit = max(1, $question_limit);
        $time_limit = max(0, $time_limit);

        $stmt = $conn->prepare("INSERT INTO quizzes (module_id, quiz_title, passing_score, question_limit, time_limit, random_questions, random_answers) VALUES (?, ?, ?, ?, ?, ?, ?)");
        $stmt->bind_param('issiiii', $module_id, $quiz_title, $passing_score, $question_limit, $time_limit, $random_q, $random_a);
        if ($stmt->execute()) {
            echo json_encode(['success' => true, 'message' => 'Quiz created successfully.']);
        } else {
            echo json_encode(['success' => false, 'message' => 'Failed to create quiz.']);
        }
        break;

    case 'update':
        $id              = (int)($_POST['id'] ?? 0);
        $module_id       = (int)($_POST['module_id'] ?? 0);
        $quiz_title      = trim($_POST['quiz_title'] ?? '');
        $passing_score   = (int)($_POST['passing_score'] ?? 70);
        $question_limit  = (int)($_POST['question_limit'] ?? 100);
        $time_limit      = (int)($_POST['time_limit'] ?? 0);
        $random_q        = isset($_POST['random_questions']) ? 1 : 0;
        $random_a        = isset($_POST['random_answers']) ? 1 : 0;

        if (!$id || !$module_id || !$quiz_title) {
            echo json_encode(['success' => false, 'message' => 'Missing required fields.']);
            exit;
        }
        $passing_score = max(1, min(100, $passing_score));
        $question_limit = max(1, $question_limit);
        $time_limit = max(0, $time_limit);

        $stmt = $conn->prepare("UPDATE quizzes SET module_id = ?, quiz_title = ?, passing_score = ?, question_limit = ?, time_limit = ?, random_questions = ?, random_answers = ? WHERE id = ?");
        $stmt->bind_param('issiiiii', $module_id, $quiz_title, $passing_score, $question_limit, $time_limit, $random_q, $random_a, $id);
        if ($stmt->execute()) {
            echo json_encode(['success' => true, 'message' => 'Quiz updated successfully.']);
        } else {
            echo json_encode(['success' => false, 'message' => 'Failed to update quiz.']);
        }
        break;

    case 'toggle_status':
        $id = (int)($_POST['id'] ?? 0);
        $status = ($_POST['status'] ?? '') === 'inactive' ? 'inactive' : 'active';
        if (!$id) {
            echo json_encode(['success' => false, 'message' => 'Invalid ID.']);
            exit;
        }
        $stmt = $conn->prepare("UPDATE quizzes SET status = ? WHERE id = ?");
        $stmt->bind_param('si', $status, $id);
        if ($stmt->execute()) {
            echo json_encode(['success' => true, 'message' => 'Quiz ' . ($status === 'active' ? 'enabled' : 'disabled') . ' successfully.']);
        } else {
            echo json_encode(['success' => false, 'message' => 'Failed to update quiz status.']);
        }
        break;

    case 'delete':
        $id = (int)($_POST['id'] ?? 0);
        if (!$id) {
            echo json_encode(['success' => false, 'message' => 'Invalid ID.']);
            exit;
        }
        $conn->begin_transaction();
        try {
            $delAns = $conn->prepare("DELETE FROM quiz_attempt_answers WHERE attempt_id IN (SELECT id FROM quiz_attempts WHERE quiz_id = ?)");
            $delAns->bind_param('i', $id);
            $delAns->execute();

            $delAq = $conn->prepare("DELETE FROM quiz_attempt_questions WHERE attempt_id IN (SELECT id FROM quiz_attempts WHERE quiz_id = ?)");
            $delAq->bind_param('i', $id);
            $delAq->execute();

            $delAtt = $conn->prepare("DELETE FROM quiz_attempts WHERE quiz_id = ?");
            $delAtt->bind_param('i', $id);
            $delAtt->execute();
            $conn->query("DELETE FROM quiz_results WHERE quiz_id = $id");
            $conn->query("DELETE FROM quiz_options WHERE question_id IN (SELECT id FROM quiz_questions WHERE quiz_id = $id)");
            $conn->query("DELETE FROM quiz_questions WHERE quiz_id = $id");
            $conn->query("DELETE FROM quizzes WHERE id = $id");
            $conn->commit();
            echo json_encode(['success' => true, 'message' => 'Quiz and all related data deleted successfully.']);
        } catch (Exception $e) {
            $conn->rollback();
            echo json_encode(['success' => false, 'message' => 'Failed to delete quiz.']);
        }
        break;

    default:
        echo json_encode(['success' => false, 'message' => 'Invalid action.']);
}