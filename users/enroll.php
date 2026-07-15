<?php 
session_start();

// 1. Get module_id from URL
$module_id = isset($_GET['module_id']) ? intval($_GET['module_id']) : 0;

if (!isset($_SESSION['user_id'])) {
    header("Location: ../auth/login.php");
    exit();
}

require_once '../config/db.php';
include_once('../includes/header.php');

// 2. Fetch Module, Course, and Price details
$sql = "SELECT c.course_name, m.name AS module_name, m.price, m.image 
        FROM modules m 
        JOIN courses c ON m.course_id = c.id 
        WHERE m.id = ?";

$stmt = $conn->prepare($sql);
$stmt->bind_param("i", $module_id);
$stmt->execute();
$details = $stmt->get_result()->fetch_assoc();

// 3. Assign values safely
$courseName = $details['course_name'] ?? 'General English';
$moduleName = $details['module_name'] ?? 'Speaking';
$price = $details['price'] ?? 0;
$moduleImage = $details['image'] ?? null;

// Fetch User Data
$userId = $_SESSION['user_id'];
$stmtUser = $conn->prepare("SELECT email, name, phone FROM users WHERE id = ?");
$stmtUser->bind_param("i", $userId);
$stmtUser->execute();
$userData = $stmtUser->get_result()->fetch_assoc();

// Fetch payment methods from the database
$sql_methods = "SELECT id, name, image, qrcode FROM payment_method WHERE status = 'Active'";
$result_methods = $conn->query($sql_methods);
$payment_methods = $result_methods->fetch_all(MYSQLI_ASSOC);
?>

<script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
<main class="bg-gray-50 min-h-screen py-12">
    <div class="max-w-6xl mx-auto grid grid-cols-1 lg:grid-cols-2 gap-12 px-6">
        
        <div class="bg-white p-8 rounded-3xl border border-gray-200 shadow-sm h-fit">
            <div class="h-58 bg-gray-100 rounded-2xl mb-6 overflow-hidden flex items-center justify-center">
        <?php if (!empty($moduleImage) && file_exists('../uploads/modules/' . $moduleImage)): ?>
            <img src="../uploads/modules/<?php echo htmlspecialchars($moduleImage); ?>" 
                 alt="<?php echo htmlspecialchars($moduleName); ?>" 
                 class="w-full h-full object-cover">
        <?php else: ?>
            <span class="text-4xl">📚</span>
        <?php endif; ?>
    </div>
            
            <span class="text-orange-500 text-[10px] font-bold uppercase tracking-wider">
                <?php echo htmlspecialchars($courseName); ?>
            </span>
            <h2 class="text-2xl font-bold mb-4">
                <?php echo htmlspecialchars($moduleName); ?>
            </h2>
            
            <div class="space-y-3 border-t border-gray-100 pt-4">
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
            <h2 class="text-xl text-brandOchre italic font-bold">Complete your enrollment 🚀</h2>
            
            <form class="grid grid-cols-1 md:grid-cols-2 gap-4">
                <input type="email" readonly value="<?php echo htmlspecialchars($userData['email']); ?>" class="bg-white border p-3 rounded-lg w-full">
                <input type="text" readonly value="<?php echo htmlspecialchars($userData['name']); ?>" class="bg-white border p-3 rounded-lg w-full">
                <!-- <input type="password" placeholder="New Password" class="bg-white border p-3 rounded-lg w-full"> -->
                <input type="text" readonly value="<?php echo htmlspecialchars($userData['phone']); ?>" class="bg-white border p-3 rounded-lg w-full">
            </form>
            
            <div>
    <p class="mb-4 font-semibold text-gray-700">Pay with Myan Myan Pay</p>
    <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
        <?php if (!empty($payment_methods)): ?>
            <?php foreach ($payment_methods as $method): ?>
                <button type="button" 
                        onclick="showQR('<?php echo htmlspecialchars($method['name']); ?>', '<?php echo htmlspecialchars($method['qrcode'] ?? '../assets/default_qr.png'); ?>', <?php echo $method['id']; ?>)" 
                        class="payment-btn border p-4 rounded-xl flex items-center gap-3 hover:border-orange-500 hover:bg-orange-50 transition">
                    
                    <img src="../assets/<?php echo htmlspecialchars($method['image'] ?? 'default.png'); ?>" 
                         alt="<?php echo htmlspecialchars($method['name'] ?? 'Payment Method'); ?>" 
                         class="w-8 h-8 object-contain">
                    
                    <span><?php echo htmlspecialchars($method['name'] ?? 'N/A'); ?></span>
                </button>
            <?php endforeach; ?>
        <?php else: ?>
            <p class="text-sm text-gray-500">No payment methods available.</p>
        <?php endif; ?>
    </div>
</div>
            <div class="w-full">
                <label class="block text-[15px] font-bold text-gray-400 uppercase mb-1">Upload Receipt</label>
                <input type="file" name="receipt" id="receiptInput" class="w-full text-sm text-gray-500 file:mr-4 file:py-1 file:px-3 file:rounded-lg file:border-0 file:text-xs file:font-semibold file:bg-purple-50 file:text-purple-700 hover:file:bg-purple-100" required>
            </div>
            <button onclick="processPurchase(event)" class="w-full bg-orange-500 text-white font-bold py-4 rounded-xl shadow-lg">
                Purchase Now - <?php echo number_format($price); ?> MMK
            </button>
        </div>
    </div>
</main>

<!-- QR Code Modal -->
<div id="qrModal" class="fixed inset-0 z-50 hidden items-center justify-center bg-black/70 backdrop-blur-sm">
    <div class="bg-[#0a0f20] rounded-3xl p-8 max-w-sm w-full mx-4 border border-gray-800 shadow-2xl relative animate-fade-in">
        <button onclick="closeQR()" class="absolute top-4 right-4 text-gray-400 hover:text-white transition text-2xl leading-none">&times;</button>
        
        <div class="text-center">
            <h2 class="text-xl font-bold text-white mb-1">Scan This QR (Myan Myan Pay)</h2>
            <p class="text-sm text-gray-400 mb-1">We are using Myan Myan Pay.</p>
            <h3 id="qrTitle" class="text-xs text-gray-500 mb-6"></h3>
            
            <div class="bg-white rounded-2xl p-4 mb-6 inline-block">
                <img id="qrImage" src="" alt="QR Code" class="w-64 h-64 object-contain mx-auto">
            </div>

            <div class="space-y-4">
                <!-- <button class="w-full py-3 bg-transparent border border-gray-700 text-white font-bold rounded-2xl hover:bg-gray-800 transition">
                    Save QR code
                </button>
        
                <div class="text-yellow-500 font-mono font-bold text-sm">
                    Expire time : <span id="timer"></span>
                </div> -->

                <button onclick="closeQR()" class="w-full py-3 bg-transparent border border-red-900/50 text-red-500 font-bold rounded-2xl hover:bg-red-900/10 transition">
                    Cancel
                </button>
            </div>
            
            <p class="text-[10px] text-gray-500 mt-4 uppercase tracking-widest">Scan this QR code with your payment app</p>
        </div>
    </div>
</div>

<style>
    @keyframes fade-in {
        from { opacity: 0; transform: scale(0.95); }
        to { opacity: 1; transform: scale(1); }
    }
    .animate-fade-in {
        animation: fade-in 0.2s ease-out;
    }
</style>

<script>
let selectedMethod = null;
let selectedMethodId = null;

// Function to trigger the QR Modal
function showQR(name, qrSrc, methodId) {
    selectedMethod = name;
    selectedMethodId = methodId;
    document.getElementById('qrTitle').textContent = "Wallet you chose: " + name;
    document.getElementById('qrImage').src = qrSrc;
    
    const modal = document.getElementById('qrModal');
    modal.classList.remove('hidden');
    modal.classList.add('flex');
    
    // Start countdown when modal opens
    startTimer(180); // 02:53 in seconds
}

function closeQR() {
    const modal = document.getElementById('qrModal');
    modal.classList.add('hidden');
    modal.classList.remove('flex');
}

// Close modal on backdrop click
document.getElementById('qrModal').addEventListener('click', function(e) {
    if (e.target === this) closeQR();
});

// Close on Escape key
document.addEventListener('keydown', function(e) {
    if (e.key === 'Escape') closeQR();
});

function processPurchase(event) {
    if (!selectedMethod) {
        Swal.fire({ title: 'Select Payment Method', text: 'Please select a payment method first.', icon: 'warning', confirmButtonColor: '#ea580c' });
        return;
    }

    const receiptInput = document.querySelector('input[name="receipt"]');
    
    // Check if input exists and has a file
    if (!receiptInput || receiptInput.files.length === 0) {
        Swal.fire({ title: 'Receipt Required', text: 'Please upload your payment receipt.', icon: 'warning', confirmButtonColor: '#ea580c' });
        return;
    }

    const file = receiptInput.files[0];
    const button = event.target;
    button.innerText = "Processing...";
    button.disabled = true;

    const formData = new FormData();
    formData.append('module_id', '<?php echo $module_id; ?>');
    formData.append('payment_method_id', selectedMethodId);
    formData.append('receipt', file); // Sending the file object

    // Debugging: Log what is being sent to the console
    console.log("Sending file:", file.name, "Size:", file.size);

    fetch('process_enrollment.php', {
        method: 'POST',
        body: formData 
    })
    .then(function(r) { return r.json(); })
    .then(function(data) {
        if (data.success) {
            Swal.fire({ title: 'Payment Successful!', text: data.message, icon: 'success', showConfirmButton: false, timer: 2000, timerProgressBar: true })
                .then(function() { window.location.href = data.redirect; });
        } else {
            console.error("Server Error:", data.message); // Check console if purchase fails
            Swal.fire({ title: 'Error', text: data.message, icon: 'error', confirmButtonColor: '#ea580c' });
            button.innerText = "Purchase Now - <?php echo number_format($price); ?> MMK";
            button.disabled = false;
        }
    })
    .catch(function(err) {
        console.error("Fetch Error:", err);
        Swal.fire({ title: 'Error', text: 'Something went wrong. Please check console.', icon: 'error', confirmButtonColor: '#ea580c' });
        button.innerText = "Purchase Now - <?php echo number_format($price); ?> MMK";
        button.disabled = false;
    });
}
</script>

<?php include_once('../includes/footer.php'); ?>