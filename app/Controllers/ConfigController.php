<?php

declare(strict_types=1);

namespace App\Controllers;

use App\Core\Controller;

final class ConfigController extends Controller
{
    public function mail(): void
    {
        $configFile = dirname(__DIR__, 2) . '/config/mail.php';
        $config = file_exists($configFile) ? (include $configFile) : [];
        if (!is_array($config)) {
            $config = [];
        }

        // Bloqueo total de modificaciones desde la interfaz web
        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            flash('err', '🔒 La configuración de correo está protegida y bloqueada a nivel de código fuente. No se puede modificar desde el navegador.');
            $this->redirect('dashboard');
        }

        $this->render('config/mail', [
            'pageTitle' => 'Servicio de Correo (Bloqueado por Seguridad)',
            'config'    => $config,
        ]);
    }
}
