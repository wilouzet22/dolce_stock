<?php

declare(strict_types=1);

namespace App\Controllers;

use App\Core\Controller;
use App\Models\AdminModel;
use PDOException;

final class CategoryController extends Controller
{
    public function index(): void
    {
        $m = new AdminModel();
        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            $action = $_POST['action'] ?? '';
            if ($action === 'create') {
                $nombre = trim((string) ($_POST['nombre'] ?? ''));
                if ($nombre !== '') {
                    $m->createCategory($nombre);
                    flash('ok', 'Categoría creada.');
                }
            } elseif ($action === 'update') {
                $id = (int) ($_POST['id_categoria'] ?? 0);
                $nombre = trim((string) ($_POST['nombre'] ?? ''));
                if ($id > 0 && $nombre !== '') {
                    $m->updateCategory($id, $nombre);
                    flash('ok', 'Categoría actualizada.');
                }
            } elseif ($action === 'delete') {
                $id = (int) ($_POST['id_categoria'] ?? 0);
                if ($id > 0) {
                    try {
                        $m->deleteCategory($id);
                        flash('ok', 'Categoría eliminada.');
                    } catch (PDOException $e) {
                        flash('err', 'No se puede eliminar: tiene productos vinculados.');
                    }
                }
            }
            $this->redirect('category');
        }

        $editId = (int) ($_GET['edit'] ?? 0);
        $row = $editId > 0 ? $m->oneById('categorias', 'id_categoria', $editId) : null;
        $this->render('categories/index', [
            'pageTitle' => 'Categorías',
            'row' => $row,
            'lista' => $m->all('categorias', 'nombre'),
        ]);
    }
}
