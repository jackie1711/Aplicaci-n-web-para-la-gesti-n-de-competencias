<?php

class EstudianteModel{
    private $connection;

    public function __construct($connection){
        $this -> connection = $connection;
    }

    // Método para consultar todos los estudiantes con información de usuario
    public function consultarTodosEstudiantes(){
        $sql_statement = "SELECT e.*, u.Nombre, u.Apellido, u.Correo, u.Telefono, u.Sexo, u.FechaNac 
                         FROM estudiantes e 
                         INNER JOIN usuarios u ON e.idUsuarios = u.idUsuarios 
                         ORDER BY e.idEstudiantes DESC";
        $result = $this -> connection -> query($sql_statement);
        return $result;
    }

    // Método para consultar un estudiante por ID
    public function consultarEstudiantePorID($idEstudiantes){
        $sql_statement = "SELECT e.*, u.Nombre, u.Apellido, u.Correo, u.Telefono, u.Sexo, u.FechaNac, u.idUsuarios
                         FROM estudiantes e 
                         INNER JOIN usuarios u ON e.idUsuarios = u.idUsuarios 
                         WHERE e.idEstudiantes = ?";
        $statement = $this -> connection -> prepare($sql_statement);
        $statement -> bind_param("i", $idEstudiantes);
        $statement -> execute();
        $result = $statement -> get_result();
        return $result -> fetch_assoc();
    }

    // Método para actualizar estudiante
    public function actualizarEstudiante($idEstudiantes, $Matricula, $Grupo, $EstadoAcademico, $Nombre, $Apellido, $Correo, $Telefono, $Sexo, $FechaNac){
        // Primero obtener el idUsuarios
        $sql_get = "SELECT idUsuarios FROM estudiantes WHERE idEstudiantes = ?";
        $stmt_get = $this -> connection -> prepare($sql_get);
        $stmt_get -> bind_param("i", $idEstudiantes);
        $stmt_get -> execute();
        $result = $stmt_get -> get_result();
        $row = $result -> fetch_assoc();
        $idUsuarios = $row['idUsuarios'];
        
        // Actualizar tabla estudiantes
        $sql_estudiante = "UPDATE estudiantes 
                          SET Matricula = ?, Grupo = ?, EstadoAcademico = ? 
                          WHERE idEstudiantes = ?";
        $stmt_estudiante = $this -> connection -> prepare($sql_estudiante);
        $stmt_estudiante -> bind_param("sssi", $Matricula, $Grupo, $EstadoAcademico, $idEstudiantes);
        $update_estudiante = $stmt_estudiante -> execute();
        
        // Actualizar tabla usuarios
        $sql_usuario = "UPDATE usuarios 
                       SET Nombre = ?, Apellido = ?, Correo = ?, Telefono = ?, Sexo = ?, FechaNac = ? 
                       WHERE idUsuarios = ?";
        $stmt_usuario = $this -> connection -> prepare($sql_usuario);
        $stmt_usuario -> bind_param("ssssssi", $Nombre, $Apellido, $Correo, $Telefono, $Sexo, $FechaNac, $idUsuarios);
        $update_usuario = $stmt_usuario -> execute();
        
        return $update_estudiante && $update_usuario;
    }

    // Método para eliminar estudiante
    public function eliminarEstudiante($idEstudiantes){
        // Por la cascada, al eliminar el usuario se elimina el estudiante
        $sql_get = "SELECT idUsuarios FROM estudiantes WHERE idEstudiantes = ?";
        $stmt_get = $this -> connection -> prepare($sql_get);
        $stmt_get -> bind_param("i", $idEstudiantes);
        $stmt_get -> execute();
        $result = $stmt_get -> get_result();
        $row = $result -> fetch_assoc();
        $idUsuarios = $row['idUsuarios'];
        
        // Eliminar usuario (cascada eliminará estudiante)
        $sql_delete = "DELETE FROM usuarios WHERE idUsuarios = ?";
        $statement = $this -> connection -> prepare($sql_delete);
        $statement -> bind_param("i", $idUsuarios);
        return $statement -> execute();
    }

    public function obtenerProgresoPorCompetencia($idEstudiante) {
        $query = "SELECT 
                    c.NombreCompetencia,
                    COUNT(ev.idEvaluaciones) as total_evaluaciones,
                    ROUND(AVG(CASE 
                        WHEN ev.Calificacion = 'Excelente' THEN 100 
                        WHEN ev.Calificacion = 'Buena' OR ev.Calificacion = 'Bueno' THEN 80 
                        WHEN ev.Calificacion = 'Regular' THEN 60 
                        WHEN ev.Calificacion = 'Deficiente' THEN 50 
                        ELSE 0 
                    END), 1) as promedio
                  FROM evaluaciones ev
                  INNER JOIN competencias c ON ev.idCompetencias = c.idCompetencias
                  WHERE ev.idEstudiantes = ?
                  GROUP BY c.idCompetencias, c.NombreCompetencia
                  ORDER BY promedio DESC";
        
        $stmt = $this->connection->prepare($query);
        $stmt->bind_param("i", $idEstudiante);
        $stmt->execute();
        $result = $stmt->get_result();
        
        $datos = [];
        while ($row = $result->fetch_assoc()) {
            $datos[] = $row;
        }
        return $datos;
    }

    // 1. Obtener lista simple de competencias para el select del filtro
    public function obtenerListaCompetencias() {
        $result = $this->connection->query("SELECT idCompetencias, NombreCompetencia FROM competencias ORDER BY NombreCompetencia");
        $data = [];
        if($result) while($row = $result->fetch_assoc()) $data[] = $row;
        return $data;
    }

    // 2. Obtener evaluaciones aplicando filtros (Competencia y Calificación)
    public function obtenerEvaluacionesFiltradas($idEstudiante, $idCompetencia = null, $calificacion = null) {
        $sql = "SELECT ev.idEvaluaciones, ev.Fecha, ev.TipoEvaluacion, ev.Calificacion, ev.Observaciones,
                       c.NombreCompetencia, c.Descripcion as DescripcionCompetencia,
                       CONCAT(u.Nombre, ' ', u.Apellido) as NombreDocente
                FROM evaluaciones ev
                INNER JOIN competencias c ON ev.idCompetencias = c.idCompetencias
                INNER JOIN docentes d ON ev.idDocentes = d.idDocentes
                INNER JOIN usuarios u ON d.idUsuarios = u.idUsuarios
                WHERE ev.idEstudiantes = ?";
        
        $types = "i";
        $params = [$idEstudiante];

        // Aplicar filtro de Competencia
        if (!empty($idCompetencia)) {
            $sql .= " AND ev.idCompetencias = ?";
            $types .= "i";
            $params[] = $idCompetencia;
        }
        
        // Aplicar filtro de Calificación
        if (!empty($calificacion) && $calificacion != 'Todas') {
            $sql .= " AND ev.Calificacion = ?";
            $types .= "s";
            $params[] = $calificacion;
        }

        $sql .= " ORDER BY ev.Fecha DESC";
        
        $stmt = $this->connection->prepare($sql);
        $stmt->bind_param($types, ...$params);
        $stmt->execute();
        return $stmt->get_result();
    }

    // 3. Obtener datos para la gráfica de progreso mensual
    public function obtenerProgresoMensual($idEstudiante) {
        $sql = "SELECT DATE_FORMAT(Fecha, '%Y-%m') as mes, 
                ROUND(AVG(CASE 
                    WHEN Calificacion='Excelente' THEN 100 
                    WHEN Calificacion LIKE 'Buen%' THEN 80 
                    WHEN Calificacion='Regular' THEN 60 
                    WHEN Calificacion='Deficiente' THEN 50 
                    ELSE 0 END), 1) as promedio
                FROM evaluaciones WHERE idEstudiantes = ? 
                GROUP BY DATE_FORMAT(Fecha, '%Y-%m') 
                ORDER BY mes DESC LIMIT 6";
        $stmt = $this->connection->prepare($sql);
        $stmt->bind_param("i", $idEstudiante);
        $stmt->execute();
        $res = $stmt->get_result();
        $data = [];
        while($r = $res->fetch_assoc()) $data[] = $r;
        return array_reverse($data); // Invertir para gráfica cronológica
    }
}
?>
