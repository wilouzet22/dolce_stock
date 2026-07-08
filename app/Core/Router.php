<?php

declare(strict_types=1);

namespace App\Core;

final class Router
{
    public function dispatch(): void
    {
        $slug = strtolower((string) ($_GET['controller'] ?? 'dashboard'));
        $actionSlug = strtolower((string) ($_GET['action'] ?? 'index'));
        $empleadoId = isset($_SESSION['empleado_id']) ? (int) $_SESSION['empleado_id'] : 0;

        $guestAuthActions = ['login', 'register', 'logout', 'index'];
        $guestOk = $slug === 'auth' && in_array($actionSlug, $guestAuthActions, true);

        if ($empleadoId <= 0 && !$guestOk) {
            header('Location: ' . url('auth', 'login'));
            exit;
        }

        if ($empleadoId > 0 && $slug === 'auth' && in_array($actionSlug, ['login', 'index'], true)) {
            header('Location: ' . url('dashboard'));
            exit;
        }

        // RBAC: Restricción de acceso para Cajeros
        $rol = $_SESSION['empleado_rol'] ?? 'cajero';
        $cajeroPermitted = ['invoice', 'client', 'auth'];
        if ($empleadoId > 0 && $rol === 'cajero' && !in_array($slug, $cajeroPermitted, true)) {
            flash('err', 'Acceso denegado: No tienes permisos para ver esta sección.');
            header('Location: ' . url('invoice'));
            exit;
        }

        $controllerName = ucfirst(strtolower((string) ($_GET['controller'] ?? 'dashboard'))) . 'Controller';
        $action = strtolower((string) ($_GET['action'] ?? 'index'));

        $controllerClass = 'App\\Controllers\\' . $controllerName;
        if (!class_exists($controllerClass)) {
            http_response_code(404);
            exit('Controlador no encontrado');
        }

        $controller = new $controllerClass();
        if (!method_exists($controller, $action)) {
            http_response_code(404);
            exit('Acción no encontrada');
        }

        $controller->$action();
    }
}
