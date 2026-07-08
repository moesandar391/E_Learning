<?php require_once 'includes/header.php'; ?>
<?php require_once 'includes/sidebar.php'; ?>
<?php require_once '../config/db.php'; ?>

<?php
$total_students = $conn->query("SELECT COUNT(*) FROM users")->fetch_row()[0] ?? 0;
$total_courses = $conn->query("SELECT COUNT(*) FROM courses")->fetch_row()[0] ?? 0;
$total_enrollments = $conn->query("SELECT COUNT(*) FROM enrollments")->fetch_row()[0] ?? 0;
$total_revenue = $conn->query("SELECT COALESCE(SUM(m.price), 0) FROM enrollments e JOIN modules m ON e.module_id = m.id WHERE e.payment_method_id IS NOT NULL")->fetch_row()[0] ?? 0;
$recent_enrollments = $conn->query("
          SELECT e.id, u.name AS student, c.course_name, m.name AS module_name, e.enroll_date, 'Paid' AS status 
          FROM enrollments e 
          JOIN users u ON e.user_id = u.id 
          JOIN modules m ON e.module_id = m.id 
          JOIN courses c ON m.course_id = c.id 
          ORDER BY e.created_at DESC LIMIT 5
");

// ── Enrollment chart data (Jan–Jul of current year) ──
$chartMonths = [];
$chartData = [];
$thisYear = date('Y');
for ($m = 1; $m <= 7; $m++) {
    $month = sprintf('%s-%02d', $thisYear, $m);
    $chartMonths[] = date('M', mktime(0, 0, 0, $m, 1));
    $result = $conn->query("SELECT COUNT(*) AS cnt FROM enrollments WHERE DATE_FORMAT(created_at, '%Y-%m') = '$month'");
    $chartData[] = (int)($result->fetch_assoc()['cnt'] ?? 0);
}
$chartLabels = json_encode($chartMonths);
$chartValues = json_encode($chartData);
?>

<div class="flex-1 flex flex-col overflow-hidden">
    <header class="h-16 bg-white border-b border-gray-200 flex items-center justify-between px-8 flex-shrink-0">
        <div>
            <h1 class="text-lg font-semibold text-gray-800">Dashboard</h1>
            <p class="text-sm text-gray-500">Welcome back, <?php echo htmlspecialchars($_SESSION['username'] ?? 'Admin'); ?>!</p>
        </div>
        <div class="flex items-center gap-4">
            <span class="text-sm text-gray-500"><?php echo date('l, F j, Y'); ?></span>
            <div class="w-9 h-9 rounded-full bg-gradient-to-br from-brandOrange to-orange-400 text-white flex items-center justify-center text-sm font-bold shadow-sm">
                <?php echo strtoupper(substr($_SESSION['username'] ?? 'A', 0, 1)); ?>
            </div>
        </div>
    </header>

    <main class="flex-1 overflow-y-auto p-8">
        <div class="grid grid-cols-4 gap-6 mb-8">
            <div class="bg-white rounded-xl border border-gray-200 p-6 hover:shadow-lg transition-shadow duration-200">
                <div class="flex items-center justify-between mb-4">
                    <span class="text-sm font-medium text-gray-500">Total Students</span>
                    <span class="w-10 h-10 rounded-lg bg-orange-50 flex items-center justify-center">
                        <svg class="w-5 h-5 text-brandOrange" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4.354a4 4 0 110 5.292M15 21H3v-1a6 6 0 0112 0v1zm0 0h6v-1a6 6 0 00-9-5.197m13.5-9a2.5 2.5 0 11-5 0 2.5 2.5 0 015 0z"/></svg>
                    </span>
                </div>
                <p class="text-3xl font-bold text-gray-900"><?= $total_students ?></p>
                <div class="mt-2 flex items-center text-xs text-green-600 font-medium">
                    <svg class="w-3 h-3 mr-1" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 10l7-7m0 0l7 7m-7-7v18"/></svg>
                    Active learners
                </div>
            </div>

            <div class="bg-white rounded-xl border border-gray-200 p-6 hover:shadow-lg transition-shadow duration-200">
                <div class="flex items-center justify-between mb-4">
                    <span class="text-sm font-medium text-gray-500">Total Courses</span>
                    <span class="w-10 h-10 rounded-lg bg-green-50 flex items-center justify-center">
                        <svg class="w-5 h-5 text-green-600" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 6.253v13m0-13C10.832 5.477 9.246 5 7.5 5S4.168 5.477 3 6.253v13C4.168 18.477 5.754 18 7.5 18s3.332.477 4.5 1.253m0-13C13.168 5.477 14.754 5 16.5 5c1.747 0 3.332.477 4.5 1.253v13C19.832 18.477 18.247 18 16.5 18c-1.746 0-3.332.477-4.5 1.253"/></svg>
                    </span>
                </div>
                <p class="text-3xl font-bold text-gray-900"><?= $total_courses ?></p>
                <div class="mt-2 flex items-center text-xs text-green-600 font-medium">
                    <svg class="w-3 h-3 mr-1" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 10l7-7m0 0l7 7m-7-7v18"/></svg>
                    Available programs
                </div>
            </div>

            <div class="bg-white rounded-xl border border-gray-200 p-6 hover:shadow-lg transition-shadow duration-200">
                <div class="flex items-center justify-between mb-4">
                    <span class="text-sm font-medium text-gray-500">Enrollments</span>
                    <span class="w-10 h-10 rounded-lg bg-blue-50 flex items-center justify-center">
                        <svg class="w-5 h-5 text-blue-600" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M18 9v3m0 0v3m0-3h3m-3 0h-3m-2-5a4 4 0 11-8 0 4 4 0 018 0zM3 20a6 6 0 0112 0v1H3v-1z"/></svg>
                    </span>
                </div>
                <p class="text-3xl font-bold text-gray-900"><?= $total_enrollments ?></p>
                <div class="mt-2 flex items-center text-xs text-green-600 font-medium">
                    <svg class="w-3 h-3 mr-1" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 10l7-7m0 0l7 7m-7-7v18"/></svg>
                    Total registrations
                </div>
            </div>

            <div class="bg-white rounded-xl border border-gray-200 p-6 hover:shadow-lg transition-shadow duration-200">
                <div class="flex items-center justify-between mb-4">
                    <span class="text-sm font-medium text-gray-500">Total Revenue</span>
                    <span class="w-10 h-10 rounded-lg bg-teal-50 flex items-center justify-center">
                        <svg class="w-5 h-5 text-teal-600" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8c-1.657 0-3 .895-3 2s1.343 2 3 2 3 .895 3 2-1.343 2-3 2m0-8c1.11 0 2.08.402 2.599 1M12 8V7m0 1v8m0 0v1m0-1c-1.11 0-2.08-.402-2.599-1M21 12a9 9 0 11-18 0 9 9 0 0118 0z"/></svg>
                    </span>
                </div>
                <p class="text-3xl font-bold text-gray-900"><?= number_format($total_revenue); ?> <span class="text-base font-medium text-gray-500">MMK</span></p>
                <div class="mt-2 flex items-center text-xs text-green-600 font-medium">
                    <svg class="w-3 h-3 mr-1" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 10l7-7m0 0l7 7m-7-7v18"/></svg>
                    Completed payments
                </div>
            </div>
        </div>

        <div class="grid grid-cols-3 gap-6 mb-8">
            <div class="col-span-2 bg-white rounded-xl border border-gray-200 p-6 hover:shadow-lg transition-shadow duration-200">
                <div class="flex items-center justify-between mb-6">
                    <h3 class="font-semibold text-gray-800">Enrollment Overview</h3>
                    <span class="text-xs text-gray-400">Last 6 months</span>
                </div>
                <div class="h-64">
                    <canvas id="enrollmentChart"></canvas>
                </div>
            </div>

            <div class="bg-white rounded-xl border border-gray-200 p-6 hover:shadow-lg transition-shadow duration-200">
                <div class="flex items-center justify-between mb-6">
                    <h3 class="font-semibold text-gray-800">Quick Actions</h3>
                </div>
                <div class="space-y-3">
                    <a href="courses.php" class="flex items-center gap-3 p-3 rounded-lg bg-gray-50 hover:bg-orange-50 hover:text-brandOrange transition-all duration-200 group">
                        <span class="w-9 h-9 rounded-lg bg-white border border-gray-200 flex items-center justify-center group-hover:border-brandOrange transition-colors">
                            <svg class="w-4 h-4 text-gray-500 group-hover:text-brandOrange" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 6.253v13m0-13C10.832 5.477 9.246 5 7.5 5S4.168 5.477 3 6.253v13C4.168 18.477 5.754 18 7.5 18s3.332.477 4.5 1.253m0-13C13.168 5.477 14.754 5 16.5 5c1.747 0 3.332.477 4.5 1.253v13C19.832 18.477 18.247 18 16.5 18c-1.746 0-3.332.477-4.5 1.253"/></svg>
                        </span>
                        <div>
                            <p class="text-sm font-medium text-gray-700 group-hover:text-brandOrange">Manage Courses</p>
                            <p class="text-xs text-gray-400">Add or edit courses</p>
                        </div>
                    </a>
                    <a href="students.php" class="flex items-center gap-3 p-3 rounded-lg bg-gray-50 hover:bg-orange-50 hover:text-brandOrange transition-all duration-200 group">
                        <span class="w-9 h-9 rounded-lg bg-white border border-gray-200 flex items-center justify-center group-hover:border-brandOrange transition-colors">
                            <svg class="w-4 h-4 text-gray-500 group-hover:text-brandOrange" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 20h5v-2a3 3 0 00-5.356-1.857M17 20H7m10 0v-2c0-.656-.126-1.283-.356-1.857M7 20H2v-2a3 3 0 015.356-1.857M7 20v-2c0-.656.126-1.283.356-1.857m0 0a5.002 5.002 0 019.288 0M15 7a3 3 0 11-6 0 3 3 0 016 0zm6 3a2 2 0 11-4 0 2 2 0 014 0zM7 10a2 2 0 11-4 0 2 2 0 014 0z"/></svg>
                        </span>
                        <div>
                            <p class="text-sm font-medium text-gray-700 group-hover:text-brandOrange">View Students</p>
                            <p class="text-xs text-gray-400">Manage student accounts</p>
                        </div>
                    </a>
                    <a href="payments.php" class="flex items-center gap-3 p-3 rounded-lg bg-gray-50 hover:bg-orange-50 hover:text-brandOrange transition-all duration-200 group">
                        <span class="w-9 h-9 rounded-lg bg-white border border-gray-200 flex items-center justify-center group-hover:border-brandOrange transition-colors">
                            <svg class="w-4 h-4 text-gray-500 group-hover:text-brandOrange" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 9V7a2 2 0 00-2-2H5a2 2 0 00-2 2v6a2 2 0 002 2h2m2 4h10a2 2 0 002-2v-6a2 2 0 00-2-2H9a2 2 0 00-2 2v6a2 2 0 002 2zm7-5a2 2 0 11-4 0 2 2 0 014 0z"/></svg>
                        </span>
                        <div>
                            <p class="text-sm font-medium text-gray-700 group-hover:text-brandOrange">View Payments</p>
                            <p class="text-xs text-gray-400">Transaction history</p>
                        </div>
                    </a>
                    <a href="reports.php" class="flex items-center gap-3 p-3 rounded-lg bg-gray-50 hover:bg-orange-50 hover:text-brandOrange transition-all duration-200 group">
                        <span class="w-9 h-9 rounded-lg bg-white border border-gray-200 flex items-center justify-center group-hover:border-brandOrange transition-colors">
                            <svg class="w-4 h-4 text-gray-500 group-hover:text-brandOrange" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 19v-6a2 2 0 00-2-2H5a2 2 0 00-2 2v6a2 2 0 002 2h2a2 2 0 002-2zm0 0V9a2 2 0 012-2h2a2 2 0 012 2v10m-6 0a2 2 0 002 2h2a2 2 0 002-2m0 0V5a2 2 0 012-2h2a2 2 0 012 2v14a2 2 0 01-2 2h-2a2 2 0 01-2-2z"/></svg>
                        </span>
                        <div>
                            <p class="text-sm font-medium text-gray-700 group-hover:text-brandOrange">Generate Report</p>
                            <p class="text-xs text-gray-400">Platform analytics</p>
                        </div>
                    </a>
                </div>
            </div>
        </div>

        <div class="bg-white rounded-xl border border-gray-200 hover:shadow-lg transition-shadow duration-200">
            <div class="px-6 py-4 border-b border-gray-100 flex items-center justify-between">
                <h3 class="font-semibold text-gray-800">Recent Enrollments</h3>
                <a href="enrollments.php" class="text-sm text-brandOrange hover:text-orange-600 font-medium">View All</a>
            </div>
            <div class="overflow-x-auto">
                <table class="w-full">
                    <thead>
                        <tr class="text-left text-xs font-semibold text-gray-400 uppercase tracking-wider">
                            <th class="px-6 py-4">Student</th>
                            <th class="px-6 py-4">Course</th>
                            <th class="px-6 py-4">Date</th>
                            <th class="px-6 py-4">Status</th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-gray-100">
                        <?php if ($recent_enrollments && $recent_enrollments->num_rows > 0): ?>
                            <?php while ($row = $recent_enrollments->fetch_assoc()): ?>
                            <tr class="hover:bg-gray-50 transition-colors">
                                <td class="px-6 py-4">
                                    <div class="flex items-center gap-3">
                                        <span class="w-8 h-8 rounded-full bg-gradient-to-br from-orange-100 to-orange-200 text-brandOrange flex items-center justify-center text-xs font-bold"><?= strtoupper(substr($row['student'], 0, 1)) ?></span>
                                        <span class="text-sm font-medium text-gray-700"><?= htmlspecialchars($row['student']) ?></span>
                                    </div>
                                </td>
                                <td class="px-6 py-4 text-sm text-gray-600"><?= htmlspecialchars($row['course_name']) ?></td>
                                <td class="px-6 py-4 text-sm text-gray-500"><?= $row['enroll_date'] ?></td>
                                <td class="px-6 py-4">
                                    <span class="inline-flex items-center gap-1 px-2.5 py-1 rounded-full text-xs font-medium bg-green-50 text-green-700">
                                        <span class="w-1.5 h-1.5 rounded-full bg-green-500"></span>
                                        <?= $row['status'] ?>
                                    </span>
                                </td>
                            </tr>
                            <?php endwhile; ?>
                        <?php else: ?>
                            <tr>
                                <td colspan="4" class="px-6 py-12 text-center">
                                    <svg class="w-12 h-12 text-gray-300 mx-auto mb-3" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2"/></svg>
                                    <p class="text-sm text-gray-400">No enrollments yet</p>
                                </td>
                            </tr>
                        <?php endif; ?>
                    </tbody>
                </table>
            </div>
        </div>
    </main>
</div>

<script src="https://cdn.jsdelivr.net/npm/chart.js@4.4.7/dist/chart.umd.min.js"></script>
<script>
document.addEventListener('DOMContentLoaded', function () {
    var ctx = document.getElementById('enrollmentChart').getContext('2d');
    new Chart(ctx, {
        type: 'bar',
        data: {
            labels: <?= $chartLabels ?>,
            datasets: [{
                label: 'Enrollments',
                data: <?= $chartValues ?>,
                backgroundColor: 'rgba(255, 138, 0, 0.2)',
                borderColor: '#FF8A00',
                borderWidth: 2,
                borderRadius: 6,
                barPercentage: 0.6,
            }]
        },
        options: {
            responsive: true,
            maintainAspectRatio: false,
            plugins: {
                legend: { display: false }
            },
            scales: {
                y: {
                    beginAtZero: true,
                    ticks: { stepSize: 1, precision: 0 }
                }
            }
        }
    });
});
</script>
<?php require_once 'includes/footer.php'; ?>
