<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="utf-8"/>
    <meta content="width=device-width, initial-scale=1.0" name="viewport"/>
    <title>Mis Reportes - UPEMOR</title>
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
// Obtener información del estudiante logueado
$idUsuario = $_SESSION['usuario_id'] ?? 0;
$nombreEstudiante = $_SESSION['usuario_nombre'] ?? 'Estudiante';

// Conectar a la base de datos
$server = "localhost";
$user = "root";
$password = "";
$db = "bdappcompetencias";

$connection = new mysqli($server, $user, $password, $db);

if($connection->connect_errno){
    die("Conexión fallida: " . $connection->connect_error);
}

$connection->set_charset("utf8mb4");

// Obtener datos del estudiante
$sqlEstudiante = "SELECT e.idEstudiantes, e.Matricula, e.Grupo, 
                  u.Nombre, u.Apellido, u.Correo, u.FechaNac
                  FROM estudiantes e
                  INNER JOIN usuarios u ON e.idUsuarios = u.idUsuarios
                  WHERE e.idUsuarios = ?
                  LIMIT 1";
$stmt = $connection->prepare($sqlEstudiante);
$stmt->bind_param("i", $idUsuario);
$stmt->execute();
$resultEst = $stmt->get_result();

if($rowEst = $resultEst->fetch_assoc()){
    $idEstudiante = $rowEst['idEstudiantes'];
    $matricula = $rowEst['Matricula'];
    $nombreCompleto = $rowEst['Nombre'] . ' ' . $rowEst['Apellido'];
    $grupo = $rowEst['Grupo'];
    $correo = $rowEst['Correo'];
    $fechaNac = $rowEst['FechaNac'];
    
    // Obtener todas las evaluaciones del estudiante
    $sqlEvaluaciones = "SELECT 
                            ev.idEvaluaciones,
                            ev.Fecha,
                            ev.Calificacion,
                            ev.Observaciones,
                            ev.TipoEvaluacion,
                            c.NombreCompetencia,
                            c.Descripcion as DescripcionCompetencia,
                            CONCAT(u.Nombre, ' ', u.Apellido) as NombreDocente
                        FROM evaluaciones ev
                        INNER JOIN competencias c ON ev.idCompetencias = c.idCompetencias
                        INNER JOIN docentes d ON ev.idDocentes = d.idDocentes
                        INNER JOIN usuarios u ON d.idUsuarios = u.idUsuarios
                        WHERE ev.idEstudiantes = ?
                        ORDER BY ev.Fecha DESC";
    $stmt = $connection->prepare($sqlEvaluaciones);
    $stmt->bind_param("i", $idEstudiante);
    $stmt->execute();
    $evaluaciones = $stmt->get_result();
    
    // Calcular estadísticas
    $totalEvaluaciones = 0;
    $excelentes = 0;
    $buenas = 0;
    $deficientes = 0;
    $competenciasEvaluadas = [];
    
    $evalArray = [];
    while($eval = $evaluaciones->fetch_assoc()){
        $evalArray[] = $eval;
        $totalEvaluaciones++;
        
        $calif = strtolower(trim($eval['Calificacion']));
        if($calif == 'excelente') $excelentes++;
        elseif($calif == 'buena' || $calif == 'bueno') $buenas++;
        elseif($calif == 'deficiente') $deficientes++;
        
        $competenciasEvaluadas[$eval['NombreCompetencia']] = true;
    }
    
    // Calcular promedio general
    $promedioGeneral = 0;
    if($totalEvaluaciones > 0){
        $promedioGeneral = round((($excelentes * 100) + ($buenas * 75) + ($deficientes * 50)) / $totalEvaluaciones, 1);
    }
    
    // Obtener progreso mensual
    $sqlProgreso = "SELECT 
                      DATE_FORMAT(Fecha, '%Y-%m') as mes,
                      AVG(CASE 
                        WHEN Calificacion = 'Excelente' THEN 100
                        WHEN Calificacion = 'Buena' OR Calificacion = 'Bueno' THEN 75
                        WHEN Calificacion = 'Deficiente' THEN 50
                        ELSE 0
                      END) as promedio,
                      COUNT(*) as total
                    FROM evaluaciones
                    WHERE idEstudiantes = ?
                    GROUP BY DATE_FORMAT(Fecha, '%Y-%m')
                    ORDER BY mes DESC
                    LIMIT 6";
    $stmt = $connection->prepare($sqlProgreso);
    $stmt->bind_param("i", $idEstudiante);
    $stmt->execute();
    $resultProgreso = $stmt->get_result();
    
    $progresoMensual = [];
    while($row = $resultProgreso->fetch_assoc()){
        $progresoMensual[] = $row;
    }
    $progresoMensual = array_reverse($progresoMensual);
    
    // Obtener evaluaciones por competencia
    $sqlPorCompetencia = "SELECT 
                            c.NombreCompetencia,
                            COUNT(*) as total,
                            SUM(CASE WHEN ev.Calificacion = 'Excelente' THEN 1 ELSE 0 END) as excelentes,
                            SUM(CASE WHEN ev.Calificacion = 'Buena' OR ev.Calificacion = 'Bueno' THEN 1 ELSE 0 END) as buenas,
                            SUM(CASE WHEN ev.Calificacion = 'Deficiente' THEN 1 ELSE 0 END) as deficientes,
                            AVG(CASE 
                              WHEN ev.Calificacion = 'Excelente' THEN 100
                              WHEN ev.Calificacion = 'Buena' OR ev.Calificacion = 'Bueno' THEN 75
                              WHEN ev.Calificacion = 'Deficiente' THEN 50
                              ELSE 0
                            END) as promedio
                          FROM evaluaciones ev
                          INNER JOIN competencias c ON ev.idCompetencias = c.idCompetencias
                          WHERE ev.idEstudiantes = ?
                          GROUP BY c.idCompetencias, c.NombreCompetencia
                          ORDER BY promedio DESC";
    $stmt = $connection->prepare($sqlPorCompetencia);
    $stmt->bind_param("i", $idEstudiante);
    $stmt->execute();
    $resultCompetencias = $stmt->get_result();
    
    $competencias = [];
    while($row = $resultCompetencias->fetch_assoc()){
        $competencias[] = $row;
    }
    
} else {
    echo "<script>alert('No se encontró información del estudiante'); window.location.href='index.php?controller=auth&action=panelEstudiante';</script>";
    exit();
}
?>
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
                        <a class="group flex items-center gap-3 rounded-lg px-3 py-2 hover:bg-upemor-orange/10 transition" href="index.php?controller=estudiante&action=misConsultas">
                            <span class="material-symbols-outlined text-slate-600 group-hover:text-upemor-purple">chat</span>
                            <p class="text-sm font-medium text-slate-700 group-hover:text-upemor-purple">Mis Consultas</p>
                        </a>
                        <a class="flex items-center gap-3 rounded-lg bg-upemor-orange/10 border-l-4 border-upemor-orange px-3 py-2" href="index.php?controller=estudiante&action=misReportes">
                            <span class="material-symbols-outlined text-upemor-purple">bar_chart</span>
                            <p class="text-sm font-bold text-upemor-purple">Mis Reportes</p>
                        </a>
                    </nav>
                </div>
                
                <div class="flex flex-col gap-2 border-t border-slate-200 pt-4">
                    <div class="rounded-lg bg-slate-50 px-3 py-2">
                        <div class="flex items-center gap-3 mb-2">
                            <div class="h-10 w-10 rounded-full bg-upemor-purple flex items-center justify-center text-white font-bold text-lg">
                                <?php echo strtoupper(substr($nombreEstudiante, 0, 1)); ?>
                            </div>
                            <div class="flex-1 min-w-0">
                                <p class="text-sm font-bold text-upemor-purple truncate"><?php echo htmlspecialchars($nombreEstudiante); ?></p>
                                <p class="text-xs text-slate-600"><?php echo htmlspecialchars($matricula); ?></p>
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
                        <h1 class="text-3xl font-bold text-upemor-purple">Mi Reporte de Progreso</h1>
                        <p class="text-slate-600">Visualiza tu progreso académico completo y recomendaciones</p>
                    </div>
                    <a href="index.php?controller=estudiante&action=exportarReportePDF" class="flex items-center gap-2 rounded-lg bg-red-600 hover:bg-red-700 px-4 py-2 text-white font-semibold transition">
                        <span class="material-symbols-outlined">picture_as_pdf</span>
                        Descargar Reporte
                    </a>
                </div>

                <div class="rounded-2xl border-t-4 border-upemor-purple bg-white p-6 shadow-lg mb-6">
                    <div class="flex items-start justify-between">
                        <div class="flex items-center gap-4">
                            <div class="flex h-16 w-16 items-center justify-center rounded-full bg-upemor-purple text-white text-2xl font-bold">
                                <?php echo strtoupper(substr($nombreCompleto, 0, 1)); ?>
                            </div>
                            <div>
                                <h2 class="text-2xl font-bold text-upemor-purple"><?php echo htmlspecialchars($nombreCompleto); ?></h2>
                                <p class="text-slate-600">Matrícula: <?php echo htmlspecialchars($matricula); ?> | Grupo: <?php echo htmlspecialchars($grupo); ?></p>
                                <p class="text-slate-600"><?php echo htmlspecialchars($correo); ?></p>
                            </div>
                        </div>
                        <div class="text-right">
                            <p class="text-sm text-slate-600">Fecha de generación</p>
                            <p class="text-lg font-bold text-upemor-purple"><?php echo date('d/m/Y'); ?></p>
                        </div>
                    </div>
                </div>

                <div class="grid grid-cols-1 md:grid-cols-4 gap-6 mb-6">
                    <div class="rounded-2xl border-t-4 border-upemor-purple bg-white p-5 shadow-lg">
                        <p class="text-sm font-medium text-slate-600">Total Evaluaciones</p>
                        <p class="text-3xl font-bold text-upemor-purple"><?php echo $totalEvaluaciones; ?></p>
                        <p class="text-sm text-slate-600 mt-1">Competencias evaluadas: <?php echo count($competenciasEvaluadas); ?></p>
                    </div>
                    <div class="rounded-2xl border-t-4 border-green-500 bg-white p-5 shadow-lg">
                        <p class="text-sm font-medium text-slate-600">Excelentes</p>
                        <p class="text-3xl font-bold text-green-600"><?php echo $excelentes; ?></p>
                        <p class="text-sm text-green-600 mt-1"><?php echo $totalEvaluaciones > 0 ? round(($excelentes/$totalEvaluaciones)*100, 1) : 0; ?>% del total</p>
                    </div>
                    <div class="rounded-2xl border-t-4 border-blue-500 bg-white p-5 shadow-lg">
                        <p class="text-sm font-medium text-slate-600">Buenas</p>
                        <p class="text-3xl font-bold text-blue-600"><?php echo $buenas; ?></p>
                        <p class="text-sm text-blue-600 mt-1"><?php echo $totalEvaluaciones > 0 ? round(($buenas/$totalEvaluaciones)*100, 1) : 0; ?>% del total</p>
                    </div>
                    <div class="rounded-2xl border-t-4 border-upemor-orange bg-white p-5 shadow-lg">
                        <p class="text-sm font-medium text-slate-600">Promedio General</p>
                        <p class="text-3xl font-bold text-upemor-orange"><?php echo $promedioGeneral; ?>%</p>
                        <p class="text-sm text-slate-600 mt-1">De desempeño</p>
                    </div>
                </div>

                <?php if(!empty($progresoMensual)): ?>
                <div class="rounded-2xl bg-white p-6 shadow-lg mb-6">
                    <h3 class="text-xl font-bold text-upemor-purple mb-4">Evolución de mi Desempeño</h3>
                    <canvas id="chartProgreso" height="80"></canvas>
                </div>
                <?php endif; ?>

                <?php if(!empty($competencias)): ?>
                <div class="rounded-2xl bg-white p-6 shadow-lg mb-6">
                    <h3 class="text-xl font-bold text-upemor-purple mb-4">Progreso por Competencia</h3>
                    <div class="overflow-x-auto">
                        <table class="w-full">
                            <thead class="bg-slate-50">
                                <tr>
                                    <th class="px-4 py-3 text-left text-sm font-semibold text-slate-700">Competencia</th>
                                    <th class="px-4 py-3 text-center text-sm font-semibold text-slate-700">Total Eval.</th>
                                    <th class="px-4 py-3 text-center text-sm font-semibold text-green-600">Excelentes</th>
                                    <th class="px-4 py-3 text-center text-sm font-semibold text-blue-600">Buenas</th>
                                    <th class="px-4 py-3 text-center text-sm font-semibold text-red-600">Deficientes</th>
                                    <th class="px-4 py-3 text-center text-sm font-semibold text-slate-700">Promedio</th>
                                </tr>
                            </thead>
                            <tbody class="divide-y divide-slate-200">
                                <?php foreach($competencias as $comp): ?>
                                <tr class="hover:bg-slate-50">
                                    <td class="px-4 py-3 text-sm font-medium text-slate-800">
                                        <?php echo htmlspecialchars($comp['NombreCompetencia']); ?>
                                    </td>
                                    <td class="px-4 py-3 text-center text-sm font-bold text-upemor-purple">
                                        <?php echo $comp['total']; ?>
                                    </td>
                                    <td class="px-4 py-3 text-center text-sm text-green-600">
                                        <?php echo $comp['excelentes']; ?>
                                    </td>
                                    <td class="px-4 py-3 text-center text-sm text-blue-600">
                                        <?php echo $comp['buenas']; ?>
                                    </td>
                                    <td class="px-4 py-3 text-center text-sm text-red-600">
                                        <?php echo $comp['deficientes']; ?>
                                    </td>
                                    <td class="px-4 py-3 text-center">
                                        <div class="flex items-center justify-center gap-2">
                                            <span class="text-sm font-bold text-upemor-purple"><?php echo round($comp['promedio'], 1); ?>%</span>
                                            <div class="w-20 bg-slate-200 rounded-full h-2">
                                                <div class="bg-gradient-to-r from-upemor-purple to-upemor-orange h-2 rounded-full" style="width: <?php echo $comp['promedio']; ?>%"></div>
                                            </div>
                                        </div>
                                    </td>
                                </tr>
                                <?php endforeach; ?>
                            </tbody>
                        </table>
                    </div>
                </div>
                <?php endif; ?>

                <div class="rounded-2xl bg-white p-6 shadow-lg mb-6">
                    <h3 class="text-xl font-bold text-upemor-purple mb-4">Historial Completo de Evaluaciones</h3>
                    <div class="space-y-3">
                        <?php if(!empty($evalArray)): ?>
                            <?php foreach($evalArray as $eval): ?>
                            <div class="border-l-4 <?php 
                                $calif = strtolower($eval['Calificacion']);
                                if($calif == 'excelente') echo 'border-green-500';
                                elseif($calif == 'buena' || $calif == 'bueno') echo 'border-blue-500';
                                else echo 'border-red-500';
                            ?> bg-slate-50 p-4 rounded-r-lg">
                                <div class="flex justify-between items-start mb-2">
                                    <div class="flex-1">
                                        <h4 class="font-bold text-upemor-purple"><?php echo htmlspecialchars($eval['NombreCompetencia']); ?></h4>
                                        <p class="text-sm text-slate-600"><?php echo htmlspecialchars($eval['DescripcionCompetencia']); ?></p>
                                    </div>
                                    <span class="inline-block rounded-full px-3 py-1 text-xs font-semibold <?php 
                                        if($calif == 'excelente') echo 'bg-green-100 text-green-800';
                                        elseif($calif == 'buena' || $calif == 'bueno') echo 'bg-blue-100 text-blue-800';
                                        else echo 'bg-red-100 text-red-800';
                                    ?>">
                                        <?php echo htmlspecialchars($eval['Calificacion']); ?>
                                    </span>
                                </div>
                                <div class="grid grid-cols-1 md:grid-cols-3 gap-2 text-sm text-slate-600 mt-3">
                                    <div>
                                        <span class="font-semibold">Fecha:</span> <?php echo date('d/m/Y', strtotime($eval['Fecha'])); ?>
                                    </div>
                                    <div>
                                        <span class="font-semibold">Tipo:</span> <?php echo htmlspecialchars($eval['TipoEvaluacion']); ?>
                                    </div>
                                    <div>
                                        <span class="font-semibold">Docente:</span> <?php echo htmlspecialchars($eval['NombreDocente']); ?>
                                    </div>
                                </div>
                                <?php if(!empty($eval['Observaciones'])): ?>
                                <div class="mt-3 p-3 bg-white rounded border border-slate-200">
                                    <p class="text-sm font-semibold text-slate-700">Observaciones del docente:</p>
                                    <p class="text-sm text-slate-600 mt-1"><?php echo htmlspecialchars($eval['Observaciones']); ?></p>
                                </div>
                                <?php endif; ?>
                            </div>
                            <?php endforeach; ?>
                        <?php else: ?>
                        <div class="text-center py-12">
                            <span class="material-symbols-outlined text-6xl text-slate-300">assignment</span>
                            <p class="mt-4 text-lg font-semibold text-slate-600">No hay evaluaciones registradas</p>
                        </div>
                        <?php endif; ?>
                    </div>
                </div>

                <div class="rounded-2xl bg-gradient-to-r from-upemor-purple to-purple-900 p-6 shadow-lg text-white">
                    <h3 class="text-xl font-bold mb-4 flex items-center gap-2">
                        <span class="material-symbols-outlined">lightbulb</span>
                        Recomendaciones para tu Desarrollo
                    </h3>
                    <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                        <?php if($promedioGeneral >= 85): ?>
                        <div class="bg-white/10 rounded-lg p-4">
                            <p class="font-semibold mb-2">¡Excelente desempeño!</p>
                            <p class="text-sm">Continúa con tu dedicación. Considera ser tutor de tus compañeros.</p>
                        </div>
                        <?php elseif($promedioGeneral >= 70): ?>
                        <div class="bg-white/10 rounded-lg p-4">
                            <p class="font-semibold mb-2">Buen trabajo</p>
                            <p class="text-sm">Mantén tu esfuerzo. Revisa las áreas donde puedes mejorar.</p>
                        </div>
                        <?php else: ?>
                        <div class="bg-white/10 rounded-lg p-4">
                            <p class="font-semibold mb-2">Área de oportunidad</p>
                            <p class="text-sm">Solicita asesorías y dedica más tiempo a tu estudio.</p>
                        </div>
                        <?php endif; ?>
                        
                        <div class="bg-white/10 rounded-lg p-4">
                            <p class="font-semibold mb-2">Desarrollo continuo</p>
                            <p class="text-sm">Revisa constantemente tus evaluaciones y observaciones de los docentes.</p>
                        </div>
                    </div>
                </div>
            </div>
        </main>
    </div>

    <?php if(!empty($progresoMensual)): ?>
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
                    label: 'Promedio Mensual (%)',
                    data: progreso.map(item => parseFloat(item.promedio)),
                    borderColor: '#4A1E6F',
                    backgroundColor: 'rgba(74, 30, 111, 0.1)',
                    tension: 0.4,
                    fill: true,
                    pointRadius: 5,
                    pointHoverRadius: 7
                }]
            },
            options: {
                responsive: true,
                maintainAspectRatio: true,
                plugins: {
                    legend: {
                        position: 'bottom'
                    },
                    tooltip: {
                        callbacks: {
                            label: function(context) {
                                return 'Promedio: ' + context.parsed.y.toFixed(1) + '%';
                            }
                        }
                    }
                },
                scales: {
                    y: {
                        beginAtZero: true,
                        max: 100,
                        ticks: {
                            callback: function(value) {
                                return value + '%';
                            }
                        }
                    }
                }
            }
        });
    </script>
    <?php endif; ?>
</body>
</html>