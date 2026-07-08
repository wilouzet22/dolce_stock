<?php
/** @var string $pageTitle */
$pageTitle = $pageTitle ?? 'Panel';
$base = rtrim(dirname($_SERVER['SCRIPT_NAME'] ?? '/'), '/\\');
if ($base === '' || $base === '.') {
    $base = '';
}
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
    <link rel="stylesheet" href="<?= e($base) ?>/assets/css/admin.css?v=1">
</head>
<body class="admin">
<div class="app-shell">
    <aside class="sidebar">
        <a href="<?= e($base) ?>/index.php" class="brand">
            <span class="brand-mark">☕</span>
            <span class="brand-text">Dolce Café</span>
            <span class="brand-sub">Administración</span>
        </a>
        <nav class="nav">
            <span class="nav-label">Menú principal</span>
            <a class="nav-item<?= ($_SERVER['PHP_SELF'] ?? '') !== '' && str_ends_with((string)$_SERVER['PHP_SELF'], 'index.php') ? ' is-active' : '' ?>" href="<?= e($base) ?>/index.php">Resumen</a>
            <a class="nav-item<?= str_ends_with((string)$_SERVER['PHP_SELF'], 'categorias.php') ? ' is-active' : '' ?>" href="<?= e($base) ?>/categorias.php">Categorías</a>
            <a class="nav-item<?= str_ends_with((string)$_SERVER['PHP_SELF'], 'productos.php') ? ' is-active' : '' ?>" href="<?= e($base) ?>/productos.php">Productos</a>
            <a class="nav-item<?= str_ends_with((string)$_SERVER['PHP_SELF'], 'movimientos.php') ? ' is-active' : '' ?>" href="<?= e($base) ?>/movimientos.php">Inventario</a>
            <a class="nav-item<?= str_ends_with((string)$_SERVER['PHP_SELF'], 'clientes.php') ? ' is-active' : '' ?>" href="<?= e($base) ?>/clientes.php">Clientes</a>
            <a class="nav-item<?= str_ends_with((string)$_SERVER['PHP_SELF'], 'empleados.php') ? ' is-active' : '' ?>" href="<?= e($base) ?>/empleados.php">Empleados</a>
            <a class="nav-item<?= str_contains((string)$_SERVER['PHP_SELF'], 'factura') ? ' is-active' : '' ?>" href="<?= e($base) ?>/facturas.php">Facturas</a>
        </nav>
    </aside>
    <div class="main-wrap">
        <header class="topbar">
            <h1 class="page-title"><?= e($pageTitle) ?></h1>
        </header>
        <main class="content">
            <?php
            foreach (['ok', 'err'] as $k) {
                $m = flash($k);
                if ($m) {
                    echo '<div class="flash flash--' . e($k) . '">' . e($m) . '</div>';
                }
            }
            ?>
