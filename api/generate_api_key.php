<?php
/**
 * One-time API key generator for Lawyerson Mobile API.
 * 
 * Run this script once via browser: http://localhost/lawyerson/api/generate_api_key.php
 * The key will be stored in config/api_key.php and in the database.
 * 
 * IMPORTANT: Delete this file after generating your key!
 */
require_once __DIR__ . '/bootstrap.php';

// Only allow from localhost
$remoteIp = $_SERVER['REMOTE_ADDR'] ?? '';
if (!in_array($remoteIp, ['127.0.0.1', '::1'], true) && php_sapi_name() !== 'cli') {
    api_error('This script can only be run from localhost.', 403);
}

$newKey = bin2hex(random_bytes(32));

// Store in config file
$configPath = __DIR__ . '/../config/api_key.php';
$configContent = "<?php\n// Auto-generated API key — DO NOT commit this file to version control.\n// Generated: " . date('Y-m-d H:i:s') . "\nreturn '" . $newKey . "';\n";

if (file_put_contents($configPath, $configContent) === false) {
    api_error('Failed to write config/api_key.php. Check directory permissions.', 500);
}
@chmod($configPath, 0600);

// Also store in database
if ($pdo ?? null) {
    try {
        $stmt = $pdo->prepare("INSERT INTO settings (setting_key, setting_value) VALUES ('api_key', ?) ON DUPLICATE KEY UPDATE setting_value = ?");
        $stmt->execute([$newKey, $newKey]);
    } catch (Exception $e) {}
}

api_success([
    'api_key' => $newKey,
    'storage' => 'config/api_key.php + database',
    'usage' => 'Add header: X-API-Key: ' . $newKey,
], 'API key generated successfully. DELETE this file now!', 201);
