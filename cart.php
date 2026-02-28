<?php
include 'includes/header.php';
?>

<!-- Breadcrumb -->
<section class="breadcrumb-section py-3 bg-light">
    <div class="container">
        <nav aria-label="breadcrumb">
            <ol class="breadcrumb mb-0">
                <li class="breadcrumb-item"><a href="index.php"><i class="bi bi-house-door me-1"></i>Home</a></li>
                <li class="breadcrumb-item active" aria-current="page">Shopping Cart</li>
            </ol>
        </nav>
    </div>
</section>

<!-- Shopping Cart Section -->
<section class="cart-section py-5">
    <div class="container">
        <div class="row">
            <div class="col-12 mb-4">
                <div class="d-flex align-items-center justify-content-between">
                    <h2 class="fw-bold mb-0">
                        <i class="bi bi-cart3 me-2" style="color: var(--primary-color);"></i>
                        Shopping Cart
                    </h2>
                    <span class="badge bg-primary-subtle text-primary px-3 py-2 fs-6">
                        12 Items
                    </span>
                </div>
            </div>
        </div>

        <div class="row g-4">
            <!-- Cart Items -->
            <div class="col-lg-8">
                <div class="cart-items-wrapper">
                    <?php
                    $cartItems = [
                        [
                            'id' => 1,
                            'name' => 'Student Notebook Set',
                            'description' => '5 Notebooks, 80 pages each, College Ruled',
                            'price' => 299,
                            'quantity' => 2,
                            'image' => 'https://via.placeholder.com/120x120/E8F4F8/1e3a5f?text=Notebook',
                            'stock' => 50,
                            'category' => 'Stationery'
                        ],
                        [
                            'id' => 2,
                            'name' => 'Scientific Calculator',
                            'description' => 'Casio FX-991ES Plus, 417 Functions',
                            'price' => 899,
                            'quantity' => 1,
                            'image' => 'https://via.placeholder.com/120x120/E8F4F8/1e3a5f?text=Calculator',
                            'stock' => 25,
                            'category' => 'Electronics'
                        ],
                        [
                            'id' => 3,
                            'name' => 'Art Supply Kit',
                            'description' => 'Complete set with brushes, paints, and canvas',
                            'price' => 1299,
                            'quantity' => 1,
                            'image' => 'https://via.placeholder.com/120x120/E8F4F8/1e3a5f?text=Art+Kit',
                            'stock' => 15,
                            'category' => 'Art Materials'
                        ],
                        [
                            'id' => 4,
                            'name' => 'Geometry Tool Set',
                            'description' => 'Compass, protractor, rulers, and triangles',
                            'price' => 449,
                            'quantity' => 3,
                            'image' => 'https://via.placeholder.com/120x120/E8F4F8/1e3a5f?text=Geometry',
                            'stock' => 40,
                            'category' => 'Mathematics'
                        ],
                        [
                            'id' => 5,
                            'name' => 'Premium Pen Set',
                            'description' => 'Set of 10 ballpoint pens, assorted colors',
                            'price' => 199,
                            'quantity' => 5,
                            'image' => 'https://via.placeholder.com/120x120/E8F4F8/1e3a5f?text=Pens',
                            'stock' => 100,
                            'category' => 'Stationery'
                        ]
                    ];

                    foreach ($cartItems as $item):
                        $subtotal = $item['price'] * $item['quantity'];
                    ?>
                        <div class="cart-item" data-item-id="<?php echo $item['id']; ?>">
                            <div class="row g-0 align-items-center">
                                <!-- Product Image -->
                                <div class="col-auto">
                                    <div class="cart-item-image">
                                        <img src="<?php echo $item['image']; ?>" alt="<?php echo $item['name']; ?>">
                                        <span class="category-badge"><?php echo $item['category']; ?></span>
                                    </div>
                                </div>

                                <!-- Product Details -->
                                <div class="col">
                                    <div class="cart-item-details">
                                        <h5 class="cart-item-title mb-2"><?php echo $item['name']; ?></h5>
                                        <p class="cart-item-description mb-2"><?php echo $item['description']; ?></p>
                                        <div class="d-flex align-items-center gap-3">
                                            <span class="price-badge">₱<?php echo number_format($item['price'], 2); ?></span>
                                            <span class="stock-badge">
                                                <i class="bi bi-box-seam me-1"></i><?php echo $item['stock']; ?> in stock
                                            </span>
                                        </div>
                                    </div>
                                </div>

                                <!-- Quantity Controls -->
                                <div class="col-auto">
                                    <div class="quantity-control">
                                        <button class="qty-btn qty-decrease" data-action="decrease">
                                            <i class="bi bi-dash"></i>
                                        </button>
                                        <input type="number" class="qty-input" value="<?php echo $item['quantity']; ?>" min="1" max="<?php echo $item['stock']; ?>" readonly>
                                        <button class="qty-btn qty-increase" data-action="increase">
                                            <i class="bi bi-plus"></i>
                                        </button>
                                    </div>
                                </div>

                                <!-- Subtotal -->
                                <div class="col-auto">
                                    <div class="cart-item-subtotal">
                                        <div class="subtotal-label">Subtotal</div>
                                        <div class="subtotal-amount">₱<?php echo number_format($subtotal, 2); ?></div>
                                    </div>
                                </div>

                                <!-- Remove Button -->
                                <div class="col-auto">
                                    <button class="btn-remove" title="Remove item">
                                        <i class="bi bi-trash"></i>
                                    </button>
                                </div>
                            </div>
                        </div>
                    <?php endforeach; ?>
                </div>

                <!-- Cart Actions -->
                <div class="cart-actions mt-4">
                    <div class="d-flex gap-3 flex-wrap">
                        <a href="index.php" class="btn btn-outline-primary btn-lg">
                            <i class="bi bi-arrow-left me-2"></i>Continue Shopping
                        </a>
                        <button class="btn btn-outline-danger btn-lg">
                            <i class="bi bi-trash me-2"></i>Clear Cart
                        </button>
                        <button class="btn btn-outline-secondary btn-lg ms-auto">
                            <i class="bi bi-arrow-clockwise me-2"></i>Update Cart
                        </button>
                    </div>
                </div>
            </div>

            <!-- Order Summary Sidebar -->
            <div class="col-lg-4">
                <div class="order-summary-sticky">
                    <!-- Promo Code -->
                    <div class="promo-card mb-4">
                        <h5 class="promo-title">
                            <i class="bi bi-tag-fill me-2"></i>Apply Promo Code
                        </h5>
                        <form class="promo-form">
                            <div class="input-group">
                                <input type="text" class="form-control" placeholder="Enter promo code">
                                <button class="btn btn-primary" type="submit">Apply</button>
                            </div>
                        </form>
                    </div>

                    <!-- Order Summary -->
                    <div class="order-summary-card">
                        <h4 class="summary-title">
                            <i class="bi bi-receipt me-2"></i>Order Summary
                        </h4>

                        <div class="summary-row">
                            <span>Subtotal (12 items)</span>
                            <span class="fw-bold">₱4,940.00</span>
                        </div>

                        <div class="summary-row">
                            <span>Shipping Fee</span>
                            <span class="text-success fw-bold">FREE</span>
                        </div>

                        <div class="summary-row">
                            <span>
                                Discount
                                <span class="badge bg-success-subtle text-success ms-1">SCHOOL10</span>
                            </span>
                            <span class="text-success fw-bold">-₱494.00</span>
                        </div>

                        <hr class="summary-divider">

                        <div class="summary-total">
                            <span>Total Amount</span>
                            <span>₱4,446.00</span>
                        </div>

                        <div class="savings-badge">
                            <i class="bi bi-piggy-bank me-2"></i>
                            You're saving ₱494.00 on this order!
                        </div>

                        <button class="btn btn-primary btn-checkout w-100">
                            <i class="bi bi-lock-fill me-2"></i>Proceed to Checkout
                        </button>

                        <div class="payment-methods mt-3">
                            <small class="text-muted d-block mb-2 text-center">We Accept</small>
                            <div class="d-flex justify-content-center gap-2">
                                <div class="payment-icon"><i class="bi bi-credit-card-fill"></i></div>
                                <div class="payment-icon"><i class="bi bi-paypal"></i></div>
                                <div class="payment-icon"><i class="bi bi-wallet2"></i></div>
                                <div class="payment-icon"><i class="bi bi-bank"></i></div>
                            </div>
                        </div>

                        <!-- Trust Badges -->
                        <div class="trust-badges mt-4">
                            <div class="trust-item">
                                <i class="bi bi-shield-check"></i>
                                <small>Secure Payment</small>
                            </div>
                            <div class="trust-item">
                                <i class="bi bi-truck"></i>
                                <small>Fast Delivery</small>
                            </div>
                            <div class="trust-item">
                                <i class="bi bi-arrow-repeat"></i>
                                <small>Easy Returns</small>
                            </div>
                        </div>
                    </div>

                    <!-- Help Card -->
                    <div class="help-card mt-4">
                        <div class="d-flex align-items-start gap-3">
                            <div class="help-icon">
                                <i class="bi bi-headset"></i>
                            </div>
                            <div>
                                <h6 class="mb-1">Need Help?</h6>
                                <p class="small mb-2">Our support team is here to assist you.</p>
                                <a href="#" class="btn btn-sm btn-outline-primary">
                                    <i class="bi bi-chat-dots me-1"></i>Chat with us
                                </a>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</section>

<!-- Recommended Products -->
<section class="recommended-section py-5 bg-light">
    <div class="container">
        <div class="text-center mb-5">
            <h3 class="fw-bold mb-2">You Might Also Like</h3>
            <p class="text-muted">Complete your school supplies</p>
        </div>

        <div class="row row-cols-1 row-cols-md-2 row-cols-lg-4 g-4">
            <?php
            $recommendedProducts = [
                ['name' => 'Highlighter Set', 'price' => '149', 'image' => 'https://via.placeholder.com/250x250/E8F4F8/1e3a5f?text=Highlighters'],
                ['name' => 'Sticky Notes Pack', 'price' => '99', 'image' => 'https://via.placeholder.com/250x250/E8F4F8/1e3a5f?text=Sticky+Notes'],
                ['name' => 'Pencil Case', 'price' => '249', 'image' => 'https://via.placeholder.com/250x250/E8F4F8/1e3a5f?text=Pencil+Case'],
                ['name' => 'Eraser Set', 'price' => '79', 'image' => 'https://via.placeholder.com/250x250/E8F4F8/1e3a5f?text=Erasers']
            ];

            foreach ($recommendedProducts as $product):
            ?>
                <div class="col">
                    <div class="recommended-card">
                        <div class="recommended-image">
                            <img src="<?php echo $product['image']; ?>" alt="<?php echo $product['name']; ?>">
                            <button class="quick-add-btn">
                                <i class="bi bi-cart-plus"></i>
                            </button>
                        </div>
                        <div class="recommended-info">
                            <h6 class="mb-1"><?php echo $product['name']; ?></h6>
                            <div class="d-flex align-items-center justify-content-between">
                                <span class="fw-bold text-primary">₱<?php echo $product['price']; ?></span>
                                <div class="text-warning small">
                                    <i class="bi bi-star-fill"></i>
                                    <i class="bi bi-star-fill"></i>
                                    <i class="bi bi-star-fill"></i>
                                    <i class="bi bi-star-fill"></i>
                                    <i class="bi bi-star-half"></i>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            <?php endforeach; ?>
        </div>
    </div>
</section>
<!-- Cart Functionality Script -->
<script>
    document.addEventListener('DOMContentLoaded', function() {
        // Quantity Controls
        document.querySelectorAll('.qty-btn').forEach(button => {
            button.addEventListener('click', function() {
                const action = this.dataset.action;
                const input = this.parentElement.querySelector('.qty-input');
                let value = parseInt(input.value);
                const max = parseInt(input.max);

                if (action === 'increase' && value < max) {
                    input.value = value + 1;
                } else if (action === 'decrease' && value > 1) {
                    input.value = value - 1;
                }

                updateCartTotals();
            });
        });

        // Remove Item
        document.querySelectorAll('.btn-remove').forEach(button => {
            button.addEventListener('click', function() {
                if (confirm('Are you sure you want to remove this item from your cart?')) {
                    const cartItem = this.closest('.cart-item');
                    cartItem.style.opacity = '0';
                    cartItem.style.transform = 'translateX(100px)';
                    setTimeout(() => {
                        cartItem.remove();
                        updateCartTotals();
                    }, 300);
                }
            });
        });

        // Quick Add to Cart (Recommended Products)
        document.querySelectorAll('.quick-add-btn').forEach(button => {
            button.addEventListener('click', function() {
                this.innerHTML = '<i class="bi bi-check2"></i>';
                this.style.background = '#22c55e';
                setTimeout(() => {
                    this.innerHTML = '<i class="bi bi-cart-plus"></i>';
                    this.style.background = '';
                }, 1500);
            });
        });

        function updateCartTotals() {
            // This would typically make an AJAX call to update the cart on the server
            console.log('Cart updated');
        }
    });
</script>

<?php
include 'includes/footer.php';
?>