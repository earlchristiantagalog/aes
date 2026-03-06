<?php
$conn = mysqli_connect("localhost", "root", "", "aes");
$id = mysqli_real_escape_string($conn, $_GET['id']);

$sql = "SELECT o.id, o.order_number, o.payment_method, o.total_amount, 
               u.full_name, ua.phone, ua.address_line, ua.barangay, ua.city, ua.province,
               sz.shipping_courier, sz.shipping_name
        FROM orders o 
        JOIN users u ON o.user_id = u.id 
        LEFT JOIN user_addresses ua ON o.user_id = ua.user_id 
        LEFT JOIN shipping_zones sz ON o.shipping_id = sz.id
        WHERE o.id = $id LIMIT 1";

$res = mysqli_query($conn, $sql);
$order = mysqli_fetch_assoc($res);

if (!$order) die("Order not found.");

$addr = "{$order['address_line']}, Brgy. {$order['barangay']}, {$order['city']}, {$order['province']}";
$courier = strtoupper($order['shipping_courier'] ?? 'STANDARD');

$copies = ['COURIER COPY', 'CUSTOMER RECEIPT'];
?>
<!DOCTYPE html>
<html>

<head>
    <meta charset="UTF-8">
    <style>
        @page {
            size: A6;
            margin: 0;
        }

        body {
            font-family: 'Arial', sans-serif;
            margin: 0;
            padding: 0;
        }

        .page-wrapper {
            width: 105mm;
            height: 148mm;
            padding: 4mm;
            box-sizing: border-box;
            page-break-after: always;
            position: relative;
        }

        .awb-container {
            border: 2px solid #000;
            height: 100%;
            display: flex;
            flex-direction: column;
        }

        /* Header */
        .awb-header {
            display: flex;
            border-bottom: 2px solid #000;
            height: 20mm;
        }

        .logo-box {
            width: 40%;
            border-right: 2px solid #000;
            display: flex;
            align-items: center;
            justify-content: center;
            padding: 5px;
        }

        .logo-box img {
            max-width: 90%;
            max-height: 80%;
        }

        .info-box {
            width: 60%;
            display: flex;
            flex-direction: column;
            justify-content: center;
            padding-left: 8px;
        }

        .courier-name {
            font-size: 18px;
            font-weight: 900;
        }

        .copy-tag {
            position: absolute;
            top: 1mm;
            right: 5mm;
            font-size: 7px;
            font-weight: bold;
            color: #555;
        }

        /* Barcode */
        .barcode-section {
            text-align: center;
            padding: 5px 0;
            border-bottom: 2px solid #000;
        }

        .barcode-mock {
            font-size: 24px;
            letter-spacing: 2px;
            line-height: 1;
            margin-bottom: 2px;
        }

        /* Address Sections */
        .section {
            padding: 5px 8px;
            border-bottom: 1px solid #000;
        }

        .label {
            font-size: 8px;
            font-weight: bold;
            text-transform: uppercase;
            display: block;
            margin-bottom: 2px;
        }

        .val-bold {
            font-size: 12px;
            font-weight: bold;
        }

        /* Specific Item Table for Customer Receipt */
        .items-area {
            flex-grow: 1;
            padding: 5px 8px;
            font-size: 11px;
        }

        .item-row {
            display: flex;
            justify-content: space-between;
            border-bottom: 1px dashed #eee;
            padding: 3px 0;
        }

        /* Footer */
        .awb-footer {
            display: flex;
            border-top: 2px solid #000;
            height: 18mm;
        }

        .f-box {
            width: 50%;
            padding: 4px 8px;
            display: flex;
            flex-direction: column;
            justify-content: center;
        }

        .f-right {
            border-left: 2px solid #000;
            text-align: right;
        }

        .pmode {
            font-size: 14px;
            font-weight: bold;
            border: 1px solid #000;
            padding: 1px 4px;
            display: inline-block;
        }

        .total-price {
            font-size: 20px;
            font-weight: bold;
        }

        .jnt {
            color: #d32f2f;
        }

        .shopee {
            color: #ee4d2d;
        }
    </style>
</head>

<body onload="window.print();">

    <?php foreach ($copies as $index => $copyTitle): ?>
        <div class="page-wrapper">
            <span class="copy-tag"><?= $copyTitle ?></span>

            <div class="awb-container">
                <div class="awb-header">
                    <div class="logo-box">
                        <?php if (strpos($courier, 'J&T') !== false): ?>
                            <img src="https://upload.wikimedia.org/wikipedia/commons/b/b2/J%26T_Express_logo.svg">
                        <?php elseif (strpos($courier, 'SHOPEE') !== false): ?>
                            <img src="https://upload.wikimedia.org/wikipedia/commons/f/fe/Shopee.svg">
                        <?php else: ?>
                            <div style="font-size: 20px; font-weight: bold;">AES</div>
                        <?php endif; ?>
                    </div>
                    <div class="info-box">
                        <div class="courier-name <?= (strpos($courier, 'J&T') !== false) ? 'jnt' : ((strpos($courier, 'SHOPEE') !== false) ? 'shopee' : '') ?>">
                            <?= $courier ?>
                        </div>
                        <div style="font-size: 9px; font-weight: bold;">STANDARD DELIVERY</div>
                    </div>
                </div>

                <div class="barcode-section">
                    <div class="barcode-mock">|||||||||||||||||||||||||</div>
                    <div style="font-weight: bold; font-size: 12px;">ORDER #<?= $order['order_number'] ?></div>
                </div>

                <div class="section">
                    <span class="label">Recipient:</span>
                    <div class="val-bold"><?= htmlspecialchars($order['full_name']) ?> (<?= htmlspecialchars($order['phone']) ?>)</div>
                    <div style="font-size: 10px;"><?= htmlspecialchars($addr) ?></div>
                </div>

                <div class="items-area">
                    <?php if ($index === 0): // Courier Copy: Minimalist 
                    ?>
                        <span class="label">Sender:</span>
                        <div style="font-size: 11px; font-weight: bold;">AES STORE Official</div>
                        <div style="font-size: 10px;">Metro Manila, Philippines</div>
                        <div style="margin-top: 15px; border: 1px solid #ddd; padding: 5px; font-size: 9px; text-align: center;">
                            FOR COURIER USE ONLY
                        </div>
                    <?php else: // Customer Receipt: Show only what they ordered 
                    ?>
                        <span class="label">Items Ordered:</span>
                        <div style="margin-top: 5px;">
                            <?php
                            $items_query = mysqli_query($conn, "SELECT product_name, variant, quantity, total FROM order_items WHERE order_id = {$order['id']}");
                            while ($item = mysqli_fetch_assoc($items_query)): ?>
                                <div class="item-row">
                                    <div style="max-width: 75%;">
                                        <strong><?= $item['quantity'] ?>x</strong> <?= htmlspecialchars($item['product_name']) ?>
                                        <div style="font-size: 9px; color: #666;"><?= htmlspecialchars($item['variant']) ?></div>
                                    </div>
                                    <div style="font-weight: bold;">₱<?= number_format($item['total'], 2) ?></div>
                                </div>
                            <?php endwhile; ?>
                        </div>
                    <?php endif; ?>
                </div>

                <div class="awb-footer">
                    <div class="f-box">
                        <span class="label">Payment</span>
                        <div><span class="pmode"><?= strtoupper($order['payment_method']) ?></span></div>
                    </div>
                    <div class="f-box f-right">
                        <span class="label">Total Amount</span>
                        <div class="total-price">₱<?= number_format($order['total_amount'], 2) ?></div>
                    </div>
                </div>
            </div>
        </div>
    <?php endforeach; ?>

</body>

</html>