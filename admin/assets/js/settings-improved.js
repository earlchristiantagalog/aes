/* ================================================
   SETTINGS PAGE - IMPROVED JAVASCRIPT
   Enhanced functionality and user experience
   ================================================ */

// Initialize on page load
document.addEventListener('DOMContentLoaded', function () {
    initializeTooltips();
    initializeFormValidation();
    initializeAutoSave();
    initializeSearchFilter();
});

// Initialize Bootstrap tooltips
function initializeTooltips() {
    const tooltipTriggerList = [].slice.call(document.querySelectorAll('[data-bs-toggle="tooltip"]'));
    tooltipTriggerList.map(function (tooltipTriggerEl) {
        return new bootstrap.Tooltip(tooltipTriggerEl);
    });
}

// Show Settings Tab
function showSettingsTab(event, tabName) {
    event.preventDefault();

    // Hide all panels with fade out
    document.querySelectorAll('.settings-panel').forEach(panel => {
        panel.style.display = 'none';
    });

    // Remove active class from all nav items
    document.querySelectorAll('.nav-item').forEach(item => {
        item.classList.remove('active');
    });

    // Show selected panel with fade in
    const selectedPanel = document.getElementById(tabName + '-settings');
    if (selectedPanel) {
        selectedPanel.style.display = 'block';
    }

    // Add active class to clicked item
    event.currentTarget.classList.add('active');

    // Update URL hash without scrolling
    history.pushState(null, null, '#' + tabName);

    // Scroll to top of content
    window.scrollTo({
        top: 0,
        behavior: 'smooth'
    });
}

// Preview Profile Picture
function previewProfilePicture(input) {
    if (input.files && input.files[0]) {
        const file = input.files[0];

        // Validate file size (2MB max)
        if (file.size > 2 * 1024 * 1024) {
            showNotification('error', 'File size must be less than 2MB');
            input.value = '';
            return;
        }

        // Validate file type
        if (!file.type.match('image.*')) {
            showNotification('error', 'Please select a valid image file');
            input.value = '';
            return;
        }

        const reader = new FileReader();
        reader.onload = function (e) {
            const preview = document.getElementById('profilePreview');
            if (preview) {
                preview.src = e.target.result;
                showNotification('success', 'Profile picture updated successfully');
            }
        };
        reader.readAsDataURL(file);
    }
}

// Toggle Mobile Sidebar
function toggleMobileSidebar(event) {
    event.preventDefault();
    const sidebar = document.querySelector('.settings-sidebar');
    if (sidebar) {
        sidebar.classList.toggle('mobile-open');
    }
}

// Show Notification
function showNotification(type, message) {
    // Create notification element
    const notification = document.createElement('div');
    notification.className = `notification notification-${type}`;

    const icon = type === 'success' ? 'check-circle' : 'exclamation-circle';
    notification.innerHTML = `
        <i class="bi bi-${icon}"></i>
        <span>${message}</span>
    `;

    // Add to page
    document.body.appendChild(notification);

    // Trigger animation
    setTimeout(() => {
        notification.classList.add('show');
    }, 10);

    // Remove after 3 seconds
    setTimeout(() => {
        notification.classList.remove('show');
        setTimeout(() => {
            notification.remove();
        }, 300);
    }, 3000);
}

let shippingZones = [];

document.addEventListener("DOMContentLoaded", () => {
    const form = document.getElementById("shippingForm");

    if (form) {
        form.addEventListener("submit", function (e) {
            // We REMOVED e.preventDefault() so the form can actually submit to PHP

            const name = document.getElementById("shippingName").value;
            const price = document.getElementById("shippingPrice").value;

            if (!name || !price) {
                e.preventDefault(); // ONLY stop if validation fails
                alert("Please complete required fields.");
                return;
            }

            // The form will now proceed to PHP naturally
        });
    }
});



function renderShippingZones() {

    const list = document.getElementById("shippingList");

    if (!list) return;

    if (shippingZones.length === 0) {
        list.innerHTML = `<p>No shipping methods added.</p>`;
        return;
    }

    list.innerHTML = "";

    shippingZones.forEach((ship, index) => {

        list.innerHTML += `
            <div class="session-item">
                <div class="session-info">
                    <div class="session-title">${ship.name}</div>
                    <div class="session-meta">
                        Courier: ${ship.courier} • ₱${ship.price}
                    </div>
                </div>

                <button class="btn-danger-outline"
                    onclick="deleteShipping(${index})">
                    Remove
                </button>
            </div>
        `;
    });
}



function deleteShipping(index) {
    shippingZones.splice(index, 1);
    renderShippingZones();
}

// Payement Methods
function openEditModal(key, name) {
    document.getElementById('modal-method-key').value = key;
    document.getElementById('modal-method-name').innerText = name;

    // Fetch existing instructions from DB
    fetch(`/aes/admin/settings/get_payment_details.php?key=${key}`)
        .then(response => response.json())
        .then(data => {
            document.getElementById('modal-instructions').value = data.instructions || '';
            var myModal = new bootstrap.Modal(document.getElementById('editPaymentModal'));
            myModal.show();
        });
}

function saveInstructions() {
    const key = document.getElementById('modal-method-key').value;
    const instructions = document.getElementById('modal-instructions').value;

    const formData = new FormData();
    formData.append('key', key);
    formData.append('instructions', instructions);

    fetch('/aes/admin/settings/save_payment_details.php', {
        method: 'POST',
        body: formData
    })
        .then(response => response.json())
        .then(data => {
            if (data.success) {
                alert('Instructions updated successfully!');
                bootstrap.Modal.getInstance(document.getElementById('editPaymentModal')).hide();
            } else {
                alert('Failed to update: ' + data.message);
            }
        });
}

document.querySelectorAll('.payment-toggle').forEach(toggle => {
    toggle.addEventListener('change', function () {
        const methodKey = this.name;
        const status = this.checked ? 1 : 0;
        const card = document.getElementById(`card-${methodKey}`);

        const formData = new FormData();
        formData.append('key', methodKey); // Matches $method_key in PHP
        formData.append('status', status);

        fetch('/aes/admin/settings/update_payment_settings.php', {
            method: 'POST',
            body: formData
        })
            .then(response => response.json())
            .then(data => {
                if (data.success) {
                    // Update opacity only after successful DB update
                    if (card) card.style.opacity = status ? '1' : '0.6';
                    console.log(methodKey + " status updated!");
                } else {
                    alert("Update failed: " + data.error);
                    this.checked = !this.checked; // Revert switch if error
                }
            })
            .catch(err => {
                console.error("Fetch Error:", err);
                this.checked = !this.checked; // Revert switch on network error
            });
    });
});


// // Form Validation
// function initializeFormValidation() {
//     const forms = document.querySelectorAll('form');
//     forms.forEach(form => {
//         form.addEventListener('submit', function (e) {
//             e.preventDefault();
//             showNotification();
//             // Basic validation
//             const inputs = form.querySelectorAll('input[required], textarea[required], select[required]');
//             let isValid = true;

//             inputs.forEach(input => {
//                 if (!input.value.trim()) {
//                     isValid = false;
//                     input.classList.add('is-invalid');
//                 } else {
//                     input.classList.remove('is-invalid');
//                 }
//             });

//             if (isValid) {
//                 showNotification('success', 'Settings saved successfully');
//             } else {
//                 showNotification('error', 'Please fill in all required fields');
//             }
//         });
//     });
// }

function openVoucherModal() {
    new bootstrap.Modal(document.getElementById('addVoucherModal')).show();
}

document.getElementById('voucherForm').addEventListener('submit', function (e) {
    e.preventDefault();
    const formData = new FormData(this);

    fetch('/aes/admin/settings/save_voucher.php', { method: 'POST', body: formData })
        .then(res => res.json())
        .then(data => {
            if (data.success) {
                location.reload();
            } else {
                alert(data.message);
            }
        });
});

function deleteVoucher(id) {
    if (confirm('Are you sure you want to delete this voucher?')) {
        const formData = new FormData();
        formData.append('id', id);
        fetch('/aes/admin/settings/delete_voucher.php', { method: 'POST', body: formData })
            .then(() => location.reload());
    }
}

// Auto-save Functionality
function initializeAutoSave() {
    let saveTimeout;
    const inputs = document.querySelectorAll('.form-input, .form-select, .toggle-switch input');

    inputs.forEach(input => {
        input.addEventListener('change', function () {
            clearTimeout(saveTimeout);

            // Show saving indicator
            showSavingIndicator(this);

            // Simulate save after 1 second
            saveTimeout = setTimeout(() => {
                showSavedIndicator(this);
            }, 1000);
        });
    });
}

// Show Saving Indicator
function showSavingIndicator(element) {
    const parent = element.closest('.form-group') || element.closest('.setting-row');
    if (!parent) return;

    // Remove existing indicators
    const existingIndicator = parent.querySelector('.save-indicator');
    if (existingIndicator) {
        existingIndicator.remove();
    }

    // Create saving indicator
    const indicator = document.createElement('span');
    indicator.className = 'save-indicator text-muted ms-2';
    indicator.innerHTML = '<i class="bi bi-arrow-clockwise spin"></i> Saving...';

    const label = parent.querySelector('.form-label') || parent.querySelector('.setting-title');
    if (label) {
        label.appendChild(indicator);
    }
}

// Show Saved Indicator
function showSavedIndicator(element) {
    const parent = element.closest('.form-group') || element.closest('.setting-row');
    if (!parent) return;

    const indicator = parent.querySelector('.save-indicator');
    if (indicator) {
        indicator.className = 'save-indicator text-success ms-2';
        indicator.innerHTML = '<i class="bi bi-check-circle"></i> Saved';

        // Remove after 2 seconds
        setTimeout(() => {
            indicator.remove();
        }, 2000);
    }
}



// Search Filter for Settings
function initializeSearchFilter() {
    const searchInput = document.querySelector('.search-input');
    if (!searchInput) return;

    searchInput.addEventListener('input', function () {
        const searchTerm = this.value.toLowerCase();
        const navItems = document.querySelectorAll('.nav-item');

        navItems.forEach(item => {
            const text = item.textContent.toLowerCase();
            if (text.includes(searchTerm)) {
                item.style.display = 'flex';
            } else {
                item.style.display = 'none';
            }
        });
    });
}

// Save All Changes
document.addEventListener('DOMContentLoaded', function () {
    const saveAllBtn = document.getElementById('saveAllBtn');
    if (saveAllBtn) {
        saveAllBtn.addEventListener('click', function () {
            showNotification('success', 'All settings saved successfully');
        });
    }
});

// Load settings from hash on page load
window.addEventListener('load', function () {
    const hash = window.location.hash.substring(1);
    if (hash) {
        const navItem = document.querySelector(`.nav-item[href="#${hash}"]`);
        if (navItem) {
            navItem.click();
        }
    }
});

// Password Strength Checker
function initializePasswordStrength() {
    const passwordInputs = document.querySelectorAll('input[type="password"]');

    passwordInputs.forEach(input => {
        input.addEventListener('input', function () {
            const strength = calculatePasswordStrength(this.value);
            updatePasswordStrengthUI(this, strength);
        });
    });
}

function calculatePasswordStrength(password) {
    let strength = 0;

    if (password.length >= 8) strength++;
    if (password.length >= 12) strength++;
    if (/[a-z]/.test(password) && /[A-Z]/.test(password)) strength++;
    if (/\d/.test(password)) strength++;
    if (/[^a-zA-Z\d]/.test(password)) strength++;

    return strength;
}

function updatePasswordStrengthUI(input, strength) {
    // Remove existing strength indicator
    const existingIndicator = input.parentElement.querySelector('.password-strength');
    if (existingIndicator) {
        existingIndicator.remove();
    }

    if (input.value.length === 0) return;

    // Create strength indicator
    const indicator = document.createElement('div');
    indicator.className = 'password-strength';

    let strengthText = '';
    let strengthClass = '';

    if (strength <= 2) {
        strengthText = 'Weak';
        strengthClass = 'weak';
    } else if (strength <= 3) {
        strengthText = 'Medium';
        strengthClass = 'medium';
    } else {
        strengthText = 'Strong';
        strengthClass = 'strong';
    }

    indicator.innerHTML = `
        <div class="strength-bar ${strengthClass}">
            <div class="strength-fill" style="width: ${(strength / 5) * 100}%"></div>
        </div>
        <span class="strength-text ${strengthClass}">${strengthText}</span>
    `;

    input.parentElement.appendChild(indicator);
}

// Character Counter for Textareas
function initializeCharacterCounter() {
    const textareas = document.querySelectorAll('textarea[maxlength]');

    textareas.forEach(textarea => {
        const maxLength = textarea.getAttribute('maxlength');

        // Create counter element
        const counter = document.createElement('div');
        counter.className = 'character-counter';
        counter.textContent = `0 / ${maxLength}`;

        textarea.parentElement.appendChild(counter);

        // Update counter on input
        textarea.addEventListener('input', function () {
            const currentLength = this.value.length;
            counter.textContent = `${currentLength} / ${maxLength}`;

            if (currentLength >= maxLength * 0.9) {
                counter.classList.add('warning');
            } else {
                counter.classList.remove('warning');
            }
        });
    });
}

// Confirm Before Delete
function confirmDelete(itemName) {
    return confirm(`Are you sure you want to delete ${itemName}? This action cannot be undone.`);
}

// Export Settings
function exportSettings() {
    const settings = {
        profile: {},
        security: {},
        notifications: {},
        appearance: {},
        business: {},
        // ... collect all settings
    };

    const dataStr = JSON.stringify(settings, null, 2);
    const dataBlob = new Blob([dataStr], { type: 'application/json' });
    const url = URL.createObjectURL(dataBlob);

    const link = document.createElement('a');
    link.href = url;
    link.download = 'settings-export.json';
    link.click();

    showNotification('success', 'Settings exported successfully');
}

// Import Settings
function importSettings(fileInput) {
    const file = fileInput.files[0];
    if (!file) return;

    const reader = new FileReader();
    reader.onload = function (e) {
        try {
            const settings = JSON.parse(e.target.result);
            // Apply settings to form fields
            showNotification('success', 'Settings imported successfully');
        } catch (error) {
            showNotification('error', 'Invalid settings file');
        }
    };
    reader.readAsText(file);
}

// Keyboard Shortcuts
document.addEventListener('keydown', function (e) {
    // Ctrl/Cmd + S to save
    if ((e.ctrlKey || e.metaKey) && e.key === 's') {
        e.preventDefault();
        const saveBtn = document.querySelector('.btn-primary');
        if (saveBtn) {
            saveBtn.click();
        }
    }

    // Escape to close modal
    if (e.key === 'Escape') {
        const modal = document.querySelector('.modal.show');
        if (modal) {
            bootstrap.Modal.getInstance(modal).hide();
        }
    }
});

// Smooth scroll for anchor links
document.querySelectorAll('a[href^="#"]').forEach(anchor => {
    anchor.addEventListener('click', function (e) {
        const href = this.getAttribute('href');
        if (href !== '#' && !href.includes('modal')) {
            e.preventDefault();
            const target = document.querySelector(href);
            if (target) {
                target.scrollIntoView({
                    behavior: 'smooth'
                });
            }
        }
    });
});

// Add notification styles dynamically
const style = document.createElement('style');
style.textContent = `
    .notification {
        position: fixed;
        top: 100px;
        right: 20px;
        background: white;
        padding: 1rem 1.5rem;
        border-radius: 10px;
        box-shadow: 0 4px 12px rgba(0, 0, 0, 0.15);
        display: flex;
        align-items: center;
        gap: 0.75rem;
        z-index: 9999;
        transform: translateX(400px);
        transition: transform 0.3s ease;
    }
    
    .notification.show {
        transform: translateX(0);
    }
    
    .notification-success {
        border-left: 4px solid #10b981;
    }
    
    .notification-error {
        border-left: 4px solid #ef4444;
    }
    
    .notification i {
        font-size: 1.25rem;
    }
    
    .notification-success i {
        color: #10b981;
    }
    
    .notification-error i {
        color: #ef4444;
    }
    
    .save-indicator {
        font-size: 0.875rem;
        display: inline-flex;
        align-items: center;
        gap: 0.25rem;
    }
    
    .spin {
        animation: spin 1s linear infinite;
    }
    
    @keyframes spin {
        from { transform: rotate(0deg); }
        to { transform: rotate(360deg); }
    }
    
    .is-invalid {
        border-color: #ef4444 !important;
    }
    
    .password-strength {
        margin-top: 0.5rem;
    }
    
    .strength-bar {
        height: 4px;
        background: #e2e8f0;
        border-radius: 2px;
        overflow: hidden;
        margin-bottom: 0.25rem;
    }
    
    .strength-fill {
        height: 100%;
        transition: width 0.3s ease, background 0.3s ease;
    }
    
    .strength-bar.weak .strength-fill {
        background: #ef4444;
    }
    
    .strength-bar.medium .strength-fill {
        background: #f59e0b;
    }
    
    .strength-bar.strong .strength-fill {
        background: #10b981;
    }
    
    .strength-text {
        font-size: 0.8125rem;
        font-weight: 500;
    }
    
    .strength-text.weak {
        color: #ef4444;
    }
    
    .strength-text.medium {
        color: #f59e0b;
    }
    
    .strength-text.strong {
        color: #10b981;
    }
    
    .character-counter {
        font-size: 0.8125rem;
        color: #64748b;
        text-align: right;
        margin-top: 0.25rem;
    }
    
    .character-counter.warning {
        color: #f59e0b;
    }
    
    @media (max-width: 768px) {
        .settings-sidebar.mobile-open {
            display: block;
            position: fixed;
            top: 72px;
            left: 0;
            width: 280px;
            height: calc(100vh - 72px);
            z-index: 1000;
            box-shadow: 2px 0 8px rgba(0, 0, 0, 0.1);
        }
    }
`;
document.head.appendChild(style);