<div class="card border-0 shadow-sm">
    <div class="card-header bg-white d-flex justify-content-between align-items-center">
        <h5 class="mb-0">Manage Fees <?= $type ? ' - ' . ucfirst($type) : ''; ?></h5>
        <button class="btn btn-primary" data-bs-toggle="modal" data-bs-target="#addFeeModal">+ Add Fee</button>
    </div>

    <div class="table-responsive">
        <table class="table align-middle mb-0">
            <thead class="table-light"><tr><th>#</th><th>Name</th><th>Type</th><th>Amount</th><th>Status</th><th class="text-end">Action</th></tr></thead>
            <tbody>
                <?php if (empty($fees)): ?>
                    <tr><td colspan="6" class="text-center text-muted py-4">No fee items found.</td></tr>
                <?php endif; ?>
                <?php foreach ($fees as $fee): ?>
                    <tr>
                        <td><?= (int) $fee->id; ?></td>
                        <td><?= html_escape($fee->name); ?></td>
                        <td><span class="badge bg-light text-dark border"><?= ucfirst(html_escape($fee->fee_type)); ?></span></td>
                        <td><?= html_escape($fee->currency); ?> <?= number_format((float) $fee->amount, 2); ?></td>
                        <td><span class="badge <?= $fee->status === 'active' ? 'bg-success' : 'bg-secondary'; ?>"><?= ucfirst(html_escape($fee->status)); ?></span></td>
                        <td class="text-end d-flex gap-2 justify-content-end">
                            <button class="btn btn-sm btn-outline-primary" data-bs-toggle="modal" data-bs-target="#editFeeModal<?= (int) $fee->id; ?>">Edit</button>
                            <a class="btn btn-sm btn-outline-danger" href="<?= site_url('payments/delete/' . $fee->id); ?>" onclick="return confirm('Delete this fee item?');">Delete</a>
                        </td>
                    </tr>
                <?php endforeach; ?>
            </tbody>
        </table>
    </div>
</div>

<div class="modal fade" id="addFeeModal" tabindex="-1" aria-hidden="true"><div class="modal-dialog"><div class="modal-content"><div class="modal-header"><h5 class="modal-title">Add Fee Item</h5><button type="button" class="btn-close" data-bs-dismiss="modal"></button></div>
<?= form_open('payments/create'); ?><div class="modal-body"><div class="row g-3"><div class="col-12"><label class="form-label">Name</label><input class="form-control" name="name" required></div>
<div class="col-md-6"><label class="form-label">Type</label><select class="form-select" name="fee_type" required><?php foreach ($feeTypes as $feeType): ?><option value="<?= html_escape($feeType); ?>"><?= ucfirst($feeType); ?></option><?php endforeach; ?></select></div>
<div class="col-md-6"><label class="form-label">Status</label><select class="form-select" name="status" required><?php foreach ($statuses as $status): ?><option value="<?= html_escape($status); ?>"><?= ucfirst($status); ?></option><?php endforeach; ?></select></div>
<div class="col-md-6"><label class="form-label">Amount</label><input type="number" step="0.01" class="form-control" name="amount" required></div>
<div class="col-md-6"><label class="form-label">Currency</label><select class="form-select" name="currency" required><?php foreach ($currencies as $currency): ?><option value="<?= html_escape($currency); ?>"><?= html_escape($currency); ?></option><?php endforeach; ?></select></div>
</div></div><div class="modal-footer"><button class="btn btn-light" data-bs-dismiss="modal" type="button">Cancel</button><button class="btn btn-primary">Save</button></div><?= form_close(); ?>
</div></div></div>

<?php foreach ($fees as $fee): ?>
<div class="modal fade" id="editFeeModal<?= (int) $fee->id; ?>" tabindex="-1" aria-hidden="true"><div class="modal-dialog"><div class="modal-content"><div class="modal-header"><h5 class="modal-title">Edit Fee Item</h5><button type="button" class="btn-close" data-bs-dismiss="modal"></button></div>
<?= form_open('payments/update/' . $fee->id); ?><div class="modal-body"><div class="row g-3"><div class="col-12"><label class="form-label">Name</label><input class="form-control" name="name" value="<?= html_escape($fee->name); ?>" required></div>
<div class="col-md-6"><label class="form-label">Type</label><select class="form-select" name="fee_type" required><?php foreach ($feeTypes as $feeType): ?><option value="<?= html_escape($feeType); ?>" <?= $fee->fee_type === $feeType ? 'selected' : ''; ?>><?= ucfirst($feeType); ?></option><?php endforeach; ?></select></div>
<div class="col-md-6"><label class="form-label">Status</label><select class="form-select" name="status" required><?php foreach ($statuses as $status): ?><option value="<?= html_escape($status); ?>" <?= $fee->status === $status ? 'selected' : ''; ?>><?= ucfirst($status); ?></option><?php endforeach; ?></select></div>
<div class="col-md-6"><label class="form-label">Amount</label><input type="number" step="0.01" class="form-control" name="amount" value="<?= html_escape($fee->amount); ?>" required></div>
<div class="col-md-6"><label class="form-label">Currency</label><select class="form-select" name="currency" required><?php foreach ($currencies as $currency): ?><option value="<?= html_escape($currency); ?>" <?= $fee->currency === $currency ? 'selected' : ''; ?>><?= html_escape($currency); ?></option><?php endforeach; ?></select></div>
</div></div><div class="modal-footer"><button class="btn btn-light" data-bs-dismiss="modal" type="button">Cancel</button><button class="btn btn-primary">Save Changes</button></div><?= form_close(); ?>
</div></div></div>
<?php endforeach; ?>
