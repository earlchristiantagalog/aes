<?php
// Database connection (adjust with your credentials)
$conn = new mysqli('localhost', 'root', '', 'aes');

if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

// 1. HANDLE ADDING
if (isset($_POST['save_shipping'])) {
    $name = $conn->real_escape_string($_POST['shipping_name']);
    $price = $conn->real_escape_string($_POST['shipping_price']);
    $courier = $conn->real_escape_string($_POST['shipping_courier']);

    $sql = "INSERT INTO shipping_zones (shipping_name, shipping_price, shipping_courier) 
            VALUES ('$name', '$price', '$courier')";

    if ($conn->query($sql)) {
        // Redirect to same page with a hash to keep the tab open
        header("Location: " . $_SERVER['PHP_SELF'] . "#shipping-settings");
        exit;
    }
}

// 2. HANDLE DELETING (If you want it in the same file)
if (isset($_GET['delete_shipping_id'])) {
    $id = intval($_GET['delete_shipping_id']);
    $conn->query("DELETE FROM shipping_zones WHERE id = $id");
    header("Location: " . $_SERVER['PHP_SELF'] . "#shipping");
    exit;
}

// Fetch all settings into an array
$settings = [];
$res = mysqli_query($conn, "SELECT method_key, is_active FROM payment_settings");
while ($row = mysqli_fetch_assoc($res)) {
    $settings[$row['method_key']] = $row['is_active'];
}
include 'includes/header.php';
?>
<!-- Settings Content -->
<div class="settings-container">
    <div class="settings-layout">
        <!-- Settings Sidebar -->
        <aside class="settings-sidebar">
            <div class="sidebar-inner">
                <div class="sidebar-section">
                    <div class="section-label">Personal</div>
                    <nav class="settings-nav">
                        <a href="#profile" class="nav-item active" onclick="showSettingsTab(event, 'profile')">
                            <i class="bi bi-person-circle"></i>
                            <span>Profile</span>
                        </a>
                        <a href="#account" class="nav-item" onclick="showSettingsTab(event, 'account')">
                            <i class="bi bi-shield-lock"></i>
                            <span>Security</span>
                        </a>
                        <a href="#notifications" class="nav-item" onclick="showSettingsTab(event, 'notifications')">
                            <i class="bi bi-bell"></i>
                            <span>Notifications</span>
                        </a>
                        <a href="#appearance" class="nav-item" onclick="showSettingsTab(event, 'appearance')">
                            <i class="bi bi-palette"></i>
                            <span>Appearance</span>
                        </a>
                    </nav>
                </div>

                <div class="sidebar-section">
                    <div class="section-label">Business</div>
                    <nav class="settings-nav">
                        <a href="#business" class="nav-item" onclick="showSettingsTab(event, 'business')">
                            <i class="bi bi-building"></i>
                            <span>Business Info</span>
                        </a>
                        <a href="#shipping" class="nav-item" onclick="showSettingsTab(event, 'shipping')">
                            <i class="bi bi-truck"></i>
                            <span>Shipping</span>
                        </a>
                        <a href="#payment" class="nav-item" onclick="showSettingsTab(event, 'payment')">
                            <i class="bi bi-credit-card"></i>
                            <span>Payments</span>
                        </a>
                        <a href="#vouchers" class="nav-item" onclick="showSettingsTab(event, 'vouchers')">
                            <i class="bi bi-gift"></i>
                            <span>Vouchers</span>
                        </a>
                    </nav>
                </div>

                <div class="sidebar-section">
                    <div class="section-label">Marketing</div>
                    <nav class="settings-nav">
                        <a href="#announcement" class="nav-item" onclick="showSettingsTab(event, 'announcement')">
                            <i class="bi bi-megaphone"></i>
                            <span>Announcements</span>
                        </a>
                        <a href="#flashsale" class="nav-item" onclick="showSettingsTab(event, 'flashsale')">
                            <i class="bi bi-lightning-charge"></i>
                            <span>Flash Sales</span>
                        </a>
                    </nav>
                </div>

                <div class="sidebar-section">
                    <div class="section-label">Advanced</div>
                    <nav class="settings-nav">
                        <a href="#integrations" class="nav-item" onclick="showSettingsTab(event, 'integrations')">
                            <i class="bi bi-plug"></i>
                            <span>Integrations</span>
                        </a>
                        <a href="#preferences" class="nav-item" onclick="showSettingsTab(event, 'preferences')">
                            <i class="bi bi-sliders"></i>
                            <span>Preferences</span>
                        </a>
                    </nav>
                </div>
            </div>
        </aside>

        <!-- Settings Content Area -->
        <div class="settings-content">
            <!-- Profile Settings -->
            <div id="profile-settings" class="settings-panel">
                <div class="panel-header">
                    <h2 class="panel-title">Profile Information</h2>
                    <p class="panel-description">Update your personal information and profile picture</p>
                </div>

                <div class="settings-card">
                    <div class="card-section">
                        <label class="form-label">Profile Picture</label>
                        <div class="profile-upload">
                            <div class="profile-preview">
                                <img src="https://ui-avatars.com/api/?name=Admin+User&background=2563eb&color=fff&size=120" id="profilePreview" alt="Profile">
                                <div class="upload-overlay">
                                    <i class="bi bi-camera"></i>
                                </div>
                            </div>
                            <div class="upload-info">
                                <input type="file" id="profilePicture" accept="image/*" onchange="previewProfilePicture(this)" hidden>
                                <button class="btn-upload" onclick="document.getElementById('profilePicture').click()">
                                    <i class="bi bi-upload me-2"></i>Upload Photo
                                </button>
                                <small class="upload-hint">JPG, PNG or GIF. Max size 2MB</small>
                            </div>
                        </div>
                    </div>

                    <div class="card-section">
                        <div class="form-grid">
                            <div class="form-group">
                                <label class="form-label">First Name</label>
                                <input type="text" class="form-input" value="Admin" placeholder="Enter first name">
                            </div>
                            <div class="form-group">
                                <label class="form-label">Last Name</label>
                                <input type="text" class="form-input" value="User" placeholder="Enter last name">
                            </div>
                            <div class="form-group">
                                <label class="form-label">Email Address</label>
                                <input type="email" class="form-input" value="admin@example.com" placeholder="Enter email">
                            </div>
                            <div class="form-group">
                                <label class="form-label">Phone Number</label>
                                <input type="tel" class="form-input" value="+1 (555) 123-4567" placeholder="Enter phone">
                            </div>
                        </div>
                    </div>

                    <div class="card-section">
                        <div class="form-group">
                            <label class="form-label">Bio</label>
                            <textarea class="form-input" rows="4" placeholder="Tell us about yourself..."></textarea>
                        </div>
                    </div>

                    <div class="card-actions">
                        <button class="btn-primary">Save Changes</button>
                        <button class="btn-secondary">Cancel</button>
                    </div>
                </div>
            </div>

            <!-- Account & Security Settings -->
            <div id="account-settings" class="settings-panel" style="display: none;">
                <div class="panel-header">
                    <h2 class="panel-title">Security Settings</h2>
                    <p class="panel-description">Manage your password and security preferences</p>
                </div>

                <div class="settings-card">
                    <div class="card-header">
                        <h3 class="card-title">Change Password</h3>
                    </div>
                    <div class="card-section">
                        <div class="form-group">
                            <label class="form-label">Current Password</label>
                            <input type="password" class="form-input" placeholder="Enter current password">
                        </div>
                        <div class="form-grid">
                            <div class="form-group">
                                <label class="form-label">New Password</label>
                                <input type="password" class="form-input" placeholder="Enter new password">
                            </div>
                            <div class="form-group">
                                <label class="form-label">Confirm Password</label>
                                <input type="password" class="form-input" placeholder="Confirm new password">
                            </div>
                        </div>
                    </div>
                    <div class="card-actions">
                        <button class="btn-primary">Update Password</button>
                    </div>
                </div>

                <div class="settings-card">
                    <div class="card-header">
                        <h3 class="card-title">Two-Factor Authentication</h3>
                    </div>
                    <div class="card-section">
                        <div class="setting-row">
                            <div class="setting-info">
                                <div class="setting-title">Enable 2FA</div>
                                <div class="setting-desc">Add an extra layer of security to your account</div>
                            </div>
                            <label class="toggle-switch">
                                <input type="checkbox" id="enable2FA">
                                <span class="toggle-slider"></span>
                            </label>
                        </div>
                    </div>
                    <div class="card-actions">
                        <button class="btn-secondary">Configure 2FA</button>
                    </div>
                </div>

                <div class="settings-card">
                    <div class="card-header">
                        <h3 class="card-title">Active Sessions</h3>
                    </div>
                    <div class="card-section">
                        <div class="session-list">
                            <div class="session-item">
                                <div class="session-icon">
                                    <i class="bi bi-laptop"></i>
                                </div>
                                <div class="session-info">
                                    <div class="session-title">Windows • Chrome</div>
                                    <div class="session-meta">Manila, Philippines • Current session</div>
                                </div>
                                <span class="session-badge active">Active</span>
                            </div>
                            <div class="session-item">
                                <div class="session-icon">
                                    <i class="bi bi-phone"></i>
                                </div>
                                <div class="session-info">
                                    <div class="session-title">iPhone • Safari</div>
                                    <div class="session-meta">Quezon City, Philippines • 2 hours ago</div>
                                </div>
                                <button class="btn-danger-outline">Revoke</button>
                            </div>
                        </div>
                    </div>
                </div>

                <div class="settings-card danger-zone">
                    <div class="card-header">
                        <h3 class="card-title">Danger Zone</h3>
                    </div>
                    <div class="card-section">
                        <div class="setting-row">
                            <div class="setting-info">
                                <div class="setting-title">Delete Account</div>
                                <div class="setting-desc">Permanently delete your account and all data</div>
                            </div>
                            <button class="btn-danger">Delete Account</button>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Business Settings -->
            <div id="business-settings" class="settings-panel" style="display: none;">
                <div class="panel-header">
                    <h2 class="panel-title">Business Information</h2>
                    <p class="panel-description">Manage your business details and localization</p>
                </div>

                <div class="settings-card">
                    <div class="card-section">
                        <div class="form-grid">
                            <div class="form-group">
                                <label class="form-label">Business Name</label>
                                <input type="text" class="form-input" value="My Store" placeholder="Enter business name">
                            </div>
                            <div class="form-group">
                                <label class="form-label">Business Type</label>
                                <select class="form-input">
                                    <option>Retail</option>
                                    <option>Wholesale</option>
                                    <option>Services</option>
                                    <option>Manufacturing</option>
                                </select>
                            </div>
                            <div class="form-group">
                                <label class="form-label">Tax ID / VAT Number</label>
                                <input type="text" class="form-input" placeholder="Enter tax ID">
                            </div>
                            <div class="form-group">
                                <label class="form-label">Registration Number</label>
                                <input type="text" class="form-input" placeholder="Enter registration number">
                            </div>
                        </div>
                    </div>

                    <div class="card-section">
                        <div class="form-group">
                            <label class="form-label">Business Address</label>
                            <textarea class="form-input" rows="3" placeholder="Enter business address"></textarea>
                        </div>
                        <div class="form-grid-3">
                            <div class="form-group">
                                <label class="form-label">City</label>
                                <input type="text" class="form-input" placeholder="Enter city">
                            </div>
                            <div class="form-group">
                                <label class="form-label">State/Province</label>
                                <input type="text" class="form-input" placeholder="Enter state">
                            </div>
                            <div class="form-group">
                                <label class="form-label">Postal Code</label>
                                <input type="text" class="form-input" placeholder="Enter postal code">
                            </div>
                        </div>
                    </div>

                    <div class="card-actions">
                        <button class="btn-primary">Save Changes</button>
                    </div>
                </div>

                <div class="settings-card">
                    <div class="card-header">
                        <h3 class="card-title">Currency & Localization</h3>
                    </div>
                    <div class="card-section">
                        <div class="form-grid">
                            <div class="form-group">
                                <label class="form-label">Currency</label>
                                <select class="form-input">
                                    <option>USD - US Dollar</option>
                                    <option>EUR - Euro</option>
                                    <option>GBP - British Pound</option>
                                    <option>PHP - Philippine Peso</option>
                                </select>
                            </div>
                            <div class="form-group">
                                <label class="form-label">Time Zone</label>
                                <select class="form-input">
                                    <option>Asia/Manila (GMT+8)</option>
                                    <option>America/New_York (GMT-5)</option>
                                    <option>Europe/London (GMT+0)</option>
                                </select>
                            </div>
                            <div class="form-group">
                                <label class="form-label">Date Format</label>
                                <select class="form-input">
                                    <option>MM/DD/YYYY</option>
                                    <option>DD/MM/YYYY</option>
                                    <option>YYYY-MM-DD</option>
                                </select>
                            </div>
                            <div class="form-group">
                                <label class="form-label">Number Format</label>
                                <select class="form-input">
                                    <option>1,234.56</option>
                                    <option>1.234,56</option>
                                    <option>1 234,56</option>
                                </select>
                            </div>
                        </div>
                    </div>
                    <div class="card-actions">
                        <button class="btn-primary">Save Changes</button>
                    </div>
                </div>
            </div>

            <!-- Notifications Settings -->
            <div id="notifications-settings" class="settings-panel" style="display: none;">
                <div class="panel-header">
                    <h2 class="panel-title">Notification Preferences</h2>
                    <p class="panel-description">Choose how you want to be notified</p>
                </div>

                <div class="settings-card">
                    <div class="card-header">
                        <h3 class="card-title">Email Notifications</h3>
                    </div>
                    <div class="card-section">
                        <div class="setting-row">
                            <div class="setting-info">
                                <div class="setting-title">Order Updates</div>
                                <div class="setting-desc">Receive emails about new orders and updates</div>
                            </div>
                            <label class="toggle-switch">
                                <input type="checkbox" checked>
                                <span class="toggle-slider"></span>
                            </label>
                        </div>
                        <div class="setting-row">
                            <div class="setting-info">
                                <div class="setting-title">Low Stock Alerts</div>
                                <div class="setting-desc">Get notified when products are running low</div>
                            </div>
                            <label class="toggle-switch">
                                <input type="checkbox" checked>
                                <span class="toggle-slider"></span>
                            </label>
                        </div>
                        <div class="setting-row">
                            <div class="setting-info">
                                <div class="setting-title">Customer Messages</div>
                                <div class="setting-desc">Notifications for customer inquiries</div>
                            </div>
                            <label class="toggle-switch">
                                <input type="checkbox" checked>
                                <span class="toggle-slider"></span>
                            </label>
                        </div>
                        <div class="setting-row">
                            <div class="setting-info">
                                <div class="setting-title">Marketing Emails</div>
                                <div class="setting-desc">Promotional content and updates</div>
                            </div>
                            <label class="toggle-switch">
                                <input type="checkbox">
                                <span class="toggle-slider"></span>
                            </label>
                        </div>
                        <div class="setting-row">
                            <div class="setting-info">
                                <div class="setting-title">Weekly Reports</div>
                                <div class="setting-desc">Summary of sales and performance</div>
                            </div>
                            <label class="toggle-switch">
                                <input type="checkbox" checked>
                                <span class="toggle-slider"></span>
                            </label>
                        </div>
                    </div>
                </div>

                <div class="settings-card">
                    <div class="card-header">
                        <h3 class="card-title">Push Notifications</h3>
                    </div>
                    <div class="card-section">
                        <div class="setting-row">
                            <div class="setting-info">
                                <div class="setting-title">Browser Notifications</div>
                                <div class="setting-desc">Desktop notifications for important events</div>
                            </div>
                            <label class="toggle-switch">
                                <input type="checkbox" checked>
                                <span class="toggle-slider"></span>
                            </label>
                        </div>
                        <div class="setting-row">
                            <div class="setting-info">
                                <div class="setting-title">Mobile Push</div>
                                <div class="setting-desc">Push notifications on mobile app</div>
                            </div>
                            <label class="toggle-switch">
                                <input type="checkbox">
                                <span class="toggle-slider"></span>
                            </label>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Appearance Settings -->
            <div id="appearance-settings" class="settings-panel" style="display: none;">
                <div class="panel-header">
                    <h2 class="panel-title">Appearance</h2>
                    <p class="panel-description">Customize the look and feel of your dashboard</p>
                </div>

                <div class="settings-card">
                    <div class="card-header">
                        <h3 class="card-title">Theme Settings</h3>
                    </div>
                    <div class="card-section">
                        <div class="form-group">
                            <label class="form-label">Color Scheme</label>
                            <div class="theme-options">
                                <label class="theme-option">
                                    <input type="radio" name="theme" value="light" checked>
                                    <div class="theme-card">
                                        <i class="bi bi-sun"></i>
                                        <span>Light</span>
                                    </div>
                                </label>
                                <label class="theme-option">
                                    <input type="radio" name="theme" value="dark">
                                    <div class="theme-card">
                                        <i class="bi bi-moon"></i>
                                        <span>Dark</span>
                                    </div>
                                </label>
                                <label class="theme-option">
                                    <input type="radio" name="theme" value="auto">
                                    <div class="theme-card">
                                        <i class="bi bi-circle-half"></i>
                                        <span>Auto</span>
                                    </div>
                                </label>
                            </div>
                        </div>
                        <div class="form-grid">
                            <div class="form-group">
                                <label class="form-label">Primary Color</label>
                                <input type="color" class="form-input-color" value="#2563eb">
                            </div>
                            <div class="form-group">
                                <label class="form-label">Sidebar Style</label>
                                <select class="form-input">
                                    <option>Expanded</option>
                                    <option>Collapsed</option>
                                    <option>Overlay</option>
                                </select>
                            </div>
                        </div>
                    </div>
                    <div class="card-actions">
                        <button class="btn-primary">Apply Changes</button>
                    </div>
                </div>

                <div class="settings-card">
                    <div class="card-header">
                        <h3 class="card-title">Display Options</h3>
                    </div>
                    <div class="card-section">
                        <div class="setting-row">
                            <div class="setting-info">
                                <div class="setting-title">Compact Mode</div>
                                <div class="setting-desc">Reduce spacing for more content</div>
                            </div>
                            <label class="toggle-switch">
                                <input type="checkbox">
                                <span class="toggle-slider"></span>
                            </label>
                        </div>
                        <div class="setting-row">
                            <div class="setting-info">
                                <div class="setting-title">Show Grid Lines</div>
                                <div class="setting-desc">Display borders in tables</div>
                            </div>
                            <label class="toggle-switch">
                                <input type="checkbox" checked>
                                <span class="toggle-slider"></span>
                            </label>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Shipping Settings -->
            <div id="shipping-settings" class="settings-panel" style="display:none;">

                <div class="panel-header">
                    <h2 class="panel-title">Shipping Configuration</h2>
                    <p class="panel-description">Manage delivery zones and rates</p>
                </div>

                <div class="settings-card">
                    <div class="card-header">
                        <h3 class="card-title">Shipping Zones</h3>

                        <button class="btn-primary-sm"
                            data-bs-toggle="modal"
                            data-bs-target="#addShippingModal">

                            <i class="bi bi-plus-circle me-1"></i>
                            Add Shipping Zone
                        </button>
                    </div>

                    <div class="card-section">
                        <div id="shippingList">
                            <?php
                            $result = $conn->query("SELECT * FROM shipping_zones ORDER BY id DESC");
                            if ($result->num_rows > 0) {
                                while ($row = $result->fetch_assoc()) {
                            ?>
                                    <div class="session-item d-flex justify-content-between align-items-center mb-2">
                                        <div class="session-info">
                                            <div class="session-title"><strong><?php echo htmlspecialchars($row['shipping_name']); ?></strong></div>
                                            <div class="session-meta text-muted">
                                                Courier: <?php echo htmlspecialchars($row['shipping_courier']); ?> • ₱<?php echo number_format($row['shipping_price'], 2); ?>
                                            </div>
                                        </div>
                                        <a href="?delete_shipping_id=<?php echo $row['id']; ?>"
                                            class="text-danger text-decoration-none"
                                            onclick="return confirm('Remove this shipping zone?')">
                                            <i class="bi bi-trash"></i> Remove
                                        </a>
                                    </div>
                            <?php
                                }
                            } else {
                                echo '<p class="text-muted">No shipping zones added.</p>';
                            }
                            ?>
                        </div>
                    </div>
                </div>

            </div>

            <!-- Payment Settings -->
            <div id="payment-settings" class="settings-panel" style="display: none;">
                <div class="panel-header">
                    <h2 class="panel-title">Payment Methods</h2>
                    <p class="panel-description">Manage your payment gateways and options for your customers</p>
                </div>
                <div class="settings-card">
                    <div class="card-section">
                        <div class="payment-methods">
                            <?php
                            // Fetch all payment settings - Ensure DB name matches (aes or es)
                            $query = "SELECT * FROM aes.payment_settings ORDER BY id ASC";
                            $result = mysqli_query($conn, $query);

                            if (mysqli_num_rows($result) > 0):
                                while ($row = mysqli_fetch_assoc($result)):
                                    $isActive = $row['is_active'] == 1;

                                    // Assign icons
                                    $icon = "bi-credit-card";
                                    if ($row['method_key'] == 'payment_gcash') $icon = "bi-wallet2 text-primary";
                                    if ($row['method_key'] == 'payment_maya') $icon = "bi-vignette text-success";
                                    if ($row['method_key'] == 'payment_cod') $icon = "bi-cash-stack text-warning";
                                    if ($row['method_key'] == 'payment_paypal') $icon = "bi-paypal text-info";
                                    if ($row['method_key'] == 'payment_bank') $icon = "bi-bank text-info";
                            ?>
                                    <div class="payment-card" id="card-<?= $row['method_key'] ?>" style="opacity: <?= $isActive ? '1' : '0.6' ?>;">
                                        <div class="payment-icon"><i class="bi <?= $icon ?>"></i></div>
                                        <div class="payment-info">
                                            <h4><?= htmlspecialchars($row['display_name']) ?></h4>
                                            <p class="mb-2">Enable or disable this gateway</p>

                                            <button type="button" class="btn btn-sm btn-light border"
                                                onclick="openEditModal('<?= $row['method_key'] ?>', '<?= htmlspecialchars($row['display_name']) ?>')">
                                                <i class="bi bi-pencil-square me-1"></i> Edit Details
                                            </button>
                                        </div>
                                        <label class="toggle-switch">
                                            <input type="checkbox"
                                                name="<?= $row['method_key'] ?>"
                                                class="payment-toggle"
                                                <?= $isActive ? 'checked' : '' ?>>
                                            <span class="toggle-slider"></span>
                                        </label>
                                    </div>
                            <?php
                                endwhile;
                            endif;
                            ?>
                        </div>

                        <div class="modal fade" id="editPaymentModal" tabindex="-1" aria-hidden="true">
                            <div class="modal-dialog">
                                <div class="modal-content">
                                    <div class="modal-header">
                                        <h5 class="modal-title">Edit <span id="modal-method-name"></span> Details</h5>
                                        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                                    </div>
                                    <div class="modal-body">
                                        <input type="hidden" id="modal-method-key">
                                        <div class="mb-3">
                                            <label class="form-label">Customer Instructions (Visible on Checkout)</label>
                                            <textarea id="modal-instructions" class="form-control" rows="6" placeholder="Enter bank account details, GCash number, or handling fees..."></textarea>
                                        </div>
                                    </div>
                                    <div class="modal-footer">
                                        <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancel</button>
                                        <button type="button" class="btn btn-primary" onclick="saveInstructions()">Save Changes</button>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Voucher Settings -->
            <div id="vouchers-settings" class="settings-panel">
                <div class="panel-header d-flex justify-content-between align-items-center">
                    <div>
                        <h2 class="panel-title">Voucher Management</h2>
                        <p class="panel-description">Create and manage discount codes for your customers</p>
                    </div>
                    <button class="btn btn-primary" onclick="openVoucherModal()">
                        <i class="bi bi-plus-lg me-1"></i> Add New Voucher
                    </button>
                </div>

                <div class="settings-card mt-4">
                    <div class="table-responsive">
                        <table class="table table-hover align-middle">
                            <thead class="table-light">
                                <tr>
                                    <th>Code</th>
                                    <th>Discount</th>
                                    <th>Target User</th>
                                    <th>Status</th>
                                    <th>Expiry</th>
                                    <th>Action</th>
                                </tr>
                            </thead>
                            <tbody>
                                <?php
                                $v_query = "SELECT v.*, u.full_name FROM aes.vouchers v 
                                LEFT JOIN users u ON v.user_id = u.id 
                                ORDER BY v.created_at DESC";
                                $v_res = mysqli_query($conn, $v_query);
                                while ($v = mysqli_fetch_assoc($v_res)):
                                    $is_expired = strtotime($v['expiry_date']) < time();
                                ?>
                                    <tr>
                                        <td><strong><?= $v['code'] ?></strong></td>
                                        <td><?= $v['discount_type'] == 'fixed' ? '₱' . $v['discount_amount'] : $v['discount_amount'] . '%' ?></td>
                                        <td><?= $v['full_name'] ?? '<span class="text-muted">Global</span>' ?></td>
                                        <td>
                                            <?php if ($v['is_used']): ?>
                                                <span class="badge bg-secondary">Used</span>
                                            <?php elseif ($is_expired): ?>
                                                <span class="badge bg-danger">Expired</span>
                                            <?php else: ?>
                                                <span class="badge bg-success">Active</span>
                                            <?php endif; ?>
                                        </td>
                                        <td><?= date('M d, Y', strtotime($v['expiry_date'])) ?></td>
                                        <td>
                                            <button class="btn btn-sm btn-outline-danger" onclick="deleteVoucher(<?= $v['id'] ?>)">
                                                <i class="bi bi-trash"></i>
                                            </button>
                                        </td>
                                    </tr>
                                <?php endwhile; ?>
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>

            <!-- Announcement Settings -->
            <div id="announcement-settings" class="settings-panel" style="display: none;">
                <div class="panel-header">
                    <h2 class="panel-title">Announcements</h2>
                    <p class="panel-description">Manage site-wide announcements and banners</p>
                </div>
                <div class="settings-card">
                    <div class="card-header">
                        <h3 class="card-title">Active Announcements</h3>
                        <button class="btn-primary-sm" data-bs-toggle="modal" data-bs-target="#addAnnouncementModal">
                            <i class="bi bi-plus-circle me-1"></i>New Announcement
                        </button>
                    </div>
                    <div class="card-section">
                        <div class="empty-state">
                            <i class="bi bi-megaphone"></i>
                            <p>No active announcements</p>
                            <button class="btn-secondary" data-bs-toggle="modal" data-bs-target="#addAnnouncementModal">Create First Announcement</button>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Flash Sale Settings -->
            <div id="flashsale-settings" class="settings-panel" style="display: none;">
                <div class="panel-header">
                    <h2 class="panel-title">Flash Sales</h2>
                    <p class="panel-description">Create time-limited promotional campaigns</p>
                </div>
                <div class="settings-card">
                    <div class="card-header">
                        <h3 class="card-title">Active Flash Sales</h3>
                        <button class="btn-primary-sm" data-bs-toggle="modal" data-bs-target="#addFlashSaleModal">
                            <i class="bi bi-plus-circle me-1"></i>Create Flash Sale
                        </button>
                    </div>
                    <div class="card-section">
                        <div class="empty-state">
                            <i class="bi bi-lightning-charge"></i>
                            <p>No active flash sales</p>
                            <button class="btn-secondary" data-bs-toggle="modal" data-bs-target="#addFlashSaleModal">Create First Flash Sale</button>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Integrations Settings -->
            <div id="integrations-settings" class="settings-panel" style="display: none;">
                <div class="panel-header">
                    <h2 class="panel-title">Integrations</h2>
                    <p class="panel-description">Connect third-party services and APIs</p>
                </div>
                <div class="settings-card">
                    <div class="card-header">
                        <h3 class="card-title">Payment Gateways</h3>
                    </div>
                    <div class="card-section">
                        <div class="integration-grid">
                            <div class="integration-card">
                                <i class="bi bi-credit-card integration-icon"></i>
                                <h4>Stripe</h4>
                                <span class="badge-success">Connected</span>
                            </div>
                            <div class="integration-card">
                                <i class="bi bi-paypal integration-icon"></i>
                                <h4>PayPal</h4>
                                <span class="badge-gray">Not Connected</span>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Preferences Settings -->
            <div id="preferences-settings" class="settings-panel" style="display: none;">
                <div class="panel-header">
                    <h2 class="panel-title">General Preferences</h2>
                    <p class="panel-description">Customize your application behavior</p>
                </div>
                <div class="settings-card">
                    <div class="card-section">
                        <div class="form-grid">
                            <div class="form-group">
                                <label class="form-label">Language</label>
                                <select class="form-input">
                                    <option>English</option>
                                    <option>Spanish</option>
                                    <option>French</option>
                                </select>
                            </div>
                            <div class="form-group">
                                <label class="form-label">Items Per Page</label>
                                <select class="form-input">
                                    <option>10</option>
                                    <option>25</option>
                                    <option>50</option>
                                    <option>100</option>
                                </select>
                            </div>
                        </div>
                    </div>
                    <div class="card-actions">
                        <button class="btn-primary">Save Preferences</button>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
</main>

<!-- Modals (simplified) -->
<div class="modal fade" id="addAnnouncementModal" tabindex="-1" aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title">Create Announcement</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
            </div>
            <div class="modal-body">
                <div class="form-group">
                    <label class="form-label">Title</label>
                    <input type="text" class="form-input" placeholder="Announcement title">
                </div>
                <div class="form-group">
                    <label class="form-label">Message</label>
                    <textarea class="form-input" rows="3" placeholder="Announcement message"></textarea>
                </div>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn-secondary" data-bs-dismiss="modal">Cancel</button>
                <button type="button" class="btn-primary">Create</button>
            </div>
        </div>
    </div>
</div>

<div class="modal fade" id="addFlashSaleModal" tabindex="-1" aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title">Create Flash Sale</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
            </div>
            <div class="modal-body">
                <div class="form-group">
                    <label class="form-label">Flash Sale Name</label>
                    <input type="text" class="form-input" placeholder="e.g., Weekend Mega Sale">
                </div>
                <div class="form-group">
                    <label class="form-label">Discount Value</label>
                    <input type="number" class="form-input" placeholder="50">
                </div>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn-secondary" data-bs-dismiss="modal">Cancel</button>
                <button type="button" class="btn-primary">Create</button>
            </div>
        </div>
    </div>
</div>

<!-- Add Shipping Modal -->
<div class="modal fade" id="addShippingModal" tabindex="-1">
    <div class="modal-dialog">
        <div class="modal-content">

            <div class="modal-header">
                <h5 class="modal-title">Add Shipping Zone</h5>
                <button type="button" class="btn-close"
                    data-bs-dismiss="modal"></button>
            </div>

            <form id="shippingForm" method="POST">
                <div class="modal-body">

                    <div class="form-group">
                        <label class="form-label">Shipping Name</label>
                        <input type="text"
                            class="form-input"
                            name="shipping_name"
                            id="shippingName"
                            placeholder="Express or Standard" required>
                    </div>

                    <div class="form-group">
                        <label class="form-label">Shipping Price (₱)</label>
                        <input type="number"
                            class="form-input"
                            name="shipping_price"
                            id="shippingPrice"
                            placeholder="50" required>
                    </div>

                    <div class="form-group">
                        <label class="form-label">Shipping Courier</label>
                        <input type="text"
                            class="form-input"
                            name="shipping_courier"
                            id="shippingCourier"
                            placeholder="J&T Express, LBC, NinjaVan" required>
                    </div>

                </div>

                <div class="modal-footer">
                    <button type="button"
                        class="btn-secondary"
                        data-bs-dismiss="modal">
                        Cancel
                    </button>

                    <button type="submit" class="btn-primary" name="save_shipping">
                        Save Shipping
                    </button>
                </div>
            </form>
        </div>
    </div>
</div>

<!-- Add Voucher -->
<div class="modal fade" id="addVoucherModal" tabindex="-1">
    <div class="modal-dialog">
        <form id="voucherForm">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title">Create New Voucher</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                </div>
                <div class="modal-body">
                    <div class="mb-3">
                        <label class="form-label">Voucher Code</label>
                        <input type="text" name="code" class="form-control" placeholder="e.g. WELCOME2024" required>
                    </div>
                    <div class="row">
                        <div class="col-md-6 mb-3">
                            <label class="form-label">Discount Value</label>
                            <input type="number" name="amount" class="form-control" required>
                        </div>
                        <div class="col-md-6 mb-3">
                            <label class="form-label">Type</label>
                            <select name="type" class="form-select">
                                <option value="fixed">Fixed (₱)</option>
                                <option value="percentage">Percentage (%)</option>
                            </select>
                        </div>
                    </div>
                    <div class="mb-3">
                        <label class="form-label">Assign to Specific User (Optional)</label>
                        <select name="user_id" class="form-select">
                            <option value="">Everyone (Global)</option>
                            <?php
                            $u_query = "SELECT id, full_name FROM users ORDER BY full_name ASC";
                            $u_res = mysqli_query($conn, $u_query);
                            while ($u = mysqli_fetch_assoc($u_res)) {
                                echo "<option value='{$u['id']}'>{$u['full_name']}</option>";
                            }
                            ?>
                        </select>
                    </div>
                    <div class="mb-3">
                        <label class="form-label">Expiry Date</label>
                        <input type="date" name="expiry" class="form-control" required>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="submit" class="btn btn-primary w-100">Create Voucher</button>
                </div>
            </div>
        </form>
    </div>
</div>
<?php
include 'includes/footer.php';
?>