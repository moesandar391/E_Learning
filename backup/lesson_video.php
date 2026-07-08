<?php
session_start();
require_once '../config/db.php';
include_once('../includes/header.php');

// 1. Get IDs for Lesson and Course
$course_id = isset($_GET['course_id']) ? (int)$_GET['course_id'] : 1;
$lesson_id = isset($_GET['lesson_id']) ? (int)$_GET['lesson_id'] : null;

// 2. Fetch all lessons to list in the outline
$stmt = $conn->prepare("SELECT * FROM lessons WHERE course_id = ? ORDER BY id ASC");
$stmt->bind_param("i", $course_id);
$stmt->execute();
$allLessons = $stmt->get_result()->fetch_all(MYSQLI_ASSOC);

// 3. Logic to determine active lesson (Video + Title + Description)
$activeLesson = null;
if ($lesson_id) {
    $activeLesson = array_filter($allLessons, fn($l) => $l['id'] == $lesson_id);
    $activeLesson = reset($activeLesson);
}
// Default to the first lesson if none selected
if (!$activeLesson && !empty($allLessons)) {
    $activeLesson = $allLessons[0];
}
?>

<div class="max-w-7xl mx-auto p-6 bg-[#0b1120] text-white text-left">
    <div class="grid grid-cols-1 lg:grid-cols-12 gap-8">
        
        <main class="lg:col-span-8 space-y-6">
            <?php if ($activeLesson): ?>
                <div class="aspect-video bg-black rounded-xl border border-gray-700 overflow-hidden shadow-2xl">
                    <iframe class="w-full h-full" src="<?php echo htmlspecialchars($activeLesson['video']); ?>" allowfullscreen></iframe>
                </div>
                
                <h2 class="text-2xl font-semibold"><?php echo htmlspecialchars($activeLesson['title']); ?></h2>
                <p class="text-gray-400 leading-relaxed"><?php echo nl2br(htmlspecialchars($activeLesson['description'])); ?></p>
            <?php else: ?>
                <p>No lessons found for this course.</p>
            <?php endif; ?>
        </main>

        <aside class="lg:col-span-4">
            <div class="bg-[#111827] p-6 rounded-xl border border-gray-700">
                <h3 class="font-bold text-xl mb-4 border-b border-gray-700 pb-2">Course Outline</h3>
                <input type="text" placeholder="Search For Lessons" class="w-full bg-[#0b1120] border border-gray-700 p-2 rounded-lg mb-4 text-sm">

                <div class="space-y-4">
                    <?php
                    $currentChapter = "";
                    foreach ($allLessons as $lesson):
                        // Parse "Chapter-X" from the title
                        $parts = explode(":", $lesson['title']);
                        $chapter = trim($parts[0]);
                        $displayTitle = isset($parts[1]) ? trim($parts[1]) : $lesson['title'];

                        // If Chapter name changed, display it
                        if ($currentChapter !== $chapter): $currentChapter = $chapter; ?>
                            <p class="font-semibold text-orange-500 mt-4"><?php echo htmlspecialchars($currentChapter); ?></p>
                        <?php endif; ?>

                        <a href="lessons.php?course_id=<?php echo $course_id; ?>&lesson_id=<?php echo $lesson['id']; ?>" 
                           class="block group cursor-pointer <?php echo ($activeLesson['id'] == $lesson['id']) ? 'text-white' : 'text-gray-500 hover:text-white'; ?> transition">
                            <p class="text-sm"><?php echo htmlspecialchars($displayTitle); ?></p>
                        </a>
                    <?php endforeach; ?>
                </div>
            </div>
        </aside>
    </div>
</div>

<?php include_once('../includes/footer.php'); ?>