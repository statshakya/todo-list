<?php
header('Content-Type: application/json');

require_once '../config/database.php';
require_once '../model/tododata.php';

$response = [];

$db = new Database();
$conn = $db->connect();
$todo = new Todo($conn);

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $action = $_POST['action'] ?? '';

    // 1. Add new task
    if ($action === 'add') {
        $title = trim($_POST['title'] ?? '');

        if (strlen($title) < 2) {
            $response = ['status' => 'error', 'message' => 'Task title must be at least 2 characters.'];
        } else {
            $success = $todo->create($title);
            $response = $success
                ? ['status' => 'success', 'message' => '✅ Task added successfully!']
                : ['status' => 'error', 'message' => '❌ Failed to add task. Try again.'];
        }

    // 2. Delete task
    } elseif ($action === 'delete') {
        $id = $_POST['id'] ?? 0;
        $success = $todo->delete($id);
        $response = $success
            ? ['status' => 'success', 'message' => '🗑️ Task deleted']
            : ['status' => 'error', 'message' => '❌ Failed to delete task'];

    // 3. Update status
    } elseif ($action === 'updateStatus') {
        $id = $_POST['id'] ?? 0;
        $status = $_POST['status'] ?? 0;
        $success = $todo->updateStatus($id, $status);
        $response = $success
            ? ['status' => 'success', 'message' => '✔️ Status updated']
            : ['status' => 'error', 'message' => '❌ Failed to update status'];

    }elseif ($action === 'update') {
    $title = trim($_POST['title'] ?? '');
    $id = intval($_POST['todo_id'] ?? 0);

    if ($id <= 0 || strlen($title) < 2) {
        $response = ['status' => 'error', 'message' => '❌ Invalid update request.'];
    } else {
        $success = $todo->update($id, $title);
        $response = $success
            ? ['status' => 'success', 'message' => '✏️ Task updated successfully!']
            : ['status' => 'error', 'message' => '❌ Failed to update task.'];
    }
} else {
        $response = ['status' => 'error', 'message' => '❌ Unknown action.'];
    }
} else {
    $response = ['status' => 'error', 'message' => '❌ Invalid request method.'];
}

echo json_encode($response);
