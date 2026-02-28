<?php
include 'includes/header.php';
include 'db/db.php';

// 1. Get Product ID
$product_id = isset($_GET['id']) ? intval($_GET['id']) : 0;

// 2. Fetch Product Info
$product_res = mysqli_query($conn, "SELECT * FROM products WHERE id = $product_id");
$product = mysqli_fetch_assoc($product_res);

if (!$product) {
    die("Product not found.");
}

// 3. Fetch Images
$img_sql = "SELECT image_path, is_cover FROM product_images WHERE product_id = $product_id ORDER BY is_cover DESC";
$img_result = mysqli_query($conn, $img_sql);

$images = [];
while ($row = mysqli_fetch_assoc($img_result)) {
    $images[] = $row;
}

$main_image = (!empty($images)) ? $images[0]['image_path'] : 'assets/images/default.jpg';

// 4. Fetch Variants
$variant_query = mysqli_query($conn, "SELECT * FROM product_variants WHERE product_id = $product_id");

$variant_groups = [];
$all_variant_prices = [];

if ($variant_query && mysqli_num_rows($variant_query) > 0) {
    while ($v = mysqli_fetch_assoc($variant_query)) {
        $variant_groups[$v['variant_type']][] = $v;
        $all_variant_prices[] = $v['price'];
    }
}

// 5. Calculate Price Range
$min_price = $product['base_price'];
$max_price = $product['base_price'];

if (!empty($all_variant_prices)) {
    $min_price = min($all_variant_prices);
    $max_price = max($all_variant_prices);
}
?>



<!-- Breadcrumb -->
<section class="pd-breadcrumb">
    <div class="pd-container">
        <nav class="pd-breadcrumb-nav">
            <a href="index.php">Home</a>
            <span class="pd-sep">—</span>
            <a href="#">Stationery</a>
            <span class="pd-sep">—</span>
            <a href="#">Notebooks</a>
            <span class="pd-sep">—</span>
            <span class="pd-breadcrumb-active">Student Notebook Set</span>
        </nav>
    </div>
</section>

<!-- Product Details Section -->
<section class="pd-section">
    <div class="pd-container">
        <div class="pd-grid">

            <!-- LEFT: Gallery -->
            <div class="pd-gallery-col">
                <div class="pd-gallery">
                    <div class="pd-category-label-wrap">
                        <span class="pd-category-label">
                            <i class="bi bi-tag me-1"></i><?php echo htmlspecialchars($product['category']); ?>
                        </span>
                    </div>
                    <div class="pd-main-img-wrap">
                        <div class="pd-img-inner">
                            <img src="admin/products/uploads/<?php echo $main_image; ?>"
                                alt="<?php echo htmlspecialchars($product['name']); ?>"
                                class="pd-main-img"
                                id="mainImage">
                            <button class="pd-zoom-btn" onclick="openImageZoom()" title="Zoom">
                                <svg width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.5">
                                    <circle cx="11" cy="11" r="8" />
                                    <line x1="21" y1="21" x2="16.65" y2="16.65" />
                                    <line x1="11" y1="8" x2="11" y2="14" />
                                    <line x1="8" y1="11" x2="14" y2="11" />
                                </svg>
                            </button>
                        </div>
                    </div>

                    <?php if (!empty($images)): ?>
                        <div class="pd-thumbs">
                            <?php foreach ($images as $index => $img): ?>
                                <button class="pd-thumb <?php echo ($index === 0) ? 'active' : ''; ?>"
                                    onclick="changeImage(this, 'admin/products/uploads/<?php echo $img['image_path']; ?>')">
                                    <img src="admin/products/uploads/<?php echo $img['image_path']; ?>" alt="View <?php echo $index + 1; ?>">
                                </button>
                            <?php endforeach; ?>
                        </div>
                    <?php endif; ?>
                </div>
            </div>

            <!-- RIGHT: Info -->
            <div class="pd-info-col">
                <div class="pd-info">

                    <div class="pd-status-row">
                        <span class="pd-stock-dot"></span>
                        <span class="pd-stock-label" id="mainStockBadge">In Stock</span>
                        <span class="pd-divider">·</span>
                        <span class="pd-sku">SKU: <?php echo htmlspecialchars($product['sku'] ?? 'N/A'); ?></span>
                    </div>

                    <h1 class="pd-title"><?php echo htmlspecialchars($product['name']); ?></h1>

                    <div class="pd-rating-row">
                        <div class="pd-stars">
                            <?php for ($i = 0; $i < 4; $i++): ?><span class="pd-star filled">★</span><?php endfor; ?>
                            <span class="pd-star half">★</span>
                        </div>
                        <span class="pd-rating-text">4.5 · <a href="#reviews" class="pd-review-link">248 reviews</a></span>
                    </div>

                    <div class="pd-price-block">
                        <div class="pd-price" id="displayPrice">
                            <?php
                            if ($min_price != $max_price) {
                                echo "₱" . number_format($min_price, 2) . " – ₱" . number_format($max_price, 2);
                            } else {
                                echo "₱" . number_format($min_price, 2);
                            }
                            ?>
                        </div>
                        <p class="pd-price-note">Price may vary by selected option</p>
                    </div>

                    <p class="pd-desc"><?php echo htmlspecialchars($product['small_description']); ?></p>

                    <div class="pd-divider-line"></div>

                    <!-- Variants -->
                    <?php foreach ($variant_groups as $type => $options): ?>
                        <div class="pd-variant-group">
                            <label class="pd-variant-label">Select <?php echo ucwords($type); ?></label>
                            <div class="pd-variant-options">
                                <?php foreach ($options as $opt): ?>
                                    <button type="button" class="pd-variant-btn"
                                        onclick="updateVariantPrice(this, <?php echo $opt['price']; ?>, <?php echo $opt['stock']; ?>)">
                                        <?php echo htmlspecialchars($opt['variant_value']); ?>
                                    </button>
                                <?php endforeach; ?>
                            </div>
                        </div>
                    <?php endforeach; ?>

                    <!-- Quantity -->
                    <div class="pd-qty-row">
                        <label class="pd-variant-label">Quantity</label>
                        <div class="pd-qty-wrap">
                            <div class="pd-qty-ctrl">
                                <button class="pd-qty-btn" onclick="decreaseQuantity()">−</button>
                                <input type="number" class="pd-qty-input" id="quantity" value="1" min="1" max="1" readonly>
                                <button class="pd-qty-btn" onclick="increaseQuantity()">+</button>
                            </div>
                            <span class="pd-stock-info" id="stockDisplay">Please select an option</span>
                        </div>
                    </div>

                    <div class="pd-actions d-flex flex-column gap-3">
                        <div class="d-flex gap-2">
                            <button class="pd-btn-cart flex-grow-1" id="addToCartBtn" disabled onclick="addToCart(<?php echo $product['id']; ?>)">
                                <svg width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.5" class="me-1">
                                    <path d="M6 2L3 6v14a2 2 0 002 2h14a2 2 0 002-2V6l-3-4z" />
                                    <line x1="3" y1="6" x2="21" y2="6" />
                                    <path d="M16 10a4 4 0 01-8 0" />
                                </svg>
                                Add to Cart
                            </button>

                            <button class="pd-btn-buy flex-grow-1" id="buyNowBtn" disabled onclick="buyNow(<?php echo $product['id']; ?>)">
                                <i class="bi bi-lightning-fill me-1"></i>
                                Buy Now
                            </button>
                        </div>

                        <div class="text-center">
                            <button type="button" class="btn btn-link btn-sm text-decoration-none" data-bs-toggle="modal" data-bs-target="#bulkOrderModal">
                                <i class="bi bi-box-seam me-1"></i> Interested in Bulk Orders?
                            </button>
                        </div>
                    </div>

                    <!-- Guarantees -->
                    <div class="pd-guarantees">
                        <div class="pd-guarantee-item">
                            <svg width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.5">
                                <rect x="1" y="3" width="15" height="13" />
                                <polygon points="16 8 20 8 23 11 23 16 16 16 16 8" />
                                <circle cx="5.5" cy="18.5" r="2.5" />
                                <circle cx="18.5" cy="18.5" r="2.5" />
                            </svg>
                            <span>Free delivery over ₱999</span>
                        </div>
                        <div class="pd-guarantee-item">
                            <svg width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.5">
                                <path d="M12 22s8-4 8-10V5l-8-3-8 3v7c0 6 8 10 8 10z" />
                            </svg>
                            <span>30-day return policy</span>
                        </div>
                        <div class="pd-guarantee-item">
                            <svg width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.5">
                                <polyline points="20 6 9 17 4 12" />
                            </svg>
                            <span>Authentic & quality assured</span>
                        </div>
                    </div>

                    <!-- Full Description -->
                    <details class="pd-full-desc">
                        <summary class="pd-full-desc-toggle">Full Details <span class="pd-toggle-icon">+</span></summary>
                        <div class="pd-full-desc-body">
                            <?php echo nl2br(htmlspecialchars($product['full_description'])); ?>
                        </div>
                    </details>

                </div>
            </div>
        </div>

        <!-- ===== TABS ===== -->
        <div class="pd-tabs-wrap">
            <div class="pd-tabs-nav">
                <button class="pd-tab-btn active" data-target="pd-tab-specs">Specifications</button>
                <button class="pd-tab-btn" data-target="pd-tab-desc">Description</button>
                <button class="pd-tab-btn" data-target="pd-tab-reviews">Reviews <span class="pd-tab-count">248</span></button>
            </div>

            <!-- Specs -->
            <div class="pd-tab-pane active" id="pd-tab-specs">
                <div class="pd-specs-grid">
                    <?php
                    $specs = [
                        ['Brand', 'AES Premium'],
                        ['Product Type', 'Notebook Set'],
                        ['Quantity', '5 Notebooks'],
                        ['Pages per Notebook', '80 Pages (160 Sides)'],
                        ['Paper Size', 'A4 (210mm × 297mm)'],
                        ['Paper Type', '80gsm White Paper'],
                        ['Rule Type', 'College Ruled (7mm spacing)'],
                        ['Binding', 'Perfect Bound'],
                        ['Cover Material', '300gsm Cardboard'],
                        ['Colors', 'Assorted (5 Different Colors)'],
                        ['Weight', '850g per set'],
                        ['Recommended Age', '12+ years'],
                    ];
                    foreach ($specs as $spec): ?>
                        <div class="pd-spec-row">
                            <span class="pd-spec-key"><?php echo $spec[0]; ?></span>
                            <span class="pd-spec-val"><?php echo $spec[1]; ?></span>
                        </div>
                    <?php endforeach; ?>
                </div>
            </div>

            <!-- Description -->
            <div class="pd-tab-pane" id="pd-tab-desc">
                <div class="pd-long-desc">
                    <h3>About This Product</h3>
                    <p>The AES Premium Student Notebook Set is designed specifically for the needs of modern students. Whether you're in high school, college, or professional training, these notebooks provide the perfect balance of quality, durability, and affordability.</p>

                    <h4>Premium Quality Materials</h4>
                    <p>Each notebook features 80gsm white paper that provides a smooth writing experience while preventing ink bleed-through. The college-ruled lines (7mm spacing) are perfect for detailed note-taking and ensure your handwriting remains neat and organized.</p>

                    <h4>Durable Construction</h4>
                    <p>Our perfect binding technique ensures pages stay securely in place throughout the academic year. The 300gsm cardboard covers protect your notes from wear and tear, making these notebooks ideal for daily use.</p>

                    <h4>Versatile Use</h4>
                    <ul class="pd-desc-list">
                        <li>Class notes and lectures</li>
                        <li>Homework assignments</li>
                        <li>Study guides and exam preparation</li>
                        <li>Project planning and research</li>
                        <li>Personal journaling</li>
                    </ul>

                    <h4>Eco-Friendly Choice</h4>
                    <p>We're committed to sustainability. Our notebooks are made from responsibly sourced paper and feature recyclable materials throughout.</p>
                </div>
            </div>

            <!-- Reviews -->
            <div class="pd-tab-pane" id="pd-tab-reviews">
                <div class="pd-reviews">
                    <div class="pd-review-summary">
                        <div class="pd-big-score">
                            <div class="pd-score-num">4.5</div>
                            <div class="pd-score-stars">★★★★<span style="opacity:0.35">★</span></div>
                            <div class="pd-score-label">248 reviews</div>
                        </div>
                        <div class="pd-rating-bars">
                            <?php
                            $bars = [
                                [5, 70, 174],
                                [4, 20, 50],
                                [3, 6, 15],
                                [2, 3, 7],
                                [1, 1, 2],
                            ];
                            foreach ($bars as $b): ?>
                                <div class="pd-bar-row">
                                    <span class="pd-bar-label"><?php echo $b[0]; ?>★</span>
                                    <div class="pd-bar-track">
                                        <div class="pd-bar-fill" style="width:<?php echo $b[1]; ?>%"></div>
                                    </div>
                                    <span class="pd-bar-count"><?php echo $b[2]; ?></span>
                                </div>
                            <?php endforeach; ?>
                        </div>
                    </div>

                    <div class="pd-review-list">
                        <?php
                        $reviews = [
                            ['MR', 'Maria Rodriguez', 'February 10, 2026', 5, 'Excellent quality notebooks! The paper is thick enough that my fountain pen doesn\'t bleed through. Perfect for my college classes. Highly recommended!', 24],
                            ['JD', 'John Dela Cruz', 'February 8, 2026', 4, 'Good value for money. The binding is sturdy and the cover quality is great. Lost one star because I wish they had more color options, but overall very satisfied.', 18],
                            ['ST', 'Sarah Tan', 'February 5, 2026', 5, 'Perfect for my high school daughter. She loves the different colors and the quality is much better than cheaper alternatives. Will definitely buy again!', 32],
                        ];
                        foreach ($reviews as $r): ?>
                            <div class="pd-review-item">
                                <div class="pd-review-top">
                                    <div class="pd-reviewer">
                                        <div class="pd-avatar"><?php echo $r[0]; ?></div>
                                        <div>
                                            <strong class="pd-reviewer-name"><?php echo $r[1]; ?></strong>
                                            <span class="pd-review-date"><?php echo $r[2]; ?></span>
                                        </div>
                                    </div>
                                    <div class="pd-review-stars">
                                        <?php for ($i = 0; $i < $r[3]; $i++): ?>★<?php endfor; ?>
                                        <?php for ($i = $r[3]; $i < 5; $i++): ?><span style="opacity:0.25">★</span><?php endfor; ?>
                                    </div>
                                </div>
                                <p class="pd-review-text"><?php echo $r[4]; ?></p>
                                <div class="pd-review-footer">
                                    <button class="pd-helpful-btn">👍 Helpful (<?php echo $r[5]; ?>)</button>
                                    <span class="pd-verified">✓ Verified Purchase</span>
                                </div>
                            </div>
                        <?php endforeach; ?>
                    </div>

                    <button class="pd-write-review-btn">Write a Review</button>
                </div>
            </div>
        </div>

        <!-- ===== RELATED PRODUCTS ===== -->
        <div class="pd-related">
            <div class="pd-related-header">
                <h2 class="pd-related-title">You May Also Like</h2>
                <div class="pd-related-line"></div>
            </div>
            <div class="pd-related-grid">
                <?php
                $relatedProducts = [
                    ['name' => 'Premium Pen Set', 'price' => '199', 'rating' => '4.8', 'image' => 'https://via.placeholder.com/400x400/F5F0EB/2C1810?text=Pens'],
                    ['name' => 'Geometry Tool Set', 'price' => '449', 'rating' => '4.6', 'image' => 'https://via.placeholder.com/400x400/F0EBF5/1C1040?text=Geometry'],
                    ['name' => 'Highlighter Set', 'price' => '149', 'rating' => '4.7', 'image' => 'https://via.placeholder.com/400x400/EBF5F0/0D3320?text=Highlighters'],
                    ['name' => 'Sticky Notes Pack', 'price' => '99', 'rating' => '4.5', 'image' => 'https://via.placeholder.com/400x400/F5EBF0/401020?text=Sticky+Notes'],
                ];
                foreach ($relatedProducts as $rp): ?>
                    <div class="pd-related-card">
                        <div class="pd-related-img-wrap">
                            <img src="<?php echo $rp['image']; ?>" alt="<?php echo $rp['name']; ?>" class="pd-related-img">
                            <button class="pd-related-quick">Quick View</button>
                        </div>
                        <div class="pd-related-info">
                            <h5 class="pd-related-name"><?php echo $rp['name']; ?></h5>
                            <div class="pd-related-meta">
                                <span class="pd-related-rating">★ <?php echo $rp['rating']; ?></span>
                                <span class="pd-related-price">₱<?php echo $rp['price']; ?></span>
                            </div>
                            <button class="pd-related-add">Add to Cart</button>
                        </div>
                    </div>
                <?php endforeach; ?>
            </div>
        </div>

    </div>
</section>

<!-- Bulk Order Modal -->
<div class="modal fade" id="bulkOrderModal" tabindex="-1" aria-labelledby="bulkOrderModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered">
        <div class="modal-content">
            <div class="modal-header border-0">
                <h5 class="modal-title fw-bold" id="bulkOrderModalLabel">Bulk Order Inquiry</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <form action="process_bulk_order.php" method="POST">
                <div class="modal-body">
                    <p class="small text-muted mb-4">Inquiring for: <strong><?php echo htmlspecialchars($product['name']); ?></strong></p>

                    <input type="hidden" name="product_id" value="<?php echo $product['id']; ?>">

                    <div class="mb-3">
                        <label class="form-label small fw-bold">Estimated Quantity</label>
                        <input type="number" name="bulk_qty" class="form-control" placeholder="Minimum 50 pcs" min="50" required>
                    </div>

                    <div class="mb-3">
                        <label class="form-label small fw-bold">Contact Email/Phone</label>
                        <input type="text" name="contact_info" class="form-control" required>
                    </div>

                    <div class="mb-3">
                        <label class="form-label small fw-bold">Additional Notes</label>
                        <textarea name="notes" class="form-control" rows="3" placeholder="Specific colors, branding requirements, etc."></textarea>
                    </div>
                </div>
                <div class="modal-footer border-0">
                    <button type="button" class="btn btn-light" data-bs-dismiss="modal">Cancel</button>
                    <button type="submit" class="btn btn-dark px-4">Submit Inquiry</button>
                </div>
            </form>
        </div>
    </div>
</div>

<script>
    // Change main image
    function changeImage(btn, src) {
        document.getElementById('mainImage').src = src;
        document.querySelectorAll('.pd-thumb').forEach(t => t.classList.remove('active'));
        btn.classList.add('active');
    }

    // Quantity
    function increaseQuantity() {
        const input = document.getElementById('quantity');
        if (parseInt(input.value) < parseInt(input.max)) input.value = parseInt(input.value) + 1;
    }

    function decreaseQuantity() {
        const input = document.getElementById('quantity');
        if (parseInt(input.value) > parseInt(input.min)) input.value = parseInt(input.value) - 1;
    }

    function updateVariantPrice(btn, price, stock) {
        // 1. Handle UI Selection
        btn.closest('.pd-variant-options').querySelectorAll('.pd-variant-btn').forEach(b => b.classList.remove('active'));
        btn.classList.add('active');

        // 2. Update Price
        document.getElementById('displayPrice').innerText = '₱' + parseFloat(price).toLocaleString(undefined, {
            minimumFractionDigits: 2
        });

        // 3. Elements to Update
        const qtyInput = document.getElementById('quantity');
        const stockDisplay = document.getElementById('stockDisplay');
        const statusLabel = document.getElementById('mainStockBadge');
        const stockDot = document.querySelector('.pd-stock-dot');

        // Select BOTH action buttons
        const cartBtn = document.getElementById('addToCartBtn');
        const buyBtn = document.getElementById('buyNowBtn');

        // 4. Update Stock Limits
        qtyInput.max = stock;
        qtyInput.value = stock > 0 ? 1 : 0;

        // 5. Check if ALL variant groups have a selection
        // (This prevents adding to cart if they picked "Size" but forgot "Color")
        const totalGroups = document.querySelectorAll('.pd-variant-group').length;
        const selectedGroups = document.querySelectorAll('.pd-variant-btn.active').length;
        const isReady = (selectedGroups === totalGroups);

        if (stock > 0) {
            stockDisplay.innerText = stock + ' pieces available';
            stockDisplay.style.color = '#3D9970';
            statusLabel.innerText = 'In Stock';
            statusLabel.style.color = '#3D9970';
            if (stockDot) stockDot.classList.remove('out');

            // Only enable buttons if stock exists AND all options are picked
            if (isReady) {
                cartBtn.disabled = false;
                if (buyBtn) buyBtn.disabled = false;
            }
        } else {
            stockDisplay.innerText = 'Out of Stock';
            stockDisplay.style.color = '#E74C3C';
            statusLabel.innerText = 'Out of Stock';
            statusLabel.style.color = '#E74C3C';
            if (stockDot) stockDot.classList.add('out');

            // Disable both buttons if out of stock
            cartBtn.disabled = true;
            if (buyBtn) buyBtn.disabled = true;
        }
    }

    // 1. Function for Add to Cart (AJAX style)
    function addToCart(productId) {
        const data = getProductData(productId);

        // Example using fetch to send data to your PHP backend
        fetch('handlers/cart_handler.php', {
                method: 'POST',
                headers: {
                    'Content-Type': 'application/json'
                },
                body: JSON.stringify(data)
            })
            .then(response => response.json())
            .then(res => {
                if (res.success) {
                    alert('Added to cart successfully!');
                    // Update your header cart count here if needed
                }
            });
    }

    // 2. Function for Buy Now (Direct Redirect)
    function buyNow(productId) {
        const data = getProductData(productId);

        // Create a URL with query parameters to send to checkout
        const params = new URLSearchParams({
            action: 'buynow',
            id: data.id,
            qty: data.qty,
            variants: data.variants.join('|') // joins variants with a pipe (e.g., "Blue|A4")
        });

        // Redirect user to checkout page
        window.location.href = 'checkout.php?' + params.toString();
    }

    // Helper function to gather all selected info
    function getProductData(productId) {
        const qty = document.getElementById('quantity').value;
        const selectedVariants = [];

        // Get text from all active variant buttons
        document.querySelectorAll('.pd-variant-btn.active').forEach(btn => {
            selectedVariants.push(btn.innerText.trim());
        });

        return {
            id: productId,
            qty: qty,
            variants: selectedVariants
        };
    }

    // Zoom placeholder
    function openImageZoom() {
        alert('Image zoom would open here.');
    }

    // Tab switching
    document.querySelectorAll('.pd-tab-btn').forEach(btn => {
        btn.addEventListener('click', () => {
            document.querySelectorAll('.pd-tab-btn').forEach(b => b.classList.remove('active'));
            document.querySelectorAll('.pd-tab-pane').forEach(p => p.classList.remove('active'));
            btn.classList.add('active');
            document.getElementById(btn.dataset.target).classList.add('active');
        });
    });

    // Full desc toggle icon
    document.querySelector('.pd-full-desc').addEventListener('toggle', function() {
        document.querySelector('.pd-toggle-icon').textContent = this.open ? '−' : '+';
    });
</script>

<?php include 'includes/footer.php'; ?>