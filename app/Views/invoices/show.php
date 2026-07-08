<div class="card" style="max-width:680px;margin-bottom:1rem;">
    <div class="card-head">
        <h2 class="card-title"><?= e($pageTitle) ?></h2>
        <div class="row-actions">
            <a class="btn btn--primary" href="<?= e(url('invoice', 'ticket', ['id' => (int) $factura['id_factura']])) ?>" target="_blank">
                <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" style="margin-right:6px;"><polyline points="6 9 6 2 18 2 18 9"></polyline><path d="M6 18H4a2 2 0 0 1-2-2v-5a2 2 0 0 1 2-2h16a2 2 0 0 1 2 2v5a2 2 0 0 1-2 2h-2"></path><rect x="6" y="14" width="12" height="8"></rect></svg>
                Imprimir Ticket
            </a>
            <a class="btn btn--ghost" href="<?= e(url('invoice')) ?>">Volver</a>
        </div>
    </div>
    <div class="form-grid" style="grid-template-columns: repeat(auto-fit, minmax(160px, 1fr)); gap: 1.25rem; margin-top: 1rem; align-items: start;">
        <div>
            <label style="font-size: 0.8rem; font-weight: 700; color: var(--coffee-600); text-transform: uppercase; letter-spacing: 0.05em;">Fecha y Hora</label>
            <div style="font-size: 1.05rem; font-weight: 600; color: var(--coffee-950); margin-top: 0.35rem;"><?= e($factura['fecha']) ?></div>
        </div>
        <div>
            <label style="font-size: 0.8rem; font-weight: 700; color: var(--coffee-600); text-transform: uppercase; letter-spacing: 0.05em;">Cliente</label>
            <div style="font-size: 1.05rem; font-weight: 600; color: var(--coffee-950); margin-top: 0.35rem;"><?= e($factura['cliente'] ?? '—') ?></div>
        </div>
        <div>
            <label style="font-size: 0.8rem; font-weight: 700; color: var(--coffee-600); text-transform: uppercase; letter-spacing: 0.05em;">Empleado que Atendió</label>
            <div style="font-size: 1.05rem; font-weight: 600; color: var(--coffee-950); margin-top: 0.35rem;"><?= e($factura['empleado'] ?? '—') ?></div>
        </div>
    </div>
</div>
<div class="card">
    <div class="card-head"><h2 class="card-title">Detalle</h2><strong class="stat-value" style="font-size:1.25rem;">Total: $ <?= number_format((float) $factura['total'], 2, '.', ',') ?></strong></div>
    <div class="table-wrap"><table class="data"><thead><tr><th>Producto</th><th class="num">Cant.</th><th class="num">Precio</th><th class="num">Subtotal</th></tr></thead><tbody>
        <?php foreach ($detalles as $d) : ?><tr><td><?= e($d['producto']) ?></td><td class="num"><?= (int) $d['cantidad'] ?></td><td class="num">$ <?= number_format((float) $d['precio_unitario'], 2, '.', ',') ?></td><td class="num">$ <?= number_format((float) $d['subtotal'], 2, '.', ',') ?></td></tr><?php endforeach; ?>
    </tbody></table></div>
</div>
