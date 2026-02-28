<?php
session_start();
include '../db/db.php';

// Import PHPMailer classes
use PHPMailer\PHPMailer\PHPMailer;
use PHPMailer\PHPMailer\Exception;

require 'vendor/autoload.php'; // Adjust path to your PHPMailer location

if (isset($_POST['register_btn'])) {
    // 1. Data Sanitization
    $full_name = mysqli_real_escape_string($conn, $_POST['full_name']);
    $email     = mysqli_real_escape_string($conn, $_POST['email']);
    $password  = $_POST['password'];
    $cpassword = $_POST['confirm_password'];

    // 2. Basic Validation
    if ($password !== $cpassword) {
        die("Passwords do not match!");
    }

    // Check if email exists
    $check_email = mysqli_query($conn, "SELECT email FROM users WHERE email='$email'");
    if (mysqli_num_rows($check_email) > 0) {
        die("Email already registered!");
    }


    // 3. Prepare Data
    $account_no = rand(100000, 999999);
    $hashed_password = password_hash($password, PASSWORD_DEFAULT);
    $otp = rand(111111, 999999);

    // Set Expiry: Current time + 5 minutes
    $expiry_time = date("Y-m-d H:i:s", strtotime('+5 minutes'));

    // 4. Update Insert Query to include otp_expiry
    $query = "INSERT INTO users (account_no, full_name, email, password, otp, otp_expiry, status) 
              VALUES ('$account_no', '$full_name', '$email', '$hashed_password', '$otp', '$expiry_time', 'pending')";
    if (mysqli_query($conn, $query)) {
        // 5. Send Email via PHPMailer
        $mail = new PHPMailer(true);

        try {
            // Server settings
            $mail->isSMTP();
            $mail->Host       = 'smtp.gmail.com'; // Your SMTP provider
            $mail->SMTPAuth   = true;
            $mail->Username   = 'aralineducationalsupplies@gmail.com'; // Your email
            $mail->Password   = 'nboy xdip jltu neab';   // Your app password
            $mail->SMTPSecure = PHPMailer::ENCRYPTION_STARTTLS;
            $mail->Port       = 587;

            // Recipients
            $mail->setFrom('aralineducationalsupplies@gmail.com', 'Aralin Educational Supplies (AES)');
            $mail->addAddress($email, $full_name);

            // Content
            $mail->isHTML(true);
            $mail->Subject = 'Verify Your Account';
            $mail->Body = '
            <!DOCTYPE html>
            <html lang="en">
            <head>
            <meta charset="UTF-8">
            <meta name="viewport" content="width=device-width, initial-scale=1.0">
            <title>Verify Your Account</title>
            </head>
            <body style="margin:0; padding:0; background-color:#0a0a0f; font-family: Georgia, serif;">

            <!-- Outer wrapper -->
            <table width="100%" cellpadding="0" cellspacing="0" border="0"
                style="background-color:#0a0a0f; padding: 48px 16px;">
                <tr>
                <td align="center">

                    <!-- Card -->
                    <table width="520" cellpadding="0" cellspacing="0" border="0"
                    style="max-width:520px; width:100%; background-color:#111118;
                            border-radius:16px; overflow:hidden;
                            border: 1px solid #1e1e2e;">

                    <!-- Top accent bar -->
                    <tr>
                        <td style="height:4px; background: #1e3a5f;"></td>
                    </tr>

                    <!-- Header -->
                    <tr>
                        <td align="center" style="padding: 48px 48px 0 48px;">
                        <!-- Logo mark -->
                        <div style="display:inline-block; margin-bottom: 28px;">
                            <table cellpadding="0" cellspacing="0" border="0">
                            <tr>
                                <td align="center"
                                style="width:56px; height:56px; background: linear-gradient(135deg,#1e3a5f,#ec4899);
                                        border-radius:14px; text-align:center; vertical-align:middle;">
                                <span style="font-size:26px; line-height:56px;">✦</span>
                                </td>
                            </tr>
                            </table>
                        </div>

                        <!-- Heading -->
                        <h1 style="margin:0 0 8px 0; font-size:26px; font-weight:700; letter-spacing:-0.5px;
                                    color:#f0f0ff; font-family: Georgia, serif;">
                            Verify your account
                        </h1>
                        <p style="margin:0; font-size:15px; color:#6b6b8a; font-family: Georgia, serif;
                                    line-height:1.5;">
                            One quick step to get you in
                        </p>
                        </td>
                    </tr>

                    <!-- Divider -->
                    <tr>
                        <td style="padding: 32px 48px 0 48px;">
                        <div style="height:1px; background-color:#1e1e2e;"></div>
                        </td>
                    </tr>

                    <!-- Body -->
                    <tr>
                        <td style="padding: 32px 48px;">
                        <p style="margin:0 0 24px 0; font-size:15px; color:#a0a0c0;
                                    line-height:1.7; font-family: Poppins, serif;">
                            Hi <strong style="color:#e0e0f0;">' . $full_name . '</strong>,
                            welcome aboard. Use the code below to confirm your email
                            address and activate your account.
                        </p>

                        <!-- OTP Box -->
                        <table width="100%" cellpadding="0" cellspacing="0" border="0">
                            <tr>
                            <td align="center"
                                style="background: linear-gradient(135deg, #1a1a2e, #16162a);
                                    border: 1px solid #2e2e4e;
                                    border-radius:12px; padding: 28px 24px;">

                                <p style="margin:0 0 12px 0; font-size:11px; font-weight:600;
                                            letter-spacing:3px; text-transform:uppercase;
                                            color:#6b6b8a; font-family: Georgia, serif;">
                                Verification Code
                                </p>

                                <p style="margin:0; font-size:42px; font-weight:800;
                                            letter-spacing:10px; color:#f0f0ff;
                                            font-family: \'Courier New\', monospace;
                                            text-shadow: 0 0 30px rgba(52, 70, 238, 0.4);">
                                ' . $otp . '
                                </p>

                                <p style="margin:12px 0 0 0; font-size:12px; color:#4a4a6a;
                                            font-family: Georgia, serif;">
                                Expires in 10 minutes
                                </p>
                            </td>
                            </tr>
                        </table>

                        <!-- Warning note -->
                        <table width="100%" cellpadding="0" cellspacing="0" border="0"
                            style="margin-top: 24px;">
                            <tr>
                            <td style="background-color:#1a1020; border-left:3px solid #1e3a5f;
                                        border-radius:0 8px 8px 0; padding:14px 16px;">
                                <p style="margin:0; font-size:13px; color:#7a6a9a;
                                            line-height:1.6; font-family: Georgia, serif;">
                                <strong style="color:#9d7fc4;">Didn\'t request this?</strong>
                                You can safely ignore this email. Someone may have typed
                                your email address by mistake.
                                </p>
                            </td>
                            </tr>
                        </table>
                        </td>
                    </tr>

                    <!-- Divider -->
                    <tr>
                        <td style="padding: 0 48px;">
                        <div style="height:1px; background-color:#1e1e2e;"></div>
                        </td>
                    </tr>

                    <!-- Footer -->
                    <tr>
                        <td style="padding: 24px 48px 40px 48px;" align="center">
                        <p style="margin:0; font-size:12px; color:#3a3a5a;
                                    line-height:1.6; font-family: Georgia, serif;">
                            This message was sent to you because you created an account.<br>
                            If you have questions, contact
                            <a href="mailto:support@yourdomain.com"
                            style="color:#1e3a5f; text-decoration:none;">
                            support@yourdomain.com
                            </a>
                        </p>
                        </td>
                    </tr>

                    </table>
                    <!-- /Card -->

                </td>
                </tr>
            </table>

            </body>
            </html>
            ';

            $mail->send();

            // Store email in session to identify user on verify.php
            $_SESSION['email_to_verify'] = $email;
            header("Location: verify.php");
            exit();
        } catch (Exception $e) {
            echo "Message could not be sent. Mailer Error: {$mail->ErrorInfo}";
        }
    } else {
        echo "Registration failed: " . mysqli_error($conn);
    }
}
