<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title><?= isset($title) ? html_escape($title) : 'ClinicMS'; ?></title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
    <style>
        body { min-height: 100vh; background: #f8f9fa; }
        .app-shell { min-height: 100vh; }
        .app-sidebar { width: 280px; min-height: 100vh; max-height: 100vh; overflow-y: auto; }
        .app-main { min-width: 0; flex: 1; max-height: 100vh; overflow-y: auto; }
        .mini-chart-bar { height: 10px; border-radius: 999px; background: #e9ecef; overflow: hidden; }
        .mini-chart-bar span { display: block; height: 100%; background: #0d6efd; }
    </style>
</head>
<body class="<?= isset($bodyClass) ? html_escape($bodyClass) : ''; ?>">
<?php if (isset($showSidebar) && $showSidebar === false): ?>
    <div class="<?= isset($containerClass) ? html_escape($containerClass) : 'container py-4'; ?>">
        <?php if ($this->session->flashdata('success')): ?>
            <div class="alert alert-success"><?= $this->session->flashdata('success'); ?></div>
        <?php endif; ?>
        <?php if ($this->session->flashdata('error')): ?>
            <div class="alert alert-danger"><?= $this->session->flashdata('error'); ?></div>
        <?php endif; ?>
<?php else: ?>
<div class="d-flex app-shell">
<?php endif; ?>
