const sidebar = document.getElementById('sidebar');
const mainContent = document.getElementById('main-content');
const toggleIcon = document.getElementById('toggleIcon');

// 1. Desktop Toggle Logic
function toggleDesktopMenu() {
    sidebar.classList.toggle('collapsed');
    mainContent.classList.toggle('expanded');

    // Change icon based on state
    if (sidebar.classList.contains('collapsed')) {
        toggleIcon.classList.replace('bi-text-indent-left', 'bi-text-indent-right');
    } else {
        toggleIcon.classList.replace('bi-text-indent-right', 'bi-text-indent-left');
    }
}

// 2. Mobile Toggle Logic
function toggleMobileSidebar(event) {
    event.stopPropagation();
    sidebar.classList.toggle('active');
}

// 3. Click Outside to Close (Mobile)
document.addEventListener('click', function (event) {
    if (window.innerWidth <= 992) {
        const isClickInsideSidebar = sidebar.contains(event.target);
        if (sidebar.classList.contains('active') && !isClickInsideSidebar) {
            sidebar.classList.remove('active');
        }
    }
});

// 4. Initialize Bootstrap Tooltips
const tooltipTriggerList = document.querySelectorAll('[data-bs-toggle="tooltip"]');
const tooltipList = [...tooltipTriggerList].map(tooltipTriggerEl => new bootstrap.Tooltip(tooltipTriggerEl));

// 5. Smooth scroll behavior
document.querySelectorAll('a[href^="#"]').forEach(anchor => {
    anchor.addEventListener('click', function (e) {
        e.preventDefault();
        const target = document.querySelector(this.getAttribute('href'));
        if (target) {
            target.scrollIntoView({
                behavior: 'smooth',
                block: 'start'
            });
        }
    });
});