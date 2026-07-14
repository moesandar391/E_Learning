<?php
// ═══════════════════════════════════════════════════════════════
// ── EXCEL EXPORT BLOCK (Intercepts request before HTML loads) ──
// ═══════════════════════════════════════════════════════════════
if (isset($_GET['export']) && $_GET['export'] == 'excel') {
    require_once '../config/db.php';

    // Re-apply current filters
    $start_date = $_GET['start_date'] ?? date('Y-m-01');
    $end_date = $_GET['end_date'] ?? date('Y-m-d');
    $course_filter = $_GET['course_id'] ?? '';
    $payment_filter = $_GET['payment_method_id'] ?? '';

    $where = ["1=1"];
    if (!empty($start_date)) $where[] = "e.created_at >= '$start_date'";
    if (!empty($end_date)) $where[] = "e.created_at <= '$end_date 23:59:59'";
    if (!empty($course_filter)) $where[] = "c.id = " . (int)$course_filter;
    if (!empty($payment_filter)) $where[] = "e.payment_method_id = " . (int)$payment_filter;
    $whereSql = implode(" AND ", $where);

    $join_modules = "JOIN modules m ON e.module_id = m.id";
    $join_courses = "LEFT JOIN courses c ON m.course_id = c.id";

    // Fetch data (high limit for full report)
    $report_data = $conn->query("
        SELECT u.name AS student, c.course_name, m.name AS module_name, pm.name AS payment_method, m.price, e.enroll_date, e.created_at
        FROM enrollments e 
        JOIN users u ON e.user_id = u.id 
        $join_modules
        JOIN courses c ON m.course_id = c.id 
        LEFT JOIN payment_method pm ON e.payment_method_id = pm.id
        WHERE $whereSql 
        ORDER BY e.created_at DESC LIMIT 5000
    ");

    // Force browser to download file
    header("Content-Type: application/vnd.ms-excel; charset=utf-8");
    header("Content-Disposition: attachment; filename=Transaction_Report_" . date('Y-m-d_His') . ".xls");
    header("Pragma: no-cache");
    header("Expires: 0");

    // Output native Excel XML formatting for perfect styling
    echo '<html xmlns:o="urn:schemas-microsoft-com:office:office" xmlns:x="urn:schemas-microsoft-com:office:excel" xmlns="http://www.w3.org/TR/REC-html40">';
    echo '<head><meta http-equiv="Content-Type" content="text/html; charset=utf-8"><!--[if gte mso 9]><xml><x:ExcelWorkbook><x:ExcelWorksheets><x:ExcelWorksheet><x:Name>Transactions</x:Name><x:WorksheetOptions><x:DisplayGridlines/></x:WorksheetOptions></x:ExcelWorksheet></x:ExcelWorksheets></x:ExcelWorkbook></xml><![endif]--></head>';
    echo '<body>';
    echo '<table border="1" cellpadding="5" cellspacing="0" style="border-collapse: collapse; font-family: Arial, sans-serif; font-size: 14px;">';
    
    // Styled Header Row matching the dashboard UI
    echo '<tr style="background-color: #FF8A00; color: #ffffff; font-weight: bold;">
            <td>#</td><td>Student</td><td>Course</td><td>Module</td><td>Payment Method</td><td>Amount (MMK)</td><td>Enroll Date</td><td>System Date</td>
          </tr>';
    
    if ($report_data && $report_data->num_rows > 0) {
        $counter = 1;
        while ($row = $report_data->fetch_assoc()) {
            $price = $row['price'] > 0 ? number_format($row['price']) : 'Free';
            $method = $row['payment_method'] ?? 'Free';
            $enroll_date = $row['enroll_date'] ?? '-';
            
            // FIXED: Separated $counter++ from the string to prevent parse error
            echo "<tr>
                    <td>" . $counter++ . "</td>
                    <td>{$row['student']}</td>
                    <td>{$row['course_name']}</td>
                    <td>{$row['module_name']}</td>
                    <td>{$method}</td>
                    <td>{$price}</td>
                    <td>{$enroll_date}</td>
                    <td>{$row['created_at']}</td>
                  </tr>";
        }
    } else {
        echo '<tr><td colspan="8" align="center">No transactions found for this period.</td></tr>';
    }
    
    echo '</table></body></html>';
    exit; // Stop executing so the HTML dashboard doesn't load
}
// ═══════════════════════════════════════════════════════════════
?>

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

// ── Badge Map for Table ──
 $badge_map = [
    'k pay' => 'bg-blue-50 text-blue-700', 'kbzpay' => 'bg-blue-50 text-blue-700', 'kbz pay' => 'bg-blue-50 text-blue-700',
    'wavepay' => 'bg-yellow-50 text-yellow-700', 'wave pay' => 'bg-yellow-50 text-yellow-700',
    'aya pay' => 'bg-red-50 text-red-700', 'ayapay' => 'bg-red-50 text-red-700',
    'cb pay' => 'bg-teal-50 text-teal-700', 'cbpay' => 'bg-teal-50 text-teal-700',
];

// ── Detailed Table Data ──
 $report_data = $conn->query("
    SELECT e.id, u.name AS student, c.course_name, m.name AS module_name, pm.name AS payment_method, m.price, e.enroll_date, e.created_at
    FROM enrollments e 
    JOIN users u ON e.user_id = u.id 
    $join_modules
    JOIN courses c ON m.course_id = c.id 
    LEFT JOIN payment_method pm ON e.payment_method_id = pm.id
    WHERE $whereSql 
    ORDER BY e.created_at DESC LIMIT 500
");

// Smart URL builder for export (preserves applied filters)
 $export_url = htmlspecialchars($_SERVER['PHP_SELF'] . '?' . http_build_query(array_merge($_GET, ['export' => 'excel'])));
?>

<div class="flex-1 flex flex-col overflow-hidden">
    <header class="h-16 bg-white border-b border-gray-200 flex items-center justify-between px-8 flex-shrink-0">
        <div>
            <h1 class="text-lg font-semibold text-gray-800">Reports & Analytics</h1>
            <p class="text-sm text-gray-500">Generate and view detailed transaction insights</p>
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

        <!-- ── Detailed Transactions Table Sheet ── -->
        <div class="bg-white rounded-xl border border-gray-200 hover:shadow-lg transition-shadow duration-200">
            <div class="px-6 py-4 border-b border-gray-100 flex items-center justify-between">
                <h3 class="font-semibold text-gray-800">Detailed Transactions</h3>
                <!-- ── MOVED: Print Button placed here ── -->
                <div class="flex items-center gap-4">
                    <span class="text-xs text-gray-400 font-medium">Showing up to 500 records</span>
                    <a href="<?= $export_url ?>" class="flex items-center gap-2 px-4 py-2 text-sm font-medium text-white bg-green-600 rounded-lg hover:bg-green-700 transition shadow-sm">
                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 17h2a2 2 0 002-2v-4a2 2 0 00-2-2H5a2 2 0 00-2 2v4a2 2 0 002 2h2m2 4h6a2 2 0 002-2v-4a2 2 0 00-2-2H9a2 2 0 00-2 2v4a2 2 0 002 2zm8-12V5a2 2 0 00-2-2H9a2 2 0 00-2 2v4h10z"/></svg>
                        Print
                    </a>
                </div>
            </div>
            <div class="overflow-x-auto">
                <table class="w-full text-left">
                    <thead class="bg-gray-50 border-b border-gray-200">
                        <tr class="text-xs font-semibold text-gray-500 uppercase tracking-wider">
                            <th class="px-6 py-4 w-12">#</th>
                            <th class="px-6 py-4">Student</th>
                            <th class="px-6 py-4">Course</th>
                            <th class="px-6 py-4">Module</th>
                            <th class="px-6 py-4">Payment Method</th>
                            <th class="px-6 py-4 text-right">Amount</th>
                            <th class="px-6 py-4">Enroll Date</th>
                            <th class="px-6 py-4">System Date</th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-gray-100">
                        <?php if ($report_data && $report_data->num_rows > 0): 
                            $counter = 1;
                        ?>
                            <?php while ($row = $report_data->fetch_assoc()): 
                                $method_name = strtolower(trim($row['payment_method'] ?? ''));
                                $badge_class = $badge_map[$method_name] ?? 'bg-gray-100 text-gray-600';
                            ?>
                            <tr class="hover:bg-orange-50/30 transition-colors">
                                <td class="px-6 py-4 text-sm text-gray-400 font-mono"><?= $counter++ ?></td>
                                <td class="px-6 py-4">
                                    <div class="flex items-center gap-3">
                                        <span class="w-8 h-8 rounded-full bg-gradient-to-br from-orange-100 to-orange-200 text-brandOrange flex items-center justify-center text-xs font-bold flex-shrink-0"><?= strtoupper(substr($row['student'], 0, 1)) ?></span>
                                        <span class="text-sm font-medium text-gray-800"><?= htmlspecialchars($row['student']) ?></span>
                                    </div>
                                </td>
                                <td class="px-6 py-4 text-sm text-gray-700 font-medium"><?= htmlspecialchars($row['course_name']) ?></td>
                                <td class="px-6 py-4 text-sm text-gray-500"><?= htmlspecialchars($row['module_name']) ?></td>
                                <td class="px-6 py-4">
                                    <?php if($row['payment_method']): ?>
                                        <span class="text-xs font-semibold px-3 py-1 rounded-full <?= $badge_class ?>"><?= htmlspecialchars($row['payment_method']) ?></span>
                                    <?php else: ?>
                                        <span class="text-xs font-medium px-3 py-1 rounded-full bg-green-50 text-green-600">Free / N/A</span>
                                    <?php endif; ?>
                                </td>
                                <td class="px-6 py-4 text-sm font-bold text-gray-900 text-right">
                                    <?= $row['price'] > 0 ? number_format($row['price']).' <span class="text-xs font-normal text-gray-400">MMK</span>' : '<span class="text-green-600 font-medium">Free</span>' ?>
                                </td>
                                <td class="px-6 py-4 text-sm text-gray-500"><?= $row['enroll_date'] ?? '-' ?></td>
                                <td class="px-6 py-4 text-sm text-gray-400"><?= date('Y-m-d H:i', strtotime($row['created_at'])) ?></td>
                            </tr>
                            <?php endwhile; ?>
                        <?php else: ?>
                            <tr>
                                <td colspan="8" class="px-6 py-16 text-center">
                                    <svg class="w-16 h-16 text-gray-200 mx-auto mb-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"/></svg>
                                    <p class="text-base font-medium text-gray-500">No transactions found</p>
                                    <p class="text-sm text-gray-400 mt-1">Try adjusting your filters or date range</p>
                                </td>
                            </tr>
                        <?php endif; ?>
                    </tbody>
                </table>
            </div>
        </div>
    </main>
</div>

<?php require_once 'includes/footer.php'; ?>