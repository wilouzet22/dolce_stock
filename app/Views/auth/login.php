<form method="post" action="<?= e(url('auth', 'login')) ?>">
    <div class="form-group">
        <label for="email">Correo Electrónico</label>
        <input id="email" name="email" type="email" required autocomplete="email" placeholder="ejemplo@dolcecafe.com" value="<?= e((string) ($_POST['email'] ?? '')) ?>">
    </div>
    <div class="form-group">
        <label for="password">Contraseña</label>
        <input id="password" name="password" type="password" required autocomplete="current-password" placeholder="••••••••">
    </div>
    <div class="form-actions auth-actions" style="margin-top:1.75rem;">
        <button type="submit" class="btn btn--primary" style="width:100%; justify-content:center;" <?= isset($authReady) && !$authReady ? 'disabled' : '' ?>>
            <svg xmlns="http://www.w3.org/2000/svg" width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M15 3h4a2 2 0 0 1 2 2v14a2 2 0 0 1-2 2h-4"></path><polyline points="10 17 15 12 10 7"></polyline><line x1="15" y1="12" x2="3" y2="12"></line></svg>
            Iniciar Sesión
        </button>
    </div>
</form>
