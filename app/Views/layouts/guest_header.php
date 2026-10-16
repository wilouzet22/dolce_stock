<?php
$pageTitle = $pageTitle ?? 'Acceso';
?>
<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title><?= e($pageTitle) ?> — Dolce Stock</title>
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
    <link rel="stylesheet" href="assets/css/admin.css?v=36">
</head>
<body class="admin admin--guest">
<div class="auth-shell">

    <!-- Canvas de fondo animado: granos de café y tazas -->
    <div class="auth-bg-canvas" id="auth-bg-canvas" aria-hidden="true"></div>

    <!-- Botón visible para alternar Modo Claro / Modo Oscuro -->
    <aside class="auth-header-toggle" aria-label="Selector de tema">
        <button type="button" class="theme-toggle-btn" id="themeToggleBtn" aria-label="Cambiar tema">
            <span class="theme-icon" id="themeIcon">
                <svg xmlns="http://www.w3.org/2000/svg" width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M21 12.79A9 9 0 1 1 11.21 3 7 7 0 0 0 21 12.79z"></path></svg>
            </span>
            <span class="theme-label" id="themeLabel">Modo Oscuro</span>
        </button>
    </aside>

    <!-- Contenedor Principal: Taza de Café Blanca (Blanco Opaco) con el Login -->
    <main class="coffee-cup-auth-wrapper">
        <!-- Vapor de café caliente animado saliendo de la taza -->
        <div class="cup-steam-container" aria-hidden="true">
            <svg class="cup-steam-svg" viewBox="0 0 180 65" fill="none" xmlns="http://www.w3.org/2000/svg">
                <path class="steam-line steam-1" d="M40 60 Q 30 35 48 22 T 42 5" stroke="currentColor" stroke-width="3" stroke-linecap="round"/>
                <path class="steam-line steam-2" d="M90 60 Q 105 38 88 22 T 95 5" stroke="currentColor" stroke-width="3.5" stroke-linecap="round"/>
                <path class="steam-line steam-3" d="M140 60 Q 125 35 138 20 T 132 5" stroke="currentColor" stroke-width="3" stroke-linecap="round"/>
            </svg>
        </div>

        <!-- Taza de Café Blanca (Mug) -->
        <div class="coffee-cup-card">
            <!-- Asa lateral de la taza -->
            <div class="cup-handle" aria-hidden="true"></div>

            <!-- Borde superior / Superficie de café aromático -->
            <div class="cup-top-rim" aria-hidden="true">
                <div class="cup-crema-line"></div>
            </div>

            <!-- Contenido del formulario de login dentro de la taza -->
            <div class="cup-body-content">
                <div class="auth-brand">
                    <div class="auth-brand-logo-wrapper">
                        <img src="assets/img/logo.png" alt="Dolce Stock Logo" class="auth-brand-logo-img">
                    </div>
                    <span class="auth-brand-text">Dolce Stock</span>
                </div>
                <h1 class="auth-title"><?= e($pageTitle) ?></h1>
                <?php foreach (['ok', 'err'] as $k) : $m = flash($k); ?>
                    <?php if ($m) : ?>
                        <div class="flash flash--<?= e($k) ?>"><?= e($m) ?></div>
                    <?php endif; ?>
                <?php endforeach; ?>
                <?php if (isset($authReady) && !$authReady) : ?>
                    <div class="flash flash--err">Ejecuta en phpMyAdmin el archivo <code>sql/empleados_auth.sql</code> sobre la base <code>dolce_stock</code> para habilitar el registro e inicio de sesion.</div>
                <?php endif; ?>
