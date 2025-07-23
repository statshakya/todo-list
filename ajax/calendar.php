<?php
session_start();
header('Content-Type: application/json');

require_once '../config/database.php';
require_once '../model/calendar.php';

if (!isset($_SESSION['user_id'])) {
    echo json_encode(['status' => 'error', 'message' => 'Not logged in']);
    exit;
}

$db = new Database();
$conn = $db->connect();
$calendar = new Calendar($conn);
$userid = $_SESSION['user_id'];
$action = $_POST['action'] ?? '';

switch ($action) {
    case 'getAll':
        $events = $calendar->getAll($userid);
        echo json_encode(['status' => 'success', 'data' => $events]);
        break;

    case 'create':
        $title = trim($_POST['title'] ?? '');
        $description = trim($_POST['description'] ?? '');
        $event_date = $_POST['event_date'] ?? '';
        if ($title === '' || $event_date === '') {
            echo json_encode(['status' => 'error', 'message' => 'Title and event date required']);
            break;
        }
        $success = $calendar->create($title, $description, $event_date, $userid);
        echo json_encode(['status' => $success ? 'success' : 'error']);
        break;

    case 'update':
        $id = intval($_POST['id'] ?? 0);
        $title = trim($_POST['title'] ?? '');
        $description = trim($_POST['description'] ?? '');
        $event_date = $_POST['event_date'] ?? '';
        if ($id <= 0 || $title === '' || $event_date === '') {
            echo json_encode(['status' => 'error', 'message' => 'Invalid update data']);
            break;
        }
        $success = $calendar->update($id, $title, $description, $event_date);
        echo json_encode(['status' => $success ? 'success' : 'error']);
        break;

    case 'delete':
        $id = intval($_POST['id'] ?? 0);
        if ($id <= 0) {
            echo json_encode(['status' => 'error', 'message' => 'Invalid ID']);
            break;
        }
        $success = $calendar->delete($id);
        echo json_encode(['status' => $success ? 'success' : 'error']);
        break;

    default:
        echo json_encode(['status' => 'error', 'message' => 'Invalid action']);
        break;
}
