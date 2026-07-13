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

// Use 'completed' (If 'completed' doesn't work, check if it's 'Completed' or another term)
$stmtCompleted = $conn->prepare("SELECT COUNT(*) AS total FROM enrollments WHERE user_id = ? AND status = 'completed'");
$stmtCompleted->bind_param("i", $userId);
$stmtCompleted->execute();
$completedCount = $stmtCompleted->get_result()->fetch_assoc()['total'] ?? 0;

$certificates = [];
$enrolledCourses = [];
// Fetch Certificates
$stmtCert = $conn->prepare("SELECT c.course_name FROM enrollments e 
                           JOIN modules m ON e.module_id = m.id 
                           JOIN courses c ON m.course_id = c.id 
                           WHERE e.user_id = ? AND e.status = 'completed'");
$stmtCert->bind_param("i", $userId);
$stmtCert->execute();
$resultCert = $stmtCert->get_result();
if ($resultCert) {
    $certificates = $resultCert->fetch_all(MYSQLI_ASSOC);
}
// Fetch Enrolled Courses
$stmtEnrolled = $conn->prepare("SELECT c.course_name, m.name as module_name FROM enrollments e 
                                JOIN modules m ON e.module_id = m.id 
                                JOIN courses c ON m.course_id = c.id 
                                WHERE e.user_id = ? AND e.status = 'confirmed'");
$stmtEnrolled->bind_param("i", $userId);
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

            <div class="p-8 text-center">
    <h3 class="text-xl font-bold text-brandOrange mb-6">My Certificates</h3>
    
    <?php if (!empty($certificates)): ?>
        <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
            <?php foreach ($certificates as $cert): ?>
                <div class="relative overflow-hidden p-6 border-2 border-orange-100 rounded-2xl bg-gradient-to-br from-orange-50 to-white text-center shadow-sm">
                    <span class="text-4xl mb-3 block">🎓</span>
                    <p class="text-sm font-bold text-gray-800 uppercase tracking-wide"><?php echo htmlspecialchars($cert['course_name']); ?></p>
                    <p class="text-[10px] text-gray-500 mt-2">Certified Student</p>
                    
                    <a href="view_certificate.php?course=<?php echo urlencode($cert['course_name']); ?>" 
                       class="mt-4 block text-[10px] bg-brandOrange text-white py-2 rounded-lg font-bold hover:bg-orange-600 transition">
                       View Certificate
                    </a>
                </div>
            <?php endforeach; ?>
        </div>
    <?php else: ?>
        <p class="text-gray-500">No certificates completed yet.</p>
    <?php endif; ?>
</div>

<div class="p-8 text-center">
    <h3 class="text-xl font-bold text-brandOrange mb-6">Enrolled Courses</h3>
    <?php if (count($enrolledCourses) > 0): ?>
        <ul class="space-y-3">
            <?php foreach ($enrolledCourses as $course): ?>
                <li class="flex items-center justify-between p-4 border rounded-xl hover:bg-gray-50">
                    <div>
                        <p class="font-bold text-gray-800"><?php echo htmlspecialchars($course['module_name']); ?></p>
                        <p class="text-[10px] text-gray-400 uppercase"><?php echo htmlspecialchars($course['course_name']); ?></p>
                    </div>
                    <span class="px-3 py-1 bg-green-100 text-green-700 text-[10px] font-bold rounded-full">Active</span>
                </li>
            <?php endforeach; ?>
        </ul>
    <?php else: ?>
        <p class="text-gray-500">You are not enrolled in any courses yet.</p>
    <?php endif; ?>
</div>
        </div>
    </div>
</div>

<?php include_once('../includes/footer.php'); ?>