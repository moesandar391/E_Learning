<?php
require_once '../config/db.php';
require_once '../includes/enrollment_check.php';
require_once '../includes/module_path_helper.php';
include_once('../includes/header.php');

 $module_id = isset($_GET['module_id']) ? (int)$_GET['module_id'] : 0;

 $stmt = $conn->prepare("
    SELECT m.id AS module_id, m.name AS module_name, m.image AS module_image, m.price,
           m.description, m.requirements, m.what_includes, m.who_is_for,
           c.id AS course_id, c.course_name, m.level, c.instructor_name,
           p.id AS prev_module_id, p.name AS prev_module_name, p.level AS prev_level,
           pc.course_name AS prev_course_name,
           COUNT(DISTINCT l.id) AS total_lessons
    FROM modules m
    JOIN courses c ON m.course_id = c.id
    LEFT JOIN modules p ON p.id = m.recommended_prev_module_id
    LEFT JOIN courses pc ON pc.id = p.course_id
    LEFT JOIN lessons l ON m.id = l.module_id
    WHERE m.id = ? AND m.status = 'active'
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
WHERE m.course_id = ? AND m.id != ? AND m.status = 'active'
GROUP BY m.id
LIMIT 3
");

 $relatedStmt->bind_param("ii", $module['course_id'], $module_id);
 $relatedStmt->execute();
 $relatedModules = $relatedStmt->get_result()->fetch_all(MYSQLI_ASSOC);


 $userId = isset($_SESSION['user_id']) ? $_SESSION['user_id'] : null;
 $enrollmentStatus = checkEnrollmentStatus($conn, $userId, $module_id);
?>

<section class="min-h-screen bg-gray-50 pt-10 pb-16">
    <div class="max-w-7xl mx-auto">

        <a href="viewAllCourses.php" class="inline-flex items-center gap-2 text-sm text-gray-500 hover:text-brandOrange mb-8 ml-6 transition-colors">
            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 19l-7-7 7-7"/></svg>
            Back to Courses
        </a>
         <!-- === BACK BUTTON ADDED HERE === -->
        <!-- <div class="mb-8">
            <a href="javascript:history.back()" class="inline-flex items-center gap-2 bg-white border-2 border-gray-200 text-slate-700 hover:text-white hover:bg-brandOrange hover:border-brandOrange px-4 py-2 mb-8 ml-6 rounded-xl shadow-sm transition-all duration-300 text-sm font-semibold">
                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M10 19l-7-7m0 0l7-7m-7 7h18"></path>
                </svg>
                Back
            </a>
        </div> -->
        <!-- === END BACK BUTTON === -->

        <div class="space-y-10 px-4 sm:px-6">

            <div class="grid grid-cols-1 lg:grid-cols-2 gap-6">
                <div class="bg-white rounded-3xl overflow-hidden border border-gray-100 shadow-sm">
                    <?php if (!empty($module['module_image'])): ?>
                        <img src="../uploads/modules/<?php echo htmlspecialchars($module['module_image']); ?>" alt="<?php echo htmlspecialchars($module['module_name']); ?>" class="w-full h-56 object-cover">
                    <?php else: ?>
                        <div class="w-full h-56 bg-gradient-to-br from-orange-100 to-amber-50 flex items-center justify-center">
                            <svg class="w-16 h-16 text-orange-200" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M12 6.253v13m0-13C10.832 5.477 9.246 5 7.5 5S4.168 5.477 3 6.253v13C4.168 18.477 5.754 18 7.5 18s3.332.477 4.5 1.253m0-13C13.168 5.477 14.754 5 16.5 5c1.747 0 3.332.477 4.5 1.253v13C19.832 18.477 18.247 18 16.5 18c-1.746 0-3.332.477-4.5 1.253"/></svg>
                        </div>
                    <?php endif; ?>
                    <div class="p-5">
                        <span class="inline-block bg-brandOrange/10 text-brandOrange text-[10px] font-bold uppercase px-3 py-1.5 rounded-full mb-2">
                            <?php echo htmlspecialchars($module['course_name']); ?>
                        </span>
                        <h1 class="font-serif font-bold text-xl md:text-2xl text-brandOchre mt-1">
                            <?php echo htmlspecialchars($module['module_name']); ?>
                        </h1>
                        <div class="flex flex-wrap items-center gap-3 mt-3 text-xs text-gray-500">
                            <span class="flex items-center gap-1.5">
                                <svg class="w-3.5 h-3.5 text-brandOrange" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z"/></svg>
                                <?php echo htmlspecialchars($module['instructor_name']); ?>
                            </span>
                            <span class="flex items-center gap-1.5">
                                <svg class="w-3.5 h-3.5 text-brandOrange" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"/></svg>
                                <?php echo (int)$module['total_lessons']; ?> Lessons
                            </span>
                            <span class="flex items-center gap-1.5">
                                <svg class="w-3.5 h-3.5 text-brandOrange" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m5.618-4.016A11.955 11.955 0 0112 2.944a11.955 11.955 0 01-8.618 3.04A12.02 12.02 0 003 9c0 5.591 3.824 10.29 9 11.622 5.176-1.332 9-6.03 9-11.622 0-1.042-.133-2.052-.382-3.016z"/></svg>
                                <span class="capitalize"><?php echo htmlspecialchars($module['level']); ?></span>
                            </span>
                        </div>
                    </div>
                </div>
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
                        <li class="flex items-center gap-3">
                            <svg class="w-5 h-5 text-green-500" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"/></svg>
                            <span>Self-paced learning with no time limits</span>
                        </li>
                    </ul>
                    <div class="mt-auto">
                        <?php 
                        $statusLower = $enrollmentStatus ? strtolower($enrollmentStatus) : false;
                        
                        if ($statusLower === 'pending' || $statusLower === 'needs_correction') {
                            echo '<a href="javascript:void(0)" 
                                   class="block w-full text-center font-bold text-sm py-4 rounded-xl bg-yellow-500 text-white cursor-not-allowed opacity-80 transition-all shadow-[0_4px_12px_rgba(234,179,8,0.3)]">
                                    ⏳ Waiting for Confirmation
                                  </a>';
                        } elseif ($statusLower === 'confirmed') {
                            echo '<a href="lesson.php?module_id=' . $module_id . '" 
                                   class="block w-full text-center text-white font-bold text-sm py-4 rounded-xl bg-green-600 hover:bg-green-700 transition-all shadow-[0_4px_12px_rgba(22,163,74,0.3)]">
                                    ▶ Learn Now
                                  </a>';
                        } elseif (!$userId) {
                            echo '<a href="../auth/login.php?module_id=' . urlencode($module_id) . '"
                                   class="block w-full text-center text-white font-bold text-sm py-4 rounded-xl bg-brandOrange hover:bg-brandOrangeHover transition-all shadow-[0_4px_12px_rgba(255,138,0,0.3)]">
                                    Login to Enroll
                                  </a>';
                        } else {
                            echo '<a href="enroll.php?module_id=' . $module_id . '" 
                                   class="block w-full text-center text-white font-bold text-sm py-4 rounded-xl bg-brandOrange hover:bg-brandOrangeHover transition-all shadow-[0_4px_12px_rgba(255,138,0,0.3)]">
                                    Enroll Now
                                  </a>';
                        }
                        ?>
                    </div>
                </div>
            </div>

            <?php
            $prevProgress = !empty($module['prev_module_id']) ? getUserModuleProgress($conn, $userId, (int)$module['prev_module_id']) : null;
            ?>
            <div class="bg-white rounded-3xl p-6 sm:p-8 border border-gray-100 shadow-sm">
                <div class="flex flex-col lg:flex-row lg:items-center gap-5">
                    <div class="flex-1">
                        <div class="flex items-center gap-2 mb-2">
                            <svg class="w-5 h-5 text-brandOrange" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 10V3L4 14h7v7l9-11h-7z"/></svg>
                            <h2 class="font-serif font-bold text-xl text-gray-700">Flexible Learning Path</h2>
                        </div>
                        <p class="text-sm text-gray-500 leading-relaxed">
                            This module is <?php echo htmlspecialchars($module['level'] ?? 'level not set'); ?> level. You can enroll in it at any time —
                            the structure below is a <strong>recommendation, not a requirement</strong>.
                        </p>
                    </div>
                    <div class="flex items-center gap-3">
                        <?php if (!empty($module['prev_module_id'])): ?>
                            <a href="details.php?module_id=<?php echo (int)$module['prev_module_id']; ?>"
                               class="flex-1 lg:flex-none text-center px-5 py-3 rounded-xl border border-gray-300 text-gray-600 text-sm font-semibold hover:bg-gray-100 transition">
                                <span class="block text-[10px] uppercase tracking-wider text-gray-400">Optional previous</span>
                                <?php echo htmlspecialchars($module['prev_course_name'] ?? ''); ?> — <?php echo htmlspecialchars($module['prev_module_name']); ?>
                                <?php if (!empty($module['prev_level'])): ?>
                                    <span class="block text-[10px] mt-0.5 font-normal"><?php echo htmlspecialchars($module['prev_level']); ?> level</span>
                                <?php endif; ?>
                            </a>
                            <svg class="w-6 h-6 text-brandOrange flex-shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 7l5 5m0 0l-5 5m5-5H6"/></svg>
                        <?php else: ?>
                            <span class="inline-flex items-center gap-1.5 px-4 py-2 rounded-full text-xs font-medium bg-green-50 text-green-700">
                                <span class="w-1.5 h-1.5 rounded-full bg-green-500"></span>No previous module — start here
                            </span>
                        <?php endif; ?>
                        <span class="inline-flex items-center gap-1.5 px-4 py-2 rounded-full text-xs font-medium bg-brandOrange/10 text-brandOrange">
                            <?php echo htmlspecialchars($module['level'] ?? '—'); ?>
                        </span>
                    </div>
                </div>
                <?php if (!empty($module['prev_module_id']) && $prevProgress): ?>
                    <div class="mt-4 pt-4 border-t border-gray-100">
                        <?php if ($prevProgress === 'completed'): ?>
                            <span class="inline-flex items-center gap-1.5 text-sm font-medium text-green-600">
                                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"/></svg>
                                You've completed "<?php echo htmlspecialchars($module['prev_module_name']); ?>" — you're ready for this module.
                            </span>
                        <?php elseif ($prevProgress === 'in_progress'): ?>
                            <span class="inline-flex items-center gap-1.5 text-sm font-medium text-orange-600">
                                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"/></svg>
                                You're learning "<?php echo htmlspecialchars($module['prev_module_name']); ?>" — you can continue here whenever you like.
                            </span>
                        <?php else: ?>
                            <span class="inline-flex items-center gap-1.5 text-sm font-medium text-gray-500">
                                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 16h-1v-4h-1m1-4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"/></svg>
                                You haven't started "<?php echo htmlspecialchars($module['prev_module_name']); ?>" yet — optional, but useful preparation.
                            </span>
                        <?php endif; ?>
                    </div>
                <?php endif; ?>
            </div>

            <div class="grid grid-cols-1 lg:grid-cols-2 gap-6">
                <div class="bg-white rounded-3xl p-8 border border-gray-100 shadow-sm flex flex-col h-full">
                    <h2 class="font-serif font-bold text-2xl text-gray-500 mb-4">About This Module</h2>
                    <p class="text-gray-600 leading-relaxed flex-grow">
                        <?php echo nl2br(htmlspecialchars($module['description'] ?? 'No description available.')); ?>
                    </p>
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

            <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
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
            </div>

            <?php if (!empty($relatedModules)): ?>
            <div>
                <h3 class="font-serif font-bold text-xl text-gray-500 mb-6">Related Modules</h3>
                <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-3 gap-6">
                    <?php foreach ($relatedModules as $rel): ?>
                        <a href="details.php?module_id=<?php echo $rel['module_id']; ?>" class="bg-white rounded-3xl overflow-hidden border border-gray-100 shadow-sm group hover:shadow-md transition">
                            <div class="h-40 bg-gradient-to-br from-orange-50 to-amber-50 overflow-hidden">
                                <?php if (!empty($rel['module_image'])): ?>
                                    <img src="../uploads/modules/<?php echo htmlspecialchars($rel['module_image']); ?>" alt="" class="w-full h-full object-cover group-hover:scale-105 transition">
                                <?php else: ?>
                                    <div class="w-full h-full flex items-center justify-center">
                                        <svg class="w-12 h-12 text-orange-200" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M12 6.253v13m0-13C10.832 5.477 9.246 5 7.5 5S4.168 5.477 3 6.253v13C4.168 18.477 5.754 18 7.5 18s3.332.477 4.5 1.253m0-13C13.168 5.477 14.754 5 16.5 5c1.747 0 3.332.477 4.5 1.253v13C19.832 18.477 18.247 18 16.5 18c-1.746 0-3.332.477-4.5 1.253"/></svg>
                                    </div>
                                <?php endif; ?>
                            </div>
                            <div class="p-5">
                                <p class="text-xs text-brandOrange font-medium uppercase tracking-wider"><?php echo htmlspecialchars($rel['course_name']); ?></p>
                                <p class="text-sm font-semibold text-gray-800 mt-1 group-hover:text-brandOrange transition-colors"><?php echo htmlspecialchars($rel['module_name']); ?></p>
                                <p class="text-xs text-gray-400 mt-2"><?php echo (int)$rel['total_lessons']; ?> lessons <?php if (!empty($rel['price']) && $rel['price'] > 0): ?>&middot; <?php echo number_format($rel['price']); ?> MMK<?php endif; ?></p>
                            </div>
                        </a>
                    <?php endforeach; ?>
                </div>
            </div>
            <?php endif; ?>

        </div>
    </div>
</section>

<?php include_once('../includes/footer.php'); ?>