<div class="two-col">
    <div class="card"><div class="card-head"><h2 class="card-title"><?= $row ? 'Editar producto' : 'Nuevo producto' ?></h2></div>
        <form method="post" action="<?= e(url('product')) ?>">
            <input type="hidden" name="action" value="<?= $row ? 'update' : 'create' ?>"><?php if ($row) : ?><input type="hidden" name="id_producto" value="<?= (int) $row['id_producto'] ?>"><?php endif; ?>
            <div class="form-grid">
                <div class="form-group" style="grid-column:1/-1;"><label>Nombre</label><input name="nombre" required maxlength="150" value="<?= e($row['nombre'] ?? '') ?>"></div>
                <div class="form-group"><label>Categoría</label><select name="id_categorias" required><option value="">— Elegir —</option><?php foreach ($categorias as $cat) : ?><option value="<?= (int) $cat['id_categoria'] ?>"<?= $row && (int) $row['id_categorias'] === (int) $cat['id_categoria'] ? ' selected' : '' ?>><?= e($cat['nombre']) ?></option><?php endforeach; ?></select></div>
                <div class="form-group"><label>Precio</label><input type="number" step="0.01" min="0" name="precio" value="<?= e((string) ($row['precio'] ?? 0)) ?>"></div>
                <div class="form-group"><label>Stock</label><input type="number" min="0" name="stock" value="<?= e((string) ($row['stock'] ?? 0)) ?>"></div>
            </div>
            <div class="form-actions"><button class="btn btn--primary"><?= $row ? 'Guardar' : 'Agregar producto' ?></button><?php if ($row) : ?><a class="btn btn--ghost" href="<?= e(url('product')) ?>">Cancelar</a><?php endif; ?></div>
        </form>
    </div>
    <div class="card"><div class="card-head"><h2 class="card-title">Listado</h2></div><div class="table-wrap"><table class="data"><thead><tr><th>Producto</th><th>Categoría</th><th class="num">Precio</th><th class="num">Stock</th><th></th></tr></thead><tbody>
        <?php foreach ($lista as $p) : ?><tr><td><?= e($p['nombre']) ?></td><td><?= e($p['categoria']) ?></td><td class="num">$ <?= number_format((float) $p['precio'], 2, '.', ',') ?></td><td class="num"><?= (int) $p['stock'] ?></td><td><div class="row-actions"><a class="btn btn--ghost" style="padding:.35rem .7rem;font-size:.82rem" href="<?= e(url('product', 'index', ['edit' => (int) $p['id_producto']])) ?>">Editar</a><form method="post" action="<?= e(url('product')) ?>" style="display:inline"><input type="hidden" name="action" value="delete"><input type="hidden" name="id_producto" value="<?= (int) $p['id_producto'] ?>"><button class="btn btn--danger" style="padding:.35rem .7rem;font-size:.82rem">Eliminar</button></form></div></td></tr><?php endforeach; ?>
    </tbody></table></div></div>
</div>
