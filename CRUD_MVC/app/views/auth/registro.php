<?php
$error = isset($_SESSION['error']) ? $_SESSION['error'] : '';
unset($_SESSION['error']);
$fechaMinima = date('Y-m-d', strtotime('-90 years')); // Hace 90 años (ej. 1934)
$fechaMaxima = date('Y-m-d', strtotime('-17 years')); // Hace 17 años (ej. 2007) 
?>
<!DOCTYPE html>
<html lang="es">
<head>
<script src="https://kit.fontawesome.com/229ca0cd03.js" crossorigin="anonymous"></script>
<meta charset="utf-8"/>
<meta content="width=device-width, initial-scale=1.0" name="viewport"/>
<title>Registro - Portal Upemor</title>
<script src="https://cdn.tailwindcss.com?plugins=forms,container-queries"></script>
<link href="https://fonts.googleapis.com/css2?family=Inter:wght@400;500;600;700;900&display=swap" rel="stylesheet"/>
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
</head>
<body class="font-sans bg-gradient-to-br from-upemor-purple via-upemor-purple to-purple-900 min-h-screen">

<!-- Paso 1: Selección de Rol -->
<div id="step1" class="flex min-h-screen items-center justify-center p-4">
<div class="w-full max-w-4xl">
<div class="text-center mb-12">
<div class="flex justify-center mb-6">
<img src="https://gruposat.com.mx/img/clientes/UPEMOR.png" alt="Upemor Logo" class="h-32 w-32 object-contain drop-shadow-2xl"/>
</div>
<h1 class="text-4xl font-bold text-white mb-3">Universidad Politécnica del Estado de Morelos</h1>
<p class="text-xl text-upemor-orange font-semibold mb-8">Ciencia y Tecnología para el Bien Común</p>
<h2 class="text-3xl font-bold text-white mb-2">Crear Cuenta</h2>
<p class="text-slate-300">Selecciona cómo deseas registrarte</p>
</div>

<?php if ($error): ?>
<div class="mb-6 p-4 bg-red-100 border border-red-400 text-red-700 rounded-xl text-center">
<?php echo htmlspecialchars($error); ?>
</div>
<?php endif; ?>

<div class="grid md:grid-cols-2 gap-6">
<!-- Tarjeta Docente -->
<button onclick="selectRole('Docente')" class="group bg-white rounded-2xl p-8 shadow-2xl hover:shadow-upemor-orange/50 transition-all duration-300 transform hover:scale-105 text-left">
<div class="flex items-center justify-center w-16 h-16 bg-gradient-to-br from-upemor-purple to-purple-600 rounded-xl mb-4 group-hover:from-upemor-orange group-hover:to-upemor-red transition-all duration-300">
<svg class="w-8 h-8 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
<path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 6.253v13m0-13C10.832 5.477 9.246 5 7.5 5S4.168 5.477 3 6.253v13C4.168 18.477 5.754 18 7.5 18s3.332.477 4.5 1.253m0-13C13.168 5.477 14.754 5 16.5 5c1.747 0 3.332.477 4.5 1.253v13C19.832 18.477 18.247 18 16.5 18c-1.746 0-3.332.477-4.5 1.253"/>
</svg>
</div>
<h3 class="text-2xl font-bold text-upemor-purple mb-2">Soy Docente</h3>
<p class="text-slate-600 mb-4">Registra tus datos como docente asesor para evaluar competencias</p>
<div class="flex items-center text-upemor-orange font-semibold group-hover:translate-x-2 transition-transform"><i class="fa-solid fa-person-chalkboard"></i>   Continuar como Docente
<svg class="w-5 h-5 ml-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
<path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7"/>
</svg>
</div>
</button>

<!-- Tarjeta Estudiante -->
<button onclick="selectRole('Estudiante')" class="group bg-white rounded-2xl p-8 shadow-2xl hover:shadow-upemor-orange/50 transition-all duration-300 transform hover:scale-105 text-left">
<div class="flex items-center justify-center w-16 h-16 bg-gradient-to-br from-upemor-purple to-purple-600 rounded-xl mb-4 group-hover:from-upemor-orange group-hover:to-upemor-red transition-all duration-300">
<svg class="w-8 h-8 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
<path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 14l9-5-9-5-9 5 9 5z"/>
<path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 14l6.16-3.422a12.083 12.083 0 01.665 6.479A11.952 11.952 0 0012 20.055a11.952 11.952 0 00-6.824-2.998 12.078 12.078 0 01.665-6.479L12 14z"/>
<path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 14l9-5-9-5-9 5 9 5zm0 0l6.16-3.422a12.083 12.083 0 01.665 6.479A11.952 11.952 0 0012 20.055a11.952 11.952 0 00-6.824-2.998 12.078 12.078 0 01.665-6.479L12 14zm-4 6v-7.5l4-2.222"/>
</svg>
</div>
<h3 class="text-2xl font-bold text-upemor-purple mb-2">Soy Estudiante</h3>
<p class="text-slate-600 mb-4">Registra tus datos como estudiante para consultar tus evaluaciones</p>
<div class="flex items-center text-upemor-orange font-semibold group-hover:translate-x-2 transition-transform"><i class="fa-solid fa-graduation-cap"></i>
 Continuar como Estudiante
<svg class="w-5 h-5 ml-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
<path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7"/>
</svg>
</div>
</button>
</div>

<div class="text-center mt-8">
<p class="text-white">
¿Ya tienes una cuenta? 
<a href="index.php?controller=auth&action=mostrarLogin" class="text-upemor-orange font-semibold hover:underline ml-1">Inicia Sesión</a>
</p>
</div>
</div>
</div>

<!-- Paso 2: Formulario de Registro -->
<div id="step2" class="hidden min-h-screen p-8">
<div class="max-w-4xl mx-auto">
<!-- Header con logo y botón volver -->
<div class="flex items-center justify-between mb-8">
<button onclick="backToStep1()" class="flex items-center text-white hover:text-upemor-orange transition">
<svg class="w-6 h-6 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
<path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 19l-7-7m0 0l7-7m-7 7h18"/>
</svg>
Volver
</button>
<img src="https://gruposat.com.mx/img/clientes/UPEMOR.png" alt="Upemor Logo" class="h-16 w-16 object-contain drop-shadow-2xl"/>
</div>

<div class="bg-white rounded-2xl shadow-2xl p-8">
<div class="mb-8">
<h2 class="text-3xl font-bold text-upemor-purple mb-2">Completa tu Registro</h2>
<p class="text-slate-600">Registrándote como: <span id="roleDisplay" class="font-semibold text-upemor-orange"></span></p>
</div>

<form action="index.php?controller=auth&action=registro" method="POST" class="space-y-6">
<input type="hidden" name="TipoUsuario" id="tipoUsuarioInput" value="">

<!-- Sección 1: Datos Generales -->
<div class="border-l-4 border-upemor-orange pl-4 mb-6">
<h3 class="text-xl font-bold text-upemor-purple mb-4">Datos Generales</h3>
</div>

<div class="grid md:grid-cols-2 gap-6">
<div>
<label class="block text-base font-semibold text-upemor-purple mb-2">Nombre(s)</label>
<input type="text" name="Nombre" required class="w-full px-4 py-3 border-2 border-slate-200 rounded-xl text-slate-900 placeholder:text-slate-400 focus:outline-none focus:ring-2 focus:ring-upemor-orange focus:border-upemor-orange transition" placeholder="Ingresa tu(s) nombre(s)"/>
</div>
<div>
<label class="block text-base font-semibold text-upemor-purple mb-2">Apellidos</label>
<input type="text" name="Apellido" required class="w-full px-4 py-3 border-2 border-slate-200 rounded-xl text-slate-900 placeholder:text-slate-400 focus:outline-none focus:ring-2 focus:ring-upemor-orange focus:border-upemor-orange transition" placeholder="Ingresa tus apellidos"/>
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
<input type="date" name="FechaNac" required min="<?php echo $fechaMinima; ?>" max="<?php echo $fechaMaxima; ?>" class="w-full px-4 py-3 border-2 border-slate-200 rounded-xl text-slate-900 focus:outline-none focus:ring-2 focus:ring-upemor-orange focus:border-upemor-orange transition"/>
</div>
<div>
<label class="block text-base font-semibold text-upemor-purple mb-2">Sexo</label>
<select name="Sexo" required class="w-full px-4 py-3 border-2 border-slate-200 rounded-xl text-slate-900 focus:outline-none focus:ring-2 focus:ring-upemor-orange focus:border-upemor-orange transition">
<option value="">Selecciona...</option>
<option value="Masculino">Masculino</option>
<option value="Femenino">Femenino</option>
</select>
</div>
</div>

<!-- Sección 2: Seguridad -->
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
<input id="confirmPassword" name="ConfirmarContrasena" type="password" required class="w-full px-4 py-3 pr-12 border-2 border-slate-200 rounded-xl text-slate-900 placeholder:text-slate-400 focus:outline-none focus:ring-2 focus:ring-upemor-orange focus:border-upemor-orange transition" placeholder="Confirma tu contraseña"/>
<button type="button" onclick="togglePassword('confirmPassword', 'eyeIcon2')" class="absolute right-3 top-1/2 -translate-y-1/2 text-slate-400 hover:text-upemor-purple transition">
<svg id="eyeIcon2" class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
<path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"/>
<path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z"/>
</svg>
</button>
</div>
</div>
</div>

<!-- Sección 3: Datos Específicos (Cambia según el rol) -->
<div id="specificFields"></div>

<!-- Botón Submit -->
<div class="pt-4">
<button type="submit" name="registrar" class="w-full bg-gradient-to-r from-upemor-orange to-upemor-red hover:from-upemor-red hover:to-upemor-orange text-white font-bold py-4 px-6 rounded-xl text-base transition duration-300 transform hover:scale-105 hover:shadow-xl focus:outline-none focus:ring-2 focus:ring-upemor-orange focus:ring-offset-2">
Crear Cuenta
</button>
</div>
</form>
</div>
</div>
</div>

<script>
let selectedRole = '';

function selectRole(role) {
  selectedRole = role;
  document.getElementById('step1').classList.add('hidden');
  document.getElementById('step2').classList.remove('hidden');
  
  const roleDisplay = document.getElementById('roleDisplay');
  const tipoUsuarioInput = document.getElementById('tipoUsuarioInput');
  roleDisplay.textContent = role;
  tipoUsuarioInput.value = role;
  
  showSpecificFields(role);
}

function backToStep1() {
  document.getElementById('step2').classList.add('hidden');
  document.getElementById('step1').classList.remove('hidden');
  selectedRole = '';
}

function showSpecificFields(role) {
  const container = document.getElementById('specificFields');
  
  if (role === 'Docente') {
    container.innerHTML = `
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
    `;
  } else {
    container.innerHTML = `
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
          <select name="Carrera" required class="w-full px-4 py-3 border-2 border-slate-200 rounded-xl text-slate-900 focus:outline-none focus:ring-2 focus:ring-upemor-orange focus:border-upemor-orange transition">
            <option value="">Selecciona tu carrera...</option>
            <option value="ISW">Ingeniería en Software</option>
            <option value="ITI">Ingeniería en Tecnologías de la Información</option>
            <option value="IIA">Ingeniería en Inteligencia Artificial</option>
            <option value="IME">Ingeniería Mecatrónica</option>
            <option value="IIN">Ingeniería Industrial</option>
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
    `;
  }
}

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