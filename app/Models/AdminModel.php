<?php

declare(strict_types=1);

namespace App\Models;

use App\Core\Model;
use RuntimeException;
use Throwable;

final class AdminModel extends Model
{
    public function dashboardStats(): array
    {
        return [
            'productos' => (int) $this->db->query('SELECT COUNT(*) FROM productos')->fetchColumn(),
            'stock_bajo' => (int) $this->db->query('SELECT COUNT(*) FROM productos WHERE stock <= 5')->fetchColumn(),
            'ventas_hoy' => (float) $this->db->query('SELECT COALESCE(SUM(total), 0) FROM facturas WHERE DATE(fecha)=CURDATE()')->fetchColumn(),
            'facturas_total' => (int) $this->db->query('SELECT COUNT(*) FROM facturas')->fetchColumn(),
        ];
    }

    public function latestInvoices(): array
    {
        return $this->db->query('SELECT f.id_factura,f.fecha,f.total,c.nombre AS cliente FROM facturas f LEFT JOIN clientes c ON c.id_cliente=f.id_cliente ORDER BY f.fecha DESC LIMIT 8')->fetchAll();
    }

    public function lowStock(): array
    {
        return $this->db->query('SELECT p.nombre,p.stock,cat.nombre AS categoria FROM productos p JOIN categorias cat ON cat.id_categoria=p.id_categorias ORDER BY p.stock ASC LIMIT 8')->fetchAll();
    }

    /**
     * Ventas agrupadas por día (últimos 30 días).
     * Devuelve [{fecha, total}]
     */
    public function salesLast30Days(): array
    {
        return $this->db->query(
            "SELECT DATE(fecha) AS fecha, SUM(total) AS total
             FROM facturas
             WHERE fecha >= DATE_SUB(CURDATE(), INTERVAL 30 DAY)
             GROUP BY DATE(fecha)
             ORDER BY fecha ASC"
        )->fetchAll();
    }

    /**
     * Top 8 productos más vendidos (por cantidad total).
     * Devuelve [{nombre, cantidad}]
     */
    public function topSellingProducts(): array
    {
        return $this->db->query(
            "SELECT p.nombre, SUM(d.cantidad) AS cantidad
             FROM detalle_factura d
             JOIN productos p ON p.id_producto = d.id_producto
             GROUP BY d.id_producto, p.nombre
             ORDER BY cantidad DESC
             LIMIT 8"
        )->fetchAll();
    }

    public function all(string $table, string $order = 'id'): array
    {
        return $this->db->query("SELECT * FROM {$table} ORDER BY {$order}")->fetchAll();
    }

    public function oneById(string $table, string $idField, int $id): ?array
    {
        $st = $this->db->prepare("SELECT * FROM {$table} WHERE {$idField}=?");
        $st->execute([$id]);
        $row = $st->fetch();
        return $row ?: null;
    }

    public function createCategory(string $nombre): void
    {
        $this->db->prepare('INSERT INTO categorias(nombre) VALUES(?)')->execute([$nombre]);
    }


    public function updateCategory(int $id, string $nombre): void
    {
        $this->db->prepare('UPDATE categorias SET nombre=? WHERE id_categoria=?')->execute([$nombre, $id]);
    }

    public function deleteCategory(int $id): void
    {
        $this->db->prepare('DELETE FROM categorias WHERE id_categoria=?')->execute([$id]);
    }

    public function createClient(string $nombre, ?string $apellidos = null, ?string $identificacion = null, ?string $email = null, ?string $direccion = null): void
    {
        $this->ensureClientColumns();
        $this->db->prepare('INSERT INTO clientes(nombre, apellidos, identificacion, email, direccion) VALUES(?,?,?,?,?)')
            ->execute([$nombre, $apellidos ?: null, $identificacion ?: null, $email ?: null, $direccion ?: null]);
    }

    public function updateClient(int $id, string $nombre, ?string $apellidos = null, ?string $identificacion = null, ?string $email = null, ?string $direccion = null): void
    {
        $this->ensureClientColumns();
        $this->db->prepare('UPDATE clientes SET nombre=?, apellidos=?, identificacion=?, email=?, direccion=? WHERE id_cliente=?')
            ->execute([$nombre, $apellidos ?: null, $identificacion ?: null, $email ?: null, $direccion ?: null, $id]);
    }

    public function deleteClient(int $id): void
    {
        $this->db->prepare('DELETE FROM clientes WHERE id_cliente=?')->execute([$id]);
    }

    public function ensureClientColumns(): void
    {
        try {
            $dbName = Database::dbname();
            $st = $this->db->prepare(
                'SELECT COLUMN_NAME FROM information_schema.columns WHERE TABLE_SCHEMA=? AND TABLE_NAME=? AND COLUMN_NAME=?'
            );
            
            $st->execute([$dbName, 'clientes', 'identificacion']);
            if (!$st->fetchColumn()) {
                $this->db->exec("ALTER TABLE `clientes` ADD COLUMN `identificacion` varchar(50) DEFAULT NULL AFTER `id_cliente`");
            }
            
            $st->execute([$dbName, 'clientes', 'Aoellidos']);
            if ($st->fetchColumn()) {
                $this->db->exec("ALTER TABLE `clientes` CHANGE COLUMN `Aoellidos` `apellidos` varchar(150) DEFAULT NULL");
            } else {
                $st->execute([$dbName, 'clientes', 'apellidos']);
                if (!$st->fetchColumn()) {
                    $this->db->exec("ALTER TABLE `clientes` ADD COLUMN `apellidos` varchar(150) DEFAULT NULL AFTER `nombre`");
                }
            }

            $st->execute([$dbName, 'clientes', 'email']);
            if (!$st->fetchColumn()) {
                $this->db->exec("ALTER TABLE `clientes` ADD COLUMN `email` varchar(150) DEFAULT NULL AFTER `apellidos`");
            }
            
            $st->execute([$dbName, 'clientes', 'direccion']);
            if (!$st->fetchColumn()) {
                $this->db->exec("ALTER TABLE `clientes` ADD COLUMN `direccion` varchar(255) DEFAULT NULL AFTER `email`");
            }
        } catch (Throwable) {
            // Ignorar errores si ya existen las columnas
        }
    }

    public function ensureAuthColumns(): void
    {
        try {
            $dbName = Database::dbname();
            $st = $this->db->prepare(
                'SELECT COLUMN_NAME FROM information_schema.columns WHERE TABLE_SCHEMA=? AND TABLE_NAME=? AND COLUMN_NAME=?'
            );
            
            $st->execute([$dbName, 'empleados', 'primer_nombre']);
            if (!$st->fetchColumn()) {
                $this->db->exec("ALTER TABLE `empleados` 
                    ADD COLUMN `primer_nombre` varchar(70) DEFAULT NULL AFTER `id_empleado`,
                    ADD COLUMN `segundo_nombre` varchar(70) DEFAULT NULL AFTER `primer_nombre`,
                    ADD COLUMN `primer_apellido` varchar(70) DEFAULT NULL AFTER `segundo_nombre`,
                    ADD COLUMN `segundo_apellido` varchar(70) DEFAULT NULL AFTER `primer_apellido`");
            }
            $st->execute([$dbName, 'empleados', 'email']);
            if (!$st->fetchColumn()) {
                $this->db->exec("ALTER TABLE `empleados` ADD COLUMN `email` varchar(150) DEFAULT NULL AFTER `salario`, ADD COLUMN `password_hash` varchar(255) DEFAULT NULL AFTER `email`, ADD UNIQUE KEY `uq_empleados_email` (`email`)");
            }
            
            $st->execute([$dbName, 'empleados', 'rol']);
            if (!$st->fetchColumn()) {
                $this->db->exec("ALTER TABLE `empleados` ADD COLUMN `rol` ENUM('admin', 'cajero') NOT NULL DEFAULT 'cajero' AFTER `password_hash`");
                $this->db->exec("UPDATE `empleados` SET `rol` = 'admin'");
            }
            $this->db->exec("UPDATE `empleados` SET `rol` = 'admin' WHERE (rol IS NULL OR rol = '' OR rol = 'cajero') AND (LOWER(cargo) LIKE '%admin%' OR LOWER(cargo) LIKE '%jefe%')");
            $this->db->exec("UPDATE `empleados` SET `rol` = 'cajero' WHERE rol IS NULL OR rol = ''");
        } catch (Throwable) {
            // Ignorar si la columna ya existe o falla por permisos
        }
    }

    public function isEmailTaken(string $email, int $excludeId = 0): bool
    {
        $email = mb_strtolower(trim($email));
        if ($email === '') {
            return false;
        }
        $st = $this->db->prepare('SELECT COUNT(*) FROM empleados WHERE LOWER(email) = ? AND id_empleado != ?');
        $st->execute([$email, $excludeId]);
        return ((int) $st->fetchColumn()) > 0;
    }

    public function createEmployee(
        string $nombre,
        ?string $primerNombre = null,
        ?string $segundoNombre = null,
        ?string $primerApellido = null,
        ?string $segundoApellido = null,
        ?string $cargo = null,
        ?float $salario = null,
        ?string $email = null,
        ?string $password = null,
        string $rol = 'cajero'
    ): void {
        $this->ensureAuthColumns();
        $emailNorm = ($email !== null && trim($email) !== '') ? mb_strtolower(trim($email)) : null;
        $hash = ($password !== null && trim($password) !== '') ? password_hash($password, PASSWORD_DEFAULT) : null;

        $this->db->prepare('INSERT INTO empleados(primer_nombre, segundo_nombre, primer_apellido, segundo_apellido, nombre, cargo, salario, email, password_hash, rol) VALUES(?,?,?,?,?,?,?,?,?,?)')
            ->execute([$primerNombre, $segundoNombre, $primerApellido, $segundoApellido, $nombre, $cargo, $salario, $emailNorm, $hash, $rol]);
    }

    public function updateEmployee(
        int $id,
        string $nombre,
        ?string $primerNombre = null,
        ?string $segundoNombre = null,
        ?string $primerApellido = null,
        ?string $segundoApellido = null,
        ?string $cargo = null,
        ?float $salario = null,
        ?string $email = null,
        ?string $password = null,
        string $rol = 'cajero'
    ): void {
        $this->ensureAuthColumns();
        $emailNorm = ($email !== null && trim($email) !== '') ? mb_strtolower(trim($email)) : null;

        if ($password !== null && trim($password) !== '') {
            $hash = password_hash($password, PASSWORD_DEFAULT);
            $this->db->prepare('UPDATE empleados SET primer_nombre=?, segundo_nombre=?, primer_apellido=?, segundo_apellido=?, nombre=?, cargo=?, salario=?, email=?, password_hash=?, rol=? WHERE id_empleado=?')
                ->execute([$primerNombre, $segundoNombre, $primerApellido, $segundoApellido, $nombre, $cargo, $salario, $emailNorm, $hash, $rol, $id]);
        } else {
            $this->db->prepare('UPDATE empleados SET primer_nombre=?, segundo_nombre=?, primer_apellido=?, segundo_apellido=?, nombre=?, cargo=?, salario=?, email=?, rol=? WHERE id_empleado=?')
                ->execute([$primerNombre, $segundoNombre, $primerApellido, $segundoApellido, $nombre, $cargo, $salario, $emailNorm, $rol, $id]);
        }
    }

    public function deleteEmployee(int $id): void
    {
        $this->db->prepare('UPDATE facturas SET id_empleado = NULL WHERE id_empleado = ?')->execute([$id]);
        $this->db->prepare('DELETE FROM empleados WHERE id_empleado = ?')->execute([$id]);
    }

    public function categories(): array
    {
        return $this->db->query('SELECT id_categoria,nombre FROM categorias ORDER BY nombre')->fetchAll();
    }

    public function products(): array
    {
        return $this->db->query('SELECT p.*,c.nombre AS categoria FROM productos p JOIN categorias c ON c.id_categoria=p.id_categorias ORDER BY c.nombre,p.nombre')->fetchAll();
    }

    public function productsSimple(): array
    {
        return $this->db->query('SELECT id_producto,nombre,precio,stock FROM productos ORDER BY nombre')->fetchAll();
    }

    public function findProductByName(string $nombre): ?array
    {
        $st = $this->db->prepare('SELECT * FROM productos WHERE LOWER(TRIM(nombre)) = LOWER(TRIM(?)) LIMIT 1');
        $st->execute([$nombre]);
        $row = $st->fetch();
        return $row ?: null;
    }

    public function countProducts(string $search = ''): int
    {
        if ($search !== '') {
            $st = $this->db->prepare('SELECT COUNT(*) FROM productos p JOIN categorias c ON c.id_categoria=p.id_categorias WHERE p.nombre LIKE ? OR c.nombre LIKE ?');
            $like = '%' . $search . '%';
            $st->execute([$like, $like]);
        } else {
            $st = $this->db->query('SELECT COUNT(*) FROM productos');
        }
        return (int) $st->fetchColumn();
    }

    public function productsPaged(string $search = '', int $page = 1, int $perPage = 10): array
    {
        $offset = ($page - 1) * $perPage;
        if ($search !== '') {
            $like = '%' . $search . '%';
            $st = $this->db->prepare(
                'SELECT p.*, c.nombre AS categoria FROM productos p
                 JOIN categorias c ON c.id_categoria = p.id_categorias
                 WHERE p.nombre LIKE :like1 OR c.nombre LIKE :like2
                 ORDER BY c.nombre, p.nombre
                 LIMIT :lim OFFSET :off'
            );
            $st->bindValue(':like1', $like);
            $st->bindValue(':like2', $like);
            $st->bindValue(':lim',  $perPage, \PDO::PARAM_INT);
            $st->bindValue(':off',  $offset,  \PDO::PARAM_INT);
        } else {
            $st = $this->db->prepare(
                'SELECT p.*, c.nombre AS categoria FROM productos p
                 JOIN categorias c ON c.id_categoria = p.id_categorias
                 ORDER BY c.nombre, p.nombre
                 LIMIT :lim OFFSET :off'
            );
            $st->bindValue(':lim', $perPage, \PDO::PARAM_INT);
            $st->bindValue(':off', $offset,  \PDO::PARAM_INT);
        }
        $st->execute();
        return $st->fetchAll();
    }

    public function allProducts(string $search = ''): array
    {
        if ($search !== '') {
            $like = '%' . $search . '%';
            $st = $this->db->prepare(
                'SELECT p.*, c.nombre AS categoria FROM productos p
                 JOIN categorias c ON c.id_categoria = p.id_categorias
                 WHERE p.nombre LIKE ? OR c.nombre LIKE ?
                 ORDER BY c.nombre, p.nombre'
            );
            $st->execute([$like, $like]);
            return $st->fetchAll();
        }
        $st = $this->db->query(
            'SELECT p.*, c.nombre AS categoria FROM productos p
             JOIN categorias c ON c.id_categoria = p.id_categorias
             ORDER BY c.nombre, p.nombre'
        );
        return $st->fetchAll();
    }


    public function createProduct(string $nombre, float $precio, int $stock, int $cat): void
    {
        $this->db->prepare('INSERT INTO productos(nombre,precio,stock,id_categorias) VALUES(?,?,?,?)')->execute([$nombre, $precio, $stock, $cat]);
    }

    public function getOrCreateProductByName(string $nombre, int $defaultCat): int
    {
        $st = $this->db->prepare('SELECT id_producto FROM productos WHERE LOWER(nombre) = LOWER(?)');
        $st->execute([$nombre]);
        $row = $st->fetch();
        if ($row) {
            return (int) $row['id_producto'];
        }
        $this->createProduct($nombre, 0.0, 0, $defaultCat);
        return (int) $this->db->lastInsertId();
    }

    public function updateProduct(int $id, string $nombre, float $precio, int $stock, int $cat): void
    {
        $this->db->prepare('UPDATE productos SET nombre=?,precio=?,stock=?,id_categorias=? WHERE id_producto=?')->execute([$nombre, $precio, $stock, $cat, $id]);
    }

    public function deleteProduct(int $id): void
    {
        $this->db->prepare('DELETE FROM productos WHERE id_producto=?')->execute([$id]);
    }

    public function inventoryMoves(): array
    {
        return $this->db->query('SELECT m.*,p.nombre AS producto FROM movimientos_inventario m JOIN productos p ON p.id_producto=m.id_producto ORDER BY m.fecha DESC,m.id_movimiento DESC LIMIT 100')->fetchAll();
    }

    public function registerMove(int $idProd, string $tipo, int $cantidad, ?string $desc): void
    {
        try {
            $this->db->beginTransaction();
            $st = $this->db->prepare('SELECT stock FROM productos WHERE id_producto=? FOR UPDATE');
            $st->execute([$idProd]);
            $cur = $st->fetch();
            if (!$cur) {
                throw new RuntimeException('Producto inexistente');
            }
            $stock = (int) $cur['stock'];
            if ($tipo === 'SALIDA' && $stock < $cantidad) {
                throw new RuntimeException('Stock insuficiente');
            }
            $delta = $tipo === 'ENTRADA' ? $cantidad : -$cantidad;
            $this->db->prepare('UPDATE productos SET stock=stock+? WHERE id_producto=?')->execute([$delta, $idProd]);
            $this->db->prepare('INSERT INTO movimientos_inventario(id_producto,tipo,cantidad,descripcion) VALUES(?,?,?,?)')->execute([$idProd, $tipo, $cantidad, $desc ?: null]);
            $this->db->commit();
        } catch (Throwable $e) {
            if ($this->db->inTransaction()) {
                $this->db->rollBack();
            }
            throw $e;
        }
    }

    public function deleteMove(int $idMovimiento): void
    {
        try {
            $this->db->beginTransaction();
            // Get the movement data before deleting
            $st = $this->db->prepare('SELECT * FROM movimientos_inventario WHERE id_movimiento = ? FOR UPDATE');
            $st->execute([$idMovimiento]);
            $move = $st->fetch();
            if (!$move) {
                throw new RuntimeException('Movimiento no encontrado.');
            }
            // Reverse the stock change
            $delta = $move['tipo'] === 'ENTRADA' ? -(int)$move['cantidad'] : (int)$move['cantidad'];
            $this->db->prepare('UPDATE productos SET stock = stock + ? WHERE id_producto = ?')
                ->execute([$delta, (int)$move['id_producto']]);
            // Delete the movement record
            $this->db->prepare('DELETE FROM movimientos_inventario WHERE id_movimiento = ?')
                ->execute([$idMovimiento]);
            $this->db->commit();
        } catch (Throwable $e) {
            if ($this->db->inTransaction()) {
                $this->db->rollBack();
            }
            throw $e;
        }
    }


    public function invoiceList(): array
    {
        return $this->db->query('SELECT f.id_factura,f.fecha,f.total,c.nombre AS cliente,e.nombre AS empleado FROM facturas f LEFT JOIN clientes c ON c.id_cliente=f.id_cliente LEFT JOIN empleados e ON e.id_empleado=f.id_empleado ORDER BY f.fecha DESC,f.id_factura DESC')->fetchAll();
    }


    public function invoiceById(int $id): ?array
    {
        $st = $this->db->prepare('SELECT f.*, c.nombre AS cliente, c.apellidos AS cliente_apellidos, c.identificacion AS cliente_identificacion, c.email AS cliente_email, c.direccion AS cliente_direccion, e.nombre AS empleado FROM facturas f LEFT JOIN clientes c ON c.id_cliente=f.id_cliente LEFT JOIN empleados e ON e.id_empleado=f.id_empleado WHERE f.id_factura=?');
        $st->execute([$id]);
        return $st->fetch() ?: null;
    }

    public function saveOrUpdateClientFromInvoice(?int $idCliente, ?string $identificacion, ?string $nombre, ?string $apellidos, ?string $email, ?string $direccion): ?int
    {
        $this->ensureClientColumns();

        $nombre = trim((string) $nombre);
        $apellidos = trim((string) $apellidos);
        $identificacion = trim((string) $identificacion);
        $email = trim((string) $email);
        $direccion = trim((string) $direccion);

        if ($nombre === '' && $identificacion === '' && $email === '') {
            return $idCliente > 0 ? $idCliente : null;
        }

        if ($idCliente !== null && $idCliente > 0) {
            $existing = $this->oneById('clientes', 'id_cliente', $idCliente);
            if ($existing) {
                $useNombre = $nombre !== '' ? $nombre : (string) ($existing['nombre'] ?? '');
                $useApellidos = $apellidos !== '' ? $apellidos : (string) ($existing['apellidos'] ?? '');
                $useIdent = $identificacion !== '' ? $identificacion : (string) ($existing['identificacion'] ?? '');
                $useEmail = $email !== '' ? $email : (string) ($existing['email'] ?? '');
                $useDir = $direccion !== '' ? $direccion : (string) ($existing['direccion'] ?? '');

                $this->updateClient($idCliente, $useNombre, $useApellidos, $useIdent, $useEmail, $useDir);
                return $idCliente;
            }
        }

        if ($identificacion !== '') {
            $st = $this->db->prepare('SELECT id_cliente, nombre FROM clientes WHERE identificacion = ? LIMIT 1');
            $st->execute([$identificacion]);
            $found = $st->fetch();
            if ($found) {
                $foundId = (int) $found['id_cliente'];
                $useNombre = $nombre !== '' ? $nombre : (string) $found['nombre'];
                $this->updateClient($foundId, $useNombre, $apellidos ?: null, $identificacion, $email ?: null, $direccion ?: null);
                return $foundId;
            }
        }

        if ($email !== '') {
            $st = $this->db->prepare('SELECT id_cliente, nombre FROM clientes WHERE email = ? LIMIT 1');
            $st->execute([$email]);
            $found = $st->fetch();
            if ($found) {
                $foundId = (int) $found['id_cliente'];
                $useNombre = $nombre !== '' ? $nombre : (string) $found['nombre'];
                $this->updateClient($foundId, $useNombre, $apellidos ?: null, $identificacion ?: null, $email, $direccion ?: null);
                return $foundId;
            }
        }

        $finalNombre = $nombre !== '' ? $nombre : ('Cliente ' . ($identificacion ?: 'General'));
        $this->createClient($finalNombre, $apellidos ?: null, $identificacion ?: null, $email ?: null, $direccion ?: null);
        return (int) $this->db->lastInsertId();
    }

    public function invoiceDetails(int $id): array
    {
        $st = $this->db->prepare('SELECT d.cantidad,d.precio_unitario,d.subtotal,p.nombre AS producto FROM detalle_factura d JOIN productos p ON p.id_producto=d.id_producto WHERE d.id_factura=? ORDER BY d.id_detalle');
        $st->execute([$id]);
        return $st->fetchAll();
    }

    public function createInvoice(?int $idCliente, ?int $idEmpleado, array $lines): int
    {
        if ($lines === []) {
            throw new RuntimeException('Agregá al menos un producto.');
        }
        $needByProd = [];
        foreach ($lines as $line) {
            $pid = (int) $line['producto'];
            $qty = (int) $line['cantidad'];
            $needByProd[$pid] = ($needByProd[$pid] ?? 0) + $qty;
        }
        try {
            $this->db->beginTransaction();
            $cache = [];
            foreach ($needByProd as $pid => $need) {
                $st = $this->db->prepare('SELECT nombre,precio,stock FROM productos WHERE id_producto=? FOR UPDATE');
                $st->execute([$pid]);
                $pr = $st->fetch();
                if (!$pr) {
                    throw new RuntimeException('Producto no válido');
                }
                if ((int) $pr['stock'] < $need) {
                    throw new RuntimeException('Stock insuficiente para ' . $pr['nombre']);
                }
                $cache[(int) $pid] = $pr;
            }
            $total = 0.0;
            $priced = [];
            foreach ($lines as $line) {
                $pid = (int) $line['producto'];
                $qty = (int) $line['cantidad'];
                $price = (float) $cache[$pid]['precio'];
                $sub = round($price * $qty, 2);
                $total += $sub;
                $priced[] = [$pid, $qty, $price, $sub];
            }

            $this->db->prepare('INSERT INTO facturas(id_cliente,id_empleado,total) VALUES(?,?,?)')->execute([$idCliente, $idEmpleado, round($total, 2)]);
            $idFactura = (int) $this->db->lastInsertId();
            $insDet = $this->db->prepare('INSERT INTO detalle_factura(id_factura,id_producto,cantidad,precio_unitario,subtotal) VALUES(?,?,?,?,?)');
            $upd = $this->db->prepare('UPDATE productos SET stock=stock-? WHERE id_producto=?');
            $insMov = $this->db->prepare('INSERT INTO movimientos_inventario(id_producto,tipo,cantidad,descripcion) VALUES(?,?,?,?)');
            foreach ($priced as [$pid, $qty, $price, $sub]) {
                $insDet->execute([$idFactura, $pid, $qty, $price, $sub]);
                $upd->execute([$qty, $pid]);
                $insMov->execute([$pid, 'SALIDA', $qty, 'Venta Factura #' . $idFactura]);
            }
            $this->db->commit();
            return $idFactura;
        } catch (Throwable $e) {
            if ($this->db->inTransaction()) {
                $this->db->rollBack();
            }
            throw $e;
        }
    }

    public function suppliers(): array
    {
        return $this->db->query('SELECT * FROM proveedores ORDER BY nombre')->fetchAll();
    }

    public function createSupplier(string $nombre, ?string $contacto, ?string $telefono, ?string $email, ?string $direccion): void
    {
        $this->db->prepare('INSERT INTO proveedores(nombre, contacto, telefono, email, direccion) VALUES(?,?,?,?,?)')
            ->execute([$nombre, $contacto, $telefono, $email, $direccion]);
    }

    public function updateSupplier(int $id, string $nombre, ?string $contacto, ?string $telefono, ?string $email, ?string $direccion): void
    {
        $this->db->prepare('UPDATE proveedores SET nombre=?, contacto=?, telefono=?, email=?, direccion=? WHERE id_proveedor=?')
            ->execute([$nombre, $contacto, $telefono, $email, $direccion, $id]);
    }

    public function deleteSupplier(int $id): void
    {
        $this->db->prepare('DELETE FROM proveedores WHERE id_proveedor=?')->execute([$id]);
    }

    public function rawMaterials(): array
    {
        return $this->db->query('SELECT m.*, p.nombre AS proveedor FROM materia_prima m LEFT JOIN proveedores p ON p.id_proveedor = m.id_proveedor ORDER BY m.nombre')->fetchAll();
    }

    public function createRawMaterial(string $nombre, string $unidad, float $stockActual, float $stockMinimo, ?float $precioUnitario, ?int $idProveedor): void
    {
        $this->db->prepare('INSERT INTO materia_prima(nombre, unidad_medida, stock_actual, stock_minimo, precio_unitario, id_proveedor) VALUES(?,?,?,?,?,?)')
            ->execute([$nombre, $unidad, $stockActual, $stockMinimo, $precioUnitario, $idProveedor]);
    }

    public function updateRawMaterial(int $id, string $nombre, string $unidad, float $stockActual, float $stockMinimo, ?float $precioUnitario, ?int $idProveedor): void
    {
        $this->db->prepare('UPDATE materia_prima SET nombre=?, unidad_medida=?, stock_actual=?, stock_minimo=?, precio_unitario=?, id_proveedor=? WHERE id_materia_prima=?')
            ->execute([$nombre, $unidad, $stockActual, $stockMinimo, $precioUnitario, $idProveedor, $id]);
    }

    public function deleteRawMaterial(int $id): void
    {
        $this->db->prepare('DELETE FROM materia_prima WHERE id_materia_prima=?')->execute([$id]);
    }
}

