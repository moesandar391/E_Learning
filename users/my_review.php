<?php
session_start();
require_once '../config/db.php';
include_once('../includes/header.php');

$userId = $_SESSION['user_id'] ?? null;
if (!$userId) {
    header('Location: ../auth/login.php');
    exit;
}

// Handle review submission
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['submit_review'])) {
    $moduleId = intval($_POST['module_id']);
    $rating = intval($_POST['rating']);
    $review = trim($_POST['review']);

    if ($rating >= 1 && $rating <= 5) {
        $stmt = $conn->prepare("INSERT INTO reviews (user_id, module_id, rating, review) VALUES (?, ?, ?, ?) ON DUPLICATE KEY UPDATE rating = VALUES(rating), review = VALUES(review)");
        $stmt->bind_param("iiis", $userId, $moduleId, $rating, $review);
        $stmt->execute();

        // Fetch user & module names for notification
        $userRes = $conn->query("SELECT name FROM users WHERE id = $userId");
        $userName = $userRes->fetch_assoc()['name'] ?? 'A user';
        $modRes = $conn->query("SELECT m.name, c.course_name FROM modules m JOIN courses c ON m.course_id = c.id WHERE m.id = $moduleId");
        $modData = $modRes->fetch_assoc();
        $moduleName = $modData['name'] ?? 'a module';
        $courseName = $modData['course_name'] ?? '';

        require_once __DIR__ . '/../includes/admin_notification_helper.php';
        $notifMsg = "$userName reviewed \"$moduleName\" ($courseName) with $rating star" . ($rating > 1 ? 's' : '');
        create_admin_notification($notifMsg, "reviews.php", 'review');

        $success = "Review submitted successfully!";

        if (!empty($_POST['redirect'])) {
            header('Location: ' . $_POST['redirect']);
            exit;
        }
    }
}

// Fetch fully completed enrollments (based on lesson progress)
 $stmt = $conn->prepare("
    SELECT e.id AS enroll_id, e.enroll_date, e.status, e.receipt,
           m.id AS module_id, m.name AS module_name, m.price, m.image,
           c.course_name, c.level,
           pm.name AS payment_method,
           COUNT(l.id) AS total_lessons,
           COUNT(lp.id) AS completed_lessons,
           r.rating AS existing_rating, r.review AS existing_review
    FROM enrollments e
    JOIN modules m ON e.module_id = m.id
    JOIN courses c ON m.course_id = c.id
    LEFT JOIN payment_method pm ON e.payment_method_id = pm.id
    LEFT JOIN lessons l ON m.id = l.module_id
    LEFT JOIN lesson_progress lp ON l.id = lp.lesson_id AND lp.user_id = ?
    LEFT JOIN reviews r ON r.user_id = e.user_id AND r.module_id = e.module_id
    WHERE e.user_id = ? AND e.status = 'confirmed'
    GROUP BY e.id, e.enroll_date, e.status, e.receipt, m.id, m.name, m.price, m.image, c.course_name, c.level, pm.name, r.rating, r.review
    HAVING total_lessons > 0 AND completed_lessons = total_lessons
    ORDER BY e.enroll_date DESC
");
 $stmt->bind_param("ii", $userId, $userId);
 $stmt->execute();
 $enrollments = $stmt->get_result()->fetch_all(MYSQLI_ASSOC);
?>

<div class="min-h-screen bg-gray-50 py-12 px-4 sm:px-6 lg:px-8">
    <div class="max-w-5xl mx-auto">
        <div class="flex items-center justify-between mb-8">
            <div>
                <h1 class="text-3xl font-bold text-gray-700">My Reviews</h1>
                <p class="text-sm text-gray-400 mt-1">Review your enrolled courses and share your feedback</p>
            </div>
            <a href="courses.php" class="text-sm font-bold text-brandOrange hover:underline flex items-center gap-1">
                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4"></path></svg>
                Browse More Courses
            </a>
        </div>

        <?php if (isset($success)): ?>
            <div class="bg-green-50 border border-green-200 text-green-700 rounded-xl px-5 py-3 mb-6 text-sm font-medium flex items-center gap-2">
                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"></path></svg>
                <?php echo $success; ?>
            </div>
        <?php endif; ?>

                <?php if (count($enrollments) > 0): ?>
            <div class="space-y-4">
                <?php foreach ($enrollments as $enr):
                    $hasReview = !is_null($enr['existing_rating']);
                ?>
                <div class="bg-white rounded-2xl border border-gray-200 shadow-sm overflow-hidden">
                    <div class="p-6">
                        <div class="flex flex-col sm:flex-row sm:items-center justify-between gap-4">
                            <div class="flex-1 min-w-0">
                                <div class="flex items-center gap-2 mb-1">
                                    <span class="text-[10px] font-bold text-gray-400 uppercase tracking-wider"><?php echo htmlspecialchars($enr['course_name']); ?></span>
                                    <span class="text-gray-300">|</span>
                                    <span class="text-[10px] font-medium text-gray-400"><?php echo htmlspecialchars($enr['level'] ?? 'N/A'); ?></span>
                                </div>
                                <h3 class="text-lg font-bold text-gray-800 truncate"><?php echo htmlspecialchars($enr['module_name']); ?></h3>
                                <div class="flex flex-wrap items-center gap-x-4 gap-y-1 mt-2 text-xs text-gray-500">
                                    <span class="flex items-center gap-1">
                                        <svg class="w-3.5 h-3.5 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z"></path></svg>
                                        <?php echo date('d M Y', strtotime($enr['enroll_date'])); ?>
                                    </span>
                                    <span class="flex items-center gap-1 font-semibold text-gray-700">
                                        <?php echo number_format($enr['price']); ?> MMK
                                    </span>
                                </div>
                            </div>
                            <div class="flex items-center gap-3 flex-shrink-0">
                                <span class="px-3 py-1 text-[11px] font-bold rounded-full bg-green-100 text-green-700">
                                    Completed
                                </span>
                            </div>
                        </div>

                        <!-- Review Section (Always visible for completed courses) -->
                        <hr class="my-4 border-gray-100">
                        <div class="review-section">
                            <form method="POST" class="space-y-3">
                                <input type="hidden" name="module_id" value="<?php echo $enr['module_id']; ?>">

                                <div>
                                    <p class="text-xs font-bold text-gray-500 uppercase tracking-wider mb-2">
                                        <?php echo $hasReview ? 'Your Review' : 'Please Leave a Review'; ?>
                                    </p>
                                    <div class="flex items-center gap-1 star-rating" data-module="<?php echo $enr['module_id']; ?>">
                                        <?php for ($i = 1; $i <= 5; $i++): ?>
                                            <button type="button" data-star="<?php echo $i; ?>"
                                                class="star-btn text-2xl transition-colors <?php echo ($hasReview && $i <= $enr['existing_rating']) ? 'text-yellow-400' : 'text-gray-300'; ?> hover:text-yellow-400">
                                                ★
                                            </button>
                                        <?php endfor; ?>
                                    </div>
                                    <input type="hidden" name="rating" class="rating-input" value="<?php echo $enr['existing_rating'] ?? 0; ?>" required>
                                </div>

                                <div>
                                    <textarea name="review" rows="2" placeholder="Share your thoughts about this course..." required
                                        class="w-full border border-gray-200 rounded-xl p-3 text-sm text-gray-700 bg-gray-50 focus:outline-none focus:border-brandOrange focus:ring-2 focus:ring-brandOrange/20 transition-all resize-none"><?php echo htmlspecialchars($enr['existing_review'] ?? ''); ?></textarea>
                                </div>

                                <div class="flex justify-end">
                                    <button type="submit" name="submit_review"
                                        class="px-5 py-2 bg-brandOrange text-white text-xs font-bold rounded-lg hover:bg-brandOrangeHover transition-colors <?php echo $hasReview ? '!bg-gray-500 hover:!bg-gray-600' : ''; ?>">
                                        <?php echo $hasReview ? 'Update Review' : 'Submit Review'; ?>
                                    </button>
                                </div>
                            </form>
                        </div>
                    </div>
                </div>
                <?php endforeach; ?>
            </div>
        <?php else: ?>
            <div class="bg-white rounded-2xl border border-gray-200 p-16 text-center shadow-sm">
                <svg class="w-20 h-20 text-gray-200 mx-auto mb-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M12 6.253v13m0-13C10.832 5.477 9.246 5 7.5 5S4.168 5.477 3 6.253v13C4.168 18.477 5.754 18 7.5 18s3.332.477 4.5 1.253m0-13C13.168 5.477 14.754 5 16.5 5c1.747 0 3.332.477 4.5 1.253v13C19.832 18.477 18.247 18 16.5 18c-1.746 0-3.332.477-4.5 1.253"></path></svg>
                <p class="text-lg font-bold text-gray-500">No Enrollments Yet</p>
                <p class="text-sm text-gray-400 mt-2 max-w-sm mx-auto">You haven't enrolled in any courses yet. Browse our courses and start your learning journey.</p>
                <a href="courses.php" class="mt-6 inline-block px-6 py-3 bg-brandOrange text-white text-sm font-bold rounded-xl hover:bg-brandOrangeHover transition-colors shadow-md">
                    Browse Courses
                </a>
            </div>
        <?php endif; ?>
    </div>
</div>

<script>
document.querySelectorAll('.star-rating').forEach(function(container) {
    var moduleId = container.dataset.module;
    var stars = container.querySelectorAll('.star-btn');
    var input = container.parentElement.querySelector('.rating-input');

    stars.forEach(function(star) {
        star.addEventListener('click', function() {
            var val = parseInt(this.dataset.star);
            input.value = val;
            stars.forEach(function(s, idx) {
                s.classList.toggle('text-yellow-400', idx < val);
                s.classList.toggle('text-gray-300', idx >= val);
            });
        });
    });
});
</script>

<?php include_once('../includes/footer.php'); ?>
