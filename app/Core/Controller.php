<?php

declare(strict_types=1);

namespace App\Core;

abstract class Controller
{
    protected function render(string $view, array $data = []): void
    {
        extract($data);
        $viewsDir = dirname(__DIR__) . '/Views/';
        $viewFile = $viewsDir . $view . '.php';
        require $viewsDir . 'layouts/header.php';
        require $viewFile;
        require $viewsDir . 'layouts/footer.php';
    }

    protected function renderGuest(string $view, array $data = []): void
    {
        extract($data);
        $viewsDir = dirname(__DIR__) . '/Views/';
        require $viewsDir . 'layouts/guest_header.php';
        require $viewsDir . $view . '.php';
        require $viewsDir . 'layouts/guest_footer.php';
    }

    protected function redirect(string $controller, string $action = 'index', array $query = []): never
    {
        header('Location: ' . url($controller, $action, $query));
        exit;
    }
}
