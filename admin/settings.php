<?php require_once 'includes/header.php'; ?>
<?php require_once 'includes/sidebar.php'; ?>
<?php require_once '../config/db.php'; ?>

<?php
 $message = '';
 $error = '';
 $upload_dir = '../uploads/admin_profile/';

// Make sure upload directory exists
if (!is_dir($upload_dir)) {
    mkdir($upload_dir, 0777, true);
}

if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['update_profile'])) {
    $name = trim($_POST['name'] ?? '');
    $email = trim($_POST['email'] ?? '');
    
    if ($name && $email) {
        // ── Handle Image Upload ──
        if (isset($_FILES['profile_image']) && $_FILES['profile_image']['error'] == 0) {
            $allowed_exts = ['jpg', 'jpeg', 'png', 'gif'];
            $file_name = $_FILES['profile_image']['name'];
            $file_size = $_FILES['profile_image']['size'];
            $file_ext = strtolower(pathinfo($file_name, PATHINFO_EXTENSION));

            if (in_array($file_ext, $allowed_exts) && $file_size < 2 * 1024 * 1024) {
                $new_image_name = 'admin_' . $_SESSION['user_id'] . '_' . time() . '.' . $file_ext;
                $destination = $upload_dir . $new_image_name;

                // Delete old image if it exists
                $stmt_old = $conn->prepare("SELECT profile_image FROM admin WHERE id = ?");
                $stmt_old->bind_param("i", $_SESSION['user_id']);
                $stmt_old->execute();
                $old_img = $stmt_old->get_result()->fetch_assoc();
                if ($old_img['profile_image'] && file_exists($upload_dir . $old_img['profile_image'])) {
                    unlink($upload_dir . $old_img['profile_image']);
                }

                if (move_uploaded_file($_FILES['profile_image']['tmp_name'], $destination)) {
                    $stmt_img = $conn->prepare("UPDATE admin SET profile_image = ? WHERE id = ?");
                    $stmt_img->bind_param("si", $new_image_name, $_SESSION['user_id']);
                    $stmt_img->execute();
                } else {
                    $error = 'Failed to upload image.';
                }
            } else {
                $error = 'Invalid image type (Allowed: jpg, png, gif) or size exceeds 2MB.';
            }
        }

        // ── Update Name and Email ──
        $stmt = $conn->prepare("UPDATE admin SET name = ?, email = ? WHERE id = ?");
        $stmt->bind_param("ssi", $name, $email, $_SESSION['user_id']);
        if ($stmt->execute()) {
            $_SESSION['username'] = $name;
            if (empty($error)) {
                $message = 'Profile updated successfully.';
            }
        } else {
            $error = 'Failed to update profile.';
        }
    } else {
        $error = 'Name and email are required.';
    }
}

if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['change_password'])) {
    $current = $_POST['current_password'] ?? '';
    $new = $_POST['new_password'] ?? '';
    $confirm = $_POST['confirm_password'] ?? '';

    if ($new !== $confirm) {
        $error = 'New passwords do not match.';
    } elseif (strlen($new) < 6) {
        $error = 'Password must be at least 6 characters.';
    } else {
        $stmt = $conn->prepare("SELECT password FROM admin WHERE id = ?");
        $stmt->bind_param("i", $_SESSION['user_id']);
        $stmt->execute();
        $user = $stmt->get_result()->fetch_assoc();

        if ($user && password_verify($current, $user['password'])) {
            $hash = password_hash($new, PASSWORD_DEFAULT);
            $stmt = $conn->prepare("UPDATE admin SET password = ? WHERE id = ?");
            $stmt->bind_param("si", $hash, $_SESSION['user_id']);
            $stmt->execute();
            $message = 'Password changed successfully.';
        } else {
            $error = 'Current password is incorrect.';
        }
    }
}

// Fetch admin data including profile image
 $stmt = $conn->prepare("SELECT name, email, profile_image FROM admin WHERE id = ?");
 $stmt->bind_param("i", $_SESSION['user_id']);
 $stmt->execute();
 $admin = $stmt->get_result()->fetch_assoc();

 $image_path = $admin['profile_image'] ? $upload_dir . $admin['profile_image'] : null;
?>

<div class="flex-1 flex flex-col overflow-hidden">
    <header class="h-16 bg-white border-b border-gray-200 flex items-center justify-between px-8 flex-shrink-0">
        <div>
            <h1 class="text-lg font-semibold text-gray-800">Profiles</h1>
            <p class="text-sm text-gray-500">Manage your account profiles</p>
        </div>
        <div class="flex items-center gap-4">
            <span class="text-sm text-gray-500"><?php echo date('l, F j, Y'); ?></span>
            <?php require_once 'includes/admin_notif_icon.php'; ?>
            <a href="settings.php" class="w-9 h-9 rounded-full bg-gradient-to-br from-brandOrange to-orange-400 text-white flex items-center justify-center text-sm font-bold shadow-sm hover:opacity-90 transition overflow-hidden">
                <?php if($image_path && file_exists($image_path)): ?>
                    <img src="<?= $image_path ?>" class="w-full h-full object-cover">
                <?php else: ?>
                    <?php echo strtoupper(substr($_SESSION['username'] ?? 'A', 0, 1)); ?>
                <?php endif; ?>
            </a>
        </div>
    </header>

    <main class="flex-1 overflow-y-auto p-8">

        <?php if ($message): ?>
        <div class="mb-6 px-4 py-3 rounded-lg bg-green-50 border border-green-200 text-green-700 text-sm flex items-center gap-2">
            <svg class="w-4 h-4 flex-shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"/></svg>
            <?= htmlspecialchars($message) ?>
        </div>
        <?php endif; ?>

        <?php if ($error): ?>
        <div class="mb-6 px-4 py-3 rounded-lg bg-red-50 border border-red-200 text-red-700 text-sm flex items-center gap-2">
            <svg class="w-4 h-4 flex-shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4m0 4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"/></svg>
            <?= htmlspecialchars($error) ?>
        </div>
        <?php endif; ?>

        <div class="grid grid-cols-1 lg:grid-cols-2 gap-8">

            <!-- ── Profile Settings Card ── -->
            <div class="bg-white rounded-xl border border-gray-200 p-6 shadow-sm">
                <form method="POST" enctype="multipart/form-data" class="space-y-6">
                    <!-- Hidden File Input -->
                    <input type="file" name="profile_image" id="profile_image" class="hidden" accept="image/png, image/jpeg, image/gif" onchange="previewImage(this)">
                    
                    <!-- Avatar with Camera Icon -->
                    <div class="flex items-center gap-5">
                        <div class="relative flex-shrink-0">
                            <div id="preview-container" 
                                 class="w-20 h-20 rounded-full bg-gradient-to-br from-orange-100 to-orange-200 border-4 border-white shadow-lg flex items-center justify-center overflow-hidden cursor-pointer"
                                 onclick="document.getElementById('profile_image').click()">
                                <?php if($image_path && file_exists($image_path)): ?>
                                    <img src="<?= $image_path ?>" class="w-full h-full object-cover">
                                <?php else: ?>
                                    <span class="text-3xl font-bold text-brandOrange"><?= strtoupper(substr($admin['name'] ?? 'A', 0, 1)) ?></span>
                                <?php endif; ?>
                            </div>
                            <!-- Camera Icon Button -->
                            <button type="button" 
                                    onclick="event.stopPropagation(); document.getElementById('profile_image').click();" 
                                    class="absolute -bottom-1 -right-1 w-7 h-7 bg-brandOrange text-white rounded-full flex items-center justify-center shadow-md hover:bg-orange-600 transition border-2 border-white focus:outline-none">
                                <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 9a2 2 0 012-2h.93a2 2 0 001.664-.89l.812-1.22A2 2 0 0110.07 4h3.86a2 2 0 011.664.89l.812 1.22A2 2 0 0018.07 7H19a2 2 0 012 2v9a2 2 0 01-2 2H5a2 2 0 01-2-2V9z"/><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 13a3 3 0 11-6 0 3 3 0 016 0z"/></svg>
                            </button>
                        </div>
                        <div>
                            <h3 class="font-semibold text-gray-800 text-lg"><?= htmlspecialchars($admin['name'] ?? '') ?></h3>
                            <p class="text-sm text-gray-500 mb-1">Admin Profile</p>
                            <p class="text-xs text-brandOrange font-medium cursor-pointer hover:underline" onclick="document.getElementById('profile_image').click()">Change Photo</p>
                        </div>
                    </div>

                    <div class="border-t border-gray-100 pt-5 space-y-4">
                        <div>
                            <label class="block text-sm font-medium text-gray-700 mb-1">Name</label>
                            <input type="text" name="name" value="<?= htmlspecialchars($admin['name'] ?? '') ?>" required
                                   class="w-full px-3 py-2.5 text-sm border border-gray-200 rounded-lg focus:outline-none focus:ring-2 focus:ring-brandOrange focus:border-transparent">
                        </div>
                        <div>
                            <label class="block text-sm font-medium text-gray-700 mb-1">Email</label>
                            <input type="email" name="email" value="<?= htmlspecialchars($admin['email'] ?? '') ?>" required
                                   class="w-full px-3 py-2.5 text-sm border border-gray-200 rounded-lg focus:outline-none focus:ring-2 focus:ring-brandOrange focus:border-transparent">
                        </div>
                    </div>
                    
                    <button type="submit" name="update_profile" class="w-full px-4 py-2.5 bg-brandOrange text-white text-sm font-bold rounded-lg hover:bg-brandOrangeHover transition shadow-sm">
                        Save Changes
                    </button>
                </form>
            </div>

            <!-- ── Change Password Card ── -->
            <div class="bg-white rounded-xl border border-gray-200 p-6 shadow-sm">
                <div class="flex items-center gap-3 mb-6">
                    <div class="w-10 h-10 rounded-lg bg-blue-50 flex items-center justify-center">
                        <svg class="w-5 h-5 text-blue-600" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 15v2m-6 4h12a2 2 0 002-2v-6a2 2 0 00-2-2H6a2 2 0 00-2 2v6a2 2 0 002 2zm10-10V7a4 4 0 00-8 0v4h8z"/></svg>
                    </div>
                    <div>
                        <h3 class="font-semibold text-gray-800">Change Password</h3>
                        <p class="text-xs text-gray-500">Update your account password</p>
                    </div>
                </div>
                <form method="POST" class="space-y-4">
                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-1">Current Password</label>
                        <input type="password" name="current_password" required
                               class="w-full px-3 py-2.5 text-sm border border-gray-200 rounded-lg focus:outline-none focus:ring-2 focus:ring-brandOrange focus:border-transparent">
                    </div>
                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-1">New Password</label>
                        <input type="password" name="new_password" required minlength="6"
                               class="w-full px-3 py-2.5 text-sm border border-gray-200 rounded-lg focus:outline-none focus:ring-2 focus:ring-brandOrange focus:border-transparent">
                    </div>
                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-1">Confirm New Password</label>
                        <input type="password" name="confirm_password" required minlength="6"
                               class="w-full px-3 py-2.5 text-sm border border-gray-200 rounded-lg focus:outline-none focus:ring-2 focus:ring-brandOrange focus:border-transparent">
                    </div>
                    <button type="submit" name="change_password" class="w-full px-4 py-2.5 bg-blue-600 text-white text-sm font-bold rounded-lg hover:bg-blue-700 transition shadow-sm">
                        Update Password
                    </button>
                </form>
            </div>

        </div>
    </main>
</div>

<!-- Javascript to preview image before saving -->
<script>
function previewImage(input) {
    const container = document.getElementById('preview-container');
    if (input.files && input.files[0]) {
        const reader = new FileReader();
        reader.onload = function(e) {
            // Replace the inner content of the avatar with the new image
            container.innerHTML = '<img src="'+e.target.result+'" class="w-full h-full object-cover">';
        }
        reader.readAsDataURL(input.files[0]);
    }
}
</script>

<?php require_once 'includes/footer.php'; ?>