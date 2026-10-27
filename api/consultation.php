<?php
require_once __DIR__ . '/bootstrap.php';

api_require_auth();
api_require_method('POST');
api_require_database($pdo ?? null);
api_rate_limit('consultation', 10, 300);

$input = api_input();
$errors = api_required_fields($input, ['name', 'email', 'phone', 'appointment_date']);

$email = api_clean_email($input['email'] ?? '');
if (!empty($input['email']) && $email === '') {
    $errors['email'] = 'Please provide a valid email address.';
}

$appointmentDate = api_clean_text($input['appointment_date'] ?? '', 20);
if ($appointmentDate !== '') {
    $date = DateTime::createFromFormat('Y-m-d', $appointmentDate);
    if (!$date || $date->format('Y-m-d') !== $appointmentDate) {
        $errors['appointment_date'] = 'Please use the YYYY-MM-DD date format.';
    }
}

if (!empty($errors)) {
    api_error('Please correct the highlighted fields.', 422, $errors);
}

$stmt = $pdo->prepare("INSERT INTO appointments (name, email, phone, appointment_date, practice_area, case_details, status) VALUES (?, ?, ?, ?, ?, ?, 'pending')");
$stmt->execute([
    api_clean_text($input['name'], 100),
    $email,
    api_clean_text($input['phone'], 20),
    $appointmentDate,
    api_clean_text($input['practice_area'] ?? '', 80),
    api_clean_text($input['case_details'] ?? $input['details'] ?? '', 5000),
]);

$id = (int) $pdo->lastInsertId();
$reference = hash_hmac('sha256', 'consultation_' . $id, api_get_key());

api_success([
    'reference_token' => $reference,
    'status' => 'pending',
], 'Consultation request submitted.', 201);
