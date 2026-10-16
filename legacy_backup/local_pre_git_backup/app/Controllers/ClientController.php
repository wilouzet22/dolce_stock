<?php
declare(strict_types=1);
namespace App\Controllers;
use App\Core\Controller;
use App\Models\AdminModel;
use PDOException;

final class ClientController extends Controller
{
    public function index(): void
    {
        $m = new AdminModel();
        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            $action = $_POST['action'] ?? '';
            $id = (int) ($_POST['id_cliente'] ?? 0);
            $nombre = trim((string) ($_POST['nombre'] ?? ''));
            if ($action === 'create' && $nombre !== '') { $m->createClient($nombre); flash('ok', 'Cliente creado.'); }
            if ($action === 'update' && $id > 0 && $nombre !== '') { $m->updateClient($id, $nombre); flash('ok', 'Cliente actualizado.'); }
            if ($action === 'delete' && $id > 0) { try { $m->deleteClient($id); flash('ok', 'Cliente eliminado.'); } catch (PDOException $e) { flash('err', 'No se puede eliminar: tiene facturas asociadas.'); } }
            $this->redirect('client');
        }
        $editId = (int) ($_GET['edit'] ?? 0);
        $this->render('clients/index', ['pageTitle' => 'Clientes', 'row' => $editId > 0 ? $m->oneById('clientes', 'id_cliente', $editId) : null, 'lista' => $m->all('clientes', 'nombre')]);
    }
}
