<?php

declare(strict_types=1);

namespace App\Helpers;

use Throwable;

final class MailHelper
{
    /**
     * Envía la factura digital por correo electrónico.
     * Retorna array ['success' => bool, 'message' => string]
     */
    public static function sendInvoiceEmail(array $factura, array $detalles, ?string $overrideEmail = null): array
    {
        $toEmail = trim((string) ($overrideEmail ?? $factura['cliente_email'] ?? ''));
        if ($toEmail === '' || !filter_var($toEmail, FILTER_VALIDATE_EMAIL)) {
            return [
                'success' => false,
                'message' => 'La dirección de correo electrónico "' . $toEmail . '" no es válida.',
            ];
        }

        $idFactura = (int) ($factura['id_factura'] ?? 0);
        $fecha = (string) ($factura['fecha'] ?? date('Y-m-d H:i:s'));
        $total = number_format((float) ($factura['total'] ?? 0), 2, '.', ',');
        
        $clienteNombre = trim(($factura['cliente'] ?? '') . ' ' . ($factura['cliente_apellidos'] ?? ''));
        if ($clienteNombre === '') {
            $clienteNombre = 'Cliente Consumidor Final';
        }
        $identificacion = (string) ($factura['cliente_identificacion'] ?? 'N/A');
        $direccion = (string) ($factura['cliente_direccion'] ?? 'N/A');

        $configFile = dirname(__DIR__, 2) . '/config/mail.php';
        $config = file_exists($configFile) ? (include $configFile) : [];
        if (!is_array($config)) {
            $config = [];
        }

        // Credenciales fijas y blindadas en código
        if (empty($config['smtp_user'])) {
            $config['smtp_host'] = 'smtp.gmail.com';
            $config['smtp_port'] = 587;
            $config['smtp_user'] = 'dolcestock1@gmail.com';
            $config['smtp_pass'] = 'rrfwwscroxzmbgpi';
            $config['smtp_encryption'] = 'tls';
            $config['from_email'] = 'dolcestock1@gmail.com';
            $config['from_name'] = 'Café La Arábica';
        }

        $fromEmail = !empty($config['from_email']) ? $config['from_email'] : 'dolcestock1@gmail.com';
        $fromName  = !empty($config['from_name']) ? $config['from_name'] : 'Café La Arábica';

        $facturaNum = str_pad((string) $idFactura, 6, '0', STR_PAD_LEFT);
        $subject = "Factura de Venta N° FAC-" . $facturaNum . " - " . $fromName;

        $itemsHtml = '';
        $subtotalSuma = 0.0;
        foreach ($detalles as $item) {
            $prod = htmlspecialchars((string) ($item['producto'] ?? 'Producto'), ENT_QUOTES, 'UTF-8');
            $cant = (int) ($item['cantidad'] ?? 1);
            $precioNum = (float) ($item['precio_unitario'] ?? 0);
            $subNum = (float) ($item['subtotal'] ?? ($precioNum * $cant));
            $subtotalSuma += $subNum;

            $precio = number_format($precioNum, 2, '.', ',');
            $subtotal = number_format($subNum, 2, '.', ',');

            $itemsHtml .= "
            <tr style='border-bottom: 1px solid #f1f5f9;'>
                <td style='padding: 13px 12px; font-size: 14px; color: #1e293b; font-weight: 500;'>{$prod}</td>
                <td style='padding: 13px 12px; font-size: 14px; color: #334155; text-align: center; font-weight: 600;'>{$cant}</td>
                <td style='padding: 13px 12px; font-size: 14px; color: #64748b; text-align: right;'>$ {$precio}</td>
                <td style='padding: 13px 12px; font-size: 14px; color: #0f172a; text-align: right; font-weight: 700;'>$ {$subtotal}</td>
            </tr>";
        }

        $subtotalFormatted = number_format($subtotalSuma > 0 ? $subtotalSuma : (float) ($factura['total'] ?? 0), 2, '.', ',');
        $identificacionStr = $identificacion !== 'N/A' && $identificacion !== '' ? $identificacion : 'Consumidor Final (222222222222)';
        $direccionStr = $direccion !== 'N/A' && $direccion !== '' ? $direccion : 'Mostrador / Venta Presencial';

        $body = "
<!DOCTYPE html>
<html lang='es'>
<head>
    <meta charset='UTF-8'>
    <meta name='viewport' content='width=device-width, initial-scale=1.0'>
    <title>Factura FAC-{$facturaNum} - {$fromName}</title>
</head>
<body style='margin: 0; padding: 25px 12px; background-color: #f8fafc; font-family: -apple-system, BlinkMacSystemFont, \"Segoe UI\", Roboto, Helvetica, Arial, sans-serif; -webkit-font-smoothing: antialiased; color: #1e293b;'>
    
    <table role='presentation' width='100%' border='0' cellspacing='0' cellpadding='0'>
        <tr>
            <td align='center'>
                
                <!-- Tarjeta Principal de Factura -->
                <div style='max-width: 650px; width: 100%; background-color: #ffffff; border-radius: 14px; overflow: hidden; border: 1px solid #e2e8f0; box-shadow: 0 10px 25px rgba(0, 0, 0, 0.05); text-align: left;'>
                    
                    <!-- Membrete Corporativo Formal -->
                    <div style='background: linear-gradient(135deg, #1c1917 0%, #292524 50%, #44403c 100%); padding: 32px 36px; color: #ffffff;'>
                        <table role='presentation' width='100%' border='0' cellspacing='0' cellpadding='0'>
                            <tr>
                                <td style='vertical-align: middle;'>
                                    <h1 style='margin: 0; font-size: 26px; font-weight: 800; letter-spacing: -0.5px; color: #fef3c7;'>" . htmlspecialchars($fromName, ENT_QUOTES, 'UTF-8') . "</h1>
                                    <p style='margin: 4px 0 0 0; font-size: 12px; color: #d6d3d1; text-transform: uppercase; letter-spacing: 1.5px; font-weight: 600;'>Cafetería de Especialidad & Repostería Artesanal</p>
                                    <p style='margin: 8px 0 0 0; font-size: 12px; color: #a8a29e; line-height: 1.45;'>
                                        <strong>NIT:</strong> 901.482.315-4 · Régimen Simplificado<br>
                                        Medellín, Antioquia · Tel: +57 (300) 845-9210<br>
                                        Email: dolcestock1@gmail.com
                                    </p>
                                </td>
                                <td align='right' style='vertical-align: middle;'>
                                    <div style='background: rgba(255, 255, 255, 0.08); border: 1px solid rgba(255, 255, 255, 0.18); border-radius: 10px; padding: 12px 18px; text-align: right; display: inline-block;'>
                                        <span style='display: block; font-size: 11px; text-transform: uppercase; letter-spacing: 1px; color: #fde68a; font-weight: 700;'>Factura de Venta</span>
                                        <span style='display: block; font-size: 20px; font-weight: 800; color: #ffffff; letter-spacing: 0.5px; margin-top: 2px;'>FAC-{$facturaNum}</span>
                                    </div>
                                </td>
                            </tr>
                        </table>
                    </div>

                    <!-- Faja de Estado y Metadatos de Factura -->
                    <div style='background-color: #f1f5f9; padding: 14px 36px; border-bottom: 1px solid #e2e8f0;'>
                        <table role='presentation' width='100%' border='0' cellspacing='0' cellpadding='0'>
                            <tr>
                                <td style='font-size: 13px; color: #475569;'>
                                    <strong>Fecha de Expedición:</strong> {$fecha}
                                </td>
                                <td align='center' style='font-size: 13px; color: #475569;'>
                                    <strong>Forma de Pago:</strong> Contado / Efectivo
                                </td>
                                <td align='right'>
                                    <span style='background-color: #dcfce7; color: #15803d; border: 1px solid #86efac; border-radius: 20px; padding: 4px 12px; font-size: 11px; font-weight: 800; text-transform: uppercase; letter-spacing: 0.5px;'>
                                        ● Pagada
                                    </span>
                                </td>
                            </tr>
                        </table>
                    </div>

                    <div style='padding: 30px 36px;'>

                        <!-- Bloque de Datos del Adquirente / Cliente -->
                        <div style='background-color: #fafaf9; border: 1px solid #e7e5e4; border-radius: 10px; padding: 18px 20px; margin-bottom: 26px;'>
                            <table role='presentation' width='100%' border='0' cellspacing='0' cellpadding='0'>
                                <tr>
                                    <td width='50%' style='vertical-align: top; padding-right: 15px;'>
                                        <span style='display: block; font-size: 11px; font-weight: 700; text-transform: uppercase; color: #92400e; letter-spacing: 0.8px;'>Facturado a:</span>
                                        <strong style='display: block; font-size: 15px; color: #1c1917; margin-top: 4px;'>" . htmlspecialchars($clienteNombre, ENT_QUOTES, 'UTF-8') . "</strong>
                                        <span style='display: block; font-size: 13px; color: #57534e; margin-top: 3px;'>CC / NIT: " . htmlspecialchars($identificacionStr, ENT_QUOTES, 'UTF-8') . "</span>
                                    </td>
                                    <td width='50%' style='vertical-align: top;'>
                                        <span style='display: block; font-size: 11px; font-weight: 700; text-transform: uppercase; color: #92400e; letter-spacing: 0.8px;'>Datos de Envío:</span>
                                        <span style='display: block; font-size: 13px; color: #57534e; margin-top: 4px;'><strong>Correo:</strong> " . htmlspecialchars($toEmail, ENT_QUOTES, 'UTF-8') . "</span>
                                        <span style='display: block; font-size: 13px; color: #57534e; margin-top: 3px;'><strong>Dirección:</strong> " . htmlspecialchars($direccionStr, ENT_QUOTES, 'UTF-8') . "</span>
                                    </td>
                                </tr>
                            </table>
                        </div>

                        <!-- Detalle Exclusivo de Compra: Producto, Cantidad, Precio, Total -->
                        <div style='margin-bottom: 24px;'>
                            <h2 style='margin: 0 0 12px 0; font-size: 13px; font-weight: 700; text-transform: uppercase; letter-spacing: 0.8px; color: #78716c;'>
                                Detalle de Compra
                            </h2>
                            <table role='presentation' width='100%' border='0' cellspacing='0' cellpadding='0' style='border-collapse: collapse;'>
                                <thead>
                                    <tr style='background-color: #f8fafc; border-bottom: 2px solid #e2e8f0; border-top: 1px solid #e2e8f0;'>
                                        <th style='padding: 12px 10px; font-size: 12px; font-weight: 700; text-transform: uppercase; color: #475569; text-align: left; letter-spacing: 0.5px;'>Producto</th>
                                        <th style='padding: 12px 10px; font-size: 12px; font-weight: 700; text-transform: uppercase; color: #475569; text-align: center; letter-spacing: 0.5px;' width='80'>Cantidad</th>
                                        <th style='padding: 12px 10px; font-size: 12px; font-weight: 700; text-transform: uppercase; color: #475569; text-align: right; letter-spacing: 0.5px;' width='110'>Precio</th>
                                        <th style='padding: 12px 10px; font-size: 12px; font-weight: 700; text-transform: uppercase; color: #475569; text-align: right; letter-spacing: 0.5px;' width='110'>Total</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    {$itemsHtml}
                                </tbody>
                            </table>
                        </div>

                        <!-- Liquidación y Totales Oficiales -->
                        <table role='presentation' width='100%' border='0' cellspacing='0' cellpadding='0' style='margin-bottom: 26px;'>
                            <tr>
                                <td width='55%' style='vertical-align: top; padding-right: 20px;'>
                                    <div style='background-color: #f8fafc; border: 1px solid #e2e8f0; border-radius: 8px; padding: 12px 14px;'>
                                        <span style='display: block; font-size: 11px; font-weight: 700; text-transform: uppercase; color: #64748b;'>Información al Consumidor:</span>
                                        <p style='margin: 4px 0 0 0; font-size: 12px; color: #64748b; line-height: 1.45;'>
                                            Conserve este documento como comprobante oficial de su compra. Los productos de repostería deben mantenerse refrigerados para conservar su frescura y calidad óptimas.
                                        </p>
                                    </div>
                                </td>
                                <td width='45%' style='vertical-align: top;'>
                                    <table role='presentation' width='100%' border='0' cellspacing='0' cellpadding='0' style='font-size: 13px;'>
                                        <tr>
                                            <td style='padding: 6px 0; color: #64748b;'>Subtotal Venta:</td>
                                            <td align='right' style='padding: 6px 0; font-weight: 600; color: #334155;'>$ {$subtotalFormatted}</td>
                                        </tr>
                                        <tr>
                                            <td style='padding: 6px 0; color: #64748b;'>Impoconsumo / IVA:</td>
                                            <td align='right' style='padding: 6px 0; font-weight: 600; color: #334155;'>$ 0.00 <span style='font-size: 10px; color: #94a3b8;'>(Incluido)</span></td>
                                        </tr>
                                        <tr style='border-top: 2px solid #e2e8f0;'>
                                            <td style='padding: 12px 0 4px 0; font-size: 16px; font-weight: 800; color: #0f172a;'>TOTAL PAGADO:</td>
                                            <td align='right' style='padding: 12px 0 4px 0; font-size: 20px; font-weight: 800; color: #047857;'>$ {$total}</td>
                                        </tr>
                                    </table>
                                </td>
                            </tr>
                        </table>

                    </div>

                    <!-- Pie Legal e Institucional -->
                    <div style='background-color: #fafaf9; border-top: 1px solid #e7e5e4; padding: 22px 36px; text-align: center;'>
                        <p style='margin: 0; font-size: 14px; font-weight: 600; color: #44403c;'>
                            ¡Agradecemos su compra y preferencia en " . htmlspecialchars($fromName, ENT_QUOTES, 'UTF-8') . "!
                        </p>
                        <p style='margin: 6px 0 0 0; font-size: 11px; color: #78716c; line-height: 1.45;'>
                            Documento equivalente a factura expedido de conformidad con las normas de comercio electrónico.<br>
                            Para cualquier inquietud comuníquese con nosotros al correo: <a href='mailto:dolcestock1@gmail.com' style='color: #b45309; text-decoration: none; font-weight: 600;'>dolcestock1@gmail.com</a>
                        </p>
                    </div>

                </div>

                <!-- Pie Exterior -->
                <p style='margin: 16px 0 0 0; font-size: 11px; color: #94a3b8; text-align: center;'>
                    Comprobante digital emitido automáticamente por el sistema Dolce Stock para Café La Arábica.
                </p>

            </td>
        </tr>
    </table>

</body>
</html>";

        // 1. Intentar enviar vía SMTP si está configurado
        $errorMsg = '';
        if (!empty($config['smtp_user']) && !empty($config['smtp_pass']) && !empty($config['smtp_host'])) {
            $sent = self::sendViaSmtp($config, $toEmail, $subject, $body, $errorMsg);
            if ($sent) {
                self::logEmail($idFactura, $toEmail, $subject, 'SMTP (' . ($config['smtp_host'] ?? 'Servidor') . ')');
                return [
                    'success' => true,
                    'message' => 'Factura #' . $idFactura . ' enviada con éxito a: ' . $toEmail,
                ];
            } else {
                return [
                    'success' => false,
                    'message' => 'No se pudo enviar vía SMTP: ' . $errorMsg,
                ];
            }
        }

        // 2. Intentar correo nativo PHP mail() solo si está disponible
        $headers  = "MIME-Version: 1.0\r\n";
        $headers .= "Content-Type: text/html; charset=UTF-8\r\n";
        $headers .= "Content-Transfer-Encoding: 8bit\r\n";
        $headers .= "From: {$fromName} <{$fromEmail}>\r\n";
        $headers .= "Reply-To: {$fromEmail}\r\n";

        try {
            $ok = @mail($toEmail, $subject, $body, $headers);
            self::logEmail($idFactura, $toEmail, $subject, $ok ? 'Nativo PHP mail()' : 'Log Local');
            
            if ($ok) {
                return [
                    'success' => true,
                    'message' => 'Factura #' . $idFactura . ' enviada al correo: ' . $toEmail,
                ];
            }
        } catch (Throwable $e) {
            $errorMsg = $e->getMessage();
        }

        // 3. Si no hay SMTP configurado
        return [
            'success' => false,
            'message' => 'Para que los correos lleguen a Gmail o Hotmail, configura tus credenciales SMTP en el menú "Config. Correo".',
        ];
    }

    /**
     * Envía correo usando sockets TCP/TLS directamente al servidor SMTP (Gmail, Hotmail/Outlook, etc.)
     */
    public static function sendViaSmtp(array $config, string $toEmail, string $subject, string $body, string &$errorMsg = ''): bool
    {
        $host = trim((string) ($config['smtp_host'] ?? 'smtp.gmail.com'));
        $port = (int) ($config['smtp_port'] ?? 587);
        $user = trim((string) ($config['smtp_user'] ?? ''));
        $pass = trim((string) ($config['smtp_pass'] ?? ''));
        $enc  = strtolower(trim((string) ($config['smtp_encryption'] ?? 'tls')));
        $fromEmail = !empty($config['from_email']) ? trim((string) $config['from_email']) : $user;
        $fromName  = !empty($config['from_name']) ? trim((string) $config['from_name']) : 'Dolce Stock - Facturación';

        // Si la contraseña de aplicación de Google o Microsoft fue pegada con espacios (ej. "abcd efgh ijkl mnop"), eliminarlos
        if (str_contains($host, 'gmail.com') || str_contains($host, 'outlook.com') || str_contains($host, 'office365.com') || str_contains($host, 'live.com')) {
            $pass = str_replace(' ', '', $pass);
        }

        if ($host === '' || $user === '' || $pass === '') {
            $errorMsg = 'Debes configurar tu correo y contraseña en Config. Correo.';
            return false;
        }

        // Para Hotmail / Outlook y Gmail: el remitente visible y el envelope DEBEN ser la misma cuenta autenticada
        // de lo contrario Microsoft rechaza con error 554 SendAsDenied y Google lo clasifica como spam
        $userLower = strtolower($user);
        if (str_ends_with($userLower, '@gmail.com') || 
            str_ends_with($userLower, '@hotmail.com') || 
            str_ends_with($userLower, '@outlook.com') || 
            str_ends_with($userLower, '@live.com')) {
            $fromEmail = $user;
        }

        $remoteScheme = ($enc === 'ssl') ? 'ssl://' : 'tcp://';
        $context = stream_context_create([
            'ssl' => [
                'verify_peer' => false,
                'verify_peer_name' => false,
                'allow_self_signed' => true,
            ]
        ]);

        $socket = @stream_socket_client($remoteScheme . $host . ':' . $port, $errno, $errstr, 12, STREAM_CLIENT_CONNECT, $context);
        if (!$socket) {
            $errorMsg = "No se pudo conectar a {$host}:{$port} ({$errstr}). Revisa el servidor host y puerto.";
            return false;
        }

        stream_set_timeout($socket, 15);

        $readResponse = function (int $expectedCode) use ($socket, &$errorMsg, $host, $user): bool {
            $response = '';
            while ($line = @fgets($socket, 512)) {
                $response .= $line;
                // La última línea de respuesta SMTP tiene el código seguido de un espacio (ej: '250 ')
                if (strlen($line) >= 4 && substr($line, 3, 1) === ' ') {
                    break;
                }
            }

            $meta = stream_get_meta_data($socket);
            if (!empty($meta['timed_out'])) {
                $errorMsg = "Tiempo de espera agotado con el servidor SMTP ({$host}).";
                return false;
            }

            $code = (int) substr($response, 0, 3);
            if ($code !== $expectedCode) {
                if ($code === 535) {
                    if (str_contains($host, 'gmail')) {
                        $errorMsg = "Autenticación rechazada por Gmail: Google requiere una 'Contraseña de aplicación' de 16 letras (no tu contraseña normal). Genérala en tu Cuenta de Google > Seguridad > Verificación en 2 pasos > Contraseñas de aplicaciones.";
                    } elseif (str_contains($host, 'outlook') || str_contains($host, 'office365')) {
                        $errorMsg = "Autenticación rechazada por Hotmail/Outlook ({$user}): Verifica tu correo y contraseña. Si tienes verificación en 2 pasos, genera una contraseña de aplicación en tu cuenta Microsoft.";
                    } else {
                        $errorMsg = "Usuario o contraseña rechazados por el servidor SMTP ({$response}).";
                    }
                } elseif ($code === 554 && str_contains($response, 'SendAsDenied')) {
                    $errorMsg = "Microsoft denegó el envío porque el remitente no coincide con {$user}. Se configuró automáticamente para que coincida.";
                } else {
                    $errorMsg = "Respuesta del servidor SMTP (esperado {$expectedCode}, recibido {$code}): " . trim($response);
                }
                return false;
            }
            return true;
        };

        if (!$readResponse(220)) return false;

        $clientHost = !empty($_SERVER['SERVER_NAME']) ? $_SERVER['SERVER_NAME'] : 'localhost';
        fputs($socket, "EHLO {$clientHost}\r\n");
        if (!$readResponse(250)) return false;

        if ($enc === 'tls') {
            fputs($socket, "STARTTLS\r\n");
            if (!$readResponse(220)) return false;

            $cryptoMethod = STREAM_CRYPTO_METHOD_TLS_CLIENT;
            if (defined('STREAM_CRYPTO_METHOD_TLSv1_2_CLIENT')) {
                $cryptoMethod |= STREAM_CRYPTO_METHOD_TLSv1_2_CLIENT;
            }
            if (defined('STREAM_CRYPTO_METHOD_TLSv1_3_CLIENT')) {
                $cryptoMethod |= STREAM_CRYPTO_METHOD_TLSv1_3_CLIENT;
            }

            if (!@stream_socket_enable_crypto($socket, true, $cryptoMethod)) {
                $errorMsg = "Error al iniciar cifrado TLS con {$host}.";
                return false;
            }
            fputs($socket, "EHLO {$clientHost}\r\n");
            if (!$readResponse(250)) return false;
        }

        // AUTH LOGIN
        fputs($socket, "AUTH LOGIN\r\n");
        if (!$readResponse(334)) return false;

        fputs($socket, base64_encode($user) . "\r\n");
        if (!$readResponse(334)) return false;

        fputs($socket, base64_encode($pass) . "\r\n");
        if (!$readResponse(235)) return false;

        // MAIL FROM / RCPT TO / DATA
        fputs($socket, "MAIL FROM: <{$fromEmail}>\r\n");
        if (!$readResponse(250)) return false;

        fputs($socket, "RCPT TO: <{$toEmail}>\r\n");
        if (!$readResponse(250)) return false;

        fputs($socket, "DATA\r\n");
        if (!$readResponse(354)) return false;

        // Cabeceras estándar para máxima entregabilidad en Gmail y Hotmail (RFC 5322)
        $encodedSubject = '=?UTF-8?B?' . base64_encode($subject) . '?=';
        $encodedFromName = '=?UTF-8?B?' . base64_encode($fromName) . '?=';
        $domainPart = explode('@', $fromEmail)[1] ?? 'dolcestock.local';
        $messageId = '<' . time() . '.' . bin2hex(random_bytes(8)) . '@' . $domainPart . '>';

        $headers  = "MIME-Version: 1.0\r\n";
        $headers .= "Content-Type: text/html; charset=UTF-8\r\n";
        $headers .= "Content-Transfer-Encoding: 8bit\r\n";
        $headers .= "From: {$encodedFromName} <{$fromEmail}>\r\n";
        $headers .= "Reply-To: {$fromEmail}\r\n";
        $headers .= "To: <{$toEmail}>\r\n";
        $headers .= "Subject: {$encodedSubject}\r\n";
        $headers .= "Date: " . date('r') . "\r\n";
        $headers .= "Message-ID: {$messageId}\r\n";
        $headers .= "X-Mailer: DolceStock-System/1.0\r\n";
        $headers .= "Auto-Submitted: auto-generated\r\n";

        $dataPayload = $headers . "\r\n" . $body . "\r\n.\r\n";
        fputs($socket, $dataPayload);
        if (!$readResponse(250)) return false;

        fputs($socket, "QUIT\r\n");
        @fclose($socket);
        return true;
    }

    private static function logEmail(int $idFactura, string $toEmail, string $subject, string $metodo): void
    {
        $logDir = dirname(__DIR__, 2) . '/storage/logs';
        if (!is_dir($logDir)) {
            @mkdir($logDir, 0777, true);
        }
        @file_put_contents(
            $logDir . '/sent_emails.log',
            "[" . date('Y-m-d H:i:s') . "] Factura #{$idFactura} | Método: {$metodo} | Destinatario: {$toEmail} | Asunto: {$subject}\n",
            FILE_APPEND
        );
    }
}
