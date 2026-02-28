<?php
$conn = mysqli_connect('localhost', 'root', '', 'aes');

// Set header so JavaScript knows we are sending JSON
header('Content-Type: application/json');

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    // We use 'key' because that's what your JavaScript FormData appends
    if (isset($_POST['key']) && isset($_POST['status'])) {

        $method_key = mysqli_real_escape_string($conn, $_POST['key']);
        $status = (int)$_POST['status'];

        $query = "UPDATE payment_settings SET is_active = $status WHERE method_key = '$method_key'";

        if (mysqli_query($conn, $query)) {
            echo json_encode(['success' => true]);
        } else {
            echo json_encode(['success' => false, 'error' => mysqli_error($conn)]);
        }
    } else {
        echo json_encode(['success' => false, 'error' => 'Missing parameters']);
    }
    exit;
}
