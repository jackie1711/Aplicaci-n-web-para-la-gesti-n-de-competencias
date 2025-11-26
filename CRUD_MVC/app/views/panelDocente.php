<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="utf-8"/>
    <meta content="width=device-width, initial-scale=1.0" name="viewport"/>
    <title>Dashboard Docente - UPEMOR</title>
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
    <style>
        .material-symbols-outlined {
            font-variation-settings: 'FILL' 0, 'wght' 400, 'GRAD' 0, 'opsz' 24;
        }
    </style>
</head>
<body class="font-display bg-slate-50 text-slate-800">
<?php
// Obtener estadísticas reales de la base de datos
$totalCompetencias = 0;
$asignacionesPendientes = 0;
$evidenciasEntregadas = 0;
$evaluacionesCompletadas = 0;
$misEvaluaciones = 0;

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
    
    // Obtener ID del docente
    $idUsuario = $_SESSION['usuario_id'];
    $sqlDocente = "SELECT idDocentes FROM docentes WHERE idUsuarios = ?";
    $stmtDoc = $connection->prepare($sqlDocente);
    $stmtDoc->bind_param("i", $idUsuario);
    $stmtDoc->execute();
    $resultDoc = $stmtDoc->get_result();
    $docente = $resultDoc->fetch_assoc();
    $idDocente = $docente['idDocentes'] ?? 0;
    
    // Total de Competencias
    $result = $connection->query("SELECT COUNT(*) as total FROM competencias");
    if ($result) {
        $row = $result->fetch_assoc();
        $totalCompetencias = (int)$row['total'];
    }
    
    // Asignaciones Pendientes (sin entregar)
    $result = $connection->query("SELECT COUNT(*) as total FROM asignaciones_evidencias WHERE Estado = 'Pendiente'");
    if ($result) {
        $row = $result->fetch_assoc();
        $asignacionesPendientes = (int)$row['total'];
    }
    
    // Evidencias Entregadas (esperando evaluación)
    $result = $connection->query("SELECT COUNT(*) as total FROM asignaciones_evidencias WHERE Estado = 'Entregada'");
    if ($result) {
        $row = $result->fetch_assoc();
        $evidenciasEntregadas = (int)$row['total'];
    }
    
    // Evaluaciones Completadas
    $result = $connection->query("SELECT COUNT(*) as total FROM asignaciones_evidencias WHERE Estado = 'Evaluada'");
    if ($result) {
        $row = $result->fetch_assoc();
        $evaluacionesCompletadas = (int)$row['total'];
    }
    
    // Mis Evaluaciones realizadas
    if($idDocente > 0) {
        $sqlMisEval = "SELECT COUNT(*) as total FROM evaluaciones WHERE idDocentes = ?";
        $stmtEval = $connection->prepare($sqlMisEval);
        $stmtEval->bind_param("i", $idDocente);
        $stmtEval->execute();
        $resultEval = $stmtEval->get_result();
        $rowEval = $resultEval->fetch_assoc();
        $misEvaluaciones = (int)$rowEval['total'];
    }
    
} catch (Exception $e) {
    $totalCompetencias = 0;
    $asignacionesPendientes = 0;
    $evidenciasEntregadas = 0;
    $evaluacionesCompletadas = 0;
    $misEvaluaciones = 0;
    
    echo "<script>console.error('Error en dashboard: " . addslashes($e->getMessage()) . "');</script>";
}
?>
    <div class="flex h-screen">
        <!-- SideNavBar -->
        <aside class="flex w-64 flex-col border-r border-slate-200 bg-white shadow-lg">
            <div class="flex h-full flex-col justify-between p-4">
                <div class="flex flex-col gap-4">
                    <!-- Logo -->
                    <div class="flex items-center gap-3 px-3">
                        <div class="flex h-10 w-10 items-center justify-center rounded-full bg-gradient-to-br from-upemor-purple to-purple-900 text-white">
                            <span class="material-symbols-outlined text-2xl">school</span>
                        </div>
                        <div class="flex flex-col">
                            <h1 class="text-base font-bold text-upemor-purple">Portal UPEMOR</h1>
                            <p class="text-sm text-slate-600">Panel Docente</p>
                        </div>
                    </div>
                    
                    <!-- Navigation -->
                    <nav class="mt-4 flex flex-col gap-1">
                        <a class="flex items-center gap-3 rounded-lg bg-upemor-orange/10 border-l-4 border-upemor-orange px-3 py-2" href="index.php?controller=auth&action=panelDocente">
                            <span class="material-symbols-outlined text-upemor-purple">space_dashboard</span>
                            <p class="text-sm font-bold text-upemor-purple">Dashboard</p>
                        </a>
                        
                        <a class="group flex items-center gap-3 rounded-lg px-3 py-2 hover:bg-upemor-orange/10 transition" href="index.php?controller=asignacion&action=listar">
                            <span class="material-symbols-outlined text-slate-600 group-hover:text-upemor-purple">assignment</span>
                            <p class="text-sm font-medium text-slate-700 group-hover:text-upemor-purple">Gestión de Evidencias</p>
                        </a>
                        
                        <a class="group flex items-center gap-3 rounded-lg px-3 py-2 hover:bg-upemor-orange/10 transition" href="index.php?controller=competencia&action=consult">
                            <span class="material-symbols-outlined text-slate-600 group-hover:text-upemor-purple">edit_document</span>
                            <p class="text-sm font-medium text-slate-700 group-hover:text-upemor-purple">Catálogo de Competencias</p>
                        </a>
                        
                        <a class="group flex items-center gap-3 rounded-lg px-3 py-2 hover:bg-upemor-orange/10 transition" href="index.php?controller=evaluacion&action=consult">
                            <span class="material-symbols-outlined text-slate-600 group-hover:text-upemor-purple">checklist</span>
                            <p class="text-sm font-medium text-slate-700 group-hover:text-upemor-purple">Historial de Evaluaciones</p>
                        </a>
                        
                        <a class="group flex items-center gap-3 rounded-lg px-3 py-2 hover:bg-upemor-orange/10 transition" href="index.php?controller=consulta&action=individual">
                            <span class="material-symbols-outlined text-slate-600 group-hover:text-upemor-purple">person_search</span>
                            <p class="text-sm font-medium text-slate-700 group-hover:text-upemor-purple">Consultas Individuales</p>
                        </a>
                        
                        <!-- Reports Section -->
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
                                <a class="group flex items-center gap-3 rounded-lg px-3 py-2 hover:bg-upemor-orange/10 transition" href="index.php?controller=reporte&action=historico">
                                    <span class="material-symbols-outlined text-slate-600 group-hover:text-upemor-purple">history</span>
                                    <p class="text-sm font-medium text-slate-700 group-hover:text-upemor-purple">Históricos</p>
                                </a>
                            </div>
                        </div>
                    </nav>
                </div>
                
                <!-- User Profile & Logout -->
                <div class="flex flex-col gap-2 border-t border-slate-200 pt-4">
                    <a class="group flex items-center gap-3 rounded-lg px-3 py-2 hover:bg-upemor-orange/10 transition" href="index.php?controller=docente&action=miPerfil">
                        <div class="h-8 w-8 rounded-full bg-upemor-purple flex items-center justify-center text-white font-bold">
                            <?php 
                                $nombre = isset($_SESSION['usuario_nombre']) ? $_SESSION['usuario_nombre'] : 'Usuario';
                                $iniciales = strtoupper(substr($nombre, 0, 2));
                                echo $iniciales;
                            ?>
                        </div>
                        <p class="text-sm font-medium text-slate-700 group-hover:text-upemor-purple">
                            <?php echo htmlspecialchars($nombre); ?>
                        </p>
                    </a>
                    <a class="group flex w-full items-center justify-start gap-3 rounded-lg px-3 py-2 text-left text-sm font-medium text-slate-600 hover:bg-red-50 hover:text-red-600 transition" href="index.php?controller=auth&action=logout">
                        <span class="material-symbols-outlined">logout</span>
                        <span class="truncate">Cerrar Sesión</span>
                    </a>
                </div>
            </div>
        </aside>
        
        <!-- Main Content -->
        <main class="w-full flex-1 overflow-y-auto bg-gradient-to-br from-slate-50 to-slate-100">
            <div class="p-6 lg:p-8">
                <!-- Page Heading -->
                <div class="flex flex-wrap items-center justify-between gap-4">
                    <div class="flex flex-col">
                        <h1 class="text-3xl font-bold text-upemor-purple">Dashboard Docente</h1>
                        <p class="text-slate-600">Bienvenido, <?php echo isset($_SESSION['usuario_nombre']) ? htmlspecialchars($_SESSION['usuario_nombre']) : 'Usuario'; ?></p>
                    </div>
                    <a href="index.php?controller=asignacion&action=listar" class="flex h-10 cursor-pointer items-center justify-center gap-2 rounded-lg bg-gradient-to-r from-upemor-orange to-upemor-red hover:from-upemor-red hover:to-upemor-orange px-4 text-sm font-bold text-white shadow-md transition">
                        <span class="material-symbols-outlined">add_circle</span>
                        <span class="truncate">Asignar Evidencia</span>
                    </a>
                </div>
                
                <!-- Stats Cards -->
                <div class="mt-6 grid grid-cols-1 gap-6 sm:grid-cols-2 lg:grid-cols-4">
                    <!-- Total Competencias -->
                    <div class="flex flex-col gap-2 rounded-2xl border-t-4 border-upemor-purple bg-white p-5 shadow-lg">
                        <div class="flex items-center justify-between">
                            <p class="text-sm font-medium text-slate-600">Total Competencias</p>
                            <span class="material-symbols-outlined text-upemor-purple">school</span>
                        </div>
                        <p class="text-3xl font-bold text-upemor-purple"><?php echo number_format($totalCompetencias); ?></p>
                        <p class="text-sm font-medium text-slate-600">Registradas en el catálogo</p>
                    </div>
                    
                    <!-- Asignaciones Pendientes -->
                    <div class="flex flex-col gap-2 rounded-2xl border-t-4 border-yellow-500 bg-white p-5 shadow-lg">
                        <div class="flex items-center justify-between">
                            <p class="text-sm font-medium text-slate-600">Pendientes de Entrega</p>
                            <span class="material-symbols-outlined text-yellow-600">pending_actions</span>
                        </div>
                        <p class="text-3xl font-bold text-upemor-purple"><?php echo number_format($asignacionesPendientes); ?></p>
                        <p class="text-sm font-medium text-yellow-600">Esperando evidencia</p>
                    </div>
                    
                    <!-- Evidencias por Revisar -->
                    <div class="flex flex-col gap-2 rounded-2xl border-t-4 border-blue-500 bg-white p-5 shadow-lg">
                        <div class="flex items-center justify-between">
                            <p class="text-sm font-medium text-slate-600">Por Revisar</p>
                            <span class="material-symbols-outlined text-blue-600">rate_review</span>
                        </div>
                        <p class="text-3xl font-bold text-upemor-purple"><?php echo number_format($evidenciasEntregadas); ?></p>
                        <p class="text-sm font-medium text-blue-600">Evidencias entregadas</p>
                    </div>
                    
                    <!-- Mis Evaluaciones -->
                    <div class="flex flex-col gap-2 rounded-2xl border-t-4 border-green-500 bg-white p-5 shadow-lg">
                        <div class="flex items-center justify-between">
                            <p class="text-sm font-medium text-slate-600">Mis Evaluaciones</p>
                            <span class="material-symbols-outlined text-green-600">check_circle</span>
                        </div>
                        <p class="text-3xl font-bold text-upemor-purple"><?php echo number_format($misEvaluaciones); ?></p>
                        <p class="text-sm font-medium text-success">Realizadas por mí</p>
                    </div>
                </div>
                
                <!-- Section Header -->
                <h2 class="text-2xl font-bold text-upemor-purple pb-3 pt-8">Acceso Rápido</h2>
                
                <!-- Quick Access Cards -->
                <div class="grid grid-cols-1 gap-6 md:grid-cols-2 lg:grid-cols-3">
                    <!-- Gestión de Evidencias -->
                    <div class="group flex flex-col gap-4 rounded-2xl border-t-4 border-upemor-orange bg-white p-5 shadow-lg transition-all hover:shadow-xl hover:scale-105">
                        <div class="flex h-12 w-12 items-center justify-center rounded-lg bg-gradient-to-br from-upemor-orange/10 to-orange-100">
                            <span class="material-symbols-outlined text-3xl text-upemor-orange">assignment</span>
                        </div>
                        <div>
                            <p class="text-base font-semibold text-upemor-purple">Gestión de Evidencias</p>
                            <p class="mt-1 text-sm text-slate-600">Asigna, revisa y evalúa evidencias de competencias.</p>
                            <a class="mt-3 inline-flex items-center gap-1 text-sm font-bold text-upemor-orange hover:text-upemor-red transition" href="index.php?controller=asignacion&action=listar">
                                <span>Ir a Gestión</span>
                                <span class="material-symbols-outlined !text-[18px]">arrow_forward</span>
                            </a>
                        </div>
                    </div>
                    
                    <!-- Catálogo de Competencias -->
                    <div class="group flex flex-col gap-4 rounded-2xl border-t-4 border-upemor-purple bg-white p-5 shadow-lg transition-all hover:shadow-xl hover:scale-105">
                        <div class="flex h-12 w-12 items-center justify-center rounded-lg bg-gradient-to-br from-upemor-purple/10 to-purple-100">
                            <span class="material-symbols-outlined text-3xl text-upemor-purple">edit_document</span>
                        </div>
                        <div>
                            <p class="text-base font-semibold text-upemor-purple">Catálogo de Competencias</p>
                            <p class="mt-1 text-sm text-slate-600">Consulta las competencias del programa educativo.</p>
                            <a class="mt-3 inline-flex items-center gap-1 text-sm font-bold text-upemor-orange hover:text-upemor-red transition" href="index.php?controller=competencia&action=consult">
                                <span>Ver Catálogo</span>
                                <span class="material-symbols-outlined !text-[18px]">arrow_forward</span>
                            </a>
                        </div>
                    </div>
                    
                    <!-- Consultas Individuales -->
                    <div class="group flex flex-col gap-4 rounded-2xl border-t-4 border-blue-500 bg-white p-5 shadow-lg transition-all hover:shadow-xl hover:scale-105">
                        <div class="flex h-12 w-12 items-center justify-center rounded-lg bg-gradient-to-br from-blue-50 to-blue-100">
                            <span class="material-symbols-outlined text-3xl text-blue-600">person_search</span>
                        </div>
                        <div>
                            <p class="text-base font-semibold text-upemor-purple">Consultas Individuales</p>
                            <p class="mt-1 text-sm text-slate-600">Consulta el progreso individual de estudiantes.</p>
                            <a class="mt-3 inline-flex items-center gap-1 text-sm font-bold text-upemor-orange hover:text-upemor-red transition" href="index.php?controller=consulta&action=individual">
                                <span>Consultar</span>
                                <span class="material-symbols-outlined !text-[18px]">arrow_forward</span>
                            </a>
                        </div>
                    </div>
                    
                    <!-- Reportes -->
                    <div class="group flex flex-col gap-4 rounded-2xl border-t-4 border-green-500 bg-white p-5 shadow-lg transition-all hover:shadow-xl hover:scale-105">
                        <div class="flex h-12 w-12 items-center justify-center rounded-lg bg-gradient-to-br from-green-50 to-green-100">
                            <span class="material-symbols-outlined text-3xl text-green-600">bar_chart</span>
                        </div>
                        <div>
                            <p class="text-base font-semibold text-upemor-purple">Reportes</p>
                            <p class="mt-1 text-sm text-slate-600">Genera reportes detallados de desempeño.</p>
                           <a class="mt-3 inline-flex items-center gap-1 text-sm font-bold text-upemor-orange hover:text-upemor-red transition" href="index.php?controller=reporte&action=grupal">
                                <span>Generar Reporte</span>
                                <span class="material-symbols-outlined !text-[18px]">arrow_forward</span>
                            </a>
                        </div>
                    </div>
                    
                    <!-- Historial de Evaluaciones -->
                    <div class="group flex flex-col gap-4 rounded-2xl border-t-4 border-indigo-500 bg-white p-5 shadow-lg transition-all hover:shadow-xl hover:scale-105">
                        <div class="flex h-12 w-12 items-center justify-center rounded-lg bg-gradient-to-br from-indigo-50 to-indigo-100">
                            <span class="material-symbols-outlined text-3xl text-indigo-600">checklist</span>
                        </div>
                        <div>
                            <p class="text-base font-semibold text-upemor-purple">Historial de Evaluaciones</p>
                            <p class="mt-1 text-sm text-slate-600">Revisa todas las evaluaciones realizadas.</p>
                            <a class="mt-3 inline-flex items-center gap-1 text-sm font-bold text-upemor-orange hover:text-upemor-red transition" href="index.php?controller=evaluacion&action=consult">
                                <span>Ver Historial</span>
                                <span class="material-symbols-outlined !text-[18px]">arrow_forward</span>
                            </a>
                        </div>
                    </div>
                    
                    <!-- Mi Perfil -->
                    <div class="group flex flex-col gap-4 rounded-2xl border-t-4 border-purple-500 bg-white p-5 shadow-lg transition-all hover:shadow-xl hover:scale-105">
                        <div class="flex h-12 w-12 items-center justify-center rounded-lg bg-gradient-to-br from-purple-50 to-purple-100">
                            <span class="material-symbols-outlined text-3xl text-purple-600">person</span>
                        </div>
                        <div>
                            <p class="text-base font-semibold text-upemor-purple">Mi Perfil</p>
                            <p class="mt-1 text-sm text-slate-600">Administra tu información personal y académica.</p>
                            <a class="mt-3 inline-flex items-center gap-1 text-sm font-bold text-upemor-orange hover:text-upemor-red transition" href="index.php?controller=docente&action=miPerfil">
                                <span>Ver Perfil</span>
                                <span class="material-symbols-outlined !text-[18px]">arrow_forward</span>
                            </a>
                        </div>
                    </div>
                </div>
            </div>
        </main>
    </div>
</body>
</html>