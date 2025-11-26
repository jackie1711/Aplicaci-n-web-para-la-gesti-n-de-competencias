<?php
// Iniciar sesión si no está iniciada
if (session_status() === PHP_SESSION_NONE) {
    session_start();
}
?>
<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="utf-8"/>
    <meta content="width=device-width, initial-scale=1.0" name="viewport"/>
    <title>Gestión de Evaluaciones - UPEMOR</title>
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
                        <a class="group flex items-center gap-3 rounded-lg px-3 py-2 hover:bg-upemor-orange/10 transition" href="index.php?controller=auth&action=panelAdministrativo">
                            <span class="material-symbols-outlined text-slate-600 group-hover:text-upemor-purple">space_dashboard</span>
                            <p class="text-sm font-medium text-slate-700 group-hover:text-upemor-purple">Dashboard</p>
                        </a>
                        <a class="group flex items-center gap-3 rounded-lg px-3 py-2 hover:bg-upemor-orange/10 transition" href="index.php?controller=competencia&action=consult">
                            <span class="material-symbols-outlined text-slate-600 group-hover:text-upemor-purple">edit_document</span>
                            <p class="text-sm font-medium text-slate-700 group-hover:text-upemor-purple">Registro de Competencias</p>
                        </a>
                        <a class="flex items-center gap-3 rounded-lg bg-upemor-orange/10 border-l-4 border-upemor-orange px-3 py-2" href="index.php?controller=evaluacion&action=consult">
                            <span class="material-symbols-outlined text-upemor-purple">checklist</span>
                            <p class="text-sm font-bold text-upemor-purple">Evaluaciones</p>
                        </a>
                        <a class="group flex items-center gap-3 rounded-lg px-3 py-2 hover:bg-upemor-orange/10 transition" href="#">
                            <span class="material-symbols-outlined text-slate-600 group-hover:text-upemor-purple">person_search</span>
                            <p class="text-sm font-medium text-slate-700 group-hover:text-upemor-purple">Consultas Individuales</p>
                        </a>
                        <a class="group flex items-center gap-3 rounded-lg px-3 py-2 hover:bg-upemor-orange/10 transition" href="#">
                            <span class="material-symbols-outlined text-slate-600 group-hover:text-upemor-purple">manage_search</span>
                            <p class="text-sm font-medium text-slate-700 group-hover:text-upemor-purple">Consultas por Competencia</p>
                        </a>
                        
                        <!-- Reports Section -->
                        <div>
                            <p class="mt-4 px-3 text-xs font-semibold uppercase text-slate-600">Reportes</p>
                            <div class="mt-1 flex flex-col gap-1">
                                <a class="group flex items-center gap-3 rounded-lg px-3 py-2 hover:bg-upemor-orange/10 transition" href="#">
                                    <span class="material-symbols-outlined text-slate-600 group-hover:text-upemor-purple">groups</span>
                                    <p class="text-sm font-medium text-slate-700 group-hover:text-upemor-purple">Grupales</p>
                                </a>
                                <a class="group flex items-center gap-3 rounded-lg px-3 py-2 hover:bg-upemor-orange/10 transition" href="#">
                                    <span class="material-symbols-outlined text-slate-600 group-hover:text-upemor-purple">person</span>
                                    <p class="text-sm font-medium text-slate-700 group-hover:text-upemor-purple">Individuales</p>
                                </a>
                                <a class="group flex items-center gap-3 rounded-lg px-3 py-2 hover:bg-upemor-orange/10 transition" href="#">
                                    <span class="material-symbols-outlined text-slate-600 group-hover:text-upemor-purple">history</span>
                                    <p class="text-sm font-medium text-slate-700 group-hover:text-upemor-purple">Históricos</p>
                                </a>
                            </div>
                        </div>
                    </nav>
                </div>
                
                <!-- User Profile & Logout -->
                <div class="flex flex-col gap-2 border-t border-slate-200 pt-4">
                    <a class="group flex items-center gap-3 rounded-lg px-3 py-2 hover:bg-upemor-orange/10 transition" href="#">
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
                <!-- Page Header -->
                <div class="mb-8">
                    <div class="flex items-center justify-between">
                        <div>
                            <h1 class="text-3xl font-bold text-upemor-purple">Gestión de Evaluaciones</h1>
                            <p class="text-slate-600 mt-1">Registro y seguimiento de evaluaciones por competencias</p>
                        </div>
                        <button onclick="document.getElementById('modalNuevaEvaluacion').classList.remove('hidden')" class="flex items-center gap-2 px-6 py-3 bg-gradient-to-r from-upemor-orange to-upemor-red hover:from-upemor-red hover:to-upemor-orange text-white font-bold rounded-xl shadow-lg transition">
                            <span class="material-symbols-outlined">add_circle</span>
                            <span>Nueva Evaluación</span>
                        </button>
                    </div>
                </div>

                <!-- Mensajes de éxito/error -->
                <?php if(isset($_GET['success']) && $_GET['success'] == 1): ?>
                <div class="mb-6 flex items-center gap-3 bg-green-50 border-l-4 border-green-500 p-4 rounded-r-xl">
                    <span class="material-symbols-outlined text-green-600">check_circle</span>
                    <p class="text-green-800 font-medium">¡Operación realizada con éxito!</p>
                </div>
                <?php endif; ?>

                <?php if(isset($_GET['error']) && $_GET['error'] == 1): ?>
                <div class="mb-6 flex items-center gap-3 bg-red-50 border-l-4 border-red-500 p-4 rounded-r-xl">
                    <span class="material-symbols-outlined text-red-600">error</span>
                    <p class="text-red-800 font-medium">Error al realizar la operación. Intenta nuevamente.</p>
                </div>
                <?php endif; ?>

                <!-- Tabla de Evaluaciones -->
                <div class="bg-white rounded-2xl shadow-lg overflow-hidden">
                    <div class="bg-gradient-to-r from-upemor-purple to-purple-900 text-white p-6">
                        <div class="flex items-center gap-3">
                            <span class="material-symbols-outlined text-4xl">assignment</span>
                            <div>
                                <h2 class="text-xl font-bold">Registro de Evaluaciones</h2>
                                <p class="text-sm text-purple-200">Evaluaciones por competencias</p>
                            </div>
                        </div>
                    </div>

                    <div class="overflow-x-auto">
                        <table class="w-full">
                            <thead class="bg-slate-100 border-b-2 border-slate-200">
                                <tr>
                                    <th class="px-6 py-4 text-left text-sm font-bold text-upemor-purple uppercase tracking-wider">ID</th>
                                    <th class="px-6 py-4 text-left text-sm font-bold text-upemor-purple uppercase tracking-wider">Fecha</th>
                                    <th class="px-6 py-4 text-left text-sm font-bold text-upemor-purple uppercase tracking-wider">Estudiante</th>
                                    <th class="px-6 py-4 text-left text-sm font-bold text-upemor-purple uppercase tracking-wider">Docente</th>
                                    <th class="px-6 py-4 text-left text-sm font-bold text-upemor-purple uppercase tracking-wider">Competencia</th>
                                    <th class="px-6 py-4 text-left text-sm font-bold text-upemor-purple uppercase tracking-wider">Tipo</th>
                                    <th class="px-6 py-4 text-left text-sm font-bold text-upemor-purple uppercase tracking-wider">Calificación</th>
                                    <th class="px-6 py-4 text-center text-sm font-bold text-upemor-purple uppercase tracking-wider">Acciones</th>
                                </tr>
                            </thead>
                            <tbody class="divide-y divide-slate-200">
                                <?php 
                                // Verificar que $evaluaciones existe, es un objeto mysqli_result y tiene filas
                                if(isset($evaluaciones) && $evaluaciones !== false && $evaluaciones !== null && is_object($evaluaciones) && $evaluaciones->num_rows > 0):
                                    while($row = $evaluaciones->fetch_assoc()): 
                                ?>
                                <tr class="hover:bg-slate-50 transition">
                                    <td class="px-6 py-4 text-sm font-medium text-slate-900"><?php echo $row['idEvaluaciones']; ?></td>
                                    <td class="px-6 py-4 text-sm text-slate-600"><?php echo date('d/m/Y', strtotime($row['Fecha'])); ?></td>
                                    <td class="px-6 py-4 text-sm font-semibold text-upemor-purple">
                                        <?php echo htmlspecialchars($row['NombreEstudiante'] . ' ' . $row['ApellidosEstudiante']); ?>
                                    </td>
                                    <td class="px-6 py-4 text-sm text-slate-600">
                                        <?php echo htmlspecialchars($row['NombreDocente'] . ' ' . $row['ApellidosDocente']); ?>
                                    </td>
                                    <td class="px-6 py-4 text-sm text-slate-600">
                                        <?php echo htmlspecialchars($row['NombreCompetencia']); ?>
                                    </td>
                                    <td class="px-6 py-4 text-sm">
                                        <span class="px-2 py-1 bg-blue-100 text-blue-800 rounded-lg text-xs font-medium">
                                            <?php echo htmlspecialchars($row['TipoEvaluacion'] ?? 'N/A'); ?>
                                        </span>
                                    </td>
                                    <td class="px-6 py-4 text-sm">
                                        <?php 
                                        $calificacion = $row['Calificacion'] ?? 'N/A';
                                        $colorClass = match($calificacion) {
                                            'Excelente' => 'bg-green-100 text-green-800',
                                            'Bueno' => 'bg-blue-100 text-blue-800',
                                            'Regular' => 'bg-yellow-100 text-yellow-800',
                                            'Deficiente' => 'bg-red-100 text-red-800',
                                            default => 'bg-gray-100 text-gray-800'
                                        };
                                        ?>
                                        <span class="px-2 py-1 <?php echo $colorClass; ?> rounded-lg text-xs font-medium">
                                            <?php echo htmlspecialchars($calificacion); ?>
                                        </span>
                                    </td>
                                    <td class="px-6 py-4 text-center">
                                        <div class="flex items-center justify-center gap-2">
                                            <a href="index.php?controller=evaluacion&action=update&id=<?php echo $row['idEvaluaciones']; ?>" 
                                               class="flex items-center gap-1 px-3 py-2 bg-blue-500 hover:bg-blue-600 text-white text-sm font-medium rounded-lg transition">
                                                <span class="material-symbols-outlined !text-[18px]">edit</span>
                                                <span>Editar</span>
                                            </a>
                                            <a href="index.php?controller=evaluacion&action=delete&id=<?php echo $row['idEvaluaciones']; ?>" 
                                               onclick="return confirm('¿Estás seguro de eliminar esta evaluación?');"
                                               class="flex items-center gap-1 px-3 py-2 bg-red-500 hover:bg-red-600 text-white text-sm font-medium rounded-lg transition">
                                                <span class="material-symbols-outlined !text-[18px]">delete</span>
                                                <span>Eliminar</span>
                                            </a>
                                        </div>
                                    </td>
                                </tr>
                                <?php 
                                    endwhile;
                                else:
                                ?>
                                <tr>
                                    <td colspan="8" class="px-6 py-12 text-center">
                                        <div class="flex flex-col items-center gap-3">
                                            <span class="material-symbols-outlined text-6xl text-slate-300">assignment_late</span>
                                            <p class="text-slate-500 font-medium">No hay evaluaciones registradas</p>
                                            <button onclick="document.getElementById('modalNuevaEvaluacion').classList.remove('hidden')" class="text-upemor-orange hover:text-upemor-red font-semibold">
                                                + Agregar la primera evaluación
                                            </button>
                                        </div>
                                    </td>
                                </tr>
                                <?php endif; ?>
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
        </main>
    </div>

    <!-- Modal Nueva Evaluación -->
    <div id="modalNuevaEvaluacion" class="hidden fixed inset-0 bg-black/50 flex items-center justify-center p-4 z-50">
        <div class="bg-white rounded-2xl shadow-2xl max-w-4xl w-full max-h-[90vh] overflow-y-auto">
            <div class="bg-gradient-to-r from-upemor-purple to-purple-900 text-white p-6">
                <div class="flex items-center justify-between">
                    <div class="flex items-center gap-3">
                        <span class="material-symbols-outlined text-4xl">add_task</span>
                        <div>
                            <h2 class="text-xl font-bold">Nueva Evaluación</h2>
                            <p class="text-sm text-purple-200">Registro de evaluación por competencia</p>
                        </div>
                    </div>
                    <button onclick="document.getElementById('modalNuevaEvaluacion').classList.add('hidden')" class="text-white hover:text-purple-200 transition">
                        <span class="material-symbols-outlined text-3xl">close</span>
                    </button>
                </div>
            </div>

            <form action="index.php?controller=evaluacion&action=insert" method="POST" class="p-8 space-y-6">
                <!-- Información General -->
                <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                    <div>
                        <label for="Fecha" class="block text-base font-semibold text-upemor-purple mb-2">
                            Fecha de Evaluación <span class="text-danger">*</span>
                        </label>
                        <input 
                            type="date" 
                            id="Fecha"
                            name="Fecha" 
                            required
                            value="<?php echo date('Y-m-d'); ?>"
                            class="w-full px-4 py-3 border-2 border-slate-200 rounded-xl text-slate-900 focus:outline-none focus:ring-2 focus:ring-upemor-orange focus:border-upemor-orange transition"
                        />
                    </div>

                    <div>
                        <label for="TipoEvaluacion" class="block text-base font-semibold text-upemor-purple mb-2">
                            Tipo de Evaluación <span class="text-danger">*</span>
                        </label>
                        <select 
                            id="TipoEvaluacion"
                            name="TipoEvaluacion" 
                            required
                            class="w-full px-4 py-3 border-2 border-slate-200 rounded-xl text-slate-900 focus:outline-none focus:ring-2 focus:ring-upemor-orange focus:border-upemor-orange transition"
                        >
                            <option value="">Seleccionar tipo</option>
                            <option value="Producto">Producto</option>
                        </select>
                    </div>

                    <div>
                        <label for="idEstudiantes" class="block text-base font-semibold text-upemor-purple mb-2">
                            Estudiante Evaluado <span class="text-danger">*</span>
                        </label>
                        <select 
                            id="idEstudiantes"
                            name="idEstudiantes" 
                            required
                            class="w-full px-4 py-3 border-2 border-slate-200 rounded-xl text-slate-900 focus:outline-none focus:ring-2 focus:ring-upemor-orange focus:border-upemor-orange transition"
                        >
                            <option value="">Seleccionar estudiante</option>
                            <?php 
                            if(isset($estudiantes) && is_array($estudiantes) && count($estudiantes) > 0):
                                foreach($estudiantes as $est): 
                            ?>
                            <option value="<?php echo $est['idEstudiantes']; ?>">
                                <?php echo htmlspecialchars($est['Apellidos'] . ', ' . $est['Nombre']); ?>
                            </option>
                            <?php endforeach; 
                            else: ?>
                            <option value="" disabled>No hay estudiantes registrados</option>
                            <?php endif; ?>
                        </select>
                    </div>

                    <div>
                        <label for="idDocentes" class="block text-base font-semibold text-upemor-purple mb-2">
                            Docente Evaluador <span class="text-danger">*</span>
                        </label>
                        <select 
                            id="idDocentes"
                            name="idDocentes" 
                            required
                            class="w-full px-4 py-3 border-2 border-slate-200 rounded-xl text-slate-900 focus:outline-none focus:ring-2 focus:ring-upemor-orange focus:border-upemor-orange transition"
                        >
                            <option value="">Seleccionar docente</option>
                            <?php 
                            if(isset($docentes) && is_array($docentes) && count($docentes) > 0):
                                foreach($docentes as $doc): 
                            ?>
                            <option value="<?php echo $doc['idDocentes']; ?>">
                                <?php echo htmlspecialchars($doc['Apellidos'] . ', ' . $doc['Nombre']); ?>
                            </option>
                            <?php endforeach;
                            else: ?>
                            <option value="" disabled>No hay docentes registrados</option>
                            <?php endif; ?>
                        </select>
                    </div>

                    <div>
                        <label for="idCompetencias" class="block text-base font-semibold text-upemor-purple mb-2">
                            Competencia a Evaluar <span class="text-danger">*</span>
                        </label>
                        <select 
                            id="idCompetencias"
                            name="idCompetencias" 
                            required
                            class="w-full px-4 py-3 border-2 border-slate-200 rounded-xl text-slate-900 focus:outline-none focus:ring-2 focus:ring-upemor-orange focus:border-upemor-orange transition"
                        >
                            <option value="">Seleccionar competencia</option>
                            <?php 
                            if(isset($competencias) && is_array($competencias) && count($competencias) > 0):
                                foreach($competencias as $comp): 
                            ?>
                            <option value="<?php echo $comp['idCompetencias']; ?>">
                                <?php echo htmlspecialchars($comp['NombreCompetencia']); ?>
                            </option>
                            <?php endforeach;
                            else: ?>
                            <option value="" disabled>No hay competencias registradas</option>
                            <?php endif; ?>
                        </select>
                    </div>

                    <div>
                        <label for="Calificacion" class="block text-base font-semibold text-upemor-purple mb-2">
                            Calificación <span class="text-danger">*</span>
                        </label>
                        <select 
                            id="Calificacion"
                            name="Calificacion" 
                            required
                            class="w-full px-4 py-3 border-2 border-slate-200 rounded-xl text-slate-900 focus:outline-none focus:ring-2 focus:ring-upemor-orange focus:border-upemor-orange transition"
                        >
                            <option value="">Seleccionar calificación</option>
                            <option value="Excelente">Excelente</option>
                            <option value="Bueno">Bueno</option>
                            <option value="Regular">Regular</option>
                            <option value="Deficiente">Deficiente</option>
                        </select>
                    </div>
                </div>

                <div>
                    <label for="Observaciones" class="block text-base font-semibold text-upemor-purple mb-2">
                        Observaciones de la Evaluación
                    </label>
                    <textarea 
                        id="Observaciones"
                        name="Observaciones" 
                        rows="4"
                        placeholder="Observaciones generales sobre la evaluación..."
                        class="w-full px-4 py-3 border-2 border-slate-200 rounded-xl text-slate-900 placeholder:text-slate-400 focus:outline-none focus:ring-2 focus:ring-upemor-orange focus:border-upemor-orange transition resize-none"
                    ></textarea>
                </div>

                <div class="bg-blue-50 border-l-4 border-blue-500 p-4 rounded-r-xl">
                    <div class="flex gap-3">
                        <span class="material-symbols-outlined text-blue-600">info</span>
                        <div class="text-sm text-blue-900">
                            <p class="font-semibold mb-1">Importante</p>
                            <p class="text-blue-800">Asegúrate de completar todos los campos requeridos antes de guardar la evaluación.</p>
                        </div>
                    </div>
                </div>

                <div class="flex gap-3 pt-4 border-t border-slate-200">
                    <button 
                        type="button"
                        onclick="document.getElementById('modalNuevaEvaluacion').classList.add('hidden')"
                        class="flex-1 px-6 py-3 border-2 border-slate-300 text-slate-700 font-bold rounded-xl hover:bg-slate-50 transition">
                        Cancelar
                    </button>
                    <button 
                        type="submit"
                        name="enviar"
                        class="flex-1 px-6 py-3 bg-gradient-to-r from-upemor-orange to-upemor-red hover:from-upemor-red hover:to-upemor-orange text-white font-bold rounded-xl transition shadow-lg flex items-center justify-center gap-2">
                        <span class="material-symbols-outlined">save</span>
                        Guardar Evaluación
                    </button>
                </div>
            </form>
        </div>
    </div>
</body>
</html>