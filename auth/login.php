<?php
// 1. ALL PHP LOGIC GOES HERE AT THE VERY TOP
// Force session to be accessible across the entire domain path
session_set_cookie_params(['path' => '/']);
session_start();
require_once '../config/db.php';

$error_message = "";

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $email = trim($_POST['email']);
    $password = trim($_POST['password']);
    $role = $_POST['role']; // Ensure your select name="role" is correct

    $table = ($role === 'admin') ? 'admin' : 'users';

    $sql = "SELECT * FROM $table WHERE email = ?";
    $stmt = $conn->prepare($sql);
    $stmt->bind_param("s", $email);
    $stmt->execute();
    $result = $stmt->get_result();

    if ($result->num_rows == 1) {
        $user = $result->fetch_assoc();

        if (password_verify($password, $user['password'])) {
            // Success logic
            session_regenerate_id(true); 
            
            $_SESSION['user_id'] = $user['id'];
            $_SESSION['username'] = $user['name'];
            $_SESSION['role'] = $role; // This is the key for your dashboard check

            // CRITICAL: Force the session data to save to the server before redirecting
            session_write_close(); 

            if ($role === 'admin') {
                header("Location: ../admin/dashboard.php");
            } else {
                $_SESSION['profile_image'] = $user['profile_image'] ?? null;
                header("Location: ../users/courses.php");
            }
            exit(); 
        } else {
            $error_message = "Invalid email or password.";
        }
    } else {
        $error_message = "Invalid email or password.";
    }
    $stmt->close();
}
?>

<?php include_once('../includes/header.php'); ?>

    <div class="w-full bg-gray-50 flex items-center justify-center py-16 px-6 min-h-[70vh]">
        
        <div class="max-w-md w-full bg-white p-8 rounded-2xl shadow-md border border-gray-100">
            
            <div class="text-center mb-6">
                <h2 class="text-2xl font-bold text-slate-800">Welcome Back</h2>
                <p class="text-xs text-slate-500 mt-1">Sign in to resume mastering your language journey.</p>
            </div>

            <?php if (!empty($error_message)): ?>
                <div class="bg-red-50 text-red-600 border border-red-200 rounded-lg p-3 text-xs font-semibold mb-4 text-center">
                    <?php echo $error_message; ?>
                </div>
            <?php endif; ?>

            <form action="" method="POST" class="space-y-4">

                <div>
                    <label class="block text-xs font-bold text-slate-700 mb-1">Email Address</label>
                    <input type="email"
                           name="email"
                           required
                           autocomplete="email"
                           class="w-full border border-gray-200 rounded-lg p-2.5 text-sm text-slate-700 focus:outline-none focus:border-orange-400 focus:ring-1 focus:ring-orange-400 transition-all">
                </div>
                
                <div>
                    <div class="flex items-center justify-between mb-1">
                        <label class="block text-xs font-bold text-slate-700">Password</label>
                        <a href="#" class="text-xs text-orange-500 hover:underline">Forgot password?</a>
                    </div>

                    <input type="password"
                           name="password"
                           required
                           autocomplete="current-password"
                           class="w-full border border-gray-200 rounded-lg p-2.5 text-sm text-slate-700 focus:outline-none focus:border-orange-400 focus:ring-1 focus:ring-orange-400 transition-all">
                </div>

                <div>
                <label class="block text-sm font-medium text-gray-700 mb-1">Login As</label>
                <select name="role" class="w-full border border-gray-300 rounded-lg p-3 outline-none focus:ring-2 focus:ring-brandOrange">
                    <option value="user">Student</option>
                    <option value="admin">Administrator</option>
                </select>
                </div>

                <button type="submit"
                    class="w-full bg-[#FF8A00] hover:bg-[#E07A00] text-white font-bold py-2.5 rounded-xl text-sm shadow-sm transition-all mt-2">
                    Sign In
                </button>

            </form>

            <div class="text-center mt-6 pt-4 border-t border-gray-100 text-xs font-medium text-slate-500">
                Don't have an account yet?
                <a href="register.php" class="text-orange-500 font-bold hover:underline ml-0.5">
                    Create Account
                </a>
            </div>

        </div>
    </div>

</body>
</html>
<?php 
include_once('../includes/footer.php');
?>
