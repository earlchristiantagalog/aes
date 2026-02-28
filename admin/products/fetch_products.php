<?php
require '../db/db.php';
header('Content-Type: application/json');

try {
    $limit = 5;
    $page = isset($_GET['page']) ? (int)$_GET['page'] : 1;
    $offset = ($page - 1) * $limit;

    // Get Filter Parameters
    $search = isset($_GET['search']) ? '%' . $_GET['search'] . '%' : '%';
    $category = isset($_GET['category']) ? $_GET['category'] : '';
    $status = isset($_GET['status']) ? $_GET['status'] : '';
    $stockFilter = isset($_GET['stock']) ? $_GET['stock'] : '';

    // Build the WHERE clause
    $conditions = ["(p.name LIKE :search OR p.sku LIKE :search)"];
    $params = [':search' => $search];

    if (!empty($category)) {
        $conditions[] = "p.category = :category";
        $params[':category'] = $category;
    }
    if (!empty($status)) {
        $conditions[] = "p.status = :status";
        $params[':status'] = $status;
    }

    // Handle Stock Status Filter in SQL
    if ($stockFilter === 'in-stock') {
        $conditions[] = "(SELECT SUM(stock) FROM product_variants WHERE product_id = p.id) > 10";
    } elseif ($stockFilter === 'low-stock') {
        $conditions[] = "(SELECT SUM(stock) FROM product_variants WHERE product_id = p.id) BETWEEN 1 AND 10";
    } elseif ($stockFilter === 'out-of-stock') {
        $conditions[] = "(SELECT SUM(stock) FROM product_variants WHERE product_id = p.id) <= 0 OR (SELECT SUM(stock) FROM product_variants WHERE product_id = p.id) IS NULL";
    }

    $whereClause = " WHERE " . implode(" AND ", $conditions);

    // 1. Fetch Stats (Always based on ALL products, or filtered? Usually dashboard stats are global)
    $statsQuery = "
        SELECT 
            COUNT(*) as total_products,
            SUM(CASE WHEN (SELECT SUM(stock) FROM product_variants WHERE product_id = p.id) > 10 THEN 1 ELSE 0 END) as in_stock,
            SUM(CASE WHEN (SELECT SUM(stock) FROM product_variants WHERE product_id = p.id) BETWEEN 1 AND 10 THEN 1 ELSE 0 END) as low_stock,
            SUM(CASE WHEN (SELECT SUM(stock) FROM product_variants WHERE product_id = p.id) <= 0 OR NOT EXISTS (SELECT 1 FROM product_variants WHERE product_id = p.id) THEN 1 ELSE 0 END) as out_of_stock
        FROM products p";
    $stats = $pdo->query($statsQuery)->fetch(PDO::FETCH_ASSOC);

    // 2. Count filtered products for pagination
    $countStmt = $pdo->prepare("SELECT COUNT(*) FROM products p $whereClause");
    $countStmt->execute($params);
    $totalFiltered = $countStmt->fetchColumn();

    // 3. Fetch Data
    $query = "
        SELECT p.*, 
            (SELECT image_path FROM product_images WHERE product_id = p.id AND is_cover = 1 LIMIT 1) as cover_image,
            (SELECT SUM(stock) FROM product_variants WHERE product_id = p.id) as total_stock
        FROM products p
        $whereClause
        ORDER BY p.id DESC
        LIMIT :limit OFFSET :offset
    ";

    $stmt = $pdo->prepare($query);
    foreach ($params as $key => $val) {
        $stmt->bindValue($key, $val);
    }
    $stmt->bindValue(':limit', $limit, PDO::PARAM_INT);
    $stmt->bindValue(':offset', $offset, PDO::PARAM_INT);
    $stmt->execute();

    echo json_encode([
        "success" => true,
        "data" => $stmt->fetchAll(PDO::FETCH_ASSOC),
        "stats" => $stats,
        "total" => (int)$totalFiltered,
        "currentPage" => $page,
        "limit" => $limit
    ]);
} catch (Exception $e) {
    http_response_code(500);
    echo json_encode(["success" => false, "message" => $e->getMessage()]);
}
