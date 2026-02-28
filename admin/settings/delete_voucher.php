<?php
$conn = mysqli_connect('localhost', 'root', '', 'aes');

header('Content-Type: application/json');

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    if (isset($_POST['id'])) {
        $id = (int)$_POST['id'];

        // Delete the voucher
        $query = "DELETE FROM aes.vouchers WHERE id = $id";

        if (mysqli_query($conn, $query)) {
            echo json_encode(['success' => true, 'message' => 'Voucher deleted.']);
        } else {
            echo json_encode(['success' => false, 'message' => 'Failed to delete voucher.']);
        }
    } else {
        echo json_encode(['success' => false, 'message' => 'No ID provided.']);
    }
} else {
    echo json_encode(['success' => false, 'message' => 'Invalid request method.']);
}
