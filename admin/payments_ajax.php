<?php
session_start();
require_once '../config/db.php';
require_once '../includes/notification_helper.php';
require_once '../includes/admin_notification_helper.php';
require_once '../includes/mailer.php';

header('Content-Type: application/json');

if (!isset($_SESSION['role']) || $_SESSION['role'] !== 'admin') {
    echo json_encode(['success' => false, 'message' => 'Unauthorized.']);
    exit;
}

 $action = $_POST['action'] ?? '';

// Send the JSON reply to the browser and close the connection immediately,
// so the admin refresh never waits for slow work (e.g. SMTP email).
function reply_and_close($payload) {
    ignore_user_abort(true);
    set_time_limit(65);
    session_write_close();                // free the session lock during background email

    @apache_setenv('no-gzip', '1');       // stop mod_deflate from buffering the response
    ini_set('zlib.output_compression', 'Off');

    while (ob_get_level() > 0) {          // drop any outer output buffers
        ob_end_clean();
    }

    ob_start();
    echo json_encode($payload);
    $size = ob_get_length();

    header('Content-Type: application/json');
    header('Content-Length: ' . $size);
    header('Connection: close');
    header('Cache-Control: no-store, must-revalidate');

    ob_end_flush();
    flush();

    if (function_exists('fastcgi_finish_request')) {
        fastcgi_finish_request();
    }
}

if ($action === 'confirm' || $action === 'reject') {
    $enroll_id = intval($_POST['id'] ?? 0);
    if (!$enroll_id) {
        echo json_encode(['success' => false, 'message' => 'Invalid enrollment ID.']);
        exit;
    }

    $enroll = $conn->query("SELECT e.user_id, e.status AS enroll_status, u.name AS user_name, u.email AS user_email, m.name AS module_name, c.course_name 
        FROM enrollments e
        JOIN users u ON e.user_id = u.id
        JOIN modules m ON e.module_id = m.id
        JOIN courses c ON m.course_id = c.id
        WHERE e.id = $enroll_id")->fetch_assoc();

    if (!$enroll) {
        echo json_encode(['success' => false, 'message' => 'Enrollment not found.']);
        exit;
    }

    $currentStatus = strtolower($enroll['enroll_status'] ?? 'pending');

    if ($action === 'confirm') {
        // Duplicate prevention: a confirmed enrollment must not be notified/emailed again
        if ($currentStatus === 'confirmed') {
            echo json_encode(['success' => true, 'message' => 'Enrollment is already confirmed.']);
            exit;
        }

        $conn->query("UPDATE enrollments SET status = 'confirmed' WHERE id = $enroll_id");
        $mod_id = $conn->query("SELECT module_id FROM enrollments WHERE id = $enroll_id")->fetch_row()[0];
        create_notification(
            $enroll['user_id'],
            'Enrollment Successful! You can now access "' . $enroll['module_name'] . '" lessons.',
            'my_learning.php',
            'enrollment'
        );

        // Approval email sent after status update + website notification (email failure must not block the flow)
        $mailSubject = 'Enrollment Successful - Access Edu';
        $mailBody = "Dear " . $enroll['user_name'] . ",\n\n"
            . "Congratulations! Your enrollment for \"" . $enroll['module_name'] . "\" (" . $enroll['course_name'] . ") has been approved.\n\n"
            . "You can now access your lessons by logging in to your account and going to the My dashboard section"
            // . "http://" . $_SERVER['HTTP_HOST'] . "/E_Learning/users/my_learning.php\n\n"
            . "Happy learning!\nAccess Edu Team";

        // Reply to the admin first so the page refreshes instantly; email runs afterwards.
        reply_and_close(['success' => true, 'message' => 'Enrollment confirmed.']);
        send_mail($enroll['user_email'], $mailSubject, $mailBody);
        exit;
    } else {
        // Duplicate prevention: a rejected enrollment must not be notified/emailed again
        if ($currentStatus === 'rejected') {
            echo json_encode(['success' => true, 'message' => 'Enrollment is already rejected.']);
            exit;
        }

        $reason = trim($_POST['reason'] ?? '');
        $conn->query("UPDATE enrollments SET status = 'rejected' WHERE id = $enroll_id");

        if ($reason) {
            $message = 'Your enrollment in "' . $enroll['module_name'] . '" was rejected: ' . $reason . '. Please contact support.';
        } else {
            $message = 'Your enrollment in "' . $enroll['module_name'] . '" was rejected. Please contact support.';
        }

        create_notification(
            $enroll['user_id'],
            $message,
            'contact.php?enrollment_id=' . $enroll_id,
            'enrollment'
        );

        // Rejection email sent after status update + website notification (email failure must not block the flow)
        $mailSubject = 'Enrollment Rejected - Access Edu';
        $mailBody = "Dear " . $enroll['user_name'] . ",\n\n"
            . "We regret to inform you that your enrollment for \"" . $enroll['module_name'] . "\" (" . $enroll['course_name'] . ") has been rejected.\n\n";
        if ($reason) {
            $mailBody .= "Reason: " . $reason . "\n\n";
        }
        $mailBody .= "If you have any questions, please contact our support team.\n\nAccess Edu Team";

        // Reply to the admin first so the page refreshes instantly; email runs afterwards.
        reply_and_close(['success' => true, 'message' => 'Enrollment rejected.']);
        send_mail($enroll['user_email'], $mailSubject, $mailBody);
        exit;
    }
    exit;
}

echo json_encode(['success' => false, 'message' => 'Unknown action.']);