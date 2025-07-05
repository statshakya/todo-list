<?php
header('Content-Type: application/json');

require_once '../config/database.php';
require_once '../model/tododata.php';
require_once '../model/user.php';

$response = ['status' => 'error', 'message' => 'Unknown error'];

try {
    $db = new Database();
    $conn = $db->connect();
    $todo = new Todo($conn);
    $user = new User($conn); // Initialize User object once at the top

    if ($_SERVER['REQUEST_METHOD'] === 'POST') {
        $action = $_POST['action'] ?? '';

        switch ($action) {
            case 'add':
                $title = trim($_POST['title'] ?? '');
                if (strlen($title) < 2) {
                    $response = ['status' => 'error', 'message' => 'Task title must be at least 2 characters.'];
                } else {
                    $success = $todo->create($title);
                    $response = $success
                        ? ['status' => 'success', 'message' => '✅ Task added successfully!']
                        : ['status' => 'error', 'message' => '❌ Failed to add task. Try again.'];
                }
                break;

            case 'delete':
                $id = $_POST['id'] ?? 0;
                $success = $todo->delete($id);
                $response = $success
                    ? ['status' => 'success', 'message' => '🗑️ Task deleted']
                    : ['status' => 'error', 'message' => '❌ Failed to delete task'];
                break;

            case 'updateStatus':
                $id = $_POST['id'] ?? 0;
                $status = $_POST['status'] ?? 0;
                $success = $todo->updateStatus($id, $status);
                $response = $success
                    ? ['status' => 'success', 'message' => '✔️ Status updated']
                    : ['status' => 'error', 'message' => '❌ Failed to update status'];
                break;

            case 'update':
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
                break;

            case 'login':
                $username = trim($_POST['username'] ?? '');
                $password = $_POST['password'] ?? '';
                if (strlen($username) < 2 || strlen($password) < 2) {
                    $response = ['status' => 'error', 'message' => '❌ Username and Password must be at least 2 characters.'];
                } else {
                    $login = $user->login($username, $password);
                    if ($login) {
                        session_start();
                        $_SESSION['user_id'] = $login->id;
                        $_SESSION['username'] = $login->username;
                        $_SESSION['name'] = $login->name;
                        $response = ['status' => 'success', 'message' => '✔️ Login successful!'];
                    } else {
                        $response = ['status' => 'error', 'message' => '❌ Invalid username or password.'];
                    }
                }
                break;

            case 'register':
                $name = trim($_POST['name'] ?? '');
                $email = trim($_POST['email'] ?? '');
                $username = trim($_POST['username'] ?? '');
                $password = $_POST['password'] ?? '';
                if (strlen($name) < 2 || strlen($username) < 2 || strlen($password) < 4 || !filter_var($email, FILTER_VALIDATE_EMAIL)) {
                    $response = ['status' => 'error', 'message' => '❌ Please fill all fields properly.'];
                } else {
                    if ($user->exists($username, $email)) {
                        $response = ['status' => 'error', 'message' => '❌ Username or Email already taken.'];
                    } else {
                        $success = $user->register($name, $email, $username, $password);
                        $response = $success
                            ? ['status' => 'success', 'message' => '✅ Registered successfully. You can now login.']
                            : ['status' => 'error', 'message' => '❌ Registration failed. Please try again.'];
                    }
                }
                break;

            case 'checkEmail':
                $email = $_POST['email'] ?? '';
                $currentId = $_POST['current_id'] ?? 0;
                $exists = $user->emailExists($email, $currentId);
                $response = ['valid' => !$exists];
                break;

            case 'checkUsername':
                $username = $_POST['username'] ?? '';
                $currentId = $_POST['current_id'] ?? 0;
                $exists = $user->usernameExists($username, $currentId);
                $response = ['valid' => !$exists];
                break;

            case 'updateProfile':
                $id = $_POST['id'] ?? '';
                $name = trim($_POST['name'] ?? '');
                $email = trim($_POST['email'] ?? '');
                $username = trim($_POST['username'] ?? '');
                $password = $_POST['password'] ?? '';
                if ($user->updateProfile($id, $name, $email, $username, $password)) {
                    $response = ['status' => 'success', 'message' => '✅ Profile updated successfully!'];
                } else {
                    $response = ['status' => 'error', 'message' => '❌ Failed to update profile.'];
                }
                break;

            default:
                $response = ['status' => 'error', 'message' => '❌ Unknown action.'];
        }
    } else {
        $response = ['status' => 'error', 'message' => '❌ Invalid request method.'];
    }
} catch (Exception $e) {
    $response = ['status' => 'error', 'message' => 'Server error: ' . $e->getMessage()];
}

echo json_encode($response);