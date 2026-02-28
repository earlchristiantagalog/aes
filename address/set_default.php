<?php
session_start();
include '../db/db.php';

if (isset($_SESSION['user_id']) && isset($_GET['id'])) {
    $user_id = $_SESSION['user_id'];
    $address_id = $_GET['id'];

    // Start a transaction to ensure both updates happen together
    $conn->begin_transaction();

    try {
        // 1. Remove default status from all of this user's addresses
        $stmt1 = $conn->prepare("UPDATE user_addresses SET is_default = 0 WHERE user_id = ?");
        $stmt1->bind_param("i", $user_id);
        $stmt1->execute();

        // 2. Set the selected address as default
        $stmt2 = $conn->prepare("UPDATE user_addresses SET is_default = 1 WHERE id = ? AND user_id = ?");
        $stmt2->bind_param("ii", $address_id, $user_id);
        $stmt2->execute();

        $conn->commit();
        header("Location: ../profile.php?tab=addresses&msg=default_success");
    } catch (Exception $e) {
        $conn->rollback();
        header("Location: ../profile.php?tab=addresses&msg=error");
    }
} else {
    header("Location: ../auth/login.php");
}
exit();
