<?php

class ConsultaModel{
    private $connection;

    public function __construct($connection){
        $this->connection = $connection;
    }

    // Obtener todos los estudiantes para el selector
    public function obtenerEstudiantes(){
        $sql = "SELECT e.idEstudiantes, u.Nombre, u.Apellido, e.Matricula
                FROM estudiantes e
                INNER JOIN usuarios u ON e.idUsuarios = u.idUsuarios
                ORDER BY u.Apellido, u.Nombre";
        
        $result = $this->connection->query($sql);
        
        if(!$result){
            error_log("Error en obtenerEstudiantes: " . $this->connection->error);
            return [];
        }
        
        $estudiantes = [];
        while($row = $result->fetch_assoc()){
            $estudiantes[] = $row;
        }
        return $estudiantes;
    }

    // Obtener todas las competencias para el selector
    public function obtenerCompetencias(){
        $sql = "SELECT idCompetencias, NombreCompetencia, Descripcion
                FROM competencias
                ORDER BY NombreCompetencia";
        
        $result = $this->connection->query($sql);
        
        if(!$result){
            error_log("Error en obtenerCompetencias: " . $this->connection->error);
            return [];
        }
        
        $competencias = [];
        while($row = $result->fetch_assoc()){
            $competencias[] = $row;
        }
        return $competencias;
    }

    // Consulta Individual: Obtener todas las evaluaciones de un estudiante
    public function consultarEvaluacionesPorEstudiante($idEstudiantes){
        $sql = "SELECT 
                    e.idEvaluaciones,
                    e.Fecha,
                    e.TipoEvaluacion,
                    e.Calificacion,
                    e.Observaciones,
                    c.NombreCompetencia,
                    c.Descripcion as DescripcionCompetencia,
                    CONCAT(u_doc.Nombre, ' ', u_doc.Apellido) as NombreDocente
                FROM evaluaciones e
                INNER JOIN competencias c ON e.idCompetencias = c.idCompetencias
                INNER JOIN docentes d ON e.idDocentes = d.idDocentes
                INNER JOIN usuarios u_doc ON d.idUsuarios = u_doc.idUsuarios
                WHERE e.idEstudiantes = ?
                ORDER BY e.Fecha DESC, c.NombreCompetencia";
        
        $statement = $this->connection->prepare($sql);
        $statement->bind_param("i", $idEstudiantes);
        $statement->execute();
        $result = $statement->get_result();
        
        $evaluaciones = [];
        while($row = $result->fetch_assoc()){
            $evaluaciones[] = $row;
        }
        return $evaluaciones;
    }

    // Obtener información del estudiante
    public function obtenerInfoEstudiante($idEstudiantes){
        // Primero intentamos con Email, si falla intentamos con Correo
        $sql = "SELECT 
                    e.idEstudiantes,
                    e.Matricula,
                    u.Nombre,
                    u.Apellido,
                    u.Correo as Email
                FROM estudiantes e
                INNER JOIN usuarios u ON e.idUsuarios = u.idUsuarios
                WHERE e.idEstudiantes = ?";
        
        $statement = $this->connection->prepare($sql);
        
        if(!$statement){
            error_log("Error en prepare obtenerInfoEstudiante: " . $this->connection->error);
            // Intentar sin el campo Email/Correo
            $sql = "SELECT 
                        e.idEstudiantes,
                        e.Matricula,
                        u.Nombre,
                        u.Apellido,
                        '' as Email
                    FROM estudiantes e
                    INNER JOIN usuarios u ON e.idUsuarios = u.idUsuarios
                    WHERE e.idEstudiantes = ?";
            
            $statement = $this->connection->prepare($sql);
            
            if(!$statement){
                error_log("Error en segundo intento obtenerInfoEstudiante: " . $this->connection->error);
                return null;
            }
        }
        
        $statement->bind_param("i", $idEstudiantes);
        
        if(!$statement->execute()){
            error_log("Error en execute obtenerInfoEstudiante: " . $statement->error);
            return null;
        }
        
        $result = $statement->get_result();
        return $result->fetch_assoc();
    }

    // Obtener estadísticas del estudiante
    public function obtenerEstadisticasEstudiante($idEstudiantes){
        $sql = "SELECT 
                    COUNT(*) as TotalEvaluaciones,
                    SUM(CASE WHEN Calificacion = 'Excelente' THEN 1 ELSE 0 END) as Excelente,
                    SUM(CASE WHEN Calificacion = 'Bueno' THEN 1 ELSE 0 END) as Bueno,
                    SUM(CASE WHEN Calificacion = 'Regular' THEN 1 ELSE 0 END) as Regular,
                    SUM(CASE WHEN Calificacion = 'Deficiente' THEN 1 ELSE 0 END) as Deficiente,
                    COUNT(DISTINCT idCompetencias) as CompetenciasEvaluadas
                FROM evaluaciones
                WHERE idEstudiantes = ?";
        
        $statement = $this->connection->prepare($sql);
        $statement->bind_param("i", $idEstudiantes);
        $statement->execute();
        $result = $statement->get_result();
        return $result->fetch_assoc();
    }

    // Consulta por Competencia: Obtener todas las evaluaciones de una competencia
    public function consultarEvaluacionesPorCompetencia($idCompetencias){
        $sql = "SELECT 
                    e.idEvaluaciones,
                    e.Fecha,
                    e.TipoEvaluacion,
                    e.Calificacion,
                    e.Observaciones,
                    CONCAT(u_est.Nombre, ' ', u_est.Apellido) as NombreEstudiante,
                    est.Matricula,
                    CONCAT(u_doc.Nombre, ' ', u_doc.Apellido) as NombreDocente
                FROM evaluaciones e
                INNER JOIN estudiantes est ON e.idEstudiantes = est.idEstudiantes
                INNER JOIN usuarios u_est ON est.idUsuarios = u_est.idUsuarios
                INNER JOIN docentes d ON e.idDocentes = d.idDocentes
                INNER JOIN usuarios u_doc ON d.idUsuarios = u_doc.idUsuarios
                WHERE e.idCompetencias = ?
                ORDER BY e.Fecha DESC, u_est.Apellido, u_est.Nombre";
        
        $statement = $this->connection->prepare($sql);
        $statement->bind_param("i", $idCompetencias);
        $statement->execute();
        $result = $statement->get_result();
        
        $evaluaciones = [];
        while($row = $result->fetch_assoc()){
            $evaluaciones[] = $row;
        }
        return $evaluaciones;
    }

    // Obtener información de la competencia
    public function obtenerInfoCompetencia($idCompetencias){
        $sql = "SELECT 
                    idCompetencias,
                    NombreCompetencia,
                    Descripcion
                FROM competencias
                WHERE idCompetencias = ?";
        
        $statement = $this->connection->prepare($sql);
        $statement->bind_param("i", $idCompetencias);
        $statement->execute();
        $result = $statement->get_result();
        return $result->fetch_assoc();
    }

    // Obtener estadísticas de la competencia
    public function obtenerEstadisticasCompetencia($idCompetencias){
        $sql = "SELECT 
                    COUNT(*) as TotalEvaluaciones,
                    SUM(CASE WHEN Calificacion = 'Excelente' THEN 1 ELSE 0 END) as Excelente,
                    SUM(CASE WHEN Calificacion = 'Bueno' THEN 1 ELSE 0 END) as Bueno,
                    SUM(CASE WHEN Calificacion = 'Regular' THEN 1 ELSE 0 END) as Regular,
                    SUM(CASE WHEN Calificacion = 'Deficiente' THEN 1 ELSE 0 END) as Deficiente,
                    COUNT(DISTINCT idEstudiantes) as EstudiantesEvaluados,
                    COUNT(DISTINCT idDocentes) as DocentesParticipantes
                FROM evaluaciones
                WHERE idCompetencias = ?";
        
        $statement = $this->connection->prepare($sql);
        $statement->bind_param("i", $idCompetencias);
        $statement->execute();
        $result = $statement->get_result();
        return $result->fetch_assoc();
    }
}
?>