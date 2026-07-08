<?php

declare(strict_types=1);

require __DIR__ . '/includes/init.php';

$pageTitle = 'Clientes';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $action = $_POST['action'] ?? '';
    if ($action === 'create') {
        $nombre = trim((string) ($_POST['nombre'] ?? ''));
        if ($nombre !== '') {
            $st = $pdo->prepare('INSERT INTO clientes (nombre) VALUES (?)');
            $st->execute([$nombre]);
            flash('ok', 'Cliente creado.');
        }
        header('Location: clientes.php');
        exit;
    }
    if ($action === 'update') {
        $id = (int) ($_POST['id_cliente'] ?? 0);
        $nombre = trim((string) ($_POST['nombre'] ?? ''));
        if ($id > 0 && $nombre !== '') {
            $st = $pdo->prepare('UPDATE clientes SET nombre = ? WHERE id_cliente = ?');
            $st->execute([$nombre, $id]);
            flash('ok', 'Cliente actualizado.');
        }
        header('Location: clientes.php');
        exit;
    }
    if ($action === 'delete') {
        $id = (int) ($_POST['id_cliente'] ?? 0);
        if ($id > 0) {
            try {
                $st = $pdo->prepare('DELETE FROM clientes WHERE id_cliente = ?');
                $st->execute([$id]);
                flash('ok', 'Cliente eliminado.');
            } catch (PDOException $e) {
                flash('err', 'No se puede eliminar: tiene facturas asociadas.');
            }
        }
        header('Location: clientes.php');
        exit;
    }
}

$row = null;
if (isset($_GET['edit'])) {
    $eid = (int) $_GET['edit'];
    if ($eid > 0) {
        $st = $pdo->prepare('SELECT * FROM clientes WHERE id_cliente = ?');
        $st->execute([$eid]);
        $row = $st->fetch();
        if (!$row) {
            $row = null;
        }
    }
}

$lista = $pdo->query('SELECT * FROM clientes ORDER BY nombre')->fetchAll();

require __DIR__ . '/includes/header.php';
?>

<div class="two-col">
    <div class="card">
        <div class="card-head">
            <h2 class="card-title"><?= $row ? 'Editar cliente' : 'Nuevo cliente' ?></h2>
        </div>
        <form method="post">
            <input type="hidden" name="action" value="<?= $row ? 'update' : 'create' ?>">
            <?php if ($row) : ?>
                <input type="hidden" name="id_cliente" value="<?= (int) $row['id_cliente'] ?>">
            <?php endif; ?>
            <div class="form-grid">
                <div class="form-group" style="grid-column: 1 / -1;">
                    <label for="nombre">Nombre</label>
                    <input type="text" id="nombre" name="nombre" required maxlength="150" value="<?= e($row['nombre'] ?? '') ?>">
                </div>
            </div>
            <div class="form-actions">
                <button type="submit" class="btn btn--primary"><?= $row ? 'Guardar' : 'Agregar' ?></button>
                <?php if ($row) : ?>
                    <a class="btn btn--ghost" href="clientes.php">Cancelar</a>
                <?php endif; ?>
            </div>
        </form>
    </div>

    <div class="card">
        <div class="card-head">
            <h2 class="card-title">Listado</h2>
        </div>
        <?php if ($lista === []) : ?>
            <p class="link-muted">No hay clientes.</p>
        <?php else : ?>
            <div class="table-wrap">
                <table class="data">
                    <thead>
                        <tr>
                            <th>ID</th>
                            <th>Nombre</th>
                            <th></th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php foreach ($lista as $c) : ?>
                            <tr>
                                <td><?= (int) $c['id_cliente'] ?></td>
                                <td><?= e($c['nombre']) ?></td>
                                <td>
                                    <div class="row-actions">
                                        <a class="btn btn--ghost" style="padding:.35rem .7rem;font-size:.82rem" href="clientes.php?edit=<?= (int) $c['id_cliente'] ?>">Editar</a>
                                        <form method="post" style="display:inline" onsubmit="return confirm('¿Eliminar este cliente?');">
                                            <input type="hidden" name="action" value="delete">
                                            <input type="hidden" name="id_cliente" value="<?= (int) $c['id_cliente'] ?>">
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
