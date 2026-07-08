<?php
declare(strict_types=1);
namespace App\Controllers;
use App\Core\Controller;
use App\Models\AdminModel;
use PDOException;
final class ProductController extends Controller
{
    public function index(): void
    {
        $m = new AdminModel();
        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            $a = $_POST['action'] ?? ''; $id = (int) ($_POST['id_producto'] ?? 0);
            $nombre = trim((string) ($_POST['nombre'] ?? '')); $precio = (float) ($_POST['precio'] ?? 0); $stock = (int) ($_POST['stock'] ?? 0); $cat = (int) ($_POST['id_categorias'] ?? 0);
            if ($a === 'create' && $nombre !== '' && $cat > 0) { $m->createProduct($nombre, $precio, $stock, $cat); flash('ok', 'Producto creado.'); }
            if ($a === 'update' && $id > 0 && $nombre !== '' && $cat > 0) { $m->updateProduct($id, $nombre, $precio, $stock, $cat); flash('ok', 'Producto actualizado.'); }
            if ($a === 'delete' && $id > 0) { try { $m->deleteProduct($id); flash('ok', 'Producto eliminado.'); } catch (PDOException $e) { flash('err', 'No se puede eliminar: está en facturas o movimientos.'); } }
            $this->redirect('product');
        }
        $editId = (int) ($_GET['edit'] ?? 0);
        $this->render('products/index', ['pageTitle' => 'Productos', 'categorias' => $m->categories(), 'row' => $editId > 0 ? $m->oneById('productos', 'id_producto', $editId) : null, 'lista' => $m->products()]);
    }
}
