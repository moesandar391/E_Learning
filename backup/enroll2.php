<?php 
session_start();
require_once '../config/db.php';
include_once('../includes/header.php');

// 1. Get module_id from URL
$module_id = isset($_GET['module_id']) ? intval($_GET['module_id']) : 0;

if (!isset($_SESSION['user_id'])) {
    header("Location: ../auth/login.php");
    exit();
}

// 2. Fetch Module, Course, and Price details
// We join modules and courses to get the names associated with the selected module
// Ensure $module_id is captured from the URL
// $module_id = isset($_GET['module_id']) ? intval($_GET['module_id']) : 0;

// Join modules and courses to get both names in one result
$sql = "SELECT c.course_name, m.name AS module_name, m.price 
        FROM modules m 
        JOIN courses c ON m.course_id = c.id 
        WHERE m.id = ?";

$stmt = $conn->prepare($sql);
$stmt->bind_param("i", $module_id);
$stmt->execute();
$result = $stmt->get_result();
$data = $result->fetch_assoc();

// Assign to variables for easy display
$courseTitle = $data['course_name'] ?? 'General English'; // Default if not found
$moduleTitle = $data['module_name'] ?? 'Speaking';        // Default if not found
$price = $data['price'] ?? 30000;

// 3. Fetch Logged-in User Data
$userId = $_SESSION['user_id'];
$stmtUser = $conn->prepare("SELECT email, name, phone FROM users WHERE id = ?");
$stmtUser->bind_param("i", $userId);
$stmtUser->execute();
$userData = $stmtUser->get_result()->fetch_assoc();
?>

<script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>

<main class="bg-gray-50 min-h-screen py-12">
    <div class="max-w-6xl mx-auto grid grid-cols-1 lg:grid-cols-2 gap-12 px-6">
        
        <div class="bg-white p-8 rounded-3xl border border-gray-200 shadow-sm">
            <div class="h-48 bg-orange-50 rounded-2xl mb-6 flex items-center justify-center text-4xl">📚</div>
            
            <span class="text-orange-500 text-[10px] font-bold uppercase tracking-wider">
                <?php echo htmlspecialchars($courseTitle); ?>
            </span>

            <h2 class="text-2xl font-bold mb-6">
                <?php echo htmlspecialchars($moduleTitle); ?>
            </h2>
            
            <div class="space-y-4 border-t border-gray-100 pt-6">
                <h3 class="font-bold text-lg text-orange-600">
                    Total Amount : <?php echo number_format($price); ?> MMK
                </h3>
                <p class="text-gray-500 font-semibold">LifeTime Provided Services</p>
                <ul class="text-gray-600 space-y-2 text-sm">
                    <li><span class="text-orange-500">★</span> LifeTime Update Access</li>
                    <li><span class="text-orange-500">★</span> One By One Meeting</li>
                    <li><span class="text-orange-500">★</span> Unlimited Download</li>
                </ul>
            </div>
        </div>

        <div class="space-y-8">
            <h2 class="text-xl text-brandOchre italic font-bold">Complete your enrollment with Access Edu 🚀</h2>
            
            <form class="grid grid-cols-1 md:grid-cols-2 gap-4">
                <input type="email" readonly value="<?php echo htmlspecialchars($userData['email']); ?>" class="bg-white border p-3 rounded-lg w-full">
                <input type="text" readonly value="<?php echo htmlspecialchars($userData['name']); ?>" class="bg-white border p-3 rounded-lg w-full">
                <input type="password" placeholder="New Password" class="bg-white border p-3 rounded-lg w-full">
                <input type="text" readonly value="<?php echo htmlspecialchars($userData['phone']); ?>" class="bg-white border p-3 rounded-lg w-full">
            </form>

            <button onclick="processPurchase(event)" class="w-full bg-orange-500 text-white font-bold py-4 rounded-xl shadow-lg hover:bg-orange-600 transition">
                Purchase Now - <?php echo number_format($price); ?> MMK
            </button>
        </div>
    </div>
</main>

<script>
function processPurchase(event) {
    const button = event.target;
    button.innerText = "Processing...";
    button.disabled = true;

    setTimeout(() => {
        Swal.fire({
            title: 'Payment Successful!',
            text: 'You have been enrolled.',
            icon: 'success',
            confirmButtonColor: '#ea580c'
        }).then(() => {
            // Redirect using the module_id passed to this page
            window.location.href = 'lesson.php?module_id=<?php echo $module_id; ?>';
        });
    }, 2000);
}
</script>

<?php include_once('../includes/footer.php'); ?>