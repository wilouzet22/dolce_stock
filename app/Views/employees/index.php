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
                        <th>Colaborador / Cargo</th>
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

                                // Descomponer nombres si no están en campos separados
                                $pNom = (string) ($emp['primer_nombre'] ?? '');
                                $sNom = (string) ($emp['segundo_nombre'] ?? '');
                                $pApe = (string) ($emp['primer_apellido'] ?? '');
                                $sApe = (string) ($emp['segundo_apellido'] ?? '');

                                if ($pNom === '' && !empty($emp['nombre'])) {
                                    $parts = preg_split('/\s+/', trim((string) $emp['nombre']));
                                    if (count($parts) === 1) {
                                        $pNom = $parts[0];
                                    } elseif (count($parts) === 2) {
                                        $pNom = $parts[0];
                                        $pApe = $parts[1];
                                    } elseif (count($parts) === 3) {
                                        $pNom = $parts[0];
                                        $pApe = $parts[1];
                                        $sApe = $parts[2];
                                    } else {
                                        $pNom = $parts[0];
                                        $sNom = $parts[1];
                                        $pApe = $parts[2];
                                        $sApe = implode(' ', array_slice($parts, 3));
                                    }
                                }
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
                                                data-primer-nombre="<?= e($pNom) ?>"
                                                data-segundo-nombre="<?= e($sNom) ?>"
                                                data-primer-apellido="<?= e($pApe) ?>"
                                                data-segundo-apellido="<?= e($sApe) ?>"
                                                data-cargo="<?= e($emp['cargo'] ?? '') ?>"
                                                data-email="<?= e($emp['email'] ?? '') ?>"
                                                data-rol="<?= e($rolVal) ?>"
                                                title="Editar colaborador">
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

<!-- ── Recuadro Modal: Añadir / Editar Empleado ────────────────────────── -->
<div class="modal-backdrop" id="modal-emp-backdrop" style="display:none;"></div>
<div class="modal-box" id="modal-emp-box" style="display:none; max-width:620px;">
    <div class="modal-head">
        <h3 class="modal-title" id="emp-modal-title">
            <svg xmlns="http://www.w3.org/2000/svg" width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M20 21v-2a4 4 0 0 0-4-4H8a4 4 0 0 0-4 4v2"></path><circle cx="12" cy="7" r="4"></circle></svg>
            <span id="emp-modal-title-text">Añadir Nuevo Empleado</span>
        </h3>
        <button type="button" class="modal-close" id="modal-emp-close-btn" aria-label="Cerrar ventana">
            <svg xmlns="http://www.w3.org/2000/svg" width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5" stroke-linecap="round" stroke-linejoin="round"><line x1="18" y1="6" x2="6" y2="18"></line><line x1="6" y1="6" x2="18" y2="18"></line></svg>
        </button>
    </div>

    <form method="post" action="<?= e(url('employee')) ?>" id="form-empleado" novalidate>
        <input type="hidden" name="action" id="emp-action" value="create">
        <input type="hidden" name="id_empleado" id="emp-id" value="">

        <div class="modal-body" style="padding:1.5rem 1.75rem;">
            <div style="display:flex; flex-direction:column; gap:1.15rem;">
                
                <!-- Nombres -->
                <div class="form-grid--2">
                    <div class="form-group">
                        <label for="emp-primer-nombre">
                            Primer Nombre <span class="required-star">*</span>
                        </label>
                        <input id="emp-primer-nombre" name="primer_nombre" required maxlength="70" placeholder="Ej: Mariana, Carlos..." autocomplete="off">
                        <span class="field-error-msg" id="err-emp-primer-nombre">
                            <svg xmlns="http://www.w3.org/2000/svg" width="12" height="12" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5"><circle cx="12" cy="12" r="10"></circle><line x1="12" y1="8" x2="12" y2="12"></line><line x1="12" y1="16" x2="12.01" y2="16"></line></svg>
                            Campo obligatorio
                        </span>
                    </div>

                    <div class="form-group">
                        <label for="emp-segundo-nombre">
                            Segundo Nombre <span class="optional">(opcional)</span>
                        </label>
                        <input id="emp-segundo-nombre" name="segundo_nombre" maxlength="70" placeholder="Ej: Sofía, Alberto..." autocomplete="off">
                    </div>
                </div>

                <!-- Apellidos -->
                <div class="form-grid--2">
                    <div class="form-group">
                        <label for="emp-primer-apellido">
                            Primer Apellido <span class="required-star">*</span>
                        </label>
                        <input id="emp-primer-apellido" name="primer_apellido" required maxlength="70" placeholder="Ej: Perea, Gómez..." autocomplete="off">
                        <span class="field-error-msg" id="err-emp-primer-apellido">
                            <svg xmlns="http://www.w3.org/2000/svg" width="12" height="12" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5"><circle cx="12" cy="12" r="10"></circle><line x1="12" y1="8" x2="12" y2="12"></line><line x1="12" y1="16" x2="12.01" y2="16"></line></svg>
                            Campo obligatorio
                        </span>
                    </div>

                    <div class="form-group">
                        <label for="emp-segundo-apellido">
                            Segundo Apellido <span class="optional">(opcional)</span>
                        </label>
                        <input id="emp-segundo-apellido" name="segundo_apellido" maxlength="70" placeholder="Ej: Cano, Martínez..." autocomplete="off">
                    </div>
                </div>

                <!-- Cargo y Rol -->
                <div class="form-grid--2">
                    <div class="form-group">
                        <label for="emp-cargo">
                            Cargo / Puesto <span class="optional">(opcional)</span>
                        </label>
                        <input id="emp-cargo" name="cargo" maxlength="100" placeholder="Ej: Barista, Cajero, Administrador..." autocomplete="off">
                    </div>

                    <div class="form-group">
                        <label for="emp-rol">
                            Rol / Permisos del Sistema
                        </label>
                        <select id="emp-rol" name="rol" class="form-select">
                            <option value="cajero">Cajero (Ventas y Facturas)</option>
                            <option value="admin">Administrador (Acceso Completo)</option>
                        </select>
                    </div>
                </div>

                <!-- Separador de Credenciales -->
                <div style="padding-top:0.75rem; border-top:1px solid var(--border-color); margin-top:0.25rem;">
                    <div style="display:flex; align-items:center; gap:0.5rem; margin-bottom:0.25rem;">
                        <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="var(--accent)" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><rect x="3" y="11" width="18" height="11" rx="2" ry="2"></rect><path d="M7 11V7a5 5 0 0 1 10 0v4"></path></svg>
                        <h4 style="font-size:0.92rem; font-weight:700; margin:0; color:var(--text-main);">Credenciales de Ingreso a Empleados</h4>
                    </div>
                    <p style="font-size:0.8rem; color:var(--text-muted); margin:0 0 0.85rem 0;">Con este correo y contraseña el colaborador podrá iniciar sesión en el sistema.</p>
                </div>

                <!-- Correo Electrónico -->
                <div class="form-group">
                    <label for="emp-email">
                        Correo Electrónico <span class="required-star">*</span>
                    </label>
                    <input id="emp-email" name="email" type="email" required placeholder="ejemplo@dolcecafe.com" autocomplete="off">
                    <span class="field-error-msg" id="err-emp-email">
                        <svg xmlns="http://www.w3.org/2000/svg" width="12" height="12" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5"><circle cx="12" cy="12" r="10"></circle><line x1="12" y1="8" x2="12" y2="12"></line><line x1="12" y1="16" x2="12.01" y2="16"></line></svg>
                        El correo electrónico es un campo obligatorio
                    </span>
                </div>

                <!-- Contraseña -->
                <div class="form-group">
                    <label for="emp-password">
                        Contraseña <span class="required-star" id="emp-pass-star">*</span>
                        <span class="optional" id="emp-pass-hint">(mínimo 6 caracteres)</span>
                    </label>
                    <div class="password-input-wrapper">
                        <input id="emp-password" name="password" type="password" required minlength="6" placeholder="Mínimo 6 caracteres" autocomplete="new-password">
                        <button type="button" class="password-toggle-btn" id="btn-toggle-password" aria-label="Mostrar u ocultar contraseña">
                            <svg id="eye-icon" xmlns="http://www.w3.org/2000/svg" width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M1 12s4-8 11-8 11 8 11 8-4 8-11 8-11-8-11-8z"></path><circle cx="12" cy="12" r="3"></circle></svg>
                        </button>
                    </div>
                    <span class="field-error-msg" id="err-emp-password">
                        <svg xmlns="http://www.w3.org/2000/svg" width="12" height="12" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5"><circle cx="12" cy="12" r="10"></circle><line x1="12" y1="8" x2="12" y2="12"></line><line x1="12" y1="16" x2="12.01" y2="16"></line></svg>
                        La contraseña es un campo obligatorio
                    </span>
                </div>

            </div>
        </div>

        <div class="modal-footer">
            <button type="button" class="btn btn--ghost" id="modal-emp-cancel-btn">Cancelar</button>
            <button type="submit" class="btn btn--primary" id="modal-emp-submit-btn">
                <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><polyline points="20 6 9 17 4 12"></polyline></svg>
                <span id="modal-emp-btn-text">Añadir Empleado</span>
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
    const pNombreInp = document.getElementById('emp-primer-nombre');
    const sNombreInp = document.getElementById('emp-segundo-nombre');
    const pApeInp    = document.getElementById('emp-primer-apellido');
    const sApeInp    = document.getElementById('emp-segundo-apellido');
    const cargoInp   = document.getElementById('emp-cargo');
    const emailInp   = document.getElementById('emp-email');
    const passInp    = document.getElementById('emp-password');
    const passStar   = document.getElementById('emp-pass-star');
    const passHint   = document.getElementById('emp-pass-hint');
    const rolInp     = document.getElementById('emp-rol');
    const titleText  = document.getElementById('emp-modal-title-text');
    const submitText = document.getElementById('modal-emp-btn-text');

    // Mensajes de error
    const errPNombre = document.getElementById('err-emp-primer-nombre');
    const errPApe    = document.getElementById('err-emp-primer-apellido');
    const errEmail   = document.getElementById('err-emp-email');
    const errPass    = document.getElementById('err-emp-password');

    function clearErrors() {
        [pNombreInp, pApeInp, emailInp, passInp].forEach(input => {
            if (input) input.classList.remove('is-invalid');
        });
        [errPNombre, errPApe, errEmail, errPass].forEach(err => {
            if (err) err.classList.remove('is-visible');
        });
    }

    function showFieldError(input, errSpan, message) {
        if (input) input.classList.add('is-invalid');
        if (errSpan) {
            if (message) {
                errSpan.innerHTML = '<svg xmlns="http://www.w3.org/2000/svg" width="12" height="12" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5"><circle cx="12" cy="12" r="10"></circle><line x1="12" y1="8" x2="12" y2="12"></line><line x1="12" y1="16" x2="12.01" y2="16"></line></svg> ' + message;
            }
            errSpan.classList.add('is-visible');
        }
    }

    // Limpiar error al tipear
    [pNombreInp, pApeInp, emailInp, passInp].forEach(input => {
        if (input) {
            input.addEventListener('input', function() {
                this.classList.remove('is-invalid');
                const errSpan = document.getElementById('err-' + this.id);
                if (errSpan) errSpan.classList.remove('is-visible');
            });
        }
    });

    // Toggle ver contraseña
    const togglePassBtn = document.getElementById('btn-toggle-password');
    if (togglePassBtn) {
        togglePassBtn.addEventListener('click', function () {
            const isPass = passInp.getAttribute('type') === 'password';
            passInp.setAttribute('type', isPass ? 'text' : 'password');
            this.innerHTML = isPass 
                ? '<svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M17.94 17.94A10.07 10.07 0 0 1 12 20c-7 0-11-8-11-8a18.45 18.45 0 0 1 5.06-5.94M9.9 4.24A9.12 9.12 0 0 1 12 4c7 0 11 8 11 8a18.5 18.5 0 0 1-2.16 3.19m-6.72-1.07a3 3 0 1 1-4.24-4.24"></path><line x1="1" y1="1" x2="23" y2="23"></line></svg>'
                : '<svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M1 12s4-8 11-8 11 8 11 8-4 8-11 8-11-8-11-8z"></path><circle cx="12" cy="12" r="3"></circle></svg>';
        });
    }

    function openModal() {
        backdrop.style.display = 'block';
        modalBox.style.display = 'block';
        document.body.style.overflow = 'hidden';
    }

    function closeModal() {
        backdrop.style.display = 'none';
        modalBox.style.display = 'none';
        document.body.style.overflow = '';
        clearErrors();
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
            clearErrors();
            actionInp.value = 'create';
            idInp.value = '';
            titleText.textContent = 'Añadir Nuevo Empleado';
            submitText.textContent = 'Añadir Empleado';
            passStar.style.display = 'inline';
            passHint.textContent = '(mínimo 6 caracteres)';
            passInp.placeholder = 'Mínimo 6 caracteres';
            passInp.required = true;
            passInp.setAttribute('type', 'password');
            rolInp.value = 'cajero';
            openModal();
            setTimeout(() => pNombreInp.focus(), 80);
        });
    }

    // Botones "Editar" en la tabla
    document.querySelectorAll('.btn-edit-emp').forEach(function (btn) {
        btn.addEventListener('click', function () {
            form.reset();
            clearErrors();
            actionInp.value = 'update';
            idInp.value = this.dataset.id || '';
            pNombreInp.value = this.dataset.primerNombre || '';
            sNombreInp.value = this.dataset.segundoNombre || '';
            pApeInp.value = this.dataset.primerApellido || '';
            sApeInp.value = this.dataset.segundoApellido || '';
            cargoInp.value = this.dataset.cargo || '';
            emailInp.value = this.dataset.email || '';
            passInp.value = '';
            passStar.style.display = 'none';
            passHint.textContent = '(dejar en blanco para conservar la actual)';
            passInp.placeholder = 'Conservar contraseña actual';
            passInp.required = false;
            passInp.setAttribute('type', 'password');
            rolInp.value = (this.dataset.rol === 'admin') ? 'admin' : 'cajero';
            titleText.textContent = 'Editar Empleado';
            submitText.textContent = 'Guardar Cambios';
            openModal();
            setTimeout(() => pNombreInp.focus(), 80);
        });
    });

    // Validación estricta antes de enviar el formulario
    form.addEventListener('submit', function (e) {
        clearErrors();
        let hasErrors = false;
        let firstInvalid = null;

        const isCreate = actionInp.value === 'create';

        // 1. Primer Nombre
        if (!pNombreInp.value.trim()) {
            showFieldError(pNombreInp, errPNombre, 'El primer nombre es un campo obligatorio');
            hasErrors = true;
            if (!firstInvalid) firstInvalid = pNombreInp;
        }

        // 2. Primer Apellido
        if (!pApeInp.value.trim()) {
            showFieldError(pApeInp, errPApe, 'El primer apellido es un campo obligatorio');
            hasErrors = true;
            if (!firstInvalid) firstInvalid = pApeInp;
        }

        // 3. Correo Electrónico (Obligatorio)
        const emailVal = emailInp.value.trim();
        const emailRegex = /^[^\s@]+@[^\s@]+\.[^\s@]+$/;
        if (!emailVal) {
            showFieldError(emailInp, errEmail, 'El correo electrónico es un campo obligatorio');
            hasErrors = true;
            if (!firstInvalid) firstInvalid = emailInp;
        } else if (!emailRegex.test(emailVal)) {
            showFieldError(emailInp, errEmail, 'Ingrese un formato de correo válido (ej: nombre@correo.com)');
            hasErrors = true;
            if (!firstInvalid) firstInvalid = emailInp;
        }

        // 4. Contraseña (Obligatoria al crear)
        const passVal = passInp.value;
        if (isCreate) {
            if (!passVal) {
                showFieldError(passInp, errPass, 'La contraseña es un campo obligatorio');
                hasErrors = true;
                if (!firstInvalid) firstInvalid = passInp;
            } else if (passVal.length < 6) {
                showFieldError(passInp, errPass, 'La contraseña debe tener al menos 6 caracteres');
                hasErrors = true;
                if (!firstInvalid) firstInvalid = passInp;
            }
        } else {
            // En edición, si escribe algo, debe tener al menos 6 caracteres
            if (passVal && passVal.length < 6) {
                showFieldError(passInp, errPass, 'La nueva contraseña debe tener al menos 6 caracteres');
                hasErrors = true;
                if (!firstInvalid) firstInvalid = passInp;
            }
        }

        if (hasErrors) {
            e.preventDefault();
            if (firstInvalid) firstInvalid.focus();
        }
    });

    // Cerrar modal
    const closeBtn = document.getElementById('modal-emp-close-btn');
    if (closeBtn) closeBtn.addEventListener('click', closeModal);

    const cancelBtn = document.getElementById('modal-emp-cancel-btn');
    if (cancelBtn) cancelBtn.addEventListener('click', closeModal);

    if (backdrop) backdrop.addEventListener('click', closeModal);

    document.addEventListener('keydown', function (e) {
        if (e.key === 'Escape' && modalBox.style.display === 'block') {
            closeModal();
        }
    });

    // Si viene con ?edit= en la URL
    <?php if ($row) : ?>
    (function() {
        actionInp.value = 'update';
        idInp.value = '<?= (int) $row['id_empleado'] ?>';
        pNombreInp.value = <?= json_encode((string) ($row['primer_nombre'] ?? '')) ?>;
        sNombreInp.value = <?= json_encode((string) ($row['segundo_nombre'] ?? '')) ?>;
        pApeInp.value = <?= json_encode((string) ($row['primer_apellido'] ?? '')) ?>;
        sApeInp.value = <?= json_encode((string) ($row['segundo_apellido'] ?? '')) ?>;
        
        // Si estaban vacíos pero hay nombre completo
        if (!pNombreInp.value && !pApeInp.value) {
            const raw = <?= json_encode((string) ($row['nombre'] ?? '')) ?>;
            const pts = raw.trim().split(/\s+/);
            if (pts.length > 0) pNombreInp.value = pts[0];
            if (pts.length === 2) pApeInp.value = pts[1];
            if (pts.length === 3) { pApeInp.value = pts[1]; sApeInp.value = pts[2]; }
            if (pts.length >= 4) { sNombreInp.value = pts[1]; pApeInp.value = pts[2]; sApeInp.value = pts.slice(3).join(' '); }
        }

        cargoInp.value = <?= json_encode((string) ($row['cargo'] ?? '')) ?>;
        emailInp.value = <?= json_encode((string) ($row['email'] ?? '')) ?>;
        passInp.value = '';
        passStar.style.display = 'none';
        passHint.textContent = '(dejar en blanco para conservar la actual)';
        passInp.placeholder = 'Conservar contraseña actual';
        passInp.required = false;
        rolInp.value = '<?= (($row['rol'] ?? '') === 'admin' || stripos($row['cargo'] ?? '', 'admin') !== false) ? 'admin' : 'cajero' ?>';
        titleText.textContent = 'Editar Empleado';
        submitText.textContent = 'Guardar Cambios';
        openModal();
    })();
    <?php endif; ?>
});
</script>
