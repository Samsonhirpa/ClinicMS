<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title><?= isset($title) ? html_escape($title) : 'ClinicMS'; ?></title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
</head>
<body class="<?= isset($bodyClass) ? html_escape($bodyClass) : 'bg-light'; ?>">
<?php if (!isset($hideNavbar) || !$hideNavbar): ?>
<nav class="navbar navbar-expand-lg navbar-dark bg-primary shadow-sm">
    <div class="container-fluid px-4">
        <a class="navbar-brand fw-semibold" href="<?= site_url('users'); ?>">ClinicMS</a>
        <?php if ($this->session->userdata('user_id')): ?>
            <div class="ms-auto text-white d-flex align-items-center gap-2">
                <span><?= html_escape($this->session->userdata('name')); ?> (<?= ucfirst(html_escape($this->session->userdata('role'))); ?>)</span>
                <a class="btn btn-sm btn-outline-light" href="<?= site_url('logout'); ?>">Logout</a>
            </div>
        <?php endif; ?>
    </div>
</nav>
<?php endif; ?>
<div class="<?= isset($containerClass) ? html_escape($containerClass) : 'container py-4'; ?>">
    <?php if ($this->session->flashdata('success')): ?>
        <div class="alert alert-success"><?= $this->session->flashdata('success'); ?></div>
    <?php endif; ?>
    <?php if ($this->session->flashdata('error')): ?>
        <div class="alert alert-danger"><?= $this->session->flashdata('error'); ?></div>
    <?php endif; ?>
