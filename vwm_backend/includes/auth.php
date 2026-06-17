<?php
function getAuthUser() {
    $headers = getallheaders();
    $auth = $headers['Authorization'] ?? $headers['authorization'] ?? '';

    if (!preg_match('/^Bearer\s+(.+)$/i', $auth, $matches)) {
        return null;
    }

    $token = $matches[1];
    $db    = getDB();
    $stmt  = $db->prepare('SELECT * FROM users WHERE auth_token = ? AND token_expires_at > NOW()');
    $stmt->execute([$token]);
    return $stmt->fetch() ?: null;
}

function requireAuth() {
    $user = getAuthUser();
    if (!$user) {
        http_response_code(401);
        die(json_encode(['success' => false, 'message' => 'Unauthorized — please log in']));
    }
    return $user;
}

function requireAdmin() {
    $user = requireAuth();
    if ($user['role'] !== 'admin') {
        http_response_code(403);
        die(json_encode(['success' => false, 'message' => 'Admin access required']));
    }
    return $user;
}
