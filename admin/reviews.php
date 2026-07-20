<?php
require_once '../config/db.php';
include_once('includes/header.php');
require_once 'includes/sidebar.php';

$limit = 12;
$page = max(1, intval($_GET['page'] ?? 1));
$offset = ($page - 1) * $limit;

$total = $conn->query("SELECT COUNT(*) FROM reviews")->fetch_row()[0] ?? 0;
$totalPages = max(1, ceil($total / $limit));

$stmt = "SELECT r.id, r.rating, r.review, r.created_at, 
                u.name AS user_name, u.email AS user_email,
                m.name AS module_name, c.course_name
         FROM reviews r
         JOIN users u ON r.user_id = u.id
         JOIN modules m ON r.module_id = m.id
         JOIN courses c ON m.course_id = c.id
         ORDER BY r.created_at DESC
         LIMIT $offset, $limit";
$reviews = $conn->query($stmt)->fetch_all(MYSQLI_ASSOC);
?>
<div class="flex-1 flex flex-col overflow-hidden">
    <main class="flex-1 overflow-y-auto p-8">
        <!-- <div class="flex justify-between items-center mb-6">
            <div>
                <h1 class="text-xl font-bold text-gray-800">Student Reviews</h1>
                <p class="text-sm text-gray-500 mt-1">Reviews submitted by students for their enrolled modules</p>
            </div>
        </div> -->

        <?php if (!empty($reviews)): ?>
        <div class="grid grid-cols-1 md:grid-cols-2 xl:grid-cols-3 gap-6">
<?php foreach ($reviews as $rv): ?>
            <div class="bg-white rounded-xl border border-gray-200 shadow-sm p-6 flex flex-col">
                <div class="flex items-center justify-between mb-4">
                    <div class="flex items-center gap-3">
                        <div class="w-10 h-10 rounded-full bg-gradient-to-br from-brandOrange to-orange-500 flex items-center justify-center text-white font-bold text-sm flex-shrink-0">
                            <?php echo strtoupper(substr($rv['user_name'], 0, 1)); ?>
                        </div>
                        <div>
                            <p class="text-sm font-semibold text-gray-700"><?php echo htmlspecialchars($rv['user_name']); ?></p>
                            <p class="text-xs text-gray-400"><?php echo htmlspecialchars($rv['user_email']); ?></p>
                        </div>
                    </div>
                    <span class="text-xs text-gray-400 whitespace-nowrap"><?php echo date('M d, Y', strtotime($rv['created_at'])); ?></span>
                </div>
                <div class="flex items-center gap-0.5 mb-3">
                    <?php for ($i = 1; $i <= 5; $i++): ?>
                        <svg class="w-4 h-4 <?php echo $i <= $rv['rating'] ? 'text-yellow-400' : 'text-gray-200'; ?>" fill="currentColor" viewBox="0 0 20 20">
                            <path d="M9.049 2.927c.3-.921 1.603-.921 1.902 0l1.07 3.292a1 1 0 00.95.69h3.462c.969 0 1.371 1.24.588 1.81l-2.8 2.034a1 1 0 00-.364 1.118l1.07 3.292c.3.921-.755 1.688-1.54 1.118l-2.8-2.034a1 1 0 00-1.175 0l-2.8 2.034c-.784.57-1.838-.197-1.539-1.118l1.07-3.292a1 1 0 00-.364-1.118L2.98 8.72c-.783-.57-.38-1.81.588-1.81h3.461a1 1 0 00.951-.69l1.07-3.292z"/>
                        </svg>
                    <?php endfor; ?>
                    <span class="ml-2 text-xs font-bold text-gray-400"><?php echo $rv['rating']; ?>/5</span>
                </div>
                <div class="flex items-center gap-2 mb-3 text-xs">
                    <span class="inline-block bg-brandOrange/10 text-brandOrange font-semibold px-2.5 py-1 rounded-full"><?php echo htmlspecialchars($rv['course_name']); ?></span>
                    <span class="text-gray-400"><?php echo htmlspecialchars($rv['module_name']); ?></span>
                </div>
                <div class="flex-1">
                    <p class="text-sm text-gray-600 leading-relaxed"><?php echo htmlspecialchars($rv['review'] ?: 'No written review'); ?></p>
                </div>
            </div>
<?php endforeach; ?>
        </div>
        <?php else: ?>
        <div class="bg-white rounded-xl border border-gray-200 p-16 text-center shadow-sm">
            <div class="w-20 h-20 bg-gray-100 rounded-full flex items-center justify-center mx-auto mb-6">
                <svg class="w-10 h-10 text-gray-300" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11.049 2.927c.3-.921 1.603-.921 1.902 0l1.519 4.674a1 1 0 00.95.69h4.915c.969 0 1.371 1.24.588 1.81l-3.976 2.888a1 1 0 00-.363 1.118l1.518 4.674c.3.922-.755 1.688-1.538 1.118l-3.976-2.888a1 1 0 00-1.176 0l-3.976 2.888c-.783.57-1.838-.197-1.538-1.118l1.518-4.674a1 1 0 00-.363-1.118l-3.976-2.888c-.784-.57-.38-1.81.588-1.81h4.914a1 1 0 00.951-.69l1.519-4.674z"/></svg>
            </div>
            <h3 class="text-lg font-semibold text-gray-500 mb-2">No reviews yet</h3>
            <p class="text-sm text-gray-400">Student reviews will appear here once they submit feedback on their enrolled modules.</p>
        </div>
        <?php endif; ?>
        <?php if ($totalPages > 1): ?>
        <div class="mt-8 flex items-center justify-between">
            <p class="text-sm text-gray-500">Page <?= $page ?> of <?= $totalPages ?> (<?= $total ?> total)</p>
            <div class="flex items-center gap-1">
                <a href="?page=1" class="px-3 py-1.5 text-sm rounded-lg border border-gray-200 text-gray-600 hover:bg-gray-50 transition <?= $page <= 1 ? 'pointer-events-none opacity-40' : '' ?>">First</a>
                <?php for ($i = 1; $i <= $totalPages; $i++): ?>
                    <a href="?page=<?= $i ?>" class="px-3 py-1.5 text-sm rounded-lg border <?= $i === $page ? 'bg-brandOrange text-white border-brandOrange' : 'border-gray-200 text-gray-600 hover:bg-gray-50' ?> transition"><?= $i ?></a>
                <?php endfor; ?>
                <a href="?page=<?= $totalPages ?>" class="px-3 py-1.5 text-sm rounded-lg border border-gray-200 text-gray-600 hover:bg-gray-50 transition <?= $page >= $totalPages ? 'pointer-events-none opacity-40' : '' ?>">Last</a>
            </div>
        </div>
        <?php endif; ?>
    </main>
</div>
<?php require_once 'includes/footer.php'; ?>
