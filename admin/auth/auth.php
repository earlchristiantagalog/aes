<?php
// session_start();

// 1. Check if user is logged in at all
if (!isset($_SESSION['user_id'])) {
    header("Location: ../auth/login.php?error=not_logged_in");
    exit();
}

// 2. Define which departments are allowed to access this specific page
$allowed_departments = [
    'Owner',
    'Administration',
    'Customer Service',
    'Sales',
    'IT/Engineering'
];

// 3. Check if the user's department is in the allowed list
if (!in_array($_SESSION['dept'], $allowed_departments)) {
    header("Location: ../auth/login.php?error=unauthorized");
    exit();
}

// If it reaches here, access is granted.
