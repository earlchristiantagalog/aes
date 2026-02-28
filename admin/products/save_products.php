<?php
require '../db/db.php';
header('Content-Type: application/json');

try {
    // 1. Read JSON body
    $data = json_decode(file_get_contents("php://input"), true);

    if (!$data) {
        throw new Exception("Invalid JSON data.");
    }

    $pdo->beginTransaction();

    // In save_products.php
    // Takes ["Printing", "Binding"] and turns it into "Printing, Binding"
    $categoryString = is_array($data['categories'])
        ? implode(', ', $data['categories'])
        : $data['categories'];

    // Use $categoryString in your execute() array

    // 2. Insert Main Product
    $stmt = $pdo->prepare("
        INSERT INTO products 
        (sku, product_number, name, small_description, full_description, category, base_price, status)
        VALUES (?, ?, ?, ?, ?, ?, ?, ?)
    ");

    $stmt->execute([
        $data['sku'],
        $data['productNumber'],
        $data['name'],
        $data['smallDesc'],
        $data['description'],
        $categoryString, // Saved as "Cat1, Cat2"
        $data['basePrice'] ?: 0,
        $data['status']
    ]);

    $productId = $pdo->lastInsertId();

    // 3. Insert Variants
    if (!empty($data['variants'])) {
        $vStmt = $pdo->prepare("
            INSERT INTO product_variants 
            (product_id, variant_type, variant_value, price, stock) 
            VALUES (?, ?, ?, ?, ?)
        ");

        foreach ($data['variants'] as $variant) {
            $vStmt->execute([
                $productId,
                $variant['type'],
                $variant['value'],
                $variant['price'] ?: 0,
                $variant['stock'] ?: 0
            ]);
        }
    }

    // 4. Save Base64 Images
    if (!empty($data['images'])) {
        $uploadDir = 'uploads/';
        if (!is_dir($uploadDir)) mkdir($uploadDir, 0777, true);

        $imgStmt = $pdo->prepare("
            INSERT INTO product_images (product_id, image_path, is_cover)
            VALUES (?, ?, ?)
        ");

        foreach ($data['images'] as $index => $base64Image) {
            if ($index >= 5) break;

            // Decode Base64
            $imageData = explode(',', $base64Image);
            if (count($imageData) < 2) continue; // Skip invalid images

            $decoded = base64_decode(end($imageData));

            $filename = "prod_{$productId}_" . uniqid() . ".png";
            $filePath = $uploadDir . $filename;

            file_put_contents($filePath, $decoded);

            $imgStmt->execute([
                $productId,
                $filename,
                $index === 0 ? 1 : 0
            ]);
        }
    }

    $pdo->commit();

    echo json_encode([
        "success" => true,
        "message" => "Product saved successfully"
    ]);
} catch (Exception $e) {
    if ($pdo->inTransaction()) {
        $pdo->rollBack();
    }
    // We remove the 500 code so the JS "catch" can read the JSON error message
    echo json_encode([
        "success" => false,
        "message" => "Server Error: " . $e->getMessage()
    ]);
}
