<?php require_once 'includes/header.php'; ?>
<?php require_once 'includes/sidebar.php'; ?>
<?php require_once '../config/db.php'; ?>

<?php
$total = $conn->query("SELECT COUNT(*) FROM users")->fetch_row()[0] ?? 0;
$new_month = $conn->query("SELECT COUNT(*) FROM users WHERE created_at >= DATE_SUB(CURDATE(), INTERVAL 1 MONTH)")->fetch_row()[0] ?? 0;
$male = $conn->query("SELECT COUNT(*) FROM users WHERE gender = 'Male'")->fetch_row()[0] ?? 0;
$female = $conn->query("SELECT COUNT(*) FROM users WHERE gender = 'Female'")->fetch_row()[0] ?? 0;
$limit = 10;
$page = max(1, intval($_GET['page'] ?? 1));
$offset = ($page - 1) * $limit;

$totalQuery = $conn->query("SELECT COUNT(*) FROM users");
$studentCount = $totalQuery->fetch_row()[0] ?? 0;
$totalPages = max(1, ceil($studentCount / $limit));

$result = $conn->query("
    SELECT u.id, u.name, u.email, u.phone, u.gender, u.created_at,
           COALESCE(enrolled.cnt, 0) AS enrolled_modules
    FROM users u
    LEFT JOIN (
        SELECT user_id, COUNT(*) AS cnt FROM enrollments WHERE status = 'confirmed' GROUP BY user_id
    ) enrolled ON u.id = enrolled.user_id
    ORDER BY u.created_at DESC
    LIMIT $offset, $limit
");
$students = $result ? $result->fetch_all(MYSQLI_ASSOC) : [];

$completedModulesPerUser = [];
if (!empty($students)) {
    $userIds = implode(',', array_column($students, 'id'));
    $compResult = $conn->query("
        SELECT e.user_id, m.name AS module_name, c.course_name
        FROM enrollments e
        JOIN modules m ON e.module_id = m.id
        JOIN courses c ON m.course_id = c.id
        LEFT JOIN lessons l ON m.id = l.module_id
        LEFT JOIN lesson_progress lp ON l.id = lp.lesson_id AND lp.user_id = e.user_id AND lp.completed = 1
        WHERE e.status = 'confirmed' AND e.user_id IN ($userIds)
        GROUP BY e.user_id, m.id, m.name, c.course_name
        HAVING COUNT(l.id) > 0 AND COUNT(lp.id) = COUNT(l.id)
        ORDER BY m.name
    ");
    if ($compResult) {
        while ($row = $compResult->fetch_assoc()) {
            $uid = $row['user_id'];
            if (!isset($completedModulesPerUser[$uid])) $completedModulesPerUser[$uid] = [];
            $completedModulesPerUser[$uid][] = $row;
        }
    }
}
?>

<div class="flex-1 flex flex-col overflow-hidden">
    <!-- <header class="h-16 bg-white border-b border-gray-200 flex items-center justify-between px-8 flex-shrink-0">
        <div>
            <h1 class="text-lg font-semibold text-gray-800">Students</h1>
            <p class="text-sm text-gray-500">Manage all registered students</p>
        </div>
        <div class="flex items-center gap-4">
            <span class="text-sm text-gray-500"><?php echo date('l, F j, Y'); ?></span>
            <?php require_once 'includes/admin_notif_icon.php'; ?>
            <a href="settings.php" class="w-9 h-9 rounded-full bg-gradient-to-br from-brandOrange to-orange-400 text-white flex items-center justify-center text-sm font-bold shadow-sm hover:opacity-90 transition">
                <?php echo strtoupper(substr($_SESSION['username'] ?? 'A', 0, 1)); ?>
            </a>
        </div>
    </header> -->

    <main class="flex-1 overflow-y-auto p-8">
        <div class="grid grid-cols-4 gap-6 mb-8">
            <div class="bg-white rounded-xl border border-gray-200 p-6 hover:shadow-lg transition-shadow duration-200">
                <div class="flex items-center justify-between mb-4">
                    <span class="text-sm font-medium text-gray-500">Total Students</span>
                    <span class="w-10 h-10 rounded-lg bg-orange-50 flex items-center justify-center">
                        <svg class="w-5 h-5 text-brandOrange" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4.354a4 4 0 110 5.292M15 21H3v-1a6 6 0 0112 0v1zm0 0h6v-1a6 6 0 00-9-5.197m13.5-9a2.5 2.5 0 11-5 0 2.5 2.5 0 015 0z"/></svg>
                    </span>
                </div>
                <p class="text-3xl font-bold text-gray-900"><?= $total ?></p>
                <div class="mt-2 flex items-center text-xs text-green-600 font-medium">
                    <svg class="w-3 h-3 mr-1" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 10l7-7m0 0l7 7m-7-7v18"/></svg>
                    All time
                </div>
            </div>

            <div class="bg-white rounded-xl border border-gray-200 p-6 hover:shadow-lg transition-shadow duration-200">
                <div class="flex items-center justify-between mb-4">
                    <span class="text-sm font-medium text-gray-500">New This Month</span>
                    <span class="w-10 h-10 rounded-lg bg-green-50 flex items-center justify-center">
                        <svg class="w-5 h-5 text-green-600" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M18 9v3m0 0v3m0-3h3m-3 0h-3m-2-5a4 4 0 11-8 0 4 4 0 018 0zM3 20a6 6 0 0112 0v1H3v-1z"/></svg>
                    </span>
                </div>
                <p class="text-3xl font-bold text-gray-900"><?= $new_month ?></p>
                <div class="mt-2 flex items-center text-xs text-green-600 font-medium">
                    <svg class="w-3 h-3 mr-1" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 10l7-7m0 0l7 7m-7-7v18"/></svg>
                    Recently joined
                </div>
            </div>

            <div class="bg-white rounded-xl border border-gray-200 p-6 hover:shadow-lg transition-shadow duration-200">
                <div class="flex items-center justify-between mb-4">
                    <span class="text-sm font-medium text-gray-500">Male</span>
                    <span class="w-10 h-10 rounded-lg bg-blue-50 flex items-center justify-center">
                        <svg class="w-5 h-5 text-blue-600" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z"/></svg>
                    </span>
                </div>
                <p class="text-3xl font-bold text-gray-900"><?= $male ?></p>
                <div class="mt-2 flex items-center text-xs text-gray-500 font-medium">
                    <?php $malePct = $total > 0 ? round($male / $total * 100) : 0; ?>
                    <span><?= $malePct ?>% of total</span>
                </div>
            </div>

            <div class="bg-white rounded-xl border border-gray-200 p-6 hover:shadow-lg transition-shadow duration-200">
                <div class="flex items-center justify-between mb-4">
                    <span class="text-sm font-medium text-gray-500">Female</span>
                    <span class="w-10 h-10 rounded-lg bg-pink-50 flex items-center justify-center">
                        <svg class="w-5 h-5 text-pink-600" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z"/></svg>
                    </span>
                </div>
                <p class="text-3xl font-bold text-gray-900"><?= $female ?></p>
                <div class="mt-2 flex items-center text-xs text-gray-500 font-medium">
                    <?php $femalePct = $total > 0 ? round($female / $total * 100) : 0; ?>
                    <span><?= $femalePct ?>% of total</span>
                </div>
            </div>
        </div>

        <div class="bg-white rounded-xl border border-gray-200 hover:shadow-lg transition-shadow duration-200">
            <div class="px-6 py-4 border-b border-gray-100 flex items-center justify-between">
                <div class="flex items-center gap-4">
                    <h3 class="font-semibold text-gray-800">All Students</h3>
                    <span class="text-xs font-medium text-gray-400 bg-gray-100 px-2.5 py-1 rounded-full"><?= $studentCount ?> registered</span>
                </div>
                <div class="relative">
                    <svg class="w-4 h-4 text-gray-400 absolute left-3 top-1/2 -translate-y-1/2" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z"/></svg>
                    <input type="text" id="searchInput" placeholder="Search students..." class="pl-9 pr-3 py-2 text-sm border border-orange-200 rounded-lg focus:outline-none focus:ring-2 focus:ring-brandOrange focus:border-transparent w-60">
                </div>
            </div>
            <div class="overflow-x-auto">
                <table class="w-full" id="studentsTable">
                    <thead class="bg-orange-100/50">
                        <tr class="text-left text-xs font-semibold text-gray-500 uppercase tracking-wider">
                            <th class="px-6 py-4">No.</th>
                            <th class="px-6 py-4">Student</th>
                            <th class="px-6 py-4">Email</th>
                            <th class="px-6 py-4">Phone</th>
                            <th class="px-6 py-4">Gender</th>
                            <th class="px-6 py-4">Joined</th>
                            <th class="px-6 py-4 text-center">Enrolled</th>
                            <th class="px-6 py-4 text-center">Completed</th>
                            <th class="px-6 py-4 text-center">Actions</th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-gray-100">
                        <?php if ($studentCount > 0): ?>
                            <?php $counter = $offset + 1; foreach ($students as $row): ?>
                            <tr class="hover:bg-gray-50 transition-colors student-row">
                                <td class="px-6 py-4 text-sm text-gray-500"><?= $counter++ ?></td>
                                <td class="px-6 py-4">
                                    <div class="flex items-center gap-3">
                                        <span class="w-9 h-9 rounded-full bg-gradient-to-br from-orange-100 to-orange-200 text-brandOrange flex items-center justify-center text-sm font-bold"><?= strtoupper(substr($row['name'], 0, 1)) ?></span>
                                        <div>
                                            <p class="text-sm font-medium text-gray-700"><?= htmlspecialchars($row['name']) ?></p>
                                        </div>
                                    </div>
                                </td>
                                <td class="px-6 py-4 text-sm text-gray-600"><?= htmlspecialchars($row['email']) ?></td>
                                <td class="px-6 py-4 text-sm text-gray-600"><?= htmlspecialchars($row['phone'] ?? '-') ?></td>
                                <td class="px-6 py-4">
                                    <?php if ($row['gender'] === 'Male'): ?>
                                        <span class="inline-flex items-center gap-1 px-2.5 py-1 rounded-full text-xs font-medium bg-blue-50 text-blue-700">
                                            <span class="w-1.5 h-1.5 rounded-full bg-blue-500"></span>
                                            Male
                                        </span>
                                    <?php elseif ($row['gender'] === 'Female'): ?>
                                        <span class="inline-flex items-center gap-1 px-2.5 py-1 rounded-full text-xs font-medium bg-pink-50 text-pink-700">
                                            <span class="w-1.5 h-1.5 rounded-full bg-pink-500"></span>
                                            Female
                                        </span>
                                    <?php else: ?>
                                        <span class="inline-flex items-center gap-1 px-2.5 py-1 rounded-full text-xs font-medium bg-gray-50 text-gray-500">
                                            <span class="w-1.5 h-1.5 rounded-full bg-gray-400"></span>
                                            -
                                        </span>
                                    <?php endif; ?>
                                </td>
                                <td class="px-6 py-4 text-sm text-gray-500"><?= date('M j, Y', strtotime($row['created_at'])) ?></td>
                                <td class="px-6 py-4 text-center">
                                    <span class="text-sm font-medium text-gray-700"><?= $row['enrolled_modules'] ?></span>
                                </td>
                                <td class="px-6 py-4">
                                    <?php
                                    $uid = $row['id'];
                                    $userComps = $completedModulesPerUser[$uid] ?? [];
                                    $compCount = count($userComps);
                                    ?>
                                    <?php if ($compCount > 0): ?>
                                        <span class="inline-flex items-center gap-1 px-2.5 py-1 rounded-full text-xs font-medium bg-green-50 text-green-700">
                                            <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"/></svg>
                                            <?= $compCount ?>
                                        </span>
                                    <?php else: ?>
                                        <span class="text-xs text-gray-400">-</span>
                                    <?php endif; ?>
                                </td>
                                <td class="px-6 py-4 text-center">
                                    <div class="flex items-center justify-center gap-2">
                                        <button onclick="viewStudent(<?= $row['id'] ?>)" class="px-3 py-1.5 text-xs font-semibold rounded-lg bg-blue-50 text-blue-700 border border-blue-200 hover:bg-blue-100 transition">
                                            View
                                        </button>
                                        <button onclick="deleteStudent(<?= $row['id'] ?>)" class="px-3 py-1.5 text-xs font-semibold rounded-lg bg-red-50 text-red-700 border border-red-200 hover:bg-red-100 transition">
                                            Delete
                                        </button>
                                    </div>
                                </td>
                            </tr>
                            <?php endforeach; ?>
                        <?php else: ?>
                            <tr>
                                <td colspan="9" class="px-6 py-12 text-center">
                                    <svg class="w-12 h-12 text-gray-300 mx-auto mb-3" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M12 4.354a4 4 0 110 5.292M15 21H3v-1a6 6 0 0112 0v1zm0 0h6v-1a6 6 0 00-9-5.197m13.5-9a2.5 2.5 0 11-5 0 2.5 2.5 0 015 0z"/></svg>
                                    <p class="text-sm text-gray-400">No students found</p>
                                </td>
                            </tr>
                        <?php endif; ?>
                    </tbody>
                </table>
            </div>
            <?php if ($totalPages > 1): ?>
            <div class="px-6 py-4 border-t border-gray-100 flex items-center justify-between">
                <p class="text-sm text-gray-500">Page <?= $page ?> of <?= $totalPages ?> (<?= $studentCount ?> total)</p>
                <div class="flex items-center gap-1">
                    <a href="?page=1" class="px-3 py-1.5 text-sm rounded-lg border border-gray-200 text-gray-600 hover:bg-gray-50 transition <?= $page <= 1 ? 'pointer-events-none opacity-40' : '' ?>">First</a>
                    <?php for ($i = 1; $i <= $totalPages; $i++): ?>
                        <a href="?page=<?= $i ?>" class="px-3 py-1.5 text-sm rounded-lg border <?= $i === $page ? 'bg-brandOrange text-white border-brandOrange' : 'border-gray-200 text-gray-600 hover:bg-gray-50' ?> transition"><?= $i ?></a>
                    <?php endfor; ?>
                    <a href="?page=<?= $totalPages ?>" class="px-3 py-1.5 text-sm rounded-lg border border-gray-200 text-gray-600 hover:bg-gray-50 transition <?= $page >= $totalPages ? 'pointer-events-none opacity-40' : '' ?>">Last</a>
                </div>
            </div>
            <?php endif; ?>
        </div>
    </main>
</div>

<div id="studentModal" class="hidden fixed inset-0 bg-black/50 flex items-center justify-center z-50">
    <div class="bg-white rounded-xl w-full max-w-lg mx-4 shadow-xl">
        <div class="flex items-center justify-between px-6 py-4 border-b border-gray-200">
            <h3 class="text-lg font-bold text-gray-800">Student Details</h3>
            <button onclick="closeModal()" class="text-gray-400 hover:text-gray-600 transition">
                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"/></svg>
            </button>
        </div>
        <div class="p-6 space-y-4" id="modalBody"></div>
    </div>
</div>

<script>
const students = <?= json_encode($students) ?>;

function viewStudent(id) {
    var modalBody = document.getElementById('modalBody');
    modalBody.innerHTML = '<div class="text-center py-8 text-gray-400">Loading...</div>';
    document.getElementById('studentModal').classList.remove('hidden');

    var formData = new FormData();
    formData.append('action', 'get_details');
    formData.append('id', id);
    fetch('students_ajax.php', { method: 'POST', body: formData })
        .then(function(r) { return r.json(); })
        .then(function(res) {
            if (!res.success) {
                modalBody.innerHTML = '<p class="text-red-500 text-center py-8">Failed to load details.</p>';
                return;
            }
            var d = res.data;
            var u = d.user;
            var enrolled = d.enrollments || [];
            var completedMods = enrolled.filter(function(e) {
                return e.total_lessons > 0 && parseInt(e.completed_lessons) === parseInt(e.total_lessons);
            });

            function renderModuleDetails(list, emptyMsg) {
                if (list.length === 0) return '<p class="text-gray-400 text-sm">' + emptyMsg + '</p>';
                return list.map(function(e) {
                    var pct = e.total_lessons > 0 ? Math.round((e.completed_lessons / e.total_lessons) * 100) : 0;
                    return '<div class="border border-green-100 rounded-lg p-3 flex items-center justify-between bg-green-50/30">' +
                        '<div><p class="text-sm font-medium text-gray-700">' + e.module_name + '</p>' +
                        '<p class="text-xs text-gray-400">' + e.course_name + ' &middot; ' + e.enroll_date + '</p></div>' +
                        '<div class="flex items-center gap-2 flex-shrink-0">' +
                        '<span class="text-xs text-green-600 font-semibold">' + e.completed_lessons + '/' + e.total_lessons + '</span>' +
                        '<span class="inline-flex items-center px-2 py-0.5 rounded-full text-[10px] font-medium border bg-green-50 text-green-700 border-green-200">Completed</span>' +
                        '</div></div>';
                }).join('');
            }

            modalBody.innerHTML =
                '<div class="flex items-center gap-4 pb-4 border-b border-gray-100">' +
                    '<span class="w-14 h-14 rounded-full bg-gradient-to-br from-orange-100 to-orange-200 text-brandOrange flex items-center justify-center text-xl font-bold">' + u.name.charAt(0).toUpperCase() + '</span>' +
                    '<div><p class="text-lg font-bold text-gray-800">' + u.name + '</p><p class="text-sm text-gray-400">ID: #' + u.id + '</p></div>' +
                '</div>' +
                '<div class="grid grid-cols-3 gap-4 text-sm">' +
                    '<div><span class="text-gray-400 block">Email</span><span class="font-medium text-gray-700">' + u.email + '</span></div>' +
                    '<div><span class="text-gray-400 block">Phone</span><span class="font-medium text-gray-700">' + (u.phone || '-') + '</span></div>' +
                    '<div><span class="text-gray-400 block">Gender</span><span class="font-medium text-gray-700">' + (u.gender || '-') + '</span></div>' +
                    '<div><span class="text-gray-400 block">Joined</span><span class="font-medium text-gray-700">' + new Date(u.created_at).toLocaleDateString('en-US', { year: 'numeric', month: 'short', day: 'numeric' }) + '</span></div>' +
                    '<div><span class="text-gray-400 block">Enrolled</span><span class="font-medium text-gray-700">' + enrolled.length + '</span></div>' +
                    '<div><span class="text-gray-400 block">Completed</span><span class="font-medium text-gray-700">' + completedMods.length + '</span></div>' +
                '</div>' +
                '<div class="pt-4 border-t border-gray-100">' +
                    '<h4 class="text-sm font-semibold text-gray-500 uppercase tracking-wider mb-3">Completed Modules (' + completedMods.length + ')</h4>' +
                    '<div class="space-y-2">' + renderModuleDetails(completedMods, 'No completed modules yet.') + '</div>' +
                '</div>';
        })
        .catch(function() {
            modalBody.innerHTML = '<p class="text-red-500 text-center py-8">Error loading details.</p>';
        });
}

function closeModal() {
    document.getElementById('studentModal').classList.add('hidden');
}

function deleteStudent(id) {
    if (!confirm('Are you sure you want to delete this student? This will also remove all their enrollments, progress, and reviews.')) return;
    var formData = new FormData();
    formData.append('action', 'delete');
    formData.append('id', id);
    fetch('students_ajax.php', { method: 'POST', body: formData })
        .then(function(r) { return r.json(); })
        .then(function(data) {
            if (data.success) {
                location.reload();
            } else {
                alert(data.message);
            }
        });
}

document.getElementById('studentModal')?.addEventListener('click', function(e) {
    if (e.target === this) closeModal();
});

document.getElementById('searchInput').addEventListener('keyup', function() {
    const query = this.value.toLowerCase();
    const rows = document.querySelectorAll('.student-row');
    
    rows.forEach(row => {
        // Find the cell that contains the student name (the first <td>)
        const nameCell = row.querySelector('td:first-child');
        // Get the name text specifically
        const nameText = nameCell.textContent.toLowerCase();
        
        // Hide/Show based ONLY on nameText match
        row.style.display = nameText.includes(query) ? '' : 'none';
    });
});
</script>

<?php require_once 'includes/footer.php'; ?>