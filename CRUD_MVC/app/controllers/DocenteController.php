<?php
include_once "app/models/DocenteModel.php";
include_once "config/db_connection.php"; 

class DocenteController{
    private $model;

    public function __construct($connection){
        $this->model = new DocenteModel($connection);
    }

    // --- MÉTODOS DE GESTIÓN (ADMIN) ---

    // 1. Listar Docentes
    public function gestionarDocentes(){
        if (session_status() === PHP_SESSION_NONE) session_start();
        // Verificar admin
        if(!isset($_SESSION['tipo_usuario']) || $_SESSION['tipo_usuario'] !== 'Administrador'){
            header("Location: index.php?controller=auth&action=mostrarLogin");
            exit();
        }

        $docentes = $this->model->consultarTodosDocentes();
        include_once "app/views/docente/gestion_docentes.php";
    }

    // 2. Editar Docente
    public function editarDocente(){
        if (session_status() === PHP_SESSION_NONE) session_start();

        // Cargar vista
        if(isset($_GET['id'])){
            $id = (int)$_GET['id'];
            $docente = $this->model->consultarDocentePorID($id);
            if($docente){
                include_once "app/views/docente/editar_docente.php";
            } else {
                $_SESSION['error'] = "Docente no encontrado";
                header("Location: index.php?controller=docente&action=gestionarDocentes");
            }
            return;
        }

        // Procesar Formulario
        if(isset($_POST['editar'])){
            $id = $_POST['id'];
            $res = $this->model->actualizarDocenteCompleto(
                $id,
                $_POST['Nombre'],
                $_POST['Apellido'],
                $_POST['Correo'],
                $_POST['Telefono'],
                $_POST['Sexo'],
                $_POST['FechaNac'],
                $_POST['Especialidad'],
                $_POST['MateriaImpartida']
            );

            if($res) $_SESSION['mensaje'] = "Docente actualizado correctamente";
            else $_SESSION['error'] = "Error al actualizar";
            
            header("Location: index.php?controller=docente&action=gestionarDocentes");
            exit();
        }
    }

    // 3. Eliminar Docente
    public function eliminarDocente(){
        if(isset($_GET['id'])){
            $id = (int)$_GET['id'];
            if($this->model->eliminarDocente($id)){
                $_SESSION['mensaje'] = "Docente eliminado del sistema";
            } else {
                $_SESSION['error'] = "Error al eliminar docente";
            }
            header("Location: index.php?controller=docente&action=gestionarDocentes");
            exit();
        }
    }

    // --- MÉTODOS DE PERFIL (DOCENTE) ---
    
    // Mostrar perfil del docente actual
    public function miPerfil(){
        if (session_status() === PHP_SESSION_NONE) session_start();
        if(!isset($_SESSION['usuario_id'])){
            header("Location: index.php?controller=auth&action=mostrarLogin");
            exit();
        }
        $docente = $this->model->consultarDocentePorUsuarioID($_SESSION['usuario_id']);
        include_once "app/views/docente/mi_perfil.php";
    }
    
    // NUEVO: Actualizar perfil del docente actual
    public function actualizarMiPerfil(){
        if (session_status() === PHP_SESSION_NONE) session_start();
        
        // Verificar autenticación
        if(!isset($_SESSION['usuario_id'])){
            header("Location: index.php?controller=auth&action=mostrarLogin");
            exit();
        }

        // Procesar formulario
        if(isset($_POST['actualizar_perfil'])){
            $especialidad = $_POST['Especialidad'];
            $materiaImpartida = $_POST['MateriaImpartida'];
            
            // Actualizar solo la información académica del docente
            $resultado = $this->model->actualizarPerfilDocente(
                $_SESSION['usuario_id'], 
                $especialidad, 
                $materiaImpartida
            );

            if($resultado){
                $_SESSION['mensaje'] = "Perfil actualizado correctamente";
            } else {
                $_SESSION['error'] = "Error al actualizar el perfil";
            }
        }

        // Redirigir de vuelta al perfil
        header("Location: index.php?controller=docente&action=miPerfil");
        exit();
    }
    
    // Métodos antiguos (insertarDocente, etc.) se mantienen si los usas
    
    //metodo para insertar docentes 
    public function insertarDocente(){
        if(isset($_POST['enviar'])){
            $Especialidad = $_POST['Especialidad'];
            $MateriaImpartida = $_POST['MateriaImpartida'];

            $insert = $this -> model -> insertarDocente($Especialidad,$MateriaImpartida);

            //verificar la insercion
            if($insert){
                echo "<br> Registro exitoso";
            }else{
                echo  "<br> Error en el registro";
            }
        }
        include_once "app/views/docente/insert_docente.php";
    }

    //metodo para consultar docentes
    public function consultarDocente(){
        $docentes = $this -> model -> consultarDocente();
        include_once "app/views/docente/consult_docente.php";
    }
}
?>