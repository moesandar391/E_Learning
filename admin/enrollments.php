<?php require_once 'includes/header.php'; ?>
<?php require_once 'includes/sidebar.php'; ?>
<?php require_once '../config/db.php'; ?>

<?php
 $total = $conn->query("SELECT COUNT(*) FROM enrollments")->fetch_row()[0] ?? 0;
 $result = $conn->query("
    SELECT e.id, u.name AS student_name, u.email AS student_email, m.name AS module_name, c.course_name, e.enroll_date
    FROM enrollments e
    JOIN users u ON e.user_id = u.id
    JOIN modules m ON e.module_id = m.id
    JOIN courses c ON m.course_id = c.id
    ORDER BY e.created_at DESC
");
?>

<div class="flex-1 flex flex-col overflow-hidden">
    <header class="h-16 bg-white border-b border-gray-200 flex items-center justify-between px-8 flex-shrink-0">
        <div>
            <h1 class="text-lg font-semibold text-gray-800">Enrollments</h1>
            <p class="text-sm text-gray-500">Track all student enrollments</p>
        </div>
        <div class="flex items-center gap-4">
            <span class="text-sm text-gray-500"><?php echo date('l, F j, Y'); ?></span>
            <?php require_once 'includes/admin_notif_icon.php'; ?>
            <a href="settings.php" class="w-9 h-9 rounded-full bg-gradient-to-br from-brandOrange to-orange-400 text-white flex items-center justify-center text-sm font-bold shadow-sm hover:opacity-90 transition">
                <?php echo strtoupper(substr($_SESSION['username'] ?? 'A', 0, 1)); ?>
            </a>
        </div>
    </header>

    <main class="flex-1 overflow-y-auto p-8">
        <div class="bg-white rounded-xl border border-gray-200 hover:shadow-lg transition-shadow duration-200">
            <div class="px-6 py-4 border-b border-gray-100 flex items-center justify-between">
                <div class="flex items-center gap-4">
                    <h3 class="font-semibold text-gray-800">All Enrollments</h3>
                    <span class="text-xs font-medium text-gray-400 bg-gray-100 px-2.5 py-1 rounded-full"><?= $total ?> total</span>
                </div>
                <div class="relative">
                    <svg class="w-4 h-4 text-gray-400 absolute left-3 top-1/2 -translate-y-1/2" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z"/></svg>
                    <input type="text" id="searchInput" placeholder="Search enrollments..." class="pl-9 pr-3 py-2 text-sm border border-orange-200 rounded-lg focus:outline-none focus:ring-2 focus:ring-brandOrange focus:border-transparent w-60">
                </div>
            </div>
            <div class="overflow-x-auto">
                <table class="w-full" id="enrollmentsTable">
                    <thead class="bg-orange-100/50">
                        <tr class="text-left text-xs font-semibold text-gray-500 uppercase tracking-wider">
                            <!-- <th class="px-6 py-4">#</th> -->
                            <th class="px-6 py-4">Student</th>
                            <th class="px-6 py-4">Course</th>
                            <th class="px-6 py-4">Module</th>
                            <th class="px-6 py-4">Date</th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-gray-100">
                        <?php if ($result && $result->num_rows > 0): ?>
                            <?php while ($row = $result->fetch_assoc()): ?>
                            <tr class="hover:bg-gray-50 transition-colors enrollment-row">
                                <!-- <td class="px-6 py-4 text-sm text-gray-500 font-mono">#<?= $row['id'] ?></td> -->
                                <td class="px-6 py-4">
                                    <div class="flex items-center gap-3">
                                        <span class="w-9 h-9 rounded-full bg-gradient-to-br from-orange-100 to-orange-200 text-brandOrange flex items-center justify-center text-sm font-bold">
                                            <?php echo strtoupper(substr($row['student_name'], 0, 1)); ?>
                                        </span>
                                        <div>
                                            <p class="text-sm font-medium text-gray-700"><?= htmlspecialchars($row['student_name']) ?></p>
                                            <p class="text-xs text-gray-400"><?= htmlspecialchars($row['student_email']) ?></p>
                                        </div>
                                    </div>
                                </td>
                                <td class="px-6 py-4 text-sm text-gray-600"><?= htmlspecialchars($row['course_name']) ?></td>
                                <td class="px-6 py-4">
                                    <span class="text-sm text-gray-600"><?= htmlspecialchars($row['module_name']) ?></span>
                                </td>
                                <td class="px-6 py-4 text-sm text-gray-500"><?= htmlspecialchars($row['enroll_date']) ?></td>
                            </tr>
                            <?php endwhile; ?>
                        <?php else: ?>
                            <tr>
                                <td colspan="5" class="px-6 py-12 text-center">
                                    <svg class="w-12 h-12 text-gray-300 mx-auto mb-3" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M17 20h5v-2a3 3 0 00-5.356-1.857M17 20H7m10 0v-2c0-.656-.126-1.283-.356-1.857M7 20H2v-2a3 3 0 015.356-1.857M7 20v-2c0-.656.126-1.283.356-1.857m0 0a5.002 5.002 0 019.288 0M15 7a3 3 0 11-6 0 3 3 0 016 0z"/></svg>
                                    <p class="text-sm text-gray-400 mb-1">No enrollments yet</p>
                                    <p class="text-xs text-gray-300">Enrollments will appear once students purchase a module.</p>
                                </td>
                            </tr>
                        <?php endif; ?>
                    </tbody>
                </table>
            </div>
        </div>
    </main>
</div>

<script>
document.getElementById('searchInput').addEventListener('keyup', function() {
    var q = this.value.toLowerCase().trim();
    var rows = document.querySelectorAll('.enrollment-row');
    
    rows.forEach(function(r) {
        // Target only the second column (Course)
        var course = r.querySelector('td:nth-child(2)').textContent.toLowerCase();
        
        // Show row if it includes the query, otherwise hide it
        r.style.display = course.includes(q) ? '' : 'none';
    });
});
</script>

<?php require_once 'includes/footer.php'; ?>
