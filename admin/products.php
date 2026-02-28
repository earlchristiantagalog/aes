<?php
include 'includes/header.php';
?>
<!-- Products Content -->
<div class="container-fluid p-4">
    <!-- Page Header -->
    <div class="d-flex justify-content-between align-items-center mb-4">
        <div>
            <h2 class="mb-1">Products</h2>
            <p class="text-muted mb-0">Manage your product inventory</p>
        </div>
        <div>
            <button class="btn btn-outline-primary" data-bs-toggle="modal" data-bs-target="#addCategoryModal">
                <i class="bi bi-tag me-2"></i>Add Category
            </button>
            <button class="btn btn-primary" data-bs-toggle="modal" data-bs-target="#addProductModal">
                <i class="bi bi-plus-circle me-2"></i>Add Product
            </button>
        </div>
    </div>

    <!-- Statistics Cards -->
    <div class="row g-3 mb-4">
        <div class="col-md-3">
            <div class="card border-0 shadow-sm">
                <div class="card-body">
                    <div class="d-flex justify-content-between align-items-center">
                        <div>
                            <p class="text-muted mb-1 small">Total Products</p>
                            <h4 class="mb-0" id="stat-total">0</h4>
                        </div>
                        <div class="bg-success bg-opacity-10 p-3 rounded">
                            <i class="bi bi-box-seam text-primary fs-4"></i>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <div class="col-md-3">
            <div class="card border-0 shadow-sm">
                <div class="card-body">
                    <div class="d-flex justify-content-between align-items-center">
                        <div>
                            <p class="text-muted mb-1 small">In Stock</p>
                            <h4 class="mb-0" id="stat-in-stock">0</h4>
                        </div>
                        <div class="bg-success bg-opacity-10 p-3 rounded">
                            <i class="bi bi-check-circle text-success fs-4"></i>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <div class="col-md-3">
            <div class="card border-0 shadow-sm">
                <div class="card-body">
                    <div class="d-flex justify-content-between align-items-center">
                        <div>
                            <p class="text-muted mb-1 small">Low Stock</p>
                            <h4 class="mb-0" id="stat-low-stock">0</h4>
                        </div>
                        <div class="bg-warning bg-opacity-10 p-3 rounded">
                            <i class="bi bi-exclamation-triangle text-warning fs-4"></i>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <div class="col-md-3">
            <div class="card border-0 shadow-sm">
                <div class="card-body">
                    <div class="d-flex justify-content-between align-items-center">
                        <div>
                            <p class="text-muted mb-1 small">Out of Stock</p>
                            <h4 class="mb-0" id="stat-out-of-stock">0</h4>
                        </div>
                        <div class="bg-danger bg-opacity-10 p-3 rounded">
                            <i class="bi bi-x-circle text-danger fs-4"></i>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Filters and Actions -->
    <div class="card border-0 shadow-sm mb-4">
        <div class="card-body">
            <div class="row g-3">
                <div class="col-md-3">
                    <input type="text" class="form-control" placeholder="Search products..." id="searchProduct">
                </div>
                <div class="col-md-2">
                    <select class="form-select" id="categoryFilter">
                        <option value="">All Categories</option>
                        <option value="electronics">Electronics</option>
                        <option value="clothing">Clothing</option>
                        <option value="food">Food & Beverage</option>
                        <option value="books">Books</option>
                        <option value="home">Home & Garden</option>
                    </select>
                </div>
                <div class="col-md-2">
                    <select class="form-select" id="stockFilter">
                        <option value="">All Stock Status</option>
                        <option value="in-stock">In Stock</option>
                        <option value="low-stock">Low Stock</option>
                        <option value="out-of-stock">Out of Stock</option>
                    </select>
                </div>
                <div class="col-md-2">
                    <select class="form-select" id="statusFilter">
                        <option value="">All Status</option>
                        <option value="active">Active</option>
                        <option value="inactive">Inactive</option>
                    </select>
                </div>
                <div class="col-md-3 text-end">
                    <button class="btn btn-outline-secondary me-2" onclick="loadProducts(1)">
                        <i class="bi bi-funnel me-1"></i>Filter
                    </button>
                    <button class="btn btn-outline-secondary" onclick="resetFilters()">
                        <i class="bi bi-arrow-clockwise me-1"></i>Reset
                    </button>
                </div>
            </div>
        </div>
    </div>

    <!-- Products Table -->
    <div class="card border-0 shadow-sm">
        <div class="card-body p-0">
            <div class="table-responsive">
                <table class="table table-hover align-middle mb-0">
                    <thead class="bg-light">
                        <tr>
                            <th class="border-0 ps-4">
                                <input type="checkbox" class="form-check-input">
                            </th>
                            <th class="border-0">Product</th>
                            <th class="border-0">SKU</th>
                            <th class="border-0">Category</th>
                            <th class="border-0">Price</th>
                            <th class="border-0">Stock</th>
                            <th class="border-0">Status</th>
                            <th class="border-0 text-end pe-4">Actions</th>
                        </tr>
                    </thead>
                    <tbody>

                    </tbody>
                </table>
            </div>
        </div>

        <!-- Pagination -->
        <div class="card-footer bg-white border-top">
            <div class="d-flex justify-content-between align-items-center">
                <div class="text-muted small">
                    Showing 1 to 6 of 1,234 products
                </div>
                <nav>
                    <ul class="pagination pagination-sm mb-0">
                        <li class="page-item disabled">
                            <a class="page-link" href="#">Previous</a>
                        </li>
                        <li class="page-item active"><a class="page-link" href="#">1</a></li>
                        <li class="page-item"><a class="page-link" href="#">2</a></li>
                        <li class="page-item"><a class="page-link" href="#">3</a></li>
                        <li class="page-item"><a class="page-link" href="#">4</a></li>
                        <li class="page-item"><a class="page-link" href="#">5</a></li>
                        <li class="page-item">
                            <a class="page-link" href="#">Next</a>
                        </li>
                    </ul>
                </nav>
            </div>
        </div>
    </div>
</div>
</main>

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

                        <!-- Left Column -->
                        <div class="col-lg-8">

                            <!-- General Information -->
                            <div class="section-card">
                                <div class="section-title">
                                    <span><i class="bi bi-info-circle me-2"></i>General Information</span>
                                </div>

                                <div class="mb-3">
                                    <label class="form-label">
                                        <i class="bi bi-tag"></i>
                                        Product Name *
                                    </label>
                                    <input type="text" class="form-control form-control-lg" id="productName"
                                        placeholder="e.g. Premium Wireless Headphones" required>
                                </div>

                                <div class="mb-3">
                                    <label class="form-label">
                                        <i class="bi bi-chat-left-text"></i>
                                        Small Description
                                    </label>
                                    <input type="text" class="form-control" id="smallDesc"
                                        placeholder="Brief catchphrase for product cards (e.g. 'Crystal clear sound quality')">
                                </div>

                                <div class="mb-0">
                                    <label class="form-label">
                                        <i class="bi bi-align-left"></i>
                                        Full Description
                                    </label>
                                    <textarea class="form-control description" id="productDescription" rows="5"
                                        placeholder="Detailed product description with features and specifications..."></textarea>
                                </div>

                                <div class="info-box">
                                    <i class="bi bi-lightbulb"></i>
                                    <div class="info-box-text">
                                        <strong>Pro tip:</strong> Include key features, benefits, and specifications to help customers make informed decisions.
                                    </div>
                                </div>
                            </div>

                            <!-- Variants & Pricing -->
                            <div class="section-card">
                                <div class="section-title">
                                    <span><i class="bi bi-grid-3x3 me-2"></i>Variants & Pricing</span>
                                    <button type="button" class="btn-add-variant" onclick="addVariantRow()">
                                        <i class="bi bi-plus-circle"></i>
                                        Add Variant
                                    </button>
                                </div>

                                <div id="variantContainer">
                                    <div class="empty-state" id="noVariantMsg">
                                        <div class="empty-state-icon">
                                            <i class="bi bi-inbox"></i>
                                        </div>
                                        <div class="empty-state-title">No variants added yet</div>
                                        <div class="empty-state-text">
                                            Click "Add Variant" to create product variations like size, color, or material options
                                        </div>
                                    </div>
                                </div>
                            </div>

                        </div>

                        <!-- Right Column -->
                        <div class="col-lg-4">

                            <!-- Product Media -->
                            <div class="section-card">
                                <div class="section-title">
                                    <span><i class="bi bi-images me-2"></i>Product Media</span>
                                </div>

                                <div id="imagePreviewContainer" class="image-preview-container mb-3">
                                    <!-- Preview images will appear here -->
                                </div>

                                <div class="image-upload-area">
                                    <input type="file" id="productImages" accept="image/*" multiple onchange="handleImageUpload(this)">
                                    <div class="upload-icon">
                                        <i class="bi bi-cloud-upload"></i>
                                    </div>
                                    <div style="font-weight: 600; color: #1a202c; margin-bottom: 0.25rem;">
                                        Upload Product Images
                                    </div>
                                    <div style="font-size: 0.85rem; color: #718096;">
                                        Click to browse or drag & drop<br>
                                        <span style="font-size: 0.8rem;">Max 5 images • PNG, JPG up to 10MB</span>
                                    </div>
                                </div>

                                <div class="info-box">
                                    <i class="bi bi-info-circle"></i>
                                    <div class="info-box-text">
                                        First image will be the cover photo
                                    </div>
                                </div>
                            </div>

                            <!-- Product Details -->
                            <div class="section-card">
                                <div class="section-title">
                                    <span><i class="bi bi-list-check me-2"></i>Product Details</span>
                                </div>

                                <div class="mb-3">
                                    <label class="form-label">
                                        <i class="bi bi-collection"></i>
                                        Categories * <small class="text-muted">(Hold Ctrl to select multiple)</small>
                                    </label>
                                    <select class="form-select" id="productCategory" name="productCategory[]" multiple required style="min-height: 120px;">
                                        <option value="" disabled>Loading categories...</option>
                                    </select>
                                </div>

                                <div class="mb-3">
                                    <label class="form-label">
                                        <i class="bi bi-upc"></i>
                                        SKU (Stock Keeping Unit)
                                    </label>
                                    <div class="input-group">
                                        <input type="text" class="form-control" id="productSKU" readonly
                                            placeholder="Auto-generated">
                                        <button class="btn" type="button" onclick="generateSKU()">
                                            <i class="bi bi-arrow-clockwise"></i>
                                        </button>
                                    </div>
                                    <small style="color: #718096; font-size: 0.8rem; display: block; margin-top: 0.5rem;">
                                        Auto-generated on save
                                    </small>
                                </div>

                                <div class="mb-3" id="basePriceContainer">
                                    <label class="form-label">
                                        <i class="bi bi-currency-peso"></i>
                                        Base Price (₱)
                                    </label>
                                    <input type="number" class="form-control" id="basePrice" step="0.01"
                                        placeholder="0.00" min="0">
                                    <small style="color: #718096; font-size: 0.8rem; display: block; margin-top: 0.5rem;">
                                        Leave empty if using variants only
                                    </small>
                                </div>

                                <div class="mb-0">
                                    <label class="form-label d-block text-muted fw-bold small">Product Status</label>
                                    <div class="form-check form-switch form-check-inline mt-2">
                                        <input class="form-check-input shadow-none" type="checkbox" role="switch" id="productStatus" checked style="width: 2.5rem; height: 1.25rem; cursor: pointer;">
                                        <label class="form-check-label ms-2 cursor-pointer" for="productStatus" id="statusLabel">Active</label>
                                    </div>
                                </div>
                            </div>

                        </div>
                    </div>
                </form>
            </div>

            <!-- Modal Footer -->
            <div class="modal-footer">
                <button type="button" class="btn-discard" data-bs-dismiss="modal">
                    Discard Changes
                </button>
                <button type="button" class="btn-save-product" onclick="saveProduct()">
                    <i class="bi bi-check-circle-fill"></i>
                    Save Product
                </button>
            </div>

        </div>
    </div>
</div>

<!-- Add Categoy Modal -->
<div class="modal fade" id="addCategoryModal" tabindex="-1" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered">
        <div class="modal-content border-0 shadow-lg">
            <div class="modal-header bg-light">
                <h5 class="modal-title fw-bold">📁 Add New Category</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <form id="addCategoryForm">
                <div class="modal-body p-4">
                    <div class="mb-3">
                        <label class="form-label fw-bold small">Category Name</label>
                        <input type="text" class="form-control" name="category_name" placeholder="e.g., Gadgets">
                    </div>

                    <div class="mb-3">
                        <label class="form-label fw-bold small">Category Icon</label>
                        <div class="input-group shadow-sm">
                            <span class="input-group-text bg-white border-end-0">
                                <i id="iconPreview" class="bi bi-collection"></i>
                            </span>

                            <select class="form-select border-start-0" name="category_icon" id="iconSelector" onchange="updateIconPreview()">
                                <option value="bi-collection">Loading Icons...</option>
                            </select>
                        </div>
                    </div>

                    <div class="mb-3">
                        <label class="form-label fw-bold small">Description</label>
                        <textarea class="form-control" name="description" rows="2"></textarea>
                    </div>

                    <div class="mb-0">
                        <label class="form-label fw-bold small d-block">Status</label>
                        <div class="form-check form-switch mt-2">
                            <input class="form-check-input" type="checkbox" name="status" id="catStatus" checked>
                            <label class="form-check-label" for="catStatus">Active</label>
                        </div>
                    </div>
                </div>
                <div class="modal-footer border-0 bg-light">
                    <button type="button" class="btn btn-outline-secondary" data-bs-dismiss="modal">Cancel</button>
                    <button type="submit" class="btn btn-primary px-4">Save Category</button>
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