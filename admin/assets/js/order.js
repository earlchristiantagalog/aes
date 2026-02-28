
/* -------------------------------------------------------
MOCK DATA — replace with real AJAX/fetch calls to PHP
------------------------------------------------------- */
const mockOrders = [{
    id: 'ORD-2026-0042',
    date: 'Feb 18, 2026',
    customer: 'Maria Santos',
    email: 'maria@school.edu',
    items: 3,
    total: 1250.00,
    payment: 'paid',
    status: 'delivered'
},
{
    id: 'ORD-2026-0041',
    date: 'Feb 17, 2026',
    customer: 'John Dela Cruz',
    email: 'john@edu.ph',
    items: 7,
    total: 4380.00,
    payment: 'paid',
    status: 'shipped'
},
{
    id: 'ORD-2026-0040',
    date: 'Feb 16, 2026',
    customer: 'Sarah Tan',
    email: 'sarah@school.ph',
    items: 2,
    total: 890.00,
    payment: 'unpaid',
    status: 'pending'
},
{
    id: 'ORD-2026-0039',
    date: 'Feb 15, 2026',
    customer: 'Rodrigo Reyes',
    email: 'r.reyes@edu.ph',
    items: 5,
    total: 3100.00,
    payment: 'paid',
    status: 'processing'
},
{
    id: 'ORD-2026-0038',
    date: 'Feb 14, 2026',
    customer: 'Ana Lim',
    email: 'ana.lim@edu.ph',
    items: 1,
    total: 199.00,
    payment: 'unpaid',
    status: 'cancelled'
},
{
    id: 'ORD-2026-0037',
    date: 'Feb 13, 2026',
    customer: 'Mark Villanueva',
    email: 'mvillanueva@ph.edu',
    items: 4,
    total: 2760.00,
    payment: 'paid',
    status: 'delivered'
},
{
    id: 'ORD-2026-0036',
    date: 'Feb 12, 2026',
    customer: 'Christine Bautista',
    email: 'cb@edu.ph',
    items: 6,
    total: 5100.00,
    payment: 'paid',
    status: 'delivered'
},
{
    id: 'ORD-2026-0035',
    date: 'Feb 11, 2026',
    customer: 'Paolo Cruz',
    email: 'pcruz@school.ph',
    items: 2,
    total: 680.00,
    payment: 'paid',
    status: 'shipped'
},
];

let currentFilter = '';

function getInitials(name) {
    return name.split(' ').map(w => w[0]).join('').toUpperCase().substring(0, 2);
}

function renderOrders(filter) {
    const search = document.getElementById('searchOrder').value.toLowerCase();
    const status = filter !== undefined ? filter : document.getElementById('statusFilter').value;
    const payment = document.getElementById('paymentFilter').value;

    const filtered = mockOrders.filter(o => {
        const matchSearch = !search || o.id.toLowerCase().includes(search) || o.customer.toLowerCase().includes(search) || o.email.toLowerCase().includes(search);
        const matchStatus = !status || o.status === status;
        const matchPayment = !payment || o.payment === payment;
        return matchSearch && matchStatus && matchPayment;
    });

    const tbody = document.getElementById('ordersTableBody');
    const empty = document.getElementById('emptyState');
    document.getElementById('paginationInfo').textContent = `Showing ${filtered.length} order${filtered.length !== 1 ? 's' : ''}`;

    if (filtered.length === 0) {
        tbody.innerHTML = '';
        empty.style.display = '';
        return;
    }
    empty.style.display = 'none';

    tbody.innerHTML = filtered.map(o => `
        <tr>
            <td class="adm-th--check"><input type="checkbox" class="adm-checkbox"></td>
            <td>
                <div class="adm-order-id">${o.id}</div>
                <div class="adm-order-date">${o.date}</div>
            </td>
            <td>
                <div class="adm-customer">
                    <div class="adm-cust-avatar">${getInitials(o.customer)}</div>
                    <div>
                        <div class="adm-cust-name">${o.customer}</div>
                        <div class="adm-cust-email">${o.email}</div>
                    </div>
                </div>
            </td>
            <td>${o.date}</td>
            <td>${o.items} item${o.items > 1 ? 's' : ''}</td>
            <td class="adm-order-total">₱${o.total.toLocaleString(undefined, { minimumFractionDigits: 2 })}</td>
            <td><span class="adm-badge adm-badge--${o.payment}">${o.payment.charAt(0).toUpperCase() + o.payment.slice(1)}</span></td>
            <td><span class="adm-badge adm-badge--${o.status}">${o.status.charAt(0).toUpperCase() + o.status.slice(1)}</span></td>
            <td>
                <div class="adm-row-actions">
                    <button class="adm-action-btn" title="View Order" onclick="viewOrder('${o.id}')">
                        <i class="bi bi-eye"></i>
                    </button>
                    <button class="adm-action-btn" title="Update Status" onclick="openStatusModal('${o.id}', '${o.status}')">
                        <i class="bi bi-arrow-repeat"></i>
                    </button>
                    <button class="adm-action-btn adm-action-btn--danger" title="Cancel Order" onclick="cancelOrder('${o.id}')">
                        <i class="bi bi-x-lg"></i>
                    </button>
                </div>
            </td>
        </tr>
    `).join('');
}

function updateStats() {
    document.getElementById('stat-total').textContent = mockOrders.length;
    document.getElementById('stat-pending').textContent = mockOrders.filter(o => o.status === 'pending').length;
    document.getElementById('stat-delivered').textContent = mockOrders.filter(o => o.status === 'delivered').length;
    document.getElementById('stat-cancelled').textContent = mockOrders.filter(o => o.status === 'cancelled').length;

    // Tab counts
    const tabMap = {
        '': 'all',
        pending: 'pending',
        processing: 'processing',
        shipped: 'shipped',
        delivered: 'delivered',
        cancelled: 'cancelled'
    };
    Object.entries(tabMap).forEach(([status, id]) => {
        const count = status === '' ? mockOrders.length : mockOrders.filter(o => o.status === status).length;
        const el = document.getElementById('tab-' + id);
        if (el) el.textContent = count;
    });
}

function loadOrders() {
    renderOrders(currentFilter);
}

function resetFilters() {
    document.getElementById('searchOrder').value = '';
    document.getElementById('statusFilter').value = '';
    document.getElementById('paymentFilter').value = '';
    document.getElementById('dateFilter').value = '';
    renderOrders('');
}

function viewOrder(id) {
    const order = mockOrders.find(o => o.id === id);
    if (!order) return;
    document.getElementById('modalOrderId').textContent = id;
    document.getElementById('orderDetailBody').innerHTML = `
        <div style="display:grid;grid-template-columns:1fr 1fr;gap:1.5rem;">
            <div>
                <p class="adm-filter-label" style="display:block;margin-bottom:.35rem;">Customer</p>
                <strong>${order.customer}</strong><br>
                <span style="font-size:.8rem;color:var(--text-muted)">${order.email}</span>
            </div>
            <div>
                <p class="adm-filter-label" style="display:block;margin-bottom:.35rem;">Order Date</p>
                <strong>${order.date}</strong>
            </div>
            <div>
                <p class="adm-filter-label" style="display:block;margin-bottom:.35rem;">Status</p>
                <span class="adm-badge adm-badge--${order.status}">${order.status.charAt(0).toUpperCase() + order.status.slice(1)}</span>
            </div>
            <div>
                <p class="adm-filter-label" style="display:block;margin-bottom:.35rem;">Payment</p>
                <span class="adm-badge adm-badge--${order.payment}">${order.payment.charAt(0).toUpperCase() + order.payment.slice(1)}</span>
            </div>
            <div style="grid-column:1/-1">
                <p class="adm-filter-label" style="display:block;margin-bottom:.35rem;">Items</p>
                <p style="font-size:.9rem;color:var(--text-muted)">${order.items} item${order.items > 1 ? 's' : ''} ordered</p>
            </div>
            <div style="grid-column:1/-1;border-top:1px solid var(--warm);padding-top:1rem;display:flex;justify-content:space-between;align-items:center;">
                <strong style="font-size:.9rem;">Order Total</strong>
                <strong style="font-size:1.3rem;color:var(--primary-color);letter-spacing:-.03em;">₱${order.total.toLocaleString(undefined, { minimumFractionDigits: 2 })}</strong>
            </div>
        </div>
    `;
    document.getElementById('updateStatusBtn').onclick = () => openStatusModal(id, order.status);
    new bootstrap.Modal(document.getElementById('orderDetailModal')).show();
}

function openStatusModal(id, currentStatus) {
    document.getElementById('statusOrderId').textContent = id;
    document.querySelectorAll('input[name="newStatus"]').forEach(r => {
        r.checked = r.value === currentStatus;
    });
    new bootstrap.Modal(document.getElementById('updateStatusModal')).show();
}

function confirmStatusUpdate() {
    const id = document.getElementById('statusOrderId').textContent;
    const newStatus = document.querySelector('input[name="newStatus"]:checked')?.value;
    if (!newStatus) return;
    const order = mockOrders.find(o => o.id === id);
    if (order) order.status = newStatus;
    bootstrap.Modal.getInstance(document.getElementById('updateStatusModal')).hide();
    showToast('Order status updated to ' + newStatus, 'success');
    renderOrders(currentFilter);
    updateStats();
}

function cancelOrder(id) {
    if (!confirm('Cancel order ' + id + '?')) return;
    const order = mockOrders.find(o => o.id === id);
    if (order) order.status = 'cancelled';
    showToast('Order ' + id + ' has been cancelled.', 'error');
    renderOrders(currentFilter);
    updateStats();
}

function exportOrders() {
    showToast('Export started — check your downloads.', 'success');
}

function showToast(msg, type) {
    document.getElementById('toastMessage').textContent = msg;
    const icon = document.getElementById('toastIcon');
    if (type === 'error') {
        icon.innerHTML = '<i class="bi bi-x-circle-fill"></i>';
        icon.className = 'adm-toast-icon adm-toast-icon--error';
    } else {
        icon.innerHTML = '<i class="bi bi-check-circle-fill"></i>';
        icon.className = 'adm-toast-icon';
    }
    const bar = document.getElementById('toastProgressBar');
    bar.style.transition = 'none';
    bar.style.width = '100%';
    const toast = new bootstrap.Toast(document.getElementById('liveToast'));
    toast.show();
    setTimeout(() => {
        bar.style.transition = 'width 4s linear';
        bar.style.width = '0%';
    }, 50);
}

// Tab switching
document.querySelectorAll('.adm-tab').forEach(tab => {
    tab.addEventListener('click', () => {
        document.querySelectorAll('.adm-tab').forEach(t => t.classList.remove('active'));
        tab.classList.add('active');
        currentFilter = tab.dataset.status;
        document.getElementById('statusFilter').value = currentFilter;
        renderOrders(currentFilter);
    });
});

// Select all checkbox
document.getElementById('selectAll').addEventListener('change', function () {
    document.querySelectorAll('#ordersTableBody .adm-checkbox').forEach(cb => cb.checked = this.checked);
});

// Live search
document.getElementById('searchOrder').addEventListener('input', () => renderOrders(currentFilter));

// Init
updateStats();
renderOrders('');