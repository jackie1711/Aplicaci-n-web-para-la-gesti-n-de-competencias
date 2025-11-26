<?php
require_once 'app/models/ReporteModel.php';
// Ajusta esta ruta si tu carpeta de librerías tiene otro nombre
require_once 'public/libraries/fpdf/fpdf.php';

class ReporteController {
    private $model;
    
    public function __construct() {
        $this->model = new ReporteModel();
    }
    
    // ==================== VISTAS HTML (Se mantienen con tus mejoras) ====================
    
    public function grupal() {
        if (session_status() === PHP_SESSION_NONE) session_start();
        if (!isset($_SESSION['usuario_id'])) { header("Location: index.php?controller=auth&action=login"); exit(); }
        
        try {
            $datosGrupos = $this->model->obtenerEstadisticasTodosGrupos();
            include 'app/views/reportes/grupal.php';
        } catch (Exception $e) { echo $e->getMessage(); }
    }
    
    public function individual() {
        if (session_status() === PHP_SESSION_NONE) session_start();
        if (!isset($_SESSION['usuario_id'])) { header("Location: index.php?controller=auth&action=login"); exit(); }
        
        try {
            $estudiantes = $this->model->obtenerTodosEstudiantes();
            $estudianteSeleccionado = null; $evaluaciones = []; $resumen = null;
            if (isset($_GET['estudiante_id']) && !empty($_GET['estudiante_id'])) {
                $idEstudiante = intval($_GET['estudiante_id']);
                $estudianteSeleccionado = $this->model->obtenerEstudiante($idEstudiante);
                if ($estudianteSeleccionado) {
                    $evaluaciones = $this->model->obtenerEvaluacionesEstudiante($idEstudiante);
                    $resumen = $this->model->obtenerResumenEstudiante($idEstudiante);
                }
            }
            include 'app/views/reportes/individual.php';
        } catch (Exception $e) { echo $e->getMessage(); }
    }

    public function historico() {
        if (session_status() === PHP_SESSION_NONE) session_start();
        if (!isset($_SESSION['usuario_id'])) { header("Location: index.php?controller=auth&action=login"); exit(); }
        
        try {
            // Datos completos para la vista HTML (Tablas, Gráficas JS, Riesgo, Tendencias)
            $evaluacionesPorMes = $this->model->obtenerEvaluacionesPorMes(12);
            $competenciasMasEvaluadas = $this->model->obtenerCompetenciasMasEvaluadas(10);
            $topEstudiantes = $this->model->obtenerTopEstudiantes(10);
            $estadisticasGenerales = $this->model->obtenerEstadisticasGenerales();
            $distribucionPorTipo = $this->model->obtenerDistribucionPorTipo();
            $competenciasRiesgo = $this->model->obtenerCompetenciasMayorRiesgo(5);
            $variacionMensual = $this->model->obtenerVariacionMensual();
            
            include 'app/views/reportes/historicos.php';
        } catch (Exception $e) {
            $_SESSION['error'] = "Error: " . $e->getMessage();
            header("Location: index.php?controller=auth&action=panelAdministrativo");
            exit();
        }
    }

    // ==================== GENERACIÓN DE PDFS ====================

    public function exportarIndividualPDF() {
        if (!isset($_GET['estudiante_id'])) { die("Error: Falta ID de estudiante"); }
        
        $idEstudiante = intval($_GET['estudiante_id']);
        $est = $this->model->obtenerEstudiante($idEstudiante);
        $evaluaciones = $this->model->obtenerEvaluacionesEstudiante($idEstudiante);

        if (!$est) { die("Estudiante no encontrado"); }

        $pdf = new FPDF();
        $pdf->AddPage();
        $pdf->SetFont('Arial', 'B', 16);
        $pdf->SetTextColor(74, 30, 111);
        $pdf->Cell(0, 10, mb_convert_encoding('Reporte Individual de Desempeño', 'ISO-8859-1', 'UTF-8'), 0, 1, 'C');
        $pdf->SetFont('Arial', '', 10);
        $pdf->SetTextColor(100);
        $pdf->Cell(0, 5, 'Generado el: ' . date('d/m/Y H:i'), 0, 1, 'C');
        $pdf->Ln(10);

        $pdf->SetFont('Arial', 'B', 12);
        $pdf->SetFillColor(245, 245, 245);
        $pdf->SetTextColor(0);
        $pdf->Cell(0, 10, mb_convert_encoding('Información del Estudiante', 'ISO-8859-1', 'UTF-8'), 0, 1, 'L', true);
        $pdf->Ln(2);
        
        $pdf->SetFont('Arial', 'B', 10);
        $pdf->Cell(30, 8, 'Nombre:', 0, 0);
        $pdf->SetFont('Arial', '', 10);
        $pdf->Cell(80, 8, mb_convert_encoding($est['Nombre'] . ' ' . $est['Apellido'], 'ISO-8859-1', 'UTF-8'), 0, 0);
        $pdf->SetFont('Arial', 'B', 10);
        $pdf->Cell(25, 8, mb_convert_encoding('Matrícula:', 'ISO-8859-1', 'UTF-8'), 0, 0);
        $pdf->SetFont('Arial', '', 10);
        $pdf->Cell(30, 8, mb_convert_encoding($est['Matricula'], 'ISO-8859-1', 'UTF-8'), 0, 1);
        $pdf->SetFont('Arial', 'B', 10);
        $pdf->Cell(30, 8, 'Grupo:', 0, 0);
        $pdf->SetFont('Arial', '', 10);
        $pdf->Cell(80, 8, mb_convert_encoding($est['Grupo'], 'ISO-8859-1', 'UTF-8'), 0, 0);
        $pdf->Ln(10);

        $pdf->SetFont('Arial', 'B', 11);
        $pdf->SetFillColor(74, 30, 111);
        $pdf->SetTextColor(255);
        $pdf->Cell(30, 10, 'Fecha', 1, 0, 'C', true);
        $pdf->Cell(100, 10, 'Competencia', 1, 0, 'L', true);
        $pdf->Cell(35, 10, mb_convert_encoding('Calificación', 'ISO-8859-1', 'UTF-8'), 1, 0, 'C', true);
        $pdf->Cell(25, 10, 'Tipo', 1, 1, 'C', true);

        $pdf->SetFont('Arial', '', 9);
        $pdf->SetTextColor(0);
        $pdf->SetFillColor(255);

        foreach ($evaluaciones as $ev) {
            $pdf->Cell(30, 8, date('d/m/Y', strtotime($ev['Fecha'])), 1, 0, 'C');
            $pdf->Cell(100, 8, mb_convert_encoding(substr($ev['NombreCompetencia'], 0, 55), 'ISO-8859-1', 'UTF-8'), 1, 0, 'L');
            $pdf->Cell(35, 8, mb_convert_encoding($ev['Calificacion'], 'ISO-8859-1', 'UTF-8'), 1, 0, 'C');
            $pdf->Cell(25, 8, mb_convert_encoding($ev['TipoEvaluacion'], 'ISO-8859-1', 'UTF-8'), 1, 1, 'C');
        }
        $pdf->Output('D', 'Reporte_' . $est['Matricula'] . '.pdf');
    }

    // ESTA ES LA FUNCIÓN QUE AGREGA LA GRÁFICA AL REPORTE GRUPAL
    public function exportarGrupalPDF() {
        $datosGrupos = $this->model->obtenerEstadisticasTodosGrupos();

        $pdf = new FPDF();
        $pdf->AddPage();
        $pdf->SetFont('Arial', 'B', 16);
        $pdf->SetTextColor(247, 148, 29);
        $pdf->Cell(0, 10, mb_convert_encoding('Reporte Comparativo Grupal', 'ISO-8859-1', 'UTF-8'), 0, 1, 'C');
        $pdf->Ln(5);

        // --- DIBUJAR GRÁFICA ---
        $this->dibujarGraficaBarras($pdf, $datosGrupos);
        
        // Mover cursor abajo de la gráfica para la tabla
        $pdf->SetXY(10, 110);

        // --- TABLA DE DATOS ---
        $pdf->SetFont('Arial', 'B', 12);
        $pdf->SetTextColor(0);
        $pdf->Cell(0, 10, 'Detalle Numérico', 0, 1);

        $pdf->SetFillColor(247, 148, 29);
        $pdf->SetTextColor(255);
        $pdf->SetFont('Arial', 'B', 10);
        
        $w = [30, 35, 35, 30, 30, 30];
        $pdf->Cell($w[0], 10, 'Grupo', 1, 0, 'C', true);
        $pdf->Cell($w[1], 10, 'Alumnos', 1, 0, 'C', true);
        $pdf->Cell($w[2], 10, 'Evaluaciones', 1, 0, 'C', true);
        $pdf->Cell($w[3], 10, 'Excelentes', 1, 0, 'C', true);
        $pdf->Cell($w[4], 10, 'Buenas', 1, 0, 'C', true);
        $pdf->Cell($w[5], 10, 'Bajas', 1, 1, 'C', true);

        $pdf->SetTextColor(0);
        $pdf->SetFont('Arial', '', 10);

        foreach ($datosGrupos as $grupo) {
            $pdf->Cell($w[0], 8, mb_convert_encoding($grupo['Grupo'], 'ISO-8859-1', 'UTF-8'), 1, 0, 'C');
            $pdf->Cell($w[1], 8, $grupo['total_estudiantes'], 1, 0, 'C');
            $pdf->Cell($w[2], 8, $grupo['total_evaluaciones'], 1, 0, 'C');
            $pdf->Cell($w[3], 8, $grupo['excelentes'], 1, 0, 'C');
            $pdf->Cell($w[4], 8, $grupo['buenas'], 1, 0, 'C');
            $pdf->Cell($w[5], 8, $grupo['deficientes'], 1, 1, 'C');
        }

        $pdf->Output('D', 'Reporte_Grupal.pdf');
    }

    public function exportarHistoricoPDF() {
        $stats = $this->model->obtenerEstadisticasGenerales();
        $competencias = $this->model->obtenerCompetenciasMasEvaluadas(10);

        $pdf = new FPDF();
        $pdf->AddPage();
        $pdf->SetFont('Arial', 'B', 16);
        $pdf->SetTextColor(74, 30, 111);
        $pdf->Cell(0, 10, mb_convert_encoding('Reporte Histórico General', 'ISO-8859-1', 'UTF-8'), 0, 1, 'C');
        $pdf->Ln(10);

        $pdf->SetFont('Arial', 'B', 12);
        $pdf->SetTextColor(0);
        $pdf->Cell(0, 10, 'Resumen General:', 0, 1);
        $pdf->SetFont('Arial', '', 11);
        $pdf->Cell(95, 8, 'Total Evaluaciones: ' . $stats['total_evaluaciones'], 1, 0);
        $pdf->Cell(95, 8, 'Total Estudiantes: ' . $stats['total_estudiantes'], 1, 1);
        $pdf->Cell(95, 8, 'Total Competencias: ' . $stats['total_competencias'], 1, 0);
        $pdf->Cell(95, 8, 'Total Docentes: ' . $stats['total_docentes'], 1, 1);
        $pdf->Ln(10);

        $pdf->SetFont('Arial', 'B', 12);
        $pdf->Cell(0, 10, mb_convert_encoding('Top Competencias Más Evaluadas:', 'ISO-8859-1', 'UTF-8'), 0, 1);
        
        $pdf->SetFillColor(74, 30, 111);
        $pdf->SetTextColor(255);
        $pdf->SetFont('Arial', 'B', 10);
        $pdf->Cell(130, 10, 'Competencia', 1, 0, 'L', true);
        $pdf->Cell(30, 10, 'Total', 1, 0, 'C', true);
        $pdf->Cell(30, 10, 'Promedio', 1, 1, 'C', true);

        $pdf->SetTextColor(0);
        $pdf->SetFont('Arial', '', 10);
        foreach ($competencias as $comp) {
            $pdf->Cell(130, 8, mb_convert_encoding(substr($comp['NombreCompetencia'], 0, 65), 'ISO-8859-1', 'UTF-8'), 1, 0, 'L');
            $pdf->Cell(30, 8, $comp['total_evaluaciones'], 1, 0, 'C');
            $pdf->Cell(30, 8, $comp['promedio_porcentual'] . '%', 1, 1, 'C');
        }

        $pdf->Output('D', 'Reporte_Historico.pdf');
    }

    // FUNCIÓN PRIVADA PARA DIBUJAR LA GRÁFICA EN FPDF
    private function dibujarGraficaBarras($pdf, $datos) {
        $pdf->SetFont('Arial', 'B', 10);
        $pdf->SetTextColor(0);
        $pdf->Text(10, 40, mb_convert_encoding('Total de Evaluaciones por Grupo:', 'ISO-8859-1', 'UTF-8'));

        $x = 20; 
        $y = 90; 
        $anchoBarra = 15;
        $espacio = 10;
        $maxAltura = 40; 

        // Calcular máximo para escalar
        $maxVal = 0;
        foreach ($datos as $d) {
            if ($d['total_evaluaciones'] > $maxVal) $maxVal = $d['total_evaluaciones'];
        }
        if ($maxVal == 0) $maxVal = 1;

        // Ejes
        $pdf->Line($x, $y, $x + (count($datos) * ($anchoBarra + $espacio)) + 5, $y); 
        $pdf->Line($x, $y, $x, $y - $maxAltura - 5);

        // Barras
        $i = 0;
        foreach ($datos as $d) {
            $valor = $d['total_evaluaciones'];
            $altura = ($valor / $maxVal) * $maxAltura;
            $posX = $x + 5 + ($i * ($anchoBarra + $espacio));
            $posY = $y - $altura;

            // Color Morado
            $pdf->SetFillColor(74, 30, 111);
            $pdf->Rect($posX, $posY, $anchoBarra, $altura, 'F');

            // Etiqueta valor
            $pdf->SetXY($posX, $posY - 5);
            $pdf->Cell($anchoBarra, 5, $valor, 0, 0, 'C');

            // Etiqueta grupo
            $pdf->SetXY($posX, $y + 2);
            $pdf->Cell($anchoBarra, 5, mb_convert_encoding($d['Grupo'], 'ISO-8859-1', 'UTF-8'), 0, 0, 'C');
            
            $i++;
        }
    }
}
?>