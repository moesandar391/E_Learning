<?php
require_once __DIR__ . '/../config/mail.php';

// Minimal SMTP client (no external dependencies, requires the openssl extension)
// Returns true on success, false on failure. All errors are logged via error_log().
function send_mail($to, $subject, $body) {
    if (!filter_var($to, FILTER_VALIDATE_EMAIL)) {
        error_log("[Access Edu Mail] Invalid recipient email: " . print_r($to, true));
        return false;
    }

    $errno = 0;
    $errstr = '';
    $socket = @stream_socket_client(
        'ssl://' . SMTP_HOST . ':' . SMTP_PORT,
        $errno,
        $errstr,
        30,
        STREAM_CLIENT_CONNECT,
        stream_context_create(['ssl' => ['verify_peer' => false, 'verify_peer_name' => false]])
    );
    if (!$socket) {
        error_log("[Access Edu Mail] SMTP connection failed: $errstr ($errno)");
        return false;
    }

    stream_set_timeout($socket, 30);

    $read = function($socket) {
        $resp = '';
        while (($line = fgets($socket, 515)) !== false) {
            $resp .= $line;
            if (isset($line[3]) && $line[3] === ' ') break;
        }
        return $resp;
    };

    $write = function($socket, $cmd) use ($read) {
        fwrite($socket, $cmd . "\r\n");
        return $read($socket);
    };

    try {
        $read($socket);

        $w = $write($socket, 'EHLO ' . gethostname());
        if (strpos($w, '250') !== 0) throw new Exception('EHLO failed: ' . $w);

        $a = $write($socket, 'AUTH LOGIN');
        if (strpos($a, '334') !== 0) throw new Exception('AUTH failed: ' . $a);

        $u = $write($socket, base64_encode(SMTP_USER));
        if (strpos($u, '334') !== 0) throw new Exception('Username rejected: ' . $u);

        $p = $write($socket, base64_encode(SMTP_PASS));
        if (strpos($p, '235') !== 0) throw new Exception('Password rejected: ' . $p);

        $m = $write($socket, 'MAIL FROM:<' . MAIL_FROM . '>');
        if (strpos($m, '250') !== 0) throw new Exception('MAIL FROM failed: ' . $m);

        $r = $write($socket, 'RCPT TO:<' . $to . '>');
        if (strpos($r, '250') !== 0) throw new Exception('RCPT TO failed: ' . $r);

        $d = $write($socket, 'DATA');
        if (strpos($d, '354') !== 0) throw new Exception('DATA failed: ' . $d);

        $headers =
            'From: ' . MAIL_FROM_NAME . ' <' . MAIL_FROM . ">\r\n" .
            'Reply-To: ' . MAIL_FROM . "\r\n" .
            'To: <' . $to . ">\r\n" .
            'Subject: ' . $subject . "\r\n" .
            'MIME-Version: 1.0' . "\r\n" .
            'Content-Type: text/plain; charset=UTF-8' . "\r\n" .
            'Content-Transfer-Encoding: quoted-printable' . "\r\n" .
            'Date: ' . date('r') . "\r\n";

        fwrite($socket, $headers . "\r\n" . quoted_printable_encode($body) . "\r\n.\r\n");
        $final = $read($socket);
        if (strpos($final, '250') !== 0) throw new Exception('Message not accepted: ' . $final);

        $write($socket, 'QUIT');
        fclose($socket);
        return true;
    } catch (Throwable $e) {
        error_log("[Access Edu Mail] SMTP error to " . $to . ": " . $e->getMessage());
        fclose($socket);
        return false;
    }
}