<?php
include 'includes/header.php';
?>

<!-- ================================================
     HERO
================================================ -->
<section class="hp-hero">
    <div class="hp-hero-bg">
        <div class="hp-hero-blob hp-blob-1"></div>
        <div class="hp-hero-blob hp-blob-2"></div>
        <div class="hp-hero-grain"></div>
    </div>

    <div class="hp-container">
        <div class="hp-hero-grid">
            <div class="hp-hero-content">
                <div class="hp-eyebrow">
                    <span class="hp-eyebrow-dot"></span>
                    Trusted Educational Partner
                </div>

                <h1 class="hp-hero-title">
                    Empowering<br>
                    Learning,<br>
                    <span class="hp-title-accent">Inspiring Growth</span>
                </h1>

                <p class="hp-hero-desc">
                    Premium educational supplies designed for modern classrooms. From essentials to innovations, we equip students and teachers for success.
                </p>

                <div class="hp-hero-actions">
                    <a href="#shop" class="hp-btn hp-btn-primary">
                        <svg width="17" height="17" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                            <rect x="3" y="3" width="7" height="7" />
                            <rect x="14" y="3" width="7" height="7" />
                            <rect x="3" y="14" width="7" height="7" />
                            <rect x="14" y="14" width="7" height="7" />
                        </svg>
                        Browse Supplies
                    </a>
                    <a href="#about" class="hp-btn hp-btn-ghost">
                        School Essentials
                        <svg width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                            <line x1="5" y1="12" x2="19" y2="12" />
                            <polyline points="12 5 19 12 12 19" />
                        </svg>
                    </a>
                </div>

                <div class="hp-hero-stats">
                    <div class="hp-stat">
                        <span class="hp-stat-num">500+</span>
                        <span class="hp-stat-label">Schools Trust Us</span>
                    </div>
                    <div class="hp-stat-sep"></div>
                    <div class="hp-stat">
                        <span class="hp-stat-num">10K+</span>
                        <span class="hp-stat-label">Happy Students</span>
                    </div>
                    <div class="hp-stat-sep"></div>
                    <div class="hp-stat">
                        <span class="hp-stat-num">4.8 ★</span>
                        <span class="hp-stat-label">Average Rating</span>
                    </div>
                </div>
            </div>

            <div class="hp-hero-visual">
                <div class="hp-hero-img-wrap">
                    <img src="assets/image.jpg" alt="Educational Supplies" class="hp-hero-img">
                    <div class="hp-floating-card">
                        <div class="hp-fc-icon">
                            <svg width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.5">
                                <path d="M5 8h14M5 8a2 2 0 110-4h14a2 2 0 110 4M5 8v10a2 2 0 002 2h10a2 2 0 002-2V8" />
                            </svg>
                        </div>
                        <div>
                            <strong class="hp-fc-title">Free Shipping</strong>
                            <span class="hp-fc-sub">Orders over ₱2,000</span>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <div class="hp-hero-wave">
        <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 1200 80" preserveAspectRatio="none">
            <path d="M0,40 C200,80 400,0 600,40 C800,80 1000,0 1200,40 L1200,80 L0,80 Z" fill="#FAF7F2" />
        </svg>
    </div>
</section>

<!-- ================================================
     FEATURES
================================================ -->
<section class="hp-features">
    <div class="hp-container">
        <div class="hp-features-grid">
            <?php
            $features = [
                ['icon' => '<path d="M5 8h14M5 8a2 2 0 110-4h14a2 2 0 110 4M5 8v10a2 2 0 002 2h10a2 2 0 002-2V8"/>', 'title' => 'Bulk Orders', 'desc' => 'Special pricing and discounts for schools and institutions'],
                ['icon' => '<circle cx="12" cy="8" r="6"/><path d="M15.477 12.89L17 22l-5-3-5 3 1.523-9.11"/>', 'title' => 'Quality Assured', 'desc' => 'Rigorously tested educational materials you can trust'],
                ['icon' => '<rect x="1" y="3" width="15" height="13"/><polygon points="16 8 20 8 23 11 23 16 16 16 16 8"/><circle cx="5.5" cy="18.5" r="2.5"/><circle cx="18.5" cy="18.5" r="2.5"/>', 'title' => 'Fast Delivery', 'desc' => 'School-ready shipping to meet your timeline'],
                ['icon' => '<path d="M17 21v-2a4 4 0 00-4-4H5a4 4 0 00-4 4v2"/><circle cx="9" cy="7" r="4"/><path d="M23 21v-2a4 4 0 00-3-3.87M16 3.13a4 4 0 010 7.75"/>', 'title' => 'Expert Support', 'desc' => 'Dedicated education specialists ready to help'],
            ];
            foreach ($features as $i => $f): ?>
                <div class="hp-feature-card" style="animation-delay: <?php echo $i * 0.1; ?>s">
                    <div class="hp-feature-icon">
                        <svg width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.5"><?php echo $f['icon']; ?></svg>
                    </div>
                    <h5 class="hp-feature-title"><?php echo $f['title']; ?></h5>
                    <p class="hp-feature-desc"><?php echo $f['desc']; ?></p>
                </div>
            <?php endforeach; ?>
        </div>
    </div>
</section>

<!-- ================================================
     PRODUCTS
================================================ -->
<?php
include 'db/db.php';

$sql = "SELECT 
            p.*, 
            pi.image_path,
            (SELECT MIN(price) FROM product_variants WHERE product_id = p.id) as min_variant_price
        FROM products p
        LEFT JOIN product_images pi ON p.id = pi.product_id AND pi.is_cover = 1
        ORDER BY p.id DESC";

$result = mysqli_query($conn, $sql);
?>

<section id="shop" class="hp-products">
    <div class="hp-container">
        <div class="hp-section-header">
            <div class="hp-section-eyebrow">Our Collection</div>
            <h2 class="hp-section-title">Featured Products</h2>
            <div class="hp-section-line"></div>
        </div>

        <div class="hp-products-grid">
            <?php
            if (mysqli_num_rows($result) > 0):
                while ($product = mysqli_fetch_assoc($result)):
                    $displayPrice = !empty($product['min_variant_price']) ? $product['min_variant_price'] : $product['base_price'];
                    $imagePath = !empty($product['image_path']) ? $product['image_path'] : 'assets/images/default-product.jpg';
            ?>
                    <div class="hp-product-card">
                        <div class="hp-product-img-wrap">
                            <img src="admin/products/uploads/<?php echo $imagePath; ?>"
                                alt="<?php echo htmlspecialchars($product['name']); ?>"
                                class="hp-product-img">
                            <div class="hp-product-actions-overlay">
                                <button class="hp-overlay-btn" title="Quick View">
                                    <svg width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                                        <path d="M1 12s4-8 11-8 11 8 11 8-4 8-11 8-11-8-11-8z" />
                                        <circle cx="12" cy="12" r="3" />
                                    </svg>
                                </button>
                                <button class="hp-overlay-btn" title="Wishlist">
                                    <svg width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                                        <path d="M20.84 4.61a5.5 5.5 0 00-7.78 0L12 5.67l-1.06-1.06a5.5 5.5 0 00-7.78 7.78l1.06 1.06L12 21.23l7.78-7.78 1.06-1.06a5.5 5.5 0 000-7.78z" />
                                    </svg>
                                </button>
                            </div>
                            <span class="hp-product-category"><?php echo htmlspecialchars($product['category']); ?></span>
                        </div>

                        <div class="hp-product-body">
                            <h5 class="hp-product-name"><?php echo htmlspecialchars($product['name']); ?></h5>
                            <p class="hp-product-desc"><?php echo htmlspecialchars($product['small_description']); ?></p>
                            <div class="hp-product-footer">
                                <div>
                                    <span class="hp-product-price">₱<?php echo number_format($displayPrice, 2); ?></span>
                                    <span class="hp-product-avail">✓ Available</span>
                                </div>
                                <a href="product-details.php?id=<?php echo $product['id']; ?>" class="hp-view-btn">
                                    View Options
                                    <svg width="14" height="14" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                                        <line x1="5" y1="12" x2="19" y2="12" />
                                        <polyline points="12 5 19 12 12 19" />
                                    </svg>
                                </a>
                            </div>
                        </div>
                    </div>
                <?php
                endwhile;
            else: ?>
                <div class="hp-empty">
                    <svg width="48" height="48" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1">
                        <path d="M21 16V8a2 2 0 00-1-1.73l-7-4a2 2 0 00-2 0l-7 4A2 2 0 003 8v8a2 2 0 001 1.73l7 4a2 2 0 002 0l7-4A2 2 0 0021 16z" />
                    </svg>
                    <p>No products found.</p>
                </div>
            <?php endif; ?>
        </div>
    </div>
</section>

<?php mysqli_close($conn); ?>

<!-- ================================================
     ABOUT
================================================ -->
<section id="about" class="hp-about">
    <div class="hp-container">
        <div class="hp-about-grid">
            <div class="hp-about-imgs">
                <div class="hp-about-img-main">
                    <img src="https://via.placeholder.com/600x400/2C4A3E/ffffff?text=Classroom+Setup" alt="Classroom">
                </div>
                <div class="hp-about-img-sm hp-about-img-sm1">
                    <img src="https://via.placeholder.com/250x250/C9B99A/1C1410?text=Students" alt="Students">
                </div>
                <div class="hp-about-img-sm hp-about-img-sm2">
                    <img src="https://via.placeholder.com/250x250/E8DDD0/2C4A3E?text=Supplies" alt="Supplies">
                </div>
            </div>

            <div class="hp-about-content">
                <div class="hp-section-eyebrow">Why Choose AES</div>
                <h2 class="hp-about-title">Everything Your School Needs in One Place</h2>
                <p class="hp-about-lead">From basic stationery to advanced teaching aids, AES provides comprehensive educational supplies for all grade levels. We're committed to supporting educators and students with quality materials.</p>

                <div class="hp-about-features">
                    <?php
                    $abtFeatures = [
                        ['Complete Supplies', 'All classroom essentials'],
                        ['STEM Equipment', 'Science & technology'],
                        ['Arts & Crafts', 'Creative materials'],
                        ['PE Equipment', 'Sports & fitness'],
                    ];
                    foreach ($abtFeatures as $af): ?>
                        <div class="hp-about-feature">
                            <div class="hp-about-check">
                                <svg width="13" height="13" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="3">
                                    <polyline points="20 6 9 17 4 12" />
                                </svg>
                            </div>
                            <div>
                                <strong class="hp-about-fname"><?php echo $af[0]; ?></strong>
                                <span class="hp-about-fsub"><?php echo $af[1]; ?></span>
                            </div>
                        </div>
                    <?php endforeach; ?>
                </div>

                <div class="hp-about-actions">
                    <a href="#" class="hp-btn hp-btn-primary">
                        <svg width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                            <path d="M14 2H6a2 2 0 00-2 2v16a2 2 0 002 2h12a2 2 0 002-2V8z" />
                            <polyline points="14 2 14 8 20 8" />
                        </svg>
                        Request Catalog
                    </a>
                    <a href="#" class="hp-btn hp-btn-outline">Contact Us</a>
                </div>
            </div>
        </div>
    </div>
</section>

<!-- ================================================
     NEWSLETTER
================================================ -->
<section class="hp-newsletter">
    <div class="hp-newsletter-bg">
        <div class="hp-newsletter-blob"></div>
        <div class="hp-hero-grain"></div>
    </div>
    <div class="hp-container">
        <div class="hp-newsletter-inner">
            <div class="hp-nl-icon">
                <svg width="28" height="28" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.5">
                    <path d="M4 4h16c1.1 0 2 .9 2 2v12c0 1.1-.9 2-2 2H4c-1.1 0-2-.9-2-2V6c0-1.1.9-2 2-2z" />
                    <polyline points="22,6 12,13 2,6" />
                </svg>
            </div>
            <h2 class="hp-nl-title">Stay Connected with AES</h2>
            <p class="hp-nl-desc">Subscribe to receive news about new products, educational resources, and exclusive school discounts.</p>
            <form class="hp-nl-form" onsubmit="return false;">
                <input type="email" class="hp-nl-input" placeholder="your.email@school.edu" required>
                <button type="submit" class="hp-nl-btn">
                    <svg width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                        <line x1="22" y1="2" x2="11" y2="13" />
                        <polygon points="22 2 15 22 11 13 2 9 22 2" />
                    </svg>
                    Subscribe
                </button>
            </form>
            <p class="hp-nl-note">
                <svg width="13" height="13" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                    <path d="M12 22s8-4 8-10V5l-8-3-8 3v7c0 6 8 10 8 10z" />
                </svg>
                Join 5,000+ educators · No spam · Unsubscribe anytime
            </p>
        </div>
    </div>
</section>

<?php include 'includes/footer.php'; ?>