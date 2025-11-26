<?php
$error = isset($_SESSION['error']) ? $_SESSION['error'] : '';
$success = isset($_SESSION['success']) ? $_SESSION['success'] : '';
unset($_SESSION['error']);
unset($_SESSION['success']);
?>
<!DOCTYPE html>
<html class="light" lang="es">
<head>
<script src="https://kit.fontawesome.com/229ca0cd03.js" crossorigin="anonymous"></script>
<meta charset="utf-8"/>
<meta content="width=device-width, initial-scale=1.0" name="viewport"/>
<title>Inicio de Sesión - Portal Upemor</title>
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
              "upemor-gold": "#B8860B",
            },
            fontFamily: {
              "sans": ["Inter", "sans-serif"]
            },
          },
        },
      }
    </script>
</head>
<body class="font-sans bg-gradient-to-br from-upemor-purple via-upemor-purple to-purple-900 text-slate-900 min-h-screen">
<div class="flex min-h-screen">
<!-- Left Column: Branding -->
<div class="hidden lg:flex lg:w-1/2 items-center justify-center p-12 relative overflow-hidden">
<!-- Decorative circles -->
<div class="absolute top-20 left-20 w-64 h-64 bg-upemor-red rounded-full opacity-20 blur-3xl"></div>
<div class="absolute bottom-20 right-20 w-80 h-80 bg-upemor-orange rounded-full opacity-15 blur-3xl"></div>
  
<div class="text-center max-w-md relative z-10">
<div class="flex justify-center mb-8">
<img src="https://gruposat.com.mx/img/clientes/UPEMOR.png" alt="Upemor Logo" class="h-60 w-60 object-contain drop-shadow-2xl"/>
</div>
<h1 class="text-4xl font-bold text-white mb-4 drop-shadow-lg">Universidad Politécnica del Estado de Morelos</h1>
<p class="text-xl text-upemor-orange font-semibold">Ciencia y Tecnología para el Bien Común</p>
</div>
</div>

<!-- Right Column: Login Form -->
<div class="flex-1 flex items-center justify-center p-8 bg-white lg:rounded-l-3xl">
<div class="w-full max-w-md">
<!-- Heading -->
<div class="mb-8">
<h2 class="text-4xl font-bold text-upemor-purple mb-2">Bienvenido</h2>
<p class="text-slate-600">Inicia sesión en tu cuenta</p>
</div>

<!-- Mensajes de error/éxito -->
<?php if ($error): ?>
<div class="mb-4 p-4 bg-red-100 border border-red-400 text-red-700 rounded-xl">
<?php echo htmlspecialchars($error); ?>
</div>
<?php endif; ?>

<?php if ($success): ?>
<div class="mb-4 p-4 bg-green-100 border border-green-400 text-green-700 rounded-xl">
<?php echo htmlspecialchars($success); ?>
</div>
<?php endif; ?>

<!-- Form -->
<form action="index.php?controller=auth&action=login" method="POST" class="space-y-6">
<!-- Username/Email Field -->
<div>
<label for="correo" class="block text-base font-semibold text-upemor-purple mb-2">
Correo Electrónico
</label>
<input 
  id="correo"
  name="correo"
  type="email" 
  required
  class="w-full px-4 py-3 border-2 border-slate-200 rounded-xl text-base text-slate-900 placeholder:text-slate-400 focus:outline-none focus:ring-2 focus:ring-upemor-orange focus:border-upemor-orange transition" 
  placeholder="Ingresa tu correo institucional"/>
</div>

<!-- Password Field -->
<div>
<label for="password" class="block text-base font-semibold text-upemor-purple mb-2">
Contraseña
</label>
<div class="relative">
<input 
  id="password"
  name="contrasena"
  type="password"
  required
  class="w-full px-4 py-3 pr-12 border-2 border-slate-200 rounded-xl text-base text-slate-900 placeholder:text-slate-400 focus:outline-none focus:ring-2 focus:ring-upemor-orange focus:border-upemor-orange transition" 
  placeholder="Ingresa tu contraseña"/>
<button 
  type="button"
  id="togglePassword"
  class="absolute right-3 top-1/2 -translate-y-1/2 text-slate-400 hover:text-upemor-purple focus:outline-none transition">
<svg id="eyeIcon" class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
<path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z" />
<path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z" />
</svg>
</button>
</div>
</div>

<!-- Login Button -->
<div class="pt-4">
<button 
  type="submit"
  name="login"
  class="w-full bg-gradient-to-r from-upemor-orange to-upemor-red hover:from-upemor-red hover:to-upemor-orange text-white font-bold py-4 px-6 rounded-xl text-base transition duration-300 transform hover:scale-105 hover:shadow-xl focus:outline-none focus:ring-2 focus:ring-upemor-orange focus:ring-offset-2"
><i class="fa-solid fa-right-to-bracket"></i>
Iniciar Sesión
</button>
</div>


</form>

<!-- Decorative line -->
<div class="mt-8 flex items-center">
<div class="flex-1 h-px bg-gradient-to-r from-transparent via-upemor-purple to-transparent opacity-20"></div>
</div>

<!-- Footer -->
<div class="mt-8 text-center text-sm text-slate-500">
<p class="font-medium text-upemor-purple">© 2024 Universidad Politécnica del Estado de Morelos</p>
<p class="mt-1">Todos los derechos reservados</p>
</div>
</div>
</div>
</div>

<script>
// Toggle password visibility
const togglePassword = document.getElementById('togglePassword');
const passwordInput = document.getElementById('password');
const eyeIcon = document.getElementById('eyeIcon');

togglePassword.addEventListener('click', function() {
  const type = passwordInput.getAttribute('type') === 'password' ? 'text' : 'password';
  passwordInput.setAttribute('type', type);
  
  if (type === 'text') {
    eyeIcon.innerHTML = `
      <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13.875 18.825A10.05 10.05 0 0112 19c-4.478 0-8.268-2.943-9.543-7a9.97 9.97 0 011.563-3.029m5.858.908a3 3 0 114.243 4.243M9.878 9.878l4.242 4.242M9.88 9.88l-3.29-3.29m7.532 7.532l3.29 3.29M3 3l3.59 3.59m0 0A9.953 9.953 0 0112 5c4.478 0 8.268 2.943 9.543 7a10.025 10.025 0 01-4.132 5.411m0 0L21 21" />
    `;
  } else {
    eyeIcon.innerHTML = `
      <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z" />
      <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z" />
    `;
  }
});
</script>
</body>
</html>