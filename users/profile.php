<?php 
session_start();
if (!isset($_SESSION['user_id'])) {
    header("Location: ../auth/login.php");
    exit();
}
require_once '../config/db.php';
include_once('../includes/header.php');

$userId = $_SESSION['user_id'];

$stmt = $conn->prepare("SELECT name, email, phone, profile_image, address, gender, date_of_birth, created_at FROM users WHERE id = ?");
$stmt->bind_param("i", $userId);
$stmt->execute();
$user = $stmt->get_result()->fetch_assoc();

$stats = $conn->query("SELECT COUNT(*) AS total FROM enrollments WHERE user_id = $userId AND status = 'confirmed'")->fetch_assoc()['total'] ?? 0;
$reviews = $conn->query("SELECT COUNT(*) AS total FROM reviews WHERE user_id = $userId")->fetch_assoc()['total'] ?? 0;
$lessons = $conn->query("
    SELECT COUNT(DISTINCT l.id) AS total FROM enrollments e
    JOIN modules m ON e.module_id = m.id
    JOIN lessons l ON m.id = l.module_id
    WHERE e.user_id = $userId AND e.status = 'confirmed'
")->fetch_assoc()['total'] ?? 0;
?>

<div class="min-h-screen bg-gradient-to-br from-orange-50 via-white to-amber-50 dark:from-gray-900 dark:via-gray-800 dark:to-gray-900 py-12 px-4 sm:px-6 lg:px-8">
    <div class="max-w-6xl mx-auto">

        <!-- Profile Header -->
        <div class="bg-white dark:bg-gray-800 rounded-3xl border border-gray-200 dark:border-gray-700 shadow-sm p-8 mb-8">
            <div class="flex flex-col sm:flex-row items-center sm:items-start gap-6">
                <div class="relative flex-shrink-0">
                    <div class="w-28 h-28 rounded-3xl overflow-hidden shadow-lg ring-4 ring-orange-100 dark:ring-orange-900 <?php echo $user['profile_image'] && file_exists('../users/' . $user['profile_image']) ? '' : 'bg-gradient-to-br from-brandOrange to-orange-400'; ?>">
                        <?php if ($user['profile_image'] && file_exists('../users/' . $user['profile_image'])): ?>
                            <img src="<?php echo htmlspecialchars($user['profile_image']); ?>" alt="Profile" class="w-full h-full object-cover" id="profileAvatar">
                        <?php else: ?>
                            <div class="w-full h-full flex items-center justify-center text-white text-4xl font-bold" id="profileInitial">
                                <?php echo strtoupper(substr($user['name'], 0, 1)); ?>
                            </div>
                        <?php endif; ?>
                    </div>
                    <button onclick="document.getElementById('profileImageInput').click();" class="absolute -bottom-1 -right-1 bg-brandOrange border-2 border-white rounded-full p-2 shadow-md hover:bg-orange-600 transition-all cursor-pointer hover:scale-110">
                        <svg class="w-3.5 h-3.5 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 9a2 2 0 012-2h.93a2 2 0 001.664-.89l.812-1.22A2 2 0 0110.07 4h3.86a2 2 0 011.664.89l.812 1.22A2 2 0 0018.07 7H19a2 2 0 012 2v9a2 2 0 01-2 2H5a2 2 0 01-2-2V9z"/>
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 13a3 3 0 11-6 0 3 3 0 016 0z"/>
                        </svg>
                    </button>
                    <input type="file" id="profileImageInput" accept="image/jpeg,image/png,image/gif,image/webp" class="hidden" onchange="uploadProfileImage(this)">
                </div>
                <div class="flex-1 text-center sm:text-left">
                    <h1 class="text-3xl font-bold text-gray-800 dark:text-gray-100"><?php echo htmlspecialchars($user['name']); ?></h1>
                    <p class="text-sm text-gray-400 flex items-center justify-center sm:justify-start gap-1.5 mt-1.5">
                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 8l7.89 5.26a2 2 0 002.22 0L21 8M5 19h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v10a2 2 0 002 2z"/></svg>
                        <?php echo htmlspecialchars($user['email']); ?>
                    </p>
                    <div class="flex flex-wrap items-center justify-center sm:justify-start gap-3 mt-4">
                        <span class="inline-flex items-center gap-1.5 px-3.5 py-1.5 bg-green-50 text-green-700 text-xs font-bold rounded-full border border-green-200">
                            <span class="w-2 h-2 bg-green-400 rounded-full"></span>
                            Active Account
                        </span>
                        <span class="inline-flex items-center gap-1.5 px-3.5 py-1.5 bg-blue-50 text-blue-600 text-xs font-bold rounded-full border border-blue-200">
                            <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v10a2 2 0 002 2z"/></svg>
                            Joined <?php echo date("M Y", strtotime($user['created_at'])); ?>
                        </span>
                    </div>
                </div>
                <div class="flex flex-col gap-2 flex-shrink-0">
                    <a href="edit_profile.php" class="inline-flex items-center justify-center gap-2 px-6 py-3 bg-brandOrange text-white text-sm font-bold rounded-xl hover:bg-orange-600 transition-all shadow-md hover:shadow-lg">
                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z"/></svg>
                        Edit Profile
                    </a>
                    <a href="../auth/logout.php" class="inline-flex items-center justify-center gap-2 px-6 py-3 bg-gray-100 text-gray-600 text-sm font-bold rounded-xl hover:bg-gray-200 transition-all">
                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 16l4-4m0 0l-4-4m4 4H7m6 4v1a3 3 0 01-3 3H6a3 3 0 01-3-3V7a3 3 0 013-3h4a3 3 0 013 3v1"/></svg>
                        Sign Out
                    </a>
                </div>
            </div>
        </div>

        <!-- Stats Row -->
        <div class="grid grid-cols-1 sm:grid-cols-3 gap-4 mb-8">
            <div class="bg-white dark:bg-gray-800 rounded-2xl border border-gray-200 dark:border-gray-700 shadow-sm p-5 flex items-center gap-4">
                <div class="w-12 h-12 rounded-xl bg-orange-50 dark:bg-orange-900/30 flex items-center justify-center flex-shrink-0">
                    <svg class="w-6 h-6 text-brandOrange" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 6.253v13m0-13C10.832 5.477 9.246 5 7.5 5S4.168 5.477 3 6.253v13C4.168 18.477 5.754 18 7.5 18s3.332.477 4.5 1.253m0-13C13.168 5.477 14.754 5 16.5 5c1.747 0 3.332.477 4.5 1.253v13C19.832 18.477 18.247 18 16.5 18c-1.746 0-3.332.477-4.5 1.253"/></svg>
                </div>
                <div>
                    <p class="text-2xl font-bold text-gray-800 dark:text-gray-100"><?php echo $stats; ?></p>
                    <p class="text-xs text-gray-400 font-medium">Enrolled Courses</p>
                </div>
            </div>
            <div class="bg-white dark:bg-gray-800 rounded-2xl border border-gray-200 dark:border-gray-700 shadow-sm p-5 flex items-center gap-4">
                <div class="w-12 h-12 rounded-xl bg-yellow-50 dark:bg-yellow-900/30 flex items-center justify-center flex-shrink-0">
                    <svg class="w-6 h-6 text-yellow-500" fill="currentColor" viewBox="0 0 20 20"><path d="M9.049 2.927c.3-.921 1.603-.921 1.902 0l1.07 3.292a1 1 0 00.95.69h3.462c.969 0 1.371 1.24.588 1.81l-2.8 2.034a1 1 0 00-.364 1.118l1.07 3.292c.3.921-.755 1.688-1.54 1.118l-2.8-2.034a1 1 0 00-1.175 0l-2.8 2.034c-.784.57-1.838-.197-1.539-1.118l1.07-3.292a1 1 0 00-.364-1.118L2.98 8.72c-.783-.57-.38-1.81.588-1.81h3.461a1 1 0 00.951-.69l1.07-3.292z"/></svg>
                </div>
                <div>
                    <p class="text-2xl font-bold text-gray-800 dark:text-gray-100"><?php echo $reviews; ?></p>
                    <p class="text-xs text-gray-400 font-medium">Reviews Given</p>
                </div>
            </div>
            <div class="bg-white dark:bg-gray-800 rounded-2xl border border-gray-200 dark:border-gray-700 shadow-sm p-5 flex items-center gap-4">
                <div class="w-12 h-12 rounded-xl bg-blue-50 dark:bg-blue-900/30 flex items-center justify-center flex-shrink-0">
                    <svg class="w-6 h-6 text-blue-500" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"/></svg>
                </div>
                <div>
                    <p class="text-2xl font-bold text-gray-800 dark:text-gray-100"><?php echo $lessons; ?></p>
                    <p class="text-xs text-gray-400 font-medium">Available Lessons</p>
                </div>
            </div>
        </div>

        <!-- Main Content Grid -->
        <div class="grid grid-cols-1 lg:grid-cols-2 gap-8">
            <!-- Left: Personal Information -->
            <div>
                <div class="bg-white dark:bg-gray-800 rounded-2xl border border-gray-200 dark:border-gray-700 shadow-sm p-6 h-full">
                    <h3 class="text-sm font-bold text-gray-700 dark:text-gray-300 mb-5 flex items-center gap-2">
                        <svg class="w-4 h-4 text-brandOrange" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z"/></svg>
                        Personal Information
                    </h3>
                    <div class="space-y-4">
                        <div class="flex items-center justify-between p-3 bg-gray-50 dark:bg-gray-700/50 rounded-xl">
                            <span class="text-sm text-gray-600 dark:text-gray-400">Phone</span>
                            <span class="text-sm font-semibold text-gray-800 dark:text-gray-200"><?php echo htmlspecialchars($user['phone'] ?: 'Not set'); ?></span>
                        </div>
                        <div class="flex items-center justify-between p-3 bg-gray-50 dark:bg-gray-700/50 rounded-xl">
                            <span class="text-sm text-gray-600 dark:text-gray-400">Gender</span>
                            <span class="text-sm font-semibold text-gray-800 dark:text-gray-200"><?php echo htmlspecialchars($user['gender'] ?: 'Not set'); ?></span>
                        </div>
                        <div class="flex items-center justify-between p-3 bg-gray-50 dark:bg-gray-700/50 rounded-xl">
                            <span class="text-sm text-gray-600 dark:text-gray-400">Date of Birth</span>
                            <span class="text-sm font-semibold text-gray-800 dark:text-gray-200"><?php echo $user['date_of_birth'] ? date("d M Y", strtotime($user['date_of_birth'])) : 'Not set'; ?></span>
                        </div>
                        <div class="flex items-center justify-between p-3 bg-gray-50 dark:bg-gray-700/50 rounded-xl">
                            <span class="text-sm text-gray-600 dark:text-gray-400">Address</span>
                            <span class="text-sm font-semibold text-gray-800 dark:text-gray-200 text-right max-w-[200px]"><?php echo htmlspecialchars($user['address'] ?: 'Not set'); ?></span>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Right: Account Details -->
            <div>
                <div class="bg-white dark:bg-gray-800 rounded-2xl border border-gray-200 dark:border-gray-700 shadow-sm p-6 h-full">
                    <h3 class="text-sm font-bold text-gray-700 dark:text-gray-300 mb-5 flex items-center gap-2">
                        <svg class="w-4 h-4 text-brandOrange" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10.325 4.317c.426-1.756 2.924-1.756 3.35 0a1.724 1.724 0 002.573 1.066c1.543-.94 3.31.826 2.37 2.37a1.724 1.724 0 001.066 2.573c1.756.426 1.756 2.924 0 3.35a1.724 1.724 0 00-1.066 2.573c.94 1.543-.826 3.31-2.37 2.37a1.724 1.724 0 00-2.573 1.066c-.426 1.756-2.924 1.756-3.35 0a1.724 1.724 0 00-1.066-2.573c-1.756-.426-1.756-2.924 0-3.35a1.724 1.724 0 001.066-2.573c-.94-1.543.826-3.31 2.37-2.37.996.608 2.296.07 2.572-1.065z"/><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"/></svg>
                        Account Details
                    </h3>
                    <div class="grid grid-cols-1 sm:grid-cols-2 gap-4">
                        <div class="bg-gradient-to-br from-orange-50 to-white dark:from-gray-700 dark:to-gray-800 rounded-xl p-4 border border-orange-100 dark:border-gray-600">
                            <label class="text-[10px] font-bold text-orange-400 uppercase tracking-wider">Full Name</label>
                            <p class="text-gray-800 dark:text-gray-100 font-semibold mt-1 text-lg"><?php echo htmlspecialchars($user['name']); ?></p>
                        </div>
                        <div class="bg-gradient-to-br from-orange-50 to-white dark:from-gray-700 dark:to-gray-800 rounded-xl p-4 border border-orange-100 dark:border-gray-600">
                            <label class="text-[10px] font-bold text-orange-400 uppercase tracking-wider">Email Address</label>
                            <p class="text-gray-800 dark:text-gray-100 font-semibold mt-1 break-all"><?php echo htmlspecialchars($user['email']); ?></p>
                        </div>
                        <div class="sm:col-span-2 mt-2 flex justify-end">
                            <a href="edit_profile.php" class="inline-flex items-center gap-2 px-5 py-2.5 bg-brandOrange text-white text-sm font-bold rounded-xl hover:bg-orange-600 transition-all shadow-md hover:shadow-lg">
                                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z"/></svg>
                                Manage Account
                            </a>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<script>
function uploadProfileImage(input) {
    var file = input.files[0];
    if (!file) return;
    var formData = new FormData();
    formData.append('profile_image', file);
    fetch('upload_profile_image.php', { method: 'POST', body: formData })
    .then(function(r) { return r.json(); })
    .then(function(data) {
        if (data.success) { location.reload(); }
        else { alert(data.message); }
    })
    .catch(function() { alert('Upload failed'); });
}
</script>

<?php include_once('../includes/footer.php'); ?>
