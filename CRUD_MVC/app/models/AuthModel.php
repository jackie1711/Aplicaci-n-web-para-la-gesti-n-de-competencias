<?php

class AuthModel {
    private $connection;

    public function __construct($connection) {
        $this->connection = $connection;
    }

    // Buscar usuario por correo
    public function buscarUsuarioPorCorreo($correo) {
        $sql = "SELECT idUsuarios, Nombre, Apellido, TipoUsuario, Correo, Contrasena 
                FROM usuarios 
                WHERE Correo = ?";
        $statement = $this->connection->prepare($sql);
        $statement->bind_param("s", $correo);
        $statement->execute();
        $result = $statement->get_result();
        return $result->fetch_assoc();
    }

    // Verificar si el correo existe
    public function correoExiste($correo) {
        $sql = "SELECT COUNT(*) as total FROM usuarios WHERE Correo = ?";
        $statement = $this->connection->prepare($sql);
        $statement->bind_param("s", $correo);
        $statement->execute();
        $result = $statement->get_result();
        $row = $result->fetch_assoc();
        return $row['total'] > 0;
    }

    // Verificar si la matrícula existe (para estudiantes)
    public function matriculaExiste($matricula) {
        $sql = "SELECT COUNT(*) as total FROM estudiantes WHERE Matricula = ?";
        $statement = $this->connection->prepare($sql);
        $statement->bind_param("s", $matricula);
        $statement->execute();
        $result = $statement->get_result();
        $row = $result->fetch_assoc();
        return $row['total'] > 0;
    }

    // Insertar usuario (Tabla General)
    public function insertarUsuario($nombre, $apellido, $tipoUsuario, $fechaNac, $sexo, $telefono, $correo, $contrasena) {
        $sql = "INSERT INTO usuarios (Nombre, Apellido, TipoUsuario, FechaNac, Sexo, Telefono, Correo, Contrasena) 
                VALUES (?, ?, ?, ?, ?, ?, ?, ?)";
        
        $statement = $this->connection->prepare($sql);
        
        // Validar que la preparación fue exitosa
        if (!$statement) {
            error_log("Error prepare usuarios: " . $this->connection->error);
            return false;
        }

        $statement->bind_param("ssssssss", $nombre, $apellido, $tipoUsuario, $fechaNac, $sexo, $telefono, $correo, $contrasena);
        
        if ($statement->execute()) {
            return $this->connection->insert_id;
        } else {
            error_log("Error execute usuarios: " . $statement->error);
            return false;
        }
    }

    // Insertar docente
    public function insertarDocente($idUsuario, $especialidad, $materias) {
        $sql = "INSERT INTO docentes (idUsuarios, Especialidad, MateriaImpartida) VALUES (?, ?, ?)";
        $statement = $this->connection->prepare($sql);
        $statement->bind_param("iss", $idUsuario, $especialidad, $materias);
        return $statement->execute();
    }

    // Insertar estudiante
    public function insertarEstudiante($idUsuario, $matricula, $grupo) {
        $sql = "INSERT INTO estudiantes (idUsuarios, Matricula, Grupo, EstadoAcademico) VALUES (?, ?, ?, 'Activo')";
        $statement = $this->connection->prepare($sql);
        $statement->bind_param("iss", $idUsuario, $matricula, $grupo);
        return $statement->execute();
    }
    
    // NUEVO: Insertar administrador (ESTO ES LO QUE FALTABA)
    public function insertarAdministrador($idUsuario, $numeroEmpleado, $departamento, $cargo) {
        // Aseguramos que la tabla existe, si no, insertamos solo en usuarios
        $sqlCheck = "SHOW TABLES LIKE 'administradores'";
        $resCheck = $this->connection->query($sqlCheck);
        
        if($resCheck->num_rows > 0) {
            $sql = "INSERT INTO administradores (idUsuarios, NumeroEmpleado, Departamento, Cargo) VALUES (?, ?, ?, ?)";
            $statement = $this->connection->prepare($sql);
            
            if (!$statement) {
                error_log("Error prepare admin: " . $this->connection->error);
                return false;
            }
            
            $statement->bind_param("isss", $idUsuario, $numeroEmpleado, $departamento, $cargo);
            return $statement->execute();
        } else {
            // Si no existe la tabla administradores, retornamos true porque el usuario ya se creó
            return true;
        }
    }

    // Eliminar usuario (en caso de error en registro)
    public function eliminarUsuario($idUsuario) {
        $sql = "DELETE FROM usuarios WHERE idUsuarios = ?";
        $statement = $this->connection->prepare($sql);
        $statement->bind_param("i", $idUsuario);
        return $statement->execute();
    }
}
?>