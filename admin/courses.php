<?php require_once 'includes/header.php'; ?>
<?php require_once 'includes/sidebar.php'; ?>
<?php require_once '../config/db.php'; ?>

<?php
$total = $conn->query("SELECT COUNT(*) FROM courses")->fetch_row()[0] ?? 0;
$result = $conn->query("SELECT id, course_name, instructor_name, level, description, created_at, updated_at FROM courses ORDER BY created_at DESC");
?>

<div class="flex-1 flex flex-col overflow-hidden">
    <header class="h-16 bg-white border-b border-gray-200 flex items-center justify-between px-8 flex-shrink-0">
        <div>
            <h1 class="text-lg font-semibold text-gray-800">Courses</h1>
            <p class="text-sm text-gray-500">Manage all courses</p>
        </div>
        <div class="flex items-center gap-4">
            <span class="text-sm text-gray-500"><?php echo date('l, F j, Y'); ?></span>
            <div class="w-9 h-9 rounded-full bg-gradient-to-br from-brandOrange to-orange-400 text-white flex items-center justify-center text-sm font-bold shadow-sm">
                <?php echo strtoupper(substr($_SESSION['username'] ?? 'A', 0, 1)); ?>
            </div>
        </div>
    </header>

    <main class="flex-1 overflow-y-auto p-8">
        <div class="bg-white rounded-xl border border-gray-200 hover:shadow-lg transition-shadow duration-200">
            <div class="px-6 py-4 border-b border-gray-100 flex items-center justify-between">
                <div class="flex items-center gap-4">
                    <h3 class="font-semibold text-gray-800">All Courses</h3>
                    <span class="text-xs font-medium text-gray-400 bg-gray-100 px-2.5 py-1 rounded-full"><?= $total ?> total</span>
                </div>
                <div class="flex items-center gap-3">
                    <div class="relative">
                        <svg class="w-4 h-4 text-gray-400 absolute left-3 top-1/2 -translate-y-1/2" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z"/></svg>
                        <input type="text" id="searchInput" placeholder="Search courses..." class="pl-9 pr-3 py-2 text-sm border border-gray-200 rounded-lg focus:outline-none focus:ring-2 focus:ring-brandOrange focus:border-transparent w-60">
                    </div>
                    <button onclick="openModal()" class="inline-flex items-center gap-1.5 px-4 py-2 bg-brandOrange text-white text-sm font-semibold rounded-lg hover:bg-brandOrangeHover transition shadow-sm">
                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4"/></svg>
                        Add Course
                    </button>
                </div>
            </div>
            <div class="overflow-x-auto">
                <table class="w-full" id="coursesTable">
                    <thead>
                        <tr class="text-left text-xs font-semibold text-gray-400 uppercase tracking-wider">
                            <th class="px-6 py-4">Course</th>
                            <th class="px-6 py-4">Instructor</th>
                            <th class="px-6 py-4">Level</th>
                            <th class="px-6 py-4">Description</th>
                            <th class="px-6 py-4">Created</th>
                            <th class="px-6 py-4 text-center">Actions</th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-gray-100">
                        <?php if ($result && $result->num_rows > 0): ?>
                            <?php while ($row = $result->fetch_assoc()): ?>
                            <tr class="hover:bg-gray-50 transition-colors course-row" data-id="<?= $row['id'] ?>">
                                <td class="px-6 py-4">
                                    <div class="flex items-center gap-3">
                                        <span class="w-9 h-9 rounded-lg bg-gradient-to-br from-orange-100 to-orange-200 text-brandOrange flex items-center justify-center text-sm font-bold">
                                            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 6.253v13m0-13C10.832 5.477 9.246 5 7.5 5S4.168 5.477 3 6.253v13C4.168 18.477 5.754 18 7.5 18s3.332.477 4.5 1.253m0-13C13.168 5.477 14.754 5 16.5 5c1.747 0 3.332.477 4.5 1.253v13C19.832 18.477 18.247 18 16.5 18c-1.746 0-3.332.477-4.5 1.253"/></svg>
                                        </span>
                                        <span class="text-sm font-medium text-gray-700"><?= htmlspecialchars($row['course_name']) ?></span>
                                    </div>
                                </td>
                                <td class="px-6 py-4 text-sm text-gray-600"><?= htmlspecialchars($row['instructor_name'] ?? '-') ?></td>
                                <td class="px-6 py-4">
                                    <?php if ($row['level'] === 'Beginner'): ?>
                                        <span class="inline-flex items-center gap-1 px-2.5 py-1 rounded-full text-xs font-medium bg-green-50 text-green-700"><span class="w-1.5 h-1.5 rounded-full bg-green-500"></span>Beginner</span>
                                    <?php elseif ($row['level'] === 'Intermediate'): ?>
                                        <span class="inline-flex items-center gap-1 px-2.5 py-1 rounded-full text-xs font-medium bg-yellow-50 text-yellow-700"><span class="w-1.5 h-1.5 rounded-full bg-yellow-500"></span>Intermediate</span>
                                    <?php elseif ($row['level'] === 'Advanced'): ?>
                                        <span class="inline-flex items-center gap-1 px-2.5 py-1 rounded-full text-xs font-medium bg-red-50 text-red-700"><span class="w-1.5 h-1.5 rounded-full bg-red-500"></span>Advanced</span>
                                    <?php else: ?>
                                        <span class="inline-flex items-center gap-1 px-2.5 py-1 rounded-full text-xs font-medium bg-gray-50 text-gray-500"><span class="w-1.5 h-1.5 rounded-full bg-gray-400"></span><?= htmlspecialchars($row['level'] ?? '-') ?></span>
                                    <?php endif; ?>
                                </td>
                                <td class="px-6 py-4 text-sm text-gray-500 max-w-xs truncate"><?= htmlspecialchars($row['description'] ?? '-') ?></td>
                                <td class="px-6 py-4 text-sm text-gray-500"><?= date('M j, Y', strtotime($row['created_at'])) ?></td>
                                <td class="px-6 py-4 text-center">
                                    <div class="flex items-center justify-center gap-2">
                                        <button onclick="openModal(<?= $row['id'] ?>)" class="p-2 text-gray-400 hover:text-brandOrange hover:bg-orange-50 rounded-lg transition" title="Edit">
                                            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z"/></svg>
                                        </button>
                                        <button onclick="deleteCourse(<?= $row['id'] ?>)" class="p-2 text-gray-400 hover:text-red-600 hover:bg-red-50 rounded-lg transition" title="Delete">
                                            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"/></svg>
                                        </button>
                                    </div>
                                </td>
                            </tr>
                            <?php endwhile; ?>
                        <?php else: ?>
                            <tr>
                                <td colspan="6" class="px-6 py-12 text-center">
                                    <svg class="w-12 h-12 text-gray-300 mx-auto mb-3" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M12 6.253v13m0-13C10.832 5.477 9.246 5 7.5 5S4.168 5.477 3 6.253v13C4.168 18.477 5.754 18 7.5 18s3.332.477 4.5 1.253m0-13C13.168 5.477 14.754 5 16.5 5c1.747 0 3.332.477 4.5 1.253v13C19.832 18.477 18.247 18 16.5 18c-1.746 0-3.332.477-4.5 1.253"/></svg>
                                    <p class="text-sm text-gray-400 mb-3">No courses yet</p>
                                    <button onclick="openModal()" class="inline-flex items-center gap-1.5 px-4 py-2 bg-brandOrange text-white text-sm font-semibold rounded-lg hover:bg-brandOrangeHover transition shadow-sm">
                                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4"/></svg>
                                        Add First Course
                                    </button>
                                </td>
                            </tr>
                        <?php endif; ?>
                    </tbody>
                </table>
            </div>
        </div>
    </main>
</div>

<div id="courseModal" class="fixed inset-0 z-50 hidden items-center justify-center bg-black/50 backdrop-blur-sm">
    <div class="bg-white rounded-2xl p-6 w-full max-w-lg mx-4 shadow-2xl">
        <div class="flex items-center justify-between mb-6">
            <h3 class="text-lg font-bold text-gray-800" id="modalTitle">Add Course</h3>
            <button onclick="closeModal()" class="text-gray-400 hover:text-gray-600 transition">
                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"/></svg>
            </button>
        </div>
        <form id="courseForm" class="space-y-4">
            <input type="hidden" name="id" id="courseId">
            <div>
                <label class="block text-sm font-medium text-gray-700 mb-1">Course Name</label>
                <input type="text" name="course_name" id="courseName" required
                       class="w-full px-3 py-2.5 text-sm border border-gray-200 rounded-lg focus:outline-none focus:ring-2 focus:ring-brandOrange focus:border-transparent"
                       placeholder="e.g. General English">
            </div>
            <div>
                <label class="block text-sm font-medium text-gray-700 mb-1">Instructor Name</label>
                <input type="text" name="instructor_name" id="instructorName"
                       class="w-full px-3 py-2.5 text-sm border border-gray-200 rounded-lg focus:outline-none focus:ring-2 focus:ring-brandOrange focus:border-transparent"
                       placeholder="e.g. John Doe">
            </div>
            <div>
                <label class="block text-sm font-medium text-gray-700 mb-1">Level</label>
                <select name="level" id="courseLevel"
                        class="w-full px-3 py-2.5 text-sm border border-gray-200 rounded-lg focus:outline-none focus:ring-2 focus:ring-brandOrange focus:border-transparent">
                    <option value="">Select level</option>
                    <option value="Beginner">Beginner</option>
                    <option value="Intermediate">Intermediate</option>
                    <option value="Advanced">Advanced</option>
                </select>
            </div>
            <div>
                <label class="block text-sm font-medium text-gray-700 mb-1">Description</label>
                <textarea name="description" id="courseDesc" rows="3"
                          class="w-full px-3 py-2.5 text-sm border border-gray-200 rounded-lg focus:outline-none focus:ring-2 focus:ring-brandOrange focus:border-transparent"
                          placeholder="Brief description of the course"></textarea>
            </div>
            <div class="flex items-center gap-3 pt-2">
                <button type="submit" class="flex-1 px-4 py-2.5 bg-brandOrange text-white text-sm font-bold rounded-lg hover:bg-brandOrangeHover transition shadow-sm">
                    Save Course
                </button>
                <button type="button" onclick="closeModal()" class="px-4 py-2.5 text-sm font-medium text-gray-600 hover:text-gray-800 transition">
                    Cancel
                </button>
            </div>
        </form>
    </div>
</div>

<script>
function openModal(id) {
    document.getElementById('courseForm').reset();
    document.getElementById('courseId').value = '';
    document.getElementById('modalTitle').textContent = 'Add Course';
    if (id) {
        document.getElementById('modalTitle').textContent = 'Edit Course';
        fetch('courses_ajax.php?action=get&id=' + id)
            .then(r => r.json())
            .then(d => {
                if (d.success) {
                    document.getElementById('courseId').value = d.data.id;
                    document.getElementById('courseName').value = d.data.course_name;
                    document.getElementById('instructorName').value = d.data.instructor_name || '';
                    document.getElementById('courseLevel').value = d.data.level || '';
                    document.getElementById('courseDesc').value = d.data.description || '';
                }
            });
    }
    document.getElementById('courseModal').classList.remove('hidden');
    document.getElementById('courseModal').classList.add('flex');
}

function closeModal() {
    document.getElementById('courseModal').classList.add('hidden');
    document.getElementById('courseModal').classList.remove('flex');
}

document.getElementById('courseModal').addEventListener('click', function(e) {
    if (e.target === this) closeModal();
});

document.getElementById('courseForm').addEventListener('submit', function(e) {
    e.preventDefault();
    const id = document.getElementById('courseId').value;
    const action = id ? 'update' : 'create';
    const formData = new URLSearchParams(new FormData(this));
    formData.append('action', action);

    fetch('courses_ajax.php', {
        method: 'POST',
        headers: { 'Content-Type': 'application/x-www-form-urlencoded' },
        body: formData
    })
    .then(r => r.json())
    .then(d => {
        if (d.success) {
            closeModal();
            location.reload();
        } else {
            alert(d.message || 'Something went wrong.');
        }
    });
});

function deleteCourse(id) {
    if (!confirm('Are you sure you want to delete this course?')) return;
    fetch('courses_ajax.php', {
        method: 'POST',
        headers: { 'Content-Type': 'application/x-www-form-urlencoded' },
        body: new URLSearchParams({ action: 'delete', id: id })
    })
    .then(r => r.json())
    .then(d => {
        if (d.success) {
            location.reload();
        } else {
            alert(d.message || 'Delete failed.');
        }
    });
}

document.getElementById('searchInput').addEventListener('keyup', function() {
    const q = this.value.trim();
    if (!q) { document.querySelectorAll('.course-row').forEach(r => r.style.display = ''); return; }
    const words = q.toLowerCase().split(/\s+/);
    document.querySelectorAll('.course-row').forEach(r => {
        const name = r.querySelector('td:first-child').textContent.toLowerCase();
        const match = words.some(w => name.split(/\s+/).some(n => n.startsWith(w)));
        r.style.display = match ? '' : 'none';
    });
});
</script>

<?php require_once 'includes/footer.php'; ?>