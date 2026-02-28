<?php
header('Content-Type: application/json');
require_once('../db/db.php');

try {
    $stmt = $pdo->query("SELECT id, name FROM categories WHERE status = 1 ORDER BY name ASC");
    $categories = $stmt->fetchAll(PDO::FETCH_ASSOC);
    echo json_encode(['success' => true, 'data' => $categories]);
} catch (Exception $e) {
    echo json_encode(['success' => false, 'message' => $e->getMessage()]);
}
