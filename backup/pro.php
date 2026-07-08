<?php
session_start();
if (!isset($_SESSION['user_id'])) {
    header("Location: ../auth/login.php");
    exit();
}
require_once '../config/db.php';
include_once('../includes/header.php');

$stmt = $conn->prepare("SELECT name, email, phone, created_at FROM users WHERE id = ?");
$stmt->bind_param("i", $_SESSION['user_id']);
$stmt->execute();
$user = $stmt->get_result()->fetch_assoc();
?>

<div class="min-h-screen bg-[#050a1d] py-12 px-4 sm:px-6 lg:px-8">
    <div class="max-w-6xl mx-auto grid grid-cols-1 lg:grid-cols-2 gap-8">
        
        <div class="bg-[#0a0f20] p-8 rounded-3xl border border-gray-800 shadow-xl">
            <div class="flex items-center space-x-5 mb-10">
                <div class="w-20 h-20 bg-brandOrange rounded-2xl flex items-center justify-center text-white text-3xl font-bold shadow-lg shadow-blue-900/20">
                    <?php echo strtoupper(substr($user['name'], 0, 1)); ?>
                </div>
                <div>
                    <h2 class="text-xl font-bold text-white"><?php echo htmlspecialchars($user['name']); ?></h2>
                    <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-blue-900/30 text-blue-400 border border-blue-800/50">
                        Verified Student
                    </span>
                </div>
            </div>

            <div class="space-y-6">
                <div>
                    <label class="text-xs font-semibold text-gray-500 uppercase tracking-wider block mb-1">Email Address</label>
                    <p class="text-gray-300 font-medium"><?php echo htmlspecialchars($user['email']); ?></p>
                </div>
                <div>
                    <label class="text-xs font-semibold text-gray-500 uppercase tracking-wider block mb-1">Phone Number</label>
                    <p class="text-gray-300 font-medium"><?php echo htmlspecialchars($user['phone'] ?? 'N/A'); ?></p>
                </div>
                <div>
                    <label class="text-xs font-semibold text-gray-500 uppercase tracking-wider block mb-1">Member Since</label>
                    <p class="text-gray-300 font-medium"><?php echo date("F j, Y", strtotime($user['created_at'])); ?></p>
                </div>
            </div>

            <div class="mt-10 flex gap-4">
                <a href="edit-profile.php" class="flex-1 text-center py-3 bg-brandOrange text-white font-bold rounded-xl hover:opacity-90 transition">Edit Profile</a>
                <a href="../auth/logout.php" class="px-6 py-3 bg-[#1f2937] hover:bg-[#374151] text-gray-300 font-bold rounded-xl transition">Logout</a>
            </div>
        </div>

        <div class="space-y-8">
            <div class="grid grid-cols-2 gap-4">
                <div class="bg-[#0a0f20] p-6 rounded-2xl border border-gray-800 text-center">
                    <p class="text-blue-500 text-3xl font-bold">0</p>
                    <p class="text-gray-400 text-[10px] uppercase font-bold tracking-wider">Courses Learning</p>
                </div>
                <div class="bg-[#0a0f20] p-6 rounded-2xl border border-gray-800 text-center">
                    <p class="text-green-500 text-3xl font-bold">0</p>
                    <p class="text-gray-400 text-[10px] uppercase font-bold tracking-wider">Courses Completed</p>
                </div>
            </div>

            <div class="bg-[#0a0f20] p-8 rounded-3xl border border-gray-800">
                <h3 class="text-xl font-bold text-white mb-4">My Certificates</h3>
                <p class="text-gray-500">No certificates completed yet.</p>
            </div>

            <div class="bg-[#0a0f20] p-8 rounded-3xl border border-gray-800">
                <h3 class="text-xl font-bold text-white mb-4">Enrolled Courses</h3>
                <p class="text-gray-500">You are not enrolled in any courses yet.</p>
            </div>
        </div>
    </div>
</div>

<?php include_once('../includes/footer.php'); ?>