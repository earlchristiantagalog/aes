<?php
session_start();
include '../db/db.php';

if (isset($_SESSION['user_id']) && isset($_GET['id'])) {
    $user_id = $_SESSION['user_id'];
    $address_id = $_GET['id'];

    // Check if the address is a default address before deleting
    $check_sql = "SELECT is_default FROM user_addresses WHERE id = ? AND user_id = ?";
    $stmt = $conn->prepare($check_sql);
    $stmt->bind_param("ii", $address_id, $user_id);
    $stmt->execute();
    $result = $stmt->get_result();
    $address = $result->fetch_assoc();

    if ($address && $address['is_default'] == 1) {
        // Option: Prevent deleting default address
        header("Location: ../profile.php?tab=addresses&msg=cannot_delete_default");
    } else {
        // Proceed with deletion
        $del_stmt = $conn->prepare("DELETE FROM user_addresses WHERE id = ? AND user_id = ?");
        $del_stmt->bind_param("ii", $address_id, $user_id);

        if ($del_stmt->execute()) {
            header("Location: ../profile.php?tab=addresses&msg=delete_success");
        } else {
            header("Location: ../profile.php?tab=addresses&msg=error");
        }
    }
} else {
    header("Location: ../auth/login.php");
}
exit();
