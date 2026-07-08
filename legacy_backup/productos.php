<?php

declare(strict_types=1);

require __DIR__ . '/includes/init.php';

$pageTitle = 'Productos';

$categorias = $pdo->query('SELECT id_categoria, nombre FROM categorias ORDER BY nombre')->fetchAll();

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $action = $_POST['action'] ?? '';
    if ($action === 'create') {
        $nombre = trim((string) ($_POST['nombre'] ?? ''));
        $precio = (float) ($_POST['precio'] ?? 0);
        $stock = (int) ($_POST['stock'] ?? 0);
        $idCat = (int) ($_POST['id_categorias'] ?? 0);
        if ($nombre !== '' && $idCat > 0 && $precio >= 0 && $stock >= 0) {
            $st = $pdo->prepare('INSERT INTO productos (nombre, precio, stock, id_categorias) VALUES (?, ?, ?, ?)');
            $st->execute([$nombre, $precio, $stock, $idCat]);
            flash('ok', 'Producto creado.');
        } else {
            flash('err', 'Completá nombre, categoría y valores válidos.');
        }
        header('Location: productos.php');
        exit;
    }
    if ($action === 'update') {
        $id = (int) ($_POST['id_producto'] ?? 0);
        $nombre = trim((string) ($_POST['nombre'] ?? ''));
        $precio = (float) ($_POST['precio'] ?? 0);
        $stock = (int) ($_POST['stock'] ?? 0);
        $idCat = (int) ($_POST['id_categorias'] ?? 0);
        if ($id > 0 && $nombre !== '' && $idCat > 0 && $precio >= 0 && $stock >= 0) {
            $st = $pdo->prepare('UPDATE productos SET nombre = ?, precio = ?, stock = ?, id_categorias = ? WHERE id_producto = ?');
            $st->execute([$nombre, $precio, $stock, $idCat, $id]);
            flash('ok', 'Producto actualizado.');
        }
        header('Location: productos.php');
        exit;
    }
    if ($action === 'delete') {
        $id = (int) ($_POST['id_producto'] ?? 0);
        if ($id > 0) {
            try {
                $st = $pdo->prepare('DELETE FROM productos WHERE id_producto = ?');
                $st->execute([$id]);
                flash('ok', 'Producto eliminado.');
            } catch (PDOException $e) {
                flash('err', 'No se puede eliminar: está en facturas o movimientos.');
            }
        }
        header('Location: productos.php');
        exit;
    }
}

$row = null;
if (isset($_GET['edit'])) {
    $eid = (int) $_GET['edit'];
    if ($eid > 0) {
        $st = $pdo->prepare('SELECT * FROM productos WHERE id_producto = ?');
        $st->execute([$eid]);
        $row = $st->fetch();
        if (!$row) {
            $row = null;
        }
    }
}

$lista = $pdo->query('
    SELECT p.*, c.nombre AS categoria
    FROM productos p
    JOIN categorias c ON c.id_categoria = p.id_categorias
    ORDER BY c.nombre, p.nombre
')->fetchAll();

require __DIR__ . '/includes/header.php';
?>

<?php if ($categorias === []) : ?>
    <div class="flash flash--err">Necesitás al menos una categoría. <a href="categorias.php" class="link-muted">Crear categoría</a></div>
<?php endif; ?>

<div class="two-col">
    <div class="card">
        <div class="card-head">
            <h2 class="card-title"><?= $row ? 'Editar producto' : 'Nuevo producto' ?></h2>
        </div>
        <form method="post">
            <input type="hidden" name="action" value="<?= $row ? 'update' : 'create' ?>">
            <?php if ($row) : ?>
                <input type="hidden" name="id_producto" value="<?= (int) $row['id_producto'] ?>">
            <?php endif; ?>
            <div class="form-grid">
                <div class="form-group" style="grid-column: 1 / -1;">
                    <label for="nombre">Nombre</label>
                    <input type="text" id="nombre" name="nombre" required maxlength="150" value="<?= e($row['nombre'] ?? '') ?>">
                </div>
                <div class="form-group">
                    <label for="id_categorias">Categoría</label>
                    <select id="id_categorias" name="id_categorias" required <?= $categorias === [] ? 'disabled' : '' ?>>
                        <option value="">— Elegir —</option>
                        <?php foreach ($categorias as $cat) :
                            $sel = $row && (int) $row['id_categorias'] === (int) $cat['id_categoria'] ? ' selected' : ''; ?>
                            <option value="<?= (int) $cat['id_categoria'] ?>"<?= $sel ?>><?= e($cat['nombre']) ?></option>
                        <?php endforeach; ?>
                    </select>
                </div>
                <div class="form-group">
                    <label for="precio">Precio venta</label>
                    <input type="number" id="precio" name="precio" step="0.01" min="0" required value="<?= e(isset($row['precio']) ? (string) $row['precio'] : '0') ?>">
                </div>
                <div class="form-group">
                    <label for="stock">Stock actual</label>
                    <input type="number" id="stock" name="stock" min="0" required value="<?= isset($row['stock']) ? (int) $row['stock'] : '0' ?>">
                </div>
            </div>
            <div class="form-actions">
                <button type="submit" class="btn btn--primary" <?= $categorias === [] ? 'disabled' : '' ?>><?= $row ? 'Guardar' : 'Agregar producto' ?></button>
                <?php if ($row) : ?>
                    <a class="btn btn--ghost" href="productos.php">Cancelar</a>
                <?php endif; ?>
            </div>
        </form>
        <p class="link-muted" style="margin:1rem 0 0;font-size:.88rem;">Para ajustes de stock por reposición o merma usá la sección <a href="movimientos.php">Inventario</a>.</p>
    </div>

    <div class="card">
        <div class="card-head">
            <h2 class="card-title">Carta / almacén</h2>
        </div>
        <?php if ($lista === []) : ?>
            <p class="link-muted">Todavía no hay productos.</p>
        <?php else : ?>
            <div class="table-wrap">
                <table class="data">
                    <thead>
                        <tr>
                            <th>Producto</th>
                            <th>Categoría</th>
                            <th class="num">Precio</th>
                            <th class="num">Stock</th>
                            <th></th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php foreach ($lista as $p) : ?>
                            <tr>
                                <td><?= e($p['nombre']) ?></td>
                                <td><?= e($p['categoria']) ?></td>
                                <td class="num">$ <?= number_format((float) $p['precio'], 2, '.', ',') ?></td>
                                <td class="num"><?= (int) $p['stock'] ?></td>
                                <td>
                                    <div class="row-actions">
                                        <a class="btn btn--ghost" style="padding:.35rem .7rem;font-size:.82rem" href="productos.php?edit=<?= (int) $p['id_producto'] ?>">Editar</a>
                                        <form method="post" style="display:inline" onsubmit="return confirm('¿Eliminar este producto?');">
                                            <input type="hidden" name="action" value="delete">
                                            <input type="hidden" name="id_producto" value="<?= (int) $p['id_producto'] ?>">
                                            <button type="submit" class="btn btn--danger" style="padding:.35rem .7rem;font-size:.82rem">Eliminar</button>
                                        </form>
                                    </div>
                                </td>
                            </tr>
                        <?php endforeach; ?>
                    </tbody>
                </table>
            </div>
        <?php endif; ?>
    </div>
</div>

<?php require __DIR__ . '/includes/footer.php'; ?>
