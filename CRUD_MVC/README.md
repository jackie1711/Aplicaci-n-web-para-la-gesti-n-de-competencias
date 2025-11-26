# Aplicacion web para la Gestion de competencias

## Descripción

Aplicación web para la gestión y evaluación de competencias educativas. Permite a los docentes asignar evidencias, a los estudiantes subirlas y facilita el proceso de evaluación de capacidades por competencia a través de una dimension: producto.

## Características principales

### Gestión de Usuarios (3 tipos)
- **Administrador**: Gestión completa del sistema, usuarios, docentes, estudiantes y competencias
- **Asesor (Docente)**: Asignación y evaluación de evidencias y gestion de competencias
- **Estudiante**: Subida de evidencias, consulta de evaluaciones y reportes individuales

### Funcionalidades Principales

#### Gestión Administrativa
- Registro, búsqueda, modificación y eliminación de usuarios
- Gestión de expedientes de estudiantes (datos académicos, matriculación, competencias)
- Gestión de docentes (datos personales, materias)
- Gestión de catálogo de competencias con criterios de evaluación
- Control de permisos por tipo de usuario

#### Evaluación de Competencias
- Asignación de evidencias por competencia a estudiantes
- Carga de evidencias por parte de estudiantes
- Evaluación de evidencias en una dimension:
  - **Producto**: Evaluación de resultados tangibles
- Registro de calificaciones y observaciones

#### Consultas y Reportes
- **Consultas de evaluaciones individuales**: Progreso por estudiante
- **Consultas de evaluaciones por competencia**: Análisis de rendimiento grupal
- **Reportes individuales**: Evolución histórica del estudiante
- **Reportes comparativos grupales**: Comparación entre grupos y métricas estadísticas
- **Reportes históricos**: Tendencias del cuatrimestre Septiembre-Diciembre 2025

## Requisitos previos

### Software necesario
- **XAMPP** (incluye Apache y MySQL)
- **PHP** 7.4 o superior
- **MySQL** 5.7 o superior
- Navegador web moderno (Chrome, Firefox, Edge)

### Extensiones PHP requeridas
- mysqli o PDO
- mbstring
- json
- fileinfo (para manejo de archivos de evidencias)
- openssl (para encriptación de contraseñas)

## Instalación

### 1. Clonar o descargar el proyecto
```bash
# Opción A: Clonar el repositorio
git clone [URL-de-tu-repositorio]

# Opción B: Descargar y extraer el ZIP
# Coloca la carpeta en: C:\xampp\htdocs\ESTANCIAII
```

### 2. Configurar la Base de Datos

#### Opción A: Usando phpMyAdmin (Recomendado)

1. Abre XAMPP Control Panel
2. Inicia los servicios de **Apache** y **MySQL**
3. Abre tu navegador y ve a: `http://localhost/phpmyadmin`
4. Haz clic en **"Nueva"** para crear una base de datos
5. Nombra la base de datos: `bdappcompetencias`
6. Selecciona cotejamiento: `utf8mb4_general_ci`
7. Haz clic en **"Crear"**
8. Selecciona la base de datos creada
9. Ve a la pestaña **"Importar"**
10. Haz clic en **"Seleccionar archivo"**
11. Busca y selecciona: `CRUD_MVC/database/bdappcompetencias.sql`
12. Haz clic en **"Continuar"** al final de la página
13. Espera a que aparezca el mensaje de éxito

#### Opción B: Usando línea de comandos
```bash
# Accede a MySQL
mysql -u root -p

# Dentro de MySQL, ejecuta:
CREATE DATABASE bdappcompetencias CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci;
EXIT;

# Importa el archivo SQL (desde la raíz del proyecto)
mysql -u root -p bdappcompetencias < CRUD_MVC/database/bdappcompetencias.sql
```

### 3. Configurar la conexión a la base de datos

Edita el archivo `CRUD_MVC/config.php` con tus credenciales:
```php
<?php
$host = "localhost";
$usuario = "root";
$password = ""; // Deja vacío si no configuraste contraseña en XAMPP
$basedatos = "bdappcompetencias";
?>
```

### 4. Verificar permisos de carpetas

Asegúrate de que las siguientes carpetas tengan permisos de escritura:
```
ESTANCIAII/
├── uploads/
│   └── evidencias/     # Carpeta donde se guardan las evidencias
└── public/
    └── libraries/
        └── fpdf/       # Librería para generar reportes PDF
```

## Uso del Sistema

### Iniciar la aplicación

1. Abre **XAMPP Control Panel**
2. Inicia **Apache** y **MySQL**
3. Abre tu navegador
4. Ve a: `http://localhost/ESTANCIAII`

### Acceso al Sistema

El sistema cuenta con diferentes paneles según el tipo de usuario:

| Panel                | Archivo                   | Tipo de Usuario |
|----------------------|---------------------------|-----------------|
| Panel Administrativo | `panelAdministrativo.php` | Administrador   |
| Panel Docente        | `panelDocente.php`        | Asesor/Docente  |
| Panel Estudiante     | `panelEstudiantes.php`    | Estudiante      |

### Proceso de Registro

El usuario administrador puede registrar nuevas cuentas validando:
- Nombre y apellido
- Tipo de usuario (Administrador, Asesor, Estudiante)
- Fecha de nacimiento
- Sexo
- Número de teléfono
- Correo electrónico
- **Contraseña encriptada** que debe contener:
  - Al menos una letra mayúscula
  - Al menos una letra minúscula
  - Al menos un número
  - Al menos un carácter especial

## Estructura del Proyecto
```
ESTANCIAII/
├── CRUD_MVC/
│   ├── app/                          # Aplicación principal
│   │   ├── controllers/              # Controladores MVC
│   │   ├── models/                   # Modelos MVC
│   │   └── views/                    # Vistas organizadas por módulo
│   │       ├── asignaciones/         # Vistas de asignación de evidencias
│   │       ├── auth/                 # Vistas de autenticación (login)
│   │       ├── competencia/          # Vistas de gestión de competencias
│   │       ├── consultas/            # Vistas de consultas
│   │       ├── docente/              # Vistas específicas de docentes
│   │       ├── estudiante/           # Vistas específicas de estudiantes
│   │       ├── evaluacion/           # Vistas de evaluación
│   │       ├── reportes/             # Vistas de reportes
│   │       ├── formulario_subida.php # Formulario para subir evidencias
│   │       ├── mis_evidencias.php    # Vista de evidencias del estudiante
│   │       ├── panelAdministrativo.php  # Panel del administrador
│   │       ├── panelDocente.php      # Panel del docente
│   │       └── panelEstudiantes.php  # Panel del estudiante
│   ├── config.php                    # Configuración de conexión a BD
│   ├── database/                     # Base de datos
│   │   └── bdappcompetencias.sql    # Script SQL
│   └── public/                       # Recursos públicos
│       └── libraries/
│           └── fpdf/                 # Librería para PDFs
├── uploads/
│   └── evidencias/                   # Archivos de evidencias subidos
├── index.php                         # Punto de entrada principal
├── php_errors.log                    # Log de errores PHP
└── README.md                         # Este archivo
```

## Funcionalidades Detalladas

### FN.1 - Gestión de Usuarios
**Descripción:** El usuario administrador puede registrar, buscar, modificar y eliminar cuentas dentro del sistema web.

**Características:**
- Formulario de registro con validación completa
- Encriptación de contraseñas con requisitos de seguridad
- Búsqueda de usuarios existentes
- Modificación de datos de usuarios
- Eliminación de cuentas (impide inicio de sesión)
- Todos los cambios se reflejan inmediatamente en la base de datos

**Validaciones:**
- Contraseña: mínimo 1 mayúscula, 1 minúscula, 1 número, 1 carácter especial
- Correo electrónico único y válido
- Campos obligatorios completos

---

### FN.2 - Inicio de Sesión
**Descripción:** Todos los usuarios pueden iniciar sesión utilizando su correo electrónico y contraseña.

**Características:**
- Autenticación segura con contraseñas encriptadas
- Validación de credenciales contra la base de datos
- Redirección automática según tipo de usuario
- Gestión de sesiones seguras

**Proceso:**
1. Usuario ingresa correo y contraseña
2. Sistema valida credenciales
3. Sistema verifica tipo de usuario
4. Redirección al panel correspondiente

---

### FN.3 - Gestión de Expediente de Estudiantes
**Descripción:** El administrador y los asesores pueden gestionar la información completa de los estudiantes.

**Características:**
- Registro de datos académicos completos
- Matriculación en competencias
- Asignación de grupos
- Seguimiento del progreso individual
- Estado académico actualizado
- CRUD completo de información estudiantil

**Información gestionada:**
- Datos personales
- Matrícula
- Grupo asignado
- Competencias inscritas
- Historial académico

---

### FN.4 - Gestión de Docentes
**Descripción:** El administrador puede gestionar la información de los docentes asesores.

**Características:**
- Registro de datos personales
- Asignación de materias
- Definición de competencias autorizadas a evaluar
- Control de evaluadores por competencia
- CRUD completo de información docente

**Beneficios:**
- Control de qué docentes pueden evaluar qué competencias
- Garantiza calidad y consistencia en evaluaciones
- Facilita asignación de evaluadores

---

### FN.5 - Gestión de Competencias
**Descripción:** El administrador puede definir, modificar y gestionar las competencias a evaluar.

**Características:**
- Catálogo completo de competencias
- Definición de criterios de evaluación
- Una dimension de evaluación:
  - **Producto:** Criterios de resultados tangibles
- CRUD de competencias

**Importancia:**
- Define la base del sistema de evaluación
- Establece coherencia con el modelo educativo por competencias
- Estandariza criterios de evaluación

---

### FN.6 - Gestión de Evaluaciones
**Descripción:** Los docentes asesores pueden registrar evaluaciones de competencias para los estudiantes.

**Características:**
- Registro de evaluaciones completas
- Calificaciones por dimensión (conocimiento, producto, desempeño)
- Captura de evidencias
- Observaciones y retroalimentación
- Fecha de evaluación
- Almacenamiento en base de datos

**Proceso de evaluación:**
1. Docente selecciona estudiante y competencia
2. Registra calificaciones por cada dimensión
3. Agrega observaciones
4. Guarda evaluación en el sistema
5. Disponible inmediatamente para consultas

---

### FN.7 - Consulta de Evaluaciones por Alumno
**Descripción:** Los administradores y asesores pueden consultar todas las evaluaciones de un estudiante específico.

**Características:**
- Búsqueda por matrícula o nombre
- Visualización de todas las evaluaciones
- Organización por competencia y fecha
- Presentación en tablas dinámicas
- Muestra calificaciones y observaciones

**Beneficios:**
- Seguimiento individualizado del progreso
- Identificación de fortalezas y áreas de mejora
- Apoyo en toma de decisiones pedagógicas personalizadas

---

### FN.8 - Consulta de Evaluaciones por Competencias
**Descripción:** Los usuarios pueden consultar todas las evaluaciones realizadas para una competencia específica.

**Características:**
- Selección de competencia específica
- Visualización de evaluaciones de todos los estudiantes en esa competencia
- Estadísticas básicas de desempeño grupal
- Análisis de rendimiento colectivo

**Utilidad:**
- Identificar competencias con mayor/menor dominio
- Ajustar estrategias pedagógicas grupales
- Comparar rendimiento entre estudiantes

---

### FN.9 - Reporte de Progreso Individual
**Descripción:** El sistema genera reportes detallados del progreso individual de cada estudiante.

**Características:**
- Los estudiantes pueden visualizar sus propios reportes
- Los asesores pueden generar reportes de cualquier estudiante
- Incluye tablas de calificaciones por competencia
- Evolución histórica completa
- Recomendaciones personalizadas
- Información del cuatrimestre actual

**Contenido del reporte:**
- Datos del estudiante
- Competencias evaluadas
- Calificaciones por dimensión
- Progreso temporal
- Observaciones y recomendaciones

**Importancia:**
- Documentación formal del progreso académico
- Comunicación con estudiantes y padres
- Evidencia para acreditación institucional

---

### FN.10 - Reportes Comparativos Grupales
**Descripción:** El sistema genera reportes que comparan el rendimiento de grupos de estudiantes.

**Características:**
- Comparaciones entre grupos
- Análisis por competencia específica o rendimiento general
- Métricas estadísticas:
  - Promedios grupales
  - Medianas
  - Rangos de desempeño
- Visualizaciones de datos estadísticos

**Beneficios:**
- Identificar patrones de rendimiento grupal
- Comparar efectividad de enfoques pedagógicos
- Tomar decisiones informadas sobre estrategias de enseñanza
- Distribución óptima de recursos educativos

---

### FN.11 - Reportes Históricos
**Descripción:** El sistema mantiene un historial completo de evaluaciones y genera reportes de tendencias históricas.

**Características:**
- Evolución del rendimiento a lo largo del cuatrimestre (Septiembre-Diciembre 2025)
- Tendencias por competencia
- Tendencias por estudiante
- Tendencias por grupo
- Sistema de archivado histórico
- Análisis longitudinal

**Importancia:**
- Análisis institucional a largo plazo
- Evaluación de efectividad del modelo educativo
- Identificación de tendencias de mejora
- Toma de decisiones estratégicas basadas en evidencia histórica

## Flujo de Trabajo del Sistema
```
1. ADMINISTRADOR
   ├─> Registra usuarios (administradores, asesores, estudiantes)
   ├─> Gestiona expedientes de estudiantes
   ├─> Gestiona información de docentes
   ├─> Define y gestiona catálogo de competencias
   └─> Asigna competencias autorizadas a docentes

2. DOCENTE/ASESOR
   ├─> Inicia sesión en el sistema
   ├─> Asigna evidencias a estudiantes
   ├─> Define competencias a evaluar
   └─> Espera subida de evidencias
   
3. ESTUDIANTE
   ├─> Inicia sesión en el sistema
   ├─> Consulta evidencias asignadas
   ├─> Sube archivos de evidencia
   └─> Espera evaluación
   
4. DOCENTE/ASESOR
   ├─> Revisa evidencias subidas
   ├─> Evalúa en una dimension:
   │   ├─> Producto
   ├─> Registra calificaciones y observaciones
   ├─> Consulta evaluaciones (individuales y por competencia)
   └─> Genera reportes (individuales, grupales, históricos)

5. ESTUDIANTE
   ├─> Consulta sus evaluaciones
   ├─> Visualiza retroalimentación
   └─> Consulta reportes de progreso individual

6. ADMINISTRADOR
   └─> Genera reportes comparativos y análisis institucional
```

## Tecnologías Utilizadas

### Backend
- **PHP** con arquitectura MVC (Modelo-Vista-Controlador)
- **MySQL** para base de datos relacional
- **PDO/MySQLi** para conexión segura a base de datos
- **Encriptación** de contraseñas con funciones nativas de PHP

### Frontend
- **HTML5** para estructura semántica
- **CSS3** para estilos y diseño responsive
- **JavaScript** para interactividad del lado cliente
- Formularios dinámicos con validación

### Librerías y Herramientas
- **FPDF**: Generación de reportes en formato PDF
- **XAMPP**: Servidor local de desarrollo

### Seguridad Implementada
- Contraseñas encriptadas
- Validación de datos del lado servidor
- Validación de datos del lado cliente
- Control de sesiones
- Permisos por tipo de usuario
- Prepared statements para prevenir SQL injection

## Solución de Problemas Comunes

### Error de conexión a la base de datos
```
Síntomas: No se puede conectar al sistema, error de conexión

Verifica:
✓ MySQL está corriendo en XAMPP
✓ Credenciales correctas en config.php
✓ La base de datos bdappcompetencias existe
✓ El usuario tiene permisos sobre la BD
✓ El puerto 3306 no está bloqueado
```

### Los archivos de evidencias no se suben
```
Síntomas: Error al subir archivos, no se guardan evidencias

Verifica:
✓ Permisos de escritura en uploads/evidencias/
✓ php.ini: upload_max_filesize (mínimo 10M)
✓ php.ini: post_max_size (mínimo 12M)
✓ La carpeta uploads/evidencias/ existe
✓ El tipo de archivo está permitido
```

### La página no carga o da error 404
```
Síntomas: Página en blanco, error 404

Verifica:
✓ Apache está corriendo en XAMPP
✓ La ruta es correcta: http://localhost/ESTANCIAII
✓ El archivo index.php existe en la raíz
✓ No hay errores de sintaxis en PHP
✓ Revisa php_errors.log para detalles
```

### Error al generar reportes PDF
```
Síntomas: No se generan PDFs, error al descargar

Verifica:
✓ La carpeta public/libraries/fpdf/ existe
✓ Los archivos de FPDF están completos
✓ Permisos de lectura en la carpeta fpdf/
✓ No hay errores de sintaxis en el código del reporte
```

### No aparecen las vistas correctamente
```
Síntomas: Pantallas en blanco, elementos faltantes

Verifica:
✓ La ruta a las vistas en los controladores es correcta
✓ Los nombres de archivo coinciden (mayúsculas/minúsculas)
✓ Los archivos PHP no tienen errores de sintaxis
✓ Las rutas CSS y JS son correctas
```

### Problemas con contraseñas
```
Síntomas: No puede iniciar sesión, contraseña no válida

Verifica:
✓ La contraseña cumple con los requisitos:
  - Al menos 1 mayúscula
  - Al menos 1 minúscula
  - Al menos 1 número
  - Al menos 1 carácter especial
✓ El correo electrónico es correcto
✓ La cuenta existe en la base de datos
✓ La cuenta no ha sido eliminada
```

### Permisos insuficientes
```
Síntomas: No puede acceder a ciertas funciones

Verifica:
✓ El tipo de usuario tiene los permisos necesarios
✓ La sesión está activa
✓ No hay errores en la validación de permisos
```

## Configuración Avanzada

### Aumentar tamaño máximo de archivo para evidencias

Edita `C:\xampp\php\php.ini`:
```ini
upload_max_filesize = 20M
post_max_size = 25M
max_execution_time = 300
memory_limit = 256M
```

**Reinicia Apache después de los cambios.**

### Habilitar registro de errores

En `php.ini`:
```ini
display_errors = On
error_reporting = E_ALL
log_errors = On
error_log = C:\xampp\htdocs\ESTANCIAII\php_errors.log
```

### Configurar zona horaria

En `php.ini`:
```ini
date.timezone = America/Mexico_City
```

### Mejorar seguridad de sesiones

En tu archivo de configuración PHP:
```php
<?php
// Configuración de sesiones seguras
ini_set('session.cookie_httponly', 1);
ini_set('session.cookie_secure', 0); // Cambiar a 1 si usas HTTPS
ini_set('session.use_only_cookies', 1);
session_start();
?>
```

## Seguridad

⚠️ **Recomendaciones CRÍTICAS para producción:**

### Obligatorias
- [x] Contraseñas encriptadas (ya implementado)
- [ ] Cambiar todas las contraseñas predeterminadas
- [ ] Configurar contraseña para el usuario root de MySQL
- [ ] Validar y sanitizar TODAS las entradas del usuario
- [ ] Usar prepared statements para prevenir SQL injection
- [ ] Implementar protección contra CSRF
- [ ] Implementar HTTPS (certificado SSL)
- [ ] Configurar headers de seguridad HTTP

### Recomendadas
- [ ] Límite de intentos de inicio de sesión
- [ ] Registro de auditoría de acciones críticas
- [ ] Respaldo automático de base de datos
- [ ] Sistema de recuperación de contraseñas
- [ ] Validación de tipos de archivo permitidos para evidencias
- [ ] Escaneo de archivos subidos (antivirus)
- [ ] Límites de tamaño de archivo por tipo de usuario
- [ ] Expiración de sesiones por inactividad
- [ ] Mantener PHP y MySQL actualizados

### Validaciones de archivos de evidencias
```php
// Tipos de archivo permitidos
$tiposPermitidos = ['pdf', 'doc', 'docx', 'jpg', 'png', 'zip'];

// Tamaño máximo: 10MB
$tamanoMaximo = 10 * 1024 * 1024;

// Validar extensión y tamaño antes de guardar
```

## Respaldo y Mantenimiento

### Crear respaldo de la base de datos
```bash
# Respaldo completo
mysqldump -u root -p bdappcompetencias > backup_bdappcompetencias_$(date +%Y%m%d_%H%M%S).sql

# Respaldo solo estructura (sin datos)
mysqldump -u root -p --no-data bdappcompetencias > estructura_bdappcompetencias.sql

# Respaldo solo datos
mysqldump -u root -p --no-create-info bdappcompetencias > datos_bdappcompetencias.sql
```

### Restaurar respaldo
```bash
mysql -u root -p bdappcompetencias < backup_bdappcompetencias_20250101_120000.sql
```

### Programar respaldos automáticos (Windows)

Crea un archivo `backup.bat`:
```batch
@echo off
set fecha=%date:~-4%%date:~3,2%%date:~0,2%
"C:\xampp\mysql\bin\mysqldump.exe" -u root bdappcompetencias > "C:\backups\bdappcompetencias_%fecha%.sql"
```

Programa en Programador de Tareas de Windows para ejecución diaria.

### Limpiar logs de errores
```bash
# En Windows (PowerShell)
Clear-Content php_errors.log

# O simplemente elimina el archivo
del php_errors.log
```

### Limpiar archivos antiguos de evidencias

Revisa periódicamente `uploads/evidencias/` y elimina archivos obsoletos o de evaluaciones completadas de cuatrimestres anteriores.

### Optimizar base de datos
```sql
-- Optimizar todas las tablas
OPTIMIZE TABLE usuarios, estudiantes, docentes, competencias, evaluaciones;

-- Analizar tablas para mejorar rendimiento
ANALYZE TABLE usuarios, estudiantes, docentes, competencias, evaluaciones;

-- Reparar tablas si es necesario
REPAIR TABLE nombre_tabla;
```

## Testing y Validación

### Checklist de pruebas básicas

**Gestión de Usuarios:**
- [ ] Registrar usuario con todos los datos válidos
- [ ] Intentar registrar con contraseña débil (debe fallar)
- [ ] Buscar usuario existente
- [ ] Modificar datos de usuario
- [ ] Eliminar usuario
- [ ] Intentar iniciar sesión con usuario eliminado (debe fallar)

**Inicio de Sesión:**
- [ ] Iniciar sesión con credenciales válidas
- [ ] Intentar con contraseña incorrecta (debe fallar)
- [ ] Verificar redirección según tipo de usuario

**Gestión de Estudiantes:**
- [ ] Registrar estudiante completo
- [ ] Asignar competencias a estudiante
- [ ] Consultar expediente de estudiante
- [ ] Modificar datos académicos

**Gestión de Evaluaciones:**
- [ ] Asignar evidencia a estudiante
- [ ] Subir archivo de evidencia como estudiante
- [ ] Evaluar evidencia con calificaciones por dimensión
- [ ] Verificar que la evaluación se guarde correctamente

**Consultas:**
- [ ] Consultar evaluaciones por alumno
- [ ] Consultar evaluaciones por competencia
- [ ] Verificar filtros de búsqueda

**Reportes:**
- [ ] Generar reporte individual de estudiante
- [ ] Generar reporte grupal comparativo
- [ ] Generar reporte histórico
- [ ] Verificar que los PDFs se descarguen correctamente

## Glosario de Términos

- **Competencia**: Capacidad que integra conocimientos, habilidades y actitudes para resolver problemas en contextos específicos
- **Evidencia**: Documento o producto que demuestra el logro de una competencia
- **Dimensión de Producto**: Evaluación de resultados tangibles creados
- **Asesor**: Docente encargado de evaluar competencias
- **Expediente**: Registro completo de información académica del estudiante
- **Matrícula**: Identificador único del estudiante en el sistema

## Preguntas Frecuentes (FAQ)

**P: ¿Qué navegadores son compatibles?**
R: Chrome, Firefox, Edge y Safari en sus versiones más recientes.

**P: ¿Qué tipos de archivo puedo subir como evidencia?**
R: Generalmente PDF, DOC, DOCX, JPG, PNG y ZIP. Consulta con tu administrador los tipos específicos permitidos.

**P: ¿Cuál es el tamaño máximo de archivo?**
R: Por defecto 10MB, pero puede variar según la configuración del servidor.

**P: ¿Puedo modificar una evaluación después de guardarla?**
R: Solo los docentes con permisos apropiados pueden modificar evaluaciones registradas.

**P: ¿Cuánto tiempo se mantiene el historial de evaluaciones?**
R: El sistema mantiene todo el historial del cuatrimestre actual (Septiembre-Diciembre 2025) y puede archivar datos de cuatrimestres anteriores.

**P: ¿Qué pasa si olvido mi contraseña?**
R: Contacta al administrador del sistema para que restablezca tu contraseña.

**P: ¿Los estudiantes pueden ver las evaluaciones de otros compañeros?**
R: No, cada estudiante solo puede ver sus propias evaluaciones. Solo docentes y administradores pueden ver evaluaciones de todos los estudiantes.

## Documentación Adicional

### Recursos de aprendizaje
- [Documentación de PHP](https://www.php.net/manual/es/)
- [Documentación de MySQL](https://dev.mysql.com/doc/)
- [Documentación de FPDF](http://www.fpdf.org/)
- [Tutorial de MVC en PHP](https://www.php.net/manual/es/intro-whatis.php)

### Documentos del proyecto
- Manual de usuario (administrador)
- Manual de usuario (docente)
- Manual de usuario (estudiante)
- Diccionario de datos de la base de datos
- Diagrama Entidad-Relación

## Contribuciones

Si deseas contribuir al proyecto:

1. Haz un fork del repositorio
2. Crea una rama para tu feature (`git checkout -b feature/NuevaCaracteristica`)
3. Realiza tus cambios y haz commits descriptivos
4. Asegúrate de probar tu código
5. Haz commit de tus cambios (`git commit -m 'Agregué nueva característica X'`)
6. Push a la rama (`git push origin feature/NuevaCaracteristica`)
7. Abre un Pull Request con descripción detallada

### Estándares de código
- Usar nombres descriptivos en variables y funciones
- Comentar código complejo
- Seguir el patrón MVC establecido
- Validar todas las entradas de usuario
- Manejar errores apropiadamente

## Roadmap / Futuras Mejoras

### Versión 1.1 (Planeada)
- [ ] Sistema de notificaciones por correo electrónico
- [ ] Dashboard con gráficas estadísticas
- [ ] Exportación de reportes a Excel
- [ ] Sistema de recuperación de contraseñas
- [ ] Autenticación de dos factores

### Versión 1.2 (Considerada)
- [ ] API REST para integración con otros sistemas
- [ ] Aplicación móvil

### Iniciar Sesion (Administrador): 
- **gmail**: ibaez@upemor.edu.mx
- **contraseña** Hola123@