<?php
session_start();
require_once '../config/database.php';
require_once '../model/note.php';

// Check if user is logged in
if (!isset($_SESSION['user_id'])) {
    echo json_encode(['status' => 'error', 'message' => 'Not logged in 🔒']);
    exit;
}

$db = new Database();
$conn = $db->connect();
$note = new Note($conn);

$userid = $_SESSION['user_id'];
$action = $_POST['action'] ?? '';

// Helper function to handle file upload
function handleFileUpload($fileInputName = 'file')
{
    if (isset($_FILES[$fileInputName]) && $_FILES[$fileInputName]['error'] === UPLOAD_ERR_OK) {
        $uploadDir = '../uploads/notes/';
        if (!is_dir($uploadDir)) {
            mkdir($uploadDir, 0755, true);
        }

        $filename = time() . '_' . basename($_FILES[$fileInputName]['name']);
        $targetPath = $uploadDir . $filename;

        if (move_uploaded_file($_FILES[$fileInputName]['tmp_name'], $targetPath)) {
            return 'uploads/notes/' . $filename;
        }
    }
    return null;
}

switch ($action) {
    case 'create':
        $title = $_POST['title'] ?? '';
        $content = $_POST['content'] ?? '';

        if (empty($title) || empty($content)) {
            echo json_encode([
                'status' => 'error',
                'message' => 'Title and content are required ✏️'
            ]);
            break;
        }

        try {
            $filePath = handleFileUpload();
            $success = $note->create($title, $content, $userid, $filePath);

            echo json_encode([
                'status' => $success ? 'success' : 'error',
                'message' => $success ? 'Note created 📝' : 'Failed to create note ❌',
                'file' => $filePath
            ]);
        } catch (Exception $e) {
            echo json_encode([
                'status' => 'error',
                'message' => $e->getMessage() . ' ⚠️'
            ]);
        }
        break;

    case 'read':
        $notes = $note->getAll($userid);
        echo json_encode(['status' => 'success', 'data' => $notes]);
        break;

    case 'update':
        $id = $_POST['id'] ?? '';
        $title = $_POST['title'] ?? '';
        $content = $_POST['content'] ?? '';
        $file = $_FILES['file'] ?? null;

        $success = $note->update($id, $title, $content, $file);
        echo json_encode([
            'status' => $success ? 'success' : 'error',
            'message' => $success ? 'Note updated ✏️' : 'Failed to update note ❌'
        ]);
        break;

    case 'delete':
        $id = $_POST['id'] ?? '';

        if (empty($id)) {
            echo json_encode(['status' => 'error', 'message' => 'Missing note ID ❗']);
            exit;
        }

        $success = $note->delete($id);

        echo json_encode([
            'status' => $success ? 'success' : 'error',
            'message' => $success ? 'Note deleted 🗑️' : 'Failed to delete note ❌'
        ]);
        break;

    case 'deletefile':
        $id = $_POST['id'] ?? '';
        $success = $note->deleteFileOnly($id);
        echo json_encode([
            'status' => $success ? 'success' : 'error',
            'message' => $success ? 'File removed 🧹' : 'Failed to remove file ❌'
        ]);
        break;

    default:
        echo json_encode(['status' => 'error', 'message' => 'Invalid action 🚫']);
        break;
}
