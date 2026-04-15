<?php $title = 'Login'; $this->load->view('layouts/header', compact('title')); ?>
<div class="row justify-content-center">
    <div class="col-md-5">
        <div class="card shadow-sm">
            <div class="card-body p-4">
                <h4 class="mb-3">ClinicMS Login</h4>
                <?= form_open('login'); ?>
                    <div class="mb-3">
                        <label for="email" class="form-label">Email</label>
                        <input type="email" name="email" id="email" class="form-control" required value="<?= set_value('email'); ?>">
                    </div>
                    <div class="mb-3">
                        <label for="password" class="form-label">Password</label>
                        <input type="password" name="password" id="password" class="form-control" required>
                    </div>
                    <button type="submit" class="btn btn-primary w-100">Login</button>
                <?= form_close(); ?>
            </div>
        </div>
    </div>
</div>
<?php $this->load->view('layouts/footer'); ?>
