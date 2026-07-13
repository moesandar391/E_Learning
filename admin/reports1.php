<?php require_once 'includes/header.php'; ?>
<?php require_once 'includes/sidebar.php'; ?>
<?php require_once '../config/db.php'; ?>

<?php
 $total_students = $conn->query("SELECT COUNT(*) FROM users")->fetch_row()[0] ?? 0;
 $total_courses = $conn->query("SELECT COUNT(*) FROM courses")->fetch_row()[0] ?? 0;
 $total_enrollments = $conn->query("SELECT COUNT(*) FROM enrollments")->fetch_row()[0] ?? 0;
 $total_completed = $conn->query("SELECT COUNT(*) FROM enrollments WHERE payment_method_id IS NOT NULL")->fetch_row()[0] ?? 0;
 $total_modules = $conn->query("SELECT COUNT(*) FROM modules")->fetch_row()[0] ?? 0;
 $total_certificates = $conn->query("SELECT COUNT(*) FROM certificates")->fetch_row()[0] ?? 0;

 $revenue = $conn->query("SELECT COALESCE(SUM(m.price), 0) FROM enrollments e JOIN modules m ON e.module_id = m.id WHERE e.payment_method_id IS NOT NULL")->fetch_row()[0] ?? 0;

 $enrollments_by_month = $conn->query("
    SELECT DATE_FORMAT(created_at, '%Y-%m') AS month, COUNT(*) AS cnt
    FROM enrollments GROUP BY month ORDER BY month DESC LIMIT 6
");
?>

<div class="flex-1 flex flex-col overflow-hidden">
    <header class="h-16 bg-white border-b border-gray-200 flex items-center justify-between px-8 flex-shrink-0">
        <div>
            <h1 class="text-lg font-semibold text-gray-800">Reports</h1>
            <p class="text-sm text-gray-500">Platform analytics and insights</p>
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

        <div class="grid grid-cols-1 md:grid-cols-3 gap-6 mb-8">
            <div class="bg-white rounded-xl border border-gray-200 p-6 shadow-sm">
                <div class="flex items-center justify-between">
                    <div>
                        <p class="text-sm font-medium text-gray-500">Total Students</p>
                        <p class="text-3xl font-bold text-gray-800 mt-1"><?= $total_students ?></p>
                    </div>
                    <div class="w-12 h-12 rounded-full bg-blue-50 flex items-center justify-center">
                        <svg class="w-6 h-6 text-blue-600" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4.354a4 4 0 110 5.292M15 21H3v-1a6 6 0 0112 0v1zm0 0h6v-1a6 6 0 00-9-5.197m13.5-9a2.5 2.5 0 11-5 0 2.5 2.5 0 015 0z"/></svg>
                    </div>
                </div>
            </div>
            <div class="bg-white rounded-xl border border-gray-200 p-6 shadow-sm">
                <div class="flex items-center justify-between">
                    <div>
                        <p class="text-sm font-medium text-gray-500">Total Courses</p>
                        <p class="text-3xl font-bold text-gray-800 mt-1"><?= $total_courses ?></p>
                    </div>
                    <div class="w-12 h-12 rounded-full bg-green-50 flex items-center justify-center">
                        <svg class="w-6 h-6 text-green-600" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 6.253v13m0-13C10.832 5.477 9.246 5 7.5 5S4.168 5.477 3 6.253v13C4.168 18.477 5.754 18 7.5 18s3.332.477 4.5 1.253m0-13C13.168 5.477 14.754 5 16.5 5c1.747 0 3.332.477 4.5 1.253v13C19.832 18.477 18.247 18 16.5 18c-1.746 0-3.332.477-4.5 1.253"/></svg>
                    </div>
                </div>
            </div>
            <div class="bg-white rounded-xl border border-gray-200 p-6 shadow-sm">
                <div class="flex items-center justify-between">
                    <div>
                        <p class="text-sm font-medium text-gray-500">Total Modules</p>
                        <p class="text-3xl font-bold text-gray-800 mt-1"><?= $total_modules ?></p>
                    </div>
                    <div class="w-12 h-12 rounded-full bg-indigo-50 flex items-center justify-center">
                        <svg class="w-6 h-6 text-indigo-600" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 11H5m14 0a2 2 0 012 2v6a2 2 0 01-2 2H5a2 2 0 01-2-2v-6a2 2 0 012-2m14 0V9a2 2 0 00-2-2M5 11V9a2 2 0 012-2m0 0V5a2 2 0 012-2h6a2 2 0 012 2v2M7 7h10"/></svg>
                    </div>
                </div>
            </div>
            <div class="bg-white rounded-xl border border-gray-200 p-6 shadow-sm">
                <div class="flex items-center justify-between">
                    <div>
                        <p class="text-sm font-medium text-gray-500">Total Enrollments</p>
                        <p class="text-3xl font-bold text-gray-800 mt-1"><?= $total_enrollments ?></p>
                    </div>
                    <div class="w-12 h-12 rounded-full bg-orange-50 flex items-center justify-center">
                        <svg class="w-6 h-6 text-brandOrange" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M18 9v3m0 0v3m0-3h3m-3 0h-3m-2-5a4 4 0 11-8 0 4 4 0 018 0zM3 20a6 6 0 0112 0v1H3v-1z"/></svg>
                    </div>
                </div>
            </div>
            <div class="bg-white rounded-xl border border-gray-200 p-6 shadow-sm">
                <div class="flex items-center justify-between">
                    <div>
                        <p class="text-sm font-medium text-gray-500">Completed Payments</p>
                        <p class="text-3xl font-bold text-gray-800 mt-1"><?= $total_completed ?></p>
                    </div>
                    <div class="w-12 h-12 rounded-full bg-green-50 flex items-center justify-center">
                        <svg class="w-6 h-6 text-green-600" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"/></svg>
                    </div>
                </div>
            </div>
            <div class="bg-white rounded-xl border border-gray-200 p-6 shadow-sm">
                <div class="flex items-center justify-between">
                    <div>
                        <p class="text-sm font-medium text-gray-500">Certificates Issued</p>
                        <p class="text-3xl font-bold text-gray-800 mt-1"><?= $total_certificates ?></p>
                    </div>
                    <div class="w-12 h-12 rounded-full bg-purple-50 flex items-center justify-center">
                        <svg class="w-6 h-6 text-purple-600" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4M7.835 4.697a3.42 3.42 0 001.946-.806 3.42 3.42 0 014.438 0 3.42 3.42 0 001.946.806 3.42 3.42 0 013.138 3.138 3.42 3.42 0 00.806 1.946 3.42 3.42 0 010 4.438 3.42 3.42 0 00-.806 1.946 3.42 3.42 0 01-3.138 3.138 3.42 3.42 0 00-1.946.806 3.42 3.42 0 01-4.438 0 3.42 3.42 0 00-1.946-.806 3.42 3.42 0 01-3.138-3.138 3.42 3.42 0 00-.806-1.946 3.42 3.42 0 010-4.438 3.42 3.42 0 00.806-1.946 3.42 3.42 0 013.138-3.138z"/></svg>
                    </div>
                </div>
            </div>
        </div>

        <div class="grid grid-cols-1 lg:grid-cols-2 gap-6 mb-8">
            <div class="bg-white rounded-xl border border-gray-200 p-6 shadow-sm">
                <div class="flex items-center justify-between mb-4">
                    <h3 class="font-semibold text-gray-800">Total Revenue</h3>
                    <span class="text-xs text-gray-400">All time</span>
                </div>
                <p class="text-4xl font-bold text-gray-900"><?= number_format($revenue); ?> <span class="text-lg font-medium text-gray-500">MMK</span></p>
                <div class="mt-4 h-2 bg-gray-100 rounded-full overflow-hidden">
                    <div class="h-full bg-gradient-to-r from-brandOrange to-orange-400 rounded-full" style="width: 100%"></div>
                </div>
            </div>
            <div class="bg-white rounded-xl border border-gray-200 p-6 shadow-sm">
                <div class="flex items-center justify-between mb-4">
                    <h3 class="font-semibold text-gray-800">Enrollments (Last 6 Months)</h3>
                </div>
                <div class="space-y-3">
                    <?php if ($enrollments_by_month && $enrollments_by_month->num_rows > 0): ?>
                        <?php while ($row = $enrollments_by_month->fetch_assoc()): ?>
                        <div class="flex items-center gap-4">
                            <span class="text-sm text-gray-600 w-16"><?= htmlspecialchars($row['month']) ?></span>
                            <div class="flex-1 h-6 bg-gray-100 rounded-full overflow-hidden">
                                <div class="h-full bg-gradient-to-r from-brandOrange to-orange-400 rounded-full flex items-center justify-end px-2 text-xs text-white font-bold" style="width: <?= min($row['cnt'] * 20, 100) ?>%">
                                    <?= $row['cnt'] ?>
                                </div>
                            </div>
                        </div>
                        <?php endwhile; ?>
                    <?php else: ?>
                        <p class="text-sm text-gray-400 text-center py-6">No enrollment data yet.</p>
                    <?php endif; ?>
                </div>
            </div>
        </div>

        <div class="bg-white rounded-xl border border-gray-200 p-6 shadow-sm">
            <div class="flex items-center justify-between mb-4">
                <h3 class="font-semibold text-gray-800">Platform Summary</h3>
            </div>
            <div class="grid grid-cols-2 md:grid-cols-4 gap-4 text-center">
                <div class="p-4 bg-gray-50 rounded-lg">
                    <p class="text-2xl font-bold text-gray-800"><?= $total_students ?></p>
                    <p class="text-xs text-gray-500">Students</p>
                </div>
                <div class="p-4 bg-gray-50 rounded-lg">
                    <p class="text-2xl font-bold text-gray-800"><?= $total_courses ?></p>
                    <p class="text-xs text-gray-500">Courses</p>
                </div>
                <div class="p-4 bg-gray-50 rounded-lg">
                    <p class="text-2xl font-bold text-gray-800"><?= $total_modules ?></p>
                    <p class="text-xs text-gray-500">Modules</p>
                </div>
                <div class="p-4 bg-gray-50 rounded-lg">
                    <p class="text-2xl font-bold text-gray-800"><?= $total_enrollments ?></p>
                    <p class="text-xs text-gray-500">Enrollments</p>
                </div>
                <div class="p-4 bg-gray-50 rounded-lg">
                    <p class="text-2xl font-bold text-gray-800"><?= $total_completed ?></p>
                    <p class="text-xs text-gray-500">Paid</p>
                </div>
                <div class="p-4 bg-gray-50 rounded-lg">
                    <p class="text-2xl font-bold text-gray-800"><?= $total_certificates ?></p>
                    <p class="text-xs text-gray-500">Certificates</p>
                </div>
                <div class="p-4 bg-gray-50 rounded-lg">
                    <p class="text-2xl font-bold text-gray-800"><?= number_format($revenue) ?></p>
                    <p class="text-xs text-gray-500">Revenue (MMK)</p>
                </div>
                <div class="p-4 bg-gray-50 rounded-lg">
                    <p class="text-2xl font-bold text-gray-800"><?= $total_courses + $total_modules ?></p>
                    <p class="text-xs text-gray-500">Total Content</p>
                </div>
            </div>
        </div>
    </main>
</div>

<?php require_once 'includes/footer.php'; ?>
