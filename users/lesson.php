<?php
session_start();
require_once '../config/db.php';

$module_id = isset($_GET['module_id']) ? (int)$_GET['module_id'] : 1;
$lesson_id = isset($_GET['lesson_id']) ? (int)$_GET['lesson_id'] : null;
$user_id = $_SESSION['user_id'] ?? null;

if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['complete_lesson']) && $user_id) {
    $complete_lesson_id = (int)$_POST['complete_lesson_id'];
    $stmt = $conn->prepare("INSERT INTO lesson_progress (user_id, lesson_id, completed, completed_at) VALUES (?, ?, 1, NOW()) ON DUPLICATE KEY UPDATE completed = 1, completed_at = NOW()");
    $stmt->bind_param("ii", $user_id, $complete_lesson_id);
    $stmt->execute();
    if (!empty($_SERVER['HTTP_X_REQUESTED_WITH']) && strtolower($_SERVER['HTTP_X_REQUESTED_WITH']) === 'xmlhttprequest') {
        echo json_encode(['success' => true]);
        exit;
    }
    header("Location: lesson.php?module_id=$module_id&lesson_id=$complete_lesson_id");
    exit;
}

include_once('../includes/header.php');

$sql = "SELECT l.*, m.name AS module_name, c.course_name 
        FROM lessons l 
        JOIN modules m ON l.module_id = m.id 
        JOIN courses c ON m.course_id = c.id 
        WHERE l.module_id = ? 
        ORDER BY l.id ASC";
$stmt = $conn->prepare($sql);
$stmt->bind_param("i", $module_id);
$stmt->execute();
$allLessons = $stmt->get_result()->fetch_all(MYSQLI_ASSOC);

$completedIds = [];
if ($user_id && !empty($allLessons)) {
    $ids = implode(',', array_column($allLessons, 'id'));
    $p = $conn->query("SELECT lesson_id FROM lesson_progress WHERE user_id = $user_id AND lesson_id IN ($ids) AND completed = 1");
    while ($row = $p->fetch_assoc()) {
        $completedIds[] = $row['lesson_id'];
    }
}

$currentIndex = null;
if ($lesson_id) {
    foreach ($allLessons as $i => $l) {
        if ($l['id'] == $lesson_id) { $currentIndex = $i; break; }
    }
}
if ($currentIndex === null && !empty($allLessons)) {
    $currentIndex = 0;
}

$activeLesson = $currentIndex !== null ? $allLessons[$currentIndex] : null;

function isUnlocked($index, $allLessons, $completedIds) {
    if ($index === 0) return true;
    $prevId = $allLessons[$index - 1]['id'];
    return in_array($prevId, $completedIds);
}

$prevIndex = $currentIndex !== null && $currentIndex > 0 ? $currentIndex - 1 : null;
$nextIndex = $currentIndex !== null && $currentIndex < count($allLessons) - 1 ? $currentIndex + 1 : null;
$nextUnlocked = $nextIndex !== null ? isUnlocked($nextIndex, $allLessons, $completedIds) : false;
$isCompleted = $activeLesson && in_array($activeLesson['id'], $completedIds);
?>

<div class="max-w-7xl mx-auto px-6 py-6 bg-gray-50 text-gray-900 text-left min-h-screen">

    <div class="grid grid-cols-1 lg:grid-cols-12 gap-8">

        <main class="lg:col-span-8 space-y-6">
            <?php if ($activeLesson): ?>
                <?php if (!empty($allLessons)): ?>
                    <div class="text-sm text-gray-500 mb-2">
                        <?php echo htmlspecialchars($allLessons[0]['course_name']); ?> >
                        <?php echo htmlspecialchars($allLessons[0]['module_name']); ?> >
                        <span class="text-brandOrange font-semibold"><?php echo htmlspecialchars($activeLesson['title']); ?></span>
                    </div>
                <?php endif; ?>

                <div class="aspect-video bg-gray-200 rounded-xl border border-gray-200 overflow-hidden shadow-sm">
                    <video id="lessonVideo" class="w-full h-full" controls preload="metadata" <?= $user_id && !$isCompleted ? 'onended="autoComplete(' . $activeLesson['id'] . ')"' : '' ?>>
                        <source src="../admin/<?php echo htmlspecialchars($activeLesson['video']); ?>" type="video/mp4">
                        Your browser does not support the video tag.
                    </video>
                </div>
                <div class="text-sm mt-1">
                    <a href="../admin/<?php echo htmlspecialchars($activeLesson['video']); ?>" target="_blank" class="text-brandOrange hover:underline inline-flex items-center gap-1">
                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 16v1a3 3 0 003 3h10a3 3 0 003-3v-1m-4-4l-4 4m0 0l-4-4m4 4V4"/></svg>
                        Download Video
                    </a>
                </div>

                <div class="flex items-center justify-between">
                    <p class="text-brandOchre font-bold leading-relaxed text-2xl">
                        <?php echo nl2br(htmlspecialchars($activeLesson['title'])); ?>
                    </p>
                    <?php if ($user_id): ?>
                        <?php if ($isCompleted): ?>
                            <span class="inline-flex items-center gap-1.5 px-4 py-2 rounded-full text-sm font-semibold bg-green-50 text-green-700 border border-green-200">
                                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M5 13l4 4L19 7"/></svg>
                                Completed
                            </span>
                        <?php elseif (isUnlocked($currentIndex, $allLessons, $completedIds)): ?>
                            <span class="inline-flex items-center gap-1.5 px-4 py-2 rounded-full text-sm font-semibold bg-gray-50 text-gray-500 border border-gray-200">
                                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M14.752 11.168l-3.197-2.132A1 1 0 0010 9.87v4.263a1 1 0 001.555.832l3.197-2.132a1 1 0 000-1.664z"/></svg>
                                Watching...
                            </span>
                        <?php endif; ?>
                    <?php endif; ?>
                </div>

                <p class="text-gray-600 leading-relaxed text-base">
                    <?php echo nl2br(htmlspecialchars($activeLesson['description'])); ?>
                </p>

                <div class="flex items-center justify-between pt-4 border-t border-gray-200">
                    <?php if ($prevIndex !== null): ?>
                        <a href="lesson.php?module_id=<?php echo $module_id; ?>&lesson_id=<?php echo $allLessons[$prevIndex]['id']; ?>" 
                            class="inline-flex items-center gap-2 px-4 py-2 rounded-lg text-sm font-medium text-white bg-brandOrange hover:bg-brandOrangeHover transition">
                            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 19l-7-7 7-7"/></svg>
                            Previous
                        </a>
                    <?php else: ?>
                        <span></span>
                    <?php endif; ?>

                    <?php if ($nextIndex !== null && $nextUnlocked): ?>
                        <a href="lesson.php?module_id=<?php echo $module_id; ?>&lesson_id=<?php echo $allLessons[$nextIndex]['id']; ?>" class="inline-flex items-center gap-2 px-4 py-2 rounded-lg text-sm font-medium text-white bg-brandOrange hover:bg-brandOrangeHover transition shadow-sm">
                            Next
                            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7"/></svg>
                        </a>
                    <?php elseif ($nextIndex !== null): ?>
                        <span class="inline-flex items-center gap-2 px-4 py-2 rounded-lg text-sm font-medium text-gray-400 bg-gray-100 cursor-not-allowed">
                            Locked
                            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 15v2m-6 4h12a2 2 0 002-2v-6a2 2 0 00-2-2H6a2 2 0 00-2 2v6a2 2 0 002 2zm10-10V7a4 4 0 00-8 0v4h8z"/></svg>
                        </span>
                    <?php else: ?>
                        <span class="text-sm text-gray-500 font-medium">Course Complete!</span>
                    <?php endif; ?>
                </div>

            <?php else: ?>
                <p class="text-gray-500 text-center py-20">No lessons found in this module.</p>
            <?php endif; ?>
        </main>

        <aside class="lg:col-span-4">
            <div class="bg-white p-6 rounded-xl border border-orange-200 shadow-sm">
                <h3 class="font-bold text-lg text-gray-800 mb-4 border-b border-gray-100 pb-3">Course Outline</h3>

                <div class="space-y-1 max-h-[60vh] overflow-y-auto pr-1">
                    <?php foreach ($allLessons as $index => $lesson):
                        $unlocked = isUnlocked($index, $allLessons, $completedIds);
                        $isActive = $index === $currentIndex;
                        $completed = in_array($lesson['id'], $completedIds);
                    ?>
                        <?php if ($unlocked || $completed): ?>
                            <a href="lesson.php?module_id=<?php echo $module_id; ?>&lesson_id=<?php echo $lesson['id']; ?>" class="flex items-center gap-3 px-3 py-2.5 rounded-lg transition text-sm <?php echo $isActive ? 'bg-orange-50 text-brandOrange font-semibold' : 'text-gray-600 hover:bg-gray-50 hover:text-gray-900'; ?>">
                                <span class="flex-shrink-0 w-6 h-6 rounded-full flex items-center justify-center text-xs font-bold <?php echo $completed ? 'bg-green-100 text-green-700' : ($isActive ? 'bg-brandOrange text-white' : 'bg-gray-100 text-gray-500'); ?>">
                                    <?php if ($completed): ?>
                                        <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="3" d="M5 13l4 4L19 7"/></svg>
                                    <?php else: ?>
                                        <?php echo $index + 1; ?>
                                    <?php endif; ?>
                                </span>
                                <span class="flex-1 truncate"><?php echo htmlspecialchars($lesson['title']); ?></span>
                                <?php if ($completed): ?>
                                    <svg class="w-4 h-4 text-green-500 flex-shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M5 13l4 4L19 7"/></svg>
                                <?php endif; ?>
                            </a>
                        <?php else: ?>
                            <div class="flex items-center gap-3 px-3 py-2.5 rounded-lg text-sm text-gray-300 cursor-not-allowed">
                                <span class="flex-shrink-0 w-6 h-6 rounded-full bg-gray-100 flex items-center justify-center text-xs font-bold text-gray-300"><?php echo $index + 1; ?></span>
                                <span class="flex-1 truncate"><?php echo htmlspecialchars($lesson['title']); ?></span>
                                <svg class="w-3.5 h-3.5 text-gray-300 flex-shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 15v2m-6 4h12a2 2 0 002-2v-6a2 2 0 00-2-2H6a2 2 0 00-2 2v6a2 2 0 002 2zm10-10V7a4 4 0 00-8 0v4h8z"/></svg>
                            </div>
                        <?php endif; ?>
                    <?php endforeach; ?>
                </div>

                <?php
                $totalLessons = count($allLessons);
                $completedCount = count($completedIds);
                $allCompleted = $totalLessons > 0 && $completedCount === $totalLessons;
                ?>
                <div class="mt-4">
                    <?php if ($allCompleted): ?>
                        <button onclick="downloadCertificate(event, <?php echo $module_id; ?>)"
                           class="flex items-center justify-center gap-2 w-full px-4 py-3 rounded-lg text-sm font-bold transition border bg-green-50 text-green-700 border-green-300 hover:bg-green-100" style="cursor:default">
                            <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 10v6m0 0l-3-3m3 3l3-3m2 8H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"/></svg>
                            Download Certificate
                        </button>
                    <?php else: ?>
                        <div class="flex items-center justify-center gap-2 w-full px-4 py-3 rounded-lg text-sm font-bold border bg-gray-100 text-gray-400 border-gray-200" style="cursor:default;pointer-events:none">
                            <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 10v6m0 0l-3-3m3 3l3-3m2 8H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"/></svg>
                            Download Certificate
                            <span class="text-xs ml-1">(<?php echo $completedCount; ?>/<?php echo $totalLessons; ?>)</span>
                        </div>
                    <?php endif; ?>
                </div>
            </div>
            <?php
$progress = $totalLessons > 0 ? round(($completedCount / $totalLessons) * 100) : 0;
?>

<div class="mt-3 rounded-xl border border-orange-200 bg-orange-50 p-4">
    <div class="flex justify-between items-center mb-2">
        <h4 class="font-semibold text-gray-800">Lesson Progress</h4>
        <span class="text-sm font-bold text-brandOrange">
            <?= $completedCount ?>/<?= $totalLessons ?>
        </span>
    </div>

    <div class="w-full h-2 bg-gray-200 rounded-full overflow-hidden">
        <div class="h-full bg-green-500 rounded-full"
             style="width: <?= $progress ?>%"></div>
    </div>

    <div class="mt-2 flex justify-between text-xs">
        <span class="text-gray-500"><?= $progress ?>% Completed</span>

        <?php if($progress==100): ?>
            <span class="font-semibold text-green-600">
                ✔ Completed
            </span>
        <?php else: ?>
            <span class="font-semibold text-yellow-600">
                In Progress
            </span>
        <?php endif; ?>
    </div>
</div>
        </aside>

    </div>
</div>

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

function autoComplete(lessonId) {
    var formData = new FormData();
    formData.append('complete_lesson', '1');
    formData.append('complete_lesson_id', lessonId);
    var xhr = new XMLHttpRequest();
    xhr.open('POST', '', true);
    xhr.setRequestHeader('X-Requested-With', 'XMLHttpRequest');
    xhr.onload = function() {
        if (xhr.status === 200) {
            location.reload();
        }
    };
    xhr.send(formData);
}

</script>
<?php include_once('../includes/footer.php'); ?>
