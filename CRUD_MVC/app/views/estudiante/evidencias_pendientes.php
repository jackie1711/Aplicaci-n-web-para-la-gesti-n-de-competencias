<?php
// Evidencias pendientes del Estudiante
$idEstudiante = $estudiante['idEstudiantes'] ?? 0;
?>
<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="utf-8"/>
    <meta content="width=device-width, initial-scale=1.0" name="viewport"/>
    <title>Pendientes - UPEMOR</title>
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
                    },
                    fontFamily: { "display": ["Inter", "sans-serif"] },
                },
            },
        }
    </script>
    <style>.material-symbols-outlined { font-variation-settings: 'FILL' 0, 'wght' 400, 'GRAD' 0, 'opsz' 24; }</style>
</head>
<body class="font-display bg-slate-50 text-slate-800">
    <div class="p-6 lg:p-8">
        <div class="mb-8">
            <h1 class="text-3xl font-bold text-upemor-purple">Tareas Pendientes</h1>
            <p class="text-slate-600 mt-2">Sube tus archivos para completar las actividades.</p>
        </div>
        
        <?php if(isset($_SESSION['success'])): ?>
        <div class="mb-6 p-4 bg-green-100 text-green-800 rounded-lg flex items-center gap-2">
            <span class="material-symbols-outlined">check_circle</span>
            <?php echo $_SESSION['success']; unset($_SESSION['success']); ?>
        </div>
        <?php endif; ?>

        <?php if(isset($_SESSION['error'])): ?>
        <div class="mb-6 p-4 bg-red-100 text-red-800 rounded-lg flex items-center gap-2">
            <span class="material-symbols-outlined">error</span>
            <?php echo $_SESSION['error']; unset($_SESSION['error']); ?>
        </div>
        <?php endif; ?>

        <div class="rounded-2xl bg-white p-6 shadow-lg">
            <?php if($asignaciones->num_rows > 0): ?>
            <div class="space-y-4">
                <?php while($asig = $asignaciones->fetch_assoc()): 
                    $hoy = new DateTime();
                    $fechaLimite = new DateTime($asig['FechaLimite']);
                    // Ajustamos la hora límite al final del día para evitar falsos vencidos
                    $fechaLimite->setTime(23, 59, 59);
                    
                    $diff = $hoy->diff($fechaLimite);
                    // Si la fecha límite es menor a hoy (y ya pasó la hora), es negativo
                    $esVencida = $fechaLimite < $hoy;
                    $diasRestantes = $diff->days;
                ?>
                <div class="border-l-4 border-yellow-500 bg-slate-50 p-5 rounded-r-lg hover:shadow-md transition">
                    <div class="flex justify-between items-start gap-4">
                        <div class="flex-1">
                            <h4 class="font-bold text-lg text-upemor-purple"><?php echo htmlspecialchars($asig['NombreEvidencia']); ?></h4>
                            <p class="text-sm text-slate-600 mt-1"><?php echo htmlspecialchars($asig['Descripcion']); ?></p>
                            
                            <div class="mt-3 flex gap-4 text-xs text-slate-500">
                                <span class="flex items-center gap-1">
                                    <span class="material-symbols-outlined text-sm">person</span>
                                    Docente: <?php echo htmlspecialchars($asig['NombreDocente']); ?>
                                </span>
                                <span class="flex items-center gap-1">
                                    <span class="material-symbols-outlined text-sm">school</span>
                                    Competencia: <?php echo htmlspecialchars($asig['NombreCompetencia']); ?>
                                </span>
                            </div>

                            <div class="mt-3 inline-flex items-center gap-2 px-3 py-1 rounded-full text-xs font-bold bg-white border border-slate-200">
                                <span class="material-symbols-outlined text-sm text-slate-500">calendar_today</span>
                                <?php if($esVencida): ?>
                                    <span class="text-red-600">¡Vencida hace <?php echo $diasRestantes; ?> días!</span>
                                <?php else: ?>
                                    <span class="text-yellow-600">Quedan <?php echo $diasRestantes; ?> días para entregar</span>
                                <?php endif; ?>
                            </div>
                        </div>
                        
                        <div>
                            <button onclick="abrirModal(
                                <?php echo $asig['idAsignacion']; ?>, 
                                '<?php echo htmlspecialchars(addslashes($asig['NombreEvidencia'])); ?>',
                                <?php echo $asig['idCompetencias']; ?>
                            )" class="flex items-center gap-2 bg-upemor-orange hover:bg-upemor-red text-white px-4 py-2 rounded-lg font-bold transition shadow-md">
                                <span class="material-symbols-outlined">upload_file</span>
                                Subir Evidencia
                            </button>
                        </div>
                    </div>
                </div>
                <?php endwhile; ?>
            </div>
            <?php else: ?>
            <div class="text-center py-12 text-slate-500">
                <span class="material-symbols-outlined text-6xl mb-2">assignment_turned_in</span>
                <p>¡Felicidades! No tienes evidencias pendientes por entregar.</p>
            </div>
            <?php endif; ?>
        </div>
    </div>

    <div id="modalSubir" class="hidden fixed inset-0 bg-black/60 flex items-center justify-center z-50 p-4 backdrop-blur-sm">
        <div class="bg-white rounded-2xl shadow-2xl max-w-md w-full transform transition-all scale-100">
            <div class="bg-gradient-to-r from-upemor-orange to-upemor-red p-6 rounded-t-2xl">
                <div class="flex justify-between items-center text-white">
                    <h3 class="text-xl font-bold">Entregar Evidencia</h3>
                    <button onclick="cerrarModal()" class="hover:text-slate-200 transition"><span class="material-symbols-outlined">close</span></button>
                </div>
                <p id="nombreTareaModal" class="text-orange-100 text-sm mt-1 truncate"></p>
            </div>
            
            <form action="index.php?controller=estudiante&action=subirEvidencia" method="POST" enctype="multipart/form-data" class="p-6">
                <input type="hidden" name="idAsignacion" id="idAsignacionInput">
                <input type="hidden" name="idCompetencia" id="idCompetenciaInput">
                
                <div class="space-y-4">
                    <div class="border-2 border-dashed border-slate-300 rounded-xl p-6 text-center hover:bg-slate-50 transition cursor-pointer relative">
                        <input type="file" name="archivo" required class="absolute inset-0 w-full h-full opacity-0 cursor-pointer" onchange="mostrarNombreArchivo(this)">
                        <span class="material-symbols-outlined text-4xl text-slate-400 mb-2">cloud_upload</span>
                        <p class="text-sm font-medium text-slate-600">Haz clic o arrastra tu archivo aquí</p>
                        <p id="nombreArchivoSeleccionado" class="text-xs text-upemor-purple mt-2 font-bold"></p>
                    </div>
                    <p class="text-xs text-slate-400 text-center">PDF, Word, Excel, Imágenes (Máx 10MB)</p>
                </div>

                <div class="flex justify-end gap-3 mt-6">
                    <button type="button" onclick="cerrarModal()" class="px-4 py-2 text-slate-600 hover:bg-slate-100 rounded-lg font-medium transition">Cancelar</button>
                    <button type="submit" name="subir" class="px-6 py-2 bg-upemor-purple hover:bg-purple-900 text-white rounded-lg font-bold shadow-lg transition flex items-center gap-2">
                        <span class="material-symbols-outlined text-sm">send</span>
                        Entregar Tarea
                    </button>
                </div>
            </form>
        </div>
    </div>

    <script>
        function abrirModal(id, nombre, competencia) {
            document.getElementById('idAsignacionInput').value = id;
            document.getElementById('idCompetenciaInput').value = competencia;
            document.getElementById('nombreTareaModal').textContent = nombre;
            document.getElementById('modalSubir').classList.remove('hidden');
        }

        function cerrarModal() {
            document.getElementById('modalSubir').classList.add('hidden');
            document.getElementById('nombreArchivoSeleccionado').textContent = '';
        }

        function mostrarNombreArchivo(input) {
            if(input.files && input.files[0]) {
                document.getElementById('nombreArchivoSeleccionado').textContent = input.files[0].name;
            }
        }
    </script>
</body>
</html>