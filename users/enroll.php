<?php 
session_start();

 $module_id = isset($_GET['module_id']) ? intval($_GET['module_id']) : 0;

if (!isset($_SESSION['user_id'])) {
    header("Location: ../auth/login.php");
    exit();
}

require_once '../config/db.php';

 $sql = "SELECT c.course_name, m.name AS module_name, m.price, m.image 
       FROM modules m 
       JOIN courses c ON m.course_id = c.id 
       WHERE m.id = ?";
 $stmt = $conn->prepare($sql);
 $stmt->bind_param("i", $module_id);
 $stmt->execute();
 $details = $stmt->get_result()->fetch_assoc();

// ✅ FIXED: Redirect to home/learning page, NOT back to enroll.php
if (!$details) {
    header("Location: my_learning.php");
    exit();
}

include_once('../includes/header.php');

 $courseName = $details['course_name'];
 $moduleName = $details['module_name'];
 $price = $details['price'] ?? 0;
 $moduleImage = $details['image'] ?? null;

 $userId = $_SESSION['user_id'];
 $stmtUser = $conn->prepare("SELECT email, name, phone FROM users WHERE id = ?");
 $stmtUser->bind_param("i", $userId);
 $stmtUser->execute();
 $userData = $stmtUser->get_result()->fetch_assoc();

 $sql_methods = "SELECT id, name, image, qrcode FROM payment_method WHERE status = 'Active' ORDER BY id ASC";
 $result_methods = $conn->query($sql_methods);
 $payment_methods = $result_methods->fetch_all(MYSQLI_ASSOC);

 $sql_admin = "SELECT name, phone FROM admin LIMIT 1";
 $result_admin = $conn->query($sql_admin);
 $adminData = $result_admin->fetch_assoc();

 $adminName = $adminData['name'] ?? 'Myan Myan Pay Admin';
 $adminPhone = $adminData['phone'] ?? '09-123-456-789';

 $stmtEnrolled = $conn->prepare("
    SELECT e.id, e.status, e.enroll_date, m.name AS module_name, c.course_name
    FROM enrollments e
    JOIN modules m ON e.module_id = m.id
    JOIN courses c ON m.course_id = c.id
    WHERE e.user_id = ? AND e.status = 'confirmed'
    ORDER BY e.enroll_date DESC
");
 $stmtEnrolled->bind_param("i", $userId);
 $stmtEnrolled->execute();
 $enrolledModules = $stmtEnrolled->get_result()->fetch_all(MYSQLI_ASSOC);

function getMethodColor($name) {
    $key = strtolower(preg_replace('/[\s\-]+/', '', trim($name)));
    $colors = [
        'kpay'    => '#003d7a',
        'kbzpay'  => '#003d7a',
        'wavepay' => '#e4f03bff',
        'wave'    => '#e4f03bff',
        'ayapay'  => '#c62828',
        'aya'     => '#c62828',
        'cbpay'   => '#4772e7ff',
        'cb'      => '#4772e7ff',
    ];
    return isset($colors[$key]) ? $colors[$key] : '#ea580c';
}

function isLightColor($hex) {
    $r = hexdec(substr($hex, 1, 2));
    $g = hexdec(substr($hex, 3, 2));
    $b = hexdec(substr($hex, 5, 2));
    return ($r * 299 + $g * 587 + $b * 114) / 1000 > 160;
}
?>

<script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.0/css/all.min.css">

<main class="bg-gray-50 min-h-screen py-12">
    <div class="max-w-7xl mx-auto grid grid-cols-1 lg:grid-cols-2 gap-12 px-6">
        
        <!-- LEFT: Module Info -->
        <div class="bg-white p-8 rounded-3xl border border-gray-200 shadow-sm h-fit">
            <div class="h-50 bg-gray-100 rounded-2xl mb-6 overflow-hidden flex items-center justify-center">
                <?php if (!empty($moduleImage) && file_exists('../uploads/modules/' . $moduleImage)): ?>
                    <img src="../uploads/modules/<?php echo htmlspecialchars($moduleImage); ?>" 
                         alt="<?php echo htmlspecialchars($moduleName); ?>" class="w-full h-full object-cover">
                <?php else: ?>
                    <span class="text-4xl">📚</span>
                <?php endif; ?>
            </div>
            <span class="text-orange-500 text-[10px] font-bold uppercase tracking-wider">
                <?php echo htmlspecialchars($courseName); ?>
            </span>
            <h2 class="text-2xl font-bold mb-4"><?php echo htmlspecialchars($moduleName); ?></h2>
            <div class="space-y-3 border-t border-gray-100 pt-4">
                <h3 class="font-bold text-lg text-orange-600">Total Amount : <?php echo number_format($price); ?> MMK</h3>
                <p class="text-gray-500 font-semibold">LifeTime Provided Services</p>
                <ul class="text-gray-600 space-y-2 text-sm">
                    <li><span class="text-orange-500">★</span> LifeTime Update Access</li>
                    <li><span class="text-orange-500">★</span> One By One Meeting</li>
                    <li><span class="text-orange-500">★</span> Unlimited Download</li>
                </ul>
            </div>
        </div>

        <!-- RIGHT: Enrollment Form -->
        <div class="flex flex-col gap-6">

            <h2 class="text-xl text-brandOchre italic font-bold -mb-2">Complete your enrollment 🚀</h2>
            
            <!-- User Info -->
            <div class="bg-white border border-gray-200 rounded-2xl p-5 shadow-sm">
                <div class="grid grid-cols-1 md:grid-cols-2 gap-3">
                    <div>
                        <label class="block text-[11px] font-bold text-gray-400 uppercase mb-1">Email</label>
                        <input type="email" readonly value="<?php echo htmlspecialchars($userData['email']); ?>" class="w-full bg-gray-50 border border-gray-200 p-2.5 rounded-lg text-sm text-gray-700">
                    </div>
                    <div>
                        <label class="block text-[11px] font-bold text-gray-400 uppercase mb-1">Name</label>
                        <input type="text" readonly value="<?php echo htmlspecialchars($userData['name']); ?>" class="w-full bg-gray-50 border border-gray-200 p-2.5 rounded-lg text-sm text-gray-700">
                    </div>
                    <div class="md:col-span-2">
                        <label class="block text-[11px] font-bold text-gray-400 uppercase mb-1">Phone</label>
                        <input type="text" readonly value="<?php echo htmlspecialchars($userData['phone']); ?>" class="w-full bg-gray-50 border border-gray-200 p-2.5 rounded-lg text-sm text-gray-700">
                    </div>
                </div>
            </div>
            
            <!-- Payment Methods -->
            <div class="bg-white border border-gray-200 rounded-2xl p-5 shadow-sm">
                <p class="mb-3 text-sm font-semibold text-gray-800">Select Payment Method</p>
                <div class="grid grid-cols-1 md:grid-cols-2 gap-3" id="paymentMethodsGrid">
                    <?php if (!empty($payment_methods)): ?>
                        <?php foreach ($payment_methods as $method): 
                            $brandColor = getMethodColor($method['name']);
                            $useWhiteText = !isLightColor($brandColor);
                        ?>
                            <button type="button" 
                                    data-method-id="<?php echo $method['id']; ?>"
                                    data-method-name="<?php echo htmlspecialchars($method['name']); ?>"
                                    data-qr-src="<?php echo htmlspecialchars($method['qrcode'] ?? '../assets/default_qr.png'); ?>"
                                    data-brand-color="<?php echo $brandColor; ?>"
                                    data-white-text="<?php echo $useWhiteText ? '1' : '0'; ?>"
                                    onclick="selectPaymentMethod(this)" 
                                    class="payment-btn border-2 border-gray-200 bg-white p-3.5 rounded-xl flex items-center gap-3 transition-all duration-300 cursor-pointer hover:shadow-md">
                                
                                <img src="../assets/<?php echo htmlspecialchars($method['image'] ?? 'default.png'); ?>" 
                                     alt="<?php echo htmlspecialchars($method['name']); ?>" 
                                     class="w-9 h-9 object-contain rounded-lg bg-white p-0.5 method-logo">
                                
                                <span class="method-text font-medium text-gray-700 text-sm transition-all duration-300">
                                    <?php echo htmlspecialchars($method['name']); ?>
                                </span>
                                
                                <i class="fa-solid fa-circle-check ml-auto text-lg hidden selected-check transition-all duration-300"></i>
                            </button>
                        <?php endforeach; ?>
                    <?php else: ?>
                        <p class="text-sm text-gray-500 col-span-2 text-center py-4">No payment methods available.</p>
                    <?php endif; ?>
                </div>
            </div>

            <!-- QR Code -->
            <div id="qrSection" style="display:none;">
                <div class="bg-white border border-gray-200 rounded-2xl p-4 shadow-sm">
                    <div class="flex flex-col sm:flex-row items-center gap-4">
                        <div class="flex-shrink-0 bg-gray-50 rounded-xl p-2.5 border border-gray-100">
                            <img id="qrImageInline" src="" alt="QR Code" class="w-28 h-28 object-contain">
                        </div>
                        <div class="flex-1 text-center sm:text-left space-y-2">
                            <p class="text-xs font-bold text-gray-800 uppercase tracking-wider">Scan to Pay</p>
                            <div class="flex items-center justify-center sm:justify-start gap-2">
                                <div class="w-7 h-7 rounded-full bg-orange-50 flex items-center justify-center flex-shrink-0">
                                    <i class="fa-solid fa-user text-orange-500 text-[10px]"></i>
                                </div>
                                <span class="text-gray-800 font-semibold text-sm"><?php echo htmlspecialchars($adminName); ?></span>
                            </div>
                            <div class="flex items-center justify-center sm:justify-start gap-2">
                                <div class="w-7 h-7 rounded-full bg-orange-50 flex items-center justify-center flex-shrink-0">
                                    <i class="fa-solid fa-phone text-orange-500 text-[10px]"></i>
                                </div>
                                <span id="adminPhoneDisplay" class="text-gray-600 text-sm font-mono"><?php echo htmlspecialchars($adminPhone); ?></span>
                                <button onclick="copyPhone()" id="copyBtn" 
                                        class="p-1.5 rounded-lg bg-gray-100 hover:bg-green-100 transition-all duration-200 group" 
                                        title="Copy phone number">
                                    <i class="fa-regular fa-copy text-gray-400 group-hover:text-green-600 text-[11px]" id="copyIcon"></i>
                                </button>
                            </div>
                            <p id="selectedMethodLabel" class="text-[11px] font-semibold"></p>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Receipt Upload -->
            <div class="bg-white border border-gray-200 rounded-2xl p-5 shadow-sm">
                <label class="block text-[11px] font-bold text-gray-400 uppercase mb-3">Upload Receipt</label>
                <div id="receiptDropzone" 
                     class="border-2 border-dashed border-gray-300 rounded-xl overflow-hidden hover:border-orange-400 transition-all duration-300 bg-gray-50/50">
                    
                    <div id="receiptPlaceholder" class="py-6 px-4 text-center">
                        <div class="flex items-center justify-center gap-2 mb-3">
                            <div class="w-1.5 h-1.5 rounded-full bg-orange-500"></div>
                            <h3 class="text-[10px] font-bold text-gray-800 uppercase tracking-widest">Payment Receipt</h3>
                            <div class="w-1.5 h-1.5 rounded-full bg-orange-500"></div>
                        </div>
                        <div class="w-12 h-12 rounded-full bg-orange-50 flex items-center justify-center mx-auto mb-2">
                            <i class="fa-solid fa-cloud-arrow-up text-orange-500 text-lg"></i>
                        </div>
                        <p class="text-xs text-gray-500 mb-2">Drag & drop or click below</p>
                        <label for="receiptInput" class="inline-block px-5 py-2 bg-orange-500 text-white text-xs font-bold rounded-lg cursor-pointer hover:bg-orange-600 transition-colors shadow-sm">
                            Choose File
                        </label>
                        <input type="file" name="receipt" id="receiptInput" accept="image/*,.pdf" class="hidden" required onchange="handleReceiptUpload(this)">
                        <p class="text-[10px] text-gray-400 mt-2">PNG, JPG or PDF (Max 5MB)</p>
                    </div>

                    <div id="receiptPreview" style="display:none;">
                        <div class="bg-gray-800 text-white py-2 px-4 flex items-center justify-between">
                            <div class="flex items-center gap-2">
                                <div class="w-2 h-2 rounded-full bg-green-400"></div>
                                <span class="text-[10px] font-bold uppercase tracking-widest">Receipt Uploaded</span>
                            </div>
                            <button type="button" onclick="removeReceipt()" class="text-gray-400 hover:text-red-400 transition-colors p-0.5">
                                <i class="fa-solid fa-xmark text-xs"></i>
                            </button>
                        </div>
                        <div class="p-3">
                            <div class="border border-gray-200 rounded-lg overflow-hidden">
                                <div class="bg-gray-50 border-b border-gray-200 px-3 py-1.5 flex items-center justify-between">
                                    <span class="text-[10px] font-bold text-gray-500 uppercase tracking-wider">Transaction Proof</span>
                                    <span id="receiptFileSize" class="text-[10px] text-gray-400"></span>
                                </div>
                                <div class="p-4 bg-white flex items-center justify-center min-h-[160px]">
                                    <img id="receiptThumb" src="" alt="Receipt Preview" class="max-h-[200px] max-w-full object-contain rounded">
                                </div>
                                <div class="border-t border-gray-200 px-3 py-2 bg-gray-50 flex items-center justify-between">
                                    <div class="flex items-center gap-1.5">
                                        <i class="fa-solid fa-file-image text-orange-400 text-xs"></i>
                                        <span id="receiptFileName" class="text-[11px] text-gray-600 truncate max-w-[160px]"></span>
                                    </div>
                                    <label for="receiptInput" class="text-[11px] text-orange-500 font-semibold hover:underline cursor-pointer">Change</label>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Purchase Button -->
            <button onclick="processPurchase(event)" id="purchaseBtn" class="w-full bg-orange-500 text-white font-bold py-4 rounded-2xl shadow-lg hover:bg-orange-600 hover:shadow-xl transition-all duration-200 text-[15px]">
                Purchase Now - <?php echo number_format($price); ?> MMK
            </button>
        </div>
    </div>
</main>

<style>
    .payment-btn:hover { border-color: #d1d5db; background: #f9fafb; }
    .payment-btn .method-logo { background: white; transition: all 0.3s; }
    .copy-success { background: #dcfce7 !important; }
    .copy-success i { color: #22c55e !important; }
    @keyframes slideDown {
        from { opacity: 0; transform: translateY(-10px); }
        to { opacity: 1; transform: translateY(0); }
    }
    #qrSection.show { animation: slideDown 0.25s ease-out; }
</style>

<script>
let selectedMethod = null;
let selectedMethodId = null;

function selectPaymentMethod(btn) {
    var color = btn.getAttribute('data-brand-color');
    var whiteText = btn.getAttribute('data-white-text') === '1';
    var txtColor = whiteText ? '#ffffff' : '#1f2937';
    var chkColor = whiteText ? '#ffffff' : '#374151';
    var logoShadow = whiteText ? '0 1px 4px rgba(0,0,0,0.15)' : 'none';

    document.querySelectorAll('.payment-btn').forEach(function(b) {
        b.style.backgroundColor = '#ffffff';
        b.style.borderColor = '#e5e7eb';
        b.style.boxShadow = 'none';
        b.style.transform = 'scale(1)';
        b.querySelector('.method-text').style.color = '#374151';
        b.querySelector('.method-text').style.fontWeight = '500';
        b.querySelector('.selected-check').style.display = 'none';
        b.querySelector('.method-logo').style.boxShadow = 'none';
    });

    btn.style.backgroundColor = color;
    btn.style.borderColor = color;
    btn.style.boxShadow = '0 4px 15px ' + color + '55, 0 0 0 2px ' + color + '33';
    btn.style.transform = 'scale(1.02)';
    btn.querySelector('.method-text').style.color = txtColor;
    btn.querySelector('.method-text').style.fontWeight = '600';
    btn.querySelector('.selected-check').style.display = 'inline-block';
    btn.querySelector('.selected-check').style.color = chkColor;
    btn.querySelector('.method-logo').style.boxShadow = logoShadow;

    selectedMethod = btn.getAttribute('data-method-name');
    selectedMethodId = btn.getAttribute('data-method-id');
    document.getElementById('qrImageInline').src = btn.getAttribute('data-qr-src');
    
    var label = document.getElementById('selectedMethodLabel');
    label.textContent = 'Selected: ' + selectedMethod;
    label.style.color = color;
    
    var qrSection = document.getElementById('qrSection');
    qrSection.style.display = 'block';
    qrSection.classList.add('show');
    setTimeout(function() {
        qrSection.scrollIntoView({ behavior: 'smooth', block: 'center' });
    }, 100);
}

function copyPhone() {
    var phoneText = document.getElementById('adminPhoneDisplay').textContent;
    var copyBtn = document.getElementById('copyBtn');
    var copyIcon = document.getElementById('copyIcon');
    navigator.clipboard.writeText(phoneText).then(function() {
        copyBtn.classList.add('copy-success');
        copyIcon.classList.remove('fa-regular', 'fa-copy');
        copyIcon.classList.add('fa-solid', 'fa-check');
        setTimeout(function() {
            copyBtn.classList.remove('copy-success');
            copyIcon.classList.remove('fa-solid', 'fa-check');
            copyIcon.classList.add('fa-regular', 'fa-copy');
        }, 2000);
    }).catch(function() {
        var ta = document.createElement('textarea');
        ta.value = phoneText; ta.style.position = 'fixed'; ta.style.opacity = '0';
        document.body.appendChild(ta); ta.select(); document.execCommand('copy'); document.body.removeChild(ta);
        copyBtn.classList.add('copy-success');
        copyIcon.classList.remove('fa-regular', 'fa-copy');
        copyIcon.classList.add('fa-solid', 'fa-check');
        setTimeout(function() {
            copyBtn.classList.remove('copy-success');
            copyIcon.classList.remove('fa-solid', 'fa-check');
            copyIcon.classList.add('fa-regular', 'fa-copy');
        }, 2000);
    });
}

function handleReceiptUpload(input) {
    var file = input.files[0];
    if (!file) return;
    if (file.size > 5 * 1024 * 1024) {
        Swal.fire({ title: 'File Too Large', text: 'Max 5MB.', icon: 'warning', confirmButtonColor: '#ea580c' });
        input.value = ''; return;
    }
    var reader = new FileReader();
    reader.onload = function(e) {
        document.getElementById('receiptThumb').src = e.target.result;
        document.getElementById('receiptFileName').textContent = file.name;
        var size = file.size;
        var t = size < 1024 ? size + ' B' : size < 1048576 ? (size/1024).toFixed(1) + ' KB' : (size/1048576).toFixed(1) + ' MB';
        document.getElementById('receiptFileSize').textContent = t;
        document.getElementById('receiptPlaceholder').style.display = 'none';
        document.getElementById('receiptPreview').style.display = 'block';
        var dz = document.getElementById('receiptDropzone');
        dz.style.borderColor = '#86efac'; dz.style.borderStyle = 'solid'; dz.style.backgroundColor = '#f0fdf4';
    };
    reader.readAsDataURL(file);
}

function removeReceipt() {
    document.getElementById('receiptInput').value = '';
    document.getElementById('receiptThumb').src = '';
    document.getElementById('receiptFileName').textContent = '';
    document.getElementById('receiptFileSize').textContent = '';
    document.getElementById('receiptPlaceholder').style.display = 'block';
    document.getElementById('receiptPreview').style.display = 'none';
    var dz = document.getElementById('receiptDropzone');
    dz.style.borderColor = '#d1d5db'; dz.style.borderStyle = 'dashed'; dz.style.backgroundColor = '';
}

var dz = document.getElementById('receiptDropzone');
dz.addEventListener('dragover', function(e) { e.preventDefault(); this.style.borderColor = '#f97316'; this.style.backgroundColor = '#fff7ed'; });
dz.addEventListener('dragleave', function(e) { e.preventDefault(); this.style.borderColor = '#d1d5db'; this.style.backgroundColor = ''; });
dz.addEventListener('drop', function(e) {
    e.preventDefault(); this.style.borderColor = '#d1d5db'; this.style.backgroundColor = '';
    if (e.dataTransfer.files.length > 0) {
        document.getElementById('receiptInput').files = e.dataTransfer.files;
        handleReceiptUpload(document.getElementById('receiptInput'));
    }
});

function processPurchase(event) {
    if (!selectedMethod) {
        Swal.fire({ title: 'Select Payment Method', text: 'Please select a payment method first.', icon: 'warning', confirmButtonColor: '#ea580c' }); return;
    }
    var ri = document.querySelector('input[name="receipt"]');
    if (!ri || ri.files.length === 0) {
        Swal.fire({ title: 'Receipt Required', text: 'Please upload your payment receipt.', icon: 'warning', confirmButtonColor: '#ea580c' }); return;
    }
    var file = ri.files[0];
    var btn = event.target;
    var orig = btn.innerText;
    btn.innerText = "Processing..."; btn.disabled = true; btn.classList.add('opacity-70');
    var fd = new FormData();
    fd.append('module_id', '<?php echo $module_id; ?>');
    fd.append('payment_method_id', selectedMethodId);
    fd.append('receipt', file);
    fetch('process_enrollment.php', { method: 'POST', body: fd })
    .then(function(r) { return r.json(); })
    .then(function(data) {
        if (data.success) {
            Swal.fire({ title: 'Payment Successful!', text: data.message, icon: 'success', showConfirmButton: false, timer: 2000, timerProgressBar: true })
            .then(function() { window.location.href = data.redirect; });
        } else {
            Swal.fire({ title: 'Error', text: data.message, icon: 'error', confirmButtonColor: '#ea580c' });
            btn.innerText = orig; btn.disabled = false; btn.classList.remove('opacity-70');
        }
    })
    .catch(function() {
        Swal.fire({ title: 'Error', text: 'Something went wrong.', icon: 'error', confirmButtonColor: '#ea580c' });
        btn.innerText = orig; btn.disabled = false; btn.classList.remove('opacity-70');
    });
}
</script>

<?php include_once('../includes/footer.php'); ?>