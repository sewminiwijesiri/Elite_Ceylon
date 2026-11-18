<?php
// moderate_review.php
session_start();
header('Content-Type: application/json');

if (!isset($_SESSION['admin_logged_in']) || $_SESSION['admin_logged_in'] !== true) {
    echo json_encode(['success' => false, 'message' => 'Unauthorized access.']);
    exit;
}

if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
    echo json_encode(['success' => false, 'message' => 'Invalid request method.']);
    exit;
}

$id = isset($_POST['id']) ? intval($_POST['id']) : 0;
$action = isset($_POST['action']) ? trim($_POST['action']) : '';

$valid_actions = ['approve', 'unapprove', 'delete', 'bulk_approve', 'add'];
if (!in_array($action, $valid_actions)) {
    echo json_encode(['success' => false, 'message' => 'Invalid action parameter.']);
    exit;
}

if (in_array($action, ['approve', 'unapprove', 'delete']) && $id <= 0) {
    echo json_encode(['success' => false, 'message' => 'Invalid review ID.']);
    exit;
}

require_once 'includes/db_connect.php';

try {
    if ($action === 'approve') {
        $stmt = $pdo->prepare('UPDATE reviews SET approved = 1 WHERE id = ?');
        $stmt->execute([$id]);
        echo json_encode(['success' => true, 'message' => 'Review successfully approved and published!']);
    } else if ($action === 'unapprove') {
        $stmt = $pdo->prepare('UPDATE reviews SET approved = 0 WHERE id = ?');
        $stmt->execute([$id]);
        echo json_encode(['success' => true, 'message' => 'Review moved back to pending moderation.']);
    } else if ($action === 'delete') {
        $stmt = $pdo->prepare('DELETE FROM reviews WHERE id = ?');
        $stmt->execute([$id]);
        echo json_encode(['success' => true, 'message' => 'Review permanently deleted.']);
    } else if ($action === 'bulk_approve') {
        $stmt = $pdo->query('UPDATE reviews SET approved = 1 WHERE approved = 0');
        $affected = $stmt->rowCount();
        echo json_encode(['success' => true, 'message' => "Successfully approved {$affected} pending reviews!"]);
    } else if ($action === 'add') {
        $author_name = isset($_POST['author_name']) ? trim($_POST['author_name']) : '';
        $author_info = isset($_POST['author_info']) ? trim($_POST['author_info']) : '';
        $tour_package = isset($_POST['tour_package']) ? trim($_POST['tour_package']) : null;
        $rating = isset($_POST['rating']) ? intval($_POST['rating']) : 5;
        $review_text = isset($_POST['review_text']) ? trim($_POST['review_text']) : '';
        $approved = isset($_POST['approved']) ? intval($_POST['approved']) : 1;

        if (empty($author_name) || empty($author_info) || empty($review_text)) {
            echo json_encode(['success' => false, 'message' => 'Please provide Author Name, Country/Info, and Review Text.']);
            exit;
        }

        if ($rating < 1 || $rating > 5) {
            $rating = 5;
        }

        $stmt = $pdo->prepare('INSERT INTO reviews (author_name, author_info, rating, review_text, tour_package, approved, created_at) VALUES (?, ?, ?, ?, ?, ?, NOW())');
        $stmt->execute([$author_name, $author_info, $rating, $review_text, $tour_package ?: null, $approved]);
        echo json_encode(['success' => true, 'message' => 'New review created successfully!']);
    }
} catch (PDOException $e) {
    echo json_encode(['success' => false, 'message' => 'Database error occurred: ' . $e->getMessage()]);
}
