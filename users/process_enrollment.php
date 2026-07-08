<?php
session_set_cookie_params(['path' => '/']);
session_start();

if (!isset($_SESSION['user_id'])) {
    echo json_encode(['success' => false, 'message' => 'Please login first.']);
    exit();
}

header('Content-Type: application/json; charset=utf-8');
require_once '../config/db.php';
require_once '../includes/notification_helper.php';

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

$mod = $conn->prepare("SELECT m.id, m.name, m.price, m.course_id, c.course_name FROM modules m JOIN courses c ON m.course_id = c.id WHERE m.id = ?");
$mod->bind_param("i", $module_id);
$mod->execute();
$module = $mod->get_result()->fetch_assoc();

if (!$module) {
    echo json_encode(['success' => false, 'message' => 'Module not found.']);
    exit();
}

$check = $conn->prepare("SELECT id FROM enrollments WHERE user_id = ? AND module_id = ?");
$check->bind_param("ii", $user_id, $module_id);
$check->execute();
if ($check->get_result()->num_rows > 0) {
    echo json_encode(['success' => false, 'message' => 'You are already enrolled in this module.']);
    exit();
}

$enroll_date = date('Y-m-d');
$stmt = $conn->prepare("INSERT INTO enrollments (user_id, module_id, payment_method_id, enroll_date, status) VALUES (?, ?, ?, ?, 'pending')");
$stmt->bind_param("iiis", $user_id, $module_id, $payment_method_id, $enroll_date);
$stmt->execute();
$enroll_id = $conn->insert_id;

create_notification(
    $user_id,
    'Your enrollment in "' . $module['name'] . '" is pending approval',
    'enroll.php',
    'enrollment'
);

require_once __DIR__ . '/../includes/admin_notification_helper.php';
$uname = $conn->query("SELECT name FROM users WHERE id = $user_id")->fetch_row()[0] ?? 'A student';
create_admin_notification(
    $uname . ' enrolled in ' . $module['course_name'] . ' - ' . $module['name'],
    'enroll.php',
    'enrollment'
);

echo json_encode(['success' => true, 'message' => 'Enrollment submitted! Awaiting admin approval.', 'redirect' => 'enroll.php']);
