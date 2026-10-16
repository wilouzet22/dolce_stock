<div class="card" style="max-width:720px; margin:0 auto;">
    <div class="card-head">
        <h2 class="card-title">Servicio Central de Correo y Facturación</h2>
        <a class="btn btn--ghost" href="<?= e(url('dashboard')) ?>">Volver al Panel</a>
    </div>

    <div style="background:var(--bg-surface-hover); border:1px solid #10b981; border-radius:var(--radius-lg); padding:1.5rem; margin-top:1rem;">
        <div style="display:flex; align-items:center; gap:0.75rem; margin-bottom:1rem;">
            <div style="width:40px; height:40px; border-radius:50%; background:#dcfce7; display:flex; align-items:center; justify-content:center; color:#166534; font-size:1.3rem;">
                🔒
            </div>
            <div>
                <h3 style="margin:0; font-size:1.1rem; color:var(--text-main);">Configuración Blindada en Código</h3>
                <span style="font-size:0.85rem; color:#10b981; font-weight:600;">🟢 Estado: Activo y en Funcionamiento</span>
            </div>
        </div>

        <p style="color:var(--text-muted); font-size:0.92rem; line-height:1.6; margin-bottom:1.25rem;">
            Este sistema cuenta con una cuenta central fija y segura configurada directamente en el software. <strong>Por motivos de seguridad, las credenciales no pueden ser alteradas desde el navegador por ningún usuario ni empleado.</strong>
        </p>

        <div style="background:var(--bg-card); border:1px solid var(--border-color); border-radius:var(--radius-md); padding:1rem; display:grid; grid-template-columns: repeat(auto-fit, minmax(200px, 1fr)); gap:1rem;">
            <div>
                <span style="font-size:0.78rem; text-transform:uppercase; color:var(--text-muted); font-weight:700;">Remitente Central</span>
                <div style="font-weight:600; font-size:0.95rem; color:var(--text-main); margin-top:0.25rem;">Café La Arábica</div>
            </div>
            <div>
                <span style="font-size:0.78rem; text-transform:uppercase; color:var(--text-muted); font-weight:700;">Buzón Emisor</span>
                <div style="font-weight:600; font-size:0.95rem; color:var(--accent); margin-top:0.25rem;">dolcestock1@gmail.com</div>
            </div>
            <div>
                <span style="font-size:0.78rem; text-transform:uppercase; color:var(--text-muted); font-weight:700;">Servidor SMTP</span>
                <div style="font-weight:600; font-size:0.95rem; color:var(--text-main); margin-top:0.25rem;">smtp.gmail.com:587 (TLS)</div>
            </div>
            <div>
                <span style="font-size:0.78rem; text-transform:uppercase; color:var(--text-muted); font-weight:700;">Entrega a Clientes</span>
                <div style="font-weight:600; font-size:0.95rem; color:#10b981; margin-top:0.25rem;">Gmail, Hotmail, Yahoo, etc.</div>
            </div>
        </div>

        <div style="margin-top:1.5rem; text-align:right;">
            <a href="<?= e(url('dashboard')) ?>" class="btn btn--primary">Entendido, regresar</a>
        </div>
    </div>
</div>
