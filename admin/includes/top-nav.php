<!-- Top Navigation Bar -->
<nav class="top-navbar d-flex justify-content-between align-items-center">
    <button class="btn btn-light d-lg-none" onclick="toggleMobileSidebar(event)">
        <i class="bi bi-list"></i>
    </button>

    <div class="search-bar d-none d-md-block">
        <div class="input-group">
            <span class="input-group-text bg-transparent border-end-0">
                <i class="bi bi-search"></i>
            </span>
            <input type="text" class="form-control border-start-0 shadow-none" placeholder="Search products, orders, customers...">
        </div>
    </div>

    <div class="d-flex align-items-center gap-3">
        <i class="bi bi-bell fs-5" style="cursor: pointer;" data-bs-toggle="tooltip" title="Notifications"></i>

        <div class="dropdown">
            <a href="#" class="d-flex align-items-center text-decoration-none text-dark dropdown-toggle" data-bs-toggle="dropdown">
                <img src="https://ui-avatars.com/api/?name=<?= urlencode($_SESSION['full_name']) ?>&background=2563eb&color=fff"
                    class="rounded-circle me-2"
                    width="36"
                    height="36"
                    alt="Profile">

                <div class="d-none d-sm-block">
                    <div class="fw-semibold lh-1"><?= htmlspecialchars($_SESSION['full_name']) ?></div>
                    <small class="text-muted" style="font-size: 0.75rem;"><?= htmlspecialchars($_SESSION['dept'] ?? 'Client') ?></small>
                </div>
            </a>

            <ul class="dropdown-menu dropdown-menu-end shadow-sm">
                <li>
                    <div class="dropdown-header">Manage Account</div>
                </li>
                <li><a class="dropdown-item" href="profile.php"><i class="bi bi-person me-2"></i>Profile</a></li>
                <li><a class="dropdown-item" href="settings.php"><i class="bi bi-gear me-2"></i>Settings</a></li>
                <li>
                    <hr class="dropdown-divider">
                </li>
                <li><a class="dropdown-item text-danger" href="auth/logout.php"><i class="bi bi-box-arrow-left me-2"></i>Logout</a></li>
            </ul>
        </div>
    </div>
</nav>