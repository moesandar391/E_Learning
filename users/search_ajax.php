<?php
require_once '../config/db.php';

header('Content-Type: application/json');

$q = isset($_GET['q']) ? trim($_GET['q']) : '';
if (strlen($q) < 1) {
    echo json_encode([]);
    exit;
}

$like = "%" . $q . "%";
$stmt = $conn->prepare("
    SELECT m.id, m.name AS module_name, m.image,
           c.course_name, c.level, c.instructor_name,
           m.price
    FROM modules m
    JOIN courses c ON m.course_id = c.id
    WHERE m.name LIKE ? OR c.course_name LIKE ?
    LIMIT 8
");
$stmt->bind_param("ss", $like, $like);
$stmt->execute();
$results = $stmt->get_result()->fetch_all(MYSQLI_ASSOC);

echo json_encode($results);
