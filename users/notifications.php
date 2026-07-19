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
                        <?php foreach ($notifications as $n):
                // ▼▼▼ ADD THIS BLOCK RIGHT HERE ▼▼▼
                $is_rejection = (strpos($n['message'], 'was rejected') !== false);
                $reason = '';
                $base_message = $n['message'];

                if ($is_rejection) {
                    if (preg_match('/^(.+was rejected):\s*(.+?)\.\s*Please contact support\./', $n['message'], $m)) {
                        $base_message = $m[1] . '.';
                        $reason = trim($m[2]);
                    }
                }
                // ▲▲▲ END OF ADDED BLOCK ▲▲▲
            ?>
                <a href="<?php echo htmlspecialchars($n['link'] ?: '#'); ?>" class="flex items-start gap-4 px-6 py-4 hover:bg-gray-50 transition notif-page-item <?php echo $n['is_read'] ? '' : ($is_rejection ? 'bg-red-50' : 'bg-orange-50'); ?>" data-id="<?php echo $n['id']; ?>">
                    <span class="text-xl flex-shrink-0 mt-0.5">
                        <?php if ($is_rejection): ?>
                            <span class="inline-flex items-center justify-center w-9 h-9 rounded-full bg-red-100 text-base">✕</span>
                        <?php else: ?>
                            <?php
                            $icons = ['enrollment' => '📋', 'lesson' => '📖', 'quiz' => '📝', 'certificate' => '🎓', 'payment' => '💰'];
                            echo $icons[$n['type']] ?? '🔔';
                            ?>
                        <?php endif; ?>
                    </span>
                    <div class="flex-1 min-w-0">
                        <!-- ▼▼▼ CHANGED: use $base_message instead of $n['message'] ▼▼▼ -->
                        <p class="text-sm <?php echo $is_rejection ? 'text-red-700' : 'text-gray-700'; ?>"><?php echo htmlspecialchars($base_message); ?></p>
                        <!-- ▼▼▼ ADDED: show reason box if exists ▼▼▼ -->
                        <?php if ($reason): ?>
                            <div class="mt-2 bg-red-100 border border-red-200 rounded-lg px-3 py-2">
                                <p class="text-xs font-semibold text-red-500 uppercase tracking-wider mb-0.5">Reason</p>
                                <p class="text-sm text-red-700">"<?php echo htmlspecialchars($reason); ?>"</p>
                            </div>
                        <?php endif; ?>
                        <p class="text-xs text-gray-400 mt-1"><?php echo $n['time_ago']; ?></p>
                    </div>
                    <?php if (!$n['is_read']): ?>
                        <span class="w-2.5 h-2.5 rounded-full <?php echo $is_rejection ? 'bg-red-500' : 'bg-brandOrange'; ?> flex-shrink-0 mt-2"></span>
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
