<?php
require_once __DIR__ . '/../config/database.php';

header('Content-Type: application/json; charset=utf-8');
header('X-Content-Type-Options: nosniff');
header('Referrer-Policy: strict-origin-when-cross-origin');
header('Cache-Control: no-store');

$origin = $_SERVER['HTTP_ORIGIN'] ?? '';
$host = preg_replace('/[^A-Za-z0-9.\-:]/', '', $_SERVER['HTTP_HOST'] ?? '');
if ($origin !== '') {
    $originHost = parse_url($origin, PHP_URL_HOST);
    if ($originHost && preg_replace('/[^A-Za-z0-9.\-]/', '', $originHost) === preg_replace('/:\d+$/', '', $host)) {
        header('Access-Control-Allow-Origin: ' . $origin);
        header('Vary: Origin');
    }
}
header('Access-Control-Allow-Methods: GET, POST, OPTIONS');
header('Access-Control-Allow-Headers: Content-Type');

if ($_SERVER['REQUEST_METHOD'] === 'OPTIONS') {
    http_response_code(204);
    exit;
}

function api_json($payload, $statusCode = 200) {
    http_response_code($statusCode);
    echo json_encode($payload, JSON_UNESCAPED_SLASHES);
    exit;
}

function api_error($message, $statusCode = 400, $details = []) {
    $payload = [
        'success' => false,
        'message' => $message,
    ];

    if (!empty($details)) {
        $payload['errors'] = $details;
    }

    api_json($payload, $statusCode);
}

function api_success($data = [], $message = 'OK', $statusCode = 200) {
    api_json([
        'success' => true,
        'message' => $message,
        'data' => $data,
    ], $statusCode);
}

function api_require_database($pdo) {
    if (!$pdo) {
        api_error('Database connection is not available.', 500);
    }
}

function api_require_method($method) {
    if ($_SERVER['REQUEST_METHOD'] !== strtoupper($method)) {
        api_error('Method not allowed.', 405);
    }
}

function api_input() {
    $contentType = $_SERVER['CONTENT_TYPE'] ?? '';

    if (stripos($contentType, 'application/json') !== false) {
        $rawBody = file_get_contents('php://input');
        if (strlen($rawBody) > 1024 * 1024) {
            api_error('Request body is too large.', 413);
        }
        $decoded = json_decode($rawBody, true);

        if (json_last_error() !== JSON_ERROR_NONE) {
            api_error('Invalid JSON request body.', 400);
        }

        return is_array($decoded) ? $decoded : [];
    }

    return $_POST;
}

function api_required_fields($input, $fields) {
    $errors = [];

    foreach ($fields as $field) {
        if (!isset($input[$field]) || trim((string) $input[$field]) === '') {
            $errors[$field] = 'This field is required.';
        }
    }

    return $errors;
}

function api_base_url() {
    $https = (!empty($_SERVER['HTTPS']) && $_SERVER['HTTPS'] !== 'off');
    $scheme = $https ? 'https' : 'http';
    $host = preg_replace('/[^A-Za-z0-9.\-:]/', '', $_SERVER['HTTP_HOST'] ?? 'localhost');
    $scriptDir = str_replace('\\', '/', dirname($_SERVER['SCRIPT_NAME'] ?? '/lawyerson/api'));
    $projectDir = rtrim(dirname($scriptDir), '/');

    return $scheme . '://' . $host . $projectDir;
}

function api_asset_url($path) {
    $path = trim((string) $path);

    if ($path === '') {
        return '';
    }

    if (preg_match('#^https?://#i', $path) || strpos($path, '//') === 0) {
        return $path;
    }

    $path = ltrim(str_replace('\\', '/', $path), '/');
    if (!preg_match('#^(uploads|assets)/[A-Za-z0-9._/\-]+$#', $path)) {
        return '';
    }
    return rtrim(api_base_url(), '/') . '/' . $path;
}

function api_clean_text($value, $maxLength = 255) {
    $value = trim((string) $value);
    $value = preg_replace('/[\x00-\x08\x0B\x0C\x0E-\x1F\x7F]/u', '', $value);
    if (function_exists('mb_substr')) {
        return mb_substr($value, 0, $maxLength);
    }
    return substr($value, 0, $maxLength);
}

function api_clean_email($value) {
    $email = api_clean_text($value, 180);
    return filter_var($email, FILTER_VALIDATE_EMAIL) ? $email : '';
}

function api_rate_limit($bucket, $maxRequests = 20, $windowSeconds = 300) {
    $ip = preg_replace('/[^A-Fa-f0-9:.,]/', '', $_SERVER['REMOTE_ADDR'] ?? 'unknown');
    $dir = sys_get_temp_dir() . DIRECTORY_SEPARATOR . 'lawyerson_api_limits';
    if (!is_dir($dir)) {
        @mkdir($dir, 0700, true);
    }
    $file = $dir . DIRECTORY_SEPARATOR . hash('sha256', $bucket . '|' . $ip) . '.json';
    $now = time();
    $record = ['start' => $now, 'count' => 0];

    $handle = @fopen($file, 'c+');
    if (!$handle) {
        return;
    }

    flock($handle, LOCK_EX);
    $contents = stream_get_contents($handle);
    if ($contents) {
        $decoded = json_decode($contents, true);
        if (is_array($decoded)) {
            $record = $decoded;
        }
    }
    if ($now - (int) ($record['start'] ?? 0) > $windowSeconds) {
        $record = ['start' => $now, 'count' => 0];
    }
    $record['count'] = (int) ($record['count'] ?? 0) + 1;
    ftruncate($handle, 0);
    rewind($handle);
    fwrite($handle, json_encode($record));
    fflush($handle);
    flock($handle, LOCK_UN);
    fclose($handle);

    if ($record['count'] > $maxRequests) {
        api_error('Too many requests. Please try again later.', 429);
    }
}

function api_get_key() {
    // Load environment if not already loaded
    require_once __DIR__ . '/../config/env.php';
    
    // First: Check environment variable
    $key = Env::get('API_KEY', '');
    if (strlen($key) >= 32) {
        return $key;
    }
    
    // Second: Try config file (legacy support)
    $configFile = __DIR__ . '/../config/api_key.php';
    if (file_exists($configFile)) {
        $key = include $configFile;
        if (is_string($key) && strlen($key) >= 32) {
            return $key;
        }
    }
    
    // Third: Try database (legacy support)
    global $pdo;
    if ($pdo) {
        try {
            $stmt = $pdo->prepare("SELECT setting_value FROM settings WHERE setting_key = 'api_key'");
            $stmt->execute();
            $result = $stmt->fetchColumn();
            if ($result && strlen($result) >= 32) {
                return $result;
            }
        } catch (Exception $e) {}
    }
    
    return '';
}

function api_require_auth() {
    $providedKey = $_SERVER['HTTP_X_API_KEY'] ?? '';
    $signature = $_SERVER['HTTP_X_API_SIGNATURE'] ?? '';
    $timestamp = (int) ($_SERVER['HTTP_X_API_TIMESTAMP'] ?? 0);
    $storedKey = api_get_key();

    if ($storedKey === '') {
        api_error('Unauthorized. API is not configured.', 401);
    }

    $isAuthenticated = false;

    // HMAC verification if timestamp and signature are provided
    if ($signature !== '' && $timestamp > 0) {
        if (abs(time() - $timestamp) <= 300) {
            $payload = $timestamp . ($_SERVER['REQUEST_URI'] ?? '');
            $expectedSignature = hash_hmac('sha256', $payload, $storedKey);
            if (hash_equals($expectedSignature, $signature)) {
                $isAuthenticated = true;
            }
        }
    } else {
        // Fallback to plain API key, but ONLY via header (query string removed to prevent leakage)
        if ($providedKey !== '' && hash_equals($storedKey, $providedKey)) {
            $isAuthenticated = true;
        }
    }

    if (!$isAuthenticated) {
        api_error('Unauthorized. A valid API key or HMAC signature is required.', 401);
    }
}
