            </div><!-- /.cup-body-content -->
        </div><!-- /.coffee-cup-card -->

        <!-- Plato base / Platito de la taza de café -->
        <div class="cup-saucer" aria-hidden="true"></div>

        <p class="auth-footer">Dolce Cafe &copy; <?= date('Y') ?> &middot; Sistema de Gestion</p>
    </main><!-- /.coffee-cup-auth-wrapper -->
</div><!-- /.auth-shell -->

<script>
document.addEventListener('DOMContentLoaded', function() {
    /* ── Toggle de Modo Claro / Modo Oscuro ───────────────── */
    var btn = document.getElementById('themeToggleBtn');
    var iconContainer = document.getElementById('themeIcon');
    var labelContainer = document.getElementById('themeLabel');

    var sunIcon = '<svg xmlns="http://www.w3.org/2000/svg" width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><circle cx="12" cy="12" r="5"></circle><line x1="12" y1="1" x2="12" y2="3"></line><line x1="12" y1="21" x2="12" y2="23"></line><line x1="4.22" y1="4.22" x2="5.64" y2="5.64"></line><line x1="18.36" y1="18.36" x2="19.78" y2="19.78"></line><line x1="1" y1="12" x2="3" y2="12"></line><line x1="21" y1="12" x2="23" y2="12"></line><line x1="4.22" y1="19.78" x2="5.64" y2="18.36"></line><line x1="18.36" y1="5.64" x2="19.78" y2="4.22"></line></svg>';
    var moonIcon = '<svg xmlns="http://www.w3.org/2000/svg" width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M21 12.79A9 9 0 1 1 11.21 3 7 7 0 0 0 21 12.79z"></path></svg>';

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

    /* ── Animación de fondo: granos de café y tazas ──────── */
    var canvas = document.getElementById('auth-bg-canvas');
    if (!canvas) return;

    function beanSVG(size, color, rot) {
        return '<svg width="' + size + '" height="' + Math.round(size * 0.62) + '" viewBox="0 0 56 35" fill="none" xmlns="http://www.w3.org/2000/svg" style="transform:rotate(' + rot + 'deg); display:block;">'
            + '<ellipse cx="28" cy="17.5" rx="26" ry="15.5" fill="' + color + '" opacity="0.88"/>'
            + '<path d="M28 3 Q36 17.5 28 32 Q20 17.5 28 3Z" fill="none" stroke="rgba(255,255,255,0.22)" stroke-width="1.5"/>'
            + '</svg>';
    }

    function cupSVG(size, color) {
        var s = size;
        return '<svg width="' + s + '" height="' + Math.round(s * 1.0) + '" viewBox="0 0 48 48" fill="none" xmlns="http://www.w3.org/2000/svg" style="display:block;">'
            + '<rect x="6" y="14" width="30" height="22" rx="5" fill="' + color + '" opacity="0.82"/>'
            + '<path d="M36 19 Q46 19 46 26 Q46 33 36 33" stroke="' + color + '" stroke-width="3.5" stroke-linecap="round" fill="none" opacity="0.75"/>'
            + '<rect x="4" y="36" width="34" height="4" rx="2" fill="' + color + '" opacity="0.6"/>'
            + '<ellipse cx="21" cy="14" rx="15" ry="4" fill="rgba(255,255,255,0.14)"/>'
            + '<path d="M14 8 Q16 4 18 8" stroke="' + color + '" stroke-width="1.8" stroke-linecap="round" fill="none" opacity="0.5"/>'
            + '<path d="M21 5 Q23 1 25 5" stroke="' + color + '" stroke-width="1.8" stroke-linecap="round" fill="none" opacity="0.5"/>'
            + '</svg>';
    }

    var lightBeanColors = ['#8B5230','#A0622A','#7A4520','#C17F48','#6B3D1A'];
    var darkBeanColors  = ['#3D1D0A','#5A2A12','#2A1005','#7A4520','#4A2010'];
    var lightCupColors  = ['#8B5230','#A0622A','#7A4520','#C17F48'];
    var darkCupColors   = ['#3D1D0A','#5A2A12','#4A2010','#7A4520'];

    function getColors() {
        var dark = document.documentElement.getAttribute('data-theme') === 'dark';
        return {
            beans: dark ? darkBeanColors  : lightBeanColors,
            cups:  dark ? darkCupColors   : lightCupColors
        };
    }

    var items = [
        { type:'bean', x:4,  y:8,  size:38, rot:15,  dur:7.2,  delay:0,    anim:'floatBean' },
        { type:'bean', x:12, y:75, size:44, rot:-20, dur:8.5,  delay:1.2,  anim:'driftBean' },
        { type:'bean', x:22, y:30, size:30, rot:35,  dur:6.8,  delay:0.5,  anim:'floatBean' },
        { type:'bean', x:72, y:12, size:42, rot:-10, dur:9.0,  delay:2.0,  anim:'driftBean' },
        { type:'bean', x:82, y:60, size:36, rot:25,  dur:7.5,  delay:0.8,  anim:'floatBean' },
        { type:'bean', x:90, y:85, size:48, rot:-30, dur:8.2,  delay:3.1,  anim:'driftBean' },
        { type:'bean', x:55, y:88, size:34, rot:50,  dur:6.5,  delay:1.8,  anim:'floatBean' },
        { type:'bean', x:38, y:5,  size:40, rot:-15, dur:7.8,  delay:0.3,  anim:'driftBean' },
        { type:'bean', x:65, y:40, size:28, rot:60,  dur:9.5,  delay:2.5,  anim:'floatBean' },
        { type:'bean', x:8,  y:50, size:32, rot:-45, dur:8.0,  delay:4.0,  anim:'driftBean' },
        { type:'bean', x:48, y:65, size:36, rot:20,  dur:7.0,  delay:1.5,  anim:'floatBean' },
        { type:'bean', x:78, y:82, size:30, rot:-55, dur:8.8,  delay:2.8,  anim:'driftBean' },
        { type:'bean', x:30, y:92, size:44, rot:40,  dur:6.2,  delay:0.7,  anim:'floatBean' },
        { type:'bean', x:92, y:22, size:38, rot:-25, dur:9.2,  delay:3.5,  anim:'driftBean' },
        { type:'bean', x:18, y:18, size:26, rot:70,  dur:7.4,  delay:1.0,  anim:'floatBean' },
        { type:'bean', x:60, y:20, size:34, rot:-8,  dur:8.6,  delay:2.2,  anim:'driftBean' },
        { type:'cup',  x:6,  y:38, size:40, rot:0,   dur:9.8,  delay:0.6,  anim:'floatCup'  },
        { type:'cup',  x:85, y:32, size:48, rot:0,   dur:10.2, delay:2.4,  anim:'floatCup'  },
        { type:'cup',  x:42, y:78, size:36, rot:0,   dur:8.9,  delay:1.6,  anim:'floatCup'  },
        { type:'cup',  x:70, y:68, size:44, rot:0,   dur:11.0, delay:3.8,  anim:'floatCup'  },
        { type:'cup',  x:25, y:55, size:32, rot:0,   dur:9.4,  delay:0.9,  anim:'floatCup'  },
        { type:'cup',  x:94, y:52, size:38, rot:0,   dur:10.5, delay:4.2,  anim:'floatCup'  },
    ];

    var colors = getColors();

    items.forEach(function(item, i) {
        var div = document.createElement('div');
        div.className = 'auth-bg-item';
        div.style.left  = item.x + '%';
        div.style.top   = item.y + '%';
        div.style.animationName     = item.anim;
        div.style.animationDuration = item.dur + 's';
        div.style.animationDelay    = item.delay + 's';

        var colorArr = item.type === 'bean' ? colors.beans : colors.cups;
        var color = colorArr[i % colorArr.length];

        if (item.type === 'bean') {
            div.innerHTML = beanSVG(item.size, color, item.rot);
        } else {
            div.innerHTML = cupSVG(item.size, color);
        }

        canvas.appendChild(div);
    });

    if (btn) {
        btn.addEventListener('click', function() {
            var newColors = getColors();
            var beanEls = canvas.querySelectorAll('.auth-bg-item');
            beanEls.forEach(function(el, i) {
                var item = items[i];
                if (!item) return;
                var colorArr = item.type === 'bean' ? newColors.beans : newColors.cups;
                var color = colorArr[i % colorArr.length];
                if (item.type === 'bean') {
                    el.innerHTML = beanSVG(item.size, color, item.rot);
                } else {
                    el.innerHTML = cupSVG(item.size, color);
                }
            });
        });
    }
});
</script>
</body>
</html>
