<?php
require_once __DIR__ . '/bootstrap.php';
require_once __DIR__ . '/../models/Setting.php';

api_require_auth();
api_require_method('GET');
api_rate_limit('settings_get', 30, 60);
api_require_database($pdo ?? null);

$settingModel = new Setting($pdo);
$settings = $settingModel->getAll();

$publicSettings = [
    'site_name' => $settings['site_name'] ?? 'Lawyerson & Partners',
    'contact_email' => $settings['contact_email'] ?? 'lawyersongroupllp@gmail.com',
    'footer_text' => $settings['footer_text'] ?? '',
    'social_facebook' => $settings['social_facebook'] ?? '',
    'social_linkedin' => $settings['social_linkedin'] ?? '',
    'social_twitter' => $settings['social_twitter'] ?? '',
    'social_website' => $settings['social_website'] ?? '',
    'site_logo' => api_asset_url($settings['site_logo'] ?? ''),
    'site_favicon' => api_asset_url($settings['site_favicon'] ?? ''),
    'home_banner' => api_asset_url($settings['home_banner'] ?? ''),
    'inner_banner' => api_asset_url($settings['inner_banner'] ?? ''),
];

api_success(['settings' => $publicSettings]);
