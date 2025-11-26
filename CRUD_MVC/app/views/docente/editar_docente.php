<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="utf-8"/>
    <title>Editar Docente</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@400;600;700&display=swap" rel="stylesheet"/>
</head>
<body class="bg-slate-100 font-sans p-8">
    <div class="max-w-4xl mx-auto bg-white rounded-2xl shadow-xl p-8">
        <h2 class="text-2xl font-bold text-purple-900 mb-6 border-b pb-2">Editar Información del Docente</h2>
        
        <form action="index.php?controller=docente&action=editarDocente" method="POST" class="space-y-6">
            <input type="hidden" name="id" value="<?php echo $docente['idDocentes']; ?>">
            
            <h3 class="text-lg font-semibold text-orange-500">Datos Personales</h3>
            <div class="grid md:grid-cols-2 gap-4">
                <div>
                    <label class="block text-sm font-bold text-slate-700">Nombre</label>
                    <input type="text" name="Nombre" value="<?php echo htmlspecialchars($docente['Nombre']); ?>" required class="w-full border rounded-lg p-2">
                </div>
                <div>
                    <label class="block text-sm font-bold text-slate-700">Apellido</label>
                    <input type="text" name="Apellido" value="<?php echo htmlspecialchars($docente['Apellido']); ?>" required class="w-full border rounded-lg p-2">
                </div>
                <div>
                    <label class="block text-sm font-bold text-slate-700">Correo</label>
                    <input type="email" name="Correo" value="<?php echo htmlspecialchars($docente['Correo']); ?>" required class="w-full border rounded-lg p-2">
                </div>
                <div>
                    <label class="block text-sm font-bold text-slate-700">Teléfono</label>
                    <input type="text" name="Telefono" value="<?php echo htmlspecialchars($docente['Telefono']); ?>" class="w-full border rounded-lg p-2">
                </div>
                <div>
                    <label class="block text-sm font-bold text-slate-700">Fecha Nacimiento</label>
                    <input type="date" name="FechaNac" value="<?php echo htmlspecialchars($docente['FechaNac']); ?>" class="w-full border rounded-lg p-2">
                </div>
                <div>
                    <label class="block text-sm font-bold text-slate-700">Sexo</label>
                    <select name="Sexo" class="w-full border rounded-lg p-2">
                        <option value="Masculino" <?php echo $docente['Sexo'] == 'Masculino' ? 'selected' : ''; ?>>Masculino</option>
                        <option value="Femenino" <?php echo $docente['Sexo'] == 'Femenino' ? 'selected' : ''; ?>>Femenino</option>
                    </select>
                </div>
            </div>

            <h3 class="text-lg font-semibold text-orange-500 pt-4">Datos Académicos</h3>
            <div class="grid md:grid-cols-2 gap-4">
                <div>
                    <label class="block text-sm font-bold text-slate-700">Especialidad</label>
                    <input type="text" name="Especialidad" value="<?php echo htmlspecialchars($docente['Especialidad']); ?>" required class="w-full border rounded-lg p-2">
                </div>
                <div>
                    <label class="block text-sm font-bold text-slate-700">Materias Impartidas</label>
                    <input type="text" name="MateriaImpartida" value="<?php echo htmlspecialchars($docente['MateriaImpartida']); ?>" required class="w-full border rounded-lg p-2">
                </div>
            </div>

            <div class="flex justify-end gap-4 pt-6">
                <a href="index.php?controller=docente&action=gestionarDocentes" class="px-6 py-2 border rounded-lg text-slate-600 hover:bg-slate-50">Cancelar</a>
                <button type="submit" name="editar" class="px-6 py-2 bg-purple-900 text-white rounded-lg hover:bg-purple-800 font-bold">Guardar Cambios</button>
            </div>
        </form>
    </div>
</body>
</html>