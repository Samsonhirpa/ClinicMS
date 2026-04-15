<?php $title = 'User Management'; $this->load->view('layouts/header', compact('title')); ?>
<div class="d-flex justify-content-between align-items-center mb-3">
    <h4 class="mb-0">User Management</h4>
</div>

<div class="card mb-4">
    <div class="card-header">Create User</div>
    <div class="card-body">
        <?= form_open('users/create'); ?>
        <div class="row g-3">
            <div class="col-md-4">
                <label class="form-label">Name</label>
                <input type="text" class="form-control" name="name" required>
            </div>
            <div class="col-md-4">
                <label class="form-label">Email</label>
                <input type="email" class="form-control" name="email" required>
            </div>
            <div class="col-md-4">
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
            <div class="col-md-3 d-flex align-items-end">
                <button class="btn btn-success">Create</button>
            </div>
        </div>
        <?= form_close(); ?>
    </div>
</div>

<div class="card">
    <div class="card-header">System Users</div>
    <div class="table-responsive">
        <table class="table table-striped mb-0 align-middle">
            <thead>
                <tr>
                    <th>#</th>
                    <th>Name</th>
                    <th>Email</th>
                    <th>Role</th>
                    <th>Status</th>
                    <th>Created</th>
                    <th style="width: 380px;">Actions</th>
                </tr>
            </thead>
            <tbody>
                <?php if (empty($users)): ?>
                    <tr><td colspan="7" class="text-center text-muted">No users found.</td></tr>
                <?php endif; ?>
                <?php foreach ($users as $user): ?>
                    <tr>
                        <td><?= (int) $user->id; ?></td>
                        <td><?= html_escape($user->name); ?></td>
                        <td><?= html_escape($user->email); ?></td>
                        <td><span class="badge bg-info text-dark"><?= ucfirst(html_escape($user->role)); ?></span></td>
                        <td>
                            <span class="badge <?= $user->status === 'active' ? 'bg-success' : 'bg-secondary'; ?>">
                                <?= ucfirst(html_escape($user->status)); ?>
                            </span>
                        </td>
                        <td><?= html_escape($user->created_at); ?></td>
                        <td>
                            <?= form_open('users/update/' . $user->id, ['class' => 'row row-cols-lg-auto g-2 align-items-center']); ?>
                                <div class="col-12"><input class="form-control form-control-sm" type="text" name="name" value="<?= html_escape($user->name); ?>" required></div>
                                <div class="col-12"><input class="form-control form-control-sm" type="email" name="email" value="<?= html_escape($user->email); ?>" required></div>
                                <div class="col-12"><input class="form-control form-control-sm" type="password" name="password" placeholder="New password"></div>
                                <div class="col-12">
                                    <select class="form-select form-select-sm" name="role" required>
                                        <?php foreach ($roles as $role): ?>
                                            <option value="<?= html_escape($role); ?>" <?= $role === $user->role ? 'selected' : ''; ?>><?= ucfirst($role); ?></option>
                                        <?php endforeach; ?>
                                    </select>
                                </div>
                                <div class="col-12">
                                    <select class="form-select form-select-sm" name="status" required>
                                        <?php foreach ($statuses as $status): ?>
                                            <option value="<?= html_escape($status); ?>" <?= $status === $user->status ? 'selected' : ''; ?>><?= ucfirst($status); ?></option>
                                        <?php endforeach; ?>
                                    </select>
                                </div>
                                <div class="col-12 d-flex gap-1">
                                    <button class="btn btn-sm btn-primary">Save</button>
                                    <a class="btn btn-sm btn-danger" href="<?= site_url('users/delete/' . $user->id); ?>" onclick="return confirm('Delete this user?');">Delete</a>
                                </div>
                            <?= form_close(); ?>
                        </td>
                    </tr>
                <?php endforeach; ?>
            </tbody>
        </table>
    </div>
</div>

<?php $this->load->view('layouts/footer'); ?>
