<?php
require_once __DIR__ . '/../../includes/cors.php';
require_once __DIR__ . '/../../config/db.php';
require_once __DIR__ . '/../../config/mail.php';
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

$name         = trim($data['name']         ?? '');
$mobile       = trim($data['mobile']       ?? '');
$email        = trim($data['email']        ?? '');
$gender       = trim($data['gender']       ?? '');
$dob          = trim($data['dob']          ?? '');
$whatsapp     = trim($data['whatsapp']     ?? '');
$city         = trim($data['city']         ?? '');
$state        = trim($data['state']        ?? '');
$country      = trim($data['country']      ?? '');
$churchActive = trim($data['churchActive'] ?? '');
$churchName   = trim($data['churchName']   ?? '');
$pastor       = trim($data['pastor']       ?? '');
$testimony    = trim($data['testimony']    ?? '');
$skills       = trim($data['skills']       ?? '');
$occupation   = trim($data['occupation']   ?? '');
$organization = trim($data['organization'] ?? '');
$motivation   = trim($data['motivation']   ?? '');

$required = [
    'name'         => $name,
    'mobile'       => $mobile,
    'email'        => $email,
    'gender'       => $gender,
    'dob'          => $dob,
    'whatsapp'     => $whatsapp,
    'city'         => $city,
    'state'        => $state,
    'country'      => $country,
    'churchActive' => $churchActive,
    'churchName'   => $churchName,
    'pastor'       => $pastor,
    'testimony'    => $testimony,
    'skills'       => $skills,
    'occupation'   => $occupation,
    'organization' => $organization,
    'motivation'   => $motivation,
];

$missing = array_keys(array_filter($required, fn($v) => $v === ''));
if ($missing) {
    http_response_code(422);
    echo json_encode([
        'success' => false,
        'message' => 'Missing required fields: ' . implode(', ', $missing),
    ]);
    exit;
}

if (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
    http_response_code(422);
    echo json_encode(['success' => false, 'message' => 'Invalid email address']);
    exit;
}

$db   = getDB();

$verificationToken = bin2hex(random_bytes(32));

$stmt = $db->prepare("
    INSERT INTO volunteer_registrations
        (name, gender, dob, mobile, whatsapp, email, verification_token, city, state, country,
         is_believer, church_active, church_name, pastor_name, personal_testimony,
         ministry_areas, service_type, availability,
         skills, occupation, organization, motivation, comments, declared)
    VALUES (?,?,?,?,?,?,?,?,?,?,?,?,?,?,?,?,?,?,?,?,?,?,?,?)
");

$dob = $dob !== '' ? $dob : null;

$stmt->execute([
    $name,
    $gender,
    $dob,
    $mobile,
    $whatsapp,
    $email,
    $verificationToken,
    $city,
    $state,
    $country,
    $churchActive === 'Yes' ? 1 : 0,
    $churchActive === 'Yes' ? 1 : 0,
    $churchName,
    $pastor,
    $testimony,
    json_encode($data['selectedAreas'] ?? []),
    json_encode($data['serviceType']   ?? []),
    json_encode($data['availability']  ?? []),
    $skills,
    $occupation,
    $organization,
    $motivation,
    trim($data['comments'] ?? ''),
    !empty($data['declared']) ? 1 : 0,
]);

// Send the email-verification link. Prefer the request origin in local dev,
// otherwise use the configured app URL.
$origin = $_SERVER['HTTP_ORIGIN'] ?? '';
if (preg_match('/^https?:\/\/(localhost|127\.0\.0\.1)(:\d+)?$/i', $origin)) {
    $base = $origin;
} else {
    $base = rtrim(APP_FRONTEND_URL, '/');
}
$verifyUrl = $base . '/#/verify-email?token=' . rawurlencode($verificationToken);

$mail = sendMail(
    $email,
    'Verify your email — Visual Word Media',
    buildVerificationEmail($name, $verifyUrl)
);
if (!$mail['success']) {
    error_log('[volunteers] verification mail failed for ' . $email . ': ' . $mail['message']);
}

echo json_encode([
    'success' => true,
    'message' => 'Registration submitted. Please check your email to verify your address.',
]);

/**
 * Build the HTML body for the volunteer email-verification message.
 */
function buildVerificationEmail(string $name, string $verifyUrl): string
{
    $safeName = htmlspecialchars($name, ENT_QUOTES, 'UTF-8');
    $safeUrl  = htmlspecialchars($verifyUrl, ENT_QUOTES, 'UTF-8');
    return <<<HTML
<div style="font-family:Arial,Helvetica,sans-serif;max-width:520px;margin:0 auto;color:#222">
  <h2 style="color:#1a2d5a">Welcome, {$safeName}!</h2>
  <p>Thank you for registering to serve with Visual Word Media Mission.</p>
  <p>Please confirm your email address by clicking the button below.</p>
  <p style="text-align:center;margin:28px 0">
    <a href="{$safeUrl}"
       style="background:#1a2d5a;color:#fff;text-decoration:none;padding:12px 28px;border-radius:6px;display:inline-block">
      Verify My Email
    </a>
  </p>
  <p style="font-size:13px;color:#666">If the button doesn't work, copy and paste this link into your browser:</p>
  <p style="font-size:13px;word-break:break-all"><a href="{$safeUrl}">{$safeUrl}</a></p>
  <hr style="border:none;border-top:1px solid #eee;margin:24px 0">
  <p style="font-size:13px;color:#555">After your email is verified, our team will review your registration. Once approved, your login credentials will be emailed to you.</p>
  <p style="font-size:12px;color:#999">If you didn't register, you can safely ignore this email.</p>
</div>
HTML;
}
