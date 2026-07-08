<?php
// Logic at the top to prevent "headers already sent" errors
session_start();
if (!isset($_SESSION['user_id'])) {
    header("Location: ../auth/login.php");
    exit();
}
require_once '../config/db.php';
include_once('../includes/header.php');

// Fetch user data
$stmt = $conn->prepare("SELECT name, email, phone, created_at FROM users WHERE id = ?");
$stmt->bind_param("i", $_SESSION['user_id']);
$stmt->execute();
$user = $stmt->get_result()->fetch_assoc();
?>

<div class="min-h-screen bg-gray-50 py-12 px-4 sm:px-6 lg:px-8">
    <div class="max-w-6xl mx-auto grid grid-cols-1 lg:grid-cols-3 gap-8">
        
        <div class="bg-white p-8 rounded-xl border border-gray-200">
                <div class="flex items-center space-x-5 mb-10">
                    <div class="w-20 h-20 bg-brandOrange rounded-full flex items-center justify-center text-white text-3xl font-bold shadow-lg shadow-blue-900/20">
                        <?php echo strtoupper(substr($user['name'], 0, 1)); ?>
                    </div>

                    <div>
                        <h2 class="text-xl font-bold text-white"><?php echo htmlspecialchars($user['name']); ?></h2>
                        <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-blue-900/30 text-blue-400 border border-blue-800/50">
                            Verified Student
                        </span>
                    </div>

                </div>

                <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                    <!-- <div class="bg-[#111827] p-4 rounded-xl border border-gray-700/50">
                        <label class="text-xs font-semibold text-gray-500 uppercase tracking-wider">Full Name</label>
                        <p class="text-gray-200 mt-1 font-medium"><?php echo htmlspecialchars($user['name']); ?></p>
                    </div> -->

                        <!-- <label class="text-xs font-semibold text-gray-500 uppercase tracking-wider">Email Address</label>
                        <p class="text-gray-200 mt-1 font-medium"><?php echo htmlspecialchars($user['email']); ?></p> -->
                    <div class="space-y-6">
                        <div class="mb-8">
                            <label class="text-xs font-semibold text-gray-500 uppercase tracking-wider block mb-1">Email Address</label>
                            <p class="text-gray-300 font-medium"><?php echo htmlspecialchars($user['email']); ?></p>
                        </div>

                        <div class="mb-8">
                            <label class="text-xs font-semibold text-gray-500 uppercase tracking-wider block mb-1">phone number</label>
                            <p class="text-gray-300 font-medium"><?php echo htmlspecialchars($user['phone']); ?></p>
                        </div>

                        <div class="mb-8">
                            <label class="text-xs font-semibold text-gray-500 uppercase tracking-wider block mb-1">Member Since</label>
                            <p class="text-gray-300 font-medium"><?php echo date("F j, Y", strtotime($user['created_at'])); ?></p>
                        </div>
                    </div>

                </div>

                <div class="mt-10 flex gap-4">
                    <a href="edit_profile.php" class="flex-1 text-center py-3 bg-brandOrange hover:bg-brandOrange text-white font-bold rounded-xl transition duration-200 shadow-lg shadow-blue-900/20">
                        Edit Profile
                    </a>

                    <a href="../auth/logout.php" class="px-6 py-3 bg-[#1f2937] hover:bg-[#374151] text-gray-300 font-bold rounded-xl transition duration-200">
                        Logout
                    </a>
                </div>
        </div>

        <div class="lg:col-span-2 space-y-8">
            <div class="flex gap-4 items-center justify-center">
                <div class="bg-white p-6 rounded-2xl border border-gray-200 text-center w-48">
                    <p class="text-blue-500 text-3xl font-bold">0</p>
                    <p class="text-gray-400 text-[10px] uppercase font-bold tracking-wider">Courses Learning</p>
                </div>
                <div class="bg-white p-6 rounded-2xl border border-gray-200 text-center w-48">
                    <p class="text-green-500 text-3xl font-bold">0</p>
                    <p class="text-gray-400 text-[10px] uppercase font-bold tracking-wider">Courses Completed</p>
                </div>
            </div>

            <div class="p-8 text-center">
                <h3 class="text-xl font-bold text-brandOrange mb-4">My Certificates</h3>
                <p class="text-gray-500">No certificates completed yet.</p>
            </div>

            <div class="p-8 text-center">
                <h3 class="text-xl font-bold text-brandOrange mb-4">Enrolled Courses</h3>
                <p class="text-gray-500">You are not enrolled in any courses yet.</p>
            </div>
        </div>
    </div>
</div>

<?php include_once('../includes/footer.php'); ?>