<div class="row g-3 mb-4">
    <div class="col-md-3"><div class="card border-0 shadow-sm"><div class="card-body"><div class="text-muted small">New Patients</div><div class="display-6 fw-semibold"><?= (int) $counts['newPatients']; ?></div></div></div></div>
    <div class="col-md-3"><div class="card border-0 shadow-sm"><div class="card-body"><div class="text-muted small">My Patients</div><div class="display-6 fw-semibold"><?= (int) $counts['myPatients']; ?></div></div></div></div>
    <div class="col-md-3"><div class="card border-0 shadow-sm"><div class="card-body"><div class="text-muted small">Waiting Diagnose Payment</div><div class="display-6 fw-semibold text-warning"><?= (int) $counts['waitingDiagnose']; ?></div></div></div></div>
    <div class="col-md-3"><div class="card border-0 shadow-sm"><div class="card-body"><div class="text-muted small">Waiting Lab Payment</div><div class="display-6 fw-semibold text-warning"><?= (int) $counts['waitingLab']; ?></div></div></div></div>
</div>

<div class="card border-0 shadow-sm">
    <div class="card-header bg-white"><h5 class="mb-0">Analytics</h5></div>
    <div class="card-body">
        <?php foreach ($analytics as $label => $value): ?>
            <div class="mb-3">
                <div class="d-flex justify-content-between small mb-1"><span><?= html_escape($label); ?></span><span><?= (int) $value; ?>%</span></div>
                <div class="mini-chart-bar"><span style="width: <?= (int) $value; ?>%"></span></div>
            </div>
        <?php endforeach; ?>
    </div>
</div>
