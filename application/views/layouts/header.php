<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title><?= isset($title) ? html_escape($title) : 'ClinicMS'; ?></title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
</head>
<body class="bg-light">
<nav class="navbar navbar-expand-lg navbar-dark bg-primary mb-4">
    <div class="container">
        <a class="navbar-brand" href="<?= site_url('users'); ?>">ClinicMS</a>
        <?php if ($this->session->userdata('user_id')): ?>
            <div class="ms-auto text-white">
                <?= html_escape($this->session->userdata('name')); ?> (<?= html_escape($this->session->userdata('role')); ?>)
                <a class="btn btn-sm btn-outline-light ms-2" href="<?= site_url('logout'); ?>">Logout</a>
            </div>
        <?php endif; ?>
    </div>
</nav>
<div class="container">
    <?php if ($this->session->flashdata('success')): ?>
        <div class="alert alert-success"><?= $this->session->flashdata('success'); ?></div>
    <?php endif; ?>
    <?php if ($this->session->flashdata('error')): ?>
        <div class="alert alert-danger"><?= $this->session->flashdata('error'); ?></div>
    <?php endif; ?>
