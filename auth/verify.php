<?php
session_start();
include '../db/db.php';

// Initialize variables to avoid "Undefined variable" warnings
$success = false;
$error = "";

// 1. Handle the Verification Form Submission
if (isset($_POST['verify_btn'])) {
    $otp_input = mysqli_real_escape_string($conn, $_POST['otp']);
    $email = $_SESSION['email_to_verify'] ?? '';

    $query = "SELECT * FROM users WHERE email='$email'";
    $result = mysqli_query($conn, $query);
    $user = mysqli_fetch_assoc($result);

    if ($user) {
        $current_time = date("Y-m-d H:i:s");

        if ($user['otp'] != $otp_input) {
            $error = "Invalid OTP code!";
        } elseif ($current_time > $user['otp_expiry']) {
            $error = "OTP has expired! Please register again.";
        } else {
            // SUCCESS LOGIC
            mysqli_query($conn, "UPDATE users SET status='active', otp=0 WHERE email='$email'");
            unset($_SESSION['email_to_verify']);
            $success = true; // This triggers your Success Card
        }
    } else {
        $error = "User session not found.";
    }
}

// 2. Calculate remaining time for the JS timer
$remaining_seconds = 0;
// We only need to calculate this if we aren't already successful
if (!$success) {
    if (isset($_SESSION['email_to_verify'])) {
        $email = $_SESSION['email_to_verify'];
        $res = mysqli_query($conn, "SELECT otp_expiry FROM users WHERE email='$email'");
        $user_data = mysqli_fetch_assoc($res);

        if ($user_data) {
            $expiry_ts = strtotime($user_data['otp_expiry']);
            $current_ts = time();
            $remaining_seconds = max(0, $expiry_ts - $current_ts);
        }
    } else {
        // Redirect if no session exists and no success state
        header("Location: login.php");
        exit();
    }
}
?>
<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Verify Your Email</title>
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link href="https://fonts.googleapis.com/css2?family=Cormorant+Garamond:wght@400;600;700&family=DM+Sans:wght@300;400;500&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="../assets/css/verify.css">
</head>

<body>

    <!-- Particles -->
    <div class="particles" aria-hidden="true">
        <?php
        $positions = [
            [10, 15, 3, 0],
            [25, 70, 5, 1],
            [80, 20, 4, 0.5],
            [60, 85, 3, 1.5],
            [90, 55, 6, 0.8],
            [40, 10, 4, 2],
            [15, 90, 3, 0.3],
            [70, 40, 5, 1.2],
            [35, 60, 3, 0.7],
            [55, 25, 4, 1.8]
        ];
        foreach ($positions as $p) {
            echo "<div class='particle' style='left:{$p[0]}%;top:{$p[1]}%;--dur:{$p[2]}s;--delay:{$p[3]}s'></div>";
        }
        ?>
    </div>

    <?php if ($success): ?>
        <div class="card">
            <div class="card-banner" style="padding-bottom:28px;">
                <div class="shield-icon">
                    <svg viewBox="0 0 52 52" fill="none" xmlns="http://www.w3.org/2000/svg">
                        <path d="M26 4L8 11v14c0 10.5 7.7 20.3 18 22.7C36.3 45.3 44 35.5 44 25V11L26 4z" fill="rgba(255,215,0,0.15)" stroke="#FFD700" stroke-width="1.5" />
                    </svg>
                </div>
                <h1>Email Verified</h1>
                <p>Your account is now active</p>
            </div>
            <div class="accent-line"></div>
            <div class="card-body">
                <div class="success-overlay">
                    <div class="success-check">
                        <svg width="32" height="32" viewBox="0 0 32 32" fill="none">
                            <path d="M8 16.5l5.5 5.5 10.5-11" stroke="#FFD700" stroke-width="2.5" stroke-linecap="round" stroke-linejoin="round" />
                        </svg>
                    </div>
                    <h2>Verification Successful!</h2>
                    <p>Your identity has been confirmed. Redirecting you to login…</p>
                    <a href="login.php" class="btn-verify" style="display:flex;align-items:center;justify-content:center;text-decoration:none;">
                        <span class="btn-dot"></span> Go to Login
                    </a>
                </div>
            </div>
        </div>
        <script>
            setTimeout(() => window.location = 'login.php', 2800);
        </script>

    <?php else: ?>
        <div class="card">
            <?php if ($error): ?>
                <div style="color: #c53030; background: rgba(197, 48, 48, 0.1); padding: 10px; border-radius: 8px; margin-bottom: 15px; text-align: center;">
                    <?php echo $error; ?>
                </div>
            <?php endif; ?>
            <!-- VERIFY FORM -->
            <div class="card">
                <div class="card-banner">
                    <div class="shield-icon">
                        <svg viewBox="0 0 52 52" fill="none" xmlns="http://www.w3.org/2000/svg">
                            <path d="M26 4L8 11v14c0 10.5 7.7 20.3 18 22.7C36.3 45.3 44 35.5 44 25V11L26 4z" fill="rgba(255,215,0,0.15)" stroke="#FFD700" stroke-width="1.5" />
                            <path d="M26 20v6M26 30h.01" stroke="#87CEEB" stroke-width="2" stroke-linecap="round" />
                        </svg>
                    </div>
                    <h1>Check Your Email</h1>
                    <p>We sent a 6-digit code to <span><?php echo htmlspecialchars($_SESSION['email_to_verify']); ?></span></p>
                </div>
                <div class="accent-line"></div>

                <div class="card-body">

                    <?php if ($error): ?>
                        <div class="error-msg">
                            <svg width="16" height="16" viewBox="0 0 16 16" fill="none">
                                <circle cx="8" cy="8" r="7" stroke="#c53030" stroke-width="1.5" />
                                <path d="M8 5v4M8 11h.01" stroke="#c53030" stroke-width="1.5" stroke-linecap="round" />
                            </svg>
                            <p><?php echo htmlspecialchars($error); ?></p>
                        </div>
                    <?php endif; ?>

                    <form method="POST" id="otpForm">
                        <input type="hidden" name="otp" id="otp-hidden">
                        <input type="hidden" name="verify_btn" value="1">

                        <label class="otp-label">Verification Code</label>

                        <div class="otp-inputs" id="otp-boxes">
                            <input type="text" class="otp-digit" maxlength="1" inputmode="numeric" pattern="[0-9]" autocomplete="off">
                            <input type="text" class="otp-digit" maxlength="1" inputmode="numeric" pattern="[0-9]" autocomplete="off">
                            <input type="text" class="otp-digit" maxlength="1" inputmode="numeric" pattern="[0-9]" autocomplete="off">
                            <input type="text" class="otp-digit" maxlength="1" inputmode="numeric" pattern="[0-9]" autocomplete="off">
                            <input type="text" class="otp-digit" maxlength="1" inputmode="numeric" pattern="[0-9]" autocomplete="off">
                            <input type="text" class="otp-digit" maxlength="1" inputmode="numeric" pattern="[0-9]" autocomplete="off">
                        </div>

                        <button type="submit" class="btn-verify" id="submitBtn" disabled>
                            <span class="btn-dot"></span> Verify My Account
                        </button>
                    </form>

                    <div class="resend-row">
                        <p>Didn't receive the code?</p>
                        <a href="resend-otp.php">Resend code</a>
                    </div>

                    <div class="timer">
                        Code expires in <span class="timer-badge" id="countdown">09:59</span>
                    </div>

                </div>
            </div>
        <?php endif; ?>
        <script src="../assets/js/verify.js"></script>
        <script>
            // ── Countdown timer ──────────────────────────────────────────────
            const countdownEl = document.getElementById('countdown');
            const verifyBtn = document.getElementById('verifyBtn');

            if (countdownEl) {
                // Pass the PHP calculation to JS
                let total = <?php echo $remaining_seconds; ?>;

                const tick = () => {
                    if (total <= 0) {
                        countdownEl.textContent = 'Expired';
                        countdownEl.style.color = '#c53030';
                        if (verifyBtn) verifyBtn.disabled = true;
                        return;
                    }

                    const m = String(Math.floor(total / 60)).padStart(2, '0');
                    const s = String(total % 60).padStart(2, '0');
                    countdownEl.textContent = `${m}:${s}`;

                    total--;
                    setTimeout(tick, 1000);
                };

                tick();
            }
        </script>
</body>

</html>