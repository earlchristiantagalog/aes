<?php
include 'includes/header.php';
include 'db/db.php';
?>
<?php
// checkout.php

// Check if this is a "Buy Now" action from the URL
$is_buy_now = isset($_GET['id']) && isset($_GET['qty']);

$items = [];
$subtotal = 0;

if ($is_buy_now) {
    $p_id = intval($_GET['id']);
    $qty = intval($_GET['qty']);
    $variants = isset($_GET['variants']) ? mysqli_real_escape_string($conn, $_GET['variants']) : '';

    // Join with variants table to get the specific price for that variant
    // We use COALESCE to fallback to base_price if no variant price is found
    $query = "SELECT p.name, p.id, p.base_price, v.price as variant_price 
              FROM products p 
              LEFT JOIN product_variants v ON p.id = v.product_id AND v.variant_value = '$variants'
              WHERE p.id = $p_id";

    $res = mysqli_query($conn, $query);
    $product = mysqli_fetch_assoc($res);

    if ($product) {
        // Determine the price: use variant_price if it exists, otherwise use base_price
        $price = (!empty($product['variant_price'])) ? $product['variant_price'] : $product['base_price'];

        // Fetch the cover image
        $img_res = mysqli_query($conn, "SELECT image_path FROM product_images WHERE product_id = $p_id AND is_cover = 1 LIMIT 1");
        $img = mysqli_fetch_assoc($img_res);
        $image_path = $img ? 'admin/products/uploads/' . $img['image_path'] : 'assets/images/default.jpg';

        $total_item_price = $price * $qty;

        $items[] = [
            'name' => $product['name'],
            'image' => $image_path,
            'qty' => $qty,
            'price' => $price,
            'total' => $total_item_price,
            'variants' => $variants
        ];

        $subtotal = $total_item_price;
    }
}

// Simple Tax/Discount logic
$discount = 0; // Example: 494.00
$shipping = 0; // FREE
$total_amount = $subtotal - $discount + $shipping;

// 1. Fetch active methods from the database
$active_methods = [];
$query = "SELECT method_key FROM aes.payment_settings WHERE is_active = 1";
$result = mysqli_query($conn, $query);

if ($result) {
    while ($row = mysqli_fetch_assoc($result)) {
        $active_methods[] = $row['method_key'];
    }
}

// 2. Helper to handle the "checked" state for the first available method
$defaultSet = false;
function setChecked(&$defaultSet)
{
    if (!$defaultSet) {
        $defaultSet = true;
        return 'checked';
    }
    return '';
}
?>
<!-- Checkout Section -->
<section class="checkout-section py-5">
    <div class="container">
        <!-- Page Header -->
        <div class="row mb-4">
            <div class="col-12">
                <h2 class="fw-bold mb-3">
                    <i class="bi bi-bag-check me-2" style="color: var(--primary-color);"></i>
                    Secure Checkout
                </h2>
                <p class="text-muted">Complete your order in a few simple steps</p>
            </div>
        </div>

        <div class="row g-4">
            <!-- Checkout Form -->
            <div class="col-lg-8">
                <form id="checkoutForm">
                    <?php
                    // Fetch the default address for the logged-in user
                    $user_id = $_SESSION['user_id'];
                    $query = "SELECT * FROM user_addresses WHERE user_id = ? AND is_default = 1 LIMIT 1";
                    $stmt = $conn->prepare($query);
                    $stmt->bind_param("i", $user_id);
                    $stmt->execute();
                    $address = $stmt->get_result()->fetch_assoc();
                    ?>

                    <div class="checkout-card mb-4">
                        <div class="card-body-custom p-3">
                            <div class="d-flex align-items-center mb-2 text-danger">
                                <i class="bi bi-geo-alt-fill me-2"></i>
                                <h6 class="mb-0 fw-bold">Delivery Address</h6>
                            </div>

                            <div class="d-flex justify-content-between align-items-start">
                                <div class="address-details">
                                    <?php if ($address): ?>
                                        <div class="fw-bold mb-1">
                                            <span id="display-name"><?= htmlspecialchars($address['first_name'] . ' ' . ($address['last_name'] ?? '')  ?? 'Add Name'); ?></span>
                                            <span class="text-muted ms-2" id="display-phone"><?= htmlspecialchars($address['phone'] ?? ''); ?></span>
                                        </div>
                                        <div class="text-secondary small" id="display-address">
                                            <?= htmlspecialchars($address['address_line']); ?>,
                                            <?= htmlspecialchars($address['city']); ?>,
                                            <?= htmlspecialchars($address['province']); ?>,
                                            <?= htmlspecialchars($address['zip_code']); ?>
                                        </div>
                                        <?php if ($address['is_default']): ?>
                                            <span class="badge border border-danger text-danger mt-2" style="font-size: 10px;">Default</span>
                                        <?php endif; ?>

                                        <input type="hidden" name="address_id" value="<?= $address['id']; ?>">
                                    <?php else: ?>
                                        <p class="text-muted mb-0">No address found. Please add one.</p>
                                    <?php endif; ?>
                                </div>

                                <button type="button" class="btn btn-sm text-primary fw-bold" data-bs-toggle="modal" data-bs-target="#changeAddressModal">
                                    CHANGE
                                </button>
                            </div>
                        </div>
                    </div>

                    <!-- Shipping Method -->
                    <div class="checkout-card mb-4">
                        <div class="card-header-custom">
                            <h5 class="mb-0">
                                <i class="bi bi-truck me-2"></i>
                                Shipping Method
                            </h5>
                        </div>
                        <div class="card-body-custom">
                            <div class="shipping-options">
                                <?php
                                // Fetch shipping methods ordered by price (cheapest first)
                                $query = "SELECT * FROM shipping_zones ORDER BY shipping_price ASC";
                                $result = mysqli_query($conn, $query);

                                if (mysqli_num_rows($result) > 0):
                                    $isFirst = true;
                                    while ($row = mysqli_fetch_assoc($result)):
                                        // Format the price: Show 'FREE' if 0, otherwise format with Peso sign
                                        $displayPrice = ($row['shipping_price'] <= 0) ? 'FREE' : '₱' . number_format($row['shipping_price'], 2);

                                        // Logic to pick an icon based on the name (Optional)
                                        $icon = "bi-box-seam";
                                        if (stripos($row['shipping_name'], 'Express') !== false) $icon = "bi-lightning-charge";
                                        if (stripos($row['shipping_name'], 'Same Day') !== false) $icon = "bi-rocket-takeoff";
                                ?>
                                        <label class="shipping-option">
                                            <input type="radio"
                                                name="shipping"
                                                value="<?= $row['id'] ?>"
                                                data-price="<?= $row['shipping_price'] ?>"
                                                <?= $isFirst ? 'checked' : '' ?>>

                                            <div class="shipping-content">
                                                <div class="shipping-info">
                                                    <div class="shipping-name">
                                                        <i class="bi <?= $icon ?> me-2"></i>
                                                        <?= htmlspecialchars($row['shipping_name']) ?>
                                                    </div>
                                                </div>
                                                <div class="shipping-price"><?= $displayPrice ?></div>
                                            </div>
                                        </label>
                                    <?php
                                        $isFirst = false;
                                    endwhile;
                                else:
                                    ?>
                                    <p class="text-center p-3">No shipping methods available.</p>
                                <?php endif; ?>
                            </div>
                        </div>
                    </div>

                    <!-- Payment Method -->
                    <div class="checkout-card mb-4">
                        <div class="card-header-custom">
                            <h5 class="mb-0">
                                <i class="bi bi-credit-card me-2"></i>
                                Payment Method
                            </h5>
                        </div>
                        <div class="card-body-custom">
                            <div class="payment-tabs">

                                <?php if (in_array('payment_cards', $active_methods)): ?>
                                    <input type="radio" name="payment" id="credit-card" value="credit-card" <?= setChecked($defaultSet) ?>>
                                <?php endif; ?>

                                <?php if (in_array('payment_gcash', $active_methods)): ?>
                                    <input type="radio" name="payment" id="gcash" value="gcash" <?= setChecked($defaultSet) ?>>
                                <?php endif; ?>

                                <?php if (in_array('payment_bank', $active_methods)): ?>
                                    <input type="radio" name="payment" id="bank-transfer" value="bank-transfer" <?= setChecked($defaultSet) ?>>
                                <?php endif; ?>

                                <?php if (in_array('payment_cod', $active_methods)): ?>
                                    <input type="radio" name="payment" id="cod" value="cod" <?= setChecked($defaultSet) ?>>
                                <?php endif; ?>

                                <div class="payment-tab-buttons">
                                    <?php if (in_array('payment_cards', $active_methods)): ?>
                                        <label for="credit-card" class="payment-tab-btn">
                                            <i class="bi bi-credit-card"></i>
                                            <span>Card</span>
                                        </label>
                                    <?php endif; ?>

                                    <?php if (in_array('payment_gcash', $active_methods)): ?>
                                        <label for="gcash" class="payment-tab-btn">
                                            <i class="bi bi-wallet2"></i>
                                            <span>GCash</span>
                                        </label>
                                    <?php endif; ?>

                                    <?php if (in_array('payment_bank', $active_methods)): ?>
                                        <label for="bank-transfer" class="payment-tab-btn">
                                            <i class="bi bi-bank"></i>
                                            <span>Bank</span>
                                        </label>
                                    <?php endif; ?>

                                    <?php if (in_array('payment_cod', $active_methods)): ?>
                                        <label for="cod" class="payment-tab-btn">
                                            <i class="bi bi-cash-coin"></i>
                                            <span>COD</span>
                                        </label>
                                    <?php endif; ?>
                                </div>

                                <div class="payment-tab-content">

                                    <?php if (in_array('payment_cards', $active_methods)): ?>
                                        <div class="payment-panel" id="credit-card-panel">
                                            <div class="row g-3">
                                                <div class="col-12">
                                                    <label class="form-label">Card Number</label>
                                                    <div class="input-group">
                                                        <span class="input-group-text"><i class="bi bi-credit-card"></i></span>
                                                        <input type="text" class="form-control form-control-custom" placeholder="1234 5678 9012 3456">
                                                    </div>
                                                </div>
                                                <div class="col-12">
                                                    <div class="security-badge">
                                                        <i class="bi bi-shield-check me-2"></i> Your payment information is encrypted and secure
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                    <?php endif; ?>

                                    <?php if (in_array('payment_gcash', $active_methods)): ?>
                                        <div class="payment-panel" id="gcash-panel">
                                            <div class="payment-info-box">
                                                <div class="text-center mb-3">
                                                    <i class="bi bi-wallet2 fs-1 text-primary mb-3 d-block"></i>
                                                    <h6>Pay with GCash</h6>
                                                    <p class="text-muted small">You will be redirected to GCash to complete your payment</p>
                                                </div>
                                                <div class="alert alert-info">
                                                    <i class="bi bi-info-circle me-2"></i> Please have your GCash app ready for verification
                                                </div>
                                            </div>
                                        </div>
                                    <?php endif; ?>

                                    <?php if (in_array('payment_bank', $active_methods)): ?>

                                        <div class="payment-panel" id="bank-transfer-panel">
                                            <div class="payment-info-box">
                                                <h6 class="mb-3">Bank Transfer Details</h6>
                                                <div class="bank-details p-3 bg-light rounded mb-3">
                                                    <div class="bank-detail-item"><strong>Bank Name:</strong> BDO Unibank</div>
                                                    <div class="bank-detail-item"><strong>Account Name:</strong> Aralin Educational Supplies</div>
                                                    <div class="bank-detail-item"><strong>Account Number:</strong> 1234-5678-9012</div>
                                                </div>

                                                <div class="upload-section mt-3">
                                                    <label class="form-label fw-bold small">Upload Proof of Payment (Receipt)</label>
                                                    <div class="input-group">
                                                        <input type="file" name="payment_receipt" id="payment_receipt" class="form-control form-control-custom" accept="image/*">
                                                    </div>
                                                    <div class="form-text mt-1" style="font-size: 0.75rem;">
                                                        Accepted formats: JPG, PNG (Max 2MB)
                                                    </div>
                                                    <div id="receipt-preview-container" class="mt-2 d-none">
                                                        <img id="receipt-preview" src="#" alt="Preview" class="img-thumbnail" style="max-height: 150px;">
                                                    </div>
                                                </div>

                                                <div class="alert alert-warning mt-3 mb-0">
                                                    <i class="bi bi-exclamation-triangle me-2"></i>
                                                    Please ensure the Reference Number and Date are clearly visible in the photo.
                                                </div>
                                            </div>
                                        </div>
                                    <?php endif; ?>

                                    <?php if (in_array('payment_cod', $active_methods)): ?>
                                        <div class="payment-panel" id="cod-panel">
                                            <div class="payment-info-box">
                                                <div class="text-center mb-3">
                                                    <i class="bi bi-cash-coin fs-1 text-success mb-3 d-block"></i>
                                                    <h6>Cash on Delivery</h6>
                                                    <p class="text-muted small">Pay when you receive your order</p>
                                                </div>
                                                <div class="alert alert-info">
                                                    <i class="bi bi-info-circle me-2"></i> Additional ₱50 handling fee applies for COD orders
                                                </div>
                                            </div>
                                        </div>
                                    <?php endif; ?>

                                    <?php if (empty($active_methods)): ?>
                                        <div class="text-center p-4">
                                            <p class="text-danger">No payment methods available. Please contact support.</p>
                                        </div>
                                    <?php endif; ?>

                                </div>
                            </div>
                        </div>
                    </div>
                    <input type="hidden" name="subtotal" value="<?= $subtotal ?>">
                    <input type="hidden" name="discount" id="discount-input" value="0">
                    <input type="hidden" name="final_total" id="final-order-total-input" value="<?= $total_amount ?>">
                </form>
            </div>

            <div class="col-lg-4">
                <div class="order-summary-sticky">
                    <div class="order-summary-card">
                        <h4 class="summary-title">
                            <i class="bi bi-receipt me-2"></i> Order Summary
                        </h4>

                        <div class="order-items">
                            <?php if (!empty($items)): ?>
                                <?php foreach ($items as $item): ?>
                                    <div class="order-item">
                                        <div class="item-image">
                                            <img src="<?php echo $item['image']; ?>" alt="Product">
                                            <span class="item-qty"><?php echo $item['qty']; ?></span>
                                        </div>
                                        <div class="item-details">
                                            <div class="item-name"><?php echo $item['name']; ?></div>
                                            <?php if (!empty($item['variants'])): ?>
                                                <small class="text-muted d-block"><?php echo $item['variants']; ?></small>
                                            <?php endif; ?>
                                            <div class="item-price">₱<?php echo number_format($item['price'], 2); ?></div>
                                        </div>
                                        <div class="item-total">₱<?php echo number_format($item['total'], 2); ?></div>
                                    </div>
                                <?php endforeach; ?>
                            <?php else: ?>
                                <p class="text-center py-3">No items found.</p>
                            <?php endif; ?>
                        </div>

                        <?php if ($is_buy_now): ?>
                            <div class="view-all-items text-center small mt-2">
                                <span class="text-muted">Direct Purchase Mode</span>
                            </div>
                        <?php else: ?>
                            <div class="view-all-items">
                                <a href="cart.php"><i class="bi bi-pencil-square me-1"></i> Edit Cart</a>
                            </div>
                        <?php endif; ?>

                        <hr class="summary-divider">

                        <div class="summary-row">
                            <span>Subtotal (<?php echo count($items); ?> item/s)</span>
                            <span class="fw-bold">₱<?php echo number_format($subtotal, 2); ?></span>
                        </div>

                        <div class="summary-row">
                            <span id="summary-shipping-name">Shipping Fee</span>
                            <span id="summary-shipping-price" class="text-success fw-bold">FREE</span>
                        </div>

                        <div class="summary-row text-danger mt-1" id="voucher-row" style="display: none;">
                            <span class="small">Voucher Discount (<span id="summary-voucher-code"></span>)</span>
                            <span id="summary-voucher-amount" class="fw-bold">-₱0.00</span>
                        </div>

                        <div class="summary-row mt-2">
                            <button type="button"
                                id="open-voucher-modal"
                                class="btn btn-primary px-4"
                                data-bs-toggle="modal"
                                data-bs-target="#voucherModal">
                                Apply Voucher
                            </button>
                        </div>

                        <hr class="summary-divider">

                        <div class="summary-total">
                            <span>Total Amount</span>
                            <span id="summary-total-amount">₱<?php echo number_format($total_amount, 2); ?></span>
                        </div>

                        <button type="submit"
                            id="btn-place-order"
                            form="checkoutForm"
                            class="btn btn-primary btn-place-order w-100 mt-3">

                            <i class="bi bi-lock-fill me-2"></i>
                            Place Order —
                            <span id="btn-total-text">
                                ₱<?php echo number_format($total_amount, 2); ?>
                            </span>

                        </button>
                    </div>
                </div>
            </div>
        </div>
    </div>
</section>

<!-- Trust Section -->
<section class="trust-section py-5 bg-light">
    <div class="container">
        <div class="row g-4 text-center">
            <div class="col-md-3">
                <div class="trust-feature">
                    <i class="bi bi-shield-check"></i>
                    <h6>Secure Checkout</h6>
                    <p>256-bit SSL encryption</p>
                </div>
            </div>
            <div class="col-md-3">
                <div class="trust-feature">
                    <i class="bi bi-truck"></i>
                    <h6>Fast Delivery</h6>
                    <p>Free shipping over ₱2,000</p>
                </div>
            </div>
            <div class="col-md-3">
                <div class="trust-feature">
                    <i class="bi bi-arrow-repeat"></i>
                    <h6>Easy Returns</h6>
                    <p>30-day return policy</p>
                </div>
            </div>
            <div class="col-md-3">
                <div class="trust-feature">
                    <i class="bi bi-headset"></i>
                    <h6>24/7 Support</h6>
                    <p>Always here to help</p>
                </div>
            </div>
        </div>
    </div>
</section>

<!-- Address -->
<div class="modal fade" id="changeAddressModal" tabindex="-1" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title">My Addresses</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body">
                <?php
                $all_addresses = $conn->query("SELECT * FROM user_addresses WHERE user_id = $user_id");
                while ($row = $all_addresses->fetch_assoc()):
                ?>
                    <div class="border rounded p-3 mb-2 address-option" style="cursor: pointer;"
                        onclick="selectAddress(<?= htmlspecialchars(json_encode($row)); ?>)">
                        <div class="d-flex justify-content-between">
                            <strong><?= htmlspecialchars($row['first_name'] . ' ' . ($row['last_name'] ?? '')); ?></strong>
                            <span class="text-muted small"><?= htmlspecialchars($row['phone']); ?></span>
                        </div>
                        <div class="small text-secondary mt-1">
                            <?= htmlspecialchars($row['address_line']); ?>, <?= htmlspecialchars($row['city']); ?>
                        </div>
                    </div>
                <?php endwhile; ?>

                <a href="profile.php?tab=addresses" class="btn btn-outline-secondary btn-sm w-100 mt-2">
                    <i class="bi bi-plus-lg"></i> Add New Address
                </a>
            </div>
        </div>
    </div>
</div>

<!-- Voucher -->
<div class="modal fade" id="voucherModal" tabindex="-1" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered">
        <div class="modal-content">
            <div class="modal-header border-0 pb-0">
                <h5 class="modal-title fw-bold">Select a Voucher</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body">
                <div id="voucher-list">
                    <?php
                    // Assuming $user_id is the ID of the logged-in user
                    $current_user = $_SESSION['user_id'] ?? 0;
                    $today = date('Y-m-d');

                    $v_query = "SELECT * FROM aes.vouchers 
                                WHERE (user_id IS NULL OR user_id = '$current_user') 
                                AND is_used = 0 
                                AND expiry_date >= '$today' 
                                ORDER BY discount_amount DESC";
                    $v_res = mysqli_query($conn, $v_query);

                    if (mysqli_num_rows($v_res) > 0):
                        while ($v = mysqli_fetch_assoc($v_res)):
                            $display_discount = ($v['discount_type'] == 'fixed') ? '₱' . number_format($v['discount_amount'], 2) : $v['discount_amount'] . '% OFF';
                    ?>
                            <div class="voucher-option mb-2">
                                <input type="radio" class="btn-check" name="selected_voucher"
                                    id="v_<?= $v['id'] ?>"
                                    value="<?= $v['id'] ?>"
                                    data-code="<?= $v['code'] ?>"
                                    data-amount="<?= $v['discount_amount'] ?>"
                                    data-type="<?= $v['discount_type'] ?>"
                                    autocomplete="off">
                                <label class="btn btn-outline-primary w-100 text-start p-3 d-flex justify-content-between align-items-center" for="v_<?= $v['id'] ?>">
                                    <div>
                                        <span class="d-block fw-bold"><?= htmlspecialchars($v['code']) ?></span>
                                        <small class="text-muted">Expires: <?= date('M d, Y', strtotime($v['expiry_date'])) ?></small>
                                    </div>
                                    <span class="badge bg-primary fs-6"><?= $display_discount ?></span>
                                </label>
                            </div>
                        <?php
                        endwhile;
                    else:
                        ?>
                        <div class="text-center py-4">
                            <i class="bi bi-ticket-perforated fs-1 text-muted"></i>
                            <p class="mt-2 text-muted">No vouchers available for you right now.</p>
                        </div>
                    <?php endif; ?>
                </div>
            </div>
            <div class="modal-footer border-0">
                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancel</button>
                <button type="button"
                    id="apply-voucher-btn"
                    class="btn btn-primary px-4">
                    Apply Voucher
                </button>
            </div>
        </div>
    </div>
</div>



<!-- Checkout Script -->
<script>
    const voucherModal =
        document.getElementById("voucherModal");

    voucherModal.addEventListener("hidden.bs.modal", function() {

        // return focus to safe visible element
        document.getElementById("btn-place-order").focus();

    });
    document.addEventListener("DOMContentLoaded", function() {

        const subtotal = <?= (float)$subtotal ?>;
        let voucherDiscount = 0;

        const form = document.getElementById("checkoutForm");
        const placeBtn = document.getElementById("btn-place-order");

        /* =========================
           UPDATE TOTAL
        ========================= */
        function updateTotal() {

            const selectedShipping =
                document.querySelector('input[name="shipping"]:checked');

            let shipping = 0;

            if (selectedShipping) {
                shipping = parseFloat(selectedShipping.dataset.price);
            }

            let total = subtotal + shipping - voucherDiscount;

            if (total < 0) total = 0;

            const formatted =
                "₱" + total.toLocaleString(undefined, {
                    minimumFractionDigits: 2
                });

            document.getElementById("summary-total-amount").innerText = formatted;
            document.getElementById("btn-total-text").innerText = formatted;
            document.getElementById("final-order-total-input").value = total.toFixed(2);
        }

        /* =========================
           SHIPPING CHANGE
        ========================= */
        document.querySelectorAll('input[name="shipping"]')
            .forEach(r => {
                r.addEventListener("change", updateTotal);
            });

        /* =========================
           PAYMENT VALIDATION
        ========================= */

        const receiptInput = document.getElementById("payment_receipt");

        function updatePaymentRequirement() {

            const selectedPayment =
                document.querySelector('input[name="payment"]:checked');

            if (!selectedPayment) return;

            if (selectedPayment.value === "bank-transfer") {
                receiptInput.required = true;
            } else {
                receiptInput.required = false;
                receiptInput.value = "";
            }
        }

        document.querySelectorAll('input[name="payment"]')
            .forEach(radio => {
                radio.addEventListener("change", updatePaymentRequirement);
            });

        updatePaymentRequirement();

        /* =========================
           APPLY VOUCHER
        ========================= */
        const applyBtn = document.getElementById("apply-voucher-btn");

        if (applyBtn) {

            applyBtn.addEventListener("click", function() {

                const selected =
                    document.querySelector('input[name="selected_voucher"]:checked');

                if (!selected) {
                    alert("Please select a voucher first.");
                    return;
                }

                const code = selected.dataset.code;
                const amount = parseFloat(selected.dataset.amount);
                const type = selected.dataset.type;

                voucherDiscount =
                    type === "fixed" ?
                    amount :
                    subtotal * (amount / 100);

                document.getElementById("voucher-row").style.display = "flex";
                document.getElementById("summary-voucher-code").innerText = code;
                document.getElementById("summary-voucher-amount").innerText =
                    "-₱" + voucherDiscount.toLocaleString(undefined, {
                        minimumFractionDigits: 2
                    });

                updateTotal();

                const modal =
                    bootstrap.Modal.getInstance(
                        document.getElementById("voucherModal")
                    );

                if (modal) modal.hide();
            });

        }


        /* =========================
           PLACE ORDER
        ========================= */
        form.addEventListener("submit", function(e) {

            e.preventDefault();

            placeBtn.disabled = true;

            placeBtn.innerHTML =
                '<span class="spinner-border spinner-border-sm me-2"></span>Processing...';

            const formData = new FormData(form);

            fetch(
                    "process_checkout.php?id=<?= $_GET['id'] ?? '' ?>&qty=<?= $_GET['qty'] ?? '' ?>&variants=<?= $_GET['variants'] ?? '' ?>", {
                        method: "POST",
                        body: formData
                    }
                )
                .then(async response => {

                    const text = await response.text();

                    try {
                        return JSON.parse(text);
                    } catch (e) {
                        console.error("Invalid JSON:", text);
                        throw new Error("Server returned invalid response");
                    }

                })
                .then(data => {

                    if (data.success) {
                        window.location =
                            "order-confirmation.php?id=" + data.order_id;
                    } else {
                        alert(data.message || "Checkout failed");
                        placeBtn.disabled = false;
                        placeBtn.innerHTML = "Place Order";
                    }

                })
                .catch(error => {
                    console.error(error);
                    alert("Checkout failed. Check console.");
                    placeBtn.disabled = false;
                    placeBtn.innerHTML = "Place Order";
                });

        });

        updateTotal();

    });
</script>
<?php
include 'includes/footer.php';
?>