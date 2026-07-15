<?php
session_start();
if (!isset($_SESSION['user_id'])) {
    header("Location: ../auth/login.php");
    exit();
}
require_once '../config/db.php';

// Get course name from URL safely
 $courseName = urldecode($_GET['course'] ?? '');

if (empty($courseName)) {
    die("<h1 style='text-align:center;margin-top:50px;'>Error: No course specified.</h1>");
}

// Fetch student and course data for the certificate
 $stmt = $conn->prepare("
    SELECT u.name, u.email, c.course_name, MAX(e.enroll_date) as completion_date
    FROM enrollments e 
    JOIN users u ON e.user_id = u.id 
    JOIN modules m ON e.module_id = m.id 
    JOIN courses c ON m.course_id = c.id 
    WHERE e.user_id = ? AND c.course_name = ? AND e.status IN ('completed', 'confirmed')
    GROUP BY u.name, u.email, c.course_name
");
 $stmt->bind_param("is", $_SESSION['user_id'], $courseName);
 $stmt->execute();
 $certData = $stmt->get_result()->fetch_assoc();

// Fallback if no specific record found (just use session data)
if (!$certData) {
    $certData = [
        'name' => $_SESSION['username'] ?? 'Student',
        'email' => '',
        'course_name' => $courseName,
        'completion_date' => date('Y-m-d')
    ];
}

 $formattedDate = date("F d, Y", strtotime($certData['completion_date']));
 $certificateId = 'CERT-' . strtoupper(substr(md5($certData['email'] . $certData['course_name']), 0, 8));
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Certificate - <?= htmlspecialchars($certData['course_name']) ?></title>
    <style>
        /* ── Screen Styles ── */
        body {
            background-color: #f3f4f6;
            font-family: 'Times New Roman', Times, serif;
            display: flex;
            flex-direction: column;
            align-items: center;
            justify-content: center;
            min-height: 100vh;
            padding: 40px 20px;
        }
        .print-btn-container {
            margin-bottom: 30px;
            text-align: center;
        }
        .print-btn {
            background: #FF8A00;
            color: white;
            border: none;
            padding: 12px 30px;
            font-size: 16px;
            font-weight: bold;
            border-radius: 8px;
            cursor: pointer;
            box-shadow: 0 4px 6px rgba(0,0,0,0.1);
            transition: 0.2s;
        }
        .print-btn:hover {
            background: #e07a00;
        }

        /* ── Certificate Paper Styles ── */
        .certificate-paper {
            background: #ffffff;
            width: 800px;
            min-height: 600px;
            padding: 60px;
            position: relative;
            box-shadow: 0 10px 30px rgba(0,0,0,0.15);
            color: #1f2937;
        }

        /* Elegant Border */
        .border-ornate {
            position: absolute;
            top: 10px;
            left: 10px;
            right: 10px;
            bottom: 10px;
            border: 3px solid #FF8A00;
            pointer-events: none;
        }
        .border-inner {
            position: absolute;
            top: 18px;
            left: 18px;
            right: 18px;
            bottom: 18px;
            border: 1px solid #fdba74;
            pointer-events: none;
        }
        
        /* Corner Ornaments */
        .corner {
            position: absolute;
            width: 80px;
            height: 80px;
            border-radius: 50%;
            border: 3px double #FF8A00;
        }
        .top-left { top: 25px; left: 25px; border-right: none; border-bottom: none; }
        .top-right { top: 25px; right: 25px; border-left: none; border-bottom: none; }
        .bottom-left { bottom: 25px; left: 25px; border-right: none; border-top: none; }
        .bottom-right { bottom: 25px; right: 25px; border-left: none; border-top: none; }

        .content {
            position: relative;
            z-index: 2;
            text-align: center;
            display: flex;
            flex-direction: column;
            align-items: center;
            height: 100%;
            justify-content: space-between;
        }

        /* Header Section */
        .header-section {
            margin-top: 40px;
        }
        .logo-text {
            font-size: 18px;
            font-weight: bold;
            letter-spacing: 4px;
            color: #FF8A00;
            text-transform: uppercase;
            margin-bottom: 20px;
        }
        .cert-title {
            font-size: 42px;
            font-weight: normal;
            color: #111827;
            font-family: 'Palatino Linotype', 'Book Antiqua', Palatino, serif;
            margin-bottom: 10px;
        }
        .divider-line {
            width: 150px;
            height: 4px;
            background: linear-gradient(to right, transparent, #FF8A00, transparent);
            margin: 0 auto 40px auto;
        }

        /* Body Section */
        .body-section {
            flex-grow: 1;
            display: flex;
            flex-direction: column;
            justify-content: center;
            padding: 0 40px;
        }
        .presented-to {
            font-size: 14px;
            color: #6b7280;
            text-transform: uppercase;
            letter-spacing: 2px;
            margin-bottom: 10px;
        }
        .student-name {
            font-size: 38px;
            font-weight: bold;
            color: #111827;
            font-family: 'Palatino Linotype', 'Book Antiqua', Palatino, serif;
            border-bottom: 2px solid #e5e7eb;
            display: inline-block;
            padding-bottom: 10px;
            margin-bottom: 30px;
        }
        .cert-text {
            font-size: 16px;
            line-height: 1.6;
            color: #4b5563;
            max-width: 500px;
            margin: 0 auto 20px auto;
        }
        .course-title {
            font-size: 26px;
            font-weight: bold;
            color: #1f2937;
            font-style: italic;
            margin-bottom: 10px;
        }

        /* Official Seal */
        .seal-container {
            margin: 30px 0;
        }
        .seal {
            width: 100px;
            height: 100px;
            border: 3px solid #FF8A00;
            border-radius: 50%;
            display: flex;
            align-items: center;
            justify-content: center;
            margin: 0 auto;
            background: #fff7ed;
            position: relative;
        }
        .seal::after {
            content: '';
            position: absolute;
            inset: 4px;
            border: 1px dashed #FF8A00;
            border-radius: 50%;
        }
        .seal-icon {
            font-size: 40px;
            z-index: 2;
            position: relative;
        }
        .seal-text {
            font-size: 10px;
            font-weight: bold;
            color: #FF8A00;
            text-transform: uppercase;
            letter-spacing: 1px;
            z-index: 2;
            position: absolute;
            bottom: 18px;
        }

        /* Footer Section */
        .footer-section {
            margin-bottom: 40px;
            width: 100%;
        }
        .date-text {
            font-size: 14px;
            color: #4b5563;
            margin-bottom: 40px;
        }
        .signatures {
            display: flex;
            justify-content: center;
            gap: 80px;
            margin-top: 20px;
        }
        .sig-block {
            text-align: center;
            width: 200px;
        }
        .sig-line {
            width: 200px;
            height: 1px;
            background: #111827;
            margin-bottom: 8px;
        }
        .sig-name {
            font-size: 14px;
            font-weight: 600;
            color: #111827;
        }
        .sig-role {
            font-size: 11px;
            color: #6b7280;
            text-transform: uppercase;
        }
        .cert-id {
            margin-top: 40px;
            font-size: 10px;
            color: #9ca3af;
            font-family: monospace;
        }

        /* ── Print Specific Styles ── */
        @media print {
            body {
                background: white;
                padding: 0;
            }
            .print-btn-container {
                display: none !important;
            }
            .certificate-paper {
                box-shadow: none;
                width: 100%;
                height: 100vh;
                min-height: auto;
                padding: 40px;
            }
        }
    </style>
</head>
<body>

    <!-- Print Button (Hidden when printing) -->
    <div class="print-btn-container">
        <button class="print-btn" onclick="window.print()">
            🖨️ Print Certificate
        </button>
    </div>

    <!-- Certificate Document -->
    <div class="certificate-paper">
        <!-- Decorative Borders -->
        <div class="border-ornate"></div>
        <div class="border-inner"></div>
        <div class="corner top-left"></div>
        <div class="corner top-right"></div>
        <div class="corner bottom-left"></div>
        <div class="corner bottom-right"></div>

        <div class="content">
            
            <!-- Header -->
            <div class="header-section">
                <div class="logo-text">E-Learning Platform</div>
                <h1 class="cert-title">Certificate of Achievement</h1>
                <div class="divider-line"></div>
            </div>

            <!-- Body -->
            <div class="body-section">
                <p class="presented-to">This is to certify that</p>
                <h2 class="student-name"><?= htmlspecialchars($certData['name']) ?></h2>
                
                <p class="cert-text">
                    has successfully completed the course requirements and demonstrated a comprehensive understanding of the curriculum.
                </p>

                <p class="presented-to" style="margin-top: 20px;">Awarded for</p>
                <h3 class="course-title">"<?= htmlspecialchars($certData['course_name']) ?>"</h3>

                <!-- Official Seal -->
                <div class="seal-container">
                    <div class="seal">
                        <span class="seal-icon">🏆</span>
                        <span class="seal-text">Approved</span>
                    </div>
                </div>
            </div>

            <!-- Footer -->
            <div class="footer-section">
                <p class="date-text">
                    Date of Issue: <?= $formattedDate ?>
                </p>

                <div class="signatures">
                    <div class="sig-block">
                        <div class="sig-line"></div>
                        <div class="sig-name">Admin Name</div>
                        <div class="sig-role">Platform Director</div>
                    </div>
                    <div class="sig-block">
                        <div class="sig-line"></div>
                        <div class="sig-name">Instructor</div>
                        <div class="sig-role">Course Head</div>
                    </div>
                </div>

                <div class="cert-id">
                    Verification ID: <?= $certificateId ?>
                </div>
            </div>

        </div>
    </div>

</body>
</html>