<?php
function sanitizeInput($input) {
    $input = trim($input);
    $input = preg_replace('/[\x00-\x09\x0B\x0C\x0E-\x1F\x7F]/', '', $input);
    $input = str_replace(["\r", "\n", "%0a", "%0d"], '', $input);
    return $input;
}

function validateEmail($email) {
    return filter_var($email, FILTER_VALIDATE_EMAIL);
}

function validateName($name) {
    return preg_match('/^[a-zA-ZäöüÄÖÜß\s-]{2,50}$/', $name);
}

function validateMessage($message) {
    $message = trim($message);
    return mb_strlen($message, 'UTF-8') >= 10 && mb_strlen($message, 'UTF-8') <= 1000;
}

function validateAttachment($file) {
    $allowed_types = ['pdf', 'doc', 'docx', 'xls', 'xlsx', 'ppt', 'pptx', 'zip', 'rar', '7z'];
    $max_size = 15 * 1024 * 1024; // 15 MB

    if ($file['error'] !== UPLOAD_ERR_OK) {
        return false;
    }

    $file_extension = strtolower(pathinfo($file['name'], PATHINFO_EXTENSION));
    if (!in_array($file_extension, $allowed_types)) {
        return false;
    }

    if ($file['size'] > $max_size) {
        return false;
    }

    return true;
}