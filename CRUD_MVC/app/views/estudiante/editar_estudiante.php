<?php
// Calcular límites de fecha para 17 a 90 años
$fechaMinima = date('Y-m-d', strtotime('-90 years')); // Hace 90 años (ej. 1934)
$fechaMaxima = date('Y-m-d', strtotime('-17 years')); // Hace 17 años (ej. 2007)
?>

<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Editar Estudiante - Portal UPEMOR</title>
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
            <a href="index.php?controller=estudiante&action=gestionarEstudiantes" class="text-sm text-slate-600 hover:text-upemor-orange flex items-center gap-2">
                <span class="material-symbols-outlined text-lg">arrow_back</span>
                Volver a Gestión de Estudiantes
            </a>
        </div>

        <div class="max-w-4xl mx-auto">
            <!-- Header -->
            <div class="bg-white rounded-2xl shadow-lg p-8 mb-6">
                <div class="flex items-center gap-6">
                    <div class="h-20 w-20 rounded-full bg-gradient-to-br from-upemor-purple to-purple-900 flex items-center justify-center text-white">
                        <span class="material-symbols-outlined text-4xl">edit</span>
                    </div>
                    <div>
                        <h1 class="text-3xl font-bold text-upemor-purple">Editar Estudiante</h1>
                        <p class="text-lg text-slate-600 mt-1">
                            <?php echo htmlspecialchars($estudiante['Nombre'] . ' ' . $estudiante['Apellido']); ?>
                        </p>
                        <span class="inline-block mt-2 px-3 py-1 bg-blue-100 text-blue-800 text-sm font-semibold rounded-full">
                            Matrícula: <?php echo htmlspecialchars($estudiante['Matricula']); ?>
                        </span>
                    </div>
                </div>
            </div>

            <!-- Formulario -->
            <div class="bg-white rounded-2xl shadow-lg p-8">
                <form action="index.php?controller=estudiante&action=editarEstudiante" method="POST" class="space-y-6">
                    <input type="hidden" name="id" value="<?php echo $estudiante['idEstudiantes']; ?>">

                    <h2 class="text-2xl font-bold text-upemor-purple mb-6 flex items-center gap-2">
                        <span class="material-symbols-outlined">badge</span>
                        Información Personal
                    </h2>

                    <div class="grid md:grid-cols-2 gap-6">
                        <div class="space-y-2">
                            <label for="Nombre" class="block text-sm font-semibold text-slate-700">
                                Nombre <span class="text-red-500">*</span>
                            </label>
                            <input 
                                type="text" 
                                id="Nombre"
                                name="Nombre" 
                                value="<?php echo htmlspecialchars($estudiante['Nombre']); ?>" 
                                required
                                class="w-full px-4 py-3 border border-slate-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-upemor-orange focus:border-transparent"
                                placeholder="Nombre del estudiante">
                        </div>

                        <div class="space-y-2">
                            <label for="Apellido" class="block text-sm font-semibold text-slate-700">
                                Apellido <span class="text-red-500">*</span>
                            </label>
                            <input 
                                type="text" 
                                id="Apellido"
                                name="Apellido" 
                                value="<?php echo htmlspecialchars($estudiante['Apellido']); ?>" 
                                required
                                class="w-full px-4 py-3 border border-slate-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-upemor-orange focus:border-transparent"
                                placeholder="Apellido del estudiante">
                        </div>

                        <div class="space-y-2">
                            <label for="Correo" class="block text-sm font-semibold text-slate-700">
                                Correo Electrónico <span class="text-red-500">*</span>
                            </label>
                            <input 
                                type="email" 
                                id="Correo"
                                name="Correo" 
                                value="<?php echo htmlspecialchars($estudiante['Correo']); ?>" 
                                required
                                class="w-full px-4 py-3 border border-slate-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-upemor-orange focus:border-transparent"
                                placeholder="correo@upemor.edu.mx">
                        </div>

                        <div class="space-y-2">
                            <label for="Telefono" class="block text-sm font-semibold text-slate-700">
                                Teléfono <span class="text-red-500">*</span>
                            </label>
                            <input 
                                type="text" 
                                id="Telefono"
                                name="Telefono" 
                                value="<?php echo htmlspecialchars($estudiante['Telefono']); ?>" 
                                required
                                class="w-full px-4 py-3 border border-slate-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-upemor-orange focus:border-transparent"
                                placeholder="777-123-4567">
                        </div>

                        <div class="space-y-2">
                            <label for="Sexo" class="block text-sm font-semibold text-slate-700">
                                Sexo <span class="text-red-500">*</span>
                            </label>
                            <select 
                                id="Sexo"
                                name="Sexo" 
                                required
                                class="w-full px-4 py-3 border border-slate-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-upemor-orange focus:border-transparent">
                                <option value="">Seleccionar...</option>
                                <option value="Masculino" <?php echo ($estudiante['Sexo'] == 'Masculino') ? 'selected' : ''; ?>>Masculino</option>
                                <option value="Femenino" <?php echo ($estudiante['Sexo'] == 'Femenino') ? 'selected' : ''; ?>>Femenino</option>
                            </select>
                        </div>

                        <div class="space-y-2">
                            <label for="FechaNac" required min="<?php echo $fechaMinima; ?>" max="<?php echo $fechaMaxima; ?>" class="block text-sm font-semibold text-slate-700">
                                Fecha de Nacimiento <span class="text-red-500">*</span>
                            </label>
                            <input 
                                type="date" 
                                id="FechaNac"
                                name="FechaNac" 
                                value="<?php echo htmlspecialchars($estudiante['FechaNac']); ?>" 
                                required
                                class="w-full px-4 py-3 border border-slate-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-upemor-orange focus:border-transparent">
                        </div>
                    </div>

                    <hr class="my-8 border-slate-200">

                    <h2 class="text-2xl font-bold text-upemor-purple mb-6 flex items-center gap-2">
                        <span class="material-symbols-outlined">school</span>
                        Información Académica
                    </h2>

                    <div class="grid md:grid-cols-2 gap-6">
                        <div class="space-y-2">
                            <label for="Matricula" class="block text-sm font-semibold text-slate-700">
                                Matrícula <span class="text-red-500">*</span>
                            </label>
                            <input 
                                type="text" 
                                id="Matricula"
                                name="Matricula" 
                                value="<?php echo htmlspecialchars($estudiante['Matricula']); ?>" 
                                required
                                class="w-full px-4 py-3 border border-slate-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-upemor-orange focus:border-transparent"
                                placeholder="VEJO230305">
                        </div>

                        <div class="space-y-2">
                            <label for="Grupo" class="block text-sm font-semibold text-slate-700">
                                Grupo <span class="text-red-500">*</span>
                            </label>
                            <input 
                                type="text" 
                                id="Grupo"
                                name="Grupo" 
                                value="<?php echo htmlspecialchars($estudiante['Grupo']); ?>" 
                                required
                                class="w-full px-4 py-3 border border-slate-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-upemor-orange focus:border-transparent"
                                placeholder="A, B, C...">
                        </div>

                        <div class="space-y-2">
                            <label for="EstadoAcademico" class="block text-sm font-semibold text-slate-700">
                                Estado Académico <span class="text-red-500">*</span>
                            </label>
                            <select 
                                id="EstadoAcademico"
                                name="EstadoAcademico" 
                                required
                                class="w-full px-4 py-3 border border-slate-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-upemor-orange focus:border-transparent">
                                <option value="">Seleccionar...</option>
                                <option value="Activo" <?php echo ($estudiante['EstadoAcademico'] == 'Activo') ? 'selected' : ''; ?>>Activo</option>
                                <option value="Inactivo" <?php echo ($estudiante['EstadoAcademico'] == 'Inactivo') ? 'selected' : ''; ?>>Inactivo</option>
                            </select>
                        </div>
                    </div>

                    <div class="bg-blue-50 border border-blue-200 rounded-lg p-4 flex items-start gap-3 mt-6">
                        <span class="material-symbols-outlined text-blue-600">info</span>
                        <div>
                            <p class="text-sm text-blue-800 font-medium">Información importante</p>
                            <p class="text-sm text-blue-700 mt-1">
                                Todos los campos marcados con <span class="text-red-500">*</span> son obligatorios. 
                                Verifica que la información sea correcta antes de guardar.
                            </p>
                        </div>
                    </div>

                    <div class="flex gap-4 pt-4">
                        <button 
                            type="submit" 
                            name="editar"
                            class="flex-1 bg-gradient-to-r from-upemor-orange to-upemor-red hover:from-upemor-red hover:to-upemor-orange text-white font-semibold py-3 px-6 rounded-lg shadow-lg transition flex items-center justify-center gap-2">
                            <span class="material-symbols-outlined">save</span>
                            Guardar Cambios
                        </button>
                        
                        <a 
                            href="index.php?controller=estudiante&action=gestionarEstudiantes"
                            class="flex-1 bg-slate-200 hover:bg-slate-300 text-slate-700 font-semibold py-3 px-6 rounded-lg transition flex items-center justify-center gap-2">
                            <span class="material-symbols-outlined">cancel</span>
                            Cancelar
                        </a>
                    </div>
                </form>
            </div>
        </div>
    </div>
</body>
</html>