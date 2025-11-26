<?php
include_once "app/models/EvidenciaModel.php";
include_once "config/db_connection.php";

class EvidenciaController {
    private $model;
    private $uploadDir;
    private $connection; // Añadida propiedad faltante

    public function __construct($connection) {
        $this->connection = $connection; // Guardar conexión
        $this->model = new EvidenciaModel($connection);
        
        // Definir la ruta absoluta del directorio de uploads
        $this->uploadDir = __DIR__ . "/../../uploads/evidencias/";
        
        // Crear directorio si no existe
        if (!file_exists($this->uploadDir)) {
            mkdir($this->uploadDir, 0777, true);
        }
    }

    // Subir evidencia (no asignada, libre)
    public function subirEvidencia() {
        if (isset($_POST['subir'])) {
            // Verificar que el usuario esté logueado
            if (!isset($_SESSION['usuario_id'])) {
                $_SESSION['error'] = "Debe iniciar sesión para subir evidencias";
                header("Location: index.php?controller=auth&action=mostrarLogin");
                exit();
            }

            // OBTENER EL idEstudiantes usando el método del modelo
            $idUsuario = $_SESSION['usuario_id'];
            $idEstudiantes = $this->model->obtenerIdEstudiantePorUsuario($idUsuario);
            
            if (!$idEstudiantes) {
                $_SESSION['error'] = "Su usuario no está registrado como estudiante. Contacte al administrador.";
                header("Location: index.php?controller=evidencia&action=mostrarFormulario");
                exit();
            }
            
            $idCompetencias = $_POST['idCompetencias'];

            // Verificar que se haya seleccionado una competencia
            if (empty($idCompetencias)) {
                $_SESSION['error'] = "Debe seleccionar una competencia";
                header("Location: index.php?controller=evidencia&action=mostrarFormulario");
                exit();
            }

            // Verificar que se haya subido un archivo
            if (!isset($_FILES['archivo']) || $_FILES['archivo']['error'] !== UPLOAD_ERR_OK) {
                $_SESSION['error'] = "Error al subir el archivo. Por favor intente nuevamente.";
                header("Location: index.php?controller=evidencia&action=mostrarFormulario");
                exit();
            }

            $archivo = $_FILES['archivo'];
            $nombreOriginal = $archivo['name'];
            $tmpName = $archivo['tmp_name'];
            $tamano = $archivo['size'];
            $tipo = $archivo['type'];

            // Validar tamaño (máximo 10MB)
            $maxSize = 10 * 1024 * 1024;
            if ($tamano > $maxSize) {
                $_SESSION['error'] = "El archivo es demasiado grande. Tamaño máximo: 10MB";
                header("Location: index.php?controller=evidencia&action=mostrarFormulario");
                exit();
            }

            // Validar extensión
            $extensionesPermitidas = ['pdf', 'doc', 'docx', 'xls', 'xlsx', 'jpg', 'jpeg', 'png'];
            $extension = strtolower(pathinfo($nombreOriginal, PATHINFO_EXTENSION));
            
            if (!in_array($extension, $extensionesPermitidas)) {
                $_SESSION['error'] = "Tipo de archivo no permitido. Formatos soportados: PDF, DOC, DOCX, XLS, XLSX, JPG, PNG";
                header("Location: index.php?controller=evidencia&action=mostrarFormulario");
                exit();
            }

            // Generar nombre único para el archivo
            $nombreUnico = uniqid() . '_' . time() . '.' . $extension;
            $rutaDestino = $this->uploadDir . $nombreUnico;

            // Mover el archivo al directorio de destino
            if (move_uploaded_file($tmpName, $rutaDestino)) {
                // Guardar información en la base de datos
                $resultado = $this->model->insertarEvidencia(
                    $idEstudiantes,
                    $idCompetencias,
                    $nombreOriginal,
                    $rutaDestino,
                    $tipo,
                    $tamano
                );

                if ($resultado) {
                    $_SESSION['success'] = "Evidencia subida exitosamente";
                    header("Location: index.php?controller=evidencia&action=misEvidencias");
                } else {
                    // Si falla la BD, eliminar el archivo subido
                    unlink($rutaDestino);
                    $_SESSION['error'] = "Error al guardar la evidencia en la base de datos";
                    header("Location: index.php?controller=evidencia&action=mostrarFormulario");
                }
            } else {
                $_SESSION['error'] = "Error al guardar el archivo en el servidor";
                header("Location: index.php?controller=evidencia&action=mostrarFormulario");
            }
            exit();
        }
    }

    // Mostrar formulario de subida
    public function mostrarFormulario() {
        if (!isset($_SESSION['usuario_id'])) {
            header("Location: index.php?controller=auth&action=mostrarLogin");
            exit();
        }
        
        $competencias = $this->model->obtenerCompetencias();
        include_once "app/views/formulario_subida.php";
    }

    // Ver mis evidencias
    public function misEvidencias() {
        if (!isset($_SESSION['usuario_id'])) {
            header("Location: index.php?controller=auth&action=mostrarLogin");
            exit();
        }

        // Obtener el idEstudiantes usando el método del modelo
        $idUsuario = $_SESSION['usuario_id'];
        $idEstudiantes = $this->model->obtenerIdEstudiantePorUsuario($idUsuario);
        
        if ($idEstudiantes) {
            $evidencias = $this->model->obtenerEvidenciasPorEstudiante($idEstudiantes);
            include_once "app/views/mis_evidencias.php";
        } else {
            $_SESSION['error'] = "No se encontró registro de estudiante";
            header("Location: index.php?controller=auth&action=panelEstudiante");
            exit();
        }
    }

    // Eliminar evidencia
    public function eliminarEvidencia() {
        if (!isset($_SESSION['usuario_id']) || !isset($_GET['id'])) {
            header("Location: index.php?controller=auth&action=mostrarLogin");
            exit();
        }

        $idEvidencia = (int)$_GET['id'];
        
        // Obtener idEstudiantes usando el método del modelo
        $idUsuario = $_SESSION['usuario_id'];
        $idEstudiantes = $this->model->obtenerIdEstudiantePorUsuario($idUsuario);
        
        if (!$idEstudiantes) {
            $_SESSION['error'] = "No tiene permisos para eliminar esta evidencia";
            header("Location: index.php?controller=evidencia&action=misEvidencias");
            exit();
        }
        
        // Obtener datos de la evidencia
        $evidencia = $this->model->obtenerEvidenciaPorId($idEvidencia);
        
        if ($evidencia && $evidencia['idEstudiantes'] == $idEstudiantes) {
            // Eliminar archivo físico
            if (file_exists($evidencia['RutaArchivo'])) {
                unlink($evidencia['RutaArchivo']);
            }
            
            // Eliminar de la base de datos
            if ($this->model->eliminarEvidencia($idEvidencia)) {
                $_SESSION['success'] = "Evidencia eliminada exitosamente";
            } else {
                $_SESSION['error'] = "Error al eliminar la evidencia";
            }
        } else {
            $_SESSION['error'] = "No tiene permisos para eliminar esta evidencia";
        }

        header("Location: index.php?controller=evidencia&action=misEvidencias");
        exit();
    }

    // Descargar evidencia
    public function descargarEvidencia() {
        if (!isset($_SESSION['usuario_id']) || !isset($_GET['id'])) {
            header("Location: index.php?controller=auth&action=mostrarLogin");
            exit();
        }

        $idEvidencia = (int)$_GET['id'];
        $evidencia = $this->model->obtenerEvidenciaPorId($idEvidencia);

        if ($evidencia && file_exists($evidencia['RutaArchivo'])) {
            // Configurar headers para descarga
            header('Content-Description: File Transfer');
            header('Content-Type: ' . $evidencia['TipoArchivo']);
            header('Content-Disposition: attachment; filename="' . $evidencia['NombreArchivo'] . '"');
            header('Content-Length: ' . $evidencia['TamanoArchivo']);
            header('Pragma: public');
            
            // Limpiar el buffer de salida
            ob_clean();
            flush();
            
            // Leer y enviar el archivo
            readfile($evidencia['RutaArchivo']);
            exit();
        } else {
            $_SESSION['error'] = "Archivo no encontrado";
            header("Location: index.php?controller=evidencia&action=misEvidencias");
            exit();
        }
    }

    // CORRECCIÓN AQUÍ: Esta función ahora está DENTRO de la clase
    public function subirAsignada() {
        if (session_status() === PHP_SESSION_NONE) {
            session_start();
        }
        
        if (!isset($_SESSION['usuario_id']) || $_SERVER['REQUEST_METHOD'] !== 'POST') {
            header("Location: index.php?controller=estudiante&action=evidenciasPendientes");
            exit();
        }
        
        // Obtener datos del estudiante
        $idUsuario = $_SESSION['usuario_id'];
        $sqlEstudiante = "SELECT idEstudiantes FROM estudiantes WHERE idUsuarios = ?";
        $stmt = $this->connection->prepare($sqlEstudiante);
        $stmt->bind_param("i", $idUsuario);
        $stmt->execute();
        $resultEst = $stmt->get_result();
        
        if($rowEst = $resultEst->fetch_assoc()) {
            $idEstudiante = $rowEst['idEstudiantes'];
            $idAsignacion = intval($_POST['idAsignacion']);
            $idCompetencias = intval($_POST['idCompetencias']);
            
            // Validar que el archivo se haya subido
            if (!isset($_FILES['archivo']) || $_FILES['archivo']['error'] !== UPLOAD_ERR_OK) {
                $_SESSION['error'] = "Error al subir el archivo. Intenta nuevamente.";
                header("Location: index.php?controller=estudiante&action=evidenciasPendientes");
                exit();
            }
            
            $archivo = $_FILES['archivo'];
            $nombreArchivo = $archivo['name'];
            $tipoArchivo = $archivo['type'];
            $tamanoArchivo = $archivo['size'];
            $tmpName = $archivo['tmp_name'];
            
            // Validar tamaño (10MB máximo)
            if ($tamanoArchivo > 10485760) {
                $_SESSION['error'] = "El archivo es demasiado grande. Máximo 10MB.";
                header("Location: index.php?controller=estudiante&action=evidenciasPendientes");
                exit();
            }
            
            // Validar tipo de archivo
            $extensionesPermitidas = ['pdf', 'doc', 'docx', 'jpg', 'jpeg', 'png', 'ppt', 'pptx'];
            $extension = strtolower(pathinfo($nombreArchivo, PATHINFO_EXTENSION));
            
            if (!in_array($extension, $extensionesPermitidas)) {
                $_SESSION['error'] = "Formato de archivo no permitido.";
                header("Location: index.php?controller=estudiante&action=evidenciasPendientes");
                exit();
            }
            
            // Crear directorio si no existe
            $directorioBase = __DIR__ . '/../../uploads/evidencias/';
            if (!file_exists($directorioBase)) {
                mkdir($directorioBase, 0777, true);
            }
            
            // Generar nombre único para el archivo
            $nombreUnico = uniqid() . '_' . time() . '.' . $extension;
            $rutaCompleta = $directorioBase . $nombreUnico;
            
            // Mover archivo
            if (move_uploaded_file($tmpName, $rutaCompleta)) {
                // Insertar en la tabla evidencias
                $sqlInsert = "INSERT INTO evidencias (idEstudiantes, idCompetencias, idAsignacion, NombreArchivo, RutaArchivo, TipoArchivo, TamanoArchivo, Estado) 
                              VALUES (?, ?, ?, ?, ?, ?, ?, 'Pendiente')";
                $stmt = $this->connection->prepare($sqlInsert);
                $stmt->bind_param("iiisssi", $idEstudiante, $idCompetencias, $idAsignacion, $nombreArchivo, $rutaCompleta, $tipoArchivo, $tamanoArchivo);
                
                if ($stmt->execute()) {
                    $idEvidencia = $this->connection->insert_id;
                    
                    // Actualizar la asignación
                    $sqlUpdate = "UPDATE asignaciones_evidencias SET Estado = 'Entregada', idEvidencia = ? WHERE idAsignacion = ?";
                    $stmt = $this->connection->prepare($sqlUpdate);
                    $stmt->bind_param("ii", $idEvidencia, $idAsignacion);
                    $stmt->execute();
                    
                    $_SESSION['success'] = "¡Evidencia subida exitosamente! Tu docente la revisará pronto.";
                } else {
                    $_SESSION['error'] = "Error al guardar la evidencia en la base de datos.";
                }
            } else {
                $_SESSION['error'] = "Error al guardar el archivo en el servidor.";
            }
        }
        
        header("Location: index.php?controller=estudiante&action=evidenciasPendientes");
        exit();
    }
} // <--- ESTA ES LA LLAVE DE CIERRE CORRECTA DE LA CLASE
?>