<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Login - AES Aralin Educational Supplies</title>

    <!-- Bootstrap CSS -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet"
        integrity="sha384-QWTKZyjpPEjISv5WaRU9OFeRpok6YctnYmDr5pNlyT2bRjXh0JMhjY6hW+ALEwIH" crossorigin="anonymous">

    <!-- Bootstrap Icons -->
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.min.css">
    <link rel="stylesheet" href="../assets/css/login.css">
</head>

<body>
    <div class="login-container">
        <div class="login-card">
            <!-- Left Side - Branding -->
            <div class="login-brand">
                <div class="brand-content">
                    <div class="brand-logo">
                        <i class="bi bi-book-fill"></i>
                    </div>
                    <h1 class="brand-title">Welcome to AES</h1>
                    <p class="brand-subtitle">Your trusted partner in education. Quality supplies for every student's success.</p>

                    <div class="brand-features">
                        <div class="feature-item">
                            <div class="feature-icon">
                                <i class="bi bi-shield-check"></i>
                            </div>
                            <div class="feature-text">
                                <strong>Secure Shopping</strong>
                                <small>Protected transactions</small>
                            </div>
                        </div>
                        <div class="feature-item">
                            <div class="feature-icon">
                                <i class="bi bi-truck"></i>
                            </div>
                            <div class="feature-text">
                                <strong>Fast Delivery</strong>
                                <small>Free shipping over ₱2,000</small>
                            </div>
                        </div>
                        <div class="feature-item">
                            <div class="feature-icon">
                                <i class="bi bi-award"></i>
                            </div>
                            <div class="feature-text">
                                <strong>Quality Assured</strong>
                                <small>Trusted by 500+ schools</small>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Right Side - Login Form -->
            <div class="login-form-section">
                <a href="../" class="back-home">
                    <i class="bi bi-arrow-left"></i>
                    Back to Home
                </a>

                <div class="form-header">
                    <h2>Welcome Back!</h2>
                    <p>Sign in to your account or create a new one</p>
                </div>

                <!-- Tab Navigation -->
                <div class="auth-tabs">
                    <button class="auth-tab active" onclick="switchTab('login')">
                        <i class="bi bi-box-arrow-in-right"></i>
                        <span>Sign In</span>
                    </button>
                    <button class="auth-tab" onclick="switchTab('register')">
                        <i class="bi bi-person-plus"></i>
                        <span>Sign Up</span>
                    </button>
                </div>

                <?php if (isset($_GET['error'])): ?>
                    <div class="alert alert-danger d-flex align-items-center small" role="alert">
                        <i class="bi bi-exclamation-triangle-fill me-2"></i>
                        <div>
                            <?php
                            switch ($_GET['error']) {
                                case 'invalid':
                                    echo 'Invalid ID/Email or password. Please try again.';
                                    break;
                                case 'unauthorized':
                                    echo '<strong>Access Denied:</strong> Your account does not have permission to view that department page.';
                                    break;
                                case 'not_logged_in':
                                    echo 'Please log in to access your dashboard.';
                                    break;
                                case 'system':
                                    echo 'A system error occurred. Please contact IT support.';
                                    break;
                                default:
                                    echo 'An unexpected error occurred. Please try again.';
                            }
                            ?>
                        </div>
                    </div>
                <?php endif; ?>

                <!-- Login Form -->
                <div class="auth-form active" id="loginForm">
                    <form id="loginFormSubmit" action="login_process.php" method="POST">
                        <div class="form-group">
                            <label class="form-label">Email</label>
                            <div class="input-wrapper">
                                <i class="bi bi-person-badge input-icon"></i>
                                <input type="text"
                                    name="credential"
                                    class="form-control"
                                    placeholder="Enter email"
                                    required>
                            </div>
                        </div>

                        <div class="form-group">
                            <label class="form-label">Password</label>
                            <div class="input-wrapper">
                                <i class="bi bi-lock input-icon"></i>
                                <input type="password" name="password" class="form-control" id="loginPassword" placeholder="Enter your password" required>
                                <button type="button" class="password-toggle" onclick="togglePasswordVisibility('loginPassword', this)">
                                    <i class="bi bi-eye"></i>
                                </button>
                            </div>
                        </div>

                        <div class="form-options">
                            <div class="remember-me">
                                <input type="checkbox" id="remember">
                                <label for="remember">Remember me</label>
                            </div>
                            <a href="forgot-password.php" class="forgot-password">Forgot Password?</a>
                        </div>

                        <button type="submit" class="btn-login">
                            <i class="bi bi-box-arrow-in-right"></i> Sign In
                        </button>
                    </form>
                </div>

                <!-- Register Form -->
                <div class="auth-form" id="registerForm">
                    <form action="register.php" method="POST">
                        <div class="form-group">
                            <label class="form-label">Full Name</label>
                            <div class="input-wrapper">
                                <i class="bi bi-person input-icon"></i>
                                <input type="text"
                                    class="form-control"
                                    id="fullName"
                                    name="full_name"
                                    placeholder="John Doe"
                                    required>
                            </div>
                        </div>

                        <div class="form-group">
                            <label class="form-label">Email Address</label>
                            <div class="input-wrapper">
                                <i class="bi bi-envelope input-icon"></i>
                                <input type="email"
                                    class="form-control"
                                    id="registerEmail"
                                    name="email"
                                    placeholder="your.email@example.com"
                                    required>
                            </div>
                        </div>

                        <div class="form-group">
                            <label class="form-label">Password</label>
                            <div class="input-wrapper">
                                <i class="bi bi-lock input-icon"></i>
                                <input type="password"
                                    class="form-control"
                                    id="registerPassword"
                                    name="password"
                                    placeholder="Create a password"
                                    required>
                                <button type="button" class="password-toggle" onclick="togglePasswordVisibility('registerPassword', this)">
                                    <i class="bi bi-eye"></i>
                                </button>
                            </div>
                            <small class="text-muted" style="display: block; margin-top: 0.5rem;">
                                At least 8 characters with uppercase, lowercase, and number
                            </small>
                        </div>

                        <div class="form-group">
                            <label class="form-label">Confirm Password</label>
                            <div class="input-wrapper">
                                <i class="bi bi-lock input-icon"></i>
                                <input type="password"
                                    class="form-control"
                                    id="confirmPassword"
                                    placeholder="Confirm your password"
                                    name="confirm_password"
                                    required>
                                <button type="button" class="password-toggle" onclick="togglePasswordVisibility('confirmPassword', this)">
                                    <i class="bi bi-eye"></i>
                                </button>
                            </div>
                        </div>

                        <div class="form-group">
                            <div class="remember-me">
                                <input type="checkbox" id="terms" required>
                                <label for="terms">
                                    I agree to the <a href="#" style="color: var(--primary-color); text-decoration: none;">Terms & Conditions</a> and <a href="#" style="color: var(--primary-color); text-decoration: none;">Privacy Policy</a>
                                </label>
                            </div>
                        </div>

                        <button type="submit" name="register_btn" class="btn-login" id="registerBtn">
                            <i class="bi bi-person-plus"></i>
                            Create Account
                        </button>
                    </form>

                </div>
            </div>
        </div>
    </div>

    <!-- Bootstrap JS -->
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"
        integrity="sha384-YvpcrYf0tY3lHB60NNkmXc5s9fDVZLESaAA55NDzOxhy9GkcIdslK1eN7N6jIeHz"
        crossorigin="anonymous"></script>

    <script>
        // Tab Switching
        function switchTab(tab) {
            const loginTab = document.querySelector('.auth-tab:first-child');
            const registerTab = document.querySelector('.auth-tab:last-child');
            const loginForm = document.getElementById('loginForm');
            const registerForm = document.getElementById('registerForm');

            // Fix: Check if the PHP alert exists before trying to hide it
            const alertBox = document.querySelector('.alert');
            if (alertBox) {
                alertBox.style.display = 'none';
            }

            if (tab === 'login') {
                loginTab.classList.add('active');
                registerTab.classList.remove('active');
                loginForm.classList.add('active');
                registerForm.classList.remove('active');
            } else {
                loginTab.classList.remove('active');
                registerTab.classList.add('active');
                loginForm.classList.remove('active');
                registerForm.classList.add('active');
            }
        }

        // Password Toggle
        function togglePasswordVisibility(inputId, button) {
            const input = document.getElementById(inputId);
            const icon = button.querySelector('i');

            if (input.type === 'password') {
                input.type = 'text';
                icon.classList.remove('bi-eye');
                icon.classList.add('bi-eye-slash');
            } else {
                input.type = 'password';
                icon.classList.remove('bi-eye-slash');
                icon.classList.add('bi-eye');
            }
        }

        // // Login Form Validation & Submit
        // const loginFormSubmit = document.getElementById('loginFormSubmit');
        // const loginBtn = document.getElementById('loginBtn');
        // const errorAlert = document.getElementById('errorAlert');
        // const errorMessage = document.getElementById('errorMessage');

        // loginFormSubmit.addEventListener('submit', function(e) {
        //     e.preventDefault();

        //     // Reset error state
        //     errorAlert.style.display = 'none';

        //     const email = document.getElementById('loginEmail').value;
        //     const password = document.getElementById('loginPassword').value;

        //     // Basic validation
        //     if (!email || !password) {
        //         showError('Please fill in all fields');
        //         return;
        //     }

        //     if (!isValidEmail(email)) {
        //         showError('Please enter a valid email address');
        //         return;
        //     }

        //     // Show loading state
        //     loginBtn.classList.add('loading');
        //     loginBtn.innerHTML = '<div class="spinner"></div> Signing In...';

        //     // Simulate API call
        //     setTimeout(() => {
        //         // Reset button
        //         loginBtn.classList.remove('loading');
        //         loginBtn.innerHTML = '<i class="bi bi-box-arrow-in-right"></i> Sign In';
        //     }, 1500);
        // });

        function showError(message) {
            errorMessage.textContent = message;
            errorAlert.className = 'alert-custom alert-error';
            errorAlert.style.display = 'flex';
        }

        function showSuccess(message) {
            errorMessage.textContent = message;
            errorAlert.className = 'alert-custom alert-success';
            errorAlert.style.display = 'flex';
        }

        function isValidEmail(email) {
            return /^[^\s@]+@[^\s@]+\.[^\s@]+$/.test(email);
        }

        function isValidPassword(password) {
            // At least 8 characters, one uppercase, one lowercase, one number
            return password.length >= 8 &&
                /[A-Z]/.test(password) &&
                /[a-z]/.test(password) &&
                /[0-9]/.test(password);
        }



        // Clear error on input
        const allInputs = document.querySelectorAll('input[type="email"], input[type="password"], input[type="text"], input[type="tel"]');
        allInputs.forEach(input => {
            input.addEventListener('input', function() {
                errorAlert.style.display = 'none';
            });
        });
    </script>
</body>

</html>