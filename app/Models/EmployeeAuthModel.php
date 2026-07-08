<?php

declare(strict_types=1);

namespace App\Models;

use App\Core\Database;
use App\Core\Model;
use PDOException;
use RuntimeException;

final class EmployeeAuthModel extends Model
{
    public function authTablesReady(): bool
    {
        return $this->columnExists('empleados', 'email')
            && $this->columnExists('empleados', 'password_hash')
            && $this->columnExists('empleados', 'rol');
    }

    public function countAdmins(): int
    {
        if (!$this->authTablesReady()) {
            return 0;
        }
        return (int) $this->db->query("SELECT COUNT(*) FROM empleados WHERE rol = 'admin'")->fetchColumn();
    }

    public function findByEmail(string $email): ?array
    {
        $email = mb_strtolower(trim($email));
        if ($email === '') {
            return null;
        }
        $st = $this->db->prepare('SELECT * FROM empleados WHERE email = ? LIMIT 1');
        $st->execute([$email]);
        $row = $st->fetch();
        return $row ?: null;
    }

    /** @throws RuntimeException|PDOException si el correo existe o hay error BD */
    public function register(string $nombre, ?string $cargo, string $email, string $password, string $rol = 'cajero'): void
    {
        if (!$this->authTablesReady()) {
            throw new RuntimeException('Falta aplicar la migración en la base de datos: ejecutá sql/empleados_roles.sql en phpMyAdmin.');
        }

        $nombre = trim($nombre);
        $emailNorm = mb_strtolower(trim($email));
        if ($nombre === '' || $emailNorm === '' || strlen($password) < 6) {
            throw new RuntimeException('Completá nombre, correo y una contraseña de al menos 6 caracteres.');
        }
        if ($this->findByEmail($emailNorm) !== null) {
            throw new RuntimeException('Ese correo ya está registrado.');
        }

        $hash = password_hash($password, PASSWORD_DEFAULT);

        try {
            $st = $this->db->prepare(
                'INSERT INTO empleados (nombre, cargo, salario, email, password_hash, rol) VALUES (?, ?, ?, ?, ?, ?)'
            );
            $st->execute([
                $nombre,
                ($cargo !== null && trim($cargo) !== '') ? trim($cargo) : null,
                null,
                $emailNorm,
                $hash,
                $rol
            ]);
        } catch (PDOException $e) {
            if ((int) $e->errorInfo[1] === 1062) {
                throw new RuntimeException('Ese correo ya está registrado.');
            }
            throw $e;
        }
    }

    /** Busca usuario y verifica contraseña. Sin hash no puede iniciar sesión */
    public function verifyLogin(string $email, string $password): ?array
    {
        if (!$this->authTablesReady()) {
            return null;
        }

        $row = $this->findByEmail($email);
        if (!$row || empty($row['password_hash'])) {
            return null;
        }

        return password_verify($password, $row['password_hash']) ? $row : null;
    }

    private function columnExists(string $table, string $column): bool
    {
        $dbName = Database::dbname();
        if ($dbName === '') {
            return false;
        }
        $st = $this->db->prepare(
            'SELECT 1 FROM information_schema.columns WHERE TABLE_SCHEMA=? AND TABLE_NAME=? AND COLUMN_NAME=? LIMIT 1'
        );
        $st->execute([$dbName, $table, $column]);

        return (bool) $st->fetchColumn();
    }
}
