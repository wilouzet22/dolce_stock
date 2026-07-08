<form method="post" action="<?= e(url('auth', 'login')) ?>">
    <div class="form-group">
        <label for="email">Correo</label>
        <input id="email" name="email" type="email" required autocomplete="email" value="<?= e((string) ($_POST['email'] ?? '')) ?>">
    </div>
    <div class="form-group">
        <label for="password">Contraseña</label>
        <input id="password" name="password" type="password" required autocomplete="current-password">
    </div>
    <div class="form-actions auth-actions">
        <button type="submit" class="btn btn--primary" <?= isset($authReady) && !$authReady ? 'disabled' : '' ?>>Entrar</button>
    </div>
</form>
<p class="auth-switch">¿Nuevo/a en el equipo? <a href="<?= e(url('auth', 'register')) ?>" class="link-muted">Creá tu cuenta</a></p>
