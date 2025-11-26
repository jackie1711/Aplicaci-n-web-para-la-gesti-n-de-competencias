<?php
// Incluir la conexión a la BD y el modelo del usuario
include_once "config/db_connection.php";
include_once "app/models/UserModel.php";

class UserController {
    private $model;

    public function __construct($connection) {
        $this->model = new UserModel($connection);
    }

    // Método para insertar usuario (Desde formulario de registro)
    public function insertarUsuario() {
        if (isset($_POST['enviar'])) {
            $Nombre = trim($_POST['Nombre']);
            $Apellido = $_POST['Apellido'];
            $TipoUsuario = $_POST['TipoUsuario'];
            $FechaNac = $_POST['FechaNac'];
            $Sexo = $_POST['Sexo'];
            $Telefono = $_POST['Telefono'];
            $Correo = $_POST['Correo'];
            $Contraseña = password_hash($_POST['Contraseña'], PASSWORD_BCRYPT);

            $insert = $this->model->insertarUsuario($Nombre, $Apellido, $TipoUsuario, $FechaNac, $Sexo, $Telefono, $Correo, $Contraseña);

            if ($insert) {
                echo "<script>alert('Registro exitoso'); window.location='index.php?controller=auth&action=panelAdministrativo';</script>";
            } else {
                echo "<script>alert('Error en el registro'); window.history.back();</script>";
            }
        }
    }

    // Consultar usuarios
    public function consultarUsuarios() {
        $usuarios = $this->model->consultarUsuarios();
        include "app/views/user/consult.php";
    }

    // Actualizar usuario
    public function actualizarUsuario() {
        if (isset($_GET['id'])) {
            $id_browser = (int) $_GET['id'];
            $row = $this->model->consultarPorID($id_browser);
            include_once "app/views/user/edit.php";
            return;
        }

        if (isset($_POST['editar'])) {
            $idUsuarios = $_POST['id'];
            $Nombre = trim($_POST['Nombre']);
            $Apellido = $_POST['Apellido'];
            $TipoUsuario = $_POST['TipoUsuario'];
            $FechaNac = $_POST['FechaNac'];
            $Sexo = $_POST['Sexo'];
            $Telefono = $_POST['Telefono'];
            $Correo = $_POST['Correo'];
            
            // Solo encriptar si se escribió una nueva contraseña
            $Contraseña = !empty($_POST['Contraseña']) ? password_hash($_POST['Contraseña'], PASSWORD_BCRYPT) : '';

            $update = $this->model->actualizarUsuario($idUsuarios, $Nombre, $Apellido, $TipoUsuario, $FechaNac, $Sexo, $Telefono, $Correo, $Contraseña);

            if ($update) {
                header("Location: index.php?action=consult");
            } else {
                header("Location: index.php?action=update&id=" . $idUsuarios);
            }
        }
    }

    // Eliminar usuario
    public function eliminarUsuario() {
        if (isset($_GET['id'])) {
            $id_browser = (int) $_GET['id'];
            $delete = $this->model->eliminarUsuario($id_browser);

            if ($delete) {
                header("Location: index.php?action=consult");
            } else {
                echo "Error al eliminar el usuario";
            }
        }
    }


    public function realizarRespaldoBD() {
        $sqlContent = $this->model->backup_tables();
        $fecha = date("Y-m-d_H-i-s");
        $nombreArchivo = "respaldo_sistema_" . $fecha . ".sql";

        header('Content-Type: application/octet-stream');
        header("Content-Transfer-Encoding: Binary");
        header("Content-disposition: attachment; filename=\"" . $nombreArchivo . "\"");
        echo $sqlContent;
        exit;
    }

    
    public function restaurarBD() {
        if (isset($_POST['restaurar']) && isset($_FILES['archivo_sql'])) {
            $tmpName = $_FILES['archivo_sql']['tmp_name'];
            
            if (is_uploaded_file($tmpName)) {
                $sqlContent = file_get_contents($tmpName);
                
                if ($this->model->restaurarBD($sqlContent)) {
                    echo "<script>alert('¡Restauración completada con éxito!'); window.location='index.php?controller=auth&action=panelAdministrativo';</script>";
                } else {
                    echo "<script>alert('Error al restaurar la base de datos. El archivo podría estar dañado.'); window.location='index.php?controller=auth&action=panelAdministrativo';</script>";
                }
            } else {
                echo "<script>alert('Error al subir el archivo.'); window.location='index.php?controller=auth&action=panelAdministrativo';</script>";
            }
        } else {
            // Si entra directo sin POST, redirigir al panel
            header("Location: index.php?controller=auth&action=panelAdministrativo");
        }
    }
}
?>