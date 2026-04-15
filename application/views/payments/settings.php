<div class="card border-0 shadow-sm">
    <div class="card-header bg-white">
        <h5 class="mb-0">Registration Fee Settings</h5>
    </div>
    <div class="card-body">
        <p class="text-muted">Set dynamic registration fee and preferred currency (e.g., ETB).</p>
        <?= form_open('payments/settings'); ?>
            <div class="row g-3">
                <div class="col-md-6">
                    <label class="form-label">Default Currency</label>
                    <select name="currency" class="form-select" required>
                        <?php foreach ($currencies as $currency): ?>
                            <option value="<?= html_escape($currency); ?>" <?= $defaultCurrency === $currency ? 'selected' : ''; ?>><?= html_escape($currency); ?></option>
                        <?php endforeach; ?>
                    </select>
                </div>
                <div class="col-md-6">
                    <label class="form-label">Default Registration Fee</label>
                    <input type="number" step="0.01" min="0" class="form-control" name="registration_fee" value="<?= html_escape($defaultRegistrationFee); ?>" required>
                </div>
            </div>
            <div class="mt-3">
                <button class="btn btn-primary">Save Settings</button>
            </div>
        <?= form_close(); ?>
    </div>
</div>
