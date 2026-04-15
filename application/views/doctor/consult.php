<div class="card border-0 shadow-sm">
    <div class="card-header bg-white"><h5 class="mb-0">Consultation - <?= html_escape($case->name); ?> (<?= html_escape($case->patient_code); ?>)</h5></div>
    <div class="card-body">
        <div class="mb-3 p-3 rounded bg-light border small">
            <div><strong>Patient:</strong> <?= html_escape($case->name); ?> | <?= ucfirst(html_escape((string) $case->gender)); ?> | Age <?= (int) $case->age; ?></div>
            <div><strong>Phone:</strong> <?= html_escape((string) $case->phone); ?></div>
            <div><strong>Diagnose Fee:</strong> <?= html_escape($diagnoseFee['currency']); ?> <?= number_format((float) $diagnoseFee['amount'], 2); ?> (will be sent to reception payment queue)</div>
        </div>

        <?= form_open('doctor/consult-submit/' . $case->id); ?>
            <div class="mb-3">
                <label class="form-label">Consultation Note</label>
                <textarea name="consultation_note" class="form-control" rows="4" required><?= html_escape((string) $case->consultation_note); ?></textarea>
            </div>
            <div class="mb-3">
                <label class="form-label">Recommended Test (optional)</label>
                <input name="recommended_tests" class="form-control" placeholder="e.g. CBC, Urinalysis" value="<?= html_escape((string) $case->recommended_tests); ?>">
                <div class="form-text">If filled, lab payment will be created after diagnose payment approval.</div>
            </div>
            <button class="btn btn-primary">Save Consultation & Request Payment</button>
        <?= form_close(); ?>

        <?php if ($existingLab): ?>
            <div class="alert alert-info mt-3 mb-0">
                <strong>Existing Lab Request:</strong> <?= html_escape($existingLab->test_name); ?> - Status: <?= ucfirst(html_escape($existingLab->status)); ?>
            </div>
        <?php endif; ?>
    </div>
</div>
