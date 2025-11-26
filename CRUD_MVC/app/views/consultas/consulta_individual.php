<?php
if (session_status() === PHP_SESSION_NONE) {
    session_start();
}
?>
<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="utf-8"/>
    <meta content="width=device-width, initial-scale=1.0" name="viewport"/>
    <title>Consulta Individual - UPEMOR</title>
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
                        "upemor-red": "#ED1C24"
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
    <div class="flex h-screen">
        <!-- SideNavBar (mismo que en evaluaciones) -->
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
                        <a class="flex items-center gap-3 rounded-lg bg-upemor-orange/10 border-l-4 border-upemor-orange px-3 py-2" href="index.php?controller=consulta&action=individual">
                            <span class="material-symbols-outlined text-upemor-purple">person_search</span>
                            <p class="text-sm font-bold text-upemor-purple">Consultas Individuales</p>
                        </a>
                        <a class="group flex items-center gap-3 rounded-lg px-3 py-2 hover:bg-upemor-orange/10 transition" href="index.php?controller=consulta&action=competencia">
                            <span class="material-symbols-outlined text-slate-600 group-hover:text-upemor-purple">manage_search</span>
                            <p class="text-sm font-medium text-slate-700 group-hover:text-upemor-purple">Consultas por Competencia</p>
                        </a>
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
                    <a class="group flex w-full items-center justify-start gap-3 rounded-lg px-3 py-2 text-left text-sm font-medium text-slate-600 hover:bg-red-50 hover:text-red-600 transition" 
                        href="index.php?controller=auth&action=logout"
                        onclick="return confirm('¿Estás seguro de que deseas cerrar sesión?');">
                        <span class="material-symbols-outlined">logout</span>
                        <span class="truncate">Cerrar Sesión</span>
                    </a>
                </div>
            </div>
        </aside>
        
        <!-- Main Content -->
        <main class="w-full flex-1 overflow-y-auto bg-gradient-to-br from-slate-50 to-slate-100">
            <div class="p-6 lg:p-8">
                <!-- Header -->
                <div class="mb-8">
                    <h1 class="text-3xl font-bold text-upemor-purple">Consulta Individual</h1>
                    <p class="text-slate-600 mt-1">Historial de evaluaciones por estudiante</p>
                </div>

                <!-- Selector de Estudiante -->
                <div class="bg-white rounded-2xl shadow-lg p-6 mb-6">
                    <form method="GET" action="index.php" class="flex flex-col md:flex-row gap-4 items-end">
                        <input type="hidden" name="controller" value="consulta">
                        <input type="hidden" name="action" value="individual">
                        
                        <div class="flex-1">
                            <label for="idEstudiante" class="block text-base font-semibold text-upemor-purple mb-2">
                                Seleccionar Estudiante
                            </label>
                            <select 
                                id="idEstudiante"
                                name="idEstudiante" 
                                required
                                class="w-full px-4 py-3 border-2 border-slate-200 rounded-xl text-slate-900 focus:outline-none focus:ring-2 focus:ring-upemor-orange focus:border-upemor-orange transition"
                            >
                                <option value="">Seleccionar estudiante...</option>
                                <?php foreach($estudiantes as $est): ?>
                                <option value="<?php echo $est['idEstudiantes']; ?>"
                                    <?php echo (isset($_GET['idEstudiante']) && $_GET['idEstudiante'] == $est['idEstudiantes']) ? 'selected' : ''; ?>>
                                    <?php echo htmlspecialchars($est['Apellido'] . ', ' . $est['Nombre'] . ' - ' . $est['Matricula']); ?>
                                </option>
                                <?php endforeach; ?>
                            </select>
                        </div>
                        
                        <button 
                            type="submit"
                            class="px-6 py-3 bg-gradient-to-r from-upemor-orange to-upemor-red hover:from-upemor-red hover:to-upemor-orange text-white font-bold rounded-xl transition shadow-lg flex items-center justify-center gap-2">
                            <span class="material-symbols-outlined">search</span>
                            <span>Consultar</span>
                        </button>
                    </form>
                </div>

                <?php if($infoEstudiante): ?>
                <!-- Información del Estudiante -->
                <div class="grid grid-cols-1 lg:grid-cols-3 gap-6 mb-6">
                    <!-- Info Card -->
                    <div class="bg-white rounded-2xl shadow-lg p-6 border-t-4 border-upemor-purple">
                        <div class="flex items-center gap-4 mb-4">
                            <div class="h-16 w-16 rounded-full bg-gradient-to-br from-upemor-purple to-purple-900 flex items-center justify-center text-white text-2xl font-bold">
                                <?php echo strtoupper(substr($infoEstudiante['Nombre'], 0, 1) . substr($infoEstudiante['Apellido'], 0, 1)); ?>
                            </div>
                            <div>
                                <h3 class="text-xl font-bold text-upemor-purple">
                                    <?php echo htmlspecialchars($infoEstudiante['Nombre'] . ' ' . $infoEstudiante['Apellido']); ?>
                                </h3>
                                <p class="text-sm text-slate-600">Matrícula: <?php echo htmlspecialchars($infoEstudiante['Matricula']); ?></p>
                            </div>
                        </div>
                        <div class="pt-4 border-t border-slate-200">
                            <p class="text-sm text-slate-600">Email</p>
                            <p class="font-semibold text-upemor-purple"><?php echo htmlspecialchars($infoEstudiante['Email']); ?></p>
                        </div>
                    </div>

                    <!-- Estadísticas -->
                    <div class="lg:col-span-2 bg-white rounded-2xl shadow-lg p-6">
                        <h3 class="text-lg font-bold text-upemor-purple mb-4 flex items-center gap-2">
                            <span class="material-symbols-outlined">bar_chart</span>
                            Resumen de Evaluaciones
                        </h3>
                        <div class="grid grid-cols-2 md:grid-cols-3 gap-4">
                            <div class="bg-slate-50 rounded-xl p-4">
                                <p class="text-sm text-slate-600">Total Evaluaciones</p>
                                <p class="text-3xl font-bold text-upemor-purple"><?php echo $estadisticas['TotalEvaluaciones']; ?></p>
                            </div>
                            <div class="bg-green-50 rounded-xl p-4">
                                <p class="text-sm text-green-700">Excelente</p>
                                <p class="text-3xl font-bold text-green-600"><?php echo $estadisticas['Excelente']; ?></p>
                            </div>
                            <div class="bg-blue-50 rounded-xl p-4">
                                <p class="text-sm text-blue-700">Bueno</p>
                                <p class="text-3xl font-bold text-blue-600"><?php echo $estadisticas['Bueno']; ?></p>
                            </div>
                            <div class="bg-yellow-50 rounded-xl p-4">
                                <p class="text-sm text-yellow-700">Regular</p>
                                <p class="text-3xl font-bold text-yellow-600"><?php echo $estadisticas['Regular']; ?></p>
                            </div>
                            <div class="bg-red-50 rounded-xl p-4">
                                <p class="text-sm text-red-700">Deficiente</p>
                                <p class="text-3xl font-bold text-red-600"><?php echo $estadisticas['Deficiente']; ?></p>
                            </div>
                            <div class="bg-purple-50 rounded-xl p-4">
                                <p class="text-sm text-purple-700">Competencias</p>
                                <p class="text-3xl font-bold text-purple-600"><?php echo $estadisticas['CompetenciasEvaluadas']; ?></p>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Tabla de Evaluaciones -->
                <div class="bg-white rounded-2xl shadow-lg overflow-hidden">
                    <div class="bg-gradient-to-r from-upemor-purple to-purple-900 text-white p-6">
                        <div class="flex items-center gap-3">
                            <span class="material-symbols-outlined text-4xl">history_edu</span>
                            <div>
                                <h2 class="text-xl font-bold">Historial de Evaluaciones</h2>
                                <p class="text-sm text-purple-200"><?php echo count($evaluaciones); ?> evaluaciones registradas</p>
                            </div>
                        </div>
                    </div>

                    <?php if(count($evaluaciones) > 0): ?>
                    <div class="overflow-x-auto">
                        <table class="w-full">
                            <thead class="bg-slate-100 border-b-2 border-slate-200">
                                <tr>
                                    <th class="px-6 py-4 text-left text-sm font-bold text-upemor-purple uppercase">Fecha</th>
                                    <th class="px-6 py-4 text-left text-sm font-bold text-upemor-purple uppercase">Competencia</th>
                                    <th class="px-6 py-4 text-left text-sm font-bold text-upemor-purple uppercase">Tipo</th>
                                    <th class="px-6 py-4 text-left text-sm font-bold text-upemor-purple uppercase">Docente</th>
                                    <th class="px-6 py-4 text-left text-sm font-bold text-upemor-purple uppercase">Calificación</th>
                                    <th class="px-6 py-4 text-left text-sm font-bold text-upemor-purple uppercase">Observaciones</th>
                                </tr>
                            </thead>
                            <tbody class="divide-y divide-slate-200">
                                <?php foreach($evaluaciones as $eval): ?>
                                <tr class="hover:bg-slate-50 transition">
                                    <td class="px-6 py-4 text-sm font-medium text-slate-900">
                                        <?php echo date('d/m/Y', strtotime($eval['Fecha'])); ?>
                                    </td>
                                    <td class="px-6 py-4">
                                        <p class="text-sm font-semibold text-upemor-purple">
                                            <?php echo htmlspecialchars($eval['NombreCompetencia']); ?>
                                        </p>
                                        <p class="text-xs text-slate-500">
                                            <?php echo htmlspecialchars(substr($eval['DescripcionCompetencia'], 0, 60)) . '...'; ?>
                                        </p>
                                    </td>
                                    <td class="px-6 py-4">
                                        <span class="px-2 py-1 bg-blue-100 text-blue-800 rounded-lg text-xs font-medium">
                                            <?php echo htmlspecialchars($eval['TipoEvaluacion']); ?>
                                        </span>
                                    </td>
                                    <td class="px-6 py-4 text-sm text-slate-600">
                                        <?php echo htmlspecialchars($eval['NombreDocente']); ?>
                                    </td>
                                    <td class="px-6 py-4">
                                        <?php 
                                        $colorClass = match($eval['Calificacion']) {
                                            'Excelente' => 'bg-green-100 text-green-800',
                                            'Bueno' => 'bg-blue-100 text-blue-800',
                                            'Regular' => 'bg-yellow-100 text-yellow-800',
                                            'Deficiente' => 'bg-red-100 text-red-800',
                                            default => 'bg-gray-100 text-gray-800'
                                        };
                                        ?>
                                        <span class="px-2 py-1 <?php echo $colorClass; ?> rounded-lg text-xs font-medium">
                                            <?php echo htmlspecialchars($eval['Calificacion']); ?>
                                        </span>
                                    </td>
                                    <td class="px-6 py-4 text-sm text-slate-600 max-w-xs truncate">
                                        <?php echo htmlspecialchars($eval['Observaciones']); ?>
                                    </td>
                                </tr>
                                <?php endforeach; ?>
                            </tbody>
                        </table>
                    </div>
                    <?php else: ?>
                    <div class="p-12 text-center">
                        <span class="material-symbols-outlined text-6xl text-slate-300">description</span>
                        <p class="text-slate-500 font-medium mt-3">No hay evaluaciones registradas para este estudiante</p>
                    </div>
                    <?php endif; ?>
                </div>
                <?php endif; ?>

                <?php if(!$infoEstudiante && !isset($_GET['idEstudiante'])): ?>
                <div class="bg-white rounded-2xl shadow-lg p-12 text-center">
                    <span class="material-symbols-outlined text-6xl text-slate-300">person_search</span>
                    <p class="text-slate-500 font-medium mt-3">Selecciona un estudiante para ver su historial de evaluaciones</p>
                </div>
                <?php endif; ?>
            </div>
        </main>
    </div>
</body>
</html>