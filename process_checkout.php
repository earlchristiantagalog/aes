<?php
session_start();
include 'db/db.php';

header('Content-Type: application/json');

/* ============================
   SECURITY CHECK
============================ */

if (!isset($_SESSION['user_id'])) {
    echo json_encode([
        "success" => false,
        "message" => "User not logged in"
    ]);
    exit;
}

$user_id = $_SESSION['user_id'];

/* ============================
   GENERATE ORDER NUMBER
============================ */

function generateOrderNumber()
{
    return "AES-" . date("Ymd") . "-" . rand(10000, 99999);
}

$order_number = generateOrderNumber();

/* ============================
   GET FORM DATA
============================ */

$address_id = intval($_POST['address_id'] ?? 0);
$payment_method = $_POST['payment'] ?? '';
$shipping_id = intval($_POST['shipping'] ?? 0);

$subtotal = floatval($_POST['subtotal']);
$discount = floatval($_POST['discount'] ?? 0);
$total_amount = floatval($_POST['final_total']);

/* ============================
   GET SHIPPING FEE
============================ */

$ship = mysqli_query(
    $conn,
    "SELECT shipping_price FROM shipping_zones WHERE id='$shipping_id' LIMIT 1"
);

$shipping = mysqli_fetch_assoc($ship);
$shipping_fee = $shipping['shipping_price'] ?? 0;

/* ============================
   PAYMENT STATUS LOGIC
============================ */

$payment_reference = NULL;
$confirmed_at = NULL;

if ($payment_method == "cod") {
    $payment_reference = "COD";
}

if ($payment_method == "bank-transfer") {
    $payment_reference = "PENDING";
}

/* ============================
   RECEIPT UPLOAD
============================ */

$receipt_path = NULL;

if ($payment_method == "bank-transfer" && isset($_FILES['payment_receipt'])) {

    if ($_FILES['payment_receipt']['error'] == 0) {

        $uploadDir = "uploads/receipts/";

        if (!is_dir($uploadDir)) {
            mkdir($uploadDir, 0777, true);
        }

        $filename = time() . '_' . basename($_FILES['payment_receipt']['name']);
        $targetFile = $uploadDir . $filename;

        move_uploaded_file(
            $_FILES['payment_receipt']['tmp_name'],
            $targetFile
        );

        $receipt_path = $targetFile;
    }
}

/* ============================
   INSERT ORDER
============================ */

$stmt = $conn->prepare("
INSERT INTO orders
(user_id,order_number,address_id,payment_method,
 shipping_id,subtotal,shipping_fee,discount,total_amount,
 payment_reference,confirmed_at,receipt_image)
VALUES (?,?,?,?,?,?,?,?,?,?,?,?)
");

$stmt->bind_param(
    "isisiidddsss",
    $user_id,
    $order_number,
    $address_id,
    $payment_method,
    $shipping_id,
    $subtotal,
    $shipping_fee,
    $discount,
    $total_amount,
    $payment_reference,
    $confirmed_at,
    $receipt_path
);

$stmt->execute();

$order_id = $stmt->insert_id;

/* ============================
   INSERT ORDER ITEMS (BUY NOW)
============================ */

if (isset($_GET['id']) && isset($_GET['qty'])) {

    $product_id = intval($_GET['id']);
    $qty = intval($_GET['qty']);
    $variant = $_GET['variants'] ?? '';

    $product = mysqli_fetch_assoc(mysqli_query(
        $conn,
        "SELECT name, base_price FROM products WHERE id=$product_id"
    ));

    $price = $product['base_price'];
    $total = $price * $qty;

    $item = $conn->prepare("
        INSERT INTO order_items
        (order_id,product_id,product_name,variant,price,quantity,total)
        VALUES (?,?,?,?,?,?,?)
    ");

    $item->bind_param(
        "iissdii",
        $order_id,
        $product_id,
        $product['name'],
        $variant,
        $price,
        $qty,
        $total
    );

    $item->execute();
}

/* ============================
   CLEAR CHECKOUT SESSION
============================ */

unset($_SESSION['checkout_data']);
unset($_SESSION['applied_voucher']);

/* ============================
   SUCCESS RESPONSE
============================ */

echo json_encode([
    "success" => true,
    "order_id" => $order_id,
    "order_number" => $order_number
]);
