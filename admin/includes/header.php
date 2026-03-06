<?php
session_start();
require 'auth/auth.php';

// Logic for initials and display names
$initials = 'AD';
if (!empty($_SESSION['full_name'])) {
    $parts = explode(' ', $_SESSION['full_name']);
    $initials = strtoupper(substr($parts[0], 0, 1) . (isset($parts[1]) ? substr($parts[1], 0, 1) : ''));
}
$displayName = $_SESSION['full_name'] ?? 'Admin User';
$displayRole = $_SESSION['role']      ?? 'Administrator';
$pageName = basename($_SERVER['SCRIPT_NAME']);
?>
<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Admin Panel - AES</title>
    <link href="https://cdn.jsdelivr.net/npm/summernote@0.8.18/dist/summernote.min.css" rel="stylesheet">
    <link href="https://cdn.jsdelivr.net/npm/summernote@0.8.18/dist/summernote-lite.min.css" rel="stylesheet">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.min.css">
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@400;500;600;700&display=swap" rel="stylesheet">

    <link rel="stylesheet" href="assets/css/index.css">
    <link rel="stylesheet" href="assets/css/order.css">
    <link rel="stylesheet" href="assets/css/product.css">
    <link rel="stylesheet" href="assets/css/staff.css">
    <link rel="stylesheet" href="assets/css/settings.css">
    <link rel="stylesheet" href="assets/css/warehouse.css">

    <style>
        :root {
            --sb-width: 200px;
            --sb-collapsed-width: 70px;
            --sb-bg: #1e3a5f;
            --sb-text: rgba(255, 255, 255, 0.7);
            --sb-active-accent: #87CEEB;
            --sb-transition: 0.3s cubic-bezier(0.4, 0, 0.2, 1);
            --light-bg: #f8f9fa;
        }

        body {
            font-family: 'Inter', sans-serif;
            background: var(--light-bg);
            margin: 0;
            display: flex;
            /* Use flex for layout */
            min-height: 100vh;
        }

        /* --- SIDEBAR --- */
        #sidebar {
            width: var(--sb-width);
            background: var(--sb-bg);
            height: 100vh;
            position: fixed;
            left: 0;
            top: 0;
            z-index: 1050;
            transition: width var(--sb-transition);
            display: flex;
            flex-direction: column;
            overflow: hidden;
        }

        #sidebar.collapsed {
            width: var(--sb-collapsed-width);
        }

        /* --- MAIN CONTENT (The Fix) --- */
        #main-content {
            flex: 1;
            margin-left: var(--sb-width);
            /* Matches sidebar */
            width: calc(100% - var(--sb-width));
            /* Takes remaining space */
            transition: margin-left var(--sb-transition), width var(--sb-transition);
            min-height: 100vh;
            display: flex;
            flex-direction: column;
        }

        #sidebar.collapsed+#main-content {
            margin-left: var(--sb-collapsed-width);
            width: calc(100% - var(--sb-collapsed-width));
        }

        /* --- SIDEBAR ELEMENTS --- */
        .sb-header {
            height: 70px;
            display: flex;
            align-items: center;
            padding: 0 15px;
            border-bottom: 1px solid rgba(255, 255, 255, 0.1);
            justify-content: space-between;
        }

        .sb-logo {
            height: 35px;
            transition: opacity 0.2s;
        }

        .sb-logo-icon {
            height: 30px;
            display: none;
        }

        .collapsed .sb-logo {
            display: none;
        }

        .collapsed .sb-logo-icon {
            display: block;
            margin: 0 auto;
        }

        .collapsed .sb-header {
            justify-content: center;
        }

        .sb-nav {
            flex: 1;
            padding: 15px 0;
            overflow-y: auto;
            overflow-x: hidden;
        }

        .sb-section-label {
            font-size: 10px;
            text-transform: uppercase;
            color: rgba(255, 255, 255, 0.4);
            padding: 10px 20px;
            display: block;
            white-space: nowrap;
        }

        .collapsed .sb-section-label {
            opacity: 0;
        }

        .sb-link {
            display: flex;
            align-items: center;
            padding: 12px 20px;
            color: var(--sb-text);
            text-decoration: none;
            transition: all 0.2s;
            white-space: nowrap;
            gap: 15px;
        }

        .sb-link i {
            font-size: 1.2rem;
            width: 25px;
            text-align: center;
        }

        .sb-link:hover,
        .sb-link.active {
            background: rgba(255, 255, 255, 0.1);
            color: #fff;
        }

        .sb-link.active {
            border-left: 4px solid var(--sb-active-accent);
        }

        .collapsed .sb-link {
            justify-content: center;
            padding: 12px 0;
            gap: 0;
        }

        .collapsed .sb-link span {
            display: none;
        }

        .sb-footer {
            padding: 15px;
            background: rgba(0, 0, 0, 0.2);
            display: flex;
            align-items: center;
            gap: 12px;
        }

        .sb-user-avatar {
            width: 35px;
            height: 35px;
            background: var(--sb-active-accent);
            border-radius: 50%;
            display: flex;
            align-items: center;
            justify-content: center;
            font-weight: bold;
            color: #1e3a5f;
        }

        .collapsed .sb-user-info {
            display: none;
        }

        /* --- MOBILE RESPONSIVENESS --- */
        @media (max-width: 991px) {
            #sidebar {
                transform: translateX(-100%);
                width: var(--sb-width) !important;
            }

            #sidebar.mobile-open {
                transform: translateX(0);
            }

            #main-content {
                margin-left: 0 !important;
                width: 100% !important;
            }

            .sb-toggle {
                display: none;
            }

            .sb-overlay {
                display: none;
                position: fixed;
                inset: 0;
                background: rgba(0, 0, 0, 0.5);
                z-index: 1040;
            }

            .sb-overlay.active {
                display: block;
            }
        }
    </style>
</head>

<body>

    <div class="sb-overlay" id="sbOverlay" onclick="toggleMobileSidebar()"></div>

    <nav id="sidebar">
        <div class="sb-header">
            <img src="assets/AES.png" alt="Logo" class="sb-logo">
            <!-- <img src="assets/AES-icon.png" alt="Logo" class="sb-logo-icon"> -->
            <button class="btn p-0 text-white sb-toggle" onclick="toggleSidebar()">
                <i class="bi bi-list" style="font-size: 1.5rem;"></i>
            </button>
        </div>

        <div class="sb-nav">
            <span class="sb-section-label">Main</span>
            <a href="index.php" class="sb-link <?= $pageName == 'index.php' ? 'active' : '' ?>">
                <i class="bi bi-speedometer2"></i> <span>Dashboard</span>
            </a>

            <span class="sb-section-label">Catalog</span>
            <a href="products.php" class="sb-link <?= $pageName == 'products.php' ? 'active' : '' ?>">
                <i class="bi bi-box-seam"></i> <span>Products</span>
            </a>
            <a href="orders.php" class="sb-link <?= $pageName == 'orders.php' ? 'active' : '' ?>">
                <i class="bi bi-receipt"></i> <span>Orders</span>
            </a>
            <a href="warehouse.php" class="sb-link <?= $pageName == 'warehouse.php' ? 'active' : '' ?>">
                <i class="bi bi-house"></i> <span>Warehouse</span>
            </a>

            <span class="sb-section-label">System</span>
            <a href="settings.php" class="sb-link <?= $pageName == 'settings.php' ? 'active' : '' ?>">
                <i class="bi bi-gear"></i> <span>Settings</span>
            </a>
        </div>
    </nav>

    <main id="main-content">
        <?php include 'top-nav.php'; ?>
        <script>
            // Desktop Toggle
            function toggleSidebar() {
                const sb = document.getElementById('sidebar');
                sb.classList.toggle('collapsed');
                localStorage.setItem('sbCollapsed', sb.classList.contains('collapsed') ? '1' : '0');
            }

            // Mobile Toggle (Triggered by button in top-nav.php)
            function toggleMobileSidebar() {
                const sb = document.getElementById('sidebar');
                const overlay = document.getElementById('sbOverlay');
                sb.classList.toggle('mobile-open');
                overlay.classList.toggle('active');
            }

            // Restore sidebar state immediately to prevent "jumpy" UI
            (function() {
                if (window.innerWidth >= 992 && localStorage.getItem('sbCollapsed') === '1') {
                    document.getElementById('sidebar').classList.add('collapsed');
                }
            })();
        </script>