<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="utf-8"/>
    <meta content="width=device-width, initial-scale=1.0" name="viewport"/>
    <title>Reporte Histórico - UPEMOR</title>
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
                        "success": "#16A34A",
                        "danger": "#DC2626"
                    },
                    fontFamily: {
                        "display": ["Inter", "sans-serif"]
                    },
                },
            },
        }
    </script>
</head>
<body class="font-display bg-slate-50 text-slate-800">
<?php
// Función para agrupar evaluaciones por cuatrimestre
function agruparPorCuatrimestre($evaluacionesPorMes) {
    $porCuatrimestre = [];
    
    foreach ($evaluacionesPorMes as $item) {
        list($year, $month) = explode('-', $item['mes']);
        $month = (int)$month;
        
        // Determinar cuatrimestre (Enero-Abril: Q1, Mayo-Agosto: Q2, Septiembre-Diciembre: Q3)
        if ($month >= 1 && $month <= 4) {
            $cuatrimestre = "Q1 $year";
            $label = "Ene-Abr $year";
        } elseif ($month >= 5 && $month <= 8) {
            $cuatrimestre = "Q2 $year";
            $label = "May-Ago $year";
        } else {
            $cuatrimestre = "Q3 $year";
            $label = "Sep-Dic $year";
        }
        
        if (!isset($porCuatrimestre[$cuatrimestre])) {
            $porCuatrimestre[$cuatrimestre] = [
                'label' => $label,
                'excelentes' => 0,
                'buenas' => 0,
                'deficientes' => 0,
                'total' => 0
            ];
        }
        
        $porCuatrimestre[$cuatrimestre]['excelentes'] += $item['excelentes'];
        $porCuatrimestre[$cuatrimestre]['buenas'] += $item['buenas'];
        $porCuatrimestre[$cuatrimestre]['deficientes'] += $item['deficientes'];
        $porCuatrimestre[$cuatrimestre]['total'] += ($item['excelentes'] + $item['buenas'] + $item['deficientes']);
    }
    
    return array_values($porCuatrimestre);
}

// Si tienes $evaluacionesPorMes desde el controlador, agrupar por cuatrimestre
$evaluacionesPorCuatrimestre = isset($evaluacionesPorMes) ? agruparPorCuatrimestre($evaluacionesPorMes) : [];
?>
    <div class="flex h-screen">
        <!-- SideNavBar -->
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
                        <a class="group flex items-center gap-3 rounded-lg px-3 py-2 hover:bg-upemor-orange/10 transition" href="index.php?controller=competencia&action=consult">
                            <span class="material-symbols-outlined text-slate-600 group-hover:text-upemor-purple">edit_document</span>
                            <p class="text-sm font-medium text-slate-700 group-hover:text-upemor-purple">Registro de Competencias</p>
                        </a>
                        <a class="group flex items-center gap-3 rounded-lg px-3 py-2 hover:bg-upemor-orange/10 transition" href="index.php?controller=evaluacion&action=consult">
                            <span class="material-symbols-outlined text-slate-600 group-hover:text-upemor-purple">checklist</span>
                            <p class="text-sm font-medium text-slate-700 group-hover:text-upemor-purple">Evaluaciones</p>
                        </a>
                        
                        <div>
                            <p class="mt-4 px-3 text-xs font-semibold uppercase text-slate-600">Reportes</p>
                            <div class="mt-1 flex flex-col gap-1">
                                <a class="group flex items-center gap-3 rounded-lg px-3 py-2 hover:bg-upemor-orange/10 transition" href="index.php?controller=reporte&action=grupal">
                                    <span class="material-symbols-outlined text-slate-600 group-hover:text-upemor-purple">groups</span>
                                    <p class="text-sm font-medium text-slate-700 group-hover:text-upemor-purple">Grupales</p>
                                </a>
                                <a class="group flex items-center gap-3 rounded-lg px-3 py-2 hover:bg-upemor-orange/10 transition" href="index.php?controller=reporte&action=individual">
                                    <span class="material-symbols-outlined text-slate-600 group-hover:text-upemor-purple">person</span>
                                    <p class="text-sm font-medium text-slate-700 group-hover:text-upemor-purple">Individuales</p>
                                </a>
                                <a class="flex items-center gap-3 rounded-lg bg-upemor-orange/10 border-l-4 border-upemor-orange px-3 py-2" href="index.php?controller=reporte&action=historico">
                                    <span class="material-symbols-outlined text-upemor-purple">history</span>
                                    <p class="text-sm font-bold text-upemor-purple">Históricos</p>
                                </a>
                            </div>
                        </div>
                    </nav>
                </div>
                
                <div class="flex flex-col gap-2 border-t border-slate-200 pt-4">
                    <a class="group flex items-center gap-3 rounded-lg px-3 py-2 hover:bg-upemor-orange/10 transition" href="#">
                        <div class="h-8 w-8 rounded-full bg-upemor-purple flex items-center justify-center text-white font-bold">
                            <?php 
                                $nombre = isset($_SESSION['usuario_nombre']) ? $_SESSION['usuario_nombre'] : 'Usuario';
                                echo strtoupper(substr($nombre, 0, 2));
                            ?>
                        </div>
                        <p class="text-sm font-medium text-slate-700 group-hover:text-upemor-purple">
                            <?php echo htmlspecialchars($nombre); ?>
                        </p>
                    </a>
                    <a class="group flex w-full items-center justify-start gap-3 rounded-lg px-3 py-2 hover:bg-red-50 hover:text-red-600 transition" href="index.php?controller=auth&action=logout">
                        <span class="material-symbols-outlined">logout</span>
                        <span class="truncate text-sm font-medium">Cerrar Sesión</span>
                    </a>
                </div>
            </div>
        </aside>
        
        <!-- Main Content -->
        <main class="w-full flex-1 overflow-y-auto bg-gradient-to-br from-slate-50 to-slate-100">
            <div class="p-6 lg:p-8">
                <!-- Header -->
                <div class="flex items-center justify-between mb-6">
                    <div>
                        <h1 class="text-3xl font-bold text-upemor-purple">Reporte Histórico</h1>
                        <p class="text-slate-600">Análisis de tendencias por cuatrimestre</p>
                    </div>
                    <a href="index.php?controller=reporte&action=exportarHistoricoPDF" class="flex items-center gap-2 rounded-lg bg-red-600 hover:bg-red-700 px-4 py-2 text-white font-semibold transition shadow-sm">
                        <span class="material-symbols-outlined">picture_as_pdf</span>
                        Exportar PDF
                    </a>
                </div>
                
                <!-- Estadísticas Generales -->
                <?php if (isset($estadisticasGenerales) && $estadisticasGenerales): ?>
                <div class="grid grid-cols-2 md:grid-cols-4 gap-6 mb-6">
                    <div class="rounded-2xl border-t-4 border-upemor-purple bg-white p-5 shadow-lg">
                        <p class="text-sm font-medium text-slate-600">Competencias</p>
                        <p class="text-3xl font-bold text-upemor-purple"><?php echo $estadisticasGenerales['total_competencias']; ?></p>
                    </div>
                    <div class="rounded-2xl border-t-4 border-blue-500 bg-white p-5 shadow-lg">
                        <p class="text-sm font-medium text-slate-600">Estudiantes</p>
                        <p class="text-3xl font-bold text-blue-600"><?php echo $estadisticasGenerales['total_estudiantes']; ?></p>
                    </div>
                    <div class="rounded-2xl border-t-4 border-upemor-orange bg-white p-5 shadow-lg">
                        <p class="text-sm font-medium text-slate-600">Docentes</p>
                        <p class="text-3xl font-bold text-upemor-orange"><?php echo $estadisticasGenerales['total_docentes']; ?></p>
                    </div>
                    <div class="rounded-2xl border-t-4 border-green-500 bg-white p-5 shadow-lg">
                        <p class="text-sm font-medium text-slate-600">Evaluaciones</p>
                        <p class="text-3xl font-bold text-green-600"><?php echo $estadisticasGenerales['total_evaluaciones']; ?></p>
                    </div>
                </div>
                <?php endif; ?>
                
                <!-- Gráfica de Evaluaciones por Cuatrimestre -->
                <div class="rounded-2xl bg-white p-6 shadow-lg mb-6">
                    <div class="flex items-center justify-between mb-4">
                        <h3 class="text-xl font-bold text-upemor-purple">Evaluaciones por Cuatrimestre</h3>
                        <span class="text-sm text-slate-600 bg-slate-100 px-3 py-1 rounded-full">Últimos 4 cuatrimestres</span>
                    </div>
                    <canvas id="chartEvaluacionesCuatrimestre" height="80"></canvas>
                </div>
                
                <!-- Distribución por Tipo de Evaluación -->
                <?php if (isset($distribucionPorTipo) && !empty($distribucionPorTipo)): ?>
                <div class="rounded-2xl bg-white p-6 shadow-lg mb-6">
                    <h3 class="text-xl font-bold text-upemor-purple mb-4">Distribución por Tipo de Evaluación</h3>
                    <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-4">
                        <?php foreach ($distribucionPorTipo as $tipo): ?>
                        <div class="border border-slate-200 rounded-lg p-4 hover:shadow-md transition">
                            <h4 class="font-semibold text-slate-700 mb-3"><?php echo htmlspecialchars($tipo['TipoEvaluacion']); ?></h4>
                            <p class="text-2xl font-bold text-upemor-purple mb-2"><?php echo $tipo['total']; ?> evaluaciones</p>
                            <div class="space-y-1 text-sm">
                                <div class="flex justify-between">
                                    <span class="text-slate-600">Excelentes:</span>
                                    <span class="font-semibold text-green-600"><?php echo $tipo['excelentes']; ?></span>
                                </div>
                                <div class="flex justify-between">
                                    <span class="text-slate-600">Buenas:</span>
                                    <span class="font-semibold text-blue-600"><?php echo $tipo['buenas']; ?></span>
                                </div>
                                <div class="flex justify-between">
                                    <span class="text-slate-600">Deficientes:</span>
                                    <span class="font-semibold text-red-600"><?php echo $tipo['deficientes']; ?></span>
                                </div>
                            </div>
                        </div>
                        <?php endforeach; ?>
                    </div>
                </div>
                <?php endif; ?>
                
                <!-- Top Competencias Más Evaluadas -->
                <?php if (isset($competenciasMasEvaluadas) && !empty($competenciasMasEvaluadas)): ?>
                <div class="rounded-2xl bg-white p-6 shadow-lg mb-6">
                    <h3 class="text-xl font-bold text-upemor-purple mb-4">Top 10 Competencias Más Evaluadas</h3>
                    <div class="overflow-x-auto">
                        <table class="w-full">
                            <thead class="bg-slate-50">
                                <tr>
                                    <th class="px-4 py-3 text-left text-sm font-semibold text-slate-700">#</th>
                                    <th class="px-4 py-3 text-left text-sm font-semibold text-slate-700">Competencia</th>
                                    <th class="px-4 py-3 text-center text-sm font-semibold text-slate-700">Total</th>
                                    <th class="px-4 py-3 text-center text-sm font-semibold text-slate-700">Excelentes</th>
                                    <th class="px-4 py-3 text-center text-sm font-semibold text-slate-700">Buenas</th>
                                    <th class="px-4 py-3 text-center text-sm font-semibold text-slate-700">Deficientes</th>
                                    <th class="px-4 py-3 text-center text-sm font-semibold text-slate-700">Promedio</th>
                                </tr>
                            </thead>
                            <tbody class="divide-y divide-slate-200">
                                <?php $posicion = 1; foreach ($competenciasMasEvaluadas as $comp): ?>
                                <tr class="hover:bg-slate-50">
                                    <td class="px-4 py-3 text-sm font-bold text-upemor-purple"><?php echo $posicion++; ?></td>
                                    <td class="px-4 py-3 text-sm font-medium text-slate-800"><?php echo htmlspecialchars($comp['NombreCompetencia']); ?></td>
                                    <td class="px-4 py-3 text-sm text-center"><?php echo $comp['total_evaluaciones']; ?></td>
                                    <td class="px-4 py-3 text-sm text-center text-green-600 font-semibold"><?php echo $comp['excelentes']; ?></td>
                                    <td class="px-4 py-3 text-sm text-center text-blue-600 font-semibold"><?php echo $comp['buenas']; ?></td>
                                    <td class="px-4 py-3 text-sm text-center text-red-600 font-semibold"><?php echo $comp['deficientes']; ?></td>
                                    <td class="px-4 py-3 text-sm text-center font-semibold text-upemor-purple"><?php echo number_format($comp['promedio_porcentual'], 1); ?>%</td>
                                </tr>
                                <?php endforeach; ?>
                            </tbody>
                        </table>
                    </div>
                </div>
                <?php endif; ?>
                
                <!-- Top Estudiantes -->
                <?php if (isset($topEstudiantes) && !empty($topEstudiantes)): ?>
                <div class="rounded-2xl bg-white p-6 shadow-lg">
                    <h3 class="text-xl font-bold text-upemor-purple mb-4">Top 10 Estudiantes con Más Evaluaciones</h3>
                    <div class="overflow-x-auto">
                        <table class="w-full">
                            <thead class="bg-slate-50">
                                <tr>
                                    <th class="px-4 py-3 text-left text-sm font-semibold text-slate-700">#</th>
                                    <th class="px-4 py-3 text-left text-sm font-semibold text-slate-700">Estudiante</th>
                                    <th class="px-4 py-3 text-left text-sm font-semibold text-slate-700">Matrícula</th>
                                    <th class="px-4 py-3 text-left text-sm font-semibold text-slate-700">Grupo</th>
                                    <th class="px-4 py-3 text-center text-sm font-semibold text-slate-700">Total</th>
                                    <th class="px-4 py-3 text-center text-sm font-semibold text-slate-700">Excelentes</th>
                                    <th class="px-4 py-3 text-center text-sm font-semibold text-slate-700">Promedio</th>
                                </tr>
                            </thead>
                            <tbody class="divide-y divide-slate-200">
                                <?php $posicion = 1; foreach ($topEstudiantes as $est): ?>
                                <tr class="hover:bg-slate-50">
                                    <td class="px-4 py-3 text-sm font-bold text-upemor-purple"><?php echo $posicion++; ?></td>
                                    <td class="px-4 py-3 text-sm font-medium text-slate-800"><?php echo htmlspecialchars($est['nombre_completo']); ?></td>
                                    <td class="px-4 py-3 text-sm text-slate-600"><?php echo htmlspecialchars($est['Matricula']); ?></td>
                                    <td class="px-4 py-3 text-sm text-slate-600"><?php echo htmlspecialchars($est['Grupo']); ?></td>
                                    <td class="px-4 py-3 text-sm text-center"><?php echo $est['total_evaluaciones']; ?></td>
                                    <td class="px-4 py-3 text-sm text-center text-green-600 font-semibold"><?php echo $est['excelentes']; ?></td>
                                    <td class="px-4 py-3 text-sm text-center font-semibold text-upemor-purple"><?php echo number_format($est['promedio_porcentual'], 1); ?>%</td>
                                </tr>
                                <?php endforeach; ?>
                            </tbody>
                        </table>
                    </div>
                </div>
                <?php endif; ?>
            </div>
        </main>
    </div>
    
    <!-- Script para gráfica -->
    <script>
        // Datos de evaluaciones por cuatrimestre
        const evalPorCuatrimestre = <?php echo json_encode(array_reverse($evaluacionesPorCuatrimestre)); ?>;
        
        const labels = evalPorCuatrimestre.map(item => item.label);
        
        const ctx = document.getElementById('chartEvaluacionesCuatrimestre').getContext('2d');
        new Chart(ctx, {
            type: 'bar',
            data: {
                labels: labels,
                datasets: [
                    {
                        label: 'Excelentes',
                        data: evalPorCuatrimestre.map(item => item.excelentes),
                        backgroundColor: 'rgba(22, 163, 74, 0.8)',
                        borderColor: '#16A34A',
                        borderWidth: 2
                    },
                    {
                        label: 'Buenas',
                        data: evalPorCuatrimestre.map(item => item.buenas),
                        backgroundColor: 'rgba(59, 130, 246, 0.8)',
                        borderColor: '#3B82F6',
                        borderWidth: 2
                    },
                    {
                        label: 'Deficientes',
                        data: evalPorCuatrimestre.map(item => item.deficientes),
                        backgroundColor: 'rgba(220, 38, 38, 0.8)',
                        borderColor: '#DC2626',
                        borderWidth: 2
                    }
                ]
            },
            options: {
                responsive: true,
                maintainAspectRatio: true,
                plugins: {
                    legend: {
                        position: 'bottom',
                        labels: {
                            padding: 15,
                            font: {
                                size: 12,
                                weight: 'bold'
                            }
                        }
                    },
                    tooltip: {
                        callbacks: {
                            footer: function(context) {
                                let total = 0;
                                context.forEach(item => {
                                    total += item.parsed.y;
                                });
                                return 'Total: ' + total;
                            }
                        }
                    }
                },
                scales: {
                    x: {
                        stacked: false,
                        grid: {
                            display: false
                        }
                    },
                    y: {
                        stacked: false,
                        beginAtZero: true,
                        ticks: {
                            precision: 0
                        },
                        grid: {
                            color: 'rgba(0, 0, 0, 0.05)'
                        }
                    }
                }
            }
        });
    </script>
</body>
</html>