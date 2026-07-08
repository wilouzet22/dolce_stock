<div class="two-col">
    <div class="card"><div class="card-head"><h2 class="card-title"><?= $row ? 'Editar empleado' : 'Nuevo empleado' ?></h2></div>
        <form method="post" action="<?= e(url('employee')) ?>">
            <input type="hidden" name="action" value="<?= $row ? 'update' : 'create' ?>"><?php if ($row) : ?><input type="hidden" name="id_empleado" value="<?= (int) $row['id_empleado'] ?>"><?php endif; ?>
            <div class="form-grid">
                <div class="form-group" style="grid-column:1/-1;"><label>Nombre</label><input name="nombre" required maxlength="150" value="<?= e($row['nombre'] ?? '') ?>"></div>
                <div class="form-group"><label>Cargo</label><input name="cargo" maxlength="100" value="<?= e($row['cargo'] ?? '') ?>"></div>
                <div class="form-group"><label>Salario</label><input type="number" step="0.01" min="0" name="salario" value="<?= isset($row['salario']) ? e((string) $row['salario']) : '' ?>"></div>
            </div>
            <div class="form-actions"><button class="btn btn--primary"><?= $row ? 'Guardar' : 'Agregar' ?></button><?php if ($row) : ?><a class="btn btn--ghost" href="<?= e(url('employee')) ?>">Cancelar</a><?php endif; ?></div>
        </form>
    </div>
    <div class="card"><div class="card-head"><h2 class="card-title">Equipo</h2></div><div class="table-wrap"><table class="data"><thead><tr><th>Nombre</th><th>Cargo</th><th class="num">Salario</th><th></th></tr></thead><tbody>
        <?php foreach ($lista as $emp) : ?><tr><td><?= e($emp['nombre']) ?></td><td><?= e($emp['cargo'] ?? '—') ?></td><td class="num"><?= $emp['salario'] !== null ? '$ ' . number_format((float) $emp['salario'], 2, '.', ',') : '—' ?></td><td><div class="row-actions"><a class="btn btn--ghost" style="padding:.35rem .7rem;font-size:.82rem" href="<?= e(url('employee', 'index', ['edit' => (int) $emp['id_empleado']])) ?>">Editar</a><form method="post" action="<?= e(url('employee')) ?>" style="display:inline"><input type="hidden" name="action" value="delete"><input type="hidden" name="id_empleado" value="<?= (int) $emp['id_empleado'] ?>"><button class="btn btn--danger" style="padding:.35rem .7rem;font-size:.82rem">Eliminar</button></form></div></td></tr><?php endforeach; ?>
    </tbody></table></div></div>
</div>
