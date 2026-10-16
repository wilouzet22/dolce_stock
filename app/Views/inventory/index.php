<?php
$urlBase = e(url('inventory'));
$editId  = (int) ($_GET['edit'] ?? 0);
?>

<!-- ── Modal: Nuevo / Editar Producto ───────────────────────────────────── -->
<div class="modal-backdrop" id="modal-backdrop" style="display:none;"></div>
<div class="modal-box" id="modal-producto" style="display:none;">
    <div class="modal-head">
        <h3 class="modal-title" id="modal-titulo">Nuevo Producto</h3>
        <button type="button" class="modal-close" id="modal-close-btn" aria-label="Cerrar">
            <svg xmlns="http://www.w3.org/2000/svg" width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5" stroke-linecap="round" stroke-linejoin="round"><line x1="18" y1="6" x2="6" y2="18"/><line x1="6" y1="6" x2="18" y2="18"/></svg>
        </button>
    </div>
    <form method="post" action="<?= $urlBase ?>" id="form-producto">
        <input type="hidden" name="action" id="form-action" value="create">
        <input type="hidden" name="id_producto" id="form-id" value="">

        <div class="form-grid" style="padding:1.25rem 1.5rem 0;">
            <div class="form-group col-full">
                <label for="form-nombre">Nombre del Producto</label>
                <input id="form-nombre" name="nombre" required maxlength="150"
                       placeholder="Ej: Torta de Chocolate, Jugo de Mora...">
            </div>

            <div class="form-group col-full">
                <label for="form-cat">Categoría</label>
                <select id="form-cat" name="id_categorias" required>
                    <option value="">— Seleccionar —</option>
                    <?php foreach ($categorias as $cat) : ?>
                        <option value="<?= (int) $cat['id_categoria'] ?>"><?= e($cat['nombre']) ?></option>
                    <?php endforeach; ?>
                </select>
            </div>

            <div class="form-group">
                <label for="form-precio">Precio de Venta</label>
                <div class="input-wrapper">
                    <span class="input-prefix">$</span>
                    <input id="form-precio" class="input-has-prefix" type="number" step="0.01" min="0"
                           name="precio" placeholder="0.00" required>
                </div>
            </div>

            <div class="form-group">
                <label for="form-stock">Stock</label>
                <div class="input-wrapper">
                    <input id="form-stock" class="input-has-suffix" type="number" min="0"
                           name="stock" placeholder="0" required>
                    <span class="input-suffix">und</span>
                </div>
            </div>

            <div class="form-group col-full" id="aviso-duplicado" style="display:none;">
                <div class="flash flash--err" style="margin:0;padding:.6rem 1rem;border-radius:8px;">
                    ⚠️ <strong>Este producto ya existe.</strong> Si continúas, se sumará el stock ingresado al stock actual.
                </div>
            </div>
        </div>

        <div class="form-actions" style="padding:1rem 1.5rem 1.5rem;">
            <button type="submit" class="btn btn--primary" id="btn-guardar">
                <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><polyline points="20 6 9 17 4 12"/></svg>
                Guardar Producto
            </button>
            <button type="button" class="btn btn--ghost" id="btn-cancelar">Cancelar</button>
        </div>
    </form>
</div>

<!-- ── Encabezado de página ─────────────────────────────────────────────── -->
<div class="card" style="margin-bottom:1.25rem;">
    <div class="card-head" style="flex-wrap:wrap;gap:.75rem;">
        <div>
            <h2 class="card-title">Inventario de Productos</h2>
            <span id="contador-productos" style="font-size:.82rem;color:var(--text-muted);"><?= $total ?> producto<?= $total !== 1 ? 's' : '' ?> en total</span>
        </div>
        <div style="display:flex;gap:.75rem;align-items:center;flex-wrap:wrap;">
            <!-- Búsqueda en tiempo real -->
            <form method="get" action="<?= $urlBase ?>" style="display:flex;gap:.5rem;align-items:center;" id="form-busqueda" onsubmit="return false;">
                <div class="search-input-wrap">
                    <span class="search-icon">
                        <svg xmlns="http://www.w3.org/2000/svg" width="15" height="15" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5" stroke-linecap="round" stroke-linejoin="round"><circle cx="11" cy="11" r="8"/><line x1="21" y1="21" x2="16.65" y2="16.65"/></svg>
                    </span>
                    <input type="text" name="q" value="<?= e($search) ?>"
                           placeholder="Buscar producto o categoría..." autocomplete="off"
                           class="table-search" style="width:340px;padding-right:2.5rem;" id="input-busqueda">
                    <button type="button" id="btn-limpiar-busqueda" class="search-clear-btn"
                            style="position:absolute;right:.8rem;top:50%;transform:translateY(-50%);background:none;border:none;cursor:pointer;color:var(--text-muted);font-size:1rem;line-height:1;display:<?= $search !== '' ? 'block' : 'none' ?>;padding:.2rem;z-index:3;"
                            title="Limpiar búsqueda">✕</button>
                </div>
            </form>
            <!-- Botón nuevo -->
            <button type="button" class="btn btn--primary" id="btn-nuevo-producto">
                <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><line x1="12" y1="5" x2="12" y2="19"/><line x1="5" y1="12" x2="19" y2="12"/></svg>
                Nuevo Producto
            </button>
        </div>
    </div>
</div>

<!-- ── Tabla CRUD ────────────────────────────────────────────────────────── -->
<div class="card">
    <div class="table-wrap">
        <table class="data" id="tbl-inventario">
            <thead>
                <tr>
                    <th>Producto</th>
                    <th>Categoría</th>
                    <th class="num">Precio</th>
                    <th class="num">Stock</th>
                    <th style="text-align:right;width:200px;">Acciones</th>
                </tr>
            </thead>
            <tbody>
                <!-- Fila para cuando no hay resultados -->
                <tr id="fila-sin-resultados" style="display:<?= empty($lista) ? '' : 'none' ?>;">
                    <td colspan="5" style="text-align:center;color:var(--text-muted);padding:3rem;" id="celda-sin-resultados">
                        <?= $search !== '' ? "No se encontraron productos para «" . e($search) . "»." : 'No hay productos registrados aún.' ?>
                    </td>
                </tr>
                <?php if (!empty($lista)) : ?>
                    <?php foreach ($lista as $p) : ?>
                        <tr class="fila-producto"
                            data-nombre="<?= e(mb_strtolower($p['nombre'], 'UTF-8')) ?>"
                            data-cat="<?= e(mb_strtolower($p['categoria'] ?? '', 'UTF-8')) ?>">
                            <td><strong><?= e($p['nombre']) ?></strong></td>
                            <td>
                                <span class="badge" style="background:var(--accent-soft);color:var(--accent);">
                                    <?= e($p['categoria']) ?>
                                </span>
                            </td>
                            <td class="num">$ <?= number_format((float) $p['precio'], 2, '.', ',') ?></td>
                            <td class="num">
                                <?php $s = (int)$p['stock']; ?>
                                <span class="badge <?= $s <= 5 ? 'badge--danger' : ($s <= 15 ? 'badge--warning' : 'badge--success') ?>">
                                    <?= $s ?>
                                </span>
                            </td>
                            <td style="text-align:right;">
                                <div class="row-actions" style="justify-content:flex-end;gap:.4rem;">
                                    <!-- Agregar stock -->
                                    <button type="button" class="btn btn--ghost btn--sm btn-add-stock"
                                            data-id="<?= (int)$p['id_producto'] ?>"
                                            data-nombre="<?= e($p['nombre']) ?>"
                                            title="Agregar stock">
                                        <svg xmlns="http://www.w3.org/2000/svg" width="14" height="14" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5" stroke-linecap="round" stroke-linejoin="round"><line x1="12" y1="5" x2="12" y2="19"/><line x1="5" y1="12" x2="19" y2="12"/></svg>
                                        Stock
                                    </button>
                                    <!-- Editar -->
                                    <button type="button" class="btn btn--ghost btn--sm btn-editar"
                                            data-id="<?= (int)$p['id_producto'] ?>"
                                            data-nombre="<?= e($p['nombre']) ?>"
                                            data-precio="<?= (float)$p['precio'] ?>"
                                            data-stock="<?= (int)$p['stock'] ?>"
                                            data-cat="<?= (int)$p['id_categorias'] ?>">
                                        Editar
                                    </button>
                                    <!-- Eliminar -->
                                    <form method="post" action="<?= $urlBase ?>" style="display:inline"
                                          onsubmit="return confirm('¿Eliminar el producto «<?= e(addslashes($p['nombre'])) ?>»?');">
                                        <input type="hidden" name="action" value="delete">
                                        <input type="hidden" name="id_producto" value="<?= (int)$p['id_producto'] ?>">
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

<!-- ── Modal: Agregar Stock ─────────────────────────────────────────────── -->
<div class="modal-box" id="modal-stock" style="display:none;max-width:380px;">
    <div class="modal-head">
        <h3 class="modal-title">Agregar Stock</h3>
        <button type="button" class="modal-close" id="modal-stock-close" aria-label="Cerrar">
            <svg xmlns="http://www.w3.org/2000/svg" width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5" stroke-linecap="round" stroke-linejoin="round"><line x1="18" y1="6" x2="6" y2="18"/><line x1="6" y1="6" x2="18" y2="18"/></svg>
        </button>
    </div>
    <form method="post" action="<?= $urlBase ?>">
        <input type="hidden" name="action" value="add_stock">
        <input type="hidden" name="id_producto" id="stock-id" value="">
        <div class="form-grid" style="padding:1.25rem 1.5rem 0;">
            <div class="form-group col-full">
                <label>Producto</label>
                <p id="stock-nombre" style="font-weight:600;color:var(--text-main);margin:0;"></p>
            </div>
            <div class="form-group col-full">
                <label for="stock-cantidad">Cantidad a agregar</label>
                <div class="input-wrapper">
                    <input id="stock-cantidad" name="cantidad_agregar" type="number" min="1" value="1" required>
                    <span class="input-suffix">und</span>
                </div>
            </div>
        </div>
        <div class="form-actions" style="padding:1rem 1.5rem 1.5rem;">
            <button type="submit" class="btn btn--primary">
                <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><line x1="12" y1="5" x2="12" y2="19"/><line x1="5" y1="12" x2="19" y2="12"/></svg>
                Agregar
            </button>
            <button type="button" class="btn btn--ghost" id="btn-stock-cancelar">Cancelar</button>
        </div>
    </form>
</div>

<!-- ── Lista de productos para detección de duplicados ──────────────────── -->
<script>
const productosExistentes = <?= json_encode(array_map(fn($p) => strtolower(trim($p['nombre'])), $lista), JSON_UNESCAPED_UNICODE) ?>;
const todosLosNombres = <?= json_encode(array_map(fn($p) => ['id' => (int)$p['id_producto'], 'nombre' => $p['nombre'], 'precio' => (float)$p['precio'], 'stock' => (int)$p['stock'], 'cat' => (int)$p['id_categorias']], $lista), JSON_UNESCAPED_UNICODE) ?>;

document.addEventListener('DOMContentLoaded', function () {
    const backdrop      = document.getElementById('modal-backdrop');
    const modalProd     = document.getElementById('modal-producto');
    const modalStock    = document.getElementById('modal-stock');
    const formAccion    = document.getElementById('form-action');
    const formId        = document.getElementById('form-id');
    const formNombre    = document.getElementById('form-nombre');
    const formPrecio    = document.getElementById('form-precio');
    const formStock     = document.getElementById('form-stock');
    const formCat       = document.getElementById('form-cat');
    const modalTitulo   = document.getElementById('modal-titulo');
    const btnGuardar    = document.getElementById('btn-guardar');
    const avisoDup      = document.getElementById('aviso-duplicado');

    function openModal(modal) {
        backdrop.style.display = 'block';
        modal.style.display    = 'block';
        document.body.style.overflow = 'hidden';
    }
    function closeAll() {
        backdrop.style.display  = 'none';
        modalProd.style.display = 'none';
        modalStock.style.display = 'none';
        document.body.style.overflow = '';
    }

    // Abrir modal nuevo producto
    document.getElementById('btn-nuevo-producto').addEventListener('click', function () {
        modalTitulo.textContent  = 'Nuevo Producto';
        formAccion.value         = 'create';
        formId.value             = '';
        formNombre.value         = '';
        formPrecio.value         = '';
        formStock.value          = '';
        formCat.value            = '';
        avisoDup.style.display   = 'none';
        btnGuardar.textContent   = ' Guardar Producto';
        openModal(modalProd);
        setTimeout(() => formNombre.focus(), 100);
    });

    // Detectar duplicado al escribir nombre
    formNombre.addEventListener('input', function () {
        const val = this.value.trim().toLowerCase();
        const dup = val.length > 2 && productosExistentes.some(n => n === val);
        avisoDup.style.display = (dup && formAccion.value === 'create') ? 'block' : 'none';
    });

    // Abrir modal editar
    document.querySelectorAll('.btn-editar').forEach(btn => {
        btn.addEventListener('click', function () {
            modalTitulo.textContent = 'Editar Producto';
            formAccion.value        = 'update';
            formId.value            = this.dataset.id;
            formNombre.value        = this.dataset.nombre;
            formPrecio.value        = this.dataset.precio;
            formStock.value         = this.dataset.stock;
            formCat.value           = this.dataset.cat;
            avisoDup.style.display  = 'none';
            btnGuardar.textContent  = ' Guardar Cambios';
            openModal(modalProd);
        });
    });

    // Abrir modal agregar stock
    document.querySelectorAll('.btn-add-stock').forEach(btn => {
        btn.addEventListener('click', function () {
            document.getElementById('stock-id').value       = this.dataset.id;
            document.getElementById('stock-nombre').textContent = this.dataset.nombre;
            document.getElementById('stock-cantidad').value = 1;
            openModal(modalStock);
            setTimeout(() => document.getElementById('stock-cantidad').focus(), 100);
        });
    });

    // Cerrar modales
    document.getElementById('modal-close-btn').addEventListener('click', closeAll);
    document.getElementById('btn-cancelar').addEventListener('click', closeAll);
    document.getElementById('modal-stock-close').addEventListener('click', closeAll);
    document.getElementById('btn-stock-cancelar').addEventListener('click', closeAll);
    backdrop.addEventListener('click', closeAll);

    // ── Búsqueda instantánea en tiempo real sin recargar página ───────────
    const inputBusqueda     = document.getElementById('input-busqueda');
    const formBusqueda      = document.getElementById('form-busqueda');
    const btnLimpiar        = document.getElementById('btn-limpiar-busqueda');
    const filasProductos    = Array.from(document.querySelectorAll('.fila-producto'));
    const filaSinResultados = document.getElementById('fila-sin-resultados');
    const celdaSinResultados= document.getElementById('celda-sin-resultados');
    const contadorProductos = document.getElementById('contador-productos');
    const totalProductos    = filasProductos.length;

    function normalizarTexto(txt) {
        return (txt || '')
            .toString()
            .toLowerCase()
            .normalize('NFD')
            .replace(/[\u0300-\u036f]/g, '')
            .trim();
    }

    function filtrarProductos() {
        if (!inputBusqueda) return;
        const textoOriginal = inputBusqueda.value;
        const termino = normalizarTexto(textoOriginal);

        // Mostrar / ocultar botón limpiar
        if (btnLimpiar) {
            btnLimpiar.style.display = termino.length > 0 ? 'inline-flex' : 'none';
        }

        let visibles = 0;
        filasProductos.forEach(fila => {
            const nombre = normalizarTexto(fila.dataset.nombre || fila.cells[0]?.textContent || '');
            const cat    = normalizarTexto(fila.dataset.cat || fila.cells[1]?.textContent || '');
            const coincide = !termino || nombre.includes(termino) || cat.includes(termino);

            if (coincide) {
                fila.style.display = '';
                visibles++;
            } else {
                fila.style.display = 'none';
            }
        });

        // Mostrar aviso cuando no hay resultados
        if (filaSinResultados) {
            if (visibles === 0) {
                filaSinResultados.style.display = '';
                if (celdaSinResultados) {
                    celdaSinResultados.textContent = termino.length > 0
                        ? `No se encontraron productos que coincidan con «${textoOriginal.trim()}».`
                        : 'No hay productos registrados aún.';
                }
            } else {
                filaSinResultados.style.display = 'none';
            }
        }

        // Actualizar contador dinámicamente
        if (contadorProductos) {
            if (!termino) {
                contadorProductos.textContent = `${totalProductos} producto${totalProductos !== 1 ? 's' : ''} en total`;
            } else {
                contadorProductos.textContent = `Mostrando ${visibles} de ${totalProductos} producto${totalProductos !== 1 ? 's' : ''}`;
            }
        }
    }

    if (inputBusqueda) {
        inputBusqueda.addEventListener('input', filtrarProductos);
        inputBusqueda.addEventListener('keyup', filtrarProductos);
        inputBusqueda.addEventListener('search', filtrarProductos);

        if (formBusqueda) {
            formBusqueda.addEventListener('submit', function (e) {
                e.preventDefault();
                filtrarProductos();
            });
        }

        if (btnLimpiar) {
            btnLimpiar.addEventListener('click', function () {
                inputBusqueda.value = '';
                filtrarProductos();
                inputBusqueda.focus();
            });
        }

        // Filtrar si ya había texto al cargar la página
        if (inputBusqueda.value.trim() !== '') {
            filtrarProductos();
        }
    }
});
</script>

<style>
.search-clear-btn {
    position: absolute;
    right: 0.85rem;
    top: 50%;
    transform: translateY(-50%);
    background: transparent;
    border: none;
    cursor: pointer;
    color: var(--text-muted);
    font-size: 0.95rem;
    line-height: 1;
    padding: 0.25rem;
    border-radius: 50%;
    display: none;
    align-items: center;
    justify-content: center;
    transition: color 0.2s ease, transform 0.2s ease;
    z-index: 3;
}
.search-clear-btn:hover {
    color: var(--accent);
    transform: translateY(-50%) scale(1.15);
}
.modal-backdrop {
    position: fixed; inset: 0;
    background: rgba(0,0,0,.45);
    backdrop-filter: blur(3px);
    z-index: 900;
}
.modal-box {
    position: fixed;
    top: 50%; left: 50%;
    transform: translate(-50%, -50%);
    z-index: 901;
    background: var(--card-bg);
    border: 1px solid var(--border-color);
    border-radius: 16px;
    box-shadow: 0 24px 64px rgba(0,0,0,.25);
    width: 90%;
    max-width: 520px;
    animation: modal-in .18s ease;
}
@keyframes modal-in {
    from { opacity:0; transform:translate(-50%,-48%) scale(.97); }
    to   { opacity:1; transform:translate(-50%,-50%) scale(1); }
}
.modal-head {
    display: flex;
    align-items: center;
    justify-content: space-between;
    padding: 1.1rem 1.5rem;
    border-bottom: 1px solid var(--border-color);
}
.modal-title { font-size: 1.1rem; font-weight: 700; margin: 0; }
.modal-close {
    background: none; border: none; cursor: pointer;
    color: var(--text-muted); padding: .2rem;
    border-radius: 6px; transition: color .15s, background .15s;
}
.modal-close:hover { color: var(--text-main); background: var(--hover-bg, rgba(0,0,0,.06)); }
.badge--warning { background: #fef3c720; color: #d97706; }
</style>
