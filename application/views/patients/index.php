<div class="card border-0 shadow-sm mb-4">
    <div class="card-header bg-white d-flex justify-content-between align-items-center">
        <h5 class="mb-0">Manage Patients</h5>
        <button class="btn btn-primary" data-bs-toggle="modal" data-bs-target="#addPatientModal">+ Add Patient</button>
    </div>
    <div class="card-body border-bottom">
        <form method="get" action="<?= site_url('patients'); ?>" class="row g-2">
            <div class="col-md-8"><input class="form-control" name="search" placeholder="Search by name/code/phone" value="<?= html_escape($search); ?>"></div>
            <div class="col-md-4 d-grid"><button class="btn btn-outline-primary">Search</button></div>
        </form>
    </div>
    <div class="table-responsive">
        <table class="table align-middle mb-0">
            <thead class="table-light"><tr><th>Code</th><th>Name</th><th>Age</th><th>Gender</th><th>Phone</th><th>Action</th></tr></thead>
            <tbody>
            <?php if (empty($patients)): ?>
                <tr><td colspan="6" class="text-center text-muted py-4">No patients found.</td></tr>
            <?php endif; ?>
            <?php foreach ($patients as $patient): ?>
                <tr>
                    <td><?= html_escape($patient->patient_code); ?></td>
                    <td><?= html_escape($patient->name); ?></td>
                    <td><?= $patient->age !== null ? (int) $patient->age : '-'; ?></td>
                    <td><?= ucfirst(html_escape((string) $patient->gender)); ?></td>
                    <td><?= html_escape((string) $patient->phone); ?></td>
                    <td class="d-flex flex-wrap gap-2">
                        <button class="btn btn-sm btn-outline-primary" data-bs-toggle="modal" data-bs-target="#editPatientModal<?= (int) $patient->id; ?>">Edit</button>
                        <a href="<?= site_url('patients/add-diagnose-fee/' . $patient->id); ?>" class="btn btn-sm btn-outline-secondary" onclick="return confirm('Add pending diagnose fee for this patient?');">Add Diagnose Fee (<?= html_escape($diagnoseFee['currency']); ?> <?= number_format((float) $diagnoseFee['amount'], 2); ?>)</a>
                    </td>
                </tr>
            <?php endforeach; ?>
            </tbody>
        </table>
    </div>
</div>

<div class="modal fade" id="addPatientModal" tabindex="-1" aria-hidden="true"><div class="modal-dialog"><div class="modal-content"><div class="modal-header"><h5 class="modal-title">Add Patient</h5><button type="button" class="btn-close" data-bs-dismiss="modal"></button></div>
<?= form_open('patients/create'); ?><div class="modal-body"><div class="alert alert-info small">Registration fee will be created as pending: <?= html_escape($registrationFee['currency']); ?> <?= number_format((float) $registrationFee['amount'], 2); ?></div><div class="row g-3"><div class="col-12"><label class="form-label">Patient Name</label><input class="form-control" name="name" required></div>
<div class="col-md-4"><label class="form-label">Age</label><input class="form-control" name="age" type="number" min="0"></div>
<div class="col-md-8"><label class="form-label">Gender</label><select class="form-select" name="gender" required><option value="male">Male</option><option value="female">Female</option><option value="other">Other</option></select></div>
<div class="col-12"><label class="form-label">Phone</label><input class="form-control" name="phone"></div>
<div class="col-12"><label class="form-label">Address</label><textarea class="form-control" name="address" rows="2"></textarea></div>
</div></div><div class="modal-footer"><button class="btn btn-light" data-bs-dismiss="modal" type="button">Cancel</button><button class="btn btn-primary">Register Patient</button></div><?= form_close(); ?>
</div></div></div>

<?php foreach ($patients as $patient): ?>
<div class="modal fade" id="editPatientModal<?= (int) $patient->id; ?>" tabindex="-1" aria-hidden="true"><div class="modal-dialog"><div class="modal-content"><div class="modal-header"><h5 class="modal-title">Edit Patient</h5><button type="button" class="btn-close" data-bs-dismiss="modal"></button></div>
<?= form_open('patients/update/' . $patient->id); ?><div class="modal-body"><div class="row g-3"><div class="col-12"><label class="form-label">Patient Name</label><input class="form-control" name="name" value="<?= html_escape($patient->name); ?>" required></div>
<div class="col-md-4"><label class="form-label">Age</label><input class="form-control" name="age" type="number" min="0" value="<?= html_escape((string) $patient->age); ?>"></div>
<div class="col-md-8"><label class="form-label">Gender</label><select class="form-select" name="gender" required><option value="male" <?= $patient->gender === 'male' ? 'selected' : ''; ?>>Male</option><option value="female" <?= $patient->gender === 'female' ? 'selected' : ''; ?>>Female</option><option value="other" <?= $patient->gender === 'other' ? 'selected' : ''; ?>>Other</option></select></div>
<div class="col-12"><label class="form-label">Phone</label><input class="form-control" name="phone" value="<?= html_escape((string) $patient->phone); ?>"></div>
<div class="col-12"><label class="form-label">Address</label><textarea class="form-control" name="address" rows="2"><?= html_escape((string) $patient->address); ?></textarea></div>
</div></div><div class="modal-footer"><button class="btn btn-light" data-bs-dismiss="modal" type="button">Cancel</button><button class="btn btn-primary">Save Changes</button></div><?= form_close(); ?>
</div></div></div>
<?php endforeach; ?>
