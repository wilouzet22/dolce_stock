/**
 * Dolce Café — Buscador en tiempo real para tablas
 * 
 * Busca automáticamente cualquier <input> con la clase `.table-search`
 * y filtra las filas del <tbody> de la tabla dentro del mismo `.card`.
 * 
 * Uso en las vistas:
 *   <input type="text" class="table-search" placeholder="Buscar..." data-table="ID_DE_LA_TABLA">
 *   <table class="data" id="ID_DE_LA_TABLA"> ...
 */
(function () {
    'use strict';

    document.addEventListener('DOMContentLoaded', function () {
        const inputs = document.querySelectorAll('.table-search');
        if (!inputs.length) return;

        inputs.forEach(function (input) {
            const tableId = input.getAttribute('data-table');
            const table = tableId
                ? document.getElementById(tableId)
                : input.closest('.card')?.querySelector('table.data');

            if (!table) return;

            const tbody = table.querySelector('tbody');
            if (!tbody) return;

            // Referencia al contador (opcional)
            const counter = input.parentNode.querySelector('.search-count');

            input.addEventListener('input', function () {
                const query = this.value.toLowerCase().trim();
                const rows = tbody.querySelectorAll('tr');
                let visible = 0;

                rows.forEach(function (row) {
                    const text = row.textContent.toLowerCase();
                    const match = query === '' || text.indexOf(query) !== -1;
                    row.style.display = match ? '' : 'none';
                    if (match) visible++;
                });

                // Actualizar contador de resultados
                if (counter) {
                    counter.textContent = query === ''
                        ? ''
                        : visible + ' resultado' + (visible !== 1 ? 's' : '');
                }

                // Highlight animado: marcar la tabla si hay filtro activo
                if (query !== '') {
                    table.classList.add('is-filtered');
                } else {
                    table.classList.remove('is-filtered');
                }
            });

            // Limpiar con Escape
            input.addEventListener('keydown', function (e) {
                if (e.key === 'Escape') {
                    this.value = '';
                    this.dispatchEvent(new Event('input'));
                    this.blur();
                }
            });
        });
    });
})();
