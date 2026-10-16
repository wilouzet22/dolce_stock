<div class="two-col">
    <div class="card">
        <div class="card-head">
            <h2 class="card-title"><?= $row ? 'Editar categoría' : 'Nueva categoría' ?></h2>
        </div>
        <form method="post" action="<?= e(url('category')) ?>">
            <input type="hidden" name="action" value="<?= $row ? 'update' : 'create' ?>">
            <?php if ($row) : ?>
                <input type="hidden" name="id_categoria" value="<?= (int) $row['id_categoria'] ?>">
            <?php endif; ?>
            
            <div class="form-grid">
                <div class="form-group col-full">
                    <label for="cat-nombre">Nombre de la Categoría</label>
                    <input id="cat-nombre" name="nombre" required maxlength="100" placeholder="Ej: Bebidas, Panadería, Repostería..." value="<?= e($row['nombre'] ?? '') ?>">
                </div>
            </div>

            <div class="form-actions">
                <button class="btn btn--primary" type="submit">
                    <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><polyline points="20 6 9 17 4 12"></polyline></svg>
                    <?= $row ? 'Guardar cambios' : 'Agregar categoría' ?>
                </button>
                <?php if ($row) : ?>
                    <a class="btn btn--ghost" href="<?= e(url('category')) ?>">Cancelar</a>
                <?php endif; ?>
            </div>
        </form>
    </div>

    <div class="card">
        <div class="card-head">
            <h2 class="card-title">Listado de Categorías</h2>
        </div>
        <div class="table-search-bar">
            <div class="search-input-wrap">
                <span class="search-icon">
                    <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5" stroke-linecap="round" stroke-linejoin="round"><circle cx="11" cy="11" r="8"></circle><line x1="21" y1="21" x2="16.65" y2="16.65"></line></svg>
                </span>
                <input type="text" class="table-search" placeholder="Filtrar categorías..." data-table="tbl-categorias" autocomplete="off">
            </div>
        </div>
        <div class="table-wrap">
            <table class="data" id="tbl-categorias">
                <thead>
                    <tr>
                        <th style="width:70px;">ID</th>
                        <th>Nombre</th>
                        <th style="text-align:right;">Acciones</th>
                    </tr>
                </thead>
                <tbody>
                    <?php if (empty($lista)) : ?>
                        <tr><td colspan="3" style="text-align:center;color:var(--text-muted);padding:2rem;">No hay categorías registradas.</td></tr>
                    <?php else : ?>
                        <?php foreach ($lista as $c) : ?>
                            <tr>
                                <td><span class="badge" style="background:var(--border-color);color:var(--text-muted);"><?= (int) $c['id_categoria'] ?></span></td>
                                <td><strong><?= e($c['nombre']) ?></strong></td>
                                <td style="text-align:right;">
                                    <div class="row-actions" style="justify-content:flex-end;">
                                        <a class="btn btn--ghost btn--sm" href="<?= e(url('category', 'index', ['edit' => (int) $c['id_categoria']])) ?>">Editar</a>
                                        <form method="post" action="<?= e(url('category')) ?>" style="display:inline" onsubmit="return confirm('¿Eliminar esta categoría? Se eliminarán los productos asociados.');">
                                            <input type="hidden" name="action" value="delete">
                                            <input type="hidden" name="id_categoria" value="<?= (int) $c['id_categoria'] ?>">
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
