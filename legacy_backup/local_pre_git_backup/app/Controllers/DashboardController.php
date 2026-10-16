<?php

declare(strict_types=1);

namespace App\Controllers;

use App\Core\Controller;
use App\Models\AdminModel;

final class DashboardController extends Controller
{
    public function index(): void
    {
        $model = new AdminModel();
        $this->render('dashboard/index', [
            'pageTitle'        => 'Resumen',
            'stats'            => $model->dashboardStats(),
            'ultimasFacturas'  => $model->latestInvoices(),
            'stockAlerta'      => $model->lowStock(),
            'salesHistory'     => $model->salesLast30Days(),
            'topProducts'      => $model->topSellingProducts(),
        ]);
    }
}
