<?php
require_once __DIR__ . '/config/database.php'; // correct path to database config

// Initialize DB
$db = new Database();
$conn = $db->connect();

$email = null;

// Handle GET request to show form
if ($_SERVER["REQUEST_METHOD"] === "GET" && isset($_GET["token"])) {
    $token = $_GET["token"];

    // Check token in DB
    $stmt = $conn->prepare("SELECT email FROM password_resets WHERE token = ? AND expires_at > NOW()");
    $stmt->execute([$token]); // ✅ PDO
    $row = $stmt->fetch(PDO::FETCH_ASSOC);

    if ($row) {
        $email = $row["email"];
    } else {
        die("Invalid or expired token.");
    }
}

// Handle POST request to reset password
if ($_SERVER["REQUEST_METHOD"] === "POST") {
    $token = $_POST["token"] ?? '';
    $password = $_POST["password"] ?? '';

    if (strlen($password) < 4) {
        die("Password must be at least 4 characters.");
    }

    $stmt = $conn->prepare("SELECT email FROM password_resets WHERE token = ? AND expires_at > NOW()");
    $stmt->execute([$token]); // ✅ PDO
    $row = $stmt->fetch(PDO::FETCH_ASSOC);

    if ($row) {
        $email = $row["email"];
        $hashedPassword = password_hash($password, PASSWORD_BCRYPT);

        // Update user password
        $update = $conn->prepare("UPDATE tbl_users SET password = ? WHERE email = ?");
        $update->execute([$hashedPassword, $email]); // ✅ PDO

        // Delete token
        $delete = $conn->prepare("DELETE FROM password_resets WHERE email = ?");
        $delete->execute([$email]); // ✅ PDO

        echo "Password reset successful. <a href='login.php'>Login</a>";
        exit;
    } else {
        die("Invalid or expired token.");
    }
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Reset Password | Todo List</title>
    <link rel="stylesheet" href="styles.css"> <!-- reuse your styles -->
</head>
<body>
    <div class="form-box">
        <h2>Reset Password</h2>
        <form method="POST">
            <input type="hidden" name="token" value="<?php echo htmlspecialchars($_GET['token'] ?? ''); ?>">
            <label>New Password:</label><br>
            <input type="password" name="password" placeholder="Enter new password" required><br><br>
            <button type="submit">Reset Password</button>
        </form>
    </div>
</body>
</html>
