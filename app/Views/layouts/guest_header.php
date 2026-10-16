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
    <link rel="stylesheet" href="assets/css/admin.css?v=32">
</head>
<body class="admin admin--guest">
<div class="auth-shell">
    <div class="auth-header-toggle">
        <button type="button" class="theme-toggle-btn" id="themeToggleBtn" aria-label="Cambiar tema">
            <span id="themeIcon">
                <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><circle cx="12" cy="12" r="5"></circle><line x1="12" y1="1" x2="12" y2="3"></line><line x1="12" y1="21" x2="12" y2="23"></line><line x1="4.22" y1="4.22" x2="5.64" y2="5.64"></line><line x1="18.36" y1="18.36" x2="19.78" y2="19.78"></line><line x1="1" y1="12" x2="3" y2="12"></line><line x1="21" y1="12" x2="23" y2="12"></line><line x1="4.22" y1="19.78" x2="5.64" y2="18.36"></line><line x1="18.36" y1="5.64" x2="19.78" y2="4.22"></line></svg>
            </span>
            <span id="themeLabel">Modo Oscuro</span>
        </button>
    </div>

    <!-- Contenedor Centrado de la Mancha de Café y Login -->
    <div class="coffee-splash-wrapper">
        <div class="coffee-stain-bg">
            <svg class="stain-shape-svg" viewBox="0 0 620 620" preserveAspectRatio="none" fill="none" xmlns="http://www.w3.org/2000/svg">
                <defs>
                    <radialGradient id="stainGrad" cx="50%" cy="48%" r="52%">
                        <stop offset="0%" stop-color="var(--stain-color-accent)"/>
                        <stop offset="45%" stop-color="#2F1407"/>
                        <stop offset="85%" stop-color="var(--stain-color-main)"/>
                        <stop offset="100%" stop-color="#140601"/>
                    </radialGradient>
                    <radialGradient id="stainInnerRing" cx="50%" cy="50%" r="48%">
                        <stop offset="82%" stop-color="rgba(226, 141, 56, 0)"/>
                        <stop offset="94%" stop-color="rgba(226, 141, 56, 0.22)"/>
                        <stop offset="100%" stop-color="rgba(226, 141, 56, 0)"/>
                    </radialGradient>
                </defs>

                <!-- Mancha de café elegante, fluida y armónica (SIN círculos aislados) -->
                <path class="stain-path-outer" fill="url(#stainGrad)"
                    d="
                    M 310 40
                    C 375 36,  425 58,  475 88
                    C 525 118, 568 152, 588 205
                    C 608 258, 595 315, 602 370
                    C 609 425, 590 478, 555 520
                    C 520 562, 468 586, 412 602
                    C 356 618, 295 615, 242 596
                    C 189 577, 145 540, 112 494
                    C 79 448,  62 390,  66 332
                    C 70 274,  95 218,  134 170
                    C 173 122, 235 44,  310 40 Z
                    " />

                <!-- Anillo de secado sutil para realismo de café gourmet -->
                <path fill="url(#stainInnerRing)"
                    d="
                    M 310 40
                    C 375 36,  425 58,  475 88
                    C 525 118, 568 152, 588 205
                    C 608 258, 595 315, 602 370
                    C 609 425, 590 478, 555 520
                    C 520 562, 468 586, 412 602
                    C 356 618, 295 615, 242 596
                    C 189 577, 145 540, 112 494
                    C 79 448,  62 390,  66 332
                    C 70 274,  95 218,  134 170
                    C 173 122, 235 44,  310 40 Z
                    " />
            </svg>
        </div><!-- /.coffee-stain-bg -->

            <!-- Card de login alojada dentro de la mancha -->
            <div class="auth-card coffee-stain-content">
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
                    <div class="flash flash--err">Ejecutá en phpMyAdmin el archivo <code>sql/empleados_auth.sql</code> sobre la base <code>dolce_stock</code> para habilitar el registro e inicio de sesión.</div>
                <?php endif; ?>

