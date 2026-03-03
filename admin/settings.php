<?php
$conn = new mysqli('localhost', 'root', '', 'aes');
if ($conn->connect_error) die("Connection failed: " . $conn->connect_error);

if (isset($_POST['save_shipping'])) {
    $name    = $conn->real_escape_string($_POST['shipping_name']);
    $price   = $conn->real_escape_string($_POST['shipping_price']);
    $courier = $conn->real_escape_string($_POST['shipping_courier']);
    $conn->query("INSERT INTO shipping_zones (shipping_name, shipping_price, shipping_courier) VALUES ('$name', '$price', '$courier')");
    header("Location: " . $_SERVER['PHP_SELF'] . "#shipping-settings");
    exit;
}
if (isset($_GET['delete_shipping_id'])) {
    $id = intval($_GET['delete_shipping_id']);
    $conn->query("DELETE FROM shipping_zones WHERE id = $id");
    header("Location: " . $_SERVER['PHP_SELF'] . "#shipping");
    exit;
}

$settings = [];
$res = mysqli_query($conn, "SELECT method_key, is_active FROM payment_settings");
while ($row = mysqli_fetch_assoc($res)) $settings[$row['method_key']] = $row['is_active'];

include 'includes/header.php';
?>

<!-- ============================================================
     SETTINGS PAGE
============================================================ -->
<div class="st-wrap">
    <div class="st-layout">

        <!-- ===== LEFT NAV ===== -->
        <aside class="st-sidebar">
            <div class="st-sidebar-inner">

                <div class="st-nav-group">
                    <span class="st-nav-label">Personal</span>
                    <a href="#" class="st-nav-item active" data-tab="profile">
                        <i class="bi bi-person-circle"></i><span>Profile</span>
                    </a>
                    <a href="#" class="st-nav-item" data-tab="account">
                        <i class="bi bi-shield-lock"></i><span>Security</span>
                    </a>
                    <a href="#" class="st-nav-item" data-tab="notifications">
                        <i class="bi bi-bell"></i><span>Notifications</span>
                    </a>
                    <a href="#" class="st-nav-item" data-tab="appearance">
                        <i class="bi bi-palette"></i><span>Appearance</span>
                    </a>
                </div>

                <div class="st-nav-group">
                    <span class="st-nav-label">Business</span>
                    <a href="#" class="st-nav-item" data-tab="business">
                        <i class="bi bi-building"></i><span>Business Info</span>
                    </a>
                    <a href="#" class="st-nav-item" data-tab="shipping">
                        <i class="bi bi-truck"></i><span>Shipping</span>
                    </a>
                    <a href="#" class="st-nav-item" data-tab="payment">
                        <i class="bi bi-credit-card"></i><span>Payments</span>
                    </a>
                    <a href="#" class="st-nav-item" data-tab="vouchers">
                        <i class="bi bi-gift"></i><span>Vouchers</span>
                    </a>
                </div>

                <div class="st-nav-group">
                    <span class="st-nav-label">Marketing</span>
                    <a href="#" class="st-nav-item" data-tab="announcement">
                        <i class="bi bi-megaphone"></i><span>Announcements</span>
                    </a>
                    <a href="#" class="st-nav-item" data-tab="flashsale">
                        <i class="bi bi-lightning-charge"></i><span>Flash Sales</span>
                    </a>
                </div>

                <div class="st-nav-group">
                    <span class="st-nav-label">Advanced</span>
                    <a href="#" class="st-nav-item" data-tab="integrations">
                        <i class="bi bi-plug"></i><span>Integrations</span>
                    </a>
                    <a href="#" class="st-nav-item" data-tab="preferences">
                        <i class="bi bi-sliders"></i><span>Preferences</span>
                    </a>
                </div>

            </div>
        </aside>

        <!-- ===== MAIN CONTENT ===== -->
        <main class="st-content">

            <!-- ---- PROFILE ---- -->
            <div class="st-panel active" id="tab-profile">
                <div class="st-panel-head">
                    <h2 class="st-panel-title">Profile Information</h2>
                    <p class="st-panel-sub">Update your personal information and profile picture</p>
                </div>

                <div class="st-card">
                    <div class="st-card-section">
                        <label class="st-label">Profile Picture</label>
                        <div class="st-avatar-row">
                            <div class="st-avatar-preview">
                                <img src="https://ui-avatars.com/api/?name=Admin+User&background=1e3a5f&color=fff&size=120" id="profilePreview" alt="Profile">
                                <div class="st-avatar-overlay"><i class="bi bi-camera"></i></div>
                            </div>
                            <div class="st-avatar-meta">
                                <input type="file" id="profilePicture" accept="image/*" hidden onchange="previewProfilePicture(this)">
                                <button class="st-btn st-btn-outline" onclick="document.getElementById('profilePicture').click()">
                                    <i class="bi bi-upload me-2"></i>Upload Photo
                                </button>
                                <small class="st-hint">JPG, PNG or GIF. Max size 2MB</small>
                            </div>
                        </div>
                    </div>

                    <div class="st-card-section">
                        <div class="st-form-grid">
                            <div class="st-field">
                                <label class="st-label">First Name</label>
                                <input type="text" class="st-input" value="Admin" placeholder="First name">
                            </div>
                            <div class="st-field">
                                <label class="st-label">Last Name</label>
                                <input type="text" class="st-input" value="User" placeholder="Last name">
                            </div>
                            <div class="st-field">
                                <label class="st-label">Email Address</label>
                                <input type="email" class="st-input" value="admin@aes.ph" placeholder="Email">
                            </div>
                            <div class="st-field">
                                <label class="st-label">Phone Number</label>
                                <input type="tel" class="st-input" value="+63 912 345 6789" placeholder="Phone">
                            </div>
                            <div class="st-field st-field--full">
                                <label class="st-label">Bio</label>
                                <textarea class="st-input st-textarea" rows="3" placeholder="Tell us about yourself..."></textarea>
                            </div>
                        </div>
                    </div>

                    <div class="st-card-footer">
                        <button class="st-btn st-btn-primary">Save Changes</button>
                        <button class="st-btn st-btn-ghost">Cancel</button>
                    </div>
                </div>
            </div>

            <!-- ---- SECURITY ---- -->
            <div class="st-panel" id="tab-account">
                <div class="st-panel-head">
                    <h2 class="st-panel-title">Security Settings</h2>
                    <p class="st-panel-sub">Manage your password and account security</p>
                </div>

                <div class="st-card">
                    <div class="st-card-head">
                        <h3 class="st-card-title">Change Password</h3>
                    </div>
                    <div class="st-card-section">
                        <div class="st-field st-field--full">
                            <label class="st-label">Current Password</label>
                            <input type="password" class="st-input" placeholder="Enter current password">
                        </div>
                        <div class="st-form-grid" style="margin-top:1rem;">
                            <div class="st-field">
                                <label class="st-label">New Password</label>
                                <input type="password" class="st-input" placeholder="New password">
                            </div>
                            <div class="st-field">
                                <label class="st-label">Confirm Password</label>
                                <input type="password" class="st-input" placeholder="Confirm password">
                            </div>
                        </div>
                    </div>
                    <div class="st-card-footer">
                        <button class="st-btn st-btn-primary">Update Password</button>
                    </div>
                </div>

                <div class="st-card">
                    <div class="st-card-head">
                        <h3 class="st-card-title">Two-Factor Authentication</h3>
                    </div>
                    <div class="st-card-section">
                        <div class="st-toggle-row">
                            <div class="st-toggle-info">
                                <div class="st-toggle-title">Enable 2FA</div>
                                <div class="st-toggle-sub">Add an extra layer of security to your account</div>
                            </div>
                            <label class="st-toggle"><input type="checkbox"><span class="st-toggle-track"></span></label>
                        </div>
                    </div>
                    <div class="st-card-footer">
                        <button class="st-btn st-btn-ghost">Configure 2FA</button>
                    </div>
                </div>

                <div class="st-card">
                    <div class="st-card-head">
                        <h3 class="st-card-title">Active Sessions</h3>
                    </div>
                    <div class="st-card-section">
                        <div class="st-session-list">
                            <div class="st-session">
                                <div class="st-session-icon"><i class="bi bi-laptop"></i></div>
                                <div class="st-session-info">
                                    <div class="st-session-title">Windows · Chrome</div>
                                    <div class="st-session-meta">Manila, Philippines · Current session</div>
                                </div>
                                <span class="st-badge st-badge--active">Active</span>
                            </div>
                            <div class="st-session">
                                <div class="st-session-icon"><i class="bi bi-phone"></i></div>
                                <div class="st-session-info">
                                    <div class="st-session-title">iPhone · Safari</div>
                                    <div class="st-session-meta">Quezon City, Philippines · 2 hours ago</div>
                                </div>
                                <button class="st-btn st-btn-danger-outline">Revoke</button>
                            </div>
                        </div>
                    </div>
                </div>

                <div class="st-card st-card--danger">
                    <div class="st-card-head st-card-head--danger">
                        <h3 class="st-card-title">Danger Zone</h3>
                    </div>
                    <div class="st-card-section">
                        <div class="st-toggle-row">
                            <div class="st-toggle-info">
                                <div class="st-toggle-title">Delete Account</div>
                                <div class="st-toggle-sub">Permanently delete your account and all data. This cannot be undone.</div>
                            </div>
                            <button class="st-btn st-btn-danger">Delete Account</button>
                        </div>
                    </div>
                </div>
            </div>

            <!-- ---- NOTIFICATIONS ---- -->
            <div class="st-panel" id="tab-notifications">
                <div class="st-panel-head">
                    <h2 class="st-panel-title">Notification Preferences</h2>
                    <p class="st-panel-sub">Choose how and when you get notified</p>
                </div>

                <div class="st-card">
                    <div class="st-card-head">
                        <h3 class="st-card-title">Email Notifications</h3>
                    </div>
                    <div class="st-card-section">
                        <?php
                        $notifs = [
                            ['Order Updates',       'Receive emails about new orders and status changes', true],
                            ['Low Stock Alerts',    'Get notified when products are running low',          true],
                            ['Customer Messages',   'Notifications for customer inquiries',                true],
                            ['Marketing Emails',    'Promotional content and feature updates',             false],
                            ['Weekly Reports',      'Summary of sales and store performance',              true],
                        ];
                        foreach ($notifs as $n): ?>
                            <div class="st-toggle-row">
                                <div class="st-toggle-info">
                                    <div class="st-toggle-title"><?= $n[0] ?></div>
                                    <div class="st-toggle-sub"><?= $n[1] ?></div>
                                </div>
                                <label class="st-toggle"><input type="checkbox" <?= $n[2] ? 'checked' : '' ?>><span class="st-toggle-track"></span></label>
                            </div>
                        <?php endforeach; ?>
                    </div>
                </div>

                <div class="st-card">
                    <div class="st-card-head">
                        <h3 class="st-card-title">Push Notifications</h3>
                    </div>
                    <div class="st-card-section">
                        <div class="st-toggle-row">
                            <div class="st-toggle-info">
                                <div class="st-toggle-title">Browser Notifications</div>
                                <div class="st-toggle-sub">Desktop notifications for important events</div>
                            </div>
                            <label class="st-toggle"><input type="checkbox" checked><span class="st-toggle-track"></span></label>
                        </div>
                        <div class="st-toggle-row">
                            <div class="st-toggle-info">
                                <div class="st-toggle-title">Mobile Push</div>
                                <div class="st-toggle-sub">Push notifications on mobile app</div>
                            </div>
                            <label class="st-toggle"><input type="checkbox"><span class="st-toggle-track"></span></label>
                        </div>
                    </div>
                </div>
            </div>

            <!-- ---- APPEARANCE ---- -->
            <div class="st-panel" id="tab-appearance">
                <div class="st-panel-head">
                    <h2 class="st-panel-title">Appearance</h2>
                    <p class="st-panel-sub">Customize the look and feel of your dashboard</p>
                </div>

                <div class="st-card">
                    <div class="st-card-head">
                        <h3 class="st-card-title">Color Scheme</h3>
                    </div>
                    <div class="st-card-section">
                        <div class="st-theme-grid">
                            <label class="st-theme-opt">
                                <input type="radio" name="theme" value="light" checked>
                                <div class="st-theme-card"><i class="bi bi-sun"></i><span>Light</span></div>
                            </label>
                            <label class="st-theme-opt">
                                <input type="radio" name="theme" value="dark">
                                <div class="st-theme-card"><i class="bi bi-moon"></i><span>Dark</span></div>
                            </label>
                            <label class="st-theme-opt">
                                <input type="radio" name="theme" value="auto">
                                <div class="st-theme-card"><i class="bi bi-circle-half"></i><span>Auto</span></div>
                            </label>
                        </div>
                    </div>
                    <div class="st-card-section">
                        <div class="st-form-grid">
                            <div class="st-field">
                                <label class="st-label">Primary Color</label>
                                <input type="color" class="st-color-input" value="#1e3a5f">
                            </div>
                            <div class="st-field">
                                <label class="st-label">Sidebar Style</label>
                                <select class="st-select">
                                    <option>Expanded</option>
                                    <option>Collapsed</option>
                                </select>
                            </div>
                        </div>
                    </div>
                    <div class="st-card-section">
                        <div class="st-toggle-row">
                            <div class="st-toggle-info">
                                <div class="st-toggle-title">Compact Mode</div>
                                <div class="st-toggle-sub">Reduce spacing for more content on screen</div>
                            </div>
                            <label class="st-toggle"><input type="checkbox"><span class="st-toggle-track"></span></label>
                        </div>
                        <div class="st-toggle-row">
                            <div class="st-toggle-info">
                                <div class="st-toggle-title">Show Grid Lines</div>
                                <div class="st-toggle-sub">Display borders in data tables</div>
                            </div>
                            <label class="st-toggle"><input type="checkbox" checked><span class="st-toggle-track"></span></label>
                        </div>
                    </div>
                    <div class="st-card-footer">
                        <button class="st-btn st-btn-primary">Apply Changes</button>
                    </div>
                </div>
            </div>

            <!-- ---- BUSINESS INFO ---- -->
            <div class="st-panel" id="tab-business">
                <div class="st-panel-head">
                    <h2 class="st-panel-title">Business Information</h2>
                    <p class="st-panel-sub">Manage your business details and localization</p>
                </div>

                <div class="st-card">
                    <div class="st-card-head">
                        <h3 class="st-card-title">Business Details</h3>
                    </div>
                    <div class="st-card-section">
                        <div class="st-form-grid">
                            <div class="st-field">
                                <label class="st-label">Business Name</label>
                                <input type="text" class="st-input" value="Aralin Educational Supplies" placeholder="Business name">
                            </div>
                            <div class="st-field">
                                <label class="st-label">Business Type</label>
                                <select class="st-select">
                                    <option>Retail</option>
                                    <option>Wholesale</option>
                                    <option>Services</option>
                                </select>
                            </div>
                            <div class="st-field">
                                <label class="st-label">Tax ID / VAT Number</label>
                                <input type="text" class="st-input" placeholder="Tax ID">
                            </div>
                            <div class="st-field">
                                <label class="st-label">Registration Number</label>
                                <input type="text" class="st-input" placeholder="Reg. number">
                            </div>
                            <div class="st-field st-field--full">
                                <label class="st-label">Business Address</label>
                                <textarea class="st-input st-textarea" rows="2" placeholder="Full address"></textarea>
                            </div>
                            <div class="st-field">
                                <label class="st-label">City</label>
                                <input type="text" class="st-input" placeholder="City">
                            </div>
                            <div class="st-field">
                                <label class="st-label">Postal Code</label>
                                <input type="text" class="st-input" placeholder="Postal code">
                            </div>
                        </div>
                    </div>
                    <div class="st-card-footer"><button class="st-btn st-btn-primary">Save Changes</button></div>
                </div>

                <div class="st-card">
                    <div class="st-card-head">
                        <h3 class="st-card-title">Currency &amp; Localization</h3>
                    </div>
                    <div class="st-card-section">
                        <div class="st-form-grid">
                            <div class="st-field">
                                <label class="st-label">Currency</label>
                                <select class="st-select">
                                    <option>PHP - Philippine Peso</option>
                                    <option>USD - US Dollar</option>
                                </select>
                            </div>
                            <div class="st-field">
                                <label class="st-label">Time Zone</label>
                                <select class="st-select">
                                    <option>Asia/Manila (GMT+8)</option>
                                </select>
                            </div>
                            <div class="st-field">
                                <label class="st-label">Date Format</label>
                                <select class="st-select">
                                    <option>MM/DD/YYYY</option>
                                    <option>DD/MM/YYYY</option>
                                </select>
                            </div>
                            <div class="st-field">
                                <label class="st-label">Number Format</label>
                                <select class="st-select">
                                    <option>1,234.56</option>
                                    <option>1.234,56</option>
                                </select>
                            </div>
                        </div>
                    </div>
                    <div class="st-card-footer"><button class="st-btn st-btn-primary">Save Changes</button></div>
                </div>
            </div>

            <!-- ---- SHIPPING ---- -->
            <div class="st-panel" id="tab-shipping">
                <div class="st-panel-head">
                    <h2 class="st-panel-title">Shipping Configuration</h2>
                    <p class="st-panel-sub">Manage delivery zones and courier rates</p>
                </div>

                <div class="st-card">
                    <div class="st-card-head">
                        <h3 class="st-card-title">Shipping Zones</h3>
                        <button class="st-btn st-btn-primary st-btn--sm" data-bs-toggle="modal" data-bs-target="#addShippingModal">
                            <i class="bi bi-plus-circle me-1"></i>Add Zone
                        </button>
                    </div>
                    <div class="st-card-section">
                        <?php
                        $result = $conn->query("SELECT * FROM shipping_zones ORDER BY id DESC");
                        if ($result->num_rows > 0):
                            while ($row = $result->fetch_assoc()): ?>
                                <div class="st-session">
                                    <div class="st-session-icon"><i class="bi bi-truck"></i></div>
                                    <div class="st-session-info">
                                        <div class="st-session-title"><?= htmlspecialchars($row['shipping_name']) ?></div>
                                        <div class="st-session-meta">
                                            <?= htmlspecialchars($row['shipping_courier']) ?> &nbsp;·&nbsp; ₱<?= number_format($row['shipping_price'], 2) ?>
                                        </div>
                                    </div>
                                    <a href="?delete_shipping_id=<?= $row['id'] ?>"
                                        class="st-btn st-btn-danger-outline"
                                        onclick="return confirm('Remove this zone?')">
                                        <i class="bi bi-trash"></i>
                                    </a>
                                </div>
                            <?php endwhile;
                        else: ?>
                            <div class="st-empty">
                                <i class="bi bi-truck"></i>
                                <p>No shipping zones added yet.</p>
                            </div>
                        <?php endif; ?>
                    </div>
                </div>
            </div>

            <!-- ---- PAYMENTS ---- -->
            <div class="st-panel" id="tab-payment">
                <div class="st-panel-head">
                    <h2 class="st-panel-title">Payment Methods</h2>
                    <p class="st-panel-sub">Enable or disable payment gateways for checkout</p>
                </div>

                <div class="st-card">
                    <div class="st-card-section">
                        <div class="st-payment-list">
                            <?php
                            $query  = "SELECT * FROM aes.payment_settings ORDER BY id ASC";
                            $result = mysqli_query($conn, $query);
                            if (mysqli_num_rows($result) > 0):
                                while ($row = mysqli_fetch_assoc($result)):
                                    $isActive = $row['is_active'] == 1;
                                    $icon = match ($row['method_key']) {
                                        'payment_gcash'  => 'bi-wallet2',
                                        'payment_maya'   => 'bi-vignette',
                                        'payment_cod'    => 'bi-cash-stack',
                                        'payment_paypal' => 'bi-paypal',
                                        'payment_bank'   => 'bi-bank',
                                        default          => 'bi-credit-card',
                                    };
                            ?>
                                    <div class="st-payment-row" id="card-<?= $row['method_key'] ?>" style="opacity:<?= $isActive ? '1' : '0.55' ?>">
                                        <div class="st-payment-icon"><i class="bi <?= $icon ?>"></i></div>
                                        <div class="st-payment-info">
                                            <div class="st-payment-name"><?= htmlspecialchars($row['display_name']) ?></div>
                                            <button class="st-edit-link"
                                                onclick="openEditModal('<?= $row['method_key'] ?>', '<?= htmlspecialchars($row['display_name']) ?>')">
                                                <i class="bi bi-pencil me-1"></i>Edit details
                                            </button>
                                        </div>
                                        <label class="st-toggle">
                                            <input type="checkbox" name="<?= $row['method_key'] ?>" class="payment-toggle" <?= $isActive ? 'checked' : '' ?>>
                                            <span class="st-toggle-track"></span>
                                        </label>
                                    </div>
                            <?php endwhile;
                            endif; ?>
                        </div>
                    </div>
                </div>
            </div>

            <!-- ---- VOUCHERS ---- -->
            <div class="st-panel" id="tab-vouchers">
                <div class="st-panel-head st-panel-head--row">
                    <div>
                        <h2 class="st-panel-title">Voucher Management</h2>
                        <p class="st-panel-sub">Create and manage discount codes</p>
                    </div>
                    <button class="st-btn st-btn-primary" onclick="openVoucherModal()">
                        <i class="bi bi-plus-circle me-2"></i>New Voucher
                    </button>
                </div>

                <div class="st-card">
                    <div class="st-card-section st-card-section--flush">
                        <table class="st-table">
                            <thead>
                                <tr>
                                    <th>Code</th>
                                    <th>Discount</th>
                                    <th>Target</th>
                                    <th>Expiry</th>
                                    <th>Status</th>
                                    <th></th>
                                </tr>
                            </thead>
                            <tbody>
                                <?php
                                $v_res = mysqli_query($conn, "SELECT v.*, u.full_name FROM aes.vouchers v LEFT JOIN users u ON v.user_id = u.id ORDER BY v.created_at DESC");
                                while ($v = mysqli_fetch_assoc($v_res)):
                                    $expired = strtotime($v['expiry_date']) < time();
                                ?>
                                    <tr>
                                        <td><strong><?= $v['code'] ?></strong></td>
                                        <td><?= $v['discount_type'] == 'fixed' ? '₱' . $v['discount_amount'] : $v['discount_amount'] . '%' ?></td>
                                        <td><?= $v['full_name'] ?? '<span class="st-muted">Global</span>' ?></td>
                                        <td><?= date('M d, Y', strtotime($v['expiry_date'])) ?></td>
                                        <td>
                                            <?php if ($v['is_used']): ?>
                                                <span class="st-chip st-chip--gray">Used</span>
                                            <?php elseif ($expired): ?>
                                                <span class="st-chip st-chip--red">Expired</span>
                                            <?php else: ?>
                                                <span class="st-chip st-chip--green">Active</span>
                                            <?php endif; ?>
                                        </td>
                                        <td>
                                            <button class="st-icon-btn st-icon-btn--danger" onclick="deleteVoucher(<?= $v['id'] ?>)" title="Delete">
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

            <!-- ---- ANNOUNCEMENTS ---- -->
            <div class="st-panel" id="tab-announcement">
                <div class="st-panel-head st-panel-head--row">
                    <div>
                        <h2 class="st-panel-title">Announcements</h2>
                        <p class="st-panel-sub">Manage site-wide banners and notices</p>
                    </div>
                    <button class="st-btn st-btn-primary" data-bs-toggle="modal" data-bs-target="#addAnnouncementModal">
                        <i class="bi bi-plus-circle me-2"></i>New Announcement
                    </button>
                </div>
                <div class="st-card">
                    <div class="st-card-section">
                        <div class="st-empty"><i class="bi bi-megaphone"></i>
                            <p>No active announcements</p>
                        </div>
                    </div>
                </div>
            </div>

            <!-- ---- FLASH SALES ---- -->
            <div class="st-panel" id="tab-flashsale">
                <div class="st-panel-head st-panel-head--row">
                    <div>
                        <h2 class="st-panel-title">Flash Sales</h2>
                        <p class="st-panel-sub">Create time-limited promotional campaigns</p>
                    </div>
                    <button class="st-btn st-btn-primary" data-bs-toggle="modal" data-bs-target="#addFlashSaleModal">
                        <i class="bi bi-plus-circle me-2"></i>Create Flash Sale
                    </button>
                </div>
                <div class="st-card">
                    <div class="st-card-section">
                        <div class="st-empty"><i class="bi bi-lightning-charge"></i>
                            <p>No active flash sales</p>
                        </div>
                    </div>
                </div>
            </div>

            <!-- ---- INTEGRATIONS ---- -->
            <div class="st-panel" id="tab-integrations">
                <div class="st-panel-head">
                    <h2 class="st-panel-title">Integrations</h2>
                    <p class="st-panel-sub">Connect third-party services and APIs</p>
                </div>
                <div class="st-card">
                    <div class="st-card-head">
                        <h3 class="st-card-title">Payment Gateways</h3>
                    </div>
                    <div class="st-card-section">
                        <div class="st-integration-grid">
                            <div class="st-integration-card">
                                <i class="bi bi-credit-card"></i>
                                <h4>Stripe</h4>
                                <span class="st-chip st-chip--green">Connected</span>
                            </div>
                            <div class="st-integration-card">
                                <i class="bi bi-paypal"></i>
                                <h4>PayPal</h4>
                                <span class="st-chip st-chip--gray">Not Connected</span>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <!-- ---- PREFERENCES ---- -->
            <div class="st-panel" id="tab-preferences">
                <div class="st-panel-head">
                    <h2 class="st-panel-title">General Preferences</h2>
                    <p class="st-panel-sub">Customize application behavior</p>
                </div>
                <div class="st-card">
                    <div class="st-card-section">
                        <div class="st-form-grid">
                            <div class="st-field">
                                <label class="st-label">Language</label>
                                <select class="st-select">
                                    <option>English</option>
                                    <option>Filipino</option>
                                </select>
                            </div>
                            <div class="st-field">
                                <label class="st-label">Items Per Page</label>
                                <select class="st-select">
                                    <option>10</option>
                                    <option>25</option>
                                    <option>50</option>
                                    <option>100</option>
                                </select>
                            </div>
                        </div>
                    </div>
                    <div class="st-card-footer"><button class="st-btn st-btn-primary">Save Preferences</button></div>
                </div>
            </div>

        </main>
    </div>
</div>
</main>

<!-- ============================================================
     MODALS
============================================================ -->

<!-- Add Shipping -->
<div class="modal fade" id="addShippingModal" tabindex="-1">
    <div class="modal-dialog modal-dialog-centered" style="max-width:420px;">
        <div class="modal-content st-modal">
            <div class="st-modal-head">
                <h5 class="st-modal-title">Add Shipping Zone</h5>
                <button class="st-modal-close" data-bs-dismiss="modal"><i class="bi bi-x-lg"></i></button>
            </div>
            <form method="POST">
                <div class="st-modal-body">
                    <div class="st-field">
                        <label class="st-label">Zone Name</label>
                        <input type="text" class="st-input" name="shipping_name" placeholder="e.g. Metro Manila" required>
                    </div>
                    <div class="st-field">
                        <label class="st-label">Price (₱)</label>
                        <input type="number" class="st-input" name="shipping_price" placeholder="50" required>
                    </div>
                    <div class="st-field">
                        <label class="st-label">Courier</label>
                        <input type="text" class="st-input" name="shipping_courier" placeholder="J&T Express, LBC…" required>
                    </div>
                </div>
                <div class="st-modal-foot">
                    <button type="button" class="st-btn st-btn-ghost" data-bs-dismiss="modal">Cancel</button>
                    <button type="submit" class="st-btn st-btn-primary" name="save_shipping">Save Zone</button>
                </div>
            </form>
        </div>
    </div>
</div>

<!-- Edit Payment -->
<div class="modal fade" id="editPaymentModal" tabindex="-1" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered" style="max-width:460px;">
        <div class="modal-content st-modal">
            <div class="st-modal-head">
                <h5 class="st-modal-title">Edit <span id="modal-method-name"></span></h5>
                <button class="st-modal-close" data-bs-dismiss="modal"><i class="bi bi-x-lg"></i></button>
            </div>
            <div class="st-modal-body">
                <input type="hidden" id="modal-method-key">
                <div class="st-field">
                    <label class="st-label">Customer Instructions (shown at checkout)</label>
                    <textarea id="modal-instructions" class="st-input st-textarea" rows="5" placeholder="e.g. GCash number, bank account details…"></textarea>
                </div>
            </div>
            <div class="st-modal-foot">
                <button class="st-btn st-btn-ghost" data-bs-dismiss="modal">Cancel</button>
                <button class="st-btn st-btn-primary" onclick="saveInstructions()">Save Changes</button>
            </div>
        </div>
    </div>
</div>

<!-- Add Voucher -->
<div class="modal fade" id="addVoucherModal" tabindex="-1">
    <div class="modal-dialog modal-dialog-centered" style="max-width:460px;">
        <form id="voucherForm">
            <div class="modal-content st-modal">
                <div class="st-modal-head">
                    <h5 class="st-modal-title">Create New Voucher</h5>
                    <button type="button" class="st-modal-close" data-bs-dismiss="modal"><i class="bi bi-x-lg"></i></button>
                </div>
                <div class="st-modal-body">
                    <div class="st-field">
                        <label class="st-label">Voucher Code</label>
                        <input type="text" name="code" class="st-input" placeholder="e.g. WELCOME2024" required>
                    </div>
                    <div class="st-form-grid">
                        <div class="st-field">
                            <label class="st-label">Discount Value</label>
                            <input type="number" name="amount" class="st-input" required>
                        </div>
                        <div class="st-field">
                            <label class="st-label">Type</label>
                            <select name="type" class="st-select">
                                <option value="fixed">Fixed (₱)</option>
                                <option value="percentage">Percentage (%)</option>
                            </select>
                        </div>
                    </div>
                    <div class="st-field">
                        <label class="st-label">Assign to User (optional)</label>
                        <select name="user_id" class="st-select">
                            <option value="">Everyone (Global)</option>
                            <?php
                            $u_res = mysqli_query($conn, "SELECT id, full_name FROM users ORDER BY full_name ASC");
                            while ($u = mysqli_fetch_assoc($u_res)) echo "<option value='{$u['id']}'>{$u['full_name']}</option>";
                            ?>
                        </select>
                    </div>
                    <div class="st-field">
                        <label class="st-label">Expiry Date</label>
                        <input type="date" name="expiry" class="st-input" required>
                    </div>
                </div>
                <div class="st-modal-foot">
                    <button type="button" class="st-btn st-btn-ghost" data-bs-dismiss="modal">Cancel</button>
                    <button type="submit" class="st-btn st-btn-primary">Create Voucher</button>
                </div>
            </div>
        </form>
    </div>
</div>

<!-- Announcement / Flash Sale (simple) -->
<div class="modal fade" id="addAnnouncementModal" tabindex="-1">
    <div class="modal-dialog modal-dialog-centered" style="max-width:420px;">
        <div class="modal-content st-modal">
            <div class="st-modal-head">
                <h5 class="st-modal-title">Create Announcement</h5>
                <button class="st-modal-close" data-bs-dismiss="modal"><i class="bi bi-x-lg"></i></button>
            </div>
            <div class="st-modal-body">
                <div class="st-field"><label class="st-label">Title</label><input type="text" class="st-input" placeholder="Announcement title"></div>
                <div class="st-field"><label class="st-label">Message</label><textarea class="st-input st-textarea" rows="3" placeholder="Message…"></textarea></div>
            </div>
            <div class="st-modal-foot">
                <button class="st-btn st-btn-ghost" data-bs-dismiss="modal">Cancel</button>
                <button class="st-btn st-btn-primary">Create</button>
            </div>
        </div>
    </div>
</div>

<div class="modal fade" id="addFlashSaleModal" tabindex="-1">
    <div class="modal-dialog modal-dialog-centered" style="max-width:420px;">
        <div class="modal-content st-modal">
            <div class="st-modal-head">
                <h5 class="st-modal-title">Create Flash Sale</h5>
                <button class="st-modal-close" data-bs-dismiss="modal"><i class="bi bi-x-lg"></i></button>
            </div>
            <div class="st-modal-body">
                <div class="st-field"><label class="st-label">Name</label><input type="text" class="st-input" placeholder="e.g. Weekend Mega Sale"></div>
                <div class="st-field"><label class="st-label">Discount (%)</label><input type="number" class="st-input" placeholder="50"></div>
            </div>
            <div class="st-modal-foot">
                <button class="st-btn st-btn-ghost" data-bs-dismiss="modal">Cancel</button>
                <button class="st-btn st-btn-primary">Create</button>
            </div>
        </div>
    </div>
</div>

<style>

</style>

<!-- ============================================================
     JAVASCRIPT
============================================================ -->
<script>
    // Tab switching
    document.querySelectorAll('.st-nav-item').forEach(link => {
        link.addEventListener('click', e => {
            e.preventDefault();
            const tab = link.dataset.tab;

            document.querySelectorAll('.st-nav-item').forEach(l => l.classList.remove('active'));
            document.querySelectorAll('.st-panel').forEach(p => p.classList.remove('active'));

            link.classList.add('active');
            const panel = document.getElementById('tab-' + tab);
            if (panel) panel.classList.add('active');

            // Update URL hash without scroll
            history.replaceState(null, '', '#' + tab);
        });
    });

    // Restore tab from hash
    (function() {
        const hash = location.hash.replace('#', '');
        if (hash) {
            const link = document.querySelector(`.st-nav-item[data-tab="${hash}"]`);
            if (link) link.click();
        }
    })();

    // Avatar preview
    function previewProfilePicture(input) {
        if (input.files && input.files[0]) {
            const reader = new FileReader();
            reader.onload = e => document.getElementById('profilePreview').src = e.target.result;
            reader.readAsDataURL(input.files[0]);
        }
    }

    // Payment modal helpers
    function openEditModal(key, name) {
        document.getElementById('modal-method-key').value = key;
        document.getElementById('modal-method-name').textContent = name;
        document.getElementById('modal-instructions').value = '';
        new bootstrap.Modal(document.getElementById('editPaymentModal')).show();
    }

    function saveInstructions() {
        bootstrap.Modal.getInstance(document.getElementById('editPaymentModal')).hide();
    }

    // Payment toggle opacity
    document.querySelectorAll('.payment-toggle').forEach(toggle => {
        toggle.addEventListener('change', function() {
            const card = this.closest('[id^="card-"]');
            if (card) card.style.opacity = this.checked ? '1' : '0.55';
            // TODO: fire AJAX to update payment_settings
        });
    });

    // Voucher modal
    function openVoucherModal() {
        new bootstrap.Modal(document.getElementById('addVoucherModal')).show();
    }

    function deleteVoucher(id) {
        if (!confirm('Delete this voucher?')) return;
        // TODO: AJAX delete
    }
</script>

<?php include 'includes/footer.php'; ?>