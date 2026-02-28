<?php
include 'db/db.php';
session_start();

// 1. SECURITY CHECK: Must be at the very top!
if (!isset($_SESSION['user_id']) || $_SESSION['role'] !== 'user') {
    header("Location: auth/login.php");
    exit();
}

$sql = "SELECT * FROM categories ORDER BY name ASC";
$result = mysqli_query($conn, $sql);
?>
<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>AES - Aralin Educational Supplies</title>

    <!-- Bootstrap CSS -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet"
        integrity="sha384-QWTKZyjpPEjISv5WaRU9OFeRpok6YctnYmDr5pNlyT2bRjXh0JMhjY6hW+ALEwIH" crossorigin="anonymous">

    <!-- Bootstrap Icons -->
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.min.css">

    <!-- Custom CSS files -->
    <link rel="stylesheet" href="assets/css/navbar.css">
    <link rel="stylesheet" href="assets/css/index.css">
    <link rel="stylesheet" href="assets/css/cart.css">
    <link rel="stylesheet" href="assets/css/checkout.css">
    <link rel="stylesheet" href="assets/css/orders.css">
    <link rel="stylesheet" href="assets/css/product-details.css">
    <link rel="stylesheet" href="assets/css/profile.css">
</head>

<body>

    <!-- ============================================================
     TOP UTILITY BANNER
============================================================ -->
    <div class="nb-top-bar">
        <div class="container">
            <div class="nb-top-bar-inner">
                <div class="nb-top-bar-promo">
                    <span class="nb-top-bar-dot"></span>
                    <strong>Special Offer:</strong>&nbsp;Free shipping on orders over ₱2,000
                </div>
                <div class="nb-top-bar-links">
                    <a href="tel:+639123456789">
                        <i class="bi bi-telephone"></i>
                        +63 912 345 6789
                    </a>
                    <span class="nb-top-bar-sep"></span>
                    <a href="mailto:info@aes.ph">
                        <i class="bi bi-envelope"></i>
                        info@aes.ph
                    </a>
                    <span class="nb-top-bar-sep"></span>
                    <span class="nb-top-bar-hours">
                        <i class="bi bi-clock"></i>
                        Mon – Sat: 8AM – 6PM
                    </span>
                </div>
            </div>
        </div>
    </div>

    <!-- ============================================================
     MAIN NAVBAR
============================================================ -->
    <nav class="nb-nav sticky-top" id="mainNav">
        <div class="container">
            <div class="nb-nav-inner">

                <!-- Brand -->
                <a class="nb-brand" href="index.php">
                    <img src="assets/AES.png" alt="AES Logo" class="nb-brand-logo">
                </a>

                <!-- Search -->
                <div class="nb-search-wrap">
                    <form class="nb-search-form" role="search">
                        <div class="nb-search-box">
                            <i class="bi bi-search nb-search-icon"></i>
                            <input
                                type="search"
                                class="nb-search-input"
                                placeholder="Search for products, brands and more..."
                                aria-label="Search">
                            <button class="nb-search-btn" type="submit">Search</button>
                        </div>
                    </form>
                </div>

                <!-- Actions -->
                <div class="nb-actions">

                    <!-- Notifications -->
                    <a href="#" class="nb-icon-btn" title="Notifications">
                        <i class="bi bi-bell"></i>
                        <span class="nb-badge">3</span>
                    </a>

                    <!-- Cart -->
                    <a href="cart.php" class="nb-icon-btn" title="Shopping Cart">
                        <i class="bi bi-cart3"></i>
                        <span class="nb-badge">12</span>
                    </a>

                    <?php if (isset($_SESSION['user_id'])):
                        // Logic for initials
                        $words = explode(' ', $_SESSION['full_name']);
                        $initials = '';
                        foreach ($words as $w) $initials .= $w[0];
                        $initials = strtoupper(substr($initials, 0, 2));
                    ?>
                        <div class="dropdown">
                            <button class="nb-user-btn dropdown-toggle" type="button" id="userDropdown" data-bs-toggle="dropdown" aria-expanded="false">
                                <div class="nb-user-avatar"><?php echo $initials; ?></div>
                            </button>

                            <ul class="dropdown-menu dropdown-menu-end nb-dropdown" aria-labelledby="userDropdown">
                                <li>
                                    <div class="nb-dropdown-header">
                                        <div class="nb-dropdown-avatar"><?php echo $initials; ?></div>
                                        <div>
                                            <div class="nb-dropdown-name"><?php echo htmlspecialchars($_SESSION['full_name']); ?></div>
                                            <?php if (isset($_SESSION['email'])): ?>
                                                <div class="nb-dropdown-email"><?php echo htmlspecialchars($_SESSION['email']); ?></div>
                                            <?php endif; ?>
                                        </div>
                                    </div>
                                </li>
                                <li>
                                    <div class="nb-dropdown-divider"></div>
                                </li>
                                <li>
                                    <a class="nb-dropdown-item" href="profile.php">
                                        <i class="bi bi-person"></i>
                                        <span>My Account</span>
                                    </a>
                                </li>
                                <li>
                                    <a class="nb-dropdown-item" href="orders.php">
                                        <i class="bi bi-box-seam"></i>
                                        <span>My Orders</span>
                                    </a>
                                </li>
                                <li>
                                    <a class="nb-dropdown-item" href="wishlist.php">
                                        <i class="bi bi-heart"></i>
                                        <span>Wishlist</span>
                                    </a>
                                </li>
                                <li>
                                    <div class="nb-dropdown-divider"></div>
                                </li>
                                <li>
                                    <a class="nb-dropdown-item nb-dropdown-item--danger" href="auth/logout.php">
                                        <i class="bi bi-box-arrow-right"></i>
                                        <span>Sign Out</span>
                                    </a>
                                </li>
                            </ul>
                        </div>

                    <?php else: ?>
                        <a href="auth/login.php" class="btn btn-outline-primary btn-sm rounded-pill px-3">
                            Login
                        </a>
                    <?php endif; ?>

                    <!-- Mobile Toggle -->
                    <button class="nb-mobile-toggle d-lg-none" type="button"
                        data-bs-toggle="collapse" data-bs-target="#mobileNav"
                        aria-controls="mobileNav" aria-expanded="false" aria-label="Toggle menu">
                        <span></span><span></span><span></span>
                    </button>
                </div>
            </div>

            <!-- Mobile Search (collapsed) -->
            <div class="collapse nb-mobile-search" id="mobileNav">
                <form class="nb-search-form nb-search-form--mobile" role="search">
                    <div class="nb-search-box nb-search-box--mobile">
                        <i class="bi bi-search nb-search-icon"></i>
                        <input
                            type="search"
                            class="nb-search-input"
                            placeholder="Search products..."
                            aria-label="Search">
                        <button class="nb-search-btn" type="submit">Search</button>
                    </div>
                </form>
            </div>
        </div>
    </nav>

    <!-- ============================================================
     CATEGORY NAV
============================================================ -->
    <div class="nb-cat-nav d-none d-lg-block">
        <div class="container">
            <nav class="nb-cat-nav-inner">
                <a href="products.php" class="nb-cat-link active">
                    <i class="bi bi-grid-3x3-gap"></i>
                    All Categories
                </a>

                <?php
                if (mysqli_num_rows($result) > 0):
                    while ($row = mysqli_fetch_assoc($result)):
                        $isHot = ($row['is_hot'] == 1);
                        $icon  = !empty($row['icon']) ? $row['icon'] : 'bi-bookmark';
                ?>
                        <a href="products.php?category=<?php echo $row['id']; ?>"
                            class="nb-cat-link <?php echo $isHot ? 'nb-cat-link--hot' : ''; ?>">
                            <i class="bi <?php echo $icon; ?>"></i>
                            <?php echo htmlspecialchars($row['name']); ?>
                            <?php if ($isHot): ?><span class="nb-hot-badge">HOT</span><?php endif; ?>
                        </a>
                <?php
                    endwhile;
                endif;

                mysqli_free_result($result);
                mysqli_close($conn);
                ?>
            </nav>
        </div>
    </div>

    <!-- Navbar scroll script -->
    <script>
        window.addEventListener('scroll', function() {
            const nav = document.getElementById('mainNav');
            nav.classList.toggle('nb-nav--scrolled', window.scrollY > 50);
        });
    </script>