<?php
require_once __DIR__ . '/bootstrap.php';
require_once __DIR__ . '/../models/Attorney.php';

api_require_auth();
api_require_method('GET');
api_rate_limit('attorneys_get', 30, 60);
api_require_database($pdo ?? null);

$attorneyModel = new Attorney($pdo);
$attorneys = array_map(function ($attorney) {
    return [
        'id' => (int) $attorney['id'],
        'name' => $attorney['name'],
        'position' => $attorney['position'],
        'bio' => $attorney['bio'] ?? '',
        'image_url' => api_asset_url($attorney['image_url'] ?? ''),
        'facebook' => $attorney['facebook'] ?? '',
        'twitter' => $attorney['twitter'] ?? '',
        'linkedin' => $attorney['linkedin'] ?? '',
        'created_at' => $attorney['created_at'] ?? null,
    ];
}, $attorneyModel->getAll());

api_success([
    'attorneys' => $attorneys,
    'count' => count($attorneys),
]);
