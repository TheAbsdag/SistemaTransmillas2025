<?php
// // Mostrar todos los errores
// error_reporting(E_ALL);
// ini_set('display_errors', 1);
// ini_set('html_errors', 0);

require('../../../fpdf/fpdf.php');

class PDF extends FPDF
{
    function Header()
    {
        // Datos del empleado con valores por defecto
        $nombre = $_POST["nombre"] ?? '';
        $cedula = $_POST["cedula"] ?? '';
        $cargo = $_POST["cargo"] ?? '';
        $fecha_ingreso = $_POST["fecha_ingreso"] ?? '';
        $fecha_retiro = $_POST["fecha_retiro"] ?? '';
        $motivo = $_POST["motivo"] ?? '';
        $fecha = date("d M Y");
        

        // Marco general
        $this->SetLineWidth(0.5);
        $this->Rect(10, 10, 190, 270, 'D');

        // Logo
        if (file_exists('../../../images/logoDesprendible.jpg')) {
            $this->Image('../../../images/logoDesprendible.jpg', 165, 12, 30);
        }

        // Título
        $this->SetFont('Arial', 'B', 14);
        $this->Cell(0, 10, utf8_decode('LIQUIDACIÓN DE CONTRATO DE TRABAJO'), 0, 1, 'C');
        $this->Ln(2);
        $this->SetFont('Arial', '', 10);
        $this->Cell(0, 10, '', 0, 1, 'R');

        // Datos del empleado
        $this->SetFont('Arial', '', 10);
        $this->Ln(3);
        $this->Cell(100, 6, utf8_decode("Nombre del trabajador: $nombre"."     Cédula: $cedula."."                Fecha:"  . $fecha), 0, 1);
        $this->Cell(100, 6, utf8_decode("Cargo: $cargo"."   Fecha de ingreso: $fecha_ingreso". "      Fecha de retiro: $fecha_retiro"), 0, 1);

        $this->Cell(100, 6, utf8_decode("Motivo de terminación: $motivo"), 0, 1);
        $this->Ln(5);
    }

    function Footer()
    {
        $this->SetY(-25);
        $this->SetFont('Arial', '', 9);
        $this->Cell(0, 10, utf8_decode('TRANSMILLAS LOGÍSTICA Y TRANSPORTADORA S.A.S.'), 0, 1, 'C');
        $this->Cell(0, 10, utf8_decode('NIT. 901.089.478-8'), 0, 1, 'C');
    }
}

// Crear PDF
$pdf = new PDF();
$pdf->AddPage();
$pdf->SetFont('Arial', '', 10);

// Variables con valores por defecto
$dias_trabajados   = $_POST["dias_trabajados"] ?? 0;
$dias_cesantias    = $_POST["dias_cesantias"] ?? 0;
$dias_prima1        = $_POST["dias_prima1"] ?? 0;
$dias_prima2       = $_POST["dias_prima2"] ?? 0;
$dias_vacaciones   = $_POST["dias_vacaciones"] ?? 0;
$sueldobasico      = $_POST["sueldobasico"] ?? 0;
$transporte        = $_POST["transporte"] ?? 0;
$horas_extras_mes  = $_POST["horas_extras_mes"] ?? 0;
$horas_extras_anio = $_POST["horas_extras_anio"] ?? 0;
$cesantias         = $_POST["cesantias"] ?? 0;
$intereses         = $_POST["intereses"] ?? 0;
$prima             = $_POST["prima"] ?? 0;
$vacaciones        = $_POST["vacaciones"] ?? 0;
$total_devengado   = $_POST["total_devengado"] ?? 0;
$total_descuentos  = $_POST["total_descuentos"] ?? 0;
$total_pagar       = $_POST["valor_total"] ?? 0;
$valor_letras      = $_POST["valor_letras"] ?? '';
$firma             = $_POST["firma"] ?? '';
$noTrabajados= $_POST["noTrabajados"] ?? '';
$valorTotalDevengado = $_POST["valorTotalDevengado"] ?? '';
$valorVacacionesCompletas = $_POST["valorVacacionesCompletas"] ?? '';
$deudas = $_POST["valorDeudas"] ?? '';
$valorVacacionestomadas = $_POST["valorVacacionestomadas"] ?? '';
$dias_vacacionesTomadas = $_POST["dias_vacacionesTomadas"] ?? '';
$cant_vacaciones_tomadas = $_POST["cant_vacaciones_tomadas"] ?? '';
$dias_sanciones = $_POST["dias_sanciones"] ?? '';



// --- DETALLE SALARIAL ---
$pdf->SetFont('Arial', 'B', 10);
$pdf->Cell(0, 7, utf8_decode('DETALLE SALARIAL'), 0, 1, 'L');
$pdf->SetFont('Arial', '', 10);

// Encabezados
$pdf->Cell(100, 6, 'Concepto', 1, 0, 'C');
$pdf->Cell(90, 6, 'Valor', 1, 1, 'C');

// Filas
$pdf->Cell(100, 6, utf8_decode('Sueldo Básico'), 1);
$pdf->Cell(90, 6, '$' . number_format($sueldobasico, 0, ',', '.'), 1, 1);

$pdf->Cell(100, 6, utf8_decode('Subsidio de transporte'), 1);
$pdf->Cell(90, 6, '$' . number_format($transporte, 0, ',', '.'), 1, 1);

$pdf->Cell(100, 6, utf8_decode('Promedio horas Extras II Semestre'), 1);
$pdf->Cell(90, 6, '$' . number_format($horas_extras_mes, 0, ',', '.'), 1, 1);

$pdf->Cell(100, 6, utf8_decode('Promedio horas Extras Año'), 1);
$pdf->Cell(90, 6, '$' . number_format($horas_extras_anio, 0, ',', '.'), 1);

$pdf->Ln();

// --- TIEMPOS LABORADOS ---
$pdf->SetFont('Arial', 'B', 10);

$pdf->Cell(0, 7, utf8_decode('TIEMPOS LABORADOS'), 0, 1, 'L');
$pdf->SetFont('Arial', '', 10);
$pdf->Cell(60, 6, utf8_decode("Sanciones y lic no remuneradas:"), 1);
$pdf->Cell(40, 6, "$dias_sanciones dias", 1);
$pdf->Cell(60, 6, utf8_decode("No laborados:"), 1);
$pdf->Cell(30, 6, "$noTrabajados dias", 1);
$pdf->Ln();
$pdf->Cell(60, 6, utf8_decode("Tiempo trabajado:"), 1);
$pdf->Cell(40, 6, "$dias_trabajados dias", 1);
$pdf->Cell(60, 6, utf8_decode("Tiempo cesantías:"), 1);
$pdf->Cell(30, 6, "$dias_cesantias dias", 1);
$pdf->Ln();
$pdf->Cell(60, 6, utf8_decode("Tiempo prima:"), 1);
$pdf->Cell(20, 6, "$dias_prima1 dias", 1);
$pdf->Cell(20, 6, "$dias_prima2 dias", 1);

$pdf->Cell(60, 6, utf8_decode("Tiempo vacaciones:"), 1);
$pdf->Cell(30, 6, "$dias_vacaciones dias", 1);
$pdf->Ln();



// --- DEVENGADOS ---
$pdf->SetFont('Arial', 'B', 10); $pdf->Cell(0, 7, utf8_decode('DEVENGADOS'), 0, 1, 'L'); 
$pdf->SetFont('Arial', '', 10); $pdf->Cell(90, 6, 'Concepto', 1, 0, 'C'); 
$pdf->Cell(40, 6, 'Dias', 1, 0, 'C'); 
$pdf->Cell(60, 6, 'Valor', 1, 1, 'C'); 
$devengados = [
    ['Cesantías', $dias_cesantias, $cesantias], 
    ['Intereses Cesantías', $dias_cesantias, $intereses],
    ['Prima de Servicios', $dias_prima, $prima],
    ['Vacaciones', $dias_vacaciones, $valorVacacionesCompletas] 
]; 
       foreach ($devengados as $d) {
         [$concepto, $dias, $valor] = $d; 
         $pdf->Cell(90, 6, utf8_decode($concepto), 1); 
         $pdf->Cell(40, 6, $dias, 1); 
         $pdf->Cell(60, 6, $valor, 1, 1);
         }

$pdf->SetFont('Arial', 'B', 10);
$pdf->Cell(130, 7, 'TOTAL DEVENGADO', 1);
$pdf->Cell(60, 7, $valorTotalDevengado, 1);
$pdf->Ln();

// --- DESCUENTOS ---
$pdf->SetFont('Arial', 'B', 10);
$pdf->Cell(0, 7, 'DESCUENTOS', 0, 1, 'L');
$pdf->SetFont('Arial', '', 10); 
$pdf->Cell(90, 6, 'Concepto', 1, 0, 'C'); 
$pdf->Cell(40, 6, 'Dias', 1, 0, 'C'); 
$pdf->Cell(60, 6, 'Valor', 1, 1, 'C'); 
$pdf->SetFont('Arial', '', 10);
$pdf->Cell(90, 6, 'Base de descuentos', 1);
$pdf->Cell(40, 6, '', 1);
$pdf->Cell(60, 6, '', 1);

$pdf->Ln();
$pdf->Cell(90, 6, 'Anticipo vacaciones', 1);
$pdf->Cell(40, 6, ''.$cant_vacaciones_tomadas.'', 1);
$pdf->Cell(60, 6, $valorVacacionestomadas, 1);
$pdf->Ln();
$pdf->Cell(90, 6, 'Deudas', 1);
$pdf->Cell(40, 6, '', 1);
$pdf->Cell(60, 6, $deudas, 1);
$pdf->Ln();
// --- TOTAL A PAGAR ---
$pdf->SetFont('Arial', 'B', 10);
$pdf->Cell(130, 7, 'TOTAL A PAGAR AL TRABAJADOR', 1);
$pdf->Cell(60, 7, $total_pagar, 1, 1);
$pdf->Ln(6);

// ✅ Función para convertir número a letras (seguro)
function numeroEnLetras($num)
{
    // Asegurarnos de que sea un número puro
    $num = str_replace(['$', '.', ',', ' '], '', $num);

    if (!is_numeric($num)) {
        return '';
    }

    $formatter = new NumberFormatter("es", NumberFormatter::SPELLOUT);
    $letras = ucfirst($formatter->format($num));

    return $letras;
}

// 🔢 Limpiar el valor antes de pasarlo
$total_pagar_limpio = str_replace(['$', '.', ',', ' '], '', $total_pagar);

// 🔠 Convertir a letras
$total_en_letras = numeroEnLetras($total_pagar_limpio);

// // --- Mostrar total en letras ---
// $pdf->SetFont('Arial', '', 9);
// $pdf->MultiCell(190, 6, utf8_decode("Valor en letras: " . $total_en_letras . " pesos."), 0, 'L');
// $pdf->Ln(4);

// --- VALOR EN LETRAS ---
$pdf->SetFont('Arial', '', 10);
if (!empty($total_en_letras)) {
    $pdf->SetFont('Arial', 'B', 10);
    $pdf->MultiCell(0, 6, utf8_decode("SON: $total_en_letras pesos moneda corriente."), 1, 'L');
    $pdf->Ln(8);
}

// --- FIRMAS ---
$pdf->Ln(25);

// 🖋️ Verificar si llegó la firma por POST
$firmaArchivo = $_POST['firma'] ?? ''; // por ejemplo: 'firma_751_1730000000.png'

// Ruta donde se guardan las firmas
$rutaFirma = 'https://sistema.transmillas.com/nueva_plataforma/uploads/firmasLiquidaciones/' . $firmaArchivo;
            
// 🧩 Si existe la firma en el servidor, la mostramos
if (!empty($firmaArchivo)) {
    // Guardar posición actual
    $yActual = $pdf->GetY();

    // Mostrar firma sobre la línea
    $pdf->Image($rutaFirma, 35, $yActual - 15, 35); // mueve menos hacia arriba (ajusta el -15 según se vea)

    // Líneas de firma
    $pdf->SetY($yActual + 2); // baja un poco para alinear el texto
    $pdf->Cell(90, 6, '________________________', 0, 0, 'C');
    $pdf->Cell(90, 6, '________________________', 0, 1, 'C');
    $pdf->Cell(90, 6, utf8_decode($nombre), 0, 0, 'C');
    $pdf->Cell(90, 6, 'TRANSMILLAS LOGISTICA Y TRANSPORTADORA S.A.S.', 0, 1, 'C');
} else {
    // Si no hay firma, mostrar las líneas normales
    $pdf->Cell(90, 6, '', 0, 0, 'C');
    $pdf->Cell(90, 6, '________________________', 0, 1, 'C');
    $pdf->Cell(90, 6, utf8_decode($nombre), 0, 0, 'C');
    $pdf->Cell(90, 6, 'TRANSMILLAS LOGISTICA Y TRANSPORTADORA S.A.S.', 0, 1, 'C');
}

$pdf->Output();
?>
