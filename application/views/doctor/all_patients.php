<div class="card border-0 shadow-sm">
    <div class="card-header bg-white"><h5 class="mb-0">All My Patients</h5></div>
    <div class="table-responsive">
        <table class="table mb-0 align-middle">
            <thead class="table-light"><tr><th>Patient</th><th>Code</th><th>Case Status</th><th>Latest Payment</th><th>Action</th></tr></thead>
            <tbody>
            <?php if (empty($cases)): ?>
                <tr><td colspan="5" class="text-center text-muted py-4">No assigned patients yet.</td></tr>
            <?php endif; ?>
            <?php foreach ($cases as $case): ?>
                <?php $payment = isset($paymentMap[$case->patient_id]) ? $paymentMap[$case->patient_id] : null; ?>
                <tr>
                    <td><?= html_escape($case->name); ?></td>
                    <td><?= html_escape($case->patient_code); ?></td>
                    <td><span class="badge bg-light text-dark border"><?= ucfirst(str_replace('_', ' ', html_escape($case->status))); ?></span></td>
                    <td>
                        <?php if ($payment): ?>
                            <?= ucfirst(html_escape($payment->payment_type)); ?> - <span class="badge <?= $payment->status === 'approved' ? 'bg-success' : ($payment->status === 'pending' ? 'bg-warning text-dark' : 'bg-danger'); ?>"><?= ucfirst(html_escape($payment->status)); ?></span>
                        <?php else: ?>
                            <span class="text-muted">No payment data</span>
                        <?php endif; ?>
                    </td>
                    <td>
                        <a href="<?= site_url('doctor/consult/' . $case->id); ?>" class="btn btn-sm btn-outline-primary">Open Case</a>
                    </td>
                </tr>
            <?php endforeach; ?>
            </tbody>
        </table>
    </div>
</div>
