<?php
$error = isset($_SESSION['error']) ? $_SESSION['error'] : '';
unset($_SESSION['error']);

// Obtener el tipo de usuario de la URL (por defecto Estudiante)
$tipoUsuario = isset($_GET['tipo']) ? $_GET['tipo'] : 'Estudiante';
?>
<!DOCTYPE html>
<html lang="es">
<head>
    <script src="https://kit.fontawesome.com/229ca0cd03.js" crossorigin="anonymous"></script>
    <meta charset="utf-8"/>
    <meta content="width=device-width, initial-scale=1.0" name="viewport"/>
    <title>Registrar <?php echo htmlspecialchars($tipoUsuario); ?> - Portal Upemor</title>
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
              "sans": ["Inter", "sans-serif"]
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
<body class="font-sans bg-gradient-to-br from-slate-50 to-slate-100 min-h-screen">

    <div class="min-h-screen p-8">
        <div class="max-w-4xl mx-auto">
            <div class="flex items-center justify-between mb-8">
                <a href="index.php?controller=auth&action=panelAdministrativo" class="flex items-center text-upemor-purple hover:text-upemor-orange transition font-semibold">
                    <span class="material-symbols-outlined mr-2">arrow_back</span>
                    Volver al Dashboard
                </a>
                <img src="https://gruposat.com.mx/img/clientes/UPEMOR.png" alt="Upemor Logo" class="h-16 w-16 object-contain drop-shadow-2xl"/>
            </div>

            <div class="bg-white rounded-2xl shadow-2xl p-8">
                <div class="mb-8">
                    <h2 class="text-3xl font-bold text-upemor-purple mb-2">Registrar Nuevo Usuario</h2>
                    <p class="text-slate-600">Tipo de usuario: <span class="font-semibold text-upemor-orange"><?php echo htmlspecialchars($tipoUsuario); ?></span></p>
                </div>

                <?php if ($error): ?>
                <div class="mb-6 p-4 bg-red-100 border border-red-400 text-red-700 rounded-xl">
                    <?php echo htmlspecialchars($error); ?>
                </div>
                <?php endif; ?>

                <form action="index.php?controller=auth&action=registro" method="POST" class="space-y-6">
                    <input type="hidden" name="TipoUsuario" value="<?php echo htmlspecialchars($tipoUsuario); ?>">

                    <div class="border-l-4 border-upemor-orange pl-4 mb-6">
                        <h3 class="text-xl font-bold text-upemor-purple mb-4">Datos Generales</h3>
                    </div>

                    <div class="grid md:grid-cols-2 gap-6">
                        <div>
                            <label class="block text-base font-semibold text-upemor-purple mb-2">Nombre(s)</label>
                            <input type="text" name="Nombre" required class="w-full px-4 py-3 border-2 border-slate-200 rounded-xl text-slate-900 placeholder:text-slate-400 focus:outline-none focus:ring-2 focus:ring-upemor-orange focus:border-upemor-orange transition" placeholder="Ingresa el/los nombre(s)"/>
                        </div>
                        <div>
                            <label class="block text-base font-semibold text-upemor-purple mb-2">Apellidos</label>
                            <input type="text" name="Apellido" required class="w-full px-4 py-3 border-2 border-slate-200 rounded-xl text-slate-900 placeholder:text-slate-400 focus:outline-none focus:ring-2 focus:ring-upemor-orange focus:border-upemor-orange transition" placeholder="Ingresa los apellidos"/>
                        </div>
                    </div>

                    <div class="grid md:grid-cols-2 gap-6">
                        <div>
                            <label class="block text-base font-semibold text-upemor-purple mb-2">Correo Electrónico</label>
                            <input type="email" name="Correo" required class="w-full px-4 py-3 border-2 border-slate-200 rounded-xl text-slate-900 placeholder:text-slate-400 focus:outline-none focus:ring-2 focus:ring-upemor-orange focus:border-upemor-orange transition" placeholder="correo@ejemplo.com"/>
                        </div>
                        <div>
                            <label class="block text-base font-semibold text-upemor-purple mb-2">Teléfono</label>
                            <input type="tel" name="Telefono" pattern="[0-9]{3}-[0-9]{3}-[0-9]{4}" required class="w-full px-4 py-3 border-2 border-slate-200 rounded-xl text-slate-900 placeholder:text-slate-400 focus:outline-none focus:ring-2 focus:ring-upemor-orange focus:border-upemor-orange transition" placeholder="777-123-4567"/>
                        </div>
                    </div>

                    <div class="grid md:grid-cols-2 gap-6">
                        <div>
                            <label class="block text-base font-semibold text-upemor-purple mb-2">Fecha de Nacimiento</label>
                            <input type="date" name="FechaNac" required class="w-full px-4 py-3 border-2 border-slate-200 rounded-xl text-slate-900 focus:outline-none focus:ring-2 focus:ring-upemor-orange focus:border-upemor-orange transition"/>
                        </div>
                        <div>
                            <label class="block text-base font-semibold text-upemor-purple mb-2">Sexo</label>
                            <select name="Sexo" required class="w-full px-4 py-3 border-2 border-slate-200 rounded-xl text-slate-900 focus:outline-none focus:ring-2 focus:ring-upemor-orange focus:border-upemor-orange transition">
                                <option value="">Selecciona...</option>
                                <option value="Masculino">Masculino</option>
                                <option value="Femenino">Femenino</option>
                                <option value="Otro">Otro</option>
                            </select>
                        </div>
                    </div>

                    <div class="border-l-4 border-upemor-orange pl-4 mb-6 mt-8">
                        <h3 class="text-xl font-bold text-upemor-purple mb-4">Seguridad</h3>
                    </div>

                    <div class="grid md:grid-cols-2 gap-6">
                        <div>
                            <label class="block text-base font-semibold text-upemor-purple mb-2">Contraseña</label>
                            <div class="relative">
                                <input id="password" name="Contrasena" type="password" required class="w-full px-4 py-3 pr-12 border-2 border-slate-200 rounded-xl text-slate-900 placeholder:text-slate-400 focus:outline-none focus:ring-2 focus:ring-upemor-orange focus:border-upemor-orange transition" placeholder="Mínimo 8 caracteres"/>
                                <button type="button" onclick="togglePassword('password', 'eyeIcon1')" class="absolute right-3 top-1/2 -translate-y-1/2 text-slate-400 hover:text-upemor-purple transition">
                                    <svg id="eyeIcon1" class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"/>
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z"/>
                                    </svg>
                                </button>
                            </div>
                            <p class="text-xs text-slate-500 mt-1">Debe contener: mayúscula, minúscula, número y carácter especial</p>
                        </div>
                        <div>
                            <label class="block text-base font-semibold text-upemor-purple mb-2">Confirmar Contraseña</label>
                            <div class="relative">
                                <input id="confirmPassword" name="ConfirmarContrasena" type="password" required class="w-full px-4 py-3 pr-12 border-2 border-slate-200 rounded-xl text-slate-900 placeholder:text-slate-400 focus:outline-none focus:ring-2 focus:ring-upemor-orange focus:border-upemor-orange transition" placeholder="Confirma la contraseña"/>
                                <button type="button" onclick="togglePassword('confirmPassword', 'eyeIcon2')" class="absolute right-3 top-1/2 -translate-y-1/2 text-slate-400 hover:text-upemor-purple transition">
                                    <svg id="eyeIcon2" class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"/>
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z"/>
                                    </svg>
                                </button>
                            </div>
                        </div>
                    </div>

                    <?php if ($tipoUsuario === 'Docente'): ?>
                        <div class="border-l-4 border-upemor-orange pl-4 mb-6 mt-8">
                            <h3 class="text-xl font-bold text-upemor-purple mb-4">Información Docente</h3>
                        </div>

                        <div class="grid md:grid-cols-2 gap-6">
                            <div>
                                <label class="block text-base font-semibold text-upemor-purple mb-2">Especialidad</label>
                                <input type="text" name="Especialidad" required class="w-full px-4 py-3 border-2 border-slate-200 rounded-xl text-slate-900 placeholder:text-slate-400 focus:outline-none focus:ring-2 focus:ring-upemor-orange focus:border-upemor-orange transition" placeholder="Ej: Ingeniería en Software"/>
                            </div>
                            <div>
                                <label class="block text-base font-semibold text-upemor-purple mb-2">Materias que Imparte</label>
                                <input type="text" name="Materias" required class="w-full px-4 py-3 border-2 border-slate-200 rounded-xl text-slate-900 placeholder:text-slate-400 focus:outline-none focus:ring-2 focus:ring-upemor-orange focus:border-upemor-orange transition" placeholder="Ej: Base de Datos, Programación"/>
                            </div>
                        </div>

                    <?php elseif ($tipoUsuario === 'Administrador'): ?>
                        <div class="border-l-4 border-upemor-orange pl-4 mb-6 mt-8">
                            <h3 class="text-xl font-bold text-upemor-purple mb-4">Información Administrativa</h3>
                        </div>

                        <div class="grid md:grid-cols-2 gap-6">
                            <div>
                                <label class="block text-base font-semibold text-upemor-purple mb-2">No. Empleado</label>
                                <input type="text" name="NumeroEmpleado" required class="w-full px-4 py-3 border-2 border-slate-200 rounded-xl text-slate-900 placeholder:text-slate-400 focus:outline-none focus:ring-2 focus:ring-upemor-orange focus:border-upemor-orange transition" placeholder="Ej: ADM-001"/>
                            </div>
                            <div>
                                <label class="block text-base font-semibold text-upemor-purple mb-2">Departamento</label>
                                <select name="Departamento" required class="w-full px-4 py-3 border-2 border-slate-200 rounded-xl text-slate-900 focus:outline-none focus:ring-2 focus:ring-upemor-orange focus:border-upemor-orange transition">
                                    <option value="">Selecciona...</option>
                                    <option value="Sistemas">Sistemas</option>
                                    <option value="Servicios Escolares">Servicios Escolares</option>
                                    <option value="Dirección Académica">Dirección Académica</option>
                                    <option value="Recursos Humanos">Recursos Humanos</option>
                                </select>
                            </div>
                        </div>

                        <div class="mt-6">
                            <label class="block text-base font-semibold text-upemor-purple mb-2">Cargo</label>
                            <input type="text" name="Cargo" required class="w-full px-4 py-3 border-2 border-slate-200 rounded-xl text-slate-900 placeholder:text-slate-400 focus:outline-none focus:ring-2 focus:ring-upemor-orange focus:border-upemor-orange transition" placeholder="Ej: Jefe de Departamento, Analista, Secretaria"/>
                        </div>

                    <?php else: // Por defecto es Estudiante ?>
                        <div class="border-l-4 border-upemor-orange pl-4 mb-6 mt-8">
                            <h3 class="text-xl font-bold text-upemor-purple mb-4">Información Académica</h3>
                        </div>

                        <div class="grid md:grid-cols-2 gap-6">
                            <div>
                                <label class="block text-base font-semibold text-upemor-purple mb-2">Matrícula</label>
                                <input type="text" name="Matricula" required class="w-full px-4 py-3 border-2 border-slate-200 rounded-xl text-slate-900 placeholder:text-slate-400 focus:outline-none focus:ring-2 focus:ring-upemor-orange focus:border-upemor-orange transition" placeholder="Ej: 2024010123"/>
                            </div>
                            <div>
                                <label class="block text-base font-semibold text-upemor-purple mb-2">Carrera</label>
                                <select name="Carrera" required class="w-full px-4 py-3 border-2 border-slate-200 rounded-xl text-slate-900 focus:outline-none focus:ring-2 focus:ring-upemor-orange focus:border-upemor-orange transition bg-slate-100 cursor-not-allowed" onclick="return false;">
                                    <option value="ITI" selected>Ingeniería en Tecnologías de la Información</option>
                                </select>
                            </div>
                        </div>

                        <div class="grid md:grid-cols-2 gap-6">
                            <div>
                                <label class="block text-base font-semibold text-upemor-purple mb-2">Grupo</label>
                                <input type="text" name="Grupo" required class="w-full px-4 py-3 border-2 border-slate-200 rounded-xl text-slate-900 placeholder:text-slate-400 focus:outline-none focus:ring-2 focus:ring-upemor-orange focus:border-upemor-orange transition" placeholder="Ej: A, B, C"/>
                            </div>
                            <div>
                                <label class="block text-base font-semibold text-upemor-purple mb-2">Cuatrimestre</label>
                                <select name="Cuatrimestre" required class="w-full px-4 py-3 border-2 border-slate-200 rounded-xl text-slate-900 focus:outline-none focus:ring-2 focus:ring-upemor-orange focus:border-upemor-orange transition">
                                    <option value="">Selecciona...</option>
                                    <option value="1">1° Cuatrimestre</option>
                                    <option value="2">2° Cuatrimestre</option>
                                    <option value="3">3° Cuatrimestre</option>
                                    <option value="4">4° Cuatrimestre</option>
                                    <option value="5">5° Cuatrimestre</option>
                                    <option value="6">6° Cuatrimestre</option>
                                    <option value="7">7° Cuatrimestre</option>
                                    <option value="8">8° Cuatrimestre</option>
                                    <option value="9">9° Cuatrimestre</option>
                                    <option value="10">10° Cuatrimestre</option>
                                </select>
                            </div>
                        </div>
                    <?php endif; ?>

                    <div class="pt-4 flex gap-4">
                        <a href="index.php?controller=auth&action=panelAdministrativo" class="flex-1 bg-slate-200 hover:bg-slate-300 text-slate-700 font-bold py-4 px-6 rounded-xl text-base transition text-center">
                            <span class="material-symbols-outlined mr-2 align-middle">close</span>
                            Cancelar
                        </a>
                        <button type="submit" name="registrar" class="flex-1 bg-gradient-to-r from-upemor-orange to-upemor-red hover:from-upemor-red hover:to-upemor-orange text-white font-bold py-4 px-6 rounded-xl text-base transition transform hover:scale-105 hover:shadow-xl focus:outline-none focus:ring-2 focus:ring-upemor-orange focus:ring-offset-2">
                            <span class="material-symbols-outlined mr-2 align-middle">person_add</span>
                            Crear Usuario
                        </button>
                    </div>
                </form>
            </div>
        </div>
    </div>

    <script>
        function togglePassword(inputId, iconId) {
          const input = document.getElementById(inputId);
          const icon = document.getElementById(iconId);
          
          if (input.type === 'password') {
            input.type = 'text';
            icon.innerHTML = `
              <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13.875 18.825A10.05 10.05 0 0112 19c-4.478 0-8.268-2.943-9.543-7a9.97 9.97 0 011.563-3.029m5.858.908a3 3 0 114.243 4.243M9.878 9.878l4.242 4.242M9.88 9.88l-3.29-3.29m7.532 7.532l3.29 3.29M3 3l3.59 3.59m0 0A9.953 9.953 0 0112 5c4.478 0 8.268 2.943 9.543 7a10.025 10.025 0 01-4.132 5.411m0 0L21 21"/>
            `;
          } else {
            input.type = 'password';
            icon.innerHTML = `
              <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"/>
              <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z"/>
            `;
          }
        }
    </script>
</body>
</html>