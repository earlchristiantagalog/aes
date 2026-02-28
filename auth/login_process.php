<?php
session_start();
include '../db/db.php';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $credential = trim($_POST['credential']);
    $password = $_POST['password'];

    // 1. Check Staff Table first
    $stmt = $conn->prepare("SELECT id, staff_id, first_name, last_name, password, department FROM staff WHERE staff_id = ? LIMIT 1");
    $stmt->bind_param("s", $credential);
    $stmt->execute();
    $result = $stmt->get_result();
    $userData = $result->fetch_assoc();

    if ($userData) {
        $role = 'staff';
    } else {
        // 2. Check Users Table if not staff
        $stmt = $conn->prepare("SELECT id, email, full_name, password FROM users WHERE email = ? LIMIT 1");
        $stmt->bind_param("s", $credential);
        $stmt->execute();
        $result = $stmt->get_result();
        $userData = $result->fetch_assoc();
        $role = 'user';
    }

    // 3. Verify Password
    if ($userData && password_verify($password, $userData['password'])) {
        session_regenerate_id(true);

        $_SESSION['user_id'] = $userData['id'];
        $_SESSION['role']    = $role;

        if ($role === 'staff') {
            $_SESSION['email']     = $userData['staff_id'];
            $_SESSION['full_name'] = $userData['first_name'] . ' ' . $userData['last_name'];
            $dept                  = $userData['department']; // Fixed: Assigning to local variable for switch
            $_SESSION['dept']      = $dept;

            // --- DEPARTMENT BASED REDIRECT ---
            // Note: Adjust these paths based on where this handler is located
            switch ($dept) {
                case 'Owner':
                    header("Location: ../admin/index.php");
                    break;
                case 'Administrator':
                    header("Location: ../administrator/index.php");
                    break;
                case 'Human Resources':
                    header("Location: ../hr/dashboard.php");
                    break;
                case 'Accounting':
                    header("Location: ../accounting/dashboard.php");
                    break;
                case 'IT Support':
                    header("Location: ../tech/dashboard.php");
                    break;
                default:
                    header("Location: ../staff_dashboard.php");
                    break;
            }
        } else {
            // Role is 'user'
            $_SESSION['email']     = $userData['email'];
            $_SESSION['full_name'] = $userData['full_name'];
            header("Location: ../index.php");
        }
        exit();
    } else {
        header("Location: login.php?error=invalid");
        exit();
    }
}
