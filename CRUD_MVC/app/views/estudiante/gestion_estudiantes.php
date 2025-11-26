<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Gestión de Estudiantes - Portal UPEMOR</title>
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
            <a href="index.php?controller=auth&action=panelAdministrativo" class="text-sm text-slate-600 hover:text-upemor-orange flex items-center gap-2">
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

        <!-- Header -->
        <div class="mb-6">
            <h1 class="text-3xl font-bold text-upemor-purple flex items-center gap-3">
                <span class="material-symbols-outlined text-4xl">school</span>
                Gestión de Estudiantes
            </h1>
            <p class="text-slate-600 mt-2">Administra la información de todos los estudiantes registrados</p>
        </div>

        <!-- Tabla de estudiantes -->
        <div class="bg-white rounded-2xl shadow-lg overflow-hidden">
            <div class="overflow-x-auto">
                <table class="w-full">
                    <thead class="bg-gradient-to-r from-upemor-purple to-purple-900 text-white">
                        <tr>
                            <th class="px-6 py-4 text-left text-sm font-semibold">ID</th>
                            <th class="px-6 py-4 text-left text-sm font-semibold">Matrícula</th>
                            <th class="px-6 py-4 text-left text-sm font-semibold">Nombre Completo</th>
                            <th class="px-6 py-4 text-left text-sm font-semibold">Correo</th>
                            <th class="px-6 py-4 text-left text-sm font-semibold">Grupo</th>
                            <th class="px-6 py-4 text-left text-sm font-semibold">Estado</th>
                            <th class="px-6 py-4 text-center text-sm font-semibold">Acciones</th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-slate-200">
                        <?php 
                        $count = 0;
                        while($row = $estudiantes -> fetch_assoc()){ 
                            $count++;
                            $bgClass = $count % 2 == 0 ? 'bg-slate-50' : 'bg-white';
                            $estadoClass = $row['EstadoAcademico'] == 'Activo' ? 'bg-green-100 text-green-800' : 'bg-red-100 text-red-800';
                        ?>
                        <tr class="<?php echo $bgClass; ?> hover:bg-upemor-orange/5 transition">
                            <td class="px-6 py-4 text-sm font-medium text-upemor-purple">
                                <?php echo $row['idEstudiantes']; ?>
                            </td>
                            <td class="px-6 py-4 text-sm font-bold text-slate-700">
                                <?php echo htmlspecialchars($row['Matricula']); ?>
                            </td>
                            <td class="px-6 py-4 text-sm text-slate-700">
                                <?php echo htmlspecialchars($row['Nombre'] . ' ' . $row['Apellido']); ?>
                            </td>
                            <td class="px-6 py-4 text-sm text-slate-600">
                                <div class="flex items-center gap-2">
                                    <span class="material-symbols-outlined text-upemor-orange text-lg">email</span>
                                    <?php echo htmlspecialchars($row['Correo']); ?>
                                </div>
                            </td>
                            <td class="px-6 py-4 text-sm font-semibold text-upemor-purple">
                                Grupo <?php echo htmlspecialchars($row['Grupo']); ?>
                            </td>
                            <td class="px-6 py-4 text-sm">
                                <span class="px-3 py-1 rounded-full text-xs font-semibold <?php echo $estadoClass; ?>">
                                    <?php echo htmlspecialchars($row['EstadoAcademico']); ?>
                                </span>
                            </td>
                            <td class="px-6 py-4 text-sm">
                                <div class="flex items-center justify-center gap-2">
                                    <a href="index.php?controller=estudiante&action=editarEstudiante&id=<?php echo $row['idEstudiantes']; ?>" 
                                       class="flex items-center gap-1 bg-blue-500 hover:bg-blue-600 text-white px-3 py-2 rounded-lg transition font-medium">
                                        <span class="material-symbols-outlined text-lg">edit</span>
                                        Editar
                                    </a>
                                    <a href="index.php?controller=estudiante&action=eliminarEstudiante&id=<?php echo $row['idEstudiantes']; ?>" 
                                       onclick="return confirm('¿Estás seguro de eliminar a <?php echo htmlspecialchars($row['Nombre'] . ' ' . $row['Apellido']); ?>? Esta acción no se puede deshacer.')"
                                       class="flex items-center gap-1 bg-red-500 hover:bg-red-600 text-white px-3 py-2 rounded-lg transition font-medium">
                                        <span class="material-symbols-outlined text-lg">delete</span>
                                        Eliminar
                                    </a>
                                </div>
                            </td>
                        </tr>
                        <?php } ?>
                        
                        <?php if($count == 0): ?>
                        <tr>
                            <td colspan="7" class="px-6 py-12 text-center">
                                <div class="flex flex-col items-center gap-3">
                                    <span class="material-symbols-outlined text-6xl text-slate-300">person_off</span>
                                    <p class="text-lg font-medium text-slate-500">No hay estudiantes registrados</p>
                                </div>
                            </td>
                        </tr>
                        <?php endif; ?>
                    </tbody>
                </table>
            </div>
        </div>

        <!-- Estadísticas -->
        <div class="mt-6 grid grid-cols-1 md:grid-cols-3 gap-6">
            <div class="bg-white rounded-xl shadow-lg p-6 border-t-4 border-upemor-purple">
                <div class="flex items-center justify-between">
                    <div>
                        <p class="text-sm font-medium text-slate-600">Total Estudiantes</p>
                        <p class="text-3xl font-bold text-upemor-purple mt-2"><?php echo $count; ?></p>
                    </div>
                    <span class="material-symbols-outlined text-5xl text-upemor-purple opacity-20">groups</span>
                </div>
            </div>
            
            <div class="bg-white rounded-xl shadow-lg p-6 border-t-4 border-green-500">
                <div class="flex items-center justify-between">
                    <div>
                        <p class="text-sm font-medium text-slate-600">Activos</p>
                        <p class="text-3xl font-bold text-green-600 mt-2">
                            <?php 
                            mysqli_data_seek($estudiantes, 0);
                            $activos = 0;
                            while($row = $estudiantes->fetch_assoc()) {
                                if($row['EstadoAcademico'] == 'Activo') $activos++;
                            }
                            echo $activos;
                            ?>
                        </p>
                    </div>
                    <span class="material-symbols-outlined text-5xl text-green-500 opacity-20">check_circle</span>
                </div>
            </div>
            
            <div class="bg-white rounded-xl shadow-lg p-6 border-t-4 border-red-500">
                <div class="flex items-center justify-between">
                    <div>
                        <p class="text-sm font-medium text-slate-600">Inactivos</p>
                        <p class="text-3xl font-bold text-red-600 mt-2">
                            <?php echo $count - $activos; ?>
                        </p>
                    </div>
                    <span class="material-symbols-outlined text-5xl text-red-500 opacity-20">cancel</span>
                </div>
            </div>
        </div>
    </div>
</body>
</html>
