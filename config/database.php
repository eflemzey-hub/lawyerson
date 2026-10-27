<?php
/**
 * Database Configuration
 * 
 * Loads database credentials from .env file for security.
 * Falls back to defaults if .env is not available.
 */

// Load environment variables
require_once __DIR__ . '/env.php';

// Get database configuration from environment
$host = Env::get('DB_HOST', 'localhost');
$dbname = Env::get('DB_NAME', 'lawfirm_db');
$username = Env::get('DB_USER', 'root');
$password = Env::get('DB_PASS', '');

// Validate required configuration
if (empty($host) || empty($dbname)) {
    error_log('Lawyerson Config Error: Missing required database configuration (DB_HOST or DB_NAME)');
    die('Database configuration error. Please check your .env file.');
}

try {
    $pdo = new PDO("mysql:host=$host;dbname=$dbname;charset=utf8mb4", $username, $password);
    $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
    $pdo->setAttribute(PDO::ATTR_EMULATE_PREPARES, false);
    $pdo->setAttribute(PDO::ATTR_DEFAULT_FETCH_MODE, PDO::FETCH_ASSOC);
} catch (PDOException $e) {
    // Log the real error for debugging but never expose it to users
    error_log('Lawyerson DB Error: ' . $e->getMessage());
    
    // More helpful error in development mode
    if (Env::get('APP_DEBUG', 'false') === 'true' || Env::get('APP_ENV', 'production') === 'development') {
        $db_error = 'Database connection failed: ' . htmlspecialchars($e->getMessage());
    } else {
        $db_error = 'Database connection failed. Please check your configuration.';
    }
}

