<?php
error_reporting(0);
session_start();
require_once '../config/db.php';
require_once '../vendor/autoload.php';

use Dompdf\Dompdf;
use Dompdf\Options;

$module_id = isset($_GET['module_id']) ? (int)$_GET['module_id'] : 0;
$user_id = $_SESSION['user_id'] ?? null;
session_write_close();

if (!$user_id || !$module_id) {
    http_response_code(400);
    die("Invalid request.");
}

$userStmt = $conn->prepare("SELECT name, email FROM users WHERE id = ?");
$userStmt->bind_param("i", $user_id);
$userStmt->execute();
$user = $userStmt->get_result()->fetch_assoc();
if (!$user) {
    http_response_code(404);
    die("User not found.");
}

$modStmt = $conn->prepare("
    SELECT m.name AS module_name, m.price, c.course_name, c.instructor_name
    FROM modules m
    JOIN courses c ON m.course_id = c.id
    WHERE m.id = ?
");
$modStmt->bind_param("i", $module_id);
$modStmt->execute();
$module = $modStmt->get_result()->fetch_assoc();
if (!$module) {
    http_response_code(404);
    die("Module not found.");
}

$lessonCount = $conn->query("SELECT COUNT(*) AS total FROM lessons WHERE module_id = $module_id")->fetch_assoc()['total'];
$completedCount = $conn->query("SELECT COUNT(*) AS done FROM lesson_progress WHERE user_id = $user_id AND lesson_id IN (SELECT id FROM lessons WHERE module_id = $module_id) AND completed = 1")->fetch_assoc()['done'];

if ($completedCount < $lessonCount) {
    if (isset($_GET['validate'])) {
        header('Content-Type: application/json');
        echo json_encode(['ok' => false, 'error' => "You have not completed all lessons yet. ($completedCount/$lessonCount)"]);
        exit;
    }
    http_response_code(403);
    die("You have not completed all lessons yet. ($completedCount/$lessonCount)");
}

// Quiz gate: the module quiz must be passed before a certificate is issued.
$quizRow = $conn->query("SELECT id FROM quizzes WHERE module_id = $module_id AND status = 'active' ORDER BY id DESC LIMIT 1")->fetch_assoc();
if (!$quizRow) {
    if (isset($_GET['validate'])) {
        header('Content-Type: application/json');
        echo json_encode(['ok' => false, 'error' => 'No quiz has been set up for this module yet.']);
        exit;
    }
    http_response_code(403);
    die("No quiz has been set up for this module yet.");
}
$passedRow = $conn->query("SELECT passed FROM quiz_results WHERE user_id = $user_id AND quiz_id = {$quizRow['id']} ORDER BY id DESC LIMIT 1")->fetch_assoc();
if (!$passedRow || (int)$passedRow['passed'] !== 1) {
    if (isset($_GET['validate'])) {
        header('Content-Type: application/json');
        echo json_encode(['ok' => false, 'error' => 'You must pass the module quiz to earn your certificate.']);
        exit;
    }
    http_response_code(403);
    die("You must pass the module quiz to earn your certificate.");
}

$enrollRow = $conn->query("SELECT id FROM enrollments WHERE user_id = $user_id AND module_id = $module_id")->fetch_assoc();
$enrollId = $enrollRow ? $enrollRow['id'] : 0;

$existing = $conn->query("SELECT id, certificate_no FROM certificates WHERE enroll_id = $enrollId");
$certRow = $existing && $existing->num_rows > 0 ? $existing->fetch_assoc() : null;

if ($certRow) {
    $certId = $certRow['certificate_no'];
} else {
    $certId = 'CERT-' . strtoupper(substr(md5($user_id . $module_id), 0, 8));
}

if ($certRow) {
    if (isset($_GET['validate'])) {
        header('Content-Type: application/json');
        echo json_encode(['ok' => false, 'error' => 'Certificate already downloaded. You can only download it once.']);
        exit;
    }
    http_response_code(403);
    die('Certificate already downloaded. You can only download it once.');
}

if (isset($_GET['validate'])) {
    header('Content-Type: application/json');
    echo json_encode(['ok' => true]);
    exit;
}

$completion = $conn->query("SELECT MAX(completed_at) AS date FROM lesson_progress WHERE user_id = $user_id AND lesson_id IN (SELECT id FROM lessons WHERE module_id = $module_id) AND completed = 1")->fetch_assoc();
$completionDate = $completion['date'] ? date('F j, Y', strtotime($completion['date'])) : date('F j, Y');

$studentName = htmlspecialchars($user['name']);
$moduleName = htmlspecialchars($module['module_name']);
$courseName = htmlspecialchars($module['course_name']);
$instructorName = htmlspecialchars($module['instructor_name'] ?? 'N/A');

$stampPath = __DIR__ . '/../assets/schoolstamp.png';
$stampSrc = '';
if (file_exists($stampPath)) {
    $stampData = base64_encode(file_get_contents($stampPath));
    $stampSrc = 'data:image/png;base64,' . $stampData;
}

$html = <<<HTML
<!DOCTYPE html>
<html>
<head>
<meta charset="UTF-8">
<style>
    @page { margin: 0; }
    body {
        margin: 0;
        padding: 0;
        font-family: Helvetica, Arial, sans-serif;
        width: 297mm;
        height: 210mm;
        background: #f7f5ef;
        position: relative;
        color: #17233c;
    }

    /* ================= Sidebar ================= */
    .sidebar {
        position: absolute;
        top: 0; left: 0; bottom: 0;
        width: 64mm;
        background: #131f38;
    }
    .sidebar-shade {
        position: absolute;
        top: 0; left: 0; right: 0; height: 90mm;
        background: #0d1628;
    }
    .sidebar-edge {
        position: absolute;
        top: 0; left: 64mm; bottom: 0;
        width: 1.4mm;
        background: #c9a227;
    }

    .brand {
        position: absolute;
        top: 15mm; left: 0;
        width: 64mm;
        text-align: center;
    }
    .monogram {
        width: 21mm; height: 21mm;
        margin: 0 auto;
        background: #ffffff;
        border: 0.7mm solid #c9a227;
        border-radius: 50%;
        color: #131f38;
        font-size: 25px;
        font-weight: bold;
        line-height: 21mm;
        text-align: center;
    }
    .brand-name {
        margin-top: 5mm;
        color: #ffffff;
        font-size: 14px;
        font-weight: bold;
        letter-spacing: 3px;
    }
    .brand-sub {
        margin-top: 2.5mm;
        color: #8fa0c2;
        font-size: 7.5px;
        letter-spacing: 2.5px;
    }
    .brand-rule {
        width: 22mm; height: 0.5mm;
        background: #c9a227;
        margin: 4mm auto 0;
    }

    .rings { position: absolute; top: 88mm; left: 12mm; width: 40mm; height: 40mm; }
    .ring-outer {
        position: absolute; top: 0; left: 0;
        width: 40mm; height: 40mm;
        border: 0.35mm solid #3d4f75;
        border-radius: 50%;
    }
    .ring-mid {
        position: absolute; top: 3.5mm; left: 3.5mm;
        width: 33mm; height: 33mm;
        border: 0.3mm solid #c9a227;
        border-radius: 50%;
    }
    .ring-inner {
        position: absolute; top: 7mm; left: 7mm;
        width: 26mm; height: 26mm;
        border: 0.25mm solid #2c3c60;
        border-radius: 50%;
    }

    .stamp-wrap {
        position: absolute;
        bottom: 13mm; left: 11.5mm;
        width: 41mm; height: 41mm;
        background: #ffffff;
        border: 0.6mm solid #c9a227;
        border-radius: 50%;
        padding: 2.5mm;
    }
    .stamp-img { width: 100%; height: 100%; }

    .side-foot {
        position: absolute;
        bottom: 6.5mm; left: 0;
        width: 64mm;
        text-align: center;
        color: #7688ad;
        font-size: 7px;
        letter-spacing: 2px;
    }

    /* ================= Main sheet ================= */
    .sheet {
        position: absolute;
        top: 10mm; left: 77mm; right: 12mm; bottom: 10mm;
        background: #ffffff;
    }
    .frame-outer {
        position: absolute; top: 0; left: 0; right: 0; bottom: 0;
        border: 0.5mm solid #c9a227;
    }
    .frame-inner {
        position: absolute; top: 2.4mm; left: 2.4mm; right: 2.4mm; bottom: 2.4mm;
        border: 0.2mm solid #e6d49a;
    }

    .corner { position: absolute; width: 13mm; height: 13mm; }
    .corner-tl { top: -0.6mm; left: -0.6mm; border-top: 1.3mm solid #17233c; border-left: 1.3mm solid #17233c; }
    .corner-tr { top: -0.6mm; right: -0.6mm; border-top: 1.3mm solid #17233c; border-right: 1.3mm solid #17233c; }
    .corner-bl { bottom: -0.6mm; left: -0.6mm; border-bottom: 1.3mm solid #17233c; border-left: 1.3mm solid #17233c; }
    .corner-br { bottom: -0.6mm; right: -0.6mm; border-bottom: 1.3mm solid #17233c; border-right: 1.3mm solid #17233c; }

    .watermark {
        position: absolute;
        top: 92mm; left: 0; right: 0;
        text-align: center;
        font-size: 54px;
        font-weight: bold;
        letter-spacing: 6px;
        color: rgba(23, 35, 60, 0.05);
    }

    .inner {
        position: absolute;
        top: 11mm; left: 10mm; right: 10mm; bottom: 16mm;
        text-align: center;
    }

    .kicker {
        font-size: 9px;
        font-weight: bold;
        letter-spacing: 5px;
        color: #a3841f;
    }
    .cert-title {
        margin-top: 3.5mm;
        font-size: 44px;
        font-weight: bold;
        letter-spacing: 1px;
        color: #17233c;
    }
    .title-rule {
        width: 32mm; height: 0.9mm;
        background: #c9a227;
        margin: 4.5mm auto 0;
    }

    .presented {
        margin-top: 6mm;
        font-size: 10px;
        letter-spacing: 3px;
        text-transform: uppercase;
        color: #8b95ac;
    }
    .student-name {
        margin-top: 4mm;
        font-size: 36px;
        font-weight: bold;
        color: #17233c;
    }
    .name-rule {
        width: 72mm; height: 0.45mm;
        background: #c9a227;
        margin: 3mm auto 0;
    }

    .body-text {
        margin-top: 6.5mm;
        font-size: 12px;
        color: #4a5670;
    }
    .module-name {
        margin-top: 2mm;
        font-size: 22px;
        font-weight: bold;
        color: #a3841f;
    }
    .course-info {
        margin-top: 2mm;
        font-size: 11px;
        color: #66708a;
    }

    table.meta {
        width: 100%;
        margin-top: 8mm;
        border-collapse: collapse;
    }
    table.meta td {
        width: 33.33%;
        text-align: center;
        vertical-align: top;
        padding: 0 4mm;
    }
    .meta-label {
        font-size: 7.5px;
        letter-spacing: 1.5px;
        text-transform: uppercase;
        color: #99a2b6;
    }
    .meta-value {
        margin-top: 1.5mm;
        font-size: 11.5px;
        font-weight: bold;
        color: #17233c;
    }
    .meta-line {
        width: 62%;
        height: 0.3mm;
        background: #e6d49a;
        margin: 1.8mm auto 0;
    }

    table.signs {
        width: 100%;
        margin-top: 9mm;
        border-collapse: collapse;
    }
    table.signs td {
        width: 50%;
        text-align: center;
        vertical-align: bottom;
        padding: 0 12mm;
    }
    .sig-line { height: 0.35mm; background: #3a4763; }
    .sig-name {
        margin-top: 1.8mm;
        font-size: 11px;
        font-weight: bold;
        color: #17233c;
    }
    .sig-role {
        margin-top: 1mm;
        font-size: 7.5px;
        letter-spacing: 1.5px;
        text-transform: uppercase;
        color: #8b95ac;
    }

    .foot {
        position: absolute;
        bottom: 5mm; left: 10mm; right: 10mm;
        text-align: center;
        font-size: 7.5px;
        font-style: italic;
        color: #9aa3b6;
    }
</style>
</head>
<body>
    <div class="sidebar">
        <div class="sidebar-shade"></div>
        <div class="brand">
            <div class="monogram">EA</div>
            <div class="brand-name">ENGLISH<br>ACADEMY</div>
            <div class="brand-sub">LEARN &middot; GROW &middot; SUCCEED</div>
            <div class="brand-rule"></div>
        </div>
        <div class="rings">
            <div class="ring-outer"></div>
            <div class="ring-mid"></div>
            <div class="ring-inner"></div>
        </div>
        <div class="stamp-wrap"><img class="stamp-img" src="$stampSrc" alt="School Seal"></div>
        <div class="side-foot">EST. EXCELLENCE IN EDUCATION</div>
    </div>
    <div class="sidebar-edge"></div>

    <div class="sheet">
        <div class="frame-outer"></div>
        <div class="frame-inner"></div>
        <div class="corner corner-tl"></div>
        <div class="corner corner-tr"></div>
        <div class="corner corner-bl"></div>
        <div class="corner corner-br"></div>

        <div class="watermark">ENGLISH ACADEMY</div>

        <div class="inner">
            <div class="kicker">ENGLISH ACADEMY</div>
            <div class="cert-title">Certificate of Completion</div>
            <div class="title-rule"></div>

            <div class="presented">This certificate is proudly presented to</div>
            <div class="student-name">$studentName</div>
            <div class="name-rule"></div>

            <div class="body-text">for successfully completing all lessons and passing the final assessment of the module</div>
            <div class="module-name">$moduleName</div>
            <div class="course-info">Course: &quot;$courseName&quot; &nbsp;&middot;&nbsp; Instructor: $instructorName</div>

            <table class="meta" cellpadding="0" cellspacing="0">
                <tr>
                    <td>
                        <div class="meta-label">Date of Completion</div>
                        <div class="meta-value">$completionDate</div>
                        <div class="meta-line"></div>
                    </td>
                    <td>
                        <div class="meta-label">Lessons Completed</div>
                        <div class="meta-value">$lessonCount Lessons</div>
                        <div class="meta-line"></div>
                    </td>
                    <td>
                        <div class="meta-label">Certificate ID</div>
                        <div class="meta-value">$certId</div>
                        <div class="meta-line"></div>
                    </td>
                </tr>
            </table>

            <table class="signs" cellpadding="0" cellspacing="0">
                <tr>
                    <td>
                        <div class="sig-line"></div>
                        <div class="sig-name">$instructorName</div>
                        <div class="sig-role">Course Instructor</div>
                    </td>
                    <td>
                        <div class="sig-line"></div>
                        <div class="sig-name">English Academy</div>
                        <div class="sig-role">Platform Director</div>
                    </td>
                </tr>
            </table>
        </div>

        <div class="foot">This certificate is issued by English Academy &nbsp;&middot;&nbsp; Verify with Certificate ID: $certId</div>
    </div>
</body>
</html>
HTML;

$options = new Options();
$options->set('isRemoteEnabled', false);
$options->set('isHtml5ParserEnabled', true);
$options->set('defaultFont', 'Helvetica');

$dompdf = new Dompdf($options);
$dompdf->loadHtml($html);
$dompdf->setPaper('A4', 'landscape');
$dompdf->render();

$filename = 'Certificate_' . preg_replace('/[^A-Za-z0-9]/', '_', $user['name']) . '_' . $certId . '.pdf';

$pdfOutput = $dompdf->output();

$today = date('Y-m-d');
if (!$certRow) {
    $stmt = $conn->prepare("INSERT INTO certificates (enroll_id, issue_date, certificate_no) VALUES (?, ?, ?)");
    $stmt->bind_param("iss", $enrollId, $today, $certId);
    $stmt->execute();
    $stmt->close();
}

if (isset($_GET['dl'])) {
    while (ob_get_level() > 0) {
        ob_end_clean();
    }
    header('Content-Type: application/pdf');
    header('Content-Disposition: attachment; filename="' . $filename . '"');
    header('Content-Length: ' . strlen($pdfOutput));
    echo $pdfOutput;
    exit;
}

while (ob_get_level() > 0) {
    ob_end_clean();
}

header('Content-Type: application/json');
header('Cache-Control: no-store, no-cache, must-revalidate');
header('Pragma: no-cache');

echo json_encode(['pdf' => base64_encode($pdfOutput), 'filename' => $filename]);
exit;
