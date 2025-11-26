<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="utf-8"/>
    <meta content="width=device-width, initial-scale=1.0" name="viewport"/>
    <title>Dashboard Administrativo - UPEMOR</title>
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
    
} catch (Exception $e) {
    $totalCompetencias = 0;
    $asignacionesPendientes = 0;
    $evidenciasEntregadas = 0;
    $evaluacionesCompletadas = 0;
    
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
                            <span class="material-symbols-outlined text-2xl">insights</span>
                        </div>
                        <div class="flex flex-col">
                            <h1 class="text-base font-bold text-upemor-purple">Portal UPEMOR</h1>
                            <p class="text-sm text-slate-600">Panel Administrativo</p>
                        </div>
                    </div>
                    
                    <!-- Navigation -->
                    <nav class="mt-4 flex flex-col gap-1">
                        <a class="flex items-center gap-3 rounded-lg bg-upemor-orange/10 border-l-4 border-upemor-orange px-3 py-2" href="index.php?controller=auth&action=panelAdministrativo">
                            <span class="material-symbols-outlined text-upemor-purple">space_dashboard</span>
                            <p class="text-sm font-bold text-upemor-purple">Dashboard</p>
                        </a>
                        
                        <button onclick="openRegistroModal()" class="group flex items-center gap-3 rounded-lg px-3 py-2 hover:bg-upemor-orange/10 transition w-full text-left">
                            <span class="material-symbols-outlined text-slate-600 group-hover:text-upemor-purple">person_add</span>
                            <p class="text-sm font-medium text-slate-700 group-hover:text-upemor-purple">Registrar Usuario</p>
                        </button>
                        
                        <a class="group flex items-center gap-3 rounded-lg px-3 py-2 hover:bg-upemor-orange/10 transition" href="index.php?controller=asignacion&action=listar">
                            <span class="material-symbols-outlined text-slate-600 group-hover:text-upemor-purple">assignment</span>
                            <p class="text-sm font-medium text-slate-700 group-hover:text-upemor-purple">Gestión de Evidencias</p>
                        </a>

                        <a class="group flex items-center gap-3 rounded-lg px-3 py-2 hover:bg-upemor-orange/10 transition" href="index.php?controller=docente&action=gestionarDocentes">
                            <span class="material-symbols-outlined text-slate-600 group-hover:text-upemor-purple">history_edu</span>
                            <p class="text-sm font-medium text-slate-700 group-hover:text-upemor-purple">Gestión de Docentes</p>
                        </a>
                        
                        <a class="group flex items-center gap-3 rounded-lg px-3 py-2 hover:bg-upemor-orange/10 transition" href="index.php?controller=estudiante&action=gestionarEstudiantes">
                            <span class="material-symbols-outlined text-slate-600 group-hover:text-upemor-purple">groups</span>
                            <p class="text-sm font-medium text-slate-700 group-hover:text-upemor-purple">Gestión de Estudiantes</p>
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
                
                <!-- User Profile & Logout - SIN ENLACE A MI PERFIL -->
                <div class="flex flex-col gap-2 border-t border-slate-200 pt-4">
                    <!-- Perfil sin enlace (solo visual) -->
                    <div class="flex items-center gap-3 rounded-lg px-3 py-2">
                        <div class="h-8 w-8 rounded-full bg-upemor-purple flex items-center justify-center text-white font-bold">
                            <?php 
                                $nombre = isset($_SESSION['usuario_nombre']) ? $_SESSION['usuario_nombre'] : 'Usuario';
                                $iniciales = strtoupper(substr($nombre, 0, 2));
                                echo $iniciales;
                            ?>
                        </div>
                        <p class="text-sm font-medium text-slate-700">
                            <?php echo htmlspecialchars($nombre); ?>
                        </p>
                    </div>
                    
                    <!-- Botón de Cerrar Sesión -->
                    <a class="group flex w-full items-center justify-start gap-3 rounded-lg px-3 py-2 text-left text-sm font-medium text-slate-600 hover:bg-red-50 hover:text-red-600 transition" href="index.php?controller=auth&action=logout">
                        <span class="material-symbols-outlined">logout</span>
                        <span class="truncate">Cerrar Sesión</span>
                    </a>
                </div>
        </aside>
        
        <!-- Main Content -->
        <main class="w-full flex-1 overflow-y-auto bg-gradient-to-br from-slate-50 to-slate-100">
            <div class="p-6 lg:p-8">
                <!-- Page Heading -->
                <div class="flex flex-wrap items-center justify-between gap-4">
                    <div class="flex flex-col">
                        <h1 class="text-3xl font-bold text-upemor-purple">Dashboard</h1>
                        <p class="text-slate-600">Bienvenido, <?php echo isset($_SESSION['usuario_nombre']) ? htmlspecialchars($_SESSION['usuario_nombre']) : 'Usuario'; ?></p>
                    </div>
                    <a href="index.php?controller=asignacion&action=listar" class="flex h-10 cursor-pointer items-center justify-center gap-2 rounded-lg bg-gradient-to-r from-upemor-orange to-upemor-red hover:from-upemor-red hover:to-upemor-orange px-4 text-sm font-bold text-white shadow-md transition">
                        <span class="material-symbols-outlined">add_circle</span>
                        <span class="truncate">Asignar Evidencia</span>
                    </a>
                </div>
                
                <!-- Stats Cards (4 cards now) -->
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
                        <p class="text-sm font-medium text-yellow-600">Esperando que suban evidencia</p>
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
                    
                    <!-- Evaluaciones Completadas -->
                    <div class="flex flex-col gap-2 rounded-2xl border-t-4 border-green-500 bg-white p-5 shadow-lg">
                        <div class="flex items-center justify-between">
                            <p class="text-sm font-medium text-slate-600">Evaluadas</p>
                            <span class="material-symbols-outlined text-green-600">check_circle</span>
                        </div>
                        <p class="text-3xl font-bold text-upemor-purple"><?php echo number_format($evaluacionesCompletadas); ?></p>
                        <p class="text-sm font-medium text-success">Completadas exitosamente</p>
                    </div>
                </div>
                
                <!-- Section Header -->
                <h2 class="text-2xl font-bold text-upemor-purple pb-3 pt-8">Acceso Rápido</h2>
                
                <!-- Quick Access Cards -->
                <div class="grid grid-cols-1 gap-6 md:grid-cols-2 lg:grid-cols-3">

                    <div class="group flex flex-col gap-4 rounded-2xl border-t-4 border-gray-600 bg-white p-5 shadow-lg transition-all hover:shadow-xl hover:scale-105">
                        <div class="flex h-12 w-12 items-center justify-center rounded-lg bg-gradient-to-br from-gray-100 to-gray-200">
                            <span class="material-symbols-outlined text-3xl text-gray-600">cloud_download</span>
                        </div>
                        <div>
                            <p class="text-base font-semibold text-upemor-purple">Respaldo de BD</p>
                            <p class="mt-1 text-sm text-slate-600">Descarga una copia de seguridad completa.</p>
                            <a class="mt-3 inline-flex items-center gap-1 text-sm font-bold text-upemor-orange hover:text-upemor-red transition" href="index.php?controller=user&action=backup">
                                <span>Descargar SQL</span>
                                <span class="material-symbols-outlined !text-[18px]">download</span>
                            </a>
                        </div>
                    </div>

                    <div class="group flex flex-col gap-4 rounded-2xl border-t-4 border-red-500 bg-white p-5 shadow-lg transition-all hover:shadow-xl hover:scale-105 cursor-pointer" onclick="openRestoreModal()">
                        <div class="flex h-12 w-12 items-center justify-center rounded-lg bg-gradient-to-br from-red-50 to-red-100">
                            <span class="material-symbols-outlined text-3xl text-red-600">cloud_upload</span>
                        </div>
                        <div>
                            <p class="text-base font-semibold text-upemor-purple">Restaurar Sistema</p>
                            <p class="mt-1 text-sm text-slate-600">Recupera datos desde un archivo de respaldo.</p>
                            <div class="mt-3 inline-flex items-center gap-1 text-sm font-bold text-upemor-orange group-hover:text-upemor-red transition">
                                <span>Subir Respaldo</span>
                                <span class="material-symbols-outlined !text-[18px]">upload</span>
                            </div>
                        </div>
                    </div>
                </div>
                    <div id="restoreModal" class="hidden fixed inset-0 bg-black bg-opacity-50 z-50 flex items-center justify-center p-4">
                        <div class="bg-white rounded-2xl shadow-2xl max-w-md w-full p-6 relative animate-fadeIn">
                            <button onclick="closeRestoreModal()" class="absolute top-4 right-4 text-slate-400 hover:text-upemor-red transition">
                                <span class="material-symbols-outlined text-2xl">close</span>
                            </button>

                            <div class="text-center mb-6">
                                <div class="bg-red-50 w-16 h-16 rounded-full flex items-center justify-center mx-auto mb-4">
                                    <span class="material-symbols-outlined text-3xl text-red-600">warning</span>
                                </div>
                                <h3 class="text-xl font-bold text-upemor-purple">Restaurar Base de Datos</h3>
                                <p class="text-sm text-slate-600 mt-2">
                                    Esta acción reemplazará todos los datos actuales con la información del archivo de respaldo.
                                </p>
                            </div>

                            <form action="index.php?controller=user&action=restore" method="POST" enctype="multipart/form-data">
                                <div class="mb-6">
                                    <label class="block text-sm font-medium text-slate-700 mb-2">Archivo SQL de Respaldo</label>
                                    <input type="file" name="archivo_sql" required accept=".sql" class="block w-full text-sm text-slate-500 file:mr-4 file:py-2 file:px-4 file:rounded-full file:border-0 file:text-sm file:font-semibold file:bg-upemor-purple file:text-white hover:file:bg-purple-700 transition"/>
                                </div>

                                <div class="flex gap-3">
                                    <button type="button" onclick="closeRestoreModal()" class="flex-1 py-2 px-4 border border-slate-300 rounded-lg text-slate-700 font-medium hover:bg-slate-50 transition">
                                        Cancelar
                                    </button>
                                    <button type="submit" name="restaurar" class="flex-1 py-2 px-4 bg-red-600 text-white rounded-lg font-medium hover:bg-red-700 transition shadow-lg" onclick="return confirm('¿Estás 100% seguro? Se perderán los datos actuales no guardados.')">
                                        Restaurar Ahora
                                    </button>
                                </div>
                            </form>
                        </div>
                    </div>
                     <script>
                        // Scripts existentes...

                        // Funciones para el Modal de Restauración
                        function openRestoreModal() {
                            document.getElementById('restoreModal').classList.remove('hidden');
                        }

                        function closeRestoreModal() {
                            document.getElementById('restoreModal').classList.add('hidden');
                        }
                    </script>
                    
                    <!-- Registrar Usuario -->
                    <div class="group flex flex-col gap-4 rounded-2xl border-t-4 border-blue-500 bg-white p-5 shadow-lg transition-all hover:shadow-xl hover:scale-105 cursor-pointer" onclick="openRegistroModal()">
                        <div class="flex h-12 w-12 items-center justify-center rounded-lg bg-gradient-to-br from-blue-50 to-blue-100">
                            <span class="material-symbols-outlined text-3xl text-blue-600">person_add</span>
                        </div>
                        <div>
                            <p class="text-base font-semibold text-upemor-purple">Registrar Usuario</p>
                            <p class="mt-1 text-sm text-slate-600">Registra nuevos docentes o estudiantes en el sistema.</p>
                            <div class="mt-3 inline-flex items-center gap-1 text-sm font-bold text-upemor-orange group-hover:text-upemor-red transition">
                                <span>Registrar</span>
                                <span class="material-symbols-outlined !text-[18px]">arrow_forward</span>
                            </div>
                        </div>
                    </div>
                    
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
                            <p class="mt-1 text-sm text-slate-600">Administra las competencias del programa educativo.</p>
                            <a class="mt-3 inline-flex items-center gap-1 text-sm font-bold text-upemor-orange hover:text-upemor-red transition" href="index.php?controller=competencia&action=consult">
                                <span>Ver Catálogo</span>
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
                </div>
            </div>
        </main>
    </div>

    <!-- Modal de Registro -->
    <div id="registroModal" class="hidden fixed inset-0 bg-black bg-opacity-50 z-50 flex items-center justify-center p-4">
        <div class="bg-gradient-to-br from-upemor-purple via-upemor-purple to-purple-900 rounded-3xl max-w-4xl w-full p-8 relative animate-fadeIn">
            <!-- Botón cerrar -->
            <button onclick="closeRegistroModal()" class="absolute top-4 right-4 text-white hover:text-upemor-orange transition">
                <span class="material-symbols-outlined text-3xl">close</span>
            </button>

            <div class="text-center mb-8">
                <div class="flex justify-center mb-6">
                    <img src="https://gruposat.com.mx/img/clientes/UPEMOR.png" alt="Upemor Logo" class="h-24 w-24 object-contain drop-shadow-2xl"/>
                </div>
                <h1 class="text-3xl font-bold text-white mb-3">Universidad Politécnica del Estado de Morelos</h1>
                <p class="text-lg text-upemor-orange font-semibold mb-6">Ciencia y Tecnología para el Bien Común</p>
                <h2 class="text-2xl font-bold text-white mb-2">Crear Cuenta</h2>
                <p class="text-slate-200">Selecciona cómo deseas registrarte</p>
            </div>

            <div class="grid md:grid-cols-3 gap-6">
                <!-- Tarjeta Docente -->
                <button onclick="goToRegistroDocente()" class="group bg-white rounded-2xl p-6 shadow-2xl hover:shadow-upemor-orange/50 transition-all duration-300 transform hover:scale-105 text-left">
                    <div class="flex items-center justify-center w-14 h-14 bg-gradient-to-br from-upemor-purple to-purple-600 rounded-xl mb-4 group-hover:from-upemor-orange group-hover:to-upemor-red transition-all duration-300">
                        <span class="material-symbols-outlined text-white text-3xl">menu_book</span>
                    </div>
                    <h3 class="text-xl font-bold text-upemor-purple mb-2">Registrar Docente</h3>
                    <p class="text-slate-600 mb-4">Registra tus datos como docente asesor para evaluar competencias</p>
                    <div class="flex items-center text-upemor-orange font-semibold group-hover:translate-x-2 transition-transform">
                        <span class="material-symbols-outlined mr-2">school</span>
                        <span>Continuar como Docente</span>
                        <span class="material-symbols-outlined ml-2">arrow_forward</span>
                    </div>
                </button>

                <!-- Tarjeta Estudiante -->
                <button onclick="goToRegistroEstudiante()" class="group bg-white rounded-2xl p-6 shadow-2xl hover:shadow-upemor-orange/50 transition-all duration-300 transform hover:scale-105 text-left">
                    <div class="flex items-center justify-center w-14 h-14 bg-gradient-to-br from-upemor-purple to-purple-600 rounded-xl mb-4 group-hover:from-upemor-orange group-hover:to-upemor-red transition-all duration-300">
                        <span class="material-symbols-outlined text-white text-3xl">school</span>
                    </div>
                    <h3 class="text-xl font-bold text-upemor-purple mb-2">Registrar Estudiante</h3>
                    <p class="text-slate-600 mb-4">Registra tus datos como estudiante para consultar tus evaluaciones</p>
                    <div class="flex items-center text-upemor-orange font-semibold group-hover:translate-x-2 transition-transform">
                        <span class="material-symbols-outlined mr-2">person</span>
                        <span>Continuar como Estudiante</span>
                        <span class="material-symbols-outlined ml-2">arrow_forward</span>
                    </div>
                </button>

                <!-- Tarjeta Administrador -->
                <button onclick="goToRegistroAdministrador()" class="group bg-white rounded-2xl p-6 shadow-2xl hover:shadow-upemor-orange/50 transition-all duration-300 transform hover:scale-105 text-left">
                    <div class="flex items-center justify-center w-14 h-14 bg-gradient-to-br from-upemor-purple to-purple-600 rounded-xl mb-4 group-hover:from-upemor-orange group-hover:to-upemor-red transition-all duration-300">
                        <span class="material-symbols-outlined text-white text-3xl">admin_panel_settings</span>
                    </div>
                    <h3 class="text-xl font-bold text-upemor-purple mb-2">Registrar Administrador</h3>
                    <p class="text-slate-600 mb-4">Registra un nuevo administrador con acceso completo al sistema</p>
                    <div class="flex items-center text-upemor-orange font-semibold group-hover:translate-x-2 transition-transform">
                        <span class="material-symbols-outlined mr-2">security</span>
                        <span>Continuar como Admin</span>
                        <span class="material-symbols-outlined ml-2">arrow_forward</span>
                    </div>
                </button>
            </div>
        </div>
    </div>

    <script>
        function openRegistroModal() {
            document.getElementById('registroModal').classList.remove('hidden');
        }

        function closeRegistroModal() {
            document.getElementById('registroModal').classList.add('hidden');
        }

        function goToRegistroDocente() {
            window.location.href = 'index.php?controller=auth&action=registroDirecto&tipo=Docente';
        }

        function goToRegistroEstudiante() {
            window.location.href = 'index.php?controller=auth&action=registroDirecto&tipo=Estudiante';
        }

        function goToRegistroAdministrador() {
            window.location.href = 'index.php?controller=auth&action=registroDirecto&tipo=Administrador';
        }

        // Cerrar modal al hacer clic fuera de él
        document.getElementById('registroModal').addEventListener('click', function(e) {
            if (e.target === this) {
                closeRegistroModal();
            }
        });

        // Cerrar modal con tecla ESC
        document.addEventListener('keydown', function(e) {
            if (e.key === 'Escape') {
                closeRegistroModal();
            }
        });
    </script>
</body>
</html>