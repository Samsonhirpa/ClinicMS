<?php
$title = 'Login';
$hideNavbar = true;
$bodyClass = 'bg-primary bg-gradient min-vh-100 d-flex align-items-center';
$containerClass = 'container';
$this->load->view('layouts/header', compact('title', 'hideNavbar', 'bodyClass', 'containerClass'));
?>
<div class="row justify-content-center">
    <div class="col-lg-9 col-xl-8">
        <div class="card border-0 shadow-lg overflow-hidden">
            <div class="row g-0">
                <div class="col-md-5 bg-dark text-white p-4 p-lg-5 d-flex flex-column justify-content-center">
                    <h2 class="fw-bold">ClinicMS</h2>
                    <p class="mb-0 text-white-50">Secure admin access for your clinic operations, users, and registration services.</p>
                </div>
                <div class="col-md-7 p-4 p-lg-5 bg-white">
                    <h4 class="fw-semibold mb-1">Welcome back</h4>
                    <p class="text-muted mb-4">Please log in to continue.</p>
                    <?= form_open('login'); ?>
                        <div class="mb-3">
                            <label for="email" class="form-label">Email Address</label>
                            <input type="email" name="email" id="email" class="form-control form-control-lg" required value="<?= set_value('email'); ?>">
                        </div>
                        <div class="mb-4">
                            <label for="password" class="form-label">Password</label>
                            <input type="password" name="password" id="password" class="form-control form-control-lg" required>
                        </div>
                        <button type="submit" class="btn btn-primary btn-lg w-100">Sign In</button>
                    <?= form_close(); ?>
                </div>
            </div>
        </div>
    </div>
</div>
<?php $this->load->view('layouts/footer'); ?>
