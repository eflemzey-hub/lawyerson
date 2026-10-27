<?php
// Enhanced Session Security
ini_set('session.use_only_cookies', '1');
ini_set('session.use_strict_mode', '1');
session_set_cookie_params([
    'lifetime' => 0,
    'path' => '/',
    'domain' => '',
    'secure' => isset($_SERVER['HTTPS']) && $_SERVER['HTTPS'] !== 'off',
    'httponly' => true,
    'samesite' => 'Strict'
]);
session_start();

// Security Headers
header('X-Content-Type-Options: nosniff');
header('X-Frame-Options: SAMEORIGIN');
header('Referrer-Policy: strict-origin-when-cross-origin');
header('Permissions-Policy: camera=(), microphone=(), geolocation=()');

// HSTS for HTTPS
if (!empty($_SERVER['HTTPS']) && $_SERVER['HTTPS'] !== 'off') {
    header('Strict-Transport-Security: max-age=31536000; includeSubDomains');
}

// Enhanced Content Security Policy
// Allows necessary external resources while blocking inline scripts
$cspDirectives = [
    "default-src 'self'",
    "script-src 'self' https://cdn.jsdelivr.net https://cdnjs.cloudflare.com https://embed.tawk.to https://translate.googleapis.com 'unsafe-inline'", // unsafe-inline needed for legacy inline scripts
    "style-src 'self' 'unsafe-inline' https://cdn.jsdelivr.net https://cdnjs.cloudflare.com https://fonts.googleapis.com https://translate.googleapis.com",
    "font-src 'self' https://fonts.gstatic.com https://cdnjs.cloudflare.com data:",
    "img-src 'self' data: https: blob:",
    "connect-src 'self' https://embed.tawk.to wss://va.tawk.to https://translate.googleapis.com",
    "frame-src https://embed.tawk.to https://www.youtube.com https://translate.google.com https://www.openstreetmap.org",
    "object-src 'none'",
    "base-uri 'self'",
    "form-action 'self'",
    "frame-ancestors 'self'",
    "upgrade-insecure-requests"
];
header("Content-Security-Policy: " . implode('; ', $cspDirectives));

// Generate CSRF token if not exists
if (empty($_SESSION['csrf_token'])) {
    $_SESSION['csrf_token'] = bin2hex(random_bytes(32));
}

// Check for remember me cookie if not logged in
if (empty($_SESSION['admin_logged_in']) && isset($_COOKIE['admin_remember'])) {
    $rememberToken = $_COOKIE['admin_remember'];
    if (validate_remember_token($pdo, $rememberToken)) {
        $_SESSION['admin_logged_in'] = true;
        $_SESSION['admin_fingerprint'] = hash('sha256', ($_SERVER['HTTP_USER_AGENT'] ?? '') . '|' . substr($_SERVER['REMOTE_ADDR'] ?? '', 0, 7));
        $_SESSION['admin_last_activity'] = time();
        session_regenerate_id(true);
    }
}

if (!empty($_SESSION['admin_logged_in'])) {
    $fingerprint = hash('sha256', ($_SERVER['HTTP_USER_AGENT'] ?? '') . '|' . substr($_SERVER['REMOTE_ADDR'] ?? '', 0, 7));
    if (empty($_SESSION['admin_fingerprint'])) {
        $_SESSION['admin_fingerprint'] = $fingerprint;
    } elseif (!hash_equals($_SESSION['admin_fingerprint'], $fingerprint)) {
        $_SESSION = [];
        session_destroy();
        clear_remember_cookie();
        header("Location: index.php?page=admin_login");
        exit;
    }

    if (!empty($_SESSION['admin_last_activity']) && time() - (int) $_SESSION['admin_last_activity'] > 1800) {
        $_SESSION = [];
        session_destroy();
        clear_remember_cookie();
        header("Location: index.php?page=admin_login&expired=1");
        exit;
    }
    $_SESSION['admin_last_activity'] = time();
}

// Main entry point for the application (Front Controller)

// Load configuration & models
require_once 'config/database.php';
require_once 'includes/admin_helpers.php';
require_once 'models/Setting.php';
require_once 'models/Media.php';
require_once 'models/Attorney.php';
require_once 'models/Post.php';
require_once 'models/Message.php';
require_once 'models/Testimonial.php';

$pdo_instance = $pdo ?? null;
$settingModel = new Setting($pdo_instance);
$mediaModel = new Media($pdo_instance);
$attorneyModel = new Attorney($pdo_instance);
$postModel = new Post($pdo_instance);
$messageModel = new Message($pdo_instance);
$testimonialModel = new Testimonial($pdo_instance);

// Site-wide settings (navbar, footer, page titles)
$siteName = $settingModel->get('site_name', 'Lawyerson & Partners');
$contactEmail = $settingModel->get('contact_email', 'lawyersongroupllp@gmail.com');
$footerText = $settingModel->get('footer_text', '© ' . date('Y') . ' Lawyerson & Partners. All rights reserved.');
$socialFacebook = $settingModel->get('social_facebook', '');
$socialLinkedin = $settingModel->get('social_linkedin', '');
$socialTwitter = $settingModel->get('social_twitter', '');
$socialWebsite = $settingModel->get('social_website', '');
$siteLogoPath = resolve_setting_media_path($settingModel, $mediaModel, 'site_logo', 'logo_media_id');
$siteFaviconPath = resolve_setting_media_path($settingModel, $mediaModel, 'site_favicon', 'favicon_media_id');
$siteLogoUrl = $siteLogoPath !== '' ? resolve_post_image_url($siteLogoPath) : '';
$siteFaviconUrl = $siteFaviconPath !== '' ? resolve_post_image_url($siteFaviconPath) : '';

function format_site_name_html($name) {
    if (preg_match('/^(.+?)\s*&\s*(.+)$/u', trim($name), $m)) {
        return htmlspecialchars(trim($m[1])) . ' <span class="text-gold">&</span> ' . htmlspecialchars(trim($m[2]));
    }
    return htmlspecialchars($name);
}

function normalize_social_url($url) {
    $url = trim($url);
    if ($url === '') {
        return '';
    }
    if (!preg_match('#^https?://#i', $url)) {
        $url = 'https://' . ltrim($url, '/');
    }
    if (filter_var($url, FILTER_VALIDATE_URL) === false || !preg_match('#^https?://#i', $url)) {
        return '';
    }
    return $url;
}

function clean_text($value, $maxLength = 255) {
    $value = trim((string) $value);
    $value = preg_replace('/[\x00-\x08\x0B\x0C\x0E-\x1F\x7F]/u', '', $value);
    if (function_exists('mb_substr')) {
        return mb_substr($value, 0, $maxLength);
    }
    return substr($value, 0, $maxLength);
}

function clean_email($value) {
    $email = clean_text($value, 180);
    return filter_var($email, FILTER_VALIDATE_EMAIL) ? $email : '';
}

function clean_url_value($value) {
    $value = clean_text($value, 2048);
    if ($value === '') {
        return '';
    }
    if (preg_match('#^(https?:)?//#i', $value) || preg_match('#^(uploads|assets)/[A-Za-z0-9._/\-]+$#', $value)) {
        return $value;
    }
    return '';
}

function safe_redirect($target, $fallback = 'index.php?page=admin_dashboard') {
    $target = trim((string) $target);
    if ($target === '' || preg_match('/[\r\n]/', $target)) {
        return $fallback;
    }
    if (preg_match('#^index\.php(\?[A-Za-z0-9_=&%+\-.,#]*)?$#', $target)) {
        return $target;
    }
    if (preg_match('#^/lawyerson/index\.php(\?[A-Za-z0-9_=&%+\-.,#]*)?$#', $target)) {
        return $target;
    }
    return $fallback;
}

function resolve_post_image_url($url, $default = '') {
    $url = trim((string) $url);
    if ($url === '') {
        return $default;
    }
    if (preg_match('#^https?://#i', $url) || strpos($url, '//') === 0) {
        return $url;
    }
    $url = ltrim(str_replace('\\', '/', $url), '/');
    return preg_match('#^(uploads|assets)/[A-Za-z0-9._/\-]+$#', $url) ? $url : $default;
}

function resolve_setting_media_path($settingModel, $mediaModel, $pathKey, $legacyMediaIdKey = '') {
    $path = trim((string) $settingModel->get($pathKey, ''));
    if ($path !== '') {
        return $path;
    }
    if ($legacyMediaIdKey !== '' && $mediaModel) {
        $mediaId = trim((string) $settingModel->get($legacyMediaIdKey, ''));
        if ($mediaId !== '') {
            $media = $mediaModel->getById($mediaId);
            if (!empty($media['file_path'])) {
                return $media['file_path'];
            }
        }
    }
    return '';
}

function admin_password_needs_setup($settingModel) {
    if (!$settingModel) return true;
    $hash = trim((string) $settingModel->get('admin_password_hash', ''));
    return $hash === '';
}

function admin_password_matches($settingModel, $password) {
    $hash = $settingModel ? trim((string) $settingModel->get('admin_password_hash', '')) : '';
    if ($hash !== '') {
        return password_verify($password, $hash);
    }
    // No fallback password — must go through setup first
    return false;
}

function admin_store_password($settingModel, $password) {
    if (!$settingModel) {
        return false;
    }
    return $settingModel->update('admin_password_hash', password_hash($password, PASSWORD_DEFAULT));
}

function generate_remember_token() {
    return bin2hex(random_bytes(32));
}

function set_remember_cookie($token) {
    $cookieName = 'admin_remember';
    $expiry = time() + (30 * 24 * 60 * 60); // 30 days
    setcookie($cookieName, $token, [
        'expires' => $expiry,
        'path' => '/',
        'domain' => '',
        'secure' => isset($_SERVER['HTTPS']) && $_SERVER['HTTPS'] !== 'off',
        'httponly' => true,
        'samesite' => 'Strict'
    ]);
}

function clear_remember_cookie() {
    setcookie('admin_remember', '', [
        'expires' => time() - 3600,
        'path' => '/',
        'domain' => '',
        'secure' => isset($_SERVER['HTTPS']) && $_SERVER['HTTPS'] !== 'off',
        'httponly' => true,
        'samesite' => 'Strict'
    ]);
}

function validate_remember_token($pdo, $token) {
    try {
        $stmt = $pdo->prepare("SELECT setting_value FROM settings WHERE setting_key = 'admin_remember_token' LIMIT 1");
        $stmt->execute();
        $result = $stmt->fetch(PDO::FETCH_ASSOC);
        if ($result && hash_equals($result['setting_value'], $token)) {
            return true;
        }
    } catch (Exception $e) {}
    return false;
}

function store_remember_token($pdo, $token) {
    try {
        $stmt = $pdo->prepare("INSERT INTO settings (setting_key, setting_value) VALUES ('admin_remember_token', ?) ON DUPLICATE KEY UPDATE setting_value = ?");
        $stmt->execute([$token, $token]);
    } catch (Exception $e) {}
}

function clear_remember_token($pdo) {
    try {
        $stmt = $pdo->prepare("DELETE FROM settings WHERE setting_key = 'admin_remember_token'");
        $stmt->execute();
    } catch (Exception $e) {}
}

function resolve_post_featured_image($postData, $mediaModel) {
    if (!empty($_FILES['featured_image']['name']) && ($_FILES['featured_image']['error'] ?? UPLOAD_ERR_NO_FILE) === UPLOAD_ERR_OK) {
        return $mediaModel->upload($_FILES['featured_image']);
    }
    if (!empty($postData['image_media_id'])) {
        $media = $mediaModel->getById($postData['image_media_id']);
        if ($media && !empty($media['file_path'])) {
            return $media['file_path'];
        }
    }
    return clean_url_value($postData['image_url'] ?? '');
}

$validPages = [
    'home', 'about', 'practices', 'attorneys', 'consultation', 'contact', 'blog', 'article',
    'admin_appointments', 'admin_messages', 'admin_attorneys', 'admin_blog', 'admin_edit_post',
    'admin_dashboard', 'admin_media', 'admin_settings', 'admin_login', 'admin_setup', 'admin_logout'
];
$page = $_GET['page'] ?? 'home';
if (!in_array($page, $validPages, true)) {
    $page = 'home';
}

// Handle form submissions
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['action'])) {
    $action = clean_text($_POST['action'], 80);
    // Validate CSRF token
    if (!isset($_POST['csrf_token']) || !hash_equals($_SESSION['csrf_token'], $_POST['csrf_token'])) {
        die("Security Error: CSRF token validation failed.");
    }

    $publicActions = ['book_consultation', 'send_contact', 'admin_login'];
    if (!in_array($action, $publicActions, true) && empty($_SESSION['admin_logged_in'])) {
        header("Location: index.php?page=admin_login");
        exit;
    }

    if ($action === 'book_consultation') {
        $name = clean_text($_POST['name'] ?? '', 100);
        $email = clean_email($_POST['email'] ?? '');
        $phone = clean_text($_POST['phone'] ?? '', 20);
        $date = clean_text($_POST['appointment_date'] ?? $_POST['date'] ?? '', 20);
        if ($date === '' || $date === '0000-00-00') {
            $date = date('Y-m-d', strtotime('+5 weekdays'));
        }
        $dateObj = DateTime::createFromFormat('Y-m-d', $date);
        $date = $dateObj ? $dateObj->format('Y-m-d') : date('Y-m-d', strtotime('+5 weekdays'));
        $practice_area = clean_text($_POST['practice_area'] ?? '', 80);
        $details = clean_text($_POST['case_details'] ?? $_POST['details'] ?? '', 5000);

        try {
            if (isset($pdo) && $name !== '' && $email !== '') {
                $stmt = $pdo->prepare("INSERT INTO appointments (name, email, phone, appointment_date, practice_area, case_details, status) VALUES (?, ?, ?, ?, ?, ?, 'pending')");
                $stmt->execute([$name, $email, $phone, $date, $practice_area, $details]);
            }
        } catch (Exception $e) {}

        $redirectPage = ($_POST['redirect_page'] ?? 'consultation') === 'home' ? 'home' : 'consultation';
        header('Location: index.php?page=' . urlencode($redirectPage) . '&booked=1');
        exit;
    } elseif ($action === 'update_appointment_status') {
        $status = clean_text($_POST['status'] ?? '', 20);
        $allowedStatuses = ['pending', 'confirmed', 'completed', 'cancelled'];
        try {
            if (isset($pdo) && in_array($status, $allowedStatuses, true)) {
                $stmt = $pdo->prepare("UPDATE appointments SET status = ? WHERE id = ?");
                $stmt->execute([$status, (int) ($_POST['appointment_id'] ?? 0)]);
            }
        } catch (Exception $e) {}
        $_SESSION['admin_flash'] = 'Booking status updated.';
        $redirect = safe_redirect($_POST['redirect'] ?? 'index.php?page=admin_appointments', 'index.php?page=admin_appointments');
        header('Location: ' . $redirect);
        exit;
    } elseif ($action === 'delete_appointment') {
        try {
            if (isset($pdo)) {
                $stmt = $pdo->prepare("DELETE FROM appointments WHERE id = ?");
                $stmt->execute([(int) ($_POST['appointment_id'] ?? 0)]);
            }
        } catch (Exception $e) {}
        $_SESSION['admin_flash'] = 'Booking deleted.';
        $redirect = safe_redirect($_POST['redirect'] ?? 'index.php?page=admin_appointments', 'index.php?page=admin_appointments');
        header('Location: ' . $redirect);
        exit;
    } elseif ($action === 'send_contact') {
        $first_name = clean_text($_POST['first_name'] ?? '', 100);
        $last_name = clean_text($_POST['last_name'] ?? '', 100);
        $email = clean_email($_POST['email'] ?? '');
        $phone = clean_text($_POST['phone'] ?? '', 20);
        $subject = clean_text($_POST['subject'] ?? '', 255);
        $message = clean_text($_POST['message'] ?? '', 5000);

        try {
            if (isset($pdo) && $first_name !== '' && $last_name !== '' && $email !== '' && $subject !== '' && $message !== '') {
                $stmt = $pdo->prepare("INSERT INTO messages (first_name, last_name, email, phone, subject, message) VALUES (?, ?, ?, ?, ?, ?)");
                $stmt->execute([$first_name, $last_name, $email, $phone, $subject, $message]);
            }
        } catch(Exception $e) {}

        header("Location: index.php?page=contact&sent=1");
        exit;
    } elseif ($action === 'create_post') {
        try {
            if (isset($postModel) && isset($mediaModel)) {
                $_POST['title'] = clean_text($_POST['title'] ?? '', 255);
                $_POST['category'] = clean_text($_POST['category'] ?? '', 80);
                $_POST['content'] = clean_text($_POST['content'] ?? '', 20000);
                $_POST['status'] = in_array(($_POST['status'] ?? 'draft'), ['draft', 'published'], true) ? $_POST['status'] : 'draft';
                $_POST['slug'] = strtolower(trim(preg_replace('/[^A-Za-z0-9-]+/', '-', $_POST['title'] ?? ''), '-'));
                $_POST['image_url'] = resolve_post_featured_image($_POST, $mediaModel);
                if ($_POST['image_url'] === '') {
                    throw new Exception('Please upload a featured image, select one from the Media Library, or paste an image URL.');
                }
                $postModel->create($_POST);
                header("Location: index.php?page=admin_blog&success=1");
                exit;
            }
        } catch (Exception $e) {
            $_SESSION['post_error'] = $e->getMessage();
        }
        header("Location: index.php?page=admin_blog&error=1");
        exit;
    } elseif ($action === 'delete_post') {
        try {
            if (isset($postModel)) {
                $postModel->delete((int) ($_POST['post_id'] ?? 0));
            }
        } catch(Exception $e) {}
        header("Location: index.php?page=admin_blog");
        exit;
    } elseif ($action === 'update_post') {
        try {
            if (isset($postModel) && isset($mediaModel)) {
                $_POST['title'] = clean_text($_POST['title'] ?? '', 255);
                $_POST['category'] = clean_text($_POST['category'] ?? '', 80);
                $_POST['content'] = clean_text($_POST['content'] ?? '', 20000);
                $_POST['status'] = in_array(($_POST['status'] ?? 'draft'), ['draft', 'published'], true) ? $_POST['status'] : 'draft';
                $_POST['slug'] = strtolower(trim(preg_replace('/[^A-Za-z0-9-]+/', '-', $_POST['title'] ?? ''), '-'));
                $newImage = resolve_post_featured_image($_POST, $mediaModel);
                if ($newImage !== '') {
                    $_POST['image_url'] = $newImage;
                } else {
                    $_POST['image_url'] = clean_url_value($_POST['existing_image_url'] ?? '');
                }
                if ($_POST['image_url'] === '') {
                    throw new Exception('Please upload a featured image, select one from the Media Library, or paste an image URL.');
                }
                $postModel->update((int) ($_POST['post_id'] ?? 0), $_POST);
                header("Location: index.php?page=admin_blog&success=1");
                exit;
            }
        } catch (Exception $e) {
            $_SESSION['post_error'] = $e->getMessage();
            header("Location: index.php?page=admin_edit_post&id=" . urlencode((string) (int) ($_POST['post_id'] ?? 0)) . "&error=1");
            exit;
        }
        header("Location: index.php?page=admin_blog&error=1");
        exit;
    } elseif ($action === 'upload_media') {
        try {
            if(isset($_FILES['files'])) {
                $file_count = count($_FILES['files']['name']);
                for($i=0; $i<$file_count; $i++) {
                    if ($_FILES['files']['error'][$i] === UPLOAD_ERR_OK) {
                        $file = [
                            'name' => $_FILES['files']['name'][$i],
                            'type' => $_FILES['files']['type'][$i],
                            'tmp_name' => $_FILES['files']['tmp_name'][$i],
                            'error' => $_FILES['files']['error'][$i],
                            'size' => $_FILES['files']['size'][$i]
                        ];
                        $mediaModel->upload($file);
                    }
                }
            }
        } catch(Exception $e) {
            $_SESSION['error'] = $e->getMessage();
        }
        header("Location: index.php?page=admin_media");
        exit;
    } elseif ($action === 'delete_media') {
        $id = (int) ($_POST['media_id'] ?? 0);
        $mediaModel->delete($id);
        header("Location: index.php?page=admin_media");
        exit;
    } elseif ($action === 'update_settings') {
        if (!$pdo_instance) {
            $_SESSION['settings_error'] = 'Database is not connected. Start MySQL in XAMPP and import database.sql.';
            header("Location: index.php?page=admin_settings&error=1");
            exit;
        }
        $settings = $_POST['settings'] ?? [];
        foreach ($settings as $key => $value) {
            $key = preg_replace('/[^A-Za-z0-9_]/', '', (string) $key);
            if ($key === '') {
                continue;
            }
            if (preg_match('/(_bg|_img|site_logo|site_favicon|admin_image)$/', $key)) {
                $value = clean_url_value($value);
            } elseif (strpos($key, 'social_') === 0) {
                $value = normalize_social_url(clean_text($value, 2048));
            } elseif ($key === 'tawk_id') {
                $value = preg_replace('/[^A-Za-z0-9_\-\/]/', '', (string) $value);
            } else {
                $value = clean_text($value, 2000);
            }
            $settingModel->update($key, $value);
        }
        foreach (['site_logo' => 'logo_media_id', 'site_favicon' => 'favicon_media_id'] as $pathKey => $mediaIdKey) {
            $path = trim($settingModel->get($pathKey, ''));
            $matchedId = '';
            if ($path !== '') {
                foreach ($mediaModel->getAll() as $media) {
                    if (($media['file_path'] ?? '') === $path) {
                        $matchedId = (string) $media['id'];
                        break;
                    }
                }
            }
            $settingModel->update($mediaIdKey, $matchedId);
        }
        header("Location: index.php?page=admin_settings&success=1");
        exit;
    } elseif ($action === 'create_attorney') {
        $_POST['name'] = clean_text($_POST['name'] ?? '', 255);
        $_POST['position'] = clean_text($_POST['position'] ?? '', 255);
        $_POST['bio'] = clean_text($_POST['bio'] ?? '', 5000);
        $_POST['image_url'] = clean_url_value($_POST['image_url'] ?? '');
        $_POST['facebook'] = normalize_social_url(clean_text($_POST['facebook'] ?? '', 2048));
        $_POST['twitter'] = normalize_social_url(clean_text($_POST['twitter'] ?? '', 2048));
        $_POST['linkedin'] = normalize_social_url(clean_text($_POST['linkedin'] ?? '', 2048));
        $attorneyModel->create($_POST);
        header("Location: index.php?page=admin_attorneys&success=1");
        exit;
    } elseif ($action === 'delete_attorney') {
        $attorneyModel->delete((int) ($_POST['attorney_id'] ?? 0));
        header("Location: index.php?page=admin_attorneys");
        exit;
    } elseif ($action === 'admin_login') {
        if (!empty($_SESSION['admin_logged_in'])) {
            header("Location: index.php?page=admin_dashboard");
            exit;
        }
        // Redirect to setup if no password has been configured yet
        if (admin_password_needs_setup($settingModel)) {
            header("Location: index.php?page=admin_setup");
            exit;
        }
        if (!empty($_SESSION['login_locked_until']) && time() < (int) $_SESSION['login_locked_until']) {
            $_SESSION['login_error'] = "Too many login attempts. Please try again shortly.";
            header("Location: index.php?page=admin_login");
            exit;
        }
        $email = clean_email($_POST['email'] ?? '');
        $password = $_POST['password'] ?? '';
        $rememberMe = isset($_POST['remember_me']);
        
        if ($email === 'admin@lawyerson.com' && admin_password_matches($settingModel, $password)) {
            unset($_SESSION['login_attempts'], $_SESSION['login_locked_until']);
            $_SESSION['admin_logged_in'] = true;
            $_SESSION['admin_fingerprint'] = hash('sha256', ($_SERVER['HTTP_USER_AGENT'] ?? '') . '|' . substr($_SERVER['REMOTE_ADDR'] ?? '', 0, 7));
            $_SESSION['admin_last_activity'] = time();
            session_regenerate_id(true);
            
            // Handle remember me
            if ($rememberMe) {
                $token = generate_remember_token();
                store_remember_token($pdo, $token);
                set_remember_cookie($token);
            } else {
                clear_remember_cookie();
                clear_remember_token($pdo);
            }
            
            header("Location: index.php?page=admin_dashboard");
            exit;
        } else {
            $_SESSION['login_attempts'] = (int) ($_SESSION['login_attempts'] ?? 0) + 1;
            if ($_SESSION['login_attempts'] >= 5) {
                $_SESSION['login_locked_until'] = time() + 900;
            }
            $_SESSION['login_error'] = "Invalid email or password.";
            header("Location: index.php?page=admin_login");
            exit;
        }
    } elseif ($action === 'admin_setup_password') {
        // First-time password setup — only works when no password hash exists
        if (!admin_password_needs_setup($settingModel)) {
            header("Location: index.php?page=admin_login");
            exit;
        }
        if (!$pdo_instance) {
            $_SESSION['setup_error'] = 'Database is not connected. Start MySQL in XAMPP and import database.sql.';
            header("Location: index.php?page=admin_setup");
            exit;
        }
        $newPassword = $_POST['new_password'] ?? '';
        $confirmPassword = $_POST['confirm_password'] ?? '';
        if (strlen($newPassword) < 8) {
            $_SESSION['setup_error'] = 'Password must be at least 8 characters long.';
            header("Location: index.php?page=admin_setup");
            exit;
        }
        if ($newPassword !== $confirmPassword) {
            $_SESSION['setup_error'] = 'Passwords do not match.';
            header("Location: index.php?page=admin_setup");
            exit;
        }
        admin_store_password($settingModel, $newPassword);
        $_SESSION['admin_logged_in'] = true;
        $_SESSION['admin_fingerprint'] = hash('sha256', ($_SERVER['HTTP_USER_AGENT'] ?? '') . '|' . substr($_SERVER['REMOTE_ADDR'] ?? '', 0, 7));
        $_SESSION['admin_last_activity'] = time();
        session_regenerate_id(true);
        $_SESSION['admin_flash'] = 'Admin password set successfully. Welcome!';
        header("Location: index.php?page=admin_dashboard");
        exit;
    } elseif ($action === 'regenerate_api_key') {
        // Regenerate the API key from the admin dashboard
        if (!$pdo_instance) {
            $_SESSION['settings_error'] = 'Database is not connected.';
            header("Location: index.php?page=admin_settings&error=1");
            exit;
        }
        $newKey = bin2hex(random_bytes(32));
        $configPath = __DIR__ . '/config/api_key.php';
        $configContent = "<?php\n// Auto-generated API key — DO NOT commit this file to version control.\n// Generated: " . date('Y-m-d H:i:s') . "\nreturn '" . $newKey . "';\n";
        file_put_contents($configPath, $configContent);
        @chmod($configPath, 0600);
        $stmt = $pdo->prepare("INSERT INTO settings (setting_key, setting_value) VALUES ('api_key', ?) ON DUPLICATE KEY UPDATE setting_value = ?");
        $stmt->execute([$newKey, $newKey]);
        $_SESSION['admin_flash'] = 'API key regenerated successfully.';
        header("Location: index.php?page=admin_settings&success=1");
        exit;
    } elseif ($action === 'update_admin_password') {
        $currentPassword = $_POST['current_password'] ?? '';
        $newPassword = $_POST['new_password'] ?? '';
        $confirmPassword = $_POST['confirm_password'] ?? '';
        $redirect = safe_redirect($_POST['redirect'] ?? 'index.php?page=admin_dashboard');

        if (!$pdo_instance) {
            $_SESSION['admin_security_error'] = 'Database is not connected. Start MySQL in XAMPP before changing the admin password.';
        } elseif (!admin_password_matches($settingModel, $currentPassword)) {
            $_SESSION['admin_security_error'] = 'Current password is incorrect.';
        } elseif (strlen($newPassword) < 8) {
            $_SESSION['admin_security_error'] = 'New password must be at least 8 characters long.';
        } elseif ($newPassword !== $confirmPassword) {
            $_SESSION['admin_security_error'] = 'New password and confirmation do not match.';
        } elseif (hash_equals($currentPassword, $newPassword)) {
            $_SESSION['admin_security_error'] = 'New password must be different from the current password.';
        } else {
            admin_store_password($settingModel, $newPassword);
            $_SESSION['admin_security_flash'] = 'Admin password updated successfully.';
        }

        header("Location: " . $redirect);
        exit;
    } elseif ($action === 'reply_to_message') {
        $messageId = (int) ($_POST['message_id'] ?? 0);
        $replyText = clean_text($_POST['reply_text'] ?? '', 10000);
        
        try {
            if (isset($messageModel) && $messageId > 0 && $replyText !== '') {
                $messageModel->addReply($messageId, $replyText, 'dashboard');
                $messageModel->markAsRead($messageId);
                // Update message status to replied
                $pdo->prepare("UPDATE messages SET status = 'replied' WHERE id = ?")->execute([$messageId]);
            }
        } catch (Exception $e) {}
        
        $_SESSION['admin_flash'] = 'Reply sent successfully.';
        header("Location: index.php?page=admin_messages");
        exit;
    } elseif ($action === 'delete_message') {
        $messageId = (int) ($_POST['message_id'] ?? 0);
        try {
            if (isset($messageModel)) {
                $messageModel->delete($messageId);
            }
        } catch (Exception $e) {}
        
        $_SESSION['admin_flash'] = 'Message deleted.';
        header("Location: index.php?page=admin_messages");
        exit;
    } elseif ($action === 'mark_message_read') {
        $messageId = (int) ($_POST['message_id'] ?? 0);
        try {
            if (isset($messageModel)) {
                $messageModel->markAsRead($messageId);
            }
        } catch (Exception $e) {}
        
        header("Location: index.php?page=admin_messages");
        exit;
    } elseif ($action === 'admin_logout') {
        $_SESSION = [];
        session_destroy();
        clear_remember_cookie();
        clear_remember_token($pdo);
        header("Location: index.php?page=admin_login");
        exit;
    }
}

// Authentication check for admin routes
if (strpos($page, 'admin_') === 0 && $page !== 'admin_login' && $page !== 'admin_logout' && $page !== 'admin_setup') {
    if (empty($_SESSION['admin_logged_in'])) {
        header("Location: index.php?page=admin_login");
        exit;
    }
}

// Handle Logout
if ($page === 'admin_logout') {
    // State-changing logout is handled by POST action=admin_logout to prevent CSRF logout.
    header("Location: index.php?page=admin_login");
    exit;
}

// Buffer the output of the view
ob_start();

if ($page === 'about') {
    $pageTitle = "About Us | " . $siteName;
    require_once 'views/public/about.php';
} elseif ($page === 'practices') {
    $pageTitle = "Practice Areas | " . $siteName;
    require_once 'views/public/practices.php';
} elseif ($page === 'attorneys') {
    $pageTitle = "Our Attorneys | " . $siteName;
    require_once 'views/public/attorneys.php';
} elseif ($page === 'consultation') {
    $pageTitle = "Book a Consultation | " . $siteName;
    require_once 'views/public/consultation.php';
} elseif ($page === 'contact') {
    $pageTitle = "Contact Us | " . $siteName;
    require_once 'views/public/contact.php';
} elseif ($page === 'blog') {
    $pageTitle = "Insights & News | " . $siteName;
    require_once 'views/public/blog.php';
} elseif ($page === 'article') {
    $pageTitle = "Article | " . $siteName;
    require_once 'views/public/article.php';
} elseif ($page === 'admin_appointments') {
    $pageTitle = "Appointments | Admin";
    require_once 'views/admin/appointments.php';
} elseif ($page === 'admin_messages') {
    $pageTitle = "Messages | Admin";
    require_once 'views/admin/messages.php';
} elseif ($page === 'admin_attorneys') {
    $pageTitle = "Manage Attorneys | Admin";
    require_once 'views/admin/attorneys.php';
} elseif ($page === 'admin_blog') {
    $pageTitle = "Blog Posts | Admin";
    require_once 'views/admin/blog.php';
} elseif ($page === 'admin_edit_post') {
    $pageTitle = "Edit Post | Admin";
    require_once 'views/admin/edit_post.php';
} elseif ($page === 'admin_dashboard') {
    $pageTitle = "Dashboard | Admin";
    require_once 'views/admin/dashboard.php';
} elseif ($page === 'admin_media') {
    $pageTitle = "Media Manager | Admin";
    require_once 'views/admin/media.php';
} elseif ($page === 'admin_settings') {
    $pageTitle = "Settings | Admin";
    require_once 'views/admin/settings.php';
} elseif ($page === 'admin_login') {
    // Redirect to setup if no password configured
    if (admin_password_needs_setup($settingModel)) {
        header("Location: index.php?page=admin_setup");
        exit;
    }
    $pageTitle = "Admin Login | " . $siteName;
    require_once 'views/admin/login.php';
} elseif ($page === 'admin_setup') {
    // First-time password setup — only accessible when no password exists
    if (!admin_password_needs_setup($settingModel)) {
        header("Location: index.php?page=admin_login");
        exit;
    }
    $pageTitle = "Initial Setup | " . $siteName;
    require_once 'views/admin/setup_password.php';
} else {
    $pageTitle = $siteName . " | Premium Legal Services";
    require_once 'views/public/home.php';
}

$content = ob_get_clean();

// Choose layout based on page route
if ($page === 'admin_login' || $page === 'admin_setup') {
    require_once 'views/layouts/auth.php';
} elseif (strpos($page, 'admin_') === 0) {
    require_once 'views/layouts/admin.php';
} else {
    require_once 'views/layouts/main.php';
}
