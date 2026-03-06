/* -------------------------------------------------------
   ORDER MANAGEMENT LOGIC
------------------------------------------------------- */

// 1. Search and Filters (Page Refresh approach)
function changePage(pageNum) {
    const url = new URL(window.location.href);
    url.searchParams.set('page', pageNum);
    window.location.href = url.href;
}

// Update applyFilters to reset page to 1 when a new search is made
function applyFilters() {
    const search = document.getElementById('searchOrder').value;
    const status = document.getElementById('statusFilter').value;
    const payment = document.getElementById('paymentFilter').value;
    const date = document.getElementById('dateFilter').value;

    const url = new URL(window.location.href);
    url.searchParams.set('page', 1); // Reset to page 1
    if (search) url.searchParams.set('search', search); else url.searchParams.delete('search');
    if (status) url.searchParams.set('status', status); else url.searchParams.delete('status');
    if (payment) url.searchParams.set('payment', payment); else url.searchParams.delete('payment');
    if (date) url.searchParams.set('date', date); else url.searchParams.delete('date');

    window.location.href = url.href;
}

function resetFilters() {
    window.location.href = 'orders.php'; // Redirect to clean page
}

// 2. View Order Details
function viewOrderDetails(orderId) {
    const modalEl = document.getElementById('orderDetailModal');
    const modal = new bootstrap.Modal(modalEl);

    document.getElementById('orderDetailBody').innerHTML =
        '<div class="text-center p-5"><div class="spinner-border text-primary"></div><p class="mt-2">Loading...</p></div>';
    modal.show();

    fetch(`/aes/admin/order/get_order_details.php?id=${orderId}`)
        .then(res => res.text())
        .then(html => {
            document.getElementById('orderDetailBody').innerHTML = html;
            document.getElementById('modalOrderId').innerText = 'Order #' + orderId;
        })
        .catch(err => {
            document.getElementById('orderDetailBody').innerHTML = '<p class="text-danger p-5 text-center">Failed to load details.</p>';
        });
}

// 3. Accept Order Action
function acceptOrder(orderId) {
    if (!confirm('Accept this order and notify the customer?')) return;

    const btn = document.getElementById(`btn-accept-${orderId}`);
    const originalContent = btn.innerHTML;

    btn.disabled = true;
    btn.innerHTML = '<span class="spinner-border spinner-border-sm"></span>';

    fetch('/aes/admin/order/process_order_action.php', {
        method: 'POST',
        headers: { 'Content-Type': 'application/x-www-form-urlencoded' },
        body: `action=accept&order_id=${orderId}`
    })
        .then(res => res.json())
        .then(data => {
            if (data.success) {
                showToast('Order accepted successfully!', 'success');
                setTimeout(() => location.reload(), 1500);
            } else {
                showToast(data.message || 'Error processing request', 'error');
                btn.disabled = false;
                btn.innerHTML = originalContent;
            }
        });
}

// 4. Toast with Auto-Close Progress Bar
function showToast(msg, type = 'success') {
    const toastEl = document.getElementById('liveToast');
    const toastMsg = document.getElementById('toastMessage');
    const icon = document.getElementById('toastIcon');
    const bar = document.getElementById('toastProgressBar');

    // Set Message and Type
    toastMsg.textContent = msg;

    if (type === 'error') {
        toastEl.classList.add('bg-danger', 'text-white');
        icon.innerHTML = '<i class="bi bi-x-circle-fill"></i>';
    } else {
        toastEl.classList.remove('bg-danger', 'text-white');
        icon.innerHTML = '<i class="bi bi-check-circle-fill"></i>';
    }

    const toast = new bootstrap.Toast(toastEl, { delay: 4000 });

    // Reset and Start Progress Bar
    bar.style.transition = 'none';
    bar.style.width = '100%';

    toast.show();

    // Small timeout to allow transition to trigger
    setTimeout(() => {
        bar.style.transition = 'width 4s linear';
        bar.style.width = '0%';
    }, 10);
}

function printAirWaybill(orderId) {
    const printWindow = window.open(`/aes/admin/order/print_awb.php?id=${orderId}`, '_blank', 'width=400,height=600');
    printWindow.focus();
}

/* -------------------------------------------------------
   EVENT LISTENERS
------------------------------------------------------- */

// Tab switching (updates URL)
document.querySelectorAll('.adm-tab').forEach(tab => {
    tab.addEventListener('click', () => {
        const status = tab.dataset.status;
        let url = new URL(window.location.href);
        if (status) url.searchParams.set('status', status); else url.searchParams.delete('status');
        window.location.href = url.href;
    });
});

// Select all checkbox
const selectAll = document.getElementById('selectAll');
if (selectAll) {
    selectAll.addEventListener('change', function () {
        document.querySelectorAll('#ordersTableBody .adm-checkbox').forEach(cb => cb.checked = this.checked);
    });
}

// Attach Search "Enter" Key
document.getElementById('searchOrder').addEventListener('keypress', (e) => {
    if (e.key === 'Enter') applyFilters();
});