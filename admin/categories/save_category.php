<?php
header('Content-Type: application/json');

// 1. Database Connection
// Ensure this path leads to the file where you defined $pdo
require_once('../db/db.php');

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    try {
        // 2. Map POST data
        $name        = trim($_POST['category_name'] ?? '');
        $icon        = trim($_POST['category_icon'] ?? 'bi-collection');
        $description = trim($_POST['description'] ?? '');
        $status      = isset($_POST['status']) ? 1 : 0;

        if (empty($name)) {
            echo json_encode(['success' => false, 'message' => 'Category name is required.']);
            exit;
        }

        // 3. Prepare and Execute using PDO
        // We use named placeholders (:name, etc.) which are safer and easier to read
        $sql = "INSERT INTO categories (name, description, icon, status) 
                VALUES (:name, :description, :icon, :status)";

        $stmt = $pdo->prepare($sql);

        // PDO execution is done by passing an array
        $result = $stmt->execute([
            ':name'        => $name,
            ':description' => $description,
            ':icon'        => $icon,
            ':status'     => $status
        ]);

        if ($result) {
            echo json_encode(['success' => true]);
        } else {
            throw new Exception("Failed to insert record.");
        }
    } catch (PDOException $e) {
        // Specifically catch database errors (like missing columns)
        echo json_encode(['success' => false, 'message' => 'Database Error: ' . $e->getMessage()]);
    } catch (Exception $e) {
        // Catch general errors
        echo json_encode(['success' => false, 'message' => $e->getMessage()]);
    }
} else {
    echo json_encode(['success' => false, 'message' => 'Invalid Request Method']);
}
