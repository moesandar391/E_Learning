<?php require_once 'includes/header.php'; ?>
<?php require_once 'includes/sidebar.php'; ?>
<?php require_once '../config/db.php'; ?>

<?php
 $total = $conn->query("SELECT COUNT(*) FROM modules")->fetch_row()[0] ?? 0;
 $result = $conn->query("
    SELECT m.id, m.name, m.price, m.image, m.created_at, c.course_name
    FROM modules m
    JOIN courses c ON m.course_id = c.id
    ORDER BY m.created_at DESC
");
 $courses = $conn->query("SELECT id, course_name FROM courses ORDER BY course_name ASC");
?>

<div class="flex-1 flex flex-col overflow-hidden">
    <header class="h-16 bg-white border-b border-gray-200 flex items-center justify-between px-8 flex-shrink-0">
        <div>
            <h1 class="text-lg font-semibold text-gray-800">Modules</h1>
            <p class="text-sm text-gray-500">Manage modules within courses</p>
        </div>
        <div class="flex items-center gap-4">
            <span class="text-sm text-gray-500"><?php echo date('l, F j, Y'); ?></span>
            <?php require_once 'includes/admin_notif_icon.php'; ?>
            <a href="settings.php" class="w-9 h-9 rounded-full bg-gradient-to-br from-brandOrange to-orange-400 text-white flex items-center justify-center text-sm font-bold shadow-sm hover:opacity-90 transition">
                <?php echo strtoupper(substr($_SESSION['username'] ?? 'A', 0, 1)); ?>
            </a>
        </div>
    </header>

    <main class="flex-1 overflow-y-auto p-8">
        <div class="bg-white rounded-xl border border-gray-200 hover:shadow-lg transition-shadow duration-200">
            <div class="px-6 py-4 border-b border-gray-100 flex items-center justify-between">
                <div class="flex items-center gap-4">
                    <h3 class="font-semibold text-gray-800">All Modules</h3>
                    <span class="text-xs font-medium text-gray-400 bg-gray-100 px-2.5 py-1 rounded-full"><?= $total ?> total</span>
                </div>
                <div class="flex items-center gap-3">
                    <div class="relative">
                        <svg class="w-4 h-4 text-gray-400 absolute left-3 top-1/2 -translate-y-1/2" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z"/></svg>
                        <input type="text" id="searchInput" placeholder="Search courses..." class="pl-9 pr-3 py-2 text-sm border border-orange-200 rounded-lg focus:outline-none focus:ring-2 focus:ring-brandOrange focus:border-transparent w-60">
                    </div>
                    <button onclick="openModal()" class="inline-flex items-center gap-1.5 px-4 py-2 bg-brandOrange text-white text-sm font-semibold rounded-lg hover:bg-brandOrangeHover transition shadow-sm">
                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4"/></svg>
                        Add Module
                    </button>
                </div>
            </div>
            <div class="overflow-x-auto">
                <table class="w-full" id="modulesTable">
                    <thead class="bg-orange-100/50">
                        <tr class="text-left text-xs font-semibold text-gray-500 uppercase tracking-wider">
                            <th class="px-6 py-4">Image</th>
                            <th class="px-6 py-4">Module</th>
                            <th class="px-6 py-4">Course</th>
                            <th class="px-6 py-4">Price</th>
                            <th class="px-6 py-4">Created</th>
                            <th class="px-6 py-4 text-center">Actions</th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-gray-100">
                        <?php if ($result && $result->num_rows > 0): ?>
                            <?php while ($row = $result->fetch_assoc()): ?>
                            <tr class="hover:bg-gray-50 transition-colors module-row" data-id="<?= $row['id'] ?>">
                                <td class="px-6 py-4">
                                    <div class="w-12 h-12 rounded-xl overflow-hidden bg-gray-100 border border-gray-200 flex-shrink-0">
                                        <?php if (!empty($row['image'])): ?>
                                            <img src="../uploads/modules/<?= htmlspecialchars($row['image']) ?>" alt="<?= htmlspecialchars($row['name']) ?>" class="w-full h-full object-cover">
                                        <?php else: ?>
                                            <div class="w-full h-full flex items-center justify-center text-gray-300">
                                                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M4 16l4.586-4.586a2 2 0 012.828 0L16 16m-2-2l1.586-1.586a2 2 0 012.828 0L20 14m-6-6h.01M6 20h12a2 2 0 002-2V6a2 2 0 00-2-2H6a2 2 0 00-2 2v12a2 2 0 002 2z"/></svg>
                                            </div>
                                        <?php endif; ?>
                                    </div>
                                </td>
                                <td class="px-6 py-4">
                                    <div class="flex items-center gap-3">
                                        <span class="w-9 h-9 rounded-lg bg-gradient-to-br from-teal-100 to-teal-200 text-teal-600 flex items-center justify-center text-sm font-bold">
                                            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 11H5m14 0a2 2 0 012 2v6a2 2 0 01-2 2H5a2 2 0 01-2-2v-6a2 2 0 012-2m14 0V9a2 2 0 00-2-2M5 11V9a2 2 0 012-2m0 0V5a2 2 0 012-2h6a2 2 0 012 2v2M7 7h10"/></svg>
                                        </span>
                                        <div>
                                            <p class="text-sm font-medium text-gray-700"><?= htmlspecialchars($row['name']) ?></p>
                                            <p class="text-xs text-gray-400">ID: #<?= $row['id'] ?></p>
                                        </div>
                                    </div>
                                </td>
                                <td class="px-6 py-4 text-sm text-gray-600"><?= htmlspecialchars($row['course_name']) ?></td>
                                <td class="px-6 py-4">
                                    <span class="text-sm font-semibold text-gray-700"><?= number_format($row['price']) ?> MMK</span>
                                </td>
                                <td class="px-6 py-4 text-sm text-gray-500"><?= date('M j, Y', strtotime($row['created_at'])) ?></td>
                                <td class="px-6 py-4 text-center">
                                    <div class="flex items-center justify-center gap-2">
                                        <button onclick="openModal(<?= $row['id'] ?>)" 
                                            class="p-2 text-green-600 hover:text-green-700 hover:bg-green-100 rounded-lg transition" 
                                            title="Edit">
                                            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z"/>
                                            </svg>
                                        </button>

                                        <button onclick="deleteCourse(<?= $row['id'] ?>)" 
                                            class="p-2 text-red-600 hover:text-red-700 hover:bg-red-100 rounded-lg transition" 
                                            title="Delete">
                                            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"/>
                                            </svg>
                                        </button>
                                    </div>
                                </td>
                            </tr>
                            <?php endwhile; ?>
                        <?php else: ?>
                            <tr>
                                <td colspan="6" class="px-6 py-12 text-center">
                                    <svg class="w-12 h-12 text-gray-300 mx-auto mb-3" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M19 11H5m14 0a2 2 0 012 2v6a2 2 0 01-2 2H5a2 2 0 01-2-2v-6a2 2 0 012-2m14 0V9a2 2 0 00-2-2M5 11V9a2 2 0 012-2m0 0V5a2 2 0 012-2h6a2 2 0 012 2v2M7 7h10"/></svg>
                                    <p class="text-sm text-gray-400 mb-3">No modules yet</p>
                                    <button onclick="openModal()" class="inline-flex items-center gap-1.5 px-4 py-2 bg-brandOrange text-white text-sm font-semibold rounded-lg hover:bg-brandOrangeHover transition shadow-sm">
                                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4"/></svg>
                                        Add First Module
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

<!-- Modal -->
<div id="moduleModal" class="fixed inset-0 z-50 hidden items-center justify-center bg-black/50 backdrop-blur-sm">
    <div class="bg-white rounded-2xl p-6 w-full max-w-lg mx-4 shadow-2xl">
        <div class="flex items-center justify-between mb-6">
            <h3 class="text-lg font-bold text-gray-800" id="modalTitle">Add Module</h3>
            <button onclick="closeModal()" class="text-gray-400 hover:text-gray-600 transition">
                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"/></svg>
            </button>
        </div>
        <form id="moduleForm" class="space-y-4" enctype="multipart/form-data">
            <input type="hidden" name="id" id="moduleId">
            <!-- Image Upload -->
            <div>
                <label class="block text-sm font-medium text-gray-700 mb-1">Image</label>
                <div class="flex items-center gap-4">
                    <div id="imagePreviewBox" class="w-20 h-20 rounded-xl border-2 border-dashed border-gray-200 flex items-center justify-center bg-gray-50 overflow-hidden flex-shrink-0 cursor-pointer hover:border-brandOrange transition" onclick="document.getElementById('moduleImage').click()">
                        <div id="imagePlaceholder" class="text-center">
                            <svg class="w-6 h-6 text-gray-300 mx-auto" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M4 16l4.586-4.586a2 2 0 012.828 0L16 16m-2-2l1.586-1.586a2 2 0 012.828 0L20 14m-6-6h.01M6 20h12a2 2 0 002-2V6a2 2 0 00-2-2H6a2 2 0 00-2 2v12a2 2 0 002 2z"/></svg>
                            <p class="text-[10px] text-gray-400 mt-1">Upload</p>
                        </div>
                        <img id="imagePreview" src="" alt="" class="w-full h-full object-cover hidden">
                    </div>
                    <div class="flex-1">
                        <input type="file" name="image" id="moduleImage" accept="image/*" class="hidden">
                        <button type="button" onclick="document.getElementById('moduleImage').click()" class="w-full px-3 py-2 text-sm border border-gray-200 rounded-lg hover:bg-gray-50 transition text-gray-600">
                            Choose File
                        </button>
                        <p id="imageName" class="text-xs text-gray-400 mt-1">No file chosen</p>
                    </div>
                </div>
                <input type="hidden" name="existing_image" id="existingImage" value="">
            </div>
            <div>
                <label class="block text-sm font-medium text-gray-700 mb-1">Course</label>
                <select name="course_id" id="moduleCourse" required
                        class="w-full px-3 py-2.5 text-sm border border-gray-200 rounded-lg focus:outline-none focus:ring-2 focus:ring-brandOrange focus:border-transparent">
                    <option value="">Select course</option>
                    <?php while ($c = $courses->fetch_assoc()): ?>
                        <option value="<?= $c['id'] ?>"><?= htmlspecialchars($c['course_name']) ?></option>
                    <?php endwhile; ?>
                </select>
            </div>
            <div>
                <label class="block text-sm font-medium text-gray-700 mb-1">Module Name</label>
                <input type="text" name="name" id="moduleName" required
                       class="w-full px-3 py-2.5 text-sm border border-gray-200 rounded-lg focus:outline-none focus:ring-2 focus:ring-brandOrange focus:border-transparent"
                       placeholder="e.g. Speaking">
            </div>
            <div>
                <label class="block text-sm font-medium text-gray-700 mb-1">Price (MMK)</label>
                <input type="number" name="price" id="modulePrice" step="0.01" min="0"
                       class="w-full px-3 py-2.5 text-sm border border-gray-200 rounded-lg focus:outline-none focus:ring-2 focus:ring-brandOrange focus:border-transparent"
                       placeholder="e.g. 50000">
            </div>
            <div class="flex items-center gap-3 pt-2">
                <button type="submit" id="submitBtn" class="flex-1 px-4 py-2.5 bg-brandOrange text-white text-sm font-bold rounded-lg hover:bg-brandOrangeHover transition shadow-sm">
                    Save Module
                </button>
                <button type="button" onclick="closeModal()" class="px-4 py-2.5 text-sm font-medium text-gray-600 hover:text-gray-800 transition">
                    Cancel
                </button>
            </div>
        </form>
    </div>
</div>

<script>
// Image preview on file select
document.getElementById('moduleImage').addEventListener('change', function() {
    const file = this.files[0];
    const preview = document.getElementById('imagePreview');
    const placeholder = document.getElementById('imagePlaceholder');
    const nameEl = document.getElementById('imageName');

    if (file) {
        nameEl.textContent = file.name;
        const reader = new FileReader();
        reader.onload = function(e) {
            preview.src = e.target.result;
            preview.classList.remove('hidden');
            placeholder.classList.add('hidden');
        };
        reader.readAsDataURL(file);
    } else {
        preview.src = '';
        preview.classList.add('hidden');
        placeholder.classList.remove('hidden');
        nameEl.textContent = 'No file chosen';
    }
});

function resetImagePreview() {
    document.getElementById('imagePreview').src = '';
    document.getElementById('imagePreview').classList.add('hidden');
    document.getElementById('imagePlaceholder').classList.remove('hidden');
    document.getElementById('imageName').textContent = 'No file chosen';
    document.getElementById('moduleImage').value = '';
    document.getElementById('existingImage').value = '';
}

function openModal(id) {
    document.getElementById('moduleForm').reset();
    document.getElementById('moduleId').value = '';
    document.getElementById('modalTitle').textContent = 'Add Module';
    resetImagePreview();

    if (id) {
        document.getElementById('modalTitle').textContent = 'Edit Module';
        fetch('modules_ajax.php?action=get&id=' + id)
            .then(r => r.json())
            .then(d => {
                if (d.success) {
                    document.getElementById('moduleId').value = d.data.id;
                    document.getElementById('moduleCourse').value = d.data.course_id;
                    document.getElementById('moduleName').value = d.data.name;
                    document.getElementById('modulePrice').value = d.data.price;

                    if (d.data.image) {
                        document.getElementById('existingImage').value = d.data.image;
                        document.getElementById('imagePreview').src = '../uploads/modules/' + d.data.image;
                        document.getElementById('imagePreview').classList.remove('hidden');
                        document.getElementById('imagePlaceholder').classList.add('hidden');
                        document.getElementById('imageName').textContent = d.data.image;
                    }
                }
            });
    }
    document.getElementById('moduleModal').classList.remove('hidden');
    document.getElementById('moduleModal').classList.add('flex');
}

function closeModal() {
    document.getElementById('moduleModal').classList.add('hidden');
    document.getElementById('moduleModal').classList.remove('flex');
}

document.getElementById('moduleModal').addEventListener('click', function(e) {
    if (e.target === this) closeModal();
});

document.getElementById('moduleForm').addEventListener('submit', function(e) {
    e.preventDefault();
    const id = document.getElementById('moduleId').value;
    const action = id ? 'update' : 'create';
    const formData = new FormData(this);
    formData.append('action', action);

    const btn = document.getElementById('submitBtn');
    btn.disabled = true;
    btn.textContent = 'Saving...';

    fetch('modules_ajax.php', {
        method: 'POST',
        body: formData
    })
    .then(r => r.json())
    .then(d => {
        btn.disabled = false;
        btn.textContent = 'Save Module';
        if (d.success) {
            closeModal();
            location.reload();
        } else {
            alert(d.message || 'Something went wrong.');
        }
    })
    .catch(() => {
        btn.disabled = false;
        btn.textContent = 'Save Module';
        alert('Network error. Please try again.');
    });
});

function deleteModule(id) {
    if (!confirm('Are you sure you want to delete this module? Related lessons will also be deleted.')) return;
    fetch('modules_ajax.php', {
        method: 'POST',
        headers: { 'Content-Type': 'application/x-www-form-urlencoded' },
        body: new URLSearchParams({ action: 'delete', id: id })
    })
    .then(r => r.json())
    .then(d => {
        if (d.success) location.reload();
        else alert(d.message || 'Delete failed.');
    });
}

document.getElementById('searchInput').addEventListener('keyup', function() {
    const q = this.value.trim();
    if (!q) { document.querySelectorAll('.module-row').forEach(r => r.style.display = ''); return; }
    const words = q.toLowerCase().split(/\s+/);
    document.querySelectorAll('.module-row').forEach(r => {
        const name = r.querySelector('td:nth-child(3)').textContent.toLowerCase();
        const match = words.some(w => name.split(/\s+/).some(n => n.startsWith(w)));
        r.style.display = match ? '' : 'none';
    });
});
</script>

<?php require_once 'includes/footer.php'; ?>