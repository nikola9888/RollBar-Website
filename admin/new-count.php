<?php
declare(strict_types=1);
require_once __DIR__ . '/../app/bootstrap.php';
rb_session();
header('Content-Type: application/json; charset=utf-8');
header('Cache-Control: no-store, no-cache, must-revalidate');
if (empty($_SESSION['rb_admin_id'])) { http_response_code(401); echo json_encode(['error'=>'unauthorized']); exit; }
try {
    $after = max(0,(int)($_GET['after'] ?? 0));
    $stmt = rb_db()->prepare("SELECT COUNT(*) FROM orders WHERE id > ? AND status='new'");
    $stmt->execute([$after]);
    echo json_encode(['new_count'=>(int)$stmt->fetchColumn()]);
} catch (Throwable $e) {
    http_response_code(500);
    echo json_encode(['error'=>'database_error']);
}
