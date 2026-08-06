<?php require_once 'includes/header.php'; ?>
<?php require_once 'includes/sidebar.php'; ?>
<?php require_once '../config/db.php'; ?>

<?php
$limit = 10;
$page = max(1, intval($_GET['page'] ?? 1));
$offset = ($page - 1) * $limit;

$total = $conn->query("SELECT COUNT(*) FROM quizzes")->fetch_row()[0] ?? 0;
$totalPages = max(1, ceil($total / $limit));
$result = $conn->query("
    SELECT q.id, q.quiz_title, q.module_id, q.passing_score, q.question_limit,
           q.time_limit, q.random_questions, q.random_answers, q.status, q.created_at,
           m.name AS module_name, c.course_name,
           (SELECT COUNT(*) FROM quiz_questions WHERE quiz_id = q.id) AS question_count,
           (SELECT COUNT(*) FROM quiz_results WHERE quiz_id = q.id) AS attempt_count
    FROM quizzes q
    JOIN modules m ON q.module_id = m.id
    JOIN courses c ON m.course_id = c.id
    ORDER BY q.created_at DESC
    LIMIT $offset, $limit
");
$courses = $conn->query("SELECT id, course_name FROM courses ORDER BY course_name ASC");
?>

<div class="flex-1 flex flex-col overflow-hidden">
    <main class="flex-1 overflow-y-auto p-4 sm:p-6 lg:p-8">
        <div class="bg-white rounded-xl border border-gray-200 hover:shadow-lg transition-shadow duration-200">
            <div class="px-6 py-4 border-b border-gray-100 flex items-center justify-between">
                <div class="flex items-center gap-4">
                    <h3 class="font-semibold text-gray-800">All Quizzes</h3>
                    <span class="text-xs font-medium text-gray-400 bg-gray-100 px-2.5 py-1 rounded-full"><?= $total ?> total</span>
                </div>
                <div class="flex items-center gap-3">
                    <div class="relative">
                        <svg class="w-4 h-4 text-gray-400 absolute left-3 top-1/2 -translate-y-1/2" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z"/></svg>
                        <input type="text" id="searchInput" placeholder="Search quizzes..." class="pl-9 pr-3 py-2 text-sm border border-orange-200 rounded-lg focus:outline-none focus:ring-2 focus:ring-brandOrange focus:border-transparent w-60">
                    </div>
                    <button onclick="openModal()" class="inline-flex items-center gap-1.5 px-4 py-2 bg-brandOrange text-white text-sm font-semibold rounded-lg hover:bg-brandOrangeHover transition shadow-sm">
                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4"/></svg>
                        Add Quiz
                    </button>
                </div>
            </div>
            <div class="overflow-x-auto">
                <table class="w-full" id="quizzesTable">
                    <thead class="bg-orange-100/50">
                        <tr class="text-left text-xs font-semibold text-gray-500 uppercase tracking-wider">
                            <th class="px-6 py-4">No.</th>
                            <th class="px-6 py-4">Quiz</th>
                            <th class="px-6 py-4">Module</th>
                            <th class="px-6 py-4 text-center">Questions</th>
                            <th class="px-6 py-4 text-center">Attempts</th>
                            <th class="px-6 py-4 text-center">Passing</th>
                            <th class="px-6 py-4 text-center">Status</th>
                            <th class="px-6 py-4 text-center">Actions</th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-gray-100">
                        <?php if ($result && $result->num_rows > 0): ?>
                            <?php $counter = $offset + 1; while ($row = $result->fetch_assoc()): ?>
                            <tr class="hover:bg-gray-50 transition-colors quiz-row" data-id="<?= $row['id'] ?>" data-status="<?= $row['status'] ?>">
                                <td class="px-6 py-4 text-sm text-gray-500"><?= $counter++ ?></td>
                                <td class="px-6 py-4">
                                    <div class="flex items-center gap-3">
                                        <span class="w-9 h-9 rounded-lg bg-gradient-to-br from-pink-100 to-pink-200 text-pink-600 flex items-center justify-center">
                                            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"/></svg>
                                        </span>
                                        <div>
                                            <p class="text-sm font-medium text-gray-700"><?= htmlspecialchars($row['quiz_title']) ?></p>
                                            <p class="text-[11px] text-gray-400">Limit <?= $row['question_limit'] ?>/attempt<?= $row['time_limit'] > 0 ? ' &middot; ' . $row['time_limit'] . ' min' : '' ?></p>
                                        </div>
                                    </div>
                                </td>
                                <td class="px-6 py-4">
                                    <p class="text-sm font-medium text-gray-700"><?= htmlspecialchars($row['module_name']) ?></p>
                                    <p class="text-xs text-gray-400"><?= htmlspecialchars($row['course_name']) ?></p>
                                </td>
                                <td class="px-6 py-4 text-center">
                                    <span class="inline-flex items-center px-2.5 py-1 text-xs font-medium rounded-full <?= $row['question_count'] > 0 ? 'bg-green-100 text-green-700' : 'bg-gray-100 text-gray-500' ?>"><?= $row['question_count'] ?></span>
                                </td>
                                <td class="px-6 py-4 text-center text-sm text-gray-500"><?= (int)$row['attempt_count'] ?></td>
                                <td class="px-6 py-4 text-center">
                                    <span class="inline-flex items-center px-2.5 py-1 text-xs font-medium rounded-full bg-blue-100 text-blue-700"><?= (int)$row['passing_score'] ?>%</span>
                                </td>
                                <td class="px-6 py-4 text-center">
                                    <button onclick="toggleStatus(<?= $row['id'] ?>, '<?= $row['status'] === 'active' ? 'inactive' : 'active' ?>')"
                                            class="inline-flex items-center px-2.5 py-1 text-xs font-medium rounded-full transition cursor-pointer <?= $row['status'] === 'active' ? 'bg-green-100 text-green-700 hover:bg-green-200' : 'bg-gray-200 text-gray-600 hover:bg-gray-300' ?>">
                                        <?= $row['status'] === 'active' ? 'Active' : 'Inactive' ?>
                                    </button>
                                </td>
                                <td class="px-6 py-4 text-center">
                                    <div class="flex items-center justify-center gap-2">
                                        <a href="questions.php?quiz_id=<?= $row['id'] ?>"
                                           class="p-2 text-blue-600 hover:text-blue-700 hover:bg-blue-50 rounded-lg transition"
                                           title="Manage Questions">
                                            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"/></svg>
                                        </a>
                                        <button onclick="openModal(<?= $row['id'] ?>)" class="p-2 text-green-600 hover:text-green-700 hover:bg-green-100 rounded-lg transition" title="Edit">
                                            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z"/></svg>
                                        </button>
                                        <button onclick="deleteQuiz(<?= $row['id'] ?>)" class="p-2 text-red-600 hover:text-red-700 hover:bg-red-100 rounded-lg transition" title="Delete">
                                            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"/></svg>
                                        </button>
                                    </div>
                                </td>
                            </tr>
                            <?php endwhile; ?>
                        <?php else: ?>
                            <tr>
                                <td colspan="8" class="px-6 py-12 text-center">
                                    <svg class="w-12 h-12 text-gray-300 mx-auto mb-3" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"/></svg>
                                    <p class="text-sm text-gray-400 mb-3">No quizzes yet</p>
                                    <button onclick="openModal()" class="inline-flex items-center gap-1.5 px-4 py-2 bg-brandOrange text-white text-sm font-semibold rounded-lg hover:bg-brandOrangeHover transition shadow-sm">
                                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4"/></svg>
                                        Add First Quiz
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
                    <a href="?page=1" class="px-3 py-1.5 text-sm rounded-lg border border-gray-200 text-gray-600 hover:bg-gray-50 transition <?= $page <= 1 ? 'pointer-events-none opacity-40' : '' ?>">First</a>
                    <a href="?page=<?= max(1, $page - 1) ?>" class="px-3 py-1.5 text-sm rounded-lg border border-gray-200 text-gray-600 hover:bg-gray-50 transition <?= $page <= 1 ? 'pointer-events-none opacity-40' : '' ?>">&lt;</a>
                    <form method="GET" class="flex items-center gap-1" onsubmit="var v=parseInt(this.querySelector('input').value);if(v>0&&v<=<?= $totalPages ?>)location.href='?page='+v;return false;">
                        <label class="text-sm text-gray-500">Page</label>
                        <input type="number" min="1" max="<?= $totalPages ?>" value="<?= $page ?>" class="w-16 px-2 py-1.5 text-sm border border-gray-200 rounded-lg focus:outline-none focus:ring-2 focus:ring-brandOrange">
                    </form>
                    <a href="?page=<?= min($totalPages, $page + 1) ?>" class="px-3 py-1.5 text-sm rounded-lg border border-gray-200 text-gray-600 hover:bg-gray-50 transition <?= $page >= $totalPages ? 'pointer-events-none opacity-40' : '' ?>">&gt;</a>
                    <a href="?page=<?= $totalPages ?>" class="px-3 py-1.5 text-sm rounded-lg border border-gray-200 text-gray-600 hover:bg-gray-50 transition <?= $page >= $totalPages ? 'pointer-events-none opacity-40' : '' ?>">Last</a>
                </div>
            </div>
            <?php endif; ?>
        </div>
    </main>
</div>

<!-- Modal -->
<div id="quizModal" class="fixed inset-0 z-50 hidden items-center justify-center bg-black/50 backdrop-blur-sm">
    <div class="bg-white rounded-2xl p-6 w-full max-w-lg mx-4 shadow-2xl max-h-[90vh] overflow-y-auto">
        <div class="flex items-center justify-between mb-6">
            <h3 class="text-lg font-bold text-gray-800" id="modalTitle">Add Quiz</h3>
            <button onclick="closeModal()" class="text-gray-400 hover:text-gray-600 transition">
                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"/></svg>
            </button>
        </div>
        <form id="quizForm" class="space-y-4">
            <input type="hidden" name="id" id="quizId">
            <div>
                <label class="block text-sm font-medium text-gray-700 mb-1">Course</label>
                <select name="course_id" id="courseSelect" required
                        onchange="loadModules(this.value)"
                        class="w-full px-3 py-2.5 text-sm border border-gray-200 rounded-lg focus:outline-none focus:ring-2 focus:ring-brandOrange focus:border-transparent">
                    <option value="">Select course</option>
                    <?php while ($c = $courses->fetch_assoc()): ?>
                        <option value="<?= $c['id'] ?>"><?= htmlspecialchars($c['course_name']) ?></option>
                    <?php endwhile; ?>
                </select>
            </div>
            <div>
                <label class="block text-sm font-medium text-gray-700 mb-1">Module</label>
                <select name="module_id" id="moduleSelect" required
                        class="w-full px-3 py-2.5 text-sm border border-gray-200 rounded-lg focus:outline-none focus:ring-2 focus:ring-brandOrange focus:border-transparent">
                    <option value="">Select course first</option>
                </select>
            </div>
            <div>
                <label class="block text-sm font-medium text-gray-700 mb-1">Quiz Title</label>
                <input type="text" name="quiz_title" id="quizTitle" required
                       class="w-full px-3 py-2.5 text-sm border border-gray-200 rounded-lg focus:outline-none focus:ring-2 focus:ring-brandOrange focus:border-transparent"
                       placeholder="e.g. Module Final Quiz">
            </div>
            <div class="grid grid-cols-3 gap-3">
                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-1">Passing %</label>
                    <input type="number" name="passing_score" id="passingScore" min="1" max="100" value="70"
                           class="w-full px-3 py-2.5 text-sm border border-gray-200 rounded-lg focus:outline-none focus:ring-2 focus:ring-brandOrange focus:border-transparent">
                </div>
                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-1">Questions/attempt</label>
                    <input type="number" name="question_limit" id="questionLimit" min="1" value="100"
                           class="w-full px-3 py-2.5 text-sm border border-gray-200 rounded-lg focus:outline-none focus:ring-2 focus:ring-brandOrange focus:border-transparent">
                </div>
                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-1">Time limit (min)</label>
                    <input type="number" name="time_limit" id="timeLimit" min="0" value="0"
                           title="0 = no time limit"
                           class="w-full px-3 py-2.5 text-sm border border-gray-200 rounded-lg focus:outline-none focus:ring-2 focus:ring-brandOrange focus:border-transparent">
                </div>
            </div>
            <div class="grid grid-cols-2 gap-3 bg-gray-50 rounded-lg p-3">
                <label class="flex items-center gap-2 text-sm text-gray-700 cursor-pointer">
                    <input type="checkbox" name="random_questions" id="randomQuestions" checked class="w-4 h-4 text-brandOrange focus:ring-brandOrange rounded">
                    <span>Random questions per attempt</span>
                </label>
                <label class="flex items-center gap-2 text-sm text-gray-700 cursor-pointer">
                    <input type="checkbox" name="random_answers" id="randomAnswers" checked class="w-4 h-4 text-brandOrange focus:ring-brandOrange rounded">
                    <span>Shuffle answer order</span>
                </label>
            </div>
            <div class="flex items-center gap-3 pt-2">
                <button type="button" onclick="closeModal()" class="px-4 py-2.5 text-sm font-medium text-gray-600 hover:text-gray-800 transition">Cancel</button>
                <button type="submit" id="submitBtn" class="flex-1 px-4 py-2.5 bg-brandOrange text-white text-sm font-bold rounded-lg hover:bg-brandOrangeHover transition shadow-sm">Save Quiz</button>
            </div>
        </form>
    </div>
</div>

<script>
function loadModules(courseId, selected) {
    var sel = document.getElementById('moduleSelect');
    sel.innerHTML = '<option value="">Loading...</option>';
    fetch('quizzes_ajax.php?action=get_modules&course_id=' + courseId)
        .then(function(r) { return r.json(); })
        .then(function(d) {
            if (d.success) {
                sel.innerHTML = '<option value="">Select module</option>';
                d.data.forEach(function(m) {
                    var opt = document.createElement('option');
                    opt.value = m.id;
                    opt.textContent = m.name;
                    if (selected && m.id == selected) opt.selected = true;
                    sel.appendChild(opt);
                });
            } else {
                sel.innerHTML = '<option value="">No modules found</option>';
            }
        });
}

function openModal(id) {
    document.getElementById('quizForm').reset();
    document.getElementById('quizId').value = '';
    document.getElementById('modalTitle').textContent = 'Add Quiz';
    document.getElementById('moduleSelect').innerHTML = '<option value="">Select course first</option>';
    document.getElementById('courseSelect').value = '';
    document.getElementById('randomQuestions').checked = true;
    document.getElementById('randomAnswers').checked = true;
    document.getElementById('passingScore').value = 70;
    document.getElementById('questionLimit').value = 100;
    document.getElementById('timeLimit').value = 0;

    if (id) {
        document.getElementById('modalTitle').textContent = 'Edit Quiz';
        fetch('quizzes_ajax.php?action=get&id=' + id)
            .then(function(r) { return r.json(); })
            .then(function(d) {
                if (d.success) {
                    document.getElementById('quizId').value = d.data.id;
                    document.getElementById('quizTitle').value = d.data.quiz_title;
                    document.getElementById('passingScore').value = d.data.passing_score;
                    document.getElementById('questionLimit').value = d.data.question_limit;
                    document.getElementById('timeLimit').value = d.data.time_limit;
                    document.getElementById('randomQuestions').checked = d.data.random_questions == 1;
                    document.getElementById('randomAnswers').checked = d.data.random_answers == 1;
                    document.getElementById('courseSelect').value = d.data.course_id;
                    loadModules(d.data.course_id, d.data.module_id);
                }
            });
    }
    document.getElementById('quizModal').classList.remove('hidden');
    document.getElementById('quizModal').classList.add('flex');
}

function closeModal() {
    document.getElementById('quizModal').classList.add('hidden');
    document.getElementById('quizModal').classList.remove('flex');
}

document.getElementById('quizModal').addEventListener('click', function(e) {
    if (e.target === this) closeModal();
});

document.getElementById('quizForm').addEventListener('submit', function(e) {
    e.preventDefault();
    var id = document.getElementById('quizId').value;
    var action = id ? 'update' : 'create';
    var body = new URLSearchParams(new FormData(this));
    body.set('action', action);

    var btn = document.getElementById('submitBtn');
    btn.disabled = true; btn.textContent = 'Saving...';

    fetch('quizzes_ajax.php', {
        method: 'POST',
        headers: { 'Content-Type': 'application/x-www-form-urlencoded' },
        body: body.toString()
    })
    .then(function(r) { return r.json(); })
    .then(function(d) {
        btn.disabled = false; btn.textContent = 'Save Quiz';
        if (d.success) { closeModal(); location.reload(); }
        else alert(d.message || 'Something went wrong.');
    })
    .catch(function() { btn.disabled = false; btn.textContent = 'Save Quiz'; alert('Network error. Please try again.'); });
});

function deleteQuiz(id) {
    if (!confirm('Are you sure you want to delete this quiz, all of its questions, attempts and results?')) return;
    fetch('quizzes_ajax.php', {
        method: 'POST',
        headers: { 'Content-Type': 'application/x-www-form-urlencoded' },
        body: new URLSearchParams({ action: 'delete', id: id })
    })
    .then(function(r) { return r.json(); })
    .then(function(d) { if (d.success) location.reload(); else alert(d.message || 'Delete failed.'); });
}

function toggleStatus(id, status) {
    fetch('quizzes_ajax.php', {
        method: 'POST',
        headers: { 'Content-Type': 'application/x-www-form-urlencoded' },
        body: new URLSearchParams({ action: 'toggle_status', id: id, status: status })
    })
    .then(function(r) { return r.json(); })
    .then(function(d) { if (d.success) location.reload(); else alert(d.message || 'Update failed.'); });
}

document.getElementById('searchInput').addEventListener('keyup', function() {
    var q = this.value.trim();
    if (!q) { document.querySelectorAll('.quiz-row').forEach(function(r) { r.style.display = ''; }); return; }
    var words = q.toLowerCase().split(/\s+/);
    document.querySelectorAll('.quiz-row').forEach(function(r) {
        var text = r.textContent.toLowerCase();
        var match = words.some(function(w) { return text.indexOf(w) !== -1; });
        r.style.display = match ? '' : 'none';
    });
});
</script>

<?php require_once 'includes/footer.php'; ?>