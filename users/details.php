<?php
require_once '../config/db.php';
include_once('../includes/header.php');

$module_id = isset($_GET['module_id']) ? (int)$_GET['module_id'] : 0;

$stmt = $conn->prepare("
    SELECT m.id AS module_id, m.name AS module_name, m.image AS module_image, m.price,
           m.description, m.requirements, m.what_includes, m.who_is_for,
           c.id AS course_id, c.course_name, c.level, c.instructor_name,
           COUNT(DISTINCT l.id) AS total_lessons
    FROM modules m
    JOIN courses c ON m.course_id = c.id
    LEFT JOIN lessons l ON m.id = l.module_id
    WHERE m.id = ?
    GROUP BY m.id
");
$stmt->bind_param("i", $module_id);
$stmt->execute();
$module = $stmt->get_result()->fetch_assoc();

if (!$module) {
    echo '<div class="min-h-screen flex items-center justify-center"><p class="text-gray-500 text-lg">Module not found.</p></div>';
    include_once('../includes/footer.php');
    exit;
}
$relatedStmt = $conn->prepare("
SELECT m.id AS module_id, m.name AS module_name, m.image AS module_image, m.price,
c.course_name, COUNT(l.id) AS total_lessons
FROM modules m
JOIN courses c ON m.course_id = c.id
LEFT JOIN lessons l ON m.id = l.module_id
WHERE m.course_id = ? AND m.id != ?
GROUP BY m.id
LIMIT 3
");

$relatedStmt->bind_param("ii", $module['course_id'], $module_id);
$relatedStmt->execute();
$relatedModules = $relatedStmt->get_result()->fetch_all(MYSQLI_ASSOC);


$isLoggedIn = isset($_SESSION['user_id']);
$isEnrolled = false;
if ($isLoggedIn) {
    $userId = $_SESSION['user_id'];
    $enrollCheck = $conn->prepare("SELECT id FROM enrollments WHERE user_id = ? AND module_id = ? AND status = 'confirmed'");
    $enrollCheck->bind_param("ii", $userId, $module_id);
    $enrollCheck->execute();
    $isEnrolled = $enrollCheck->get_result()->num_rows > 0;
}
?>

<section class="min-h-screen bg-gray-50 pt-10 pb-16">
    <div class="max-w-6xl mx-auto px-6">

        <a href="viewAllCourses.php" class="inline-flex items-center gap-2 text-sm text-gray-500 hover:text-brandOrange mb-8 transition-colors">
            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 19l-7-7 7-7"/></svg>
            Back to Courses
        </a>

        <div class="space-y-10">

            <div class="bg-white rounded-3xl overflow-hidden border border-gray-100 shadow-sm">
                <div class="relative h-72 bg-gradient-to-br from-orange-100 to-amber-50">
                    <?php if (!empty($module['module_image'])): ?>
                        <img src="../uploads/modules/<?php echo htmlspecialchars($module['module_image']); ?>" alt="<?php echo htmlspecialchars($module['module_name']); ?>" class="w-full h-full object-cover">
                        <div class="absolute inset-0 bg-gradient-to-t from-black/50 to-transparent"></div>
                    <?php endif; ?>
                    <div class="absolute bottom-0 left-0 right-0 p-8">
                        <span class="inline-block bg-white/90 text-brandOrange text-[10px] font-bold uppercase px-3 py-1.5 rounded-full mb-3">
                            <?php echo htmlspecialchars($module['course_name']); ?>
                        </span>
                        <h1 class="font-serif font-bold text-3xl md:text-4xl text-white drop-shadow-lg">
                            <?php echo htmlspecialchars($module['module_name']); ?>
                        </h1>
                    </div>
                </div>
                <div class="p-8 flex flex-wrap items-center gap-6 text-sm text-gray-500 border-b border-gray-100">
                    <div class="flex items-center gap-2">
                        <svg class="w-5 h-5 text-brandOrange" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z"/></svg>
                        <span class="font-medium text-gray-700"><?php echo htmlspecialchars($module['instructor_name']); ?></span>
                    </div>
                    <div class="flex items-center gap-2">
                        <svg class="w-5 h-5 text-brandOrange" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"/></svg>
                        <span><?php echo (int)$module['total_lessons']; ?> Lessons</span>
                    </div>
                    <div class="flex items-center gap-2">
                        <svg class="w-5 h-5 text-brandOrange" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m5.618-4.016A11.955 11.955 0 0112 2.944a11.955 11.955 0 01-8.618 3.04A12.02 12.02 0 003 9c0 5.591 3.824 10.29 9 11.622 5.176-1.332 9-6.03 9-11.622 0-1.042-.133-2.052-.382-3.016z"/></svg>
                        <span class="capitalize"><?php echo htmlspecialchars($module['level']); ?></span>
                    </div>
                </div>
            </div>
                          
            <div class="grid grid-cols-1 lg:grid-cols-2 gap-6 items-start">
    
    <!-- Price / Enrollment Card -->
    <div class="bg-white rounded-3xl p-8 border border-gray-100 shadow-sm flex flex-col h-full">
        <div class="text-center mb-6">
            <?php if (!empty($module['price']) && $module['price'] > 0): ?>
                <p class="text-4xl font-bold text-brandOrange"><?php echo number_format($module['price']); ?> <span class="text-lg text-gray-400 font-normal">MMK</span></p>
            <?php else: ?>
                <p class="text-4xl font-bold text-green-500">Free</p>
            <?php endif; ?>
        </div>

        <ul class="space-y-3 mb-8 text-sm text-gray-600 flex-grow">
            <li class="flex items-center gap-3">
                <svg class="w-5 h-5 text-green-500" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"/></svg>
                <span><?php echo (int)$module['total_lessons']; ?> on-demand lessons</span>
            </li>
            <li class="flex items-center gap-3">
                <svg class="w-5 h-5 text-green-500" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"/></svg>
                <span>Full lifetime access</span>
            </li>
            <li class="flex items-center gap-3">
                <svg class="w-5 h-5 text-green-500" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"/></svg>
                <span>Certificate of completion</span>
            </li>
            <li class="flex items-center gap-3">
                <svg class="w-5 h-5 text-green-500" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"/></svg>
                <span>Access on mobile and desktop</span>
            </li>
        </ul>

        <div class="mt-auto">
            <a href="<?php echo $isEnrolled ? 'lesson.php' : ($isLoggedIn ? 'enroll.php?module_id=' . $module_id : '../auth/login.php?redirect=' . urlencode('../users/enroll.php?module_id=' . $module_id)); ?>"
               class="block w-full text-center text-white font-bold text-sm py-4 rounded-xl bg-brandOrange hover:bg-brandOrangeHover transition-all shadow-[0_4px_12px_rgba(255,138,0,0.3)]">
                <?php echo $isEnrolled ? 'Learn Now' : ($isLoggedIn ? 'Enroll Now' : 'Login to Enroll'); ?>
            </a>
        </div>
    </div>

    <!-- Related Modules Card -->
    <?php if (!empty($relatedModules)): ?>
    <div class="bg-white rounded-3xl p-8 border border-gray-100 shadow-sm flex flex-col h-full">
        <h3 class="font-serif font-bold text-xl text-gray-500 mb-6">Related Modules</h3>
        <div class="space-y-4 flex-grow">
            <?php foreach ($relatedModules as $rel): ?>
                <a href="details.php?module_id=<?php echo $rel['module_id']; ?>" class="flex items-center gap-4 p-3 rounded-xl hover:bg-gray-50 transition-colors group">
                    <div class="w-16 h-16 rounded-xl bg-gradient-to-br from-orange-50 to-amber-50 flex-shrink-0 overflow-hidden">
                        <?php if (!empty($rel['module_image'])): ?>
                            <img src="../uploads/modules/<?php echo htmlspecialchars($rel['module_image']); ?>" alt="" class="w-full h-full object-cover">
                        <?php else: ?>
                            <div class="w-full h-full flex items-center justify-center">
                                <svg class="w-8 h-8 text-orange-200" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 6.253v13m0-13C10.832 5.477 9.246 5 7.5 5S4.168 5.477 3 6.253v13C4.168 18.477 5.754 18 7.5 18s3.332.477 4.5 1.253m0-13C13.168 5.477 14.754 5 16.5 5c1.747 0 3.332.477 4.5 1.253v13C19.832 18.477 18.247 18 16.5 18c-1.746 0-3.332.477-4.5 1.253"/></svg>
                            </div>
                        <?php endif; ?>
                    </div>
                    <div class="flex-1 min-w-0">
                        <p class="text-xs text-brandOrange font-medium uppercase tracking-wider"><?php echo htmlspecialchars($rel['course_name']); ?></p>
                        <p class="text-sm font-semibold text-gray-800 truncate group-hover:text-brandOrange transition-colors"><?php echo htmlspecialchars($rel['module_name']); ?></p>
                        <p class="text-xs text-gray-400"><?php echo (int)$rel['total_lessons']; ?> lessons <?php if (!empty($rel['price']) && $rel['price'] > 0): ?>&middot; <?php echo number_format($rel['price']); ?> MMK<?php endif; ?></p>
                    </div>
                </a>
            <?php endforeach; ?>
        </div>
    </div>
    <?php endif; ?>

</div>

            <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                <div class="bg-white rounded-3xl p-8 border border-gray-100 shadow-sm flex flex-col h-full">
                    <h2 class="font-serif font-bold text-2xl text-gray-500 mb-4">About This Module</h2>
                    <p class="text-gray-600 leading-relaxed flex-grow">
                        <?php echo nl2br(htmlspecialchars($module['description'] ?? 'No description available.')); ?>
                    </p>
                </div>

                <div class="bg-white rounded-3xl p-8 border border-gray-100 shadow-sm flex flex-col h-full">
                    <h2 class="font-serif font-bold text-2xl text-gray-500 mb-4">What This Module Includes</h2>
                    <div class="flex-grow">
                        <?php if (!empty($module['what_includes'])): ?>
                            <ul class="space-y-3">
                                <?php foreach (explode("\n", $module['what_includes']) as $item): ?>
                                    <?php $item = trim($item); if (empty($item)) continue; ?>
                                    <li class="flex items-start gap-3 text-gray-600">
                                        <svg class="w-5 h-5 text-green-500 mt-0.5 flex-shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"/></svg>
                                        <span><?php echo htmlspecialchars($item); ?></span>
                                    </li>
                                <?php endforeach; ?>
                            </ul>
                        <?php endif; ?>
                    </div>
                </div>

                <div class="bg-white rounded-3xl p-8 border border-gray-100 shadow-sm flex flex-col h-full">
                    <h2 class="font-serif font-bold text-2xl text-gray-500 mb-4">Requirements</h2>
                    <div class="flex-grow">
                        <?php if (!empty($module['requirements'])): ?>
                            <ul class="space-y-3">
                                <?php foreach (explode("\n", $module['requirements']) as $item): ?>
                                    <?php $item = trim($item); if (empty($item)) continue; ?>
                                    <li class="flex items-start gap-3 text-gray-600">
                                        <svg class="w-5 h-5 text-brandOrange mt-0.5 flex-shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 16h-1v-4h-1m1-4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"/></svg>
                                        <span><?php echo htmlspecialchars($item); ?></span>
                                    </li>
                                <?php endforeach; ?>
                            </ul>
                        <?php endif; ?>
                    </div>
                </div>

                <div class="bg-white rounded-3xl p-8 border border-gray-100 shadow-sm flex flex-col h-full">
                    <h2 class="font-serif font-bold text-2xl text-gray-500 mb-4">Who This Module Is For</h2>
                    <div class="flex-grow">
                        <?php if (!empty($module['who_is_for'])): ?>
                            <ul class="space-y-3">
                                <?php foreach (explode("\n", $module['who_is_for']) as $item): ?>
                                    <?php $item = trim($item); if (empty($item)) continue; ?>
                                    <li class="flex items-start gap-3 text-gray-600">
                                        <svg class="w-5 h-5 text-blue-500 mt-0.5 flex-shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 20h5v-2a3 3 0 00-5.356-1.857M17 20H7m10 0v-2c0-.656-.126-1.283-.356-1.857M7 20H2v-2a3 3 0 015.356-1.857M7 20v-2c0-.656.126-1.283.356-1.857m0 0a5.002 5.002 0 019.288 0M15 7a3 3 0 11-6 0 3 3 0 016 0z"/></svg>
                                        <span><?php echo htmlspecialchars($item); ?></span>
                                    </li>
                                <?php endforeach; ?>
                            </ul>
                        <?php endif; ?>
                    </div>
                </div>
            </div>

        </div>
    </div>
</section>

<?php include_once('../includes/footer.php'); ?>