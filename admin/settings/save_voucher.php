<?php
$conn = mysqli_connect('localhost', 'root', '', 'aes');
header('Content-Type: application/json');

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $code = mysqli_real_escape_string($conn, strtoupper(trim($_POST['code'])));
    $amount = (float)$_POST['amount'];
    $type = mysqli_real_escape_string($conn, $_POST['type']);
    $user_id = !empty($_POST['user_id']) ? (int)$_POST['user_id'] : 'NULL';
    $expiry = mysqli_real_escape_string($conn, $_POST['expiry']);

    // 1. Check if voucher code already exists
    $check = mysqli_query($conn, "SELECT id FROM aes.vouchers WHERE code = '$code'");
    if (mysqli_num_rows($check) > 0) {
        echo json_encode(['success' => false, 'message' => 'This voucher code already exists.']);
        exit;
    }

    // 2. Insert into database
    $query = "INSERT INTO aes.vouchers (code, discount_amount, discount_type, user_id, expiry_date) 
              VALUES ('$code', $amount, '$type', $user_id, '$expiry')";

    if (mysqli_query($conn, $query)) {
        echo json_encode(['success' => true, 'message' => 'Voucher created!']);
    } else {
        echo json_encode(['success' => false, 'message' => 'Database error: ' . mysqli_error($conn)]);
    }
} else {
    echo json_encode(['success' => false, 'message' => 'Invalid request method.']);
}
