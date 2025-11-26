<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="utf-8"/>
    <meta content="width=device-width, initial-scale=1.0" name="viewport"/>
    <title>Mis Consultas - UPEMOR</title>
    <script src="https://cdn.tailwindcss.com?plugins=forms,container-queries"></script>
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@400;500;600;700;800;900&display=swap" rel="stylesheet"/>
    <link href="https://fonts.googleapis.com/css2?family=Material+Symbols+Outlined" rel="stylesheet"/>
    <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
    <script>
        tailwind.config = {
            theme: {
                extend: {
                    colors: {
                        "upemor-purple": "#4A1E6F",
                        "upemor-orange": "#F7941D",
                        "upemor-red": "#ED1C24",
                    },
                    fontFamily: { "display": ["Inter", "sans-serif"] },
                },
            },
        }
    </script>
</head>
<body class="font-display bg-slate-50 text-slate-800">
    <div class="flex h-screen">
        <aside class="flex w-64 flex-col border-r border-slate-200 bg-white shadow-lg">
            <div class="flex h-full flex-col justify-between p-4">
                <div class="flex flex-col gap-4">
                    <div class="flex items-center gap-3 px-3">
                        <div class="flex h-10 w-10 items-center justify-center rounded-full bg-gradient-to-br from-upemor-purple to-purple-900 text-white">
                            <span class="material-symbols-outlined text-2xl">school</span>
                        </div>
                        <div class="flex flex-col">
                            <h1 class="text-base font-bold text-upemor-purple">Portal UPEMOR</h1>
                            <p class="text-sm text-slate-600">Sistema de Competencias</p>
                        </div>
                    </div>
                    
                    <nav class="mt-4 flex flex-col gap-1">
                        <a class="group flex items-center gap-3 rounded-lg px-3 py-2 hover:bg-upemor-orange/10 transition" href="index.php?controller=auth&action=panelEstudiante">
                            <span class="material-symbols-outlined text-slate-600 group-hover:text-upemor-purple">space_dashboard</span>
                            <p class="text-sm font-medium text-slate-700 group-hover:text-upemor-purple">Dashboard</p>
                        </a>
                        <a class="flex items-center gap-3 rounded-lg bg-upemor-orange/10 border-l-4 border-upemor-orange px-3 py-2" href="index.php?controller=estudiante&action=misConsultas">
                            <span class="material-symbols-outlined text-upemor-purple">chat</span>
                            <p class="text-sm font-bold text-upemor-purple">Mis Consultas</p>
                        </a>
                        <a class="group flex items-center gap-3 rounded-lg px-3 py-2 hover:bg-upemor-orange/10 transition" href="index.php?controller=estudiante&action=misReportes">
                            <span class="material-symbols-outlined text-slate-600 group-hover:text-upemor-purple">bar_chart</span>
                            <p class="text-sm font-medium text-slate-700 group-hover:text-upemor-purple">Mis Reportes</p>
                        </a>
                        <a class="group flex items-center gap-3 rounded-lg px-3 py-2 hover:bg-upemor-orange/10 transition" href="index.php?controller=estudiante&action=evidenciasPendientes">
                            <span class="material-symbols-outlined text-slate-600 group-hover:text-upemor-purple">assignment</span>
                            <p class="text-sm font-medium text-slate-700 group-hover:text-upemor-purple">Pendientes</p>
                        </a>
                    </nav>
                </div>
                
                <div class="flex flex-col gap-2 border-t border-slate-200 pt-4">
                    <div class="rounded-lg bg-slate-50 px-3 py-2">
                        <div class="flex items-center gap-3 mb-2">
                            <div class="h-10 w-10 rounded-full bg-upemor-purple flex items-center justify-center text-white font-bold text-lg">
                                <?php echo strtoupper(substr($_SESSION['usuario_nombre'] ?? 'E', 0, 1)); ?>
                            </div>
                            <div class="flex-1 min-w-0">
                                <p class="text-sm font-bold text-upemor-purple truncate"><?php echo htmlspecialchars($_SESSION['usuario_nombre'] ?? 'Estudiante'); ?></p>
                                <p class="text-xs text-slate-600"><?php echo htmlspecialchars($matricula ?? 'N/A'); ?></p>
                            </div>
                        </div>
                    </div>
                    <a class="group flex w-full items-center justify-start gap-3 rounded-lg px-3 py-2 hover:bg-red-50 hover:text-red-600 transition" href="index.php?controller=auth&action=logout">
                        <span class="material-symbols-outlined">logout</span>
                        <span class="truncate text-sm font-medium">Cerrar Sesión</span>
                    </a>
                </div>
            </div>
        </aside>
        
        <main class="w-full flex-1 overflow-y-auto bg-gradient-to-br from-slate-50 to-slate-100">
            <div class="p-6 lg:p-8">
                <div class="flex items-center justify-between mb-6">
                    <div>
                        <h1 class="text-3xl font-bold text-upemor-purple">Mis Evaluaciones</h1>
                        <p class="text-slate-600">Consulta tu progreso individual en todas las competencias</p>
                    </div>
                    <button onclick="window.print()" class="flex items-center gap-2 rounded-lg bg-slate-600 hover:bg-slate-700 px-4 py-2 text-white font-semibold transition">
                        <span class="material-symbols-outlined">print</span>
                        Imprimir
                    </button>
                </div>

                <div class="rounded-2xl bg-white p-6 shadow-lg mb-6">
                    <h3 class="text-lg font-bold text-upemor-purple mb-4 flex items-center gap-2">
                        <span class="material-symbols-outlined">filter_list</span>
                        Filtrar Resultados
                    </h3>
                    <form method="GET" action="index.php" class="grid grid-cols-1 md:grid-cols-3 gap-4">
                        <input type="hidden" name="controller" value="estudiante">
                        <input type="hidden" name="action" value="misConsultas">
                        
                        <div>
                            <label class="block text-sm font-semibold text-slate-700 mb-2">Competencia</label>
                            <select name="competencia" class="w-full rounded-lg border-slate-300 focus:border-upemor-purple focus:ring-upemor-purple">
                                <option value="">Todas las competencias</option>
                                <?php if(isset($listaCompetencias)): ?>
                                    <?php foreach($listaCompetencias as $comp): ?>
                                        <option value="<?php echo $comp['idCompetencias']; ?>" 
                                            <?php echo (isset($_GET['competencia']) && $_GET['competencia'] == $comp['idCompetencias']) ? 'selected' : ''; ?>>
                                            <?php echo htmlspecialchars($comp['NombreCompetencia']); ?>
                                        </option>
                                    <?php endforeach; ?>
                                <?php endif; ?>
                            </select>
                        </div>
                        
                        <div>
                            <label class="block text-sm font-semibold text-slate-700 mb-2">Calificación</label>
                            <select name="calificacion" class="w-full rounded-lg border-slate-300 focus:border-upemor-purple focus:ring-upemor-purple">
                                <option value="">Todas</option>
                                <option value="Excelente" <?php echo (isset($_GET['calificacion']) && $_GET['calificacion'] == 'Excelente') ? 'selected' : ''; ?>>Excelente</option>
                                <option value="Buena" <?php echo (isset($_GET['calificacion']) && $_GET['calificacion'] == 'Buena') ? 'selected' : ''; ?>>Buena</option>
                                <option value="Deficiente" <?php echo (isset($_GET['calificacion']) && $_GET['calificacion'] == 'Deficiente') ? 'selected' : ''; ?>>Deficiente</option>
                            </select>
                        </div>
                        
                        <div class="flex items-end gap-2">
                            <button type="submit" class="flex-1 flex items-center justify-center gap-2 rounded-lg bg-upemor-purple hover:bg-purple-900 px-4 py-2 text-white font-semibold transition shadow-md">
                                <span class="material-symbols-outlined">search</span>
                                Buscar
                            </button>
                            <a href="index.php?controller=estudiante&action=misConsultas" class="px-3 py-2 rounded-lg border border-slate-300 text-slate-600 hover:bg-slate-100" title="Limpiar filtros">
                                <span class="material-symbols-outlined align-middle">restart_alt</span>
                            </a>
                        </div>
                    </form>
                </div>

                <?php if(isset($estadisticas)): ?>
                <div class="grid grid-cols-1 md:grid-cols-4 gap-6 mb-6">
                    <div class="rounded-2xl border-t-4 border-upemor-purple bg-white p-5 shadow-lg">
                        <p class="text-sm font-medium text-slate-600">Total</p>
                        <p class="text-3xl font-bold text-upemor-purple"><?php echo $estadisticas['total']; ?></p>
                    </div>
                    <div class="rounded-2xl border-t-4 border-green-500 bg-white p-5 shadow-lg">
                        <p class="text-sm font-medium text-slate-600">Excelentes</p>
                        <p class="text-3xl font-bold text-green-600"><?php echo $estadisticas['excelentes']; ?></p>
                    </div>
                    <div class="rounded-2xl border-t-4 border-blue-500 bg-white p-5 shadow-lg">
                        <p class="text-sm font-medium text-slate-600">Buenas</p>
                        <p class="text-3xl font-bold text-blue-600"><?php echo $estadisticas['buenas']; ?></p>
                    </div>
                    <div class="rounded-2xl border-t-4 border-red-500 bg-white p-5 shadow-lg">
                        <p class="text-sm font-medium text-slate-600">Deficientes</p>
                        <p class="text-3xl font-bold text-red-600"><?php echo $estadisticas['deficientes']; ?></p>
                    </div>
                </div>
                <?php endif; ?>

                <div class="rounded-2xl bg-white p-6 shadow-lg mb-6">
                    <h3 class="text-xl font-bold text-upemor-purple mb-4">Resultados de la Búsqueda</h3>
                    <div class="overflow-x-auto">
                        <?php if(!empty($evaluaciones)): ?>
                        <table class="w-full">
                            <thead class="bg-slate-50 border-b border-slate-200">
                                <tr>
                                    <th class="px-4 py-3 text-left text-sm font-bold text-upemor-purple">Fecha</th>
                                    <th class="px-4 py-3 text-left text-sm font-bold text-upemor-purple">Competencia</th>
                                    <th class="px-4 py-3 text-left text-sm font-bold text-upemor-purple">Docente</th>
                                    <th class="px-4 py-3 text-center text-sm font-bold text-upemor-purple">Calificación</th>
                                    <th class="px-4 py-3 text-left text-sm font-bold text-upemor-purple">Observaciones</th>
                                </tr>
                            </thead>
                            <tbody class="divide-y divide-slate-200">
                                <?php foreach($evaluaciones as $eval): ?>
                                <tr class="hover:bg-slate-50 transition">
                                    <td class="px-4 py-3 text-sm text-slate-600 font-medium">
                                        <?php echo date('d/m/Y', strtotime($eval['Fecha'])); ?>
                                    </td>
                                    <td class="px-4 py-3 text-sm font-medium text-slate-800">
                                        <?php echo htmlspecialchars($eval['NombreCompetencia']); ?>
                                        <p class="text-xs text-slate-500 font-normal truncate max-w-[200px]"><?php echo htmlspecialchars($eval['DescripcionCompetencia']); ?></p>
                                    </td>
                                    <td class="px-4 py-3 text-sm text-slate-600">
                                        <?php echo htmlspecialchars($eval['NombreDocente']); ?>
                                    </td>
                                    <td class="px-4 py-3 text-center">
                                        <?php 
                                        $calif = strtolower($eval['Calificacion']);
                                        $colorClass = 'bg-slate-100 text-slate-700';
                                        if($calif == 'excelente') $colorClass = 'bg-green-100 text-green-700';
                                        elseif(strpos($calif, 'buen') !== false) $colorClass = 'bg-blue-100 text-blue-700';
                                        elseif($calif == 'deficiente') $colorClass = 'bg-red-100 text-red-700';
                                        ?>
                                        <span class="inline-flex px-3 py-1 rounded-full text-xs font-bold <?php echo $colorClass; ?>">
                                            <?php echo htmlspecialchars($eval['Calificacion']); ?>
                                        </span>
                                    </td>
                                    <td class="px-4 py-3 text-sm text-slate-500 italic max-w-xs truncate">
                                        "<?php echo htmlspecialchars($eval['Observaciones'] ?? 'Sin observaciones'); ?>"
                                    </td>
                                </tr>
                                <?php endforeach; ?>
                            </tbody>
                        </table>
                        <?php else: ?>
                        <div class="text-center py-12">
                            <span class="material-symbols-outlined text-6xl text-slate-300">search_off</span>
                            <p class="mt-4 text-lg font-semibold text-slate-600">No se encontraron evaluaciones</p>
                            <p class="text-sm text-slate-500">Intenta ajustar los filtros de búsqueda o selecciona otra competencia.</p>
                        </div>
                        <?php endif; ?>
                    </div>
                </div>

                <?php if(!empty($progresoMensual)): ?>
                <div class="rounded-2xl bg-white p-6 shadow-lg">
                    <h3 class="text-xl font-bold text-upemor-purple mb-4">Mi Progreso en los Últimos Meses</h3>
                    <div class="h-64">
                        <canvas id="chartProgreso"></canvas>
                    </div>
                </div>

                <script>
                    const progreso = <?php echo json_encode($progresoMensual); ?>;
                    
                    const labels = progreso.map(item => {
                        const [year, month] = item.mes.split('-');
                        const date = new Date(year, month - 1);
                        return date.toLocaleDateString('es-ES', { month: 'short', year: 'numeric' });
                    });
                    
                    const ctx = document.getElementById('chartProgreso').getContext('2d');
                    new Chart(ctx, {
                        type: 'line',
                        data: {
                            labels: labels,
                            datasets: [{
                                label: 'Promedio Mensual (0-100)',
                                data: progreso.map(item => parseFloat(item.promedio)),
                                borderColor: '#4A1E6F',
                                backgroundColor: 'rgba(74, 30, 111, 0.1)',
                                tension: 0.4,
                                fill: true,
                                pointRadius: 5,
                                pointHoverRadius: 8
                            }]
                        },
                        options: {
                            responsive: true,
                            maintainAspectRatio: false,
                            plugins: {
                                legend: { position: 'bottom' }
                            },
                            scales: {
                                y: {
                                    beginAtZero: true,
                                    max: 100
                                }
                            }
                        }
                    });
                </script>
                <?php endif; ?>
            </div>
        </main>
    </div>
</body>
</html>