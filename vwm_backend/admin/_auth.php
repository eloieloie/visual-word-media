<?php
session_start();
require_once dirname(__DIR__) . '/config/db.php';

if (empty($_SESSION['admin_id'])) {
    header('Location: login.php');
    exit();
}

$db   = getDB();
$stmt = $db->prepare('SELECT id, name, email, role FROM users WHERE id = ?');
$stmt->execute([$_SESSION['admin_id']]);
$adminUser = $stmt->fetch();

if (!$adminUser || $adminUser['role'] !== 'admin') {
    session_destroy();
    header('Location: login.php');
    exit();
}
