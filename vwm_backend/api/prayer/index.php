<?php
require_once __DIR__ . '/../../includes/cors.php';
require_once __DIR__ . '/../../includes/mailer.php';

if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
    http_response_code(405);
    echo json_encode(['success' => false, 'message' => 'Method not allowed']);
    exit;
}

$data = json_decode(file_get_contents('php://input'), true);
if (!$data) {
    http_response_code(400);
    echo json_encode(['success' => false, 'message' => 'Invalid request body']);
    exit;
}

$name        = trim($data['name']        ?? '');
$email       = trim($data['email']       ?? '');
$phone       = trim($data['phone']       ?? '');
$request     = trim($data['request']     ?? '');
$confidential = !empty($data['confidential']);

if (!$name || !$email || !$request) {
    http_response_code(422);
    echo json_encode(['success' => false, 'message' => 'Name, email, and prayer request are required']);
    exit;
}

if (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
    http_response_code(422);
    echo json_encode(['success' => false, 'message' => 'Invalid email address']);
    exit;
}

$submittedAt       = gmdate('d M Y, H:i') . ' UTC';
$phoneDisplay      = $phone ?: '—';
$confidentialLabel = $confidential ? 'Yes — keep confidential' : 'No';

$safeName    = htmlspecialchars($name,    ENT_QUOTES, 'UTF-8');
$safeEmail   = htmlspecialchars($email,   ENT_QUOTES, 'UTF-8');
$safePhone   = htmlspecialchars($phoneDisplay, ENT_QUOTES, 'UTF-8');
$safeRequest = nl2br(htmlspecialchars($request, ENT_QUOTES, 'UTF-8'));

// ── 1. Notification → prayer@visualword.in ────────────────────────────────────
$notifyHtml = <<<HTML
<div style="font-family:Arial,Helvetica,sans-serif;max-width:620px;margin:0 auto;color:#222">
  <h2 style="color:#1a2d5a">New Prayer Request</h2>
  <table style="width:100%;border-collapse:collapse;margin-bottom:20px">
    <tr><td style="padding:8px 12px;background:#f7f9ff;font-weight:700;width:180px;color:#1a2d5a">Name</td>
        <td style="padding:8px 12px;border:1px solid #e5e9f5">{$safeName}</td></tr>
    <tr><td style="padding:8px 12px;background:#f7f9ff;font-weight:700;color:#1a2d5a">Email</td>
        <td style="padding:8px 12px;border:1px solid #e5e9f5"><a href="mailto:{$safeEmail}">{$safeEmail}</a></td></tr>
    <tr><td style="padding:8px 12px;background:#f7f9ff;font-weight:700;color:#1a2d5a">Phone</td>
        <td style="padding:8px 12px;border:1px solid #e5e9f5">{$safePhone}</td></tr>
    <tr><td style="padding:8px 12px;background:#f7f9ff;font-weight:700;color:#1a2d5a">Confidential?</td>
        <td style="padding:8px 12px;border:1px solid #e5e9f5">{$confidentialLabel}</td></tr>
    <tr><td style="padding:8px 12px;background:#f7f9ff;font-weight:700;color:#1a2d5a">Submitted</td>
        <td style="padding:8px 12px;border:1px solid #e5e9f5">{$submittedAt}</td></tr>
  </table>
  <h3 style="color:#1a2d5a;margin-bottom:10px">Prayer Request</h3>
  <div style="background:#fffdf4;border-left:4px solid #c8a84b;padding:16px 20px;font-size:0.97rem;line-height:1.8;color:#333;border-radius:4px">
    {$safeRequest}
  </div>
  <hr style="border:none;border-top:1px solid #eee;margin:28px 0">
  <p style="font-size:12px;color:#999">This request was submitted via the Visual Word Media prayer form at visualword.in</p>
</div>
HTML;

$notifyResult = sendMail(
    'Visual Word Media Prayer Team <prayer@visualword.in>',
    '[Prayer Request] ' . $name,
    $notifyHtml,
    null,
    [
        'fromName' => 'VWM Prayer Form',
        'from'     => MAIL_FROM_ADDRESS,
        'replyTo'  => $name . ' <' . $email . '>',
    ]
);

if (!$notifyResult['success']) {
    error_log('[prayer] notification to prayer@visualword.in failed: ' . $notifyResult['message']);
    http_response_code(502);
    echo json_encode(['success' => false, 'message' => 'Could not send your prayer request. Please try again later.']);
    exit;
}

// ── 2. Acknowledgement → requestor ───────────────────────────────────────────
$ackHtml = <<<HTML
<div style="font-family:Arial,Helvetica,sans-serif;max-width:520px;margin:0 auto;color:#222">
  <h2 style="color:#1a2d5a">We Received Your Prayer Request</h2>
  <p>Dear <strong>{$safeName}</strong>,</p>
  <p>Thank you for reaching out to us. We have received your prayer request and our prayer team will be lifting you up in prayer.</p>
  <div style="background:#fffdf4;border-left:4px solid #c8a84b;padding:16px 20px;margin:20px 0;font-size:0.95rem;line-height:1.8;color:#555;border-radius:4px;font-style:italic">
    "The effective, fervent prayer of a righteous man avails much." — James 5:16
  </div>
  <p>If you'd like to follow up or share more details, you can reply to this email or contact us at <a href="mailto:prayer@visualword.in">prayer@visualword.in</a>.</p>
  <p>May God's grace and peace be with you.</p>
  <p style="margin-top:28px;color:#1a2d5a;font-weight:700">Visual Word Media Prayer Team</p>
  <hr style="border:none;border-top:1px solid #eee;margin:24px 0">
  <p style="font-size:12px;color:#999">You submitted this request via visualword.in. If this was not you, you can safely ignore this email.</p>
</div>
HTML;

$ackResult = sendMail(
    $name . ' <' . $email . '>',
    'We received your prayer request — Visual Word Media',
    $ackHtml,
    null,
    [
        'fromName' => 'Visual Word Media Prayer Team',
        'from'     => MAIL_FROM_ADDRESS,
        'replyTo'  => 'prayer@visualword.in',
    ]
);

if (!$ackResult['success']) {
    error_log('[prayer] acknowledgement to ' . $email . ' failed: ' . $ackResult['message']);
    // Non-fatal — the main notification went out; just log this
}

echo json_encode([
    'success' => true,
    'message' => 'Your prayer request has been received. We will be praying for you!',
]);
