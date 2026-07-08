<?php

declare(strict_types=1);

require __DIR__ . '/includes/init.php';

$pageTitle = 'Empleados';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $action = $_POST['action'] ?? '';
    if ($action === 'create') {
        $nombre = trim((string) ($_POST['nombre'] ?? ''));
        $cargo = trim((string) ($_POST['cargo'] ?? ''));
        $salario = $_POST['salario'] !== '' ? (float) $_POST['salario'] : null;
        if ($nombre !== '') {
            $st = $pdo->prepare('INSERT INTO empleados (nombre, cargo, salario) VALUES (?, ?, ?)');
            $st->execute([$nombre, $cargo ?: null, $salario]);
            flash('ok', 'Empleado registrado.');
        }
        header('Location: empleados.php');
        exit;
    }
    if ($action === 'update') {
        $id = (int) ($_POST['id_empleado'] ?? 0);
        $nombre = trim((string) ($_POST['nombre'] ?? ''));
        $cargo = trim((string) ($_POST['cargo'] ?? ''));
        $salario = $_POST['salario'] !== '' ? (float) $_POST['salario'] : null;
        if ($id > 0 && $nombre !== '') {
            $st = $pdo->prepare('UPDATE empleados SET nombre = ?, cargo = ?, salario = ? WHERE id_empleado = ?');
            $st->execute([$nombre, $cargo ?: null, $salario, $id]);
            flash('ok', 'Empleado actualizado.');
        }
        header('Location: empleados.php');
        exit;
    }
    if ($action === 'delete') {
        $id = (int) ($_POST['id_empleado'] ?? 0);
        if ($id > 0) {
            try {
                $st = $pdo->prepare('DELETE FROM empleados WHERE id_empleado = ?');
                $st->execute([$id]);
                flash('ok', 'Empleado eliminado.');
            } catch (PDOException $e) {
                flash('err', 'No se puede eliminar: figura en facturas.');
            }
        }
        header('Location: empleados.php');
        exit;
    }
}

$row = null;
if (isset($_GET['edit'])) {
    $eid = (int) $_GET['edit'];
    if ($eid > 0) {
        $st = $pdo->prepare('SELECT * FROM empleados WHERE id_empleado = ?');
        $st->execute([$eid]);
        $row = $st->fetch();
        if (!$row) {
            $row = null;
        }
    }
}

$lista = $pdo->query('SELECT * FROM empleados ORDER BY nombre')->fetchAll();

require __DIR__ . '/includes/header.php';
?>

<div class="two-col">
    <div class="card">
        <div class="card-head">
            <h2 class="card-title"><?= $row ? 'Editar empleado' : 'Nuevo empleado' ?></h2>
        </div>
        <form method="post">
            <input type="hidden" name="action" value="<?= $row ? 'update' : 'create' ?>">
            <?php if ($row) : ?>
                <input type="hidden" name="id_empleado" value="<?= (int) $row['id_empleado'] ?>">
            <?php endif; ?>
            <div class="form-grid">
                <div class="form-group" style="grid-column: 1 / -1;">
                    <label for="nombre">Nombre</label>
                    <input type="text" id="nombre" name="nombre" required maxlength="150" value="<?= e($row['nombre'] ?? '') ?>">
                </div>
                <div class="form-group">
                    <label for="cargo">Cargo</label>
                    <input type="text" id="cargo" name="cargo" maxlength="100" value="<?= e($row['cargo'] ?? '') ?>">
                </div>
                <div class="form-group">
                    <label for="salario">Salario</label>
                    <input type="number" id="salario" name="salario" step="0.01" min="0" value="<?= isset($row['salario']) && $row['salario'] !== null ? e((string) $row['salario']) : '' ?>">
                </div>
            </div>
            <div class="form-actions">
                <button type="submit" class="btn btn--primary"><?= $row ? 'Guardar' : 'Agregar' ?></button>
                <?php if ($row) : ?>
                    <a class="btn btn--ghost" href="empleados.php">Cancelar</a>
                <?php endif; ?>
            </div>
        </form>
    </div>

    <div class="card">
        <div class="card-head">
            <h2 class="card-title">Equipo</h2>
        </div>
        <?php if ($lista === []) : ?>
            <p class="link-muted">No hay empleados cargados.</p>
        <?php else : ?>
            <div class="table-wrap">
                <table class="data">
                    <thead>
                        <tr>
                            <th>Nombre</th>
                            <th>Cargo</th>
                            <th class="num">Salario</th>
                            <th></th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php foreach ($lista as $emp) : ?>
                            <tr>
                                <td><?= e($emp['nombre']) ?></td>
                                <td><?= e($emp['cargo'] ?? '—') ?></td>
                                <td class="num"><?= $emp['salario'] !== null ? '$ ' . number_format((float) $emp['salario'], 2, '.', ',') : '—' ?></td>
                                <td>
                                    <div class="row-actions">
                                        <a class="btn btn--ghost" style="padding:.35rem .7rem;font-size:.82rem" href="empleados.php?edit=<?= (int) $emp['id_empleado'] ?>">Editar</a>
                                        <form method="post" style="display:inline" onsubmit="return confirm('¿Eliminar este empleado?');">
                                            <input type="hidden" name="action" value="delete">
                                            <input type="hidden" name="id_empleado" value="<?= (int) $emp['id_empleado'] ?>">
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
