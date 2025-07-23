<?php
session_start();
header('Content-Type: application/json');

require_once '../config/database.php';

if (!isset($_SESSION['user_id'])) {
    echo json_encode(['status' => 'error', 'message' => 'Not logged in']);
    exit;
}

$db = new Database();
$conn = $db->connect();

$userid = $_SESSION['user_id'];

$uploadDir = '../uploads/'; // Make sure this folder exists and is writable

// Allowed file types (extensions)
$allowedExtensions = ['jpg', 'jpeg', 'png', 'gif', 'pdf', 'doc', 'docx', 'txt'];

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    if (!isset($_FILES['file'])) {
        echo json_encode(['status' => 'error', 'message' => 'No file uploaded']);
        exit;
    }

    $file = $_FILES['file'];
    $fileName = basename($file['name']);
    $fileTmp = $file['tmp_name'];
    $fileSize = $file['size'];
    $fileError = $file['error'];

    $fileExt = strtolower(pathinfo($fileName, PATHINFO_EXTENSION));

    // Validate file extension
    if (!in_array($fileExt, $allowedExtensions)) {
        echo json_encode(['status' => 'error', 'message' => 'File type not allowed']);
        exit;
    }

    if ($fileError !== 0) {
        echo json_encode(['status' => 'error', 'message' => 'File upload error']);
        exit;
    }

    // Optional: Limit file size (e.g., 5MB)
    if ($fileSize > 5 * 1024 * 1024) {
        echo json_encode(['status' => 'error', 'message' => 'File size exceeds limit']);
        exit;
    }

    // Create unique file name to avoid overwrites
    $newFileName = uniqid('file_', true) . '.' . $fileExt;
    $destination = $uploadDir . $newFileName;

    if (move_uploaded_file($fileTmp, $destination)) {
        // You can save file info to the database here if you want

        echo json_encode(['status' => 'success', 'message' => 'File uploaded successfully', 'file' => $newFileName]);
    } else {
        echo json_encode(['status' => 'error', 'message' => 'Failed to move uploaded file']);
    }
} else {
    echo json_encode(['status' => 'error', 'message' => 'Invalid request method']);
}
