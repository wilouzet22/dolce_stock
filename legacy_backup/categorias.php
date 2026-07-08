<?php

declare(strict_types=1);

require __DIR__ . '/includes/init.php';

$pageTitle = 'Categorías';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $action = $_POST['action'] ?? '';
    if ($action === 'create') {
        $nombre = trim((string) ($_POST['nombre'] ?? ''));
        if ($nombre !== '') {
            $st = $pdo->prepare('INSERT INTO categorias (nombre) VALUES (?)');
            $st->execute([$nombre]);
            flash('ok', 'Categoría creada.');
        } else {
            flash('err', 'El nombre es obligatorio.');
        }
        header('Location: categorias.php');
        exit;
    }
    if ($action === 'update') {
        $id = (int) ($_POST['id_categoria'] ?? 0);
        $nombre = trim((string) ($_POST['nombre'] ?? ''));
        if ($id > 0 && $nombre !== '') {
            $st = $pdo->prepare('UPDATE categorias SET nombre = ? WHERE id_categoria = ?');
            $st->execute([$nombre, $id]);
            flash('ok', 'Categoría actualizada.');
        }
        header('Location: categorias.php');
        exit;
    }
    if ($action === 'delete') {
        $id = (int) ($_POST['id_categoria'] ?? 0);
        if ($id > 0) {
            try {
                $st = $pdo->prepare('DELETE FROM categorias WHERE id_categoria = ?');
                $st->execute([$id]);
                flash('ok', 'Categoría eliminada.');
            } catch (PDOException $e) {
                flash('err', 'No se puede eliminar: tiene productos vinculados (o restricción de BD).');
            }
        }
        header('Location: categorias.php');
        exit;
    }
}

$row = null;
if (isset($_GET['edit'])) {
    $eid = (int) $_GET['edit'];
    if ($eid > 0) {
        $st = $pdo->prepare('SELECT * FROM categorias WHERE id_categoria = ?');
        $st->execute([$eid]);
        $row = $st->fetch();
        if (!$row) {
            $row = null;
        }
    }
}

$lista = $pdo->query('SELECT * FROM categorias ORDER BY nombre')->fetchAll();

require __DIR__ . '/includes/header.php';
?>

<div class="two-col">
    <div class="card">
        <div class="card-head">
            <h2 class="card-title"><?= $row ? 'Editar categoría' : 'Nueva categoría' ?></h2>
        </div>
        <form method="post">
            <input type="hidden" name="action" value="<?= $row ? 'update' : 'create' ?>">
            <?php if ($row) : ?>
                <input type="hidden" name="id_categoria" value="<?= (int) $row['id_categoria'] ?>">
            <?php endif; ?>
            <div class="form-grid">
                <div class="form-group" style="grid-column: 1 / -1;">
                    <label for="nombre">Nombre</label>
                    <input type="text" id="nombre" name="nombre" required maxlength="100" value="<?= e($row['nombre'] ?? '') ?>">
                </div>
            </div>
            <div class="form-actions">
                <button type="submit" class="btn btn--primary"><?= $row ? 'Guardar cambios' : 'Agregar categoría' ?></button>
                <?php if ($row) : ?>
                    <a class="btn btn--ghost" href="categorias.php">Cancelar</a>
                <?php endif; ?>
            </div>
        </form>
    </div>

    <div class="card">
        <div class="card-head">
            <h2 class="card-title">Listado</h2>
        </div>
        <?php if ($lista === []) : ?>
            <p class="link-muted">No hay categorías. Creá una al costado.</p>
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
                                <td><?= (int) $c['id_categoria'] ?></td>
                                <td><?= e($c['nombre']) ?></td>
                                <td>
                                    <div class="row-actions">
                                        <a class="btn btn--ghost" style="padding:.35rem .7rem;font-size:.82rem" href="categorias.php?edit=<?= (int) $c['id_categoria'] ?>">Editar</a>
                                        <form method="post" style="display:inline" onsubmit="return confirm('¿Eliminar esta categoría y sus productos (cascade)?');">
                                            <input type="hidden" name="action" value="delete">
                                            <input type="hidden" name="id_categoria" value="<?= (int) $c['id_categoria'] ?>">
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
