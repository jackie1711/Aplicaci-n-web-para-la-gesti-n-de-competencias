<?php
include_once "app/models/EvaluacionModel.php";
include_once "config/db_connection.php"; 

class EvaluacionController{
    private $model;
    private $connection;

    public function __construct($connection){
        $this->connection = $connection;
        $this->model = new EvaluacionModel($connection);
    }

    // Método para insertar evaluación
    public function insertarEvaluacion(){
        if(isset($_POST['enviar'])){
            error_log("=== INICIO INSERCIÓN EVALUACIÓN ===");
            error_log("POST recibido: " . print_r($_POST, true));
            
            $Fecha = $_POST['Fecha'];
            $Observaciones = $_POST['Observaciones'];
            $TipoEvaluacion = isset($_POST['TipoEvaluacion']) ? $_POST['TipoEvaluacion'] : '';
            $Calificacion = isset($_POST['Calificacion']) ? $_POST['Calificacion'] : '';
            $idEstudiantes = (int)$_POST['idEstudiantes'];
            $idDocentes = (int)$_POST['idDocentes'];
            $idCompetencias = (int)$_POST['idCompetencias'];

            error_log("Datos a insertar:");
            error_log("Fecha: $Fecha");
            error_log("Observaciones: $Observaciones");
            error_log("TipoEvaluacion: $TipoEvaluacion");
            error_log("Calificacion: $Calificacion");
            error_log("idEstudiantes: $idEstudiantes");
            error_log("idDocentes: $idDocentes");
            error_log("idCompetencias: $idCompetencias");

            $insert = $this->model->insertarEvaluacion(
                $Fecha, 
                $Observaciones, 
                $TipoEvaluacion,
                $Calificacion,
                $idEstudiantes, 
                $idDocentes, 
                $idCompetencias
            );

            if($insert){
                error_log("Inserción exitosa");
                header("Location: index.php?controller=evaluacion&action=consult&success=1");
                exit();
            } else {
                error_log("Error en la inserción");
                header("Location: index.php?controller=evaluacion&action=consult&error=1");
                exit();
            }
        } else {
            header("Location: index.php?controller=evaluacion&action=consult");
            exit();
        }
    }

    // Método para consultar evaluaciones
    public function consultarEvaluacion(){
        error_log("Método consultarEvaluacion ejecutado");
        
        try {
            // Obtener todas las evaluaciones
            $evaluaciones = $this->model->consultarEvaluaciones();
            
            if($evaluaciones === null){
                error_log("ERROR: consultarEvaluaciones devolvió null");
                $evaluaciones = []; // Convertir a array vacío para evitar errores
            } else {
                error_log("Evaluaciones obtenidas: " . $evaluaciones->num_rows);
            }
            
            // Obtener estudiantes y convertir a array
            $estudiantesResult = $this->model->obtenerEstudiantes();
            $estudiantes = [];
            if($estudiantesResult && $estudiantesResult->num_rows > 0){
                while($est = $estudiantesResult->fetch_assoc()){
                    $estudiantes[] = $est;
                }
            }
            error_log("Estudiantes obtenidos: " . count($estudiantes));
            
            // Obtener docentes y convertir a array
            $docentesResult = $this->model->obtenerDocentes();
            $docentes = [];
            if($docentesResult && $docentesResult->num_rows > 0){
                while($doc = $docentesResult->fetch_assoc()){
                    $docentes[] = $doc;
                }
            }
            error_log("Docentes obtenidos: " . count($docentes));
            
            // Obtener competencias y convertir a array
            $competenciasResult = $this->model->obtenerCompetencias();
            $competencias = [];
            if($competenciasResult && $competenciasResult->num_rows > 0){
                while($comp = $competenciasResult->fetch_assoc()){
                    $competencias[] = $comp;
                }
            }
            error_log("Competencias obtenidas: " . count($competencias));
            
            $viewPath = "app/views/evaluacion/consult_evaluacion.php";
            error_log("Intentando incluir vista: " . $viewPath);
            
            if(file_exists($viewPath)){
                include_once $viewPath;
                error_log("Vista incluida exitosamente");
            } else {
                error_log("ERROR: La vista no existe en: " . $viewPath);
                die("ERROR: No se encontró el archivo de vista en: " . $viewPath);
            }
        } catch(Exception $e) {
            error_log("ERROR en consultarEvaluacion: " . $e->getMessage());
            die("Error: " . $e->getMessage());
        }
    }

    // Método para actualizar evaluación
    public function actualizarEvaluacion(){
        if(isset($_GET['id'])){
            $idEvaluaciones = (int) $_GET['id'];
            $row = $this->model->consultarEvaluacionPorID($idEvaluaciones);
            
            // Convertir resultados a arrays
            $estudiantesResult = $this->model->obtenerEstudiantes();
            $estudiantes = [];
            if($estudiantesResult && $estudiantesResult->num_rows > 0){
                while($est = $estudiantesResult->fetch_assoc()){
                    $estudiantes[] = $est;
                }
            }
            
            $docentesResult = $this->model->obtenerDocentes();
            $docentes = [];
            if($docentesResult && $docentesResult->num_rows > 0){
                while($doc = $docentesResult->fetch_assoc()){
                    $docentes[] = $doc;
                }
            }
            
            $competenciasResult = $this->model->obtenerCompetencias();
            $competencias = [];
            if($competenciasResult && $competenciasResult->num_rows > 0){
                while($comp = $competenciasResult->fetch_assoc()){
                    $competencias[] = $comp;
                }
            }
            
            include_once "app/views/evaluacion/edit_evaluacion.php";
            return;     
        }

        if(isset($_POST['editar'])){
            $idEvaluaciones = $_POST['id'];
            $Fecha = $_POST['Fecha'];
            $Observaciones = $_POST['Observaciones'];
            $TipoEvaluacion = isset($_POST['TipoEvaluacion']) ? $_POST['TipoEvaluacion'] : '';
            $Calificacion = isset($_POST['Calificacion']) ? $_POST['Calificacion'] : '';
            $idEstudiantes = $_POST['idEstudiantes'];
            $idDocentes = $_POST['idDocentes'];
            $idCompetencias = $_POST['idCompetencias'];

            $update = $this->model->actualizarEvaluacion(
                $idEvaluaciones, 
                $Fecha, 
                $Observaciones, 
                $TipoEvaluacion,
                $Calificacion,
                $idEstudiantes, 
                $idDocentes, 
                $idCompetencias
            );

            if($update){
                echo "<script>alert('Evaluación actualizada correctamente');</script>";
                echo "<script>window.location='index.php?controller=evaluacion&action=consult';</script>";
                exit;
            } else {
                echo "<script>alert('Error al actualizar la evaluación');</script>";
            }
        }
    }

    // Método para eliminar evaluación
    public function eliminarEvaluacion(){
        if(isset($_GET['id'])){
            $id_browser = (int) $_GET['id'];
            $delete = $this->model->eliminarEvaluacion($id_browser);

            if($delete){
                header("Location: index.php?controller=evaluacion&action=consult&success=1"); 
            } else {
                header("Location: index.php?controller=evaluacion&action=consult&error=1"); 
            }      
        }
    }
}
?>