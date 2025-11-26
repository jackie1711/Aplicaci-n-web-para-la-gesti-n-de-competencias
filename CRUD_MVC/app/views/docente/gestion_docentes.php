<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="utf-8"/>
    <title>Gestión de Docentes - UPEMOR</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@400;600;700&display=swap" rel="stylesheet"/>
    <link href="https://fonts.googleapis.com/css2?family=Material+Symbols+Outlined" rel="stylesheet"/>
</head>
<body class="bg-slate-50 font-sans">
    <div class="p-8">
        <div class="mb-6 flex justify-between items-center">
            <div class="flex flex-col gap-1">
                <a href="index.php?controller=auth&action=panelAdministrativo" class="flex items-center text-upemor-purple hover:text-upemor-orange transition font-semibold mb-2">
                    <span class="material-symbols-outlined mr-2">arrow_back</span>
                    Volver al Dashboard
                </a>
                <h1 class="text-3xl font-bold text-purple-900">Gestión de Docentes</h1>
            </div>
            <a href="index.php?controller=auth&action=registroDirecto&tipo=Docente" class="bg-orange-500 hover:bg-orange-600 text-white font-bold py-2 px-4 rounded-lg flex items-center gap-2 transition shadow-md">
                <span class="material-symbols-outlined">person_add</span> Nuevo Docente
            </a>
        </div>

        <?php if(isset($_SESSION['mensaje'])): ?>
            <div class="bg-green-100 border-l-4 border-green-500 text-green-700 p-4 mb-4 flex items-center gap-2">
                <span class="material-symbols-outlined">check_circle</span>
                <?php echo $_SESSION['mensaje']; unset($_SESSION['mensaje']); ?>
            </div>
        <?php endif; ?>
        <?php if(isset($_SESSION['error'])): ?>
            <div class="bg-red-100 border-l-4 border-red-500 text-red-700 p-4 mb-4 flex items-center gap-2">
                <span class="material-symbols-outlined">error</span>
                <?php echo $_SESSION['error']; unset($_SESSION['error']); ?>
            </div>
        <?php endif; ?>

        <div class="bg-white rounded-xl shadow-lg overflow-hidden">
            <table class="w-full text-left border-collapse">
                <thead class="bg-purple-900 text-white">
                    <tr>
                        <th class="p-4 font-semibold uppercase text-sm tracking-wider">Nombre Completo</th>
                        <th class="p-4 font-semibold uppercase text-sm tracking-wider">Correo</th>
                        <th class="p-4 font-semibold uppercase text-sm tracking-wider">Especialidad</th>
                        <th class="p-4 font-semibold uppercase text-sm tracking-wider">Materias</th>
                        <th class="p-4 text-center font-semibold uppercase text-sm tracking-wider">Acciones</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-slate-200">
                    <?php if(isset($docentes) && $docentes): ?>
                        <?php while($doc = $docentes->fetch_assoc()): ?>
                        <tr class="hover:bg-slate-50 transition">
                            <td class="p-4 font-semibold text-slate-700">
                                <?php echo htmlspecialchars($doc['Nombre'] . ' ' . $doc['Apellido']); ?>
                            </td>
                            <td class="p-4 text-slate-600"><?php echo htmlspecialchars($doc['Correo']); ?></td>
                            <td class="p-4 text-purple-700 font-medium"><?php echo htmlspecialchars($doc['Especialidad']); ?></td>
                            <td class="p-4 text-slate-500 text-sm truncate max-w-xs"><?php echo htmlspecialchars($doc['MateriaImpartida']); ?></td>
                            <td class="p-4 text-center flex justify-center gap-2">
                                <a href="index.php?controller=docente&action=editarDocente&id=<?php echo $doc['idDocentes']; ?>" class="bg-blue-100 text-blue-600 p-2 rounded-lg hover:bg-blue-200 transition" title="Editar">
                                    <span class="material-symbols-outlined">edit</span>
                                </a>
                                <a href="index.php?controller=docente&action=eliminarDocente&id=<?php echo $doc['idDocentes']; ?>" onclick="return confirm('¿Eliminar a este docente y su cuenta de usuario?');" class="bg-red-100 text-red-600 p-2 rounded-lg hover:bg-red-200 transition" title="Eliminar">
                                    <span class="material-symbols-outlined">delete</span>
                                </a>
                            </td>
                        </tr>
                        <?php endwhile; ?>
                    <?php else: ?>
                        <tr>
                            <td colspan="5" class="p-12 text-center text-slate-500 flex flex-col items-center">
                                <span class="material-symbols-outlined text-6xl mb-2 text-slate-300">person_off</span>
                                No hay docentes registrados.
                            </td>
                        </tr>
                    <?php endif; ?>
                </tbody>
            </table>
        </div>
    </div>
    
    <script>
      tailwind.config = {
        theme: {
          extend: {
            colors: {
              "upemor-purple": "#4A1E6F",
              "upemor-orange": "#F7941D",
            }
          }
        }
      }
    </script>
</body>
</html>