<?php
session_start();
require_once '../config/db.php';
require_once '../includes/enrollment_check.php';

$module_id = isset($_GET['module_id']) ? (int)$_GET['module_id'] : 0;
$attempt_id = isset($_GET['attempt']) ? (int)$_GET['attempt'] : 0;
$user_id = $_SESSION['user_id'] ?? null;

if (!$user_id) {
    header("Location: ../auth/login.php");
    exit;
}
if (!$module_id) {
    header("Location: my_learning.php");
    exit;
}

$modStmt = $conn->prepare("
    SELECT m.id, m.name AS module_name, c.course_name
    FROM modules m
    JOIN courses c ON m.course_id = c.id
    WHERE m.id = ?
");
$modStmt->bind_param("i", $module_id);
$modStmt->execute();
$module = $modStmt->get_result()->fetch_assoc();
if (!$module) {
    header("Location: my_learning.php");
    exit;
}

$enrollStatus = checkEnrollmentStatus($conn, $user_id, $module_id);
if (strtolower($enrollStatus) !== 'confirmed') {
    header("Location: lesson.php?module_id=$module_id");
    exit;
}

$lessonCount = $conn->query("SELECT COUNT(*) AS total FROM lessons WHERE module_id = $module_id")->fetch_assoc()['total'];
$completedCount = $conn->query("SELECT COUNT(*) AS done FROM lesson_progress WHERE user_id = $user_id AND lesson_id IN (SELECT id FROM lessons WHERE module_id = $module_id) AND completed = 1")->fetch_assoc()['done'];
if ($completedCount < $lessonCount) {
    header("Location: lesson.php?module_id=$module_id");
    exit;
}

// Latest active quiz for this module
$quizStmt = $conn->prepare("SELECT id, quiz_title, passing_score, question_limit, time_limit, random_questions, random_answers, status FROM quizzes WHERE module_id = ? AND status = 'active' ORDER BY id DESC LIMIT 1");
$quizStmt->bind_param("i", $module_id);
$quizStmt->execute();
$quiz = $quizStmt->get_result()->fetch_assoc();

$PASS_PERCENT = $quiz ? (int)$quiz['passing_score'] : 60;

function getLatestResult($conn, $user_id, $quiz_id) {
    $stmt = $conn->prepare("SELECT * FROM quiz_results WHERE user_id = ? AND quiz_id = ? ORDER BY id DESC LIMIT 1");
    $stmt->bind_param("ii", $user_id, $quiz_id);
    $stmt->execute();
    return $stmt->get_result()->fetch_assoc();
}

function createAttempt($conn, $quiz, $user_id) {
    $bank = [];
    $all = $conn->query("SELECT id FROM quiz_questions WHERE quiz_id = {$quiz['id']} ORDER BY id ASC");
    while ($row = $all->fetch_assoc()) { $bank[] = (int)$row['id']; }

    $limit = (int)$quiz['question_limit'];
    if ($limit < 1) $limit = count($bank);
    $limit = min($limit, count($bank));
    if ($limit < 1) return false;

    if ((int)$quiz['random_questions'] === 1) {
        shuffle($bank);
    }
    $picked = array_slice($bank, 0, $limit);

    $conn->begin_transaction();
    try {
        $stmt = $conn->prepare("INSERT INTO quiz_attempts (quiz_id, user_id, qlimit) VALUES (?, ?, ?)");
        $stmt->bind_param('iii', $quiz['id'], $user_id, $limit);
        $stmt->execute();
        $attemptId = $conn->insert_id;

        $ins = $conn->prepare("INSERT INTO quiz_attempt_questions (attempt_id, question_id, display_order, options_order) VALUES (?, ?, ?, ?)");
        $order = 0;
        foreach ($picked as $qid) {
            $optIds = [];
            $o = $conn->query("SELECT id FROM quiz_options WHERE question_id = $qid ORDER BY position ASC");
            while ($row = $o->fetch_assoc()) { $optIds[] = (int)$row['id']; }
            if ((int)$quiz['random_answers'] === 1) {
                shuffle($optIds);
            }
            $optionsOrder = json_encode($optIds);
            $ins->bind_param('iiis', $attemptId, $qid, $order, $optionsOrder);
            $ins->execute();
            $order++;
        }
        $conn->commit();
        return $attemptId;
    } catch (Exception $e) {
        $conn->rollback();
        return false;
    }
}

function gradeAttempt($conn, $attempt_id, $user_id, $quiz) {
    $conn->begin_transaction();
    try {
        $lock = $conn->prepare("UPDATE quiz_attempts SET status = 'completed', end_time = NOW() WHERE id = ? AND status = 'in_progress'");
        $lock->bind_param('i', $attempt_id);
        $lock->execute();
        if ($lock->affected_rows === 0) {
            $conn->rollback();
            return false;
        }

        $total = 0; $correct = 0;
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
        $passed = $score >= (int)$quiz['passing_score'] ? 1 : 0;

        $attemptNo = $conn->query("SELECT COUNT(*) c FROM quiz_results WHERE quiz_id = {$quiz['id']} AND user_id = $user_id")->fetch_assoc()['c'] + 1;

        $ins = $conn->prepare("INSERT INTO quiz_results (quiz_id, user_id, attempt_id, attempt_number, total_questions, correct_count, wrong_count, score, passed, attempt_date)
                               VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?, NOW())");
        $ins->bind_param('iiiiiiiis', $quiz['id'], $user_id, $attempt_id, $attemptNo, $total, $correct, $wrong, $score, $passed);
        $ins->execute();
        $conn->commit();
        return $attempt_id;
    } catch (Exception $e) {
        $conn->rollback();
        return false;
    }
}

// Start a new attempt
if ($_SERVER['REQUEST_METHOD'] === 'POST' && ($_POST['start'] ?? '') === '1' && $quiz) {
    $newAttempt = createAttempt($conn, $quiz, $user_id);
    if ($newAttempt) {
        header("Location: quiz.php?module_id=$module_id&attempt=$newAttempt");
        exit;
    }
    $startError = "Could not start the quiz. Please try again.";
}

// Load attempt (if any)
$attempt = null;
if ($attempt_id && $quiz) {
    $stmt = $conn->prepare("SELECT a.*, q.quiz_title, q.passing_score, q.time_limit, q.random_answers FROM quiz_attempts a JOIN quizzes q ON a.quiz_id = q.id WHERE a.id = ? AND a.user_id = ? AND a.quiz_id = ?");
    $stmt->bind_param('iii', $attempt_id, $user_id, $quiz['id']);
    $stmt->execute();
    $attempt = $stmt->get_result()->fetch_assoc();
    if (!$attempt) {
        header("Location: quiz.php?module_id=$module_id");
        exit;
    }
}

// If attempt is in progress but time expired -> auto finalize
if ($attempt && $attempt['status'] === 'in_progress' && (int)$attempt['time_limit'] > 0) {
    $deadline = strtotime($attempt['start_time']) + ((int)$attempt['time_limit'] * 60);
    if (time() > $deadline) {
        gradeAttempt($conn, $attempt_id, $user_id, $quiz);
        $stmt = $conn->prepare("SELECT status FROM quiz_attempts WHERE id = ?");
        $stmt->bind_param('i', $attempt_id);
        $stmt->execute();
        $attempt['status'] = $stmt->get_result()->fetch_assoc()['status'];
    }
}

$lastResult = $quiz ? getLatestResult($conn, $user_id, $quiz['id']) : null;
$alreadyPassed = $lastResult && (int)$lastResult['passed'] === 1;

include_once('../includes/header.php');
?>

<div class="max-w-4xl mx-auto px-4 sm:px-6 py-10">
    <nav class="text-sm text-gray-500 mb-6">
        <a href="my_learning.php" class="hover:text-brandOrange">My Dashboard</a> &gt;
        <a href="lesson.php?module_id=<?= $module_id ?>" class="hover:text-brandOrange"><?= htmlspecialchars($module['module_name']) ?></a> &gt;
        <span class="text-brandOrange font-semibold">Quiz</span>
    </nav>

    <?php if (!$quiz): ?>
        <div class="bg-white rounded-2xl border border-gray-200 p-10 text-center shadow-sm">
            <span class="text-5xl mb-4 block">📝</span>
            <h2 class="text-xl font-bold text-gray-800 mb-2">Quiz Not Available</h2>
            <p class="text-gray-500 text-sm mb-6">The quiz for this module has not been set up yet or is currently disabled.</p>
            <a href="lesson.php?module_id=<?= $module_id ?>" class="inline-block px-5 py-2.5 bg-brandOrange text-white text-sm font-semibold rounded-lg hover:bg-brandOrangeHover transition">Back to Lessons</a>
        </div>

    <?php elseif ($attempt && $attempt['status'] === 'completed'): ?>
        <?php
        $res = $conn->prepare("SELECT * FROM quiz_results WHERE attempt_id = ? AND user_id = ? LIMIT 1");
        $res->bind_param('ii', $attempt_id, $user_id);
        $res->execute();
        $resultRow = $res->get_result()->fetch_assoc();
        $resPassed = $resultRow && (int)$resultRow['passed'] === 1;
        ?>
        <div class="bg-white rounded-2xl border <?= $resPassed ? 'border-green-200' : 'border-red-200' ?> shadow-sm p-10 text-center mb-8">
            <span class="text-5xl mb-4 block"><?= $resPassed ? '🎉' : '💪' ?></span>
            <h2 class="text-2xl font-bold <?= $resPassed ? 'text-green-600' : 'text-red-600' ?> mb-2">
                <?= $resPassed ? 'Congratulations, You Passed!' : 'Not Quite, Try Again!' ?>
            </h2>
            <p class="text-gray-500 text-sm">
                You scored <span class="text-3xl font-bold text-brandOrange mx-1"><?= (int)$resultRow['score'] ?>%</span>
            </p>
            <p class="text-gray-400 text-xs mt-2">Passing score: <?= $PASS_PERCENT ?>%</p>
            <div class="grid grid-cols-3 gap-3 max-w-md mx-auto mt-6">
                <div class="bg-gray-50 rounded-xl p-3">
                    <p class="text-lg font-bold text-gray-800"><?= (int)$resultRow['total_questions'] ?></p>
                    <p class="text-[10px] uppercase font-bold text-gray-400">Total</p>
                </div>
                <div class="bg-green-50 rounded-xl p-3">
                    <p class="text-lg font-bold text-green-600"><?= (int)$resultRow['correct_count'] ?></p>
                    <p class="text-[10px] uppercase font-bold text-gray-400">Correct</p>
                </div>
                <div class="bg-red-50 rounded-xl p-3">
                    <p class="text-lg font-bold text-red-600"><?= (int)$resultRow['wrong_count'] ?></p>
                    <p class="text-[10px] uppercase font-bold text-gray-400">Wrong</p>
                </div>
            </div>
            <div class="flex items-center justify-center gap-3 mt-8 flex-wrap">
                <?php if ($resPassed): ?>
                    <button onclick="downloadCertificate(event, <?= $module_id ?>)"
                            class="inline-flex items-center gap-2 px-6 py-3 rounded-xl text-sm font-bold bg-green-600 text-white hover:bg-green-700 transition shadow-sm">
                        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 10v6m0 0l-3-3m3 3l3-3m2 8H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"/></svg>
                        Download Certificate
                    </button>
                <?php endif; ?>
                <form method="POST" action="quiz.php?module_id=<?= $module_id ?>">
                    <input type="hidden" name="start" value="1">
                    <button type="submit" class="px-6 py-3 rounded-xl text-sm font-bold bg-brandOrange text-white hover:bg-brandOrangeHover transition shadow-sm">Retake Quiz</button>
                </form>
                <a href="lesson.php?module_id=<?= $module_id ?>" class="px-6 py-3 rounded-xl text-sm font-bold border border-gray-300 text-gray-600 hover:bg-gray-50 transition">Back to Lessons</a>
            </div>
        </div>

        <?php if ($resultRow): ?>
        <div class="bg-white rounded-2xl border border-gray-200 shadow-sm overflow-hidden mb-8">
            <div class="px-6 py-4 border-b border-gray-100 flex items-center justify-between">
                <h3 class="font-bold text-gray-800">Answer Review</h3>
                <span class="text-xs text-gray-400">Attempt #<?= (int)$resultRow['attempt_number'] ?> &middot; <?= date('M j, Y H:i', strtotime($resultRow['attempt_date'])) ?></span>
            </div>
            <div class="divide-y divide-gray-100">
                <?php
                $review = $conn->query("SELECT q.id AS question_id, q.question_text, q.explanation, oa.option_id AS given_id
                                        FROM quiz_attempt_questions aq
                                        JOIN quiz_questions q ON aq.question_id = q.id
                                        LEFT JOIN quiz_attempt_answers oa ON oa.attempt_id = $attempt_id AND oa.question_id = q.id
                                        WHERE aq.attempt_id = $attempt_id ORDER BY aq.display_order ASC");
                $letters = ['A', 'B', 'C', 'D'];
                $i = 0;
                while ($rq = $review->fetch_assoc()):
                    $opts = $conn->query("SELECT id, option_text, is_correct, position FROM quiz_options WHERE question_id = {$rq['question_id']} ORDER BY position ASC")->fetch_all(MYSQLI_ASSOC);
                    $givenCorrect = false;
                    foreach ($opts as $op) {
                        if ((int)$op['is_correct'] === 1 && $rq['given_id'] !== null && (int)$op['id'] === (int)$rq['given_id']) { $givenCorrect = true; }
                    }
                ?>
                <div class="px-6 py-5">
                    <p class="text-sm font-semibold text-gray-800 mb-3">
                        <?= $i + 1 ?>. <?= htmlspecialchars($rq['question_text']) ?>
                        <span class="ml-2 text-xs font-bold <?= $givenCorrect ? 'text-green-600' : 'text-red-600' ?>"><?= $givenCorrect ? 'Correct' : ($rq['given_id'] !== null ? 'Incorrect' : 'Not answered') ?></span>
                    </p>
                    <div class="grid grid-cols-1 sm:grid-cols-2 gap-2">
                        <?php foreach ($opts as $op):
                            $isCorrectOpt = (int)$op['is_correct'] === 1;
                            $isGivenOpt = $rq['given_id'] !== null && (int)$op['id'] === (int)$rq['given_id'];
                        ?>
                        <div class="flex items-center gap-2 px-3 py-2 rounded-lg text-sm <?= $isCorrectOpt ? 'bg-green-50 text-green-700 border border-green-200' : ($isGivenOpt ? 'bg-red-50 text-red-600 border border-red-200' : 'bg-gray-50 text-gray-600 border border-gray-100') ?>">
                            <span class="font-bold"><?= $letters[$op['position']] ?>)</span>
                            <span class="flex-1"><?= htmlspecialchars($op['option_text']) ?></span>
                            <?php if ($isCorrectOpt): ?>
                                <svg class="w-4 h-4 flex-shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M5 13l4 4L19 7"/></svg>
                            <?php elseif ($isGivenOpt): ?>
                                <svg class="w-4 h-4 flex-shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M6 18L18 6M6 6l12 12"/></svg>
                            <?php endif; ?>
                        </div>
                        <?php endforeach; ?>
                    </div>
                    <?php if (!empty($rq['explanation'])): ?>
                        <p class="mt-2 text-xs text-gray-500"><span class="font-semibold text-brandOchre">Explanation:</span> <?= htmlspecialchars($rq['explanation']) ?></p>
                    <?php endif; ?>
                </div>
                <?php $i++; endwhile; ?>
            </div>
        </div>
        <?php endif; ?>

    <?php elseif ($attempt && $attempt['status'] === 'in_progress'): ?>
        <?php
        $aq = $conn->query("SELECT aq.question_id, aq.options_order FROM quiz_attempt_questions aq WHERE aq.attempt_id = $attempt_id ORDER BY aq.display_order ASC");
        $questions = [];
        while ($row = $aq->fetch_assoc()) {
            $qrow = $conn->query("SELECT id, question_text FROM quiz_questions WHERE id = {$row['question_id']}")->fetch_assoc();
            $optOrder = json_decode($row['options_order'] ?: '[]', true);
            $options = [];
            foreach ($optOrder as $oid) {
                $o = $conn->query("SELECT id, option_text FROM quiz_options WHERE id = $oid")->fetch_assoc();
                if ($o) $options[] = ['id' => (int)$o['id'], 'text' => $o['option_text']];
            }
            $questions[] = ['id' => (int)$qrow['id'], 'text' => $qrow['question_text'], 'options' => $options];
        }
        $saved = [];
        $sa = $conn->query("SELECT question_id, option_id FROM quiz_attempt_answers WHERE attempt_id = $attempt_id");
        while ($row = $sa->fetch_assoc()) { $saved[(int)$row['question_id']] = (int)$row['option_id']; }

        $timeLimitSec = (int)$attempt['time_limit'] > 0 ? (int)$attempt['time_limit'] * 60 : 0;
        $deadlineTs = $timeLimitSec > 0 ? strtotime($attempt['start_time']) + $timeLimitSec : 0;
        ?>
        <div id="quizApp" data-json='<?= htmlspecialchars(json_encode([
            'attemptId' => (int)$attempt_id,
            'moduleId' => $module_id,
            'quizTitle' => $quiz['quiz_title'],
            'passing' => $PASS_PERCENT,
            'questions' => $questions,
            'saved' => $saved,
            'timeLimitSec' => $timeLimitSec,
            'deadlineTs' => $deadlineTs,
            'q' => max(0, (int)($_GET['q'] ?? 0)),
        ]), ENT_QUOTES) ?>'></div>

    <?php else: ?>
        <?php
        $bankCount = $conn->query("SELECT COUNT(*) c FROM quiz_questions WHERE quiz_id = {$quiz['id']}")->fetch_assoc()['c'];
        ?>
        <div class="bg-white rounded-2xl border border-gray-200 shadow-sm p-10">
            <div class="text-center mb-8">
                <span class="text-5xl mb-4 block">📝</span>
                <h1 class="text-2xl font-bold text-brandOchre mb-2"><?= htmlspecialchars($quiz['quiz_title']) ?></h1>
                <p class="text-sm text-gray-500"><?= htmlspecialchars($module['module_name']) ?> &middot; <?= htmlspecialchars($module['course_name']) ?></p>
            </div>

            <?php if ($bankCount === 0): ?>
                <div class="text-center py-6">
                    <p class="text-gray-500 text-sm mb-4">This quiz is not ready yet. Please check back later.</p>
                    <a href="lesson.php?module_id=<?= $module_id ?>" class="inline-block px-5 py-2.5 bg-brandOrange text-white text-sm font-semibold rounded-lg hover:bg-brandOrangeHover transition">Back to Lessons</a>
                </div>
            <?php else: ?>
                <?php if ($alreadyPassed && $lastResult): ?>
                    <div class="mb-6 flex items-start gap-3 bg-green-50 border border-green-200 text-green-700 rounded-xl px-5 py-4 text-sm">
                        <svg class="w-5 h-5 flex-shrink-0 mt-0.5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"/></svg>
                        <span>You already passed with <span class="font-bold"><?= (int)$lastResult['score'] ?>%</span>. Retaking lets you try for a higher score.</span>
                    </div>
                <?php endif; ?>
                <?php if (isset($startError)): ?>
                    <div class="mb-6 flex items-start gap-3 bg-red-50 border border-red-200 text-red-700 rounded-xl px-5 py-4 text-sm">
                        <span><?= htmlspecialchars($startError) ?></span>
                    </div>
                <?php endif; ?>

                <div class="grid grid-cols-2 sm:grid-cols-4 gap-3 max-w-2xl mx-auto mb-8">
                    <div class="bg-gray-50 rounded-xl p-4 text-center">
                        <p class="text-lg font-bold text-gray-800"><?= min((int)$quiz['question_limit'], $bankCount) ?></p>
                        <p class="text-[10px] uppercase font-bold text-gray-400 mt-1">Questions</p>
                    </div>
                    <div class="bg-gray-50 rounded-xl p-4 text-center">
                        <p class="text-lg font-bold text-brandOrange"><?= (int)$quiz['passing_score'] ?>%</p>
                        <p class="text-[10px] uppercase font-bold text-gray-400 mt-1">To Pass</p>
                    </div>
                    <div class="bg-gray-50 rounded-xl p-4 text-center">
                        <p class="text-lg font-bold text-gray-800"><?= (int)$quiz['time_limit'] > 0 ? (int)$quiz['time_limit'] . ' min' : 'None' ?></p>
                        <p class="text-[10px] uppercase font-bold text-gray-400 mt-1">Time Limit</p>
                    </div>
                    <div class="bg-gray-50 rounded-xl p-4 text-center">
                        <p class="text-lg font-bold text-gray-800"><?= (int)$quiz['random_questions'] === 1 ? 'Yes' : 'No' ?></p>
                        <p class="text-[10px] uppercase font-bold text-gray-400 mt-1">Random Set</p>
                    </div>
                </div>

                <div class="max-w-2xl mx-auto">
                    <ul class="text-sm text-gray-600 space-y-2 mb-8">
                        <li class="flex items-start gap-2"><span class="text-brandOrange font-bold">•</span> You'll get <span class="font-semibold"><?= min((int)$quiz['question_limit'], $bankCount) ?></span> questions randomly chosen from a bank of <?= $bankCount ?>.</li>
                        <li class="flex items-start gap-2"><span class="text-brandOrange font-bold">•</span> You need <span class="font-semibold"><?= (int)$quiz['passing_score'] ?>%</span> or higher to pass and unlock your certificate.</li>
                        <?php if ((int)$quiz['time_limit'] > 0): ?><li class="flex items-start gap-2"><span class="text-brandOrange font-bold">•</span> The quiz will be submitted automatically when time runs out.</li><?php endif; ?>
                        <li class="flex items-start gap-2"><span class="text-brandOrange font-bold">•</span> Your answers are saved as you go. Submit only when you're ready &mdash; it can't be changed afterwards.</li>
                    </ul>

                    <form method="POST" action="quiz.php?module_id=<?= $module_id ?>" onsubmit="var b=this.querySelector('button');b.disabled=true;b.textContent='Starting...';">
                        <input type="hidden" name="start" value="1">
                        <button type="submit" class="w-full px-6 py-3 rounded-xl text-sm font-bold bg-brandOrange text-white hover:bg-brandOrangeHover transition shadow-sm">Start Quiz</button>
                    </form>
                </div>
            <?php endif; ?>
        </div>
    <?php endif; ?>
</div>

<?php if ($attempt && $attempt['status'] === 'in_progress'): ?>
<script>
(function() {
    var el = document.getElementById('quizApp');
    var data = JSON.parse(el.getAttribute('data-json'));

    var current = Math.min(Math.max(0, data.q), data.questions.length - 1);
    var total = data.questions.length;
    var saved = data.saved;
    var submitLocked = false;

    var container = document.createElement('div');
    container.className = 'space-y-6';
    el.appendChild(container);

    // Header
    var header = document.createElement('div');
    header.className = 'bg-white rounded-2xl border border-gray-200 shadow-sm p-6 flex items-center justify-between flex-wrap gap-3';
    header.innerHTML =
        '<div><h1 class="text-lg font-bold text-brandOchre">' + esc(data.quizTitle) + '</h1>' +
        '<p class="text-xs text-gray-500 mt-0.5">Question <span id="curNum">' + (current + 1) + '</span> of ' + total + '</p></div>' +
        (data.timeLimitSec > 0 ? '<div id="timerBox" class="px-4 py-2 rounded-lg font-mono font-bold text-sm bg-orange-50 text-brandOrange border border-orange-200"></div>' : '');
    container.appendChild(header);

    // Progress dots
    var nav = document.createElement('div');
    nav.className = 'flex flex-wrap gap-1.5';
    for (var i = 0; i < total; i++) {
        var dot = document.createElement('button');
        dot.className = 'w-8 h-8 rounded-lg text-xs font-bold transition question-dot';
        dot.textContent = i + 1;
        dot.addEventListener('click', function(idx) { return function() { goTo(idx); }; }(i));
        nav.appendChild(dot);
    }
    container.appendChild(nav);

    // Question card
    var card = document.createElement('div');
    card.className = 'bg-white rounded-2xl border border-gray-200 shadow-sm p-6 sm:p-8';
    container.appendChild(card);

    var qNumEl, qTextEl, optsEl;
    function render() {
        var q = data.questions[current];
        card.innerHTML =
            '<p class="font-semibold text-gray-800 text-lg mb-6"><span class="inline-flex items-center justify-center w-8 h-8 rounded-full bg-brandOrange text-white text-sm font-bold mr-3">' + (current + 1) + '</span>' + esc(q.text) + '</p>' +
            '<div class="space-y-3" id="optList"></div>' +
            '<div class="flex items-center justify-between mt-8 pt-5 border-t border-gray-100">' +
            '  <button type="button" id="btnPrev" class="px-5 py-2.5 rounded-xl text-sm font-bold border border-gray-300 text-gray-600 hover:bg-gray-50 transition">← Previous</button>' +
            '  <span id="answeredBadge" class="text-xs text-gray-400"></span>' +
            '  <button type="button" id="btnNext" class="px-5 py-2.5 rounded-xl text-sm font-bold bg-brandOrange text-white hover:bg-brandOrangeHover transition shadow-sm">Next →</button>' +
            '</div>';

        var list = card.querySelector('#optList');
        q.options.forEach(function(opt, idx) {
            var label = document.createElement('label');
            label.className = 'flex items-center gap-3 px-4 py-3 rounded-xl border cursor-pointer transition opt-label';
            label.innerHTML = '<input type="radio" name="opt" value="' + opt.id + '" class="w-4 h-4 text-brandOrange focus:ring-brandOrange">' +
                              '<span class="text-sm text-gray-700"><span class="font-bold">' + String.fromCharCode(65 + idx) + ')</span> ' + esc(opt.text) + '</span>';
            if (saved[q.id] && saved[q.id] == opt.id) {
                label.classList.add('border-brandOrange', 'bg-orange-50/60');
                label.querySelector('input').checked = true;
            }
            label.addEventListener('click', function(oid) { return function() { select(q.id, oid, label); }; }(opt.id));
            list.appendChild(label);
        });

        document.getElementById('btnPrev').addEventListener('click', function() { goTo(current - 1); });
        var btnNext = document.getElementById('btnNext');
        btnNext.addEventListener('click', function() {
            if (current < total - 1) goTo(current + 1);
            else askSubmit();
        });
        btnNext.textContent = current === total - 1 ? 'Submit Quiz ✓' : 'Next →';

        updateDots();
        updateTimerText();
        updateAnsweredBadge();
        window.scrollTo({ top: 0, behavior: 'smooth' });
    }

    function updateAnsweredBadge() {
        var answered = Object.keys(saved).length;
        var badge = document.getElementById('answeredBadge');
        if (badge) badge.textContent = answered + ' / ' + total + ' answered';
    }

    function updateDots() {
        document.querySelectorAll('.question-dot').forEach(function(d, i) {
            var q = data.questions[i];
            if (saved[q.id]) { d.classList.remove('bg-gray-100', 'text-gray-400', 'bg-brandOrange', 'text-white'); d.classList.add('bg-green-500', 'text-white'); }
            else if (i === current) { d.classList.remove('bg-gray-100', 'text-gray-400', 'bg-green-500', 'text-white'); d.classList.add('bg-brandOrange', 'text-white'); }
            else { d.classList.remove('bg-green-500', 'text-brandOrange', 'text-white'); d.classList.add('bg-gray-100', 'text-gray-400'); }
        });
    }

    function select(qid, oid, label) {
        label.parentNode.querySelectorAll('.opt-label').forEach(function(l) {
            l.classList.remove('border-brandOrange', 'bg-orange-50/60');
        });
        label.classList.add('border-brandOrange', 'bg-orange-50/60');
        saved[qid] = oid;
        save(qid, oid);
        updateDots();
        updateAnsweredBadge();
    }

    function save(qid, oid) {
        var fd = new FormData();
        fd.append('action', 'save');
        fd.append('attempt_id', data.attemptId);
        fd.append('question_id', qid);
        fd.append('option_id', oid);
        fetch('quiz_ajax.php', { method: 'POST', body: fd })
            .then(function(r) { return r.json(); })
            .then(function(d) { if (d && d.expired) { finalSubmit(true); } })
            .catch(function() {});
    }

    function goTo(idx) {
        if (idx < 0 || idx >= total) return;
        current = idx;
        history.replaceState(null, '', '?module_id=' + data.moduleId + '&attempt=' + data.attemptId + '&q=' + current);
        render();
    }

    function askSubmit() {
        var answered = Object.keys(saved).length;
        var msg = 'You have answered ' + answered + ' of ' + total + ' questions. Are you sure you want to submit?';
        if (answered < total) msg += '\n\nUnanswered questions will be marked as wrong.';
        if (confirm(msg)) finalSubmit(false);
    }

    function finalSubmit(expired) {
        if (submitLocked) return;
        submitLocked = true;
        var overlay = document.createElement('div');
        overlay.className = 'fixed inset-0 z-50 bg-black/40 flex items-center justify-center';
        overlay.innerHTML = '<div class="bg-white rounded-2xl p-6 shadow-2xl"><p class="text-sm text-gray-700 font-semibold mb-2">' + (expired ? 'Time is up!' : 'Submitting your quiz...') + '</p><div class="animate-spin w-6 h-6 border-2 border-brandOrange border-t-transparent rounded-full mx-auto"></div></div>';
        document.body.appendChild(overlay);

        var fd = new FormData();
        fd.append('action', 'submit');
        fd.append('attempt_id', data.attemptId);
        fetch('quiz_ajax.php', { method: 'POST', body: fd })
            .then(function(r) { return r.json(); })
            .then(function(d) {
                if (d && d.success) {
                    window.location.href = 'quiz.php?module_id=' + data.moduleId + '&attempt=' + d.attempt_id;
                } else if (d && d.message === 'already_submitted') {
                    window.location.href = 'quiz.php?module_id=' + data.moduleId + '&attempt=' + data.attemptId;
                } else {
                    overlay.remove();
                    submitLocked = false;
                    alert(d.message || 'Failed to submit. Please try again.');
                }
            })
            .catch(function() { overlay.remove(); submitLocked = false; alert('Network error. Please try again.'); });
    }

    // Timer
    function updateTimerText() {
        var box = document.getElementById('timerBox');
        if (!box) return;
        var remaining = data.deadlineTs - Math.floor(Date.now() / 1000);
        if (remaining <= 0) {
            box.textContent = '0:00';
            if (!submitLocked) finalSubmit(true);
            return;
        }
        var m = Math.floor(remaining / 60);
        var s = remaining % 60;
        box.textContent = '⏱ ' + m + ':' + (s < 10 ? '0' : '') + s;
        if (remaining < 60) box.classList.add('bg-red-50', 'text-red-600', 'border-red-200');
    }

    if (data.timeLimitSec > 0) {
        setInterval(updateTimerText, 1000);
    }

    function esc(s) {
        if (!s) return '';
        var d = document.createElement('div');
        d.textContent = s;
        return d.innerHTML;
    }

    render();
})();
</script>
<?php endif; ?>

<script>
function downloadCertificate(event, moduleId) {
    var btn = event.currentTarget;
    btn.disabled = true;
    btn.innerText = 'Generating...';

    var xhr = new XMLHttpRequest();
    xhr.open('GET', 'certificates.php?module_id=' + moduleId + '&validate=1', true);
    xhr.onload = function () {
        if (xhr.status === 200) {
            var data = JSON.parse(xhr.responseText);
            if (data.ok) {
                fetchCertificate(moduleId, btn);
            } else {
                alert(data.error || 'Failed to generate certificate.');
                btn.disabled = false;
                btn.innerText = 'Download Certificate';
            }
        } else {
            alert('Failed to generate certificate.');
            btn.disabled = false;
            btn.innerText = 'Download Certificate';
        }
    };
    xhr.onerror = function () {
        alert('Download failed. Please try again.');
        btn.disabled = false;
        btn.innerText = 'Download Certificate';
    };
    xhr.send();
}

function fetchCertificate(moduleId, btn) {
    var xhr = new XMLHttpRequest();
    xhr.open('GET', 'certificates.php?module_id=' + moduleId, true);
    xhr.responseType = 'json';
    xhr.onload = function () {
        if (xhr.status === 200) {
            var data = xhr.response;
            if (data.pdf) {
                var byteStr = atob(data.pdf);
                var len = byteStr.length;
                var nums = new Array(len);
                for (var i = 0; i < len; i++) nums[i] = byteStr.charCodeAt(i);
                var blob = new Blob([new Uint8Array(nums)], {type: 'application/pdf'});
                var url = URL.createObjectURL(blob);
                var a = document.createElement('a');
                a.href = url;
                a.download = data.filename || 'Certificate.pdf';
                document.body.appendChild(a);
                a.click();
                document.body.removeChild(a);
                setTimeout(function () { URL.revokeObjectURL(url); }, 5000);
            } else {
                alert('Failed to generate certificate.');
            }
        } else {
            alert('Failed to generate certificate.');
        }
        btn.disabled = false;
        btn.innerText = 'Download Certificate';
    };
    xhr.onerror = function () {
        alert('Download failed. Please try again.');
        btn.disabled = false;
        btn.innerText = 'Download Certificate';
    };
    xhr.send();
}
</script>

<?php include_once('../includes/footer.php'); ?>