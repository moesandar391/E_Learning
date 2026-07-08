<?php
session_start();
require_once '../config/db.php';
require_once '../includes/notification_helper.php';
include_once('../includes/header.php');

if (!isset($_SESSION['user_id'])) {
    header("Location: ../auth/login.php");
    exit();
}

$user_id = $_SESSION['user_id'];
$page = isset($_GET['page']) ? max(1, intval($_GET['page'])) : 1;
$limit = 20;
$offset = ($page - 1) * $limit;

$total = $conn->prepare("SELECT COUNT(*) FROM notifications WHERE user_id = ?");
$total->bind_param("i", $user_id);
$total->execute();
$total_count = (int) $total->get_result()->fetch_row()[0];
$total_pages = max(1, ceil($total_count / $limit));

$notifications = get_user_notifications($user_id, $limit);
?>
<main class="max-w-3xl mx-auto px-6 py-10">
    <div class="flex items-center justify-between mb-8">
        <h1 class="text-2xl font-bold text-gray-800">All Notifications</h1>
        <a href="javascript:void(0)" id="markAllReadPage" class="text-sm text-brandOrange hover:text-brandOrangeHover font-medium">Mark all as read</a>
    </div>

    <?php if (empty($notifications)): ?>
        <div class="bg-white rounded-2xl border border-gray-200 p-12 text-center">
            <svg class="w-16 h-16 text-gray-300 mx-auto mb-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M15 17h5l-1.405-1.405A2.032 2.032 0 0118 14.158V11a6.002 6.002 0 00-4-5.659V5a2 2 0 10-4 0v.341C7.67 6.165 6 8.388 6 11v3.159c0 .538-.214 1.055-.595 1.436L4 17h5m6 0v1a3 3 0 11-6 0v-1m6 0H9"/></svg>
            <p class="text-gray-500">No notifications yet.</p>
        </div>
    <?php else: ?>
        <div class="bg-white rounded-2xl border border-gray-200 divide-y divide-gray-100">
            <?php foreach ($notifications as $n): ?>
                <a href="<?php echo htmlspecialchars($n['link'] ?: '#'); ?>" class="flex items-start gap-4 px-6 py-4 hover:bg-gray-50 transition notif-page-item <?php echo $n['is_read'] ? '' : 'bg-orange-50'; ?>" data-id="<?php echo $n['id']; ?>">
                    <span class="text-xl flex-shrink-0 mt-0.5">
                        <?php
                        $icons = ['enrollment' => '📋', 'lesson' => '📖', 'quiz' => '📝', 'certificate' => '🎓', 'payment' => '💰'];
                        echo $icons[$n['type']] ?? '🔔';
                        ?>
                    </span>
                    <div class="flex-1 min-w-0">
                        <p class="text-sm text-gray-700"><?php echo htmlspecialchars($n['message']); ?></p>
                        <p class="text-xs text-gray-400 mt-1"><?php echo $n['time_ago']; ?></p>
                    </div>
                    <?php if (!$n['is_read']): ?>
                        <span class="w-2.5 h-2.5 rounded-full bg-brandOrange flex-shrink-0 mt-2"></span>
                    <?php endif; ?>
                </a>
            <?php endforeach; ?>
        </div>

        <?php if ($total_pages > 1): ?>
            <div class="flex items-center justify-center gap-2 mt-8">
                <?php if ($page > 1): ?>
                    <a href="?page=<?php echo $page - 1; ?>" class="px-4 py-2 text-sm border border-gray-200 rounded-lg hover:bg-gray-50 transition">Previous</a>
                <?php endif; ?>
                <?php for ($i = 1; $i <= $total_pages; $i++): ?>
                    <a href="?page=<?php echo $i; ?>" class="px-3 py-2 text-sm rounded-lg transition <?php echo $i === $page ? 'bg-brandOrange text-white' : 'border border-gray-200 hover:bg-gray-50'; ?>"><?php echo $i; ?></a>
                <?php endfor; ?>
                <?php if ($page < $total_pages): ?>
                    <a href="?page=<?php echo $page + 1; ?>" class="px-4 py-2 text-sm border border-gray-200 rounded-lg hover:bg-gray-50 transition">Next</a>
                <?php endif; ?>
            </div>
        <?php endif; ?>
    <?php endif; ?>
</main>

<script>
document.querySelectorAll('.notif-page-item').forEach(function(el) {
    el.addEventListener('click', function() {
        var id = el.getAttribute('data-id');
        if (id) {
            fetch('../users/get_notifications.php?action=mark_read&id=' + id);
        }
    });
});

document.getElementById('markAllReadPage').addEventListener('click', function() {
    fetch('../users/get_notifications.php?action=mark_all_read').then(function() {
        document.querySelectorAll('.notif-page-item').forEach(function(el) {
            el.classList.remove('bg-orange-50');
            var dot = el.querySelector('.w-2\\.5');
            if (dot) dot.remove();
        });
    });
});
</script>

<?php include_once('../includes/footer.php'); ?>
