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

    public function createClient(string $nombre): void
    {
        $this->db->prepare('INSERT INTO clientes(nombre) VALUES(?)')->execute([$nombre]);
    }

    public function updateClient(int $id, string $nombre): void
    {
        $this->db->prepare('UPDATE clientes SET nombre=? WHERE id_cliente=?')->execute([$nombre, $id]);
    }

    public function deleteClient(int $id): void
    {
        $this->db->prepare('DELETE FROM clientes WHERE id_cliente=?')->execute([$id]);
    }

    public function createEmployee(string $nombre, ?string $cargo, ?float $salario): void
    {
        $this->db->prepare('INSERT INTO empleados(nombre,cargo,salario) VALUES(?,?,?)')->execute([$nombre, $cargo, $salario]);
    }

    public function updateEmployee(int $id, string $nombre, ?string $cargo, ?float $salario): void
    {
        $this->db->prepare('UPDATE empleados SET nombre=?,cargo=?,salario=? WHERE id_empleado=?')->execute([$nombre, $cargo, $salario, $id]);
    }

    public function deleteEmployee(int $id): void
    {
        $this->db->prepare('DELETE FROM empleados WHERE id_empleado=?')->execute([$id]);
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

    public function createProduct(string $nombre, float $precio, int $stock, int $cat): void
    {
        $this->db->prepare('INSERT INTO productos(nombre,precio,stock,id_categorias) VALUES(?,?,?,?)')->execute([$nombre, $precio, $stock, $cat]);
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

    public function invoiceList(): array
    {
        return $this->db->query('SELECT f.id_factura,f.fecha,f.total,c.nombre AS cliente,e.nombre AS empleado FROM facturas f LEFT JOIN clientes c ON c.id_cliente=f.id_cliente LEFT JOIN empleados e ON e.id_empleado=f.id_empleado ORDER BY f.fecha DESC,f.id_factura DESC')->fetchAll();
    }

    public function invoiceById(int $id): ?array
    {
        $st = $this->db->prepare('SELECT f.*,c.nombre AS cliente,e.nombre AS empleado FROM facturas f LEFT JOIN clientes c ON c.id_cliente=f.id_cliente LEFT JOIN empleados e ON e.id_empleado=f.id_empleado WHERE f.id_factura=?');
        $st->execute([$id]);
        return $st->fetch() ?: null;
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
            foreach ($priced as [$pid, $qty, $price, $sub]) {
                $insDet->execute([$idFactura, $pid, $qty, $price, $sub]);
                $upd->execute([$qty, $pid]);
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
}
