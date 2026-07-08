<?php
session_start();
require_once '../config/db.php';

header('Content-Type: application/json');

if (!isset($_SESSION['user_id'])) {
    echo json_encode(['success' => false, 'message' => 'Unauthorized']);
    exit;
}

 $action = $_POST['action'] ?? $_GET['action'] ?? '';

// Upload directory
 $uploadDir = '../uploads/modules/';
if (!is_dir($uploadDir)) {
    mkdir($uploadDir, 0755, true);
}

switch ($action) {

    case 'get':
        $id = (int)($_GET['id'] ?? 0);
        $stmt = $conn->prepare("SELECT id, course_id, name, price, image FROM modules WHERE id = ?");
        $stmt->bind_param('i', $id);
        $stmt->execute();
        $result = $stmt->get_result();
        if ($row = $result->fetch_assoc()) {
            echo json_encode(['success' => true, 'data' => $row]);
        } else {
            echo json_encode(['success' => false, 'message' => 'Module not found']);
        }
        break;

    case 'create':
        $course_id = (int)($_POST['course_id'] ?? 0);
        $name = trim($_POST['name'] ?? '');
        $price = floatval($_POST['price'] ?? 0);

        if (!$course_id || !$name) {
            echo json_encode(['success' => false, 'message' => 'Course and module name are required.']);
            exit;
        }

        // Handle image upload
        $image = '';
        if (isset($_FILES['image']) && $_FILES['image']['error'] === UPLOAD_ERR_OK) {
            $allowed = ['jpg', 'jpeg', 'png', 'gif', 'webp'];
            $ext = strtolower(pathinfo($_FILES['image']['name'], PATHINFO_EXTENSION));
            if (in_array($ext, $allowed)) {
                $newName = 'module_' . time() . '_' . bin2hex(random_bytes(4)) . '.' . $ext;
                $dest = $uploadDir . $newName;
                if (move_uploaded_file($_FILES['image']['tmp_name'], $dest)) {
                    $image = $newName;
                }
            }
        }

        $stmt = $conn->prepare("INSERT INTO modules (course_id, name, price, image) VALUES (?, ?, ?, ?)");
        $stmt->bind_param('isds', $course_id, $name, $price, $image);
        if ($stmt->execute()) {
            echo json_encode(['success' => true, 'message' => 'Module created successfully.']);
        } else {
            echo json_encode(['success' => false, 'message' => 'Failed to create module.']);
        }
        break;

    case 'update':
        $id = (int)($_POST['id'] ?? 0);
        $course_id = (int)($_POST['course_id'] ?? 0);
        $name = trim($_POST['name'] ?? '');
        $price = floatval($_POST['price'] ?? 0);
        $existing_image = $_POST['existing_image'] ?? '';

        if (!$id || !$course_id || !$name) {
            echo json_encode(['success' => false, 'message' => 'Missing required fields.']);
            exit;
        }

        // Get current image
        $stmt = $conn->prepare("SELECT image FROM modules WHERE id = ?");
        $stmt->bind_param('i', $id);
        $stmt->execute();
        $current = $stmt->get_result()->fetch_assoc();
        $image = $current['image'] ?? '';

        // Handle new image upload
        if (isset($_FILES['image']) && $_FILES['image']['error'] === UPLOAD_ERR_OK) {
            $allowed = ['jpg', 'jpeg', 'png', 'gif', 'webp'];
            $ext = strtolower(pathinfo($_FILES['image']['name'], PATHINFO_EXTENSION));
            if (in_array($ext, $allowed)) {
                // Delete old image
                if ($image && file_exists($uploadDir . $image)) {
                    unlink($uploadDir . $image);
                }
                $newName = 'module_' . time() . '_' . bin2hex(random_bytes(4)) . '.' . $ext;
                $dest = $uploadDir . $newName;
                if (move_uploaded_file($_FILES['image']['tmp_name'], $dest)) {
                    $image = $newName;
                }
            }
        } else {
            // Keep existing image if no new file uploaded
            $image = $existing_image;
        }

        $stmt = $conn->prepare("UPDATE modules SET course_id = ?, name = ?, price = ?, image = ? WHERE id = ?");
        $stmt->bind_param('isdsi', $course_id, $name, $price, $image, $id);
        if ($stmt->execute()) {
            echo json_encode(['success' => true, 'message' => 'Module updated successfully.']);
        } else {
            echo json_encode(['success' => false, 'message' => 'Failed to update module.']);
        }
        break;

    case 'delete':
        $id = (int)($_POST['id'] ?? 0);
        if (!$id) {
            echo json_encode(['success' => false, 'message' => 'Invalid ID.']);
            exit;
        }

        // Get image to delete
        $stmt = $conn->prepare("SELECT image FROM modules WHERE id = ?");
        $stmt->bind_param('i', $id);
        $stmt->execute();
        $row = $stmt->get_result()->fetch_assoc();

        $stmt = $conn->prepare("DELETE FROM modules WHERE id = ?");
        $stmt->bind_param('i', $id);
        if ($stmt->execute()) {
            // Delete image file
            if ($row && $row['image'] && file_exists($uploadDir . $row['image'])) {
                unlink($uploadDir . $row['image']);
            }
            echo json_encode(['success' => true, 'message' => 'Module deleted successfully.']);
        } else {
            echo json_encode(['success' => false, 'message' => 'Failed to delete module.']);
        }
        break;

    default:
        echo json_encode(['success' => false, 'message' => 'Invalid action.']);
}