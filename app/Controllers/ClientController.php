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
        $m->ensureClientColumns();

        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            $action = $_POST['action'] ?? '';
            $id = (int) ($_POST['id_cliente'] ?? 0);
            $nombre = trim((string) ($_POST['nombre'] ?? ''));
            $apellidos = trim((string) ($_POST['apellidos'] ?? ''));
            $identificacion = trim((string) ($_POST['identificacion'] ?? ''));
            $email = trim((string) ($_POST['email'] ?? ''));
            $direccion = trim((string) ($_POST['direccion'] ?? ''));

            if ($action === 'create' && $nombre !== '') {
                $m->createClient($nombre, $apellidos, $identificacion, $email, $direccion);
                flash('ok', 'Cliente creado.');
            }
            if ($action === 'update' && $id > 0 && $nombre !== '') {
                $m->updateClient($id, $nombre, $apellidos, $identificacion, $email, $direccion);
                flash('ok', 'Cliente actualizado.');
            }
            if ($action === 'delete' && $id > 0) {
                try {
                    $m->deleteClient($id);
                    flash('ok', 'Cliente eliminado.');
                } catch (PDOException $e) {
                    flash('err', 'No se puede eliminar: tiene facturas asociadas.');
                }
            }
            $this->redirect('client');
        }
        $editId = (int) ($_GET['edit'] ?? 0);
        $this->render('clients/index', [
            'pageTitle' => 'Clientes',
            'row' => $editId > 0 ? $m->oneById('clientes', 'id_cliente', $editId) : null,
            'lista' => $m->all('clientes', 'nombre')
        ]);
    }
}
