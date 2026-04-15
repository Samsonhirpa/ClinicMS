<div class="card border-0 shadow-sm">
    <div class="card-header bg-white"><h5 class="mb-0">Lab Status for My Patients</h5></div>
    <div class="table-responsive">
        <table class="table mb-0 align-middle">
            <thead class="table-light"><tr><th>Patient</th><th>Code</th><th>Recommended Test</th><th>Status</th><th>Result</th></tr></thead>
            <tbody>
            <?php if (empty($cases)): ?>
                <tr><td colspan="5" class="text-center text-muted py-4">No patient records found.</td></tr>
            <?php endif; ?>
            <?php foreach ($cases as $case): ?>
                <tr>
                    <td><?= html_escape($case->name); ?></td>
                    <td><?= html_escape($case->patient_code); ?></td>
                    <td><?= $case->recommended_tests ? html_escape($case->recommended_tests) : '<span class="text-muted">-</span>'; ?></td>
                    <td><span class="badge bg-light text-dark border"><?= ucfirst(str_replace('_', ' ', html_escape($case->status))); ?></span></td>
                    <td><?= $case->status === 'completed' ? '<span class="text-success">Completed in Lab</span>' : '<span class="text-muted">Pending</span>'; ?></td>
                </tr>
            <?php endforeach; ?>
            </tbody>
        </table>
    </div>
</div>
