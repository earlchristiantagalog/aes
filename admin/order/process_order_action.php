<?php
// Ensure no extra whitespace before this tag to avoid JSON errors
header('Content-Type: application/json');

// Check the path to your vendor folder - if this file is in a subfolder, use ../vendor/autoload.php
require 'vendor/autoload.php';

use PHPMailer\PHPMailer\PHPMailer;
use PHPMailer\PHPMailer\Exception;

$conn = mysqli_connect("localhost", "root", "", "aes");

$order_id = $_POST['order_id'] ?? null;
$action = $_POST['action'] ?? null;

// Note: I matched 'accept' case-sensitivity with your JS call
if ($order_id && ($action === 'accept' || $action === 'Accept')) {

    // 1. Update Order Status
    $stmt = $conn->prepare("UPDATE orders SET order_status = 'Processing', confirmed_at = NOW() WHERE id = ?");
    $stmt->bind_param("i", $order_id);

    if ($stmt->execute()) {
        // 2. Get User Email and Order Number
        $query = "SELECT u.email, o.order_number FROM orders o JOIN users u ON o.user_id = u.id WHERE o.id = ?";
        $user_stmt = $conn->prepare($query);
        $user_stmt->bind_param("i", $order_id);
        $user_stmt->execute();
        $user_data = $user_stmt->get_result()->fetch_assoc();

        if ($user_data) {
            $mail = new PHPMailer(true);

            try {
                // Server settings
                $mail->isSMTP();
                $mail->Host       = 'smtp.gmail.com'; // Replace with your SMTP provider
                $mail->SMTPAuth   = true;
                $mail->Username   = 'aralineducationalsupplies@gmail.com'; // Your email
                $mail->Password   = 'nboy xdip jltu neab';   // Your App Password (not your login password)
                $mail->SMTPSecure = PHPMailer::ENCRYPTION_STARTTLS;
                $mail->Port       = 587;

                // Recipients
                $mail->setFrom('noreply@aes.com', 'AES');
                $mail->addAddress($user_data['email']);

                // Content
                $mail->isHTML(true);
                $mail->Subject = "Your Order #{$user_data['order_number']} has been accepted!";
                $mail->Body    = "<h3>Great news!</h3><p>We have accepted your order <b>#{$user_data['order_number']}</b> and it is now being processed.</p>";
                $mail->AltBody = "Hi! We've accepted your order #{$user_data['order_number']} and it's now being processed.";

                $mail->send();
                echo json_encode(['success' => true]);
            } catch (Exception $e) {
                // Order updated but email failed
                echo json_encode(['success' => true, 'message' => 'Order updated, but email could not be sent. Mailer Error: ' . $mail->ErrorInfo]);
            }
        } else {
            echo json_encode(['success' => false, 'message' => 'User data not found']);
        }
    } else {
        echo json_encode(['success' => false, 'message' => 'Database update failed']);
    }
} else {
    echo json_encode(['success' => false, 'message' => 'Invalid Request']);
}
