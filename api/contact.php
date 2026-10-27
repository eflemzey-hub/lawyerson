<?php
require_once __DIR__ . '/bootstrap.php';

api_require_auth();
api_require_method('POST');
api_require_database($pdo ?? null);
api_rate_limit('contact', 10, 300);

$input = api_input();
$errors = api_required_fields($input, ['first_name', 'last_name', 'email', 'subject', 'message']);

$email = api_clean_email($input['email'] ?? '');
if (!empty($input['email']) && $email === '') {
    $errors['email'] = 'Please provide a valid email address.';
}

if (!empty($errors)) {
    api_error('Please correct the highlighted fields.', 422, $errors);
}

$stmt = $pdo->prepare("INSERT INTO messages (first_name, last_name, email, phone, subject, message) VALUES (?, ?, ?, ?, ?, ?)");
$stmt->execute([
    api_clean_text($input['first_name'], 100),
    api_clean_text($input['last_name'], 100),
    $email,
    api_clean_text($input['phone'] ?? '', 20),
    api_clean_text($input['subject'], 255),
    api_clean_text($input['message'], 5000),
]);

$id = (int) $pdo->lastInsertId();
$reference = hash_hmac('sha256', 'message_' . $id, api_get_key());

api_success([
    'reference_token' => $reference,
    'status' => 'unread',
], 'Message submitted.', 201);
