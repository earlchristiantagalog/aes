<?php
include '../../db/db.php';

// Include PHPMailer classes
use PHPMailer\PHPMailer\PHPMailer;
use PHPMailer\PHPMailer\Exception;

// Check path: if vendor is in the same folder as staff.php
require __DIR__ . '/vendor/autoload.php';

if (isset($_POST['submit_staff'])) {
    $fname  = mysqli_real_escape_string($conn, $_POST['first_name']);
    $lname  = mysqli_real_escape_string($conn, $_POST['last_name']);
    $email  = mysqli_real_escape_string($conn, $_POST['email']);
    $phone  = mysqli_real_escape_string($conn, $_POST['phone']);
    $dept   = mysqli_real_escape_string($conn, $_POST['department']);
    $status = mysqli_real_escape_string($conn, $_POST['status']);

    $staff_id = "STF-" . rand(1000, 9999);
    $raw_password = bin2hex(random_bytes(4));
    $hashed_password = password_hash($raw_password, PASSWORD_DEFAULT);

    $sql = "INSERT INTO staff (first_name, last_name, email, phone, department, status, staff_id, password) 
            VALUES ('$fname', '$lname', '$email', '$phone', '$dept', '$status', '$staff_id', '$hashed_password')";

    if (mysqli_query($conn, $sql)) {
        $mail = new PHPMailer(true);
        try {
            $mail->isSMTP();
            $mail->Host       = 'smtp.gmail.com';
            $mail->SMTPAuth   = true;
            $mail->Username   = 'aralineducationalsupplies@gmail.com';
            $mail->Password   = 'qvcp gagn asbz atvx';
            $mail->SMTPSecure = PHPMailer::ENCRYPTION_STARTTLS;
            $mail->Port       = 587;

            $mail->setFrom('aralineducationalsupplies@gmail.com', 'AES HR');
            $mail->addAddress($email);
            $mail->isHTML(true);
            $mail->Subject = 'Your Staff Account Credentials';
            $mail->Body    = "<h3>Welcome, $fname!</h3><p>ID: $staff_id<br>Pass: $raw_password</p>";
            $mail->send();

            header("Location: ../staff.php");
            $status = "success";
            exit();
        } catch (Exception $e) {
            header("Location: ../staff.php?status=mail_error");
            exit();
        }
    } else {
        die("Error: " . mysqli_error($conn));
    }
}
