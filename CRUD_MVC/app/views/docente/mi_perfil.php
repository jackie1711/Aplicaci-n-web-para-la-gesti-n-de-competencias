<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Mi Perfil - Portal UPEMOR</title>
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
                    fontFamily: {
                        "display": ["Inter", "sans-serif"]
                    },
                },
            },
        }
    </script>
</head>
<body class="font-display bg-gradient-to-br from-slate-50 to-slate-100">
    <div class="min-h-screen p-6 lg:p-8">
        <!-- Breadcrumb -->
        <div class="mb-6">
            <a href="index.php?controller=auth&action=panelDocente" class="text-sm text-slate-600 hover:text-upemor-orange flex items-center gap-2">
                <span class="material-symbols-outlined text-lg">arrow_back</span>
                Volver al Dashboard
            </a>
        </div>

        <!-- Mensajes de éxito/error -->
        <?php if(isset($_SESSION['mensaje'])): ?>
            <div class="mb-6 p-4 bg-green-100 border border-green-400 text-green-700 rounded-lg flex items-center gap-3">
                <span class="material-symbols-outlined">check_circle</span>
                <span><?php echo $_SESSION['mensaje']; unset($_SESSION['mensaje']); ?></span>
            </div>
        <?php endif; ?>

        <?php if(isset($_SESSION['error'])): ?>
            <div class="mb-6 p-4 bg-red-100 border border-red-400 text-red-700 rounded-lg flex items-center gap-3">
                <span class="material-symbols-outlined">error</span>
                <span><?php echo $_SESSION['error']; unset($_SESSION['error']); ?></span>
            </div>
        <?php endif; ?>

        <div class="max-w-4xl mx-auto">
            <!-- Header -->
            <div class="bg-white rounded-2xl shadow-lg p-8 mb-6">
                <div class="flex items-center gap-6">
                    <div class="h-24 w-24 rounded-full bg-gradient-to-br from-upemor-purple to-purple-900 flex items-center justify-center text-white">
                        <span class="material-symbols-outlined text-5xl">person</span>
                    </div>
                    <div>
                        <h1 class="text-3xl font-bold text-upemor-purple">Mi Perfil</h1>
                        <p class="text-lg text-slate-600 mt-1">
                            <?php echo htmlspecialchars($docente['Nombre'] . ' ' . $docente['Apellido']); ?>
                        </p>
                        <span class="inline-block mt-2 px-3 py-1 bg-upemor-orange/10 text-upemor-orange text-sm font-semibold rounded-full">
                            Docente
                        </span>
                    </div>
                </div>
            </div>

            <!-- Tabs -->
            <div class="bg-white rounded-2xl shadow-lg overflow-hidden">
                <div class="border-b border-slate-200">
                    <nav class="flex">
                        <button onclick="showTab('info')" id="tab-info" class="tab-button active flex-1 px-6 py-4 text-center font-semibold text-upemor-purple border-b-2 border-upemor-orange hover:bg-slate-50 transition">
                            <span class="material-symbols-outlined align-middle mr-2">info</span>
                            Información Personal
                        </button>
                        <button onclick="showTab('edit')" id="tab-edit" class="tab-button flex-1 px-6 py-4 text-center font-semibold text-slate-600 border-b-2 border-transparent hover:bg-slate-50 hover:text-upemor-purple transition">
                            <span class="material-symbols-outlined align-middle mr-2">edit</span>
                            Editar Información
                        </button>
                    </nav>
                </div>

                <!-- Tab Content: Información Personal -->
                <div id="content-info" class="tab-content p-8">
                    <h2 class="text-2xl font-bold text-upemor-purple mb-6 flex items-center gap-2">
                        <span class="material-symbols-outlined">badge</span>
                        Datos Personales
                    </h2>
                    
                    <div class="grid md:grid-cols-2 gap-6">
                        <div class="space-y-2">
                            <label class="text-sm font-semibold text-slate-600">Nombre Completo</label>
                            <p class="text-lg font-medium text-upemor-purple">
                                <?php echo htmlspecialchars($docente['Nombre'] . ' ' . $docente['Apellido']); ?>
                            </p>
                        </div>

                        <div class="space-y-2">
                            <label class="text-sm font-semibold text-slate-600">Correo Electrónico</label>
                            <p class="text-lg font-medium text-upemor-purple flex items-center gap-2">
                                <span class="material-symbols-outlined text-upemor-orange">email</span>
                                <?php echo htmlspecialchars($docente['Correo']); ?>
                            </p>
                        </div>

                        <div class="space-y-2">
                            <label class="text-sm font-semibold text-slate-600">Teléfono</label>
                            <p class="text-lg font-medium text-upemor-purple flex items-center gap-2">
                                <span class="material-symbols-outlined text-upemor-orange">phone</span>
                                <?php echo htmlspecialchars($docente['Telefono'] ?? 'No especificado'); ?>
                            </p>
                        </div>

                        <div class="space-y-2">
                            <label class="text-sm font-semibold text-slate-600">Sexo</label>
                            <p class="text-lg font-medium text-upemor-purple">
                                <?php echo htmlspecialchars($docente['Sexo'] ?? 'No especificado'); ?>
                            </p>
                        </div>

                        <div class="space-y-2">
                            <label class="text-sm font-semibold text-slate-600">Fecha de Nacimiento</label>
                            <p class="text-lg font-medium text-upemor-purple flex items-center gap-2">
                                <span class="material-symbols-outlined text-upemor-orange">cake</span>
                                <?php 
                                if($docente['FechaNac']){
                                    echo date('d/m/Y', strtotime($docente['FechaNac']));
                                } else {
                                    echo 'No especificada';
                                }
                                ?>
                            </p>
                        </div>
                    </div>

                    <hr class="my-8 border-slate-200">

                    <h2 class="text-2xl font-bold text-upemor-purple mb-6 flex items-center gap-2">
                        <span class="material-symbols-outlined">school</span>
                        Información Académica
                    </h2>

                    <div class="grid md:grid-cols-2 gap-6">
                        <div class="space-y-2">
                            <label class="text-sm font-semibold text-slate-600">Especialidad</label>
                            <p class="text-lg font-medium text-upemor-purple">
                                <?php echo htmlspecialchars($docente['Especialidad']); ?>
                            </p>
                        </div>

                        <div class="space-y-2">
                            <label class="text-sm font-semibold text-slate-600">Materia Impartida</label>
                            <p class="text-lg font-medium text-upemor-purple">
                                <?php echo htmlspecialchars($docente['MateriaImpartida']); ?>
                            </p>
                        </div>
                    </div>
                </div>

                <!-- Tab Content: Editar Información -->
                <div id="content-edit" class="tab-content hidden p-8">
                    <h2 class="text-2xl font-bold text-upemor-purple mb-6 flex items-center gap-2">
                        <span class="material-symbols-outlined">edit_note</span>
                        Editar Información Académica
                    </h2>

                    <form action="index.php?controller=docente&action=actualizarMiPerfil" method="POST" class="space-y-6">
                        <div class="grid md:grid-cols-2 gap-6">
                            <div class="space-y-2">
                                <label for="Especialidad" class="block text-sm font-semibold text-slate-700">
                                    Especialidad <span class="text-red-500">*</span>
                                </label>
                                <input 
                                    type="text" 
                                    id="Especialidad"
                                    name="Especialidad" 
                                    value="<?php echo htmlspecialchars($docente['Especialidad']); ?>" 
                                    required
                                    class="w-full px-4 py-3 border border-slate-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-upemor-orange focus:border-transparent"
                                    placeholder="Ej: Ingeniería de Software">
                            </div>

                            <div class="space-y-2">
                                <label for="MateriaImpartida" class="block text-sm font-semibold text-slate-700">
                                    Materia Impartida <span class="text-red-500">*</span>
                                </label>
                                <input 
                                    type="text" 
                                    id="MateriaImpartida"
                                    name="MateriaImpartida" 
                                    value="<?php echo htmlspecialchars($docente['MateriaImpartida']); ?>" 
                                    required
                                    class="w-full px-4 py-3 border border-slate-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-upemor-orange focus:border-transparent"
                                    placeholder="Ej: Programación, Bases de Datos">
                            </div>
                        </div>

                        <div class="bg-blue-50 border border-blue-200 rounded-lg p-4 flex items-start gap-3">
                            <span class="material-symbols-outlined text-blue-600">info</span>
                            <div>
                                <p class="text-sm text-blue-800 font-medium">Nota importante</p>
                                <p class="text-sm text-blue-700 mt-1">
                                    Para editar tu información personal (nombre, correo, teléfono, etc.), 
                                    contacta al administrador del sistema.
                                </p>
                            </div>
                        </div>

                        <div class="flex gap-4 pt-4">
                            <button 
                                type="submit" 
                                name="actualizar_perfil"
                                class="flex-1 bg-gradient-to-r from-upemor-orange to-upemor-red hover:from-upemor-red hover:to-upemor-orange text-white font-semibold py-3 px-6 rounded-lg shadow-lg transition flex items-center justify-center gap-2">
                                <span class="material-symbols-outlined">save</span>
                                Guardar Cambios
                            </button>
                            
                            <button 
                                type="button"
                                onclick="showTab('info')"
                                class="flex-1 bg-slate-200 hover:bg-slate-300 text-slate-700 font-semibold py-3 px-6 rounded-lg transition flex items-center justify-center gap-2">
                                <span class="material-symbols-outlined">cancel</span>
                                Cancelar
                            </button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>

    <script>
        function showTab(tabName) {
            // Ocultar todos los contenidos
            document.querySelectorAll('.tab-content').forEach(content => {
                content.classList.add('hidden');
            });
            
            // Remover active de todos los botones
            document.querySelectorAll('.tab-button').forEach(button => {
                button.classList.remove('active', 'text-upemor-purple', 'border-upemor-orange');
                button.classList.add('text-slate-600', 'border-transparent');
            });
            
            // Mostrar contenido seleccionado
            document.getElementById('content-' + tabName).classList.remove('hidden');
            
            // Activar botón seleccionado
            const activeButton = document.getElementById('tab-' + tabName);
            activeButton.classList.add('active', 'text-upemor-purple', 'border-upemor-orange');
            activeButton.classList.remove('text-slate-600', 'border-transparent');
        }
    </script>
</body>
</html>
