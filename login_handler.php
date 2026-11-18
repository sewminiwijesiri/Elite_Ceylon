<?php
// Secure AJAX handler for admin login authentication
header('Content-Type: application/json');

if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
    echo json_encode(['success' => false, 'message' => 'Invalid request method.']);
    exit;
}

$username = isset($_POST['username']) ? trim($_POST['username']) : '';
$password = isset($_POST['password']) ? trim($_POST['password']) : '';

if (empty($username) || empty($password)) {
    echo json_encode(['success' => false, 'message' => 'Please fill in all fields.']);
    exit;
}

require_once 'includes/db_connect.php';

try {
    // Check if the user exists
    $stmt = $pdo->prepare('SELECT * FROM admins WHERE username = ? LIMIT 1');
    $stmt->execute([$username]);
    $admin = $stmt->fetch();

    if ($admin && password_verify($password, $admin['password'])) {
        // Successful login, set up session variables
        if (session_status() === PHP_SESSION_NONE) {
            session_start();
        }
        $_SESSION['admin_logged_in'] = true;
        $_SESSION['admin_id'] = $admin['id'];
        $_SESSION['admin_username'] = $admin['username'];

        // Update last login timestamp
        $update_stmt = $pdo->prepare('UPDATE admins SET last_login = CURRENT_TIMESTAMP WHERE id = ?');
        $update_stmt->execute([$admin['id']]);

        echo json_encode(['success' => true, 'message' => 'Login successful! Redirecting...']);
    } else {
        echo json_encode(['success' => false, 'message' => 'Incorrect username or password.']);
    }
} catch (PDOException $e) {
    echo json_encode(['success' => false, 'message' => 'Database error occurred. Please try again later.']);
}
