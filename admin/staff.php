<?php
include 'db/db.php'; // Ensure this file defines the $pdo variable
include 'includes/header.php';

try {
    // 1. Fetch Total Staff
    $total_stmt = $pdo->query("SELECT COUNT(*) FROM staff");
    $total_staff = $total_stmt->fetchColumn();

    // 2. Fetch Active Staff
    $active_stmt = $pdo->query("SELECT COUNT(*) FROM staff WHERE status = 'active'");
    $active_staff = $active_stmt->fetchColumn();

    // 3. Fetch Admins
    $admin_stmt = $pdo->query("SELECT COUNT(*) FROM staff WHERE department = 'Administration'");
    $admin_staff = $admin_stmt->fetchColumn();

    // 4. Fetch All Staff Members for the table
    $staff_list_stmt = $pdo->query("SELECT * FROM staff ORDER BY id DESC");
    $staff_members = $staff_list_stmt->fetchAll(PDO::FETCH_ASSOC);

    // 5. Fetch counts per department for the sidebar
    $dept_stmt = $pdo->query("SELECT department, COUNT(*) as count FROM staff GROUP BY department");
    $depts = $dept_stmt->fetchAll(PDO::FETCH_ASSOC);
} catch (PDOException $e) {
    die("Database Error: " . $e->getMessage());
}
?>
<!-- Staff Content -->
<div class="container-fluid p-4">
    <div class="d-flex justify-content-end mb-4">
        <button class="btn btn-primary btn-lg" data-bs-toggle="modal" data-bs-target="#addStaffModal">
            <i class="bi bi-person-plus me-2"></i>Add Staff Member
        </button>
    </div>

    <!-- Statistics Cards -->
    <div class="row g-3 mb-4">
        <div class="col-md-3">
            <div class="card border-0 shadow-sm h-100">
                <div class="card-body">
                    <div class="d-flex justify-content-between align-items-center">
                        <div>
                            <p class="text-muted mb-1 small">Total Staff</p>
                            <h3 class="mb-0 fw-bold"><?php echo $total_staff; ?></h3>
                        </div>
                        <div class="stat-icon bg-primary"><i class="bi bi-people text-white"></i></div>
                    </div>
                </div>
            </div>
        </div>
        <div class="col-md-3">
            <div class="card border-0 shadow-sm h-100">
                <div class="card-body">
                    <div class="d-flex justify-content-between align-items-center">
                        <div>
                            <p class="text-muted mb-1 small">Active</p>
                            <h3 class="mb-0 fw-bold text-success"><?php echo $active_staff; ?></h3>
                        </div>
                        <div class="stat-icon bg-success"><i class="bi bi-person-check text-white"></i></div>
                    </div>
                </div>
            </div>
        </div>
        <div class="col-md-3">
            <div class="card border-0 shadow-sm h-100">
                <div class="card-body">
                    <div class="d-flex justify-content-between align-items-center">
                        <div>
                            <p class="text-muted mb-1 small">Admins</p>
                            <h3 class="mb-0 fw-bold text-warning"><?php echo $admin_staff; ?></h3>
                        </div>
                        <div class="stat-icon bg-warning"><i class="bi bi-shield-check text-white"></i></div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <div class="row g-4">
        <!-- Staff List -->
        <div class="col-lg-8">
            <div class="card border-0 shadow-sm">
                <div class="card-body">
                    <div class="d-flex justify-content-between align-items-center mb-4">
                        <h5 class="card-title mb-0">Team Members</h5>
                        <div class="d-flex gap-2">
                            <select class="form-select form-select-sm" style="width: auto;">
                                <option>All Roles</option>
                                <option>Administrator</option>
                                <option>Manager</option>
                                <option>Sales</option>
                                <option>Support</option>
                            </select>
                            <div class="input-group" style="width: 250px;">
                                <span class="input-group-text bg-transparent">
                                    <i class="bi bi-search"></i>
                                </span>
                                <input type="text" class="form-control form-control-sm" placeholder="Search staff...">
                            </div>
                        </div>
                    </div>

                    <!-- Staff Table -->
                    <div class="table-responsive">
                        <table class="table table-hover align-middle">
                            <thead class="table-light">
                                <tr>
                                    <th>Account ID</th>
                                    <th>Staff Member</th>
                                    <th>Role</th>
                                    <th>Status</th>
                                    <th class="text-end">Actions</th>
                                </tr>
                            </thead>
                            <tbody>
                                <?php if (empty($staff_members)): ?>
                                    <tr>
                                        <td colspan="5" class="text-center">No staff members found.</td>
                                    </tr>
                                <?php else: ?>
                                    <?php foreach ($staff_members as $row):
                                        $fullname = $row['first_name'] . ' ' . $row['last_name'];
                                        $status_class = ($row['status'] == 'active') ? 'bg-success' : 'bg-secondary';
                                    ?>
                                        <tr>
                                            <td><small class="text-muted"><?= htmlspecialchars($row['staff_id']) ?></small></td>
                                            <td>
                                                <div class="d-flex align-items-center">
                                                    <img src="https://ui-avatars.com/api/?name=<?= urlencode($fullname) ?>&background=random&color=fff" class="rounded-circle me-3" width="40" height="40">
                                                    <div>
                                                        <h6 class="mb-0"><?= htmlspecialchars($fullname) ?></h6>
                                                        <small class="text-muted"><?= htmlspecialchars($row['email']) ?></small>
                                                    </div>
                                                </div>
                                            </td>
                                            <td><span class="badge bg-primary"><?= htmlspecialchars($row['department']) ?></span></td>
                                            <td><span class="badge <?= $status_class ?>"><?= ucfirst($row['status']) ?></span></td>
                                            <td class="text-end">
                                                <div class="btn-group btn-group-sm">
                                                    <button class="btn btn-outline-primary"><i class="bi bi-pencil"></i></button>
                                                    <button class="btn btn-outline-danger"><i class="bi bi-trash"></i></button>
                                                </div>
                                            </td>
                                        </tr>
                                    <?php endforeach; ?>
                                <?php endif; ?>
                            </tbody>
                        </table>
                    </div>

                    <!-- Pagination -->
                    <div class="d-flex justify-content-between align-items-center mt-3">
                        <small class="text-muted">Showing 1 to 5 of 24 staff members</small>
                        <nav>
                            <ul class="pagination pagination-sm mb-0">
                                <li class="page-item disabled">
                                    <a class="page-link" href="#">Previous</a>
                                </li>
                                <li class="page-item active"><a class="page-link" href="#">1</a></li>
                                <li class="page-item"><a class="page-link" href="#">2</a></li>
                                <li class="page-item"><a class="page-link" href="#">3</a></li>
                                <li class="page-item"><a class="page-link" href="#">4</a></li>
                                <li class="page-item"><a class="page-link" href="#">5</a></li>
                                <li class="page-item">
                                    <a class="page-link" href="#">Next</a>
                                </li>
                            </ul>
                        </nav>
                    </div>
                </div>
            </div>
        </div>

        <!-- Roles Sidebar -->
        <div class="col-lg-4">
            <div class="card border-0 shadow-sm mb-4">
                <div class="card-body">
                    <div class="d-flex justify-content-between align-items-center mb-3">
                        <h5 class="card-title mb-0">Roles & Permissions</h5>
                        <button class="btn btn-sm btn-primary" data-bs-toggle="modal" data-bs-target="#addRoleModal">
                            <i class="bi bi-plus-circle"></i>
                        </button>
                    </div>

                    <div class="list-group list-group-flush">
                        <!-- Administrator Role -->
                        <div class="list-group-item px-0 py-3">
                            <div class="d-flex justify-content-between align-items-start mb-2">
                                <div>
                                    <h6 class="mb-1 d-flex align-items-center">
                                        <span class="badge bg-danger me-2">Admin</span>
                                        Administrator
                                    </h6>
                                    <small class="text-muted">Full system access</small>
                                </div>
                                <div class="btn-group btn-group-sm">
                                    <button class="btn btn-outline-secondary" onclick="editRole('admin')">
                                        <i class="bi bi-pencil"></i>
                                    </button>
                                </div>
                            </div>
                            <div class="d-flex gap-1 flex-wrap">
                                <span class="badge bg-light text-dark border">All Permissions</span>
                            </div>
                            <div class="mt-2">
                                <small class="text-muted"><i class="bi bi-people me-1"></i>3 members</small>
                            </div>
                        </div>

                        <!-- Manager Role -->
                        <div class="list-group-item px-0 py-3">
                            <div class="d-flex justify-content-between align-items-start mb-2">
                                <div>
                                    <h6 class="mb-1 d-flex align-items-center">
                                        <span class="badge bg-primary me-2">MGR</span>
                                        Manager
                                    </h6>
                                    <small class="text-muted">Manage products & orders</small>
                                </div>
                                <div class="btn-group btn-group-sm">
                                    <button class="btn btn-outline-secondary" onclick="editRole('manager')">
                                        <i class="bi bi-pencil"></i>
                                    </button>
                                </div>
                            </div>
                            <div class="d-flex gap-1 flex-wrap">
                                <span class="badge bg-light text-dark border">Products</span>
                                <span class="badge bg-light text-dark border">Orders</span>
                                <span class="badge bg-light text-dark border">Reports</span>
                            </div>
                            <div class="mt-2">
                                <small class="text-muted"><i class="bi bi-people me-1"></i>5 members</small>
                            </div>
                        </div>

                        <!-- Sales Role -->
                        <div class="list-group-item px-0 py-3">
                            <div class="d-flex justify-content-between align-items-start mb-2">
                                <div>
                                    <h6 class="mb-1 d-flex align-items-center">
                                        <span class="badge bg-info me-2">SLS</span>
                                        Sales
                                    </h6>
                                    <small class="text-muted">Process orders & customers</small>
                                </div>
                                <div class="btn-group btn-group-sm">
                                    <button class="btn btn-outline-secondary" onclick="editRole('sales')">
                                        <i class="bi bi-pencil"></i>
                                    </button>
                                </div>
                            </div>
                            <div class="d-flex gap-1 flex-wrap">
                                <span class="badge bg-light text-dark border">Orders</span>
                                <span class="badge bg-light text-dark border">Customers</span>
                            </div>
                            <div class="mt-2">
                                <small class="text-muted"><i class="bi bi-people me-1"></i>8 members</small>
                            </div>
                        </div>

                        <!-- Support Role -->
                        <div class="list-group-item px-0 py-3">
                            <div class="d-flex justify-content-between align-items-start mb-2">
                                <div>
                                    <h6 class="mb-1 d-flex align-items-center">
                                        <span class="badge bg-success me-2">SUP</span>
                                        Support
                                    </h6>
                                    <small class="text-muted">Handle customer queries</small>
                                </div>
                                <div class="btn-group btn-group-sm">
                                    <button class="btn btn-outline-secondary" onclick="editRole('support')">
                                        <i class="bi bi-pencil"></i>
                                    </button>
                                </div>
                            </div>
                            <div class="d-flex gap-1 flex-wrap">
                                <span class="badge bg-light text-dark border">Messages</span>
                                <span class="badge bg-light text-dark border">Tickets</span>
                            </div>
                            <div class="mt-2">
                                <small class="text-muted"><i class="bi bi-people me-1"></i>6 members</small>
                            </div>
                        </div>

                        <!-- Inventory Role -->
                        <div class="list-group-item px-0 py-3 border-0">
                            <div class="d-flex justify-content-between align-items-start mb-2">
                                <div>
                                    <h6 class="mb-1 d-flex align-items-center">
                                        <span class="badge bg-warning me-2">INV</span>
                                        Inventory
                                    </h6>
                                    <small class="text-muted">Manage stock & suppliers</small>
                                </div>
                                <div class="btn-group btn-group-sm">
                                    <button class="btn btn-outline-secondary" onclick="editRole('inventory')">
                                        <i class="bi bi-pencil"></i>
                                    </button>
                                </div>
                            </div>
                            <div class="d-flex gap-1 flex-wrap">
                                <span class="badge bg-light text-dark border">Products</span>
                                <span class="badge bg-light text-dark border">Inventory</span>
                            </div>
                            <div class="mt-2">
                                <small class="text-muted"><i class="bi bi-people me-1"></i>2 members</small>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
</main>

<!-- Add Staff Modal -->
<div class="modal fade" id="addStaffModal" tabindex="-1" aria-hidden="true">
    <div class="modal-dialog modal-lg">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title"><i class="bi bi-person-plus me-2"></i>Add New Staff Member</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
            </div>
            <form action="staff/add_staff.php" method="POST">
                <div class="modal-body">
                    <div class="row g-3">
                        <div class="col-md-6">
                            <label class="form-label">First Name *</label>
                            <input type="text" name="first_name" class="form-control" required>
                        </div>
                        <div class="col-md-6">
                            <label class="form-label">Last Name *</label>
                            <input type="text" name="last_name" class="form-control" required>
                        </div>
                        <div class="col-md-6">
                            <label class="form-label">Email Address *</label>
                            <input type="email" name="email" class="form-control" required>
                        </div>
                        <div class="col-md-6">
                            <label class="form-label">Phone Number</label>
                            <input type="tel" name="phone" class="form-control">
                        </div>
                        <div class="col-12">
                            <label class="form-label">Department *</label>
                            <select name="department" class="form-select" required>
                                <option value="">Select Department</option>
                                <option value="Administration">Administration</option>
                                <option value="Sales">Sales</option>
                                <option value="Customer Support">Customer Support</option>
                                <option value="IT">IT / Engineering</option>
                            </select>
                        </div>
                        <div class="col-md-6">
                            <label class="form-label">Status</label>
                            <select name="status" class="form-select">
                                <option value="active">Active</option>
                                <option value="inactive">Inactive</option>
                            </select>
                        </div>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancel</button>
                    <button type="submit" name="submit_staff" class="btn btn-primary">
                        <i class="bi bi-check-circle me-1"></i>Add Staff Member
                    </button>
                </div>
            </form>
        </div>
    </div>
</div>

<!-- Add Role Modal -->
<div class="modal fade" id="addRoleModal" tabindex="-1" aria-hidden="true">
    <div class="modal-dialog modal-lg">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title">
                    <i class="bi bi-shield-plus me-2"></i>Create New Role
                </h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
            </div>
            <div class="modal-body">
                <form id="addRoleForm">
                    <div class="mb-3">
                        <label class="form-label">Role Name *</label>
                        <input type="text" class="form-control" placeholder="e.g., Marketing Manager" required>
                    </div>
                    <div class="mb-3">
                        <label class="form-label">Description</label>
                        <textarea class="form-control" rows="2" placeholder="Brief description of this role"></textarea>
                    </div>
                    <div class="mb-3">
                        <label class="form-label">Badge Color</label>
                        <div class="d-flex gap-2">
                            <input type="radio" class="btn-check" name="badgeColor" id="colorPrimary" checked>
                            <label class="btn btn-outline-primary" for="colorPrimary">Primary</label>

                            <input type="radio" class="btn-check" name="badgeColor" id="colorSuccess">
                            <label class="btn btn-outline-success" for="colorSuccess">Success</label>

                            <input type="radio" class="btn-check" name="badgeColor" id="colorDanger">
                            <label class="btn btn-outline-danger" for="colorDanger">Danger</label>

                            <input type="radio" class="btn-check" name="badgeColor" id="colorWarning">
                            <label class="btn btn-outline-warning" for="colorWarning">Warning</label>

                            <input type="radio" class="btn-check" name="badgeColor" id="colorInfo">
                            <label class="btn btn-outline-info" for="colorInfo">Info</label>
                        </div>
                    </div>
                    <div class="mb-3">
                        <label class="form-label fw-bold">Permissions</label>
                        <div class="row g-3">
                            <div class="col-md-6">
                                <div class="card border">
                                    <div class="card-body">
                                        <h6 class="mb-3">Products</h6>
                                        <div class="form-check mb-2">
                                            <input class="form-check-input" type="checkbox" id="productView">
                                            <label class="form-check-label" for="productView">View Products</label>
                                        </div>
                                        <div class="form-check mb-2">
                                            <input class="form-check-input" type="checkbox" id="productCreate">
                                            <label class="form-check-label" for="productCreate">Create Products</label>
                                        </div>
                                        <div class="form-check mb-2">
                                            <input class="form-check-input" type="checkbox" id="productEdit">
                                            <label class="form-check-label" for="productEdit">Edit Products</label>
                                        </div>
                                        <div class="form-check">
                                            <input class="form-check-input" type="checkbox" id="productDelete">
                                            <label class="form-check-label" for="productDelete">Delete Products</label>
                                        </div>
                                    </div>
                                </div>
                            </div>
                            <div class="col-md-6">
                                <div class="card border">
                                    <div class="card-body">
                                        <h6 class="mb-3">Orders</h6>
                                        <div class="form-check mb-2">
                                            <input class="form-check-input" type="checkbox" id="orderView">
                                            <label class="form-check-label" for="orderView">View Orders</label>
                                        </div>
                                        <div class="form-check mb-2">
                                            <input class="form-check-input" type="checkbox" id="orderProcess">
                                            <label class="form-check-label" for="orderProcess">Process Orders</label>
                                        </div>
                                        <div class="form-check mb-2">
                                            <input class="form-check-input" type="checkbox" id="orderCancel">
                                            <label class="form-check-label" for="orderCancel">Cancel Orders</label>
                                        </div>
                                        <div class="form-check">
                                            <input class="form-check-input" type="checkbox" id="orderRefund">
                                            <label class="form-check-label" for="orderRefund">Issue Refunds</label>
                                        </div>
                                    </div>
                                </div>
                            </div>
                            <div class="col-md-6">
                                <div class="card border">
                                    <div class="card-body">
                                        <h6 class="mb-3">Customers</h6>
                                        <div class="form-check mb-2">
                                            <input class="form-check-input" type="checkbox" id="customerView">
                                            <label class="form-check-label" for="customerView">View Customers</label>
                                        </div>
                                        <div class="form-check mb-2">
                                            <input class="form-check-input" type="checkbox" id="customerEdit">
                                            <label class="form-check-label" for="customerEdit">Edit Customers</label>
                                        </div>
                                        <div class="form-check">
                                            <input class="form-check-input" type="checkbox" id="customerDelete">
                                            <label class="form-check-label" for="customerDelete">Delete Customers</label>
                                        </div>
                                    </div>
                                </div>
                            </div>
                            <div class="col-md-6">
                                <div class="card border">
                                    <div class="card-body">
                                        <h6 class="mb-3">Reports & Analytics</h6>
                                        <div class="form-check mb-2">
                                            <input class="form-check-input" type="checkbox" id="reportsView">
                                            <label class="form-check-label" for="reportsView">View Reports</label>
                                        </div>
                                        <div class="form-check mb-2">
                                            <input class="form-check-input" type="checkbox" id="reportsExport">
                                            <label class="form-check-label" for="reportsExport">Export Reports</label>
                                        </div>
                                        <div class="form-check">
                                            <input class="form-check-input" type="checkbox" id="analytics">
                                            <label class="form-check-label" for="analytics">Access Analytics</label>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </form>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancel</button>
                <button type="button" class="btn btn-primary" onclick="saveRole()">
                    <i class="bi bi-check-circle me-1"></i>Create Role
                </button>
            </div>
        </div>
    </div>
</div>

<?php if (isset($_GET['status'])): ?>
    <script>
        document.addEventListener('DOMContentLoaded', function() {
            const status = "<?php echo $_GET['status']; ?>";

            if (status === "success") {
                showToast("Staff added successfully!", "success");
            }

            if (status === "mail_error") {
                showToast("Staff saved but email failed to send.", "danger");
            }
        });
    </script>
<?php endif; ?>

<!-- TOAST -->
<div class="toast-container position-fixed bottom-0 end-0 p-3">
    <div id="liveToast" class="toast overflow-hidden border-0" role="alert" aria-live="assertive" aria-atomic="true" data-bs-delay="5000">
        <div class="d-flex">
            <div class="toast-body" id="toastMessage">
            </div>
            <button type="button" class="btn-close btn-close-white me-2 m-auto" data-bs-dismiss="toast" aria-label="Close"></button>
        </div>
        <div class="toast-progress-container" style="height: 4px; background: rgba(0,0,0,0.1);">
            <div id="toastProgressBar" style="height: 100%; width: 100%; background: rgba(255,255,255,0.7); transition: width 5s linear;"></div>
        </div>
    </div>
</div>
<?php
include 'includes/footer.php';
?>