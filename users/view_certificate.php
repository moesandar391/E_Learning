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
        :root {
            --navy: #131f38;
            --navy-deep: #0d1628;
            --gold: #c9a227;
            --gold-light: #e6d49a;
            --ink: #17233c;
            --muted: #66708a;
        }

        * { box-sizing: border-box; margin: 0; padding: 0; }

        body {
            background: #eceef2;
            font-family: 'Segoe UI', Helvetica, Arial, sans-serif;
            display: flex;
            flex-direction: column;
            align-items: center;
            justify-content: center;
            min-height: 100vh;
            padding: 40px 20px;
            color: var(--ink);
        }

        .print-btn-container { margin-bottom: 28px; }
        .print-btn {
            background: linear-gradient(135deg, #17233c, #131f38);
            color: #fff;
            border: 1px solid rgba(201,162,39,.55);
            padding: 13px 34px;
            font-size: 15px;
            font-weight: 600;
            letter-spacing: .5px;
            border-radius: 999px;
            cursor: pointer;
            box-shadow: 0 10px 24px rgba(19,31,56,.25);
            transition: transform .2s ease, box-shadow .2s ease;
        }
        .print-btn:hover {
            transform: translateY(-2px);
            box-shadow: 0 14px 30px rgba(19,31,56,.32);
        }

        /* ── Certificate paper ── */
        .certificate-paper {
            background: linear-gradient(160deg, #ffffff 0%, #faf9f5 100%);
            width: 900px;
            max-width: 100%;
            position: relative;
            box-shadow: 0 30px 60px rgba(13,22,40,.18);
            overflow: hidden;
            color: var(--ink);
        }

        /* Left brand band */
        .band {
            position: absolute;
            top: 0; left: 0; bottom: 0;
            width: 190px;
            background:
                radial-gradient(circle at 50% 42%, rgba(201,162,39,.16), transparent 60%),
                linear-gradient(180deg, var(--navy-deep) 0%, var(--navy) 100%);
            text-align: center;
        }
        .band::after {
            content: '';
            position: absolute;
            top: 0; right: -3px; bottom: 0;
            width: 3px;
            background: linear-gradient(180deg, var(--gold), #f0dc9a, var(--gold));
        }
        .site-logo {
            margin: 48px auto 0;
            width: 100px; height: 100px;
            object-fit: contain;
        }
        .brand-name {
            margin-top: 20px;
            color: #fff;
            font-weight: 800;
            font-size: 17px;
            line-height: 1.45;
            letter-spacing: 4px;
        }
        .brand-sub {
            margin-top: 8px;
            color: #8fa0c2;
            font-size: 9px;
            letter-spacing: 3px;
        }
        .brand-rule {
            width: 70px; height: 2px;
            background: var(--gold);
            margin: 14px auto 0;
        }

        .side-foot {
            position: absolute;
            bottom: 18px; left: 0; right: 0;
            color: #7688ad;
            font-size: 8.5px;
            letter-spacing: 2.5px;
        }

        /* Main area */
        .main {
            margin-left: 190px;
            padding: 46px 52px 34px;
            text-align: center;
            position: relative;
        }
        .frame {
            pointer-events: none;
            position: absolute;
            inset: 14px;
            border: 1.5px solid var(--gold);
            outline: 1px solid var(--gold-light);
            outline-offset: 4px;
        }
        .corner {
            position: absolute;
            width: 34px; height: 34px;
            z-index: 1;
        }
        .corner-tl { top: 14px; left: 14px; border-top: 4px solid var(--ink); border-left: 4px solid var(--ink); }
        .corner-tr { top: 14px; right: 14px; border-top: 4px solid var(--ink); border-right: 4px solid var(--ink); }
        .corner-bl { bottom: 14px; left: 14px; border-bottom: 4px solid var(--ink); border-left: 4px solid var(--ink); }
        .corner-br { bottom: 14px; right: 14px; border-bottom: 4px solid var(--ink); border-right: 4px solid var(--ink); }

        .inner { position: relative; z-index: 2; }

        .kicker {
            font-size: 11px;
            font-weight: 700;
            letter-spacing: 6px;
            color: #a3841f;
            text-transform: uppercase;
        }
        .cert-title {
            margin-top: 10px;
            font-size: 44px;
            font-weight: 800;
            letter-spacing: .5px;
            color: var(--ink);
        }
        .title-rule {
            width: 110px; height: 3px;
            background: linear-gradient(to right, transparent, var(--gold), transparent);
            margin: 18px auto 0;
        }

        .presented {
            margin-top: 26px;
            font-size: 12px;
            letter-spacing: 3px;
            text-transform: uppercase;
            color: #8b95ac;
        }
        .student-name {
            margin-top: 12px;
            font-size: 38px;
            font-weight: 800;
            color: var(--ink);
        }
        .name-rule {
            width: 260px; height: 2px;
            background: var(--gold);
            margin: 10px auto 0;
        }

        .body-text {
            margin-top: 24px;
            font-size: 14.5px;
            color: #4a5670;
            max-width: 520px;
            margin-left: auto;
            margin-right: auto;
        }
        .module-name {
            margin-top: 8px;
            font-size: 23px;
            font-weight: 700;
            color: #a3841f;
        }
        .course-info { margin-top: 8px; font-size: 13px; color: var(--muted); }

        .meta {
            display: flex;
            justify-content: center;
            gap: 54px;
            margin-top: 30px;
        }
        .meta-item { min-width: 130px; }
        .meta-label {
            font-size: 9.5px;
            letter-spacing: 1.5px;
            text-transform: uppercase;
            color: #99a2b6;
        }
        .meta-value { margin-top: 5px; font-size: 14px; font-weight: 700; color: var(--ink); }
        .meta-line { width: 62%; height: 1px; background: var(--gold-light); margin: 6px auto 0; }

        .signatures {
            display: flex;
            justify-content: center;
            gap: 90px;
            margin-top: 38px;
        }
        .sig-block { width: 200px; text-align: center; }
        .sig-line { height: 1.5px; background: #3a4763; }
        .sig-name { margin-top: 8px; font-size: 13.5px; font-weight: 700; color: var(--ink); }
        .sig-role {
            margin-top: 3px;
            font-size: 9.5px;
            letter-spacing: 1.5px;
            text-transform: uppercase;
            color: #8b95ac;
        }

        .foot {
            margin-top: 26px;
            font-size: 10px;
            font-style: italic;
            color: #9aa3b6;
        }
        .cert-id { font-style: normal; font-family: monospace; }

        @media print {
            body { background: #fff; padding: 0; }
            .print-btn-container { display: none !important; }
            .certificate-paper {
                box-shadow: none;
                width: 100%;
                min-height: 100vh;
            }
        }
        @media (max-width: 760px) {
            .band { position: static; width: auto; padding: 32px 20px; }
            .site-logo { margin-top: 0; }
            .side-foot { position: static; transform: none; margin-top: 16px; }
            .main { margin-left: 0; padding: 32px 20px; }
            .signatures { gap: 30px; flex-wrap: wrap; }
            .meta { flex-wrap: wrap; gap: 22px; }
        }
    </style>
</head>
<body>

    <!-- Print Button (Hidden when printing) -->
    <div class="print-btn-container">
        <button class="print-btn" onclick="window.print()">Print Certificate</button>
    </div>

    <!-- Certificate Document -->
    <div class="certificate-paper">

        <!-- Left brand band -->
        <div class="band">
            <img src="../assets/Logo 3.png" alt="Logo" class="site-logo">
            <div class="brand-name">ENGLISH<br>ACADEMY</div>
            <div class="brand-sub">LEARN &middot; GROW &middot; SUCCEED</div>
            <div class="brand-rule"></div>
            <div class="side-foot">EST. EXCELLENCE IN EDUCATION</div>
        </div>

        <!-- Main certificate area -->
        <div class="main">
            <div class="frame"></div>
            <div class="corner corner-tl"></div>
            <div class="corner corner-tr"></div>
            <div class="corner corner-bl"></div>
            <div class="corner corner-br"></div>

            <div class="inner">
                <div class="kicker">English Academy</div>
                <h1 class="cert-title">Certificate of Completion</h1>
                <div class="title-rule"></div>

                <p class="presented">This certificate is proudly presented to</p>
                <h2 class="student-name"><?= htmlspecialchars($certData['name']) ?></h2>
                <div class="name-rule"></div>

                <p class="body-text">
                    for successfully completing all lessons and demonstrating outstanding
                    understanding of the course requirements.
                </p>
                <h3 class="module-name">&ldquo;<?= htmlspecialchars($certData['course_name']) ?>&rdquo;</h3>
                <p class="course-info">Awarded on <?= $formattedDate ?></p>

                <div class="meta">
                    <div class="meta-item">
                        <div class="meta-label">Date of Issue</div>
                        <div class="meta-value"><?= $formattedDate ?></div>
                        <div class="meta-line"></div>
                    </div>
                    <div class="meta-item">
                        <div class="meta-label">Certificate ID</div>
                        <div class="meta-value"><?= $certificateId ?></div>
                        <div class="meta-line"></div>
                    </div>
                </div>

                <div class="signatures">
                    <div class="sig-block">
                        <div class="sig-line"></div>
                        <div class="sig-name">English Academy</div>
                        <div class="sig-role">Platform Director</div>
                    </div>
                    <div class="sig-block">
                        <div class="sig-line"></div>
                        <div class="sig-name">Course Instructor</div>
                        <div class="sig-role">Academic Team</div>
                    </div>
                </div>

                <p class="foot">
                    This certificate is issued by English Academy &nbsp;&middot;&nbsp;
                    <span class="cert-id">Verify with ID: <?= $certificateId ?></span>
                </p>
            </div>
        </div>
    </div>

</body>
</html>
