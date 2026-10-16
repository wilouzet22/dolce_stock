<?php
// Vista dedicada para imprimir ticket (formato 80mm POS)
declare(strict_types=1);
?>
<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Ticket #<?= (int) $factura['id_factura'] ?> - Dolce Café</title>
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Space+Mono:ital,wght@0,400;0,700;1,400&display=swap" rel="stylesheet">
    <style>
        :root {
            --ticket-width: 80mm;
            --text-color: #000;
        }
        
        * {
            box-sizing: border-box;
        }

        body {
            margin: 0;
            padding: 0;
            background: #e2e2e2;
            font-family: 'Space Mono', monospace;
            font-size: 12px;
            color: var(--text-color);
            display: flex;
            justify-content: center;
        }

        .ticket {
            width: var(--ticket-width);
            max-width: 100%;
            background: #fff;
            padding: 15px;
            margin: 20px 0;
            box-shadow: 0 4px 10px rgba(0,0,0,0.1);
        }

        .brand-header {
            text-align: center;
            margin-bottom: 15px;
            border-bottom: 1px dashed #000;
            padding-bottom: 10px;
        }

        .brand-header h1 {
            font-size: 20px;
            margin: 0 0 5px;
            font-weight: 700;
            letter-spacing: -0.05em;
        }

        .brand-header p {
            margin: 0;
            font-size: 10px;
        }

        .meta {
            margin-bottom: 15px;
            font-size: 11px;
            line-height: 1.4;
        }

        table {
            width: 100%;
            border-collapse: collapse;
            margin-bottom: 10px;
        }

        th, td {
            text-align: left;
            padding: 4px 0;
            vertical-align: top;
        }
        
        th {
            border-top: 1px dashed #000;
            border-bottom: 1px dashed #000;
            font-weight: 700;
        }

        .num { text-align: right; }
        .qty { width: 15%; }
        .desc { width: 55%; padding-right: 5px; }
        .sub { width: 30%; }

        .totals {
            margin-top: 10px;
            border-top: 1px dashed #000;
            padding-top: 10px;
            text-align: right;
            font-size: 14px;
        }

        .totals strong {
            font-size: 16px;
        }

        .footer {
            margin-top: 25px;
            text-align: center;
            font-size: 10px;
        }

        .btn-print {
            display: block;
            width: 100%;
            padding: 12px;
            background: #3d2914;
            color: #fff;
            border: none;
            cursor: pointer;
            font-family: inherit;
            font-size: 14px;
            font-weight: bold;
            margin-top: 20px;
        }

        /* Ocultar elementos innecesarios al imprimir */
        @media print {
            @page {
                margin: 0;
                size: 80mm auto;
            }
            body {
                background: transparent;
                display: block;
                margin: 0;
            }
            .ticket {
                margin: 0;
                padding: 10px;
                box-shadow: none;
            }
            .btn-print {
                display: none;
            }
        }
    </style>
</head>
<body>

<div class="ticket">
    <div class="brand-header">
        <h1>DOLCE CAFÉ</h1>
        <p>El mejor café de autor</p>
        <p>NIT: 900.123.456-7</p>
    </div>

    <div class="meta">
        <div><strong>TICKET:</strong> #<?= str_pad((string)$factura['id_factura'], 6, '0', STR_PAD_LEFT) ?></div>
        <div><strong>FECHA:</strong> <?= e($factura['fecha']) ?></div>
        <div><strong>CAJERO:</strong> <?= e($factura['empleado'] ?? 'Genérico') ?></div>
        <div><strong>CLIENTE:</strong> <?= e($factura['cliente'] ?? 'Consumidor Final') ?></div>
    </div>

    <table>
        <thead>
            <tr>
                <th class="qty">CANT</th>
                <th class="desc">DESCRIPCIÓN</th>
                <th class="num sub">IMPORTE</th>
            </tr>
        </thead>
        <tbody>
            <?php foreach ($detalles as $d): ?>
            <tr>
                <td class="qty"><?= (int) $d['cantidad'] ?></td>
                <td class="desc"><?= e($d['producto']) ?></td>
                <td class="num sub">$<?= number_format((float) $d['subtotal'], 2, '.', ',') ?></td>
            </tr>
            <?php endforeach; ?>
        </tbody>
    </table>

    <div class="totals">
        TOTAL: <strong>$ <?= number_format((float) $factura['total'], 2, '.', ',') ?></strong>
    </div>

    <div class="footer">
        <p>¡Gracias por tu compra!</p>
        <p>Síguenos en @dolcecafe_oficial</p>
    </div>
    
    <button class="btn-print" onclick="window.print()">🖨️ IMPRIMIR TICKET</button>
</div>

<script>
    // Iniciar el diálogo de impresión automáticamente al abrir el ticket
    window.onload = function() {
        setTimeout(function() {
            window.print();
        }, 300);
    };
</script>

</body>
</html>
