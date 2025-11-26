<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="utf-8"/>
    <meta content="width=device-width, initial-scale=1.0" name="viewport"/>
    <title>Gestión de Evidencias - UPEMOR</title>
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
                            <p class="text-sm text-slate-600">Panel Docente</p>
                        </div>
                    </div>
                    
                    <nav class="mt-4 flex flex-col gap-1">
                        <a class="group flex items-center gap-3 rounded-lg px-3 py-2 hover:bg-upemor-orange/10 transition" href="index.php?controller=auth&action=panelAdministrativo">
                            <span class="material-symbols-outlined text-slate-600 group-hover:text-upemor-purple">space_dashboard</span>
                            <p class="text-sm font-medium text-slate-700 group-hover:text-upemor-purple">Dashboard</p>
                        </a>
                        <a class="flex items-center gap-3 rounded-lg bg-upemor-orange/10 border-l-4 border-upemor-orange px-3 py-2" href="index.php?controller=asignacion&action=listar">
                            <span class="material-symbols-outlined text-upemor-purple">assignment</span>
                            <p class="text-sm font-bold text-upemor-purple">Gestión de Evidencias</p>
                        </a>
                        <a class="group flex items-center gap-3 rounded-lg px-3 py-2 hover:bg-upemor-orange/10 transition" href="index.php?controller=competencia&action=consult">
                            <span class="material-symbols-outlined text-slate-600 group-hover:text-upemor-purple">edit_document</span>
                            <p class="text-sm font-medium text-slate-700 group-hover:text-upemor-purple">Competencias</p>
                        </a>
                        <a class="group flex items-center gap-3 rounded-lg px-3 py-2 hover:bg-upemor-orange/10 transition" href="index.php?controller=evaluacion&action=consult">
                            <span class="material-symbols-outlined text-slate-600 group-hover:text-upemor-purple">history</span>
                            <p class="text-sm font-medium text-slate-700 group-hover:text-upemor-purple">Historial Evaluaciones</p>
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
                        <h1 class="text-3xl font-bold text-upemor-purple">Gestión de Evidencias</h1>
                        <p class="text-slate-600">Sistema de asignación, entrega y evaluación de competencias</p>
                    </div>
                    <button onclick="document.getElementById('modalAsignar').classList.remove('hidden')" class="flex items-center gap-2 rounded-lg bg-gradient-to-r from-upemor-orange to-upemor-red hover:from-upemor-red hover:to-upemor-orange px-5 py-2.5 text-white font-bold shadow-lg transition transform hover:scale-105">
                        <span class="material-symbols-outlined">add_circle</span>
                        Asignar Nueva Evidencia
                    </button>
                </div>

                <?php if(isset($_SESSION['success'])): ?>
                <div class="mb-6 rounded-lg bg-green-50 border-l-4 border-green-500 p-4 flex items-center gap-3">
                    <span class="material-symbols-outlined text-green-600">check_circle</span>
                    <p class="text-green-800 font-medium"><?php echo $_SESSION['success']; unset($_SESSION['success']); ?></p>
                </div>
                <?php endif; ?>

                <?php if(isset($_SESSION['error'])): ?>
                <div class="mb-6 rounded-lg bg-red-50 border-l-4 border-red-500 p-4 flex items-center gap-3">
                    <span class="material-symbols-outlined text-red-600">error</span>
                    <p class="text-red-800 font-medium"><?php echo $_SESSION['error']; unset($_SESSION['error']); ?></p>
                </div>
                <?php endif; ?>

                <div class="mb-6 flex gap-2 flex-wrap">
                    <button onclick="filtrarEstado('todos')" class="filtro-btn active px-4 py-2 rounded-lg font-semibold transition" data-estado="todos">
                        Todas (<?php echo $asignaciones->num_rows; ?>)
                    </button>
                    <button onclick="filtrarEstado('Pendiente')" class="filtro-btn px-4 py-2 rounded-lg font-semibold transition" data-estado="Pendiente">
                        <span class="inline-block w-2 h-2 rounded-full bg-yellow-500 mr-2"></span>
                        Pendientes
                    </button>
                    <button onclick="filtrarEstado('Entregada')" class="filtro-btn px-4 py-2 rounded-lg font-semibold transition" data-estado="Entregada">
                        <span class="inline-block w-2 h-2 rounded-full bg-blue-500 mr-2"></span>
                        Entregadas
                    </button>
                    <button onclick="filtrarEstado('Evaluada')" class="filtro-btn px-4 py-2 rounded-lg font-semibold transition" data-estado="Evaluada">
                        <span class="inline-block w-2 h-2 rounded-full bg-green-500 mr-2"></span>
                        Evaluadas
                    </button>
                </div>

                <div class="rounded-2xl bg-white p-6 shadow-lg">
                    <?php if($asignaciones->num_rows > 0): ?>
                    <div class="space-y-4" id="listaAsignaciones">
                        <?php 
                        mysqli_data_seek($asignaciones, 0); // Reset pointer
                        while($asig = $asignaciones->fetch_assoc()): 
                            $estado = $asig['Estado'];
                            $borderClass = '';
                            $badgeClass = '';
                            $iconEstado = '';
                            
                            if($estado == 'Pendiente') {
                                $borderClass = 'border-yellow-500';
                                $badgeClass = 'bg-yellow-100 text-yellow-800';
                                $iconEstado = 'pending_actions';
                            } elseif($estado == 'Entregada') {
                                $borderClass = 'border-blue-500';
                                $badgeClass = 'bg-blue-100 text-blue-800';
                                $iconEstado = 'upload_file';
                            } else {
                                $borderClass = 'border-green-500';
                                $badgeClass = 'bg-green-100 text-green-800';
                                $iconEstado = 'check_circle';
                            }
                            
                            // Calcular días restantes
                            $hoy = new DateTime();
                            $fechaLimite = new DateTime($asig['FechaLimite']);
                            $diff = $hoy->diff($fechaLimite);
                            $diasRestantes = $fechaLimite > $hoy ? $diff->days : -$diff->days;
                        ?>
                        <div class="asignacion-item border-l-4 <?php echo $borderClass; ?> bg-slate-50 p-5 rounded-r-lg hover:shadow-md transition" data-estado="<?php echo $estado; ?>">
                            <div class="flex justify-between items-start gap-4">
                                <div class="flex-1">
                                    <div class="flex items-start gap-3 mb-3">
                                        <div class="flex-shrink-0 mt-1">
                                            <div class="w-12 h-12 rounded-lg bg-gradient-to-br from-upemor-purple/10 to-purple-100 flex items-center justify-center">
                                                <span class="material-symbols-outlined text-upemor-purple text-2xl"><?php echo $iconEstado; ?></span>
                                            </div>
                                        </div>
                                        <div class="flex-1">
                                            <h4 class="font-bold text-lg text-upemor-purple"><?php echo htmlspecialchars($asig['NombreEvidencia']); ?></h4>
                                            <p class="text-sm text-slate-600 mt-1"><?php echo htmlspecialchars($asig['Descripcion']); ?></p>
                                        </div>
                                        <div class="flex flex-col items-end gap-2">
                                            <span class="inline-flex items-center gap-1 px-3 py-1 rounded-full text-xs font-bold <?php echo $badgeClass; ?>">
                                                <span class="material-symbols-outlined text-sm"><?php echo $iconEstado; ?></span>
                                                <?php echo $estado; ?>
                                            </span>
                                            <?php if($estado == 'Pendiente'): ?>
                                                <?php if($diasRestantes > 0): ?>
                                                    <span class="text-xs font-medium text-slate-600">
                                                        <?php echo $diasRestantes; ?> día<?php echo $diasRestantes != 1 ? 's' : ''; ?> restantes
                                                    </span>
                                                <?php else: ?>
                                                    <span class="text-xs font-bold text-red-600">¡Vencida!</span>
                                                <?php endif; ?>
                                            <?php endif; ?>
                                        </div>
                                    </div>
                                    
                                    <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-3 text-sm mt-4 bg-white p-3 rounded-lg">
                                        <div class="flex items-center gap-2">
                                            <span class="material-symbols-outlined text-slate-500 text-lg">person</span>
                                            <div>
                                                <p class="text-xs text-slate-500 font-medium">Estudiante</p>
                                                <p class="font-semibold text-slate-700"><?php echo htmlspecialchars($asig['NombreEstudiante']); ?></p>
                                            </div>
                                        </div>
                                        <div class="flex items-center gap-2">
                                            <span class="material-symbols-outlined text-slate-500 text-lg">badge</span>
                                            <div>
                                                <p class="text-xs text-slate-500 font-medium">Matrícula</p>
                                                <p class="font-semibold text-slate-700"><?php echo htmlspecialchars($asig['Matricula']); ?></p>
                                            </div>
                                        </div>
                                        <div class="flex items-center gap-2">
                                            <span class="material-symbols-outlined text-slate-500 text-lg">school</span>
                                            <div>
                                                <p class="text-xs text-slate-500 font-medium">Competencia</p>
                                                <p class="font-semibold text-slate-700"><?php echo htmlspecialchars($asig['NombreCompetencia']); ?></p>
                                            </div>
                                        </div>
                                        <div class="flex items-center gap-2">
                                            <span class="material-symbols-outlined text-slate-500 text-lg">calendar_today</span>
                                            <div>
                                                <p class="text-xs text-slate-500 font-medium">Fecha límite</p>
                                                <p class="font-semibold text-slate-700"><?php echo date('d/m/Y', strtotime($asig['FechaLimite'])); ?></p>
                                            </div>
                                        </div>
                                    </div>
                                    
                                    <?php if($estado == 'Entregada' && $asig['NombreArchivo']): ?>
                                    <div class="mt-4 p-4 bg-blue-50 rounded-lg border border-blue-200">
                                        <div class="flex items-center gap-3">
                                            <div class="w-10 h-10 rounded-lg bg-blue-500 flex items-center justify-center">
                                                <span class="material-symbols-outlined text-white">description</span>
                                            </div>
                                            <div class="flex-1">
                                                <p class="text-sm font-bold text-blue-800">Archivo entregado</p>
                                                <p class="text-sm text-blue-700"><?php echo htmlspecialchars($asig['NombreArchivo']); ?></p>
                                                <p class="text-xs text-blue-600 mt-1">
                                                    <span class="material-symbols-outlined text-xs align-middle">schedule</span>
                                                    <?php echo date('d/m/Y H:i', strtotime($asig['FechaSubida'])); ?>
                                                </p>
                                            </div>
                                        </div>
                                    </div>
                                    <?php endif; ?>
                                    
                                    <?php if($estado == 'Evaluada' && isset($asig['Calificacion'])): ?>
                                    <div class="mt-4 p-4 bg-green-50 rounded-lg border border-green-200">
                                        <div class="flex items-center gap-3">
                                            <div class="w-10 h-10 rounded-lg bg-green-500 flex items-center justify-center">
                                                <span class="material-symbols-outlined text-white">task_alt</span>
                                            </div>
                                            <div class="flex-1">
                                                <p class="text-sm font-bold text-green-800">Evaluación completada</p>
                                                <p class="text-sm text-green-700 mt-1">
                                                    <span class="font-semibold">Calificación:</span> <?php echo htmlspecialchars($asig['Calificacion']); ?>
                                                </p>
                                                <?php if(isset($asig['ObservacionesEval'])): ?>
                                                <p class="text-xs text-green-600 mt-2 italic">"<?php echo htmlspecialchars($asig['ObservacionesEval']); ?>"</p>
                                                <?php endif; ?>
                                            </div>
                                        </div>
                                    </div>
                                    <?php endif; ?>
                                </div>
                                
                                <div class="flex flex-col gap-2">
                                    <?php if($estado == 'Entregada'): ?>
                                    <button onclick="mostrarModalEvaluar(<?php echo $asig['idAsignacion']; ?>, '<?php echo htmlspecialchars(addslashes($asig['NombreEvidencia'])); ?>', '<?php echo htmlspecialchars(addslashes($asig['NombreEstudiante'])); ?>', <?php echo $asig['idEstudiantes']; ?>, <?php echo $asig['idCompetencias']; ?>)" class="flex items-center gap-1 rounded-lg bg-green-600 hover:bg-green-700 px-4 py-2 text-white text-sm font-bold transition transform hover:scale-105 shadow-md">
                                        <span class="material-symbols-outlined text-sm">check_circle</span>
                                        Evaluar
                                    </button>
                                    <?php endif; ?>
                                    
                                    
                                    </div>
                            </div>
                        </div>
                        <?php endwhile; ?>
                    </div>
                    <?php else: ?>
                    <div class="text-center py-16">
                        <div class="inline-block p-6 rounded-full bg-slate-100 mb-4">
                            <span class="material-symbols-outlined text-6xl text-slate-400">assignment</span>
                        </div>
                        <p class="text-xl font-bold text-slate-700 mb-2">No hay evidencias asignadas</p>
                        <p class="text-sm text-slate-500 mb-6">Comienza asignando evidencias a tus estudiantes</p>
                        <button onclick="document.getElementById('modalAsignar').classList.remove('hidden')" class="inline-flex items-center gap-2 rounded-lg bg-upemor-orange hover:bg-upemor-red px-6 py-3 text-white font-bold transition">
                            <span class="material-symbols-outlined">add_circle</span>
                            Asignar Primera Evidencia
                        </button>
                    </div>
                    <?php endif; ?>
                </div>
            </div>
        </main>
    </div>

    <div id="modalAsignar" class="hidden fixed inset-0 bg-black/50 flex items-center justify-center z-50 p-4">
        <div class="bg-white rounded-2xl shadow-2xl max-w-2xl w-full max-h-[90vh] overflow-y-auto">
            <div class="sticky top-0 bg-gradient-to-r from-upemor-purple to-purple-900 text-white p-6 rounded-t-2xl">
                <div class="flex justify-between items-center">
                    <div>
                        <h3 class="text-2xl font-bold">Asignar Nueva Evidencia</h3>
                        <p class="text-sm text-purple-100 mt-1">Crea una nueva asignación de competencia</p>
                    </div>
                    <button onclick="document.getElementById('modalAsignar').classList.add('hidden')" class="text-white hover:text-slate-200 transition">
                        <span class="material-symbols-outlined text-3xl">close</span>
                    </button>
                </div>
            </div>
            
            <form action="index.php?controller=asignacion&action=crear" method="POST" class="p-6">
                <div class="space-y-5">
                    <div>
                        <label class="block text-sm font-bold text-slate-700 mb-2">
                            <span class="material-symbols-outlined text-sm align-middle">title</span>
                            Nombre de la Evidencia *
                        </label>
                        <input type="text" name="nombreEvidencia" required class="w-full rounded-lg border-slate-300 focus:border-upemor-purple focus:ring-2 focus:ring-upemor-purple/20 transition" placeholder="Ej: Proyecto Final de Programación Orientada a Objetos">
                    </div>
                    
                    <div>
                        <label class="block text-sm font-bold text-slate-700 mb-2">
                            <span class="material-symbols-outlined text-sm align-middle">description</span>
                            Descripción *
                        </label>
                        <textarea name="descripcion" required rows="4" class="w-full rounded-lg border-slate-300 focus:border-upemor-purple focus:ring-2 focus:ring-upemor-purple/20 transition" placeholder="Describe los objetivos, criterios de evaluación y qué debe contener la evidencia..."></textarea>
                    </div>
                    
                    <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                        <div>
                            <label class="block text-sm font-bold text-slate-700 mb-2">
                                <span class="material-symbols-outlined text-sm align-middle">person</span>
                                Estudiante *
                            </label>
                            <select name="idEstudiante" required class="w-full rounded-lg border-slate-300 focus:border-upemor-purple focus:ring-2 focus:ring-upemor-purple/20 transition">
                                <option value="">Selecciona un estudiante</option>
                                <?php 
                                if (isset($estudiantes)) {
                                    mysqli_data_seek($estudiantes, 0);
                                    while($est = $estudiantes->fetch_assoc()): 
                                ?>
                                    <option value="<?php echo $est['idEstudiantes']; ?>">
                                        <?php echo htmlspecialchars($est['NombreCompleto'] . ' - ' . $est['Matricula']); ?>
                                    </option>
                                <?php endwhile; } ?>
                            </select>
                        </div>
                        
                        <div>
                            <label class="block text-sm font-bold text-slate-700 mb-2">
                                <span class="material-symbols-outlined text-sm align-middle">school</span>
                                Competencia *
                            </label>
                            <select name="idCompetencia" required class="w-full rounded-lg border-slate-300 focus:border-upemor-purple focus:ring-2 focus:ring-upemor-purple/20 transition">
                                <option value="">Selecciona una competencia</option>
                                <?php 
                                if (isset($competencias)) {
                                    mysqli_data_seek($competencias, 0);
                                    while($comp = $competencias->fetch_assoc()): 
                                ?>
                                    <option value="<?php echo $comp['idCompetencias']; ?>">
                                        <?php echo htmlspecialchars($comp['NombreCompetencia']); ?>
                                    </option>
                                <?php endwhile; } ?>
                            </select>
                        </div>
                    </div>
                    
                    <div>
                        <label class="block text-sm font-bold text-slate-700 mb-2">
                            <span class="material-symbols-outlined text-sm align-middle">calendar_today</span>
                            Fecha Límite de Entrega *
                        </label>
                        <input type="date" name="fechaLimite" required min="<?php echo date('Y-m-d', strtotime('+1 day')); ?>" class="w-full rounded-lg border-slate-300 focus:border-upemor-purple focus:ring-2 focus:ring-upemor-purple/20 transition">
                        <p class="text-xs text-slate-500 mt-1">El estudiante debe subir su evidencia antes de esta fecha</p>
                    </div>
                    
                    <div class="flex justify-end gap-3 pt-4 border-t border-slate-200">
                        <button type="button" onclick="document.getElementById('modalAsignar').classList.add('hidden')" class="px-6 py-2.5 rounded-lg border border-slate-300 text-slate-700 hover:bg-slate-50 font-bold transition">
                            Cancelar
                        </button>
                        <button type="submit" class="px-6 py-2.5 rounded-lg bg-gradient-to-r from-upemor-orange to-upemor-red hover:from-upemor-red hover:to-upemor-orange text-white font-bold transition flex items-center gap-2 shadow-lg">
                            <span class="material-symbols-outlined">send</span>
                            Asignar Evidencia
                        </button>
                    </div>
                </div>
            </form>
        </div>
    </div>

    <div id="modalEvaluar" class="hidden fixed inset-0 bg-black/50 flex items-center justify-center z-50 p-4">
        <div class="bg-white rounded-2xl shadow-2xl max-w-xl w-full">
            <div class="bg-gradient-to-r from-green-600 to-green-700 text-white p-6 rounded-t-2xl">
                <div class="flex items-center gap-3">
                    <div class="w-12 h-12 rounded-lg bg-white/20 flex items-center justify-center">
                        <span class="material-symbols-outlined text-3xl">rate_review</span>
                    </div>
                    <div>
                        <h3 class="text-2xl font-bold">Evaluar Evidencia</h3>
                        <p id="evidenciaNombre" class="text-sm text-green-100 mt-1"></p>
                    </div>
                </div>
            </div>
            
            <form action="index.php?controller=asignacion&action=evaluar" method="POST" class="p-6">
                <input type="hidden" name="idAsignacion" id="idAsignacionEvaluar">
                <input type="hidden" name="idEstudiante" id="idEstudianteEvaluar">
                <input type="hidden" name="idCompetencia" id="idCompetenciaEvaluar">
                
                <div class="space-y-5">
                    <div>
                        <label class="block text-sm font-bold text-slate-700 mb-2">
                            <span class="material-symbols-outlined text-sm align-middle">grade</span>
                            Calificación *
                        </label>
                        <select name="calificacion" required class="w-full rounded-lg border-slate-300 focus:border-green-500 focus:ring-2 focus:ring-green-500/20 transition">
                            <option value="">Selecciona una calificación</option>
                            <option value="Excelente">⭐⭐⭐ Excelente - Supera las expectativas</option>
                            <option value="Buena">⭐⭐ Buena - Cumple con los objetivos</option>
                            <option value="Deficiente">⭐ Deficiente - Necesita mejorar</option>
                        </select>
                    </div>
                    
                    <div>
                        <label class="block text-sm font-bold text-slate-700 mb-2">
                            <span class="material-symbols-outlined text-sm align-middle">comment</span>
                            Observaciones y Retroalimentación *
                        </label>
                        <textarea name="observaciones" required rows="5" class="w-full rounded-lg border-slate-300 focus:border-green-500 focus:ring-2 focus:ring-green-500/20 transition" placeholder="Proporciona retroalimentación constructiva al estudiante:&#10;- ¿Qué aspectos fueron bien ejecutados?&#10;- ¿Qué puede mejorar?&#10;- Recomendaciones específicas..."></textarea>
                        <p class="text-xs text-slate-500 mt-1">Esta retroalimentación será visible para el estudiante</p>
                    </div>
                    
                    <div class="bg-blue-50 border border-blue-200 rounded-lg p-4">
                        <div class="flex items-start gap-2">
                            <span class="material-symbols-outlined text-blue-600 text-sm mt-0.5">info</span>
                            <div class="text-sm text-blue-800">
                                <p class="font-semibold mb-1">Nota importante:</p>
                                <p>Al evaluar esta evidencia se registrará automáticamente en el historial de evaluaciones y el estudiante podrá ver tu retroalimentación.</p>
                            </div>
                        </div>
                    </div>
                    
                    <div class="flex justify-end gap-3 pt-4 border-t border-slate-200">
                        <button type="button" onclick="document.getElementById('modalEvaluar').classList.add('hidden')" class="px-6 py-2.5 rounded-lg border border-slate-300 text-slate-700 hover:bg-slate-50 font-bold transition">
                            Cancelar
                        </button>
                        <button type="submit" class="px-6 py-2.5 rounded-lg bg-gradient-to-r from-green-600 to-green-700 hover:from-green-700 hover:to-green-800 text-white font-bold transition flex items-center gap-2 shadow-lg">
                            <span class="material-symbols-outlined">check_circle</span>
                            Confirmar Evaluación
                        </button>
                    </div>
                </div>
            </form>
        </div>
    </div>

    <script>
        // Sistema de filtrado
        function filtrarEstado(estado) {
            const items = document.querySelectorAll('.asignacion-item');
            const botones = document.querySelectorAll('.filtro-btn');
            
            // Actualizar botones
            botones.forEach(btn => {
                if(btn.dataset.estado === estado) {
                    btn.classList.add('active', 'bg-upemor-purple', 'text-white');
                    btn.classList.remove('bg-slate-100', 'text-slate-700', 'hover:bg-slate-200');
                } else {
                    btn.classList.remove('active', 'bg-upemor-purple', 'text-white');
                    btn.classList.add('bg-slate-100', 'text-slate-700', 'hover:bg-slate-200');
                }
            });
            
            // Filtrar items
            items.forEach(item => {
                if(estado === 'todos') {
                    item.style.display = 'block';
                } else {
                    if(item.dataset.estado === estado) {
                        item.style.display = 'block';
                    } else {
                        item.style.display = 'none';
                    }
                }
            });
        }
        
        // Inicializar estilos de botones al cargar
        document.addEventListener('DOMContentLoaded', function() {
            const botonesInactivos = document.querySelectorAll('.filtro-btn:not(.active)');
            botonesInactivos.forEach(btn => {
                btn.classList.add('bg-slate-100', 'text-slate-700', 'hover:bg-slate-200');
            });
            
            const botonActivo = document.querySelector('.filtro-btn.active');
            if(botonActivo) {
                botonActivo.classList.add('bg-upemor-purple', 'text-white');
            }
        });
        
        // Mostrar modal de evaluación
        function mostrarModalEvaluar(idAsignacion, nombreEvidencia, nombreEstudiante, idEstudiante, idCompetencia) {
            document.getElementById('idAsignacionEvaluar').value = idAsignacion;
            document.getElementById('idEstudianteEvaluar').value = idEstudiante;
            document.getElementById('idCompetenciaEvaluar').value = idCompetencia;
            document.getElementById('evidenciaNombre').textContent = nombreEvidencia + ' - ' + nombreEstudiante;
            document.getElementById('modalEvaluar').classList.remove('hidden');
        }
        
        // Funciones adicionales
        function editarAsignacion(id) {
            alert('Función de edición en desarrollo. ID: ' + id);
            // Aquí puedes implementar la edición
        }
        
        // Cerrar modales con ESC
        document.addEventListener('keydown', function(e) {
            if(e.key === 'Escape') {
                document.getElementById('modalAsignar').classList.add('hidden');
                document.getElementById('modalEvaluar').classList.add('hidden');
            }
        });
    </script>
</body>
</html>