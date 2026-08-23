<?php require_once 'includes/header.php'; ?>
<?php require_once 'includes/sidebar.php'; ?>
<?php require_once '../config/db.php'; ?>

<?php
$quiz_id = (int)($_GET['quiz_id'] ?? 0);
$q = trim($_GET['q'] ?? '');

$quizInfo = null;
if ($quiz_id) {
    $stmt = $conn->prepare("
        SELECT q.id, q.quiz_title, q.question_limit, q.random_questions, m.name AS module_name, c.course_name
        FROM quizzes q
        JOIN modules m ON q.module_id = m.id
        JOIN courses c ON m.course_id = c.id
        WHERE q.id = ?
    ");
    $stmt->bind_param('i', $quiz_id);
    $stmt->execute();
    $quizInfo = $stmt->get_result()->fetch_assoc();
}
if (!$quizInfo) {
    echo '<div class="flex-1 flex flex-col overflow-hidden"><main class="flex-1 overflow-y-auto p-8"><p class="text-gray-500">Quiz not found. <a href="quizzes.php" class="text-brandOrange font-semibold">Back to quizzes</a></p></main></div>';
    require_once 'includes/footer.php';
    exit;
}

$total = $conn->query("SELECT COUNT(*) c FROM quiz_questions WHERE quiz_id = $quiz_id" . ($q !== '' ? " AND question_text LIKE '%" . $conn->real_escape_string($q) . "%'" : ''))->fetch_assoc()['c'];

$limit = 10;
$page = max(1, intval($_GET['page'] ?? 1));
$offset = ($page - 1) * $limit;
$totalPages = max(1, ceil($total / $limit));

$sql = "SELECT id, question_text, explanation FROM quiz_questions WHERE quiz_id = $quiz_id";
if ($q !== '') $sql .= " AND question_text LIKE '%" . $conn->real_escape_string($q) . "%'";
$sql .= " ORDER BY id ASC LIMIT $offset, $limit";
$qResult = $conn->query($sql);

$rows = [];
while ($row = $qResult->fetch_assoc()) {
    $opts = $conn->query("SELECT option_text, is_correct FROM quiz_options WHERE question_id = {$row['id']} ORDER BY position ASC");
    $row['options'] = $opts->fetch_all(MYSQLI_ASSOC);
    $rows[] = $row;
}
?>

<div class="flex-1 flex flex-col overflow-hidden">
    <main class="flex-1 overflow-y-auto p-4 sm:p-6 lg:p-8">
        <div class="mb-4 flex items-center justify-between flex-wrap gap-3">
            <div>
                <a href="quizzes.php" class="inline-flex items-center gap-1.5 text-sm text-gray-500 hover:text-brandOrange mb-2 transition">
                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 19l-7-7 7-7"/></svg>
                    Back to Quizzes
                </a>
                <h2 class="text-lg font-bold text-gray-800"><?= htmlspecialchars($quizInfo['quiz_title']) ?></h2>
                <p class="text-sm text-gray-500"><?= htmlspecialchars($quizInfo['module_name']) ?> &middot; <?= htmlspecialchars($quizInfo['course_name']) ?></p>
            </div>
            <div class="flex items-center gap-2">
                <form method="GET" class="relative">
                    <input type="hidden" name="quiz_id" value="<?= $quiz_id ?>">
                    <svg class="w-4 h-4 text-gray-400 absolute left-3 top-1/2 -translate-y-1/2" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z"/></svg>
                    <input type="text" name="q" value="<?= htmlspecialchars($q) ?>" placeholder="Search question bank..." class="pl-9 pr-3 py-2 text-sm border border-orange-200 rounded-lg focus:outline-none focus:ring-2 focus:ring-brandOrange focus:border-transparent w-64">
                </form>
                <button onclick="openModal()" class="inline-flex items-center gap-1.5 px-4 py-2 bg-brandOrange text-white text-sm font-semibold rounded-lg hover:bg-brandOrangeHover transition shadow-sm">
                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4"/></svg>
                    Add Question
                </button>
            </div>
        </div>

        <div class="bg-white rounded-xl border border-gray-200 shadow-sm">
            <div class="px-6 py-4 border-b border-gray-100 flex items-center justify-between">
                <h3 class="font-semibold text-gray-800">Question Bank (<?= $total ?>)</h3>
                <span class="text-xs text-gray-400">
                    <?= $quizInfo['question_limit'] ?> shown per attempt
                    <?= $quizInfo['random_questions'] == 1 ? '&middot; randomly selected' : '&middot; all in order' ?>
                </span>
            </div>
            <div class="overflow-x-auto">
                <table class="w-full">
                    <thead class="bg-orange-100/50">
                        <tr class="text-left text-xs font-semibold text-gray-500 uppercase tracking-wider">
                            <th class="px-6 py-4">No.</th>
                            <th class="px-6 py-4">Question</th>
                            <th class="px-6 py-4">Options</th>
                            <th class="px-6 py-4 text-center">Correct</th>
                            <th class="px-6 py-4 text-center">Actions</th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-gray-100">
                        <?php if (count($rows) > 0): ?>
                            <?php $counter = $offset + 1; foreach ($rows as $row): ?>
                            <tr class="hover:bg-gray-50 transition-colors question-row" data-id="<?= $row['id'] ?>">
                                <td class="px-6 py-4 text-sm text-gray-500"><?= $counter++ ?></td>
                                <td class="px-6 py-4">
                                    <p class="text-sm font-medium text-gray-700 max-w-xs"><?= htmlspecialchars($row['question_text']) ?></p>
                                    <?php if (!empty($row['explanation'])): ?>
                                        <p class="text-[11px] text-gray-400 mt-1 max-w-xs"><span class="font-semibold">Explanation:</span> <?= htmlspecialchars($row['explanation']) ?></p>
                                    <?php endif; ?>
                                </td>
                                <td class="px-6 py-4">
                                    <div class="text-xs text-gray-600 space-y-0.5">
                                        <?php $letters = ['A', 'B', 'C', 'D']; $i = 0; foreach ($row['options'] as $opt): ?>
                                            <p class="<?= (int)$opt['is_correct'] === 1 ? 'text-green-600 font-semibold' : '' ?>">
                                                <span class="font-semibold <?= (int)$opt['is_correct'] === 1 ? 'text-green-600' : 'text-gray-500' ?>"><?= $letters[$i] ?>)</span>
                                                <?= htmlspecialchars($opt['option_text']) ?>
                                            </p>
                                        <?php $i++; endforeach; ?>
                                    </div>
                                </td>
                                <td class="px-6 py-4 text-center">
                                    <?php $correct = ''; foreach ($row['options'] as $i => $opt) { if ((int)$opt['is_correct'] === 1) $correct = $letters[$i]; } ?>
                                    <span class="inline-flex items-center justify-center w-7 h-7 rounded-full bg-green-100 text-green-700 font-bold text-xs"><?= $correct ?></span>
                                </td>
                                <td class="px-6 py-4 text-center">
                                    <div class="flex items-center justify-center gap-2">
                                        <button onclick="openModal(<?= $row['id'] ?>)" class="p-2 text-green-600 hover:text-green-700 hover:bg-green-100 rounded-lg transition" title="Edit">
                                            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z"/></svg>
                                        </button>
                                        <button onclick="deleteQuestion(<?= $row['id'] ?>)" class="p-2 text-red-600 hover:text-red-700 hover:bg-red-100 rounded-lg transition" title="Delete">
                                            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"/></svg>
                                        </button>
                                    </div>
                                </td>
                            </tr>
                            <?php endforeach; ?>
                        <?php else: ?>
                            <tr>
                                <td colspan="5" class="px-6 py-12 text-center">
                                    <p class="text-sm text-gray-400 mb-3"><?= $q !== '' ? 'No questions match your search.' : 'No questions in the bank yet.' ?></p>
                                    <button onclick="openModal()" class="inline-flex items-center gap-1.5 px-4 py-2 bg-brandOrange text-white text-sm font-semibold rounded-lg hover:bg-brandOrangeHover transition shadow-sm">
                                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4"/></svg>
                                        Add First Question
                                    </button>
                                </td>
                            </tr>
                        <?php endif; ?>
                    </tbody>
                </table>
            </div>
            <?php if ($totalPages > 1): ?>
            <div class="px-6 py-4 border-t border-gray-100 flex items-center justify-between">
                <p class="text-sm text-gray-500">Page <?= $page ?> of <?= $totalPages ?> (<?= $total ?> total)</p>
                <div class="flex items-center gap-1">
                    <a href="?quiz_id=<?= $quiz_id ?>&page=<?= max(1, $page - 1) ?>&q=<?= urlencode($q) ?>" class="px-3 py-1.5 text-sm rounded-lg border border-gray-200 text-gray-600 hover:bg-gray-50 transition <?= $page <= 1 ? 'pointer-events-none opacity-40' : '' ?>">&lt;</a>
                    <span class="px-2 text-sm text-gray-500"><?= $page ?> / <?= $totalPages ?></span>
                    <a href="?quiz_id=<?= $quiz_id ?>&page=<?= min($totalPages, $page + 1) ?>&q=<?= urlencode($q) ?>" class="px-3 py-1.5 text-sm rounded-lg border border-gray-200 text-gray-600 hover:bg-gray-50 transition <?= $page >= $totalPages ? 'pointer-events-none opacity-40' : '' ?>">&gt;</a>
                </div>
            </div>
            <?php endif; ?>
        </div>
    </main>
</div>

<!-- Modal -->
<div id="questionModal" class="fixed inset-0 z-50 hidden items-center justify-center bg-black/50 backdrop-blur-sm">
    <div class="bg-white rounded-2xl p-6 w-full max-w-lg mx-4 shadow-2xl max-h-[90vh] overflow-y-auto">
        <div class="flex items-center justify-between mb-6">
            <h3 class="text-lg font-bold text-gray-800" id="modalTitle">Add Question</h3>
            <button onclick="closeModal()" class="text-gray-400 hover:text-gray-600 transition">
                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"/></svg>
            </button>
        </div>
        <form id="questionForm" class="space-y-4">
            <input type="hidden" name="quiz_id" id="quizId" value="<?= $quiz_id ?>">
            <input type="hidden" name="id" id="questionId">
            <div>
                <label class="block text-sm font-medium text-gray-700 mb-1">Question</label>
                <textarea name="question_text" id="questionText" rows="2" required
                          class="w-full px-3 py-2.5 text-sm border border-gray-200 rounded-lg focus:outline-none focus:ring-2 focus:ring-brandOrange focus:border-transparent"
                          placeholder="What is the meaning of...?"></textarea>
            </div>
            <?php foreach (['A', 'B', 'C', 'D'] as $opt): ?>
            <div class="flex items-center gap-2">
                <label class="w-6 text-sm font-bold text-gray-500 flex-shrink-0"><?= $opt ?>)</label>
                <input type="text" name="option_<?= strtolower($opt) ?>" id="option<?= $opt ?>" required
                       class="flex-1 px-3 py-2.5 text-sm border border-gray-200 rounded-lg focus:outline-none focus:ring-2 focus:ring-brandOrange focus:border-transparent"
                       placeholder="Option <?= $opt ?>">
                <label class="flex items-center gap-1.5 text-xs text-gray-500 cursor-pointer whitespace-nowrap" title="Mark as correct answer">
                    <input type="radio" name="correct_answer" value="<?= $opt ?>" required class="w-4 h-4 text-brandOrange focus:ring-brandOrange">
                    Correct
                </label>
            </div>
            <?php endforeach; ?>
            <div>
                <label class="block text-sm font-medium text-gray-700 mb-1">Explanation <span class="text-gray-400 font-normal">(optional)</span></label>
                <textarea name="explanation" id="explanation" rows="2"
                          class="w-full px-3 py-2.5 text-sm border border-gray-200 rounded-lg focus:outline-none focus:ring-2 focus:ring-brandOrange focus:border-transparent"
                          placeholder="Shown to students after they submit..."></textarea>
            </div>
            <div class="flex items-center gap-3 pt-2">
                <button type="button" onclick="closeModal()" class="px-4 py-2.5 text-sm font-medium text-gray-600 hover:text-gray-800 transition">Cancel</button>
                <button type="submit" id="submitBtn" class="flex-1 px-4 py-2.5 bg-brandOrange text-white text-sm font-bold rounded-lg hover:bg-brandOrangeHover transition shadow-sm">Save Question</button>
            </div>
        </form>
    </div>
</div>

<script>
function openModal(id) {
    document.getElementById('questionForm').reset();
    document.getElementById('questionId').value = '';
    document.getElementById('modalTitle').textContent = 'Add Question';

    if (id) {
        document.getElementById('modalTitle').textContent = 'Edit Question';
        fetch('questions_ajax.php?action=get&id=' + id)
            .then(function(r) { return r.json(); })
            .then(function(d) {
                if (d.success) {
                    document.getElementById('questionId').value = d.data.id;
                    document.getElementById('questionText').value = d.data.question_text;
                    document.getElementById('explanation').value = d.data.explanation || '';
                    document.getElementById('optionA').value = d.data.option_a || '';
                    document.getElementById('optionB').value = d.data.option_b || '';
                    document.getElementById('optionC').value = d.data.option_c || '';
                    document.getElementById('optionD').value = d.data.option_d || '';
                    var radios = document.querySelectorAll('input[name="correct_answer"]');
                    radios.forEach(function(r) { if (r.value === d.data.correct_answer) r.checked = true; });
                }
            });
    }
    document.getElementById('questionModal').classList.remove('hidden');
    document.getElementById('questionModal').classList.add('flex');
}

function closeModal() {
    document.getElementById('questionModal').classList.add('hidden');
    document.getElementById('questionModal').classList.remove('flex');
}

document.getElementById('questionModal').addEventListener('click', function(e) {
    if (e.target === this) closeModal();
});

document.getElementById('questionForm').addEventListener('submit', function(e) {
    e.preventDefault();
    var id = document.getElementById('questionId').value;
    var action = id ? 'update' : 'create';
    var body = new URLSearchParams(new FormData(this));
    body.set('action', action);

    var btn = document.getElementById('submitBtn');
    btn.disabled = true; btn.textContent = 'Saving...';

    fetch('questions_ajax.php', {
        method: 'POST',
        headers: { 'Content-Type': 'application/x-www-form-urlencoded' },
        body: body.toString()
    })
    .then(function(r) { return r.json(); })
    .then(function(d) {
        btn.disabled = false; btn.textContent = 'Save Question';
        if (d.success) { closeModal(); location.reload(); }
        else alert(d.message || 'Something went wrong.');
    })
    .catch(function() { btn.disabled = false; btn.textContent = 'Save Question'; alert('Network error. Please try again.'); });
});

function deleteQuestion(id) {
    showConfirm('Are you sure you want to delete this question?', function() {
        fetch('questions_ajax.php', {
            method: 'POST',
            headers: { 'Content-Type': 'application/x-www-form-urlencoded' },
            body: new URLSearchParams({ action: 'delete', id: id })
        })
        .then(function(r) { return r.json(); })
        .then(function(d) { if (d.success) location.reload(); else window.alert(d.message || 'Delete failed.'); });
    }, { okText: 'Delete', title: 'Delete Question' });
}
</script>

<?php require_once 'includes/footer.php'; ?>