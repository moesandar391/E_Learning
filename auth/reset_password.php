<?php
session_set_cookie_params(['path' => '/']);
session_start();
require_once '../config/db.php';

$error_message = "";
$success_message = "";

// Token arrives via GET on first visit, and via POST (hidden field) when submitting the new password.
$token = $_POST['token'] ?? $_GET['token'] ?? '';
$tokenHash = hash('sha256', $token);

// Look up a valid (existing + unexpired) token
$stmt = $conn->prepare("SELECT id FROM users WHERE reset_token = ? AND reset_token_expires > NOW() LIMIT 1");
$stmt->bind_param("s", $tokenHash);
$stmt->execute();
$result = $stmt->get_result();
$user = $result->num_rows == 1 ? $result->fetch_assoc() : null;
$stmt->close();

if ($_SERVER["REQUEST_METHOD"] == "POST" && $user) {
    $password = $_POST['password'] ?? '';
    $confirm_password = $_POST['confirm_password'] ?? '';

    if (strlen($password) < 8) {
        $error_message = "Password must be at least 8 characters long.";
    } elseif (!preg_match('/[^a-zA-Z0-9]/', $password)) {
        $error_message = "Password must include at least one special character.";
    } elseif ($password !== $confirm_password) {
        $error_message = "Passwords do not match!";
    } else {
        $hashedPassword = password_hash($password, PASSWORD_DEFAULT);

        // Update the password and invalidate the token so it can never be reused
        $update = $conn->prepare("UPDATE users SET password = ?, reset_token = NULL, reset_token_expires = NULL WHERE id = ?");
        $update->bind_param("si", $hashedPassword, $user['id']);

        if ($update->execute()) {
            $success_message = "Your password has been reset successfully. You can now log in with your new password.";
        } else {
            $error_message = "Something went wrong. Please try again.";
        }
        $update->close();
    }
}
include_once('../includes/header.php');
?>

<div class="min-h-[80vh] flex items-center justify-center py-12 px-4 sm:px-6 lg:px-8 bg-gradient-to-br from-orange-50 via-white to-amber-50">
    <div class="max-w-md w-full">

        <div class="bg-white rounded-3xl shadow-xl shadow-orange-100/50 border border-orange-100/60 px-8 py-8">

            <?php if ($success_message): ?>

                <div class="text-center mb-6">
                    <div class="mx-auto flex items-center justify-center h-14 w-14 rounded-full bg-green-100 mb-4">
                        <svg class="w-7 h-7 text-green-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"></path>
                        </svg>
                    </div>
                    <h2 class="text-2xl font-bold text-brandOrange">Password Reset</h2>
                </div>

                <div class="bg-green-50 text-green-700 border border-green-200 rounded-xl px-4 py-3 text-sm font-medium mb-6">
                    <?php echo htmlspecialchars($success_message); ?>
                </div>

                <a href="login.php"
                   class="w-full block text-center bg-gradient-to-r from-brandOrange to-orange-500 hover:from-orange-500 hover:to-brandOrange text-white font-bold py-3 rounded-xl text-sm shadow-lg shadow-orange-200/50 hover:shadow-xl hover:shadow-orange-200/60 transition-all duration-300 active:scale-[0.98]">
                    Go to Login
                </a>

            <?php elseif (!$user): ?>

                <div class="text-center mb-6">
                    <div class="mx-auto flex items-center justify-center h-14 w-14 rounded-full bg-red-100 mb-4">
                        <svg class="w-7 h-7 text-red-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4m0 4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                        </svg>
                    </div>
                    <h2 class="text-2xl font-bold text-brandOrange">Invalid or Expired Link</h2>
                </div>

                <p class="text-sm text-gray-500 text-center mb-6">
                    This password reset link is invalid or has expired (links are valid for 1 hour).
                    Please request a new one.
                </p>

                <a href="forgot_password.php"
                   class="w-full block text-center bg-gradient-to-r from-brandOrange to-orange-500 hover:from-orange-500 hover:to-brandOrange text-white font-bold py-3 rounded-xl text-sm shadow-lg shadow-orange-200/50 hover:shadow-xl hover:shadow-orange-200/60 transition-all duration-300 active:scale-[0.98]">
                    Request New Link
                </a>

            <?php else: ?>

                <div class="text-center mb-8">
                    <h2 class="text-2xl font-bold text-brandOrange">Set New Password</h2>
                    <p class="text-sm text-gray-500 mt-1">Choose a strong new password for your account</p>
                </div>

                <?php if (!empty($error_message)): ?>
                    <div class="bg-red-50 text-red-600 border border-red-200 rounded-xl px-4 py-3 text-sm font-medium mb-6">
                        <?php echo htmlspecialchars($error_message); ?>
                    </div>
                <?php endif; ?>

                <form action="" method="POST" class="space-y-5">
                    <input type="hidden" name="token" value="<?php echo htmlspecialchars($token); ?>">

                    <div>
                        <label for="password" class="block text-sm font-semibold text-gray-700 mb-1.5">New Password</label>
                        <div class="relative">
                            <div class="absolute inset-y-0 left-0 pl-3.5 flex items-center pointer-events-none">
                                <svg class="w-5 h-5 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 15v2m-6 4h12a2 2 0 002-2v-6a2 2 0 00-2-2H6a2 2 0 00-2 2v6a2 2 0 002 2zm10-10V7a4 4 0 00-8 0v4h8z"></path>
                                </svg>
                            </div>
                            <input type="password"
                                   id="password"
                                   name="password"
                                   required
                                   minlength="8"
                                   autocomplete="new-password"
                                   placeholder="At least 8 characters"
                                   pattern=".*[^a-zA-Z0-9].*"
                                   title="Password must include at least one special character (e.g., @, #, $, !)."
                                   class="w-full border border-gray-200 rounded-xl py-3 pl-11 pr-4 text-sm text-gray-700 bg-gray-50/50 focus:outline-none focus:border-brandOrange focus:ring-2 focus:ring-brandOrange/20 focus:bg-white transition-all">
                        </div>
                        <p class="mt-1 text-xs text-gray-400">Minimum 8 characters, including at least one special character.</p>
                    </div>

                    <div>
                        <label for="confirm_password" class="block text-sm font-semibold text-gray-700 mb-1.5">Confirm New Password</label>
                        <input type="password"
                               id="confirm_password"
                               name="confirm_password"
                               required
                               minlength="8"
                               autocomplete="new-password"
                               placeholder="Re-enter your new password"
                               class="w-full border border-gray-200 rounded-xl py-3 pl-11 pr-4 text-sm text-gray-700 bg-gray-50/50 focus:outline-none focus:border-brandOrange focus:ring-2 focus:ring-brandOrange/20 focus:bg-white transition-all">
                    </div>

                    <button type="submit"
                        class="w-full bg-gradient-to-r from-brandOrange to-orange-500 hover:from-orange-500 hover:to-brandOrange text-white font-bold py-3 rounded-xl text-sm shadow-lg shadow-orange-200/50 hover:shadow-xl hover:shadow-orange-200/60 transition-all duration-300 active:scale-[0.98] flex items-center justify-center gap-2">
                        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 15v2m-6 4h12a2 2 0 002-2v-6a2 2 0 00-2-2H6a2 2 0 00-2 2v6a2 2 0 002 2zm10-10V7a4 4 0 00-8 0v4h8z"></path>
                        </svg>
                        Reset Password
                    </button>
                </form>

            <?php endif; ?>

        </div>
    </div>
</div>

<?php include_once('../includes/footer.php'); ?>
