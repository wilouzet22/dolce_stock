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
        $m->ensureAuthColumns();

        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            $a = $_POST['action'] ?? '';
            $id = (int) ($_POST['id_empleado'] ?? 0);
            $nombre = trim((string) ($_POST['nombre'] ?? ''));
            $cargo = trim((string) ($_POST['cargo'] ?? '')) ?: null;
            $salario = null;
            $email = trim((string) ($_POST['email'] ?? '')) ?: null;
            $password = (string) ($_POST['password'] ?? '');
            $rol = (string) ($_POST['rol'] ?? 'cajero');

            if (!in_array($rol, ['admin', 'cajero'], true)) {
                $rol = 'cajero';
            }

            if ($a === 'create' && $nombre !== '') {
                if ($email !== null) {
                    if (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
                        flash('err', 'El formato del correo electrónico no es válido.');
                        $this->redirect('employee');
                    }
                    if ($m->isEmailTaken($email)) {
                        flash('err', 'Ese correo electrónico ya está registrado.');
                        $this->redirect('employee');
                    }
                    if ($password !== '' && strlen($password) < 6) {
                        flash('err', 'La contraseña debe tener al menos 6 caracteres.');
                        $this->redirect('employee');
                    }
                }
                $m->createEmployee($nombre, $cargo, $salario, $email, $password, $rol);
                flash('ok', 'Empleado y cuenta registrados correctamente.');
            }

            if ($a === 'update' && $id > 0 && $nombre !== '') {
                if ($email !== null) {
                    if (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
                        flash('err', 'El formato del correo electrónico no es válido.');
                        $this->redirect('employee', 'index', ['edit' => $id]);
                    }
                    if ($m->isEmailTaken($email, $id)) {
                        flash('err', 'Ese correo electrónico ya pertenece a otro empleado.');
                        $this->redirect('employee', 'index', ['edit' => $id]);
                    }
                    if ($password !== '' && strlen($password) < 6) {
                        flash('err', 'La contraseña debe tener al menos 6 caracteres.');
                        $this->redirect('employee', 'index', ['edit' => $id]);
                    }
                }
                $m->updateEmployee($id, $nombre, $cargo, $salario, $email, $password, $rol);
                if ($id === (int) ($_SESSION['empleado_id'] ?? 0)) {
                    $_SESSION['empleado_nombre'] = $nombre;
                    if ($email !== null) {
                        $_SESSION['empleado_email'] = $email;
                    }
                    $_SESSION['empleado_rol'] = $rol;
                }
                flash('ok', 'Empleado actualizado correctamente.');
            }

            if ($a === 'delete' && $id > 0) {
                if ($id === (int) ($_SESSION['empleado_id'] ?? 0)) {
                    flash('err', 'No puedes eliminar tu propia cuenta mientras mantienes la sesión activa.');
                    $this->redirect('employee');
                }
                try {
                    $m->deleteEmployee($id);
                    flash('ok', 'Empleado eliminado.');
                } catch (PDOException $e) {
                    flash('err', 'No se puede eliminar: figura en facturas u otros registros.');
                }
            }
            $this->redirect('employee');
        }

        $editId = (int) ($_GET['edit'] ?? 0);
        $this->render('employees/index', [
            'pageTitle' => 'Empleados y Permisos',
            'row' => $editId > 0 ? $m->oneById('empleados', 'id_empleado', $editId) : null,
            'lista' => $m->all('empleados', 'nombre')
        ]);
    }
}
