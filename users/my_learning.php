<?php
session_start();
require_once '../config/db.php';
include_once('../includes/header.php');

$user_id = $_SESSION['user_id'] ?? null;
if (!$user_id) {
    header('Location: ../auth/login.php');
    exit;
}

$hasProgress = $conn->query("SHOW TABLES LIKE 'lesson_progress'")->num_rows > 0;

// Courses Learning count
$learningCount = $conn->query("SELECT COUNT(*) AS total FROM enrollments WHERE user_id = $user_id AND status = 'confirmed'")->fetch_assoc()['total'] ?? 0;

// Courses Completed count
$completedCount = 0;
if ($hasProgress) {
    $comp = $conn->query("
        SELECT COUNT(*) AS total FROM (
            SELECT m.id
            FROM enrollments e
            JOIN modules m ON e.module_id = m.id
            LEFT JOIN lessons l ON m.id = l.module_id
            LEFT JOIN lesson_progress lp ON l.id = lp.lesson_id AND lp.user_id = $user_id
            WHERE e.user_id = $user_id AND e.status = 'confirmed'
            GROUP BY m.id
            HAVING COUNT(l.id) > 0 AND COUNT(lp.id) = COUNT(l.id)
        ) AS fully_completed
    ");
    $completedCount = $comp ? $comp->fetch_assoc()['total'] : 0;
}

// Certificates
$certs = $conn->query("
    SELECT DISTINCT c.course_name, m.id AS module_id, m.name AS module_name
    FROM certificates cert
    JOIN enrollments e ON cert.enroll_id = e.id
    JOIN modules m ON e.module_id = m.id
    JOIN courses c ON m.course_id = c.id
    WHERE e.user_id = $user_id
");
$certificates = $certs ? $certs->fetch_all(MYSQLI_ASSOC) : [];

// Enrolled Courses with progress and existing reviews
if ($hasProgress) {
    $stmt = $conn->prepare("
        SELECT m.id AS module_id, c.course_name, m.name AS module_name, m.image, m.price,
               COUNT(l.id) AS total_lessons,
               COUNT(lp.id) AS completed_lessons,
               r.rating AS existing_rating, r.review AS existing_review
        FROM enrollments e
        JOIN modules m ON e.module_id = m.id
        JOIN courses c ON m.course_id = c.id
        LEFT JOIN lessons l ON m.id = l.module_id
        LEFT JOIN lesson_progress lp ON l.id = lp.lesson_id AND lp.user_id = ?
        LEFT JOIN reviews r ON r.user_id = e.user_id AND r.module_id = m.id
        WHERE e.user_id = ? AND e.status = 'confirmed'
        GROUP BY m.id, c.course_name, m.name, m.image, m.price, r.rating, r.review
        ORDER BY e.created_at DESC
    ");
    $stmt->bind_param("ii", $user_id, $user_id);
} else {
    $stmt = $conn->prepare("
        SELECT m.id AS module_id, c.course_name, m.name AS module_name, m.image, m.price,
               COUNT(l.id) AS total_lessons, 0 AS completed_lessons,
               r.rating AS existing_rating, r.review AS existing_review
        FROM enrollments e
        JOIN modules m ON e.module_id = m.id
        JOIN courses c ON m.course_id = c.id
        LEFT JOIN lessons l ON m.id = l.module_id
        LEFT JOIN reviews r ON r.user_id = e.user_id AND r.module_id = m.id
        WHERE e.user_id = ? AND e.status = 'confirmed'
        GROUP BY m.id, c.course_name, m.name, m.image, m.price, r.rating, r.review
        ORDER BY e.created_at DESC
    ");
    $stmt->bind_param("i", $user_id);
}
$stmt->execute();
$enrolledCourses = $stmt->get_result()->fetch_all(MYSQLI_ASSOC);

// User's existing reviews
$userReviews = $conn->query("
    SELECT r.rating, r.review, r.created_at, m.name AS module_name, c.course_name
    FROM reviews r
    JOIN modules m ON r.module_id = m.id
    JOIN courses c ON m.course_id = c.id
    WHERE r.user_id = $user_id
    ORDER BY r.created_at DESC
");
$userReviews = $userReviews ? $userReviews->fetch_all(MYSQLI_ASSOC) : [];

// Waiting enrollments (payment done, awaiting admin confirmation)
 $stmtWaiting = $conn->prepare("
    SELECT m.id AS module_id, c.course_name, m.name AS module_name,
           m.image, m.price, e.created_at
    FROM enrollments e
    JOIN modules m ON e.module_id = m.id
    JOIN courses c ON m.course_id = c.id
    WHERE e.user_id = ? AND e.status = 'pending'
    ORDER BY e.created_at DESC
");
 $stmtWaiting->bind_param("i", $user_id);
 $stmtWaiting->execute();
 $waitingEnrollments = $stmtWaiting->get_result()->fetch_all(MYSQLI_ASSOC);
?>

<div class="min-h-screen bg-gray-50 py-12 px-4 sm:px-6 lg:px-8">
    <div class="max-w-7xl mx-auto">
        <h1 class="text-2xl sm:text-3xl font-bold text-brandOchre mb-6 sm:mb-8 px-4 sm:px-8">My Dashboard</h1>

        <?php if (isset($_SESSION['enroll_pending_message'])): ?>
            <div class="mx-4 sm:mx-8 mb-6 flex items-start gap-3 bg-yellow-50 border border-yellow-200 text-yellow-800 rounded-2xl px-5 py-4 text-sm">
                <svg class="w-5 h-5 text-yellow-600 flex-shrink-0 mt-0.5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4m0 4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"/></svg>
                <span><?php echo htmlspecialchars($_SESSION['enroll_pending_message']); ?></span>
            </div>
            <?php unset($_SESSION['enroll_pending_message']); ?>
        <?php endif; ?>

        <!-- Stats Cards -->
        <div class="flex flex-col sm:flex-row gap-4 items-stretch sm:items-center mb-6 sm:mb-8 px-4 sm:px-8">
            <div class="bg-white p-6 rounded-2xl border border-gray-200 text-center flex-1 shadow-sm">
                <p class="text-blue-500 text-3xl font-bold"><?= $learningCount ?></p>
                <p class="text-gray-400 text-[10px] uppercase font-bold tracking-wider">Courses Learning</p>
            </div>
            <div class="bg-white p-6 rounded-2xl border border-gray-200 text-center flex-1 shadow-sm">
                <p class="text-green-500 text-3xl font-bold"><?= $completedCount ?></p>
                <p class="text-gray-400 text-[10px] uppercase font-bold tracking-wider">Courses Completed</p>
            </div>
        </div>

        <!-- My Certificates -->
         <?php 
// Filter to get only unique certificates by course name
$uniqueCertificates = [];
$seenCourses = [];

foreach ($certificates as $cert) {
    if (!in_array($cert['course_name'], $seenCourses)) {
        $uniqueCertificates[] = $cert;
        $seenCourses[] = $cert['course_name'];
    }
}
?>
        <div class="bg-white p-6 sm:p-8 rounded-2xl border border-gray-200 mb-6 sm:mb-8 mx-4 sm:mx-8">
            <div class="flex items-center justify-between">
                <h3 class="text-lg sm:text-xl font-bold text-brandOrange mb-6">My Certificates</h3>
                <a href="quiz_history.php" class="inline-flex items-center gap-1.5 text-sm font-semibold text-brandOrange border border-brandOrange rounded-lg px-3 py-1.5 mb-6 hover:bg-orange-50 transition">
                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"/></svg>
                    Quiz History
                </a>
            </div>
            <?php if (!empty($certificates)): ?>
                <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-4 gap-4">
                    <?php foreach ($uniqueCertificates as $cert): ?>
                        <div class="relative overflow-hidden p-6 border-2 border-orange-100 rounded-2xl bg-gradient-to-br from-orange-50 to-white text-center shadow-sm hover:shadow-md transition-shadow">
                            <span class="text-5xl mb-4 block">🎓</span>
                            <p class="text-sm font-bold text-gray-800 uppercase tracking-wide leading-tight"><?= htmlspecialchars($cert['module_name']) ?></p>
                            <p class="text-[10px] text-gray-500 mt-2"><?= htmlspecialchars($cert['course_name']) ?></p>
                        </div>
                    <?php endforeach; ?>
                </div>
            <?php else: ?>
                <div class="text-center py-12 border-2 border-dashed border-gray-200 rounded-2xl">
                    <svg class="w-20 h-20 text-gray-200 mx-auto mb-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M9 12l2 2 4-4M7.835 4.697a3.42 3.42 0 001.946-.806 3.42 3.42 0 014.438 0 3.42 3.42 0 001.946.806 3.42 3.42 0 013.138 3.138 3.42 3.42 0 00.806 1.946 3.42 3.42 0 010 4.438 3.42 3.42 0 00-.806 1.946 3.42 3.42 0 01-3.138 3.138 3.42 3.42 0 00-1.946.806 3.42 3.42 0 01-4.438 0 3.42 3.42 0 00-1.946-.806 3.42 3.42 0 01-3.138-3.138 3.42 3.42 0 00-.806-1.946 3.42 3.42 0 010-4.438 3.42 3.42 0 00.806-1.946 3.42 3.42 0 013.138-3.138z"></path></svg>
                    <p class="text-base font-bold text-gray-500">No Certificates Yet</p>
                    <p class="text-sm text-gray-400 mt-1 max-w-sm mx-auto">Complete your enrolled courses to earn official certificates.</p>
                    <a href="courses.php" class="mt-5 inline-block text-sm font-bold text-brandOrange hover:underline">Browse Courses</a>
                </div>
            <?php endif; ?>
        </div>

<!-- Waiting Enrollments -->
<div class="bg-white p-6 sm:p-8 rounded-2xl border border-gray-200 mb-6 sm:mb-8 mx-4 sm:mx-8">
    <h3 class="text-lg sm:text-xl font-bold text-brandOrange mb-6">Waiting</h3>
    <?php if (!empty($waitingEnrollments)): ?>
        <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
            <?php foreach ($waitingEnrollments as $waiting): ?>
                <div class="p-5 border border-yellow-200 rounded-2xl bg-yellow-50/50 transition-all duration-200">
                    <div class="flex items-start gap-4">
                        <?php if (!empty($waiting['image'])): ?>
                        <img src="../uploads/modules/<?= htmlspecialchars($waiting['image']) ?>"
                             alt="<?= htmlspecialchars($waiting['module_name']) ?>"
                             class="w-20 h-20 sm:w-24 sm:h-24 rounded-2xl object-cover border border-yellow-200 flex-shrink-0"
                             onerror="this.style.display='none'">
                        <?php endif; ?>
                        <div class="flex-1 min-w-0">
                            <div class="flex items-start justify-between gap-2">
                                <div class="pr-4">
                                    <h4 class="font-bold text-gray-800 leading-snug">
                                        <?= htmlspecialchars($waiting['module_name']) ?>
                                    </h4>
                                    <p class="text-[11px] text-gray-400 uppercase tracking-wider mt-1"><?= htmlspecialchars($waiting['course_name']) ?></p>
                                </div>
                                <span class="inline-flex items-center gap-1.5 text-xs font-bold text-yellow-700 bg-yellow-100 px-2 py-1 rounded-full whitespace-nowrap flex-shrink-0">
                                    <svg class="w-3 h-3 animate-spin" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <circle class="opacity-25" cx="12" cy="12" r="10" stroke-width="4"></circle>
                                        <path class="opacity-75" stroke-linecap="round" stroke-linejoin="round" stroke-width="4" d="M12 2a10 10 0 0110 10"></path>
                                    </svg>
                                    Waiting
                                </span>
                            </div>
                            <div class="mt-4 flex items-center gap-2 text-xs text-gray-500">
                                <svg class="w-3.5 h-3.5 text-yellow-500 flex-shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"></path></svg>
                                <span>Payment received. Awaiting admin confirmation to start learning.</span>
                            </div>
                        </div>
                    </div>
                </div>
            <?php endforeach; ?>
        </div>
    <?php else: ?>
        <div class="text-center py-10">
            <p class="text-gray-400 text-sm">No enrollments awaiting confirmation.</p>
        </div>
    <?php endif; ?>
</div>

        <!-- Enrolled Courses -->
        <div class="bg-white p-6 sm:p-8 rounded-2xl border border-gray-200 mx-4 sm:mx-8">
            <h3 class="text-lg sm:text-xl font-bold text-brandOrange mb-6">Enrolled Courses</h3>
            <?php if (count($enrolledCourses) > 0): ?>
                <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                    <?php foreach ($enrolledCourses as $course):
                        $progress = 0;
                        if ($course['total_lessons'] > 0) {
                            $progress = round(($course['completed_lessons'] / $course['total_lessons']) * 100);
                        }
                        $barColor = 'bg-gray-200';
                        $textColor = 'text-gray-500';
                        if ($progress > 0 && $progress < 100) {
                            $barColor = 'bg-brandOrange';
                            $textColor = 'text-brandOrange';
                        } elseif ($progress == 100) {
                            $barColor = 'bg-green-500';
                            $textColor = 'text-green-600';
                        }
                        $hasReview = !is_null($course['existing_rating']);
                    ?>
                    <div class="p-5 border border-gray-100 rounded-2xl hover:shadow-lg hover:border-brandOrange/30 transition-all duration-200 bg-gray-50/50">
                        <div class="flex items-start gap-4">
                            <?php if (!empty($course['image'])): ?>
                            <img src="../uploads/modules/<?= htmlspecialchars($course['image']) ?>"
                                 alt="<?= htmlspecialchars($course['module_name']) ?>"
                                 class="w-20 h-20 sm:w-24 sm:h-24 rounded-2xl object-cover border border-gray-200 flex-shrink-0"
                                 onerror="this.style.display='none'">
                            <?php endif; ?>
                            <div class="flex-1 min-w-0">
                                <a href="lesson.php?module_id=<?= $course['module_id'] ?>" class="block group">
                                    <div class="flex items-start justify-between gap-2 mb-4">
                                        <div class="pr-4">
                                            <h4 class="font-bold text-gray-800 group-hover:text-brandOrange transition-colors leading-snug">
                                                <?= htmlspecialchars($course['module_name']) ?>
                                            </h4>
                                            <p class="text-[11px] text-gray-400 uppercase tracking-wider mt-1"><?= htmlspecialchars($course['course_name']) ?></p>
                                        </div>
                                        <?php if ($progress == 100): ?>
                                            <span class="text-xs font-bold text-green-600 bg-green-50 px-2 py-1 rounded-full flex-shrink-0">Completed</span>
                                        <?php endif; ?>
                                    </div>
                                    <?php if ($hasProgress): ?>
                                    <div class="space-y-2">
                                        <div class="flex justify-between text-xs">
                                            <span class="text-gray-500">Progress</span>
                                            <span class="font-bold <?= $textColor ?>"><?= $course['completed_lessons'] ?>/<?= $course['total_lessons'] ?> Lessons</span>
                                        </div>
                                        <div class="w-full bg-gray-200 rounded-full h-2 overflow-hidden">
                                            <div class="h-full <?= $barColor ?> rounded-full transition-all duration-700 ease-out" style="width: <?= $progress ?>%;"></div>
                                        </div>
                                    </div>
                                    <?php endif; ?>
                                </a>
                                <div class="mt-4 flex items-center justify-between gap-2">
                                    <a href="lesson.php?module_id=<?= $course['module_id'] ?>"
                                       class="text-xs text-brandOrange font-semibold flex items-center gap-1 hover:text-brandOrangeHover transition-colors">
                                        <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M14 5l7 7m0 0l-7 7m7-7H3"></path></svg>
                                        Continue Learning
                                    </a>
                                    <?php if ($progress == 100 && !$hasReview): ?>
                                        <a href="my_review.php?write=1"
                                           class="text-xs font-bold px-3 py-1.5 rounded-lg transition-colors whitespace-nowrap bg-brandOrange text-white hover:bg-brandOrangeHover">
                                            Write Review
                                        </a>
                                    <?php elseif ($progress == 100 && $hasReview): ?>
                                        <span class="text-xs font-bold text-green-600 bg-green-50 px-3 py-1.5 rounded-lg whitespace-nowrap">Reviewed</span>
                                    <?php endif; ?>
                                </div>
                            </div>
                        </div>
                    </div>
                    <?php endforeach; ?>
                </div>
            <?php else: ?>
                <div class="text-center py-10">
                    <svg class="w-16 h-16 text-gray-200 mx-auto mb-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M12 6.253v13m0-13C10.832 5.477 9.246 5 7.5 5S4.168 5.477 3 6.253v13C4.168 18.477 5.754 18 7.5 18s3.332.477 4.5 1.253m0-13C13.168 5.477 14.754 5 16.5 5c1.747 0 3.332.477 4.5 1.253v13C19.832 18.477 18.247 18 16.5 18c-1.746 0-3.332.477-4.5 1.253"></path></svg>
                    <p class="text-gray-500">You are not enrolled in any courses yet.</p>
                    <a href="courses.php" class="mt-3 inline-block text-sm font-bold text-brandOrange hover:underline">Browse Courses</a>
                </div>
            <?php endif; ?>
        </div>


    </div>
</div>

<?php include_once('../includes/footer.php'); ?>
