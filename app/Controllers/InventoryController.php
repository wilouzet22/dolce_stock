<?php
declare(strict_types=1);
namespace App\Controllers;
use App\Core\Controller;
use App\Models\AdminModel;
use PDOException;
use RuntimeException;
use Throwable;

final class InventoryController extends Controller
{
    public function index(): void
    {
        $m = new AdminModel();

        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            $action = $_POST['action'] ?? '';

            // ── Crear producto ──────────────────────────────────────────────
            if ($action === 'create') {
                $nombre = trim((string) ($_POST['nombre'] ?? ''));
                $precio = (float) ($_POST['precio'] ?? 0);
                $stock  = (int)   ($_POST['stock']  ?? 0);
                $cat    = (int)   ($_POST['id_categorias'] ?? 0);

                if ($nombre !== '' && $cat > 0) {
                    // ¿Ya existe? → sumar stock
                    $existe = $m->findProductByName($nombre);
                    if ($existe) {
                        $nuevoStock = (int)$existe['stock'] + $stock;
                        $m->updateProduct((int)$existe['id_producto'], $existe['nombre'], (float)$existe['precio'], $nuevoStock, (int)$existe['id_categorias']);
                        flash('ok', "El producto '{$nombre}' ya existía. Se sumaron {$stock} unidades al stock (total: {$nuevoStock}).");
                    } else {
                        $m->createProduct($nombre, $precio, $stock, $cat);
                        flash('ok', 'Producto creado correctamente.');
                    }
                } else {
                    flash('err', 'Nombre y categoría son obligatorios.');
                }
                $this->redirect('inventory');
            }

            // ── Actualizar producto ─────────────────────────────────────────
            if ($action === 'update') {
                $id     = (int)   ($_POST['id_producto']  ?? 0);
                $nombre = trim((string) ($_POST['nombre'] ?? ''));
                $precio = (float) ($_POST['precio'] ?? 0);
                $stock  = (int)   ($_POST['stock']  ?? 0);
                $cat    = (int)   ($_POST['id_categorias'] ?? 0);

                if ($id > 0 && $nombre !== '' && $cat > 0) {
                    $m->updateProduct($id, $nombre, $precio, $stock, $cat);
                    flash('ok', 'Producto actualizado.');
                } else {
                    flash('err', 'Datos inválidos.');
                }
                $this->redirect('inventory');
            }

            // ── Eliminar producto ───────────────────────────────────────────
            if ($action === 'delete') {
                $id = (int) ($_POST['id_producto'] ?? 0);
                if ($id > 0) {
                    try {
                        $m->deleteProduct($id);
                        flash('ok', 'Producto eliminado.');
                    } catch (PDOException $e) {
                        flash('err', 'No se puede eliminar: el producto tiene movimientos o facturas asociadas.');
                    }
                }
                $this->redirect('inventory');
            }

            // ── Sumar stock manualmente ─────────────────────────────────────
            if ($action === 'add_stock') {
                $id      = (int) ($_POST['id_producto'] ?? 0);
                $agregar = (int) ($_POST['cantidad_agregar'] ?? 0);
                if ($id > 0 && $agregar > 0) {
                    try {
                        $m->registerMove($id, 'ENTRADA', $agregar, 'Ingreso manual de stock');
                        flash('ok', "Se agregaron {$agregar} unidades al stock.");
                    } catch (Throwable $e) {
                        flash('err', $e instanceof RuntimeException ? $e->getMessage() : 'Error al agregar stock.');
                    }
                }
                $this->redirect('inventory');
            }
        }

        // ── Listado completo de productos (sin paginación de números) ────
        $search = trim((string) ($_GET['q'] ?? ''));
        $editId = (int) ($_GET['edit'] ?? 0);

        $lista  = $m->allProducts();
        $total  = count($lista);
        $row    = $editId > 0 ? $m->oneById('productos', 'id_producto', $editId) : null;

        $this->render('inventory/index', [
            'pageTitle'  => 'Inventario',
            'lista'      => $lista,
            'categorias' => $m->categories(),
            'row'        => $row,
            'total'      => $total,
            'search'     => $search,
        ]);
    }
}
