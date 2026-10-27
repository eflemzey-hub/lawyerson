<?php
require_once __DIR__ . '/bootstrap.php';

api_require_auth();

api_success([
    'endpoints' => [
        'GET /api/attorneys.php',
        'GET /api/posts.php',
        'GET /api/posts.php?id=1',
        'GET /api/posts.php?slug=example-slug',
        'GET /api/settings.php',
        'POST /api/consultation.php',
        'POST /api/contact.php',
    ],
], 'Lawyerson mobile API is ready.');
