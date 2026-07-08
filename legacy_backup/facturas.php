<?php

declare(strict_types=1);

require __DIR__ . '/includes/init.php';

$pageTitle = 'Facturas';

$lista = $pdo->query('
    SELECT f.id_factura, f.fecha, f.total, c.nombre AS cliente, e.nombre AS empleado
    FROM facturas f
    LEFT JOIN clientes c ON c.id_cliente = f.id_cliente
    LEFT JOIN empleados e ON e.id_empleado = f.id_empleado
    ORDER BY f.fecha DESC, f.id_factura DESC
')->fetchAll();

require __DIR__ . '/includes/header.php';
?>

<div class="card">
    <div class="card-head">
        <h2 class="card-title">Ventas registradas</h2>
        <a class="btn btn--primary" href="factura_nueva.php">Nueva factura</a>
    </div>
    <?php if ($lista === []) : ?>
        <p class="link-muted">No hay facturas. Registrá la primera venta.</p>
    <?php else : ?>
        <div class="table-wrap">
            <table class="data">
                <thead>
                    <tr>
                        <th>#</th>
                        <th>Fecha</th>
                        <th>Cliente</th>
                        <th>Atendió</th>
                        <th class="num">Total</th>
                        <th></th>
                    </tr>
                </thead>
                <tbody>
                    <?php foreach ($lista as $f) : ?>
                        <tr>
                            <td><?= (int) $f['id_factura'] ?></td>
                            <td><?= e($f['fecha']) ?></td>
                            <td><?= e($f['cliente'] ?? '—') ?></td>
                            <td><?= e($f['empleado'] ?? '—') ?></td>
                            <td class="num">$ <?= number_format((float) $f['total'], 2, '.', ',') ?></td>
                            <td><a class="btn btn--ghost" style="padding:.35rem .7rem;font-size:.82rem" href="factura_ver.php?id=<?= (int) $f['id_factura'] ?>">Ver</a></td>
                        </tr>
                    <?php endforeach; ?>
                </tbody>
            </table>
        </div>
    <?php endif; ?>
</div>

<?php require __DIR__ . '/includes/footer.php'; ?>
