<?php
include 'includes/header.php';
?>

<!-- Breadcrumb -->
<section class="breadcrumb-section py-3 bg-light">
    <div class="container">
        <nav aria-label="breadcrumb">
            <ol class="breadcrumb mb-0">
                <li class="breadcrumb-item"><a href="index.php"><i class="bi bi-house-door me-1"></i>Home</a></li>
                <li class="breadcrumb-item"><a href="#"><i class="bi bi-person me-1"></i>My Account</a></li>
                <li class="breadcrumb-item active" aria-current="page">My Orders</li>
            </ol>
        </nav>
    </div>
</section>

<!-- Orders Section -->
<section class="orders-section py-5">
    <div class="container">
        <!-- Page Header -->
        <div class="row mb-4">
            <div class="col-12">
                <div class="d-flex justify-content-between align-items-center flex-wrap gap-3">
                    <div>
                        <h2 class="fw-bold mb-2">
                            <i class="bi bi-box-seam me-2" style="color: var(--primary-color);"></i>
                            My Orders
                        </h2>
                        <p class="text-muted mb-0">Track and manage your orders</p>
                    </div>
                    <div class="d-flex gap-2">
                        <button class="btn btn-outline-primary">
                            <i class="bi bi-funnel me-2"></i>Filter
                        </button>
                        <button class="btn btn-outline-primary">
                            <i class="bi bi-download me-2"></i>Export
                        </button>
                    </div>
                </div>
            </div>
        </div>

        <!-- Order Filters -->
        <div class="order-filters mb-4">
            <div class="filter-tabs">
                <button class="filter-tab active" data-filter="all">
                    All Orders
                    <span class="badge">12</span>
                </button>
                <button class="filter-tab" data-filter="processing">
                    Processing
                    <span class="badge">3</span>
                </button>
                <button class="filter-tab" data-filter="shipped">
                    Shipped
                    <span class="badge">5</span>
                </button>
                <button class="filter-tab" data-filter="delivered">
                    Delivered
                    <span class="badge">3</span>
                </button>
                <button class="filter-tab" data-filter="cancelled">
                    Cancelled
                    <span class="badge">1</span>
                </button>
            </div>
        </div>

        <!-- Search Bar -->
        <div class="order-search mb-4">
            <div class="search-box">
                <i class="bi bi-search"></i>
                <input type="text" class="form-control" placeholder="Search by Order ID, Product Name, or Date...">
            </div>
        </div>

        <!-- Orders List -->
        <div class="orders-list">
            <?php
            $orders = [
                [
                    'id' => 'AES-2026-001234',
                    'date' => 'Feb 8, 2026',
                    'status' => 'Processing',
                    'statusClass' => 'warning',
                    'total' => 4446.00,
                    'items' => 12,
                    'payment' => 'Credit Card',
                    'tracking' => 'N/A',
                    'estimatedDelivery' => 'Feb 15, 2026',
                    'products' => [
                        ['name' => 'Student Notebook Set', 'qty' => 2, 'price' => 299, 'image' => 'https://via.placeholder.com/80x80/E8F4F8/1e3a5f?text=Notebook'],
                        ['name' => 'Scientific Calculator', 'qty' => 1, 'price' => 899, 'image' => 'https://via.placeholder.com/80x80/E8F4F8/1e3a5f?text=Calculator'],
                        ['name' => 'Art Supply Kit', 'qty' => 1, 'price' => 1299, 'image' => 'https://via.placeholder.com/80x80/E8F4F8/1e3a5f?text=Art+Kit']
                    ]
                ],
                [
                    'id' => 'AES-2026-001189',
                    'date' => 'Feb 5, 2026',
                    'status' => 'Shipped',
                    'statusClass' => 'primary',
                    'total' => 2350.00,
                    'items' => 8,
                    'payment' => 'GCash',
                    'tracking' => 'JT123456789PH',
                    'estimatedDelivery' => 'Feb 10, 2026',
                    'products' => [
                        ['name' => 'Premium Pen Set', 'qty' => 2, 'price' => 199, 'image' => 'https://via.placeholder.com/80x80/E8F4F8/1e3a5f?text=Pens'],
                        ['name' => 'Geometry Tool Set', 'qty' => 2, 'price' => 449, 'image' => 'https://via.placeholder.com/80x80/E8F4F8/1e3a5f?text=Geometry']
                    ]
                ],
                [
                    'id' => 'AES-2026-001156',
                    'date' => 'Feb 1, 2026',
                    'status' => 'Delivered',
                    'statusClass' => 'success',
                    'total' => 1850.00,
                    'items' => 5,
                    'payment' => 'Cash on Delivery',
                    'tracking' => 'JT987654321PH',
                    'estimatedDelivery' => 'Delivered',
                    'products' => [
                        ['name' => 'Highlighter Set', 'qty' => 3, 'price' => 149, 'image' => 'https://via.placeholder.com/80x80/E8F4F8/1e3a5f?text=Highlighters'],
                        ['name' => 'Sticky Notes Pack', 'qty' => 2, 'price' => 99, 'image' => 'https://via.placeholder.com/80x80/E8F4F8/1e3a5f?text=Notes']
                    ]
                ],
                [
                    'id' => 'AES-2026-001098',
                    'date' => 'Jan 28, 2026',
                    'status' => 'Delivered',
                    'statusClass' => 'success',
                    'total' => 3299.00,
                    'items' => 10,
                    'payment' => 'Bank Transfer',
                    'tracking' => 'JT456789123PH',
                    'estimatedDelivery' => 'Delivered',
                    'products' => [
                        ['name' => 'School Backpack', 'qty' => 1, 'price' => 1299, 'image' => 'https://via.placeholder.com/80x80/E8F4F8/1e3a5f?text=Backpack'],
                        ['name' => 'Pencil Case', 'qty' => 2, 'price' => 249, 'image' => 'https://via.placeholder.com/80x80/E8F4F8/1e3a5f?text=Case']
                    ]
                ],
                [
                    'id' => 'AES-2026-000987',
                    'date' => 'Jan 25, 2026',
                    'status' => 'Cancelled',
                    'statusClass' => 'danger',
                    'total' => 1599.00,
                    'items' => 4,
                    'payment' => 'Credit Card',
                    'tracking' => 'N/A',
                    'estimatedDelivery' => 'Cancelled',
                    'products' => [
                        ['name' => 'Desk Organizer', 'qty' => 1, 'price' => 599, 'image' => 'https://via.placeholder.com/80x80/E8F4F8/1e3a5f?text=Organizer'],
                        ['name' => 'Marker Set', 'qty' => 3, 'price' => 199, 'image' => 'https://via.placeholder.com/80x80/E8F4F8/1e3a5f?text=Markers']
                    ]
                ]
            ];

            foreach ($orders as $index => $order):
            ?>
                <div class="order-card" data-status="<?php echo strtolower($order['status']); ?>">
                    <!-- Order Header -->
                    <div class="order-header">
                        <div class="order-header-left">
                            <div class="order-id">
                                <strong>Order ID:</strong> <?php echo $order['id']; ?>
                            </div>
                            <div class="order-date">
                                <i class="bi bi-calendar3 me-1"></i>
                                <?php echo $order['date']; ?>
                            </div>
                        </div>
                        <div class="order-header-right">
                            <span class="status-badge status-<?php echo $order['statusClass']; ?>">
                                <?php echo $order['status']; ?>
                            </span>
                        </div>
                    </div>

                    <!-- Order Body -->
                    <div class="order-body">
                        <div class="row g-3">
                            <!-- Product Images -->
                            <div class="col-md-5">
                                <div class="order-products">
                                    <?php foreach ($order['products'] as $pIndex => $product): ?>
                                        <?php if ($pIndex < 3): ?>
                                            <div class="order-product-item">
                                                <img src="<?php echo $product['image']; ?>" alt="<?php echo $product['name']; ?>">
                                                <div class="product-qty"><?php echo $product['qty']; ?>x</div>
                                            </div>
                                        <?php endif; ?>
                                    <?php endforeach; ?>
                                    <?php if (count($order['products']) > 3): ?>
                                        <div class="more-products">
                                            +<?php echo count($order['products']) - 3; ?> more
                                        </div>
                                    <?php endif; ?>
                                </div>
                            </div>

                            <!-- Order Info -->
                            <div class="col-md-7">
                                <div class="order-info-grid">
                                    <div class="info-item">
                                        <div class="info-label">
                                            <i class="bi bi-box-seam me-1"></i>Items
                                        </div>
                                        <div class="info-value"><?php echo $order['items']; ?> items</div>
                                    </div>
                                    <div class="info-item">
                                        <div class="info-label">
                                            <i class="bi bi-credit-card me-1"></i>Payment
                                        </div>
                                        <div class="info-value"><?php echo $order['payment']; ?></div>
                                    </div>
                                    <div class="info-item">
                                        <div class="info-label">
                                            <i class="bi bi-truck me-1"></i>Tracking
                                        </div>
                                        <div class="info-value"><?php echo $order['tracking']; ?></div>
                                    </div>
                                    <div class="info-item">
                                        <div class="info-label">
                                            <i class="bi bi-calendar-check me-1"></i>Delivery
                                        </div>
                                        <div class="info-value"><?php echo $order['estimatedDelivery']; ?></div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>

                    <!-- Order Footer -->
                    <div class="order-footer">
                        <div class="order-total">
                            <span class="total-label">Total Amount:</span>
                            <span class="total-amount">₱<?php echo number_format($order['total'], 2); ?></span>
                        </div>
                        <div class="order-actions">
                            <?php if ($order['status'] == 'Processing'): ?>
                                <button class="btn btn-sm btn-outline-danger" onclick="confirmCancel('<?php echo $order['id']; ?>')">
                                    <i class="bi bi-x-circle me-1"></i>Cancel Order
                                </button>
                            <?php endif; ?>

                            <?php if ($order['status'] == 'Shipped'): ?>
                                <button class="btn btn-sm btn-primary" onclick="trackOrder('<?php echo $order['tracking']; ?>')">
                                    <i class="bi bi-geo-alt me-1"></i>Track Order
                                </button>
                            <?php endif; ?>

                            <?php if ($order['status'] == 'Delivered'): ?>
                                <button class="btn btn-sm btn-outline-primary" onclick="reviewOrder('<?php echo $order['id']; ?>')">
                                    <i class="bi bi-star me-1"></i>Write Review
                                </button>
                                <button class="btn btn-sm btn-primary" onclick="reorder('<?php echo $order['id']; ?>')">
                                    <i class="bi bi-arrow-repeat me-1"></i>Reorder
                                </button>
                            <?php endif; ?>

                            <button class="btn btn-sm btn-outline-primary" onclick="viewOrderDetails('<?php echo $order['id']; ?>')">
                                <i class="bi bi-eye me-1"></i>View Details
                            </button>
                        </div>
                    </div>
                </div>
            <?php endforeach; ?>
        </div>

        <!-- Empty State (Hidden by default) -->
        <div class="empty-state" style="display: none;">
            <div class="empty-icon">
                <i class="bi bi-inbox"></i>
            </div>
            <h4>No Orders Found</h4>
            <p class="text-muted">You haven't placed any orders yet.</p>
            <a href="index.php" class="btn btn-primary">
                <i class="bi bi-shop me-2"></i>Start Shopping
            </a>
        </div>

        <!-- Pagination -->
        <div class="pagination-wrapper mt-4">
            <nav aria-label="Order pagination">
                <ul class="pagination justify-content-center">
                    <li class="page-item disabled">
                        <a class="page-link" href="#" tabindex="-1">
                            <i class="bi bi-chevron-left"></i>
                        </a>
                    </li>
                    <li class="page-item active"><a class="page-link" href="#">1</a></li>
                    <li class="page-item"><a class="page-link" href="#">2</a></li>
                    <li class="page-item"><a class="page-link" href="#">3</a></li>
                    <li class="page-item">
                        <a class="page-link" href="#">
                            <i class="bi bi-chevron-right"></i>
                        </a>
                    </li>
                </ul>
            </nav>
        </div>
    </div>
</section>

<!-- Order Details Modal -->
<div class="modal fade" id="orderDetailsModal" tabindex="-1" aria-hidden="true">
    <div class="modal-dialog modal-lg modal-dialog-centered modal-dialog-scrollable">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title">
                    <i class="bi bi-receipt me-2"></i>
                    Order Details
                </h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body">
                <!-- Order Timeline -->
                <div class="order-timeline mb-4">
                    <h6 class="mb-3">Order Status</h6>
                    <div class="timeline">
                        <div class="timeline-item completed">
                            <div class="timeline-icon">
                                <i class="bi bi-check-circle-fill"></i>
                            </div>
                            <div class="timeline-content">
                                <div class="timeline-title">Order Placed</div>
                                <div class="timeline-date">Feb 8, 2026 - 10:30 AM</div>
                            </div>
                        </div>
                        <div class="timeline-item completed">
                            <div class="timeline-icon">
                                <i class="bi bi-check-circle-fill"></i>
                            </div>
                            <div class="timeline-content">
                                <div class="timeline-title">Payment Confirmed</div>
                                <div class="timeline-date">Feb 8, 2026 - 10:32 AM</div>
                            </div>
                        </div>
                        <div class="timeline-item active">
                            <div class="timeline-icon">
                                <i class="bi bi-box-seam"></i>
                            </div>
                            <div class="timeline-content">
                                <div class="timeline-title">Processing</div>
                                <div class="timeline-date">In Progress</div>
                            </div>
                        </div>
                        <div class="timeline-item">
                            <div class="timeline-icon">
                                <i class="bi bi-truck"></i>
                            </div>
                            <div class="timeline-content">
                                <div class="timeline-title">Shipped</div>
                                <div class="timeline-date">Pending</div>
                            </div>
                        </div>
                        <div class="timeline-item">
                            <div class="timeline-icon">
                                <i class="bi bi-house-check"></i>
                            </div>
                            <div class="timeline-content">
                                <div class="timeline-title">Delivered</div>
                                <div class="timeline-date">Expected: Feb 15, 2026</div>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Order Items -->
                <div class="order-items-detail mb-4">
                    <h6 class="mb-3">Order Items</h6>
                    <div class="table-responsive">
                        <table class="table table-borderless">
                            <thead>
                                <tr>
                                    <th>Product</th>
                                    <th>Price</th>
                                    <th>Qty</th>
                                    <th>Total</th>
                                </tr>
                            </thead>
                            <tbody>
                                <tr>
                                    <td>
                                        <div class="d-flex align-items-center gap-2">
                                            <img src="https://via.placeholder.com/50x50/E8F4F8/1e3a5f?text=Item" alt="Product" class="rounded">
                                            <span>Student Notebook Set</span>
                                        </div>
                                    </td>
                                    <td>₱299.00</td>
                                    <td>2</td>
                                    <td class="fw-bold">₱598.00</td>
                                </tr>
                                <tr>
                                    <td>
                                        <div class="d-flex align-items-center gap-2">
                                            <img src="https://via.placeholder.com/50x50/E8F4F8/1e3a5f?text=Calc" alt="Product" class="rounded">
                                            <span>Scientific Calculator</span>
                                        </div>
                                    </td>
                                    <td>₱899.00</td>
                                    <td>1</td>
                                    <td class="fw-bold">₱899.00</td>
                                </tr>
                            </tbody>
                        </table>
                    </div>
                </div>

                <!-- Shipping & Payment Info -->
                <div class="row g-4">
                    <div class="col-md-6">
                        <div class="info-card">
                            <h6><i class="bi bi-geo-alt me-2"></i>Shipping Address</h6>
                            <p class="mb-0">
                                John Doe<br>
                                123 Main Street, Apt 4B<br>
                                Quezon City, Metro Manila 1100<br>
                                Philippines<br>
                                <strong>Phone:</strong> +63 912 345 6789
                            </p>
                        </div>
                    </div>
                    <div class="col-md-6">
                        <div class="info-card">
                            <h6><i class="bi bi-credit-card me-2"></i>Payment Method</h6>
                            <p class="mb-0">
                                <strong>Credit Card</strong><br>
                                •••• •••• •••• 1234<br>
                                Status: <span class="text-success">Paid</span>
                            </p>
                        </div>
                    </div>
                </div>

                <!-- Order Summary -->
                <div class="order-summary-modal mt-4">
                    <h6 class="mb-3">Order Summary</h6>
                    <div class="summary-row">
                        <span>Subtotal</span>
                        <span>₱4,940.00</span>
                    </div>
                    <div class="summary-row">
                        <span>Shipping</span>
                        <span class="text-success">FREE</span>
                    </div>
                    <div class="summary-row">
                        <span>Discount</span>
                        <span class="text-success">-₱494.00</span>
                    </div>
                    <hr>
                    <div class="summary-row total">
                        <span>Total</span>
                        <span>₱4,446.00</span>
                    </div>
                </div>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-outline-primary">
                    <i class="bi bi-download me-2"></i>Download Invoice
                </button>
                <button type="button" class="btn btn-primary">
                    <i class="bi bi-printer me-2"></i>Print Order
                </button>
            </div>
        </div>
    </div>
</div>
<!-- Orders Page Script -->
<script>
    document.addEventListener('DOMContentLoaded', function() {
        // Filter functionality
        const filterTabs = document.querySelectorAll('.filter-tab');
        const orderCards = document.querySelectorAll('.order-card');

        filterTabs.forEach(tab => {
            tab.addEventListener('click', function() {
                // Remove active class from all tabs
                filterTabs.forEach(t => t.classList.remove('active'));
                // Add active class to clicked tab
                this.classList.add('active');

                const filter = this.dataset.filter;

                // Filter orders
                orderCards.forEach(card => {
                    if (filter === 'all') {
                        card.style.display = 'block';
                    } else {
                        const status = card.dataset.status;
                        card.style.display = status === filter ? 'block' : 'none';
                    }
                });
            });
        });

        // Search functionality
        const searchInput = document.querySelector('.order-search input');
        searchInput.addEventListener('input', function() {
            const searchTerm = this.value.toLowerCase();

            orderCards.forEach(card => {
                const orderId = card.querySelector('.order-id').textContent.toLowerCase();
                const orderDate = card.querySelector('.order-date').textContent.toLowerCase();

                if (orderId.includes(searchTerm) || orderDate.includes(searchTerm)) {
                    card.style.display = 'block';
                } else {
                    card.style.display = 'none';
                }
            });
        });
    });

    // Action Functions
    function viewOrderDetails(orderId) {
        const modal = new bootstrap.Modal(document.getElementById('orderDetailsModal'));
        modal.show();
    }

    function trackOrder(trackingNumber) {
        alert('Tracking order with tracking number: ' + trackingNumber);
        // Redirect to tracking page or show tracking modal
    }

    function confirmCancel(orderId) {
        if (confirm('Are you sure you want to cancel this order?')) {
            alert('Order ' + orderId + ' has been cancelled.');
            // Handle order cancellation
        }
    }

    function reviewOrder(orderId) {
        alert('Opening review form for order: ' + orderId);
        // Redirect to review page or show review modal
    }

    function reorder(orderId) {
        if (confirm('Add all items from this order to your cart?')) {
            alert('Items added to cart!');
            // Handle reorder
        }
    }
</script>

<?php
include 'includes/footer.php';
?>