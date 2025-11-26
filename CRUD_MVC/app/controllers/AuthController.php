<?php
include_once "app/models/UserModel.php";

class AuthController {
    private $connection;
    private $userModel;

    public function __construct($connection) {
        $this->connection = $connection;
        $this->userModel = new UserModel($connection);
    }

    // Mostrar formulario de login
    public function mostrarLogin() {
        if(isset($_SESSION['usuario_id'])) {
            $this->redirigirSegunTipo($_SESSION['tipo_usuario']);
            exit();
        }
        include_once "app/views/auth/login.php";
    }

    // Procesar login
    public function login() {
        if(isset($_POST['login'])) {
            $correo = trim($_POST['correo']);
            $contrasena = $_POST['contrasena'];

            // Debug mejorado
            error_log("=== INTENTO DE LOGIN ===");
            error_log("Correo recibido: " . $correo);
            error_log("Longitud contraseña: " . strlen($contrasena));

            // Buscar usuario
            $sql = "SELECT * FROM usuarios WHERE Correo = ?";
            $stmt = $this->connection->prepare($sql);
            
            if (!$stmt) {
                error_log("Error en prepare: " . $this->connection->error);
                $_SESSION['error_login'] = "Error del sistema. Intente más tarde.";
                header("Location: index.php?controller=auth&action=mostrarLogin");
                exit();
            }
            
            $stmt->bind_param("s", $correo);
            $stmt->execute();
            $result = $stmt->get_result();

            if($result->num_rows > 0) {
                $usuario = $result->fetch_assoc();
                
                error_log("Usuario encontrado: " . $usuario['Nombre']);
                error_log("Tipo: " . $usuario['TipoUsuario']);
                error_log("Hash en BD: " . substr($usuario['Contrasena'], 0, 20) . "...");
                
                // Verificar contraseña
                if(password_verify($contrasena, $usuario['Contrasena'])) {
                    error_log("✓ Contraseña verificada correctamente");
                    
                    // Establecer variables de sesión
                    $_SESSION['usuario_id'] = $usuario['idUsuarios'];
                    $_SESSION['usuario_nombre'] = $usuario['Nombre'];
                    $_SESSION['usuario_apellido'] = $usuario['Apellido'];
                    $_SESSION['tipo_usuario'] = $usuario['TipoUsuario'];
                    $_SESSION['correo'] = $usuario['Correo'];

                    error_log("Sesión establecida. ID: " . $_SESSION['usuario_id']);
                    error_log("Redirigiendo a: " . $usuario['TipoUsuario']);

                    // Redirección con verificación
                    $this->redirigirSegunTipo($usuario['TipoUsuario']);
                    exit();
                    
                } else {
                    error_log("✗ Contraseña incorrecta");
                    $_SESSION['error_login'] = "Contraseña incorrecta";
                    header("Location: index.php?controller=auth&action=mostrarLogin");
                    exit();
                }
            } else {
                error_log("✗ Usuario no encontrado con correo: " . $correo);
                $_SESSION['error_login'] = "Usuario no encontrado";
                header("Location: index.php?controller=auth&action=mostrarLogin");
                exit();
            }
        } else {
            error_log("No se recibió POST['login']");
            header("Location: index.php?controller=auth&action=mostrarLogin");
            exit();
        }
    }

    // Redirección según tipo de usuario
    private function redirigirSegunTipo($tipoUsuario) {
        error_log("=== REDIRIGIENDO ===");
        error_log("Tipo recibido: " . $tipoUsuario);
        
        // Limpiar el tipo (por si hay espacios o caracteres raros)
        $tipoUsuario = trim($tipoUsuario);
        
        switch($tipoUsuario) {
            case 'Administrador':
                error_log("→ Redirigiendo a panel administrativo");
                header("Location: index.php?controller=auth&action=panelAdministrativo");
                break;
                
            case 'Docente':
                error_log("→ Redirigiendo a panel docente");
                header("Location: index.php?controller=auth&action=panelDocente");
                break;
                
            case 'Estudiante':
                error_log("→ Redirigiendo a panel estudiante");
                header("Location: index.php?controller=auth&action=panelEstudiante");
                break;
                
            default:
                error_log("✗ Tipo de usuario desconocido: [" . $tipoUsuario . "]");
                $_SESSION['error_login'] = "Tipo de usuario no válido";
                header("Location: index.php?controller=auth&action=mostrarLogin");
        }
        
        exit();
    }

    // Panel Administrativo
    public function panelAdministrativo() {
        error_log("=== ACCEDIENDO A PANEL ADMINISTRATIVO ===");
        error_log("Sesión usuario_id: " . (isset($_SESSION['usuario_id']) ? $_SESSION['usuario_id'] : 'NO SET'));
        error_log("Sesión tipo_usuario: " . (isset($_SESSION['tipo_usuario']) ? $_SESSION['tipo_usuario'] : 'NO SET'));
        
        if(!isset($_SESSION['usuario_id']) || $_SESSION['tipo_usuario'] != 'Administrador') {
            error_log("✗ Acceso denegado a panel administrativo");
            header("Location: index.php?controller=auth&action=mostrarLogin");
            exit();
        }
        
        error_log("✓ Acceso permitido - Cargando vista");
        include_once "app/views/panelAdministrativo.php";
    }

    // Panel Docente
    public function panelDocente() {
        error_log("=== ACCEDIENDO A PANEL DOCENTE ===");
        error_log("Sesión usuario_id: " . (isset($_SESSION['usuario_id']) ? $_SESSION['usuario_id'] : 'NO SET'));
        error_log("Sesión tipo_usuario: " . (isset($_SESSION['tipo_usuario']) ? $_SESSION['tipo_usuario'] : 'NO SET'));
        
        if(!isset($_SESSION['usuario_id']) || $_SESSION['tipo_usuario'] != 'Docente') {
            error_log("✗ Acceso denegado a panel docente");
            header("Location: index.php?controller=auth&action=mostrarLogin");
            exit();
        }
        
        error_log("✓ Acceso permitido - Cargando vista");
        include_once "app/views/panelDocente.php";
    }

    // Panel Estudiante
    public function panelEstudiante() {
        error_log("=== ACCEDIENDO A PANEL ESTUDIANTE ===");
        error_log("Sesión usuario_id: " . (isset($_SESSION['usuario_id']) ? $_SESSION['usuario_id'] : 'NO SET'));
        error_log("Sesión tipo_usuario: " . (isset($_SESSION['tipo_usuario']) ? $_SESSION['tipo_usuario'] : 'NO SET'));
        
        if(!isset($_SESSION['usuario_id']) || $_SESSION['tipo_usuario'] != 'Estudiante') {
            error_log("✗ Acceso denegado a panel estudiante");
            header("Location: index.php?controller=auth&action=mostrarLogin");
            exit();
        }
        
        error_log("✓ Acceso permitido - Cargando vista");
        include_once "app/views/panelEstudiantes.php";
    }

    // Mostrar formulario de registro
    public function mostrarRegistro() {
        include_once "app/views/auth/registro.php";
    }

    // Registro directo (desde panel administrativo)
    public function registroDirecto() {
        if(!isset($_SESSION['usuario_id'])) {
            header("Location: index.php?controller=auth&action=mostrarLogin");
            exit();
        }

        $tipoUsuario = isset($_GET['tipo']) ? $_GET['tipo'] : 'Estudiante';
        include_once "app/views/auth/registroDirecto.php";
    }

    // Procesar registro - MÉTODO CORREGIDO
    public function registro() {
        try {
            // Verificar que sea POST con el botón 'registrar'
            if (!isset($_POST['registrar'])) {
                error_log("No se recibió POST['registrar']");
                header("Location: index.php?controller=auth&action=mostrarLogin");
                exit();
            }

            // Datos generales
            $tipoUsuario = trim($_POST['TipoUsuario'] ?? '');
            $nombre = trim($_POST['Nombre'] ?? '');
            $apellido = trim($_POST['Apellido'] ?? '');
            $correo = trim($_POST['Correo'] ?? '');
            $telefono = trim($_POST['Telefono'] ?? '');
            $fechaNac = $_POST['FechaNac'] ?? '';
            $sexo = $_POST['Sexo'] ?? '';
            $contrasena = $_POST['Contrasena'] ?? '';
            $confirmarContrasena = $_POST['ConfirmarContrasena'] ?? '';

            error_log("=== REGISTRO NUEVO USUARIO ===");
            error_log("Tipo: " . $tipoUsuario);
            error_log("Nombre: " . $nombre);
            error_log("Correo: " . $correo);

            // Validaciones básicas
            if (empty($tipoUsuario) || empty($nombre) || empty($apellido) || 
                empty($correo) || empty($contrasena)) {
                error_log("✗ Campos obligatorios vacíos");
                $_SESSION['error'] = 'Todos los campos obligatorios deben ser completados.';
                header("Location: index.php?controller=auth&action=registroDirecto&tipo=" . urlencode($tipoUsuario));
                exit();
            }

            // Validar que las contraseñas coincidan
            if ($contrasena !== $confirmarContrasena) {
                error_log("✗ Contraseñas no coinciden");
                $_SESSION['error'] = 'Las contraseñas no coinciden.';
                header("Location: index.php?controller=auth&action=registroDirecto&tipo=" . urlencode($tipoUsuario));
                exit();
            }

            // Verificar si el correo ya existe
            $stmtCheck = $this->connection->prepare("SELECT idUsuarios FROM usuarios WHERE Correo = ?");
            $stmtCheck->bind_param("s", $correo);
            $stmtCheck->execute();
            $stmtCheck->store_result();
            
            if ($stmtCheck->num_rows > 0) {
                error_log("✗ Correo ya registrado: " . $correo);
                $_SESSION['error'] = 'El correo electrónico ya está registrado.';
                $stmtCheck->close();
                header("Location: index.php?controller=auth&action=registroDirecto&tipo=" . urlencode($tipoUsuario));
                exit();
            }
            $stmtCheck->close();

            // Hashear la contraseña
            $contrasenaHash = password_hash($contrasena, PASSWORD_BCRYPT);

            // Iniciar transacción
            $this->connection->begin_transaction();

            // Insertar en tabla usuarios
            $stmtUsuario = $this->connection->prepare(
                "INSERT INTO usuarios (Nombre, Apellido, Correo, Contrasena, Telefono, FechaNac, Sexo, TipoUsuario) 
                 VALUES (?, ?, ?, ?, ?, ?, ?, ?)"
            );
            
            $stmtUsuario->bind_param(
                "ssssssss", 
                $nombre, $apellido, $correo, $contrasenaHash, $telefono, $fechaNac, $sexo, $tipoUsuario
            );

            if (!$stmtUsuario->execute()) {
                throw new Exception("Error al registrar usuario: " . $stmtUsuario->error);
            }

            $usuarioID = $this->connection->insert_id;
            error_log("✓ Usuario creado con ID: " . $usuarioID);
            $stmtUsuario->close();

            // Insertar en tabla específica según tipo de usuario
            if ($tipoUsuario === 'Estudiante') {
                $matricula = trim($_POST['Matricula'] ?? '');
                $grupo = trim($_POST['Grupo'] ?? '');
                $estadoAcademico = 'Activo';

                // Verificar que la matrícula no exista
                $stmtCheckMat = $this->connection->prepare("SELECT idEstudiantes FROM estudiantes WHERE Matricula = ?");
                $stmtCheckMat->bind_param("s", $matricula);
                $stmtCheckMat->execute();
                $stmtCheckMat->store_result();
                
                if ($stmtCheckMat->num_rows > 0) {
                    $stmtCheckMat->close();
                    throw new Exception("La matrícula ya está registrada.");
                }
                $stmtCheckMat->close();

                $stmtEstudiante = $this->connection->prepare(
                    "INSERT INTO estudiantes (idUsuarios, Matricula, Grupo, EstadoAcademico) 
                     VALUES (?, ?, ?, ?)"
                );
                $stmtEstudiante->bind_param("isss", $usuarioID, $matricula, $grupo, $estadoAcademico);
                
                if (!$stmtEstudiante->execute()) {
                    throw new Exception("Error al registrar estudiante: " . $stmtEstudiante->error);
                }
                error_log("✓ Estudiante registrado");
                $stmtEstudiante->close();

            } elseif ($tipoUsuario === 'Docente') {
                $especialidad = trim($_POST['Especialidad'] ?? '');
                $materias = trim($_POST['Materias'] ?? '');

                $stmtDocente = $this->connection->prepare(
                    "INSERT INTO docentes (idUsuarios, Especialidad, MateriaImpartida) 
                     VALUES (?, ?, ?)"
                );
                $stmtDocente->bind_param("iss", $usuarioID, $especialidad, $materias);
                
                if (!$stmtDocente->execute()) {
                    throw new Exception("Error al registrar docente: " . $stmtDocente->error);
                }
                error_log("✓ Docente registrado");
                $stmtDocente->close();

            } elseif ($tipoUsuario === 'Administrador') {
                $numeroEmpleado = trim($_POST['NumeroEmpleado'] ?? '');
                $departamento = trim($_POST['Departamento'] ?? '');
                $cargo = trim($_POST['Cargo'] ?? '');

                // Verificar que el número de empleado no exista
                $stmtCheckEmp = $this->connection->prepare("SELECT idAdministrador FROM administradores WHERE NumeroEmpleado = ?");
                $stmtCheckEmp->bind_param("s", $numeroEmpleado);
                $stmtCheckEmp->execute();
                $stmtCheckEmp->store_result();
                
                if ($stmtCheckEmp->num_rows > 0) {
                    $stmtCheckEmp->close();
                    throw new Exception("El número de empleado ya está registrado.");
                }
                $stmtCheckEmp->close();

                $stmtAdmin = $this->connection->prepare(
                    "INSERT INTO administradores (idUsuarios, NumeroEmpleado, Departamento, Cargo) 
                     VALUES (?, ?, ?, ?)"
                );
                $stmtAdmin->bind_param("isss", $usuarioID, $numeroEmpleado, $departamento, $cargo);
                
                if (!$stmtAdmin->execute()) {
                    throw new Exception("Error al registrar administrador: " . $stmtAdmin->error);
                }
                error_log("✓ Administrador registrado");
                $stmtAdmin->close();
            }

            // Confirmar transacción
            $this->connection->commit();
            error_log("✓ Transacción completada exitosamente");

            // Éxito - redirigir al dashboard
            $_SESSION['success'] = "Usuario de tipo {$tipoUsuario} registrado exitosamente.";
            header("Location: index.php?controller=auth&action=panelAdministrativo");
            exit();

        } catch (Exception $e) {
            // Revertir transacción en caso de error
            if ($this->connection) {
                $this->connection->rollback();
            }
            
            error_log("✗ Error en registro: " . $e->getMessage());
            $_SESSION['error'] = 'Error al registrar usuario: ' . $e->getMessage();
            
            $tipoUsuario = $_POST['TipoUsuario'] ?? 'Estudiante';
            header("Location: index.php?controller=auth&action=registroDirecto&tipo=" . urlencode($tipoUsuario));
            exit();
        }
    }

    // Logout
    public function logout() {
        error_log("=== LOGOUT ===");
        error_log("Usuario: " . (isset($_SESSION['usuario_nombre']) ? $_SESSION['usuario_nombre'] : 'desconocido'));
        session_destroy();
        header("Location: index.php?controller=auth&action=mostrarLogin");
        exit();
    }
}
?>