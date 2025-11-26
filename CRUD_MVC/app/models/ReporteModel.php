<?php
class ReporteModel {
    private $connection;
    
    public function __construct() {
        $server = "localhost";
        $user = "root";
        $password = "";
        $db = "bdappcompetencias";
        
        $this->connection = new mysqli($server, $user, $password, $db);
        
        if($this->connection->connect_errno){
            throw new Exception("Conexión fallida: " . $this->connection->connect_error);
        }
        
        $this->connection->set_charset("utf8mb4");
    }
    
    // ==================== REPORTE GRUPAL ====================
    
    public function obtenerEstadisticasTodosGrupos() {
        $grupos = [];
        $queryGroups = "SELECT DISTINCT Grupo FROM estudiantes ORDER BY Grupo";
        $resultGroups = $this->connection->query($queryGroups);
        
        if ($resultGroups) {
            while ($row = $resultGroups->fetch_assoc()) {
                $grupo = $row['Grupo'];
                
                $queryStats = "
                    SELECT 
                        e.Grupo,
                        COUNT(DISTINCT est.idEstudiantes) as total_estudiantes,
                        COUNT(ev.idEvaluaciones) as total_evaluaciones,
                        SUM(CASE WHEN ev.Calificacion = 'Excelente' THEN 1 ELSE 0 END) as excelentes,
                        SUM(CASE WHEN ev.Calificacion = 'Buena' OR ev.Calificacion = 'Bueno' THEN 1 ELSE 0 END) as buenas,
                        SUM(CASE WHEN ev.Calificacion = 'Deficiente' THEN 1 ELSE 0 END) as deficientes
                    FROM estudiantes e
                    LEFT JOIN evaluaciones ev ON e.idEstudiantes = ev.idEstudiantes
                    LEFT JOIN estudiantes est ON e.Grupo = est.Grupo
                    WHERE e.Grupo = ?
                    GROUP BY e.Grupo
                ";
                
                $stmt = $this->connection->prepare($queryStats);
                $stmt->bind_param("s", $grupo);
                $stmt->execute();
                $resStats = $stmt->get_result();
                
                if ($data = $resStats->fetch_assoc()) {
                    $grupos[] = $data;
                }
            }
        }
        return $grupos;
    }
    
    // ==================== REPORTE INDIVIDUAL ====================
    
    public function obtenerTodosEstudiantes() {
        $query = "SELECT est.idEstudiantes, est.Matricula, u.Nombre, u.Apellido, est.Grupo, est.EstadoAcademico 
                  FROM estudiantes est INNER JOIN usuarios u ON est.idUsuarios = u.idUsuarios ORDER BY u.Apellido, u.Nombre";
        $result = $this->connection->query($query);
        $data = [];
        if ($result) while ($row = $result->fetch_assoc()) $data[] = $row;
        return $data;
    }
    
    public function obtenerEstudiante($id) {
        $query = "SELECT est.idEstudiantes, est.Matricula, u.Nombre, u.Apellido, est.Grupo, est.EstadoAcademico 
                  FROM estudiantes est INNER JOIN usuarios u ON est.idUsuarios = u.idUsuarios WHERE est.idEstudiantes = ?";
        $stmt = $this->connection->prepare($query);
        $stmt->bind_param("i", $id);
        $stmt->execute();
        return $stmt->get_result()->fetch_assoc();
    }
    
    public function obtenerEvaluacionesEstudiante($id) {
        $query = "SELECT ev.Fecha, ev.Calificacion, ev.TipoEvaluacion, ev.Observaciones, c.NombreCompetencia, c.Descripcion as DescripcionCompetencia, CONCAT(u.Nombre, ' ', u.Apellido) as NombreDocente 
                  FROM evaluaciones ev 
                  INNER JOIN competencias c ON ev.idCompetencias = c.idCompetencias 
                  INNER JOIN docentes d ON ev.idDocentes = d.idDocentes 
                  INNER JOIN usuarios u ON d.idUsuarios = u.idUsuarios 
                  WHERE ev.idEstudiantes = ? ORDER BY ev.Fecha DESC";
        $stmt = $this->connection->prepare($query);
        $stmt->bind_param("i", $id);
        $stmt->execute();
        $result = $stmt->get_result();
        $data = [];
        while ($row = $result->fetch_assoc()) $data[] = $row;
        return $data;
    }

    public function obtenerResumenEstudiante($id) {
        return []; 
    }
    
    // ==================== REPORTE HISTÓRICO ====================

    public function obtenerEvaluacionesPorMes($limite = null) {
        $query = "SELECT DATE_FORMAT(Fecha, '%Y-%m') as mes, COUNT(*) as total_evaluaciones,
                  SUM(CASE WHEN Calificacion = 'Excelente' THEN 1 ELSE 0 END) as excelentes,
                  SUM(CASE WHEN Calificacion = 'Buena' OR Calificacion = 'Bueno' THEN 1 ELSE 0 END) as buenas,
                  SUM(CASE WHEN Calificacion = 'Deficiente' THEN 1 ELSE 0 END) as deficientes
                  FROM evaluaciones GROUP BY DATE_FORMAT(Fecha, '%Y-%m') ORDER BY mes DESC";
        if ($limite) $query .= " LIMIT " . intval($limite);
        $result = $this->connection->query($query);
        $datos = [];
        if ($result) while ($row = $result->fetch_assoc()) $datos[] = $row;
        return $datos;
    }

    public function obtenerCompetenciasMasEvaluadas($limite = 10) {
        $query = "SELECT c.NombreCompetencia, COUNT(ev.idEvaluaciones) as total_evaluaciones,
                  SUM(CASE WHEN ev.Calificacion = 'Excelente' THEN 1 ELSE 0 END) as excelentes,
                  SUM(CASE WHEN ev.Calificacion = 'Buena' OR ev.Calificacion = 'Bueno' THEN 1 ELSE 0 END) as buenas,
                  SUM(CASE WHEN ev.Calificacion = 'Deficiente' THEN 1 ELSE 0 END) as deficientes,
                  ROUND(AVG(CASE WHEN ev.Calificacion = 'Excelente' THEN 100 WHEN ev.Calificacion = 'Buena' OR ev.Calificacion = 'Bueno' THEN 75 WHEN ev.Calificacion = 'Deficiente' THEN 50 ELSE 0 END), 1) as promedio_porcentual
                  FROM competencias c LEFT JOIN evaluaciones ev ON c.idCompetencias = ev.idCompetencias
                  GROUP BY c.idCompetencias, c.NombreCompetencia HAVING total_evaluaciones > 0
                  ORDER BY total_evaluaciones DESC LIMIT ?";
        $stmt = $this->connection->prepare($query);
        $stmt->bind_param("i", $limite);
        $stmt->execute();
        $result = $stmt->get_result();
        $datos = [];
        while ($row = $result->fetch_assoc()) $datos[] = $row;
        return $datos;
    }

    public function obtenerTopEstudiantes($limite = 10) {
        $query = "SELECT CONCAT(u.Nombre, ' ', u.Apellido) as nombre_completo, est.Matricula, est.Grupo,
                  COUNT(ev.idEvaluaciones) as total_evaluaciones,
                  SUM(CASE WHEN ev.Calificacion = 'Excelente' THEN 1 ELSE 0 END) as excelentes,
                  ROUND(AVG(CASE WHEN ev.Calificacion = 'Excelente' THEN 100 WHEN ev.Calificacion = 'Buena' OR ev.Calificacion = 'Bueno' THEN 75 WHEN ev.Calificacion = 'Deficiente' THEN 50 ELSE 0 END), 1) as promedio_porcentual
                  FROM estudiantes est INNER JOIN usuarios u ON est.idUsuarios = u.idUsuarios
                  LEFT JOIN evaluaciones ev ON est.idEstudiantes = ev.idEstudiantes
                  WHERE est.EstadoAcademico = 'Activo' GROUP BY est.idEstudiantes, u.Nombre, u.Apellido, est.Matricula, est.Grupo
                  HAVING total_evaluaciones > 0 ORDER BY total_evaluaciones DESC LIMIT ?";
        $stmt = $this->connection->prepare($query);
        $stmt->bind_param("i", $limite);
        $stmt->execute();
        $result = $stmt->get_result();
        $datos = [];
        while ($row = $result->fetch_assoc()) $datos[] = $row;
        return $datos;
    }

    public function obtenerEstadisticasGenerales() {
        $query = "SELECT 
                (SELECT COUNT(*) FROM competencias) as total_competencias,
                (SELECT COUNT(*) FROM estudiantes WHERE EstadoAcademico = 'Activo') as total_estudiantes,
                (SELECT COUNT(*) FROM docentes) as total_docentes,
                (SELECT COUNT(*) FROM evaluaciones) as total_evaluaciones";
        $result = $this->connection->query($query);
        return $result->fetch_assoc();
    }

    public function obtenerDistribucionPorTipo() {
        $query = "SELECT TipoEvaluacion, COUNT(*) as total,
                  SUM(CASE WHEN Calificacion = 'Excelente' THEN 1 ELSE 0 END) as excelentes,
                  SUM(CASE WHEN Calificacion IN ('Buena', 'Bueno') THEN 1 ELSE 0 END) as buenas,
                  SUM(CASE WHEN Calificacion = 'Deficiente' THEN 1 ELSE 0 END) as deficientes
                  FROM evaluaciones GROUP BY TipoEvaluacion ORDER BY total DESC";
        $result = $this->connection->query($query);
        $datos = [];
        if ($result) while ($row = $result->fetch_assoc()) $datos[] = $row;
        return $datos;
    }

    // 1. Obtener las competencias con mayor índice de reprobación (Riesgo)
    public function obtenerCompetenciasMayorRiesgo($limite = 5) {
        $query = "SELECT c.NombreCompetencia,
                  COUNT(ev.idEvaluaciones) as total,
                  SUM(CASE WHEN ev.Calificacion = 'Deficiente' THEN 1 ELSE 0 END) as deficientes,
                  (SUM(CASE WHEN ev.Calificacion = 'Deficiente' THEN 1 ELSE 0 END) / COUNT(ev.idEvaluaciones)) * 100 as porcentaje_reprobacion
                  FROM competencias c
                  INNER JOIN evaluaciones ev ON c.idCompetencias = ev.idCompetencias
                  GROUP BY c.idCompetencias, c.NombreCompetencia
                  HAVING total > 0 AND deficientes > 0
                  ORDER BY porcentaje_reprobacion DESC, deficientes DESC LIMIT ?";
        $stmt = $this->connection->prepare($query);
        $stmt->bind_param("i", $limite);
        $stmt->execute();
        $result = $stmt->get_result();
        $datos = [];
        while ($row = $result->fetch_assoc()) $datos[] = $row;
        return $datos;
    }

    // 2. Calcular la tendencia (variación porcentual)
    public function obtenerVariacionMensual() {
        $sqlActual = "SELECT COUNT(*) as total FROM evaluaciones WHERE MONTH(Fecha) = MONTH(CURRENT_DATE()) AND YEAR(Fecha) = YEAR(CURRENT_DATE())";
        $resActual = $this->connection->query($sqlActual)->fetch_assoc()['total'];

        $sqlAnterior = "SELECT COUNT(*) as total FROM evaluaciones WHERE MONTH(Fecha) = MONTH(CURRENT_DATE() - INTERVAL 1 MONTH) AND YEAR(Fecha) = YEAR(CURRENT_DATE() - INTERVAL 1 MONTH)";
        $resAnterior = $this->connection->query($sqlAnterior)->fetch_assoc()['total'];

        $porcentaje = 0;
        $tendencia = 'neutral'; 

        if ($resAnterior > 0) {
            $porcentaje = (($resActual - $resAnterior) / $resAnterior) * 100;
        } else {
            $porcentaje = $resActual > 0 ? 100 : 0; 
        }

        if ($porcentaje > 0) $tendencia = 'sube';
        elseif ($porcentaje < 0) $tendencia = 'baja';

        return [
            'actual' => $resActual,
            'anterior' => $resAnterior,
            'porcentaje' => round(abs($porcentaje), 1),
            'tendencia' => $tendencia
        ];
    }

    public function cerrarConexion() {
        if ($this->connection) $this->connection->close();
    }
}
?>