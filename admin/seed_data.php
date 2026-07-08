<?php
error_reporting(E_ALL);
ini_set('display_errors', 1);

require_once __DIR__ . '/../config/db.php';

echo "<pre>";

// ── Helper functions ──────────────────────────────────────────────

function ranName() {
    $first = ['Aung', 'Kyaw', 'Thiha', 'Min', 'Zaw', 'Hla', 'Myo', 'Tun', 'Soe', 'Thet',
              'Su', 'Hnin', 'Yu', 'May', 'Phyu', 'Khin', 'Thida', 'Moe', 'Aye', 'Htay',
              'Nanda', 'Zin', 'Wai', 'Ei', 'Cho', 'Thiri', 'Yadanar', 'Naw', 'Saw', 'Htoo'];
    $last  = ['Aung', 'Kyaw', 'Tun', 'Lwin', 'Oo', 'Myint', 'Naing', 'Win', 'Htun', 'Shein',
              'Hlaing', 'Moe', 'Aye', 'Thwe', 'Khaing', 'Htet', 'Phyo', 'Lin', 'Zaw', 'Nyein'];
    return $first[array_rand($first)] . ' ' . $last[array_rand($last)];
}

function ranEmail($name, $n) {
    $clean = strtolower(preg_replace('/[^a-z]/', '', $name));
    $domains = ['gmail.com', 'yahoo.com', 'mail.com'];
    return $clean . $n . '@' . $domains[array_rand($domains)];
}

function ranPhone() {
    return '09' . rand(10000000, 99999999);
}

function ranDate($monthsAgo) {
    $now = time();
    $start = strtotime("-{$monthsAgo} months", $now);
    return date('Y-m-d', mt_rand($start, $now));
}

function ranCertNo() {
    return 'CERT-' . strtoupper(substr(md5(uniqid(mt_rand(), true)), 0, 8));
}

// ── Fetch existing reference data ─────────────────────────────────

$modules = $conn->query("SELECT id, course_id FROM modules")->fetch_all(MYSQLI_ASSOC);
if (!$modules) {
    die("No modules found in database. Seed courses/modules first.");
}
$moduleIds = array_column($modules, 'id');

$paymentMethods = $conn->query("SELECT id FROM payment_method")->fetch_all(MYSQLI_ASSOC);
if (!$paymentMethods) {
    die("No payment methods found.");
}
$pmIds = array_column($paymentMethods, 'id');

// ── Config ────────────────────────────────────────────────────────

$userCount     = 25;
$monthsBack    = 6;
$passwordHash  = password_hash('password123', PASSWORD_DEFAULT);
$now           = date('Y-m-d');

echo "=== Seeding {$userCount} users over past {$monthsBack} months ===\n\n";

// ── 1. Insert users ──────────────────────────────────────────────

$insertUser = $conn->prepare("INSERT INTO users (name, email, password, phone, gender, created_at, updated_at) VALUES (?, ?, ?, ?, ?, ?, ?)");

$insertedUsers = [];
for ($i = 1; $i <= $userCount; $i++) {
    $name  = ranName();
    $email = ranEmail($name, $i);
    $phone = ranPhone();
    $gender = rand(0, 1) ? 'Male' : 'Female';
    $createdAt = ranDate($monthsBack) . ' ' . sprintf('%02d:%02d:%02d', rand(8, 17), rand(0, 59), rand(0, 59));

    // stagger updated_at slightly after created_at
    $updatedTs = strtotime($createdAt) + rand(0, 86400 * 7);
    $updatedAt = date('Y-m-d H:i:s', min($updatedTs, time()));

    $insertUser->bind_param("sssssss", $name, $email, $passwordHash, $phone, $gender, $createdAt, $updatedAt);
    $insertUser->execute();
    $insertedUsers[] = $conn->insert_id;

    echo "  [user {$conn->insert_id}] {$name} <{$email}>\n";
}
$insertUser->close();
echo "\n" . count($insertedUsers) . " users inserted.\n\n";

// ── 2. Insert enrollments ────────────────────────────────────────

$statuses = ['Pending', 'Confirmed', 'Rejected'];

$insertEnroll = $conn->prepare("INSERT INTO enrollments (user_id, module_id, enroll_date, created_at, payment_method_id, status) VALUES (?, ?, ?, ?, ?, ?)");

$insertedEnrollments = [];
foreach ($insertedUsers as $uid) {
    // each user gets 1-3 enrollments
    $numEnrolls = rand(1, 3);
    for ($e = 0; $e < $numEnrolls; $e++) {
        $moduleId   = $moduleIds[array_rand($moduleIds)];
        $enrollDate = ranDate($monthsBack);
        $createdAt  = $enrollDate . ' ' . sprintf('%02d:%02d:%02d', rand(8, 17), rand(0, 59), rand(0, 59));
        $pmId       = $pmIds[array_rand($pmIds)];
        $status     = $statuses[array_rand($statuses)];

        $insertEnroll->bind_param("iissis", $uid, $moduleId, $enrollDate, $createdAt, $pmId, $status);
        $insertEnroll->execute();
        $eid = $conn->insert_id;
        $insertedEnrollments[] = ['id' => $eid, 'user_id' => $uid, 'module_id' => $moduleId, 'status' => $status, 'enroll_date' => $enrollDate];

        echo "  [enroll {$eid}] user={$uid} module={$moduleId} status={$status} date={$enrollDate}\n";
    }
}
$insertEnroll->close();
echo "\n" . count($insertedEnrollments) . " enrollments inserted.\n\n";

// ── 3. Insert certificates ───────────────────────────────────────

$insertCert = $conn->prepare("INSERT INTO certificates (enroll_id, issue_date, certificate_no) VALUES (?, ?, ?)");

$certCount = 0;
foreach ($insertedEnrollments as $enr) {
    // Only confirmed enrollments older than 30 days get certificates
    if ($enr['status'] !== 'Confirmed') continue;

    $enrollTs  = strtotime($enr['enroll_date']);
    $minAge    = 30 * 86400; // 30 days
    if ((time() - $enrollTs) < $minAge) continue;

    // ~70% chance of having a certificate
    if (rand(1, 10) > 7) continue;

    $issueDate = date('Y-m-d', $enrollTs + rand(1, 14) * 86400); // 1-14 days after enrollment
    $certNo    = ranCertNo();

    $insertCert->bind_param("iss", $enr['id'], $issueDate, $certNo);
    $insertCert->execute();

    echo "  [cert {$conn->insert_id}] enroll={$enr['id']} user={$enr['user_id']} no={$certNo} issued={$issueDate}\n";
    $certCount++;
}
$insertCert->close();
echo "\n{$certCount} certificates inserted.\n\n";

echo "=== Done ===\n";
