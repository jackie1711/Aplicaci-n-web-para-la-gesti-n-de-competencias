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
    <title>Editar Evaluación - UPEMOR</title>
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
<body class="font-display bg-gradient-to-br from-slate-50 to-slate-100 min-h-screen">
    <div class="p-6 lg:p-8">
        <div class="max-w-5xl mx-auto">
            <!-- Header -->
            <div class="mb-8">
                <a href="index.php?controller=evaluacion&action=consult" class="inline-flex items-center gap-2 text-upemor-purple hover:text-upemor-orange transition mb-4">
                    <span class="material-symbols-outlined">arrow_back</span>
                    <span class="font-medium">Volver a Gestión de Evaluaciones</span>
                </a>
                <h1 class="text-3xl font-bold text-upemor-purple">Editar Evaluación</h1>
                <p class="text-slate-600 mt-2">
                    Evaluación ID: <span class="font-semibold text-upemor-orange">#<?php echo $row['idEvaluaciones']; ?></span> | 
                    Estudiante: <span class="font-semibold text-upemor-purple"><?php echo htmlspecialchars($row['NombreEstudiante'] . ' ' . $row['ApellidosEstudiante']); ?></span>
                </p>
            </div>

            <!-- Form Card -->
            <div class="bg-white rounded-2xl shadow-lg overflow-hidden">
                <div class="bg-gradient-to-r from-upemor-purple to-purple-900 text-white p-6">
                    <div class="flex items-center gap-3">
                        <span class="material-symbols-outlined text-4xl">edit_note</span>
                        <div>
                            <h2 class="text-xl font-bold">Formulario de Edición</h2>
                            <p class="text-sm text-purple-200">Actualiza la información de la evaluación</p>
                        </div>
                    </div>
                </div>

                <form action="index.php?controller=evaluacion&action=update" method="POST" class="p-8 space-y-6">
                    <input type="hidden" name="id" value="<?php echo $row['idEvaluaciones']; ?>">

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
                                value="<?php echo htmlspecialchars($row['Fecha']); ?>"
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
                                <option value="Producto" <?php echo (isset($row['TipoEvaluacion']) && $row['TipoEvaluacion'] == 'Producto') ? 'selected' : ''; ?>>Producto</option>
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
                                <?php 
                                if(isset($estudiantes) && is_array($estudiantes)):
                                    foreach($estudiantes as $est): 
                                ?>
                                <option value="<?php echo $est['idEstudiantes']; ?>" 
                                    <?php echo ($est['idEstudiantes'] == $row['idEstudiantes']) ? 'selected' : ''; ?>>
                                    <?php echo htmlspecialchars($est['Apellidos'] . ', ' . $est['Nombre']); ?>
                                </option>
                                <?php endforeach; endif; ?>
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
                                <?php 
                                if(isset($docentes) && is_array($docentes)):
                                    foreach($docentes as $doc): 
                                ?>
                                <option value="<?php echo $doc['idDocentes']; ?>"
                                    <?php echo ($doc['idDocentes'] == $row['idDocentes']) ? 'selected' : ''; ?>>
                                    <?php echo htmlspecialchars($doc['Apellidos'] . ', ' . $doc['Nombre']); ?>
                                </option>
                                <?php endforeach; endif; ?>
                            </select>
                        </div>

                        <div>
                            <label for="idCompetencias" class="block text-base font-semibold text-upemor-purple mb-2">
                                Competencia <span class="text-danger">*</span>
                            </label>
                            <select 
                                id="idCompetencias"
                                name="idCompetencias" 
                                required
                                disabled
                                class="w-full px-4 py-3 border-2 border-slate-200 rounded-xl text-slate-900 bg-slate-100 cursor-not-allowed"
                            >
                                <?php 
                                if(isset($competencias) && is_array($competencias)):
                                    foreach($competencias as $comp): 
                                ?>
                                <option value="<?php echo $comp['idCompetencias']; ?>"
                                    <?php echo ($comp['idCompetencias'] == $row['idCompetencias']) ? 'selected' : ''; ?>>
                                    <?php echo htmlspecialchars($comp['NombreCompetencia']); ?>
                                </option>
                                <?php endforeach; endif; ?>
                            </select>
                            <!-- Campo oculto para enviar el valor -->
                            <input type="hidden" name="idCompetencias" value="<?php echo $row['idCompetencias']; ?>">
                            <p class="text-xs text-slate-500 mt-1">La competencia no puede ser modificada una vez creada la evaluación</p>
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
                                <option value="Excelente" <?php echo (isset($row['Calificacion']) && $row['Calificacion'] == 'Excelente') ? 'selected' : ''; ?>>Excelente</option>
                                <option value="Bueno" <?php echo (isset($row['Calificacion']) && $row['Calificacion'] == 'Bueno') ? 'selected' : ''; ?>>Bueno</option>
                                <option value="Regular" <?php echo (isset($row['Calificacion']) && $row['Calificacion'] == 'Regular') ? 'selected' : ''; ?>>Regular</option>
                                <option value="Deficiente" <?php echo (isset($row['Calificacion']) && $row['Calificacion'] == 'Deficiente') ? 'selected' : ''; ?>>Deficiente</option>
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
                            class="w-full px-4 py-3 border-2 border-slate-200 rounded-xl text-slate-900 placeholder:text-slate-400 focus:outline-none focus:ring-2 focus:ring-upemor-orange focus:border-upemor-orange transition resize-none"
                        ><?php echo htmlspecialchars($row['Observaciones']); ?></textarea>
                    </div>

                    <!-- Información adicional -->
                    <div class="bg-blue-50 border-l-4 border-blue-500 p-4 rounded-r-xl">
                        <div class="flex gap-3">
                            <span class="material-symbols-outlined text-blue-600">info</span>
                            <div class="text-sm text-blue-900">
                                <p class="font-semibold mb-1">Información Importante</p>
                                <ul class="list-disc list-inside space-y-1 text-blue-800">
                                    <li>La competencia no puede ser modificada después de crear la evaluación</li>
                                    <li>Puedes actualizar todos los demás campos según sea necesario</li>
                                    <li>Los cambios se guardarán inmediatamente al confirmar</li>
                                </ul>
                            </div>
                        </div>
                    </div>

                    <!-- Botones de acción -->
                    <div class="flex gap-3 pt-4 border-t border-slate-200">
                        <a href="index.php?controller=evaluacion&action=consult" 
                           class="flex-1 text-center px-6 py-3 border-2 border-slate-300 text-slate-700 font-bold rounded-xl hover:bg-slate-50 transition">
                            Cancelar
                        </a>
                        <button 
                            type="submit"
                            name="editar"
                            class="flex-1 px-6 py-3 bg-gradient-to-r from-upemor-orange to-upemor-red hover:from-upemor-red hover:to-upemor-orange text-white font-bold rounded-xl transition shadow-lg flex items-center justify-center gap-2">
                            <span class="material-symbols-outlined">save</span>
                            Guardar Cambios
                        </button>
                    </div>
                </form>
            </div>

            <!-- Card de Información de la Evaluación -->
            <div class="mt-6 bg-white rounded-2xl shadow-lg overflow-hidden">
                <div class="bg-slate-100 p-4 border-b-2 border-slate-200">
                    <h3 class="text-lg font-bold text-upemor-purple flex items-center gap-2">
                        <span class="material-symbols-outlined">info</span>
                        Información de la Evaluación
                    </h3>
                </div>
                <div class="p-6 grid grid-cols-1 md:grid-cols-3 gap-6">
                    <div>
                        <p class="text-sm font-medium text-slate-600">Estudiante</p>
                        <p class="text-base font-bold text-upemor-purple mt-1">
                            <?php echo htmlspecialchars($row['NombreEstudiante'] . ' ' . $row['ApellidosEstudiante']); ?>
                        </p>
                    </div>
                    <div>
                        <p class="text-sm font-medium text-slate-600">Docente Evaluador</p>
                        <p class="text-base font-bold text-upemor-purple mt-1">
                            <?php echo htmlspecialchars($row['NombreDocente'] . ' ' . $row['ApellidosDocente']); ?>
                        </p>
                    </div>
                    <div>
                        <p class="text-sm font-medium text-slate-600">Competencia</p>
                        <p class="text-base font-bold text-upemor-purple mt-1">
                            <?php echo htmlspecialchars($row['NombreCompetencia']); ?>
                        </p>
                    </div>
                </div>
            </div>
        </div>
    </div>
</body>
</html>