<?php
$conn = mysqli_connect('localhost', 'root', '', 'aes');
session_start();

// Get order ID from URL
$order_id = $_GET['id'] ?? null;

if (!$order_id) {
    header("Location: index.php");
    exit;
}

// Fetch order details
$order_stmt = $conn->prepare("SELECT * FROM orders WHERE id = ?");
$order_stmt->bind_param("i", $order_id);
$order_stmt->execute();
$order_result = $order_stmt->get_result();
$order = $order_result->fetch_assoc();

if (!$order) {
    echo "Order not found!";
    exit;
}

// Fetch order items
$items_stmt = $conn->prepare("SELECT * FROM order_items WHERE order_id = ?");
$items_stmt->bind_param("i", $order_id);
$items_stmt->execute();
$items_result = $items_stmt->get_result();
$order_items = [];
while ($row = $items_result->fetch_assoc()) {
    $order_items[] = $row;
}

// Fetch shipping info
$shipping_id = $order['shipping_id'] ?? 0;
$shipping_fee = 0;
$shipping_name = "FREE";
if ($shipping_id) {
    $ship = $conn->query("SELECT shipping_name, shipping_price FROM shipping_zones WHERE id = $shipping_id")->fetch_assoc();
    if ($ship) {
        $shipping_name = $ship['shipping_name'];
        $shipping_fee = $ship['shipping_price'];
    }
}

// Fetch address
$address_id = $order['address_id'] ?? 0;
$address = $conn->query("SELECT * FROM user_addresses WHERE id = $address_id")->fetch_assoc();
?>
<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Order Confirmed | Aralin Educational Supplies</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.1/font/bootstrap-icons.css">
    <style>
        :root {
            --primary-color: #0d6efd;
        }

        body {
            background-color: #f8f9fa;
            font-family: 'Inter', sans-serif;
        }

        .success-checkmark {
            width: 80px;
            height: 80px;
            background: #d1e7dd;
            color: #198754;
            display: flex;
            align-items: center;
            justify-content: center;
            border-radius: 50%;
            font-size: 40px;
            margin: 0 auto 20px;
            animation: scaleUp 0.5s ease-out;
        }

        @keyframes scaleUp {
            0% {
                transform: scale(0);
            }

            100% {
                transform: scale(1);
            }
        }

        .conf-card {
            border: none;
            border-radius: 15px;
            box-shadow: 0 4px 12px rgba(0, 0, 0, 0.05);
        }

        .order-id-badge {
            background: #e9ecef;
            padding: 5px 15px;
            border-radius: 50px;
            font-weight: 600;
            color: #495057;
        }

        .active-step .step-icon {
            border-color: var(--primary-color);
            color: var(--primary-color);
        }

        .step-icon {
            width: 40px;
            height: 40px;
            background: #fff;
            border: 2px solid #dee2e6;
            border-radius: 50%;
            display: flex;
            align-items: center;
            justify-content: center;
            margin-right: 15px;
        }

        @media print {
            .no-print {
                display: none !important;
            }

            .conf-card {
                box-shadow: none;
                border: 1px solid #ddd;
            }
        }
    </style>
</head>

<body>
    <div class="container py-5">
        <div class="row justify-content-center">
            <!-- Success Message -->
            <div class="col-lg-7 text-center mb-4 no-print">
                <div class="success-checkmark"><i class="bi bi-check-lg"></i></div>
                <h2 class="fw-bold">Thank you for your order!</h2>
                <p class="text-muted">Order <span class="order-id-badge">#<?= htmlspecialchars($order['id']) ?></span> has been placed successfully.</p>
                <p class="small">A confirmation email has been sent to your registered address.</p>
            </div>

            <div class="col-lg-8">
                <!-- Order Summary Card -->
                <div class="card conf-card mb-4">
                    <div class="card-body p-4">
                        <h5 class="fw-bold mb-4">Order Summary</h5>

                        <?php foreach ($order_items as $item):
                            // Fetch product image
                            $img = $conn->query("SELECT image_path FROM product_images WHERE product_id = {$item['product_id']} AND is_cover = 1 LIMIT 1")->fetch_assoc();
                            $image_path = $img ? 'admin/products/uploads/' . $img['image_path'] : 'assets/images/default.jpg';
                        ?>
                            <div class="d-flex justify-content-between align-items-center mb-3">
                                <div class="d-flex align-items-center">
                                    <div class="bg-light rounded p-2 me-3" style="width:50px;height:50px;display:flex;align-items:center;justify-content:center;">
                                        <img src="<?= $image_path ?>" alt="Product" style="max-width:100%;max-height:100%;">
                                    </div>
                                    <div>
                                        <h6 class="mb-0 small fw-bold"><?= htmlspecialchars($item['product_name']) ?></h6>
                                        <?php if ($item['variant']): ?><small class="text-muted">Variant: <?= htmlspecialchars($item['variant']) ?></small><br><?php endif; ?>
                                        <small class="text-muted">Qty: <?= $item['quantity'] ?></small>
                                    </div>
                                </div>
                                <span class="fw-bold small">₱<?= number_format($item['total'], 2) ?></span>
                            </div>
                        <?php endforeach; ?>

                        <hr class="my-4">

                        <div class="d-flex justify-content-between mb-2"><span class="text-muted small">Subtotal</span><span class="small">₱<?= number_format($order['subtotal'], 2) ?></span></div>
                        <div class="d-flex justify-content-between mb-2"><span class="text-muted small">Shipping Fee (<?= htmlspecialchars($shipping_name) ?>)</span><span class="small">₱<?= number_format($shipping_fee, 2) ?></span></div>
                        <?php if ($order['discount'] > 0): ?>
                            <div class="d-flex justify-content-between mb-2 text-danger"><span class="small">Discount</span><span class="small">-₱<?= number_format($order['discount'], 2) ?></span></div>
                        <?php endif; ?>
                        <div class="d-flex justify-content-between mt-3">
                            <h5 class="fw-bold">Total Amount</h5>
                            <h5 class="fw-bold text-primary">₱<?= number_format($order['total_amount'], 2) ?></h5>
                        </div>
                    </div>
                </div>

                <!-- Shipping and Payment -->
                <div class="row g-4 mb-4">
                    <div class="col-md-6">
                        <div class="card conf-card h-100">
                            <div class="card-body">
                                <h6 class="fw-bold mb-3"><i class="bi bi-geo-alt me-2"></i>Shipping Address</h6>
                                <?php if ($address): ?>
                                    <p class="small text-muted mb-0">
                                        <?= htmlspecialchars($address['first_name'] . ' ' . $address['last_name']) ?><br>
                                        <?= htmlspecialchars($address['address_line']) ?>, <?= htmlspecialchars($address['city']) ?><br>
                                        <?= htmlspecialchars($address['province']) ?>, <?= htmlspecialchars($address['zip_code']) ?><br>
                                        <?= htmlspecialchars($address['phone']) ?>
                                    </p>
                                <?php else: ?>
                                    <p class="small text-muted mb-0">Address not found</p>
                                <?php endif; ?>
                            </div>
                        </div>
                    </div>
                    <div class="col-md-6">
                        <div class="card conf-card h-100">
                            <div class="card-body">
                                <h6 class="fw-bold mb-3"><i class="bi bi-credit-card me-2"></i>Payment Method</h6>
                                <p class="small text-muted mb-0">
                                    <strong><?= htmlspecialchars(ucwords(str_replace('-', ' ', $order['payment_method']))) ?></strong><br>
                                    <?php
                                    $status = ($order['payment_method'] == 'bank-transfer') ? 'Pending' : 'Completed';
                                    $badgeClass = ($status == 'Pending') ? 'bg-warning text-dark' : 'bg-success';
                                    ?>
                                    Status: <span class="badge <?= $badgeClass ?>"><?= $status ?></span>
                                </p>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Buttons -->
                <div class="d-flex gap-2 justify-content-center no-print">
                    <a href="shop.php" class="btn btn-outline-primary px-4 rounded-pill">Continue Shopping</a>
                    <button onclick="window.print()" class="btn btn-primary px-4 rounded-pill"><i class="bi bi-printer me-2"></i>Print / Track Order</button>
                </div>

            </div>
        </div>
    </div>
</body>

</html>