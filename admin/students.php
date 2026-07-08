<?php require_once 'includes/header.php'; ?>
<?php require_once 'includes/sidebar.php'; ?>
<?php require_once '../config/db.php'; ?>

<?php
$total = $conn->query("SELECT COUNT(*) FROM users")->fetch_row()[0] ?? 0;
$new_month = $conn->query("SELECT COUNT(*) FROM users WHERE created_at >= DATE_SUB(CURDATE(), INTERVAL 1 MONTH)")->fetch_row()[0] ?? 0;
$male = $conn->query("SELECT COUNT(*) FROM users WHERE gender = 'Male'")->fetch_row()[0] ?? 0;
$female = $conn->query("SELECT COUNT(*) FROM users WHERE gender = 'Female'")->fetch_row()[0] ?? 0;
$result = $conn->query("SELECT id, name, email, phone, gender, created_at FROM users ORDER BY created_at DESC");
?>

<div class="flex-1 flex flex-col overflow-hidden">
    <header class="h-16 bg-white border-b border-gray-200 flex items-center justify-between px-8 flex-shrink-0">
        <div>
            <h1 class="text-lg font-semibold text-gray-800">Students</h1>
            <p class="text-sm text-gray-500">Manage all registered students</p>
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
                <p class="text-3xl font-bold text-gray-900"><?= $total ?></p>
                <div class="mt-2 flex items-center text-xs text-green-600 font-medium">
                    <svg class="w-3 h-3 mr-1" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 10l7-7m0 0l7 7m-7-7v18"/></svg>
                    All time
                </div>
            </div>

            <div class="bg-white rounded-xl border border-gray-200 p-6 hover:shadow-lg transition-shadow duration-200">
                <div class="flex items-center justify-between mb-4">
                    <span class="text-sm font-medium text-gray-500">New This Month</span>
                    <span class="w-10 h-10 rounded-lg bg-green-50 flex items-center justify-center">
                        <svg class="w-5 h-5 text-green-600" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M18 9v3m0 0v3m0-3h3m-3 0h-3m-2-5a4 4 0 11-8 0 4 4 0 018 0zM3 20a6 6 0 0112 0v1H3v-1z"/></svg>
                    </span>
                </div>
                <p class="text-3xl font-bold text-gray-900"><?= $new_month ?></p>
                <div class="mt-2 flex items-center text-xs text-green-600 font-medium">
                    <svg class="w-3 h-3 mr-1" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 10l7-7m0 0l7 7m-7-7v18"/></svg>
                    Recently joined
                </div>
            </div>

            <div class="bg-white rounded-xl border border-gray-200 p-6 hover:shadow-lg transition-shadow duration-200">
                <div class="flex items-center justify-between mb-4">
                    <span class="text-sm font-medium text-gray-500">Male</span>
                    <span class="w-10 h-10 rounded-lg bg-blue-50 flex items-center justify-center">
                        <svg class="w-5 h-5 text-blue-600" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z"/></svg>
                    </span>
                </div>
                <p class="text-3xl font-bold text-gray-900"><?= $male ?></p>
                <div class="mt-2 flex items-center text-xs text-gray-500 font-medium">
                    <?php $malePct = $total > 0 ? round($male / $total * 100) : 0; ?>
                    <span><?= $malePct ?>% of total</span>
                </div>
            </div>

            <div class="bg-white rounded-xl border border-gray-200 p-6 hover:shadow-lg transition-shadow duration-200">
                <div class="flex items-center justify-between mb-4">
                    <span class="text-sm font-medium text-gray-500">Female</span>
                    <span class="w-10 h-10 rounded-lg bg-pink-50 flex items-center justify-center">
                        <svg class="w-5 h-5 text-pink-600" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z"/></svg>
                    </span>
                </div>
                <p class="text-3xl font-bold text-gray-900"><?= $female ?></p>
                <div class="mt-2 flex items-center text-xs text-gray-500 font-medium">
                    <?php $femalePct = $total > 0 ? round($female / $total * 100) : 0; ?>
                    <span><?= $femalePct ?>% of total</span>
                </div>
            </div>
        </div>

        <div class="bg-white rounded-xl border border-gray-200 hover:shadow-lg transition-shadow duration-200">
            <div class="px-6 py-4 border-b border-gray-100 flex items-center justify-between">
                <div class="flex items-center gap-4">
                    <h3 class="font-semibold text-gray-800">All Students</h3>
                    <span class="text-xs font-medium text-gray-400 bg-gray-100 px-2.5 py-1 rounded-full"><?= $result->num_rows ?> registered</span>
                </div>
                <div class="relative">
                    <svg class="w-4 h-4 text-gray-400 absolute left-3 top-1/2 -translate-y-1/2" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z"/></svg>
                    <input type="text" id="searchInput" placeholder="Search students..." class="pl-9 pr-3 py-2 text-sm border border-gray-200 rounded-lg focus:outline-none focus:ring-2 focus:ring-brandOrange focus:border-transparent w-60">
                </div>
            </div>
            <div class="overflow-x-auto">
                <table class="w-full" id="studentsTable">
                    <thead>
                        <tr class="text-left text-xs font-semibold text-gray-400 uppercase tracking-wider">
                            <th class="px-6 py-4">Student</th>
                            <th class="px-6 py-4">Email</th>
                            <th class="px-6 py-4">Phone</th>
                            <th class="px-6 py-4">Gender</th>
                            <th class="px-6 py-4">Joined</th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-gray-100">
                        <?php if ($result && $result->num_rows > 0): ?>
                            <?php while ($row = $result->fetch_assoc()): ?>
                            <tr class="hover:bg-gray-50 transition-colors student-row">
                                <td class="px-6 py-4">
                                    <div class="flex items-center gap-3">
                                        <span class="w-9 h-9 rounded-full bg-gradient-to-br from-orange-100 to-orange-200 text-brandOrange flex items-center justify-center text-sm font-bold"><?= strtoupper(substr($row['name'], 0, 1)) ?></span>
                                        <div>
                                            <p class="text-sm font-medium text-gray-700"><?= htmlspecialchars($row['name']) ?></p>
                                            <p class="text-xs text-gray-400">ID: #<?= $row['id'] ?></p>
                                        </div>
                                    </div>
                                </td>
                                <td class="px-6 py-4 text-sm text-gray-600"><?= htmlspecialchars($row['email']) ?></td>
                                <td class="px-6 py-4 text-sm text-gray-600"><?= htmlspecialchars($row['phone'] ?? '-') ?></td>
                                <td class="px-6 py-4">
                                    <?php if ($row['gender'] === 'Male'): ?>
                                        <span class="inline-flex items-center gap-1 px-2.5 py-1 rounded-full text-xs font-medium bg-blue-50 text-blue-700">
                                            <span class="w-1.5 h-1.5 rounded-full bg-blue-500"></span>
                                            Male
                                        </span>
                                    <?php elseif ($row['gender'] === 'Female'): ?>
                                        <span class="inline-flex items-center gap-1 px-2.5 py-1 rounded-full text-xs font-medium bg-pink-50 text-pink-700">
                                            <span class="w-1.5 h-1.5 rounded-full bg-pink-500"></span>
                                            Female
                                        </span>
                                    <?php else: ?>
                                        <span class="inline-flex items-center gap-1 px-2.5 py-1 rounded-full text-xs font-medium bg-gray-50 text-gray-500">
                                            <span class="w-1.5 h-1.5 rounded-full bg-gray-400"></span>
                                            -
                                        </span>
                                    <?php endif; ?>
                                </td>
                                <td class="px-6 py-4 text-sm text-gray-500"><?= date('M j, Y', strtotime($row['created_at'])) ?></td>
                            </tr>
                            <?php endwhile; ?>
                        <?php else: ?>
                            <tr>
                                <td colspan="5" class="px-6 py-12 text-center">
                                    <svg class="w-12 h-12 text-gray-300 mx-auto mb-3" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M12 4.354a4 4 0 110 5.292M15 21H3v-1a6 6 0 0112 0v1zm0 0h6v-1a6 6 0 00-9-5.197m13.5-9a2.5 2.5 0 11-5 0 2.5 2.5 0 015 0z"/></svg>
                                    <p class="text-sm text-gray-400">No students found</p>
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
    const query = this.value.toLowerCase();
    const rows = document.querySelectorAll('.student-row');
    
    rows.forEach(row => {
        // Find the cell that contains the student name (the first <td>)
        const nameCell = row.querySelector('td:first-child');
        // Get the name text specifically
        const nameText = nameCell.textContent.toLowerCase();
        
        // Hide/Show based ONLY on nameText match
        row.style.display = nameText.includes(query) ? '' : 'none';
    });
});
</script>

<?php require_once 'includes/footer.php'; ?>