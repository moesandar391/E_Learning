<?php require_once 'includes/header.php'; ?>
<?php require_once 'includes/sidebar.php'; ?>
<?php require_once '../config/db.php'; ?>

<?php
// ── Filter Defaults ──
 $start_date = $_GET['start_date'] ?? date('Y-m-01'); 
 $end_date = $_GET['end_date'] ?? date('Y-m-d');     
 $course_filter = $_GET['course_id'] ?? '';
 $payment_filter = $_GET['payment_method_id'] ?? '';

// ── Dropdown Data ──
 $courses_list = $conn->query("SELECT id, course_name FROM courses ORDER BY course_name ASC");
 $payment_methods_list = $conn->query("SELECT id, name FROM payment_method ORDER BY name ASC");

// ── Base Where Clause ──
 $where = ["1=1"];
if (!empty($start_date)) $where[] = "e.created_at >= '$start_date'";
if (!empty($end_date)) $where[] = "e.created_at <= '$end_date 23:59:59'";
if (!empty($course_filter)) $where[] = "c.id = " . (int)$course_filter;
if (!empty($payment_filter)) $where[] = "e.payment_method_id = " . (int)$payment_filter;
 $whereSql = implode(" AND ", $where);

// ── Common JOINs to prevent missing column errors ──
 $join_modules = "JOIN modules m ON e.module_id = m.id";
 $join_courses = "LEFT JOIN courses c ON m.course_id = c.id";

// ── Summary Metrics ──
 $total_revenue = $conn->query("SELECT COALESCE(SUM(m.price), 0) FROM enrollments e $join_modules $join_courses WHERE e.payment_method_id IS NOT NULL AND $whereSql")->fetch_row()[0] ?? 0;
 $total_enrollments = $conn->query("SELECT COUNT(*) FROM enrollments e $join_modules $join_courses WHERE $whereSql")->fetch_row()[0] ?? 0;
 $total_students = $conn->query("SELECT COUNT(DISTINCT e.user_id) FROM enrollments e $join_modules $join_courses WHERE $whereSql")->fetch_row()[0] ?? 0;
 $avg_value = $total_enrollments > 0 ? $total_revenue / $total_enrollments : 0;

// ── Chart 1: Trend Data ──
 $trend_labels = [];
 $trend_revenue = [];
 $trend_enrollments = [];
 $trendQuery = $conn->query("
    SELECT DATE(e.created_at) as date, COALESCE(SUM(m.price), 0) as rev, COUNT(e.id) as cnt 
    FROM enrollments e 
    $join_modules $join_courses
    WHERE $whereSql AND e.payment_method_id IS NOT NULL
    GROUP BY DATE(e.created_at) 
    ORDER BY DATE(e.created_at) ASC
");
if ($trendQuery && $trendQuery->num_rows > 0) {
    while ($t = $trendQuery->fetch_assoc()) {
        $trend_labels[] = date('M d', strtotime($t['date']));
        $trend_revenue[] = (float)$t['rev'];
        $trend_enrollments[] = (int)$t['cnt'];
    }
}

// ── Chart 2: Payment Methods (Using Custom Colors) ──
 $pieLabels = []; $pieValues = []; $pieColors = []; $pieBorderColors = [];
 $methodColorMap = [
    'k pay' => ['bg' => 'rgba(59, 130, 246, 0.85)', 'border' => '#3B82F6'],
    'kbzpay' => ['bg' => 'rgba(59, 130, 246, 0.85)', 'border' => '#3B82F6'],
    'kbz pay' => ['bg' => 'rgba(59, 130, 246, 0.85)', 'border' => '#3B82F6'],
    'wavepay' => ['bg' => 'rgba(234, 179, 8, 0.85)', 'border' => '#EAB308'],
    'wave pay' => ['bg' => 'rgba(234, 179, 8, 0.85)', 'border' => '#EAB308'],
    'aya pay' => ['bg' => 'rgba(239, 68, 68, 0.85)', 'border' => '#EF4444'],
    'ayapay' => ['bg' => 'rgba(239, 68, 68, 0.85)', 'border' => '#EF4444'],
    'cb pay' => ['bg' => 'rgba(20, 184, 166, 0.85)', 'border' => '#14B8A6'],
    'cbpay' => ['bg' => 'rgba(20, 184, 166, 0.85)', 'border' => '#14B8A6'],
];
 $fallbackColors = ['rgba(255, 138, 0, 0.85)', '#FF8A00', 'rgba(139, 92, 246, 0.85)', '#8B5CF6'];
 $fbIdx = 0;

 $pieQuery = $conn->query("
    SELECT pm.name AS method_name, COUNT(e.id) AS total_count 
    FROM enrollments e 
    $join_modules $join_courses
    LEFT JOIN payment_method pm ON e.payment_method_id = pm.id
    WHERE $whereSql AND e.payment_method_id IS NOT NULL
    GROUP BY pm.id, pm.name ORDER BY total_count DESC
");
if ($pieQuery && $pieQuery->num_rows > 0) {
    while ($pm = $pieQuery->fetch_assoc()) {
        $name = strtolower(trim($pm['method_name']));
        $pieLabels[] = $pm['method_name'];
        $pieValues[] = (int)$pm['total_count'];
        if (isset($methodColorMap[$name])) {
            $pieColors[] = $methodColorMap[$name]['bg'];
            $pieBorderColors[] = $methodColorMap[$name]['border'];
        } else {
            $pieColors[] = $fallbackColors[$fbIdx * 2];
            $pieBorderColors[] = $fallbackColors[$fbIdx * 2 + 1];
            $fbIdx++;
        }
    }
}

// ── Chart 3: Top Courses ──
 $course_labels = []; $course_counts = [];
 $courseChartQuery = $conn->query("
    SELECT c.course_name, COUNT(e.id) as cnt 
    FROM enrollments e 
    $join_modules 
    JOIN courses c ON m.course_id = c.id 
    WHERE $whereSql 
    GROUP BY c.id ORDER BY cnt DESC LIMIT 5
");
if ($courseChartQuery) {
    while ($cc = $courseChartQuery->fetch_assoc()) {
        $course_labels[] = $cc['course_name'];
        $course_counts[] = (int)$cc['cnt'];
    }
}

// ── Detailed Table Data ──
 $report_data = $conn->query("
    SELECT e.id, u.name AS student, c.course_name, m.name AS module_name, pm.name AS payment_method, m.price, e.enroll_date, e.created_at
    FROM enrollments e 
    JOIN users u ON e.user_id = u.id 
    $join_modules
    JOIN courses c ON m.course_id = c.id 
    LEFT JOIN payment_method pm ON e.payment_method_id = pm.id
    WHERE $whereSql 
    ORDER BY e.created_at DESC LIMIT 100
");
?>

<div class="flex-1 flex flex-col overflow-hidden">
    <header class="h-16 bg-white border-b border-gray-200 flex items-center justify-between px-8 flex-shrink-0">
        <div>
            <h1 class="text-lg font-semibold text-gray-800">Reports & Analytics</h1>
            <p class="text-sm text-gray-500">Generate and view platform insights</p>
        </div>
        <div class="flex items-center gap-3">
            <button onclick="window.print()" class="flex items-center gap-2 px-4 py-2 text-sm font-medium text-gray-600 bg-gray-100 rounded-lg hover:bg-gray-200 transition">
                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 17h2a2 2 0 002-2v-4a2 2 0 00-2-2H5a2 2 0 00-2 2v4a2 2 0 002 2h2m2 4h6a2 2 0 002-2v-4a2 2 0 00-2-2H9a2 2 0 00-2 2v4a2 2 0 002 2zm8-12V5a2 2 0 00-2-2H9a2 2 0 00-2 2v4h10z"/></svg>
                Print
            </button>
            <?php require_once 'includes/admin_notif_icon.php'; ?>
        </div>
    </header>

    <main class="flex-1 overflow-y-auto p-8">
        <!-- ── Filters ── -->
        <form method="GET" class="bg-white rounded-xl border border-gray-200 p-6 mb-8 hover:shadow-lg transition-shadow duration-200">
            <div class="grid grid-cols-5 gap-4 items-end">
                <div>
                    <label class="block text-sm font-medium text-gray-600 mb-1.5">Start Date</label>
                    <input type="date" name="start_date" value="<?= htmlspecialchars($start_date) ?>" class="w-full border border-gray-200 rounded-lg px-4 py-2.5 text-sm focus:ring-2 focus:ring-brandOrange focus:border-brandOrange outline-none transition">
                </div>
                <div>
                    <label class="block text-sm font-medium text-gray-600 mb-1.5">End Date</label>
                    <input type="date" name="end_date" value="<?= htmlspecialchars($end_date) ?>" class="w-full border border-gray-200 rounded-lg px-4 py-2.5 text-sm focus:ring-2 focus:ring-brandOrange focus:border-brandOrange outline-none transition">
                </div>
                <div>
                    <label class="block text-sm font-medium text-gray-600 mb-1.5">Course</label>
                    <select name="course_id" class="w-full border border-gray-200 rounded-lg px-4 py-2.5 text-sm focus:ring-2 focus:ring-brandOrange focus:border-brandOrange outline-none transition bg-white">
                        <option value="">All Courses</option>
                        <?php if($courses_list): while($c = $courses_list->fetch_assoc()): ?>
                            <option value="<?= $c['id'] ?>" <?= ($course_filter == $c['id']) ? 'selected' : '' ?>><?= htmlspecialchars($c['course_name']) ?></option>
                        <?php endwhile; endif; ?>
                    </select>
                </div>
                <div>
                    <label class="block text-sm font-medium text-gray-600 mb-1.5">Payment Method</label>
                    <select name="payment_method_id" class="w-full border border-gray-200 rounded-lg px-4 py-2.5 text-sm focus:ring-2 focus:ring-brandOrange focus:border-brandOrange outline-none transition bg-white">
                        <option value="">All Methods</option>
                        <?php if($payment_methods_list): while($pm = $payment_methods_list->fetch_assoc()): ?>
                            <option value="<?= $pm['id'] ?>" <?= ($payment_filter == $pm['id']) ? 'selected' : '' ?>><?= htmlspecialchars($pm['name']) ?></option>
                        <?php endwhile; endif; ?>
                    </select>
                </div>
                <div class="flex gap-2">
                    <button type="submit" class="flex-1 bg-brandOrange text-white px-6 py-2.5 rounded-lg text-sm font-semibold hover:bg-orange-600 transition shadow-sm">Apply</button>
                    <a href="reports.php" class="px-4 py-2.5 border border-gray-200 rounded-lg text-sm font-medium text-gray-600 hover:bg-gray-50 transition">Reset</a>
                </div>
            </div>
        </form>

        <!-- ── Summary Cards ── -->
        <div class="grid grid-cols-4 gap-6 mb-8">
            <div class="bg-white rounded-xl border border-gray-200 p-6 hover:shadow-lg transition-shadow duration-200">
                <span class="text-sm font-medium text-gray-500">Filtered Revenue</span>
                <p class="text-3xl font-bold text-gray-900 mt-2"><?= number_format($total_revenue) ?> <span class="text-base font-medium text-gray-500">MMK</span></p>
            </div>
            <div class="bg-white rounded-xl border border-gray-200 p-6 hover:shadow-lg transition-shadow duration-200">
                <span class="text-sm font-medium text-gray-500">Total Enrollments</span>
                <p class="text-3xl font-bold text-gray-900 mt-2"><?= number_format($total_enrollments) ?></p>
            </div>
            <div class="bg-white rounded-xl border border-gray-200 p-6 hover:shadow-lg transition-shadow duration-200">
                <span class="text-sm font-medium text-gray-500">Unique Students</span>
                <p class="text-3xl font-bold text-gray-900 mt-2"><?= number_format($total_students) ?></p>
            </div>
            <div class="bg-white rounded-xl border border-gray-200 p-6 hover:shadow-lg transition-shadow duration-200">
                <span class="text-sm font-medium text-gray-500">Avg. Value</span>
                <p class="text-3xl font-bold text-gray-900 mt-2"><?= number_format($avg_value, 0) ?> <span class="text-base font-medium text-gray-500">MMK</span></p>
            </div>
        </div>

        <!-- ── Charts Row 1 ── -->
        <div class="grid grid-cols-3 gap-6 mb-8">
            <div class="col-span-2 bg-white rounded-xl border border-gray-200 p-6 hover:shadow-lg transition-shadow duration-200">
                <h3 class="font-semibold text-gray-800 mb-6">Revenue & Enrollment Trend</h3>
                <div class="h-80"><canvas id="trendChart"></canvas></div>
            </div>
            <div class="bg-white rounded-xl border border-gray-200 p-6 hover:shadow-lg transition-shadow duration-200">
                <h3 class="font-semibold text-gray-800 mb-6">Payment Methods</h3>
                <div class="h-80 flex items-center justify-center">
                    <?php if (count($pieLabels) > 0): ?>
                        <canvas id="paymentPieChart"></canvas>
                    <?php else: ?>
                        <p class="text-sm text-gray-400">No data for this period</p>
                    <?php endif; ?>
                </div>
            </div>
        </div>

        <!-- ── Charts Row 2 & Table ── -->
        <div class="grid grid-cols-3 gap-6 mb-8">
            <div class="bg-white rounded-xl border border-gray-200 p-6 hover:shadow-lg transition-shadow duration-200">
                <h3 class="font-semibold text-gray-800 mb-6">Top Courses</h3>
                <div class="h-64"><canvas id="topCoursesChart"></canvas></div>
            </div>
            
            <div class="col-span-2 bg-white rounded-xl border border-gray-200 hover:shadow-lg transition-shadow duration-200">
                <div class="px-6 py-4 border-b border-gray-100 flex items-center justify-between">
                    <h3 class="font-semibold text-gray-800">Detailed Transactions</h3>
                    <span class="text-xs text-gray-400">Showing latest 100 records</span>
                </div>
                <div class="overflow-x-auto max-h-[320px] overflow-y-auto">
                    <table class="w-full">
                        <thead class="bg-gray-50 sticky top-0">
                            <tr class="text-left text-xs font-semibold text-gray-500 uppercase tracking-wider">
                                <th class="px-6 py-3">Student</th>
                                <th class="px-6 py-3">Course / Module</th>
                                <th class="px-6 py-3">Method</th>
                                <th class="px-6 py-3">Amount</th>
                                <th class="px-6 py-3">Date</th>
                            </tr>
                        </thead>
                        <tbody class="divide-y divide-gray-100">
                            <?php if ($report_data && $report_data->num_rows > 0): ?>
                                <?php while ($row = $report_data->fetch_assoc()): 
                                    $method_name = strtolower(trim($row['payment_method'] ?? ''));
                                    $badge_map = [
                                        'k pay' => 'bg-blue-50 text-blue-700', 'kbzpay' => 'bg-blue-50 text-blue-700', 'kbz pay' => 'bg-blue-50 text-blue-700',
                                        'wavepay' => 'bg-yellow-50 text-yellow-700', 'wave pay' => 'bg-yellow-50 text-yellow-700',
                                        'aya pay' => 'bg-red-50 text-red-700', 'ayapay' => 'bg-red-50 text-red-700',
                                        'cb pay' => 'bg-teal-50 text-teal-700', 'cbpay' => 'bg-teal-50 text-teal-700',
                                    ];
                                    $badge_class = $badge_map[$method_name] ?? 'bg-gray-100 text-gray-600';
                                ?>
                                <tr class="hover:bg-gray-50 transition-colors">
                                    <td class="px-6 py-3 text-sm font-medium text-gray-700"><?= htmlspecialchars($row['student']) ?></td>
                                    <td class="px-6 py-3">
                                        <p class="text-sm text-gray-700 font-medium"><?= htmlspecialchars($row['course_name']) ?></p>
                                        <p class="text-xs text-gray-400"><?= htmlspecialchars($row['module_name']) ?></p>
                                    </td>
                                    <td class="px-6 py-3">
                                        <?php if($row['payment_method']): ?>
                                            <span class="text-xs font-medium px-2.5 py-1 rounded-full <?= $badge_class ?>"><?= htmlspecialchars($row['payment_method']) ?></span>
                                        <?php else: ?>
                                            <span class="text-xs text-gray-400">Free</span>
                                        <?php endif; ?>
                                    </td>
                                    <td class="px-6 py-3 text-sm font-semibold text-gray-800"><?= $row['price'] > 0 ? number_format($row['price']).' MMK' : 'Free' ?></td>
                                    <td class="px-6 py-3 text-sm text-gray-500"><?= date('M d, Y', strtotime($row['created_at'])) ?></td>
                                </tr>
                                <?php endwhile; ?>
                            <?php else: ?>
                                <tr>
                                    <td colspan="5" class="px-6 py-12 text-center text-sm text-gray-400">No transactions found for the selected filters.</td>
                                </tr>
                            <?php endif; ?>
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </main>
</div>

<script src="https://cdn.jsdelivr.net/npm/chart.js@4.4.7/dist/chart.umd.min.js"></script>
<script>
document.addEventListener('DOMContentLoaded', function () {
    const chartFont = { family: "'Inter', sans-serif" };

    // 1. Trend Chart
    new Chart(document.getElementById('trendChart'), {
        type: 'bar',
        data: {
            labels: <?= json_encode($trend_labels) ?>,
            datasets: [
                {
                    label: 'Revenue (MMK)',
                    data: <?= json_encode($trend_revenue) ?>,
                    backgroundColor: 'rgba(255, 138, 0, 0.15)',
                    borderColor: '#FF8A00',
                    borderWidth: 2,
                    yAxisID: 'y',
                    order: 2
                },
                {
                    label: 'Enrollments',
                    data: <?= json_encode($trend_enrollments) ?>,
                    type: 'line',
                    borderColor: '#3B82F6',
                    backgroundColor: 'rgba(59, 130, 246, 0.1)',
                    tension: 0.4,
                    fill: true,
                    yAxisID: 'y1',
                    order: 1
                }
            ]
        },
        options: {
            responsive: true, maintainAspectRatio: false,
            interaction: { mode: 'index', intersect: false },
            plugins: { legend: { labels: { font: chartFont } } },
            scales: {
                y: { type: 'linear', position: 'left', beginAtZero: true, ticks: { font: chartFont }, title: { display: true, text: 'Revenue', font: chartFont } },
                y1: { type: 'linear', position: 'right', beginAtZero: true, grid: { drawOnChartArea: false }, ticks: { stepSize: 1, font: chartFont }, title: { display: true, text: 'Enrollments', font: chartFont } },
                x: { ticks: { font: chartFont } }
            }
        }
    });

    // 2. Payment Pie Chart
    <?php if (count($pieLabels) > 0): ?>
    new Chart(document.getElementById('paymentPieChart'), {
        type: 'doughnut',
        data: {
            labels: <?= json_encode($pieLabels) ?>,
            datasets: [{
                data: <?= json_encode($pieValues) ?>,
                backgroundColor: <?= json_encode($pieColors) ?>,
                borderColor: '#ffffff',
                borderWidth: 3,
            }]
        },
        options: {
            responsive: true, maintainAspectRatio: false,
            cutout: '60%',
            plugins: {
                legend: { position: 'bottom', labels: { padding: 15, usePointStyle: true, font: chartFont } }
            }
        }
    });
    <?php endif; ?>

    // 3. Top Courses Chart
    new Chart(document.getElementById('topCoursesChart'), {
        type: 'bar',
        data: {
            labels: <?= json_encode($course_labels) ?>,
            datasets: [{
                label: 'Enrollments',
                data: <?= json_encode($course_counts) ?>,
                backgroundColor: 'rgba(16, 185, 129, 0.2)',
                borderColor: '#10B981',
                borderWidth: 2,
                borderRadius: 4,
            }]
        },
        options: {
            indexAxis: 'y',
            responsive: true, maintainAspectRatio: false,
            plugins: { legend: { display: false } },
            scales: {
                x: { beginAtZero: true, ticks: { stepSize: 1, font: chartFont } },
                y: { ticks: { font: chartFont } }
            }
        }
    });
});
</script>
<?php require_once 'includes/footer.php'; ?>