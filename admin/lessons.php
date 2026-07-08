<?php require_once 'includes/header.php'; ?>
<?php require_once 'includes/sidebar.php'; ?>
<?php require_once '../config/db.php'; ?>

<?php
 $total = $conn->query("SELECT COUNT(*) FROM lessons")->fetch_row()[0] ?? 0;
 $result = $conn->query("
    SELECT l.id, l.title, l.description, l.video, m.name AS module_name, c.course_name, l.created_at
    FROM lessons l
    JOIN modules m ON l.module_id = m.id
    JOIN courses c ON m.course_id = c.id
    ORDER BY l.created_at DESC
");
 $courses = $conn->query("SELECT id, course_name FROM courses ORDER BY course_name ASC");
?>

<div class="flex-1 flex flex-col overflow-hidden">
    <header class="h-16 bg-white border-b border-gray-200 flex items-center justify-between px-8 flex-shrink-0">
        <div>
            <h1 class="text-lg font-semibold text-gray-800">Lessons</h1>
            <p class="text-sm text-gray-500">Manage all lessons across modules</p>
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
                    <h3 class="font-semibold text-gray-800">All Lessons</h3>
                    <span class="text-xs font-medium text-gray-400 bg-gray-100 px-2.5 py-1 rounded-full"><?= $total ?> total</span>
                </div>
                <div class="flex items-center gap-3">
                    <div class="relative">
                        <svg class="w-4 h-4 text-gray-400 absolute left-3 top-1/2 -translate-y-1/2" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z"/></svg>
                        <input type="text" id="searchInput" placeholder="Search lessons..." class="pl-9 pr-3 py-2 text-sm border border-gray-200 rounded-lg focus:outline-none focus:ring-2 focus:ring-brandOrange focus:border-transparent w-60">
                    </div>
                    <button onclick="openModal()" class="inline-flex items-center gap-1.5 px-4 py-2 bg-brandOrange text-white text-sm font-semibold rounded-lg hover:bg-brandOrangeHover transition shadow-sm">
                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4"/></svg>
                        Add Lesson
                    </button>
                </div>
            </div>
            <div class="overflow-x-auto">
                <table class="w-full" id="lessonsTable">
                    <thead>
                        <tr class="text-left text-xs font-semibold text-gray-400 uppercase tracking-wider">
                            <th class="px-6 py-4">Lesson</th>
                            <th class="px-6 py-4">Course</th>
                            <th class="px-6 py-4">Module</th>
                            <th class="px-6 py-4">Video</th>
                            <th class="px-6 py-4">Created</th>
                            <th class="px-6 py-4 text-center">Actions</th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-gray-100">
                        <?php if ($result && $result->num_rows > 0): ?>
                            <?php while ($row = $result->fetch_assoc()): ?>
                            <tr class="hover:bg-gray-50 transition-colors lesson-row" data-id="<?= $row['id'] ?>">
                                <td class="px-6 py-4">
                                    <div class="flex items-center gap-3">
                                        <span class="w-9 h-9 rounded-lg bg-gradient-to-br from-purple-100 to-purple-200 text-purple-600 flex items-center justify-center text-sm font-bold">
                                            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"/></svg>
                                        </span>
                                        <div>
                                            <p class="text-sm font-medium text-gray-700"><?= htmlspecialchars($row['title']) ?></p>
                                            <p class="text-xs text-gray-400">ID: #<?= $row['id'] ?></p>
                                        </div>
                                    </div>
                                </td>
                                <td class="px-6 py-4 text-sm text-gray-600"><?= htmlspecialchars($row['course_name']) ?></td>
                                <td class="px-6 py-4">
                                    <span class="text-sm text-gray-600"><?= htmlspecialchars($row['module_name']) ?></span>
                                </td>
                                <td class="px-6 py-4">
                                    <?php if ($row['video']): ?>
                                        <a href="<?= htmlspecialchars($row['video']) ?>" target="_blank" class="inline-flex items-center gap-1 text-xs font-medium text-brandOrange hover:underline">
                                            <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M14.752 11.168l-3.197-2.132A1 1 0 0010 9.87v4.263a1 1 0 001.555.832l3.197-2.132a1 1 0 000-1.664z"/><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 12a9 9 0 11-18 0 9 9 0 0118 0z"/></svg>
                                            Play
                                        </a>
                                    <?php else: ?>
                                        <span class="text-xs text-gray-400">No video</span>
                                    <?php endif; ?>
                                </td>
                                <td class="px-6 py-4 text-sm text-gray-500"><?= date('M j, Y', strtotime($row['created_at'])) ?></td>
                                <td class="px-6 py-4 text-center">
                                    <div class="flex items-center justify-center gap-2">
                                        <button onclick="openModal(<?= $row['id'] ?>)" class="p-2 text-gray-400 hover:text-brandOrange hover:bg-orange-50 rounded-lg transition" title="Edit">
                                            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z"/></svg>
                                        </button>
                                        <button onclick="deleteLesson(<?= $row['id'] ?>)" class="p-2 text-gray-400 hover:text-red-600 hover:bg-red-50 rounded-lg transition" title="Delete">
                                            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"/></svg>
                                        </button>
                                    </div>
                                </td>
                            </tr>
                            <?php endwhile; ?>
                        <?php else: ?>
                            <tr>
                                <td colspan="6" class="px-6 py-12 text-center">
                                    <svg class="w-12 h-12 text-gray-300 mx-auto mb-3" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"/></svg>
                                    <p class="text-sm text-gray-400 mb-3">No lessons yet</p>
                                    <button onclick="openModal()" class="inline-flex items-center gap-1.5 px-4 py-2 bg-brandOrange text-white text-sm font-semibold rounded-lg hover:bg-brandOrangeHover transition shadow-sm">
                                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4"/></svg>
                                        Add First Lesson
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

<div id="lessonModal" class="fixed inset-0 z-50 hidden items-center justify-center bg-black/50 backdrop-blur-sm">
    <div class="bg-white rounded-2xl p-6 w-full max-w-lg mx-4 shadow-2xl">
        <div class="flex items-center justify-between mb-6">
            <h3 class="text-lg font-bold text-gray-800" id="modalTitle">Add Lesson</h3>
            <button onclick="closeModal()" class="text-gray-400 hover:text-gray-600 transition">
                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"/></svg>
            </button>
        </div>
        <form id="lessonForm" class="space-y-4">
            <input type="hidden" name="id" id="lessonId">
            <input type="hidden" name="action" id="formAction" value="create">
            <input type="hidden" name="video" id="videoPath" value="">
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
                <label class="block text-sm font-medium text-gray-700 mb-1">Lesson Title</label>
                <input type="text" name="title" id="lessonTitle" required
                       class="w-full px-3 py-2.5 text-sm border border-gray-200 rounded-lg focus:outline-none focus:ring-2 focus:ring-brandOrange focus:border-transparent"
                       placeholder="e.g. Introduction to Speaking">
            </div>
            <div>
                <label class="block text-sm font-medium text-gray-700 mb-1">Description</label>
                <textarea name="description" id="lessonDesc" rows="3"
                          class="w-full px-3 py-2.5 text-sm border border-gray-200 rounded-lg focus:outline-none focus:ring-2 focus:ring-brandOrange focus:border-transparent"
                          placeholder="Lesson description"></textarea>
            </div>
            <div>
                <label class="block text-sm font-medium text-gray-700 mb-1">Video File</label>
                <input type="file" id="videoFileInput" accept="video/mp4,video/webm,video/ogg,video/quicktime"
                       class="w-full text-sm text-gray-500 file:mr-4 file:py-2 file:px-4 file:rounded-lg file:border-0 file:text-sm file:font-semibold file:bg-orange-50 file:text-brandOrange hover:file:bg-orange-100 cursor-pointer">
                <div id="videoStatus" class="text-xs mt-1"></div>
            </div>
            <div class="flex items-center gap-3 pt-2">
                <button type="submit" class="flex-1 px-4 py-2.5 bg-brandOrange text-white text-sm font-bold rounded-lg hover:bg-brandOrangeHover transition shadow-sm">
                    Save Lesson
                </button>
                <button type="button" onclick="closeModal()" class="px-4 py-2.5 text-sm font-medium text-gray-600 hover:text-gray-800 transition">
                    Cancel
                </button>
            </div>
        </form>
    </div>
</div>

<script>
function loadModules(courseId, selected) {
    var sel = document.getElementById('moduleSelect');
    sel.innerHTML = '<option value="">Loading...</option>';
    fetch('lessons_ajax.php?action=get_modules&course_id=' + courseId)
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
    document.getElementById('lessonForm').reset();
    document.getElementById('lessonId').value = '';
    document.getElementById('videoPath').value = '';
    document.getElementById('videoStatus').innerHTML = '';
    document.getElementById('modalTitle').textContent = 'Add Lesson';
    document.getElementById('moduleSelect').innerHTML = '<option value="">Select course first</option>';
    document.getElementById('courseSelect').value = '';
    
    if (id) {
        document.getElementById('modalTitle').textContent = 'Edit Lesson';
        fetch('lessons_ajax.php?action=get&id=' + id)
            .then(function(r) { return r.json(); })
            .then(function(d) {
                if (d.success) {
                    document.getElementById('lessonId').value = d.data.id;
                    document.getElementById('lessonTitle').value = d.data.title;
                    document.getElementById('lessonDesc').value = d.data.description || '';
                    document.getElementById('courseSelect').value = d.data.course_id;
                    document.getElementById('videoPath').value = d.data.video || '';
                    if (d.data.video) {
                        document.getElementById('videoStatus').innerHTML = '<span class="text-green-600">Current: ' + d.data.video.split('/').pop() + '</span>';
                    }
                    loadModules(d.data.course_id, d.data.module_id);
                }
            });
    }
    document.getElementById('lessonModal').classList.remove('hidden');
    document.getElementById('lessonModal').classList.add('flex');
}

function closeModal() {
    document.getElementById('lessonModal').classList.add('hidden');
    document.getElementById('lessonModal').classList.remove('flex');
}

document.getElementById('lessonModal').addEventListener('click', function(e) {
    if (e.target === this) closeModal();
});

document.getElementById('lessonForm').addEventListener('submit', function(e) {
    e.preventDefault();
    var id = document.getElementById('lessonId').value;
    document.getElementById('formAction').value = id ? 'update' : 'create';

    var formData = new URLSearchParams(new FormData(this));
    fetch('lessons_ajax.php', {
        method: 'POST',
        headers: { 'Content-Type': 'application/x-www-form-urlencoded' },
        body: formData
    })
    .then(function(r) { return r.json(); })
    .then(function(d) {
        if (d.success) {
            closeModal();
            location.reload();
        } else {
            alert(d.message || 'Something went wrong.');
        }
    })
    .catch(function(err) {
        alert('Error saving lesson.');
        console.error(err);
    });
});

function deleteLesson(id) {
    if (!confirm('Are you sure you want to delete this lesson?')) return;
    fetch('lessons_ajax.php', {
        method: 'POST',
        headers: { 'Content-Type': 'application/x-www-form-urlencoded' },
        body: new URLSearchParams({ action: 'delete', id: id })
    })
    .then(function(r) { return r.json(); })
    .then(function(d) {
        if (d.success) location.reload();
        else alert(d.message || 'Delete failed.');
    });
}

document.getElementById('searchInput').addEventListener('keyup', function() {
    var q = this.value.trim();
    if (!q) { document.querySelectorAll('.lesson-row').forEach(function(r) { r.style.display = ''; }); return; }
    var words = q.toLowerCase().split(/\s+/);
    document.querySelectorAll('.lesson-row').forEach(function(r) {
        var name = r.querySelector('td:nth-child(3)').textContent.toLowerCase();
        var match = words.some(function(w) { return name.split(/\s+/).some(function(n) { return n.startsWith(w); }); });
        r.style.display = match ? '' : 'none';
    });
});

document.getElementById('videoFileInput').addEventListener('change', function() {
    if (!this.files || !this.files[0]) return;
    
    var file = this.files[0];
    var status = document.getElementById('videoStatus');
    
    var allowed = ['video/mp4', 'video/webm', 'video/ogg', 'video/quicktime'];
    if (allowed.indexOf(file.type) === -1) {
        status.innerHTML = '<span class="text-red-600">Invalid type. Use MP4, WebM, OGG, or MOV.</span>';
        this.value = '';
        return;
    }
    
    if (file.size > 100 * 1024 * 1024) {
        status.innerHTML = '<span class="text-red-600">File too large (max 100MB).</span>';
        this.value = '';
        return;
    }
    
    status.innerHTML = '<span class="text-blue-600">Uploading... 0%</span>';
    
    var formData = new FormData();
    formData.append('video', file);
    
    var xhr = new XMLHttpRequest();
    
    xhr.upload.onprogress = function(e) {
        if (e.lengthComputable) {
            var pct = Math.round((e.loaded / e.total) * 100);
            status.innerHTML = '<span class="text-blue-600">Uploading... ' + pct + '%</span>';
        }
    };
    
    xhr.onload = function() {
        if (xhr.status === 200) {
            try {
                var d = JSON.parse(xhr.responseText);
                if (d.success) {
                    document.getElementById('videoPath').value = d.path;
                    status.innerHTML = '<span class="text-green-600">✓ ' + d.path.split('/').pop() + '</span>';
                } else {
                    status.innerHTML = '<span class="text-red-600">' + d.message + '</span>';
                }
            } catch (e) {
                status.innerHTML = '<span class="text-red-600">Server error.</span>';
                console.log('Response:', xhr.responseText);
            }
        } else {
            status.innerHTML = '<span class="text-red-600">Server error: ' + xhr.status + '</span>';
        }
    };
    
    xhr.onerror = function() {
        status.innerHTML = '<span class="text-red-600">Network error.</span>';
    };
    
    xhr.open('POST', 'upload_video.php', true);
    xhr.send(formData);
});
</script>

<?php require_once 'includes/footer.php'; ?>