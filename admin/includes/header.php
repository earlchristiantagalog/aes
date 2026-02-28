<?php
session_start();
require 'auth/auth.php';
?>
<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Admin Panel - AES Aralin Educational Supplies</title>

    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.min.css">
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@400;500;600;700&display=swap" rel="stylesheet">
    <link href="https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/css/select2.min.css" rel="stylesheet" />
    <!-- include summernote css/js -->
    <link href="https://cdn.jsdelivr.net/npm/summernote@0.8.18/dist/summernote-lite.min.css" rel="stylesheet">
    <!-- Custom CSS -->
    <link rel="stylesheet" href="assets/css/index.css">
    <link rel="stylesheet" href="assets/css/products.css">
    <link rel="stylesheet" href="assets/css/staff.css">
    <link rel="stylesheet" href="assets/css/settings-improved.css">
    <link rel="stylesheet" href="assets/css/warehouse.css">
    <link rel="stylesheet" href="assets/css/order.css">
</head>

<body>
    <?php
    $pageName = substr($_SERVER['SCRIPT_NAME'], strrpos($_SERVER['SCRIPT_NAME'], "/") + 1);
    ?>
    <!-- Sidebar Navigation -->
    <nav id="sidebar">
        <div class="sidebar-header d-flex align-items-center justify-content-between">
            <div class="logo-wrapper">
                <img src="assets/AES.png" alt="AES Logo" class="logo-full">
            </div>

            <button class="btn btn-sm text-white d-none d-lg-block" onclick="toggleDesktopMenu()">
                <i class="bi bi-text-indent-left fs-4" id="toggleIcon"></i>
            </button>
        </div>

        <div class="mt-4">
            <a href="./" class="nav-link <?= $pageName == 'index.php' ? 'active' : '' ?>">
                <i class="bi bi-speedometer2"></i>
                <span class="nav-text">Dashboard</span>
            </a>
            <hr class="mx-3 opacity-25">

            <a href="products.php" class="nav-link <?= $pageName == 'products.php' ? 'active' : '' ?>">
                <i class="bi bi-box-seam"></i>
                <span class="nav-text">Products</span>
            </a>
            <a href="orders.php" class="nav-link <?= $pageName == 'orders.php' ? 'active' : '' ?>">
                <i class="bi bi-cart3"></i>
                <span class="nav-text">Orders</span>
            </a>
            <a href="#" class="nav-link">
                <i class="bi bi-people"></i>
                <span class="nav-text">Customers</span>
            </a>
            <a href="staff.php" class="nav-link <?= $pageName == 'staff.php' ? 'active' : '' ?>">
                <i class="bi bi-people"></i>
                <span class="nav-text">Staff</span>
            </a>
            <a href="warehouse.php" class="nav-link <?= $pageName == 'warehouse.php' ? 'active' : '' ?>">
                <i class="bi bi-graph-up"></i>
                <span class="nav-text">Warehouse</span>
            </a>
            <a href="settings.php" class="nav-link <?= $pageName == 'settings.php' ? 'active' : '' ?>">
                <i class="bi bi-gear"></i>
                <span class="nav-text">Settings</span>
            </a>
        </div>
    </nav>
    <!-- Main Content Area -->
    <main id="main-content">
        <?php include 'top-nav.php' ?>