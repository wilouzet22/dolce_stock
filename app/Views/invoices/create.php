<div class="card" style="max-width:860px; margin:0 auto;">
    <div class="card-head">
        <h2 class="card-title">Registrar nueva venta</h2>
        <a class="btn btn--ghost" href="<?= e(url('invoice')) ?>">Cancelar</a>
    </div>

    <form method="post" action="<?= e(url('invoice', 'create')) ?>" id="invoice-form">
        
        <!-- Sección de Información del Cliente -->
        <div class="card" style="background:var(--bg-surface-hover); border:1px solid var(--border-strong); border-radius:var(--radius-lg); padding:1.25rem; margin-bottom:1.5rem; box-shadow:none;">
            <h3 style="font-size:0.95rem; font-weight:700; color:var(--accent); margin:0 0 1rem 0; text-transform:uppercase; letter-spacing:0.05em; display:flex; align-items:center; gap:0.5rem;">
                <svg xmlns="http://www.w3.org/2000/svg" width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M20 21v-2a4 4 0 0 0-4-4H8a4 4 0 0 0-4 4v2"></path><circle cx="12" cy="7" r="4"></circle></svg>
                Datos del Cliente y Facturación
            </h3>

            <div class="form-grid" style="row-gap:1rem;">
                <div class="form-group col-full">
                    <label for="inv-cliente">Buscar / Seleccionar Cliente Guardado <span class="optional">(opcional)</span></label>
                    <select id="inv-cliente" name="id_cliente" class="form-select">
                        <option value="" data-identificacion="" data-nombre="" data-apellidos="" data-email="" data-direccion="">— Consumidor Final / Mostrador (O ingresar datos abajo) —</option>
                        <?php foreach ($clientes as $c) : ?>
                            <option value="<?= (int) $c['id_cliente'] ?>"
                                data-identificacion="<?= e($c['identificacion'] ?? '') ?>"
                                data-nombre="<?= e($c['nombre'] ?? '') ?>"
                                data-apellidos="<?= e($c['apellidos'] ?? '') ?>"
                                data-email="<?= e($c['email'] ?? '') ?>"
                                data-direccion="<?= e($c['direccion'] ?? '') ?>"
                            >
                                <?= e($c['nombre']) ?><?= !empty($c['apellidos']) ? ' ' . e($c['apellidos']) : '' ?><?= !empty($c['identificacion']) ? ' · CC/NIT: ' . e($c['identificacion']) : '' ?>
                            </option>
                        <?php endforeach; ?>
                    </select>
                </div>

                <div class="form-group">
                    <label for="cli-identificacion">Número de Identidad / CC / NIT</label>
                    <input id="cli-identificacion" name="cli_identificacion" placeholder="Ej: 1020304050" autocomplete="off">
                </div>

                <div class="form-group">
                    <label for="cli-nombre">Nombres</label>
                    <input id="cli-nombre" name="cli_nombre" placeholder="Ej: Juan Antonio" autocomplete="off">
                </div>

                <div class="form-group">
                    <label for="cli-apellidos">Apellidos <span class="optional">(opcional)</span></label>
                    <input id="cli-apellidos" name="cli_apellidos" placeholder="Ej: Pérez Gómez" autocomplete="off">
                </div>

                <div class="form-group">
                    <label for="cli-email">Correo Electrónico (Gmail, Hotmail, etc.)</label>
                    <input id="cli-email" name="cli_email" type="email" placeholder="cliente@correo.com" autocomplete="off">
                    <div style="margin-top:0.45rem;">
                        <label style="display:flex; align-items:center; gap:0.5rem; font-size:0.83rem; color:var(--text-main); cursor:pointer;">
                            <input type="checkbox" name="send_email_now" value="1" checked style="accent-color:var(--primary); width:16px; height:16px;">
                            <span>✉️ Enviar factura digital a este correo al emitir</span>
                        </label>
                        <small style="color:#10b981; font-size:0.78rem; display:block; margin-top:0.25rem;">
                            🟢 Envío automático activo: La factura llegará al cliente desde <strong>Café La Arábica</strong> (dolcestock1@gmail.com).
                        </small>
                    </div>
                </div>

                <div class="form-group col-full">
                    <label for="cli-direccion">Dirección de Residencia <span class="optional">(opcional)</span></label>
                    <input id="cli-direccion" name="cli_direccion" placeholder="Ej: Calle 10 # 45-20, Apto 301" autocomplete="off">
                </div>
            </div>
        </div>

        <div class="form-grid" style="margin-bottom:1.5rem;">
            <div class="form-group col-full">
                <label for="inv-empleado">Atendido por <span class="optional">(opcional)</span></label>
                <select id="inv-empleado" name="id_empleado">
                    <option value="">— Empleado Actual —</option>
                    <?php foreach ($empleados as $em) : ?>
                        <option value="<?= (int) $em['id_empleado'] ?>"><?= e($em['nombre']) ?> (<?= e($em['cargo'] ?? 'Caja') ?>)</option>
                    <?php endforeach; ?>
                </select>
            </div>
        </div>

        <!-- Ítems de la Venta -->
        <div class="card-head" style="margin-top:1.5rem; border-top:1px solid var(--border-color); padding-top:1.25rem;">
            <h3 class="card-title" style="font-size:1.1rem;">Ítems de la Venta</h3>
            <button type="button" class="btn btn--secondary btn--sm" id="add-line">
                <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><line x1="12" y1="5" x2="12" y2="19"></line><line x1="5" y1="12" x2="19" y2="12"></line></svg>
                Añadir producto
            </button>
        </div>

        <div id="lines" style="display:flex; flex-direction:column; gap:0.85rem; margin-bottom:1.5rem;">
            <div class="line-row" style="display:grid; grid-template-columns: minmax(0, 3fr) minmax(120px, 1fr) auto; gap:0.85rem; align-items:end; background:var(--bg-surface-hover); padding:0.85rem; border-radius:var(--radius-md); border:1px solid var(--border-color);">
                <div class="form-group" style="margin-bottom:0;">
                    <label>Producto</label>
                    <select name="prod_id[]" required>
                        <option value="">— Seleccionar Producto —</option>
                        <?php foreach ($productos as $p) : ?>
                            <option value="<?= (int) $p['id_producto'] ?>">
                                <?= e($p['nombre']) ?> · $ <?= number_format((float) $p['precio'], 2, '.', ',') ?> (Stock: <?= (int) $p['stock'] ?>)
                            </option>
                        <?php endforeach; ?>
                    </select>
                </div>
                <div class="form-group" style="margin-bottom:0;">
                    <label>Cantidad</label>
                    <div class="input-wrapper">
                        <input type="number" name="prod_qty[]" min="1" value="1" placeholder="1" required class="input-has-suffix">
                        <span class="input-suffix">und</span>
                    </div>
                </div>
                <div>
                    <button type="button" class="btn btn--ghost rm-line" title="Quitar ítem" style="color:var(--danger); height:44px; padding:0 0.85rem;">
                        <svg xmlns="http://www.w3.org/2000/svg" width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><polyline points="3 6 5 6 21 6"></polyline><path d="M19 6v14a2 2 0 0 1-2 2H7a2 2 0 0 1-2-2V6m3 0V4a2 2 0 0 1 2-2h4a2 2 0 0 1 2 2v2"></path></svg>
                    </button>
                </div>
            </div>
        </div>

        <div class="form-actions" style="justify-content:flex-end;">
            <button type="submit" class="btn btn--primary" style="padding:0.85rem 2rem; font-size:1rem;">
                <svg xmlns="http://www.w3.org/2000/svg" width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><polyline points="20 6 9 17 4 12"></polyline></svg>
                Confirmar y Emitir Factura
            </button>
        </div>
    </form>
</div>

<template id="tpl-line">
    <div class="line-row" style="display:grid; grid-template-columns: minmax(0, 3fr) minmax(120px, 1fr) auto; gap:0.85rem; align-items:end; background:var(--bg-surface-hover); padding:0.85rem; border-radius:var(--radius-md); border:1px solid var(--border-color);">
        <div class="form-group" style="margin-bottom:0;">
            <label>Producto</label>
            <select name="prod_id[]" required>
                <option value="">— Seleccionar Producto —</option>
                <?php foreach ($productos as $p) : ?>
                    <option value="<?= (int) $p['id_producto'] ?>">
                        <?= e($p['nombre']) ?> · $ <?= number_format((float) $p['precio'], 2, '.', ',') ?> (Stock: <?= (int) $p['stock'] ?>)
                    </option>
                <?php endforeach; ?>
            </select>
        </div>
        <div class="form-group" style="margin-bottom:0;">
            <label>Cantidad</label>
            <div class="input-wrapper">
                <input type="number" name="prod_qty[]" min="1" value="1" placeholder="1" required class="input-has-suffix">
                <span class="input-suffix">und</span>
            </div>
        </div>
        <div>
            <button type="button" class="btn btn--ghost rm-line" title="Quitar ítem" style="color:var(--danger); height:44px; padding:0 0.85rem;">
                <svg xmlns="http://www.w3.org/2000/svg" width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><polyline points="3 6 5 6 21 6"></polyline><path d="M19 6v14a2 2 0 0 1-2 2H7a2 2 0 0 1-2-2V6m3 0V4a2 2 0 0 1 2-2h4a2 2 0 0 1 2 2v2"></path></svg>
            </button>
        </div>
    </div>
</template>

<script>
(() => {
    // 1. Manejo de líneas de productos
    const c = document.getElementById('lines');
    const t = document.getElementById('tpl-line');
    const bind = (r) => {
        r.querySelector('.rm-line').addEventListener('click', () => {
            if (c.querySelectorAll('.line-row').length > 1) {
                r.remove();
            }
        });
    };
    c.querySelectorAll('.line-row').forEach(bind);
    document.getElementById('add-line').addEventListener('click', () => {
        const w = document.createElement('div');
        w.innerHTML = t.innerHTML.trim();
        const row = w.firstElementChild;
        c.appendChild(row);
        bind(row);
    });

    // 2. Autocompletado dinámico de datos de Cliente
    const selectCliente = document.getElementById('inv-cliente');
    const inputIdent = document.getElementById('cli-identificacion');
    const inputNombre = document.getElementById('cli-nombre');
    const inputApellidos = document.getElementById('cli-apellidos');
    const inputEmail = document.getElementById('cli-email');
    const inputDir = document.getElementById('cli-direccion');

    const fillClientData = (opt) => {
        if (!opt) return;
        inputIdent.value = opt.dataset.identificacion || '';
        inputNombre.value = opt.dataset.nombre || '';
        inputApellidos.value = opt.dataset.apellidos || '';
        inputEmail.value = opt.dataset.email || '';
        inputDir.value = opt.dataset.direccion || '';
    };

    selectCliente.addEventListener('change', () => {
        const opt = selectCliente.options[selectCliente.selectedIndex];
        fillClientData(opt);
    });

    // Autocompletado si escribe el número de identificación
    inputIdent.addEventListener('input', () => {
        const val = inputIdent.value.trim().toLowerCase();
        if (!val) return;

        for (let i = 1; i < selectCliente.options.length; i++) {
            const opt = selectCliente.options[i];
            const ident = (opt.dataset.identificacion || '').toLowerCase();
            if (ident && ident === val) {
                selectCliente.selectedIndex = i;
                fillClientData(opt);
                break;
            }
        }
    });
})();
</script>
