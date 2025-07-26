<?php
header('Content-Type: application/json');

require_once '../config/database.php';
require_once '../model/calendar.php';
require_once '../login_verify.php';

$response = ['status' => 'error', 'message' => 'Unknown error occurred.'];

try {
    $db = new Database();
    $conn = $db->connect();
    $calendar = new Calendar($conn);
    $userid = $_SESSION['user_id'] ?? null;

    if (!$userid) {
        throw new Exception('User not logged in');
    }

    if ($_SERVER['REQUEST_METHOD'] === 'POST') {
        $action = $_POST['action'] ?? '';
        
        switch ($action) {
            case 'add':
                $title = trim($_POST['title'] ?? '');
                $event_date = trim($_POST['event_date'] ?? '');
                
                if (empty($title)) {
                    throw new Exception('Title is required');
                }
                if (empty($event_date)) {
                    throw new Exception('Date is required');
                }
                
                if ($calendar->create($title, $event_date, $userid)) {
                    $response = ['status' => 'success', 'message' => 'Event added successfully!'];
                } else {
                    throw new Exception('Failed to add event');
                }
                break;
                
            case 'update':
                $id = intval($_POST['id'] ?? 0);
                $title = trim($_POST['title'] ?? '');
                $event_date = trim($_POST['event_date'] ?? '');
                
                if ($id <= 0) {
                    throw new Exception('Invalid event ID');
                }
                if (empty($title)) {
                    throw new Exception('Title is required');
                }
                if (empty($event_date)) {
                    throw new Exception('Date is required');
                }
                
                if ($calendar->update($id, $title, $event_date)) {
                    $response = ['status' => 'success', 'message' => 'Event updated successfully!'];
                } else {
                    throw new Exception('Failed to update event');
                }
                break;
                
            case 'delete':
                $id = intval($_POST['id'] ?? 0);
                
                if ($id <= 0) {
                    throw new Exception('Invalid event ID');
                }
                
                if ($calendar->delete($id)) {
                    $response = ['status' => 'success', 'message' => 'Event deleted successfully!'];
                } else {
                    throw new Exception('Failed to delete event');
                }
                break;
                
            default:
                throw new Exception('Invalid action');
        }
    } else {
        throw new Exception('Invalid request method');
    }
} catch (Exception $e) {
    $response = ['status' => 'error', 'message' => $e->getMessage()];
}

echo json_encode($response);