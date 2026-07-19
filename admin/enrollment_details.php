<?php require_once 'includes/header.php'; ?>
<?php require_once 'includes/sidebar.php'; ?>
<?php require_once '../config/db.php'; ?>

<?php
$enroll_id = intval($_GET['id'] ?? 0);
if (!$enroll_id) {
    header('Location: enrollments.php');
    exit;
}

$row = $conn->query("
    SELECT e.id, u.name AS student_name, u.email AS student_email, u.phone AS student_phone,
           m.name AS module_name, m.price, c.course_name, e.enroll_date, e.created_at, e.status, e.receipt,
           pm.name AS payment_method
    FROM enrollments e
    JOIN users u ON e.user_id = u.id
    JOIN modules m ON e.module_id = m.id
    JOIN courses c ON m.course_id = c.id
    LEFT JOIN payment_method pm ON e.payment_method_id = pm.id
    WHERE e.id = $enroll_id
")->fetch_assoc();

if (!$row) {
    header('Location: enrollments.php');
    exit;
}

$s = strtolower($row['status'] ?? 'pending');
$badge = match($s) {
    'confirmed' => 'bg-green-50 text-green-700 border-green-200',
    'pending' => 'bg-yellow-50 text-yellow-700 border-yellow-200',
    'rejected' => 'bg-red-50 text-red-700 border-red-200',
    default => 'bg-gray-50 text-gray-600 border-gray-200'
};
?>

<div class="flex-1 flex flex-col overflow-hidden">
    <header class="h-16 bg-white border-b border-gray-200 flex items-center justify-between px-8 flex-shrink-0">
        <div>
            <h1 class="text-lg font-semibold text-gray-800">Enrollment Details</h1>
            <p class="text-sm text-gray-500">#<?= $row['id'] ?></p>
        </div>
        <div class="flex items-center gap-4">
            <a href="enrollments.php" class="text-sm text-gray-500 hover:text-gray-700 transition">&larr; Back to Enrollments</a>
        </div>
    </header>

    <main class="flex-1 overflow-y-auto p-8">
        <div class="max-w-3xl mx-auto space-y-6">
            <!-- Student Info -->
            <div class="bg-white rounded-xl border border-gray-200 p-6 shadow-sm">
                <h3 class="text-sm font-semibold text-gray-400 uppercase tracking-wider mb-4">Student Information</h3>
                <div class="flex items-center gap-4 pb-4 border-b border-gray-100">
                    <span class="w-12 h-12 rounded-full bg-gradient-to-br from-orange-100 to-orange-200 text-brandOrange flex items-center justify-center text-lg font-bold">
                        <?= strtoupper(substr($row['student_name'], 0, 1)) ?>
                    </span>
                    <div>
                        <p class="text-lg font-bold text-gray-800"><?= htmlspecialchars($row['student_name']) ?></p>
                        <p class="text-sm text-gray-400"><?= htmlspecialchars($row['student_email']) ?></p>
                    </div>
                </div>
                <div class="grid grid-cols-2 gap-4 pt-4 text-sm">
                    <div>
                        <span class="text-gray-400 block">Phone</span>
                        <span class="font-medium text-gray-700"><?= htmlspecialchars($row['student_phone'] ?? 'N/A') ?></span>
                    </div>
                    <div>
                        <span class="text-gray-400 block">Enrolled At</span>
                        <span class="font-medium text-gray-700"><?= htmlspecialchars($row['created_at']) ?></span>
                    </div>
                </div>
            </div>

            <!-- Enrollment Info -->
            <div class="bg-white rounded-xl border border-gray-200 p-6 shadow-sm">
                <h3 class="text-sm font-semibold text-gray-400 uppercase tracking-wider mb-4">Enrollment Information</h3>
                <div class="grid grid-cols-2 gap-4 text-sm">
                    <div>
                        <span class="text-gray-400 block">Course</span>
                        <span class="font-medium text-gray-700"><?= htmlspecialchars($row['course_name']) ?></span>
                    </div>
                    <div>
                        <span class="text-gray-400 block">Module</span>
                        <span class="font-medium text-gray-700"><?= htmlspecialchars($row['module_name']) ?></span>
                    </div>
                    <div>
                        <span class="text-gray-400 block">Enroll Date</span>
                        <span class="font-medium text-gray-700"><?= htmlspecialchars($row['enroll_date']) ?></span>
                    </div>
                    <div>
                        <span class="text-gray-400 block">Payment Method</span>
                        <span class="font-medium text-gray-700"><?= htmlspecialchars($row['payment_method'] ?? 'N/A') ?></span>
                    </div>
                    <div>
                        <span class="text-gray-400 block">Amount</span>
                        <span class="font-medium text-gray-700"><?= $row['price'] ? number_format($row['price']) . ' MMK' : 'Free' ?></span>
                    </div>
                    <div>
                        <span class="text-gray-400 block">Status</span>
                        <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium border <?= $badge ?>">
                            <?= ucfirst($s) ?>
                        </span>
                    </div>
                </div>
            </div>

            <!-- Receipt -->
            <div class="bg-white rounded-xl border border-gray-200 p-6 shadow-sm">
                <h3 class="text-sm font-semibold text-gray-400 uppercase tracking-wider mb-4">Payment Receipt</h3>
                <?php if (!empty($row['receipt'])): ?>
                    <img src="<?= htmlspecialchars($row['receipt']) ?>" alt="Receipt" class="max-w-full h-auto rounded-lg border border-gray-200" onerror="this.onerror=null; this.parentElement.innerHTML='<p class=\'text-red-500 text-sm\'>Receipt image not found</p>'">
                <?php else: ?>
                    <p class="text-sm text-gray-400">No receipt uploaded.</p>
                <?php endif; ?>
            </div>

                        <!-- Actions -->
            <?php if ($s === 'pending'): ?>
            <div class="bg-white rounded-xl border border-gray-200 p-6 shadow-sm">
                <h3 class="text-sm font-semibold text-gray-400 uppercase tracking-wider mb-4">Actions</h3>
                <div class="flex items-center gap-3">
                    <button onclick="updateStatus(<?= $row['id'] ?>, 'confirm')" class="px-4 py-2 text-sm font-semibold rounded-lg bg-green-50 text-green-700 border border-green-200 hover:bg-green-100 transition">
                        Confirm Enrollment
                    </button>
                    <button onclick="openRejectModal(<?= $row['id'] ?>)" class="px-4 py-2 text-sm font-semibold rounded-lg bg-red-50 text-red-700 border border-red-200 hover:bg-red-100 transition">
                        Reject Enrollment
                    </button>
                </div>
            </div>
            <?php endif; ?>
        </div>
    </main>
</div>

<!-- Modal OUTSIDE the flex container and main -->
<div id="rejectModal" class="hidden fixed inset-0 bg-black/50 flex items-center justify-center z-50">
    <div class="bg-white p-6 rounded-xl w-96 shadow-xl">
        <h3 class="font-bold text-lg mb-4">Reject Enrollment</h3>
        <input type="hidden" id="rejectEnrollmentId">
        <textarea id="rejectReason" class="w-full border rounded-lg p-2 mb-4" rows="3" placeholder="Enter reason for rejection..."></textarea>
        <div class="flex justify-end gap-2">
            <button onclick="document.getElementById('rejectModal').classList.add('hidden')" class="px-4 py-2 text-sm text-gray-500">Cancel</button>
            <button onclick="submitRejection()" class="px-4 py-2 text-sm bg-red-600 text-white rounded-lg">Confirm Rejection</button>
        </div>
    </div>
</div>

<script>
function updateStatus(id, action) {
    if (!confirm('Are you sure you want to ' + action + ' this enrollment?')) return;
    var formData = new FormData();
    formData.append('action', action);
    formData.append('id', id);
    fetch('payments_ajax.php', { method: 'POST', body: formData })
        .then(function(r) { return r.json(); })
        .then(function(data) {
            if (data.success) {
                location.reload();
            } else {
                alert(data.message);
            }
        });
}

function openRejectModal(id) {
    document.getElementById('rejectEnrollmentId').value = id;
    document.getElementById('rejectReason').value = '';
    document.getElementById('rejectModal').classList.remove('hidden');
}

function submitRejection() {
    var id = document.getElementById('rejectEnrollmentId').value;
    var reason = document.getElementById('rejectReason').value.trim();
    if (!reason) {
        alert('Please enter a reason for rejection.');
        return;
    }
    var formData = new FormData();
    formData.append('action', 'reject');
    formData.append('id', id);
    formData.append('reason', reason);
    fetch('payments_ajax.php', { method: 'POST', body: formData })
        .then(function(r) { return r.json(); })
        .then(function(data) {
            if (data.success) {
                location.reload();
            } else {
                alert(data.message);
            }
        });
}
</script>

<?php require_once 'includes/footer.php'; ?>
