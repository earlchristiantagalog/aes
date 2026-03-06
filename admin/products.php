<?php
include 'includes/header.php';
?>
<!-- Products Content -->
<div class="container-fluid p-4">
    <!-- Page Header -->
    <div class="d-flex justify-content-between align-items-center mb-4">
        <div>
            <h2 class="fw-bold mb-1" style="color:#1e3a5f;letter-spacing:-0.02em;">Products</h2>
            <p class="text-muted mb-0" style="font-size:0.875rem;">Manage your product inventory</p>
        </div>
        <div class="d-flex gap-2">
            <button class="st-btn st-btn-outline" data-bs-toggle="modal" data-bs-target="#addCategoryModal">
                <i class="bi bi-tag me-2"></i>Add Category
            </button>
            <button class="st-btn st-btn-primary" data-bs-toggle="modal" data-bs-target="#addProductModal">
                <i class="bi bi-plus-circle me-2"></i>Add Product
            </button>
        </div>
    </div>

    <!-- Statistics Cards -->
    <div class="row g-3 mb-4">
        <div class="col-md-3">
            <div class="prd-stat-card">
                <div class="prd-stat-icon" style="background:rgba(30,58,95,0.1);">
                    <i class="bi bi-box-seam" style="color:#1e3a5f;"></i>
                </div>
                <div>
                    <div class="prd-stat-label">Total Products</div>
                    <div class="prd-stat-value" id="stat-total">0</div>
                </div>
            </div>
        </div>
        <div class="col-md-3">
            <div class="prd-stat-card">
                <div class="prd-stat-icon" style="background:rgba(39,174,96,0.1);">
                    <i class="bi bi-check-circle" style="color:#27ae60;"></i>
                </div>
                <div>
                    <div class="prd-stat-label">In Stock</div>
                    <div class="prd-stat-value" id="stat-in-stock">0</div>
                </div>
            </div>
        </div>
        <div class="col-md-3">
            <div class="prd-stat-card">
                <div class="prd-stat-icon" style="background:rgba(255,215,0,0.15);">
                    <i class="bi bi-exclamation-triangle" style="color:#b8860b;"></i>
                </div>
                <div>
                    <div class="prd-stat-label">Low Stock</div>
                    <div class="prd-stat-value" id="stat-low-stock">0</div>
                </div>
            </div>
        </div>
        <div class="col-md-3">
            <div class="prd-stat-card">
                <div class="prd-stat-icon" style="background:rgba(229,62,62,0.1);">
                    <i class="bi bi-x-circle" style="color:#e53e3e;"></i>
                </div>
                <div>
                    <div class="prd-stat-label">Out of Stock</div>
                    <div class="prd-stat-value" id="stat-out-of-stock">0</div>
                </div>
            </div>
        </div>
    </div>

    <!-- Filters and Actions -->
    <div class="prd-filters-card mb-4">
        <div class="row g-3 align-items-center">
            <div class="col-md-3">
                <div class="prd-search-wrap">
                    <i class="bi bi-search prd-search-icon"></i>
                    <input type="text" class="prd-search-input" placeholder="Search products..." id="searchProduct">
                </div>
            </div>
            <div class="col-md-2">
                <select class="prd-select" id="categoryFilter">
                    <option value="">All Categories</option>
                    <option value="electronics">Electronics</option>
                    <option value="clothing">Clothing</option>
                    <option value="food">Food &amp; Beverage</option>
                    <option value="books">Books</option>
                    <option value="home">Home &amp; Garden</option>
                </select>
            </div>
            <div class="col-md-2">
                <select class="prd-select" id="stockFilter">
                    <option value="">All Stock Status</option>
                    <option value="in-stock">In Stock</option>
                    <option value="low-stock">Low Stock</option>
                    <option value="out-of-stock">Out of Stock</option>
                </select>
            </div>
            <div class="col-md-2">
                <select class="prd-select" id="statusFilter">
                    <option value="">All Status</option>
                    <option value="active">Active</option>
                    <option value="inactive">Inactive</option>
                </select>
            </div>
            <div class="col-md-3 d-flex justify-content-end gap-2">
                <button class="st-btn st-btn-outline" onclick="loadProducts(1)">
                    <i class="bi bi-funnel me-1"></i>Filter
                </button>
                <button class="st-btn st-btn-ghost" onclick="resetFilters()">
                    <i class="bi bi-arrow-clockwise me-1"></i>Reset
                </button>
            </div>
        </div>
    </div>

    <!-- Products Table -->
    <div class="card border-0 shadow-sm">
        <div class="prd-table-card">
            <div class="table-responsive">
                <table class="prd-table">
                    <thead>
                        <tr>
                            <th style="width:48px;">
                                <input type="checkbox" class="prd-checkbox">
                            </th>
                            <th>Product</th>
                            <th>SKU</th>
                            <th>Category</th>
                            <th>Price</th>
                            <th>Stock</th>
                            <th>Status</th>
                            <th style="text-align:right;">Actions</th>
                        </tr>
                    </thead>
                    <tbody>
                        <!-- JS-rendered rows go here -->
                    </tbody>
                </table>
            </div>

            <!-- Pagination -->
            <div class="prd-pagination">
                <span class="prd-page-info">Showing 1 to 6 of 1,234 products</span>
                <nav>
                    <ul class="prd-pager">
                        <li class="disabled"><a href="#">Previous</a></li>
                        <li class="active"><a href="#">1</a></li>
                        <li><a href="#">2</a></li>
                        <li><a href="#">3</a></li>
                        <li><a href="#">4</a></li>
                        <li><a href="#">5</a></li>
                        <li><a href="#">Next</a></li>
                    </ul>
                </nav>
            </div>
        </div>
    </div>
</div>
</main>

<!-- Add Product Modal Styles -->
<style>
    /* ============================================================
   ADD PRODUCT MODAL — AES Brand System
============================================================ */

    .product-modal .modal-content {
        border: none;
        border-radius: 4px;
        overflow: hidden;
        box-shadow: 0 25px 60px rgba(30, 58, 95, 0.25);
        background: #fff;
    }

    .product-modal .modal-header {
        background: #1e3a5f;
        padding: 1.25rem 2rem;
        border: none;
        position: relative;
        overflow: hidden;
    }

    /* Subtle dot pattern on header */
    .product-modal .modal-header::before {
        content: '';
        position: absolute;
        inset: 0;
        background-image: radial-gradient(rgba(255, 255, 255, 0.07) 1px, transparent 1px);
        background-size: 18px 18px;
        pointer-events: none;
    }

    /* Gold accent bar at very top of header */
    .product-modal .modal-header::after {
        content: '';
        position: absolute;
        top: 0;
        left: 0;
        right: 0;
        height: 3px;
        background: #FFD700;
    }

    .product-modal .modal-title {
        font-weight: 800;
        font-size: 1.15rem;
        color: #fff;
        margin: 0;
        position: relative;
        z-index: 1;
        display: flex;
        align-items: center;
        gap: 0.65rem;
        letter-spacing: -0.01em;
    }

    .product-modal .btn-close {
        filter: brightness(0) invert(1);
        opacity: 0.7;
        position: relative;
        z-index: 1;
        transition: all 0.2s ease;
    }

    .product-modal .btn-close:hover {
        opacity: 1;
        transform: rotate(90deg);
    }

    .product-modal .modal-body {
        padding: 1.75rem 2rem;
        background: #f7fafc;
    }

    /* ---- Section Card ---- */
    .section-card {
        background: #fff;
        border: 1px solid #e2e8f0;
        border-radius: 4px;
        padding: 1.5rem;
        margin-bottom: 1.25rem;
        box-shadow: 0 2px 8px rgba(30, 58, 95, 0.06);
        transition: box-shadow 0.2s ease;
    }

    .section-card:hover {
        box-shadow: 0 6px 20px rgba(30, 58, 95, 0.11);
    }

    .section-title {
        font-size: 0.68rem;
        font-weight: 700;
        text-transform: uppercase;
        letter-spacing: 0.1em;
        color: #1e3a5f;
        margin-bottom: 1.25rem;
        padding-left: 0.75rem;
        border-left: 3px solid #1e3a5f;
        display: flex;
        align-items: center;
        justify-content: space-between;
    }

    /* ---- Fields ---- */
    .prd-field {
        display: flex;
        flex-direction: column;
        gap: 0.35rem;
    }

    .prd-label {
        font-size: 0.72rem;
        font-weight: 700;
        text-transform: uppercase;
        letter-spacing: 0.07em;
        color: #718096;
        display: flex;
        align-items: center;
        gap: 0.4rem;
        margin-bottom: 0;
    }

    .prd-label i {
        color: #1e3a5f;
        font-size: 0.85rem;
    }

    .prd-req {
        color: #e53e3e;
    }

    .prd-label-hint {
        font-weight: 400;
        text-transform: none;
        letter-spacing: 0;
        color: #a0aec0;
        font-size: 0.7rem;
        margin-left: auto;
    }

    .prd-field-hint {
        font-size: 0.72rem;
        color: #a0aec0;
    }

    /* ---- Inputs ---- */
    .prd-input {
        padding: 0.65rem 0.9rem;
        border: 1.5px solid #e2e8f0;
        border-radius: 4px;
        background: #fff;
        font-size: 0.875rem;
        color: #1a202c;
        outline: none;
        width: 100%;
        transition: border-color 0.2s ease, box-shadow 0.2s ease;
        font-family: inherit;
    }

    .prd-input:focus {
        border-color: #1e3a5f;
        box-shadow: 0 0 0 3px rgba(30, 58, 95, 0.1);
    }

    .prd-input::placeholder {
        color: #a0aec0;
    }

    .prd-input--lg {
        font-size: 1rem;
        font-weight: 600;
        padding: 0.8rem 1rem;
    }

    .prd-textarea {
        resize: vertical;
        min-height: 110px;
        line-height: 1.6;
    }

    .prd-input--mono {
        font-family: 'Courier New', monospace;
        font-size: 0.82rem;
        font-weight: 700;
        color: #718096;
    }

    /* Input group (SKU / price) */
    .prd-input-group {
        display: flex;
        align-items: stretch;
    }

    .prd-input-group .prd-input {
        border-radius: 4px 0 0 4px;
        flex: 1;
    }

    .prd-input-prefix {
        display: flex;
        align-items: center;
        padding: 0 0.85rem;
        background: #f7fafc;
        border: 1.5px solid #e2e8f0;
        border-right: none;
        border-radius: 4px 0 0 4px;
        font-weight: 700;
        font-size: 0.875rem;
        color: #1e3a5f;
    }

    .prd-input--prefixed {
        border-radius: 0 4px 4px 0;
    }

    .prd-input-btn {
        width: 40px;
        background: #f7fafc;
        border: 1.5px solid #e2e8f0;
        border-left: none;
        border-radius: 0 4px 4px 0;
        color: #1e3a5f;
        cursor: pointer;
        font-size: 0.9rem;
        transition: all 0.2s ease;
        display: flex;
        align-items: center;
        justify-content: center;
    }

    .prd-input-btn:hover {
        background: #1e3a5f;
        color: #fff;
        border-color: #1e3a5f;
    }

    /* Multi-select */
    .prd-multiselect {
        width: 100%;
        padding: 0.5rem;
        border: 1.5px solid #e2e8f0;
        border-radius: 4px;
        min-height: 120px;
        font-size: 0.875rem;
        color: #1a202c;
        outline: none;
        transition: border-color 0.2s ease, box-shadow 0.2s ease;
    }

    .prd-multiselect:focus {
        border-color: #1e3a5f;
        box-shadow: 0 0 0 3px rgba(30, 58, 95, 0.1);
    }

    .prd-multiselect option {
        padding: 0.45rem 0.5rem;
        border-radius: 3px;
        margin-bottom: 2px;
    }

    .prd-multiselect option:checked {
        background: #1e3a5f;
        color: #fff;
    }

    /* ---- Toggle for product status ---- */
    .prd-status-toggle {
        display: inline-flex;
        align-items: center;
        gap: 0.6rem;
        cursor: pointer;
        margin-top: 0.4rem;
    }

    .prd-status-toggle input {
        display: none;
    }

    .prd-toggle-track {
        position: relative;
        width: 44px;
        height: 24px;
        background: #e2e8f0;
        border-radius: 24px;
        transition: background 0.2s ease;
        flex-shrink: 0;
    }

    .prd-toggle-track::after {
        content: '';
        position: absolute;
        width: 18px;
        height: 18px;
        background: #fff;
        border-radius: 50%;
        top: 3px;
        left: 3px;
        transition: transform 0.2s ease;
        box-shadow: 0 1px 4px rgba(0, 0, 0, 0.18);
    }

    .prd-status-toggle input:checked+.prd-toggle-track {
        background: #1e3a5f;
    }

    .prd-status-toggle input:checked+.prd-toggle-track::after {
        transform: translateX(20px);
    }

    .prd-toggle-label {
        font-size: 0.875rem;
        font-weight: 600;
        color: #1a202c;
    }

    /* ---- Image Upload ---- */
    .prd-img-grid {
        display: grid;
        grid-template-columns: repeat(auto-fill, minmax(80px, 1fr));
        gap: 0.65rem;
    }

    .prd-upload-area {
        border: 2px dashed #e2e8f0;
        border-radius: 4px;
        padding: 1.75rem 1.25rem;
        text-align: center;
        background: #f7fafc;
        transition: all 0.2s ease;
        cursor: pointer;
        position: relative;
        overflow: hidden;
    }

    .prd-upload-area:hover {
        border-color: #1e3a5f;
        background: rgba(30, 58, 95, 0.03);
    }

    .prd-upload-area input[type="file"] {
        position: absolute;
        inset: 0;
        opacity: 0;
        cursor: pointer;
        width: 100%;
        height: 100%;
    }

    .prd-upload-icon {
        width: 52px;
        height: 52px;
        background: #1e3a5f;
        border-radius: 4px;
        display: inline-flex;
        align-items: center;
        justify-content: center;
        font-size: 1.4rem;
        color: #fff;
        margin-bottom: 0.75rem;
    }

    .prd-upload-title {
        font-weight: 700;
        font-size: 0.875rem;
        color: #1a202c;
        margin-bottom: 0.3rem;
    }

    .prd-upload-hint {
        font-size: 0.8rem;
        color: #718096;
        line-height: 1.5;
    }

    .prd-upload-hint span {
        font-size: 0.72rem;
        color: #a0aec0;
    }

    /* Image preview items */
    .image-preview-item {
        position: relative;
        aspect-ratio: 1;
        border-radius: 4px;
        overflow: hidden;
        border: 1.5px solid #e2e8f0;
        transition: border-color 0.2s ease;
    }

    .image-preview-item:hover {
        border-color: #1e3a5f;
    }

    .image-preview-item img {
        width: 100%;
        height: 100%;
        object-fit: cover;
    }

    .image-preview-remove {
        position: absolute;
        top: 4px;
        right: 4px;
        width: 22px;
        height: 22px;
        background: rgba(229, 62, 62, 0.9);
        border: none;
        border-radius: 50%;
        color: #fff;
        display: flex;
        align-items: center;
        justify-content: center;
        cursor: pointer;
        opacity: 0;
        transition: opacity 0.2s ease;
        font-size: 0.72rem;
    }

    .image-preview-item:hover .image-preview-remove {
        opacity: 1;
    }

    .cover-badge {
        position: absolute;
        bottom: 4px;
        left: 4px;
        background: #1e3a5f;
        color: #FFD700;
        padding: 0.18rem 0.45rem;
        border-radius: 3px;
        font-size: 0.6rem;
        font-weight: 800;
        text-transform: uppercase;
        letter-spacing: 0.05em;
    }

    /* ---- Info box ---- */
    .prd-info-box {
        display: flex;
        align-items: flex-start;
        gap: 0.65rem;
        padding: 0.85rem 1rem;
        background: rgba(135, 206, 235, 0.12);
        border-left: 3px solid #1e3a5f;
        border-radius: 4px;
        font-size: 0.82rem;
        color: #1e3a5f;
        line-height: 1.5;
    }

    .prd-info-box i {
        font-size: 1rem;
        margin-top: 0.1rem;
        flex-shrink: 0;
        color: #1e3a5f;
    }

    /* ---- Variant rows ---- */
    .prd-btn-variant {
        display: inline-flex;
        align-items: center;
        gap: 0.4rem;
        padding: 0.4rem 0.9rem;
        background: none;
        color: #1e3a5f;
        border: 1.5px solid #1e3a5f;
        border-radius: 4px;
        font-size: 0.78rem;
        font-weight: 700;
        cursor: pointer;
        transition: all 0.2s ease;
    }

    .prd-btn-variant:hover {
        background: #1e3a5f;
        color: #fff;
    }

    .variant-row {
        background: #fff;
        border: 1.5px solid #e2e8f0;
        border-radius: 4px;
        padding: 1.1rem;
        margin-bottom: 0.75rem;
        transition: border-color 0.2s ease;
        animation: prd-slide-in 0.2s ease-out;
    }

    .variant-row:hover {
        border-color: #87CEEB;
    }

    .variant-header {
        display: flex;
        align-items: center;
        justify-content: space-between;
        margin-bottom: 0.75rem;
        padding-bottom: 0.65rem;
        border-bottom: 1px solid #e2e8f0;
    }

    .variant-label {
        font-size: 0.82rem;
        font-weight: 700;
        color: #1e3a5f;
        display: flex;
        align-items: center;
        gap: 0.4rem;
    }

    .variant-remove {
        width: 28px;
        height: 28px;
        background: none;
        border: 1.5px solid #fecaca;
        color: #e53e3e;
        border-radius: 4px;
        cursor: pointer;
        transition: all 0.2s ease;
        display: flex;
        align-items: center;
        justify-content: center;
        font-size: 0.8rem;
    }

    .variant-remove:hover {
        background: #e53e3e;
        color: #fff;
        border-color: #e53e3e;
    }

    /* ---- Empty state ---- */
    .prd-empty-state {
        text-align: center;
        padding: 2.5rem 1.5rem;
        background: #f7fafc;
        border: 2px dashed #e2e8f0;
        border-radius: 4px;
    }

    .prd-empty-icon {
        width: 64px;
        height: 64px;
        background: rgba(30, 58, 95, 0.07);
        border-radius: 4px;
        display: inline-flex;
        align-items: center;
        justify-content: center;
        font-size: 1.75rem;
        color: #1e3a5f;
        margin-bottom: 0.75rem;
    }

    .prd-empty-title {
        font-weight: 700;
        font-size: 0.95rem;
        color: #1a202c;
        margin-bottom: 0.35rem;
    }

    .prd-empty-text {
        font-size: 0.82rem;
        color: #718096;
    }

    /* ---- Modal Footer ---- */
    .product-modal .modal-footer {
        background: #f7fafc;
        border-top: 1px solid #e2e8f0;
        padding: 1.1rem 2rem;
        gap: 0.75rem;
    }

    .prd-btn-discard {
        display: inline-flex;
        align-items: center;
        gap: 0.4rem;
        padding: 0.65rem 1.5rem;
        background: none;
        color: #718096;
        border: 1.5px solid #e2e8f0;
        border-radius: 4px;
        font-size: 0.875rem;
        font-weight: 600;
        cursor: pointer;
        transition: all 0.2s ease;
    }

    .prd-btn-discard:hover {
        border-color: #718096;
        color: #1a202c;
        background: #fff;
    }

    .prd-btn-save {
        display: inline-flex;
        align-items: center;
        gap: 0.4rem;
        padding: 0.65rem 2rem;
        background: #1e3a5f;
        color: #fff;
        border: none;
        border-radius: 4px;
        font-size: 0.875rem;
        font-weight: 700;
        cursor: pointer;
        box-shadow: 0 4px 12px rgba(30, 58, 95, 0.25);
        transition: all 0.2s ease;
    }

    .prd-btn-save:hover {
        background: #162d4a;
        transform: translateY(-1px);
        box-shadow: 0 8px 20px rgba(30, 58, 95, 0.3);
        color: #fff;
    }

    /* ---- Animation ---- */
    @keyframes prd-slide-in {
        from {
            opacity: 0;
            transform: translateY(10px);
        }

        to {
            opacity: 1;
            transform: translateY(0);
        }
    }
</style>

<!-- Add Product Modal -->
<div class="modal fade product-modal" id="addProductModal" tabindex="-1" aria-hidden="true">
    <div class="modal-dialog modal-xl modal-dialog-scrollable">
        <div class="modal-content">

            <!-- Modal Header -->
            <div class="modal-header">
                <h5 class="modal-title">
                    <i class="bi bi-plus-circle-fill"></i>
                    Add New Product
                </h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>

            <!-- Modal Body -->
            <div class="modal-body">
                <form id="addProductForm">
                    <div class="row g-4">

                        <!-- ===== LEFT COLUMN ===== -->
                        <div class="col-lg-8">

                            <!-- General Information -->
                            <div class="section-card">
                                <div class="section-title">
                                    <span><i class="bi bi-info-circle me-2"></i>General Information</span>
                                </div>

                                <div class="prd-field mb-3">
                                    <label class="prd-label">
                                        <i class="bi bi-tag"></i> Product Name <span class="prd-req">*</span>
                                    </label>
                                    <input type="text" class="prd-input prd-input--lg" id="productName"
                                        placeholder="e.g. Intermediate Mathematics Workbook" required>
                                </div>

                                <div class="prd-field mb-3">
                                    <label class="prd-label">
                                        <i class="bi bi-chat-left-text"></i> Short Description
                                    </label>
                                    <input type="text" class="prd-input" id="smallDesc"
                                        placeholder="Brief catchphrase shown on product cards">
                                </div>

                                <div class="prd-field mb-0">
                                    <label class="prd-label">
                                        <i class="bi bi-align-left"></i> Full Description
                                    </label>
                                    <textarea class="prd-input prd-textarea" id="productDescription" rows="5"
                                        placeholder="Detailed description with features, specs, and benefits..."></textarea>
                                </div>

                                <div class="prd-info-box mt-3">
                                    <i class="bi bi-lightbulb-fill"></i>
                                    <div><strong>Pro tip:</strong> Include grade level, subject, and key features to help customers choose the right product.</div>
                                </div>
                            </div>

                            <!-- Variants & Pricing -->
                            <div class="section-card">
                                <div class="section-title">
                                    <span><i class="bi bi-grid-3x3 me-2"></i>Variants &amp; Pricing</span>
                                    <button type="button" class="prd-btn-variant" onclick="addVariantRow()">
                                        <i class="bi bi-plus-circle"></i> Add Variant
                                    </button>
                                </div>

                                <div id="variantContainer">
                                    <div class="prd-empty-state" id="noVariantMsg">
                                        <div class="prd-empty-icon"><i class="bi bi-inbox"></i></div>
                                        <div class="prd-empty-title">No variants added yet</div>
                                        <div class="prd-empty-text">Click "Add Variant" to create options like size, color, or binding type</div>
                                    </div>
                                </div>
                            </div>

                        </div>

                        <!-- ===== RIGHT COLUMN ===== -->
                        <div class="col-lg-4">

                            <!-- Product Media -->
                            <div class="section-card">
                                <div class="section-title">
                                    <span><i class="bi bi-images me-2"></i>Product Media</span>
                                </div>

                                <div id="imagePreviewContainer" class="prd-img-grid mb-3"></div>

                                <div class="prd-upload-area">
                                    <input type="file" id="productImages" accept="image/*" multiple onchange="handleImageUpload(this)">
                                    <div class="prd-upload-icon"><i class="bi bi-cloud-upload"></i></div>
                                    <div class="prd-upload-title">Upload Product Images</div>
                                    <div class="prd-upload-hint">
                                        Click to browse or drag &amp; drop<br>
                                        <span>Max 5 images · PNG, JPG up to 10MB</span>
                                    </div>
                                </div>

                                <div class="prd-info-box mt-3">
                                    <i class="bi bi-info-circle"></i>
                                    <div>First image will be used as the cover photo</div>
                                </div>
                            </div>

                            <!-- Product Details -->
                            <div class="section-card">
                                <div class="section-title">
                                    <span><i class="bi bi-list-check me-2"></i>Product Details</span>
                                </div>

                                <div class="prd-field mb-3">
                                    <label class="prd-label">
                                        <i class="bi bi-collection"></i> Categories <span class="prd-req">*</span>
                                        <small class="prd-label-hint">Hold Ctrl for multiple</small>
                                    </label>
                                    <select class="prd-multiselect" id="productCategory" name="productCategory[]" multiple required>
                                        <option value="" disabled>Loading categories...</option>
                                    </select>
                                </div>

                                <div class="prd-field mb-3">
                                    <label class="prd-label">
                                        <i class="bi bi-upc"></i> SKU
                                    </label>
                                    <div class="prd-input-group">
                                        <input type="text" class="prd-input prd-input--mono" id="productSKU"
                                            readonly placeholder="Auto-generated">
                                        <button class="prd-input-btn" type="button" onclick="generateSKU()" title="Regenerate SKU">
                                            <i class="bi bi-arrow-clockwise"></i>
                                        </button>
                                    </div>
                                    <span class="prd-field-hint">Auto-generated on save</span>
                                </div>

                                <div class="prd-field mb-3" id="basePriceContainer">
                                    <label class="prd-label">
                                        <i class="bi bi-currency-peso"></i> Base Price (₱)
                                    </label>
                                    <div class="prd-input-group">
                                        <span class="prd-input-prefix">₱</span>
                                        <input type="number" class="prd-input prd-input--prefixed" id="basePrice"
                                            step="0.01" placeholder="0.00" min="0">
                                    </div>
                                    <span class="prd-field-hint">Leave empty if using variants only</span>
                                </div>

                                <div class="prd-field mb-0">
                                    <label class="prd-label">Product Status</label>
                                    <label class="prd-status-toggle">
                                        <input type="checkbox" id="productStatus" role="switch" checked
                                            onchange="document.getElementById('statusLabel').textContent = this.checked ? 'Active' : 'Inactive'">
                                        <span class="prd-toggle-track"></span>
                                        <span class="prd-toggle-label" id="statusLabel">Active</span>
                                    </label>
                                </div>

                            </div>

                        </div>
                    </div>
                </form>
            </div>

            <!-- Modal Footer -->
            <div class="modal-footer">
                <button type="button" class="prd-btn-discard" data-bs-dismiss="modal">
                    <i class="bi bi-x-circle me-1"></i> Discard
                </button>
                <button type="button" class="prd-btn-save" onclick="saveProduct()">
                    <i class="bi bi-check-circle-fill me-1"></i> Save Product
                </button>
            </div>

        </div>
    </div>
</div>

<div class="modal fade" id="addCategoryModal" tabindex="-1" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered" style="max-width:420px;">
        <div class="modal-content st-modal">
            <div class="st-modal-head">
                <h5 class="st-modal-title"><i class="bi bi-tag me-2"></i>Add New Category</h5>
                <button type="button" class="st-modal-close" data-bs-dismiss="modal"><i class="bi bi-x-lg"></i></button>
            </div>
            <form id="addCategoryForm">
                <div class="st-modal-body">
                    <div class="st-field">
                        <label class="st-label">Category Name</label>
                        <input type="text" class="st-input" name="category_name" placeholder="e.g. Gadgets" required>
                    </div>
                    <div class="st-field">
                        <label class="st-label">Category Icon</label>
                        <div class="prd-icon-group">
                            <span class="prd-icon-preview"><i id="iconPreview" class="bi bi-collection"></i></span>
                            <select class="st-select" name="category_icon" id="iconSelector" onchange="updateIconPreview()" style="border-radius:0 4px 4px 0;border-left:none;">
                                <option value="bi-collection">Loading Icons...</option>
                            </select>
                        </div>
                    </div>
                    <div class="st-field">
                        <label class="st-label">Description</label>
                        <textarea class="st-input st-textarea" name="description" rows="2"></textarea>
                    </div>
                    <div class="st-field">
                        <label class="st-label d-block">Status</label>
                        <label class="st-toggle" style="margin-top:0.4rem;">
                            <input type="checkbox" name="status" id="catStatus" checked>
                            <span class="st-toggle-track"></span>
                        </label>
                        <span class="st-hint ms-2">Active</span>
                    </div>
                </div>
                <div class="st-modal-foot">
                    <button type="button" class="st-btn st-btn-ghost" data-bs-dismiss="modal">Cancel</button>
                    <button type="submit" class="st-btn st-btn-primary">Save Category</button>
                </div>
            </form>
        </div>
    </div>
</div>


<!-- TOAST -->
<div class="toast-container position-fixed bottom-0 end-0 p-3">
    <div id="liveToast" class="toast overflow-hidden border-0" role="alert" aria-live="assertive" aria-atomic="true" data-bs-delay="5000">
        <div class="d-flex">
            <div class="toast-body" id="toastMessage">
            </div>
            <button type="button" class="btn-close btn-close-white me-2 m-auto" data-bs-dismiss="toast" aria-label="Close"></button>
        </div>
        <div class="toast-progress-container" style="height: 4px; background: rgba(0,0,0,0.1);">
            <div id="toastProgressBar" style="height: 100%; width: 100%; background: rgba(255,255,255,0.7); transition: width 5s linear;"></div>
        </div>
    </div>
</div>
<?php
include 'includes/footer.php';
?>