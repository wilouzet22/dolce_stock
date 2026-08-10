        </main>
        <footer class="site-footer">
            Dolce Café &copy; <?= date('Y') ?> · Sistema de Inventario y Ventas
        </footer>
    </div>
</div>

<script>
document.addEventListener('DOMContentLoaded', function() {
    var btn = document.getElementById('themeToggleBtn');
    var iconContainer = document.getElementById('themeIcon');
    var labelContainer = document.getElementById('themeLabel');

    var sunIcon = '<svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><circle cx="12" cy="12" r="5"></circle><line x1="12" y1="1" x2="12" y2="3"></line><line x1="12" y1="21" x2="12" y2="23"></line><line x1="4.22" y1="4.22" x2="5.64" y2="5.64"></line><line x1="18.36" y1="18.36" x2="19.78" y2="19.78"></line><line x1="1" y1="12" x2="3" y2="12"></line><line x1="21" y1="12" x2="23" y2="12"></line><line x1="4.22" y1="19.78" x2="5.64" y2="18.36"></line><line x1="18.36" y1="5.64" x2="19.78" y2="4.22"></line></svg>';
    var moonIcon = '<svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M21 12.79A9 9 0 1 1 11.21 3 7 7 0 0 0 21 12.79z"></path></svg>';

    function updateUI(theme) {
        if (theme === 'dark') {
            if (iconContainer) iconContainer.innerHTML = sunIcon;
            if (labelContainer) labelContainer.textContent = 'Modo Claro';
        } else {
            if (iconContainer) iconContainer.innerHTML = moonIcon;
            if (labelContainer) labelContainer.textContent = 'Modo Oscuro';
        }
    }

    var currentTheme = document.documentElement.getAttribute('data-theme') || 'light';
    updateUI(currentTheme);

    if (btn) {
        btn.addEventListener('click', function() {
            var active = document.documentElement.getAttribute('data-theme');
            var next = active === 'dark' ? 'light' : 'dark';
            document.documentElement.setAttribute('data-theme', next);
            localStorage.setItem('dolce_theme', next);
            updateUI(next);
        });
    }
});
</script>
</body>
</html>
