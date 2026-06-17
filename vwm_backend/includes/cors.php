<?php
// Never output PHP errors as HTML — always return JSON
ini_set('display_errors', '0');
error_reporting(0);

set_exception_handler(function (Throwable $e) {
    http_response_code(500);
    echo json_encode(['success' => false, 'message' => 'Server error: ' . $e->getMessage()]);
    exit();
});

// Allow requests from the Vue dev server and production origin
$allowed = [
    'http://localhost:5173',
    'http://127.0.0.1:5173',
    'https://lightsalmon-porpoise-885538.hostingersite.com',
    'https://visualword.in',
    'https://www.visualword.in',
];
$origin  = $_SERVER['HTTP_ORIGIN'] ?? '';

if (in_array($origin, $allowed)) {
    header('Access-Control-Allow-Origin: ' . $origin);
    header('Vary: Origin');
}
header('Access-Control-Allow-Methods: GET, POST, PUT, DELETE, OPTIONS');
header('Access-Control-Allow-Headers: Content-Type, Authorization');
header('Access-Control-Allow-Credentials: true');
header('Content-Type: application/json; charset=utf-8');

if ($_SERVER['REQUEST_METHOD'] === 'OPTIONS') {
    http_response_code(204);
    exit();
}
