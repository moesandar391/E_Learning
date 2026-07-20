<?php
session_start();
if (!isset($_SESSION['user_id'])) {
    header("Location: ../auth/login.php");
    exit();
}
require_once '../config/db.php';

$message = "";
$messageType = "";

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $user_id = $_SESSION['user_id'];

    if (isset($_POST['update_profile'])) {
        $new_name = trim($_POST['name']);
        $new_email = trim($_POST['email']);
        $new_phone = trim($_POST['phone']);
        $new_address = trim($_POST['address']);
        $new_gender = trim($_POST['gender']);
        $new_dob = trim($_POST['date_of_birth']);

        $stmt = $conn->prepare("UPDATE users SET name = ?, email = ?, phone = ?, address = ?, gender = ?, date_of_birth = ? WHERE id = ?");
        $stmt->bind_param("ssssssi", $new_name, $new_email, $new_phone, $new_address, $new_gender, $new_dob, $user_id);

        if ($stmt->execute()) {
            $_SESSION['username'] = $new_name;
            $message = "Profile updated successfully!";
            $messageType = "success";
        } else {
            $message = "Error updating profile.";
            $messageType = "error";
        }
    }

    if (isset($_POST['change_password'])) {
        $current = $_POST['current_password'];
        $new_pass = $_POST['new_password'];
        $confirm = $_POST['confirm_password'];

        $res = $conn->query("SELECT password FROM users WHERE id = $user_id");
        $row = $res->fetch_assoc();

        if (!password_verify($current, $row['password'])) {
            $message = "Current password is incorrect.";
            $messageType = "error";
        } elseif ($new_pass !== $confirm) {
            $message = "New passwords do not match.";
            $messageType = "error";
        } elseif (strlen($new_pass) < 6) {
            $message = "Password must be at least 6 characters.";
            $messageType = "error";
        } else {
            $hash = password_hash($new_pass, PASSWORD_DEFAULT);
            $conn->query("UPDATE users SET password = '$hash' WHERE id = $user_id");
            $message = "Password changed successfully!";
            $messageType = "success";
        }
    }
}

$stmt = $conn->prepare("SELECT name, email, phone, profile_image, address, gender, date_of_birth FROM users WHERE id = ?");
$stmt->bind_param("i", $_SESSION['user_id']);
$stmt->execute();
$user = $stmt->get_result()->fetch_assoc();

include_once('../includes/header.php');
?>

<div class="min-h-screen bg-gradient-to-br from-orange-50 via-white to-amber-50 py-12 px-4 sm:px-6 lg:px-8">
    <div class="max-w-3xl mx-auto">

        <!-- Header -->
        <div class="flex items-center justify-between mb-8">
            <div>
                <h1 class="text-2xl font-bold text-brandOchre">Edit Profile</h1>
                <p class="text-sm text-gray-400 mt-1">Update your personal information</p>
            </div>
            <a href="profile.php" class="inline-flex items-center gap-2 text-sm font-bold text-gray-500 hover:text-brandOrange transition-colors">
                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 19l-7-7m0 0l7-7m-7 7h18"/></svg>
                Back to Profile
            </a>
        </div>

        <?php if ($message): ?>
        <div class="<?php echo $messageType === 'success' ? 'bg-green-50 border-green-200 text-green-700' : 'bg-red-50 border-red-200 text-red-600'; ?> border rounded-xl px-5 py-3 mb-6 text-sm font-medium flex items-center gap-2">
            <svg class="w-5 h-5 flex-shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <?php if ($messageType === 'success'): ?>
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"/>
                <?php else: ?>
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4m0 4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"/>
                <?php endif; ?>
            </svg>
            <?php echo $message; ?>
        </div>
        <?php endif; ?>

        <!-- Profile Image Card -->
        <div class="bg-white rounded-3xl border border-gray-200 shadow-sm p-6 mb-6">
            <div class="flex items-center gap-6">
                <div class="relative">
                    <div class="w-20 h-20 rounded-2xl overflow-hidden shadow-md <?php echo $user['profile_image'] && file_exists('../users/' . $user['profile_image']) ? '' : 'bg-brandOrange'; ?>">
                        <?php if ($user['profile_image'] && file_exists('../users/' . $user['profile_image'])): ?>
                            <img src="<?php echo htmlspecialchars($user['profile_image']); ?>" alt="" class="w-full h-full object-cover">
                        <?php else: ?>
                            <div class="w-full h-full flex items-center justify-center text-white text-2xl font-bold"><?php echo strtoupper(substr($user['name'], 0, 1)); ?></div>
                        <?php endif; ?>
                    </div>
                    <button onclick="document.getElementById('editProfileImage').click();" class="absolute -bottom-1 -right-1 bg-brandOrange border-2 border-white rounded-full p-1.5 shadow-md hover:bg-orange-600 transition-all cursor-pointer hover:scale-110">
                        <svg class="w-3 h-3 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 9a2 2 0 012-2h.93a2 2 0 001.664-.89l.812-1.22A2 2 0 0110.07 4h3.86a2 2 0 011.664.89l.812 1.22A2 2 0 0018.07 7H19a2 2 0 012 2v9a2 2 0 01-2 2H5a2 2 0 01-2-2V9z"/><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 13a3 3 0 11-6 0 3 3 0 016 0z"/></svg>
                    </button>
                    <input type="file" id="editProfileImage" accept="image/jpeg,image/png,image/gif,image/webp" class="hidden" onchange="uploadFromEdit(this)">
                </div>
                <div>
                    <p class="text-sm font-semibold text-gray-800"><?php echo htmlspecialchars($user['name']); ?></p>
                    <p class="text-xs text-gray-400 mt-0.5">Click the camera icon to change your profile photo</p>
                </div>
            </div>
        </div>

        <!-- Form -->
        <div class="bg-white rounded-3xl border border-gray-200 shadow-sm p-8">
            <form method="POST" class="space-y-6">

                <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                    <div>
                        <label class="text-xs font-bold text-gray-500 uppercase tracking-wider block mb-2">Full Name</label>
                        <input type="text" name="name" value="<?php echo htmlspecialchars($user['name']); ?>" required
                               class="w-full bg-gray-50 border border-gray-200 rounded-xl px-4 py-3 text-gray-900 text-sm focus:ring-2 focus:ring-brandOrange focus:border-brandOrange outline-none transition-all">
                    </div>
                    <div>
                        <label class="text-xs font-bold text-gray-500 uppercase tracking-wider block mb-2">Email Address</label>
                        <input type="email" name="email" value="<?php echo htmlspecialchars($user['email']); ?>" required
                               class="w-full bg-gray-50 border border-gray-200 rounded-xl px-4 py-3 text-gray-900 text-sm focus:ring-2 focus:ring-brandOrange focus:border-brandOrange outline-none transition-all">
                    </div>
                    <div>
                        <label class="text-xs font-bold text-gray-500 uppercase tracking-wider block mb-2">Phone Number</label>
                        <input type="text" name="phone" value="<?php echo htmlspecialchars($user['phone']); ?>"
                               class="w-full bg-gray-50 border border-gray-200 rounded-xl px-4 py-3 text-gray-900 text-sm focus:ring-2 focus:ring-brandOrange focus:border-brandOrange outline-none transition-all">
                    </div>
                    <div>
                        <label class="text-xs font-bold text-gray-500 uppercase tracking-wider block mb-2">Gender</label>
                        <select name="gender"
                                class="w-full bg-gray-50 border border-gray-200 rounded-xl px-4 py-3 text-gray-900 text-sm focus:ring-2 focus:ring-brandOrange focus:border-brandOrange outline-none transition-all">
                            <option value="">Select Gender</option>
                            <option value="Male" <?php echo $user['gender'] === 'Male' ? 'selected' : ''; ?>>Male</option>
                            <option value="Female" <?php echo $user['gender'] === 'Female' ? 'selected' : ''; ?>>Female</option>
                            <option value="Other" <?php echo $user['gender'] === 'Other' ? 'selected' : ''; ?>>Other</option>
                        </select>
                    </div>
                    <div>
                        <label class="text-xs font-bold text-gray-500 uppercase tracking-wider block mb-2">Date of Birth</label>
                        <input type="date" name="date_of_birth" value="<?php echo $user['date_of_birth']; ?>"
                               class="w-full bg-gray-50 border border-gray-200 rounded-xl px-4 py-3 text-gray-900 text-sm focus:ring-2 focus:ring-brandOrange focus:border-brandOrange outline-none transition-all">
                    </div>
                    <div>
                        <label class="text-xs font-bold text-gray-500 uppercase tracking-wider block mb-2">Address</label>
                        <input type="text" name="address" value="<?php echo htmlspecialchars($user['address'] ?? ''); ?>"
                               class="w-full bg-gray-50 border border-gray-200 rounded-xl px-4 py-3 text-gray-900 text-sm focus:ring-2 focus:ring-brandOrange focus:border-brandOrange outline-none transition-all">
                    </div>
                </div>

                <div class="flex gap-4 pt-6 border-t border-gray-100">
                    <button type="submit" name="update_profile" class="flex-1 py-3 bg-brandOrange text-white font-bold rounded-xl hover:bg-orange-600 transition-all shadow-md hover:shadow-lg text-sm">
                        <svg class="w-4 h-4 inline mr-1.5 -mt-0.5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"/></svg>
                        Save Changes
                    </button>
                    <a href="profile.php" class="px-8 py-3 bg-gray-100 text-gray-600 font-bold rounded-xl hover:bg-gray-200 transition-all text-sm">Cancel</a>
                </div>
            </form>
        </div>

        <!-- Password Change -->
        <div class="bg-white rounded-3xl border border-gray-200 shadow-sm p-8 mt-6">
            <div class="flex items-center gap-3 mb-6">
                <div class="w-10 h-10 rounded-xl bg-orange-50 flex items-center justify-center">
                    <svg class="w-5 h-5 text-brandOrange" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 15v2m-6 4h12a2 2 0 002-2v-6a2 2 0 00-2-2H6a2 2 0 00-2 2v6a2 2 0 002 2zm10-10V7a4 4 0 00-8 0v4h8z"/></svg>
                </div>
                <div>
                    <h3 class="text-sm font-bold text-gray-700">Change Password</h3>
                    <p class="text-xs text-gray-400">Update your account password</p>
                </div>
            </div>
            <form method="POST" class="space-y-4">
                <div class="grid grid-cols-1 md:grid-cols-3 gap-4">
                    <div>
                        <label class="text-xs font-bold text-gray-500 uppercase tracking-wider block mb-2">Current Password</label>
                        <input type="password" name="current_password" required
                               class="w-full bg-gray-50 border border-gray-200 rounded-xl px-4 py-3 text-gray-900 text-sm focus:ring-2 focus:ring-brandOrange focus:border-brandOrange outline-none transition-all">
                    </div>
                    <div>
                        <label class="text-xs font-bold text-gray-500 uppercase tracking-wider block mb-2">New Password</label>
                        <input type="password" name="new_password" required minlength="6"
                               class="w-full bg-gray-50 border border-gray-200 rounded-xl px-4 py-3 text-gray-900 text-sm focus:ring-2 focus:ring-brandOrange focus:border-brandOrange outline-none transition-all">
                    </div>
                    <div>
                        <label class="text-xs font-bold text-gray-500 uppercase tracking-wider block mb-2">Confirm Password</label>
                        <input type="password" name="confirm_password" required minlength="6"
                               class="w-full bg-gray-50 border border-gray-200 rounded-xl px-4 py-3 text-gray-900 text-sm focus:ring-2 focus:ring-brandOrange focus:border-brandOrange outline-none transition-all">
                    </div>
                </div>
                <div class="flex justify-end pt-2">
                    <button type="submit" name="change_password" class="px-6 py-2.5 bg-gray-800 text-white text-sm font-bold rounded-xl hover:bg-gray-700 transition-all">
                        <svg class="w-4 h-4 inline mr-1.5 -mt-0.5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 15v2m-6 4h12a2 2 0 002-2v-6a2 2 0 00-2-2H6a2 2 0 00-2 2v6a2 2 0 002 2zm10-10V7a4 4 0 00-8 0v4h8z"/></svg>
                        Update Password
                    </button>
                </div>
            </form>
        </div>
    </div>
</div>

<script>
function uploadFromEdit(input) {
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
