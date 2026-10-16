<div class="two-col">
    <div class="card">
        <div class="card-head"><h2 class="card-title"><?= $row ? 'Editar cliente' : 'Nuevo cliente' ?></h2></div>
        <form method="post" action="<?= e(url('client')) ?>">
            <input type="hidden" name="action" value="<?= $row ? 'update' : 'create' ?>">
            <?php if ($row) : ?><input type="hidden" name="id_cliente" value="<?= (int) $row['id_cliente'] ?>"><?php endif; ?>
            <div class="form-grid"><div class="form-group" style="grid-column:1/-1;"><label>Nombre</label><input name="nombre" required maxlength="150" value="<?= e($row['nombre'] ?? '') ?>"></div></div>
            <div class="form-actions"><button class="btn btn--primary"><?= $row ? 'Guardar' : 'Agregar' ?></button><?php if ($row) : ?><a class="btn btn--ghost" href="<?= e(url('client')) ?>">Cancelar</a><?php endif; ?></div>
        </form>
    </div>
    <div class="card"><div class="card-head"><h2 class="card-title">Listado</h2></div><div class="table-wrap"><table class="data"><thead><tr><th>ID</th><th>Nombre</th><th></th></tr></thead><tbody>
        <?php foreach ($lista as $c) : ?><tr><td><?= (int) $c['id_cliente'] ?></td><td><?= e($c['nombre']) ?></td><td><div class="row-actions"><a class="btn btn--ghost" style="padding:.35rem .7rem;font-size:.82rem" href="<?= e(url('client', 'index', ['edit' => (int) $c['id_cliente']])) ?>">Editar</a><form method="post" action="<?= e(url('client')) ?>" style="display:inline"><input type="hidden" name="action" value="delete"><input type="hidden" name="id_cliente" value="<?= (int) $c['id_cliente'] ?>"><button class="btn btn--danger" style="padding:.35rem .7rem;font-size:.82rem">Eliminar</button></form></div></td></tr><?php endforeach; ?>
    </tbody></table></div></div>
</div>
