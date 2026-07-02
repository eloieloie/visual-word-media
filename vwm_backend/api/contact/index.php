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

$name       = trim($data['name']       ?? '');
$email      = trim($data['email']      ?? '');
$phone      = trim($data['phone']      ?? '');
$subject    = trim($data['subject']    ?? 'General Inquiry');
$message    = trim($data['message']    ?? '');
$newsletter = !empty($data['newsletter']);

if (!$name || !$email || !$message) {
    http_response_code(422);
    echo json_encode(['success' => false, 'message' => 'Name, email, and message are required']);
    exit;
}

if (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
    http_response_code(422);
    echo json_encode(['success' => false, 'message' => 'Invalid email address']);
    exit;
}

$subjectLabel = $subject ?: 'General Inquiry';
$phoneDisplay = $phone ?: '—';
$newsletterDisplay = $newsletter ? 'Yes' : 'No';
$submittedAt  = gmdate('d M Y, H:i') . ' UTC';

// ── 1. Notification email → contact@visualword.in ────────────────────────────
$notifyHtml = buildNotificationEmail($name, $email, $phoneDisplay, $subjectLabel, $message, $newsletterDisplay, $submittedAt);

$notifyResult = sendMail(
    'Visual Word Media <contact@visualword.in>',
    '[Contact Form] ' . $subjectLabel . ' — ' . $name,
    $notifyHtml,
    null,
    [
        'fromName' => $name . ' via VWM Contact Form',
        'from'     => MAIL_FROM_ADDRESS,
        'replyTo'  => $name . ' <' . $email . '>',
    ]
);

if (!$notifyResult['success']) {
    error_log('Contact form notification failed: ' . $notifyResult['message']);
    http_response_code(502);
    echo json_encode(['success' => false, 'message' => 'Could not send your message. Please try again later.']);
    exit;
}

// ── 2. Confirmation email → submitter ────────────────────────────────────────
$confirmHtml = buildConfirmationEmail($name, $subjectLabel, $message);

$confirmResult = sendMail(
    $name . ' <' . $email . '>',
    'We received your message — Visual Word Media',
    $confirmHtml,
    null,
    [
        'fromName' => 'Visual Word Media',
        'from'     => MAIL_FROM_ADDRESS,
        'replyTo'  => 'contact@visualword.in',
    ]
);

if (!$confirmResult['success']) {
    error_log('Contact form confirmation failed: ' . $confirmResult['message']);
    // Non-fatal — notification already sent; don't fail the user response
}

echo json_encode(['success' => true, 'message' => 'Message sent successfully']);

// ── Email builders ────────────────────────────────────────────────────────────

function buildNotificationEmail(
    string $name,
    string $email,
    string $phone,
    string $subject,
    string $message,
    string $newsletter,
    string $submittedAt
): string {
    $nameSafe       = htmlspecialchars($name,       ENT_QUOTES, 'UTF-8');
    $emailSafe      = htmlspecialchars($email,      ENT_QUOTES, 'UTF-8');
    $phoneSafe      = htmlspecialchars($phone,      ENT_QUOTES, 'UTF-8');
    $subjectSafe    = htmlspecialchars($subject,    ENT_QUOTES, 'UTF-8');
    $messageSafe    = nl2br(htmlspecialchars($message, ENT_QUOTES, 'UTF-8'));
    $newsletterSafe = htmlspecialchars($newsletter, ENT_QUOTES, 'UTF-8');
    $timeSafe       = htmlspecialchars($submittedAt, ENT_QUOTES, 'UTF-8');

    return <<<HTML
<!DOCTYPE html>
<html lang="en">
<head>
<meta charset="UTF-8" />
<meta name="viewport" content="width=device-width, initial-scale=1.0" />
<title>Contact Form Message</title>
</head>
<body style="margin:0;padding:0;background:#f4f4f0;font-family:'Segoe UI',Arial,sans-serif;">
  <table width="100%" cellpadding="0" cellspacing="0" style="background:#f4f4f0;padding:32px 16px;">
    <tr><td align="center">
      <table width="600" cellpadding="0" cellspacing="0" style="max-width:600px;width:100%;background:#ffffff;border-radius:10px;overflow:hidden;box-shadow:0 4px 24px rgba(26,45,90,0.10);">

        <!-- Header -->
        <tr>
          <td style="background:#1a2d5a;padding:36px 40px 28px;text-align:center;">
            <p style="margin:0 0 6px;color:#c9a227;font-size:11px;font-weight:700;letter-spacing:0.18em;text-transform:uppercase;">Visual Word Media</p>
            <h1 style="margin:0;color:#ffffff;font-size:22px;font-weight:700;letter-spacing:0.01em;">New Contact Form Message</h1>
            <p style="margin:10px 0 0;color:rgba(255,255,255,0.65);font-size:13px;">Submitted {$timeSafe}</p>
          </td>
        </tr>

        <!-- Subject badge -->
        <tr>
          <td style="background:#c9a227;padding:12px 40px;text-align:center;">
            <p style="margin:0;color:#1a2d5a;font-size:13px;font-weight:700;letter-spacing:0.08em;text-transform:uppercase;">{$subjectSafe}</p>
          </td>
        </tr>

        <!-- Sender details -->
        <tr>
          <td style="padding:32px 40px 0;">
            <p style="margin:0 0 20px;color:#1a2d5a;font-size:13px;font-weight:700;letter-spacing:0.12em;text-transform:uppercase;border-bottom:2px solid #c9a227;padding-bottom:10px;">Sender Details</p>
            <table width="100%" cellpadding="0" cellspacing="0">
              <tr>
                <td width="36%" style="padding:8px 0;color:#6b7280;font-size:13px;font-weight:600;">Full Name</td>
                <td style="padding:8px 0;color:#111827;font-size:14px;">{$nameSafe}</td>
              </tr>
              <tr style="background:#f9f8f6;">
                <td width="36%" style="padding:8px 10px;color:#6b7280;font-size:13px;font-weight:600;">Email</td>
                <td style="padding:8px 10px;font-size:14px;"><a href="mailto:{$emailSafe}" style="color:#1a2d5a;text-decoration:underline;">{$emailSafe}</a></td>
              </tr>
              <tr>
                <td width="36%" style="padding:8px 0;color:#6b7280;font-size:13px;font-weight:600;">Phone / WhatsApp</td>
                <td style="padding:8px 0;color:#111827;font-size:14px;">{$phoneSafe}</td>
              </tr>
              <tr style="background:#f9f8f6;">
                <td width="36%" style="padding:8px 10px;color:#6b7280;font-size:13px;font-weight:600;">Newsletter</td>
                <td style="padding:8px 10px;color:#111827;font-size:14px;">{$newsletterSafe}</td>
              </tr>
            </table>
          </td>
        </tr>

        <!-- Message body -->
        <tr>
          <td style="padding:28px 40px 0;">
            <p style="margin:0 0 14px;color:#1a2d5a;font-size:13px;font-weight:700;letter-spacing:0.12em;text-transform:uppercase;border-bottom:2px solid #c9a227;padding-bottom:10px;">Message</p>
            <div style="background:#f9f8f6;border-left:4px solid #c9a227;border-radius:4px;padding:20px 22px;color:#374151;font-size:15px;line-height:1.75;">
              {$messageSafe}
            </div>
          </td>
        </tr>

        <!-- Reply CTA -->
        <tr>
          <td style="padding:28px 40px 36px;text-align:center;">
            <a href="mailto:{$emailSafe}?subject=Re: {$subjectSafe}" style="display:inline-block;background:#c9a227;color:#1a2d5a;font-size:14px;font-weight:700;padding:13px 32px;border-radius:6px;text-decoration:none;letter-spacing:0.04em;">Reply to {$nameSafe}</a>
            <p style="margin:18px 0 0;color:#9ca3af;font-size:12px;">Or simply hit <strong>Reply</strong> in your email client — it goes directly to the sender.</p>
          </td>
        </tr>

        <!-- Footer -->
        <tr>
          <td style="background:#f4f4f0;padding:20px 40px;text-align:center;border-top:1px solid #e5e7eb;">
            <p style="margin:0;color:#9ca3af;font-size:12px;">Visual Word Media · Hyderabad, Telangana, India</p>
            <p style="margin:6px 0 0;color:#9ca3af;font-size:12px;">This notification was sent from the Contact page at <a href="https://visualword.in" style="color:#1a2d5a;">visualword.in</a></p>
          </td>
        </tr>

      </table>
    </td></tr>
  </table>
</body>
</html>
HTML;
}

function buildConfirmationEmail(string $name, string $subject, string $message): string
{
    $nameSafe    = htmlspecialchars($name,    ENT_QUOTES, 'UTF-8');
    $subjectSafe = htmlspecialchars($subject, ENT_QUOTES, 'UTF-8');
    $messageSafe = nl2br(htmlspecialchars($message, ENT_QUOTES, 'UTF-8'));

    return <<<HTML
<!DOCTYPE html>
<html lang="en">
<head>
<meta charset="UTF-8" />
<meta name="viewport" content="width=device-width, initial-scale=1.0" />
<title>We received your message</title>
</head>
<body style="margin:0;padding:0;background:#f4f4f0;font-family:'Segoe UI',Arial,sans-serif;">
  <table width="100%" cellpadding="0" cellspacing="0" style="background:#f4f4f0;padding:32px 16px;">
    <tr><td align="center">
      <table width="600" cellpadding="0" cellspacing="0" style="max-width:600px;width:100%;background:#ffffff;border-radius:10px;overflow:hidden;box-shadow:0 4px 24px rgba(26,45,90,0.10);">

        <!-- Header -->
        <tr>
          <td style="background:#1a2d5a;padding:40px 40px 32px;text-align:center;">
            <p style="margin:0 0 8px;color:#c9a227;font-size:11px;font-weight:700;letter-spacing:0.18em;text-transform:uppercase;">Visual Word Media</p>
            <h1 style="margin:0;color:#ffffff;font-size:24px;font-weight:700;">We Received Your Message</h1>
          </td>
        </tr>

        <!-- Gold divider -->
        <tr><td style="background:#c9a227;height:4px;font-size:0;line-height:0;">&nbsp;</td></tr>

        <!-- Greeting -->
        <tr>
          <td style="padding:36px 40px 0;color:#374151;font-size:15px;line-height:1.75;">
            <p style="margin:0 0 16px;">Dear <strong style="color:#1a2d5a;">{$nameSafe}</strong>,</p>
            <p style="margin:0 0 16px;">Thank you for reaching out to <strong>Visual Word Media</strong>. We have received your message regarding <em>"{$subjectSafe}"</em> and our team will respond to you as soon as possible.</p>
            <p style="margin:0;">We are grateful for your interest in our ministry and look forward to connecting with you.</p>
          </td>
        </tr>

        <!-- Message recap -->
        <tr>
          <td style="padding:28px 40px 0;">
            <p style="margin:0 0 14px;color:#1a2d5a;font-size:12px;font-weight:700;letter-spacing:0.12em;text-transform:uppercase;">Your Message</p>
            <div style="background:#f9f8f6;border-left:4px solid #c9a227;border-radius:4px;padding:18px 20px;color:#6b7280;font-size:14px;line-height:1.75;">
              {$messageSafe}
            </div>
          </td>
        </tr>

        <!-- Scripture -->
        <tr>
          <td style="padding:28px 40px 0;">
            <div style="background:#1a2d5a;border-radius:8px;padding:24px 28px;text-align:center;">
              <p style="margin:0;color:rgba(255,255,255,0.85);font-size:14px;font-style:italic;line-height:1.8;">"Cast all your anxiety on him because he cares for you."</p>
              <p style="margin:10px 0 0;color:#c9a227;font-size:12px;font-weight:700;letter-spacing:0.1em;">— 1 Peter 5:7</p>
            </div>
          </td>
        </tr>

        <!-- Contact info -->
        <tr>
          <td style="padding:28px 40px 36px;text-align:center;">
            <p style="margin:0 0 6px;color:#6b7280;font-size:13px;">If you need to reach us directly, reply to this email or visit</p>
            <a href="https://visualword.in/#/contact" style="color:#1a2d5a;font-size:13px;font-weight:600;">visualword.in/contact</a>
          </td>
        </tr>

        <!-- Footer -->
        <tr>
          <td style="background:#f4f4f0;padding:20px 40px;text-align:center;border-top:1px solid #e5e7eb;">
            <p style="margin:0;color:#9ca3af;font-size:12px;">Visual Word Media · Hyderabad, Telangana, India</p>
            <p style="margin:6px 0 0;color:#9ca3af;font-size:12px;">Serving today's generation through truth, creativity &amp; discipleship</p>
          </td>
        </tr>

      </table>
    </td></tr>
  </table>
</body>
</html>
HTML;
}
