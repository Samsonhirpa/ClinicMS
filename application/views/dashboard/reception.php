<div class="row g-3">
    <div class="col-md-2"><div class="card border-0 shadow-sm"><div class="card-body"><div class="text-muted small">Total Patients</div><div class="display-6 fw-semibold"><?= (int) $totalPatients; ?></div></div></div></div>
    <div class="col-md-2"><div class="card border-0 shadow-sm"><div class="card-body"><div class="text-muted small">Pending Registration</div><div class="display-6 fw-semibold text-warning"><?= (int) $pendingRegistrationCount; ?></div></div></div></div>
    <div class="col-md-2"><div class="card border-0 shadow-sm"><div class="card-body"><div class="text-muted small">Pending Diagnose</div><div class="display-6 fw-semibold text-warning"><?= (int) $pendingDiagnoseCount; ?></div></div></div></div>
    <div class="col-md-2"><div class="card border-0 shadow-sm"><div class="card-body"><div class="text-muted small">Ready for OPD</div><div class="display-6 fw-semibold text-success"><?= (int) $opdReadyCount; ?></div></div></div></div>
    <div class="col-md-2"><div class="card border-0 shadow-sm"><div class="card-body"><div class="text-muted small">Pending Lab Fee</div><div class="display-6 fw-semibold text-warning"><?= (int) $pendingLabCount; ?></div></div></div></div>
</div>

<div class="card border-0 shadow-sm mt-4">
    <div class="card-body">
        <h5 class="mb-3">Quick Actions</h5>
        <div class="d-flex flex-wrap gap-2">
            <a href="<?= site_url('patients'); ?>" class="btn btn-primary">Add / Manage Patient</a>
            <a href="<?= site_url('patient-payments/registration'); ?>" class="btn btn-outline-secondary">Review Registration Payments</a>
            <a href="<?= site_url('patient-payments/diagnose'); ?>" class="btn btn-outline-secondary">Review Diagnose Payments</a>
            <a href="<?= site_url('patient-payments/lab'); ?>" class="btn btn-outline-secondary">Review Lab Payments</a>
        </div>
    </div>
</div>
