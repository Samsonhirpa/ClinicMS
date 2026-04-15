<div class="card border-0 shadow-sm mb-4">
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
                        <td class="text-end d-flex gap-2 justify-content-end">
                            <button class="btn btn-sm btn-outline-primary" data-bs-toggle="modal" data-bs-target="#editUserModal<?= (int) $user->id; ?>">Edit</button>
                            <a class="btn btn-sm btn-outline-danger" href="<?= site_url('users/delete/' . $user->id); ?>" onclick="return confirm('Delete this user?');">Delete</a>
                        </td>
                    </tr>
                <?php endforeach; ?>
            </tbody>
        </table>
    </div>
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
                        <div class="col-md-6"><label class="form-label">Full Name</label><input type="text" class="form-control" name="name" required></div>
                        <div class="col-md-6"><label class="form-label">Email</label><input type="email" class="form-control" name="email" required></div>
                        <div class="col-md-6"><label class="form-label">Password</label><input type="password" class="form-control" name="password" required></div>
                        <div class="col-md-3">
                            <label class="form-label">Role</label>
                            <select class="form-select" name="role" required>
                                <?php foreach ($roles as $role): ?><option value="<?= html_escape($role); ?>"><?= ucfirst($role); ?></option><?php endforeach; ?>
                            </select>
                        </div>
                        <div class="col-md-3">
                            <label class="form-label">Status</label>
                            <select class="form-select" name="status" required>
                                <?php foreach ($statuses as $status): ?><option value="<?= html_escape($status); ?>"><?= ucfirst($status); ?></option><?php endforeach; ?>
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

<?php foreach ($users as $user): ?>
<div class="modal fade" id="editUserModal<?= (int) $user->id; ?>" tabindex="-1" aria-hidden="true">
    <div class="modal-dialog modal-lg modal-dialog-centered">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title">Edit User</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <?= form_open('users/update/' . $user->id); ?>
                <div class="modal-body">
                    <div class="row g-3">
                        <div class="col-md-6"><label class="form-label">Full Name</label><input type="text" class="form-control" name="name" value="<?= html_escape($user->name); ?>" required></div>
                        <div class="col-md-6"><label class="form-label">Email</label><input type="email" class="form-control" name="email" value="<?= html_escape($user->email); ?>" required></div>
                        <div class="col-md-6"><label class="form-label">New Password (optional)</label><input type="password" class="form-control" name="password"></div>
                        <div class="col-md-3"><label class="form-label">Role</label><select class="form-select" name="role" required><?php foreach ($roles as $role): ?><option value="<?= html_escape($role); ?>" <?= $user->role === $role ? 'selected' : ''; ?>><?= ucfirst($role); ?></option><?php endforeach; ?></select></div>
                        <div class="col-md-3"><label class="form-label">Status</label><select class="form-select" name="status" required><?php foreach ($statuses as $status): ?><option value="<?= html_escape($status); ?>" <?= $user->status === $status ? 'selected' : ''; ?>><?= ucfirst($status); ?></option><?php endforeach; ?></select></div>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-light" data-bs-dismiss="modal">Cancel</button>
                    <button class="btn btn-primary">Save Changes</button>
                </div>
            <?= form_close(); ?>
        </div>
    </div>
</div>
<?php endforeach; ?>
