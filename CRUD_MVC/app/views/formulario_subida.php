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
    <title>Subir Evidencia - UPEMOR</title>
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
                        "accent-green": "#50E3C2",
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
        #dropZone {
            transition: all 0.3s ease;
        }
        #dropZone.drag-over {
            background: linear-gradient(to right, #F7941D, #ED1C24);
            border-color: #F7941D;
            transform: scale(1.02);
        }
    </style>
</head>
<body class="font-display bg-gradient-to-br from-slate-50 to-slate-100 min-h-screen">
    <div class="p-6 lg:p-8">
        <div class="max-w-4xl mx-auto">
            <!-- Header -->
            <div class="mb-8">
                <a href="index.php?controller=auth&action=panelEstudiante" class="inline-flex items-center gap-2 text-upemor-purple hover:text-upemor-orange transition mb-4">
                    <span class="material-symbols-outlined">arrow_back</span>
                    <span class="font-medium">Volver al Dashboard</span>
                </a>
                <h1 class="text-3xl font-bold text-upemor-purple">Subir Evidencia de Producto</h1>
                <p class="text-slate-600 mt-2">Selecciona una competencia y sube tu archivo de evidencia</p>
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

            <!-- Formulario -->
            <form action="index.php?controller=evidencia&action=subir" method="POST" enctype="multipart/form-data" class="bg-white rounded-2xl shadow-lg overflow-hidden">
                <div class="bg-gradient-to-r from-upemor-purple to-purple-900 text-white p-6">
                    <div class="flex items-center gap-3">
                        <span class="material-symbols-outlined text-4xl">upload_file</span>
                        <div>
                            <h2 class="text-xl font-bold">Nueva Evidencia</h2>
                            <p class="text-sm text-purple-200">Completa el formulario para subir tu evidencia</p>
                        </div>
                    </div>
                </div>

                <div class="p-8 space-y-6">
                    <!-- Seleccionar Competencia -->
                    <div>
                        <label for="idCompetencias" class="block text-base font-semibold text-upemor-purple mb-2">
                            Competencia <span class="text-red-500">*</span>
                        </label>
                        <select 
                            id="idCompetencias"
                            name="idCompetencias" 
                            required
                            class="w-full px-4 py-3 border-2 border-slate-200 rounded-xl text-slate-900 focus:outline-none focus:ring-2 focus:ring-upemor-orange focus:border-upemor-orange transition"
                        >
                            <option value="">Seleccionar competencia</option>
                            <?php 
                            if(isset($competencias) && $competencias->num_rows > 0):
                                while($comp = $competencias->fetch_assoc()): 
                            ?>
                            <option value="<?php echo $comp['idCompetencias']; ?>">
                                <?php echo htmlspecialchars($comp['NombreCompetencia']); ?>
                            </option>
                            <?php endwhile; endif; ?>
                        </select>
                    </div>

                    <!-- Zona de Arrastre de Archivos -->
                    <div>
                        <label class="block text-base font-semibold text-upemor-purple mb-2">
                            Archivo de Evidencia <span class="text-red-500">*</span>
                        </label>
                        <div id="dropZone" class="flex flex-col items-center gap-4 rounded-2xl border-2 border-dashed border-upemor-orange bg-white px-6 py-10 text-center cursor-pointer hover:bg-orange-50 transition">
                            <div class="bg-gradient-to-br from-upemor-orange to-upemor-red/80 p-4 rounded-full">
                                <span class="material-symbols-outlined text-white !text-3xl">cloud_upload</span>
                            </div>
                            <div>
                                <p class="text-upemor-purple text-lg font-bold">Arrastra tu archivo aquí</p>
                                <p class="text-slate-600 text-sm mt-1">o haz clic para seleccionar desde tu computadora</p>
                            </div>
                            <input 
                                type="file" 
                                id="archivo" 
                                name="archivo" 
                                required
                                accept=".pdf,.doc,.docx,.xls,.xlsx,.jpg,.jpeg,.png"
                                class="hidden"
                            />
                            <button type="button" onclick="document.getElementById('archivo').click()" class="px-6 py-2 bg-gradient-to-r from-upemor-orange to-upemor-red hover:from-upemor-red hover:to-upemor-orange text-white text-sm font-bold rounded-lg transition shadow-md">
                                Seleccionar Archivo
                            </button>
                            <p class="text-xs text-slate-500">Formatos: PDF, DOCX, XLSX, JPG, PNG (Máx. 10MB)</p>
                        </div>
                        <div id="fileInfo" class="mt-3 hidden">
                            <div class="bg-green-50 border border-green-200 rounded-lg p-3 flex items-center gap-3">
                                <span class="material-symbols-outlined text-green-600">description</span>
                                <div class="flex-1">
                                    <p id="fileName" class="text-sm font-semibold text-green-800"></p>
                                    <p id="fileSize" class="text-xs text-green-600"></p>
                                </div>
                                <button type="button" onclick="clearFile()" class="text-red-500 hover:text-red-700">
                                    <span class="material-symbols-outlined">close</span>
                                </button>
                            </div>
                        </div>
                    </div>

                    <!-- Info Box -->
                    <div class="bg-blue-50 border-l-4 border-blue-500 p-4 rounded-r-xl">
                        <div class="flex gap-3">
                            <span class="material-symbols-outlined text-blue-600">info</span>
                            <div class="text-sm text-blue-900">
                                <p class="font-semibold mb-1">Importante</p>
                                <ul class="list-disc list-inside space-y-1 text-blue-800">
                                    <li>El archivo debe estar relacionado con la competencia seleccionada</li>
                                    <li>Asegúrate de que el nombre del archivo sea descriptivo</li>
                                    <li>La evidencia quedará en estado "Pendiente" hasta su revisión</li>
                                </ul>
                            </div>
                        </div>
                    </div>

                    <!-- Botones -->
                    <div class="flex gap-3 pt-4 border-t border-slate-200">
                        <a href="index.php?controller=auth&action=panelEstudiante" 
                           class="flex-1 text-center px-6 py-3 border-2 border-slate-300 text-slate-700 font-bold rounded-xl hover:bg-slate-50 transition">
                            Cancelar
                        </a>
                        <button 
                            type="submit"
                            name="subir"
                            class="flex-1 px-6 py-3 bg-gradient-to-r from-upemor-orange to-upemor-red hover:from-upemor-red hover:to-upemor-orange text-white font-bold rounded-xl transition shadow-lg flex items-center justify-center gap-2">
                            <span class="material-symbols-outlined">upload</span>
                            Subir Evidencia
                        </button>
                    </div>
                </div>
            </form>

            <!-- Link a Mis Evidencias -->
            <div class="mt-6 text-center">
                <a href="index.php?controller=evidencia&action=misEvidencias" class="inline-flex items-center gap-2 text-upemor-orange hover:text-upemor-red font-semibold transition">
                    <span class="material-symbols-outlined">folder_open</span>
                    <span>Ver mis evidencias</span>
                </a>
            </div>
        </div>
    </div>

    <script>
        const fileInput = document.getElementById('archivo');
        const dropZone = document.getElementById('dropZone');
        const fileInfo = document.getElementById('fileInfo');
        const fileName = document.getElementById('fileName');
        const fileSize = document.getElementById('fileSize');

        // Mostrar información del archivo seleccionado
        fileInput.addEventListener('change', function(e) {
            const file = e.target.files[0];
            if (file) {
                fileName.textContent = file.name;
                fileSize.textContent = formatFileSize(file.size);
                fileInfo.classList.remove('hidden');
            }
        });

        // Drag and drop
        dropZone.addEventListener('dragover', function(e) {
            e.preventDefault();
            dropZone.classList.add('drag-over');
        });

        dropZone.addEventListener('dragleave', function(e) {
            e.preventDefault();
            dropZone.classList.remove('drag-over');
        });

        dropZone.addEventListener('drop', function(e) {
            e.preventDefault();
            dropZone.classList.remove('drag-over');
            
            const files = e.dataTransfer.files;
            if (files.length > 0) {
                fileInput.files = files;
                const event = new Event('change');
                fileInput.dispatchEvent(event);
            }
        });

        // Limpiar archivo
        function clearFile() {
            fileInput.value = '';
            fileInfo.classList.add('hidden');
        }

        // Formatear tamaño de archivo
        function formatFileSize(bytes) {
            if (bytes === 0) return '0 Bytes';
            const k = 1024;
            const sizes = ['Bytes', 'KB', 'MB', 'GB'];
            const i = Math.floor(Math.log(bytes) / Math.log(k));
            return Math.round(bytes / Math.pow(k, i) * 100) / 100 + ' ' + sizes[i];
        }
    </script>
</body>
</html>