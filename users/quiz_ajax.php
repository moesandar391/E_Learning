<?php
session_start();
require_once '../config/db.php';
require_once '../includes/enrollment_check.php';

header('Content-Type: application/json');

$user_id = $_SESSION['user_id'] ?? null;
if (!$user_id) {
    echo json_encode(['success' => false, 'message' => 'Please log in to continue.']);
    exit;
}

$action = $_POST['action'] ?? '';

function attemptOwnedByUser($conn, $attempt_id, $user_id) {
    $stmt = $conn->prepare("SELECT a.id, a.quiz_id, a.status, a.start_time, a.qlimit, q.module_id, q.time_limit, q.passing_score
                            FROM quiz_attempts a
                            JOIN quizzes q ON a.quiz_id = q.id
                            WHERE a.id = ? AND a.user_id = ?");
    $stmt->bind_param('ii', $attempt_id, $user_id);
    $stmt->execute();
    return $stmt->get_result()->fetch_assoc();
}

function accessAllowed($conn, $user_id, $module_id) {
    $status = checkEnrollmentStatus($conn, $user_id, $module_id);
    if (strtolower($status) !== 'confirmed') return false;

    $lessonCount = $conn->query("SELECT COUNT(*) AS total FROM lessons WHERE module_id = $module_id")->fetch_assoc()['total'];
    $completedCount = $conn->query("SELECT COUNT(*) AS done FROM lesson_progress WHERE user_id = $user_id AND lesson_id IN (SELECT id FROM lessons WHERE module_id = $module_id) AND completed = 1")->fetch_assoc()['done'];
    return $completedCount >= $lessonCount;
}

switch ($action) {

    case 'save':
        $attempt_id  = (int)($_POST['attempt_id'] ?? 0);
        $question_id = (int)($_POST['question_id'] ?? 0);
        $option_id   = (int)($_POST['option_id'] ?? 0);

        $attempt = attemptOwnedByUser($conn, $attempt_id, $user_id);
        if (!$attempt || $attempt['status'] !== 'in_progress') {
            echo json_encode(['success' => false, 'message' => 'This attempt is no longer available.']);
            exit;
        }

        if (!accessAllowed($conn, $user_id, (int)$attempt['module_id'])) {
            echo json_encode(['success' => false, 'message' => 'Access not allowed.']);
            exit;
        }

        if ($attempt['time_limit'] > 0) {
            $deadline = strtotime($attempt['start_time']) + ($attempt['time_limit'] * 60);
            if (time() > $deadline) {
                echo json_encode(['success' => false, 'message' => 'time_expired', 'expired' => true]);
                exit;
            }
        }

        $belongs = $conn->query("SELECT id FROM quiz_attempt_questions WHERE attempt_id = $attempt_id AND question_id = $question_id");
        if ($belongs->num_rows === 0) {
            echo json_encode(['success' => false, 'message' => 'Invalid question.']);
            exit;
        }

        $optBelongs = $conn->query("SELECT id FROM quiz_options WHERE id = $option_id AND question_id = $question_id");
        if ($optBelongs->num_rows === 0) {
            echo json_encode(['success' => false, 'message' => 'Invalid option.']);
            exit;
        }

        $stmt = $conn->prepare("INSERT INTO quiz_attempt_answers (attempt_id, question_id, option_id) VALUES (?, ?, ?)
                                ON DUPLICATE KEY UPDATE option_id = VALUES(option_id)");
        $stmt->bind_param('iii', $attempt_id, $question_id, $option_id);
        if ($stmt->execute()) {
            $answered = $conn->query("SELECT COUNT(*) c FROM quiz_attempt_answers WHERE attempt_id = $attempt_id")->fetch_assoc()['c'];
            echo json_encode(['success' => true, 'answered' => (int)$answered]);
        } else {
            echo json_encode(['success' => false, 'message' => 'Failed to save your answer.']);
        }
        break;

    case 'submit':
        $attempt_id = (int)($_POST['attempt_id'] ?? 0);
        $attempt = attemptOwnedByUser($conn, $attempt_id, $user_id);
        if (!$attempt) {
            echo json_encode(['success' => false, 'message' => 'Attempt not found.']);
            exit;
        }
        if ($attempt['status'] === 'completed') {
            echo json_encode(['success' => false, 'message' => 'already_submitted']);
            exit;
        }

        if (!accessAllowed($conn, $user_id, (int)$attempt['module_id'])) {
            echo json_encode(['success' => false, 'message' => 'Access not allowed.']);
            exit;
        }

        if ($attempt['time_limit'] > 0) {
            $deadline = strtotime($attempt['start_time']) + ($attempt['time_limit'] * 60);
            if (time() > $deadline) {
                $attempt['time_expired'] = true;
            }
        }

        $qid = (int)$attempt['quiz_id'];
        $module_id = (int)$attempt['module_id'];
        $passing = (int)$attempt['passing_score'];

        $conn->begin_transaction();
        try {
            $lock = $conn->prepare("UPDATE quiz_attempts SET status = 'completed' WHERE id = ? AND status = 'in_progress'");
            $lock->bind_param('i', $attempt_id);
            $lock->execute();
            if ($lock->affected_rows === 0) {
                $conn->rollback();
                echo json_encode(['success' => false, 'message' => 'already_submitted']);
                exit;
            }

            $conn->query("UPDATE quiz_attempts SET end_time = NOW() WHERE id = $attempt_id");

            $total = 0;
            $correct = 0;
            $aq = $conn->query("SELECT question_id FROM quiz_attempt_questions WHERE attempt_id = $attempt_id");
            while ($row = $aq->fetch_assoc()) {
                $question_id = (int)$row['question_id'];
                $total++;
                $sel = $conn->query("SELECT option_id FROM quiz_attempt_answers WHERE attempt_id = $attempt_id AND question_id = $question_id")->fetch_assoc();
                $correctOpt = $conn->query("SELECT id FROM quiz_options WHERE question_id = $question_id AND is_correct = 1")->fetch_assoc();
                if ($sel && $correctOpt && (int)$sel['option_id'] === (int)$correctOpt['id']) {
                    $correct++;
                }
            }

            $wrong = $total - $correct;
            $score = $total > 0 ? round(($correct / $total) * 100) : 0;
            $passed = $score >= $passing ? 1 : 0;

            $attemptNo = $conn->query("SELECT COUNT(*) c FROM quiz_results WHERE quiz_id = $qid AND user_id = $user_id")->fetch_assoc()['c'] + 1;

            $ins = $conn->prepare("INSERT INTO quiz_results (quiz_id, user_id, attempt_id, attempt_number, total_questions, correct_count, wrong_count, score, passed, attempt_date)
                                   VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?, NOW())");
            $ins->bind_param('iiiiiiiis', $qid, $user_id, $attempt_id, $attemptNo, $total, $correct, $wrong, $score, $passed);
            $ins->execute();

            $conn->commit();
            echo json_encode(['success' => true, 'passed' => (bool)$passed, 'score' => $score, 'module_id' => $module_id, 'attempt_id' => $attempt_id]);
        } catch (Exception $e) {
            $conn->rollback();
            echo json_encode(['success' => false, 'message' => 'Failed to submit your quiz.']);
        }
        break;

    default:
        echo json_encode(['success' => false, 'message' => 'Invalid action.']);
}