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
    <title>Mis Evidencias - UPEMOR</title>
    <script src="https://cdn.tailwindcss.com?plugins=forms,container-queries"></script>
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@400;500;600;700;900&display=swap" rel="stylesheet"/>
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
        <div class="max-w-7xl mx-auto">
            <!-- Header -->
            <div class="mb-8">
                <a href="index.php?controller=auth&action=panelEstudiante" class="inline-flex items-center gap-2 text-upemor-purple hover:text-upemor-orange transition mb-4">
                    <span class="material-symbols-outlined">arrow_back</span>
                    <span class="font-medium">Volver al Dashboard</span>
                </a>
                <div class="flex items-center justify-between">
                    <div>
                        <h1 class="text-3xl font-bold text-upemor-purple">Mis Evidencias</h1>
                        <p class="text-slate-600 mt-2">Gestiona tus evidencias de competencias</p>
                    </div>
                    <a href="index.php?controller=evidencia&action=mostrarFormulario" 
                       class="px-6 py-3 bg-gradient-to-r from-upemor-orange to-upemor-red hover:from-upemor-red hover:to-upemor-orange text-white font-bold rounded-xl transition shadow-lg flex items-center gap-2">
                        <span class="material-symbols-outlined">add</span>
                        Nueva Evidencia
                    </a>
                </div>
            </div>

            <!-- Mensajes de éxito/error -->
            <?php if(isset($_SESSION['success'])): ?>
            <div class="mb-6 flex items-center gap-3 bg-green-50 border-l-4 border-green-500 p-4 rounded-r-xl">
                <span class="material-symbols-outlined text-green-600">check_circle</span>
                <p class="text-green-800 font-medium"><?php echo $_SESSION['success']; unset($_SESSION['success']); ?></p>
            </div>
            <?php endif; ?>

            <?php if(isset($_SESSION['error'])): ?>
            <div class="mb-6 flex items-center gap-3 bg-red-50 border-l-4 border-red-500 p-4 rounded-r-xl">
                <span class="material-symbols-outlined text-red-600">error</span>
                <p class="text-red-800 font-medium"><?php echo $_SESSION['error']; unset($_SESSION['error']); ?></p>
            </div>
            <?php endif; ?>

            <!-- Lista de Evidencias -->
            <?php if(isset($evidencias) && $evidencias->num_rows > 0): ?>
                <div class="grid gap-6 md:grid-cols-2 lg:grid-cols-3">
                    <?php while($evidencia = $evidencias->fetch_assoc()): ?>
                    <div class="bg-white rounded-2xl shadow-lg overflow-hidden hover:shadow-xl transition">
                        <!-- Header de la tarjeta -->
                        <div class="bg-gradient-to-r from-upemor-purple to-purple-900 p-4">
                            <div class="flex items-start gap-3">
                                <div class="bg-white/20 p-2 rounded-lg">
                                    <span class="material-symbols-outlined text-white !text-2xl">
                                        <?php 
                                        $ext = strtolower(pathinfo($evidencia['NombreArchivo'], PATHINFO_EXTENSION));
                                        if(in_array($ext, ['pdf'])) echo 'picture_as_pdf';
                                        elseif(in_array($ext, ['doc', 'docx'])) echo 'description';
                                        elseif(in_array($ext, ['xls', 'xlsx'])) echo 'table_chart';
                                        elseif(in_array($ext, ['jpg', 'jpeg', 'png'])) echo 'image';
                                        else echo 'insert_drive_file';
                                        ?>
                                    </span>
                                </div>
                                <div class="flex-1 min-w-0">
                                    <h3 class="text-white font-bold text-sm truncate"><?php echo htmlspecialchars($evidencia['NombreArchivo']); ?></h3>
                                    <p class="text-purple-200 text-xs mt-1"><?php echo htmlspecialchars($evidencia['NombreCompetencia']); ?></p>
                                </div>
                            </div>
                        </div>

                        <!-- Contenido de la tarjeta -->
                        <div class="p-4 space-y-3">
                            <!-- Estado -->
                            <div class="flex items-center gap-2">
                                <span class="material-symbols-outlined text-slate-400 !text-lg">info</span>
                                <span class="text-sm text-slate-600">Estado:</span>
                                <span class="px-3 py-1 rounded-full text-xs font-bold 
                                    <?php 
                                    if($evidencia['Estado'] == 'Aprobada') echo 'bg-green-100 text-green-700';
                                    elseif($evidencia['Estado'] == 'Rechazada') echo 'bg-red-100 text-red-700';
                                    else echo 'bg-yellow-100 text-yellow-700';
                                    ?>">
                                    <?php echo htmlspecialchars($evidencia['Estado']); ?>
                                </span>
                            </div>

                            <!-- Fecha -->
                            <div class="flex items-center gap-2 text-sm text-slate-600">
                                <span class="material-symbols-outlined text-slate-400 !text-lg">calendar_today</span>
                                <span>Subida: <?php echo date('d/m/Y H:i', strtotime($evidencia['FechaSubida'])); ?></span>
                            </div>

                            <!-- Tamaño -->
                            <div class="flex items-center gap-2 text-sm text-slate-600">
                                <span class="material-symbols-outlined text-slate-400 !text-lg">cloud</span>
                                <span><?php echo number_format($evidencia['TamanoArchivo'] / 1024 / 1024, 2); ?> MB</span>
                            </div>

                            <!-- Observaciones (si existen) -->
                            <?php if(!empty($evidencia['Observaciones'])): ?>
                            <div class="mt-3 p-3 bg-blue-50 rounded-lg">
                                <p class="text-xs font-semibold text-blue-900 mb-1">Observaciones:</p>
                                <p class="text-xs text-blue-800"><?php echo nl2br(htmlspecialchars($evidencia['Observaciones'])); ?></p>
                            </div>
                            <?php endif; ?>
                        </div>

                        <!-- Acciones -->
                        <div class="border-t border-slate-200 p-4 flex gap-2">
                            <a href="index.php?controller=evidencia&action=descargar&id=<?php echo $evidencia['idEvidencias']; ?>" 
                               class="flex-1 px-4 py-2 bg-upemor-orange hover:bg-upemor-red text-white text-sm font-bold rounded-lg transition flex items-center justify-center gap-2">
                                <span class="material-symbols-outlined !text-lg">download</span>
                                Descargar
                            </a>
                            <button onclick="confirmarEliminacion(<?php echo $evidencia['idEvidencias']; ?>)" 
                                    class="px-4 py-2 bg-red-500 hover:bg-red-600 text-white text-sm font-bold rounded-lg transition">
                                <span class="material-symbols-outlined !text-lg">delete</span>
                            </button>
                        </div>
                    </div>
                    <?php endwhile; ?>
                </div>
            <?php else: ?>
                <!-- Sin evidencias -->
                <div class="bg-white rounded-2xl shadow-lg p-12 text-center">
                    <div class="bg-gradient-to-br from-upemor-orange to-upemor-red w-24 h-24 rounded-full flex items-center justify-center mx-auto mb-6">
                        <span class="material-symbols-outlined text-white !text-5xl">folder_open</span>
                    </div>
                    <h3 class="text-2xl font-bold text-upemor-purple mb-3">No tienes evidencias aún</h3>
                    <p class="text-slate-600 mb-6">Comienza a subir tus evidencias de competencias para llevar un registro de tu progreso.</p>
                    <a href="index.php?controller=evidencia&action=mostrarFormulario" 
                       class="inline-flex items-center gap-2 px-6 py-3 bg-gradient-to-r from-upemor-orange to-upemor-red hover:from-upemor-red hover:to-upemor-orange text-white font-bold rounded-xl transition shadow-lg">
                        <span class="material-symbols-outlined">add</span>
                        Subir Primera Evidencia
                    </a>
                </div>
            <?php endif; ?>
        </div>
    </div>

    <script>
        function confirmarEliminacion(id) {
            if (confirm('¿Estás seguro de que deseas eliminar esta evidencia? Esta acción no se puede deshacer.')) {
                window.location.href = 'index.php?controller=evidencia&action=eliminar&id=' + id;
            }
        }
    </script>
</body>
</html>
