<?php
class DocenteModel{
    private $connection;

    public function __construct($connection){
        $this->connection = $connection;
    }

    // Insertar (Registro básico)
    public function insertarDocente($Especialidad, $MateriaImpartida){
        // Este método usualmente se llama desde AuthModel en el registro, 
        // pero lo dejamos por compatibilidad si se usa aislado.
        $sql = "INSERT INTO docentes (Especialidad, MateriaImpartida) VALUES (?, ?)"; 
        $stmt = $this->connection->prepare($sql);
        $stmt->bind_param("ss", $Especialidad, $MateriaImpartida); 
        return $stmt->execute(); 
    }

    // GESTIÓN: Obtener lista completa con nombres
    public function consultarTodosDocentes(){
        $sql = "SELECT d.*, u.Nombre, u.Apellido, u.Correo, u.Telefono, u.Sexo, u.FechaNac 
                FROM docentes d 
                INNER JOIN usuarios u ON d.idUsuarios = u.idUsuarios 
                ORDER BY u.Apellido, u.Nombre";
        return $this->connection->query($sql);
    }

    // GESTIÓN: Obtener un solo docente por ID (para editar)
    public function consultarDocentePorID($idDocente){
        $sql = "SELECT d.*, u.Nombre, u.Apellido, u.Correo, u.Telefono, u.Sexo, u.FechaNac 
                FROM docentes d 
                INNER JOIN usuarios u ON d.idUsuarios = u.idUsuarios 
                WHERE d.idDocentes = ?";
        $stmt = $this->connection->prepare($sql);
        $stmt->bind_param("i", $idDocente);
        $stmt->execute();
        return $stmt->get_result()->fetch_assoc();
    }

    // GESTIÓN: Actualizar TODA la información (Admin)
    public function actualizarDocenteCompleto($idDocente, $Nombre, $Apellido, $Correo, $Telefono, $Sexo, $FechaNac, $Especialidad, $MateriaImpartida){
        // 1. Obtener idUsuario
        $sqlGet = "SELECT idUsuarios FROM docentes WHERE idDocentes = ?";
        $stmtGet = $this->connection->prepare($sqlGet);
        $stmtGet->bind_param("i", $idDocente);
        $stmtGet->execute();
        $idUsuario = $stmtGet->get_result()->fetch_assoc()['idUsuarios'];

        // 2. Actualizar datos personales (Tabla Usuarios)
        $sqlUser = "UPDATE usuarios SET Nombre=?, Apellido=?, Correo=?, Telefono=?, Sexo=?, FechaNac=? WHERE idUsuarios=?";
        $stmtUser = $this->connection->prepare($sqlUser);
        $stmtUser->bind_param("ssssssi", $Nombre, $Apellido, $Correo, $Telefono, $Sexo, $FechaNac, $idUsuario);
        $resUser = $stmtUser->execute();

        // 3. Actualizar datos académicos (Tabla Docentes)
        $sqlDoc = "UPDATE docentes SET Especialidad=?, MateriaImpartida=? WHERE idDocentes=?";
        $stmtDoc = $this->connection->prepare($sqlDoc);
        $stmtDoc->bind_param("ssi", $Especialidad, $MateriaImpartida, $idDocente);
        $resDoc = $stmtDoc->execute();

        return $resUser && $resDoc;
    }

    // PERFIL: Actualizar solo información académica (Docente)
    public function actualizarPerfilDocente($idUsuario, $Especialidad, $MateriaImpartida){
        $sql = "UPDATE docentes SET Especialidad=?, MateriaImpartida=? WHERE idUsuarios=?";
        $stmt = $this->connection->prepare($sql);
        $stmt->bind_param("ssi", $Especialidad, $MateriaImpartida, $idUsuario);
        return $stmt->execute();
    }

    // GESTIÓN: Eliminar (Borra el usuario y por cascada el docente)
    public function eliminarDocente($idDocente){
        // Obtener idUsuario primero
        $sqlGet = "SELECT idUsuarios FROM docentes WHERE idDocentes = ?";
        $stmtGet = $this->connection->prepare($sqlGet);
        $stmtGet->bind_param("i", $idDocente);
        $stmtGet->execute();
        $row = $stmtGet->get_result()->fetch_assoc();
        
        if($row){
            $idUsuario = $row['idUsuarios'];
            // Al borrar el usuario, la FK con ON DELETE CASCADE borra al docente automáticamente
            $sqlDel = "DELETE FROM usuarios WHERE idUsuarios = ?";
            $stmtDel = $this->connection->prepare($sqlDel);
            $stmtDel->bind_param("i", $idUsuario);
            return $stmtDel->execute();
        }
        return false;
    }

    // Para el Perfil (Por ID de Usuario)
    public function consultarDocentePorUsuarioID($idUsuario){
        $sql = "SELECT d.*, u.Nombre, u.Apellido, u.Correo, u.Telefono, u.Sexo, u.FechaNac 
                FROM docentes d INNER JOIN usuarios u ON d.idUsuarios = u.idUsuarios 
                WHERE d.idUsuarios = ?";
        $stmt = $this->connection->prepare($sql);
        $stmt->bind_param("i", $idUsuario);
        $stmt->execute();
        return $stmt->get_result()->fetch_assoc();
    }
}
?>