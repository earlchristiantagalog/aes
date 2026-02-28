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