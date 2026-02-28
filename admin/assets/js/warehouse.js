/* ===============================================
   WAREHOUSE MANAGEMENT - IMPROVED JAVASCRIPT
   =============================================== */

// Initialize on page load
document.addEventListener('DOMContentLoaded', function () {
    initializeWarehouse();
});

// Initialize warehouse functionality
function initializeWarehouse() {
    // Initialize Bootstrap tooltips
    initializeTooltips();

    // Animate metrics
    animateMetrics();

    console.log('Warehouse Management System initialized');
}

// Initialize tooltips
function initializeTooltips() {
    const tooltipTriggerList = [].slice.call(document.querySelectorAll('[data-bs-toggle="tooltip"]'));
    tooltipTriggerList.map(function (tooltipTriggerEl) {
        return new bootstrap.Tooltip(tooltipTriggerEl);
    });
}

// Animate metric values on load
function animateMetrics() {
    const metrics = document.querySelectorAll('.metric-value');
    metrics.forEach(metric => {
        const text = metric.textContent.replace(/,/g, '').trim();
        const targetValue = parseInt(text);
        if (!isNaN(targetValue) && targetValue > 0) {
            animateValue(metric, 0, targetValue, 1000);
        }
    });
}

// Animate number counter
function animateValue(element, start, end, duration) {
    let startTimestamp = null;
    const step = (timestamp) => {
        if (!startTimestamp) startTimestamp = timestamp;
        const progress = Math.min((timestamp - startTimestamp) / duration, 1);
        const currentValue = Math.floor(progress * (end - start) + start);
        element.textContent = currentValue.toLocaleString();

        if (progress < 1) {
            window.requestAnimationFrame(step);
        }
    };
    window.requestAnimationFrame(step);
}

// Select Zone
function selectZone(zoneLetter) {
    // Remove previous selection
    document.querySelectorAll('.warehouse-zone').forEach(zone => {
        zone.classList.remove('selected');
    });

    // Add selection to clicked zone
    const zones = document.querySelectorAll('.warehouse-zone');
    const zoneIndex = zoneLetter === 'A' ? 0 : zoneLetter === 'B' ? 1 : 2;

    if (zones[zoneIndex]) {
        zones[zoneIndex].classList.add('selected');
        showSuccessToast(`Zone ${zoneLetter} selected`);
    }
}

// Zoom functions
let currentZoom = 1;

function zoomIn() {
    const map = document.getElementById('warehouseMap');
    if (currentZoom < 1.5) {
        currentZoom += 0.1;
        map.style.transform = `scale(${currentZoom})`;
        showSuccessToast(`Zoom: ${Math.round(currentZoom * 100)}%`);
    }
}

function zoomOut() {
    const map = document.getElementById('warehouseMap');
    if (currentZoom > 0.6) {
        currentZoom -= 0.1;
        map.style.transform = `scale(${currentZoom})`;
        showSuccessToast(`Zoom: ${Math.round(currentZoom * 100)}%`);
    }
}

function resetZoom() {
    const map = document.getElementById('warehouseMap');
    currentZoom = 1;
    map.style.transform = `scale(1)`;
    showSuccessToast('Zoom reset to 100%');
}

// Open Scanner
function openScanner() {
    showSuccessToast('Scanner activated - Ready to scan');
}

// Filter Inventory
function filterInventory() {
    const searchInput = document.getElementById('inventorySearch');
    const filter = searchInput.value.toLowerCase();
    const table = document.getElementById('inventoryTable');
    const rows = table.getElementsByTagName('tbody')[0].getElementsByTagName('tr');

    let visibleCount = 0;

    for (let i = 0; i < rows.length; i++) {
        const row = rows[i];
        const text = row.textContent.toLowerCase();

        if (text.includes(filter)) {
            row.style.display = '';
            visibleCount++;
        } else {
            row.style.display = 'none';
        }
    }
}

// Show Success Toast
function showSuccessToast(message) {
    // Remove existing toast if any
    const existingToast = document.getElementById('warehouseToast');
    if (existingToast) {
        existingToast.remove();
    }

    // Create toast element
    const toast = document.createElement('div');
    toast.id = 'warehouseToast';
    toast.className = 'position-fixed bottom-0 end-0 p-3';
    toast.style.zIndex = '9999';
    toast.innerHTML = `
        <div class="toast show" role="alert">
            <div class="toast-header bg-success text-white">
                <i class="bi bi-check-circle-fill me-2"></i>
                <strong class="me-auto">Success</strong>
                <button type="button" class="btn-close btn-close-white" onclick="this.closest('#warehouseToast').remove()"></button>
            </div>
            <div class="toast-body">
                ${message}
            </div>
        </div>
    `;

    document.body.appendChild(toast);

    // Auto-remove after 3 seconds
    setTimeout(() => {
        toast.remove();
    }, 3000);
}

// Keyboard Shortcuts
document.addEventListener('keydown', function (e) {
    // Ctrl/Cmd + F: Focus search
    if ((e.ctrlKey || e.metaKey) && e.key === 'f') {
        e.preventDefault();
        const searchInput = document.getElementById('inventorySearch');
        if (searchInput) {
            searchInput.focus();
        }
    }

    // Ctrl/Cmd + S: Open scanner
    if ((e.ctrlKey || e.metaKey) && e.key === 's') {
        e.preventDefault();
        openScanner();
    }
});

// Export Inventory (optional feature)
function exportInventory() {
    const table = document.getElementById('inventoryTable');
    let csv = [];

    // Headers
    const headers = [];
    table.querySelectorAll('thead th').forEach(th => {
        headers.push(th.textContent.trim());
    });
    csv.push(headers.join(','));

    // Rows
    table.querySelectorAll('tbody tr').forEach(tr => {
        if (tr.style.display !== 'none') {
            const row = [];
            tr.querySelectorAll('td').forEach((td, index) => {
                if (index < 5) { // Skip actions column
                    row.push('"' + td.textContent.trim().replace(/"/g, '""') + '"');
                }
            });
            csv.push(row.join(','));
        }
    });

    // Download
    const csvContent = csv.join('\n');
    const blob = new Blob([csvContent], { type: 'text/csv' });
    const url = URL.createObjectURL(blob);
    const link = document.createElement('a');
    link.href = url;
    link.download = `warehouse-inventory-${new Date().toISOString().split('T')[0]}.csv`;
    link.click();

    showSuccessToast('Inventory exported successfully');
}