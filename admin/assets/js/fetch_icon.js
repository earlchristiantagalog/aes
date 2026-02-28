// Keep updateIconPreview global so the HTML onchange can find it
function updateIconPreview() {
    const selector = document.getElementById('iconSelector');
    const preview = document.getElementById('iconPreview');
    if (selector && preview) {
        preview.className = 'bi ' + (selector.value || 'bi-collection');
    }
}

// Unified Toast Function (with Progress Bar)
function showToast(message, type = 'success') {
    const toastEl = document.getElementById('liveToast');
    const toastMessage = document.getElementById('toastMessage');
    const progressBar = document.getElementById('toastProgressBar');
    const duration = 5000;

    if (!toastEl || !progressBar) return; // Safety check

    toastEl.classList.remove('bg-success', 'bg-danger', 'text-white');
    if (type === 'success') {
        toastEl.classList.add('bg-success', 'text-white');
    } else {
        toastEl.classList.add('bg-danger', 'text-white');
    }

    toastMessage.textContent = message;

    // Reset Progress Bar
    progressBar.style.transition = 'none';
    progressBar.style.width = '100%';

    const toast = new bootstrap.Toast(toastEl, { autohide: true, delay: duration });

    // Animation trigger
    requestAnimationFrame(() => {
        setTimeout(() => {
            progressBar.style.transition = `width ${duration}ms linear`;
            progressBar.style.width = '0%';
        }, 10);
    });

    toast.show();
}

document.addEventListener('DOMContentLoaded', function () {
    const iconSelector = document.getElementById('iconSelector');
    const addForm = document.getElementById('addCategoryForm');

    // Load Icons
    fetch('https://unpkg.com/bootstrap-icons@1.11.3/font/bootstrap-icons.json')
        .then(response => response.json())
        .then(data => {
            if (!iconSelector) return;
            iconSelector.innerHTML = '<option value="bi-collection">Select an Icon...</option>';
            Object.keys(data).sort().forEach(iconName => {
                const option = document.createElement('option');
                option.value = 'bi-' + iconName;
                option.textContent = iconName.charAt(0).toUpperCase() + iconName.slice(1).replace(/-/g, ' ');
                iconSelector.appendChild(option);
            });
        });

    // Handle Form Submit
    if (addForm) {
        addForm.addEventListener('submit', async function (e) {
            e.preventDefault();
            const formData = new FormData(this);

            try {
                const response = await fetch('/aes/admin/categories/save_category.php', {
                    method: 'POST',
                    body: formData
                });

                const text = await response.text();
                let result;
                try {
                    result = JSON.parse(text);
                } catch (err) {
                    showToast("Server returned invalid data", "error");
                    return;
                }

                if (result.success) {
                    showToast('Category saved successfully!', 'success');

                    // Close Modal
                    const modalEl = document.getElementById('addCategoryModal');
                    const modal = bootstrap.Modal.getInstance(modalEl) || new bootstrap.Modal(modalEl);
                    modal.hide();

                    this.reset();
                    updateIconPreview();

                    // Optional: If you have a category list table, refresh it here
                    // loadCategories(); 
                } else {
                    // --- CUSTOM ERROR MESSAGE MAPPING ---
                    let friendlyMessage = "Something went wrong. Please try again.";

                    if (result.message.includes('Duplicate entry')) {
                        friendlyMessage = "This category name already exists!";
                    } else if (result.message.includes('required')) {
                        friendlyMessage = "Please fill in the required field.";
                    } else if (result.message.includes('Database Error')) {
                        friendlyMessage = "We couldn't reach the database right now.";
                    } else {
                        // Fallback to the actual server message if no match
                        friendlyMessage = result.message;
                    }

                    showToast(friendlyMessage, 'error');
                }
            } catch (error) {
                showToast('Critical connection error', 'error');
            }
        });
    }
});