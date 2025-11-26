<?php
include_once "app/models/ConsultaModel.php";

class ConsultaController{
    private $model;
    private $connection;

    public function __construct($connection){
        $this->connection = $connection;
        $this->model = new ConsultaModel($connection);
    }

    // Mostrar formulario de consulta individual
    public function mostrarConsultaIndividual(){
        $estudiantes = $this->model->obtenerEstudiantes();
        $evaluaciones = [];
        $infoEstudiante = null;
        $estadisticas = null;
        
        // Si se ha seleccionado un estudiante
        if(isset($_GET['idEstudiante']) && !empty($_GET['idEstudiante'])){
            $idEstudiante = (int)$_GET['idEstudiante'];
            $evaluaciones = $this->model->consultarEvaluacionesPorEstudiante($idEstudiante);
            $infoEstudiante = $this->model->obtenerInfoEstudiante($idEstudiante);
            $estadisticas = $this->model->obtenerEstadisticasEstudiante($idEstudiante);
        }
        
        include_once "app/views/consultas/consulta_individual.php";
    }

    // Mostrar formulario de consulta por competencia
    public function mostrarConsultaPorCompetencia(){
        $competencias = $this->model->obtenerCompetencias();
        $evaluaciones = [];
        $infoCompetencia = null;
        $estadisticas = null;
        
        // Si se ha seleccionado una competencia
        if(isset($_GET['idCompetencia']) && !empty($_GET['idCompetencia'])){
            $idCompetencia = (int)$_GET['idCompetencia'];
            $evaluaciones = $this->model->consultarEvaluacionesPorCompetencia($idCompetencia);
            $infoCompetencia = $this->model->obtenerInfoCompetencia($idCompetencia);
            $estadisticas = $this->model->obtenerEstadisticasCompetencia($idCompetencia);
        }
        
        include_once "app/views/consultas/consulta_competencia.php";
    }
}
?>