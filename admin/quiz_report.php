<?php require_once 'includes/header.php'; ?>
<?php require_once 'includes/sidebar.php'; ?>
<?php require_once '../config/db.php'; ?>

<?php
$module_id = (int)($_GET['module_id'] ?? 0);
$user_search = trim($_GET['user'] ?? '');
$date_from = trim($_GET['date_from'] ?? '');
$date_to = trim($_GET['date_to'] ?? '');

$where = ['1=1'];
$params = [];
if ($module_id) { $where[] = 'q.module_id = ?'; $params[] = $module_id; }
if ($user_search !== '') { $where[] = '(u.name LIKE ? OR u.email LIKE ?)'; $params[] = "%$user_search%"; $params[] = "%$user_search%"; }
if ($date_from !== '') { $where[] = 'DATE(qr.attempt_date) >= ?'; $params[] = $date_from; }
if ($date_to !== '') { $where[] = 'DATE(qr.attempt_date) <= ?'; $params[] = $date_to; }
$whereSql = implode(' AND ', $where);

function runQuery($conn, $sql, $params) {
    $stmt = $conn->prepare($sql);
    if (!empty($params)) {
        $types = '';
        foreach ($params as $p) { $types .= is_int($p) ? 'i' : 's'; }
        $stmt->bind_param($types, ...$params);
    }
    $stmt->execute();
    return $stmt->get_result();
}

$baseFrom = "FROM quiz_results qr
             JOIN quizzes q ON qr.quiz_id = q.id
             JOIN modules m ON q.module_id = m.id
             JOIN courses c ON m.course_id = c.id
             JOIN users u ON qr.user_id = u.id
             WHERE $whereSql";

$stats = runQuery($conn, "SELECT COUNT(*) AS total,
                                 SUM(qr.passed) AS passed,
                                 COUNT(*) - SUM(qr.passed) AS failed,
                                 ROUND(AVG(qr.score), 1) AS avg_score,
                                 MAX(qr.score) AS high_score,
                                 MIN(qr.score) AS low_score
                          $baseFrom", $params)->fetch_assoc();

$limit = 15;
$page = max(1, intval($_GET['page'] ?? 1));
$offset = ($page - 1) * $limit;
$totalRows = runQuery($conn, "SELECT COUNT(*) c $baseFrom", $params)->fetch_assoc()['c'];
$totalPages = max(1, ceil($totalRows / $limit));

$rows = runQuery($conn, "SELECT qr.attempt_id, qr.attempt_number, qr.score, qr.total_questions, qr.correct_count, qr.wrong_count,
                                qr.passed, qr.attempt_date, q.quiz_title, m.name AS module_name, c.course_name,
                                u.name AS student_name, u.email
                         $baseFrom
                         ORDER BY qr.attempt_date DESC
                         LIMIT $offset, $limit", $params);

$modules = $conn->query("SELECT m.id, m.name, c.course_name FROM modules m JOIN courses c ON m.course_id = c.id ORDER BY c.course_name, m.name");
?>

<div class="flex-1 flex flex-col overflow-hidden">
    <main class="flex-1 overflow-y-auto p-4 sm:p-6 lg:p-8">
        <div class="bg-white rounded-xl border border-gray-200 shadow-sm mb-6">
            <div class="px-6 py-4 border-b border-gray-100">
                <h3 class="font-semibold text-gray-800">Filters</h3>
            </div>
            <form method="GET" class="px-6 py-4 grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-5 gap-3">
                <div>
                    <label class="block text-xs font-medium text-gray-500 mb-1">Module</label>
                    <select name="module_id" class="w-full px-3 py-2 text-sm border border-gray-200 rounded-lg focus:outline-none focus:ring-2 focus:ring-brandOrange">
                        <option value="">All modules</option>
                        <?php while ($m = $modules->fetch_assoc()): ?>
                            <option value="<?= $m['id'] ?>" <?= $module_id === (int)$m['id'] ? 'selected' : '' ?>>
                                <?= htmlspecialchars($m['name'] . ' (' . $m['course_name'] . ')') ?>
                            </option>
                        <?php endwhile; ?>
                    </select>
                </div>
                <div>
                    <label class="block text-xs font-medium text-gray-500 mb-1">Student</label>
                    <input type="text" name="user" value="<?= htmlspecialchars($user_search) ?>" placeholder="Name or email..."
                           class="w-full px-3 py-2 text-sm border border-gray-200 rounded-lg focus:outline-none focus:ring-2 focus:ring-brandOrange">
                </div>
                <div>
                    <label class="block text-xs font-medium text-gray-500 mb-1">From</label>
                    <input type="date" name="date_from" value="<?= htmlspecialchars($date_from) ?>"
                           class="w-full px-3 py-2 text-sm border border-gray-200 rounded-lg focus:outline-none focus:ring-2 focus:ring-brandOrange">
                </div>
                <div>
                    <label class="block text-xs font-medium text-gray-500 mb-1">To</label>
                    <input type="date" name="date_to" value="<?= htmlspecialchars($date_to) ?>"
                           class="w-full px-3 py-2 text-sm border border-gray-200 rounded-lg focus:outline-none focus:ring-2 focus:ring-brandOrange">
                </div>
                <div class="flex items-end gap-2">
                    <button type="submit" class="flex-1 px-4 py-2 bg-brandOrange text-white text-sm font-semibold rounded-lg hover:bg-brandOrangeHover transition shadow-sm">Filter</button>
                    <a href="quiz_report.php" class="px-4 py-2 text-sm font-semibold text-gray-500 border border-gray-200 rounded-lg hover:bg-gray-50 transition">Reset</a>
                </div>
            </form>
        </div>

        <div class="grid grid-cols-2 md:grid-cols-3 xl:grid-cols-6 gap-4 mb-6">
            <div class="bg-white rounded-xl border border-gray-200 p-5 text-center shadow-sm">
                <p class="text-2xl font-bold text-gray-800"><?= (int)$stats['total'] ?></p>
                <p class="text-[10px] uppercase font-bold text-gray-400 mt-1">Total Attempts</p>
            </div>
            <div class="bg-white rounded-xl border border-gray-200 p-5 text-center shadow-sm">
                <p class="text-2xl font-bold text-green-600"><?= (int)$stats['passed'] ?></p>
                <p class="text-[10px] uppercase font-bold text-gray-400 mt-1">Passed</p>
            </div>
            <div class="bg-white rounded-xl border border-gray-200 p-5 text-center shadow-sm">
                <p class="text-2xl font-bold text-red-600"><?= (int)$stats['failed'] ?></p>
                <p class="text-[10px] uppercase font-bold text-gray-400 mt-1">Failed</p>
            </div>
            <div class="bg-white rounded-xl border border-gray-200 p-5 text-center shadow-sm">
                <p class="text-2xl font-bold text-brandOrange"><?= $stats['avg_score'] ?? 0 ?>%</p>
                <p class="text-[10px] uppercase font-bold text-gray-400 mt-1">Average</p>
            </div>
            <div class="bg-white rounded-xl border border-gray-200 p-5 text-center shadow-sm">
                <p class="text-2xl font-bold text-blue-600"><?= $stats['high_score'] ?? 0 ?>%</p>
                <p class="text-[10px] uppercase font-bold text-gray-400 mt-1">Highest</p>
            </div>
            <div class="bg-white rounded-xl border border-gray-200 p-5 text-center shadow-sm">
                <p class="text-2xl font-bold text-gray-500"><?= $stats['low_score'] ?? 0 ?>%</p>
                <p class="text-[10px] uppercase font-bold text-gray-400 mt-1">Lowest</p>
            </div>
        </div>

        <div class="bg-white rounded-xl border border-gray-200 shadow-sm">
            <div class="px-6 py-4 border-b border-gray-100 flex items-center justify-between">
                <h3 class="font-semibold text-gray-800">Attempts (<?= $totalRows ?>)</h3>
                <span class="text-xs text-gray-400"><?= (int)($stats['total'] > 0 ? round(($stats['passed'] / $stats['total']) * 100) : 0) ?>% pass rate</span>
            </div>
            <div class="overflow-x-auto">
                <table class="w-full">
                    <thead class="bg-orange-100/50">
                        <tr class="text-left text-xs font-semibold text-gray-500 uppercase tracking-wider">
                            <th class="px-6 py-4">Student</th>
                            <th class="px-6 py-4">Quiz</th>
                            <th class="px-6 py-4">Module</th>
                            <th class="px-6 py-4 text-center">Att.#</th>
                            <th class="px-6 py-4 text-center">Correct</th>
                            <th class="px-6 py-4 text-center">Score</th>
                            <th class="px-6 py-4 text-center">Status</th>
                            <th class="px-6 py-4 text-center">Date</th>
                            <th class="px-6 py-4 text-center">Actions</th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-gray-100">
                        <?php if ($rows->num_rows > 0): ?>
                            <?php while ($r = $rows->fetch_assoc()): ?>
                            <tr class="hover:bg-gray-50 transition-colors">
                                <td class="px-6 py-4">
                                    <p class="text-sm font-medium text-gray-700"><?= htmlspecialchars($r['student_name']) ?></p>
                                    <p class="text-xs text-gray-400"><?= htmlspecialchars($r['email']) ?></p>
                                </td>
                                <td class="px-6 py-4 text-sm text-gray-700"><?= htmlspecialchars($r['quiz_title']) ?></td>
                                <td class="px-6 py-4">
                                    <p class="text-sm text-gray-700"><?= htmlspecialchars($r['module_name']) ?></p>
                                    <p class="text-xs text-gray-400"><?= htmlspecialchars($r['course_name']) ?></p>
                                </td>
                                <td class="px-6 py-4 text-center text-sm text-gray-500"><?= (int)$r['attempt_number'] ?></td>
                                <td class="px-6 py-4 text-center text-sm text-gray-600"><?= (int)$r['correct_count'] ?>/<?= (int)$r['total_questions'] ?></td>
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
                                    <a href="attempt_detail.php?attempt_id=<?= (int)$r['attempt_id'] ?>"
                                       class="inline-flex items-center gap-1.5 px-3 py-1.5 text-xs font-semibold text-blue-600 border border-blue-200 rounded-lg hover:bg-blue-50 transition"
                                       title="View student's answers">
                                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"/><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z"/></svg>
                                        View
                                    </a>
                                </td>
                            </tr>
                            <?php endwhile; ?>
                        <?php else: ?>
                            <tr>
                                <td colspan="9" class="px-6 py-12 text-center">
                                    <p class="text-sm text-gray-400">No quiz attempts match your filters.</p>
                                </td>
                            </tr>
                        <?php endif; ?>
                    </tbody>
                </table>
            </div>
            <?php if ($totalPages > 1): ?>
            <div class="px-6 py-4 border-t border-gray-100 flex items-center justify-between">
                <p class="text-sm text-gray-500">Page <?= $page ?> of <?= $totalPages ?></p>
                <div class="flex items-center gap-1">
                    <a href="?<?= http_build_query(array_merge($_GET, ['page' => max(1, $page - 1)])) ?>" class="px-3 py-1.5 text-sm rounded-lg border border-gray-200 text-gray-600 hover:bg-gray-50 transition <?= $page <= 1 ? 'pointer-events-none opacity-40' : '' ?>">&lt;</a>
                    <span class="px-2 text-sm text-gray-500"><?= $page ?> / <?= $totalPages ?></span>
                    <a href="?<?= http_build_query(array_merge($_GET, ['page' => min($totalPages, $page + 1)])) ?>" class="px-3 py-1.5 text-sm rounded-lg border border-gray-200 text-gray-600 hover:bg-gray-50 transition <?= $page >= $totalPages ? 'pointer-events-none opacity-40' : '' ?>">&gt;</a>
                </div>
            </div>
            <?php endif; ?>
        </div>
    </main>
</div>

<?php require_once 'includes/footer.php'; ?>