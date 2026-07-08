<div class="two-col">
    <div class="card"><div class="card-head"><h2 class="card-title">Registrar movimiento</h2></div>
        <form method="post" action="<?= e(url('inventory')) ?>">
            <div class="form-grid">
                <div class="form-group" style="grid-column:1/-1;"><label>Producto</label><select name="id_producto" required><option value="">— Seleccionar —</option><?php foreach ($productos as $pr) : ?><option value="<?= (int) $pr['id_producto'] ?>"><?= e($pr['nombre']) ?> (stock <?= (int) $pr['stock'] ?>)</option><?php endforeach; ?></select></div>
                <div class="form-group"><label>Tipo</label><select name="tipo"><option value="ENTRADA">Entrada</option><option value="SALIDA">Salida</option></select></div>
                <div class="form-group"><label>Cantidad</label><input type="number" min="1" name="cantidad" value="1" required></div>
                <div class="form-group" style="grid-column:1/-1;"><label>Descripción</label><textarea name="descripcion" maxlength="500"></textarea></div>
            </div>
            <div class="form-actions"><button class="btn btn--primary">Registrar</button></div>
        </form>
    </div>
    <div class="card"><div class="card-head"><h2 class="card-title">Historial</h2></div><div class="table-wrap"><table class="data"><thead><tr><th>Fecha</th><th>Producto</th><th>Tipo</th><th class="num">Cantidad</th><th>Nota</th></tr></thead><tbody>
        <?php foreach ($lista as $m) : ?><tr><td><?= e($m['fecha']) ?></td><td><?= e($m['producto']) ?></td><td><?= $m['tipo'] === 'ENTRADA' ? '<span class="badge badge--entrada">Entrada</span>' : '<span class="badge badge--salida">Salida</span>' ?></td><td class="num"><?= (int) $m['cantidad'] ?></td><td><?= e($m['descripcion'] ?? '') ?></td></tr><?php endforeach; ?>
    </tbody></table></div></div>
</div>
