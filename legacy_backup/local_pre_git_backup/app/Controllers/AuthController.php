<?php

declare(strict_types=1);

namespace App\Controllers;

use App\Core\Controller;
use App\Models\EmployeeAuthModel;
use RuntimeException;
use Throwable;

final class AuthController extends Controller
{
    public function index(): never
    {
        $this->redirect('auth', 'login');
    }

    public function login(): void
    {
        $auth = new EmployeeAuthModel();

        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            if (!$auth->authTablesReady()) {
                flash('err', 'Falta ejecutar la migración: importá sql/empleados_auth.sql en la base dolce_stock.');
                $this->redirect('auth', 'login');
            }

            $email = trim((string) ($_POST['email'] ?? ''));
            $password = (string) ($_POST['password'] ?? '');
            $row = $auth->verifyLogin($email, $password);
            if ($row === null) {
                flash('err', 'Correo o contraseña incorrectos.');
                $this->redirect('auth', 'login');
            }

            $_SESSION['empleado_id'] = (int) $row['id_empleado'];
            $_SESSION['empleado_nombre'] = (string) $row['nombre'];
            $_SESSION['empleado_email'] = (string) ($row['email'] ?? '');
            $_SESSION['empleado_rol'] = (string) ($row['rol'] ?? 'cajero');
            session_regenerate_id(true);
            flash('ok', 'Bienvenido/a.');
            $this->redirect('dashboard');
        }

        $this->renderGuest('auth/login', [
            'pageTitle' => 'Iniciar sesión',
            'authReady' => $auth->authTablesReady(),
        ]);
    }

    public function register(): void
    {
        $auth = new EmployeeAuthModel();
        
        $authReady = $auth->authTablesReady();
        $adminsCount = $authReady ? $auth->countAdmins() : 0;
        $isAdmin = isset($_SESSION['empleado_rol']) && $_SESSION['empleado_rol'] === 'admin';
        
        // Restricción: Si ya existen administradores, solo un administrador logueado puede registrar.
        if ($authReady && $adminsCount > 0 && !$isAdmin) {
            flash('err', 'El registro está restringido. Solo los administradores pueden crear nuevas cuentas.');
            $this->redirect('auth', 'login');
        }

        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            try {
                if (!$authReady) {
                    flash('err', 'Falta ejecutar la migración: importá sql/empleados_roles.sql en la base dolce_stock.');
                    $this->redirect('auth', 'register');
                }
                $nombre = (string) ($_POST['nombre'] ?? '');
                $cargo = ($_POST['cargo'] ?? '') !== '' ? trim((string) $_POST['cargo']) : null;
                $email = (string) ($_POST['email'] ?? '');
                $password = (string) ($_POST['password'] ?? '');
                $confirm = (string) ($_POST['password_confirm'] ?? '');
                $rol = (string) ($_POST['rol'] ?? 'cajero');

                if ($password !== $confirm) {
                    flash('err', 'Las contraseñas no coinciden.');
                    $this->redirect('auth', 'register');
                }

                // Forzar 'admin' si es el primer usuario, sino verificar si el seleccionador es válido
                if ($adminsCount === 0) {
                    $rol = 'admin';
                } elseif (!in_array($rol, ['admin', 'cajero'], true)) {
                    $rol = 'cajero';
                }

                $auth->register($nombre, $cargo, $email, $password, $rol);
                flash('ok', 'Cuenta creada con éxito.');
                
                // Si el admin está logueado creando una cuenta, redirigir al panel. Sino, al login.
                if ($isAdmin) {
                    $this->redirect('dashboard');
                } else {
                    $this->redirect('auth', 'login');
                }
            } catch (RuntimeException $e) {
                flash('err', $e->getMessage());
                $this->redirect('auth', 'register');
            } catch (Throwable) {
                flash('err', 'No se pudo registrar.');
                $this->redirect('auth', 'register');
            }
        }

        // Si es admin logueado, usar el layout interno en vez del guest
        if ($isAdmin) {
            $this->render('auth/register', [
                'pageTitle' => 'Registrar nuevo empleado',
                'authReady' => $authReady,
                'adminsCount' => $adminsCount,
                'isAdmin' => $isAdmin,
            ]);
        } else {
            $this->renderGuest('auth/register', [
                'pageTitle' => 'Registro de empleado',
                'authReady' => $authReady,
                'adminsCount' => $adminsCount,
                'isAdmin' => $isAdmin,
            ]);
        }
    }

    public function logout(): never
    {
        unset($_SESSION['empleado_id'], $_SESSION['empleado_nombre'], $_SESSION['empleado_email']);
        session_regenerate_id(true);
        flash('ok', 'Sesión cerrada.');
        header('Location: ' . url('auth', 'login'));
        exit;
    }
}
