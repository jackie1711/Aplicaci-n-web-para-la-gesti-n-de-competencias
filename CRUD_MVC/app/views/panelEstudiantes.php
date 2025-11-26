<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="utf-8"/>
    <meta content="width=device-width, initial-scale=1.0" name="viewport"/>
    <title>Mi Portal - UPEMOR</title>
    <script src="https://cdn.tailwindcss.com?plugins=forms,container-queries"></script>
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@400;500;600;700;800;900&display=swap" rel="stylesheet"/>
    <link href="https://fonts.googleapis.com/css2?family=Material+Symbols+Outlined" rel="stylesheet"/>
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
// Iniciar sesión si no está iniciada
if (session_status() === PHP_SESSION_NONE) {
    session_start();
}

// Obtener información del estudiante logueado
$estudianteId = $_SESSION['usuario_id'] ?? 0;
$nombreEstudiante = $_SESSION['usuario_nombre'] ?? 'Estudiante';

// Variables por defecto
$totalEvaluaciones = 0;
$evaluacionesExcelentes = 0;
$evaluacionesBuenas = 0;
$evaluacionesDeficientes = 0;
$promedioGeneral = 0;
$evidenciasPendientes = 0;
$matricula = "N/A";
$nombreCompleto = $nombreEstudiante;
$grupo = "N/A";
$idEstudiante = 0;

try {
    $server = "localhost";
    $user = "root";
    $password = "";
    $db = "bdappcompetencias";
    
    $connection = new mysqli($server, $user, $password, $db);
    
    if($connection->connect_errno){
        throw new Exception("Conexión fallida: " . $connection->connect_error);
    }
    
    $connection->set_charset("utf8mb4");
    
    // Buscar el ID del estudiante mediante JOIN con usuarios
    $sqlEstudiante = "SELECT e.idEstudiantes, e.Matricula, e.Grupo, 
                      u.Nombre, u.Apellido
                      FROM estudiantes e
                      INNER JOIN usuarios u ON e.idUsuarios = u.idUsuarios
                      WHERE e.idUsuarios = ?
                      LIMIT 1";
    $stmt = $connection->prepare($sqlEstudiante);
    $stmt->bind_param("i", $estudianteId);
    $stmt->execute();
    $resultEst = $stmt->get_result();
    
    if($rowEst = $resultEst->fetch_assoc()){
        $idEstudiante = $rowEst['idEstudiantes'];
        $matricula = $rowEst['Matricula'];
        $nombreCompleto = $rowEst['Nombre'] . ' ' . $rowEst['Apellido'];
        $grupo = $rowEst['Grupo'];
        
        // Calcular evidencias pendientes
        $sqlPendientes = "SELECT COUNT(*) as total FROM asignaciones_evidencias WHERE idEstudiantes = ? AND Estado = 'Pendiente'";
        $stmtPend = $connection->prepare($sqlPendientes);
        $stmtPend->bind_param("i", $idEstudiante);
        $stmtPend->execute();
        $resultPend = $stmtPend->get_result();
        if($rowPend = $resultPend->fetch_assoc()){
            $evidenciasPendientes = (int)$rowPend['total'];
        }
        
        // Total de evaluaciones del estudiante
        $sqlTotal = "SELECT COUNT(*) as total FROM evaluaciones WHERE idEstudiantes = ?";
        $stmt = $connection->prepare($sqlTotal);
        $stmt->bind_param("i", $idEstudiante);
        $stmt->execute();
        $result = $stmt->get_result();
        if($row = $result->fetch_assoc()){
            $totalEvaluaciones = (int)$row['total'];
        }
        
        // Evaluaciones por calificación
        $sqlCalif = "SELECT Calificacion, COUNT(*) as total 
                     FROM evaluaciones 
                     WHERE idEstudiantes = ?
                     GROUP BY Calificacion";
        $stmt = $connection->prepare($sqlCalif);
        $stmt->bind_param("i", $idEstudiante);
        $stmt->execute();
        $result = $stmt->get_result();
        
        while($row = $result->fetch_assoc()){
            $calif = strtolower(trim($row['Calificacion']));
            $cantidad = (int)$row['total'];
            
            if($calif == 'excelente'){
                $evaluacionesExcelentes = $cantidad;
            } elseif($calif == 'buena' || $calif == 'bueno'){
                $evaluacionesBuenas = $cantidad;
            } elseif($calif == 'deficiente'){
                $evaluacionesDeficientes = $cantidad;
            }
        }
        
        // Calcular promedio general
        if($totalEvaluaciones > 0){
            $promedioGeneral = round((($evaluacionesExcelentes * 100) + ($evaluacionesBuenas * 75) + ($evaluacionesDeficientes * 50)) / $totalEvaluaciones, 1);
        }
    }
    
} catch(Exception $e) {
    error_log("Error en panel estudiante: " . $e->getMessage());
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
                        <a class="flex items-center gap-3 rounded-lg bg-upemor-orange/10 border-l-4 border-upemor-orange px-3 py-2" href="index.php?controller=auth&action=panelEstudiante">
                            <span class="material-symbols-outlined text-upemor-purple">space_dashboard</span>
                            <p class="text-sm font-bold text-upemor-purple">Dashboard</p>
                        </a>
                        <a class="group flex items-center gap-3 rounded-lg px-3 py-2 hover:bg-upemor-orange/10 transition" href="index.php?controller=estudiante&action=misConsultas">
                            <span class="material-symbols-outlined text-slate-600 group-hover:text-upemor-purple">chat</span>
                            <p class="text-sm font-medium text-slate-700 group-hover:text-upemor-purple">Mis Consultas</p>
                        </a>
                        <a class="group flex items-center gap-3 rounded-lg px-3 py-2 hover:bg-upemor-orange/10 transition" href="index.php?controller=estudiante&action=misReportes">
                            <span class="material-symbols-outlined text-slate-600 group-hover:text-upemor-purple">bar_chart</span>
                            <p class="text-sm font-medium text-slate-700 group-hover:text-upemor-purple">Mis Reportes</p>
                        </a>
                        <a class="group flex items-center gap-3 rounded-lg px-3 py-2 hover:bg-upemor-orange/10 transition" href="index.php?controller=estudiante&action=evidenciasPendientes">
                            <span class="material-symbols-outlined text-slate-600 group-hover:text-upemor-purple">assignment</span>
                            <p class="text-sm font-medium text-slate-700 group-hover:text-upemor-purple">Evidencias Pendientes</p>
                            <?php if($evidenciasPendientes > 0): ?>
                            <span class="ml-auto bg-upemor-red text-white text-xs font-bold rounded-full h-5 w-5 flex items-center justify-center">
                                <?php echo $evidenciasPendientes; ?>
                            </span>
                            <?php endif; ?>
                        </a>
                        <a class="group flex items-center gap-3 rounded-lg px-3 py-2 hover:bg-upemor-orange/10 transition" href="index.php?controller=evidencia&action=misEvidencias">
                            <span class="material-symbols-outlined text-slate-600 group-hover:text-upemor-purple">folder_open</span>
                            <p class="text-sm font-medium text-slate-700 group-hover:text-upemor-purple">Mis Evidencias</p>
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
                        <div class="text-xs text-slate-600">
                            <p><strong>Grupo:</strong> <?php echo htmlspecialchars($grupo); ?></p>
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
                <div class="flex flex-wrap items-center justify-between gap-4 mb-6">
                    <div class="flex flex-col">
                        <h1 class="text-3xl font-bold text-upemor-purple">Mi Dashboard</h1>
                        <p class="text-slate-600">Bienvenido, <?php echo htmlspecialchars($nombreEstudiante); ?></p>
                    </div>
                    
                    <div class="flex gap-2">
                        <div class="flex items-center gap-2 bg-white px-4 py-2 rounded-lg shadow-sm border border-slate-200">
                            <span class="material-symbols-outlined text-upemor-purple">calendar_today</span>
                            <span class="text-sm font-medium text-slate-700"><?php echo date('d/m/Y'); ?></span>
                        </div>
                    </div>
                </div>
                
                <div class="grid grid-cols-1 gap-6 sm:grid-cols-2 lg:grid-cols-4 mb-8">
                    <div class="flex flex-col gap-2 rounded-2xl border-t-4 border-upemor-purple bg-white p-5 shadow-lg">
                        <p class="text-sm font-medium text-slate-600">Total Evaluaciones</p>
                        <p class="text-3xl font-bold text-upemor-purple"><?php echo $totalEvaluaciones; ?></p>
                        <p class="text-sm font-medium text-slate-600">Registradas</p>
                    </div>
                    
                    <div class="flex flex-col gap-2 rounded-2xl border-t-4 border-green-500 bg-white p-5 shadow-lg">
                        <p class="text-sm font-medium text-slate-600">Excelentes</p>
                        <p class="text-3xl font-bold text-green-600"><?php echo $evaluacionesExcelentes; ?></p>
                        <p class="text-sm font-medium text-success">Calificación sobresaliente</p>
                    </div>
                    
                    <div class="flex flex-col gap-2 rounded-2xl border-t-4 border-blue-500 bg-white p-5 shadow-lg">
                        <p class="text-sm font-medium text-slate-600">Buenas</p>
                        <p class="text-3xl font-bold text-blue-600"><?php echo $evaluacionesBuenas; ?></p>
                        <p class="text-sm font-medium text-blue-600">Buen desempeño</p>
                    </div>
                    
                    <div class="flex flex-col gap-2 rounded-2xl border-t-4 border-upemor-orange bg-white p-5 shadow-lg">
                        <p class="text-sm font-medium text-slate-600">Promedio General</p>
                        <p class="text-3xl font-bold text-upemor-orange"><?php echo $promedioGeneral; ?>%</p>
                        <p class="text-sm font-medium text-slate-600">De desempeño</p>
                    </div>
                </div>
                
                <h2 class="text-2xl font-bold text-upemor-purple pb-3 pt-4">Acceso Rápido</h2>
                
                <div class="grid grid-cols-1 gap-6 md:grid-cols-2 lg:grid-cols-4">
                    
                    <a href="index.php?controller=estudiante&action=misConsultas" class="group flex flex-col gap-4 rounded-2xl border-t-4 border-upemor-purple bg-white p-5 shadow-lg transition-all hover:shadow-xl hover:scale-105">
                        <div class="flex h-12 w-12 items-center justify-center rounded-lg bg-gradient-to-br from-upemor-purple/10 to-purple-100">
                            <span class="material-symbols-outlined text-3xl text-upemor-purple">chat</span>
                        </div>
                        <div>
                            <p class="text-base font-semibold text-upemor-purple">Mis Consultas</p>
                            <p class="mt-1 text-sm text-slate-600">Consulta tu progreso.</p>
                            <div class="mt-3 inline-flex items-center gap-1 text-sm font-bold text-upemor-orange group-hover:text-upemor-red transition">
                                <span>Ver Evaluaciones</span>
                                <span class="material-symbols-outlined !text-[18px]">arrow_forward</span>
                            </div>
                        </div>
                    </a>
                    
                    <a href="index.php?controller=estudiante&action=misReportes" class="group flex flex-col gap-4 rounded-2xl border-t-4 border-blue-500 bg-white p-5 shadow-lg transition-all hover:shadow-xl hover:scale-105">
                        <div class="flex h-12 w-12 items-center justify-center rounded-lg bg-gradient-to-br from-blue-50 to-blue-100">
                            <span class="material-symbols-outlined text-3xl text-blue-600">bar_chart</span>
                        </div>
                        <div>
                            <p class="text-base font-semibold text-upemor-purple">Mis Reportes</p>
                            <p class="mt-1 text-sm text-slate-600">Visualiza tu desempeño.</p>
                            <div class="mt-3 inline-flex items-center gap-1 text-sm font-bold text-upemor-orange group-hover:text-upemor-red transition">
                                <span>Ver Reportes</span>
                                <span class="material-symbols-outlined !text-[18px]">arrow_forward</span>
                            </div>
                        </div>
                    </a>
                    
                    <a href="index.php?controller=evidencia&action=misEvidencias" class="group flex flex-col gap-4 rounded-2xl border-t-4 border-teal-500 bg-white p-5 shadow-lg transition-all hover:shadow-xl hover:scale-105">
                        <div class="flex h-12 w-12 items-center justify-center rounded-lg bg-gradient-to-br from-teal-50 to-teal-100">
                            <span class="material-symbols-outlined text-3xl text-teal-600">folder_open</span>
                        </div>
                        <div>
                            <p class="text-base font-semibold text-upemor-purple">Mis Evidencias</p>
                            <p class="mt-1 text-sm text-slate-600">Historial de archivos subidos.</p>
                            <div class="mt-3 inline-flex items-center gap-1 text-sm font-bold text-upemor-orange group-hover:text-upemor-red transition">
                                <span>Ver Historial</span>
                                <span class="material-symbols-outlined !text-[18px]">arrow_forward</span>
                            </div>
                        </div>
                    </a>

                    <a href="index.php?controller=estudiante&action=evidenciasPendientes" class="group flex flex-col gap-4 rounded-2xl border-t-4 border-upemor-red bg-white p-5 shadow-lg transition-all hover:shadow-xl hover:scale-105">
                        <div class="flex h-12 w-12 items-center justify-center rounded-lg bg-gradient-to-br from-upemor-red/10 to-red-100">
                            <span class="material-symbols-outlined text-3xl text-upemor-red">assignment_late</span>
                        </div>
                        <div>
                            <div class="flex items-center gap-2">
                                <p class="text-base font-semibold text-upemor-purple">Pendientes</p>
                                <?php if($evidenciasPendientes > 0): ?>
                                <span class="bg-upemor-red text-white text-xs font-bold rounded-full h-6 w-6 flex items-center justify-center">
                                    <?php echo $evidenciasPendientes; ?>
                                </span>
                                <?php endif; ?>
                            </div>
                            <p class="mt-1 text-sm text-slate-600">Tareas por entregar.</p>
                            <div class="mt-3 inline-flex items-center gap-1 text-sm font-bold text-upemor-orange group-hover:text-upemor-red transition">
                                <span>Ir a Entregas</span>
                                <span class="material-symbols-outlined !text-[18px]">arrow_forward</span>
                            </div>
                        </div>
                    </a>
                </div>
            </div>
        </main>
    </div>
</body>
</html>