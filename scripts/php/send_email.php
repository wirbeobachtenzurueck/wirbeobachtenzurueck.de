<?php
mb_internal_encoding('UTF-8');

use PHPMailer\PHPMailer\PHPMailer;
use PHPMailer\PHPMailer\Exception;

header('Content-Type: application/json');

require_once 'captcha.php';
require_once 'in_val.php';

require '../../scripts/src/Exception.php';
require '../../scripts/src/PHPMailer.php';
require '../../scripts/src/SMTP.php';

$config = require '/var/www/config.php';

function sendJsonResponse($status, $message, $httpCode = 200) {
    header('Content-Type: application/json');
    http_response_code($httpCode);
    
    $response = json_encode(['status' => $status, 'message' => $message]);
    if ($response === false) {
        error_log('JSON encoding failed: ' . json_last_error_msg());
        echo json_encode(['status' => 'error', 'message' => 'Ein Fehler ist aufgetreten.']);
    } else {
        echo $response;
    }
    exit;
}

if (!isset($_POST['csrf_token']) || $_POST['csrf_token'] !== $_SESSION['csrf_token']) {
    sendJsonResponse('error', 'CSRF-Token ungültig');
}

if ($_SERVER["REQUEST_METHOD"] != "POST") {
    sendJsonResponse('error', 'Ungültige Anfrage');
}

$name = sanitizeInput($_POST['name'] ?? '');
$email = sanitizeInput($_POST['email'] ?? '');
$message = sanitizeInput($_POST['message'] ?? '');
$captcha = sanitizeInput($_POST['captcha'] ?? '');

if (empty($name) || empty($email) || empty($message) || empty($captcha)) {
    sendJsonResponse('error', 'Bitte füllen Sie alle Pflichtfelder aus');
}

if (!validateName($name)) {
    sendJsonResponse('error', 'Ungültiger Name');
}

if (!validateEmail($email)) {
    sendJsonResponse('error', 'Ungültige E-Mail-Adresse');
}

if (!validateMessage($message)) {
    sendJsonResponse('error', 'Nachricht muss zwischen 10 und 1000 Zeichen lang sein');
}

if (!validateCaptcha($captcha)) {
    sendJsonResponse('error', 'Falsche Antwort auf die Sicherheitsfrage');
}

$mail = new PHPMailer(true);
$mail->CharSet = 'UTF-8';
$mail->Encoding = 'base64';

try {
    $mail->isSMTP();
    $mail->Host       = $config['smtp_host'];
    $mail->SMTPAuth   = true;
    $mail->Username   = $config['smtp_username'];
    $mail->Password   = $config['smtp_password'];
    $mail->SMTPSecure = PHPMailer::ENCRYPTION_SMTPS;
    $mail->Port       = $config['smtp_port'];

    $mail->setFrom($config['from_email']);
    $mail->addAddress($config['to_email']);

    $mail->isHTML(false);
    $mail->Subject = 'Neue Kontaktanfrage von ' . $name;
    $mailBody = "Name: $name\n";
    $mailBody .= "Email: $email\n";
    $mailBody .= "Nachricht:\n$message";

    $mail->Body = $mailBody;


    $attachments = [];
    $total_size = 0;
    $max_total_size = 15 * 1024 * 1024; 

    if (!empty($_FILES['attachment']['name'][0])) {
        foreach ($_FILES['attachment']['name'] as $key => $name) {
            $file = [
                'name' => $_FILES['attachment']['name'][$key],
                'type' => $_FILES['attachment']['type'][$key],
                'tmp_name' => $_FILES['attachment']['tmp_name'][$key],
                'error' => $_FILES['attachment']['error'][$key],
                'size' => $_FILES['attachment']['size'][$key]
            ];

            if (!validateAttachment($file)) {
                sendJsonResponse('error', 'Ungültige Datei: ' . $name);
            }

            $total_size += $file['size'];
            if ($total_size > $max_total_size) {
                sendJsonResponse('error', 'Die Gesamtgröße der Dateien überschreitet 15 MB');
            }

            $attachments[] = $file;
        }
    }


    foreach ($attachments as $attachment) {
        $mail->addAttachment($attachment['tmp_name'], $attachment['name']);
    }


    $mail->send();
    sendJsonResponse('success', 'Nachricht wurde gesendet');
    } catch (Exception $e) {
    error_log("PHPMailer Error: {$mail->ErrorInfo}");
    error_log("Exception: {$e->getMessage()}");
    error_log("Trace: {$e->getTraceAsString()}");
    sendJsonResponse('error', "Nachricht konnte nicht gesendet werden.", $debugInfo);
}
