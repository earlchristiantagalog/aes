<?php
// settings/delete_shipping.php
include '../includes/db_connection.php'; // adjust path as needed

if (isset($_GET['id'])) {
    $id = intval($_GET['id']);
    $conn->query("DELETE FROM shipping_zones WHERE id = $id");
}

// Redirect back to the settings page and open the correct tab
header("Location: ../admin_settings.php#shipping-settings");
exit;
