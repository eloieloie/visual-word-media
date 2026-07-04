<?php
require_once __DIR__ . '/../../includes/cors.php';
require_once __DIR__ . '/../../config/db.php';
require_once __DIR__ . '/../../includes/mailer.php';

if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
    http_response_code(405);
    die(json_encode(['success' => false, 'message' => 'Method not allowed']));
}

$input = json_decode(file_get_contents('php://input'), true);

// ── Required fields ───────────────────────────────────────────────────────────
$name          = trim($input['name']           ?? '');
$email         = trim($input['email']          ?? '');
$mobile        = trim($input['mobile']         ?? '');
$gender        = trim($input['gender']         ?? '');
$churchName    = trim($input['church_name']    ?? '');
$churchAddress = trim($input['church_address'] ?? '');
$referralName  = trim($input['referral_name']  ?? '');
$referralMob   = trim($input['referral_mobile']?? '');
$username      = trim($input['username']       ?? '');
$password      = $input['password']            ?? '';
$confirm       = $input['confirm_password']    ?? '';

$required = compact('name','email','mobile','gender','churchName','churchAddress','referralName','referralMob','username','password','confirm');
foreach ($required as $field => $val) {
    if ($val === '' || $val === null) {
        http_response_code(422);
        die(json_encode(['success' => false, 'message' => 'All fields are required']));
    }
}

// ── Validate format ───────────────────────────────────────────────────────────
if (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
    http_response_code(422);
    die(json_encode(['success' => false, 'message' => 'Invalid email address']));
}

if (strlen($password) < 8) {
    http_response_code(422);
    die(json_encode(['success' => false, 'message' => 'Password must be at least 8 characters']));
}

if ($password !== $confirm) {
    http_response_code(422);
    die(json_encode(['success' => false, 'message' => 'Passwords do not match']));
}

if (!in_array($gender, ['male', 'female', 'other'], true)) {
    http_response_code(422);
    die(json_encode(['success' => false, 'message' => 'Invalid gender value']));
}

// Sanitise username: allow alphanumeric + _ -
if (!preg_match('/^[a-zA-Z0-9_\-]{3,50}$/', $username)) {
    http_response_code(422);
    die(json_encode(['success' => false, 'message' => 'Username must be 3–50 characters and contain only letters, numbers, _ or -']));
}

// ── Duplicate checks ──────────────────────────────────────────────────────────
$db = getDB();

$chkEmail = $db->prepare('SELECT id FROM users WHERE email = ?');
$chkEmail->execute([$email]);
if ($chkEmail->fetch()) {
    http_response_code(409);
    die(json_encode(['success' => false, 'message' => 'An account with this email already exists']));
}

$chkUser = $db->prepare('SELECT id FROM users WHERE username = ?');
$chkUser->execute([$username]);
if ($chkUser->fetch()) {
    http_response_code(409);
    die(json_encode(['success' => false, 'message' => 'That username is already taken']));
}

// ── Insert ────────────────────────────────────────────────────────────────────
$hash = password_hash($password, PASSWORD_BCRYPT);

$stmt = $db->prepare(
    'INSERT INTO users
        (name, username, email, mobile, gender, church_name, church_address,
         referral_name, referral_mobile, password_hash, role, force_password_reset)
     VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?, ?, \'registrant\', 0)'
);
$stmt->execute([
    $name, $username, $email, $mobile, $gender,
    $churchName, $churchAddress, $referralName, $referralMob, $hash,
]);

// ── Welcome email ─────────────────────────────────────────────────────────────
$safeName  = htmlspecialchars($name, ENT_QUOTES, 'UTF-8');
$safeEmail = htmlspecialchars($email, ENT_QUOTES, 'UTF-8');
$welcomeHtml = <<<HTML
<div style="font-family:Arial,Helvetica,sans-serif;max-width:520px;margin:0 auto;color:#222">
  <h2 style="color:#1a2d5a">Welcome to Visual Word Media, {$safeName}!</h2>
  <p>Thank you for registering. Your account is currently <strong>pending approval</strong> by our admin team.</p>
  <p>Once approved, you will receive another email with instructions to complete your login.</p>
  <div style="background:#fffdf4;border-left:4px solid #c8a84b;padding:16px 20px;margin:20px 0;font-size:0.95rem;line-height:1.8;color:#555;border-radius:4px;font-style:italic">
    "I press toward the goal for the prize of the upward call of God in Christ Jesus." — Philippians 3:14
  </div>
  <p>If you have any questions, contact us at <a href="mailto:info@visualword.in">info@visualword.in</a>.</p>
  <p style="margin-top:28px;color:#1a2d5a;font-weight:700">Visual Word Media Team</p>
  <hr style="border:none;border-top:1px solid #eee;margin:24px 0">
  <p style="font-size:12px;color:#999">You registered at <a href="https://visualword.in">visualword.in</a>. If this was not you, please contact us immediately.</p>
</div>
HTML;

$mailResult = sendMail(
    $name . ' <' . $email . '>',
    'Welcome to Visual Word Media — Account Pending Approval',
    $welcomeHtml,
    null,
    ['fromName' => 'Visual Word Media', 'from' => MAIL_FROM_ADDRESS]
);

if (!$mailResult['success']) {
    error_log('[register] welcome email failed for ' . $email . ': ' . $mailResult['message']);
}

echo json_encode([
    'success' => true,
    'message' => 'Registration submitted. Please check your email — your account is pending admin approval.',
]);
