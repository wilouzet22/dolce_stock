<div class="two-col">
    <div class="card">
        <div class="card-head"><h2 class="card-title"><?= $row ? 'Editar categoría' : 'Nueva categoría' ?></h2></div>
        <form method="post" action="<?= e(url('category')) ?>">
            <input type="hidden" name="action" value="<?= $row ? 'update' : 'create' ?>">
            <?php if ($row) : ?><input type="hidden" name="id_categoria" value="<?= (int) $row['id_categoria'] ?>"><?php endif; ?>
            <div class="form-grid"><div class="form-group" style="grid-column:1/-1;"><label for="nombre">Nombre</label><input id="nombre" name="nombre" required maxlength="100" value="<?= e($row['nombre'] ?? '') ?>"></div></div>
            <div class="form-actions"><button class="btn btn--primary" type="submit"><?= $row ? 'Guardar cambios' : 'Agregar categoría' ?></button><?php if ($row) : ?><a class="btn btn--ghost" href="<?= e(url('category')) ?>">Cancelar</a><?php endif; ?></div>
        </form>
    </div>
    <div class="card">
        <div class="card-head"><h2 class="card-title">Listado</h2></div>
        <div class="table-wrap"><table class="data"><thead><tr><th>ID</th><th>Nombre</th><th></th></tr></thead><tbody>
            <?php foreach ($lista as $c) : ?>
                <tr>
                    <td><?= (int) $c['id_categoria'] ?></td><td><?= e($c['nombre']) ?></td>
                    <td><div class="row-actions">
                        <a class="btn btn--ghost" style="padding:.35rem .7rem;font-size:.82rem" href="<?= e(url('category', 'index', ['edit' => (int) $c['id_categoria']])) ?>">Editar</a>
                        <form method="post" action="<?= e(url('category')) ?>" style="display:inline" onsubmit="return confirm('¿Eliminar esta categoría?');">
                            <input type="hidden" name="action" value="delete"><input type="hidden" name="id_categoria" value="<?= (int) $c['id_categoria'] ?>">
                            <button type="submit" class="btn btn--danger" style="padding:.35rem .7rem;font-size:.82rem">Eliminar</button>
                        </form>
                    </div></td>
                </tr>
            <?php endforeach; ?>
        </tbody></table></div>
    </div>
</div>
