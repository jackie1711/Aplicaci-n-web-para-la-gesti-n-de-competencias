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
    <title>Consulta por Competencia - UPEMOR</title>
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
                        <a class="group flex items-center gap-3 rounded-lg px-3 py-2 hover:bg-upemor-orange/10 transition" href="index.php?controller=consulta&action=individual">
                            <span class="material-symbols-outlined text-slate-600 group-hover:text-upemor-purple">person_search</span>
                            <p class="text-sm font-medium text-slate-700 group-hover:text-upemor-purple">Consultas Individuales</p>
                        </a>
                        <a class="flex items-center gap-3 rounded-lg bg-upemor-orange/10 border-l-4 border-upemor-orange px-3 py-2" href="index.php?controller=consulta&action=competencia">
                            <span class="material-symbols-outlined text-upemor-purple">manage_search</span>
                            <p class="text-sm font-bold text-upemor-purple">Consultas por Competencia</p>
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
                    <h1 class="text-3xl font-bold text-upemor-purple">Consulta por Competencia</h1>
                    <p class="text-slate-600 mt-1">Evaluaciones agrupadas por competencia</p>
                </div>

                <!-- Selector de Competencia -->
                <div class="bg-white rounded-2xl shadow-lg p-6 mb-6">
                    <form method="GET" action="index.php" class="flex flex-col md:flex-row gap-4 items-end">
                        <input type="hidden" name="controller" value="consulta">
                        <input type="hidden" name="action" value="competencia">
                        
                        <div class="flex-1">
                            <label for="idCompetencia" class="block text-base font-semibold text-upemor-purple mb-2">
                                Seleccionar Competencia
                            </label>
                            <select 
                                id="idCompetencia"
                                name="idCompetencia" 
                                required
                                class="w-full px-4 py-3 border-2 border-slate-200 rounded-xl text-slate-900 focus:outline-none focus:ring-2 focus:ring-upemor-orange focus:border-upemor-orange transition"
                            >
                                <option value="">Seleccionar competencia...</option>
                                <?php foreach($competencias as $comp): ?>
                                <option value="<?php echo $comp['idCompetencias']; ?>"
                                    <?php echo (isset($_GET['idCompetencia']) && $_GET['idCompetencia'] == $comp['idCompetencias']) ? 'selected' : ''; ?>>
                                    <?php echo htmlspecialchars($comp['NombreCompetencia']); ?>
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

                <?php if($infoCompetencia): ?>
                <!-- Información de la Competencia -->
                <div class="grid grid-cols-1 lg:grid-cols-3 gap-6 mb-6">
                    <!-- Info Card -->
                    <div class="bg-white rounded-2xl shadow-lg p-6 border-t-4 border-upemor-purple">
                        <div class="flex items-center gap-3 mb-4">
                            <div class="h-12 w-12 rounded-lg bg-gradient-to-br from-upemor-purple to-purple-900 flex items-center justify-center text-white">
                                <span class="material-symbols-outlined text-3xl">workspace_premium</span>
                            </div>
                            <div>
                                <h3 class="text-lg font-bold text-upemor-purple">Competencia</h3>
                            </div>
                        </div>
                        <p class="font-bold text-upemor-purple text-lg mb-2">
                            <?php echo htmlspecialchars($infoCompetencia['NombreCompetencia']); ?>
                        </p>
                        <p class="text-sm text-slate-600">
                            <?php echo htmlspecialchars($infoCompetencia['Descripcion']); ?>
                        </p>
                    </div>

                    <!-- Estadísticas -->
                    <div class="lg:col-span-2 bg-white rounded-2xl shadow-lg p-6">
                        <h3 class="text-lg font-bold text-upemor-purple mb-4 flex items-center gap-2">
                            <span class="material-symbols-outlined">analytics</span>
                            Estadísticas Generales
                        </h3>
                        <div class="grid grid-cols-2 md:grid-cols-4 gap-4">
                            <div class="bg-slate-50 rounded-xl p-4">
                                <p class="text-sm text-slate-600">Total</p>
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
                                <p class="text-sm text-purple-700">Estudiantes</p>
                                <p class="text-3xl font-bold text-purple-600"><?php echo $estadisticas['EstudiantesEvaluados']; ?></p>
                            </div>
                            <div class="bg-orange-50 rounded-xl p-4 md:col-span-2">
                                <p class="text-sm text-orange-700">Docentes Participantes</p>
                                <p class="text-3xl font-bold text-orange-600"><?php echo $estadisticas['DocentesParticipantes']; ?></p>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Tabla de Evaluaciones -->
                <div class="bg-white rounded-2xl shadow-lg overflow-hidden">
                    <div class="bg-gradient-to-r from-upemor-purple to-purple-900 text-white p-6">
                        <div class="flex items-center gap-3">
                            <span class="material-symbols-outlined text-4xl">fact_check</span>
                            <div>
                                <h2 class="text-xl font-bold">Registro de Evaluaciones</h2>
                                <p class="text-sm text-purple-200"><?php echo count($evaluaciones); ?> evaluaciones en esta competencia</p>
                            </div>
                        </div>
                    </div>

                    <?php if(count($evaluaciones) > 0): ?>
                    <div class="overflow-x-auto">
                        <table class="w-full">
                            <thead class="bg-slate-100 border-b-2 border-slate-200">
                                <tr>
                                    <th class="px-6 py-4 text-left text-sm font-bold text-upemor-purple uppercase">Fecha</th>
                                    <th class="px-6 py-4 text-left text-sm font-bold text-upemor-purple uppercase">Estudiante</th>
                                    <th class="px-6 py-4 text-left text-sm font-bold text-upemor-purple uppercase">Matrícula</th>
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
                                    <td class="px-6 py-4 text-sm font-semibold text-upemor-purple">
                                        <?php echo htmlspecialchars($eval['NombreEstudiante']); ?>
                                    </td>
                                    <td class="px-6 py-4 text-sm text-slate-600">
                                        <?php echo htmlspecialchars($eval['Matricula']); ?>
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
                        <p class="text-slate-500 font-medium mt-3">No hay evaluaciones registradas para esta competencia</p>
                    </div>
                    <?php endif; ?>
                </div>
                <?php endif; ?>

                <?php if(!$infoCompetencia && !isset($_GET['idCompetencia'])): ?>
                <div class="bg-white rounded-2xl shadow-lg p-12 text-center">
                    <span class="material-symbols-outlined text-6xl text-slate-300">manage_search</span>
                    <p class="text-slate-500 font-medium mt-3">Selecciona una competencia para ver sus evaluaciones</p>
                </div>
                <?php endif; ?>
            </div>
        </main>
    </div>
</body>
</html>