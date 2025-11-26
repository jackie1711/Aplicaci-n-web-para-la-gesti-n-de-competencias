<?php
// Clase del modelo de usuario
class UserModel {
    private $connection;

    // Constructor de la clase
    public function __construct($connection) {
        $this->connection = $connection;
    }

    // Método para insertar usuarios
    public function insertarUsuario($Nombre, $Apellido, $TipoUsuario, $FechaNac, $Sexo, $Telefono, $Correo, $Contraseña) {
        $sql_statement = "INSERT INTO usuarios (Nombre, Apellido, TipoUsuario, FechaNac, Sexo, Telefono, Correo, Contrasena) VALUES (?, ?, ?, ?, ?, ?, ?, ?)";

        $statement = $this->connection->prepare($sql_statement);

        if (!$statement) {
            die("Error al preparar la consulta: " . $this->connection->error);
        }
        
        $statement->bind_param("ssssssss", $Nombre, $Apellido, $TipoUsuario, $FechaNac, $Sexo, $Telefono, $Correo, $Contraseña);
        return $statement->execute();
    }

    // Método para consultar usuarios
    public function consultarUsuarios() {
        $sql_statement = "SELECT * FROM usuarios";
        $result = $this->connection->query($sql_statement);
        return $result;
    }

    // Método para consultar un solo usuario
    public function consultarPorID($id_browser) {
        $sql_statement = "SELECT * FROM usuarios WHERE idUsuarios = ?";
        $statement = $this->connection->prepare($sql_statement);
        $statement->bind_param("i", $id_browser);
        $statement->execute();
        $result = $statement->get_result();
        return $result->fetch_assoc();
    }

    // Método para la actualización de registros
    public function actualizarUsuario($idUsuarios, $Nombre, $Apellido, $TipoUsuario, $FechaNac, $Sexo, $Telefono, $Correo, $Contraseña) {
        // Si la contraseña está vacía, no la actualizamos
        if (empty($Contraseña)) {
            $sql_statement = "UPDATE usuarios SET Nombre = ?, Apellido = ?, TipoUsuario = ?, FechaNac = ?, Sexo = ?, Telefono = ?, Correo = ? WHERE idUsuarios = ?";
            $statement = $this->connection->prepare($sql_statement);
            $statement->bind_param("sssssssi", $Nombre, $Apellido, $TipoUsuario, $FechaNac, $Sexo, $Telefono, $Correo, $idUsuarios);
        } else {
            $sql_statement = "UPDATE usuarios SET Nombre = ?, Apellido = ?, TipoUsuario = ?, FechaNac = ?, Sexo = ?, Telefono = ?, Correo = ?, Contrasena = ? WHERE idUsuarios = ?";
            $statement = $this->connection->prepare($sql_statement);
            $statement->bind_param("ssssssssi", $Nombre, $Apellido, $TipoUsuario, $FechaNac, $Sexo, $Telefono, $Correo, $Contraseña, $idUsuarios);
        }
        return $statement->execute();
    }

    // Método para eliminar usuarios por ID
    public function eliminarUsuario($idUsuarios) {
        $sql_statement = "DELETE FROM usuarios WHERE idUsuarios = ?";
        $statement = $this->connection->prepare($sql_statement);
        $statement->bind_param("i", $idUsuarios);
        return $statement->execute();
    }


    public function backup_tables() {
        $tables = [];
        $result = $this->connection->query('SHOW TABLES');
        while($row = $result->fetch_row()) {
            $tables[] = $row[0];
        }

        $return = "SET FOREIGN_KEY_CHECKS=0;\n\n";

        foreach($tables as $table) {
            $result = $this->connection->query('SELECT * FROM ' . $table);
            $num_fields = $result->field_count;

            $return .= "DROP TABLE IF EXISTS `" . $table . "`;\n";
            $row2 = $this->connection->query('SHOW CREATE TABLE ' . $table)->fetch_row();
            $return .= $row2[1] . ";\n\n";

            for ($i = 0; $i < $num_fields; $i++) {
                while($row = $result->fetch_row()) {
                    $return .= 'INSERT INTO ' . $table . ' VALUES(';
                    for($j = 0; $j < $num_fields; $j++) {
                        
                        if (isset($row[$j])) {
                            $row[$j] = addslashes($row[$j]);
                            $row[$j] = str_replace("\n", "\\n", $row[$j]);
                            $return .= '"' . $row[$j] . '"';
                        } else {
                            $return .= 'NULL';
                        }
                        
                        if ($j < ($num_fields - 1)) {
                            $return .= ',';
                        }
                    }
                    $return .= ");\n";
                }
                
            }
            $return .= "\n\n";

        }
        
        $fecha = date("Y-m-d");
            $handle = fopen('config/backups/db-backup-'.$fecha.'.sql', 'w+');
                fwrite($handle, $return);
                fclose($handle); 
        
        $return .= "SET FOREIGN_KEY_CHECKS=1;";
        return $return;
    }

    public function restaurarBD($sqlContent) {
        $sqlContent = preg_replace('/--.*|(\/\*[\s\S]*?\*\/)/', '', $sqlContent);
        
        if ($this->connection->multi_query($sqlContent)) {
            do {
                if ($result = $this->connection->store_result()) {
                    $result->free();
                }
            } while ($this->connection->more_results() && $this->connection->next_result());
            return true;
        }
        return false;
    }
}
?>