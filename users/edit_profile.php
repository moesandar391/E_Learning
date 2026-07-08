<?php
session_start();
if (!isset($_SESSION['user_id'])) {
    header("Location: ../auth/login.php");
    exit();
}
require_once '../config/db.php';

// Handle Form Submission (Text fields only)
$message = "";
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $new_name = $_POST['name'];
    $new_email = $_POST['email'];
    $new_phone = $_POST['phone'];
    $user_id = $_SESSION['user_id'];
    
    $stmt = $conn->prepare("UPDATE users SET name = ?, email = ?, phone = ? WHERE id = ?");
    $stmt->bind_param("sssi", $new_name, $new_email, $new_phone, $user_id);
    
    if ($stmt->execute()) {
        $_SESSION['username'] = $new_name;
        header("Location: profile.php");
        exit();
    } else {
        $message = "Error updating profile.";
    }
}

// Fetch current user data
$stmt = $conn->prepare("SELECT name, email, phone FROM users WHERE id = ?");
$stmt->bind_param("i", $_SESSION['user_id']);
$stmt->execute();
$user = $stmt->get_result()->fetch_assoc();

include_once('../includes/header.php');
?>

<div class="min-h-screen bg-gray-50 py-12 px-4">
    <div class="max-w-xl mx-auto bg-white p-8 rounded-3xl border border-gray-200 shadow-sm">
        <h2 class="text-2xl text-center font-bold text-brandOrange mb-6">Edit Profile</h2>

        <form method="POST" class="space-y-6">
            <div>
                <label class="text-xs font-semibold text-gray-500 uppercase block mb-2">Full Name</label>
                <input type="text" name="name" value="<?php echo htmlspecialchars($user['name']); ?>" 
                       class="w-full bg-gray-50 border border-gray-200 rounded-xl px-4 py-3 text-gray-900 focus:ring-2 focus:ring-brandOrange outline-none">
            </div>
            
            <div>
                <label class="text-xs font-semibold text-gray-500 uppercase block mb-2">Email Address</label>
                <input type="email" name="email" value="<?php echo htmlspecialchars($user['email']); ?>" 
                       class="w-full bg-gray-50 border border-gray-200 rounded-xl px-4 py-3 text-gray-900 focus:ring-2 focus:ring-brandOrange outline-none">
            </div>

            <div>
                <label class="text-xs font-semibold text-gray-500 uppercase block mb-2">Phone Number</label>
                <input type="text" name="phone" value="<?php echo htmlspecialchars($user['phone']); ?>" 
                       class="w-full bg-gray-50 border border-gray-200 rounded-xl px-4 py-3 text-gray-900 focus:ring-2 focus:ring-brandOrange outline-none">
            </div>

            <div class="flex gap-4 pt-4">
                <button type="submit" class="flex-1 py-3 bg-brandOrange text-white font-bold rounded-xl hover:opacity-90 transition">Save Changes</button>
                <a href="profile.php" class="px-6 py-3 bg-gray-100 text-gray-700 font-bold rounded-xl hover:bg-gray-200 transition">Cancel</a>
            </div>
        </form>
    </div>
</div>

<?php include_once('../includes/footer.php'); ?>