<?php
session_start();
require_once '../config/db.php';

$user_id = $_SESSION['user_id'] ?? null;
if (!$user_id) {
    header('Location: ../auth/login.php');
    exit;
}

include_once('../includes/header.php');

$rows = $conn->query("
    SELECT qr.attempt_number, qr.quiz_id, qr.attempt_id, qr.score, qr.total_questions, qr.correct_count, qr.wrong_count,
           qr.passed, qr.attempt_date, q.quiz_title, m.name AS module_name, c.course_name, q.module_id
    FROM quiz_results qr
    JOIN quizzes q ON qr.quiz_id = q.id
    JOIN modules m ON q.module_id = m.id
    JOIN courses c ON m.course_id = c.id
    WHERE qr.user_id = $user_id
    ORDER BY qr.id DESC
");
$history = ($rows && $rows->num_rows > 0) ? $rows->fetch_all(MYSQLI_ASSOC) : [];
?>

<div class="min-h-screen bg-gray-50 py-12 px-4 sm:px-6 lg:px-8">
    <div class="max-w-5xl mx-auto">
        <div class="flex items-center justify-between mb-6 px-4 sm:px-8">
            <div>
                <h1 class="text-2xl sm:text-3xl font-bold text-brandOchre">Quiz History</h1>
                <p class="text-sm text-gray-500 mt-1">All of your quiz attempts and scores</p>
            </div>
            <a href="my_learning.php" class="inline-flex items-center gap-1.5 px-4 py-2 text-sm font-semibold bg-brandOrange text-white rounded-lg hover:bg-brandOrangeHover transition">
                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 19l-7-7 7-7"/></svg>
                Back to Dashboard
            </a>
        </div>

        <div class="bg-white rounded-2xl border border-gray-200 mx-4 sm:mx-8 shadow-sm overflow-hidden">
            <?php if (count($history) > 0): ?>
            <div class="overflow-x-auto">
                <table class="w-full">
                    <thead class="bg-orange-100/50">
                        <tr class="text-left text-xs font-semibold text-gray-500 uppercase tracking-wider">
                            <th class="px-6 py-4">Att.#</th>
                            <th class="px-6 py-4">Quiz</th>
                            <th class="px-6 py-4">Module</th>
                            <th class="px-6 py-4 text-center">Result</th>
                            <th class="px-6 py-4 text-center">Score</th>
                            <th class="px-6 py-4 text-center">Status</th>
                            <th class="px-6 py-4 text-center">Date</th>
                            <th class="px-6 py-4 text-center">Actions</th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-gray-100">
                        <?php foreach ($history as $r): ?>
                        <tr class="hover:bg-gray-50 transition-colors">
                            <td class="px-6 py-4 text-sm text-gray-500"><?= (int)$r['attempt_number'] ?></td>
                            <td class="px-6 py-4 text-sm font-medium text-gray-700"><?= htmlspecialchars($r['quiz_title']) ?></td>
                            <td class="px-6 py-4">
                                <p class="text-sm text-gray-700"><?= htmlspecialchars($r['module_name']) ?></p>
                                <p class="text-xs text-gray-400"><?= htmlspecialchars($r['course_name']) ?></p>
                            </td>
                            <td class="px-6 py-4 text-center text-sm text-gray-600">
                                <?= (int)$r['correct_count'] ?> / <?= (int)$r['total_questions'] ?>
                            </td>
                            <td class="px-6 py-4 text-center">
                                <span class="text-sm font-bold <?= (int)$r['passed'] === 1 ? 'text-green-600' : 'text-red-600' ?>"><?= (int)$r['score'] ?>%</span>
                            </td>
                            <td class="px-6 py-4 text-center">
                                <span class="inline-flex items-center px-2.5 py-1 text-xs font-medium rounded-full <?= (int)$r['passed'] === 1 ? 'bg-green-100 text-green-700' : 'bg-red-100 text-red-700' ?>">
                                    <?= (int)$r['passed'] === 1 ? 'Passed' : 'Failed' ?>
                                </span>
                            </td>
                            <td class="px-6 py-4 text-sm text-gray-500"><?= date('M j, Y H:i', strtotime($r['attempt_date'])) ?></td>
                            <td class="px-6 py-4 text-center">
                                <a href="quiz.php?module_id=<?= (int)$r['module_id'] ?>&attempt=<?= (int)$r['attempt_id'] ?>"
                                   class="inline-flex items-center gap-1.5 px-3 py-1.5 text-xs font-semibold text-blue-600 border border-blue-200 rounded-lg hover:bg-blue-50 transition"
                                   title="Review your answers">
                                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"/><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z"/></svg>
                                    Review
                                </a>
                            </td>
                        </tr>
                        <?php endforeach; ?>
                    </tbody>
                </table>
            </div>
            <?php else: ?>
                <div class="text-center py-16">
                    <svg class="w-16 h-16 text-gray-200 mx-auto mb-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"/></svg>
                    <p class="text-gray-500 font-semibold">No Quiz Attempts Yet</p>
                    <p class="text-sm text-gray-400 mt-1">Complete your lessons and take a quiz to see results here.</p>
                    <a href="courses.php" class="mt-5 inline-block text-sm font-bold text-brandOrange hover:underline">Browse Courses</a>
                </div>
            <?php endif; ?>
        </div>
    </div>
</div>

<?php include_once('../includes/footer.php'); ?>