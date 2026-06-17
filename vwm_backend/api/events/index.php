<?php
require_once __DIR__ . '/../../includes/cors.php';
require_once __DIR__ . '/../../config/db.php';
require_once __DIR__ . '/../../includes/auth.php';

$method = $_SERVER['REQUEST_METHOD'];
$db     = getDB();

switch ($method) {

    case 'GET':
        // Public — any visitor can view events
        $stmt   = $db->query(
            'SELECT * FROM events WHERE is_active = 1
             ORDER BY FIELD(month,"JAN","FEB","MAR","APR","MAY","JUN","JUL","AUG","SEP","OCT","NOV","DEC"),
                      CAST(day AS UNSIGNED)'
        );
        $events = $stmt->fetchAll();
        echo json_encode(['success' => true, 'events' => $events]);
        break;

    case 'POST':
        $user  = requireAdmin();
        $input = json_decode(file_get_contents('php://input'), true);

        foreach (['month', 'day', 'title', 'category', 'description', 'location', 'time'] as $f) {
            if (empty($input[$f])) {
                http_response_code(400);
                die(json_encode(['success' => false, 'message' => "Field '$f' is required"]));
            }
        }

        $stmt = $db->prepare(
            'INSERT INTO events (month, day, title, category, description, location, time)
             VALUES (?, ?, ?, ?, ?, ?, ?)'
        );
        $stmt->execute([
            strtoupper(substr($input['month'], 0, 3)),
            $input['day'], $input['title'], $input['category'],
            $input['description'], $input['location'], $input['time'],
        ]);
        echo json_encode(['success' => true, 'message' => 'Event created', 'id' => $db->lastInsertId()]);
        break;

    case 'PUT':
        $user = requireAdmin();
        $id   = intval($_GET['id'] ?? 0);
        if (!$id) { http_response_code(400); die(json_encode(['success' => false, 'message' => 'Event id required'])); }

        $input = json_decode(file_get_contents('php://input'), true);
        $stmt  = $db->prepare(
            'UPDATE events SET month=?, day=?, title=?, category=?, description=?, location=?, time=?
             WHERE id=?'
        );
        $stmt->execute([
            strtoupper(substr($input['month'], 0, 3)),
            $input['day'], $input['title'], $input['category'],
            $input['description'], $input['location'], $input['time'], $id,
        ]);
        echo json_encode(['success' => true, 'message' => 'Event updated']);
        break;

    case 'DELETE':
        $user = requireAdmin();
        $id   = intval($_GET['id'] ?? 0);
        if (!$id) { http_response_code(400); die(json_encode(['success' => false, 'message' => 'Event id required'])); }

        $stmt = $db->prepare('UPDATE events SET is_active = 0 WHERE id = ?');
        $stmt->execute([$id]);
        echo json_encode(['success' => true, 'message' => 'Event removed']);
        break;

    default:
        http_response_code(405);
        echo json_encode(['success' => false, 'message' => 'Method not allowed']);
}
