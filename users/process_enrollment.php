<?php
session_set_cookie_params(['path' => '/']);
session_start();

if (!isset($_SESSION['user_id'])) {
    echo json_encode(['success' => false, 'message' => 'Please login first.']);
    exit();
}

header('Content-Type: application/json; charset=utf-8');
require_once '../config/db.php';

if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
    echo json_encode(['success' => false, 'message' => 'Invalid request method.']);
    exit();
}

$user_id = $_SESSION['user_id'];
$module_id = intval($_POST['module_id'] ?? 0);
$payment_method_id = intval($_POST['payment_method_id'] ?? 0);

if (!$module_id) {
    echo json_encode(['success' => false, 'message' => 'Module is required.']);
    exit();
}

// 1. Handle File Upload
if (!isset($_FILES['receipt']) || $_FILES['receipt']['error'] !== UPLOAD_ERR_OK) {
    echo json_encode(['success' => false, 'message' => 'Please upload a valid receipt image.']);
    exit();
}

$uploadDir = '../uploads/receipts/';
if (!is_dir($uploadDir)) mkdir($uploadDir, 0777, true);

$fileExtension = pathinfo($_FILES['receipt']['name'], PATHINFO_EXTENSION);
$fileName = 'receipt_' . $user_id . '_' . $module_id . '_' . time() . '.' . $fileExtension;
$targetPath = $uploadDir . $fileName;

if (!move_uploaded_file($_FILES['receipt']['tmp_name'], $targetPath)) {
    echo json_encode(['success' => false, 'message' => 'Failed to save receipt file.']);
    exit();
}

// 2. Fetch Module Details
$mod = $conn->prepare("SELECT m.id, m.name, m.price, m.course_id, c.course_name FROM modules m JOIN courses c ON m.course_id = c.id WHERE m.id = ?");
$mod->bind_param("i", $module_id);
$mod->execute();
$module = $mod->get_result()->fetch_assoc();

if (!$module) {
    echo json_encode(['success' => false, 'message' => 'Module not found.']);
    exit();
}

// 3. Prevent Duplicate Enrollment (allow re-enrollment if previous was rejected)
$check = $conn->prepare("SELECT id FROM enrollments WHERE user_id = ? AND module_id = ? AND LOWER(status) != 'rejected'");
$check->bind_param("ii", $user_id, $module_id);
$check->execute();
if ($check->get_result()->num_rows > 0) {
    echo json_encode(['success' => false, 'message' => 'You are already enrolled in this module.']);
    exit();
}

// 4. Insert into Database (Added 'receipt' column)
$enroll_date = date('Y-m-d');
$stmt = $conn->prepare("INSERT INTO enrollments (user_id, module_id, payment_method_id, receipt, enroll_date, status) VALUES (?, ?, ?, ?, ?, 'pending')");
$stmt->bind_param("iiiss", $user_id, $module_id, $payment_method_id, $targetPath, $enroll_date);
if (!$stmt->execute()) {
    echo json_encode(['success' => false, 'message' => 'Database error: ' . $stmt->error]);
    exit();
}

// 5. Notify admin only (user should not get notification for their own action)
require_once __DIR__ . '/../includes/admin_notification_helper.php';
$uname = $conn->query("SELECT name FROM users WHERE id = $user_id")->fetch_row()[0] ?? 'A student';
create_admin_notification(
    $uname . ' enrolled in ' . $module['course_name'] . ' - ' . $module['name'],
    'enrollments.php',
    'enrollment'
);

echo json_encode(['success' => true, 'message' => 'Enrollment submitted! Awaiting admin approval.', 'redirect' => 'enroll.php']);