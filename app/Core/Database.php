<?php

declare(strict_types=1);

namespace App\Core;

use PDO;
use PDOException;

final class Database
{
    private static ?PDO $conn = null;

    public static function dbname(): string
    {
        $config = require dirname(__DIR__, 2) . '/config/database.php';

        return (string) ($config['dbname'] ?? '');
    }

    public static function connection(): PDO
    {
        if (self::$conn !== null) {
            return self::$conn;
        }

        $config = require dirname(__DIR__, 2) . '/config/database.php';
        $dsn = sprintf(
            'mysql:host=%s;dbname=%s;charset=%s',
            $config['host'],
            $config['dbname'],
            $config['charset']
        );

        try {
            self::$conn = new PDO($dsn, $config['user'], $config['pass'], [
                PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION,
                PDO::ATTR_DEFAULT_FETCH_MODE => PDO::FETCH_ASSOC,
            ]);
        } catch (PDOException $e) {
            http_response_code(500);
            exit('Error de conexión a base de datos. Revisá config/database.php');
        }

        return self::$conn;
    }
}
