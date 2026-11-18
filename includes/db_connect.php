<?php
// Database configuration variables
$db_host = 'localhost';
$db_name = 'elite_ceylon_db';
$db_user = 'root';
$db_pass = ''; // Default XAMPP MySQL password is empty

try {
    // Establish a secure PDO connection with security enhancements
    $pdo = new PDO(
        "mysql:host={$db_host};dbname={$db_name};charset=utf8mb4",
        $db_user,
        $db_pass,
        [
            PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION,
            PDO::ATTR_DEFAULT_FETCH_MODE => PDO::FETCH_ASSOC,
            PDO::ATTR_EMULATE_PREPARES => false,
        ]
    );
} catch (PDOException $e) {
    // In production, log the error message privately instead of printing details
    die("Database Connection Failed: " . $e->getMessage());
}
