<?php
require_once __DIR__ . '/bootstrap.php';
require_once __DIR__ . '/../models/Post.php';

api_require_auth();
api_require_method('GET');
api_rate_limit('posts_get', 30, 60);
api_require_database($pdo ?? null);

$postModel = new Post($pdo);
$id = isset($_GET['id']) ? (int) $_GET['id'] : 0;
$slug = preg_replace('/[^A-Za-z0-9\-]/', '', api_clean_text($_GET['slug'] ?? '', 255));

$formatPost = function ($post) {
    return [
        'id' => (int) $post['id'],
        'title' => $post['title'],
        'slug' => $post['slug'],
        'content' => $post['content'],
        'category' => $post['category'] ?? '',
        'image_url' => api_asset_url($post['image_url'] ?? ''),
        'status' => $post['status'],
        'created_at' => $post['created_at'] ?? null,
    ];
};

if ($id > 0) {
    $post = $postModel->getPublishedById($id);

    if (!$post) {
        api_error('Post not found.', 404);
    }

    api_success(['post' => $formatPost($post)]);
}

if ($slug !== '') {
    $post = $postModel->getBySlug($slug);

    if (!$post) {
        api_error('Post not found.', 404);
    }

    api_success(['post' => $formatPost($post)]);
}

$search = api_clean_text($_GET['search'] ?? '', 120);
$posts = array_map($formatPost, $postModel->getAll(true, $search));

api_success([
    'posts' => $posts,
    'count' => count($posts),
]);
