<div class="row g-3 mb-4">
    <div class="col-md-4"><div class="card border-0 shadow-sm"><div class="card-body"><div class="text-muted small">Total Users</div><div class="display-6 fw-semibold"><?= (int) $counts['total_users']; ?></div></div></div></div>
    <div class="col-md-4"><div class="card border-0 shadow-sm"><div class="card-body"><div class="text-muted small">Active Users</div><div class="display-6 fw-semibold text-success"><?= (int) $counts['active_users']; ?></div></div></div></div>
    <div class="col-md-4"><div class="card border-0 shadow-sm"><div class="card-body"><div class="text-muted small">Inactive Users</div><div class="display-6 fw-semibold text-secondary"><?= (int) $counts['inactive_users']; ?></div></div></div></div>
</div>

<div class="row g-4">
    <div class="col-lg-6">
        <div class="card border-0 shadow-sm h-100">
            <div class="card-header bg-white"><h5 class="mb-0">Analytics</h5></div>
            <div class="card-body">
                <?php foreach ($monthlyAnalytics as $label => $value): ?>
                    <div class="mb-3">
                        <div class="d-flex justify-content-between small mb-1"><span><?= html_escape($label); ?></span><span><?= (int) $value; ?>%</span></div>
                        <div class="mini-chart-bar"><span style="width: <?= (int) $value; ?>%"></span></div>
                    </div>
                <?php endforeach; ?>
            </div>
        </div>
    </div>

    <div class="col-lg-6">
        <div class="card border-0 shadow-sm h-100">
            <div class="card-header bg-white"><h5 class="mb-0">User Role Distribution</h5></div>
            <div class="card-body">
                <?php if (empty($roleMap)): ?>
                    <p class="text-muted mb-0">No user data available.</p>
                <?php else: ?>
                    <?php $total = max(array_sum($roleMap), 1); ?>
                    <?php foreach ($roleMap as $role => $count): ?>
                        <?php $percent = (int) round(($count / $total) * 100); ?>
                        <div class="mb-3">
                            <div class="d-flex justify-content-between small mb-1"><span><?= ucfirst(html_escape($role)); ?></span><span><?= (int) $count; ?> users</span></div>
                            <div class="mini-chart-bar"><span style="width: <?= $percent; ?>%"></span></div>
                        </div>
                    <?php endforeach; ?>
                <?php endif; ?>
            </div>
        </div>
    </div>
</div>

<div class="card border-0 shadow-sm mt-4">
    <div class="card-header bg-white"><h5 class="mb-0">Reports</h5></div>
    <div class="card-body">
        <div class="row g-3">
            <?php foreach ($reports as $label => $value): ?>
                <div class="col-md-4">
                    <div class="p-3 rounded border bg-light h-100">
                        <div class="text-muted small"><?= html_escape($label); ?></div>
                        <div class="fw-semibold fs-5"><?= html_escape($value); ?></div>
                    </div>
                </div>
            <?php endforeach; ?>
        </div>
    </div>
</div>
