<?php
session_start();
if (!isset($_SESSION['role']) || $_SESSION['role'] !== 'admin') {
    header('Location: ../auth/login.php');
    exit;
}
require_once '../config/db.php';
require_once 'includes/header.php';
require_once 'includes/sidebar.php';

$attempt_id = (int)($_GET['attempt_id'] ?? 0);
if (!$attempt_id) {
    header('Location: quiz_report.php');
    exit;
}

$stmt = $conn->prepare("
    SELECT a.id AS attempt_id, a.qlimit, a.start_time, a.end_time, a.status,
           q.quiz_title, q.passing_score, m.name AS module_name, c.course_name,
           u.name AS student_name, u.email
    FROM quiz_attempts a
    JOIN quizzes q ON a.quiz_id = q.id
    JOIN modules m ON q.module_id = m.id
    JOIN courses c ON m.course_id = c.id
    JOIN users u ON a.user_id = u.id
    WHERE a.id = ?
");
$stmt->bind_param('i', $attempt_id);
$stmt->execute();
$attempt = $stmt->get_result()->fetch_assoc();

if (!$attempt) {
    header('Location: quiz_report.php');
    exit;
}

$result = $conn->query("
    SELECT * FROM quiz_results WHERE attempt_id = $attempt_id LIMIT 1
")->fetch_assoc();
?>

<div class="flex-1 flex flex-col overflow-hidden">
    <main class="flex-1 overflow-y-auto p-4 sm:p-6 lg:p-8">
        <div class="mb-4">
            <a href="quiz_report.php" class="inline-flex items-center gap-1.5 text-sm text-gray-500 hover:text-brandOrange mb-2 transition">
                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 19l-7-7 7-7"/></svg>
                Back to Quiz Report
            </a>
            <h2 class="text-lg font-bold text-gray-800"><?= htmlspecialchars($attempt['quiz_title']) ?></h2>
            <p class="text-sm text-gray-500"><?= htmlspecialchars($attempt['module_name']) ?> &middot; <?= htmlspecialchars($attempt['course_name']) ?></p>
        </div>

        <div class="bg-white rounded-xl border border-gray-200 shadow-sm p-6 mb-6">
            <div class="flex items-center justify-between flex-wrap gap-4">
                <div>
                    <p class="text-sm font-semibold text-gray-800"><?= htmlspecialchars($attempt['student_name']) ?></p>
                    <p class="text-xs text-gray-400"><?= htmlspecialchars($attempt['email']) ?></p>
                </div>
                <?php if ($result): ?>
                <div class="flex items-center gap-4 flex-wrap">
                    <div class="text-center">
                        <p class="text-lg font-bold text-gray-800"><?= (int)$result['total_questions'] ?></p>
                        <p class="text-[10px] uppercase font-bold text-gray-400">Total</p>
                    </div>
                    <div class="text-center">
                        <p class="text-lg font-bold text-green-600"><?= (int)$result['correct_count'] ?></p>
                        <p class="text-[10px] uppercase font-bold text-gray-400">Correct</p>
                    </div>
                    <div class="text-center">
                        <p class="text-lg font-bold text-red-600"><?= (int)$result['wrong_count'] ?></p>
                        <p class="text-[10px] uppercase font-bold text-gray-400">Wrong</p>
                    </div>
                    <div class="text-center">
                        <p class="text-lg font-bold <?= (int)$result['passed'] === 1 ? 'text-green-600' : 'text-red-600' ?>"><?= (int)$result['score'] ?>%</p>
                        <p class="text-[10px] uppercase font-bold text-gray-400">Score</p>
                    </div>
                    <div class="text-center">
                        <span class="inline-flex items-center px-2.5 py-1 text-xs font-medium rounded-full <?= (int)$result['passed'] === 1 ? 'bg-green-100 text-green-700' : 'bg-red-100 text-red-700' ?>">
                            <?= (int)$result['passed'] === 1 ? 'Passed' : 'Failed' ?>
                        </span>
                        <p class="text-[10px] uppercase font-bold text-gray-400 mt-1">Passing <?= (int)$attempt['passing_score'] ?>%</p>
                    </div>
                </div>
                <?php else: ?>
                <span class="inline-flex items-center px-2.5 py-1 text-xs font-medium rounded-full bg-gray-100 text-gray-500">In progress</span>
                <?php endif; ?>
            </div>
        </div>

        <div class="bg-white rounded-xl border border-gray-200 shadow-sm overflow-hidden">
            <div class="px-6 py-4 border-b border-gray-100 flex items-center justify-between">
                <h3 class="font-bold text-gray-800">Answer Review</h3>
                <span class="text-xs text-gray-400">Started <?= date('M j, Y H:i', strtotime($attempt['start_time'])) ?><?= $attempt['end_time'] ? ' &middot; Submitted ' . date('M j, Y H:i', strtotime($attempt['end_time'])) : '' ?></span>
            </div>
            <div class="divide-y divide-gray-100">
                <?php
                $review = $conn->query("
                    SELECT aq.question_id, aq.options_order, q.question_text, q.explanation,
                           oa.option_id AS given_id
                    FROM quiz_attempt_questions aq
                    JOIN quiz_questions q ON aq.question_id = q.id
                    LEFT JOIN quiz_attempt_answers oa ON oa.attempt_id = $attempt_id AND oa.question_id = q.id
                    WHERE aq.attempt_id = $attempt_id ORDER BY aq.display_order ASC
                ");
                $letters = ['A', 'B', 'C', 'D'];
                $i = 0;
                while ($rq = $review->fetch_assoc()):
                    $optOrder = json_decode($rq['options_order'] ?: '[]', true);
                    $optsById = [];
                    if (count($optOrder) > 0) {
                        $ids = implode(',', array_map('intval', $optOrder));
                        $q = $conn->query("SELECT id, option_text, is_correct FROM quiz_options WHERE id IN ($ids)");
                        while ($o = $q->fetch_assoc()) { $optsById[(int)$o['id']] = $o; }
                    }
                    $givenCorrect = $rq['given_id'] !== null && isset($optsById[(int)$rq['given_id']]) && (int)$optsById[(int)$rq['given_id']]['is_correct'] === 1;
                ?>
                <div class="px-6 py-5">
                    <p class="text-sm font-semibold text-gray-800 mb-3">
                        <?= $i + 1 ?>. <?= htmlspecialchars($rq['question_text']) ?>
                        <span class="ml-2 text-xs font-bold <?= $givenCorrect ? 'text-green-600' : 'text-red-600' ?>"><?= $givenCorrect ? 'Correct' : ($rq['given_id'] !== null ? 'Incorrect' : 'Not answered') ?></span>
                    </p>
                    <div class="grid grid-cols-1 sm:grid-cols-2 gap-2">
                        <?php foreach ($optOrder as $j => $oid):
                            $op = $optsById[(int)$oid] ?? null;
                            if (!$op) continue;
                            $isCorrectOpt = (int)$op['is_correct'] === 1;
                            $isGivenOpt = $rq['given_id'] !== null && (int)$oid === (int)$rq['given_id'];
                        ?>
                        <div class="flex items-center gap-2 px-3 py-2 rounded-lg text-sm <?= $isCorrectOpt ? 'bg-green-50 text-green-700 border border-green-200' : ($isGivenOpt ? 'bg-red-50 text-red-600 border border-red-200' : 'bg-gray-50 text-gray-600 border border-gray-100') ?>">
                            <span class="font-bold"><?= $letters[$j] ?>)</span>
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
    </main>
</div>

<?php require_once 'includes/footer.php'; ?>
