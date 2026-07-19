<?php require_once 'includes/header.php'; ?>
<?php require_once 'includes/sidebar.php'; ?>
<?php require_once '../config/db.php'; ?>

<?php
 $total_enrollments = $conn->query("SELECT COUNT(*) FROM enrollments")->fetch_row()[0] ?? 0;
 $total_pending = $conn->query("SELECT COUNT(*) FROM enrollments WHERE LOWER(status) = 'pending'")->fetch_row()[0] ?? 0;
 $total_confirmed = $conn->query("SELECT COUNT(*) FROM enrollments WHERE LOWER(status) = 'confirmed'")->fetch_row()[0] ?? 0;
 $total_rejected = $conn->query("SELECT COUNT(*) FROM enrollments WHERE LOWER(status) = 'rejected'")->fetch_row()[0] ?? 0;
 $result = $conn->query("
    SELECT e.id,
           u.name AS student_name,
           u.email AS student_email,
           m.name AS module_name,
           m.price,
           c.course_name,
           pm.name AS payment_method,
           e.enroll_date,
           e.status,
           e.created_at,
           e.receipt
    FROM enrollments e
    JOIN users u ON e.user_id = u.id
    JOIN modules m ON e.module_id = m.id
    JOIN courses c ON m.course_id = c.id
    LEFT JOIN payment_method pm ON e.payment_method_id = pm.id
    ORDER BY
        CASE
            WHEN LOWER(e.status) = 'pending' THEN 1
            WHEN LOWER(e.status) = 'confirmed' THEN 2
            WHEN LOWER(e.status) = 'completed' THEN 2
            WHEN LOWER(e.status) = 'rejected' THEN 3
            ELSE 4
        END,
        e.created_at DESC
");
?>

<div class="flex-1 flex flex-col overflow-hidden">
    <!-- <header class="h-16 bg-white border-b border-gray-200 flex items-center justify-between px-8 flex-shrink-0">
        <div>
            <h1 class="text-lg font-semibold text-gray-800">Payments</h1>
            <p class="text-sm text-gray-500">Manage enrollment payments</p>
        </div>
        <div class="flex items-center gap-4">
            <span class="text-sm text-gray-500"><?php echo date('l, F j, Y'); ?></span>
            <?php require_once 'includes/admin_notif_icon.php'; ?>
            <a href="settings.php" class="w-9 h-9 rounded-full bg-gradient-to-br from-brandOrange to-orange-400 text-white flex items-center justify-center text-sm font-bold shadow-sm hover:opacity-90 transition">
                <?php echo strtoupper(substr($_SESSION['username'] ?? 'A', 0, 1)); ?>
            </a>
        </div>
    </header> -->

    <main class="flex-1 overflow-y-auto p-8">

        <div class="grid grid-cols-1 md:grid-cols-4 gap-6 mb-8">
            <div class="bg-white rounded-xl border border-gray-200 p-6 shadow-sm">
                <div class="flex items-center justify-between">
                    <div>
                        <p class="text-sm font-medium text-gray-500">Total</p>
                        <p class="text-3xl font-bold text-gray-800 mt-1"><?= $total_enrollments ?></p>
                    </div>
                    <div class="w-12 h-12 rounded-full bg-orange-50 flex items-center justify-center">
                        <svg class="w-6 h-6 text-brandOrange" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 20h5v-2a3 3 0 00-5.356-1.857M17 20H7m10 0v-2c0-.656-.126-1.283-.356-1.857M7 20H2v-2a3 3 0 015.356-1.857M7 20v-2c0-.656.126-1.283.356-1.857m0 0a5.002 5.002 0 019.288 0M15 7a3 3 0 11-6 0 3 3 0 016 0z"/></svg>
                    </div>
                </div>
            </div>
            <div class="bg-white rounded-xl border border-gray-200 p-6 shadow-sm">
                <div class="flex items-center justify-between">
                    <div>
                        <p class="text-sm font-medium text-gray-500">Pending</p>
                        <p class="text-3xl font-bold text-yellow-600 mt-1"><?= $total_pending ?></p>
                    </div>
                    <div class="w-12 h-12 rounded-full bg-yellow-50 flex items-center justify-center">
                        <svg class="w-6 h-6 text-yellow-600" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"/></svg>
                    </div>
                </div>
            </div>
            <div class="bg-white rounded-xl border border-gray-200 p-6 shadow-sm">
                <div class="flex items-center justify-between">
                    <div>
                        <p class="text-sm font-medium text-gray-500">Confirmed</p>
                        <p class="text-3xl font-bold text-green-600 mt-1"><?= $total_confirmed ?></p>
                    </div>
                    <div class="w-12 h-12 rounded-full bg-green-50 flex items-center justify-center">
                        <svg class="w-6 h-6 text-green-600" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"/></svg>
                    </div>
                </div>
            </div>
            <div class="bg-white rounded-xl border border-gray-200 p-6 shadow-sm">
                <div class="flex items-center justify-between">
                    <div>
                        <p class="text-sm font-medium text-gray-500">Rejected</p>
                        <p class="text-3xl font-bold text-red-600 mt-1"><?= $total_rejected ?></p>
                    </div>
                    <div class="w-12 h-12 rounded-full bg-red-50 flex items-center justify-center">
                        <svg class="w-6 h-6 text-red-600" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"/></svg>
                    </div>
                </div>
            </div>
        </div>

        <div class="bg-white rounded-xl border border-gray-200">
            <div class="px-6 py-4 border-b border-gray-100 flex items-center justify-between">
                <div class="flex items-center gap-4">
                    <h3 class="font-semibold text-gray-800">All Enrollments</h3>
                    <span class="text-xs font-medium text-gray-400 bg-gray-100 px-2.5 py-1 rounded-full"><?= $total_enrollments ?> total</span>
                </div>
                <div class="flex items-center gap-3">
                    <!-- Status Filter -->
                    <select id="statusFilter"
                        class="px-3 py-2 text-sm border border-orange-200 rounded-lg focus:outline-none focus:ring-2 focus:ring-brandOrange">
                        <option value="all">All Status</option>
                        <option value="pending">Pending</option>
                        <option value="confirmed">Confirmed</option>
                        <option value="rejected">Rejected</option>
                    </select>
                    <!-- Search -->
                    <div class="relative">
                        <svg class="w-4 h-4 text-gray-400 absolute left-3 top-1/2 -translate-y-1/2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                            d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z"/>
                        </svg>
                        <input type="text" id="searchInput"
                        placeholder="Search..."
                        class="pl-9 pr-3 py-2 text-sm border border-orange-200 rounded-lg focus:outline-none focus:ring-2 focus:ring-brandOrange focus:border-transparent w-60">
                    </div>
                </div>
            </div>
            <div class="overflow-x-auto">
                <table class="w-full" id="paymentsTable">
                    <thead>
                        <tr class="text-left text-xs font-semibold text-gray-400 uppercase tracking-wider">
                            <th class="px-4 py-4">#</th>
                            <th class="px-4 py-4">Student</th>
                            <th class="px-4 py-4">Course / Module</th>
                            <th class="px-4 py-4">Method</th>
                            <th class="px-4 py-4">Receipt</th>
                            <th class="px-4 py-4">Amount</th>
                            <th class="px-4 py-4">Date</th>
                            <th class="px-4 py-4">Status</th>
                            <th class="px-4 py-4">Action</th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-gray-100">
                        <?php if ($result && $result->num_rows > 0): ?>
                            <?php while ($row = $result->fetch_assoc()): ?>
                            <tr class="hover:bg-gray-50 transition-colors payment-row"
                                data-id="<?= $row['id'] ?>"
                                data-status="<?= strtolower($row['status']) == 'completed' ? 'confirmed' : strtolower($row['status']) ?>">
                                <td class="px-4 py-4 text-sm text-gray-500 font-mono">#<?= $row['id'] ?></td>
                                <td class="px-4 py-4">
                                    <div class="flex items-center gap-2">
                                        <span class="w-8 h-8 rounded-full bg-gradient-to-br from-orange-100 to-orange-200 text-brandOrange flex items-center justify-center text-sm font-bold">
                                            <?php echo strtoupper(substr($row['student_name'], 0, 1)); ?>
                                        </span>
                                        <div>
                                            <p class="text-sm font-medium text-gray-700"><?= htmlspecialchars($row['student_name']) ?></p>
                                            <p class="text-xs text-gray-400"><?= htmlspecialchars($row['student_email']) ?></p>
                                        </div>
                                    </div>
                                </td>
                                <td class="px-4 py-4">
                                    <p class="text-sm text-gray-700"><?= htmlspecialchars($row['course_name']) ?></p>
                                    <p class="text-xs text-gray-400"><?= htmlspecialchars($row['module_name']) ?></p>
                                </td>
                                <td class="px-4 py-4">
    <?php 
    // Use strtolower to make matching case-insensitive
    $method = trim($row['payment_method'] ?? '');
    $methodLower = strtolower($method);
    
    // Define brand-specific colors
    $colors = [
        'k pay'    => 'bg-blue-100 text-blue-700 border-blue-200',
        'kbzpay'  => 'bg-blue-100 text-blue-700 border-blue-200',
        'wavepay' => 'bg-yellow-100 text-yellow-700 border-yellow-200',
        'aya pay'  => 'bg-red-100 text-red-700 border-red-200',
        'cb pay'   => 'bg-teal-100 text-teal-700 border-teal-200'
    ];

    // Get style based on lowercase match, default to gray if unknown
    $style = $colors[$methodLower] ?? 'bg-gray-50 text-gray-600 border-gray-200';
    ?>

    <?php if ($method): ?>
        <span class="inline-flex items-center gap-1 px-2 py-0.5 rounded-full text-xs font-medium border <?= $style ?>">
            <?= htmlspecialchars($method) ?>
        </span>
    <?php else: ?>
        <span class="text-xs text-gray-400">—</span>
    <?php endif; ?>
</td>
                                <td class="px-4 py-4">
                                    <?php if (!empty($row['receipt'])): ?>
                                        <a href="#" onclick="event.preventDefault(); document.getElementById('receiptModal<?= $row['id'] ?>').classList.remove('hidden')"
                                           class="inline-flex items-center gap-1 px-2 py-1 rounded-md text-xs font-medium bg-purple-50 text-purple-700 border border-purple-200 hover:bg-purple-100 transition">
                                            <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 16l4.586-4.586a2 2 0 012.828 0L16 16m-2-2l1.586-1.586a2 2 0 012.828 0L20 14m-6-6h.01M6 20h12a2 2 0 002-2V6a2 2 0 00-2-2H6a2 2 0 00-2 2v12a2 2 0 002 2z"/></svg>
                                            View Receipt
                                        </a>
                                        <!-- Modal -->
                                        <div id="receiptModal<?= $row['id'] ?>" class="fixed inset-0 z-50 hidden items-center justify-center bg-black/70 backdrop-blur-sm" onclick="if(event.target===this) this.classList.add('hidden')">
                                            <div class="bg-white rounded-2xl p-4 max-w-lg w-full mx-4 shadow-2xl relative">
                                                <button onclick="document.getElementById('receiptModal<?= $row['id'] ?>').classList.add('hidden')" class="absolute top-2 right-2 text-gray-400 hover:text-gray-600 text-2xl leading-none">&times;</button>
                                                <img src="<?= htmlspecialchars($row['receipt']) ?>" alt="Receipt" class="w-full h-auto rounded-lg" onerror="this.parentElement.innerHTML='<p class=\'text-red-500 text-sm p-4\'>Receipt image not found</p>'">
                                            </div>
                                        </div>
                                    <?php else: ?>
                                        <span class="text-xs text-gray-400">—</span>
                                    <?php endif; ?>
                                </td>
                                <td class="px-4 py-4 text-sm font-semibold text-gray-700 whitespace-nowrap">
                                    <?php if ($row['price'] > 0): ?>
                                        <?= number_format($row['price']); ?> MMK
                                    <?php else: ?>
                                        <span class="text-gray-400">Free</span>
                                    <?php endif; ?>
                                </td>
                                <td class="px-4 py-4 text-sm text-gray-500"><?= htmlspecialchars($row['enroll_date']) ?></td>
                                <td class="px-4 py-4">
                                    <?php
                                    $s = strtolower($row['status']);
                                    if ($s === 'completed') $s = 'confirmed';
                                    $badge = match($s) {
                                        'confirmed' => 'bg-green-50 text-green-700 border-green-200',
                                        'pending' => 'bg-yellow-50 text-yellow-700 border-yellow-200',
                                        'rejected' => 'bg-red-50 text-red-700 border-red-200',
                                        default => 'bg-gray-50 text-gray-600 border-gray-200'
                                    };
                                    $icon = match($s) {
                                        'confirmed' => '<svg class="w-3 h-3" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"/></svg>',
                                        'pending' => '<svg class="w-3 h-3" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"/></svg>',
                                        'rejected' => '<svg class="w-3 h-3" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"/></svg>',
                                        default => ''
                                    };
                                    ?>
                                    <span class="inline-flex items-center gap-1 px-2.5 py-1 rounded-full text-xs font-medium border <?= $badge ?>">
                                        <?= $icon ?>
                                        <?= ucfirst($s) ?>
                                    </span>
                                </td>
                                <td class="px-4 py-4 whitespace-nowrap">
                                    <div class="flex items-center gap-1.5">
                                        <button class="confirm-btn px-2 py-1 text-xs font-semibold rounded-md bg-green-50 text-green-700 border border-green-200 hover:bg-green-100 transition" data-id="<?= $row['id'] ?>">
                                            Confirm
                                        </button>
                                        <button class="reject-btn px-2 py-1 text-xs font-semibold rounded-md bg-red-50 text-red-700 border border-red-200 hover:bg-red-100 transition" data-id="<?= $row['id'] ?>">
                                            Reject
                                        </button>
                                    </div>
                                </td>
                            </tr>
                            <?php endwhile; ?>
                        <?php else: ?>
                            <tr>
                                <td colspan="9" class="px-6 py-12 text-center">
                                    <svg class="w-12 h-12 text-gray-300 mx-auto mb-3" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M17 9V7a2 2 0 00-2-2H5a2 2 0 00-2 2v6a2 2 0 002 2h2m2 4h10a2 2 0 002-2v-6a2 2 0 00-2-2H9a2 2 0 00-2 2v6a2 2 0 002 2zm7-5a2 2 0 11-4 0 2 2 0 014 0z"/></svg>
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
const searchInput = document.getElementById('searchInput');
const statusFilter = document.getElementById('statusFilter');

function filterTable() {

    const keyword = searchInput.value.toLowerCase().trim();
    const status = statusFilter.value;

    document.querySelectorAll('.payment-row').forEach(function(row){

        const text = row.innerText.toLowerCase();
        const rowStatus = row.dataset.status;

        const matchSearch = text.includes(keyword);
        const matchStatus = status === 'all' || rowStatus === status;

        row.style.display = (matchSearch && matchStatus) ? '' : 'none';

    });

}

searchInput.addEventListener('keyup', filterTable);
statusFilter.addEventListener('change', filterTable);

document.querySelectorAll('.confirm-btn').forEach(function(btn) {
    btn.addEventListener('click', function() {
        var id = this.getAttribute('data-id');
        var row = this.closest('tr');
        if (confirm('Confirm this enrollment?')) {
            var formData = new FormData();
            formData.append('action', 'confirm');
            formData.append('id', id);
            fetch('payments_ajax.php', { method: 'POST', body: formData })
                .then(function(r) { return r.json(); })
                .then(function(data) {
                    if (data.success) {
                        var statusCell = row.querySelector('td:nth-child(8)');
                        statusCell.innerHTML = '<span class="inline-flex items-center gap-1 px-2.5 py-1 rounded-full text-xs font-medium border bg-green-50 text-green-700 border-green-200"><svg class="w-3 h-3" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"/></svg> Confirmed</span>';
                    } else {
                        alert(data.message);
                    }
                });
        }
    });
});

document.querySelectorAll('.reject-btn').forEach(function(btn) {
    btn.addEventListener('click', function() {
        var id = this.getAttribute('data-id');
        var row = this.closest('tr');
        if (confirm('Reject this enrollment?')) {
            var formData = new FormData();
            formData.append('action', 'reject');
            formData.append('id', id);
            fetch('payments_ajax.php', { method: 'POST', body: formData })
                .then(function(r) { return r.json(); })
                .then(function(data) {
                    if (data.success) {
                        var statusCell = row.querySelector('td:nth-child(8)');
                        statusCell.innerHTML = '<span class="inline-flex items-center gap-1 px-2.5 py-1 rounded-full text-xs font-medium border bg-red-50 text-red-700 border-red-200"><svg class="w-3 h-3" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"/></svg> Rejected</span>';
                    } else {
                        alert(data.message);
                    }
                });
        }
    });
});
</script>

<?php require_once 'includes/footer.php'; ?>
