<?php

class CompetenciaModel{
    private $connection;

    public function __construct($connection){
        $this -> connection = $connection;
    }

    //metodo para insertar docentes
    public function insertarCompetencia($NombreCompetencia, $Descripcion){
        $sql_statement = "INSERT INTO competencias (NombreCompetencia, Descripcion) VALUES (?, ?)"; 
        
        $statement = $this -> connection -> prepare($sql_statement);
        $statement -> bind_param("ss", $NombreCompetencia, $Descripcion); 
        return $statement -> execute(); 
    }

    //Método para consultar docentes
    public function consultarCompetencia(){
        $sql_statemente =  "SELECT * FROM competencias";
        $result = $this -> connection -> query($sql_statemente);
        return $result;
    }

    //metodo para consultar un solo docentes
    public function consultarPorID($id_browser){
        $sql_statement = "SELECT * FROM competencias WHERE idCompetencias = ?";
        $statement = $this -> connection -> prepare($sql_statement);
        $statement -> bind_param("i", $id_browser);
        $statement-> execute();
        $result = $statement -> get_result();
        return $result -> fetch_assoc();
    }

    //metodo para actualizar docentes
    public function actualizarCompetencia($idCompetencias, $NombreCompetencia, $Descripcion){
        $sql_statement = "UPDATE competencias SET NombreCompetencia = ?, Descripcion = ? WHERE idCompetencias = ?"; 
        $statement = $this -> connection -> prepare($sql_statement); 
        $statement -> bind_param("ssi", $NombreCompetencia, $Descripcion, $idCompetencias);
        return $statement -> execute(); 
    }

    //metodo para eliminar docentes por ID
    public function eliminarCompetencia($idCompetencias){
        $sql_statement = "DELETE FROM competencias WHERE idCompetencias = ?";
        $statement = $this -> connection -> prepare($sql_statement);
        $statement -> bind_param("i", $idCompetencias);
        return $statement -> execute(); 
    }
}
?>