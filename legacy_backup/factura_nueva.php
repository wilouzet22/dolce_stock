<?php

declare(strict_types=1);

require __DIR__ . '/includes/init.php';

$pageTitle = 'Nueva factura';

$clientes = $pdo->query('SELECT id_cliente, nombre FROM clientes ORDER BY nombre')->fetchAll();
$empleados = $pdo->query('SELECT id_empleado, nombre FROM empleados ORDER BY nombre')->fetchAll();
$productos = $pdo->query('SELECT id_producto, nombre, precio, stock FROM productos ORDER BY nombre')->fetchAll();

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $idc = ($_POST['id_cliente'] ?? '') !== '' ? (int) $_POST['id_cliente'] : null;
    $ide = ($_POST['id_empleado'] ?? '') !== '' ? (int) $_POST['id_empleado'] : null;

    $pids = $_POST['prod_id'] ?? [];
    $qtys = $_POST['prod_qty'] ?? [];
    $lines = [];
    foreach ($pids as $i => $pid) {
        $pid = (int) $pid;
        $q = isset($qtys[$i]) ? (int) $qtys[$i] : 0;
        if ($pid > 0 && $q > 0) {
            $lines[] = ['producto' => $pid, 'cantidad' => $q];
        }
    }

    if ($lines === []) {
        flash('err', 'Agregá al menos un producto con cantidad mayor a cero.');
        header('Location: factura_nueva.php');
        exit;
    }

    $needByProd = [];
    foreach ($lines as $ln) {
        $pid = $ln['producto'];
        $needByProd[$pid] = ($needByProd[$pid] ?? 0) + $ln['cantidad'];
    }

    try {
        $pdo->beginTransaction();

        $total = 0.0;
        $priced = [];
        $cache = [];

        foreach (array_keys($needByProd) as $pid) {
            $st = $pdo->prepare('SELECT nombre, precio, stock FROM productos WHERE id_producto = ? FOR UPDATE');
            $st->execute([$pid]);
            $pr = $st->fetch();
            if (!$pr) {
                throw new RuntimeException('Producto no válido.');
            }
            $stock = (int) $pr['stock'];
            $need = $needByProd[$pid];
            if ($stock < $need) {
                throw new RuntimeException(
                    'Stock insuficiente para «' . $pr['nombre'] . '» (necesitás ' . $need . ', disponible: ' . $stock . ').'
                );
            }
            $cache[$pid] = $pr;
        }

        foreach ($lines as $ln) {
            $pid = $ln['producto'];
            $pr = $cache[$pid];
            $pu = (float) $pr['precio'];
            $sub = round($pu * $ln['cantidad'], 2);
            $total += $sub;
            $priced[] = [
                'producto' => $pid,
                'cantidad' => $ln['cantidad'],
                'precio_unitario' => $pu,
                'subtotal' => $sub,
            ];
        }

        $st = $pdo->prepare('INSERT INTO facturas (id_cliente, id_empleado, total) VALUES (?,?,?)');
        $st->execute([$idc, $ide, round($total, 2)]);
        $idFactura = (int) $pdo->lastInsertId();

        $insDet = $pdo->prepare(
            'INSERT INTO detalle_factura (id_factura, id_producto, cantidad, precio_unitario, subtotal) VALUES (?,?,?,?,?)'
        );
        $updStock = $pdo->prepare('UPDATE productos SET stock = stock - ? WHERE id_producto = ?');

        foreach ($priced as $ln) {
            $insDet->execute([
                $idFactura,
                $ln['producto'],
                $ln['cantidad'],
                $ln['precio_unitario'],
                $ln['subtotal'],
            ]);
            $updStock->execute([$ln['cantidad'], $ln['producto']]);
        }

        $pdo->commit();
        flash('ok', 'Factura registrada.');
        header('Location: factura_ver.php?id=' . $idFactura);
        exit;
    } catch (Throwable $e) {
        $pdo->rollBack();
        flash('err', $e instanceof RuntimeException ? $e->getMessage() : 'Error al guardar la venta.');
        header('Location: factura_nueva.php');
        exit;
    }
}

require __DIR__ . '/includes/header.php';
?>

<?php if ($productos === []) : ?>
    <div class="flash flash--err">Necesitás productos con stock. <a href="productos.php" class="link-muted">Ir a productos</a>.</div>
<?php endif; ?>

<div class="card" style="max-width:760px;">
    <div class="card-head">
        <h2 class="card-title">Registrar venta</h2>
        <a class="btn btn--ghost" href="facturas.php">Cancelar</a>
    </div>
    <form method="post" id="form-factura">
        <div class="form-grid" style="margin-bottom:1.25rem;">
            <div class="form-group">
                <label for="id_cliente">Cliente</label>
                <select id="id_cliente" name="id_cliente">
                    <option value="">— Opcional —</option>
                    <?php foreach ($clientes as $c) : ?>
                        <option value="<?= (int) $c['id_cliente'] ?>"><?= e($c['nombre']) ?></option>
                    <?php endforeach; ?>
                </select>
            </div>
            <div class="form-group">
                <label for="id_empleado">Empleado</label>
                <select id="id_empleado" name="id_empleado">
                    <option value="">— Opcional —</option>
                    <?php foreach ($empleados as $em) : ?>
                        <option value="<?= (int) $em['id_empleado'] ?>"><?= e($em['nombre']) ?></option>
                    <?php endforeach; ?>
                </select>
            </div>
        </div>

        <div class="card-head">
            <h3 class="card-title" style="font-size:1rem;">Ítems</h3>
            <button type="button" class="btn btn--ghost" id="add-line" <?= $productos === [] ? 'disabled' : '' ?>>Añadir línea</button>
        </div>
        <div id="lines">
            <?php
            // Plantilla inicial: una línea vacía solo si hay productos (JS clona igual)
            if ($productos !== []) :
                ?>
            <div class="line-row form-grid" style="align-items:end;margin-bottom:.75rem;border-bottom:1px solid rgba(61,41,20,.08);padding-bottom:.75rem;">
                <div class="form-group" style="grid-column: span 2; min-width:200px;">
                    <label>Producto</label>
                    <select name="prod_id[]" class="sel-prod" required>
                        <option value="">— Seleccionar —</option>
                        <?php foreach ($productos as $p) : ?>
                            <option value="<?= (int) $p['id_producto'] ?>" data-stock="<?= (int) $p['stock'] ?>">
                                <?= e($p['nombre']) ?> · $ <?= number_format((float) $p['precio'], 2, '.', ',') ?> (stock <?= (int) $p['stock'] ?>)
                            </option>
                        <?php endforeach; ?>
                    </select>
                </div>
                <div class="form-group">
                    <label>Cantidad</label>
                    <input type="number" name="prod_qty[]" min="1" value="1" required>
                </div>
                <div class="form-group">
                    <label>&nbsp;</label>
                    <button type="button" class="btn btn--danger rm-line" style="width:100%;">Quitar</button>
                </div>
            </div>
            <?php endif; ?>
        </div>

        <div class="form-actions">
            <button type="submit" class="btn btn--primary" <?= $productos === [] ? 'disabled' : '' ?>>Confirmar venta</button>
        </div>
    </form>
</div>

<template id="tpl-line">
    <div class="line-row form-grid" style="align-items:end;margin-bottom:.75rem;border-bottom:1px solid rgba(61,41,20,.08);padding-bottom:.75rem;">
        <div class="form-group" style="grid-column: span 2; min-width:200px;">
            <label>Producto</label>
            <select name="prod_id[]" class="sel-prod" required>{{OPTIONS}}</select>
        </div>
        <div class="form-group">
            <label>Cantidad</label>
            <input type="number" name="prod_qty[]" min="1" value="1" required>
        </div>
        <div class="form-group">
            <label>&nbsp;</label>
            <button type="button" class="btn btn--danger rm-line" style="width:100%;">Quitar</button>
        </div>
    </div>
</template>

<script>
(function () {
    var opts = <?= json_encode(
        '<option value="">— Seleccionar —</option>' . implode('', array_map(static function ($p) {
            $label = htmlspecialchars($p['nombre'], ENT_QUOTES, 'UTF-8') . ' · $ ' .
                number_format((float) $p['precio'], 2, '.', ',') . ' (stock ' . (int) $p['stock'] . ')';
            return '<option value="' . (int) $p['id_producto'] . '" data-stock="' . (int) $p['stock'] . '">' . $label . '</option>';
        }, $productos)),
        JSON_HEX_TAG | JSON_HEX_APOS | JSON_HEX_QUOT | JSON_HEX_AMP
    ) ?>;

    var container = document.getElementById('lines');
    var tpl = document.getElementById('tpl-line');
    var addBtn = document.getElementById('add-line');

    function bindRm(row) {
        row.querySelector('.rm-line').addEventListener('click', function () {
            if (container.querySelectorAll('.line-row').length > 1) row.remove();
        });
    }

    if (container) {
        container.querySelectorAll('.line-row').forEach(bindRm);
    }

    if (addBtn && tpl) {
        addBtn.addEventListener('click', function () {
            var html = tpl.innerHTML.replace('{{OPTIONS}}', opts);
            var wrap = document.createElement('div');
            wrap.innerHTML = html.trim();
            var row = wrap.firstElementChild;
            container.appendChild(row);
            bindRm(row);
        });
    }
})();
</script>

<?php require __DIR__ . '/includes/footer.php'; ?>
