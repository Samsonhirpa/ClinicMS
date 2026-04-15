<aside class="app-sidebar bg-white border-end shadow-sm">
    <div class="p-3 border-bottom position-sticky top-0 bg-white" style="z-index: 2;">
        <h5 class="mb-0">ClinicMS</h5>
        <small class="text-muted d-block">Welcome, <?= html_escape($this->session->userdata('name')); ?></small>
    </div>

    <nav class="p-3">
        <div class="list-group list-group-flush">
            <a href="<?= site_url('dashboard'); ?>" class="list-group-item list-group-item-action <?= $activeMenu === 'dashboard' ? 'active' : ''; ?>">Dashboard</a>
            <a href="<?= site_url('users'); ?>" class="list-group-item list-group-item-action <?= $activeMenu === 'users' ? 'active' : ''; ?>">User Management</a>

            <div class="list-group-item">
                <div class="fw-semibold mb-2">Payment</div>
                <div class="d-grid gap-1">
                    <a href="<?= site_url('payments?type=registration'); ?>" class="btn btn-sm btn-outline-secondary text-start">Registration Fee</a>
                    <a href="<?= site_url('payments?type=diagnose'); ?>" class="btn btn-sm btn-outline-secondary text-start">Diagnose Fee</a>
                    <a href="<?= site_url('payments'); ?>" class="btn btn-sm btn-outline-secondary text-start <?= $activeMenu === 'payments' ? 'active' : ''; ?>">Manage Fees</a>
                </div>
            </div>

            <a href="<?= site_url('payments/settings'); ?>" class="list-group-item list-group-item-action <?= $activeMenu === 'settings' ? 'active' : ''; ?>">Settings</a>
            <a href="<?= site_url('logout'); ?>" class="list-group-item list-group-item-action text-danger">Logout</a>
        </div>
    </nav>
</aside>

<main class="app-main d-flex flex-column">
    <header class="bg-white border-bottom px-4 py-3 d-flex justify-content-between align-items-center">
        <h6 class="mb-0"><?= isset($title) ? html_escape($title) : 'ClinicMS'; ?></h6>
        <span class="text-muted"><?= ucfirst(html_escape($this->session->userdata('role'))); ?></span>
    </header>
    <section class="<?= isset($containerClass) ? html_escape($containerClass) : 'app-content p-4'; ?>">
        <?php if ($this->session->flashdata('success')): ?>
            <div class="alert alert-success"><?= $this->session->flashdata('success'); ?></div>
        <?php endif; ?>
        <?php if ($this->session->flashdata('error')): ?>
            <div class="alert alert-danger"><?= $this->session->flashdata('error'); ?></div>
        <?php endif; ?>
