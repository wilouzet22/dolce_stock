<?php
declare(strict_types=1);
namespace App\Controllers;
use App\Core\Controller;
use App\Models\AdminModel;
use RuntimeException;
use Throwable;
final class InventoryController extends Controller
{
    public function index(): void
    {
        $m = new AdminModel();
        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            $id = (int) ($_POST['id_producto'] ?? 0); $tipo = $_POST['tipo'] ?? ''; $cantidad = (int) ($_POST['cantidad'] ?? 0); $desc = trim((string) ($_POST['descripcion'] ?? ''));
            if ($id <= 0 || !in_array($tipo, ['ENTRADA', 'SALIDA'], true) || $cantidad <= 0) { flash('err', 'Datos inválidos para movimiento.'); $this->redirect('inventory'); }
            try { $m->registerMove($id, $tipo, $cantidad, $desc); flash('ok', 'Movimiento registrado y stock actualizado.'); } catch (Throwable $e) { flash('err', $e instanceof RuntimeException ? $e->getMessage() : 'No se pudo guardar el movimiento.'); }
            $this->redirect('inventory');
        }
        $this->render('inventory/index', ['pageTitle' => 'Inventario', 'productos' => $m->productsSimple(), 'lista' => $m->inventoryMoves()]);
    }
}
