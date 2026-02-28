// Initialize tooltips
document.addEventListener('DOMContentLoaded', function () {
    var tooltipTriggerList = [].slice.call(document.querySelectorAll('[data-bs-toggle="tooltip"]'));
    var tooltipList = tooltipTriggerList.map(function (tooltipTriggerEl) {
        return new bootstrap.Tooltip(tooltipTriggerEl);
    });
});

// Search functionality
document.getElementById('searchProduct')?.addEventListener('input', function (e) {
    const searchTerm = e.target.value.toLowerCase();
    const rows = document.querySelectorAll('tbody tr');

    rows.forEach(row => {
        const text = row.textContent.toLowerCase();
        row.style.display = text.includes(searchTerm) ? '' : 'none';
    });
});


/***********  PRODUCTS MODAL  *********/
let uploadedImages = [];

// --- 1. Automatic Triggers (Bootstrap Modal Events) ---

const addProductModal = document.getElementById('addProductModal');

// Triggered when the "Add Product" button is clicked (Modal opens)
addProductModal.addEventListener('show.bs.modal', function () {
    // Automatically generate SKU only if it's currently empty
    if (!document.getElementById('productSKU').value) {
        generateSKU();
    }
});

// Triggered when the modal is closed (Cleanup)
addProductModal.addEventListener('hidden.bs.modal', function () {
    document.getElementById('addProductForm').reset();
    // --- NEW: Reset Summernote ---
    $('#productDescription').summernote('reset');
    document.getElementById('productSKU').value = '';
    document.getElementById('imagePreviewContainer').innerHTML = '';
    uploadedImages = [];

    // Reset Variants to default state
    document.getElementById('variantContainer').innerHTML = `
        <div class="text-center py-3 text-muted border rounded mb-3" id="noVariantMsg">
            No variants added. Standard pricing will apply.
        </div>`;
    document.getElementById('basePriceContainer').style.display = 'block';
});


// --- 2. Functional Logic ---

// Auto-generate SKU on modal open
document.getElementById('addProductModal').addEventListener('show.bs.modal', function () {
    const chars = 'ABCDEFGHIJKLMNOPQRSTUVWXYZ0123456789';
    let result = '';
    for (let i = 0; i < 10; i++) {
        result += chars.charAt(Math.floor(Math.random() * chars.length));
    }
    document.getElementById('productSKU').value = result;
});

// --- Updated Variant Row (Added Stock Field) ---
function addVariantRow() {
    document.getElementById('noVariantMsg').style.display = 'none';
    document.getElementById('basePriceContainer').style.display = 'none';
    const container = document.getElementById('variantContainer');
    const rowId = Date.now();

    const html = `
        <div class="row g-2 mb-2 align-items-end border-bottom pb-2 variant-row" id="row-${rowId}">
            <div class="col-3">
                <label class="small text-muted">Type (e.g. Size)</label>
                <input type="text" class="form-control form-control-sm variant-type" placeholder="Size">
            </div>
            <div class="col-3">
                <label class="small text-muted">Value (e.g. XL)</label>
                <input type="text" class="form-control form-control-sm variant-value" placeholder="XL">
            </div>
            <div class="col-3">
                <label class="small text-muted">Price (₱)</label>
                <input type="number" class="form-control form-control-sm variant-price" step="0.01" placeholder="0.00">
            </div>
            <div class="col-2">
                <label class="small text-muted">Stock</label>
                <input type="number" class="form-control form-control-sm variant-stock" placeholder="0" min="0">
            </div>
            <div class="col-1">
                <button type="button" class="btn btn-sm btn-outline-danger w-100" onclick="removeVariantRow('${rowId}')">×</button>
            </div>
        </div>`;
    container.insertAdjacentHTML('beforeend', html);
}

// Fixed the remove function logic
function removeVariantRow(id) {
    const row = document.getElementById(`row-${id}`);
    if (row) row.remove();

    const container = document.getElementById('variantContainer');
    // Check if any variant rows remain
    if (container.querySelectorAll('.variant-row').length === 0) {
        document.getElementById('noVariantMsg').style.display = 'block';
        document.getElementById('basePriceContainer').style.display = 'block';
    }
}

function handleImageUpload(input) {
    const container = document.getElementById('imagePreviewContainer');
    const files = Array.from(input.files);

    if (uploadedImages.length + files.length > 5) {
        alert("You can only upload up to 5 images.");
        input.value = ""; // Clear the input
        return;
    }

    files.forEach(file => {
        const reader = new FileReader();
        reader.onload = (e) => {
            uploadedImages.push(e.target.result);
            const imgHtml = `
                <div class="position-relative" style="width: 60px; height: 60px;">
                    <img src="${e.target.result}" class="img-thumbnail w-100 h-100" style="object-fit: cover;">
                    <span class="position-absolute top-0 start-100 translate-middle badge rounded-pill bg-danger" 
                          style="cursor:pointer" onclick="removePreviewImage(this, '${e.target.result}')">×</span>
                </div>`;
            container.insertAdjacentHTML('beforeend', imgHtml);
        };
        reader.readAsDataURL(file);
    });
}

function removePreviewImage(element, dataUrl) {
    element.parentElement.remove();
    uploadedImages = uploadedImages.filter(img => img !== dataUrl);
}

async function saveProduct() {
    // 1. Setup UI feedback
    const saveBtn = document.querySelector('[onclick="saveProduct()"]');
    const originalBtnText = saveBtn.innerHTML;

    // Helper to generate exactly 20 random digits
    const generateProductNumber = () => {
        let result = '';
        for (let i = 0; i < 20; i++) {
            result += Math.floor(Math.random() * 10);
        }
        return result;
    };

    // --- Updated: Collect Multiple Category NAMES instead of IDs ---
    const categorySelect = document.getElementById('productCategory');

    // This map gets the visible text (e.g., "Printing Services") instead of the value (e.g., "1")
    const selectedCategories = Array.from(categorySelect.selectedOptions)
        .map(option => option.text); // Use .text to get the name
    const productData = {
        name: document.getElementById('productName').value,
        sku: document.getElementById('productSKU').value,
        productNumber: generateProductNumber(),
        smallDesc: document.getElementById('smallDesc').value,

        // --- UPDATED: Get HTML content from Summernote ---
        description: $('#productDescription').summernote('code'),

        categories: selectedCategories,
        status: document.getElementById('productStatus').checked ? 'active' : 'inactive',
        basePrice: document.getElementById('basePrice') ? document.getElementById('basePrice').value : 0,
        images: uploadedImages,
        variants: []
    };

    // 2. Loop through variants
    const rows = document.querySelectorAll('#variantContainer .variant-row');
    rows.forEach(row => {
        const typeEl = row.querySelector('.variant-type');
        const valEl = row.querySelector('.variant-value');
        const priceEl = row.querySelector('.variant-price');
        const stockEl = row.querySelector('.variant-stock');

        if (typeEl && valEl && typeEl.value && valEl.value) {
            productData.variants.push({
                type: typeEl.value,
                value: valEl.value,
                price: priceEl.value || 0,
                stock: stockEl.value || 0
            });
        }
    });

    // 3. Enhanced Validation
    if (!productData.name || !productData.sku) {
        showToast("Product name and SKU are required", "error");
        return;
    }

    if (productData.categories.length === 0) {
        showToast("Please select at least one category", "error");
        return;
    }

    // 4. AJAX Request
    try {
        saveBtn.disabled = true;
        saveBtn.innerHTML = `<span class="spinner-border spinner-border-sm"></span> Saving...`;

        const response = await fetch('/aes/admin/products/save_products.php', {
            method: 'POST',
            headers: { 'Content-Type': 'application/json' },
            body: JSON.stringify(productData)
        });

        // Safe JSON parsing
        const text = await response.text();
        let result;
        try {
            result = JSON.parse(text);
        } catch (e) {
            console.error("Server Response:", text);
            throw new Error("Server returned an invalid response.");
        }

        if (response.ok && result.success) {
            showToast("Product saved successfully!", "success");

            const modalElement = document.getElementById('addProductModal');
            const modalInstance = bootstrap.Modal.getInstance(modalElement);
            if (modalInstance) modalInstance.hide();

            // Refresh your product table if the function exists
            if (typeof loadProducts === 'function') loadProducts(1);

        } else {
            throw new Error(result.message || "Failed to save product.");
        }

    } catch (error) {
        console.error("AJAX Error:", error);
        showToast(error.message, "error");
    } finally {
        saveBtn.disabled = false;
        saveBtn.innerHTML = originalBtnText;
    }
}

// Inside addProductModal.addEventListener('hidden.bs.modal'...)
document.getElementById('productStatus').checked = true; // Default to Active
document.getElementById('statusLabel').innerText = "Active";

document.getElementById('productStatus').addEventListener('change', function () {
    const label = document.getElementById('statusLabel');
    if (this.checked) {
        label.innerText = "Active";
        label.classList.remove('text-muted');
        label.classList.add('text-primary');
    } else {
        label.innerText = "Inactive";
        label.classList.remove('text-primary');
        label.classList.add('text-muted');
    }
});


// Toast
function showToast(message, type = 'success') {
    const toastEl = document.getElementById('liveToast');
    const toastMessage = document.getElementById('toastMessage');
    const progressBar = document.getElementById('toastProgressBar');

    // 1. Setup Styling
    toastEl.classList.remove('bg-success', 'bg-danger', 'text-white');
    if (type === 'success') {
        toastEl.classList.add('bg-success', 'text-white');
    } else {
        toastEl.classList.add('bg-danger', 'text-white');
    }

    toastMessage.textContent = message;

    // 2. Reset Progress Bar
    // We disable transitions momentarily to "snap" it back to 100%
    progressBar.style.transition = 'none';
    progressBar.style.width = '100%';

    // 3. Initialize Bootstrap Toast
    const toast = new bootstrap.Toast(toastEl, { autohide: true, delay: 5000 });

    // 4. Start Animation
    // Use requestAnimationFrame to ensure the "reset" above is rendered before starting the shrink
    requestAnimationFrame(() => {
        requestAnimationFrame(() => {
            progressBar.style.transition = 'width 5s linear';
            progressBar.style.width = '0%';
        });
    });

    toast.show();
}


// Fetch Products
let currentPage = 1;

// Initial load with 3-second delay
document.addEventListener('DOMContentLoaded', () => {
    setTimeout(() => {
        loadProducts(1);
    }, 2000);
});
// Function to get filters and load products
async function loadProducts(page = 1) {
    currentPage = page;

    // Get filter values
    const search = document.getElementById('searchProduct').value;
    const category = document.getElementById('categoryFilter').value;
    const stock = document.getElementById('stockFilter').value;
    const status = document.getElementById('statusFilter').value;

    const tableBody = document.querySelector('tbody');

    try {
        // Construct URL with query parameters
        const url = `/aes/admin/products/fetch_products.php?page=${page}&search=${encodeURIComponent(search)}&category=${category}&stock=${stock}&status=${status}`;

        const response = await fetch(url);
        const result = await response.json();

        if (result.success) {
            // Update Stats
            if (result.stats) {
                document.getElementById('stat-total').innerText = Number(result.stats.total_products).toLocaleString();
                document.getElementById('stat-in-stock').innerText = Number(result.stats.in_stock || 0).toLocaleString();
                document.getElementById('stat-low-stock').innerText = Number(result.stats.low_stock || 0).toLocaleString();
                document.getElementById('stat-out-of-stock').innerText = Number(result.stats.out_of_stock || 0).toLocaleString();
            }

            tableBody.innerHTML = '';

            if (result.data.length === 0) {
                tableBody.innerHTML = '<tr><td colspan="8" class="text-center py-4">No products found.</td></tr>';
            }

            result.data.forEach(product => {
                const stockVal = product.total_stock || 0;
                const statusClass = product.status === 'active' ? 'bg-success' : 'bg-secondary';
                const stockClass = stockVal <= 0 ? 'bg-danger' : (stockVal < 10 ? 'bg-warning' : 'bg-success');
                const imagePath = product.cover_image ? `uploads/${product.cover_image}` : 'https://via.placeholder.com/50';

                const row = `
                    <tr>
                        <td class="ps-4"><input type="checkbox" class="form-check-input"></td>
                        <td>
                            <div class="d-flex align-items-center">
                                <img src="/aes/admin/products/${imagePath}" class="rounded me-3" width="50" height="50" style="object-fit: cover;">
                                <div>
                                    <h6 class="mb-0">${product.name}</h6>
                                    <small class="text-muted">${product.small_description || ''}</small>
                                </div>
                            </div>
                        </td>
                        <td><span class="badge bg-light text-dark text-uppercase">${product.sku}</span></td>
                        <td class="text-uppercase">${product.category}</td>
                        <td><strong>₱${parseFloat(product.base_price).toLocaleString()}</strong></td>
                        <td><span class="badge ${stockClass}">${stockVal} units</span></td>
                        <td><span class="badge text-uppercase ${statusClass}">${product.status}</span></td>
                        <td class="text-end pe-4">
                            <div class="btn-group btn-group-sm">
                                <button class="btn btn-outline-primary" onclick="editProduct(${product.id})"><i class="bi bi-pencil"></i></button>
                                <button class="btn btn-outline-info" onclick="viewProduct(${product.id})"><i class="bi bi-eye"></i></button>
                                <button class="btn btn-outline-danger" onclick="deleteProduct(${product.id})"><i class="bi bi-trash"></i></button>
                            </div>
                        </td>
                    </tr>`;
                tableBody.insertAdjacentHTML('beforeend', row);
            });

            renderPagination(result.total, result.currentPage, result.limit);
        }
    } catch (error) {
        console.error("Error loading products:", error);
    }
}

// Reset function
function resetFilters() {
    document.getElementById('searchProduct').value = '';
    document.getElementById('categoryFilter').value = '';
    document.getElementById('stockFilter').value = '';
    document.getElementById('statusFilter').value = '';
    loadProducts(1);
}

function renderPagination(total, current, limit) {
    const totalPages = Math.ceil(total / limit);
    const paginationUl = document.querySelector('.pagination');
    const infoText = document.querySelector('.card-footer .text-muted');

    // Update info text
    const start = total === 0 ? 0 : (current - 1) * limit + 1;
    const end = Math.min(current * limit, total);
    infoText.innerText = `Showing ${start} to ${end} of ${total.toLocaleString()} products`;

    // Generate buttons
    let html = `
        <li class="page-item ${current === 1 ? 'disabled' : ''}">
            <a class="page-link" href="#" onclick="event.preventDefault(); loadProducts(${current - 1})">Previous</a>
        </li>`;

    for (let i = 1; i <= totalPages; i++) {
        html += `
            <li class="page-item ${i === current ? 'active' : ''}">
                <a class="page-link" href="#" onclick="event.preventDefault(); loadProducts(${i})">${i}</a>
            </li>`;
    }

    html += `
        <li class="page-item ${current === totalPages || totalPages === 0 ? 'disabled' : ''}">
            <a class="page-link" href="#" onclick="event.preventDefault(); loadProducts(${current + 1})">Next</a>
        </li>`;

    paginationUl.innerHTML = html;
}

// Fetch Categories
async function refreshCategoryDropdown() {
    const categorySelect = document.getElementById('productCategory');

    try {
        // You'll need a simple PHP script that returns all categories
        const response = await fetch('/aes/admin/categories/get_categories.php');
        const result = await response.json();

        if (result.success) {
            categorySelect.innerHTML = ''; // Clear loading message

            result.data.forEach(cat => {
                const option = document.createElement('option');
                option.value = cat.id; // Using ID is better than name
                option.textContent = cat.name;
                categorySelect.appendChild(option);
            });
        }
    } catch (error) {
        console.error("Error fetching categories:", error);
        showToast("Failed to load categories", "error");
    }
}

// Call it when the page loads
document.addEventListener('DOMContentLoaded', refreshCategoryDropdown);