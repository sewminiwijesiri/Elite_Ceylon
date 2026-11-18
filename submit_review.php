<?php
// submit_review.php
header('Content-Type: application/json');

if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
    echo json_encode(['success' => false, 'message' => 'Invalid request method.']);
    exit;
}

$name = isset($_POST['author_name']) ? trim($_POST['author_name']) : '';
$info = isset($_POST['author_info']) ? trim($_POST['author_info']) : '';
$rating = isset($_POST['rating']) ? intval($_POST['rating']) : 5;
$text = isset($_POST['review_text']) ? trim($_POST['review_text']) : '';
$tour_package = isset($_POST['tour_package']) ? trim($_POST['tour_package']) : '';

if (empty($tour_package)) {
    $tour_package = null;
}

if (empty($name) || empty($info) || empty($text)) {
    echo json_encode(['success' => false, 'message' => 'Please fill in all fields.']);
    exit;
}

if ($rating < 1 || $rating > 5) {
    echo json_encode(['success' => false, 'message' => 'Invalid rating value.']);
    exit;
}

require_once 'includes/db_connect.php';

try {
    $stmt = $pdo->prepare('INSERT INTO reviews (author_name, author_info, rating, review_text, tour_package, approved) VALUES (?, ?, ?, ?, ?, 0)');
    $stmt->execute([$name, $info, $rating, $text, $tour_package]);
    echo json_encode(['success' => true, 'message' => 'Thank you! Your review has been submitted for admin approval.']);
} catch (PDOException $e) {
    echo json_encode(['success' => false, 'message' => 'Database error occurred. Please try again.']);
}
