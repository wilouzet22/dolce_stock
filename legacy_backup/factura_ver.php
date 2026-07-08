<?php

declare(strict_types=1);

require __DIR__ . '/includes/init.php';

$id = (int) ($_GET['id'] ?? 0);
if ($id <= 0) {
    header('Location: facturas.php');
    exit;
}

$st = $pdo->prepare('
    SELECT f.*, c.nombre AS cliente, e.nombre AS empleado
    FROM facturas f
    LEFT JOIN clientes c ON c.id_cliente = f.id_cliente
    LEFT JOIN empleados e ON e.id_empleado = f.id_empleado
    WHERE f.id_factura = ?
');
$st->execute([$id]);
$factura = $st->fetch();
if (!$factura) {
    flash('err', 'Factura no encontrada.');
    header('Location: facturas.php');
    exit;
}

$st = $pdo->prepare('
    SELECT d.cantidad, d.precio_unitario, d.subtotal, p.nombre AS producto
    FROM detalle_factura d
    JOIN productos p ON p.id_producto = d.id_producto
    WHERE d.id_factura = ?
    ORDER BY d.id_detalle
');
$st->execute([$id]);
$detalles = $st->fetchAll();

$pageTitle = 'Factura #' . $id;

require __DIR__ . '/includes/header.php';
?>

<div class="card" style="max-width:680px;margin-bottom:1rem;">
    <div class="card-head">
        <h2 class="card-title"><?= e($pageTitle) ?></h2>
        <a class="btn btn--ghost" href="facturas.php">Volver</a>
    </div>
    <p style="margin:0 0 .5rem;"><strong>Fecha:</strong> <?= e($factura['fecha']) ?></p>
    <p style="margin:0 0 .5rem;"><strong>Cliente:</strong> <?= e($factura['cliente'] ?? '—') ?></p>
    <p style="margin:0;"><strong>Cajero / empleado:</strong> <?= e($factura['empleado'] ?? '—') ?></p>
</div>

<div class="card">
    <div class="card-head">
        <h2 class="card-title">Detalle</h2>
        <strong class="stat-value" style="font-size:1.25rem;">Total: $ <?= number_format((float) $factura['total'], 2, '.', ',') ?></strong>
    </div>
    <?php if ($detalles === []) : ?>
        <p class="link-muted">Sin líneas (dato inconsistente).</p>
    <?php else : ?>
        <div class="table-wrap">
            <table class="data">
                <thead>
                    <tr>
                        <th>Producto</th>
                        <th class="num">Cant.</th>
                        <th class="num">Precio unit.</th>
                        <th class="num">Subtotal</th>
                    </tr>
                </thead>
                <tbody>
                    <?php foreach ($detalles as $d) : ?>
                        <tr>
                            <td><?= e($d['producto']) ?></td>
                            <td class="num"><?= (int) $d['cantidad'] ?></td>
                            <td class="num">$ <?= number_format((float) $d['precio_unitario'], 2, '.', ',') ?></td>
                            <td class="num">$ <?= number_format((float) $d['subtotal'], 2, '.', ',') ?></td>
                        </tr>
                    <?php endforeach; ?>
                </tbody>
            </table>
        </div>
    <?php endif; ?>
</div>

<?php require __DIR__ . '/includes/footer.php'; ?>
