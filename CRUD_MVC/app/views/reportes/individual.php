<?php
// app/views/reportes/individual.php

// 1. LÓGICA DE PRE-PROCESAMIENTO PARA GRÁFICA Y CONSEJOS
$chartLabels = [];
$chartData = [];
$promedioNumerico = 0;
$totalPuntos = 0;
$conteo = 0;

// Mapeo de calificaciones a valores numéricos para la gráfica y promedio
$valores = [
    'Excelente' => 100,
    'Buena' => 80,
    'Bueno' => 80,
    'Regular' => 60,
    'Deficiente' => 50
];

// Si hay evaluaciones, procesamos los datos
if (!empty($evaluaciones)) {
    // Invertimos el array para que la gráfica vaya de la fecha más antigua (izquierda) a la más reciente (derecha)
    $evaluacionesGrafica = array_reverse($evaluaciones);
    
    foreach ($evaluacionesGrafica as $ev) {
        // Etiquetas: Fecha corta + Nombre competencia truncado
        $fechaCorta = date('d/m', strtotime($ev['Fecha']));
        $compCorta = substr($ev['NombreCompetencia'], 0, 10) . '...';
        $chartLabels[] = "$fechaCorta - $compCorta";
        
        // Datos numéricos
        $califTexto = ucfirst(strtolower(trim($ev['Calificacion'])));
        // Ajuste por si viene como 'Bueno' o 'Buena'
        if($califTexto == 'Bueno') $califTexto = 'Buena';
        
        $valor = $valores[$califTexto] ?? 0;
        $chartData[] = $valor;
        
        $totalPuntos += $valor;
        $conteo++;
    }
    
    if($conteo > 0) {
        $promedioNumerico = $totalPuntos / $conteo;
    }
}

// Determinar el consejo y color basado en el promedio
$consejoTitulo = "";
$consejoTexto = "";
$consejoColor = "";
$consejoIcono = "";

if ($promedioNumerico >= 90) {
    $consejoTitulo = "Desempeño Sobresaliente";
    $consejoTexto = "El estudiante muestra un dominio excelente de las competencias. Se recomienda asignarle roles de liderazgo en equipos o proyectos avanzados para mantener su motivación.";
    $consejoColor = "bg-green-100 border-green-500 text-green-800";
    $consejoIcono = "workspace_premium";
} elseif ($promedioNumerico >= 80) {
    $consejoTitulo = "Buen Desempeño";
    $consejoTexto = "El estudiante cumple con los objetivos. Para alcanzar la excelencia, se sugiere enfocar la retroalimentación en los detalles finos de las entregas y fomentar la proactividad.";
    $consejoColor = "bg-blue-100 border-blue-500 text-blue-800";
    $consejoIcono = "thumb_up";
} elseif ($promedioNumerico >= 60) {
    $consejoTitulo = "Desempeño Regular";
    $consejoTexto = "El estudiante presenta altibajos. Se recomienda una reunión de tutoría para identificar obstáculos y establecer un plan de estudio con fechas de revisión cortas.";
    $consejoColor = "bg-yellow-100 border-yellow-500 text-yellow-800";
    $consejoIcono = "warning";
} else {
    $consejoTitulo = "Requiere Atención Inmediata";
    $consejoTexto = "El promedio indica dificultades significativas. Es urgente activar el protocolo de asesorías académicas y revisar si existen factores externos afectando su rendimiento.";
    $consejoColor = "bg-red-100 border-red-500 text-red-800";
    $consejoIcono = "report";
}
?>
<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="utf-8"/>
    <meta content="width=device-width, initial-scale=1.0" name="viewport"/>
    <title>Reporte Individual - UPEMOR</title>
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
                                <a class="flex items-center gap-3 rounded-lg bg-upemor-orange/10 border-l-4 border-upemor-orange px-3 py-2" href="index.php?controller=reporte&action=individual">
                                    <span class="material-symbols-outlined text-upemor-purple">person</span>
                                    <p class="text-sm font-bold text-upemor-purple">Individuales</p>
                                </a>
                                <a class="group flex items-center gap-3 rounded-lg px-3 py-2 hover:bg-upemor-orange/10 transition" href="index.php?controller=reporte&action=historico">
                                    <span class="material-symbols-outlined text-slate-600 group-hover:text-upemor-purple">history</span>
                                    <p class="text-sm font-medium text-slate-700 group-hover:text-upemor-purple">Históricos</p>
                                </a>
                            </div>
                        </div>
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
                <div class="mb-6 flex flex-col md:flex-row justify-between items-start md:items-center gap-4">
                    <div>
                        <h1 class="text-3xl font-bold text-upemor-purple">Reporte Individual</h1>
                        <p class="text-slate-600">Análisis de desempeño y recomendaciones</p>
                    </div>
                    
                    <div class="w-full md:w-auto">
                        <select onchange="window.location.href='index.php?controller=reporte&action=individual&estudiante_id=' + this.value" class="w-full md:w-80 rounded-lg border border-slate-300 px-4 py-2 focus:border-upemor-purple focus:ring focus:ring-upemor-purple/30 bg-white shadow-sm">
                            <option value="">-- Seleccionar estudiante --</option>
                            <?php foreach ($estudiantes as $est): ?>
                            <option value="<?php echo $est['idEstudiantes']; ?>" <?php echo (isset($_GET['estudiante_id']) && $_GET['estudiante_id'] == $est['idEstudiantes']) ? 'selected' : ''; ?>>
                                <?php echo htmlspecialchars($est['Apellido'] . ' ' . $est['Nombre'] . ' - ' . $est['Matricula']); ?>
                            </option>
                            <?php endforeach; ?>
                        </select>
                    </div>
                </div>
                
                <?php if ($estudianteSeleccionado): ?>
                
                <div class="rounded-2xl bg-white p-6 shadow-lg mb-6 border-l-4 border-upemor-purple">
                    <div class="flex flex-col md:flex-row justify-between items-center gap-4">
                        <div class="flex items-center gap-4 w-full">
                            <div class="flex h-16 w-16 flex-shrink-0 items-center justify-center rounded-full bg-gradient-to-br from-upemor-purple to-indigo-900 text-white text-2xl font-bold shadow-md">
                                <?php echo strtoupper(substr($estudianteSeleccionado['Nombre'], 0, 1) . substr($estudianteSeleccionado['Apellido'], 0, 1)); ?>
                            </div>
                            <div>
                                <h2 class="text-2xl font-bold text-slate-800">
                                    <?php echo htmlspecialchars($estudianteSeleccionado['Nombre'] . ' ' . $estudianteSeleccionado['Apellido']); ?>
                                </h2>
                                <div class="flex flex-wrap gap-3 mt-1 text-sm text-slate-600">
                                    <span class="bg-slate-100 px-2 py-0.5 rounded border border-slate-200">Matrícula: <b><?php echo htmlspecialchars($estudianteSeleccionado['Matricula']); ?></b></span>
                                    <span class="bg-slate-100 px-2 py-0.5 rounded border border-slate-200">Grupo: <b><?php echo htmlspecialchars($estudianteSeleccionado['Grupo']); ?></b></span>
                                </div>
                            </div>
                        </div>
                        
                        <div class="flex gap-3 w-full md:w-auto">
                            <a href="index.php?controller=reporte&action=exportarIndividualPDF&estudiante_id=<?php echo $estudianteSeleccionado['idEstudiantes']; ?>" class="flex-1 md:flex-none flex items-center justify-center gap-2 rounded-lg bg-red-600 hover:bg-red-700 px-4 py-2 text-white font-semibold transition shadow-sm">
                                <span class="material-symbols-outlined">picture_as_pdf</span>
                                Exportar PDF
                            </a>
                        </div>
                    </div>
                </div>

                <?php if (!empty($evaluaciones)): ?>
                
                <div class="grid grid-cols-1 lg:grid-cols-3 gap-6 mb-6">
                    
                    <div class="lg:col-span-1">
                        <div class="h-full rounded-2xl p-6 shadow-lg border-l-4 <?php echo $consejoColor; ?> bg-white">
                            <div class="flex items-center gap-3 mb-3">
                                <span class="material-symbols-outlined text-3xl"><?php echo $consejoIcono; ?></span>
                                <h3 class="text-lg font-bold">Diagnóstico y Consejos</h3>
                            </div>
                            <h4 class="text-md font-semibold mb-2"><?php echo $consejoTitulo; ?></h4>
                            <p class="text-sm leading-relaxed opacity-90">
                                <?php echo $consejoTexto; ?>
                            </p>
                            
                            <div class="mt-6 pt-4 border-t border-slate-200/50">
                                <p class="text-xs text-slate-500 uppercase font-bold mb-2">Resumen Numérico</p>
                                <div class="flex justify-between items-center">
                                    <span class="text-sm">Promedio Global:</span>
                                    <span class="text-2xl font-bold"><?php echo number_format($promedioNumerico, 1); ?></span>
                                </div>
                                <div class="w-full bg-slate-200 rounded-full h-2.5 mt-2">
                                    <div class="h-2.5 rounded-full <?php echo str_replace('bg-', 'bg-', str_replace('100', '500', explode(' ', $consejoColor)[0])); ?>" style="width: <?php echo $promedioNumerico; ?>%"></div>
                                </div>
                            </div>
                        </div>
                    </div>

                    <div class="lg:col-span-2">
                        <div class="h-full rounded-2xl bg-white p-6 shadow-lg">
                            

[Image of line chart visualization]

                            <h3 class="text-lg font-bold text-upemor-purple mb-4 flex items-center gap-2">
                                <span class="material-symbols-outlined">monitoring</span>
                                Historial de Rendimiento
                            </h3>
                            <div class="relative h-64 w-full">
                                <canvas id="graficaDesempeno"></canvas>
                            </div>
                        </div>
                    </div>
                </div>

                <div class="rounded-2xl bg-white p-6 shadow-lg">
                    <h3 class="text-xl font-bold text-upemor-purple mb-4">Detalle de Evaluaciones</h3>
                    <div class="overflow-x-auto">
                        <table class="w-full text-sm text-left">
                            <thead class="bg-slate-50 text-slate-700 uppercase font-bold border-b border-slate-200">
                                <tr>
                                    <th class="px-4 py-3">Fecha</th>
                                    <th class="px-4 py-3">Competencia</th>
                                    <th class="px-4 py-3">Docente</th>
                                    <th class="px-4 py-3 text-center">Resultado</th>
                                    <th class="px-4 py-3">Observaciones</th>
                                </tr>
                            </thead>
                            <tbody class="divide-y divide-slate-200">
                                <?php foreach ($evaluaciones as $ev): ?>
                                <tr class="hover:bg-slate-50 transition">
                                    <td class="px-4 py-3 font-medium"><?php echo date('d/m/Y', strtotime($ev['Fecha'])); ?></td>
                                    <td class="px-4 py-3">
                                        <p class="font-semibold text-upemor-purple"><?php echo htmlspecialchars($ev['NombreCompetencia']); ?></p>
                                        <p class="text-xs text-slate-500 truncate w-48"><?php echo htmlspecialchars($ev['DescripcionCompetencia']); ?></p>
                                    </td>
                                    <td class="px-4 py-3 text-slate-600"><?php echo htmlspecialchars($ev['NombreDocente']); ?></td>
                                    <td class="px-4 py-3 text-center">
                                        <span class="inline-block px-3 py-1 rounded-full text-xs font-bold
                                            <?php 
                                            $c = $ev['Calificacion'];
                                            if($c == 'Excelente') echo 'bg-green-100 text-green-800';
                                            elseif($c == 'Buena' || $c == 'Bueno') echo 'bg-blue-100 text-blue-800';
                                            elseif($c == 'Regular') echo 'bg-yellow-100 text-yellow-800';
                                            else echo 'bg-red-100 text-red-800';
                                            ?>">
                                            <?php echo htmlspecialchars($c); ?>
                                        </span>
                                    </td>
                                    <td class="px-4 py-3 text-slate-500 italic max-w-xs truncate">
                                        <?php echo htmlspecialchars($ev['Observaciones'] ?? 'Sin observaciones'); ?>
                                    </td>
                                </tr>
                                <?php endforeach; ?>
                            </tbody>
                        </table>
                    </div>
                </div>

                <script>
                    const ctx = document.getElementById('graficaDesempeno');
                    
                    // Datos pasados desde PHP
                    const labels = <?php echo json_encode($chartLabels); ?>;
                    const dataPoints = <?php echo json_encode($chartData); ?>;

                    new Chart(ctx, {
                        type: 'line',
                        data: {
                            labels: labels,
                            datasets: [{
                                label: 'Puntaje (0-100)',
                                data: dataPoints,
                                borderColor: '#4A1E6F', // upemor-purple
                                backgroundColor: 'rgba(247, 148, 29, 0.1)', // upemor-orange with opacity
                                borderWidth: 3,
                                pointBackgroundColor: '#F7941D',
                                pointRadius: 5,
                                pointHoverRadius: 7,
                                tension: 0.3, // Curva suave
                                fill: true
                            }]
                        },
                        options: {
                            responsive: true,
                            maintainAspectRatio: false,
                            plugins: {
                                legend: { display: false },
                                tooltip: {
                                    callbacks: {
                                        label: function(context) {
                                            let label = context.dataset.label || '';
                                            if (label) { label += ': '; }
                                            if (context.parsed.y !== null) {
                                                label += context.parsed.y;
                                                // Añadir texto cualitativo
                                                if(context.parsed.y == 100) label += " (Excelente)";
                                                else if(context.parsed.y == 80) label += " (Buena)";
                                                else if(context.parsed.y == 60) label += " (Regular)";
                                                else label += " (Deficiente)";
                                            }
                                            return label;
                                        }
                                    }
                                }
                            },
                            scales: {
                                y: {
                                    beginAtZero: true,
                                    max: 100,
                                    grid: { color: '#f3f4f6' }
                                },
                                x: {
                                    grid: { display: false }
                                }
                            }
                        }
                    });
                </script>

                <?php else: ?>
                <div class="rounded-2xl bg-white p-12 shadow-lg text-center border border-slate-200">
                    <span class="material-symbols-outlined text-6xl text-slate-300 mb-4">assessment</span>
                    <h3 class="text-xl font-bold text-slate-700">Sin datos suficientes</h3>
                    <p class="text-slate-500 mt-2">El estudiante seleccionado aún no tiene evaluaciones registradas para generar un reporte o gráficas.</p>
                </div>
                <?php endif; ?>
                
                <?php else: ?>
                <div class="rounded-2xl bg-white p-16 shadow-lg text-center border border-slate-200">
                    <div class="inline-flex h-20 w-20 items-center justify-center rounded-full bg-upemor-orange/10 mb-6">
                        <span class="material-symbols-outlined text-5xl text-upemor-orange">person_search</span>
                    </div>
                    <h3 class="text-2xl font-bold text-slate-800">Seleccione un Estudiante</h3>
                    <p class="text-slate-500 mt-2 max-w-md mx-auto">Utilice el menú desplegable superior para cargar el historial académico, consejos personalizados y gráficas de rendimiento.</p>
                </div>
                <?php endif; ?>
            </div>
        </main>
    </div>
</body>
</html>