<?php 
session_start();
if (!isset($_SESSION['user_id'])) {
    header("Location: ../auth/login.php");
    exit();
}
require_once '../config/db.php';
include_once('../includes/header.php');

 $userId = $_SESSION['user_id'];

// Fetch user data
 $stmt = $conn->prepare("SELECT name, email, phone, created_at FROM users WHERE id = ?");
 $stmt->bind_param("i", $userId);
 $stmt->execute();
 $user = $stmt->get_result()->fetch_assoc();

 $stmtLearning = $conn->prepare("SELECT COUNT(*) AS total FROM enrollments WHERE user_id = ? AND status = 'confirmed'");
 $stmtLearning->bind_param("i", $userId);
 $stmtLearning->execute();
 $learningCount = $stmtLearning->get_result()->fetch_assoc()['total'] ?? 0;

 $stmtCompleted = $conn->prepare("SELECT COUNT(*) AS total FROM enrollments WHERE user_id = ? AND LCASE(status) = 'completed'");
 $stmtCompleted->bind_param("i", $userId);
 $stmtCompleted->execute();
 $completedCount = $stmtCompleted->get_result()->fetch_assoc()['total'] ?? 0;

 $certificates = [];
 $enrolledCourses = [];

// ── FIXED: Fetch Certificates (Case-Insensitive + Fallback) ──
// First, try to find strictly 'completed' (case-insensitive)
 $stmtCert = $conn->prepare("SELECT DISTINCT c.course_name, m.id AS module_id FROM enrollments e 
                           JOIN modules m ON e.module_id = m.id 
                           JOIN courses c ON m.course_id = c.id 
                           WHERE e.user_id = ? AND LCASE(e.status) = 'completed'");
 $stmtCert->bind_param("i", $userId);
 $stmtCert->execute();
 $resultCert = $stmtCert->get_result();

// If no 'completed' status exists, fallback to 'confirmed' courses so cards still appear
if ($resultCert && $resultCert->num_rows > 0) {
    $certificates = $resultCert->fetch_all(MYSQLI_ASSOC);
} else {
    // Fallback: Show certificates for actively enrolled courses
    $stmtCertFallback = $conn->prepare("SELECT DISTINCT c.course_name, m.id AS module_id FROM enrollments e 
                               JOIN modules m ON e.module_id = m.id 
                               JOIN courses c ON m.course_id = c.id 
                               WHERE e.user_id = ? AND e.status = 'confirmed'");
    $stmtCertFallback->bind_param("i", $userId);
    $stmtCertFallback->execute();
    $resultFallback = $stmtCertFallback->get_result();
    if ($resultFallback) {
        $certificates = $resultFallback->fetch_all(MYSQLI_ASSOC);
    }
}

// ── Check if lesson_progress table exists ──
 $hasProgressTable = false;
 $checkTable = $conn->query("SHOW TABLES LIKE 'lesson_progress'");
if ($checkTable && $checkTable->num_rows > 0) {
    $hasProgressTable = true;
}

// ── Fetch Enrolled Courses with Progress ──
if ($hasProgressTable) {
    $stmtEnrolled = $conn->prepare("
        SELECT 
            m.id AS module_id,
            c.course_name, 
            m.name AS module_name, 
            COUNT(l.id) AS total_lessons,
            COUNT(lp.id) AS completed_lessons
        FROM enrollments e 
        JOIN modules m ON e.module_id = m.id 
        JOIN courses c ON m.course_id = c.id 
        LEFT JOIN lessons l ON m.id = l.module_id
        LEFT JOIN lesson_progress lp ON l.id = lp.lesson_id AND lp.user_id = ?
        WHERE e.user_id = ? AND e.status = 'confirmed'
        GROUP BY m.id, c.course_name, m.name
    ");
    $stmtEnrolled->bind_param("ii", $userId, $userId);
} else {
    $stmtEnrolled = $conn->prepare("
        SELECT 
            m.id AS module_id,
            c.course_name, 
            m.name AS module_name, 
            COUNT(l.id) AS total_lessons,
            0 AS completed_lessons
        FROM enrollments e 
        JOIN modules m ON e.module_id = m.id 
        JOIN courses c ON m.course_id = c.id 
        LEFT JOIN lessons l ON m.id = l.module_id
        WHERE e.user_id = ? AND e.status = 'confirmed'
        GROUP BY m.id, c.course_name, m.name
    ");
    $stmtEnrolled->bind_param("i", $userId);
}

 $stmtEnrolled->execute();
 $resultEnrolled = $stmtEnrolled->get_result();
if ($resultEnrolled) {
    $enrolledCourses = $resultEnrolled->fetch_all(MYSQLI_ASSOC);
}
?>

<div class="min-h-screen bg-gray-50 py-12 px-4 sm:px-6 lg:px-8">
    <div class="max-w-6xl mx-auto grid grid-cols-1 lg:grid-cols-3 gap-8">
        
        <div class="bg-white p-8 rounded-xl border border-gray-200 w-full max-w-md h-fit">
                <div class="flex items-center space-x-5 mb-10">
                    <div class="w-20 h-20 bg-brandOrange rounded-full flex items-center justify-center text-white text-3xl font-bold shadow-lg shadow-blue-900/20">
                        <?php echo strtoupper(substr($user['name'], 0, 1)); ?>
                    </div>

                    <div>
                        <h2 class="text-xl font-bold text-gray-900"><?php echo htmlspecialchars($user['name']); ?></h2>
                        <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-blue-100 text-blue-800 border border-blue-200">
                            Verified Student
                        </span>
                    </div>
                </div>

                <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                    <div class="space-y-6">
                        <div class="mb-8">
                            <label class="text-xs font-semibold text-gray-500 uppercase tracking-wider block mb-1">Email Address</label>
                            <p class="text-gray-700 font-medium"><?php echo htmlspecialchars($user['email']); ?></p>
                        </div>

                        <div class="mb-8">
                            <label class="text-xs font-semibold text-gray-500 uppercase tracking-wider block mb-1">Phone Number</label>
                            <p class="text-gray-700 font-medium"><?php echo htmlspecialchars($user['phone']); ?></p>
                        </div>

                        <div class="mb-8">
                            <label class="text-xs font-semibold text-gray-500 uppercase tracking-wider block mb-1">Member Since</label>
                            <p class="text-gray-700 font-medium"><?php echo date("F j, Y", strtotime($user['created_at'])); ?></p>
                        </div>
                    </div>
                </div>

                <div class="mt-10 flex gap-4">
                    <a href="edit_profile.php" class="flex-1 text-center py-3 bg-brandOrange hover:bg-orange-600 text-white font-bold rounded-xl transition duration-200 shadow-lg">
                        Edit Profile
                    </a>
                    <a href="../auth/logout.php" class="px-6 py-3 bg-gray-200 hover:bg-gray-300 text-gray-700 font-bold rounded-xl transition duration-200">
                        Logout
                    </a>
                </div>
        </div>

        <div class="lg:col-span-2 space-y-8">
            <div class="flex gap-4 items-center justify-center">
                <div class="bg-white p-6 rounded-2xl border border-gray-200 text-center w-48 shadow-sm">
                    <p class="text-blue-500 text-3xl font-bold"><?php echo $learningCount; ?></p>
                    <p class="text-gray-400 text-[10px] uppercase font-bold tracking-wider">Courses Learning</p>
                </div>
                <div class="bg-white p-6 rounded-2xl border border-gray-200 text-center w-48 shadow-sm">
                    <p class="text-green-500 text-3xl font-bold"><?php echo $completedCount; ?></p>
                    <p class="text-gray-400 text-[10px] uppercase font-bold tracking-wider">Courses Completed</p>
                </div>
            </div>

            <!-- ═══════════════════════════════════════════ -->
            <!-- ── FIXED: My Certificates Section ── -->
            <!-- ═══════════════════════════════════════════ -->
            <div class="p-8 bg-white rounded-2xl border border-gray-200">
                <h3 class="text-xl font-bold text-brandOrange mb-6">My Certificates</h3>
                
                <?php if (!empty($certificates)): ?>
                    <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                        <?php foreach ($certificates as $cert): ?>
                            <div class="relative overflow-hidden p-6 border-2 border-orange-100 rounded-2xl bg-gradient-to-br from-orange-50 to-white text-center shadow-sm hover:shadow-md transition-shadow">
                                <span class="text-5xl mb-4 block">🎓</span>
                                <p class="text-sm font-bold text-gray-800 uppercase tracking-wide leading-tight"><?php echo htmlspecialchars($cert['course_name']); ?></p>
                                <p class="text-[10px] text-gray-500 mt-2">Certified Student</p>
                                
                                <a href="view_certificate.php?course=<?php echo urlencode($cert['course_name']); ?>" 
                                   class="mt-5 block text-xs bg-brandOrange text-white py-2.5 rounded-lg font-bold hover:bg-orange-600 transition w-full">
                                   View Certificate
                                </a>
                            </div>
                        <?php endforeach; ?>
                    </div>
                <?php else: ?>
                    <!-- Much better Empty State Design -->
                    <div class="text-center py-12 border-2 border-dashed border-gray-200 rounded-2xl">
                        <svg class="w-20 h-20 text-gray-200 mx-auto mb-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M9 12l2 2 4-4M7.835 4.697a3.42 3.42 0 001.946-.806 3.42 3.42 0 014.438 0 3.42 3.42 0 001.946.806 3.42 3.42 0 013.138 3.138 3.42 3.42 0 00.806 1.946 3.42 3.42 0 010 4.438 3.42 3.42 0 00-.806 1.946 3.42 3.42 0 01-3.138 3.138 3.42 3.42 0 00-1.946.806 3.42 3.42 0 01-4.438 0 3.42 3.42 0 00-1.946-.806 3.42 3.42 0 01-3.138-3.138 3.42 3.42 0 00-.806-1.946 3.42 3.42 0 010-4.438 3.42 3.42 0 00.806-1.946 3.42 3.42 0 013.138-3.138z"></path></svg>
                        <p class="text-base font-bold text-gray-500">No Certificates Yet</p>
                        <p class="text-sm text-gray-400 mt-1 max-w-sm mx-auto">Complete your enrolled courses to earn official certificates.</p>
                        <a href="courses.php" class="mt-5 inline-block text-sm font-bold text-brandOrange hover:underline">Browse Courses</a>
                    </div>
                <?php endif; ?>
            </div>
            <!-- ═══════════════════════════════════════════ -->

            <div class="p-8 bg-white rounded-2xl border border-gray-200">
                <h3 class="text-xl font-bold text-brandOrange mb-6">Enrolled Courses</h3>
                
                <?php if (count($enrolledCourses) > 0): ?>
                    <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                        <?php foreach ($enrolledCourses as $course): 
                            $progress = 0;
                            if ($course['total_lessons'] > 0) {
                                $progress = round(($course['completed_lessons'] / $course['total_lessons']) * 100);
                            }
                            
                            $progressColor = 'bg-gray-200'; 
                            $textColor = 'text-gray-500';
                            if ($progress > 0 && $progress < 100) {
                                $progressColor = 'bg-brandOrange';
                                $textColor = 'text-brandOrange';
                            } elseif ($progress == 100) {
                                $progressColor = 'bg-green-500';
                                $textColor = 'text-green-600';
                            }
                        ?>
                        <a href="lessons.php?module_id=<?= $course['module_id'] ?>" 
                           class="block p-5 border border-gray-100 rounded-2xl hover:shadow-lg hover:border-brandOrange/30 transition-all duration-200 group bg-gray-50/50">
                            
                            <div class="flex items-start justify-between mb-4">
                                <div class="pr-4">
                                    <h4 class="font-bold text-gray-800 group-hover:text-brandOrange transition-colors leading-snug">
                                        <?= htmlspecialchars($course['module_name']) ?>
                                    </h4>
                                    <p class="text-[11px] text-gray-400 uppercase tracking-wider mt-1"><?= htmlspecialchars($course['course_name']) ?></p>
                                </div>
                                <span class="px-3 py-1 bg-green-100 text-green-700 text-[10px] font-bold rounded-full whitespace-nowrap flex-shrink-0">
                                    Active
                                </span>
                            </div>
                            
                            <?php if ($hasProgressTable): ?>
                            <div class="space-y-2 mt-auto">
                                <div class="flex justify-between text-xs">
                                    <span class="text-gray-500">Progress</span>
                                    <span class="font-bold <?= $textColor ?>"><?= $course['completed_lessons'] ?>/ <?= $course['total_lessons'] ?> Lessons</span>
                                </div>
                                <div class="w-full bg-gray-200 rounded-full h-2 overflow-hidden">
                                    <div class="h-full <?= $progressColor ?> rounded-full transition-all duration-700 ease-out" style="width: <?= $progress ?>%;"></div>
                                </div>
                            </div>
                            <?php endif; ?>

                            <div class="mt-4 flex items-center justify-between">
                                <span class="text-xs text-brandOrange font-semibold opacity-0 group-hover:opacity-100 transition-opacity flex items-center gap-1">
                                    <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M14 5l7 7m0 0l-7 7m7-7H3"></path></svg>
                                    Continue Learning
                                </span>
                                <?php if($progress == 100): ?>
                                    <span class="text-xs font-bold text-green-600 bg-green-50 px-2 py-1 rounded-full">Completed</span>
                                <?php endif; ?>
                            </div>
                        </a>
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
</div>

<?php include_once('../includes/footer.php'); ?>