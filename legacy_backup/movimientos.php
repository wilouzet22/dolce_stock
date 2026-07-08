<?php

declare(strict_types=1);

require __DIR__ . '/includes/init.php';

$pageTitle = 'Inventario';

$productos = $pdo->query('SELECT id_producto, nombre, stock FROM productos ORDER BY nombre')->fetchAll();

if ($_SERVER['REQUEST_METHOD'] === 'POST' && ($_POST['action'] ?? '') === 'movimiento') {
    $idProd = (int) ($_POST['id_producto'] ?? 0);
    $tipo = $_POST['tipo'] ?? '';
    $cant = (int) ($_POST['cantidad'] ?? 0);
    $desc = trim((string) ($_POST['descripcion'] ?? ''));
    if ($idProd <= 0 || !in_array($tipo, ['ENTRADA', 'SALIDA'], true) || $cant <= 0) {
        flash('err', 'Elegí producto, tipo y una cantidad mayor a cero.');
        header('Location: movimientos.php');
        exit;
    }
    try {
        $pdo->beginTransaction();

        $st = $pdo->prepare('SELECT stock FROM productos WHERE id_producto = ? FOR UPDATE');
        $st->execute([$idProd]);
        $cur = $st->fetch();
        if (!$cur) {
            throw new RuntimeException('Producto inexistente.');
        }
        $stock = (int) $cur['stock'];
        if ($tipo === 'SALIDA' && $stock < $cant) {
            throw new RuntimeException('Stock insuficiente para esa salida.');
        }
        $delta = $tipo === 'ENTRADA' ? $cant : -$cant;
        $st = $pdo->prepare('UPDATE productos SET stock = stock + ? WHERE id_producto = ?');
        $st->execute([$delta, $idProd]);

        $st = $pdo->prepare(
            'INSERT INTO movimientos_inventario (id_producto, tipo, cantidad, descripcion) VALUES (?,?,?,?)'
        );
        $st->execute([$idProd, $tipo, $cant, $desc !== '' ? $desc : null]);

        $pdo->commit();
        flash('ok', 'Movimiento registrado y stock actualizado.');
    } catch (Throwable $e) {
        $pdo->rollBack();
        flash('err', $e instanceof RuntimeException ? $e->getMessage() : 'No se pudo guardar el movimiento.');
    }
    header('Location: movimientos.php');
    exit;
}

$lista = $pdo->query('
    SELECT m.*, p.nombre AS producto
    FROM movimientos_inventario m
    JOIN productos p ON p.id_producto = m.id_producto
    ORDER BY m.fecha DESC, m.id_movimiento DESC
    LIMIT 100
')->fetchAll();

require __DIR__ . '/includes/header.php';
?>

<?php if ($productos === []) : ?>
    <div class="flash flash--err">No hay productos. <a href="productos.php" class="link-muted">Creá uno primero</a>.</div>
<?php endif; ?>

<div class="two-col">
    <div class="card">
        <div class="card-head">
            <h2 class="card-title">Registrar movimiento</h2>
        </div>
        <form method="post">
            <input type="hidden" name="action" value="movimiento">
            <div class="form-grid">
                <div class="form-group" style="grid-column: 1 / -1;">
                    <label for="id_producto">Producto</label>
                    <select id="id_producto" name="id_producto" required <?= $productos === [] ? 'disabled' : '' ?>>
                        <option value="">— Seleccionar —</option>
                        <?php foreach ($productos as $pr) : ?>
                            <option value="<?= (int) $pr['id_producto'] ?>"><?= e($pr['nombre']) ?> (stock <?= (int) $pr['stock'] ?>)</option>
                        <?php endforeach; ?>
                    </select>
                </div>
                <div class="form-group">
                    <label for="tipo">Tipo</label>
                    <select id="tipo" name="tipo" required>
                        <option value="ENTRADA">Entrada (reposición)</option>
                        <option value="SALIDA">Salida (ajuste / consumo interno)</option>
                    </select>
                </div>
                <div class="form-group">
                    <label for="cantidad">Cantidad</label>
                    <input type="number" id="cantidad" name="cantidad" min="1" required value="1">
                </div>
                <div class="form-group" style="grid-column: 1 / -1;">
                    <label for="descripcion">Nota / descripción (opcional)</label>
                    <textarea id="descripcion" name="descripcion" maxlength="500" placeholder="Ej. reposición proveedor, merma bar…"></textarea>
                </div>
            </div>
            <div class="form-actions">
                <button type="submit" class="btn btn--primary" <?= $productos === [] ? 'disabled' : '' ?>>Registrar</button>
            </div>
        </form>
    </div>

    <div class="card">
        <div class="card-head">
            <h2 class="card-title">Historial reciente</h2>
        </div>
        <?php if ($lista === []) : ?>
            <p class="link-muted">Todavía no hay movimientos.</p>
        <?php else : ?>
            <div class="table-wrap">
                <table class="data">
                    <thead>
                        <tr>
                            <th>Fecha</th>
                            <th>Producto</th>
                            <th>Tipo</th>
                            <th class="num">Cantidad</th>
                            <th>Nota</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php foreach ($lista as $m) : ?>
                            <tr>
                                <td><?= e($m['fecha']) ?></td>
                                <td><?= e($m['producto']) ?></td>
                                <td>
                                    <?php if ($m['tipo'] === 'ENTRADA') : ?>
                                        <span class="badge badge--entrada">Entrada</span>
                                    <?php else : ?>
                                        <span class="badge badge--salida">Salida</span>
                                    <?php endif; ?>
                                </td>
                                <td class="num"><?= (int) $m['cantidad'] ?></td>
                                <td><?= e($m['descripcion'] ?? '') ?></td>
                            </tr>
                        <?php endforeach; ?>
                    </tbody>
                </table>
            </div>
        <?php endif; ?>
    </div>
</div>

<?php require __DIR__ . '/includes/footer.php'; ?>
