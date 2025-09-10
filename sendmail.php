<?php
// sendmail.php – PHPMailer + SMTP
header('Content-Type: application/json; charset=utf-8');

// Nur POST zulassen
if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
  http_response_code(405);
  echo json_encode(['ok' => false, 'error' => 'Method not allowed']);
  exit;
}

// Config laden
$config = require __DIR__ . '/config.php';

// Rate-Limit
require __DIR__ . '/ratelimit.php';
$ipKey = 'contact_' . ($_SERVER['REMOTE_ADDR'] ?? 'unknown');
if (!rate_limit_ok($ipKey, $config['rate_limit']['requests'], $config['rate_limit']['per_sec'])) {
  http_response_code(429);
  echo json_encode(['ok' => false, 'error' => 'Zu viele Anfragen. Bitte später erneut versuchen.']);
  exit;
}

// Honeypot
$hpField = $config['honeypot_field'] ?? 'website';
if (!empty($_POST[$hpField] ?? '')) {
  // Bot – melde trotzdem Erfolg, um Bots nicht „schlauer“ zu machen
  echo json_encode(['ok' => true]);
  exit;
}

// Eingaben
$from = filter_input(INPUT_POST, 'fromEmail', FILTER_VALIDATE_EMAIL);
$message = trim((string)($_POST['message'] ?? ''));

// Validierung
if (!$from || $message === '') {
  http_response_code(400);
  echo json_encode(['ok' => false, 'error' => 'Bitte gültige E-Mail und Nachricht angeben.']);
  exit;
}

// Länge begrenzen
$maxLen = (int)($config['max_message_len'] ?? 5000);
$message = mb_substr($message, 0, $maxLen);

// PHPMailer laden (Composer)
$autoload = __DIR__ . '/vendor/autoload.php';
if (!is_file($autoload)) {
  http_response_code(500);
  echo json_encode(['ok' => false, 'error' => 'Serverfehler: Mailer nicht installiert (vendor fehlt).']);
  exit;
}
require $autoload;

use PHPMailer\PHPMailer\PHPMailer;
use PHPMailer\PHPMailer\Exception;

$mail = new PHPMailer(true);

try {
  // SMTP-Konfiguration
  $mail->isSMTP();
  $mail->Host       = $config['smtp']['host'];
  $mail->SMTPAuth   = true;
  $mail->Username   = $config['smtp']['username'];
  $mail->Password   = $config['smtp']['password'];
  $enc              = strtolower((string)$config['smtp']['encryption']);
  if ($enc === 'ssl') {
    $mail->SMTPSecure = PHPMailer::ENCRYPTION_SMTPS;
    $mail->Port       = (int)($config['smtp']['port'] ?? 465);
  } else {
    $mail->SMTPSecure = PHPMailer::ENCRYPTION_STARTTLS;
    $mail->Port       = (int)($config['smtp']['port'] ?? 587);
  }

  // Absender/Empfänger
  $mail->setFrom($config['from_email'], $config['from_name']);
  $mail->addAddress($config['to_email'], $config['to_name']);
  $mail->addReplyTo($from);

  // Inhalt
  $mail->isHTML(false);
  $mail->Subject = 'Neue Nachricht von deiner Website';
  $body = "Von: {$from}\nIP: " . ($_SERVER['REMOTE_ADDR'] ?? 'unbekannt') . "\n\nNachricht:\n{$message}";
  $mail->Body = $body;

  $mail->send();
  echo json_encode(['ok' => true]);
} catch (Exception $e) {
  http_response_code(500);
  echo json_encode(['ok' => false, 'error' => 'SMTP-Fehler: ' . $mail->ErrorInfo]);
}
