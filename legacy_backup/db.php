<?php

declare(strict_types=1);

$DB_HOST = '127.0.0.1';
$DB_NAME = 'dolce_stock';
$DB_USER = 'root';
$DB_PASS = '';
$DB_CHARSET = 'utf8mb4';

$dsn = "mysql:host={$DB_HOST};dbname={$DB_NAME};charset={$DB_CHARSET}";

try {
    $pdo = new PDO($dsn, $DB_USER, $DB_PASS, [
        PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION,
        PDO::ATTR_DEFAULT_FETCH_MODE => PDO::FETCH_ASSOC,
    ]);
} catch (PDOException $e) {
    http_response_code(500);
    echo 'No se pudo conectar a la base de datos. Verifica config/db.php y que MySQL tenga la base dolce_stock importada.';
    exit;
}
