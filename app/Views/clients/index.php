<div class="two-col">
    <div class="card">
        <div class="card-head">
            <h2 class="card-title"><?= $row ? 'Editar cliente' : 'Nuevo cliente' ?></h2>
        </div>
        <form method="post" action="<?= e(url('client')) ?>">
            <input type="hidden" name="action" value="<?= $row ? 'update' : 'create' ?>">
            <?php if ($row) : ?>
                <input type="hidden" name="id_cliente" value="<?= (int) $row['id_cliente'] ?>">
            <?php endif; ?>
            
            <div class="form-grid">
                <div class="form-group col-full">
                    <label for="cli-identificacion">Número de Identidad / CC / NIT <span class="optional">(opcional)</span></label>
                    <input id="cli-identificacion" name="identificacion" maxlength="50" placeholder="Ej: 1020304050 ó 900123456-1" value="<?= e($row['identificacion'] ?? '') ?>">
                </div>

                <div class="form-group col-full">
                    <label for="cli-nombre">Nombres</label>
                    <input id="cli-nombre" name="nombre" required maxlength="150" placeholder="Ej: Juan, María José..." value="<?= e($row['nombre'] ?? '') ?>">
                </div>

                <div class="form-group col-full">
                    <label for="cli-apellidos">Apellidos <span class="optional">(opcional)</span></label>
                    <input id="cli-apellidos" name="apellidos" maxlength="150" placeholder="Ej: Pérez Gómez, Rodríguez..." value="<?= e($row['apellidos'] ?? '') ?>">
                </div>

                <div class="form-group col-full">
                    <label for="cli-email">Correo Electrónico <span class="optional">(opcional)</span></label>
                    <input id="cli-email" name="email" type="email" maxlength="150" placeholder="ejemplo@cliente.com" value="<?= e($row['email'] ?? '') ?>">
                </div>

                <div class="form-group col-full">
                    <label for="cli-direccion">Dirección de Residencia <span class="optional">(opcional)</span></label>
                    <input id="cli-direccion" name="direccion" maxlength="255" placeholder="Ej: Calle 10 # 45-20, Apto 301" value="<?= e($row['direccion'] ?? '') ?>">
                </div>
            </div>

            <div class="form-actions" style="margin-top: 1.5rem;">
                <button class="btn btn--primary" type="submit">
                    <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><polyline points="20 6 9 17 4 12"></polyline></svg>
                    <?= $row ? 'Guardar cambios' : 'Agregar cliente' ?>
                </button>
                <?php if ($row) : ?>
                    <a class="btn btn--ghost" href="<?= e(url('client')) ?>">Cancelar</a>
                <?php endif; ?>
            </div>
        </form>
    </div>

    <div class="card">
        <div class="card-head">
            <h2 class="card-title">Listado de Clientes</h2>
        </div>
        <div class="table-search-bar">
            <div class="search-input-wrap">
                <span class="search-icon">
                    <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5" stroke-linecap="round" stroke-linejoin="round"><circle cx="11" cy="11" r="8"></circle><line x1="21" y1="21" x2="16.65" y2="16.65"></line></svg>
                </span>
                <input type="text" class="table-search" placeholder="Filtrar clientes por nombre, apellidos, documento o correo..." data-table="tbl-clientes" autocomplete="off">
            </div>
        </div>
        <div class="table-wrap">
            <table class="data" id="tbl-clientes">
                <thead>
                    <tr>
                        <th style="width:50px;">ID</th>
                        <th>Identificación</th>
                        <th>Nombres</th>
                        <th>Apellidos</th>
                        <th>Correo Electrónico</th>
                        <th>Dirección</th>
                        <th style="text-align:right;">Acciones</th>
                    </tr>
                </thead>
                <tbody>
                    <?php if (empty($lista)) : ?>
                        <tr><td colspan="7" style="text-align:center;color:var(--text-muted);padding:2rem;">No hay clientes registrados aún.</td></tr>
                    <?php else : ?>
                        <?php foreach ($lista as $c) : ?>
                            <tr>
                                <td><span class="badge" style="background:var(--border-color);color:var(--text-muted);"><?= (int) $c['id_cliente'] ?></span></td>
                                <td><?= e($c['identificacion'] ?? '-') ?></td>
                                <td><strong><?= e($c['nombre']) ?></strong></td>
                                <td><?= e($c['apellidos'] ?? '-') ?></td>
                                <td><?= e($c['email'] ?? '-') ?></td>
                                <td><?= e($c['direccion'] ?? '-') ?></td>
                                <td style="text-align:right;">
                                    <div class="row-actions" style="justify-content:flex-end;">
                                        <a class="btn btn--ghost btn--sm" href="<?= e(url('client', 'index', ['edit' => (int) $c['id_cliente']])) ?>">Editar</a>
                                        <form method="post" action="<?= e(url('client')) ?>" style="display:inline" onsubmit="return confirm('¿Eliminar este cliente?');">
                                            <input type="hidden" name="action" value="delete">
                                            <input type="hidden" name="id_cliente" value="<?= (int) $c['id_cliente'] ?>">
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
