<?php
session_set_cookie_params(['path' => '/']);
session_start();
require_once '../config/db.php';

$error_message = "";

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $email = trim($_POST['email']);
    $password = trim($_POST['password']);

    $tables = ['users', 'admin'];
    $found = false;

    foreach ($tables as $table) {
        $sql = "SELECT * FROM $table WHERE email = ?";
        $stmt = $conn->prepare($sql);
        $stmt->bind_param("s", $email);
        $stmt->execute();
        $result = $stmt->get_result();

        if ($result->num_rows == 1) {
            $user = $result->fetch_assoc();
            if (password_verify($password, $user['password'])) {
                $found = true;
                session_regenerate_id(true);
                $_SESSION['user_id'] = $user['id'];
                $_SESSION['username'] = $user['name'];
                $_SESSION['role'] = ($table === 'admin') ? 'admin' : 'user';
                session_write_close();

                if ($table === 'admin') {
                    header("Location: ../admin/dashboard.php");
                } else {
                    $_SESSION['profile_image'] = $user['profile_image'] ?? null;
                    header("Location: ../users/index.php");
                }
                exit();
            }
        }
        $stmt->close();
    }
    if (!$found) {
        $error_message = "Invalid email or password.";
    }
}
?>

<?php include_once('../includes/header.php'); ?>

<div class="min-h-[80vh] flex items-center justify-center py-12 px-4 sm:px-6 lg:px-8 bg-gradient-to-br from-orange-50 via-white to-amber-50">
    <div class="max-w-md w-full">

        <div class="bg-white rounded-3xl shadow-xl shadow-orange-100/50 border border-orange-100/60 px-8 py-8">

            <div class="text-center mb-8">
                <h2 class="text-2xl font-bold text-brandOrange">Welcome Back</h2>
                <p class="text-sm text-gray-500 mt-1">Sign in to continue your learning journey</p>
            </div>

                <?php if (!empty($error_message)): ?>
                    <div class="flex items-center gap-2 bg-red-50 text-red-600 border border-red-200 rounded-xl px-4 py-3 text-sm font-medium mb-6">
                        <svg class="w-5 h-5 flex-shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4m0 4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                        </svg>
                        <span><?php echo $error_message; ?></span>
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

                    <div>
                        <div class="flex items-center justify-between mb-1.5">
                            <label for="password" class="block text-sm font-semibold text-gray-700">Password</label>
                            <a href="#" class="text-xs text-brandOrange hover:text-orange-600 font-medium transition-colors">Forgot password?</a>
                        </div>
                        <div class="relative">
                            <div class="absolute inset-y-0 left-0 pl-3.5 flex items-center pointer-events-none">
                                <svg class="w-5 h-5 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 15v2m-6 4h12a2 2 0 002-2v-6a2 2 0 00-2-2H6a2 2 0 00-2 2v6a2 2 0 002 2zm10-10V7a4 4 0 00-8 0v4h8z"></path>
                                </svg>
                            </div>
                            <input type="password"
                                   name="password"
                                   required
                                   autocomplete="current-password"
                                   placeholder="Enter your password"
                                   class="w-full border border-gray-200 rounded-xl py-3 pl-11 pr-4 text-sm text-gray-700 bg-gray-50/50 focus:outline-none focus:border-brandOrange focus:ring-2 focus:ring-brandOrange/20 focus:bg-white transition-all">
                        </div>
                    </div>

                    <div class="flex items-center">
                        <input id="remember" type="checkbox" class="w-4 h-4 text-brandOrange border-gray-300 rounded focus:ring-brandOrange">
                        <label for="remember" class="ml-2 text-sm text-gray-600">Remember me</label>
                    </div>

                    <button type="submit"
                        class="w-full bg-gradient-to-r from-brandOrange to-orange-500 hover:from-orange-500 hover:to-brandOrange text-white font-bold py-3 rounded-xl text-sm shadow-lg shadow-orange-200/50 hover:shadow-xl hover:shadow-orange-200/60 transition-all duration-300 active:scale-[0.98] flex items-center justify-center gap-2">
                        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 16l-4-4m0 0l4-4m-4 4h14m-5 4v1a3 3 0 01-3 3H6a3 3 0 01-3-3V7a3 3 0 013-3h7a3 3 0 013 3v1"></path>
                        </svg>
                        Sign In
                    </button>
                </form>

                <div class="text-center mt-7 pt-5 border-t border-gray-100">
                    <p class="text-sm text-gray-500">
                        Don't have an account yet?
                        <a href="register.php" class="text-brandOrange font-semibold hover:text-orange-600 transition-colors ml-1">
                            Create Account
                        </a>
                    </p>
                </div>

            </div>
        </div>
    </div>
</div>

</body>
</html>
<?php 
include_once('../includes/footer.php');
?>
