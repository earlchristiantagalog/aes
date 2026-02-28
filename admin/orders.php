<?php
$conn = mysqli_connect('localhost', 'root', '', 'aes');

// Get order ID from URL
$order_id = $_GET['id'] ?? null;

// if (!$order_id) {
//     header("Location: index.php");
//     exit;
// }

// Fetch order details
$order_res = mysqli_query($conn, "SELECT * FROM orders WHERE id = '$order_id' LIMIT 1");
$order = mysqli_fetch_assoc($order_res);

// if (!$order) {
//     header("Location: index.php");
//     exit;
// }

// Fetch order items
$order_items = [];
$item_res = mysqli_query($conn, "SELECT * FROM order_items WHERE order_id = '$order_id'");
while ($row = mysqli_fetch_assoc($item_res)) {
    // Optional: fetch cover image for each product
    $img_res = mysqli_query($conn, "SELECT image_path FROM product_images WHERE product_id = {$row['product_id']} AND is_cover=1 LIMIT 1");
    $img = mysqli_fetch_assoc($img_res);
    $row['image'] = $img ? 'admin/products/uploads/' . $img['image_path'] : 'assets/images/default.jpg';

    $order_items[] = $row;
}
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
        <div class="d-flex gap-2">
            <button class="adm-btn adm-btn-outline" onclick="exportOrders()">
                <i class="bi bi-download me-2"></i>Export
            </button>
            <button class="adm-btn adm-btn-primary" data-bs-toggle="modal" data-bs-target="#addOrderModal">
                <i class="bi bi-plus-circle me-2"></i>New Order
            </button>
        </div>
    </div>

    <!-- Statistics Cards -->
    <div class="row g-3 mb-4">
        <div class="col-6 col-md-3">
            <div class="adm-stat-card">
                <div class="adm-stat-icon adm-stat-icon--blue">
                    <i class="bi bi-receipt"></i>
                </div>
                <div class="adm-stat-body">
                    <span class="adm-stat-label">Total Orders</span>
                    <span class="adm-stat-value" id="stat-total">0</span>
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

    <!-- Filters -->
    <div class="adm-filter-card mb-4">
        <div class="row g-3 align-items-end">
            <div class="col-12 col-md-3">
                <label class="adm-filter-label">Search</label>
                <div class="adm-search-wrap">
                    <i class="bi bi-search adm-search-icon"></i>
                    <input type="text" class="adm-input adm-input--search" placeholder="Order ID, customer, email…" id="searchOrder">
                </div>
            </div>
            <div class="col-6 col-md-2">
                <label class="adm-filter-label">Status</label>
                <select class="adm-select" id="statusFilter">
                    <option value="">All Statuses</option>
                    <option value="pending">Pending</option>
                    <option value="processing">Processing</option>
                    <option value="shipped">Shipped</option>
                    <option value="delivered">Delivered</option>
                    <option value="cancelled">Cancelled</option>
                    <option value="refunded">Refunded</option>
                </select>
            </div>
            <div class="col-6 col-md-2">
                <label class="adm-filter-label">Payment</label>
                <select class="adm-select" id="paymentFilter">
                    <option value="">All Payments</option>
                    <option value="paid">Paid</option>
                    <option value="unpaid">Unpaid</option>
                    <option value="refunded">Refunded</option>
                </select>
            </div>
            <div class="col-6 col-md-2">
                <label class="adm-filter-label">Date Range</label>
                <input type="date" class="adm-input" id="dateFilter">
            </div>
            <div class="col-6 col-md-3 d-flex gap-2 justify-content-end">
                <button class="adm-btn adm-btn-outline" onclick="loadOrders(1)">
                    <i class="bi bi-funnel me-1"></i>Filter
                </button>
                <button class="adm-btn adm-btn-ghost" onclick="resetFilters()">
                    <i class="bi bi-arrow-clockwise me-1"></i>Reset
                </button>
            </div>
        </div>
    </div>

    <!-- Orders Table -->
    <div class="adm-table-card">

        <!-- Status Tab Bar -->
        <div class="adm-tab-bar">
            <button class="adm-tab active" data-status="">All Orders <span class="adm-tab-count" id="tab-all">—</span></button>
            <button class="adm-tab" data-status="pending">Pending <span class="adm-tab-count" id="tab-pending">—</span></button>
            <button class="adm-tab" data-status="processing">Processing <span class="adm-tab-count" id="tab-processing">—</span></button>
            <button class="adm-tab" data-status="shipped">Shipped <span class="adm-tab-count" id="tab-shipped">—</span></button>
            <button class="adm-tab" data-status="delivered">Delivered <span class="adm-tab-count" id="tab-delivered">—</span></button>
            <button class="adm-tab adm-tab--danger" data-status="cancelled">Cancelled <span class="adm-tab-count" id="tab-cancelled">—</span></button>
        </div>

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
                    <!-- Populated via JS / PHP -->
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
            <span class="adm-foot-info" id="paginationInfo">Showing 0 orders</span>
            <nav>
                <ul class="adm-pagination" id="pagination">
                    <li class="adm-page-item disabled">
                        <button class="adm-page-btn" id="prevPage">
                            <i class="bi bi-chevron-left"></i> Prev
                        </button>
                    </li>
                    <li class="adm-page-item active"><button class="adm-page-btn">1</button></li>
                    <li class="adm-page-item"><button class="adm-page-btn">2</button></li>
                    <li class="adm-page-item"><button class="adm-page-btn">3</button></li>
                    <li class="adm-page-item">
                        <button class="adm-page-btn" id="nextPage">
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
            <div class="adm-modal-footer">
                <button type="button" class="adm-btn adm-btn-ghost" data-bs-dismiss="modal">Close</button>
                <button type="button" class="adm-btn adm-btn-outline" onclick="printOrder()">
                    <i class="bi bi-printer me-1"></i> Print
                </button>
                <button type="button" class="adm-btn adm-btn-primary" id="updateStatusBtn">
                    <i class="bi bi-arrow-repeat me-1"></i> Update Status
                </button>
            </div>
        </div>
    </div>
</div>

<!-- ============================================================
     UPDATE STATUS MODAL
============================================================ -->
<div class="modal fade" id="updateStatusModal" tabindex="-1" aria-labelledby="updateStatusLabel" aria-hidden="true">
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