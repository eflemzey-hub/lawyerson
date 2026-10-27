<?php

function admin_format_date($date, $format = 'M d, Y') {
    if (empty($date) || $date === '0000-00-00' || $date === '0000-00-00 00:00:00') {
        return 'Not scheduled';
    }
    $ts = strtotime($date);
    if ($ts === false || $ts < strtotime('1971-01-01')) {
        return 'Not scheduled';
    }
    return date($format, $ts);
}

function admin_format_practice_area($value) {
    $map = [
        'corporate' => 'Corporate Law',
        'real_estate' => 'Real Estate',
        'family' => 'Family Law',
        'criminal' => 'Criminal Defense',
        'injury' => 'Personal Injury',
        'Civil Litigation' => 'Civil Litigation',
    ];
    $key = strtolower(trim((string) $value));
    if (isset($map[$key])) {
        return $map[$key];
    }
    if (isset($map[$value])) {
        return $map[$value];
    }
    return ucwords(str_replace('_', ' ', (string) $value));
}

function admin_status_badge_class($status) {
    switch ($status) {
        case 'pending':
            return 'bg-warning text-dark';
        case 'confirmed':
            return 'bg-success';
        case 'completed':
            return 'bg-primary-law';
        default:
            return 'bg-secondary';
    }
}

function admin_greeting() {
    $hour = (int) date('G');
    if ($hour < 12) {
        return 'Good Morning';
    }
    if ($hour < 17) {
        return 'Good Afternoon';
    }
    return 'Good Evening';
}
