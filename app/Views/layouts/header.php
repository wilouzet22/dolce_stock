<?php
$pageTitle = $pageTitle ?? 'Panel';
$currentController = strtolower((string) ($_GET['controller'] ?? 'dashboard'));
$userRole = $_SESSION['empleado_rol'] ?? 'cajero';
$isAdmin = $userRole === 'admin';
$userName = (string) ($_SESSION['empleado_nombre'] ?? 'Usuario');
$userInitial = mb_strtoupper(mb_substr($userName, 0, 1));
?>
<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title><?= e($pageTitle) ?> — Dolce Café</title>
    <script>
        (function() {
            var savedTheme = localStorage.getItem('dolce_theme');
            if (savedTheme === 'dark' || (!savedTheme && window.matchMedia('(prefers-color-scheme: dark)').matches)) {
                document.documentElement.setAttribute('data-theme', 'dark');
            } else {
                document.documentElement.setAttribute('data-theme', 'light');
            }
        })();
    </script>
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Fraunces:ital,opsz,wght@0,9..144,400..900;1,9..144,400..900&family=Plus+Jakarta+Sans:ital,wght@0,300..800;1,300..800&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="assets/css/admin.css?v=4">
    <script src="https://cdn.jsdelivr.net/npm/chart.js@4.4.7/dist/chart.umd.min.js"></script>
</head>
<body class="admin">
<div class="app-shell">
    <aside class="sidebar">
        <a href="<?= e(url('dashboard')) ?>" class="brand">
            <div class="brand-header-flex">
                <div class="brand-icon-wrapper">
                    <svg xmlns="http://www.w3.org/2000/svg" width="22" height="22" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M18 8h1a4 4 0 0 1 0 8h-1"></path><path d="M2 8h16v9a4 4 0 0 1-4 4H6a4 4 0 0 1-4-4V8z"></path><line x1="6" y1="1" x2="6" y2="4"></line><line x1="10" y1="1" x2="10" y2="4"></line><line x1="14" y1="1" x2="14" y2="4"></line></svg>
                </div>
                <div>
                    <span class="brand-text">Dolce Café</span>
                </div>
            </div>
            <span class="brand-sub">SISTEMA GESTIÓN</span>
        </a>
        <nav class="nav">
            <span class="nav-label">Menú Principal</span>
            <?php if ($isAdmin): ?>
            <a class="nav-item<?= $currentController === 'dashboard' ? ' is-active' : '' ?>" href="<?= e(url('dashboard')) ?>">
                <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><rect x="3" y="3" width="7" height="9"></rect><rect x="14" y="3" width="7" height="5"></rect><rect x="14" y="12" width="7" height="9"></rect><rect x="3" y="16" width="7" height="5"></rect></svg>
                Resumen
            </a>
            <a class="nav-item<?= $currentController === 'category' ? ' is-active' : '' ?>" href="<?= e(url('category')) ?>">
                <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M20.59 13.41l-7.17 7.17a2 2 0 0 1-2.83 0L2 12V2h10l8.59 8.59a2 2 0 0 1 0 2.82z"></path><line x1="7" y1="7" x2="7.01" y2="7"></line></svg>
                Categorías
            </a>
            <a class="nav-item<?= $currentController === 'product' ? ' is-active' : '' ?>" href="<?= e(url('product')) ?>">
                <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M21 16V8a2 2 0 0 0-1-1.73l-7-4a2 2 0 0 0-2 0l-7 4A2 2 0 0 0 3 8v8a2 2 0 0 0 1 1.73l7 4a2 2 0 0 0 2 0l7-4A2 2 0 0 0 21 16z"></path><polyline points="3.27 6.96 12 12.01 20.73 6.96"></polyline><line x1="12" y1="22.08" x2="12" y2="12"></line></svg>
                Productos
            </a>
            <a class="nav-item<?= $currentController === 'inventory' ? ' is-active' : '' ?>" href="<?= e(url('inventory')) ?>">
                <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><polyline points="23 4 23 10 17 10"></polyline><polyline points="1 20 1 14 7 14"></polyline><path d="M3.51 9a9 9 0 0 1 14.85-3.36L23 10M1 14l4.64 4.36A9 9 0 0 0 20.49 15"></path></svg>
                Inventario
            </a>
            <?php endif; ?>
            <a class="nav-item<?= $currentController === 'client' ? ' is-active' : '' ?>" href="<?= e(url('client')) ?>">
                <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M17 21v-2a4 4 0 0 0-4-4H5a4 4 0 0 0-4 4v2"></path><circle cx="9" cy="7" r="4"></circle><path d="M23 21v-2a4 4 0 0 0-3-3.87"></path><path d="M16 3.13a4 4 0 0 1 0 7.75"></path></svg>
                Clientes
            </a>
            <?php if ($isAdmin): ?>
            <a class="nav-item<?= $currentController === 'employee' ? ' is-active' : '' ?>" href="<?= e(url('employee')) ?>">
                <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><rect x="2" y="7" width="20" height="14" rx="2" ry="2"></rect><path d="M16 21V5a2 2 0 0 0-2-2h-4a2 2 0 0 0-2 2v16"></path></svg>
                Empleados
            </a>
            <?php endif; ?>
            <a class="nav-item<?= $currentController === 'invoice' ? ' is-active' : '' ?>" href="<?= e(url('invoice')) ?>">
                <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M14 2H6a2 2 0 0 0-2 2v16a2 2 0 0 0 2 2h12a2 2 0 0 0 2-2V8z"></path><polyline points="14 2 14 8 20 8"></polyline><line x1="16" y1="13" x2="8" y2="13"></line><line x1="16" y1="17" x2="8" y2="17"></line><polyline points="10 9 9 9 8 9"></polyline></svg>
                Facturas
            </a>
            <span class="nav-label nav-push">Cuenta</span>
            <a class="nav-item" href="<?= e(url('auth', 'logout')) ?>">
                <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M9 21H5a2 2 0 0 1-2-2V5a2 2 0 0 1 2-2h4"></path><polyline points="16 17 21 12 16 7"></polyline><line x1="21" y1="12" x2="9" y2="12"></line></svg>
                Cerrar Sesión
            </a>
        </nav>
    </aside>
    <div class="main-wrap">
        <header class="topbar">
            <h1 class="page-title"><?= e($pageTitle) ?></h1>
            <div class="topbar-actions">
                <!-- Theme Toggle Button -->
                <button type="button" class="theme-toggle-btn" id="themeToggleBtn" aria-label="Cambiar tema">
                    <span id="themeIcon">
                        <!-- Default Sun icon for dark mode toggle -->
                        <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><circle cx="12" cy="12" r="5"></circle><line x1="12" y1="1" x2="12" y2="3"></line><line x1="12" y1="21" x2="12" y2="23"></line><line x1="4.22" y1="4.22" x2="5.64" y2="5.64"></line><line x1="18.36" y1="18.36" x2="19.78" y2="19.78"></line><line x1="1" y1="12" x2="3" y2="12"></line><line x1="21" y1="12" x2="23" y2="12"></line><line x1="4.22" y1="19.78" x2="5.64" y2="18.36"></line><line x1="18.36" y1="5.64" x2="19.78" y2="4.22"></line></svg>
                    </span>
                    <span id="themeLabel">Modo Oscuro</span>
                </button>

                <?php if (!empty($_SESSION['empleado_nombre'])) : ?>
                    <div class="topbar-user">
                        <div class="user-chip">
                            <div class="user-avatar"><?= e($userInitial) ?></div>
                            <span><?= e($userName) ?></span>
                            <span class="user-role-badge"><?= e(ucfirst($userRole)) ?></span>
                        </div>
                    </div>
                <?php endif; ?>
            </div>
        </header>
        <main class="content">
            <?php foreach (['ok', 'err'] as $k) : $m = flash($k); ?>
                <?php if ($m) : ?>
                    <div class="flash flash--<?= e($k) ?>"><?= e($m) ?></div>
                <?php endif; ?>
            <?php endforeach; ?>
