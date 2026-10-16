<div class="card" style="max-width:760px;">
    <div class="card-head"><h2 class="card-title">Registrar venta</h2><a class="btn btn--ghost" href="<?= e(url('invoice')) ?>">Cancelar</a></div>
    <form method="post" action="<?= e(url('invoice', 'create')) ?>">
        <div class="form-grid" style="margin-bottom:1.25rem;">
            <div class="form-group"><label>Cliente</label><select name="id_cliente"><option value="">— Opcional —</option><?php foreach ($clientes as $c) : ?><option value="<?= (int) $c['id_cliente'] ?>"><?= e($c['nombre']) ?></option><?php endforeach; ?></select></div>
            <div class="form-group"><label>Empleado</label><select name="id_empleado"><option value="">— Opcional —</option><?php foreach ($empleados as $em) : ?><option value="<?= (int) $em['id_empleado'] ?>"><?= e($em['nombre']) ?></option><?php endforeach; ?></select></div>
        </div>
        <div class="card-head"><h3 class="card-title" style="font-size:1rem;">Ítems</h3><button type="button" class="btn btn--ghost" id="add-line">Añadir línea</button></div>
        <div id="lines">
            <div class="line-row form-grid" style="align-items:end;margin-bottom:.75rem;border-bottom:1px solid rgba(61,41,20,.08);padding-bottom:.75rem;">
                <div class="form-group" style="grid-column: span 2;"><label>Producto</label><select name="prod_id[]" required><option value="">— Seleccionar —</option><?php foreach ($productos as $p) : ?><option value="<?= (int) $p['id_producto'] ?>"><?= e($p['nombre']) ?> · $ <?= number_format((float) $p['precio'], 2, '.', ',') ?> (stock <?= (int) $p['stock'] ?>)</option><?php endforeach; ?></select></div>
                <div class="form-group"><label>Cantidad</label><input type="number" name="prod_qty[]" min="1" value="1" required></div>
                <div class="form-group"><label>&nbsp;</label><button type="button" class="btn btn--danger rm-line" style="width:100%">Quitar</button></div>
            </div>
        </div>
        <div class="form-actions"><button class="btn btn--primary">Confirmar venta</button></div>
    </form>
</div>
<template id="tpl-line"><div class="line-row form-grid" style="align-items:end;margin-bottom:.75rem;border-bottom:1px solid rgba(61,41,20,.08);padding-bottom:.75rem;"><div class="form-group" style="grid-column: span 2;"><label>Producto</label><select name="prod_id[]" required><option value="">— Seleccionar —</option><?php foreach ($productos as $p) : ?><option value="<?= (int) $p['id_producto'] ?>"><?= e($p['nombre']) ?> · $ <?= number_format((float) $p['precio'], 2, '.', ',') ?> (stock <?= (int) $p['stock'] ?>)</option><?php endforeach; ?></select></div><div class="form-group"><label>Cantidad</label><input type="number" name="prod_qty[]" min="1" value="1" required></div><div class="form-group"><label>&nbsp;</label><button type="button" class="btn btn--danger rm-line" style="width:100%">Quitar</button></div></div></template>
<script>
(() => { const c = document.getElementById('lines'); const t = document.getElementById('tpl-line'); const bind = (r) => r.querySelector('.rm-line').addEventListener('click', () => { if (c.querySelectorAll('.line-row').length > 1) r.remove(); }); c.querySelectorAll('.line-row').forEach(bind); document.getElementById('add-line').addEventListener('click', () => { const w = document.createElement('div'); w.innerHTML = t.innerHTML.trim(); const row = w.firstElementChild; c.appendChild(row); bind(row); }); })();
</script>
