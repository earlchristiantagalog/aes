<?php
include 'includes/header.php';
?>

<!-- Dashboard Content -->
<div class="container-fluid p-4">
    <!-- Page Header -->
    <div class="d-flex justify-content-between align-items-center mb-4">
        <div>
            <h2 class="mb-1" style="font-size: 1.75rem; font-weight: 700; color: var(--text-dark);">Warehouse Management</h2>
            <div class="d-flex align-items-center gap-2" style="font-size: 0.875rem; color: var(--text-muted);">
                <span class="status-dot-inline active"></span>
                <span>All Systems Operational</span>
            </div>
        </div>
        <div class="d-flex gap-2">
            <button class="btn btn-outline-primary btn-sm" onclick="openScanner()">
                <i class="bi bi-upc-scan me-1"></i>
                Scan Item
            </button>
            <button class="btn btn-primary btn-sm" data-bs-toggle="modal" data-bs-target="#receiveModal">
                <i class="bi bi-box-arrow-in-down me-1"></i>
                Receive Stock
            </button>
            <button class="btn btn-primary btn-sm" data-bs-toggle="modal" data-bs-target="#transferModal">
                <i class="bi bi-arrow-left-right me-1"></i>
                Transfer
            </button>
        </div>
    </div>

    <!-- Metrics Grid -->
    <div class="row g-3 mb-4">
        <!-- Total Inventory -->
        <div class="col-12 col-sm-6 col-lg-3">
            <div class="card border-0 shadow-sm h-100 metric-card-primary">
                <div class="card-body">
                    <div class="d-flex justify-content-between align-items-start mb-3">
                        <div class="metric-icon-wrapper bg-primary bg-opacity-10 text-primary">
                            <i class="bi bi-boxes fs-4"></i>
                        </div>
                        <div class="metric-graph-mini">
                            <svg viewBox="0 0 100 30" preserveAspectRatio="none">
                                <polyline points="0,25 10,20 20,22 30,15 40,18 50,12 60,14 70,8 80,10 90,5 100,7" fill="none" stroke="currentColor" stroke-width="2" />
                            </svg>
                        </div>
                    </div>
                    <div class="metric-label text-muted small">Total Inventory</div>
                    <h3 class="metric-value mb-2">24,583</h3>
                    <div class="metric-trend text-success small">
                        <i class="bi bi-arrow-up"></i>
                        8.2% vs last month
                    </div>
                </div>
            </div>
        </div>

        <!-- Warehouse Capacity -->
        <div class="col-12 col-sm-6 col-lg-3">
            <div class="card border-0 shadow-sm h-100 metric-card-warning">
                <div class="card-body">
                    <div class="d-flex justify-content-between align-items-start mb-3">
                        <div class="metric-icon-wrapper bg-warning bg-opacity-10 text-warning">
                            <i class="bi bi-building fs-4"></i>
                        </div>
                    </div>
                    <div class="metric-label text-muted small">Warehouse Capacity</div>
                    <h3 class="metric-value mb-2">78%</h3>
                    <div class="progress" style="height: 6px;">
                        <div class="progress-bar bg-warning" role="progressbar" style="width: 78%"></div>
                    </div>
                    <div class="text-muted small mt-2">19,575 / 25,000 units</div>
                </div>
            </div>
        </div>

        <!-- Low Stock Alerts -->
        <div class="col-12 col-sm-6 col-lg-3">
            <div class="card border-0 shadow-sm h-100 metric-card-danger">
                <div class="card-body">
                    <div class="d-flex justify-content-between align-items-start mb-3">
                        <div class="metric-icon-wrapper bg-danger bg-opacity-10 text-danger">
                            <i class="bi bi-exclamation-triangle fs-4"></i>
                        </div>
                    </div>
                    <div class="metric-label text-muted small">Low Stock Alerts</div>
                    <h3 class="metric-value mb-2">17</h3>
                    <div class="metric-trend text-danger small">
                        <i class="bi bi-arrow-up"></i>
                        5 items critical
                    </div>
                </div>
            </div>
        </div>

        <!-- Pending Shipments -->
        <div class="col-12 col-sm-6 col-lg-3">
            <div class="card border-0 shadow-sm h-100 metric-card-info">
                <div class="card-body">
                    <div class="d-flex justify-content-between align-items-start mb-3">
                        <div class="metric-icon-wrapper bg-info bg-opacity-10 text-info">
                            <i class="bi bi-truck fs-4"></i>
                        </div>
                    </div>
                    <div class="metric-label text-muted small">Pending Shipments</div>
                    <h3 class="metric-value mb-2">42</h3>
                    <div class="text-muted small">Ready for dispatch</div>
                </div>
            </div>
        </div>
    </div>

    <!-- Main Content -->
    <div class="row g-4">
        <!-- Left Column -->
        <div class="col-12 col-xl-5">
            <!-- Warehouse Layout Map -->
            <div class="card border-0 shadow-sm mb-4">
                <div class="card-header bg-white border-bottom d-flex justify-content-between align-items-center py-3">
                    <h5 class="mb-0 fw-bold">
                        <i class="bi bi-map text-primary me-2"></i>
                        Warehouse Layout
                    </h5>
                    <div class="btn-group btn-group-sm">
                        <button class="btn btn-outline-secondary" onclick="zoomIn()" title="Zoom In">
                            <i class="bi bi-zoom-in"></i>
                        </button>
                        <button class="btn btn-outline-secondary" onclick="zoomOut()" title="Zoom Out">
                            <i class="bi bi-zoom-out"></i>
                        </button>
                        <button class="btn btn-outline-secondary" onclick="resetZoom()" title="Reset">
                            <i class="bi bi-arrow-clockwise"></i>
                        </button>
                    </div>
                </div>
                <div class="card-body">
                    <div class="warehouse-map-wrapper" style="overflow: hidden;">
                        <div class="warehouse-map" id="warehouseMap">
                            <!-- Zone A -->
                            <div class="warehouse-zone" onclick="selectZone('A')">
                                <div class="zone-header">
                                    <span class="zone-label">ZONE A</span>
                                    <span class="zone-capacity-badge">85%</span>
                                </div>
                                <div class="rack-grid">
                                    <div class="rack rack-full" data-bs-toggle="tooltip" title="A1 - 95% Full"></div>
                                    <div class="rack rack-high" data-bs-toggle="tooltip" title="A2 - 80% Full"></div>
                                    <div class="rack rack-medium" data-bs-toggle="tooltip" title="A3 - 60% Full"></div>
                                    <div class="rack rack-low" data-bs-toggle="tooltip" title="A4 - 30% Full"></div>
                                </div>
                            </div>

                            <!-- Zone B -->
                            <div class="warehouse-zone" onclick="selectZone('B')">
                                <div class="zone-header">
                                    <span class="zone-label">ZONE B</span>
                                    <span class="zone-capacity-badge">62%</span>
                                </div>
                                <div class="rack-grid">
                                    <div class="rack rack-medium" data-bs-toggle="tooltip" title="B1 - 55% Full"></div>
                                    <div class="rack rack-high" data-bs-toggle="tooltip" title="B2 - 75% Full"></div>
                                    <div class="rack rack-medium" data-bs-toggle="tooltip" title="B3 - 50% Full"></div>
                                    <div class="rack rack-low" data-bs-toggle="tooltip" title="B4 - 40% Full"></div>
                                </div>
                            </div>

                            <!-- Zone C -->
                            <div class="warehouse-zone" onclick="selectZone('C')">
                                <div class="zone-header">
                                    <span class="zone-label">ZONE C</span>
                                    <span class="zone-capacity-badge">45%</span>
                                </div>
                                <div class="rack-grid">
                                    <div class="rack rack-low" data-bs-toggle="tooltip" title="C1 - 35% Full"></div>
                                    <div class="rack rack-medium" data-bs-toggle="tooltip" title="C2 - 45% Full"></div>
                                    <div class="rack rack-low" data-bs-toggle="tooltip" title="C3 - 40% Full"></div>
                                    <div class="rack rack-high" data-bs-toggle="tooltip" title="C4 - 70% Full"></div>
                                </div>
                            </div>

                            <!-- Loading Dock -->
                            <div class="warehouse-facility">
                                <i class="bi bi-truck facility-icon"></i>
                                <span class="facility-label">Loading Dock</span>
                            </div>

                            <!-- Office Area -->
                            <div class="warehouse-facility">
                                <i class="bi bi-building facility-icon"></i>
                                <span class="facility-label">Office</span>
                            </div>
                        </div>
                    </div>

                    <!-- Map Legend -->
                    <div class="map-legend mt-3 pt-3 border-top">
                        <div class="d-flex justify-content-center gap-3 flex-wrap">
                            <div class="legend-item">
                                <div class="legend-color rack-full"></div>
                                <small class="text-muted">90-100%</small>
                            </div>
                            <div class="legend-item">
                                <div class="legend-color rack-high"></div>
                                <small class="text-muted">70-89%</small>
                            </div>
                            <div class="legend-item">
                                <div class="legend-color rack-medium"></div>
                                <small class="text-muted">40-69%</small>
                            </div>
                            <div class="legend-item">
                                <div class="legend-color rack-low"></div>
                                <small class="text-muted">0-39%</small>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Recent Activity -->
            <div class="card border-0 shadow-sm">
                <div class="card-header bg-white border-bottom d-flex justify-content-between align-items-center py-3">
                    <h5 class="mb-0 fw-bold">
                        <i class="bi bi-clock-history text-primary me-2"></i>
                        Recent Activity
                    </h5>
                    <button class="btn btn-link btn-sm text-primary p-0">View All</button>
                </div>
                <div class="card-body">
                    <div class="activity-timeline">
                        <div class="activity-item">
                            <div class="activity-dot bg-success"></div>
                            <div class="activity-content">
                                <div class="d-flex justify-content-between mb-1">
                                    <span class="fw-semibold small">Stock Received</span>
                                    <span class="text-muted small">5 min ago</span>
                                </div>
                                <p class="text-muted small mb-0">250 units of SKU-A1234 added to Zone B</p>
                            </div>
                        </div>
                        <div class="activity-item">
                            <div class="activity-dot bg-info"></div>
                            <div class="activity-content">
                                <div class="d-flex justify-content-between mb-1">
                                    <span class="fw-semibold small">Internal Transfer</span>
                                    <span class="text-muted small">12 min ago</span>
                                </div>
                                <p class="text-muted small mb-0">150 units moved from Zone A to Zone C</p>
                            </div>
                        </div>
                        <div class="activity-item">
                            <div class="activity-dot bg-primary"></div>
                            <div class="activity-content">
                                <div class="d-flex justify-content-between mb-1">
                                    <span class="fw-semibold small">Shipment Dispatched</span>
                                    <span class="text-muted small">28 min ago</span>
                                </div>
                                <p class="text-muted small mb-0">Order #ORD-45382 shipped (320 units)</p>
                            </div>
                        </div>
                        <div class="activity-item">
                            <div class="activity-dot bg-danger"></div>
                            <div class="activity-content">
                                <div class="d-flex justify-content-between mb-1">
                                    <span class="fw-semibold small">Low Stock Alert</span>
                                    <span class="text-muted small">1 hr ago</span>
                                </div>
                                <p class="text-muted small mb-0">SKU-B5678 below minimum threshold (45 units)</p>
                            </div>
                        </div>
                        <div class="activity-item">
                            <div class="activity-dot bg-success"></div>
                            <div class="activity-content">
                                <div class="d-flex justify-content-between mb-1">
                                    <span class="fw-semibold small">Stock Received</span>
                                    <span class="text-muted small">2 hrs ago</span>
                                </div>
                                <p class="text-muted small mb-0">500 units of SKU-C9012 added to Zone A</p>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <!-- Right Column -->
        <div class="col-12 col-xl-7">
            <!-- Inventory Overview -->
            <div class="card border-0 shadow-sm">
                <div class="card-header bg-white border-bottom d-flex justify-content-between align-items-center py-3">
                    <h5 class="mb-0 fw-bold">
                        <i class="bi bi-clipboard-data text-primary me-2"></i>
                        Inventory Overview
                    </h5>
                    <select class="form-select form-select-sm" style="width: auto;">
                        <option>All Categories</option>
                        <option>Electronics</option>
                        <option>Apparel</option>
                        <option>Food & Beverage</option>
                        <option>Home & Garden</option>
                    </select>
                </div>
                <div class="card-body">
                    <!-- Search -->
                    <div class="input-group mb-3">
                        <span class="input-group-text bg-white">
                            <i class="bi bi-search"></i>
                        </span>
                        <input type="text" class="form-control" placeholder="Search SKU, name, or location..." id="inventorySearch" onkeyup="filterInventory()">
                    </div>

                    <!-- Table -->
                    <div class="table-responsive">
                        <table class="table table-hover align-middle" id="inventoryTable">
                            <thead class="table-light">
                                <tr>
                                    <th class="small fw-semibold">SKU</th>
                                    <th class="small fw-semibold">Product Name</th>
                                    <th class="small fw-semibold">Location</th>
                                    <th class="small fw-semibold">Stock</th>
                                    <th class="small fw-semibold">Status</th>
                                    <th class="small fw-semibold">Actions</th>
                                </tr>
                            </thead>
                            <tbody>
                                <tr>
                                    <td><code class="bg-light px-2 py-1 rounded">SKU-A1234</code></td>
                                    <td>
                                        <div class="d-flex align-items-center">
                                            <div class="bg-light p-2 rounded me-2">
                                                <i class="bi bi-box-seam text-muted"></i>
                                            </div>
                                            <div>
                                                <div class="fw-semibold small">Wireless Headphones Pro</div>
                                                <div class="text-muted small">Electronics</div>
                                            </div>
                                        </div>
                                    </td>
                                    <td><span class="badge bg-light text-info">Zone B-2</span></td>
                                    <td>
                                        <div>
                                            <div class="fw-bold small mb-1">1,245</div>
                                            <div class="progress" style="height: 4px; width: 60px;">
                                                <div class="progress-bar bg-success" style="width: 85%"></div>
                                            </div>
                                        </div>
                                    </td>
                                    <td><span class="badge bg-success bg-opacity-10 text-success">Optimal</span></td>
                                    <td>
                                        <div class="btn-group btn-group-sm">
                                            <button class="btn btn-outline-secondary" data-bs-toggle="tooltip" title="View Details"><i class="bi bi-eye"></i></button>
                                            <button class="btn btn-outline-secondary" data-bs-toggle="tooltip" title="Edit"><i class="bi bi-pencil"></i></button>
                                            <button class="btn btn-outline-secondary" data-bs-toggle="tooltip" title="Move"><i class="bi bi-arrow-right"></i></button>
                                        </div>
                                    </td>
                                </tr>
                                <tr>
                                    <td><code class="bg-light px-2 py-1 rounded">SKU-B5678</code></td>
                                    <td>
                                        <div class="d-flex align-items-center">
                                            <div class="bg-light p-2 rounded me-2">
                                                <i class="bi bi-box-seam text-muted"></i>
                                            </div>
                                            <div>
                                                <div class="fw-semibold small">Organic Cotton T-Shirt</div>
                                                <div class="text-muted small">Apparel</div>
                                            </div>
                                        </div>
                                    </td>
                                    <td><span class="badge bg-light text-info">Zone A-3</span></td>
                                    <td>
                                        <div>
                                            <div class="fw-bold small mb-1">45</div>
                                            <div class="progress" style="height: 4px; width: 60px;">
                                                <div class="progress-bar bg-warning" style="width: 15%"></div>
                                            </div>
                                        </div>
                                    </td>
                                    <td><span class="badge bg-warning bg-opacity-10 text-warning">Low Stock</span></td>
                                    <td>
                                        <div class="btn-group btn-group-sm">
                                            <button class="btn btn-outline-secondary" data-bs-toggle="tooltip" title="View Details"><i class="bi bi-eye"></i></button>
                                            <button class="btn btn-outline-secondary" data-bs-toggle="tooltip" title="Edit"><i class="bi bi-pencil"></i></button>
                                            <button class="btn btn-outline-secondary" data-bs-toggle="tooltip" title="Move"><i class="bi bi-arrow-right"></i></button>
                                        </div>
                                    </td>
                                </tr>
                                <tr>
                                    <td><code class="bg-light px-2 py-1 rounded">SKU-C9012</code></td>
                                    <td>
                                        <div class="d-flex align-items-center">
                                            <div class="bg-light p-2 rounded me-2">
                                                <i class="bi bi-box-seam text-muted"></i>
                                            </div>
                                            <div>
                                                <div class="fw-semibold small">Smart Watch Series 5</div>
                                                <div class="text-muted small">Electronics</div>
                                            </div>
                                        </div>
                                    </td>
                                    <td><span class="badge bg-light text-info">Zone A-1</span></td>
                                    <td>
                                        <div>
                                            <div class="fw-bold small mb-1">3,580</div>
                                            <div class="progress" style="height: 4px; width: 60px;">
                                                <div class="progress-bar bg-info" style="width: 95%"></div>
                                            </div>
                                        </div>
                                    </td>
                                    <td><span class="badge bg-info bg-opacity-10 text-info">Overstocked</span></td>
                                    <td>
                                        <div class="btn-group btn-group-sm">
                                            <button class="btn btn-outline-secondary" data-bs-toggle="tooltip" title="View Details"><i class="bi bi-eye"></i></button>
                                            <button class="btn btn-outline-secondary" data-bs-toggle="tooltip" title="Edit"><i class="bi bi-pencil"></i></button>
                                            <button class="btn btn-outline-secondary" data-bs-toggle="tooltip" title="Move"><i class="bi bi-arrow-right"></i></button>
                                        </div>
                                    </td>
                                </tr>
                                <tr>
                                    <td><code class="bg-light px-2 py-1 rounded">SKU-E7890</code></td>
                                    <td>
                                        <div class="d-flex align-items-center">
                                            <div class="bg-light p-2 rounded me-2">
                                                <i class="bi bi-box-seam text-muted"></i>
                                            </div>
                                            <div>
                                                <div class="fw-semibold small">Yoga Mat Premium</div>
                                                <div class="text-muted small">Sports & Fitness</div>
                                            </div>
                                        </div>
                                    </td>
                                    <td><span class="badge bg-light text-info">Zone B-4</span></td>
                                    <td>
                                        <div>
                                            <div class="fw-bold small mb-1">15</div>
                                            <div class="progress" style="height: 4px; width: 60px;">
                                                <div class="progress-bar bg-danger" style="width: 8%"></div>
                                            </div>
                                        </div>
                                    </td>
                                    <td><span class="badge bg-danger bg-opacity-10 text-danger">Critical</span></td>
                                    <td>
                                        <div class="btn-group btn-group-sm">
                                            <button class="btn btn-outline-secondary" data-bs-toggle="tooltip" title="View Details"><i class="bi bi-eye"></i></button>
                                            <button class="btn btn-outline-secondary" data-bs-toggle="tooltip" title="Edit"><i class="bi bi-pencil"></i></button>
                                            <button class="btn btn-outline-secondary" data-bs-toggle="tooltip" title="Move"><i class="bi bi-arrow-right"></i></button>
                                        </div>
                                    </td>
                                </tr>
                            </tbody>
                        </table>
                    </div>

                    <!-- Pagination -->
                    <div class="d-flex justify-content-between align-items-center mt-3">
                        <div class="text-muted small">Showing 4 of 1,247 items</div>
                        <nav>
                            <ul class="pagination pagination-sm mb-0">
                                <li class="page-item disabled">
                                    <a class="page-link" href="#">Previous</a>
                                </li>
                                <li class="page-item active"><a class="page-link" href="#">1</a></li>
                                <li class="page-item"><a class="page-link" href="#">2</a></li>
                                <li class="page-item"><a class="page-link" href="#">3</a></li>
                                <li class="page-item">
                                    <a class="page-link" href="#">Next</a>
                                </li>
                            </ul>
                        </nav>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
</main>

<!-- Receive Stock Modal -->
<div class="modal fade" id="receiveModal" tabindex="-1">
    <div class="modal-dialog modal-lg">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title">
                    <i class="bi bi-box-arrow-in-down me-2"></i>
                    Receive Stock
                </h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
            </div>
            <div class="modal-body">
                <div class="row g-3">
                    <div class="col-md-6">
                        <label class="form-label">SKU / Product</label>
                        <input type="text" class="form-control" placeholder="Enter SKU or scan barcode">
                    </div>
                    <div class="col-md-6">
                        <label class="form-label">Quantity</label>
                        <input type="number" class="form-control" placeholder="Enter quantity">
                    </div>
                    <div class="col-md-6">
                        <label class="form-label">Warehouse Zone</label>
                        <select class="form-select">
                            <option>Zone A</option>
                            <option>Zone B</option>
                            <option>Zone C</option>
                        </select>
                    </div>
                    <div class="col-md-6">
                        <label class="form-label">Rack Location</label>
                        <select class="form-select">
                            <option>Rack 1</option>
                            <option>Rack 2</option>
                            <option>Rack 3</option>
                            <option>Rack 4</option>
                        </select>
                    </div>
                    <div class="col-12">
                        <label class="form-label">Notes (Optional)</label>
                        <textarea class="form-control" rows="3" placeholder="Add any notes about this stock receipt..."></textarea>
                    </div>
                </div>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancel</button>
                <button type="button" class="btn btn-primary" data-bs-dismiss="modal" onclick="showSuccessToast('Stock received successfully')">Confirm Receipt</button>
            </div>
        </div>
    </div>
</div>

<!-- Transfer Stock Modal -->
<div class="modal fade" id="transferModal" tabindex="-1">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title">
                    <i class="bi bi-arrow-left-right me-2"></i>
                    Transfer Stock
                </h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
            </div>
            <div class="modal-body">
                <div class="row g-3">
                    <div class="col-12">
                        <label class="form-label">SKU / Product</label>
                        <input type="text" class="form-control" placeholder="Enter SKU">
                    </div>
                    <div class="col-md-6">
                        <label class="form-label">From Location</label>
                        <select class="form-select">
                            <option>Zone A-1</option>
                            <option>Zone A-2</option>
                            <option>Zone B-1</option>
                        </select>
                    </div>
                    <div class="col-md-6">
                        <label class="form-label">To Location</label>
                        <select class="form-select">
                            <option>Zone B-3</option>
                            <option>Zone C-2</option>
                            <option>Zone C-4</option>
                        </select>
                    </div>
                    <div class="col-12">
                        <label class="form-label">Quantity to Transfer</label>
                        <input type="number" class="form-control" placeholder="Enter quantity">
                    </div>
                </div>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancel</button>
                <button type="button" class="btn btn-primary" data-bs-dismiss="modal" onclick="showSuccessToast('Stock transferred successfully')">Confirm Transfer</button>
            </div>
        </div>
    </div>
</div>

<?php
include 'includes/footer.php';
?>