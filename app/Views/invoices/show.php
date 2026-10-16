<div class="card" style="max-width:760px; margin-bottom:1.5rem;">
    <div class="card-head">
        <h2 class="card-title"><?= e($pageTitle) ?></h2>
        <div class="row-actions">
            <button type="button" class="btn btn--secondary" onclick="toggleMailForm()" style="background:#0284c7; color:#ffffff; border:none;">
                <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" style="margin-right:6px;"><path d="M4 4h16c1.1 0 2 .9 2 2v12c0 1.1-.9 2-2 2H4c-1.1 0-2-.9-2-2V6c0-1.1.9-2 2-2z"></path><polyline points="22,6 12,13 2,6"></polyline></svg>
                Enviar por Correo
            </button>
            <a class="btn btn--primary" href="<?= e(url('invoice', 'ticket', ['id' => (int) $factura['id_factura']])) ?>" target="_blank">
                <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" style="margin-right:6px;"><polyline points="6 9 6 2 18 2 18 9"></polyline><path d="M6 18H4a2 2 0 0 1-2-2v-5a2 2 0 0 1 2-2h16a2 2 0 0 1 2 2v5a2 2 0 0 1-2 2h-2"></path><rect x="6" y="14" width="12" height="8"></rect></svg>
                Imprimir Ticket
            </a>
            <a class="btn btn--ghost" href="<?= e(url('invoice')) ?>">Volver</a>
        </div>
    </div>

    <!-- Formulario rápido para envío por correo -->
    <form method="post" action="<?= e(url('invoice', 'sendMail')) ?>" id="mail-send-form" style="display:none; background:var(--bg-surface-hover); border:1px solid var(--border-strong); border-radius:var(--radius-md); padding:1rem; margin-top:1rem;">
        <input type="hidden" name="id" value="<?= (int) $factura['id_factura'] ?>">
        <div style="display:flex; gap:0.75rem; align-items:flex-end; flex-wrap:wrap;">
            <div style="flex:1; min-width:240px;">
                <label style="font-size:0.8rem; font-weight:700; color:var(--text-muted); display:block; margin-bottom:0.35rem;">Correo del Cliente / Destinatario:</label>
                <input type="email" name="email" value="<?= e($factura['cliente_email'] ?? '') ?>" placeholder="cliente@correo.com" required style="width:100%;">
            </div>
            <div>
                <button type="submit" class="btn btn--primary" style="height:42px;">
                    ✉️ Enviar Factura Digital
                </button>
            </div>
        </div>
    </form>
    
    <div class="form-grid" style="grid-template-columns: repeat(auto-fit, minmax(200px, 1fr)); gap: 1.25rem; margin-top: 1rem; align-items: start;">
        <div>
            <label style="font-size: 0.78rem; font-weight: 700; color: var(--text-muted); text-transform: uppercase; letter-spacing: 0.05em;">Fecha y Hora</label>
            <div style="font-size: 1.05rem; font-weight: 600; color: var(--text-main); margin-top: 0.25rem;"><?= e($factura['fecha']) ?></div>
        </div>
        <div>
            <label style="font-size: 0.78rem; font-weight: 700; color: var(--text-muted); text-transform: uppercase; letter-spacing: 0.05em;">Cliente</label>
            <div style="font-size: 1.05rem; font-weight: 700; color: var(--text-main); margin-top: 0.25rem;">
                <?= e($factura['cliente'] ?? 'Consumidor Final') ?><?= !empty($factura['cliente_apellidos']) ? ' ' . e($factura['cliente_apellidos']) : '' ?>
            </div>
        </div>
        <div>
            <label style="font-size: 0.78rem; font-weight: 700; color: var(--text-muted); text-transform: uppercase; letter-spacing: 0.05em;">Identificación / CC / NIT</label>
            <div style="font-size: 1rem; font-weight: 600; color: var(--text-main); margin-top: 0.25rem;"><?= e($factura['cliente_identificacion'] ?? '—') ?></div>
        </div>
        <div>
            <label style="font-size: 0.78rem; font-weight: 700; color: var(--text-muted); text-transform: uppercase; letter-spacing: 0.05em;">Correo Electrónico</label>
            <div style="font-size: 0.95rem; font-weight: 600; color: var(--accent); margin-top: 0.25rem;"><?= e($factura['cliente_email'] ?? '—') ?></div>
        </div>
        <div>
            <label style="font-size: 0.78rem; font-weight: 700; color: var(--text-muted); text-transform: uppercase; letter-spacing: 0.05em;">Dirección de Residencia</label>
            <div style="font-size: 0.95rem; font-weight: 600; color: var(--text-main); margin-top: 0.25rem;"><?= e($factura['cliente_direccion'] ?? '—') ?></div>
        </div>
        <div>
            <label style="font-size: 0.78rem; font-weight: 700; color: var(--text-muted); text-transform: uppercase; letter-spacing: 0.05em;">Atendido por</label>
            <div style="font-size: 1rem; font-weight: 600; color: var(--text-main); margin-top: 0.25rem;"><?= e($factura['empleado'] ?? 'Caja') ?></div>
        </div>
    </div>
</div>

<div class="card">
    <div class="card-head">
        <h2 class="card-title">Detalle de Productos</h2>
        <strong class="stat-value" style="font-size:1.35rem; color:var(--accent);">Total: $ <?= number_format((float) $factura['total'], 2, '.', ',') ?></strong>
    </div>
    <div class="table-wrap">
        <table class="data">
            <thead>
                <tr>
                    <th>Producto</th>
                    <th class="num">Cantidad</th>
                    <th class="num">Precio Unitario</th>
                    <th class="num">Subtotal</th>
                </tr>
            </thead>
            <tbody>
                <?php foreach ($detalles as $d) : ?>
                    <tr>
                        <td><strong><?= e($d['producto']) ?></strong></td>
                        <td class="num"><?= (int) $d['cantidad'] ?> und</td>
                        <td class="num">$ <?= number_format((float) $d['precio_unitario'], 2, '.', ',') ?></td>
                        <td class="num"><strong>$ <?= number_format((float) $d['subtotal'], 2, '.', ',') ?></strong></td>
                    </tr>
                <?php endforeach; ?>
            </tbody>
        </table>
    </div>
</div>

<script>
function toggleMailForm() {
    const f = document.getElementById('mail-send-form');
    if (f.style.display === 'none' || f.style.display === '') {
        f.style.display = 'block';
    } else {
        f.style.display = 'none';
    }
}
</script>

