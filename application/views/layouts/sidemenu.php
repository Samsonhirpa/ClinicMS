<?php
$role = (string) $this->session->userdata('role');
$pendingRegistrationCount = isset($pendingRegistrationCount) ? (int) $pendingRegistrationCount : 0;
$pendingDiagnoseCount = isset($pendingDiagnoseCount) ? (int) $pendingDiagnoseCount : 0;
?>
<aside class="app-sidebar bg-white border-end shadow-sm">
    <div class="p-3 border-bottom position-sticky top-0 bg-white" style="z-index: 2;">
        <h5 class="mb-0">ClinicMS</h5>
        <small class="text-muted d-block">Welcome, <?= html_escape($this->session->userdata('name')); ?></small>
    </div>

    <nav class="p-3">
        <div class="list-group list-group-flush">
            <a href="<?= site_url('dashboard'); ?>" class="list-group-item list-group-item-action <?= $activeMenu === 'dashboard' ? 'active' : ''; ?>">Dashboard</a>

            <?php if (in_array($role, ['admin', 'reception'], true)): ?>
                <a href="<?= site_url('reception/dashboard'); ?>" class="list-group-item list-group-item-action <?= $activeMenu === 'reception_dashboard' ? 'active' : ''; ?>">Reception Dashboard</a>
                <a href="<?= site_url('patients'); ?>" class="list-group-item list-group-item-action <?= $activeMenu === 'patients_manage' ? 'active' : ''; ?>">Manage Patient</a>

                <div class="list-group-item">
                    <div class="fw-semibold mb-2">Patient Payments</div>
                    <div class="d-grid gap-1">
                        <a href="<?= site_url('patient-payments/registration'); ?>" class="btn btn-sm btn-outline-secondary text-start <?= $activeMenu === 'registration_fee' ? 'active' : ''; ?>">
                            Registration Fee <?= $pendingRegistrationCount > 0 ? '(' . $pendingRegistrationCount . ')' : ''; ?>
                        </a>
                        <a href="<?= site_url('patient-payments/diagnose'); ?>" class="btn btn-sm btn-outline-secondary text-start <?= $activeMenu === 'diagnose_fee' ? 'active' : ''; ?>">
                            Diagnose Fee <?= $pendingDiagnoseCount > 0 ? '(' . $pendingDiagnoseCount . ')' : ''; ?>
                        </a>
                    </div>
                </div>
            <?php endif; ?>

            <?php if (in_array($role, ['admin', 'doctor'], true)): ?>
                <a href="<?= site_url('opd'); ?>" class="list-group-item list-group-item-action <?= $activeMenu === 'opd' ? 'active' : ''; ?>">OPD Portal</a>
            <?php endif; ?>

            <?php if ($role === 'admin'): ?>
                <a href="<?= site_url('users'); ?>" class="list-group-item list-group-item-action <?= $activeMenu === 'users' ? 'active' : ''; ?>">User Management</a>
                <a href="<?= site_url('payments'); ?>" class="list-group-item list-group-item-action <?= $activeMenu === 'payments' ? 'active' : ''; ?>">Manage Fees</a>
                <a href="<?= site_url('payments/settings'); ?>" class="list-group-item list-group-item-action <?= $activeMenu === 'settings' ? 'active' : ''; ?>">Settings</a>
            <?php endif; ?>

            <a href="<?= site_url('logout'); ?>" class="list-group-item list-group-item-action text-danger">Logout</a>
        </div>
    </nav>
</aside>

<main class="app-main d-flex flex-column">
    <header class="bg-white border-bottom px-4 py-3 d-flex justify-content-between align-items-center">
        <h6 class="mb-0"><?= isset($title) ? html_escape($title) : 'ClinicMS'; ?></h6>
        <span class="text-muted"><?= ucfirst(html_escape($role)); ?></span>
    </header>
    <section class="<?= isset($containerClass) ? html_escape($containerClass) : 'app-content p-4'; ?>">
        <?php if ($this->session->flashdata('success')): ?>
            <div class="alert alert-success"><?= $this->session->flashdata('success'); ?></div>
        <?php endif; ?>
        <?php if ($this->session->flashdata('error')): ?>
            <div class="alert alert-danger"><?= $this->session->flashdata('error'); ?></div>
        <?php endif; ?>
