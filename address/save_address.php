<?php
session_start();
include '../db/db.php'; // Path to your DB connection

if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_SESSION['user_id'])) {
    $user_id    = $_SESSION['user_id'];
    $label      = mysqli_real_escape_string($conn, $_POST['label']);
    $first_name = mysqli_real_escape_string($conn, $_POST['first_name']);
    $last_name  = mysqli_real_escape_string($conn, $_POST['last_name']);
    $line1      = mysqli_real_escape_string($conn, $_POST['line1']);
    $province   = mysqli_real_escape_string($conn, $_POST['province']);
    $city       = mysqli_real_escape_string($conn, $_POST['city']);
    $barangay   = mysqli_real_escape_string($conn, $_POST['barangay']);
    $zip        = mysqli_real_escape_string($conn, $_POST['zip']);
    $phone      = mysqli_real_escape_string($conn, $_POST['phone']);
    $is_default = isset($_POST['is_default']) ? 1 : 0;

    // Start Transaction
    mysqli_begin_transaction($conn);

    try {
        // If this is set as default, remove default status from all other addresses for this user
        if ($is_default == 1) {
            mysqli_query($conn, "UPDATE user_addresses SET is_default = 0 WHERE user_id = $user_id");
        }

        $sql = "INSERT INTO user_addresses (user_id, label, first_name, last_name, address_line, province, city, barangay, zip_code, phone, is_default) 
                VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?)";

        $stmt = mysqli_prepare($conn, $sql);
        mysqli_stmt_bind_param($stmt, "isssssssssi", $user_id, $label, $first_name, $last_name, $line1, $province, $city, $barangay, $zip, $phone, $is_default);

        if (mysqli_stmt_execute($stmt)) {
            mysqli_commit($conn);
            header("Location: ../profile.php?msg=added"); // Redirect back to profile
        } else {
            throw new Exception("Error inserting record");
        }
    } catch (Exception $e) {
        mysqli_rollback($conn);
        header("Location: profile.php?error=failed");
    }
    exit();
}
