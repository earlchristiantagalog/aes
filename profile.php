<?php
include 'includes/header.php';
include 'db/db.php';

// Session check
$isLoggedIn = isset($_SESSION['user_id']);

if ($isLoggedIn) {
    $user_id = $_SESSION['user_id'];

    // 1. Prepare User Data
    $user = [
        'name'        => $_SESSION['full_name'] ?? 'User',
        'email'       => $_SESSION['email'] ?? '',
        'role'        => $_SESSION['role'] ?? 'Member',
        'school'      => $_SESSION['school'] ?? 'Not Specified',
        'joined'      => $_SESSION['joined_date'] ?? date('Y'),
        'avatar'      => $_SESSION['avatar'] ?? '',
        'wishlist'    => 5,
        'total_spent' => 2480.00,
        'phone'       => $_SESSION['phone'] ?? 'Not Provided', // Added for the Overview tab
        'address'     => $_SESSION['address'] ?? 'No address set' // Added for the Overview tab
    ];
    // Assuming $user_id is already defined (e.g., $user_id = $order['user_id'];)
    $stats_query = "SELECT 
                    COUNT(id) as total_orders, 
                    SUM(total_amount) as total_spent 
                FROM orders 
                WHERE user_id = '$user_id' 
                AND order_status != 'cancelled'"; // Optional: exclude cancelled orders

    $stats_res = mysqli_query($conn, $stats_query);
    $stats = mysqli_fetch_assoc($stats_res);

    // Update your $user array or use $stats directly
    $user['orders'] = $stats['total_orders'] ?? 0;
    $user['total_spent'] = $stats['total_spent'] ?? 0;
    // 2. Fetch Real Addresses using MySQLi
    $savedAddresses = [];
    $sql = "SELECT * FROM user_addresses WHERE user_id = ? ORDER BY is_default DESC, created_at DESC";

    if ($stmt = $conn->prepare($sql)) {
        $stmt->bind_param("i", $user_id);
        $stmt->execute();
        $result = $stmt->get_result();

        while ($row = $result->fetch_assoc()) {
            $savedAddresses[] = $row;
        }
        $stmt->close();
    }
} else {
    header("Location: auth/login.php");
    exit();
}

// --- 1. OVERVIEW FETCH (Limit 3) ---
$recentOrdersQuery = "SELECT o.*, (SELECT COUNT(*) FROM order_items WHERE order_id = o.id) as item_count 
                      FROM orders o WHERE o.user_id = '$user_id' 
                      ORDER BY o.created_at DESC LIMIT 3";
$recentRes = mysqli_query($conn, $recentOrdersQuery);
$recentOrders = [];
while ($row = mysqli_fetch_assoc($recentRes)) {
    $recentOrders[] = [
        'id' => $row['id'],
        'order_number' => $row['order_number'],
        'date' => date('M d, Y', strtotime($row['created_at'])),
        'total' => $row['total_amount'],
        'status' => strtolower($row['order_status']),
        'items' => $row['item_count']
    ];
}

// --- 2. ORDERS PAGE FETCH (All Items + Filter) ---
$statusFilter = $_GET['status'] ?? 'all';
$filterSql = "";
if ($statusFilter !== 'all') {
    $safeStatus = mysqli_real_escape_string($conn, $statusFilter);
    $filterSql = " AND o.order_status = '$safeStatus'";
}

$allOrdersQuery = "SELECT o.*, (SELECT COUNT(*) FROM order_items WHERE order_id = o.id) as item_count 
                   FROM orders o WHERE o.user_id = '$user_id' $filterSql 
                   ORDER BY o.created_at DESC";
$allRes = mysqli_query($conn, $allOrdersQuery);
$allOrders = [];
while ($row = mysqli_fetch_assoc($allRes)) {
    $allOrders[] = [
        'id' => $row['id'],
        'order_number' => $row['order_number'],
        'date' => date('M d, Y', strtotime($row['created_at'])),
        'total' => $row['total_amount'],
        'status' => strtolower($row['order_status']),
        'items' => $row['item_count']
    ];
}

// REMOVED: The hardcoded $savedAddresses = [...] that was here before.

$activeTab = isset($_GET['tab']) ? $_GET['tab'] : 'overview';
?>

<section class="pf-header">
    <div class="pf-header-bg">
        <?php if (isset($_GET['msg'])): ?>
            <div style="padding: 10px; margin-bottom: 15px; border-radius: 4px; background: #e3f2fd; color: #1e3a5f; font-size: 13px;">
                <?php
                if ($_GET['msg'] == 'default_success') echo "Default address updated!";
                if ($_GET['msg'] == 'delete_success') echo "Address removed successfully.";
                if ($_GET['msg'] == 'cannot_delete_default') echo "Cannot delete your default address. Set another one as default first.";
                if ($_GET['msg'] == 'error') echo "Something went wrong. Please try again.";
                ?>
            </div>
        <?php endif; ?>
    </div>
    <div class="pf-container">
    </div>
</section>

<section class="pf-section">
    <div class="pf-container">
        <div class="pf-layout">

            <!-- SIDEBAR -->
            <aside class="pf-sidebar">
                <div class="pf-avatar-card">
                    <div class="pf-avatar-ring">
                        <div class="pf-avatar">
                            <?php
                            // Logic for Avatar Image vs Initials
                            if (!empty($user['avatar'])) {
                                echo '<img src="' . htmlspecialchars($user['avatar']) . '" alt="Avatar" style="width:100%; height:100%; object-fit:cover; border-radius:50%;">';
                            } else {
                                $displayName = $user['full_name'] ?? $_SESSION['full_name'] ?? 'User';
                                $parts = explode(' ', $displayName);
                                $initials = strtoupper($parts[0][0] . (isset($parts[1][0]) ? $parts[1][0] : ''));
                                echo htmlspecialchars($initials);
                            }
                            ?>
                        </div>
                    </div>

                    <h3 class="pf-avatar-name">
                        <?php echo htmlspecialchars($user['name'] ?? 'Guest'); ?>
                    </h3>

                    <span class="pf-avatar-role text-uppercase">
                        <?php echo htmlspecialchars($user['role'] ?? 'Member'); ?>
                    </span>

                    <span class="pf-avatar-school">
                        <svg width="12" height="12" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                            <path d="M3 9l9-7 9 7v11a2 2 0 01-2 2H5a2 2 0 01-2-2z" />
                            <polyline points="9 22 9 12 15 12 15 22" />
                        </svg>
                        <?php echo htmlspecialchars($user['school'] ?? 'No School Linked'); ?>
                    </span>

                    <div class="pf-avatar-since">
                        Joined in <?php echo htmlspecialchars($user['joined'] ?? '-'); ?>
                    </div>
                </div>
                <div class="pf-stat-cards">
                    <div class="pf-stat-card">
                        <span class="pf-stat-num">
                            <?php echo number_format($user['orders']); ?>
                        </span>
                        <span class="pf-stat-lbl">Orders</span>
                    </div>
                    <div class="pf-stat-card">
                        <span class="pf-stat-num"><?php echo $user['wishlist']; ?></span>
                        <span class="pf-stat-lbl">Wishlist</span>
                    </div>
                    <div class="pf-stat-card pf-stat-card--wide">
                        <span class="pf-stat-num">
                            &#8369;<?php echo number_format($user['total_spent'], 2); ?>
                        </span>
                        <span class="pf-stat-lbl">Total Spent</span>
                    </div>
                </div>

                <nav class="pf-nav">
                    <a href="?tab=overview" class="pf-nav-item <?php echo $activeTab === 'overview' ? 'active' : ''; ?>">
                        <svg width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.75">
                            <rect x="3" y="3" width="7" height="7" />
                            <rect x="14" y="3" width="7" height="7" />
                            <rect x="3" y="14" width="7" height="7" />
                            <rect x="14" y="14" width="7" height="7" />
                        </svg>
                        Overview
                    </a>
                    <a href="?tab=orders" class="pf-nav-item <?php echo $activeTab === 'orders' ? 'active' : ''; ?>">
                        <svg width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.75">
                            <path d="M6 2L3 6v14a2 2 0 002 2h14a2 2 0 002-2V6l-3-4z" />
                            <line x1="3" y1="6" x2="21" y2="6" />
                            <path d="M16 10a4 4 0 01-8 0" />
                        </svg>
                        My Orders
                        <span class="pf-nav-badge"><?php echo $user['orders']; ?></span>
                    </a>
                    <a href="?tab=wishlist" class="pf-nav-item <?php echo $activeTab === 'wishlist' ? 'active' : ''; ?>">
                        <svg width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.75">
                            <path d="M20.84 4.61a5.5 5.5 0 00-7.78 0L12 5.67l-1.06-1.06a5.5 5.5 0 00-7.78 7.78l1.06 1.06L12 21.23l7.78-7.78 1.06-1.06a5.5 5.5 0 000-7.78z" />
                        </svg>
                        Wishlist
                        <span class="pf-nav-badge"><?php echo $user['wishlist']; ?></span>
                    </a>
                    <a href="?tab=addresses" class="pf-nav-item <?php echo $activeTab === 'addresses' ? 'active' : ''; ?>">
                        <svg width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.75">
                            <path d="M21 10c0 7-9 13-9 13S3 17 3 10a9 9 0 0118 0z" />
                            <circle cx="12" cy="10" r="3" />
                        </svg>
                        My Addresses
                        <span class="pf-nav-badge"><?php echo count($savedAddresses); ?></span>
                    </a>
                    <a href="?tab=settings" class="pf-nav-item <?php echo $activeTab === 'settings' ? 'active' : ''; ?>">
                        <svg width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.75">
                            <circle cx="12" cy="12" r="3" />
                            <path d="M19.4 15a1.65 1.65 0 00.33 1.82l.06.06a2 2 0 010 2.83 2 2 0 01-2.83 0l-.06-.06a1.65 1.65 0 00-1.82-.33 1.65 1.65 0 00-1 1.51V21a2 2 0 01-4 0v-.09A1.65 1.65 0 009 19.4a1.65 1.65 0 00-1.82.33l-.06.06a2 2 0 01-2.83-2.83l.06-.06A1.65 1.65 0 004.68 15a1.65 1.65 0 00-1.51-1H3a2 2 0 010-4h.09A1.65 1.65 0 004.6 9a1.65 1.65 0 00-.33-1.82l-.06-.06a2 2 0 012.83-2.83l.06.06A1.65 1.65 0 009 4.68a1.65 1.65 0 001-1.51V3a2 2 0 014 0v.09a1.65 1.65 0 001 1.51 1.65 1.65 0 001.82-.33l.06-.06a2 2 0 012.83 2.83l-.06.06A1.65 1.65 0 0019.4 9a1.65 1.65 0 001.51 1H21a2 2 0 010 4h-.09a1.65 1.65 0 00-1.51 1z" />
                        </svg>
                        Account Settings
                    </a>
                    <div class="pf-nav-divider"></div>
                    <a href="logout.php" class="pf-nav-item pf-nav-logout">
                        <svg width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.75">
                            <path d="M9 21H5a2 2 0 01-2-2V5a2 2 0 012-2h4" />
                            <polyline points="16 17 21 12 16 7" />
                            <line x1="21" y1="12" x2="9" y2="12" />
                        </svg>
                        Sign Out
                    </a>
                </nav>
            </aside>

            <!-- MAIN CONTENT -->
            <main class="pf-main">

                <?php if ($activeTab === 'overview'): ?>
                    <div class="pf-panel">
                        <div class="pf-panel-header">
                            <h2 class="pf-panel-title">Overview</h2>
                            <span class="pf-panel-sub">Welcome back, <?php echo explode(' ', $user['name'])[0]; ?>!</span>
                        </div>

                        <div class="pf-overview-stats">
                            <div class="pf-ov-stat">
                                <div class="pf-ov-icon pf-ov-icon--blue">
                                    <svg width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.75">
                                        <path d="M6 2L3 6v14a2 2 0 002 2h14a2 2 0 002-2V6l-3-4z" />
                                        <line x1="3" y1="6" x2="21" y2="6" />
                                        <path d="M16 10a4 4 0 01-8 0" />
                                    </svg>
                                </div>
                                <div>
                                    <div class="pf-ov-num"><?php echo $user['orders']; ?></div>
                                    <div class="pf-ov-lbl">Total Orders</div>
                                </div>
                            </div>
                            <div class="pf-ov-stat">
                                <div class="pf-ov-icon pf-ov-icon--sky">
                                    <svg width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.75">
                                        <path d="M20.84 4.61a5.5 5.5 0 00-7.78 0L12 5.67l-1.06-1.06a5.5 5.5 0 00-7.78 7.78l1.06 1.06L12 21.23l7.78-7.78 1.06-1.06a5.5 5.5 0 000-7.78z" />
                                    </svg>
                                </div>
                                <div>
                                    <div class="pf-ov-num"><?php echo $user['wishlist']; ?></div>
                                    <div class="pf-ov-lbl">Wishlist Items</div>
                                </div>
                            </div>
                            <div class="pf-ov-stat">
                                <div class="pf-ov-icon pf-ov-icon--gold">
                                    <svg width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.75">
                                        <line x1="12" y1="1" x2="12" y2="23" />
                                        <path d="M17 5H9.5a3.5 3.5 0 100 7h5a3.5 3.5 0 110 7H6" />
                                    </svg>
                                </div>
                                <div>
                                    <div class="pf-ov-num">&#8369;<?php echo number_format($user['total_spent'], 2); ?></div>
                                    <div class="pf-ov-lbl">Total Spent</div>
                                </div>
                            </div>
                            <div class="pf-ov-stat">
                                <div class="pf-ov-icon pf-ov-icon--green">
                                    <svg width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.75">
                                        <polyline points="20 6 9 17 4 12" />
                                    </svg>
                                </div>
                                <div>
                                    <div class="pf-ov-num"><?php echo count(array_filter($recentOrders, fn($o) => $o['status'] === 'delivered')); ?></div>
                                    <div class="pf-ov-lbl">Delivered</div>
                                </div>
                            </div>
                        </div>

                        <div class="pf-section-block">
                            <div class="pf-block-header">
                                <h3 class="pf-block-title">Recent Orders</h3>
                                <a href="?tab=orders" class="pf-block-link">View all &rarr;</a>
                            </div>
                            <div class="pf-order-list">
                                <?php foreach (array_slice($recentOrders, 0, 3) as $order): ?>
                                    <div class="pf-order-row">
                                        <div class="pf-order-id">
                                            <span class="pf-order-num"><?php echo $order['order_number']; ?></span>
                                            <span class="pf-order-date"><?php echo $order['date']; ?></span>
                                        </div>
                                        <div class="pf-order-meta">
                                            <span class="pf-order-items"><?php echo $order['items']; ?> item<?php echo $order['items'] > 1 ? 's' : ''; ?></span>
                                        </div>
                                        <div class="pf-order-total">&#8369;<?php echo number_format($order['total'], 2); ?></div>
                                        <span class="pf-status pf-status--<?php echo $order['status']; ?>"><?php echo ucfirst($order['status']); ?></span>
                                        <a href="order-detail.php?id=<?php echo $order['id']; ?>" class="pf-order-view">View</a>
                                    </div>
                                <?php endforeach; ?>
                            </div>
                        </div>

                        <div class="pf-section-block">
                            <div class="pf-block-header">
                                <h3 class="pf-block-title">Account Information</h3>
                                <a href="?tab=settings" class="pf-block-link">Edit &rarr;</a>
                            </div>
                            <div class="pf-info-grid">
                                <div class="pf-info-item">
                                    <span class="pf-info-label">Full Name</span>
                                    <span class="pf-info-value"><?php echo htmlspecialchars($user['name']); ?></span>
                                </div>
                                <div class="pf-info-item">
                                    <span class="pf-info-label">Email</span>
                                    <span class="pf-info-value"><?php echo htmlspecialchars($user['email']); ?></span>
                                </div>
                                <div class="pf-info-item">
                                    <span class="pf-info-label">Phone</span>
                                    <span class="pf-info-value"><?php echo htmlspecialchars($user['phone']); ?></span>
                                </div>
                                <div class="pf-info-item">
                                    <span class="pf-info-label">School / Institution</span>
                                    <span class="pf-info-value"><?php echo htmlspecialchars($user['school']); ?></span>
                                </div>
                                <div class="pf-info-item pf-info-item--full">
                                    <span class="pf-info-label">Delivery Address</span>
                                    <span class="pf-info-value"><?php echo htmlspecialchars($user['address']); ?></span>
                                </div>
                            </div>
                        </div>
                    </div>

                <?php elseif ($activeTab === 'orders'): ?>
                    <div class="pf-panel">
                        <div class="pf-panel-header">
                            <h2 class="pf-panel-title">My Orders</h2>
                            <span class="pf-panel-sub"><?php echo count($allOrders); ?> orders total</span>
                        </div>

                        <div class="pf-filter-bar">
                            <a href="?tab=orders&status=all"
                                class="pf-filter-btn <?php echo $statusFilter === 'all' ? 'active' : ''; ?>"
                                style="text-decoration: none;">All</a>

                            <a href="?tab=orders&status=delivered"
                                class="pf-filter-btn <?php echo $statusFilter === 'delivered' ? 'active' : ''; ?>"
                                style="text-decoration: none;">Delivered</a>

                            <a href="?tab=orders&status=processing"
                                class="pf-filter-btn <?php echo $statusFilter === 'processing' ? 'active' : ''; ?>"
                                style="text-decoration: none;">Processing</a>

                            <a href="?tab=orders&status=cancelled"
                                class="pf-filter-btn <?php echo $statusFilter === 'cancelled' ? 'active' : ''; ?>"
                                style="text-decoration: none;">Cancelled</a>
                        </div>

                        <div class="pf-order-list pf-order-list--full">
                            <?php if (!empty($allOrders)): ?>
                                <?php foreach ($allOrders as $order): ?>
                                    <div class="pf-order-row pf-order-row--card">
                                        <div class="pf-order-icon">
                                            <svg width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.5">
                                                <path d="M6 2L3 6v14a2 2 0 002 2h14a2 2 0 002-2V6l-3-4z" />
                                                <line x1="3" y1="6" x2="21" y2="6" />
                                                <path d="M16 10a4 4 0 01-8 0" />
                                            </svg>
                                        </div>
                                        <div class="pf-order-id">
                                            <span class="pf-order-num">#<?php echo $order['order_number']; ?></span>
                                            <span class="pf-order-date"><?php echo $order['date']; ?></span>
                                        </div>
                                        <div class="pf-order-meta">
                                            <span class="pf-order-items"><?php echo $order['items']; ?> item<?php echo $order['items'] > 1 ? 's' : ''; ?></span>
                                        </div>
                                        <div class="pf-order-total">&#8369;<?php echo number_format($order['total'], 2); ?></div>
                                        <span class="pf-status pf-status--<?php echo $order['status']; ?>"><?php echo ucfirst($order['status']); ?></span>
                                        <div class="pf-order-actions">
                                            <a href="order-detail.php?id=<?php echo $order['id']; ?>" class="pf-btn-sm pf-btn-sm--primary">View Details</a>
                                            <button class="pf-btn-sm pf-btn-sm--ghost">Reorder</button>
                                        </div>
                                    </div>
                                <?php endforeach; ?>
                            <?php else: ?>
                                <div class="text-center p-5">
                                    <p class="text-muted">No orders found for this category.</p>
                                </div>
                            <?php endif; ?>
                        </div>
                    </div>
                <?php elseif ($activeTab === 'wishlist'): ?>
                    <div class="pf-panel">
                        <div class="pf-panel-header">
                            <h2 class="pf-panel-title">My Wishlist</h2>
                            <span class="pf-panel-sub"><?php echo $user['wishlist']; ?> saved items</span>
                        </div>
                        <div class="pf-wishlist-grid">
                            <?php
                            $wishlistItems = [
                                ['name' => 'Premium Pen Set',       'price' => 199,  'category' => 'Stationery',  'img' => 'https://via.placeholder.com/200x200/edf2f7/1e3a5f?text=Pens'],
                                ['name' => 'Geometry Tool Set',     'price' => 449,  'category' => 'Mathematics', 'img' => 'https://via.placeholder.com/200x200/edf2f7/1e3a5f?text=Geometry'],
                                ['name' => 'Highlighter Set',       'price' => 149,  'category' => 'Stationery',  'img' => 'https://via.placeholder.com/200x200/edf2f7/1e3a5f?text=Highlighters'],
                                ['name' => 'Sticky Notes Pack',     'price' => 99,   'category' => 'Office',      'img' => 'https://via.placeholder.com/200x200/edf2f7/1e3a5f?text=Notes'],
                                ['name' => 'Student Notebook Set',  'price' => 350,  'category' => 'Stationery',  'img' => 'https://via.placeholder.com/200x200/edf2f7/1e3a5f?text=Notebooks'],
                            ];
                            foreach ($wishlistItems as $item): ?>
                                <div class="pf-wish-card">
                                    <button class="pf-wish-remove" title="Remove">
                                        <svg width="14" height="14" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                                            <line x1="18" y1="6" x2="6" y2="18" />
                                            <line x1="6" y1="6" x2="18" y2="18" />
                                        </svg>
                                    </button>
                                    <div class="pf-wish-img">
                                        <img src="<?php echo $item['img']; ?>" alt="<?php echo $item['name']; ?>">
                                    </div>
                                    <div class="pf-wish-body">
                                        <span class="pf-wish-cat"><?php echo $item['category']; ?></span>
                                        <h5 class="pf-wish-name"><?php echo $item['name']; ?></h5>
                                        <div class="pf-wish-footer">
                                            <span class="pf-wish-price">&#8369;<?php echo number_format($item['price'], 2); ?></span>
                                            <button class="pf-wish-add">
                                                <svg width="14" height="14" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                                                    <path d="M6 2L3 6v14a2 2 0 002 2h14a2 2 0 002-2V6l-3-4z" />
                                                    <line x1="3" y1="6" x2="21" y2="6" />
                                                    <path d="M16 10a4 4 0 01-8 0" />
                                                </svg>
                                                Add to Cart
                                            </button>
                                        </div>
                                    </div>
                                </div>
                            <?php endforeach; ?>
                        </div>
                    </div>

                <?php elseif ($activeTab === 'addresses'): ?>
                    <div class="pf-panel">
                        <div class="pf-panel-header pf-panel-header--flex">
                            <div>
                                <h2 class="pf-panel-title">My Addresses</h2>
                                <span class="pf-panel-sub"><?php echo count($savedAddresses); ?> saved address<?php echo count($savedAddresses) !== 1 ? 'es' : ''; ?></span>
                            </div>
                            <button class="pf-btn-add-address" id="openAddressModal">
                                <svg width="15" height="15" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.25">
                                    <line x1="12" y1="5" x2="12" y2="19" />
                                    <line x1="5" y1="12" x2="19" y2="12" />
                                </svg>
                                Add New Address
                            </button>
                        </div>

                        <div class="pf-address-grid">
                            <?php foreach ($savedAddresses as $addr):
                                // Detect if this is the default address (checks both common naming styles)
                                $isDefault = ($addr['is_default'] ?? $addr['default'] ?? 0);
                            ?>
                                <div class="pf-addr-card <?php echo $isDefault ? 'pf-addr-card--default' : ''; ?>">
                                    <?php if ($isDefault): ?>
                                        <span class="pf-addr-default-badge">
                                            <svg width="10" height="10" viewBox="0 0 24 24" fill="currentColor">
                                                <polyline points="20 6 9 17 4 12" stroke="currentColor" stroke-width="3" fill="none" />
                                            </svg>
                                            Default
                                        </span>
                                    <?php endif; ?>

                                    <div class="pf-addr-label-row">
                                        <div class="pf-addr-icon">
                                            <i class="bi <?php echo (strtolower($addr['label'] ?? '') === 'school') ? 'bi-building' : 'bi-geo-alt'; ?>"></i>
                                        </div>
                                        <span class="pf-addr-label"><?php echo htmlspecialchars($addr['label'] ?? 'Address'); ?></span>
                                    </div>

                                    <div class="pf-addr-body">
                                        <p class="pf-addr-name">
                                            <?php echo htmlspecialchars($addr['first_name'] . ' ' . ($addr['last_name'] ?? '') ?? $addr['full_name'] ?? 'Recipient'); ?>
                                        </p>

                                        <p class="pf-addr-line"><?php echo htmlspecialchars($addr['address_line'] ?? 'No Address Provided'); ?></p>

                                        <p class="pf-addr-line">
                                            <?php
                                            $city = $addr['city'] ?? '';
                                            $barangay = $addr['barangay'] ?? '';
                                            $prov = $addr['province'] ?? '';
                                            $zip  = $addr['zip_code'] ?? '';
                                            echo htmlspecialchars(trim("$barangay, $city, $prov $zip", ", "));
                                            ?>
                                        </p>

                                        <p class="pf-addr-phone">
                                            <i class="bi bi-telephone" style="font-size: 11px; margin-right: 4px;"></i>
                                            <?php echo htmlspecialchars($addr['phone_number'] ?? $addr['phone'] ?? 'No Phone'); ?>
                                        </p>
                                    </div>

                                    <div class="pf-addr-actions">
                                        <button class="pf-addr-btn pf-addr-btn--edit" onclick="openEditAddress(<?php echo $addr['id']; ?>)">
                                            Edit
                                        </button>
                                        <?php if (!$isDefault): ?>
                                            <button class="pf-addr-btn pf-addr-btn--default" onclick="location.href='address/set_default.php?id=<?php echo $addr['id']; ?>'">
                                                Set Default
                                            </button>
                                            <button class="pf-addr-btn pf-addr-btn--delete" onclick="if(confirm('Delete?')) location.href='address/delete_address.php?id=<?php echo $addr['id']; ?>'">
                                                Remove
                                            </button>
                                        <?php endif; ?>
                                    </div>
                                </div>
                            <?php endforeach; ?>
                            <!-- Add New Address card (shortcut) -->
                            <div class="pf-addr-card pf-addr-card--new" id="addNewShortcut">
                                <div class="pf-addr-new-inner">
                                    <div class="pf-addr-new-icon">
                                        <svg width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.75">
                                            <line x1="12" y1="5" x2="12" y2="19" />
                                            <line x1="5" y1="12" x2="19" y2="12" />
                                        </svg>
                                    </div>
                                    <span class="pf-addr-new-lbl">Add New Address</span>
                                </div>
                            </div>
                        </div>
                    </div>
                <?php elseif ($activeTab === 'settings'): ?>
                    <div class="pf-panel">
                        <div class="pf-panel-header">
                            <h2 class="pf-panel-title">Account Settings</h2>
                            <span class="pf-panel-sub">Manage your personal information</span>
                        </div>

                        <div class="pf-settings-avatar">
                            <div class="pf-avatar pf-avatar--lg">MS</div>
                            <div>
                                <button class="pf-btn-sm pf-btn-sm--primary">Change Photo</button>
                                <p class="pf-settings-avatar-note">JPG or PNG, max 2MB</p>
                            </div>
                        </div>

                        <form class="pf-settings-form" onsubmit="return false;">
                            <div class="pf-form-section">
                                <h4 class="pf-form-section-title">Personal Information</h4>
                                <div class="pf-form-grid">
                                    <div class="pf-field">
                                        <label class="pf-label">First Name</label>
                                        <input type="text" class="pf-input" value="Maria">
                                    </div>
                                    <div class="pf-field">
                                        <label class="pf-label">Last Name</label>
                                        <input type="text" class="pf-input" value="Santos">
                                    </div>
                                    <div class="pf-field">
                                        <label class="pf-label">Email Address</label>
                                        <input type="email" class="pf-input" value="maria.santos@school.edu">
                                    </div>
                                    <div class="pf-field">
                                        <label class="pf-label">Phone Number</label>
                                        <input type="tel" class="pf-input" value="+63 912 345 6789">
                                    </div>
                                    <div class="pf-field pf-field--full">
                                        <label class="pf-label">School / Institution</label>
                                        <input type="text" class="pf-input" value="Philippine National Academy">
                                    </div>
                                    <div class="pf-field pf-field--full">
                                        <label class="pf-label">Role / Position</label>
                                        <input type="text" class="pf-input" value="School Coordinator">
                                    </div>
                                    <div class="pf-field pf-field--full">
                                        <label class="pf-label">Delivery Address</label>
                                        <textarea class="pf-input pf-textarea" rows="2">123 Rizal Street, Quezon City, Metro Manila</textarea>
                                    </div>
                                </div>
                            </div>

                            <div class="pf-form-section">
                                <h4 class="pf-form-section-title">Change Password</h4>
                                <div class="pf-form-grid">
                                    <div class="pf-field pf-field--full">
                                        <label class="pf-label">Current Password</label>
                                        <input type="password" class="pf-input" placeholder="Enter current password">
                                    </div>
                                    <div class="pf-field">
                                        <label class="pf-label">New Password</label>
                                        <input type="password" class="pf-input" placeholder="New password">
                                    </div>
                                    <div class="pf-field">
                                        <label class="pf-label">Confirm New Password</label>
                                        <input type="password" class="pf-input" placeholder="Confirm password">
                                    </div>
                                </div>
                            </div>

                            <div class="pf-form-section">
                                <h4 class="pf-form-section-title">Notifications</h4>
                                <div class="pf-toggles">
                                    <div class="pf-toggle-row">
                                        <div>
                                            <strong class="pf-toggle-lbl">Order Updates</strong>
                                            <p class="pf-toggle-sub">Receive emails about your order status</p>
                                        </div>
                                        <label class="pf-toggle">
                                            <input type="checkbox" checked>
                                            <span class="pf-toggle-track"></span>
                                        </label>
                                    </div>
                                    <div class="pf-toggle-row">
                                        <div>
                                            <strong class="pf-toggle-lbl">Promotions &amp; Offers</strong>
                                            <p class="pf-toggle-sub">Exclusive deals and new product alerts</p>
                                        </div>
                                        <label class="pf-toggle">
                                            <input type="checkbox" checked>
                                            <span class="pf-toggle-track"></span>
                                        </label>
                                    </div>
                                    <div class="pf-toggle-row">
                                        <div>
                                            <strong class="pf-toggle-lbl">Newsletter</strong>
                                            <p class="pf-toggle-sub">Monthly educational resources digest</p>
                                        </div>
                                        <label class="pf-toggle">
                                            <input type="checkbox">
                                            <span class="pf-toggle-track"></span>
                                        </label>
                                    </div>
                                </div>
                            </div>

                            <div class="pf-form-footer">
                                <button type="submit" class="pf-btn-save">Save Changes</button>
                                <button type="reset" class="pf-btn-cancel">Discard</button>
                            </div>
                        </form>
                    </div>
                <?php endif; ?>

            </main>
        </div>
    </div>
</section>
<!-- ===== ADDRESS MODAL ===== -->
<div class="pf-modal-backdrop" id="addressModalBackdrop">
    <div class="pf-modal" id="addressModal" role="dialog" aria-modal="true" aria-labelledby="modalTitle">
        <div class="pf-modal-header">
            <h3 class="pf-modal-title" id="modalTitle">Add New Address</h3>
            <button class="pf-modal-close" id="closeAddressModal" aria-label="Close">
                <svg width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                    <line x1="18" y1="6" x2="6" y2="18" />
                    <line x1="6" y1="6" x2="18" y2="18" />
                </svg>
            </button>
        </div>
        <div class="pf-modal-body">
            <form class="pf-settings-form pf-modal-form" action="address/save_address.php" method="POST" id="addressForm" onsubmit="return false;">
                <div class="pf-form-grid">
                    <div class="pf-field pf-field--full">
                        <label class="pf-label">Address Label</label>
                        <div class="pf-label-chips">
                            <button type="button" class="pf-label-chip active" data-label="Home">🏠 Home</button>
                            <button type="button" class="pf-label-chip" data-label="School">🏫 School</button>
                            <button type="button" class="pf-label-chip" data-label="Office">🏢 Office</button>
                            <button type="button" class="pf-label-chip" data-label="Other">📍 Other</button>
                        </div>
                        <input type="hidden" id="addressLabel" name="label" value="Home">
                    </div>

                    <div class="pf-field">
                        <label class="pf-label" for="addrFirstName">First Name</label>
                        <input type="text" class="pf-input" id="addrFirstName" name="first_name" placeholder="Maria">
                    </div>
                    <div class="pf-field">
                        <label class="pf-label" for="addrLastName">Last Name</label>
                        <input type="text" class="pf-input" id="addrLastName" name="last_name" placeholder="Santos">
                    </div>

                    <div class="pf-field pf-field--full">
                        <label class="pf-label" for="addrLine1">Street / Building / Unit</label>
                        <input type="text" class="pf-input" id="addrLine1" name="line1" placeholder="e.g. 123 Rizal Street, Unit 4B">
                    </div>

                    <div class="pf-field">
                        <label class="pf-label">Province</label>
                        <div class="pf-custom-select-container" id="containerProvince">
                            <div class="pf-select-trigger" id="triggerProvince">Select Province</div>
                            <div class="pf-select-dropdown" id="dropdownProvince"></div>
                            <input type="hidden" name="province" id="inputProvince">
                        </div>
                    </div>

                    <div class="pf-field">
                        <label class="pf-label">City / Municipality</label>
                        <div class="pf-custom-select-container" id="containerCity">
                            <div class="pf-select-trigger disabled" id="triggerCity">Select City</div>
                            <div class="pf-select-dropdown" id="dropdownCity"></div>
                            <input type="hidden" name="city" id="inputCity">
                        </div>
                    </div>

                    <div class="pf-field">
                        <label class="pf-label">Barangay</label>
                        <div class="pf-custom-select-container" id="containerBarangay">
                            <div class="pf-select-trigger disabled" id="triggerBarangay">Select Barangay</div>
                            <div class="pf-select-dropdown" id="dropdownBarangay"></div>
                            <input type="hidden" name="barangay" id="inputBarangay">
                        </div>
                    </div>
                    <div class="pf-field">
                        <label class="pf-label" for="addrZip">ZIP Code</label>
                        <input type="text" class="pf-input" id="addrZip" name="zip" placeholder="e.g. 1100" maxlength="10">
                    </div>

                    <div class="pf-field pf-field--full">
                        <label class="pf-label" for="addrPhone">Contact Number</label>
                        <input type="tel" class="pf-input" id="addrPhone" name="phone" placeholder="+63 9XX XXX XXXX">
                    </div>

                    <div class="pf-field pf-field--full">
                        <label class="pf-toggle-row pf-toggle-row--inline">
                            <div>
                                <strong class="pf-toggle-lbl">Set as Default Address</strong>
                                <p class="pf-toggle-sub">Use this address as the primary delivery address</p>
                            </div>
                            <label class="pf-toggle">
                                <input type="checkbox" id="addrDefault" name="is_default">
                                <span class="pf-toggle-track"></span>
                            </label>
                        </label>
                    </div>
                </div>

                <div class="pf-form-footer pf-modal-footer">
                    <button type="submit" name="save_address" class="pf-btn-save" id="saveAddrBtn">Save Address</button>
                    <button type="button" class="pf-btn-cancel" id="cancelAddressBtn">Cancel</button>
                </div>
            </form>
        </div>
    </div>
</div>
<?php include 'includes/footer.php'; ?>

<script>
    document.addEventListener('DOMContentLoaded', () => {
        const apiBase = 'https://psgc.gitlab.io/api';

        // Helper to setup custom selects
        function setupCustomSelect(type, nextType = null, apiPath = '') {
            const container = document.getElementById(`container${type}`);
            const trigger = document.getElementById(`trigger${type}`);
            const dropdown = document.getElementById(`dropdown${type}`);
            const hiddenInput = document.getElementById(`input${type}`);

            trigger.addEventListener('click', () => {
                if (trigger.classList.contains('disabled')) return;
                // Close other dropdowns
                document.querySelectorAll('.pf-custom-select-container').forEach(c => {
                    if (c !== container) c.classList.remove('is-active');
                });
                container.classList.toggle('is-active');
            });

            return function updateOptions(url) {
                dropdown.innerHTML = '<div class="pf-select-option">Loading...</div>';
                fetch(url).then(res => res.json()).then(data => {
                    dropdown.innerHTML = '';
                    data.sort((a, b) => a.name.localeCompare(b.name)).forEach(item => {
                        const div = document.createElement('div');
                        div.className = 'pf-select-option';
                        div.textContent = item.name;
                        div.onclick = () => {
                            trigger.textContent = item.name;
                            hiddenInput.value = item.name;
                            container.classList.remove('is-active');

                            if (nextType) {
                                const nextTrigger = document.getElementById(`trigger${nextType}`);
                                nextTrigger.classList.remove('disabled');
                                nextTrigger.textContent = `Select ${nextType}`;

                                // Determine next API URL based on selection
                                let nextUrl = nextType === 'City' ?
                                    `${apiBase}/provinces/${item.code}/cities-municipalities.json` :
                                    `${apiBase}/cities-municipalities/${item.code}/barangays.json`;

                                window[`load${nextType}`](nextUrl);
                            }
                        };
                        dropdown.appendChild(div);
                    });
                });
            };
        }

        // Initialize the flow
        window.loadBarangay = setupCustomSelect('Barangay');
        window.loadCity = setupCustomSelect('City', 'Barangay');
        const loadProvince = setupCustomSelect('Province', 'City');

        // Initial load for Provinces
        loadProvince(`${apiBase}/provinces.json`);

        // Close dropdowns when clicking outside
        document.addEventListener('click', (e) => {
            if (!e.target.closest('.pf-custom-select-container')) {
                document.querySelectorAll('.pf-custom-select-container').forEach(c => c.classList.remove('is-active'));
            }
        });
    });
    document.querySelectorAll('.pf-filter-btn').forEach(btn => {
        btn.addEventListener('click', () => {
            document.querySelectorAll('.pf-filter-btn').forEach(b => b.classList.remove('active'));
            btn.classList.add('active');
        });
    });

    document.querySelectorAll('.pf-wish-remove').forEach(btn => {
        btn.addEventListener('click', () => {
            const card = btn.closest('.pf-wish-card');
            card.style.transition = 'opacity 0.22s ease, transform 0.22s ease';
            card.style.opacity = '0';
            card.style.transform = 'scale(0.9)';
            setTimeout(() => card.remove(), 230);
        });
    });

    const saveBtn = document.querySelector('.pf-btn-save');
    if (saveBtn) {
        saveBtn.addEventListener('click', () => {
            const orig = saveBtn.textContent;
            saveBtn.textContent = '✓ Saved!';
            saveBtn.style.background = '#27ae60';
            setTimeout(() => {
                saveBtn.textContent = orig;
                saveBtn.style.background = '';
            }, 2000);
        });
    }

    // ===== ADDRESS MODAL =====
    const backdrop = document.getElementById('addressModalBackdrop');
    const openBtn = document.getElementById('openAddressModal');
    const closeBtn = document.getElementById('closeAddressModal');
    const cancelBtn = document.getElementById('cancelAddressBtn');
    const newShortcut = document.getElementById('addNewShortcut');
    const modalTitle = document.getElementById('modalTitle');
    const saveAddrBtn = document.getElementById('saveAddrBtn');

    function openModal(editId) {
        if (editId) {
            modalTitle.textContent = 'Edit Address';
            saveAddrBtn.textContent = 'Update Address';
        } else {
            modalTitle.textContent = 'Add New Address';
            saveAddrBtn.textContent = 'Save Address';
        }
        backdrop.classList.add('is-open');
        document.body.style.overflow = 'hidden';
    }

    function closeModal() {
        backdrop.classList.remove('is-open');
        document.body.style.overflow = '';
    }

    if (openBtn) openBtn.addEventListener('click', () => openModal(null));
    if (newShortcut) newShortcut.addEventListener('click', () => openModal(null));
    if (closeBtn) closeBtn.addEventListener('click', closeModal);
    if (cancelBtn) cancelBtn.addEventListener('click', closeModal);

    if (backdrop) {
        backdrop.addEventListener('click', (e) => {
            if (e.target === backdrop) closeModal();
        });
    }

    document.addEventListener('keydown', (e) => {
        if (e.key === 'Escape' && backdrop && backdrop.classList.contains('is-open')) closeModal();
    });

    function openEditAddress(id) {
        openModal(id);
    }

    function setDefaultAddress(id) {
        const cards = document.querySelectorAll('.pf-addr-card');
        cards.forEach(c => {
            c.classList.remove('pf-addr-card--default');
            const badge = c.querySelector('.pf-addr-default-badge');
            if (badge) badge.remove();
        });
        // In a real app, send AJAX to server here
    }

    function deleteAddress(id) {
        if (!confirm('Remove this address?')) return;
        const btn = event.currentTarget;
        const card = btn.closest('.pf-addr-card');
        card.style.transition = 'opacity 0.22s ease, transform 0.22s ease';
        card.style.opacity = '0';
        card.style.transform = 'scale(0.95)';
        setTimeout(() => card.remove(), 230);
    }

    // Label chip toggle
    document.querySelectorAll('.pf-label-chip').forEach(chip => {
        chip.addEventListener('click', () => {
            document.querySelectorAll('.pf-label-chip').forEach(c => c.classList.remove('active'));
            chip.classList.add('active');
            const hiddenInput = document.getElementById('addressLabel');
            if (hiddenInput) hiddenInput.value = chip.dataset.label;
        });
    });

    // Save address feedback
    // Replace your "Save address feedback" section with this:
    if (saveAddrBtn) {
        saveAddrBtn.addEventListener('click', function(e) {
            // 1. Find the form
            const form = document.getElementById('addressForm');

            // 2. Validate required fields (First Name, Last Name, Line 1, etc.)
            // We check if the hidden inputs (Province/City) are filled
            const province = document.getElementById('inputProvince').value;
            const city = document.getElementById('inputCity').value;

            if (!form.checkValidity() || !province || !city) {
                if (!province || !city) {
                    alert("Please select a Province and City/Municipality.");
                } else {
                    form.reportValidity();
                }
                return;
            }

            // 3. UI Feedback
            const origText = saveAddrBtn.textContent;
            saveAddrBtn.textContent = 'Saving...';
            saveAddrBtn.style.background = '#27ae60';
            saveAddrBtn.style.pointerEvents = 'none'; // Prevent double-clicks

            // 4. Submit the form to save_address.php
            form.submit();
        });
    }
</script>