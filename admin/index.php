<?php
include 'includes/header.php';
?>

<!-- Dashboard Content -->
<div class="container-fluid p-4">
    <div class="d-flex justify-content-between align-items-center mb-4">
        <div>
            <h2 class="h4 fw-bold mb-1">Dashboard Overview</h2>
            <p class="text-muted mb-0">Welcome back! Here's what's happening today.</p>
        </div>
        <div class="d-flex gap-2">
            <button class="btn btn-outline-primary" data-bs-toggle="modal" data-bs-target="#requestAbsenceModal">
                <i class="bi bi-calendar-plus me-2"></i>Request Absence
            </button>

            <button class="btn btn-primary d-none d-md-block">
                <i class="bi bi-download me-2"></i>Export Report
            </button>
        </div>
    </div>

    <!-- Statistics Cards -->
    <div class="row g-4 mb-4">
        <div class="col-12 col-sm-6 col-xl-3">
            <div class="card stat-card p-3">
                <div class="d-flex justify-content-between align-items-start">
                    <div>
                        <p class="text-muted mb-1">Total Sales</p>
                        <h3 class="fw-bold mb-0">₱124,500</h3>
                        <small class="text-success"><i class="bi bi-arrow-up"></i> 12.5% from last month</small>
                    </div>
                    <div class="rounded-circle bg-primary bg-opacity-10 p-3">
                        <i class="bi bi-currency-dollar text-primary fs-3"></i>
                    </div>
                </div>
            </div>
        </div>

        <div class="col-12 col-sm-6 col-xl-3">
            <div class="card stat-card p-3">
                <div class="d-flex justify-content-between align-items-start">
                    <div>
                        <p class="text-muted mb-1">Total Orders</p>
                        <h3 class="fw-bold mb-0">1,543</h3>
                        <small class="text-success"><i class="bi bi-arrow-up"></i> 8.2% from last month</small>
                    </div>
                    <div class="rounded-circle bg-success bg-opacity-10 p-3">
                        <i class="bi bi-cart-check text-success fs-3"></i>
                    </div>
                </div>
            </div>
        </div>

        <div class="col-12 col-sm-6 col-xl-3">
            <div class="card stat-card p-3">
                <div class="d-flex justify-content-between align-items-start">
                    <div>
                        <p class="text-muted mb-1">Total Products</p>
                        <h3 class="fw-bold mb-0">2,847</h3>
                        <small class="text-warning"><i class="bi bi-dash"></i> No change</small>
                    </div>
                    <div class="rounded-circle bg-warning bg-opacity-10 p-3">
                        <i class="bi bi-box-seam text-warning fs-3"></i>
                    </div>
                </div>
            </div>
        </div>

        <div class="col-12 col-sm-6 col-xl-3">
            <div class="card stat-card p-3">
                <div class="d-flex justify-content-between align-items-start">
                    <div>
                        <p class="text-muted mb-1">Total Customers</p>
                        <h3 class="fw-bold mb-0">892</h3>
                        <small class="text-success"><i class="bi bi-arrow-up"></i> 15.3% from last month</small>
                    </div>
                    <div class="rounded-circle bg-info bg-opacity-10 p-3">
                        <i class="bi bi-people text-info fs-3"></i>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Recent Items Table -->
    <div class="table-container">
        <div class="d-flex justify-content-between align-items-center mb-3">
            <h5 class="fw-bold mb-0">Recent Products</h5>
            <a href="#" class="btn btn-sm btn-outline-primary">View All</a>
        </div>
        <div class="table-responsive">
            <table class="table table-hover align-middle">
                <thead class="table-light">
                    <tr>
                        <th>ID</th>
                        <th>Product Name</th>
                        <th>Category</th>
                        <th>Stock</th>
                        <th>Price</th>
                        <th>Status</th>
                        <th>Actions</th>
                    </tr>
                </thead>
                <tbody>
                    <tr>
                        <td><span class="badge bg-light text-dark">#SKU-001</span></td>
                        <td><strong>Hardbound Notebook</strong></td>
                        <td>Stationery</td>
                        <td>450 units</td>
                        <td>₱120.00</td>
                        <td><span class="badge bg-success">In Stock</span></td>
                        <td>
                            <button class="btn btn-sm btn-light border me-1" data-bs-toggle="tooltip" title="Edit">
                                <i class="bi bi-pencil"></i>
                            </button>
                            <button class="btn btn-sm btn-light border" data-bs-toggle="tooltip" title="Delete">
                                <i class="bi bi-trash"></i>
                            </button>
                        </td>
                    </tr>
                    <tr>
                        <td><span class="badge bg-light text-dark">#SKU-002</span></td>
                        <td><strong>Ballpoint Pen (Pack of 12)</strong></td>
                        <td>Writing Tools</td>
                        <td>1,250 units</td>
                        <td>₱85.00</td>
                        <td><span class="badge bg-success">In Stock</span></td>
                        <td>
                            <button class="btn btn-sm btn-light border me-1" data-bs-toggle="tooltip" title="Edit">
                                <i class="bi bi-pencil"></i>
                            </button>
                            <button class="btn btn-sm btn-light border" data-bs-toggle="tooltip" title="Delete">
                                <i class="bi bi-trash"></i>
                            </button>
                        </td>
                    </tr>
                    <tr>
                        <td><span class="badge bg-light text-dark">#SKU-003</span></td>
                        <td><strong>Scientific Calculator</strong></td>
                        <td>Electronics</td>
                        <td>35 units</td>
                        <td>₱450.00</td>
                        <td><span class="badge bg-warning">Low Stock</span></td>
                        <td>
                            <button class="btn btn-sm btn-light border me-1" data-bs-toggle="tooltip" title="Edit">
                                <i class="bi bi-pencil"></i>
                            </button>
                            <button class="btn btn-sm btn-light border" data-bs-toggle="tooltip" title="Delete">
                                <i class="bi bi-trash"></i>
                            </button>
                        </td>
                    </tr>
                    <tr>
                        <td><span class="badge bg-light text-dark">#SKU-004</span></td>
                        <td><strong>Art Supplies Set</strong></td>
                        <td>Art & Craft</td>
                        <td>180 units</td>
                        <td>₱320.00</td>
                        <td><span class="badge bg-success">In Stock</span></td>
                        <td>
                            <button class="btn btn-sm btn-light border me-1" data-bs-toggle="tooltip" title="Edit">
                                <i class="bi bi-pencil"></i>
                            </button>
                            <button class="btn btn-sm btn-light border" data-bs-toggle="tooltip" title="Delete">
                                <i class="bi bi-trash"></i>
                            </button>
                        </td>
                    </tr>
                    <tr>
                        <td><span class="badge bg-light text-dark">#SKU-005</span></td>
                        <td><strong>Backpack - Large</strong></td>
                        <td>Bags</td>
                        <td>0 units</td>
                        <td>₱850.00</td>
                        <td><span class="badge bg-danger">Out of Stock</span></td>
                        <td>
                            <button class="btn btn-sm btn-light border me-1" data-bs-toggle="tooltip" title="Edit">
                                <i class="bi bi-pencil"></i>
                            </button>
                            <button class="btn btn-sm btn-light border" data-bs-toggle="tooltip" title="Delete">
                                <i class="bi bi-trash"></i>
                            </button>
                        </td>
                    </tr>
                </tbody>
            </table>
        </div>
    </div>
</div>
</main>

<!-- Absence Modal -->
<div class="modal fade" id="requestAbsenceModal" tabindex="-1" aria-hidden="true">
    <div class="modal-dialog modal-lg modal-dialog-centered">
        <div class="modal-content absence-modal-content">

            <!-- Decorative top bar -->
            <div class="absence-topbar">
                <div class="absence-topbar-dots">
                    <span></span><span></span><span></span>
                </div>
                <span class="absence-topbar-label">HR · Absence Request</span>
                <button type="button" class="absence-close-btn" data-bs-dismiss="modal" aria-label="Close">
                    <svg width="14" height="14" viewBox="0 0 14 14" fill="none">
                        <path d="M1 1L13 13M13 1L1 13" stroke="currentColor" stroke-width="2" stroke-linecap="round" />
                    </svg>
                </button>
            </div>

            <!-- Header -->
            <div class="absence-header">
                <div class="absence-icon-wrap">
                    <svg width="22" height="22" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.8" stroke-linecap="round" stroke-linejoin="round">
                        <rect x="3" y="4" width="18" height="18" rx="2" />
                        <line x1="16" y1="2" x2="16" y2="6" />
                        <line x1="8" y1="2" x2="8" y2="6" />
                        <line x1="3" y1="10" x2="21" y2="10" />
                    </svg>
                </div>
                <div>
                    <h5 class="absence-title">Request Absence</h5>
                    <p class="absence-subtitle">Fill in the form below to submit your absence request.</p>
                </div>
            </div>

            <form id="absenceForm" action="generate_print.php" method="POST" target="print_frame">
                <div class="absence-body">

                    <!-- Employee -->
                    <div class="absence-field">
                        <label class="absence-label">Employee</label>
                        <div class="absence-readonly-wrap">
                            <svg width="15" height="15" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                                <circle cx="12" cy="8" r="4" />
                                <path d="M4 20c0-4 3.6-7 8-7s8 3 8 7" />
                            </svg>
                            <input type="text" name="employee_name" class="absence-input absence-input-readonly"
                                value="<?= htmlspecialchars($_SESSION['full_name']) ?>" readonly>
                            <span class="absence-lock-badge">locked</span>
                        </div>
                    </div>

                    <!-- Two column row -->
                    <div class="absence-row">
                        <div class="absence-field">
                            <label class="absence-label">Date of Request</label>
                            <div class="absence-input-wrap">
                                <svg width="15" height="15" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                                    <rect x="3" y="4" width="18" height="18" rx="2" />
                                    <line x1="16" y1="2" x2="16" y2="6" />
                                    <line x1="8" y1="2" x2="8" y2="6" />
                                    <line x1="3" y1="10" x2="21" y2="10" />
                                </svg>
                                <input type="date" name="request_date" class="absence-input"
                                    value="<?= date('Y-m-d'); ?>" required>
                            </div>
                        </div>

                        <div class="absence-field">
                            <label class="absence-label">Reason for Absence</label>
                            <div class="absence-input-wrap">
                                <svg width="15" height="15" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                                    <circle cx="12" cy="12" r="10" />
                                    <line x1="12" y1="8" x2="12" y2="12" />
                                    <line x1="12" y1="16" x2="12.01" y2="16" />
                                </svg>
                                <select class="absence-input absence-select" id="reasonSelect" name="reason" required>
                                    <option value="">Select a reason…</option>
                                    <option value="Medical/Sick">🩺 Medical / Sick</option>
                                    <option value="Personal Matter">👤 Personal Matter</option>
                                    <option value="Vacation">🌴 Vacation</option>
                                    <option value="Emergency">🚨 Emergency</option>
                                </select>
                                <svg class="absence-select-arrow" width="12" height="12" viewBox="0 0 12 12" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round">
                                    <polyline points="2 4 6 8 10 4" />
                                </svg>
                            </div>
                        </div>
                    </div>

                    <!-- Message -->
                    <div class="absence-field">
                        <label class="absence-label">Message / Additional Notes</label>
                        <textarea id="summernote" name="message" class="absence-textarea" placeholder="Add any relevant details, dates, or context for your request…"></textarea>
                    </div>

                    <!-- Info strip -->
                    <div class="absence-info-strip">
                        <svg width="14" height="14" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                            <circle cx="12" cy="12" r="10" />
                            <line x1="12" y1="8" x2="12" y2="12" />
                            <line x1="12" y1="16" x2="12.01" y2="16" />
                        </svg>
                        Submitting will generate a printable absence form for your records.
                    </div>
                </div>

                <div class="absence-footer">
                    <button type="button" class="absence-btn-secondary" data-bs-dismiss="modal">Cancel</button>
                    <button type="submit" class="absence-btn-primary">
                        <svg width="15" height="15" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                            <polyline points="6 9 6 2 18 2 18 9" />
                            <path d="M6 18H4a2 2 0 0 1-2-2v-5a2 2 0 0 1 2-2h16a2 2 0 0 1 2 2v5a2 2 0 0 1-2 2h-2" />
                            <rect x="6" y="14" width="12" height="8" />
                        </svg>
                        Submit & Print
                    </button>
                </div>
            </form>

        </div>
    </div>
</div>
<iframe name="print_frame" id="print_frame" style="display:none;"></iframe>

<style>
    @import url('https://fonts.googleapis.com/css2?family=Lora:wght@400;500;600&family=DM+Sans:wght@300;400;500;600&display=swap');

    .absence-modal-content {
        border: none;
        border-radius: 18px;
        overflow: hidden;
        background: var(--bg-light);
        box-shadow: var(--shadow-xl), 0 0 0 1px var(--border-color);
        font-family: 'DM Sans', sans-serif;
    }

    /* Top bar */
    .absence-topbar {
        background: var(--primary-dark);
        display: flex;
        align-items: center;
        justify-content: space-between;
        padding: 10px 18px;
    }

    .absence-topbar-dots {
        display: flex;
        gap: 6px;
    }

    .absence-topbar-dots span {
        width: 10px;
        height: 10px;
        border-radius: 50%;
        background: rgba(255, 255, 255, 0.2);
    }

    .absence-topbar-dots span:first-child {
        background: #ef4444;
    }

    .absence-topbar-dots span:nth-child(2) {
        background: var(--accent-color);
    }

    .absence-topbar-dots span:nth-child(3) {
        background: var(--secondary-color);
    }

    .absence-topbar-label {
        font-size: 11px;
        letter-spacing: 0.08em;
        color: rgba(255, 255, 255, 0.5);
        text-transform: uppercase;
        font-weight: 500;
    }

    .absence-close-btn {
        background: rgba(255, 255, 255, 0.1);
        border: none;
        border-radius: 6px;
        width: 26px;
        height: 26px;
        display: flex;
        align-items: center;
        justify-content: center;
        color: rgba(255, 255, 255, 0.6);
        cursor: pointer;
        transition: background 0.2s, color 0.2s;
    }

    .absence-close-btn:hover {
        background: rgba(255, 255, 255, 0.2);
        color: #fff;
    }

    /* Header */
    .absence-header {
        padding: 28px 32px 0;
        display: flex;
        align-items: flex-start;
        gap: 16px;
        background: var(--bg-white);
    }

    .absence-icon-wrap {
        width: 46px;
        height: 46px;
        background: var(--primary-color);
        border-radius: 12px;
        display: flex;
        align-items: center;
        justify-content: center;
        color: #fff;
        flex-shrink: 0;
        margin-top: 2px;
        box-shadow: 0 4px 12px rgba(37, 99, 235, 0.3);
    }

    .absence-title {
        font-family: 'Lora', serif;
        font-size: 1.35rem;
        font-weight: 600;
        color: var(--text-dark);
        margin: 0 0 4px;
    }

    .absence-subtitle {
        font-size: 0.82rem;
        color: var(--text-muted);
        margin: 0;
        font-weight: 400;
    }

    /* Body */
    .absence-body {
        padding: 24px 32px;
        display: flex;
        flex-direction: column;
        gap: 20px;
        background: var(--bg-white);
    }

    .absence-row {
        display: grid;
        grid-template-columns: 1fr 1fr;
        gap: 16px;
    }

    /* Fields */
    .absence-field {
        display: flex;
        flex-direction: column;
        gap: 7px;
    }

    .absence-label {
        font-size: 0.75rem;
        font-weight: 600;
        text-transform: uppercase;
        letter-spacing: 0.07em;
        color: var(--text-muted);
    }

    /* Input wrap */
    .absence-input-wrap,
    .absence-readonly-wrap {
        position: relative;
        display: flex;
        align-items: center;
    }

    .absence-input-wrap>svg,
    .absence-readonly-wrap>svg {
        position: absolute;
        left: 13px;
        color: var(--text-muted);
        pointer-events: none;
        flex-shrink: 0;
    }

    .absence-input {
        width: 100%;
        padding: 10px 13px 10px 38px;
        background: var(--bg-white);
        border: 1.5px solid var(--border-color);
        border-radius: 10px;
        font-size: 0.88rem;
        font-family: 'DM Sans', sans-serif;
        color: var(--text-dark);
        transition: border-color 0.2s, box-shadow 0.2s;
        appearance: none;
        -webkit-appearance: none;
    }

    .absence-input:focus {
        outline: none;
        border-color: var(--primary-color);
        box-shadow: 0 0 0 3px rgba(37, 99, 235, 0.12);
    }

    .absence-input::placeholder {
        color: #cbd5e1;
    }

    .absence-input-readonly {
        background: var(--bg-light);
        color: var(--text-muted);
        cursor: not-allowed;
    }

    .absence-lock-badge {
        position: absolute;
        right: 12px;
        font-size: 10px;
        text-transform: uppercase;
        letter-spacing: 0.06em;
        color: var(--text-muted);
        background: var(--border-color);
        padding: 2px 7px;
        border-radius: 20px;
        font-weight: 600;
    }

    .absence-select {
        padding-right: 36px;
        cursor: pointer;
    }

    .absence-select-arrow {
        position: absolute;
        right: 12px;
        color: var(--text-muted);
        pointer-events: none;
    }

    /* Textarea */
    .absence-textarea {
        width: 100%;
        min-height: 120px;
        padding: 12px 14px;
        background: var(--bg-white);
        border: 1.5px solid var(--border-color);
        border-radius: 10px;
        font-size: 0.88rem;
        font-family: 'DM Sans', sans-serif;
        color: var(--text-dark);
        resize: vertical;
        transition: border-color 0.2s, box-shadow 0.2s;
        line-height: 1.6;
    }

    .absence-textarea:focus {
        outline: none;
        border-color: var(--primary-color);
        box-shadow: 0 0 0 3px rgba(37, 99, 235, 0.12);
    }

    .absence-textarea::placeholder {
        color: #cbd5e1;
    }

    /* Info strip */
    .absence-info-strip {
        display: flex;
        align-items: center;
        gap: 8px;
        padding: 10px 14px;
        background: rgba(245, 158, 11, 0.08);
        border: 1px solid rgba(245, 158, 11, 0.25);
        border-radius: 8px;
        font-size: 0.78rem;
        color: #92400e;
        font-weight: 500;
    }

    .absence-info-strip svg {
        color: var(--accent-color);
        flex-shrink: 0;
    }

    /* Footer */
    .absence-footer {
        display: flex;
        justify-content: flex-end;
        align-items: center;
        gap: 10px;
        padding: 16px 32px 24px;
        border-top: 1px solid var(--border-color);
        background: var(--bg-light);
    }

    .absence-btn-secondary {
        padding: 9px 20px;
        background: var(--bg-white);
        border: 1.5px solid var(--border-color);
        border-radius: 10px;
        font-family: 'DM Sans', sans-serif;
        font-size: 0.86rem;
        font-weight: 500;
        color: var(--text-muted);
        cursor: pointer;
        transition: all 0.2s;
    }

    .absence-btn-secondary:hover {
        background: var(--bg-light);
        border-color: #cbd5e1;
        color: var(--text-dark);
    }

    .absence-btn-primary {
        display: flex;
        align-items: center;
        gap: 8px;
        padding: 10px 22px;
        background: var(--primary-color);
        border: none;
        border-radius: 10px;
        font-family: 'DM Sans', sans-serif;
        font-size: 0.86rem;
        font-weight: 600;
        color: #fff;
        cursor: pointer;
        transition: all 0.2s;
        letter-spacing: 0.01em;
        box-shadow: 0 4px 12px rgba(37, 99, 235, 0.3);
    }

    .absence-btn-primary:hover {
        background: var(--primary-dark);
        transform: translateY(-1px);
        box-shadow: 0 8px 20px rgba(37, 99, 235, 0.4);
    }

    .absence-btn-primary:active {
        transform: translateY(0);
        box-shadow: 0 2px 6px rgba(37, 99, 235, 0.25);
    }

    @media (max-width: 576px) {
        .absence-row {
            grid-template-columns: 1fr;
        }

        .absence-header,
        .absence-body,
        .absence-footer {
            padding-left: 20px;
            padding-right: 20px;
        }
    }
</style>
<?php
include 'includes/footer.php';
?>