<?php
declare(strict_types=1);
namespace App\Controllers;
use App\Core\Controller;
use App\Models\AdminModel;
use PDOException;
final class EmployeeController extends Controller
{
    public function index(): void
    {
        $m = new AdminModel();
        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            $a = $_POST['action'] ?? ''; $id = (int) ($_POST['id_empleado'] ?? 0);
            $nombre = trim((string) ($_POST['nombre'] ?? '')); $cargo = trim((string) ($_POST['cargo'] ?? '')) ?: null; $salario = ($_POST['salario'] ?? '') !== '' ? (float) $_POST['salario'] : null;
            if ($a === 'create' && $nombre !== '') { $m->createEmployee($nombre, $cargo, $salario); flash('ok', 'Empleado registrado.'); }
            if ($a === 'update' && $id > 0 && $nombre !== '') { $m->updateEmployee($id, $nombre, $cargo, $salario); flash('ok', 'Empleado actualizado.'); }
            if ($a === 'delete' && $id > 0) { try { $m->deleteEmployee($id); flash('ok', 'Empleado eliminado.'); } catch (PDOException $e) { flash('err', 'No se puede eliminar: figura en facturas.'); } }
            $this->redirect('employee');
        }
        $editId = (int) ($_GET['edit'] ?? 0);
        $this->render('employees/index', ['pageTitle' => 'Empleados', 'row' => $editId > 0 ? $m->oneById('empleados', 'id_empleado', $editId) : null, 'lista' => $m->all('empleados', 'nombre')]);
    }
}
