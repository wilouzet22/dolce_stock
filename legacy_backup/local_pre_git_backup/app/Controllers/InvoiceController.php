<?php
declare(strict_types=1);
namespace App\Controllers;
use App\Core\Controller;
use App\Models\AdminModel;
use RuntimeException;
use Throwable;

final class InvoiceController extends Controller
{
    public function index(): void
    {
        $m = new AdminModel();
        $this->render('invoices/index', ['pageTitle' => 'Facturas', 'lista' => $m->invoiceList()]);
    }

    public function create(): void
    {
        $m = new AdminModel();
        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            $idc = ($_POST['id_cliente'] ?? '') !== '' ? (int) $_POST['id_cliente'] : null;
            $ide = ($_POST['id_empleado'] ?? '') !== '' ? (int) $_POST['id_empleado'] : null;
            $pids = $_POST['prod_id'] ?? [];
            $qtys = $_POST['prod_qty'] ?? [];
            $lines = [];
            foreach ($pids as $i => $pid) {
                $pid = (int) $pid;
                $q = isset($qtys[$i]) ? (int) $qtys[$i] : 0;
                if ($pid > 0 && $q > 0) {
                    $lines[] = ['producto' => $pid, 'cantidad' => $q];
                }
            }
            try {
                $id = $m->createInvoice($idc, $ide, $lines);
                flash('ok', 'Factura registrada.');
                $this->redirect('invoice', 'show', ['id' => $id]);
            } catch (Throwable $e) {
                flash('err', $e instanceof RuntimeException ? $e->getMessage() : 'Error al guardar factura.');
                $this->redirect('invoice', 'create');
            }
        }
        $this->render('invoices/create', [
            'pageTitle' => 'Nueva factura',
            'clientes' => $m->all('clientes', 'nombre'),
            'empleados' => $m->all('empleados', 'nombre'),
            'productos' => $m->productsSimple(),
        ]);
    }

    public function show(): void
    {
        $id = (int) ($_GET['id'] ?? 0);
        $m = new AdminModel();
        $factura = $m->invoiceById($id);
        if (!$factura) {
            flash('err', 'Factura no encontrada.');
            $this->redirect('invoice');
        }
        $this->render('invoices/show', ['pageTitle' => 'Factura #' . $id, 'factura' => $factura, 'detalles' => $m->invoiceDetails($id)]);
    }

    public function ticket(): void
    {
        $id = (int) ($_GET['id'] ?? 0);
        $m = new AdminModel();
        $factura = $m->invoiceById($id);
        if (!$factura) {
            exit('Factura no encontrada.');
        }
        $detalles = $m->invoiceDetails($id);
        
        // Se requiere directamente la vista sin el layout de admin
        require dirname(__DIR__) . '/Views/invoices/ticket.php';
    }
}
