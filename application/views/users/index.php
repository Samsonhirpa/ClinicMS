<?php
$title = 'Admin Dashboard';
$bodyClass = 'bg-light';
$containerClass = 'container-fluid py-4';
$this->load->view('layouts/header', compact('title', 'bodyClass', 'containerClass'));
?>
<div class="row g-4">
    <aside class="col-lg-3 col-xl-2">
        <div class="card border-0 shadow-sm sticky-top" style="top: 1rem;">
            <div class="card-body p-3">
                <h6 class="text-uppercase text-muted mb-3">Admin Portal</h6>
                <div class="list-group list-group-flush">
                    <a href="#dashboard" class="list-group-item list-group-item-action active">Dashboard</a>
                    <a href="#user-management" class="list-group-item list-group-item-action">User Management</a>
                    <a href="#" class="list-group-item list-group-item-action disabled">Registration Fee</a>
                    <a href="#" class="list-group-item list-group-item-action disabled">Appointments</a>
                    <a href="#" class="list-group-item list-group-item-action disabled">Reports</a>
                </div>
            </div>
        </div>
    </aside>

    <section class="col-lg-9 col-xl-10">
        <div id="dashboard" class="mb-4">
            <h3 class="mb-1">Admin Dashboard</h3>
            <p class="text-muted mb-3">Manage users and monitor access from one place.</p>
            <div class="row g-3">
                <div class="col-md-6 col-xl-3">
                    <div class="card border-0 shadow-sm h-100">
                        <div class="card-body">
                            <div class="text-muted small">Total Users</div>
                            <div class="display-6 fw-semibold"><?= (int) $counts['total_users']; ?></div>
                        </div>
                    </div>
                </div>
                <div class="col-md-6 col-xl-3">
                    <div class="card border-0 shadow-sm h-100">
                        <div class="card-body">
                            <div class="text-muted small">Active Users</div>
                            <div class="display-6 fw-semibold text-success"><?= (int) $counts['active_users']; ?></div>
                        </div>
                    </div>
                </div>
                <div class="col-md-6 col-xl-3">
                    <div class="card border-0 shadow-sm h-100">
                        <div class="card-body">
                            <div class="text-muted small">Inactive Users</div>
                            <div class="display-6 fw-semibold text-secondary"><?= (int) $counts['inactive_users']; ?></div>
                        </div>
                    </div>
                </div>
                <div class="col-md-6 col-xl-3">
                    <div class="card border-0 shadow-sm h-100">
                        <div class="card-body">
                            <div class="text-muted small">Admins</div>
                            <div class="display-6 fw-semibold text-primary"><?= (int) $counts['admins']; ?></div>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <div id="user-management" class="card border-0 shadow-sm">
            <div class="card-header bg-white d-flex flex-wrap gap-2 justify-content-between align-items-center">
                <h5 class="mb-0">User Management</h5>
                <button class="btn btn-primary" data-bs-toggle="modal" data-bs-target="#addUserModal">+ Add New User</button>
            </div>

            <div class="card-body border-bottom">
                <?= form_open('users', ['method' => 'get', 'class' => 'row g-2']); ?>
                    <div class="col-md-4">
                        <input type="text" class="form-control" name="search" placeholder="Search by name or email" value="<?= html_escape($filters['search']); ?>">
                    </div>
                    <div class="col-md-3">
                        <select name="role" class="form-select">
                            <option value="">All Roles</option>
                            <?php foreach ($roles as $role): ?>
                                <option value="<?= html_escape($role); ?>" <?= $filters['role'] === $role ? 'selected' : ''; ?>><?= ucfirst($role); ?></option>
                            <?php endforeach; ?>
                        </select>
                    </div>
                    <div class="col-md-3">
                        <select name="status" class="form-select">
                            <option value="">All Statuses</option>
                            <?php foreach ($statuses as $status): ?>
                                <option value="<?= html_escape($status); ?>" <?= $filters['status'] === $status ? 'selected' : ''; ?>><?= ucfirst($status); ?></option>
                            <?php endforeach; ?>
                        </select>
                    </div>
                    <div class="col-md-2 d-grid">
                        <button class="btn btn-outline-primary" type="submit">Filter</button>
                    </div>
                <?= form_close(); ?>
            </div>

            <div class="table-responsive">
                <table class="table align-middle mb-0">
                    <thead class="table-light">
                        <tr>
                            <th>#</th>
                            <th>Name</th>
                            <th>Email</th>
                            <th>Role</th>
                            <th>Status</th>
                            <th>Created</th>
                            <th class="text-end">Action</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php if (empty($users)): ?>
                            <tr><td colspan="7" class="text-center text-muted py-4">No users match your filter.</td></tr>
                        <?php endif; ?>
                        <?php foreach ($users as $user): ?>
                            <tr>
                                <td><?= (int) $user->id; ?></td>
                                <td><?= html_escape($user->name); ?></td>
                                <td><?= html_escape($user->email); ?></td>
                                <td><span class="badge bg-info-subtle text-info-emphasis border"><?= ucfirst(html_escape($user->role)); ?></span></td>
                                <td>
                                    <span class="badge <?= $user->status === 'active' ? 'bg-success' : 'bg-secondary'; ?>">
                                        <?= ucfirst(html_escape($user->status)); ?>
                                    </span>
                                </td>
                                <td><?= html_escape($user->created_at); ?></td>
                                <td class="text-end">
                                    <a class="btn btn-sm btn-outline-danger" href="<?= site_url('users/delete/' . $user->id); ?>" onclick="return confirm('Delete this user?');">Delete</a>
                                </td>
                            </tr>
                        <?php endforeach; ?>
                    </tbody>
                </table>
            </div>
        </div>
    </section>
</div>

<div class="modal fade" id="addUserModal" tabindex="-1" aria-labelledby="addUserModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-lg modal-dialog-centered">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="addUserModalLabel">Register New User</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <?= form_open('users/create'); ?>
                <div class="modal-body">
                    <div class="row g-3">
                        <div class="col-md-6">
                            <label class="form-label">Full Name</label>
                            <input type="text" class="form-control" name="name" required>
                        </div>
                        <div class="col-md-6">
                            <label class="form-label">Email</label>
                            <input type="email" class="form-control" name="email" required>
                        </div>
                        <div class="col-md-6">
                            <label class="form-label">Password</label>
                            <input type="password" class="form-control" name="password" required>
                        </div>
                        <div class="col-md-3">
                            <label class="form-label">Role</label>
                            <select class="form-select" name="role" required>
                                <?php foreach ($roles as $role): ?>
                                    <option value="<?= html_escape($role); ?>"><?= ucfirst($role); ?></option>
                                <?php endforeach; ?>
                            </select>
                        </div>
                        <div class="col-md-3">
                            <label class="form-label">Status</label>
                            <select class="form-select" name="status" required>
                                <?php foreach ($statuses as $status): ?>
                                    <option value="<?= html_escape($status); ?>"><?= ucfirst($status); ?></option>
                                <?php endforeach; ?>
                            </select>
                        </div>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-light" data-bs-dismiss="modal">Cancel</button>
                    <button class="btn btn-primary">Register User</button>
                </div>
            <?= form_close(); ?>
        </div>
    </div>
</div>

<?php $this->load->view('layouts/footer'); ?>
