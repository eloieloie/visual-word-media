<?php
require_once __DIR__ . '/../../includes/cors.php';
require_once __DIR__ . '/../../config/db.php';
require_once __DIR__ . '/../../includes/auth.php';
require_once __DIR__ . '/../../includes/mailer.php';

if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
    http_response_code(405);
    die(json_encode(['success' => false, 'message' => 'Method not allowed']));
}

// Only admins may send arbitrary mail through the API
requireAdmin();

$input   = json_decode(file_get_contents('php://input'), true) ?? [];
$to      = trim($input['to'] ?? '');
$subject = trim($input['subject'] ?? '');
$html    = $input['html'] ?? '';
$text    = isset($input['text']) ? (string) $input['text'] : null;

// Accept "Name <addr@x.com>" or a bare address — validate the address part
if (preg_match('/<([^>]+)>/', $to, $m)) {
    $addr = trim($m[1]);
} else {
    $addr = $to;
}

if (!$to || !$subject || ($html === '' && ($text === null || $text === ''))) {
    http_response_code(400);
    die(json_encode(['success' => false, 'message' => 'to, subject, and a body (html or text) are required']));
}

if (!filter_var($addr, FILTER_VALIDATE_EMAIL)) {
    http_response_code(400);
    die(json_encode(['success' => false, 'message' => 'Invalid recipient email address']));
}

// If only text was supplied, use it as the HTML body too
if ($html === '') {
    $html = nl2br(htmlspecialchars($text, ENT_QUOTES, 'UTF-8'));
}

$opts = [];
if (!empty($input['replyTo']))  { $opts['replyTo']  = trim($input['replyTo']); }
if (!empty($input['fromName'])) { $opts['fromName'] = trim($input['fromName']); }

$result = sendMail($to, $subject, $html, $text, $opts);

if (!$result['success']) {
    http_response_code(502);
    die(json_encode(['success' => false, 'message' => $result['message']]));
}

echo json_encode([
    'success' => true,
    'message' => 'Email sent successfully',
    'data'    => ['id' => $result['id'] ?? null],
]);
