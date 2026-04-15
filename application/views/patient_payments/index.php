<div class="card border-0 shadow-sm">
    <div class="card-header bg-white d-flex justify-content-between align-items-center">
        <h5 class="mb-0"><?= ucfirst(html_escape($paymentType)); ?> Payment Approvals</h5>
        <span class="badge bg-warning text-dark">Pending: <?= count($payments); ?></span>
    </div>
    <div class="table-responsive">
        <table class="table mb-0 align-middle">
            <thead class="table-light"><tr><th>Patient</th><th>Code</th><th>Payment Type</th><th>Amount</th><th>Status</th><th class="text-end">Action</th></tr></thead>
            <tbody>
            <?php if (empty($payments)): ?>
                <tr><td colspan="6" class="text-center text-muted py-4">No pending <?= html_escape($paymentType); ?> payment requests.</td></tr>
            <?php endif; ?>
            <?php foreach ($payments as $payment): ?>
                <tr>
                    <td><?= html_escape($payment->patient_name); ?></td>
                    <td><?= html_escape($payment->patient_code); ?></td>
                    <td><?= ucfirst(html_escape($payment->payment_type)); ?></td>
                    <td><?= html_escape($payment->currency); ?> <?= number_format((float) $payment->amount, 2); ?></td>
                    <td><span class="badge bg-warning text-dark">Pending</span></td>
                    <td class="text-end d-flex justify-content-end gap-2">
                        <a href="<?= site_url('patient-payments/approve/' . $payment->id); ?>" class="btn btn-sm btn-success" onclick="return confirm('Approve this payment?');">Approve</a>
                        <a href="<?= site_url('patient-payments/reject/' . $payment->id); ?>" class="btn btn-sm btn-outline-danger" onclick="return confirm('Reject this payment?');">Reject</a>
                    </td>
                </tr>
            <?php endforeach; ?>
            </tbody>
        </table>
    </div>
</div>
