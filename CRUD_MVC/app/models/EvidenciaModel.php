<?php

class EvidenciaModel {
    private $connection;

    public function __construct($connection) {
        $this->connection = $connection;
    }

    // NUEVO MÉTODO: Obtener idEstudiantes por idUsuarios
    public function obtenerIdEstudiantePorUsuario($idUsuario) {
        $sql = "SELECT idEstudiantes FROM estudiantes WHERE idUsuarios = ?";
        $stmt = $this->connection->prepare($sql);
        $stmt->bind_param("i", $idUsuario);
        $stmt->execute();
        $result = $stmt->get_result();
        
        if ($result->num_rows > 0) {
            $row = $result->fetch_assoc();
            $stmt->close();
            return $row['idEstudiantes'];
        }
        
        $stmt->close();
        return false;
    }

    // Insertar nueva evidencia
    public function insertarEvidencia($idEstudiantes, $idCompetencias, $nombreArchivo, $rutaArchivo, $tipoArchivo, $tamanoArchivo) {
        try {
            $sql = "INSERT INTO evidencias (idEstudiantes, idCompetencias, NombreArchivo, RutaArchivo, TipoArchivo, TamanoArchivo, Estado) 
                    VALUES (?, ?, ?, ?, ?, ?, 'Pendiente')";
            
            $statement = $this->connection->prepare($sql);
            
            if (!$statement) {
                error_log("Error prepare: " . $this->connection->error);
                return false;
            }
            
            $statement->bind_param("iisssi", 
                $idEstudiantes, 
                $idCompetencias, 
                $nombreArchivo, 
                $rutaArchivo, 
                $tipoArchivo, 
                $tamanoArchivo
            );
            
            if ($statement->execute()) {
                $insertId = $this->connection->insert_id;
                $statement->close();
                return $insertId;
            } else {
                error_log("Error execute: " . $statement->error);
                $statement->close();
                return false;
            }
            
        } catch (Exception $e) {
            error_log("Exception insertarEvidencia: " . $e->getMessage());
            return false;
        }
    }

    // Obtener evidencias de un estudiante
    public function obtenerEvidenciasPorEstudiante($idEstudiantes) {
        $sql = "SELECT e.*, c.NombreCompetencia 
                FROM evidencias e
                INNER JOIN competencias c ON e.idCompetencias = c.idCompetencias
                WHERE e.idEstudiantes = ?
                ORDER BY e.FechaSubida DESC";
        
        $statement = $this->connection->prepare($sql);
        $statement->bind_param("i", $idEstudiantes);
        $statement->execute();
        return $statement->get_result();
    }

    // Obtener todas las competencias
    public function obtenerCompetencias() {
        $sql = "SELECT idCompetencias, NombreCompetencia FROM competencias ORDER BY NombreCompetencia";
        return $this->connection->query($sql);
    }

    // Obtener datos de una evidencia específica
    public function obtenerEvidenciaPorId($idEvidencia) {
        $sql = "SELECT * FROM evidencias WHERE idEvidencias = ?";
        $statement = $this->connection->prepare($sql);
        $statement->bind_param("i", $idEvidencia);
        $statement->execute();
        $result = $statement->get_result();
        return $result->fetch_assoc();
    }

    // Eliminar evidencia
    public function eliminarEvidencia($idEvidencia) {
        $sql = "DELETE FROM evidencias WHERE idEvidencias = ?";
        $statement = $this->connection->prepare($sql);
        $statement->bind_param("i", $idEvidencia);
        $resultado = $statement->execute();
        $statement->close();
        return $resultado;
    }

    // Actualizar estado de evidencia
    public function actualizarEstado($idEvidencia, $estado, $observaciones = '') {
        $sql = "UPDATE evidencias SET Estado = ?, Observaciones = ? WHERE idEvidencias = ?";
        $statement = $this->connection->prepare($sql);
        $statement->bind_param("ssi", $estado, $observaciones, $idEvidencia);
        $resultado = $statement->execute();
        $statement->close();
        return $resultado;
    }
}
?>