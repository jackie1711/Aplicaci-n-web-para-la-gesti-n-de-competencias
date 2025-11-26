<?php
include_once "app/models/CompetenciaModel.php";
include_once "config/db_connection.php"; 

class CompetenciaController{
    private $model;

    public function __construct($connection){
        $this -> model = new CompetenciaModel($connection);
    }

    //metodo para insertar docentes 
    public function insertarCompetencia(){
    if(isset($_POST['enviar'])){
        $NombreCompetencia = $_POST['NombreCompetencia'];
        $Descripcion = $_POST['Descripcion'];

        $insert = $this -> model -> insertarCompetencia($NombreCompetencia,$Descripcion);

        //verificar la insercion
        if($insert){
            header("Location: index.php?controller=competencia&action=consult&success=1");
            exit();
        }else{
            header("Location: index.php?controller=competencia&action=consult&error=1");
            exit();
        }
    }
    include_once "app/views/competencia/consult_competencia.php";
}

    //metodo para consultar docentes
    public function consultarCompetencia(){
        $competencias = $this -> model -> consultarCompetencia();
        include_once "app/views/competencia/consult_competencia.php";
    }

    //metodo para actualizar docentes
    public function actualizarCompetencia(){
        if(isset($_GET['id'])){
            $id_browser = (int) $_GET['id'];
            $row = $this -> model -> consultarPorID($id_browser);
            include_once "app/views/competencia/edit_competencia.php";
            return;     
        }

        if(isset($_POST['editar'])){
            $idCompetencias = $_POST['id'];
            $NombreCompetencia = $_POST['NombreCompetencia'];
            $Descripcion = $_POST['Descripcion'];
                $update = $this -> model -> actualizarCompetencia($idCompetencias, $NombreCompetencia, $Descripcion);

                if($update){
                    header("Location: index.php?controller=competencia&action=consult"); 
                }else{
                    header("Location: index.php?controller=competencia&action=update&id=".$idCompetencias); 
                }

        }
        include_once "app/views/competencia/edit_competencia.php"; 
    }

    //metodo para eliminar docentes
    public function eliminarCompetencia(){
        if(isset($_GET['id'])){
            $id_browser = (int) $_GET['id'];
            $delete = $this -> model -> eliminarCompetencia($id_browser);

            if($delete){
                header("Location: index.php?controller=competencia&action=consult"); 
            } else {
                echo "Error al eliminar la competencia"; 
            }      
        }
    }
}
?>