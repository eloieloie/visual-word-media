<?php
require_once __DIR__ . '/../../includes/cors.php';
require_once __DIR__ . '/../../config/db.php';

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

$name   = trim($data['name']   ?? '');
$mobile = trim($data['mobile'] ?? '');
$email  = trim($data['email']  ?? '');

if (!$name || !$mobile || !$email) {
    http_response_code(422);
    echo json_encode(['success' => false, 'message' => 'Name, mobile, and email are required']);
    exit;
}

if (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
    http_response_code(422);
    echo json_encode(['success' => false, 'message' => 'Invalid email address']);
    exit;
}

$db   = getDB();
$stmt = $db->prepare("
    INSERT INTO volunteer_registrations
        (name, gender, dob, mobile, whatsapp, email, city, state, country,
         is_believer, church_active, church_name, pastor_name, personal_testimony,
         ministry_areas, service_type, availability,
         skills, occupation, organization, motivation, comments, declared)
    VALUES (?,?,?,?,?,?,?,?,?,?,?,?,?,?,?,?,?,?,?,?,?,?,?)
");

$dob = !empty($data['dob']) ? $data['dob'] : null;

$stmt->execute([
    $name,
    trim($data['gender']      ?? ''),
    $dob,
    $mobile,
    trim($data['whatsapp']    ?? ''),
    $email,
    trim($data['city']        ?? ''),
    trim($data['state']       ?? ''),
    trim($data['country']     ?? ''),
    ($data['believer']     ?? '') === 'Yes' ? 1 : 0,
    ($data['churchActive'] ?? '') === 'Yes' ? 1 : 0,
    trim($data['churchName']  ?? ''),
    trim($data['pastor']      ?? ''),
    trim($data['testimony']   ?? ''),
    json_encode($data['selectedAreas'] ?? []),
    json_encode($data['serviceType']   ?? []),
    json_encode($data['availability']  ?? []),
    trim($data['skills']       ?? ''),
    trim($data['occupation']   ?? ''),
    trim($data['organization'] ?? ''),
    trim($data['motivation']   ?? ''),
    trim($data['comments']     ?? ''),
    !empty($data['declared']) ? 1 : 0,
]);

echo json_encode(['success' => true, 'message' => 'Registration submitted successfully']);
