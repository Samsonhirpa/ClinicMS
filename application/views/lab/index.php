<div class="row g-4">
    <div class="col-lg-6">
        <div class="card border-0 shadow-sm">
            <div class="card-header bg-white d-flex justify-content-between align-items-center">
                <h5 class="mb-0">New Paid Requests</h5>
                <span class="badge bg-primary"><?= count($newRequests); ?></span>
            </div>
            <div class="table-responsive">
                <table class="table mb-0 align-middle">
                    <thead class="table-light"><tr><th>Patient</th><th>Test</th><th>Action</th></tr></thead>
                    <tbody>
                    <?php if (empty($newRequests)): ?>
                        <tr><td colspan="3" class="text-center text-muted py-4">No paid requests yet.</td></tr>
                    <?php endif; ?>
                    <?php foreach ($newRequests as $request): ?>
                        <tr>
                            <td><?= html_escape($request->name); ?> (<?= html_escape($request->patient_code); ?>)</td>
                            <td><?= html_escape($request->test_name); ?></td>
                            <td>
                                <?= form_open('lab/complete/' . $request->id, ['class' => 'd-flex gap-2']); ?>
                                    <input name="result_text" class="form-control form-control-sm" placeholder="Enter result" required>
                                    <button class="btn btn-sm btn-success">Save</button>
                                <?= form_close(); ?>
                            </td>
                        </tr>
                    <?php endforeach; ?>
                    </tbody>
                </table>
            </div>
        </div>
    </div>

    <div class="col-lg-6">
        <div class="card border-0 shadow-sm">
            <div class="card-header bg-white"><h5 class="mb-0">All Requests</h5></div>
            <div class="table-responsive">
                <table class="table mb-0 align-middle">
                    <thead class="table-light"><tr><th>Patient</th><th>Test</th><th>Status</th></tr></thead>
                    <tbody>
                    <?php if (empty($allRequests)): ?>
                        <tr><td colspan="3" class="text-center text-muted py-4">No requests found.</td></tr>
                    <?php endif; ?>
                    <?php foreach ($allRequests as $request): ?>
                        <tr>
                            <td><?= html_escape($request->name); ?></td>
                            <td><?= html_escape($request->test_name); ?></td>
                            <td><span class="badge bg-light text-dark border"><?= ucfirst(html_escape($request->status)); ?></span></td>
                        </tr>
                    <?php endforeach; ?>
                    </tbody>
                </table>
            </div>
        </div>
    </div>
</div>
