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
        $stmt = $conn->prepare("INSERT INTO reviews (user_id, module_id, rating, review) VALUES (?, ?, ?, ?)");
        $stmt->bind_param("iiis", $userId, $moduleId, $rating, $review);
        $stmt->execute();

        $userRes = $conn->query("SELECT name FROM users WHERE id = $userId");
        $userName = $userRes->fetch_assoc()['name'] ?? 'A user';
        $modRes = $conn->query("SELECT m.name, c.course_name FROM modules m JOIN courses c ON m.course_id = c.id WHERE m.id = $moduleId");
        $modData = $modRes->fetch_assoc();
        $moduleName = $modData['name'] ?? 'a module';
        $courseName = $modData['course_name'] ?? '';

        require_once __DIR__ . '/../includes/admin_notification_helper.php';
        $notifMsg = "$userName reviewed \"$moduleName\" ($courseName) with $rating star" . ($rating > 1 ? 's' : '');
        create_admin_notification($notifMsg, "reviews.php", 'review');

        header('Location: write_review.php?submitted=1');
        exit;
    }
}

// Fetch fully completed enrollments without existing reviews
$stmt = $conn->prepare("
    SELECT e.id AS enroll_id, e.enroll_date, e.status, e.receipt,
           m.id AS module_id, m.name AS module_name, m.price, m.image,
           c.course_name, m.level,
           pm.name AS payment_method,
           COUNT(l.id) AS total_lessons,
           COUNT(lp.id) AS completed_lessons
    FROM enrollments e
    JOIN modules m ON e.module_id = m.id
    JOIN courses c ON m.course_id = c.id
    LEFT JOIN payment_method pm ON e.payment_method_id = pm.id
    LEFT JOIN lessons l ON m.id = l.module_id
    LEFT JOIN lesson_progress lp ON l.id = lp.lesson_id AND lp.user_id = ?
    LEFT JOIN reviews r ON r.user_id = e.user_id AND r.module_id = e.module_id
    WHERE e.user_id = ? AND e.status = 'confirmed'
    GROUP BY e.id, e.enroll_date, e.status, e.receipt, m.id, m.name, m.price, m.image, c.course_name, m.level, pm.name
    HAVING total_lessons > 0 AND completed_lessons = total_lessons AND COUNT(r.id) = 0
    ORDER BY e.enroll_date DESC
");
$stmt->bind_param("ii", $userId, $userId);
$stmt->execute();
$enrollments = $stmt->get_result()->fetch_all(MYSQLI_ASSOC);

// Fetch existing reviews by this user
$userReviews = $conn->query("
    SELECT r.rating, r.review, r.created_at, m.name AS module_name, c.course_name
    FROM reviews r
    JOIN modules m ON r.module_id = m.id
    JOIN courses c ON m.course_id = c.id
    WHERE r.user_id = $userId
    ORDER BY r.created_at DESC
");
$userReviews = $userReviews ? $userReviews->fetch_all(MYSQLI_ASSOC) : [];
?>

<div class="min-h-screen bg-gray-50 py-12 px-4 sm:px-6 lg:px-8">
    <div class="max-w-7xl mx-auto">
        <div class="flex items-center justify-between mb-8 px-6">
            <div>
                <h1 class="text-3xl font-bold text-brandOchre">Write a Review</h1>
                <p class="text-sm text-gray-400 mt-1">Share your feedback on completed courses</p>
            </div>
            <a href="my_review.php" class="text-sm font-bold text-brandOrange hover:underline flex items-center gap-1">
                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7"></path></svg>
                View My Reviews
            </a>
        </div>

        <?php if (count($enrollments) > 0): ?>
            <div class="grid grid-cols-1 md:grid-cols-2 gap-4 px-6 mx-auto max-w-4xl">
                <?php foreach ($enrollments as $enr): ?>
                <div class="bg-white rounded-xl border border-gray-100 shadow-sm hover:shadow-md transition-shadow overflow-hidden">
                    <div class="relative px-5 pt-5 pb-4">
                        <div class="flex items-center gap-3 mb-3">
                            <div class="w-9 h-9 rounded-lg bg-gradient-to-br from-brandOrange to-yellow-400 flex items-center justify-center text-white text-sm font-bold shadow-sm flex-shrink-0">
                                <?php echo strtoupper(substr($enr['module_name'], 0, 1)); ?>
                            </div>
                            <div class="min-w-0">
                                <p class="text-[10px] font-bold text-gray-400 uppercase tracking-wider"><?php echo htmlspecialchars($enr['course_name']); ?></p>
                                <p class="text-sm font-bold text-gray-800 truncate"><?php echo htmlspecialchars($enr['module_name']); ?></p>
                            </div>
                        </div>

                        <form method="POST">
                            <input type="hidden" name="module_id" value="<?php echo $enr['module_id']; ?>">

                            <div class="flex items-center gap-1 mb-3 star-rating" data-module="<?php echo $enr['module_id']; ?>">
                                <?php for ($i = 1; $i <= 5; $i++): ?>
                                    <button type="button" data-star="<?php echo $i; ?>"
                                        class="star-btn text-xl text-gray-300 hover:text-yellow-400 transition-colors">★</button>
                                <?php endfor; ?>
                                <input type="hidden" name="rating" class="rating-input" value="0" required>
                                <span class="text-[10px] text-gray-300 ml-1">|</span>
                                <span class="text-[10px] text-gray-400 ml-1">Tap to rate</span>
                            </div>

                            <textarea name="review" rows="1" placeholder="Write a short review..." required
                                class="w-full border border-gray-100 rounded-lg p-2.5 text-xs text-gray-700 bg-gray-50 focus:outline-none focus:border-brandOrange focus:ring-1 focus:ring-brandOrange/20 transition-all resize-none"></textarea>

                            <div class="flex justify-end mt-3">
                                <button type="submit" name="submit_review"
                                    class="px-4 py-1.5 bg-brandOrange text-white text-[11px] font-bold rounded-lg hover:bg-brandOrangeHover transition-colors">
                                    Submit
                                </button>
                            </div>
                        </form>
                    </div>
                </div>
                <?php endforeach; ?>
            </div>
        <?php else: ?>
            <div class="text-center py-14 mx-6">
                <div class="w-14 h-14 mx-auto mb-3 bg-gradient-to-br from-gray-100 to-gray-50 rounded-xl flex items-center justify-center border border-gray-200">
                    <svg class="w-7 h-7 text-gray-300" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z"/></path></svg>
                </div>
                <p class="text-sm font-bold text-gray-500">No Completed Modules to Review</p>
                <p class="text-xs text-gray-400 mt-1">Complete your enrolled courses first to leave a review.</p>
                <a href="my_learning.php" class="mt-4 inline-block px-5 py-2 bg-brandOrange text-white text-xs font-bold rounded-lg hover:bg-brandOrangeHover transition-colors shadow-sm">
                    Go to Dashboard
                </a>
            </div>
        <?php endif; ?>

        <?php if (isset($_GET['submitted'])): ?>
        <!-- Your review -->
        <div class="mx-6">
            <div class="flex items-center gap-3 mb-6">
                <div class="p-2.5 rounded-xl bg-gradient-to-br from-brandOrange/10 to-orange-50 border border-brandOrange/20">
                    <svg class="w-5 h-5 text-brandOrange" fill="currentColor" viewBox="0 0 24 24"><path d="M12 2l3.09 6.26L22 9.27l-5 4.87 1.18 6.88L12 17.77l-6.18 3.25L7 14.14 2 9.27l6.91-1.01L12 2z"/></svg>
                </div>
                <div>
                    <h3 class="text-xl font-bold text-gray-800">Your review</h3>
                    <p class="text-xs text-gray-400">Reviews you've shared on completed courses</p>
                </div>
            </div>
            <?php if (count($userReviews) > 0): ?>
                <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                    <?php foreach ($userReviews as $rev): ?>
                        <div class="group relative bg-white rounded-2xl border border-gray-100 shadow-sm hover:shadow-lg hover:border-brandOrange/20 transition-all duration-300">
                            <div class="absolute top-0 left-0 w-full h-1 bg-gradient-to-r from-brandOrange to-yellow-400 rounded-t-2xl"></div>
                            <div class="p-5">
                                <div class="flex items-start justify-between mb-3">
                                    <div class="flex items-center gap-3">
                                        <div class="w-10 h-10 rounded-xl bg-gradient-to-br from-brandOrange to-yellow-400 flex items-center justify-center text-white text-sm font-bold shadow-sm flex-shrink-0">
                                            <?php echo strtoupper(substr($rev['module_name'], 0, 1)); ?>
                                        </div>
                                        <div>
                                            <p class="text-sm font-bold text-gray-800 leading-tight"><?php echo htmlspecialchars($rev['module_name']); ?></p>
                                            <p class="text-[10px] text-gray-400 uppercase tracking-wider"><?php echo htmlspecialchars($rev['course_name']); ?></p>
                                        </div>
                                    </div>
                                </div>
                                <div class="flex items-center gap-0.5 mb-3">
                                    <?php for ($i = 1; $i <= 5; $i++): ?>
                                        <span class="text-base <?php echo $i <= $rev['rating'] ? 'text-yellow-400' : 'text-gray-200'; ?> drop-shadow-sm">★</span>
                                    <?php endfor; ?>
                                    <span class="text-[11px] text-gray-400 ml-2 font-medium"><?php echo $rev['rating']; ?>.0</span>
                                </div>
                                <?php if ($rev['review']): ?>
                                    <div class="relative pl-3 border-l-2 border-brandOrange/30">
                                        <p class="text-sm text-gray-600 leading-relaxed italic">"<?php echo htmlspecialchars($rev['review']); ?>"</p>
                                    </div>
                                <?php endif; ?>
                                <div class="mt-3 flex items-center gap-1.5 text-[10px] text-gray-400">
                                    <svg class="w-3 h-3" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"/></svg>
                                    <?php echo date('d M Y', strtotime($rev['created_at'])); ?>
                                </div>
                            </div>
                        </div>
                    <?php endforeach; ?>
                </div>
            <?php else: ?>
                <div class="relative bg-white rounded-2xl border-2 border-dashed border-gray-200 p-12 text-center overflow-hidden">
                    <div class="absolute inset-0 bg-gradient-to-br from-orange-50/50 to-transparent"></div>
                    <div class="relative">
                        <div class="w-16 h-16 mx-auto mb-4 bg-gradient-to-br from-gray-100 to-gray-50 rounded-2xl flex items-center justify-center border border-gray-200">
                            <svg class="w-8 h-8 text-gray-300" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M12 2l3.09 6.26L22 9.27l-5 4.87 1.18 6.88L12 17.77l-6.18 3.25L7 14.14 2 9.27l6.91-1.01L12 2z"/></path></svg>
                        </div>
                        <p class="text-base font-bold text-gray-500">No Reviews Yet</p>
                        <p class="text-sm text-gray-400 mt-1 max-w-xs mx-auto">Complete a course and leave a review — your feedback helps others learn better.</p>
                    </div>
                </div>
            <?php endif; ?>
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
