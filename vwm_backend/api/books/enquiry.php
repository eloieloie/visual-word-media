<?php
require_once __DIR__ . '/../../includes/cors.php';
require_once __DIR__ . '/../../includes/mailer.php';

if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
    http_response_code(405);
    die(json_encode(['success' => false, 'message' => 'Method not allowed']));
}

$data     = json_decode(file_get_contents('php://input'), true);
$name     = trim($data['name']      ?? '');
$email    = trim($data['email']     ?? '');
$phone    = trim($data['phone']     ?? '');
$bookName = trim($data['book_name'] ?? '');

if (!$name || !$email || !$bookName) {
    http_response_code(422);
    die(json_encode(['success' => false, 'message' => 'Name, email, and book name are required']));
}

if (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
    http_response_code(422);
    die(json_encode(['success' => false, 'message' => 'Invalid email address']));
}

$submittedAt = gmdate('d M Y, H:i') . ' UTC';
$safeName    = htmlspecialchars($name,     ENT_QUOTES, 'UTF-8');
$safeEmail   = htmlspecialchars($email,    ENT_QUOTES, 'UTF-8');
$safePhone   = htmlspecialchars($phone ?: '—', ENT_QUOTES, 'UTF-8');
$safeBook    = htmlspecialchars($bookName, ENT_QUOTES, 'UTF-8');

$html = <<<HTML
<div style="font-family:Arial,Helvetica,sans-serif;max-width:600px;margin:0 auto;color:#222">
  <h2 style="color:#1a2d5a">Book Enquiry — {$safeBook}</h2>
  <table style="width:100%;border-collapse:collapse;margin-bottom:20px">
    <tr><td style="padding:8px 12px;background:#f7f9ff;font-weight:700;width:140px;color:#1a2d5a">Name</td>
        <td style="padding:8px 12px;border:1px solid #e5e9f5">{$safeName}</td></tr>
    <tr><td style="padding:8px 12px;background:#f7f9ff;font-weight:700;color:#1a2d5a">Email</td>
        <td style="padding:8px 12px;border:1px solid #e5e9f5"><a href="mailto:{$safeEmail}">{$safeEmail}</a></td></tr>
    <tr><td style="padding:8px 12px;background:#f7f9ff;font-weight:700;color:#1a2d5a">Phone</td>
        <td style="padding:8px 12px;border:1px solid #e5e9f5">{$safePhone}</td></tr>
    <tr><td style="padding:8px 12px;background:#f7f9ff;font-weight:700;color:#1a2d5a">Book</td>
        <td style="padding:8px 12px;border:1px solid #e5e9f5"><strong>{$safeBook}</strong></td></tr>
    <tr><td style="padding:8px 12px;background:#f7f9ff;font-weight:700;color:#1a2d5a">Submitted</td>
        <td style="padding:8px 12px;border:1px solid #e5e9f5">{$submittedAt}</td></tr>
  </table>
</div>
HTML;

$result = sendMail(
    'Visual Word Media Books <books@visualword.in>',
    '[Book Enquiry] ' . $bookName . ' — ' . $name,
    $html,
    null,
    [
        'fromName' => 'VWM Books Form',
        'from'     => MAIL_FROM_ADDRESS,
        'replyTo'  => $name . ' <' . $email . '>',
    ]
);

if (!$result['success']) {
    error_log('[books/enquiry] failed: ' . $result['message']);
    http_response_code(502);
    die(json_encode(['success' => false, 'message' => 'Could not send enquiry. Please try again.']));
}

echo json_encode(['success' => true, 'message' => 'Your enquiry has been sent. We will be in touch soon!']);
