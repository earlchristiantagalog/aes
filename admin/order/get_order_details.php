<?php
$conn = mysqli_connect("localhost", "root", "", "aes");
$id = mysqli_real_escape_string($conn, $_GET['id']);

// Updated Query: Joining user_addresses to get phone and address
$order_query = "SELECT o.*, u.full_name, u.email, ua.phone, ua.address_line, ua.barangay, ua.city, ua.province
                FROM orders o 
                JOIN users u ON o.user_id = u.id 
                LEFT JOIN user_addresses ua ON o.user_id = ua.user_id 
                WHERE o.id = $id LIMIT 1";

$order = mysqli_fetch_assoc(mysqli_query($conn, $order_query));
$items = mysqli_query($conn, "SELECT * FROM order_items WHERE order_id = $id");

// Helper to format address
$full_address = $order ? "{$order['address_line']}, Brgy. {$order['barangay']}, {$order['city']}, {$order['province']}" : '';

if ($order): ?>
    <div class="row g-3 mb-4">
        <div class="col-sm-7">
            <h6 class="text-uppercase text-muted fw-bold small mb-2">Shipping Details</h6>
            <p class="mb-1 fw-bold"><?= htmlspecialchars($order['full_name']) ?></p>
            <p class="small text-muted mb-1"><?= htmlspecialchars($full_address) ?></p>
            <p class="small text-dark fw-bold"><i class="bi bi-telephone me-1"></i> <?= htmlspecialchars($order['phone']) ?></p>
        </div>
        <div class="col-sm-5 text-sm-end">
            <h6 class="text-uppercase text-muted fw-bold small mb-2">Order Summary</h6>
            <div class="mb-1">
                <span class="adm-badge adm-badge--<?= strtolower($order['order_status']) ?>">
                    <?= strtoupper($order['order_status']) ?>
                </span>
            </div>
            <p class="small text-muted mb-0">Order #<?= $order['order_number'] ?></p>
            <p class="small text-muted">Method: <?= strtoupper($order['payment_method']) ?></p>
        </div>
    </div>

    <div class="table-responsive">
        <table class="table table-borderless align-middle">
            <thead class="bg-light">
                <tr class="small text-uppercase">
                    <th>Product</th>
                    <th class="text-center">Qty</th>
                    <th class="text-end">Subtotal</th>
                </tr>
            </thead>
            <tbody>
                <?php while ($item = mysqli_fetch_assoc($items)): ?>
                    <tr class="border-bottom">
                        <td class="py-3">
                            <div class="fw-bold"><?= htmlspecialchars($item['product_name']) ?></div>
                            <div class="text-muted small"><?= htmlspecialchars($item['variant']) ?></div>
                        </td>
                        <td class="text-center"><?= $item['quantity'] ?></td>
                        <td class="text-end fw-bold">₱<?= number_format($item['total'], 2) ?></td>
                    </tr>
                <?php endwhile; ?>
            </tbody>
        </table>
    </div>

    <div class="row justify-content-end mt-2">
        <div class="col-md-5 text-end">
            <span class="text-muted me-2">Total Amount:</span>
            <span class="h4 text-primary fw-bold">₱<?= number_format($order['total_amount'], 2) ?></span>
        </div>
    </div>

    <div class="mt-4 pt-3 border-top">
        <button class="adm-btn adm-btn-outline w-100" onclick="printAirWaybill(<?= $id ?>)">
            <i class="bi bi-printer me-2"></i> Generate A6 AirWaybill
        </button>
    </div>
<?php endif; ?>