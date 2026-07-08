<?php
$pageTitle = $pageTitle ?? 'Acceso';
?>
<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title><?= e($pageTitle) ?> — Dolce Café</title>
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=DM+Sans:ital,opsz,wght@0,9..40,400..700;1,9..40,400..700&family=Fraunces:ital,opsz,wght@0,9..144,600;1,9..144,600&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="assets/css/admin.css?v=3">
</head>
<body class="admin admin--guest">
<div class="auth-shell">
    <div class="auth-brand">
        <svg xmlns="http://www.w3.org/2000/svg" width="48" height="48" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" style="color: var(--accent); margin: 0 auto 0.5rem; display: block;"><path d="M18 8h1a4 4 0 0 1 0 8h-1"></path><path d="M2 8h16v9a4 4 0 0 1-4 4H6a4 4 0 0 1-4-4V8z"></path><line x1="6" y1="1" x2="6" y2="4"></line><line x1="10" y1="1" x2="10" y2="4"></line><line x1="14" y1="1" x2="14" y2="4"></line></svg>
        <span class="auth-brand-text">Dolce Café</span>
    </div>
    <div class="auth-card card">
        <h1 class="auth-title"><?= e($pageTitle) ?></h1>
        <?php foreach (['ok', 'err'] as $k) : $m = flash($k); ?>
            <?php if ($m) : ?>
                <div class="flash flash--<?= e($k) ?>"><?= e($m) ?></div>
            <?php endif; ?>
        <?php endforeach; ?>
        <?php if (isset($authReady) && !$authReady) : ?>
            <div class="flash flash--err">Ejecutá en phpMyAdmin el archivo <code>sql/empleados_auth.sql</code> sobre la base <code>dolce_stock</code> para habilitar el registro e inicio de sesión.</div>
        <?php endif; ?>
