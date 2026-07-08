<?php
session_start();
require_once '../config/db.php';

header('Content-Type: application/json');

if (!isset($_SESSION['role']) || $_SESSION['role'] !== 'admin') {
    echo json_encode(['success' => false, 'message' => 'Unauthorized.']);
    exit;
}

if (!isset($_FILES['video']) || $_FILES['video']['error'] !== UPLOAD_ERR_OK) {
    $err = $_FILES['video']['error'] ?? UPLOAD_ERR_NO_FILE;
    $msgs = [
        UPLOAD_ERR_INI_SIZE => 'File too large (server limit).',
        UPLOAD_ERR_FORM_SIZE => 'File too large.',
        UPLOAD_ERR_PARTIAL => 'Partial upload.',
        UPLOAD_ERR_NO_FILE => 'No file selected.',
        UPLOAD_ERR_NO_TMP_DIR => 'Server misconfiguration.',
        UPLOAD_ERR_CANT_WRITE => 'Cannot write to disk.',
        UPLOAD_ERR_EXTENSION => 'Upload blocked.',
    ];
    echo json_encode(['success' => false, 'message' => $msgs[$err] ?? 'Upload error.']);
    exit;
}

 $file = $_FILES['video'];
 $tmpPath = $file['tmp_name'];

 $finfo = new finfo(FILEINFO_MIME_TYPE);
 $mime = $finfo->file($tmpPath);

 $allowed = [
    'video/mp4' => 'mp4',
    'video/webm' => 'webm',
    'video/ogg' => 'ogg',
    'video/quicktime' => 'mov',
];

if (!isset($allowed[$mime])) {
    echo json_encode(['success' => false, 'message' => 'Invalid file type. Allowed: MP4, WebM, OGG, MOV']);
    exit;
}

if ($file['size'] > 100 * 1024 * 1024) {
    echo json_encode(['success' => false, 'message' => 'File too large (max 100MB).']);
    exit;
}

 $uploadDir = __DIR__ . '/uploads/videos/';
if (!is_dir($uploadDir)) {
    mkdir($uploadDir, 0755, true);
}

 $ext = $allowed[$mime];
 $filename = 'lesson_' . time() . '_' . bin2hex(random_bytes(4)) . '.' . $ext;
 $destination = $uploadDir . $filename;

if (move_uploaded_file($tmpPath, $destination)) {
    $path = 'uploads/videos/' . $filename;
    echo json_encode(['success' => true, 'path' => $path]);
} else {
    echo json_encode(['success' => false, 'message' => 'Failed to save file.']);
}