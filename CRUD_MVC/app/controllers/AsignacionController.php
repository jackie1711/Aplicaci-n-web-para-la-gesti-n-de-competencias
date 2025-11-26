<?php
// app/controllers/AsignacionController.php

class AsignacionController {
    private $connection;
    
    public function __construct($connection) {
        $this->connection = $connection;
    }
    
    // Listar y gestionar asignaciones (Vista Admin/Docente)
    public function listar() {
        if (session_status() === PHP_SESSION_NONE) {
            session_start();
        }
        
        if (!isset($_SESSION['usuario_id'])) {
            header("Location: index.php?controller=auth&action=login");
            exit();
        }
        
        $idUsuario = $_SESSION['usuario_id'];
        $tipoUsuario = $_SESSION['tipo_usuario']; // Obtenemos el tipo de usuario
        $idDocente = null;

        // Lógica diferenciada para la consulta SQL
        $sqlAsignaciones = "SELECT 
                                a.idAsignacion,
                                a.NombreEvidencia,
                                a.Descripcion,
                                a.FechaAsignacion,
                                a.FechaLimite,
                                a.Estado,
                                CONCAT(u.Nombre, ' ', u.Apellido) as NombreEstudiante,
                                est.Matricula,
                                est.idEstudiantes,
                                c.NombreCompetencia,
                                c.idCompetencias,
                                ev.idEvidencias,
                                ev.NombreArchivo,
                                ev.RutaArchivo,
                                ev.FechaSubida,
                                eval.Calificacion,
                                eval.Observaciones as ObservacionesEval
                            FROM asignaciones_evidencias a
                            INNER JOIN estudiantes est ON a.idEstudiantes = est.idEstudiantes
                            INNER JOIN usuarios u ON est.idUsuarios = u.idUsuarios
                            INNER JOIN competencias c ON a.idCompetencias = c.idCompetencias
                            LEFT JOIN evidencias ev ON a.idEvidencia = ev.idEvidencias
                            LEFT JOIN evaluaciones eval ON eval.idEstudiantes = est.idEstudiantes 
                                AND eval.idCompetencias = c.idCompetencias
                                AND eval.idEvaluaciones = (
                                    SELECT MAX(e2.idEvaluaciones) 
                                    FROM evaluaciones e2 
                                    WHERE e2.idEstudiantes = est.idEstudiantes 
                                    AND e2.idCompetencias = c.idCompetencias
                                )";

        // SI ES DOCENTE: Filtramos por su ID
        if ($tipoUsuario === 'Docente') {
            $sqlDocente = "SELECT idDocentes FROM docentes WHERE idUsuarios = ?";
            $stmt = $this->connection->prepare($sqlDocente);
            $stmt->bind_param("i", $idUsuario);
            $stmt->execute();
            $resultDocente = $stmt->get_result();
            
            if ($rowDocente = $resultDocente->fetch_assoc()) {
                $idDocente = $rowDocente['idDocentes'];
                $sqlAsignaciones .= " WHERE a.idDocentes = ?"; // Agregamos filtro
            } else {
                $_SESSION['error'] = "No se encontró información del docente";
                header("Location: index.php?controller=auth&action=panelAdministrativo");
                exit();
            }
        }
        // SI ES ADMINISTRADOR: No agregamos WHERE (ve todo)

        // Ordenamiento común
        $sqlAsignaciones .= " ORDER BY 
                                CASE a.Estado
                                    WHEN 'Entregada' THEN 1
                                    WHEN 'Pendiente' THEN 2
                                    WHEN 'Evaluada' THEN 3
                                END,
                                a.FechaLimite ASC,
                                a.FechaAsignacion DESC";
        
        // Ejecutar consulta según el tipo
        $stmt = $this->connection->prepare($sqlAsignaciones);
        
        if ($tipoUsuario === 'Docente' && $idDocente) {
            $stmt->bind_param("i", $idDocente);
        }
        
        $stmt->execute();
        $asignaciones = $stmt->get_result();
        
        // Obtener datos auxiliares para los modales (Estudiantes y Competencias)
        $estudiantes = $this->connection->query("SELECT 
                                                    est.idEstudiantes, 
                                                    est.Matricula, 
                                                    CONCAT(u.Nombre, ' ', u.Apellido) as NombreCompleto 
                                                 FROM estudiantes est 
                                                 INNER JOIN usuarios u ON est.idUsuarios = u.idUsuarios 
                                                 WHERE est.EstadoAcademico = 'Activo' 
                                                 ORDER BY u.Apellido, u.Nombre");
        
        $competencias = $this->connection->query("SELECT idCompetencias, NombreCompetencia, Descripcion 
                                                  FROM competencias 
                                                  ORDER BY NombreCompetencia");
        
        include_once "app/views/asignaciones/listar.php";
    }
    
    // Crear nueva asignación
    public function crear() {
        session_start();
        
        if (!isset($_SESSION['usuario_id']) || $_SERVER['REQUEST_METHOD'] !== 'POST') {
            header("Location: index.php?controller=asignacion&action=listar");
            exit();
        }
        
        // Lógica para obtener quién asigna
        $idUsuario = $_SESSION['usuario_id'];
        $tipoUsuario = $_SESSION['usuario_tipo'];
        $idDocente = null;

        if ($tipoUsuario === 'Administrador') {
            // Si un admin crea la tarea, necesitamos asignarla a un docente.
            // Por ahora, para evitar errores, asignaremos al "primer docente" que encontremos o lanzaremos error.
            // Lo ideal sería tener un <select> de docentes en el formulario si eres admin.
            // Para simplicidad, buscaremos si el admin también es docente o asignaremos a un docente por defecto (ej. ID 1)
            // OJO: Si quieres que el admin asigne, debes modificar el formulario para elegir docente.
            // Aquí haremos un fallback simple:
             $res = $this->connection->query("SELECT idDocentes FROM docentes LIMIT 1");
             if($r = $res->fetch_assoc()) $idDocente = $r['idDocentes'];
        } else {
            // Es docente
            $sqlDocente = "SELECT idDocentes FROM docentes WHERE idUsuarios = ?";
            $stmt = $this->connection->prepare($sqlDocente);
            $stmt->bind_param("i", $idUsuario);
            $stmt->execute();
            $resultDocente = $stmt->get_result();
            if ($rowDocente = $resultDocente->fetch_assoc()) {
                $idDocente = $rowDocente['idDocentes'];
            }
        }
        
        if ($idDocente) {
            $nombreEvidencia = trim($_POST['nombreEvidencia']);
            $descripcion = trim($_POST['descripcion']);
            $fechaLimite = $_POST['fechaLimite'];
            $idEstudiante = intval($_POST['idEstudiante']);
            $idCompetencia = intval($_POST['idCompetencia']);
            
            $fechaActual = new DateTime();
            $fechaLimiteObj = new DateTime($fechaLimite);
            
            if ($fechaLimiteObj <= $fechaActual) {
                $_SESSION['error'] = "La fecha límite debe ser posterior a la fecha actual";
                header("Location: index.php?controller=asignacion&action=listar");
                exit();
            }
            
            $sql = "INSERT INTO asignaciones_evidencias 
                    (NombreEvidencia, Descripcion, FechaLimite, idEstudiantes, idDocentes, idCompetencias, Estado) 
                    VALUES (?, ?, ?, ?, ?, ?, 'Pendiente')";
            $stmt = $this->connection->prepare($sql);
            $stmt->bind_param("sssiii", $nombreEvidencia, $descripcion, $fechaLimite, $idEstudiante, $idDocente, $idCompetencia);
            
            if ($stmt->execute()) {
                $_SESSION['success'] = "Evidencia asignada correctamente.";
            } else {
                $_SESSION['error'] = "Error al asignar evidencia: " . $stmt->error;
            }
        } else {
            $_SESSION['error'] = "No se pudo identificar al docente para asignar la evidencia.";
        }
        
        header("Location: index.php?controller=asignacion&action=listar");
        exit();
    }
    
    // Evaluar evidencia subida
    public function evaluar() {
        session_start();
        
        if (!isset($_SESSION['usuario_id']) || $_SERVER['REQUEST_METHOD'] !== 'POST') {
            header("Location: index.php?controller=asignacion&action=listar");
            exit();
        }
        
        $idAsignacion = intval($_POST['idAsignacion']);
        $idEstudiante = intval($_POST['idEstudiante']);
        $idCompetencia = intval($_POST['idCompetencia']);
        $calificacion = trim($_POST['calificacion']);
        $observaciones = trim($_POST['observaciones']);
        
        // Obtener ID del docente (o permitir si es admin)
        $idUsuario = $_SESSION['usuario_id'];
        $tipoUsuario = $_SESSION['usuario_tipo'];
        $idDocente = 0;

        if($tipoUsuario === 'Administrador'){
             // Si es admin, obtenemos el ID del docente original de la asignación para mantener integridad
             $q = $this->connection->query("SELECT idDocentes FROM asignaciones_evidencias WHERE idAsignacion = $idAsignacion");
             if($r = $q->fetch_assoc()) $idDocente = $r['idDocentes'];
        } else {
            $sqlDocente = "SELECT idDocentes FROM docentes WHERE idUsuarios = ?";
            $stmt = $this->connection->prepare($sqlDocente);
            $stmt->bind_param("i", $idUsuario);
            $stmt->execute();
            $resultDocente = $stmt->get_result();
            if ($rowDocente = $resultDocente->fetch_assoc()) {
                $idDocente = $rowDocente['idDocentes'];
            }
        }

        if (!$idDocente) {
             $_SESSION['error'] = "Error de permisos de docente.";
             header("Location: index.php?controller=asignacion&action=listar");
             exit();
        }

        try {
            $this->connection->begin_transaction();
            
            // Verificar asignación
            $sqlVerif = "SELECT idEvidencia, Estado FROM asignaciones_evidencias WHERE idAsignacion = ?";
            $stmt = $this->connection->prepare($sqlVerif);
            $stmt->bind_param("i", $idAsignacion);
            $stmt->execute();
            $resultVerif = $stmt->get_result();
            $rowVerif = $resultVerif->fetch_assoc();
            
            if (!$rowVerif || $rowVerif['Estado'] !== 'Entregada') {
                throw new Exception("Estado incorrecto para evaluar");
            }
            
            $idEvidencia = $rowVerif['idEvidencia'];
            
            // 1. Actualizar asignación
            $sqlUpdateAsig = "UPDATE asignaciones_evidencias SET Estado = 'Evaluada' WHERE idAsignacion = ?";
            $stmt = $this->connection->prepare($sqlUpdateAsig);
            $stmt->bind_param("i", $idAsignacion);
            $stmt->execute();
            
            // 2. Crear evaluación
            $sqlEval = "INSERT INTO evaluaciones (Fecha, Observaciones, TipoEvaluacion, Calificacion, idEstudiantes, idDocentes, idCompetencias) VALUES (CURDATE(), ?, 'Producto', ?, ?, ?, ?)";
            $stmt = $this->connection->prepare($sqlEval);
            $stmt->bind_param("ssiii", $observaciones, $calificacion, $idEstudiante, $idDocente, $idCompetencia);
            $stmt->execute();
            
            // 3. Actualizar evidencia
            $estadoEvidencia = ($calificacion === 'Deficiente') ? 'Rechazada' : 'Aprobada';
            $sqlEvid = "UPDATE evidencias SET Estado = ?, Observaciones = ? WHERE idEvidencias = ?";
            $stmt = $this->connection->prepare($sqlEvid);
            $stmt->bind_param("ssi", $estadoEvidencia, $observaciones, $idEvidencia);
            $stmt->execute();
            
            $this->connection->commit();
            $_SESSION['success'] = "Evidencia evaluada correctamente.";
            
        } catch (Exception $e) {
            $this->connection->rollback();
            $_SESSION['error'] = "Error al evaluar: " . $e->getMessage();
        }
        
        header("Location: index.php?controller=asignacion&action=listar");
        exit();
    }
    
    // Eliminar asignación
    public function eliminar() {
        session_start();
        if (!isset($_GET['id'])) { header("Location: index.php"); exit(); }
        
        $idAsignacion = intval($_GET['id']);
        $sql = "DELETE FROM asignaciones_evidencias WHERE idAsignacion = ?";
        $stmt = $this->connection->prepare($sql);
        $stmt->bind_param("i", $idAsignacion);
        
        if ($stmt->execute()) $_SESSION['success'] = "Asignación eliminada";
        else $_SESSION['error'] = "Error al eliminar";
        
        header("Location: index.php?controller=asignacion&action=listar");
        exit();
    }
}
?>