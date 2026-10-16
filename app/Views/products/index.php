<div class="two-col">
    <div class="card">
        <div class="card-head">
            <h2 class="card-title"><?= $row ? 'Editar producto' : 'Nuevo producto' ?></h2>
        </div>
        <form method="post" action="<?= e(url('product')) ?>">
            <input type="hidden" name="action" value="<?= $row ? 'update' : 'create' ?>">
            <?php if ($row) : ?>
                <input type="hidden" name="id_producto" value="<?= (int) $row['id_producto'] ?>">
            <?php endif; ?>
            
            <div class="form-grid">
                <div class="form-group col-full">
                    <label for="prod-nombre">Nombre del Producto</label>
                    <input id="prod-nombre" name="nombre" required maxlength="150" placeholder="Ej: Pulpa de Maracuyá, Pan Rollo..." value="<?= e($row['nombre'] ?? '') ?>">
                </div>

                <div class="form-group col-full">
                    <label for="prod-cat">Categoría</label>
                    <select id="prod-cat" name="id_categorias" required>
                        <option value="">— Seleccionar Categoría —</option>
                        <?php foreach ($categorias as $cat) : ?>
                            <option value="<?= (int) $cat['id_categoria'] ?>"<?= $row && (int) $row['id_categorias'] === (int) $cat['id_categoria'] ? ' selected' : '' ?>>
                                <?= e($cat['nombre']) ?>
                            </option>
                        <?php endforeach; ?>
                    </select>
                </div>

                <div class="form-group">
                    <label for="prod-precio">Precio de Venta</label>
                    <div class="input-wrapper">
                        <span class="input-prefix">$</span>
                        <input id="prod-precio" class="input-has-prefix" type="number" step="0.01" min="0" name="precio" placeholder="0.00" value="<?= e((string) ($row['precio'] ?? '0.00')) ?>" required>
                    </div>
                </div>

                <div class="form-group">
                    <label for="prod-stock">Stock Inicial</label>
                    <div class="input-wrapper">
                        <input id="prod-stock" class="input-has-suffix" type="number" min="0" name="stock" placeholder="0" value="<?= e((string) ($row['stock'] ?? 0)) ?>" required>
                        <span class="input-suffix">und</span>
                    </div>
                </div>
            </div>

            <div class="form-actions">
                <button type="submit" class="btn btn--primary">
                    <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><polyline points="20 6 9 17 4 12"></polyline></svg>
                    <?= $row ? 'Guardar cambios' : 'Agregar producto' ?>
                </button>
                <?php if ($row) : ?>
                    <a class="btn btn--ghost" href="<?= e(url('product')) ?>">Cancelar</a>
                <?php endif; ?>
            </div>
        </form>
    </div>

    <div class="card">
        <div class="card-head">
            <h2 class="card-title">Listado de Productos</h2>
        </div>
        <div class="table-search-bar">
            <div class="search-input-wrap">
                <span class="search-icon">
                    <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5" stroke-linecap="round" stroke-linejoin="round"><circle cx="11" cy="11" r="8"></circle><line x1="21" y1="21" x2="16.65" y2="16.65"></line></svg>
                </span>
                <input type="text" class="table-search" placeholder="Filtrar productos por nombre o categoría..." data-table="tbl-productos" autocomplete="off">
            </div>
        </div>
        <div class="table-wrap">
            <table class="data" id="tbl-productos">
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
                    <?php if (empty($lista)) : ?>
                        <tr><td colspan="5" style="text-align:center;color:var(--text-muted);padding:2rem;">No hay productos registrados aún.</td></tr>
                    <?php else : ?>
                        <?php foreach ($lista as $p) : ?>
                            <tr>
                                <td><strong><?= e($p['nombre']) ?></strong></td>
                                <td><span class="badge" style="background:var(--accent-soft);color:var(--accent);"><?= e($p['categoria']) ?></span></td>
                                <td class="num">$ <?= number_format((float) $p['precio'], 2, '.', ',') ?></td>
                                <td class="num"><strong><?= (int) $p['stock'] ?></strong></td>
                                <td>
                                    <div class="row-actions">
                                        <a class="btn btn--ghost btn--sm" href="<?= e(url('product', 'index', ['edit' => (int) $p['id_producto']])) ?>">Editar</a>
                                        <form method="post" action="<?= e(url('product')) ?>" style="display:inline" onsubmit="return confirm('¿Eliminar este producto?');">
                                            <input type="hidden" name="action" value="delete">
                                            <input type="hidden" name="id_producto" value="<?= (int) $p['id_producto'] ?>">
                                            <button type="submit" class="btn btn--danger btn--sm">Eliminar</button>
                                        </form>
                                    </div>
                                </td>
                            </tr>
                        <?php endforeach; ?>
                    <?php endif; ?>
                </tbody>
            </table>
        </div>
    </div>
</div>
