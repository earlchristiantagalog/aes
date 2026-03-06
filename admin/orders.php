<?php
$conn = mysqli_connect('localhost', 'root', '', 'aes');

// --- 1. CONFIGURATION & FILTERS ---
$limit = 10; // Orders per page
$page = isset($_GET['page']) ? max(1, (int)$_GET['page']) : 1;
$offset = ($page - 1) * $limit;

$status = $_GET['status'] ?? '';
$search = $_GET['search'] ?? '';
$payment = $_GET['payment'] ?? '';
$date = $_GET['date'] ?? '';

// --- 2. BUILD WHERE CLAUSE ---
$whereClauses = [];
if ($status) {
    $whereClauses[] = "o.order_status = '" . mysqli_real_escape_string($conn, $status) . "'";
}
if ($payment) {
    $whereClauses[] = "o.payment_status = '" . mysqli_real_escape_string($conn, $payment) . "'";
}
if ($date) {
    $whereClauses[] = "DATE(o.created_at) = '" . mysqli_real_escape_string($conn, $date) . "'";
}
if ($search) {
    $s = mysqli_real_escape_string($conn, $search);
    $whereClauses[] = "(o.order_number LIKE '%$s%' OR u.full_name LIKE '%$s%' OR u.account_no LIKE '%$s%')";
}

$whereSql = count($whereClauses) > 0 ? " WHERE " . implode(" AND ", $whereClauses) : "";

// --- 3. FETCH STATISTICS (Global counts) ---
$stats_query = "SELECT 
    COUNT(*) as total,
    SUM(CASE WHEN order_status = 'Pending' THEN 1 ELSE 0 END) as pending,
    SUM(CASE WHEN order_status = 'delivered' THEN 1 ELSE 0 END) as delivered,
    SUM(CASE WHEN order_status = 'cancelled' THEN 1 ELSE 0 END) as cancelled
    FROM orders";
$stats = mysqli_fetch_assoc(mysqli_query($conn, $stats_query));

// --- 4. PAGINATION CALCULATIONS ---
$count_query = "SELECT COUNT(*) as total FROM orders o JOIN users u ON o.user_id = u.id $whereSql";
$total_rows = mysqli_fetch_assoc(mysqli_query($conn, $count_query))['total'];
$total_pages = ceil($total_rows / $limit);

// --- 5. FETCH ORDERS ---
$orders_sql = "SELECT o.*, u.full_name, u.account_no
               FROM orders o 
               JOIN users u ON o.user_id = u.id 
               $whereSql
               ORDER BY o.created_at DESC
               LIMIT $limit OFFSET $offset";
$all_orders_query = mysqli_query($conn, $orders_sql);

include 'includes/header.php';
?>
<!-- Orders Content -->
<div class="container-fluid p-4">

    <!-- Page Header -->
    <div class="d-flex justify-content-between align-items-center mb-4">
        <div>
            <h2 class="mb-1 adm-page-title">Orders</h2>
            <p class="text-muted mb-0 adm-page-sub">Track and manage customer orders</p>
        </div>
    </div>

    <!-- Statistics Cards -->
    <div class="row g-3 mb-4">
        <?php
        // Quick counts for the stats bar
        $stats = mysqli_fetch_assoc(mysqli_query($conn, "SELECT 
        COUNT(*) as total,
        SUM(CASE WHEN order_status = 'Pending' THEN 1 ELSE 0 END) as pending,
        SUM(CASE WHEN order_status = 'delivered' THEN 1 ELSE 0 END) as delivered,
        SUM(CASE WHEN order_status = 'cancelled' THEN 1 ELSE 0 END) as cancelled
        FROM orders"));
        ?>
        <div class="col-6 col-md-3">
            <div class="adm-stat-card">
                <div class="adm-stat-icon adm-stat-icon--blue">
                    <i class="bi bi-receipt"></i>
                </div>
                <div class="adm-stat-body">
                    <span class="adm-stat-label">Total Orders</span>
                    <span class="adm-stat-value"><?= $stats['total'] ?? 0 ?></span>
                </div>
            </div>
        </div>
        <div class="col-6 col-md-3">
            <div class="adm-stat-card">
                <div class="adm-stat-icon adm-stat-icon--yellow">
                    <i class="bi bi-hourglass-split"></i>
                </div>
                <div class="adm-stat-body">
                    <span class="adm-stat-label">Pending</span>
                    <span class="adm-stat-value" id="stat-pending">0</span>
                </div>
            </div>
        </div>
        <div class="col-6 col-md-3">
            <div class="adm-stat-card">
                <div class="adm-stat-icon adm-stat-icon--green">
                    <i class="bi bi-check-circle"></i>
                </div>
                <div class="adm-stat-body">
                    <span class="adm-stat-label">Delivered</span>
                    <span class="adm-stat-value" id="stat-delivered">0</span>
                </div>
            </div>
        </div>
        <div class="col-6 col-md-3">
            <div class="adm-stat-card">
                <div class="adm-stat-icon adm-stat-icon--red">
                    <i class="bi bi-x-circle"></i>
                </div>
                <div class="adm-stat-body">
                    <span class="adm-stat-label">Cancelled</span>
                    <span class="adm-stat-value" id="stat-cancelled">0</span>
                </div>
            </div>
        </div>
    </div>
    <!-- Orders Table -->
    <div class="adm-table-card">
        <div class="table-responsive">
            <table class="adm-table">
                <thead>
                    <tr>
                        <th class="adm-th--check">
                            <input type="checkbox" class="adm-checkbox" id="selectAll">
                        </th>
                        <th>Order</th>
                        <th>Customer</th>
                        <th>Date</th>
                        <th>Items</th>
                        <th>Total</th>
                        <th>Payment</th>
                        <th>Status</th>
                        <th class="adm-th--end">Actions</th>
                    </tr>
                </thead>
                <tbody id="ordersTableBody">
                    <?php
                    // Fetch all orders for the table (you might want to add pagination later)
                    $all_orders_query = mysqli_query($conn, "
                        SELECT o.*, u.full_name, u.account_no
                        FROM orders o 
                        JOIN users u ON o.user_id = u.id 
                        ORDER BY o.created_at DESC
                    ");
                    if (mysqli_num_rows($all_orders_query) > 0):
                        while ($row = mysqli_fetch_assoc($all_orders_query)):
                            $status_class = strtolower($row['order_status']);
                    ?>
                            <tr>
                                <td class="adm-th--check"><input type="checkbox" class="adm-checkbox"></td>
                                <td>
                                    <div class="fw-bold text-dark">#<?= $row['order_number'] ?></div>
                                    <small class="text-muted">ID: <?= $row['id'] ?></small>
                                </td>
                                <td>
                                    <div class="fw-bold text-dark"><?= htmlspecialchars($row['full_name']) ?></div>
                                    <small class="text-muted">UID: <?= $row['account_no'] ?></small>
                                </td>
                                <td><?= date('M d, Y', strtotime($row['created_at'])) ?></td>
                                <td>
                                    <?php
                                    $item_count = mysqli_fetch_assoc(mysqli_query($conn, "SELECT COUNT(*) as count FROM order_items WHERE order_id = {$row['id']}"));
                                    echo $item_count['count'] . " Items";
                                    ?>
                                </td>
                                <td class="fw-bold">₱<?= number_format($row['total_amount'], 2) ?></td>
                                <td>
                                    <span class="adm-badge <?= $row['payment_status'] == 'paid' ? 'adm-badge--delivered' : 'adm-badge--pending' ?>">
                                        <?= strtoupper($row['payment_status']) ?>
                                    </span>
                                </td>
                                <td>
                                    <span class="adm-badge adm-badge--<?= $status_class ?>">
                                        <?= ucfirst($row['order_status']) ?>
                                    </span>
                                </td>
                                <td class="adm-th--end">
                                    <div class="d-flex gap-2 justify-content-end">
                                        <button class="btn btn-sm btn-light" onclick="viewOrderDetails(<?= $row['id'] ?>)" title="View Details">
                                            <i class="bi bi-eye"></i>
                                        </button>

                                        <?php if ($row['order_status'] === 'Pending'): ?>
                                            <button class="btn btn-sm btn-success" id="btn-accept-<?= $row['id'] ?>" onclick="acceptOrder(<?= $row['id'] ?>)" title="Accept Order">
                                                <i class="bi bi-check-lg"></i>
                                            </button>
                                        <?php endif; ?>
                                    </div>
                                </td>
                            </tr>
                        <?php endwhile;
                    else: ?>
                        <tr>
                            <td colspan="9" class="text-center p-5">No orders found.</td>
                        </tr>
                    <?php endif; ?>
                </tbody>
            </table>
        </div>

        <!-- Empty state (shown when no rows) -->
        <div class="adm-empty" id="emptyState" style="display:none;">
            <div class="adm-empty-icon">
                <i class="bi bi-receipt"></i>
            </div>
            <h5 class="adm-empty-title">No orders found</h5>
            <p class="adm-empty-sub">Try adjusting your filters or check back later.</p>
        </div>

        <!-- Pagination -->
        <div class="adm-table-footer">
            <span class="adm-foot-info">
                Showing <?= min($offset + 1, $total_rows) ?> to <?= min($offset + $limit, $total_rows) ?> of <?= $total_rows ?> orders
            </span>
            <nav>
                <ul class="adm-pagination">
                    <li class="adm-page-item <?= ($page <= 1) ? 'disabled' : '' ?>">
                        <button class="adm-page-btn" onclick="changePage(<?= $page - 1 ?>)">
                            <i class="bi bi-chevron-left"></i> Prev
                        </button>
                    </li>

                    <?php for ($i = 1; $i <= $total_pages; $i++): ?>
                        <li class="adm-page-item <?= ($page == $i) ? 'active' : '' ?>">
                            <button class="adm-page-btn" onclick="changePage(<?= $i ?>)"><?= $i ?></button>
                        </li>
                    <?php endfor; ?>

                    <li class="adm-page-item <?= ($page >= $total_pages) ? 'disabled' : '' ?>">
                        <button class="adm-page-btn" onclick="changePage(<?= $page + 1 ?>)">
                            Next <i class="bi bi-chevron-right"></i>
                        </button>
                    </li>
                </ul>
            </nav>
        </div>
    </div>
</div>
</main>

<!-- ============================================================
     ORDER DETAIL MODAL
============================================================ -->
<div class="modal fade" id="orderDetailModal" tabindex="-1" aria-labelledby="orderDetailLabel" aria-hidden="true">
    <div class="modal-dialog modal-lg modal-dialog-centered">
        <div class="modal-content adm-modal">
            <div class="adm-modal-header">
                <div>
                    <h5 class="adm-modal-title" id="orderDetailLabel">Order Details</h5>
                    <span class="adm-modal-sub" id="modalOrderId">—</span>
                </div>
                <button type="button" class="adm-modal-close" data-bs-dismiss="modal">
                    <i class="bi bi-x-lg"></i>
                </button>
            </div>
            <div class="modal-body adm-modal-body" id="orderDetailBody">
                <!-- Filled dynamically -->
                <div class="adm-modal-loading">
                    <div class="spinner-border spinner-border-sm text-primary" role="status"></div>
                    <span>Loading order details…</span>
                </div>
            </div>
        </div>
    </div>
</div>

<!-- ============================================================
     UPDATE STATUS MODAL
============================================================ -->
<div class="modal fade" id="updateStatusBtn" tabindex="-1" aria-labelledby="updateStatusLabel" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered" style="max-width:420px;">
        <div class="modal-content adm-modal">
            <div class="adm-modal-header">
                <h5 class="adm-modal-title" id="updateStatusLabel">Update Order Status</h5>
                <button type="button" class="adm-modal-close" data-bs-dismiss="modal"><i class="bi bi-x-lg"></i></button>
            </div>
            <div class="modal-body adm-modal-body">
                <p class="text-muted mb-3" style="font-size:0.88rem;">Select the new status for order <strong id="statusOrderId">—</strong></p>
                <div class="adm-status-options" id="statusOptions">
                    <label class="adm-status-option">
                        <input type="radio" name="newStatus" value="pending">
                        <span class="adm-status-option-inner">
                            <span class="adm-badge adm-badge--pending">Pending</span>
                            <span class="adm-status-option-desc">Awaiting confirmation</span>
                        </span>
                    </label>
                    <label class="adm-status-option">
                        <input type="radio" name="newStatus" value="processing">
                        <span class="adm-status-option-inner">
                            <span class="adm-badge adm-badge--processing">Processing</span>
                            <span class="adm-status-option-desc">Being prepared</span>
                        </span>
                    </label>
                    <label class="adm-status-option">
                        <input type="radio" name="newStatus" value="shipped">
                        <span class="adm-status-option-inner">
                            <span class="adm-badge adm-badge--shipped">Shipped</span>
                            <span class="adm-status-option-desc">In transit</span>
                        </span>
                    </label>
                    <label class="adm-status-option">
                        <input type="radio" name="newStatus" value="delivered">
                        <span class="adm-status-option-inner">
                            <span class="adm-badge adm-badge--delivered">Delivered</span>
                            <span class="adm-status-option-desc">Received by customer</span>
                        </span>
                    </label>
                    <label class="adm-status-option">
                        <input type="radio" name="newStatus" value="cancelled">
                        <span class="adm-status-option-inner">
                            <span class="adm-badge adm-badge--cancelled">Cancelled</span>
                            <span class="adm-status-option-desc">Order cancelled</span>
                        </span>
                    </label>
                </div>
            </div>
            <div class="adm-modal-footer">
                <button type="button" class="adm-btn adm-btn-ghost" data-bs-dismiss="modal">Cancel</button>
                <button type="button" class="adm-btn adm-btn-primary" onclick="confirmStatusUpdate()">
                    <i class="bi bi-check2 me-1"></i> Confirm Update
                </button>
            </div>
        </div>
    </div>
</div>

<!-- ============================================================
     TOAST
============================================================ -->
<div class="toast-container position-fixed bottom-0 end-0 p-3">
    <div id="liveToast" class="adm-toast toast border-0" role="alert" aria-live="assertive" aria-atomic="true" data-bs-delay="4000">
        <div class="d-flex align-items-center">
            <span class="adm-toast-icon" id="toastIcon"><i class="bi bi-check-circle-fill"></i></span>
            <div class="toast-body flex-grow-1" id="toastMessage">Action completed.</div>
            <button type="button" class="btn-close me-2" data-bs-dismiss="toast" aria-label="Close"></button>
        </div>
        <div class="adm-toast-progress">
            <div class="adm-toast-bar" id="toastProgressBar"></div>
        </div>
    </div>
</div>

<script>
    // Pass PHP data to JS
    const order = <?= json_encode($order); ?>;
    const orderItems = <?= json_encode($order_items); ?>;

    console.log(order); // Order info: subtotal, total, payment, shipping, etc.
    console.log(orderItems); // Each item: name, qty, total, variant, image
</script>
<?php include 'includes/footer.php'; ?>