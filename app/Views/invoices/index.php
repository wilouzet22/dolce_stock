<div class="card">
    <div class="card-head"><h2 class="card-title">Ventas registradas</h2><a class="btn btn--primary" href="<?= e(url('invoice', 'create')) ?>">Nueva factura</a></div>
    <div class="table-wrap"><table class="data"><thead><tr><th>#</th><th>Fecha</th><th>Cliente</th><th>Atendió</th><th class="num">Total</th><th></th></tr></thead><tbody>
        <?php foreach ($lista as $f) : ?><tr><td><?= (int) $f['id_factura'] ?></td><td><?= e($f['fecha']) ?></td><td><?= e($f['cliente'] ?? '—') ?></td><td><?= e($f['empleado'] ?? '—') ?></td><td class="num">$ <?= number_format((float) $f['total'], 2, '.', ',') ?></td><td><a class="btn btn--ghost" style="padding:.35rem .7rem;font-size:.82rem" href="<?= e(url('invoice', 'show', ['id' => (int) $f['id_factura']])) ?>">Ver</a></td></tr><?php endforeach; ?>
    </tbody></table></div>
</div>
