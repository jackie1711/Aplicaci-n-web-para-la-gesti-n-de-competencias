<?php
// Habilitar reporte de errores para desarrollo
error_reporting(E_ALL);
ini_set('display_errors', 1);
ini_set('log_errors', 1);
ini_set('error_log', __DIR__ . '/php_errors.log');

session_start();

// Incluir conexión a BD
$dbPath = __DIR__ . "/config/db_connection.php";
if (!file_exists($dbPath)) {
    die("Error: No se encuentra el archivo de conexión en: " . $dbPath);
}
include_once $dbPath;

// Verificar que la conexión esté disponible
if (!isset($connection) || !$connection) {
    die("Error: No se pudo establecer la conexión a la base de datos");
}

// Obtener el controlador y acción desde la URL
$controller = isset($_GET['controller']) ? $_GET['controller'] : 'auth';
$action = isset($_GET['action']) ? $_GET['action'] : 'mostrarLogin';

// Log para debug
error_log("=== REQUEST ===");
error_log("Controller: " . $controller);
error_log("Action: " . $action);

// Middleware de autenticación (excepto para auth)
function requiereAutenticacion() {
    if (!isset($_SESSION['usuario_id'])) {
        header("Location: index.php?controller=auth&action=mostrarLogin");
        exit();
    }
}

// Enrutamiento para autenticación
if($controller == 'auth'){
    $controllerPath = __DIR__ . "/app/controllers/AuthController.php";
    
    if (!file_exists($controllerPath)) {
        die("Error: No se encuentra AuthController.php en: " . $controllerPath);
    }
    
    include_once $controllerPath;
    
    if (!class_exists('AuthController')) {
        die("Error: La clase AuthController no fue cargada correctamente desde: " . $controllerPath);
    }
    
    try {
        $authController = new AuthController($connection);
        
        switch($action){
            case 'mostrarLogin':
                $authController->mostrarLogin();
                break;
            case 'login':
                $authController->login();
                break;
            case 'mostrarRegistro':
                $authController->mostrarRegistro();
                break;
            case 'registroDirecto':
                $authController->registroDirecto();
                break;
            case 'registro':
                $authController->registro();
                break;
            case 'panelAdministrativo':
                $authController->panelAdministrativo();
                break;
            case 'panelDocente':
                $authController->panelDocente();
                break;
            case 'panelEstudiante':
                $authController->panelEstudiante();
                break;
            case 'logout':
                $authController->logout();
                break;
            default:
                $authController->mostrarLogin();
        }
    } catch (Exception $e) {
        error_log("Error en AuthController: " . $e->getMessage());
        die("Error: " . $e->getMessage());
    }
    
    exit();
}

// Requiere autenticación para los demás controladores
requiereAutenticacion();

// Enrutamiento para usuarios
if($controller == 'user'){
    $controllerPath = __DIR__ . "/app/controllers/UserController.php";
    
    if (!file_exists($controllerPath)) {
        die("Error: No se encuentra UserController.php");
    }
    
    include_once $controllerPath;
    $userController = new UserController($connection);
    
    switch($action){
        case 'insert':
            $userController->insertarUsuario();
            break;
        case 'consult':
            $userController->consultarUsuarios();
            break;
        case 'update':
            $userController->actualizarUsuario();
            break;
        case 'delete':
            $userController->eliminarUsuario();
            break;
        case 'backup':
            $userController->realizarRespaldoBD();
            break; 
        case 'restore':
            $userController->restaurarBD();
            break;
        default:
            $userController->insertarUsuario();
    }
}

// Enrutamiento para estudiantes
if($controller == 'estudiante'){
    $controllerPath = __DIR__ . "/app/controllers/EstudianteController.php";
    
    if (!file_exists($controllerPath)) {
        die("Error: No se encuentra EstudianteController.php");
    }
    
    include_once $controllerPath;
    $estudianteController = new EstudianteController($connection);
    
    switch($action){
        case 'evidenciasPendientes':
            $estudianteController->evidenciasPendientes();
            break;
        case 'subirEvidencia':
            $estudianteController->subirEvidencia();
            break;
        case 'misConsultas':
            $estudianteController->misConsultas();
            break;
        case 'misReportes':
            $estudianteController->misReportes();
            break;
         case 'exportarReportePDF':
            $estudianteController->exportarReportePDF();
            break;
        case 'gestionarEstudiantes':
            $estudianteController->gestionarEstudiantes();
            break;
        case 'editarEstudiante':
            $estudianteController->editarEstudiante();
            break;
        case 'eliminarEstudiante':
            $estudianteController->eliminarEstudiante();
            break;
        default:
            $estudianteController->evidenciasPendientes();
    }
}

// Enrutamiento para docentes
if($controller == 'docente'){
    $controllerPath = __DIR__ . "/app/controllers/DocenteController.php";
    
    if (!file_exists($controllerPath)) {
        die("Error: No se encuentra DocenteController.php");
    }
    
    include_once $controllerPath;
    $docenteController = new DocenteController($connection);
    
    switch($action){
        case 'gestionarDocentes':
            $docenteController->gestionarDocentes();
            break;
        case 'editarDocente':
            $docenteController->editarDocente();
            break;
        case 'eliminarDocente':
            $docenteController->eliminarDocente();
            break;
        case 'insert':
            $docenteController->insertarDocente();
            break;
        case 'consult':
            $docenteController->consultarDocente();
            break;
        case 'update':
            $docenteController->actualizarDocente();
            break;
        case 'delete':
            $docenteController->eliminarDocente();
            break;
        case 'miPerfil':
            $docenteController->miPerfil();
            break;
        case 'actualizarMiPerfil':
            $docenteController->actualizarMiPerfil();
            break;
        default:
            $docenteController->gestionarDocentes();
    }
}

// Enrutamiento para competencias
if($controller == 'competencia'){
    $controllerPath = __DIR__ . "/app/controllers/CompetenciaController.php";
    
    if (!file_exists($controllerPath)) {
        die("Error: No se encuentra CompetenciaController.php");
    }
    
    include_once $controllerPath;
    $competenciaController = new CompetenciaController($connection);
    
    switch($action){
        case 'consult':
            $competenciaController->consultarCompetencia();
            break;
        case 'update':
            $competenciaController->actualizarCompetencia();
            break;
        case 'delete':
            $competenciaController->eliminarCompetencia();
            break;
        default:
            $competenciaController->insertarCompetencia();
    }
}

// Enrutamiento para evaluaciones
if($controller == 'evaluacion'){
    $controllerPath = __DIR__ . "/app/controllers/EvaluacionController.php";
    
    if (!file_exists($controllerPath)) {
        die("Error: No se encuentra EvaluacionController.php");
    }
    
    include_once $controllerPath;
    $evaluacionController = new EvaluacionController($connection);
    
    switch($action){
        case 'insert': 
            $evaluacionController->insertarEvaluacion();
            break;
        case 'consult':
            $evaluacionController->consultarEvaluacion();
            break;
        case 'update':
            $evaluacionController->actualizarEvaluacion();
            break;
        case 'delete':
            $evaluacionController->eliminarEvaluacion();
            break;
        default:
            $evaluacionController->consultarEvaluacion();
    }
}

// Enrutamiento para evidencias
if($controller == 'evidencia'){
    $controllerPath = __DIR__ . "/app/controllers/EvidenciaController.php";
    
    if (file_exists($controllerPath)) {
        include_once $controllerPath;
        $evidenciaController = new EvidenciaController($connection);
        
        switch($action){
            case 'subir':
                $evidenciaController->subirEvidencia();
                break;
            case 'mostrarFormulario':
                $evidenciaController->mostrarFormulario();
                break;
            case 'misEvidencias':
                $evidenciaController->misEvidencias();
                break;
            case 'eliminar':
                $evidenciaController->eliminarEvidencia();
                break;
            case 'descargar':
                $evidenciaController->descargarEvidencia();
                break;
            default:
                $evidenciaController->mostrarFormulario();
        }
    }
}

// Enrutamiento para consultas
if($controller == 'consulta'){
    $controllerPath = __DIR__ . "/app/controllers/ConsultaController.php";
    
    if (file_exists($controllerPath)) {
        include_once $controllerPath;
        $consultaController = new ConsultaController($connection);
        
        switch($action){
            case 'individual':
                $consultaController->mostrarConsultaIndividual();
                break;
            case 'competencia':
                $consultaController->mostrarConsultaPorCompetencia();
                break;
            default:
                $consultaController->mostrarConsultaIndividual();
        }
    }
}

// Enrutamiento para reportes
if($controller == 'reporte'){
    $controllerPath = __DIR__ . "/app/controllers/ReporteController.php";
    
    if (file_exists($controllerPath)) {
        include_once $controllerPath;
        $reporteController = new ReporteController();
        
        switch($action){
            case 'grupal':
                $reporteController->grupal();
                break;
            case 'individual':
                $reporteController->individual();
                break;
            case 'historico':
                $reporteController->historico();
                break;
            case 'exportarIndividualPDF':
                $reporteController->exportarIndividualPDF();
                break;
            case 'exportarGrupalPDF':
                $reporteController->exportarGrupalPDF();
                break;
            case 'exportarHistoricoPDF': 
                $reporteController->exportarHistoricoPDF();
                break;
            default:
                $reporteController->grupal();
        }
    }
}

// Enrutamiento para asignaciones
if($controller == 'asignacion'){
    $controllerPath = __DIR__ . "/app/controllers/AsignacionController.php";
    
    if (file_exists($controllerPath)) {
        include_once $controllerPath;
        $asignacionController = new AsignacionController($connection);
        
        switch($action){
            case 'listar':
                $asignacionController->listar();
                break;
            case 'crear':
                $asignacionController->crear();
                break;
            case 'evaluar':
                $asignacionController->evaluar();
                break;
            case 'eliminar':
                $asignacionController->eliminar();
                break;
            default:
                $asignacionController->listar();
        }
    }
}
?>