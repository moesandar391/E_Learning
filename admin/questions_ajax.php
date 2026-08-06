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

    case 'get':
        $id = (int)($_GET['id'] ?? 0);
        $stmt = $conn->prepare("SELECT id, quiz_id, question_text, explanation FROM quiz_questions WHERE id = ?");
        $stmt->bind_param('i', $id);
        $stmt->execute();
        $row = $stmt->get_result()->fetch_assoc();
        if (!$row) {
            echo json_encode(['success' => false, 'message' => 'Question not found']);
            exit;
        }
        $opts = $conn->query("SELECT option_text, is_correct FROM quiz_options WHERE question_id = $id ORDER BY position ASC");
        $letters = ['A', 'B', 'C', 'D'];
        $i = 0;
        $correct = 'A';
        $rows = [];
        while ($o = $opts->fetch_assoc()) {
            $rows[$letters[$i]] = $o['option_text'];
            if ((int)$o['is_correct'] === 1) $correct = $letters[$i];
            $i++;
        }
        echo json_encode(['success' => true, 'data' => array_merge($row, [
            'correct_answer' => $correct,
            'option_a' => $rows['A'] ?? '',
            'option_b' => $rows['B'] ?? '',
            'option_c' => $rows['C'] ?? '',
            'option_d' => $rows['D'] ?? '',
        ])]);
        break;

    case 'create':
        $quiz_id        = (int)($_POST['quiz_id'] ?? 0);
        $question_text  = trim($_POST['question_text'] ?? '');
        $explanation    = trim($_POST['explanation'] ?? '');
        $correct_answer = strtoupper(trim($_POST['correct_answer'] ?? ''));
        $options = [
            'A' => trim($_POST['option_a'] ?? ''),
            'B' => trim($_POST['option_b'] ?? ''),
            'C' => trim($_POST['option_c'] ?? ''),
            'D' => trim($_POST['option_d'] ?? ''),
        ];

        if (!$quiz_id || !$question_text || !in_array($correct_answer, ['A', 'B', 'C', 'D'])) {
            echo json_encode(['success' => false, 'message' => 'Question, options and a valid correct answer are required.']);
            exit;
        }
        foreach ($options as $text) {
            if ($text === '') {
                echo json_encode(['success' => false, 'message' => 'Please fill in all four options.']);
                exit;
            }
        }

        $conn->begin_transaction();
        try {
            $stmt = $conn->prepare("INSERT INTO quiz_questions (quiz_id, question_text, explanation) VALUES (?, ?, ?)");
            $stmt->bind_param('iss', $quiz_id, $question_text, $explanation);
            $stmt->execute();
            $newId = $conn->insert_id;

            $ins = $conn->prepare("INSERT INTO quiz_options (question_id, option_text, is_correct, position) VALUES (?, ?, ?, ?)");
            $pos = 0;
            foreach (['A', 'B', 'C', 'D'] as $letter) {
                $isCorrect = ($letter === $correct_answer) ? 1 : 0;
                $ins->bind_param('isii', $newId, $options[$letter], $isCorrect, $pos);
                $ins->execute();
                $pos++;
            }
            $conn->commit();
            echo json_encode(['success' => true, 'message' => 'Question created successfully.']);
        } catch (Exception $e) {
            $conn->rollback();
            echo json_encode(['success' => false, 'message' => 'Failed to create question.']);
        }
        break;

    case 'update':
        $id             = (int)($_POST['id'] ?? 0);
        $question_text  = trim($_POST['question_text'] ?? '');
        $explanation    = trim($_POST['explanation'] ?? '');
        $correct_answer = strtoupper(trim($_POST['correct_answer'] ?? ''));
        $options = [
            'A' => trim($_POST['option_a'] ?? ''),
            'B' => trim($_POST['option_b'] ?? ''),
            'C' => trim($_POST['option_c'] ?? ''),
            'D' => trim($_POST['option_d'] ?? ''),
        ];

        if (!$id || !$question_text || !in_array($correct_answer, ['A', 'B', 'C', 'D'])) {
            echo json_encode(['success' => false, 'message' => 'Missing required fields.']);
            exit;
        }
        foreach ($options as $text) {
            if ($text === '') {
                echo json_encode(['success' => false, 'message' => 'Please fill in all four options.']);
                exit;
            }
        }

        $conn->begin_transaction();
        try {
            $upd = $conn->prepare("UPDATE quiz_questions SET question_text = ?, explanation = ? WHERE id = ?");
            $upd->bind_param('ssi', $question_text, $explanation, $id);
            $upd->execute();

            $opt = $conn->prepare("UPDATE quiz_options SET option_text = ?, is_correct = ? WHERE question_id = ? AND position = ?");
            $pos = 0;
            foreach (['A', 'B', 'C', 'D'] as $letter) {
                $isCorrect = ($letter === $correct_answer) ? 1 : 0;
                $opt->bind_param('siii', $options[$letter], $isCorrect, $id, $pos);
                $opt->execute();
                $pos++;
            }
            $conn->commit();
            echo json_encode(['success' => true, 'message' => 'Question updated successfully.']);
        } catch (Exception $e) {
            $conn->rollback();
            echo json_encode(['success' => false, 'message' => 'Failed to update question.']);
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
            $conn->query("DELETE FROM quiz_options WHERE question_id = $id");
            $conn->query("DELETE FROM quiz_attempt_answers WHERE question_id = $id");
            $conn->query("DELETE FROM quiz_attempt_questions WHERE question_id = $id");
            $conn->query("DELETE FROM quiz_questions WHERE id = $id");
            $conn->commit();
            echo json_encode(['success' => true, 'message' => 'Question deleted successfully.']);
        } catch (Exception $e) {
            $conn->rollback();
            echo json_encode(['success' => false, 'message' => 'Failed to delete question.']);
        }
        break;

    default:
        echo json_encode(['success' => false, 'message' => 'Invalid action.']);
}