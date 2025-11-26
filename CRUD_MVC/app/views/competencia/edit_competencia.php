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
    <title>Editar Competencia - UPEMOR</title>
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
        <div class="max-w-3xl mx-auto">
            <!-- Header -->
            <div class="mb-8">
                <a href="index.php?controller=competencia&action=consult" class="inline-flex items-center gap-2 text-upemor-purple hover:text-upemor-orange transition mb-4">
                    <span class="material-symbols-outlined">arrow_back</span>
                    <span class="font-medium">Volver a Gestión de Competencias</span>
                </a>
                <h1 class="text-3xl font-bold text-upemor-purple">Editar Competencia</h1>
                <p class="text-slate-600 mt-2">Modificando: <span class="font-semibold text-upemor-orange"><?php echo htmlspecialchars($row['NombreCompetencia']); ?></span></p>
            </div>

            <!-- Form Card -->
            <div class="bg-white rounded-2xl shadow-lg overflow-hidden">
                <div class="bg-gradient-to-r from-upemor-purple to-purple-900 text-white p-6">
                    <div class="flex items-center gap-3">
                        <span class="material-symbols-outlined text-4xl">edit_document</span>
                        <div>
                            <h2 class="text-xl font-bold">Formulario de Edición</h2>
                            <p class="text-sm text-purple-200">Actualiza la información de la competencia</p>
                        </div>
                    </div>
                </div>

                <form action="index.php?controller=competencia&action=update" method="POST" class="p-8 space-y-6">
                    <input type="hidden" name="id" value="<?php echo $row['idCompetencias']; ?>">

                    <!-- Campo Nombre -->
                    <div>
                        <label for="NombreCompetencia" class="block text-base font-semibold text-upemor-purple mb-2">
                            Nombre de la Competencia <span class="text-danger">*</span>
                        </label>
                        <input 
                            type="text" 
                            id="NombreCompetencia"
                            name="NombreCompetencia" 
                            value="<?php echo htmlspecialchars($row['NombreCompetencia']); ?>"
                            required
                            class="w-full px-4 py-3 border-2 border-slate-200 rounded-xl text-slate-900 placeholder:text-slate-400 focus:outline-none focus:ring-2 focus:ring-upemor-orange focus:border-upemor-orange transition"
                        />
                    </div>

                    <!-- Campo Descripción -->
                    <div>
                        <label for="Descripcion" class="block text-base font-semibold text-upemor-purple mb-2">
                            Descripción <span class="text-danger">*</span>
                        </label>
                        <textarea 
                            id="Descripcion"
                            name="Descripcion" 
                            rows="6"
                            required
                            class="w-full px-4 py-3 border-2 border-slate-200 rounded-xl text-slate-900 placeholder:text-slate-400 focus:outline-none focus:ring-2 focus:ring-upemor-orange focus:border-upemor-orange transition resize-none"
                        ><?php echo htmlspecialchars($row['Descripcion']); ?></textarea>
                        <p class="text-xs text-slate-500 mt-1">Define los criterios de evaluación según las tres dimensiones: conocimiento, producto y desempeño</p>
                    </div>

                    <!-- Información adicional -->
                    <div class="bg-blue-50 border-l-4 border-blue-500 p-4 rounded-r-xl">
                        <div class="flex gap-3">
                            <span class="material-symbols-outlined text-blue-600">info</span>
                            <div class="text-sm text-blue-900">
                                <p class="font-semibold mb-1">Importancia de las Competencias</p>
                                <ul class="list-disc list-inside space-y-1 text-blue-800">
                                    <li>Cada competencia debe tener criterios definidos</li>
                                    <li>Se evaluará el conocimiento conceptual y nivel de desempeño</li>
                                    <li>Establece coherencia con el modelo educativo</li>
                                </ul>
                            </div>
                        </div>
                    </div>

                    <!-- Botones de acción -->
                    <div class="flex gap-3 pt-4 border-t border-slate-200">
                        <a href="index.php?controller=competencia&action=consult" 
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
        </div>
    </div>
</body>
</html>