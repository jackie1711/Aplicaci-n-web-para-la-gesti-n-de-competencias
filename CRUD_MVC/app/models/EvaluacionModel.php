<?php

class EvaluacionModel{
    private $connection;

    public function __construct($connection){
        $this->connection = $connection;
    }

    // Método para insertar evaluación
    public function insertarEvaluacion($Fecha, $Observaciones, $TipoEvaluacion, $Calificacion, $idEstudiantes, $idDocentes, $idCompetencias){
        $sql_statement = "INSERT INTO evaluaciones (Fecha, Observaciones, TipoEvaluacion, Calificacion, idEstudiantes, idDocentes, idCompetencias) 
                          VALUES (?, ?, ?, ?, ?, ?, ?)"; 
        
        $statement = $this->connection->prepare($sql_statement);
        
        $statement->bind_param("sssssii", $Fecha, $Observaciones, $TipoEvaluacion, $Calificacion, $idEstudiantes, $idDocentes, $idCompetencias); 
        
        if($statement->execute()){
            error_log("Evaluación insertada correctamente - ID: " . $this->connection->insert_id);
            return true;
        } else {
            error_log("Error al insertar evaluación: " . $statement->error);
            return false;
        }
    }

    // Método para consultar evaluaciones con información relacionada
    public function consultarEvaluaciones(){
        $sql = "SELECT 
                    e.idEvaluaciones, e.Fecha, e.Observaciones, e.TipoEvaluacion, e.Calificacion,
                    u_est.Nombre as NombreEstudiante, u_est.Apellido as ApellidosEstudiante,
                    u_doc.Nombre as NombreDocente, u_doc.Apellido as ApellidosDocente,
                    co.NombreCompetencia
                FROM evaluaciones e 
                INNER JOIN estudiantes est ON e.idEstudiantes = est.idEstudiantes
                INNER JOIN usuarios u_est ON est.idUsuarios = u_est.idUsuarios
                INNER JOIN docentes doc ON e.idDocentes = doc.idDocentes
                INNER JOIN usuarios u_doc ON doc.idUsuarios = u_doc.idUsuarios
                INNER JOIN competencias co ON e.idCompetencias = co.idCompetencias
                ORDER BY e.Fecha DESC";
        
        $result = $this->connection->query($sql);
        
        if(!$result){
            error_log("Error en consultarEvaluaciones: " . $this->connection->error);
            return null;
        }
        
        return $result;
    }

    // Método para consultar una evaluación por ID
    public function consultarEvaluacionPorID($idEvaluaciones){
        $sql_statement = "SELECT 
                            e.idEvaluaciones, e.Fecha, e.Observaciones, e.TipoEvaluacion, e.Calificacion,
                            e.idEstudiantes, e.idDocentes, e.idCompetencias,
                            u_est.Nombre as NombreEstudiante, u_est.Apellido as ApellidosEstudiante,
                            u_doc.Nombre as NombreDocente, u_doc.Apellido as ApellidosDocente,
                            co.NombreCompetencia
                        FROM evaluaciones e 
                        INNER JOIN estudiantes est ON e.idEstudiantes = est.idEstudiantes
                        INNER JOIN usuarios u_est ON est.idUsuarios = u_est.idUsuarios
                        INNER JOIN docentes doc ON e.idDocentes = doc.idDocentes
                        INNER JOIN usuarios u_doc ON doc.idUsuarios = u_doc.idUsuarios
                        INNER JOIN competencias co ON e.idCompetencias = co.idCompetencias
                        WHERE e.idEvaluaciones = ?";
        
        $statement = $this->connection->prepare($sql_statement);
        $statement->bind_param("i", $idEvaluaciones);
        $statement->execute();
        $result = $statement->get_result();
        return $result->fetch_assoc();
    }

    // Método para actualizar evaluación
    public function actualizarEvaluacion($idEvaluaciones, $Fecha, $Observaciones, $TipoEvaluacion, $Calificacion, $idEstudiantes, $idDocentes, $idCompetencias){
        $sql_statement = "UPDATE evaluaciones 
                            SET Fecha = ?, Observaciones = ?, TipoEvaluacion = ?, Calificacion = ?, idEstudiantes = ?, idDocentes = ?, idCompetencias = ?
                            WHERE idEvaluaciones = ?";
        
        $statement = $this->connection->prepare($sql_statement);
        $statement->bind_param("ssssiiii", $Fecha, $Observaciones, $TipoEvaluacion, $Calificacion, $idEstudiantes, $idDocentes, $idCompetencias, $idEvaluaciones); 
        return $statement->execute();
    }

    // Método para eliminar evaluación
    public function eliminarEvaluacion($idEvaluaciones){
        $sql_statement = "DELETE FROM evaluaciones WHERE idEvaluaciones = ?";
        $statement = $this->connection->prepare($sql_statement);
        $statement->bind_param("i", $idEvaluaciones);
        return $statement->execute(); 
    }

    // Métodos auxiliares para obtener datos de selects - CON JOIN A USUARIOS
    public function obtenerEstudiantes(){
        $sql = "SELECT e.idEstudiantes, u.Nombre, u.Apellido as Apellidos 
                FROM estudiantes e
                INNER JOIN usuarios u ON e.idUsuarios = u.idUsuarios
                ORDER BY u.Apellido, u.Nombre";
        $result = $this->connection->query($sql);
        
        if(!$result){
            error_log("Error en obtenerEstudiantes: " . $this->connection->error);
            return null;
        }
        
        return $result;
    }

    public function obtenerDocentes(){
        $sql = "SELECT d.idDocentes, u.Nombre, u.Apellido as Apellidos 
                FROM docentes d
                INNER JOIN usuarios u ON d.idUsuarios = u.idUsuarios
                ORDER BY u.Apellido, u.Nombre";
        $result = $this->connection->query($sql);
        
        if(!$result){
            error_log("Error en obtenerDocentes: " . $this->connection->error);
            return null;
        }
        
        return $result;
    }

    public function obtenerCompetencias(){
        $sql = "SELECT idCompetencias, NombreCompetencia FROM competencias ORDER BY NombreCompetencia";
        $result = $this->connection->query($sql);
        
        if(!$result){
            error_log("Error en obtenerCompetencias: " . $this->connection->error);
            return null;
        }
        
        return $result;
    }
}
?>