<div class="card border-0 shadow-sm">
    <div class="card-header bg-white">
        <h5 class="mb-0">OPD Queue (Patients with Approved Registration Payment)</h5>
    </div>
    <div class="table-responsive">
        <table class="table mb-0 align-middle">
            <thead class="table-light"><tr><th>Patient Code</th><th>Name</th><th>Age</th><th>Gender</th><th>Phone</th><th>Payment Approved At</th></tr></thead>
            <tbody>
            <?php if (empty($patients)): ?>
                <tr><td colspan="6" class="text-center text-muted py-4">No patients are ready for OPD yet.</td></tr>
            <?php endif; ?>
            <?php foreach ($patients as $patient): ?>
                <tr>
                    <td><?= html_escape($patient->patient_code); ?></td>
                    <td><?= html_escape($patient->name); ?></td>
                    <td><?= $patient->age !== null ? (int) $patient->age : '-'; ?></td>
                    <td><?= ucfirst(html_escape((string) $patient->gender)); ?></td>
                    <td><?= html_escape((string) $patient->phone); ?></td>
                    <td><?= html_escape((string) $patient->approved_at); ?></td>
                </tr>
            <?php endforeach; ?>
            </tbody>
        </table>
    </div>
</div>
