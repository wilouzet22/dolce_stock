<?php
declare(strict_types=1);
namespace App\Controllers;
use App\Core\Controller;
use App\Models\AdminModel;
use App\Helpers\MailHelper;
use RuntimeException;
use Throwable;

final class InvoiceController extends Controller
{
    public function index(): void
    {
        $m = new AdminModel();
        $this->render('invoices/index', ['pageTitle' => 'Facturas', 'lista' => $m->invoiceList()]);
    }

    public function create(): void
    {
        $m = new AdminModel();
        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            $idc = ($_POST['id_cliente'] ?? '') !== '' ? (int) $_POST['id_cliente'] : null;
            $cliIdent = trim((string) ($_POST['cli_identificacion'] ?? ''));
            $cliNombre = trim((string) ($_POST['cli_nombre'] ?? ''));
            $cliApellidos = trim((string) ($_POST['cli_apellidos'] ?? ''));
            $cliEmail = trim((string) ($_POST['cli_email'] ?? ''));
            $cliDir = trim((string) ($_POST['cli_direccion'] ?? ''));

            // Auto-guardar o actualizar cliente
            $idc = $m->saveOrUpdateClientFromInvoice($idc, $cliIdent, $cliNombre, $cliApellidos, $cliEmail, $cliDir);

            $ide = ($_POST['id_empleado'] ?? '') !== '' ? (int) $_POST['id_empleado'] : null;
            if ($ide === null && isset($_SESSION['empleado_id']) && (int) $_SESSION['empleado_id'] > 0) {
                $ide = (int) $_SESSION['empleado_id'];
            }
            $pids = $_POST['prod_id'] ?? [];
            $qtys = $_POST['prod_qty'] ?? [];
            $lines = [];
            foreach ($pids as $i => $pid) {
                $pid = (int) $pid;
                $q = isset($qtys[$i]) ? (int) $qtys[$i] : 0;
                if ($pid > 0 && $q > 0) {
                    $lines[] = ['producto' => $pid, 'cantidad' => $q];
                }
            }
            try {
                $id = $m->createInvoice($idc, $ide, $lines);

                // Enviar comprobante por correo electrónico si el cliente tiene email
                $factura = $m->invoiceById($id);
                $detalles = $m->invoiceDetails($id);
                $mailResult = ['success' => false, 'message' => ''];
                $targetEmail = !empty($factura['cliente_email']) ? trim((string) $factura['cliente_email']) : $cliEmail;

                $shouldSendMail = !empty($_POST['send_email_now']);
                if (!empty($targetEmail) && $shouldSendMail) {
                    $mailResult = MailHelper::sendInvoiceEmail($factura, $detalles, $targetEmail);
                }

                if (!empty($mailResult['success'])) {
                    flash('ok', '✅ Factura #' . $id . ' emitida con éxito y enviada a ' . $targetEmail . '.');
                } elseif (!empty($targetEmail) && $shouldSendMail) {
                    flash('ok', 'Factura #' . $id . ' registrada. ⚠️ Aviso de correo: ' . $mailResult['message']);
                } else {
                    flash('ok', 'Factura #' . $id . ' registrada exitosamente.');
                }

                $this->redirect('invoice', 'show', ['id' => $id]);
            } catch (Throwable $e) {
                flash('err', $e instanceof RuntimeException ? $e->getMessage() : 'Error al guardar factura.');
                $this->redirect('invoice', 'create');
            }
        }
        $configFile = dirname(__DIR__, 2) . '/config/mail.php';
        $mailConfig = file_exists($configFile) ? (include $configFile) : [];
        $mailConfigured = !empty($mailConfig['smtp_user']) && !empty($mailConfig['smtp_pass']);

        $this->render('invoices/create', [
            'pageTitle' => 'Nueva factura',
            'clientes' => $m->all('clientes', 'nombre'),
            'empleados' => $m->all('empleados', 'nombre'),
            'productos' => $m->productsSimple(),
            'mailConfigured' => $mailConfigured,
            'smtpUser' => $mailConfig['smtp_user'] ?? '',
        ]);
    }

    public function show(): void
    {
        $id = (int) ($_GET['id'] ?? 0);
        $m = new AdminModel();
        $factura = $m->invoiceById($id);
        if (!$factura) {
            flash('err', 'Factura no encontrada.');
            $this->redirect('invoice');
        }
        $this->render('invoices/show', ['pageTitle' => 'Factura #' . $id, 'factura' => $factura, 'detalles' => $m->invoiceDetails($id)]);
    }

    public function ticket(): void
    {
        $id = (int) ($_GET['id'] ?? 0);
        $m = new AdminModel();
        $factura = $m->invoiceById($id);
        if (!$factura) {
            exit('Factura no encontrada.');
        }
        $detalles = $m->invoiceDetails($id);
        
        // Se requiere directamente la vista sin el layout de admin
        require dirname(__DIR__) . '/Views/invoices/ticket.php';
    }

    public function sendMail(): void
    {
        $id = (int) ($_POST['id'] ?? $_GET['id'] ?? 0);
        $email = trim((string) ($_POST['email'] ?? ''));

        $m = new AdminModel();
        $factura = $m->invoiceById($id);
        if (!$factura) {
            flash('err', 'Factura no encontrada.');
            $this->redirect('invoice');
        }

        $detalles = $m->invoiceDetails($id);
        $toEmail = $email !== '' ? $email : (string) ($factura['cliente_email'] ?? '');

        if ($toEmail === '' || !filter_var($toEmail, FILTER_VALIDATE_EMAIL)) {
            flash('err', 'Debes ingresar una dirección de correo válida para enviar la factura.');
            $this->redirect('invoice', 'show', ['id' => $id]);
        }

        $result = MailHelper::sendInvoiceEmail($factura, $detalles, $toEmail);

        if (!empty($result['success'])) {
            flash('ok', '✅ Factura #' . $id . ' enviada exitosamente al correo del cliente (' . $toEmail . ').');
        } else {
            flash('err', '⚠️ ' . $result['message']);
        }

        $this->redirect('invoice', 'show', ['id' => $id]);
    }
}
