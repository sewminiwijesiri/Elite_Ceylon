<?php
// update_admin_credentials.php - Secure Handler for admin credentials & password updates
header('Content-Type: application/json');

if (session_status() === PHP_SESSION_NONE) {
    session_start();
}

// 1. Authentication check
if (!isset($_SESSION['admin_logged_in']) || $_SESSION['admin_logged_in'] !== true) {
    echo json_encode(['success' => false, 'message' => 'Unauthorized access. Please log in again.']);
    exit;
}

// 2. Request method check
if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
    echo json_encode(['success' => false, 'message' => 'Invalid request method.']);
    exit;
}

// 3. CSRF Protection
$csrf_token = isset($_POST['csrf_token']) ? $_POST['csrf_token'] : '';
if (empty($_SESSION['csrf_token']) || !hash_equals($_SESSION['csrf_token'], $csrf_token)) {
    echo json_encode(['success' => false, 'message' => 'Security token invalid or expired. Please refresh the page and try again.']);
    exit;
}

$admin_id = isset($_SESSION['admin_id']) ? (int)$_SESSION['admin_id'] : 0;
$new_username = isset($_POST['username']) ? trim($_POST['username']) : '';
$email = isset($_POST['email']) ? trim($_POST['email']) : '';
$current_password = isset($_POST['current_password']) ? (string)$_POST['current_password'] : '';
$new_password = isset($_POST['new_password']) ? (string)$_POST['new_password'] : '';
$confirm_password = isset($_POST['confirm_password']) ? (string)$_POST['confirm_password'] : '';

// 4. Validate current password presence
if (empty($current_password)) {
    echo json_encode(['success' => false, 'message' => 'Current password is required.']);
    exit;
}

// 5. Validate username
if (empty($new_username)) {
    echo json_encode(['success' => false, 'message' => 'Username cannot be empty.']);
    exit;
}

if (strlen($new_username) < 3) {
    echo json_encode(['success' => false, 'message' => 'Username must be at least 3 characters long.']);
    exit;
}

require_once 'includes/db_connect.php';

try {
    // Fetch current admin record
    if ($admin_id > 0) {
        $stmt = $pdo->prepare('SELECT id, username, email, password FROM admins WHERE id = ? LIMIT 1');
        $stmt->execute([$admin_id]);
        $admin = $stmt->fetch();
    } else {
        $current_user = $_SESSION['admin_username'] ?? '';
        $stmt = $pdo->prepare('SELECT id, username, email, password FROM admins WHERE username = ? LIMIT 1');
        $stmt->execute([$current_user]);
        $admin = $stmt->fetch();
        if ($admin) {
            $admin_id = (int)$admin['id'];
            $_SESSION['admin_id'] = $admin_id;
        }
    }

    if (!$admin) {
        echo json_encode(['success' => false, 'message' => 'Admin account not found in database.']);
        exit;
    }

    // 6. Verify Current Password against secure hash
    if (!password_verify($current_password, $admin['password'])) {
        echo json_encode(['success' => false, 'message' => 'Current password is incorrect.']);
        exit;
    }

    // 7. Check username uniqueness if changed
    if (strtolower($new_username) !== strtolower($admin['username'])) {
        $check_stmt = $pdo->prepare('SELECT id FROM admins WHERE username = ? AND id != ? LIMIT 1');
        $check_stmt->execute([$new_username, $admin['id']]);
        if ($check_stmt->fetch()) {
            echo json_encode(['success' => false, 'message' => 'That username is already taken. Please choose another.']);
            exit;
        }
    }

    // 8. Check email uniqueness if email provided and changed
    if (!empty($email) && strtolower($email) !== strtolower($admin['email'])) {
        $check_email = $pdo->prepare('SELECT id FROM admins WHERE email = ? AND id != ? LIMIT 1');
        $check_email->execute([$email, $admin['id']]);
        if ($check_email->fetch()) {
            echo json_encode(['success' => false, 'message' => 'That email address is already registered to another account.']);
            exit;
        }
    }

    // 9. Handle password change if new password provided
    $password_hash = $admin['password'];
    $password_changed = false;

    if (!empty($new_password)) {
        // Password matching check
        if ($new_password !== $confirm_password) {
            echo json_encode(['success' => false, 'message' => 'New passwords do not match.']);
            exit;
        }

        // Complexity requirements checks
        $has_min_length = (strlen($new_password) >= 8);
        $has_uppercase  = (bool)preg_match('/[A-Z]/', $new_password);
        $has_lowercase  = (bool)preg_match('/[a-z]/', $new_password);
        $has_number     = (bool)preg_match('/[0-9]/', $new_password);
        $has_special    = (bool)preg_match('/[^a-zA-Z0-9]/', $new_password);

        if (!$has_min_length || !$has_uppercase || !$has_lowercase || !$has_number || !$has_special) {
            echo json_encode([
                'success' => false,
                'message' => 'New password must be at least 8 characters long and contain at least one uppercase letter, one lowercase letter, one number, and one special character.'
            ]);
            exit;
        }

        // Secure password hash
        $password_hash = password_hash($new_password, PASSWORD_DEFAULT);
        $password_changed = true;
    }

    // 10. Update database record
    $update_email = !empty($email) ? $email : $admin['email'];
    $update_stmt = $pdo->prepare('UPDATE admins SET username = ?, email = ?, password = ? WHERE id = ?');
    $update_stmt->execute([$new_username, $update_email, $password_hash, $admin['id']]);

    // 11. Handle response and session flow
    if ($password_changed) {
        // Clear and destroy current session so administrator re-authenticates with new password
        $_SESSION = array();
        if (ini_get("session.use_cookies")) {
            $params = session_get_cookie_params();
            setcookie(session_name(), '', time() - 42000,
                $params["path"], $params["domain"],
                $params["secure"], $params["httponly"]
            );
        }
        session_destroy();

        echo json_encode([
            'success' => true,
            'message' => 'Your password has been changed successfully. Redirecting to login...',
            'password_changed' => true,
            'new_username' => $new_username,
            'redirect_url' => 'index.php?admin_login=1&msg=password_reset'
        ]);
        exit;
    } else {
        // Update session state for profile changes
        $_SESSION['admin_username'] = $new_username;
        $_SESSION['csrf_token'] = bin2hex(random_bytes(32));

        echo json_encode([
            'success' => true,
            'message' => 'Your account details have been updated successfully.',
            'password_changed' => false,
            'new_username' => $new_username,
            'new_email' => $update_email,
            'new_csrf_token' => $_SESSION['csrf_token']
        ]);
        exit;
    }

} catch (PDOException $e) {
    echo json_encode(['success' => false, 'message' => 'A database error occurred. Please try again.']);
}
