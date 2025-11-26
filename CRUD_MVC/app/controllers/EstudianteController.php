<?php
include_once "app/models/EstudianteModel.php";
include_once "config/db_connection.php";
require_once __DIR__ . '/../../public/libraries/fpdf/fpdf.php';

class EstudianteController{
    private $model;
    private $connection;

    public function __construct($connection){
        $this->connection = $connection;
        $this->model = new EstudianteModel($connection);
    }

    // ========== MÉTODOS DE GESTIÓN (ADMIN/DOCENTE) ==========
    
    public function gestionarEstudiantes(){
        $estudiantes = $this->model->consultarTodosEstudiantes();
        include_once "app/views/estudiante/gestion_estudiantes.php";
    }

    public function editarEstudiante(){
        if(isset($_GET['id'])){
            $id_browser = (int) $_GET['id'];
            $estudiante = $this->model->consultarEstudiantePorID($id_browser);
            if(!$estudiante){
                header("Location: index.php?controller=estudiante&action=gestionarEstudiantes");
                exit();
            }
            include_once "app/views/estudiante/editar_estudiante.php";
            return;     
        }

        if(isset($_POST['editar'])){
            $idEstudiantes = $_POST['id'];
            
            // --- VALIDACIÓN DE EDAD ---
            $fechaNac = $_POST['FechaNac'];
            $nacimiento = new DateTime($fechaNac);
            $hoy = new DateTime();
            $edad = $hoy->diff($nacimiento)->y;

            if ($edad < 17 || $edad > 90) {
                $_SESSION['error'] = "La edad debe estar entre 17 y 90 años.";
                header("Location: index.php?controller=estudiante&action=editarEstudiante&id=".$idEstudiantes);
                exit();
            }
            $update = $this->model->actualizarEstudiante($_POST['id'], $_POST['Matricula'], $_POST['Grupo'], $_POST['EstadoAcademico'], $_POST['Nombre'], $_POST['Apellido'], $_POST['Correo'], $_POST['Telefono'], $_POST['Sexo'], $_POST['FechaNac']);
            if($update) $_SESSION['mensaje'] = "Estudiante actualizado exitosamente";
            else $_SESSION['error'] = "Error al actualizar";
            header("Location: index.php?controller=estudiante&action=gestionarEstudiantes"); 
            exit();
        }
    }

    public function eliminarEstudiante(){
        if(isset($_GET['id'])){
            $delete = $this->model->eliminarEstudiante((int)$_GET['id']);
            if($delete) $_SESSION['mensaje'] = "Estudiante eliminado";
            else $_SESSION['error'] = "Error al eliminar";
            header("Location: index.php?controller=estudiante&action=gestionarEstudiantes"); 
            exit();
        }
    }

    // ========== MÉTODOS DEL PANEL DE ESTUDIANTE ==========
    
    public function evidenciasPendientes(){
        if(!isset($_SESSION['usuario_id']) || $_SESSION['tipo_usuario'] != 'Estudiante'){
            header("Location: index.php?controller=auth&action=mostrarLogin");
            exit();
        }

        $idUsuario = $_SESSION['usuario_id'];
        
        $sqlEst = "SELECT idEstudiantes FROM estudiantes WHERE idUsuarios = ?";
        $stmt = $this->connection->prepare($sqlEst);
        $stmt->bind_param("i", $idUsuario);
        $stmt->execute();
        $res = $stmt->get_result();
        $estudiante = $res->fetch_assoc();
        
        if(!$estudiante) { echo "Error: Estudiante no encontrado."; return; }
        $idEstudiante = $estudiante['idEstudiantes'];
        
        $sqlAsignaciones = "SELECT ae.*, c.NombreCompetencia, c.Descripcion, 
                            COALESCE(CONCAT(u.Nombre, ' ', u.Apellido), 'Docente') as NombreDocente
                           FROM asignaciones_evidencias ae 
                           INNER JOIN competencias c ON ae.idCompetencias = c.idCompetencias 
                           INNER JOIN docentes d ON ae.idDocentes = d.idDocentes
                           INNER JOIN usuarios u ON d.idUsuarios = u.idUsuarios
                           WHERE ae.idEstudiantes = ? AND ae.Estado = 'Pendiente'
                           ORDER BY ae.FechaAsignacion DESC";
                           
        $stmt = $this->connection->prepare($sqlAsignaciones);
        $stmt->bind_param("i", $idEstudiante);
        $stmt->execute();
        $asignaciones = $stmt->get_result();
        
        include_once "app/views/estudiante/evidencias_pendientes.php";
    }
    
    public function subirEvidencia(){
        if(!isset($_SESSION['usuario_id'])){
            header("Location: index.php?controller=auth&action=mostrarLogin");
            exit();
        }

        if(isset($_POST['subir']) && isset($_FILES['archivo'])){
            $idAsignacion = $_POST['idAsignacion'];
            $idCompetencia = $_POST['idCompetencia'];
            
            $idUsuario = $_SESSION['usuario_id'];
            $sqlEst = "SELECT idEstudiantes FROM estudiantes WHERE idUsuarios = ?";
            $stmt = $this->connection->prepare($sqlEst);
            $stmt->bind_param("i", $idUsuario);
            $stmt->execute();
            $estudiante = $stmt->get_result()->fetch_assoc();
            $idEstudiante = $estudiante['idEstudiantes'];

            $archivo = $_FILES['archivo'];
            $nombreArchivo = $archivo['name'];
            $tmpName = $archivo['tmp_name'];
            $tipoArchivo = $archivo['type'];
            $tamanoArchivo = $archivo['size'];
            
            $uploadDir = __DIR__ . "/../../uploads/evidencias/";
            if(!file_exists($uploadDir)) mkdir($uploadDir, 0777, true);
            
            $extension = pathinfo($nombreArchivo, PATHINFO_EXTENSION);
            $nombreUnico = uniqid() . "." . $extension;
            $rutaDestino = $uploadDir . $nombreUnico;
            
            if(move_uploaded_file($tmpName, $rutaDestino)){
                $sqlInsert = "INSERT INTO evidencias (idEstudiantes, idCompetencias, NombreArchivo, RutaArchivo, TipoArchivo, TamanoArchivo, Estado, FechaSubida) 
                              VALUES (?, ?, ?, ?, ?, ?, 'Pendiente', NOW())";
                $stmt = $this->connection->prepare($sqlInsert);
                $stmt->bind_param("iisssi", $idEstudiante, $idCompetencia, $nombreArchivo, $rutaDestino, $tipoArchivo, $tamanoArchivo);
                $stmt->execute();
                $idEvidencia = $this->connection->insert_id;

                $sqlUpdate = "UPDATE asignaciones_evidencias 
                             SET Estado = 'Entregada', 
                                 FechaEntrega = NOW(),
                                 idEvidencia = ?,
                                 NombreArchivo = ?,
                                 RutaArchivo = ?
                             WHERE idAsignacion = ?";
                $stmtUpdate = $this->connection->prepare($sqlUpdate);
                $stmtUpdate->bind_param("issi", $idEvidencia, $nombreArchivo, $rutaDestino, $idAsignacion);
                
                if($stmtUpdate->execute()){
                    $_SESSION['success'] = "¡Tarea entregada correctamente!";
                } else {
                    $_SESSION['error'] = "El archivo se subió, pero no se pudo actualizar el estado.";
                }
            } else {
                $_SESSION['error'] = "Error al mover el archivo al servidor.";
            }
        }
        
        header("Location: index.php?controller=estudiante&action=evidenciasPendientes");
        exit();
    }

    // ========== MIS CONSULTAS (CON FILTROS) ==========
    public function misConsultas(){
        if(!isset($_SESSION['usuario_id']) || $_SESSION['tipo_usuario'] != 'Estudiante'){
            header("Location: index.php?controller=auth&action=mostrarLogin");
            exit();
        }

        $idUsuario = $_SESSION['usuario_id'];
        
        // 1. Obtener ID del estudiante
        $sql = "SELECT idEstudiantes, Matricula, Nombre FROM estudiantes e 
                INNER JOIN usuarios u ON e.idUsuarios = u.idUsuarios WHERE e.idUsuarios = ?";
        $stmt = $this->connection->prepare($sql);
        $stmt->bind_param("i", $idUsuario);
        $stmt->execute();
        $res = $stmt->get_result()->fetch_assoc();
        
        if(!$res) { echo "Error de estudiante"; return; }
        $idEstudiante = $res['idEstudiantes'];
        $matricula = $res['Matricula'];

        // 2. Obtener Filtros
        $filtroCompetencia = isset($_GET['competencia']) ? $_GET['competencia'] : '';
        $filtroCalificacion = isset($_GET['calificacion']) ? $_GET['calificacion'] : '';

        // 3. Obtener Datos
        $listaCompetencias = $this->model->obtenerListaCompetencias();
        $evaluacionesResult = $this->model->obtenerEvaluacionesFiltradas($idEstudiante, $filtroCompetencia, $filtroCalificacion);
        $progresoMensual = $this->model->obtenerProgresoMensual($idEstudiante);

        // 4. Procesar estadísticas
        $evaluaciones = [];
        $estadisticas = [
            'total' => 0, 'excelentes' => 0, 'buenas' => 0, 'deficientes' => 0
        ];

        while($row = $evaluacionesResult->fetch_assoc()){
            $evaluaciones[] = $row;
            $estadisticas['total']++;
            
            $c = strtolower($row['Calificacion']);
            if($c == 'excelente') $estadisticas['excelentes']++;
            elseif(strpos($c, 'buen') !== false) $estadisticas['buenas']++;
            else $estadisticas['deficientes']++;
        }

        include_once "app/views/estudiante/mis_consultas.php";
    }
    
    // ========== MIS REPORTES Y PERFIL ==========
    
    public function misReportes(){
        if(!isset($_SESSION['usuario_id']) || $_SESSION['tipo_usuario'] != 'Estudiante'){
            header("Location: index.php?controller=auth&action=mostrarLogin");
            exit();
        }
        include_once "app/views/estudiante/mis_reportes.php";
    }

    // ========== PDF CON GRÁFICA ==========
    
    // Función helper privada para convertir UTF-8 (declarada UNA SOLA VEZ)
    private function convertirTexto($texto) {
        return mb_convert_encoding($texto, 'ISO-8859-1', 'UTF-8');
    }
    
    public function exportarReportePDF() {
        // CRÍTICO: Limpiar cualquier output previo y suprimir errores deprecados
        ob_clean();
        error_reporting(E_ALL & ~E_DEPRECATED & ~E_STRICT);
        
        if (session_status() === PHP_SESSION_NONE) session_start();
        if (!isset($_SESSION['usuario_id']) || $_SESSION['tipo_usuario'] != 'Estudiante') {
            header("Location: index.php?controller=auth&action=mostrarLogin");
            exit();
        }

        // 1. Obtener datos
        $idUsuario = $_SESSION['usuario_id'];
        $sql = "SELECT e.idEstudiantes, e.Matricula, e.Grupo, u.Nombre, u.Apellido 
                FROM estudiantes e 
                INNER JOIN usuarios u ON e.idUsuarios = u.idUsuarios 
                WHERE e.idUsuarios = ?";
        $stmt = $this->connection->prepare($sql);
        $stmt->bind_param("i", $idUsuario);
        $stmt->execute();
        $estudiante = $stmt->get_result()->fetch_assoc();

        if (!$estudiante) die("Error al obtener datos del estudiante.");

        $datosProgreso = $this->model->obtenerProgresoPorCompetencia($estudiante['idEstudiantes']);

        // 2. Crear PDF
        $pdf = new FPDF();
        $pdf->AddPage();
        
        // Encabezado
        $pdf->SetFont('Arial', 'B', 16);
        $pdf->SetTextColor(74, 30, 111);
        $pdf->Cell(0, 10, $this->convertirTexto('Reporte de Progreso Académico'), 0, 1, 'C');
        $pdf->SetFont('Arial', '', 10);
        $pdf->SetTextColor(100);
        $pdf->Cell(0, 5, 'Generado el: ' . date('d/m/Y'), 0, 1, 'C');
        $pdf->Ln(10);

        // Info del estudiante
        $pdf->SetFont('Arial', 'B', 12);
        $pdf->SetTextColor(0);
        $pdf->Cell(0, 10, $this->convertirTexto('Alumno: ' . $estudiante['Nombre'] . ' ' . $estudiante['Apellido']), 0, 1);
        $pdf->SetFont('Arial', '', 11);
        $pdf->Cell(0, 6, $this->convertirTexto('Matrícula: ' . $estudiante['Matricula'] . ' | Grupo: ' . $estudiante['Grupo']), 0, 1);
        $pdf->Ln(10);

        // Gráfica
        if (!empty($datosProgreso)) {
            $this->dibujarGraficaProgreso($pdf, $datosProgreso);
            $pdf->Ln(10);
        } else {
            $pdf->Cell(0, 10, $this->convertirTexto('No hay datos suficientes para la gráfica.'), 0, 1, 'C');
        }

        // Tabla de competencias
        $pdf->SetY($pdf->GetY() + 10);
        $pdf->SetFont('Arial', 'B', 14);
        $pdf->SetTextColor(74, 30, 111);
        $pdf->Cell(0, 10, $this->convertirTexto('Detalle por Competencia'), 0, 1);
        
        $pdf->SetFillColor(247, 148, 29);
        $pdf->SetTextColor(255);
        $pdf->SetFont('Arial', 'B', 10);
        
        $pdf->Cell(110, 10, 'Competencia', 1, 0, 'L', true);
        $pdf->Cell(40, 10, 'Evaluaciones', 1, 0, 'C', true);
        $pdf->Cell(40, 10, 'Promedio', 1, 1, 'C', true);

        $pdf->SetTextColor(0);
        $pdf->SetFont('Arial', '', 10);

        foreach ($datosProgreso as $row) {
            $nombreCompetencia = $this->convertirTexto(substr($row['NombreCompetencia'], 0, 60));
            $pdf->Cell(110, 8, $nombreCompetencia, 1, 0, 'L');
            $pdf->Cell(40, 8, $row['total_evaluaciones'], 1, 0, 'C');
            
            $prom = $row['promedio'];
            if($prom >= 90) $pdf->SetTextColor(0, 128, 0);
            elseif($prom >= 70) $pdf->SetTextColor(0, 0, 255);
            else $pdf->SetTextColor(255, 0, 0);
            
            $pdf->Cell(40, 8, $prom . '%', 1, 1, 'C');
            $pdf->SetTextColor(0);
        }

        // IMPORTANTE: Limpiar buffer antes de enviar PDF
        ob_end_clean();
        $pdf->Output('D', 'Mi_Progreso.pdf');
        exit();
    }

    private function dibujarGraficaProgreso($pdf, $datos) {
        $pdf->SetFont('Arial', 'B', 12);
        $pdf->SetTextColor(0);
        $pdf->Cell(0, 10, $this->convertirTexto('Gráfica de Desempeño (Promedio 0-100)'), 0, 1);
        $pdf->Ln(5);

        $x = 20; 
        $y = $pdf->GetY();
        $anchoMax = 150; 
        $alturaBarra = 8;
        $espacio = 4;

        $pdf->SetFont('Arial', '', 9);

        foreach ($datos as $d) {
            $nombreCorto = $this->convertirTexto(substr($d['NombreCompetencia'], 0, 40));
            
            $pdf->SetXY($x, $y);
            $pdf->Cell(0, $alturaBarra, $nombreCorto . '...', 0, 0);
            
            $ancho = ($d['promedio'] / 100) * $anchoMax;
            
            if ($d['promedio'] >= 90) $pdf->SetFillColor(34, 197, 94);
            elseif ($d['promedio'] >= 70) $pdf->SetFillColor(59, 130, 246);
            else $pdf->SetFillColor(239, 68, 68);

            $pdf->Rect($x + 70, $y + 1, $ancho, $alturaBarra - 2, 'F');
            
            $pdf->SetXY($x + 70 + $ancho + 2, $y);
            $pdf->Cell(10, $alturaBarra, $d['promedio'] . '%', 0, 0);

            $y += $alturaBarra + $espacio;
        }
        
        $pdf->SetY($y + 5);
    }
}
?>