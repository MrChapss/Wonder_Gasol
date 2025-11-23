<?php
/**
 * Database Configuration File
 * This file handles the database connection using PDO
 * Beginner-friendly with error handling
 */

// Database credentials
define('DB_HOST', 'localhost');
define('DB_NAME', 'wonder_gasol_db');
define('DB_USER', 'root');
define('DB_PASS', '');

/**
 * Create database connection using PDO
 * PDO is more secure and supports prepared statements
 */
function getDatabaseConnection() {
    try {
        // Create PDO connection with error mode set to exception
        $dsn = "mysql:host=" . DB_HOST . ";dbname=" . DB_NAME . ";charset=utf8mb4";
        $options = [
            PDO::ATTR_ERRMODE            => PDO::ERRMODE_EXCEPTION,
            PDO::ATTR_DEFAULT_FETCH_MODE => PDO::FETCH_ASSOC,
            PDO::ATTR_EMULATE_PREPARES   => false,
        ];
        
        $pdo = new PDO($dsn, DB_USER, DB_PASS, $options);
        return $pdo;
        
    } catch(PDOException $e) {
        // Handle connection error
        die("Database connection failed: " . $e->getMessage());
    }
}

// Create global connection
$db = getDatabaseConnection();
?>
