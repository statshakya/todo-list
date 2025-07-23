<?php
session_start();
require_once '../config/database.php';
require_once '../model/notes.php';

// Check if user is logged in
if (!isset($_SESSION['user_id'])) {
    echo json_encode(['status' => 'error', 'message' => 'Not logged in']);
    exit;
}

$db = new Database();
$conn = $db->connect();
$note = new Note($conn);

$userid = $_SESSION['user_id'];

$action = $_POST['action'] ?? '';

switch ($action) {
    case 'create':
        $title = $_POST['title'] ?? '';
        $content = $_POST['content'] ?? '';
        $success = $note->create($title, $content, $userid);
        echo json_encode(['status' => $success ? 'success' : 'error']);
        break;

    case 'read':
        $notes = $note->getAll($userid);
        echo json_encode(['status' => 'success', 'data' => $notes]);
        break;

    case 'update':
        $id = $_POST['id'] ?? '';
        $title = $_POST['title'] ?? '';
        $content = $_POST['content'] ?? '';
        $success = $note->update($id, $title, $content);
        echo json_encode(['status' => $success ? 'success' : 'error']);
        break;

    case 'delete':
        $id = $_POST['id'] ?? '';
        $success = $note->delete($id);
        echo json_encode(['status' => $success ? 'success' : 'error']);
        break;

    default:
        echo json_encode(['status' => 'error', 'message' => 'Invalid action']);
        break;
}
