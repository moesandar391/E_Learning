<?php
session_set_cookie_params(['path' => '/']);
session_start();
require_once '../config/db.php';

$message = "";
$messageType = "";
$devResetLink = "";

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $email = trim($_POST['email'] ?? '');

    if (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
        $message = "Please enter a valid email address.";
        $messageType = "error";
    } else {
        $stmt = $conn->prepare("SELECT id FROM users WHERE email = ? LIMIT 1");
        $stmt->bind_param("s", $email);
        $stmt->execute();
        $result = $stmt->get_result();

        if ($result->num_rows == 1) {
            $user = $result->fetch_assoc();

            // Generate a cryptographically secure token; only its SHA-256 hash is stored.
            // Expiry is computed by MySQL (NOW()) so it matches the NOW()-based check
            // in reset_password.php even if the PHP and MySQL clocks differ.
            $token = bin2hex(random_bytes(32));
            $tokenHash = hash('sha256', $token);

            $update = $conn->prepare("UPDATE users SET reset_token = ?, reset_token_expires = DATE_ADD(NOW(), INTERVAL 1 HOUR) WHERE id = ?");
            $update->bind_param("si", $tokenHash, $user['id']);
            $update->execute();
            $update->close();

            // Build the absolute reset link
            $scheme = (!empty($_SERVER['HTTPS']) && $_SERVER['HTTPS'] !== 'off') ? 'https' : 'http';
            $resetPath = rtrim(str_replace('\\', '/', dirname($_SERVER['PHP_SELF'])), '/');
            $devResetLink = $scheme . '://' . $_SERVER['HTTP_HOST'] . $resetPath . '/reset_password.php?token=' . $token;

            /*
             | PRODUCTION: email the link instead of showing it on screen. Example:
             |
             | $to      = $email;
             | $subject = 'Password Reset - Access Edu';
             | $body    = "Hi,\n\nWe received a request to reset your password.\n"
             |          . "This link is valid for 1 hour:\n\n" . $devResetLink . "\n\n"
             |          . "If you didn't request this, you can safely ignore this email.";
             | $headers = 'From: no-reply@' . $_SERVER['HTTP_HOST'];
             | mail($to, $subject, $body, $headers);
             |
             | For reliable delivery on WAMP, configure SMTP (e.g. PHPMailer + Gmail).
            */
        }
        $stmt->close();

        // Same generic reply whether or not the account exists (prevents user enumeration).
        $message = "If an account exists for that email, a password reset link has been generated (valid for 1 hour).";
        $messageType = "success";
    }
}
include_once('../includes/header.php');
?>

<div class="min-h-[80vh] flex items-center justify-center py-12 px-4 sm:px-6 lg:px-8 bg-gradient-to-br from-orange-50 via-white to-amber-50">
    <div class="max-w-md w-full">

        <div class="bg-white rounded-3xl shadow-xl shadow-orange-100/50 border border-orange-100/60 px-8 py-8">

            <div class="text-center mb-8">
                <h2 class="text-2xl font-bold text-brandOrange">Forgot Password</h2>
                <p class="text-sm text-gray-500 mt-1">Enter your account email and we'll generate a reset link</p>
            </div>

            <?php if (!empty($message)): ?>
                <div class="<?php echo ($messageType === 'success')
                    ? 'bg-green-50 text-green-700 border border-green-200'
                    : 'bg-red-50 text-red-600 border border-red-200'; ?>
                    rounded-xl px-4 py-3 text-sm font-medium mb-6">
                    <?php echo htmlspecialchars($message); ?>
                </div>
            <?php endif; ?>

            <?php if (!empty($devResetLink)): ?>
                <!-- Local development only: in production this link is emailed instead -->
                <div class="bg-amber-50 border border-amber-200 rounded-xl px-4 py-4 text-sm mb-6">
                    <p class="font-semibold text-amber-800 mb-2">Local testing — your reset link:</p>
                    <a href="<?php echo htmlspecialchars($devResetLink); ?>"
                       class="text-brandOrange font-semibold break-all hover:underline">
                        <?php echo htmlspecialchars($devResetLink); ?>
                    </a>
                </div>
            <?php endif; ?>

            <form action="" method="POST" class="space-y-5">

                <div>
                    <label for="email" class="block text-sm font-semibold text-gray-700 mb-1.5">Email Address</label>
                    <div class="relative">
                        <div class="absolute inset-y-0 left-0 pl-3.5 flex items-center pointer-events-none">
                            <svg class="w-5 h-5 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 8l7.89 5.26a2 2 0 002.22 0L21 8M5 19h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v10a2 2 0 002 2z"></path>
                            </svg>
                        </div>
                        <input type="email"
                               name="email"
                               required
                               autocomplete="email"
                               placeholder="you@example.com"
                               class="w-full border border-gray-200 rounded-xl py-3 pl-11 pr-4 text-sm text-gray-700 bg-gray-50/50 focus:outline-none focus:border-brandOrange focus:ring-2 focus:ring-brandOrange/20 focus:bg-white transition-all">
                    </div>
                </div>

                <button type="submit"
                    class="w-full bg-gradient-to-r from-brandOrange to-orange-500 hover:from-orange-500 hover:to-brandOrange text-white font-bold py-3 rounded-xl text-sm shadow-lg shadow-orange-200/50 hover:shadow-xl hover:shadow-orange-200/60 transition-all duration-300 active:scale-[0.98] flex items-center justify-center gap-2">
                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 7a2 2 0 012 2m4 0a6 6 0 01-7.743 5.743L11 17H9v2H7v2H4a1 1 0 01-1-1v-2.586a1 1 0 01.293-.707l5.964-5.964A6 6 0 1121 9z"></path>
                    </svg>
                    Send Reset Link
                </button>
            </form>

            <div class="text-center mt-7 pt-5 border-t border-gray-100">
                <p class="text-sm text-gray-500">
                    Remembered it after all?
                    <a href="login.php" class="text-brandOrange font-semibold hover:text-orange-600 transition-colors ml-1">
                        Back to Login
                    </a>
                </p>
            </div>

        </div>
    </div>
</div>

<?php include_once('../includes/footer.php'); ?>
