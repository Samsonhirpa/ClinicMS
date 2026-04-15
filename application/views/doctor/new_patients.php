<div class="card border-0 shadow-sm">
    <div class="card-header bg-white d-flex justify-content-between align-items-center">
        <h5 class="mb-0">New Patients Queue</h5>
        <span class="badge bg-primary">Total: <?= count($cases); ?></span>
    </div>
    <div class="table-responsive">
        <table class="table mb-0 align-middle">
            <thead class="table-light"><tr><th>Order</th><th>Patient</th><th>Code</th><th>Age</th><th>Gender</th><th>Action</th></tr></thead>
            <tbody>
            <?php if (empty($cases)): ?>
                <tr><td colspan="6" class="text-center text-muted py-4">No new patients currently.</td></tr>
            <?php endif; ?>
            <?php foreach ($cases as $index => $case): ?>
                <tr>
                    <td>#<?= $index + 1; ?></td>
                    <td><?= html_escape($case->name); ?></td>
                    <td><?= html_escape($case->patient_code); ?></td>
                    <td><?= (int) $case->age; ?></td>
                    <td><?= ucfirst(html_escape((string) $case->gender)); ?></td>
                    <td><a href="<?= site_url('doctor/claim/' . $case->id); ?>" class="btn btn-sm btn-primary">Call & Diagnose</a></td>
                </tr>
            <?php endforeach; ?>
            </tbody>
        </table>
    </div>
</div>
