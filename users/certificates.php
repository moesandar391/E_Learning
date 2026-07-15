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
        font-family: 'Georgia', 'Times New Roman', serif;
        width: 297mm;
        height: 210mm;
        background: #fcf9f2;
        position: relative;
    }

    /* Certificate border */
    .cert-border {
        position: absolute;
        top: 5mm; left: 5mm; right: 5mm; bottom: 5mm;
        border: 1.5px solid #b8962e;
    }

    /* Footer */
    .footer {
        position: absolute;
        bottom: 18mm;
        width: 100%;
        text-align: center;
        font-size: 7px;
        color: #aaa;
        font-style: italic;
    }

    /* School stamp image at bottom */
    .stamp-img {
        position: absolute;
        bottom: 20mm;
        right: 25mm;
        width: 35mm;
        height: auto;
        z-index: 1;
    }

    /* Top ribbon */
    .ribbon {
        position: absolute;
        top: 14mm;
        left: 50%;
        transform: translateX(-50%);
        background: #b8962e;
        color: #fff;
        padding: 3px 28px;
        font-size: 10px;
        letter-spacing: 3px;
        font-weight: bold;
        text-transform: uppercase;
        z-index: 1;
    }

    /* Watermark */
    .watermark {
        position: absolute;
        top: 50%;
        left: 50%;
        transform: translate(-50%, -50%);
        font-size: 100px;
        font-weight: bold;
        color: rgba(184, 150, 46, 0.06);
        letter-spacing: 8px;
        text-transform: uppercase;
        pointer-events: none;
    }

    /* Main content */
    .content {
        position: absolute;
        top: 22mm;
        left: 14mm;
        right: 14mm;
        bottom: 14mm;
        display: flex;
        flex-direction: column;
        align-items: center;
        text-align: center;
        z-index: 1;
    }

    /* Main title */
    .cert-title {
        font-size: 38px;
        font-weight: bold;
        color: #1a1a1a;
        margin-top: 22px;
        letter-spacing: 1.5px;
    }

    /* Award text */
    .award-text {
        font-size: 13px;
        color: #555;
        margin-top: 14px;
        font-style: italic;
        letter-spacing: 0.5px;
    }

    /* Student name */
    .student-name {
        font-size: 30px;
        font-weight: bold;
        color: #1a1a1a;
        margin-top: 6px;
        letter-spacing: 1px;
    }
    .name-underline {
        width: 200px;
        height: 0.8px;
        background: #b8962e;
        margin: 4px auto 0;
    }

    /* Completion text */
    .completion-text {
        font-size: 12px;
        color: #555;
        margin-top: 10px;
        line-height: 1.5;
    }

    /* Module name */
    .module-name {
        font-size: 20px;
        font-weight: bold;
        color: #8a6a1a;
        margin-top: 2px;
    }

    /* Course and instructor */
    .course-info {
        font-size: 12px;
        color: #666;
        margin-top: 3px;
    }

    /* Details section */
    .details {
        display: flex;
        justify-content: center;
        gap: 35px;
        margin-top: 18px;
        width: 80%;
    }
    .detail {
        text-align: center;
        min-width: 80px;
    }
    .detail-label {
        font-size: 8px;
        color: #999;
        letter-spacing: 1px;
        text-transform: uppercase;
    }
    .detail-value {
        font-size: 11px;
        font-weight: bold;
        color: #333;
        margin-top: 3px;
    }
    .detail-line {
        width: 60%;
        height: 0.3px;
        background: #d4b96a;
        margin: 4px auto 0;
    }
</style>
</head>
<body>
    <div class="cert-border"></div>

    <div class="ribbon">Certificate of Completion</div>

    <div class="watermark">ENGLISH ACADEMY</div>

    <div class="content">
        <div class="cert-title">Certificate</div>

        <div class="award-text">This is to certify that</div>

        <div class="student-name">$studentName</div>
        <div class="name-underline"></div>

        <div class="completion-text">has successfully completed the module</div>
        <div class="module-name">$moduleName</div>
        <div class="course-info">under the course &quot;$courseName&quot; by Instructor: $instructorName</div>

        <div class="details">
            <div class="detail">
                <div class="detail-label">Date of Completion</div>
                <div class="detail-value">$completionDate</div>
                <div class="detail-line"></div>
            </div>
            <div class="detail">
                <div class="detail-label">Certificate ID</div>
                <div class="detail-value">$certId</div>
                <div class="detail-line"></div>
            </div>
            <div class="detail">
                <div class="detail-label">Lessons Completed</div>
                <div class="detail-value">$lessonCount</div>
                <div class="detail-line"></div>
            </div>
        </div>
    </div>

    <img class="stamp-img" src="$stampSrc" alt="School Seal">

    <div class="footer">This certificate is issued by English Academy - Certificate ID: $certId</div>
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
