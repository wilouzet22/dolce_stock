<?php
// Preparar datos para los gráficos (se inyectan como JSON al JavaScript)
$salesLabels = [];
$salesData   = [];
foreach ($salesHistory as $row) {
    $salesLabels[] = date('d M', strtotime($row['fecha']));
    $salesData[]   = round((float) $row['total'], 2);
}

$topLabels = [];
$topData   = [];
foreach ($topProducts as $row) {
    $topLabels[] = $row['nombre'];
    $topData[]   = (int) $row['cantidad'];
}
?>

<!-- ═══════ WELCOME BANNER ═══════ -->
<div class="welcome-banner">
    <div class="welcome-content">
        <h2>Hola, <?= e((string) ($_SESSION['empleado_nombre'] ?? 'Equipo')) ?></h2>
        <p>Aquí tienes el resumen de la actividad de tu Dolce Café el día de hoy.</p>
    </div>
    <div class="welcome-img">
        <img src="assets/img/cafe_illustration.png" alt="Dolce Café Illustration">
    </div>
</div>

<div class="grid-stats">
    <div class="stat-card">
        <div class="stat-info">
            <div class="stat-value"><?= (int) $stats['productos'] ?></div>
            <div class="stat-label">Productos registrados</div>
        </div>
        <div class="stat-icon">
            <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M12 2L2 7l10 5 10-5-10-5z"></path><path d="M2 17l10 5 10-5"></path><path d="M2 12l10 5 10-5"></path></svg>
        </div>
    </div>
    <div class="stat-card" style="<?= (int) $stats['stock_bajo'] > 0 ? 'border-color: var(--danger-border);' : '' ?>">
        <div class="stat-info">
            <div class="stat-value" style="<?= (int) $stats['stock_bajo'] > 0 ? 'color: var(--danger);' : '' ?>"><?= (int) $stats['stock_bajo'] ?></div>
            <div class="stat-label" style="<?= (int) $stats['stock_bajo'] > 0 ? 'color: var(--danger);' : '' ?>">Stock crítico (≤ 5)</div>
        </div>
        <div class="stat-icon" style="<?= (int) $stats['stock_bajo'] > 0 ? 'background: var(--danger-bg); color: var(--danger);' : '' ?>">
            <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M10.29 3.86L1.82 18a2 2 0 0 0 1.71 3h16.94a2 2 0 0 0 1.71-3L13.71 3.86a2 2 0 0 0-3.42 0z"></path><line x1="12" y1="9" x2="12" y2="13"></line><line x1="12" y1="17" x2="12.01" y2="17"></line></svg>
        </div>
    </div>
    <div class="stat-card">
        <div class="stat-info">
            <div class="stat-value">$ <?= number_format((float) $stats['ventas_hoy'], 2, '.', ',') ?></div>
            <div class="stat-label">Ventas de hoy</div>
        </div>
        <div class="stat-icon">
            <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><line x1="12" y1="1" x2="12" y2="23"></line><path d="M17 5H9.5a3.5 3.5 0 0 0 0 7h5a3.5 3.5 0 0 1 0 7H6"></path></svg>
        </div>
    </div>
    <div class="stat-card">
        <div class="stat-info">
            <div class="stat-value"><?= (int) $stats['facturas_total'] ?></div>
            <div class="stat-label">Facturas registradas</div>
        </div>
        <div class="stat-icon">
            <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M14 2H6a2 2 0 0 0-2 2v16a2 2 0 0 0 2 2h12a2 2 0 0 0 2-2V8z"></path><polyline points="14 2 14 8 20 8"></polyline><line x1="16" y1="13" x2="8" y2="13"></line><line x1="16" y1="17" x2="8" y2="17"></line><polyline points="10 9 9 9 8 9"></polyline></svg>
        </div>
    </div>
</div>

<!-- ═══════ GRÁFICOS INTERACTIVOS ═══════ -->
<div class="charts-row">
    <div class="card chart-card chart-card--wide">
        <div class="card-head">
            <h2 class="card-title">
                <svg xmlns="http://www.w3.org/2000/svg" width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" style="vertical-align:-3px;margin-right:6px;color:var(--accent);"><polyline points="22 12 18 12 15 21 9 3 6 12 2 12"></polyline></svg>
                Ventas — Últimos 30 días
            </h2>
            <span class="chart-badge">
                <svg xmlns="http://www.w3.org/2000/svg" width="14" height="14" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><circle cx="12" cy="12" r="10"></circle><polyline points="12 6 12 12 16 14"></polyline></svg>
                Actualizado hoy
            </span>
        </div>
        <div class="chart-container">
            <canvas id="chartSales"></canvas>
        </div>
        <?php if (empty($salesHistory)): ?>
        <p class="chart-empty">Aún no hay datos de ventas para graficar.</p>
        <?php endif; ?>
    </div>

    <div class="card chart-card">
        <div class="card-head">
            <h2 class="card-title">
                <svg xmlns="http://www.w3.org/2000/svg" width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" style="vertical-align:-3px;margin-right:6px;color:var(--accent);"><path d="M18 20V10"></path><path d="M12 20V4"></path><path d="M6 20v-6"></path></svg>
                Productos más vendidos
            </h2>
        </div>
        <div class="chart-container chart-container--doughnut">
            <canvas id="chartTopProducts"></canvas>
        </div>
        <?php if (empty($topProducts)): ?>
        <p class="chart-empty">Sin datos de ventas de productos.</p>
        <?php endif; ?>
    </div>
</div>

<div class="two-col">
    <div class="card">
        <div class="card-head">
            <h2 class="card-title">Últimas ventas</h2>
            <a class="btn btn--ghost btn--sm" href="<?= e(url('invoice', 'create')) ?>">+ Nueva factura</a>
        </div>
        <?php if ($ultimasFacturas === []) : ?>
            <p class="link-muted">Aún no hay facturas.</p>
        <?php else : ?>
            <div class="table-wrap">
                <table class="table">
                    <thead>
                        <tr>
                            <th>#</th>
                            <th>Fecha</th>
                            <th>Cliente</th>
                            <th class="num">Total</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php foreach ($ultimasFacturas as $f) : ?>
                            <tr>
                                <td>
                                    <a class="btn btn--ghost btn--sm" href="<?= e(url('invoice', 'show', ['id' => (int) $f['id_factura']])) ?>">
                                        #<?= (int) $f['id_factura'] ?>
                                    </a>
                                </td>
                                <td><?= e($f['fecha']) ?></td>
                                <td><?= e($f['cliente'] ?? '—') ?></td>
                                <td class="num" style="font-weight:700; color:var(--accent);">$ <?= number_format((float) $f['total'], 2, '.', ',') ?></td>
                            </tr>
                        <?php endforeach; ?>
                    </tbody>
                </table>
            </div>
        <?php endif; ?>
    </div>

    <div class="card">
        <div class="card-head">
            <h2 class="card-title">Stock más bajo</h2>
            <a class="btn btn--ghost btn--sm" href="<?= e(url('inventory')) ?>">Movimientos →</a>
        </div>
        <?php if ($stockAlerta === []) : ?>
            <p class="link-muted">No hay productos todavía.</p>
        <?php else : ?>
            <div class="table-wrap">
                <table class="table">
                    <thead>
                        <tr>
                            <th>Producto</th>
                            <th>Categoría</th>
                            <th class="num">Stock</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php foreach ($stockAlerta as $p) : ?>
                            <tr>
                                <td style="font-weight:600;"><?= e($p['nombre']) ?></td>
                                <td><span class="badge" style="background:var(--accent-soft);color:var(--accent);"><?= e($p['categoria']) ?></span></td>
                                <td class="num">
                                    <span class="badge <?= (int)$p['stock'] <= 5 ? 'badge--danger' : 'badge--warning' ?>">
                                        <?= (int) $p['stock'] ?> uds
                                    </span>
                                </td>
                            </tr>
                        <?php endforeach; ?>
                    </tbody>
                </table>
            </div>
        <?php endif; ?>
    </div>
</div>

<!-- ═══════ CHART.JS INITIALIZATION ═══════ -->
<script>
document.addEventListener('DOMContentLoaded', function () {

    /* ── Paleta "Coffee House" para los gráficos ── */
    const coffeePalette = [
        'hsl(22, 58%, 52%)',   // Accent (burnt orange)
        'hsl(28, 45%, 30%)',   // Dark roast
        'hsl(38, 75%, 55%)',   // Golden
        'hsl(150, 40%, 38%)', // Sage green
        'hsl(28, 35%, 50%)',   // Medium roast
        'hsl(352, 55%, 45%)', // Berry
        'hsl(28, 20%, 65%)',   // Latte
        'hsl(200, 35%, 50%)', // Steel blue accent
    ];

    const coffeePaletteAlpha = coffeePalette.map(c => c.replace(')', ', 0.82)').replace('hsl(', 'hsla('));

    /* ─────────────────────────────────────
       1) GRÁFICO DE ÁREA – Ventas últimos 30 días
       ───────────────────────────────────── */
    const salesLabels = <?= json_encode($salesLabels, JSON_UNESCAPED_UNICODE) ?>;
    const salesData   = <?= json_encode($salesData) ?>;

    const ctxSales = document.getElementById('chartSales');
    if (ctxSales && salesLabels.length > 0) {
        const ctx = ctxSales.getContext('2d');

        // Gradiente elegante para el relleno del área
        const gradient = ctx.createLinearGradient(0, 0, 0, 320);
        gradient.addColorStop(0, 'hsla(22, 58%, 52%, 0.35)');
        gradient.addColorStop(0.5, 'hsla(22, 58%, 52%, 0.08)');
        gradient.addColorStop(1, 'hsla(22, 58%, 52%, 0.0)');

        new Chart(ctxSales, {
            type: 'line',
            data: {
                labels: salesLabels,
                datasets: [{
                    label: 'Ventas ($)',
                    data: salesData,
                    fill: true,
                    backgroundColor: gradient,
                    borderColor: 'hsl(22, 58%, 52%)',
                    borderWidth: 2.5,
                    pointBackgroundColor: '#fff',
                    pointBorderColor: 'hsl(22, 58%, 52%)',
                    pointBorderWidth: 2,
                    pointRadius: 4,
                    pointHoverRadius: 7,
                    pointHoverBackgroundColor: 'hsl(22, 58%, 52%)',
                    pointHoverBorderColor: '#fff',
                    pointHoverBorderWidth: 3,
                    tension: 0.4,
                }]
            },
            options: {
                responsive: true,
                maintainAspectRatio: false,
                interaction: {
                    mode: 'index',
                    intersect: false,
                },
                plugins: {
                    legend: { display: false },
                    tooltip: {
                        backgroundColor: 'hsl(28, 48%, 12%)',
                        titleColor: 'hsl(36, 40%, 92%)',
                        bodyColor: '#fff',
                        bodyFont: { size: 14, weight: '600' },
                        titleFont: { size: 12 },
                        padding: 14,
                        cornerRadius: 12,
                        displayColors: false,
                        callbacks: {
                            label: function(ctx) {
                                return '$ ' + ctx.parsed.y.toLocaleString('es-CO', { minimumFractionDigits: 2 });
                            }
                        }
                    }
                },
                scales: {
                    x: {
                        grid: {
                            display: false,
                        },
                        ticks: {
                            color: 'hsl(28, 20%, 48%)',
                            font: { size: 11, weight: '500' },
                            maxRotation: 45,
                            autoSkipPadding: 12,
                        },
                        border: { display: false },
                    },
                    y: {
                        beginAtZero: true,
                        grid: {
                            color: 'hsla(28, 20%, 48%, 0.08)',
                            drawBorder: false,
                        },
                        ticks: {
                            color: 'hsl(28, 20%, 48%)',
                            font: { size: 11, weight: '500' },
                            callback: function(val) {
                                return '$ ' + val.toLocaleString('es-CO');
                            },
                            maxTicksLimit: 6,
                        },
                        border: { display: false },
                    }
                },
                animation: {
                    duration: 1200,
                    easing: 'easeOutQuart',
                }
            }
        });
    }

    /* ─────────────────────────────────────
       2) GRÁFICO DOUGHNUT – Productos más vendidos
       ───────────────────────────────────── */
    const topLabels = <?= json_encode($topLabels, JSON_UNESCAPED_UNICODE) ?>;
    const topData   = <?= json_encode($topData) ?>;

    const ctxTop = document.getElementById('chartTopProducts');
    if (ctxTop && topLabels.length > 0) {
        new Chart(ctxTop, {
            type: 'doughnut',
            data: {
                labels: topLabels,
                datasets: [{
                    data: topData,
                    backgroundColor: coffeePaletteAlpha,
                    borderColor: '#fff',
                    borderWidth: 3,
                    hoverBorderColor: '#fff',
                    hoverBorderWidth: 4,
                    hoverOffset: 8,
                }]
            },
            options: {
                responsive: true,
                maintainAspectRatio: false,
                cutout: '62%',
                plugins: {
                    legend: {
                        position: 'bottom',
                        labels: {
                            color: 'hsl(28, 35%, 28%)',
                            font: { size: 12, weight: '500', family: "'DM Sans', sans-serif" },
                            padding: 16,
                            usePointStyle: true,
                            pointStyleWidth: 10,
                        }
                    },
                    tooltip: {
                        backgroundColor: 'hsl(28, 48%, 12%)',
                        titleColor: 'hsl(36, 40%, 92%)',
                        bodyColor: '#fff',
                        bodyFont: { size: 14, weight: '600' },
                        titleFont: { size: 12 },
                        padding: 14,
                        cornerRadius: 12,
                        callbacks: {
                            label: function(ctx) {
                                const total = ctx.dataset.data.reduce((a, b) => a + b, 0);
                                const pct = total > 0 ? ((ctx.parsed / total) * 100).toFixed(1) : 0;
                                return ctx.label + ': ' + ctx.parsed + ' uds (' + pct + '%)';
                            }
                        }
                    }
                },
                animation: {
                    animateRotate: true,
                    animateScale: true,
                    duration: 1400,
                    easing: 'easeOutQuart',
                }
            }
        });
    }
});
</script>
