<?php
header('Content-Type: application/json');

require_once '../config/database.php';
require_once '../model/tododata.php';
require_once '../model/user.php';

session_start();

$response = ['status' => 'error', 'message' => 'Unknown error occurred.'];

try {
    $db = new Database();
    $conn = $db->connect();
    $todo = new Todo($conn);
    $user = new User($conn);

    

    if ($_SERVER['REQUEST_METHOD'] === 'POST') {
        $action = $_POST['action'] ?? '';

        switch ($action) {

            // ✅ Add New Task
            case 'add':
                $title = trim($_POST['title'] ?? '');
                $userid = $_SESSION['user_id'] ?? null;

                if (!$userid) {
                    $response = ['status' => 'error', 'message' => 'User not logged in.'];
                } elseif (strlen($title) < 2) {
                    $response = ['status' => 'error', 'message' => 'Task title must be at least 2 characters.'];
                } else {
                    $success = $todo->create($title, $userid);
                    $response = $success
                        ? ['status' => 'success', 'message' => '✅ Task added successfully!']
                        : ['status' => 'error', 'message' => '❌ Failed to add task.'];
                }
                break;

            // 🗑️ Delete Task
            case 'delete':
                $id = $_POST['id'] ?? 0;
                $success = $todo->delete($id);
                $response = $success
                    ? ['status' => 'success', 'message' => '🗑️ Task deleted.']
                    : ['status' => 'error', 'message' => '❌ Failed to delete task.'];
                break;

            // 🔁 Update Task Status
            case 'updateStatus':
                $id = $_POST['id'] ?? 0;
                $status = $_POST['status'] ?? 0;
                $success = $todo->updateStatus($id, $status);
                $response = $success
                    ? ['status' => 'success', 'message' => '✔️ Status updated.']
                    : ['status' => 'error', 'message' => '❌ Failed to update status.'];
                break;

            // ✏️ Update Task Title
            case 'update':
                $title = trim($_POST['title'] ?? '');
                $id = intval($_POST['todo_id'] ?? 0);

                if ($id <= 0 || strlen($title) < 2) {
                    $response = ['status' => 'error', 'message' => '❌ Invalid task update request.'];
                } else {
                    $success = $todo->update($id, $title);
                    $response = $success
                        ? ['status' => 'success', 'message' => '✏️ Task updated successfully!']
                        : ['status' => 'error', 'message' => '❌ Failed to update task.'];
                }
                break;

            // 🔐 Login
            case 'login':
                $username = trim($_POST['username'] ?? '');
                $password = $_POST['password'] ?? '';

                if (strlen($username) < 2 || strlen($password) < 2) {
                    $response = ['status' => 'error', 'message' => '❌ Username and password must be at least 2 characters.'];
                } else {
                    $login = $user->login($username, $password);
                    if ($login) {
                        $_SESSION['user_id'] = $login->id;
                        $_SESSION['username'] = $login->username;
                        $_SESSION['name'] = $login->name;

                        $response = ['status' => 'success', 'message' => '✔️ Login successful!'];
                    } else {
                        $response = ['status' => 'error', 'message' => '❌ Invalid username or password.'];
                    }
                }
                break;

            // 🧾 Registration
            case 'register':
                $name = trim($_POST['name'] ?? '');
                $email = trim($_POST['email'] ?? '');
                $username = trim($_POST['username'] ?? '');
                $password = $_POST['password'] ?? '';

                if (
                    strlen($name) < 2 || strlen($username) < 2 ||
                    strlen($password) < 4 || !filter_var($email, FILTER_VALIDATE_EMAIL)
                ) {
                    $response = ['status' => 'error', 'message' => '❌ Please fill all fields properly.'];
                } elseif ($user->exists($username, $email)) {
                    $response = ['status' => 'error', 'message' => '❌ Username or Email already taken.'];
                } else {
                    $success = $user->register($name, $email, $username, $password);
                    $response = $success
                        ? ['status' => 'success', 'message' => '✅ Registered successfully. You can now log in.']
                        : ['status' => 'error', 'message' => '❌ Registration failed.'];
                }
                break;

            // 🔍 Check Email (AJAX validation)
            case 'checkEmail':
                $email = $_POST['email'] ?? '';
                $currentId = $_POST['current_id'] ?? 0;
                $exists = $user->emailExists($email, $currentId);
                $response = ['valid' => !$exists];
                break;

            // 🔍 Check Username (AJAX validation)
            case 'checkUsername':
                $username = $_POST['username'] ?? '';
                $currentId = $_POST['current_id'] ?? 0;
                $exists = $user->usernameExists($username, $currentId);
                $response = ['valid' => !$exists];
                break;

            // 👤 Update Profile
            case 'updateProfile':
                $id = $_POST['id'] ?? '';
                $name = trim($_POST['name'] ?? '');
                $email = trim($_POST['email'] ?? '');
                $username = trim($_POST['username'] ?? '');
                $password = trim($_POST['password'] ?? '');

                if (empty($password)) {
                    $success = $user->updateProfileWithoutPassword($id, $name, $email, $username);
                } else {
                    $success = $user->updateProfile($id, $name, $email, $username, $password);
                }

                if ($success) {
                    $_SESSION['username'] = $username;
                    $_SESSION['email'] = $email;
                    $_SESSION['name'] = $name;
                    $response = ['status' => 'success', 'message' => '✅ Profile updated successfully!'];
                } else {
                    $response = ['status' => 'error', 'message' => '❌ Failed to update profile.'];
                }
                break;

            default:
                $response = ['status' => 'error', 'message' => '❌ Unknown action.'];
                break;
        }
    } else {
        $response = ['status' => 'error', 'message' => '❌ Invalid request method.'];
    }
} catch (Exception $e) {
    $response = ['status' => 'error', 'message' => '⚠️ Server error: ' . $e->getMessage()];
}

echo json_encode($response);
