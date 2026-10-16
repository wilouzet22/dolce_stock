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
            $a = (string) ($_POST['action'] ?? '');
            $id = (int) ($_POST['id_empleado'] ?? 0);

            $primerNombre = trim((string) ($_POST['primer_nombre'] ?? ''));
            $segundoNombre = trim((string) ($_POST['segundo_nombre'] ?? '')) ?: null;
            $primerApellido = trim((string) ($_POST['primer_apellido'] ?? ''));
            $segundoApellido = trim((string) ($_POST['segundo_apellido'] ?? '')) ?: null;

            if ($primerNombre !== '' || $primerApellido !== '') {
                $nombre = trim(preg_replace('/\s+/', ' ', implode(' ', array_filter([$primerNombre, $segundoNombre, $primerApellido, $segundoApellido]))));
            } else {
                $nombre = trim((string) ($_POST['nombre'] ?? ''));
            }

            $rol = (string) ($_POST['rol'] ?? 'cajero');
            if ($rol !== 'admin' && $rol !== 'cajero') {
                $rol = 'cajero';
            }
            $cargo = ($rol === 'admin') ? 'Administrador' : 'Cajero';
            $salario = null;
            $email = trim((string) ($_POST['email'] ?? ''));
            $password = (string) ($_POST['password'] ?? '');

            if ($a === 'create') {
                if ($primerNombre === '' || $primerApellido === '') {
                    flash('err', 'El primer nombre y el primer apellido son campos obligatorios.');
                    $this->redirect('employee');
                }
                if ($email === '') {
                    flash('err', 'El correo electrónico es un campo obligatorio para el acceso del empleado.');
                    $this->redirect('employee');
                }
                if (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
                    flash('err', 'El formato del correo electrónico no es válido.');
                    $this->redirect('employee');
                }
                if ($m->isEmailTaken($email)) {
                    flash('err', 'Ese correo electrónico ya está registrado.');
                    $this->redirect('employee');
                }
                if ($password === '') {
                    flash('err', 'La contraseña es un campo obligatorio para la cuenta del empleado.');
                    $this->redirect('employee');
                }
                if (strlen($password) < 6) {
                    flash('err', 'La contraseña debe tener al menos 6 caracteres.');
                    $this->redirect('employee');
                }

                $m->createEmployee($nombre, $primerNombre, $segundoNombre, $primerApellido, $segundoApellido, $cargo, $salario, $email, $password, $rol);
                flash('ok', 'Empleado y cuenta registrados exitosamente.');
                $this->redirect('employee');
            }

            if ($a === 'update' && $id > 0) {
                if ($primerNombre === '' || $primerApellido === '') {
                    flash('err', 'El primer nombre y el primer apellido son campos obligatorios.');
                    $this->redirect('employee', 'index', ['edit' => $id]);
                }
                if ($email === '') {
                    flash('err', 'El correo electrónico es un campo obligatorio.');
                    $this->redirect('employee', 'index', ['edit' => $id]);
                }
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

                $m->updateEmployee($id, $nombre, $primerNombre, $segundoNombre, $primerApellido, $segundoApellido, $cargo, $salario, $email, $password !== '' ? $password : null, $rol);
                if ($id === (int) ($_SESSION['empleado_id'] ?? 0)) {
                    $_SESSION['empleado_nombre'] = $nombre;
                    $_SESSION['empleado_email'] = $email;
                    $_SESSION['empleado_rol'] = $rol;
                }
                flash('ok', 'Empleado actualizado correctamente.');
                $this->redirect('employee');
            }

            if ($a === 'delete' && $id > 0) {
                if ($id === (int) ($_SESSION['empleado_id'] ?? 0)) {
                    flash('err', 'No puedes eliminar tu propia cuenta mientras mantienes la sesión activa.');
                    $this->redirect('employee');
                }
                try {
                    $m->deleteEmployee($id);
                    flash('ok', 'Empleado eliminado correctamente.');
                } catch (PDOException $e) {
                    flash('err', 'No se pudo eliminar el empleado: ' . $e->getMessage());
                }
                $this->redirect('employee');
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
