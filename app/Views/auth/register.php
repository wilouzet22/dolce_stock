<form method="post" action="<?= e(url('auth', 'register')) ?>">
    <?php if (isset($adminsCount) && $adminsCount === 0): ?>
        <div class="flash flash--warning" style="background:var(--warning-bg); border-color:var(--warning-border); color:var(--warning); margin-bottom:1.5rem;">
            <svg xmlns="http://www.w3.org/2000/svg" width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" style="flex-shrink:0"><path d="M10.29 3.86L1.82 18a2 2 0 0 0 1.71 3h16.94a2 2 0 0 0 1.71-3L13.71 3.86a2 2 0 0 0-3.42 0z"></path><line x1="12" y1="9" x2="12" y2="13"></line><line x1="12" y1="17" x2="12.01" y2="17"></line></svg>
            <span>Configuración Inicial: Esta primera cuenta será registrada como <strong>Administrador Principal</strong>.</span>
        </div>
    <?php endif; ?>

    <div class="form-group">
        <label for="nombre">Nombre Completo</label>
        <input id="nombre" name="nombre" required maxlength="150" placeholder="Ej: Juan Pérez" value="<?= e((string) ($_POST['nombre'] ?? '')) ?>">
    </div>

    <div class="form-group">
        <label for="cargo">Cargo <span class="optional">(opcional)</span></label>
        <input id="cargo" name="cargo" maxlength="100" placeholder="Ej: Barista, Administrador, Cajero..." value="<?= e((string) ($_POST['cargo'] ?? '')) ?>">
    </div>

    <div class="form-group">
        <label for="email">Correo Electrónico (usuario)</label>
        <input id="email" name="email" type="email" required maxlength="150" autocomplete="email" placeholder="usuario@dolcecafe.com" value="<?= e((string) ($_POST['email'] ?? '')) ?>">
    </div>
    
    <?php if (isset($adminsCount, $isAdmin) && $adminsCount > 0 && $isAdmin): ?>
    <div class="form-group">
        <label for="rol">Rol de la cuenta</label>
        <select id="rol" name="rol" required>
            <option value="cajero" <?= (($_POST['rol'] ?? '') === 'cajero') ? 'selected' : '' ?>>Cajero (Acceso Restringido: Ventas y Clientes)</option>
            <option value="admin" <?= (($_POST['rol'] ?? '') === 'admin') ? 'selected' : '' ?>>Administrador (Acceso Total)</option>
        </select>
    </div>
    <?php endif; ?>

    <div class="form-group">
        <label for="password">Contraseña</label>
        <input id="password" name="password" type="password" required minlength="6" autocomplete="new-password" placeholder="••••••••">
        <span class="helper">Mínimo 6 caracteres</span>
    </div>

    <div class="form-group">
        <label for="password_confirm">Confirmar Contraseña</label>
        <input id="password_confirm" name="password_confirm" type="password" required minlength="6" autocomplete="new-password" placeholder="••••••••">
    </div>

    <div class="form-actions auth-actions" style="margin-top:1.75rem;">
        <button type="submit" class="btn btn--primary" style="width:100%; justify-content:center;" <?= isset($authReady) && !$authReady ? 'disabled' : '' ?>>
            <svg xmlns="http://www.w3.org/2000/svg" width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><polyline points="20 6 9 17 4 12"></polyline></svg>
            <?= (isset($isAdmin) && $isAdmin) ? 'Crear Empleado' : 'Completar Registro' ?>
        </button>
    </div>
</form>

<?php if (empty($isAdmin)): ?>
<p class="auth-switch" style="text-align:center; margin-top:1.5rem;">
    <a href="<?= e(url('auth', 'login')) ?>" class="link-muted">¿Ya tienes cuenta? Iniciar Sesión</a>
</p>
<?php endif; ?>
