<?php
// app/views/reportes/grupal.php

// 1. PRE-PROCESAMIENTO DE DATOS PARA GRÁFICAS Y RANKING
$chartLabels = [];
$dataExcelentes = [];
$dataBuenas = [];
$dataDeficientes = [];
$rankingGrupos = [];

if (!empty($datosGrupos)) {
    foreach ($datosGrupos as $grupo) {
        // Datos para la gráfica
        $chartLabels[] = "Grupo " . $grupo['Grupo'];
        $dataExcelentes[] = $grupo['excelentes'];
        $dataBuenas[] = $grupo['buenas'];
        $dataDeficientes[] = $grupo['deficientes'];
        
        // Calcular promedio ponderado para el ranking (0-100)
        $total = $grupo['total_evaluaciones'];
        $promedio = 0;
        if ($total > 0) {
            $puntos = ($grupo['excelentes'] * 100) + ($grupo['buenas'] * 80) + ($grupo['deficientes'] * 50);
            $promedio = $puntos / $total;
        }
        
        // Guardar para la tabla de ranking
        $rankingGrupos[] = [
            'grupo' => $grupo['Grupo'],
            'promedio' => $promedio,
            'total' => $total,
            'riesgo' => ($total > 0) ? ($grupo['deficientes'] / $total) * 100 : 0
        ];
    }
    
    // Ordenar ranking de mayor a menor promedio
    usort($rankingGrupos, function($a, $b) {
        return $b['promedio'] <=> $a['promedio'];
    });
}
?>
<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="utf-8"/>
    <meta content="width=device-width, initial-scale=1.0" name="viewport"/>
    <title>Reporte Grupal - UPEMOR</title>
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
                            <span class="material-symbols-outlined text-2xl">insights</span>
                        </div>
                        <div class="flex flex-col">
                            <h1 class="text-base font-bold text-upemor-purple">Portal UPEMOR</h1>
                            <p class="text-sm text-slate-600">Panel Administrativo</p>
                        </div>
                    </div>
                    
                    <nav class="mt-4 flex flex-col gap-1">
                        <a class="group flex items-center gap-3 rounded-lg px-3 py-2 hover:bg-upemor-orange/10 transition" href="index.php?controller=auth&action=panelAdministrativo">
                            <span class="material-symbols-outlined text-slate-600 group-hover:text-upemor-purple">space_dashboard</span>
                            <p class="text-sm font-medium text-slate-700 group-hover:text-upemor-purple">Dashboard</p>
                        </a>
                        <a class="flex items-center gap-3 rounded-lg bg-upemor-orange/10 border-l-4 border-upemor-orange px-3 py-2" href="index.php?controller=reporte&action=grupal">
                            <span class="material-symbols-outlined text-upemor-purple">groups</span>
                            <p class="text-sm font-bold text-upemor-purple">Grupales</p>
                        </a>
                        <a class="group flex items-center gap-3 rounded-lg px-3 py-2 hover:bg-upemor-orange/10 transition" href="index.php?controller=reporte&action=individual">
                            <span class="material-symbols-outlined text-slate-600 group-hover:text-upemor-purple">person</span>
                            <p class="text-sm font-medium text-slate-700 group-hover:text-upemor-purple">Individuales</p>
                        </a>
                    </nav>
                </div>
                <div class="flex flex-col gap-2 border-t border-slate-200 pt-4">
                    <a class="group flex w-full items-center justify-start gap-3 rounded-lg px-3 py-2 hover:bg-red-50 hover:text-red-600 transition" href="index.php?controller=auth&action=logout">
                        <span class="material-symbols-outlined">logout</span>
                        <span class="truncate text-sm font-medium">Cerrar Sesión</span>
                    </a>
                </div>
            </div>
        </aside>
        
        <main class="w-full flex-1 overflow-y-auto bg-gradient-to-br from-slate-50 to-slate-100">
            <div class="p-6 lg:p-8">
                <div class="flex flex-col md:flex-row justify-between items-start md:items-center mb-8 gap-4">
                    <div>
                        <h1 class="text-3xl font-bold text-upemor-purple">Reporte Grupal</h1>
                        <p class="text-slate-600 mt-1">Comparativa de rendimiento académico por grupos</p>
                    </div>
                    <a href="index.php?controller=reporte&action=exportarGrupalPDF" class="flex items-center gap-2 rounded-lg bg-red-600 hover:bg-red-700 px-4 py-2 text-white font-semibold transition shadow-sm">
                        <span class="material-symbols-outlined">picture_as_pdf</span>
                        Exportar PDF
                    </a>
                </div>
                
                <?php if (!empty($datosGrupos)): ?>

                <div class="bg-white rounded-2xl p-6 shadow-lg mb-8">
                    <div class="flex items-center justify-between mb-6">
                        <h3 class="text-xl font-bold text-upemor-purple flex items-center gap-2">
                            <span class="material-symbols-outlined">bar_chart</span>
                            Desempeño por Grupo
                        </h3>
                        <div class="flex gap-4 text-sm">
                            <div class="flex items-center gap-1"><div class="w-3 h-3 rounded-full bg-green-500"></div> Excelente</div>
                            <div class="flex items-center gap-1"><div class="w-3 h-3 rounded-full bg-blue-500"></div> Buena</div>
                            <div class="flex items-center gap-1"><div class="w-3 h-3 rounded-full bg-red-500"></div> Deficiente</div>
                        </div>
                    </div>
                    
                    <div class="relative h-80 w-full">
                        <canvas id="graficaGrupal"></canvas>
                    </div>
                </div>

                <div class="grid grid-cols-1 lg:grid-cols-3 gap-8">
                    
                    <div class="lg:col-span-1 bg-white rounded-2xl shadow-lg overflow-hidden border border-slate-100">
                        <div class="bg-gradient-to-r from-upemor-purple to-indigo-900 p-4 text-white">
                            <h3 class="font-bold text-lg flex items-center gap-2">
                                <span class="material-symbols-outlined text-yellow-400">emoji_events</span>
                                Ranking de Grupos
                            </h3>
                            <p class="text-xs text-indigo-200 opacity-80">Basado en promedio ponderado</p>
                        </div>
                        <div class="p-0">
                            <table class="w-full text-sm text-left">
                                <thead class="bg-slate-50 text-slate-500 font-semibold border-b">
                                    <tr>
                                        <th class="px-4 py-3">#</th>
                                        <th class="px-4 py-3">Grupo</th>
                                        <th class="px-4 py-3 text-right">Puntaje</th>
                                    </tr>
                                </thead>
                                <tbody class="divide-y divide-slate-100">
                                    <?php $pos = 1; foreach($rankingGrupos as $rank): ?>
                                    <tr class="hover:bg-slate-50">
                                        <td class="px-4 py-3 font-bold text-slate-400"><?php echo $pos++; ?></td>
                                        <td class="px-4 py-3 font-semibold text-slate-700"><?php echo htmlspecialchars($rank['grupo']); ?></td>
                                        <td class="px-4 py-3 text-right">
                                            <span class="inline-block px-2 py-1 rounded text-xs font-bold 
                                                <?php echo $rank['promedio'] >= 85 ? 'bg-green-100 text-green-700' : ($rank['promedio'] >= 70 ? 'bg-blue-100 text-blue-700' : 'bg-red-100 text-red-700'); ?>">
                                                <?php echo number_format($rank['promedio'], 1); ?>
                                            </span>
                                        </td>
                                    </tr>
                                    <?php endforeach; ?>
                                </tbody>
                            </table>
                        </div>
                    </div>

                    <div class="lg:col-span-2 grid grid-cols-1 md:grid-cols-2 gap-6">
                        <?php foreach ($datosGrupos as $grupo): 
                            // Calcular riesgo para borde
                            $total = $grupo['total_evaluaciones'];
                            $riesgo = ($total > 0) ? ($grupo['deficientes'] / $total) : 0;
                            $bordeColor = $riesgo > 0.3 ? 'border-red-500' : 'border-slate-200';
                        ?>
                        <div class="bg-white rounded-xl p-5 shadow hover:shadow-md transition border-l-4 <?php echo $bordeColor; ?>">
                            <div class="flex justify-between items-start mb-4">
                                <div>
                                    <h4 class="text-xl font-bold text-slate-800">Grupo <?php echo htmlspecialchars($grupo['Grupo']); ?></h4>
                                    <p class="text-xs text-slate-500"><?php echo $grupo['total_estudiantes']; ?> Estudiantes activos</p>
                                </div>
                                <div class="bg-slate-100 p-2 rounded-lg">
                                    <span class="text-2xl font-bold text-upemor-purple"><?php echo $grupo['total_evaluaciones']; ?></span>
                                    <span class="block text-[10px] text-slate-500 uppercase font-bold">Evals</span>
                                </div>
                            </div>
                            
                            <?php if ($total > 0): 
                                $pExcel = ($grupo['excelentes'] / $total) * 100;
                                $pBuen = ($grupo['buenas'] / $total) * 100;
                                $pDef = ($grupo['deficientes'] / $total) * 100;
                            ?>
                            <div class="w-full h-2 bg-slate-100 rounded-full overflow-hidden flex mb-3">
                                <div class="bg-green-500 h-full" style="width: <?php echo $pExcel; ?>%"></div>
                                <div class="bg-blue-500 h-full" style="width: <?php echo $pBuen; ?>%"></div>
                                <div class="bg-red-500 h-full" style="width: <?php echo $pDef; ?>%"></div>
                            </div>
                            <div class="flex justify-between text-xs text-slate-600">
                                <span><span class="text-green-600 font-bold"><?php echo $grupo['excelentes']; ?></span> Exc.</span>
                                <span><span class="text-blue-600 font-bold"><?php echo $grupo['buenas']; ?></span> Bue.</span>
                                <span><span class="text-red-600 font-bold"><?php echo $grupo['deficientes']; ?></span> Def.</span>
                            </div>
                            <?php else: ?>
                                <p class="text-sm text-slate-400 italic py-2">Sin evaluaciones registradas</p>
                            <?php endif; ?>
                        </div>
                        <?php endforeach; ?>
                    </div>
                </div>

                <script>
                    const ctx = document.getElementById('graficaGrupal').getContext('2d');
                    
                    new Chart(ctx, {
                        type: 'bar',
                        data: {
                            labels: <?php echo json_encode($chartLabels); ?>,
                            datasets: [
                                {
                                    label: 'Excelente',
                                    data: <?php echo json_encode($dataExcelentes); ?>,
                                    backgroundColor: '#22c55e', // green-500
                                    borderRadius: 4,
                                },
                                {
                                    label: 'Buena',
                                    data: <?php echo json_encode($dataBuenas); ?>,
                                    backgroundColor: '#3b82f6', // blue-500
                                    borderRadius: 4,
                                },
                                {
                                    label: 'Deficiente',
                                    data: <?php echo json_encode($dataDeficientes); ?>,
                                    backgroundColor: '#ef4444', // red-500
                                    borderRadius: 4,
                                }
                            ]
                        },
                        options: {
                            responsive: true,
                            maintainAspectRatio: false,
                            scales: {
                                x: { stacked: true, grid: { display: false } },
                                y: { stacked: true, grid: { color: '#f1f5f9' } }
                            },
                            plugins: {
                                legend: { display: false }, // Usamos nuestra propia leyenda HTML
                                tooltip: {
                                    mode: 'index',
                                    intersect: false,
                                }
                            }
                        }
                    });
                </script>

                <?php else: ?>
                <div class="rounded-2xl bg-white p-16 shadow-lg text-center">
                    <div class="inline-flex h-24 w-24 items-center justify-center rounded-full bg-slate-100 mb-6">
                        <span class="material-symbols-outlined text-5xl text-slate-400">groups_2</span>
                    </div>
                    <h3 class="text-2xl font-bold text-slate-800">Sin Datos de Grupos</h3>
                    <p class="text-slate-500 mt-2 max-w-md mx-auto">Aún no hay evaluaciones registradas para generar el reporte comparativo.</p>
                </div>
                <?php endif; ?>
            </div>
        </main>
    </div>
</body>
</html>