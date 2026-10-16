<?php

declare(strict_types=1);

return [
    /*
    |--------------------------------------------------------------------------
    | Cuenta Central de Envío de Facturas - Dolce Stock
    |--------------------------------------------------------------------------
    | Correo maestro del sistema: todas las facturas se emiten y envían
    | automáticamente a través de este buzón sin importar el usuario o rol.
    */
    'smtp_host'       => 'smtp.gmail.com',
    'smtp_port'       => 587,
    'smtp_user'       => 'dolcestock1@gmail.com',
    'smtp_pass'       => 'rrfwwscroxzmbgpi',
    'smtp_encryption' => 'tls',

    'from_email'      => 'dolcestock1@gmail.com',
    'from_name'       => 'Café La Arábica',
];
