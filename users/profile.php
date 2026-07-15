<?php 
session_start();
if (!isset($_SESSION['user_id'])) {
    header("Location: ../auth/login.php");
    exit();
}
require_once '../config/db.php';
include_once('../includes/header.php');

 $userId = $_SESSION['user_id'];

// Fetch user data
 $stmt = $conn->prepare("SELECT name, email, phone, created_at FROM users WHERE id = ?");
 $stmt->bind_param("i", $userId);
 $stmt->execute();
 $user = $stmt->get_result()->fetch_assoc();

?>

<div class="min-h-screen bg-gray-50 py-12 px-4 sm:px-6 lg:px-8">
    <div class="max-w-6xl mx-auto flex justify-center">
        
        <div class="bg-white p-8 rounded-xl border border-gray-200 w-full max-w-md h-fit">
                <div class="flex items-center space-x-5 mb-10">
                    <!-- Wrap in a relative div -->
                    <div class="relative">
                        <!-- Main Avatar -->
                        <div class="w-20 h-20 bg-brandOrange rounded-full flex items-center justify-center text-white text-3xl font-bold shadow-lg shadow-blue-900/20">
                            <?php echo strtoupper(substr($user['name'], 0, 1)); ?>
                        </div>
                         
                        <!-- Active Status Indicator (Green Dot) -->
                        <!-- <div class="absolute top-0 right-0 w-5 h-5 bg-green-500 border-2 border-white rounded-full"></div> -->
                        
                        <!-- Camera Icon Overlay -->
                        <div class="absolute bottom-0 right-0 bg-brandOrange border-2 border-white rounded-full p-1.5 shadow-md">
                            <svg class="w-4 h-4 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 9a2 2 0 012-2h.93a2 2 0 001.664-.89l.812-1.22A2 2 0 0110.07 4h3.86a2 2 0 011.664.89l.812 1.22A2 2 0 0018.07 7H19a2 2 0 012 2v9a2 2 0 01-2 2H5a2 2 0 01-2-2V9z"/>
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 13a3 3 0 11-6 0 3 3 0 016 0z"/>
                            </svg>
                        </div>
                    </div>

                    <div>
                        <h2 class="text-xl font-bold text-gray-900"><?php echo htmlspecialchars($user['name']); ?></h2>
                        <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-blue-100 text-blue-800 border border-blue-200">
                        Verified Student
                        </span>
                    </div>
                </div>

                <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                    <div class="space-y-6">
                        <div class="mb-8">
                            <label class="text-xs font-semibold text-gray-500 uppercase tracking-wider block mb-1">Email Address</label>
                            <p class="text-gray-700 font-medium"><?php echo htmlspecialchars($user['email']); ?></p>
                        </div>

                        <div class="mb-8">
                            <label class="text-xs font-semibold text-gray-500 uppercase tracking-wider block mb-1">Phone Number</label>
                            <p class="text-gray-700 font-medium"><?php echo htmlspecialchars($user['phone']); ?></p>
                        </div>

                        <div class="mb-8">
                            <label class="text-xs font-semibold text-gray-500 uppercase tracking-wider block mb-1">Member Since</label>
                            <p class="text-gray-700 font-medium"><?php echo date("F j, Y", strtotime($user['created_at'])); ?></p>
                        </div>
                    </div>
                </div>

                <div class="mt-10 flex gap-4">
                    <a href="edit_profile.php" class="flex-1 text-center py-3 bg-brandOrange hover:bg-orange-600 text-white font-bold rounded-xl transition duration-200 shadow-lg">
                        Edit Profile
                    </a>
                    <a href="../auth/logout.php" class="px-6 py-3 bg-gray-200 hover:bg-gray-300 text-gray-700 font-bold rounded-xl transition duration-200">
                        Logout
                    </a>
                </div>
        </div>

    </div>
</div>

<?php include_once('../includes/footer.php'); ?>