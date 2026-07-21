<?php
session_start();
require_once '../config/db.php';

 $message = "";
 $messageType = "";

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $username = trim($_POST['username'] ?? '');
    $email = trim($_POST['email'] ?? '');
    $password = $_POST['password'] ?? '';
    $confirm_password = $_POST['confirm_password'] ?? '';
    $phone = trim($_POST['phone'] ?? '');
    $dob = $_POST['dob'] ?? '';
    $gender = $_POST['gender'] ?? 'Male';
    $address = trim($_POST['address'] ?? '');

    // --- PHP BACKUP VALIDATION ---
    if (strlen($username) <= 3) {
        $message = "Name must be more than 3 characters long.";
        $messageType = "error";
    } elseif (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
        $message = "Invalid email format. Please include an '@' and a valid domain.";
        $messageType = "error";
    } elseif (!preg_match('/^09\d{9}$/', $phone)) {
        $message = "Invalid phone number. Must be 11 digits starting with 09.";
        $messageType = "error";
    } elseif (!preg_match('/[^a-zA-Z0-9]/', $password)) {
        $message = "Password must include at least one special character.";
        $messageType = "error";
    } elseif ($password != $confirm_password) {
        $message = "Passwords do not match!";
        $messageType = "error";
    } elseif (!empty($dob) && $dob > date('Y-m-d')) {
        $message = "Date of birth cannot be in the future.";
        $messageType = "error";
    } elseif (strlen($address) < 5) {
        $message = "Address must be at least 5 characters long.";
        $messageType = "error";
    } else {
        $check = $conn->prepare("SELECT id FROM users WHERE email=?");
        $check->bind_param("s", $email);
        $check->execute();
        $check->store_result();

        if ($check->num_rows > 0) {
            $message = "Email already exists!";
            $messageType = "error";
        } else {
            $hashedPassword = password_hash($password, PASSWORD_DEFAULT);
            $stmt = $conn->prepare("INSERT INTO users(name,email,password,phone,address,gender, date_of_birth) VALUES(?,?,?,?,?,?,?)");
            $stmt->bind_param("sssssss", $username, $email, $hashedPassword, $phone, $address, $gender, $dob);
            
            if ($stmt->execute()) {
                require_once __DIR__ . '/../includes/admin_notification_helper.php';
                create_admin_notification(
                    'New student registered: ' . $username . ' (' . $email . ')',
                    'students.php',
                    'student'
                );
                $message = "Registration Successful!";
                $messageType = "success";
            } else {
                $message = "Registration Failed!";
                $messageType = "error";
            }
            $stmt->close();
        }
        $check->close();
    }
}
include_once('../includes/header.php');

 $todayDate = date('Y-m-d');
?>

<div class="flex items-center justify-center min-h-screen bg-gray-50 p-6">
    <div class="bg-white p-8 rounded-3xl border border-gray-200 w-full max-w-lg shadow-sm">
        <h2 class="text-brandOrange text-2xl font-bold mb-6 text-center">Create Free Account</h2>
        
        <?php if(!empty($message)): ?>
        <div class="<?php echo ($messageType=='success')
            ? 'bg-green-50 border border-green-200 text-green-700'
            : 'bg-red-50 border border-red-200 text-red-700'; ?>
            p-3 rounded-lg mb-4 text-center font-medium">
            <?php echo $message; ?>
        </div>
        <?php endif; ?>

        <!-- Added onsubmit back to trigger the JS alert for the Name field -->
        <form action="register.php" method="POST" onsubmit="return validateForm()">
            <div class="mb-4">
                <!-- Removed minlength HTML attribute so it doesn't trigger the browser tooltip before our JS alert -->
                <input type="text" name="username" placeholder="Username" required 
                       class="w-full bg-gray-50 border border-gray-200 text-gray-900 rounded-lg p-3 outline-none focus:border-brandOrange">
            </div>

            <div class="mb-4">
                <!-- Native HTML5 email tooltip will trigger here -->
                <input type="email" name="email" placeholder="Email Address" required 
                       class="w-full bg-gray-50 border border-gray-200 text-gray-900 rounded-lg p-3 outline-none focus:border-brandOrange">
            </div>

            <div class="mb-4">
                <div class="relative">
                    <input type="password" id="password" name="password" placeholder="Password" onkeyup="checkPasswordStrength(this.value)" required 
                           pattern=".*[^a-zA-Z0-9].*" title="Password must include at least one special character (e.g., @, #, $, !)." 
                           class="w-full bg-gray-50 border border-gray-200 text-gray-900 rounded-lg p-3 outline-none focus:border-brandOrange pr-10"> 
                    <button type="button" onclick="toggleVisibility()" class="absolute right-3 top-3.5 text-gray-400 hover:text-brandOrange">
                        <svg id="eye-icon" xmlns="http://www.w3.org/2000/svg" class="h-5 w-5" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z" />
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z" />
                        </svg>
                    </button>
                </div>
                <div class="mt-2">
                    <div id="strength-bar" class="h-1 bg-gray-100 rounded-full overflow-hidden"><div id="strength-fill" class="h-full w-0 transition-all duration-300"></div></div>
                    <p id="strength-text" class="text-xs mt-1 font-bold"></p>
                </div>
            </div>

            <div class="mb-4">
                <input type="password" id="confirm_password" name="confirm_password" placeholder="Confirm Password" onkeyup="checkPasswordMatch()" required 
                       class="w-full bg-gray-50 border border-gray-200 text-gray-900 rounded-lg p-3 outline-none focus:border-brandOrange">
                <p id="match-text" class="text-xs mt-1"></p>
            </div>

            <div class="grid grid-cols-2 gap-4 mb-4">
                <!-- Native HTML5 phone tooltip will trigger here -->
                <input type="text" name="phone" placeholder="Phone (09xxxxxxxxx)" required pattern="09\d{9}" title="Must be 11 digits starting with 09 (e.g., 09123456789)." 
                       class="bg-gray-50 border border-gray-200 text-gray-900 rounded-lg p-3 outline-none focus:border-brandOrange">
                
                <input type="date" name="dob" required max="<?php echo $todayDate; ?>" 
                       class="bg-gray-50 border border-gray-200 text-gray-900 rounded-lg p-3 outline-none focus:border-brandOrange">
            </div>

            <div class="mb-4">
                <select name="gender" required class="w-full bg-gray-50 border border-gray-200 text-gray-900 rounded-lg p-3 outline-none focus:border-brandOrange">
                    <option value="Male">Male</option>
                    <option value="Female">Female</option>
                </select>
            </div>

            <div class="mb-4">
                <!-- Native HTML5 address tooltip will trigger here -->
                <input type="text" name="address" placeholder="Address" required minlength="5" title="Address must be at least 5 characters long." 
                       class="w-full bg-gray-50 border border-gray-200 text-gray-900 rounded-lg p-3 outline-none focus:border-brandOrange">
            </div>

            <button type="submit" class="w-full bg-brandOrange hover:bg-orange-600 text-white font-bold py-3 rounded-lg transition">Create Free Account</button>

            <div class="text-center mt-5 pt-5 border-t border-gray-100">
                <p class="text-sm text-gray-500">
                    Already have an account?
                    <a href="login.php" class="text-brandOrange font-semibold hover:text-orange-600 hover:underline transition-colors ml-1">
                        Login
                    </a>
                </p>
            </div>
        </form>
    </div>
</div>

<script>
function checkPasswordStrength(password){
    const fill = document.getElementById("strength-fill");
    const text = document.getElementById("strength-text");

    if (password.length === 0) {
        fill.style.width = "0%";
        text.innerText = "";
        return;
    }

    if (password.length <= 3) {
        fill.style.width = "25%";
        fill.className = "h-full bg-red-500 transition-all duration-300";
        text.innerText = "Weak";
        text.className = "text-xs mt-1 font-bold text-red-500";
    } else if (password.length <= 5) {
        fill.style.width = "50%";
        fill.className = "h-full bg-yellow-500 transition-all duration-300";
        text.innerText = "Fair";
        text.className = "text-xs mt-1 font-bold text-yellow-500";
    } else if (password.length <= 7) {
        fill.style.width = "75%";
        fill.className = "h-full bg-blue-500 transition-all duration-300";
        text.innerText = "Good";
        text.className = "text-xs mt-1 font-bold text-blue-500";
    } else {
        fill.style.width = "100%";
        fill.className = "h-full bg-green-500 transition-all duration-300";
        text.innerText = "Excellent";
        text.className = "text-xs mt-1 font-bold text-green-500";
    }
}

function checkPasswordMatch(){
    let password = document.getElementById("password").value;
    let confirm = document.getElementById("confirm_password").value;
    let msg = document.getElementById("match-text");

    if(confirm == ""){
        msg.innerHTML = "";
        return;
    }

    if(password === confirm){
        msg.innerHTML = "✓ Password Match";
        msg.className = "text-green-500 text-xs mt-1";
    } else {
        msg.innerHTML = "✗ Password Not Match";
        msg.className = "text-red-500 text-xs mt-1";
    }
}

function validateForm(){
    let name = document.querySelector('input[name="username"]').value.trim();

    // 1. Name validation (Shows JavaScript Alert)
    if (name.length <= 3) {
        alert("Name must be more than 3 characters long.");
        return false; // Stops the form from submitting
    }

    // If the name is valid, return true. 
    // The browser will then automatically check the HTML5 native validation for the other fields.
    return true;
}

function toggleVisibility() {
    const passwordInput = document.getElementById("password");
    if (passwordInput.type === "password") {
        passwordInput.type = "text";
    } else {
        passwordInput.type = "password";
    }
}
</script>

<?php include_once('../includes/footer.php'); ?>