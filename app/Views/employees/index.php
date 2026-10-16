<?php
$currentUserId = (int) ($_SESSION['empleado_id'] ?? 0);
?>
<div class="stacked-layout">
    <div class="card">
        <div class="card-head" style="display:flex; justify-content:space-between; align-items:center; flex-wrap:wrap; gap:1rem;">
            <div>
                <h2 class="card-title" style="margin:0;">Equipo de Trabajo y Permisos</h2>
                <p style="font-size:0.85rem; color:var(--text-muted); margin:0.25rem 0 0 0;">Gestión de colaboradores, cargos y accesos al sistema</p>
            </div>
            <button type="button" class="btn btn--primary" id="btn-nuevo-emp">
                <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5" stroke-linecap="round" stroke-linejoin="round"><line x1="12" y1="5" x2="12" y2="19"></line><line x1="5" y1="12" x2="19" y2="12"></line></svg>
                Nuevo Empleado
            </button>
        </div>

        <div class="table-search-bar" style="margin-top: 1rem;">
            <div class="search-input-wrap">
                <span class="search-icon">
                    <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5" stroke-linecap="round" stroke-linejoin="round"><circle cx="11" cy="11" r="8"></circle><line x1="21" y1="21" x2="16.65" y2="16.65"></line></svg>
                </span>
                <input type="text" class="table-search" placeholder="Filtrar por nombre, correo o cargo..." data-table="tbl-empleados" autocomplete="off">
            </div>
        </div>

        <div class="table-wrap">
            <table class="data" id="tbl-empleados">
                <thead>
                    <tr>
                        <th>Nombre / Cargo</th>
                        <th>Cuenta / Correo</th>
                        <th>Rol</th>
                        <th style="text-align:right;">Acciones</th>
                    </tr>
                </thead>
                <tbody>
                    <?php if (empty($lista)) : ?>
                        <tr><td colspan="4" style="text-align:center;color:var(--text-muted);padding:2.5rem;">No hay colaboradores registrados actualmente.</td></tr>
                    <?php else : ?>
                        <?php foreach ($lista as $emp) : ?>
                            <?php 
                                $isSelf = ((int) $emp['id_empleado'] === $currentUserId); 
                                $rolVal = !empty($emp['rol']) ? strtolower($emp['rol']) : ((stripos($emp['cargo'] ?? '', 'admin') !== false || stripos($emp['cargo'] ?? '', 'jefe') !== false) ? 'admin' : 'cajero');
                            ?>
                            <tr>
                                <td>
                                    <div style="display:flex; align-items:center; gap:0.6rem;">
                                        <strong><?= e($emp['nombre']) ?></strong>
                                        <?php if ($isSelf): ?>
                                            <span style="font-size:0.7rem; background:rgba(229,137,35,0.15); color:var(--accent); padding:0.15rem 0.45rem; border-radius:10px; font-weight:600;">(Tú)</span>
                                        <?php endif; ?>
                                    </div>
                                    <?php if (!empty($emp['cargo'])): ?>
                                        <div style="font-size:0.8rem; color:var(--text-muted);"><?= e($emp['cargo']) ?></div>
                                    <?php endif; ?>
                                </td>
                                <td>
                                    <?php if (!empty($emp['email'])): ?>
                                        <span style="font-size:0.85rem; font-family:monospace; color:var(--text-main);"><?= e($emp['email']) ?></span>
                                    <?php else: ?>
                                        <span style="color:var(--text-muted); font-size:0.85rem; font-style:italic;">Sin cuenta</span>
                                    <?php endif; ?>
                                </td>
                                <td>
                                    <?php if ($rolVal === 'admin'): ?>
                                        <span class="badge" style="background:rgba(234, 88, 12, 0.15); color:#f97316; border: 1px solid rgba(234,88,12,0.3); font-weight:600;">Administrador</span>
                                    <?php else: ?>
                                        <span class="badge" style="background:var(--accent-soft); color:var(--accent); font-weight:600;">Cajero</span>
                                    <?php endif; ?>
                                </td>
                                <td style="text-align:right;">
                                    <div class="row-actions" style="justify-content:flex-end; gap:0.4rem;">
                                        <button type="button" 
                                                class="btn btn--ghost btn--sm btn-edit-emp" 
                                                data-id="<?= (int) $emp['id_empleado'] ?>"
                                                data-nombre="<?= e($emp['nombre']) ?>"
                                                data-cargo="<?= e($emp['cargo'] ?? '') ?>"
                                                data-email="<?= e($emp['email'] ?? '') ?>"
                                                data-rol="<?= e($rolVal) ?>"
                                                title="Editar datos del empleado">
                                            <svg xmlns="http://www.w3.org/2000/svg" width="13" height="13" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M11 4H4a2 2 0 0 0-2 2v14a2 2 0 0 0 2 2h14a2 2 0 0 0 2-2v-7"></path><path d="M18.5 2.5a2.121 2.121 0 0 1 3 3L12 15l-4 1 1-4 9.5-9.5z"></path></svg>
                                            Editar
                                        </button>

                                        <?php if ($isSelf): ?>
                                            <button type="button" class="btn btn--danger btn--sm" disabled title="No puedes eliminar tu propia cuenta mientras la sesión esté activa" style="opacity:0.4; cursor:not-allowed;">
                                                <svg xmlns="http://www.w3.org/2000/svg" width="13" height="13" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><polyline points="3 6 5 6 21 6"></polyline><path d="M19 6v14a2 2 0 0 1-2 2H7a2 2 0 0 1-2-2V6m3 0V4a2 2 0 0 1 2-2h4a2 2 0 0 1 2 2v2"></path></svg>
                                                Eliminar
                                            </button>
                                        <?php else: ?>
                                            <form method="post" action="<?= e(url('employee')) ?>" style="display:inline" onsubmit="return confirm('¿Seguro que deseas eliminar al empleado &quot;<?= e(addslashes($emp['nombre'])) ?>&quot;? Esta acción no se puede deshacer.');">
                                                <input type="hidden" name="action" value="delete">
                                                <input type="hidden" name="id_empleado" value="<?= (int) $emp['id_empleado'] ?>">
                                                <button type="submit" class="btn btn--danger btn--sm" title="Eliminar empleado">
                                                    <svg xmlns="http://www.w3.org/2000/svg" width="13" height="13" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><polyline points="3 6 5 6 21 6"></polyline><path d="M19 6v14a2 2 0 0 1-2 2H7a2 2 0 0 1-2-2V6m3 0V4a2 2 0 0 1 2-2h4a2 2 0 0 1 2 2v2"></path></svg>
                                                    Eliminar
                                                </button>
                                            </form>
                                        <?php endif; ?>
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

<!-- Modal: Crear / Editar Empleado -->
<div class="modal-backdrop" id="modal-emp-backdrop" style="display:none;"></div>
<div class="modal-box" id="modal-emp-box" style="display:none;">
    <div class="modal-head">
        <h3 class="modal-title" id="emp-modal-title">
            <svg xmlns="http://www.w3.org/2000/svg" width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M20 21v-2a4 4 0 0 0-4-4H8a4 4 0 0 0-4 4v2"></path><circle cx="12" cy="7" r="4"></circle></svg>
            <span id="emp-modal-title-text">Nuevo Empleado</span>
        </h3>
        <button type="button" class="modal-close" id="modal-emp-close-btn" aria-label="Cerrar modal">
            <svg xmlns="http://www.w3.org/2000/svg" width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5" stroke-linecap="round" stroke-linejoin="round"><line x1="18" y1="6" x2="6" y2="18"></line><line x1="6" y1="6" x2="18" y2="18"></line></svg>
        </button>
    </div>

    <form method="post" action="<?= e(url('employee')) ?>" id="form-empleado">
        <input type="hidden" name="action" id="emp-action" value="create">
        <input type="hidden" name="id_empleado" id="emp-id" value="">

        <div class="modal-body">
            <div class="form-grid" style="gap:1rem;">
                <div class="form-group col-full">
                    <label for="emp-nombre">Nombre Completo <span style="color:var(--danger, #ef4444);">*</span></label>
                    <input id="emp-nombre" name="nombre" required maxlength="150" placeholder="Ej: Carlos Gómez, Daniela Moreno..." autocomplete="off">
                </div>

                <div class="form-group col-full">
                    <label for="emp-cargo">Cargo / Puesto <span class="optional">(opcional)</span></label>
                    <input id="emp-cargo" name="cargo" maxlength="100" placeholder="Ej: Barista, Cajero, Supervisor..." autocomplete="off">
                </div>

                <div class="col-full" style="margin-top:0.25rem; padding-top:0.75rem; border-top:1px solid var(--border-color);">
                    <h4 style="font-size:0.9rem; font-weight:600; margin:0 0 0.2rem 0; color:var(--accent);">Acceso al Sistema (Cuenta de Usuario)</h4>
                    <p style="font-size:0.8rem; color:var(--text-muted); margin:0 0 0.75rem 0;">Credenciales para que el empleado inicie sesión en Dolce Stock.</p>
                </div>

                <div class="form-group col-full">
                    <label for="emp-email">Correo Electrónico <span class="optional">(para login)</span></label>
                    <input id="emp-email" name="email" type="email" placeholder="ejemplo@dolcecafe.com" autocomplete="off">
                </div>

                <div class="form-group col-full">
                    <label for="emp-password">
                        Contraseña 
                        <span class="optional" id="emp-pass-hint">(mínimo 6 caracteres)</span>
                    </label>
                    <input id="emp-password" name="password" type="password" placeholder="••••••••" minlength="6" autocomplete="new-password">
                </div>

                <div class="form-group col-full">
                    <label for="emp-rol">Rol / Nivel de Permisos</label>
                    <select id="emp-rol" name="rol" class="form-select">
                        <option value="cajero">Cajero (Ventas y Facturación)</option>
                        <option value="admin">Administrador (Acceso Completo)</option>
                    </select>
                </div>
            </div>
        </div>

        <div class="modal-footer">
            <button type="button" class="btn btn--ghost" id="modal-emp-cancel-btn">Cancelar</button>
            <button type="submit" class="btn btn--primary" id="modal-emp-submit-btn">
                <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><polyline points="20 6 9 17 4 12"></polyline></svg>
                <span id="modal-emp-btn-text">Guardar Empleado</span>
            </button>
        </div>
    </form>
</div>

<script>
document.addEventListener('DOMContentLoaded', function () {
    const backdrop   = document.getElementById('modal-emp-backdrop');
    const modalBox   = document.getElementById('modal-emp-box');
    const form       = document.getElementById('form-empleado');
    const actionInp  = document.getElementById('emp-action');
    const idInp      = document.getElementById('emp-id');
    const nombreInp  = document.getElementById('emp-nombre');
    const cargoInp   = document.getElementById('emp-cargo');
    const emailInp   = document.getElementById('emp-email');
    const passInp    = document.getElementById('emp-password');
    const passHint   = document.getElementById('emp-pass-hint');
    const rolInp     = document.getElementById('emp-rol');
    const titleText  = document.getElementById('emp-modal-title-text');
    const submitText = document.getElementById('modal-emp-btn-text');

    function openModal() {
        backdrop.style.display = 'block';
        modalBox.style.display = 'block';
        document.body.style.overflow = 'hidden';
    }

    function closeModal() {
        backdrop.style.display = 'none';
        modalBox.style.display = 'none';
        document.body.style.overflow = '';
        if (window.location.search.indexOf('edit=') !== -1) {
            const cleanUrl = window.location.pathname + '?controller=employee';
            window.history.replaceState({}, document.title, cleanUrl);
        }
    }

    // Botón "+ Nuevo Empleado"
    const btnNuevo = document.getElementById('btn-nuevo-emp');
    if (btnNuevo) {
        btnNuevo.addEventListener('click', function () {
            form.reset();
            actionInp.value = 'create';
            idInp.value = '';
            titleText.textContent = 'Nuevo Empleado';
            submitText.textContent = 'Agregar Empleado';
            passHint.textContent = '(mínimo 6 caracteres)';
            passInp.removeAttribute('placeholder');
            passInp.placeholder = '••••••••';
            rolInp.value = 'cajero';
            openModal();
            setTimeout(() => nombreInp.focus(), 80);
        });
    }

    // Botones "Editar" de la tabla
    document.querySelectorAll('.btn-edit-emp').forEach(function (btn) {
        btn.addEventListener('click', function () {
            form.reset();
            actionInp.value = 'update';
            idInp.value = this.dataset.id || '';
            nombreInp.value = this.dataset.nombre || '';
            cargoInp.value = this.dataset.cargo || '';
            emailInp.value = this.dataset.email || '';
            passInp.value = '';
            passHint.textContent = '(dejar en blanco para conservar la actual)';
            passInp.placeholder = 'Conservar actual';
            rolInp.value = (this.dataset.rol === 'admin') ? 'admin' : 'cajero';
            titleText.textContent = 'Editar Empleado';
            submitText.textContent = 'Guardar Cambios';
            openModal();
            setTimeout(() => nombreInp.focus(), 80);
        });
    });

    // Cerrar modal con botones y backdrop
    const closeBtn = document.getElementById('modal-emp-close-btn');
    if (closeBtn) closeBtn.addEventListener('click', closeModal);

    const cancelBtn = document.getElementById('modal-emp-cancel-btn');
    if (cancelBtn) cancelBtn.addEventListener('click', closeModal);

    if (backdrop) backdrop.addEventListener('click', closeModal);

    // Cerrar con Escape
    document.addEventListener('keydown', function (e) {
        if (e.key === 'Escape' && modalBox.style.display === 'block') {
            closeModal();
        }
    });

    // Abrir automáticamente si vino con parámetro ?edit= desde URL
    <?php if ($row) : ?>
    (function() {
        actionInp.value = 'update';
        idInp.value = '<?= (int) $row['id_empleado'] ?>';
        nombreInp.value = <?= json_encode((string) ($row['nombre'] ?? '')) ?>;
        cargoInp.value = <?= json_encode((string) ($row['cargo'] ?? '')) ?>;
        emailInp.value = <?= json_encode((string) ($row['email'] ?? '')) ?>;
        passInp.value = '';
        passHint.textContent = '(dejar en blanco para conservar la actual)';
        passInp.placeholder = 'Conservar actual';
        rolInp.value = '<?= (($row['rol'] ?? '') === 'admin' || stripos($row['cargo'] ?? '', 'admin') !== false) ? 'admin' : 'cajero' ?>';
        titleText.textContent = 'Editar Empleado';
        submitText.textContent = 'Guardar Cambios';
        openModal();
    })();
    <?php endif; ?>
});
</script>
