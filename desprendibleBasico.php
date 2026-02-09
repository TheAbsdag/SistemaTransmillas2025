<?php
// include class

// require("login_autentica.php");
// include("cabezote3.php"); 
// $nombre="nombre";
// $cedula="cedula";
// $valor="80000";
// $deudas="deudas";
// $fechaini="fechaini";
// $fechafin="fechafin";
// $asc="ASC";


$diastrabajados=$_GET["diastrabajados"];
$sueldo=$_GET["sueldo"];
$auxilitrans=$_GET["auxilitrans"];
$pagdiasinca=$_GET["pagdiasinca"];
$totaldeveng=$_GET["totaldeveng"];
$salud=$_GET["salud"];
$pension=$_GET["pension"];
$prestamos=$_GET["prestamos"];
$totaldeduccion=$_GET["totaldeduccion"];

$valor=$_GET["valor"];
$deudas=$_GET["deudas"];
$validado=$_GET["confirmado"];
$diasIncapacidad=$_GET["diasIncapacidad"];
$valordiasVacaciones=$_GET["vacaciones"];
$diasVacaciones=$_GET["diasvacaciones"];
$firma=$_GET["firma"];
$licencias=$_GET["licencias"];
$valorlicencias=$_GET["valorlicencias"];

$descriprestamos=$_GET["descriprestamos"];
$valorAjusteB=$_GET["valorAjuste"];
$tipoAjusteB=$_GET["tipoAjuste"];
$descripcionAjusteB=$_GET["descripcionAjuste"];
$otros=$_GET["otros"];

$ajustessumB=0;
$ajustesresB=0;
if ($tipoAjusteB=="suma") {
    $ajustessumB=$valorAjusteB;
    $ajustesresB=0;
}else if($tipoAjusteB=="descuento"){
    $ajustessumB=0;
    $ajustesresB=$valorAjusteB;
}

// desprendibleBasico.php?cedula=".$rw1[5]."&nombre=".$rw1[1].$rw1[2]."&cargo=$rw1[3]&fechaini=$fechaactual&fechafin=$fechafinal&cuenta=&diastrabajados=$diassitrabajo&sueldo=$valordediastrabajados&auxilitrans=$totalauxilio&pagdiasinca=$valorDiasIncapadidad&totaldeveng=$totaldevengado&salud=$valorSalud&pension=$valorPension&prestamos=$restaABasico&totaldeduccion=$totaldeduccion"

$fechaini = strtotime($fechaini);
$fechafin = strtotime($fechafin);

$fechainidia=date("d",$fechaini);
$fechainimes=date("m",$fechaini);
$fechainiaño=date("Y",$fechaini);      


$fechafindia=date("d",$fechafin);
$fechafinmes=date("m",$fechafin);
$fechafinaño=date("Y",$fechafin);   
//             $fechadelregidia = date("d",$fechadelregi);

// // error_reporting(0);
require('fpdf/fpdf.php');




class PDF extends FPDF
{
// Cabecera de página


function addBackground($file, $x = 0, $y = 0, $w = null, $h = null) {
    $this->Image($file, $x, $y, $w, $h);
}
function Header()
{

$sede=$_GET["sede"];
$cedula=$_GET["cedula"];
$nombre=$_GET["nombre"];
$cargo=$_GET["cargo"];
$fechaini=$_GET["fechaini"];
$fechafin=$_GET["fechafin"];
          // Definir borde negro alrededor del encabezado
          $this->SetLineWidth(0.5); // Establece el ancho de línea
          $this->SetDrawColor(0, 0, 0); // Establece el color del borde (negro)
          $this->Rect(10, 10, 190, 60, 'D'); // Dibuja un rectángulo con borde

    $this->SetFont('Times','B',15);
   
    $this->Cell(80);
   
    $this->Ln(20);

    $this->SetFont('Arial', 'B', 25); // Establece la fuente, el estilo (negrita) y el tamaño (12 puntos)
    $this->SetY($this->GetY() -15); // Mueve hacia abajo
        $this->Cell(150, 10, 'DESPRENDIBLE DE NOMINA', 0, 1, 'C'); // Agrega un título centrado

        // $posX = $this->GetPageWidth() - 50; // Ajusta el valor según sea necesario
        
        // // Agrega la imagen al lado derecho del encabezado
        // $this->Image('images/logoDesprendible.jpg', $posX, $this->GetY(), 50); // Cambia 'ruta/a/tu/imagen.png' a la ruta de tu imagen y ajusta el tamaño si es necesario


        $imageWidth = 35; // Ancho de la imagen
        $textWidth = $this->GetStringWidth('DESPRENDIBLE DE NOMINA'); // Ancho del texto
        $availableWidth = $this->GetPageWidth() - $textWidth - $imageWidth; // Ancho disponible para la imagen
        $posX = $this->GetPageWidth() - $availableWidth; // Posición x para la imagen
        // Calcula la posición y para la imagen (subir un poco la imagen)
        $posY = $this->GetY() - 13; // Ajusta el valor según sea necesario

        // Agrega la imagen al lado derecho del encabezado
        $this->Image('images/logoDesprendible.jpg', $posX, $posY, $imageWidth); // Cambia 'ruta/a/tu/imagen.png' a la ruta de tu imagen y ajusta el tamaño si es necesario
        // Agrega la imagen al lado derecho del encabezado
        // $this->Image('images/logoDesprendible.jpg', $posX, $this->GetY(), $imageWidth); // Cambia 'ruta/a/tu/imagen.png' a la ruta de tu imagen y ajusta el tamaño si es necesario
        
        // $this->SetFont('Arial', '', 12);

        // $this->SetY($this->GetY() +1); // Mueve hacia abajo
        // $this->Cell(143, 10,'CEDULA '.$cedula.'                                        No.BOG 50', 0, 1, 'C');
        // $this->SetY($this->GetY() -2); // Mueve hacia abajo
        // $this->Cell(90, 10, 'NOMBRE '.$nombre.'                                        ', 0, 1, 'C');
        // $this->SetY($this->GetY() -2); // Mueve hacia abajo
        // $this->Cell(84, 10, 'Cargo '.$cargo.'                                          ', 0, 1, 'C');
        // $this->SetY($this->GetY() -2); // Mueve hacia abajo
        // $this->Cell(98, 10, 'Periodo del:'.$fechaini.'  Al:  '.$fechafin.'             ', 0, 1, 'C');
        // $this->SetY($this->GetY() -2); // Mueve hacia abajo
        // $this->Cell(173, 10, 'No DAVIVIENDA '.$cedula.'                                                               BOGOTA', 0, 1, 'C');

        
        $this->SetY(+30);
        $this->SetX(+20);
        $this->SetDrawColor(255, 255, 255); // Establecer color de borde en blanco
        $this->SetFont('Arial', 'B', 10); // Establecer fuente en negrita
        $this->Cell(100, 7, 'CEDULA:  '.$cedula.'', 1);
        $this->SetFont('Arial', 'B', 10); // Establecer fuente en negrita
        $this->Cell(35, 7, '', 1);
        $this->Ln(); // Salto de línea

        $this->SetX(+20);
        $this->SetFont('Arial', 'B', 10); // Establecer fuente en negrita
        $this->Cell(100, 7, 'NOMBRE:     '.$nombre.'', 1);
        $this->SetFont('Arial', 'B', 10); // Establecer fuente en negrita
        $this->Cell(35, 7, '', 1);
        $this->Ln(); // Salto de línea
        
        $this->SetX(+20);
        $this->SetFont('Arial', 'B', 10); // Establecer fuente en negrita
        $this->Cell(100, 7, 'Cargo:       '.$cargo.'', 1);
        $this->SetFont('Arial', 'B', 10); // Establecer fuente en negrita
        $this->Cell(35, 7, '', 1);
        $this->Ln(); // Salto de línea
        
        $this->SetX(+20);
        $this->SetFont('Arial', 'B', 10); // Establecer fuente en negrita
        $this->Cell(100, 7, 'Periodo     del:  '.$fechaini.'  Al: '.$fechafin.'', 1);
        $this->SetFont('Arial', 'B', 10); // Establecer fuente en negrita
        $this->Cell(35, 7, '', 1);
        $this->Ln(); // Salto de línea

        $this->SetX(+20);
        $this->SetFont('Arial', 'B', 10); // Establecer fuente en negrita
        $this->Cell(100, 7, ' ', 1);
        $this->SetFont('Arial', 'B', 10); // Establecer fuente en negrita
        $this->Cell(35, 7, ''.$sede.'', 1);
        $this->Ln(); // Salto de línea
}






// Pie de página
function Footer()
{
    // Posición: a 1,5 cm del final
    $this->SetY(-1);
    // Times italic 8
    $this->SetFont('Times','I',8);
    // $this->Image('assets/piedepaginaberm.jpg',0,260,0);
    // Número de página
    // $this->Cell(0,10,'Page '.$this->PageNo().'/{nb}',0,0,'C');
}
}




// Creación del objeto de la clase heredada
$pdf = new PDF();
$pdf->AliasNbPages();
$pdf->AddPage();
$pdf->SetFont('Times','',12);
// for($i=1;$i<=40;$i++)
//     $pdf->Cell(0,10,'Imprimiendo línea número '.$i,0,1);
$pdf->Ln(20);
$pdf->SetY($pdf->GetY() -10); // Mueve hacia abajo
$pdf->SetDrawColor(0, 0, 0); // Establecer color de borde en blanco
// Dibuja el primer rectángulo
$pdf->SetLineWidth(0.5); // Establece el ancho de línea
$pdf->SetDrawColor(0, 0, 0); // Establece el color del borde (negro en este caso)
$pdf->Rect(10, 72, 95, 15); // Dibuja un rectángulo con relleno


// Dibuja el segundo rectángulo
$pdf->SetLineWidth(0.5); // Establece el ancho de línea
$pdf->SetDrawColor(0, 0, 0); // Establece el color del borde (negro en este caso)
$pdf->Rect(105, 72, 95,15); // Dibuja un rectángulo sin relleno


        //   // Definir borde negro alrededor del encabezado
        //   $pdf->SetLineWidth(0.2); // Establece el ancho de línea
        //   $pdf->SetDrawColor(0, 0, 0); // Establece el color del borde (negro)
        //   $pdf->Rect(12, 90, 185, 100, 'D'); // Dibuja un rectángulo con borde


            //         // Dibuja el primer rectángulo
            // $pdf->SetLineWidth(0.2); // Establece el ancho de línea
            // $pdf->SetDrawColor(0, 0, 0); // Establece el color del borde (negro en este caso)
            // $pdf->Rect(12, 90, 92, 10); // Dibuja un rectángulo con relleno


            // // Dibuja el segundo rectángulo
            // $pdf->SetLineWidth(0.2); // Establece el ancho de línea
            // $pdf->SetDrawColor(0, 0, 0); // Establece el color del borde (negro en este caso)
            // $pdf->Rect(105, 90, 92,10); // Dibuja un rectángulo sin relleno




$pdf->Cell(190, 10, 'DEVENGADOS                                                                      DEDUCCIONES', 0, 1, 'C');



$pdf->SetY($pdf->GetY() +5); // Mueve hacia abajo




// Lista 1

$pdf->SetFont('Arial', 'B', 10); // Establecer fuente en negrita
$pdf->Cell(60, 5, 'Concepto ', 1);
$pdf->SetFont('Arial', 'B', 10); // Establecer fuente en negrita
$pdf->Cell(35, 5, 'Valor ', 1);
$pdf->SetFont('Arial', 'B', 10); // Establecer fuente en negrita
$pdf->Cell(55, 5, 'Concepto ', 1);
$pdf->SetFont('Arial', 'B', 10); // Establecer fuente en negrita
$pdf->Cell(40, 5, 'Valor ', 1);
$pdf->Ln(); // Salto de línea
// Lista 2
$pdf->SetFont('Arial', '', 10); // Restaurar fuente normal
$pdf->Cell(60, 5, 'Dias Trabajados', 1);
$pdf->SetFont('Arial', '', 10); // Restaurar fuente normal
$pdf->Cell(35, 5, ''.$diastrabajados.'', 1);
$pdf->SetFont('Arial', '', 10); // Restaurar fuente normal
$pdf->Cell(55, 5, '', 1);
$pdf->SetFont('Arial', '', 10); // Restaurar fuente normal
$pdf->Cell(40, 5, '', 1);
$pdf->Ln(); // Salto de línea

// Lista 3
$sueldo_formateado = number_format($sueldo, 0, ',', '.');
$salud_formateado = number_format($salud, 0, ',', '.');
$pdf->SetFont('Arial', '', 10); // Restaurar fuente normal
$pdf->Cell(60, 5, 'SUELDO', 1);
$pdf->SetFont('Arial', '', 10); // Restaurar fuente normal
$pdf->Cell(35, 5, ''.$sueldo_formateado.'', 1);
$pdf->SetFont('Arial', '', 10); // Restaurar fuente normal
$pdf->Cell(55, 5, 'SALUD', 1);
$pdf->SetFont('Arial', '', 10); // Restaurar fuente normal
$pdf->Cell(40, 5, ''.$salud_formateado.'', 1);
$pdf->Ln(); // Salto de línea

// Lista 4
$auxilitrans_formateado = number_format($auxilitrans, 0, ',', '.');
$pension_formateado = number_format($pension, 0, ',', '.');
$pdf->SetFont('Arial', '', 10); // Restaurar fuente normal
$pdf->Cell(60, 5, 'AUXILIO DE TRANSPORTE', 1);
$pdf->SetFont('Arial', '', 10); // Restaurar fuente normal
$pdf->Cell(35, 5, ''.$auxilitrans_formateado.'', 1);
$pdf->SetFont('Arial', '', 10); // Restaurar fuente normal
$pdf->Cell(55, 5, 'PENSION', 1);
$pdf->SetFont('Arial', '', 10); // Restaurar fuente normal
$pdf->Cell(40, 5, ''.$pension_formateado.'', 1);
$pdf->Ln(); // Salto de línea

// Lista 5

$pdf->SetFont('Arial', '', 10); // Restaurar fuente normal
$pdf->Cell(60, 5, 'HORAS EXTRA', 1);
$pdf->SetFont('Arial', '', 10); // Restaurar fuente normal
$pdf->Cell(35, 5, '', 1);
$pdf->SetFont('Arial', '', 10); // Restaurar fuente normal
$pdf->Cell(55, 5, 'F.S.P', 1);
$pdf->SetFont('Arial', '', 10); // Restaurar fuente normal
$pdf->Cell(40, 5, '', 1);
$pdf->Ln(); // Salto de línea
// Lista 6

$pdf->SetFont('Arial', '', 10); // Restaurar fuente normal
$pdf->Cell(60, 5, 'DIAS DE INCAPACIDAD', 1);
$pdf->SetFont('Arial', '', 10); // Restaurar fuente normal
$pdf->Cell(35, 5, ''.$diasIncapacidad.'', 1);
$pdf->SetFont('Arial', '', 10); // Restaurar fuente normal
$pdf->Cell(55, 5, 'DESCUENTOS', 1);
$pdf->SetFont('Arial', '', 10); // Restaurar fuente normal
$pdf->Cell(40, 5, '', 1);
$pdf->Ln(); // Salto de línea
// Lista 7
$pagdiasinca_formateado = number_format($pagdiasinca, 0, ',', '.');
// $pension_formateado = number_format($pension, 0, ',', '.');
$pdf->SetFont('Arial', '', 10); // Restaurar fuente normal
$pdf->Cell(60, 5, 'PAGO DE DIAS DE INCAPACIDAD', 1);
$pdf->SetFont('Arial', '', 10); // Restaurar fuente normal
$pdf->Cell(35, 5, ''.$pagdiasinca_formateado.'', 1);
$pdf->SetFont('Arial', '', 10); // Restaurar fuente normal
$pdf->Cell(55, 5, 'PRESTAMOS', 1);
$pdf->SetFont('Arial', '', 10); // Restaurar fuente normal
$pdf->Cell(40, 5, ''.$prestamos.'', 1);
$pdf->Ln(); // Salto de línea

// $prestamos_formateado = number_format($licencia, 0, ',', '.');
// $pension_formateado = number_format($pension, 0, ',', '.');
$pdf->SetFont('Arial', '', 10); // Restaurar fuente normal
$pdf->Cell(60, 5, 'DIAS LIC. PERMISOS', 1);
$pdf->SetFont('Arial', '', 10); // Restaurar fuente normal
$pdf->Cell(35, 5, ''.$licencias.'', 1);
$pdf->SetFont('Arial', '', 10); // Restaurar fuente normal
$pdf->Cell(55, 5, '', 1);
$pdf->SetFont('Arial', '', 10); // Restaurar fuente normal
$pdf->Cell(40, 5, '', 1);
$pdf->Ln(); // Salto de línea

$valorlicencias_formateado = number_format($valorlicencias, 0, ',', '.');
// $pension_formateado = number_format($pension, 0, ',', '.');
$pdf->SetFont('Arial', '', 10); // Restaurar fuente normal
$pdf->Cell(60, 5, 'PAGO LIC. PERMISOS', 1);
$pdf->SetFont('Arial', '', 10); // Restaurar fuente normal
$pdf->Cell(35, 5, ''.$valorlicencias_formateado.'', 1);
$pdf->SetFont('Arial', '', 10); // Restaurar fuente normal
$pdf->Cell(55, 5, '', 1);
$pdf->SetFont('Arial', '', 10); // Restaurar fuente normal
$pdf->Cell(40, 5, '', 1);
$pdf->Ln(); // Salto de línea
// Lista 8
$prestamos_formateado = number_format($prestamos, 0, ',', '.');
// $pension_formateado = number_format($pension, 0, ',', '.');
$pdf->SetFont('Arial', '', 10); // Restaurar fuente normal
$pdf->Cell(60, 5, 'OTROS NO CONSTITUYE SALARIO', 1);
$pdf->SetFont('Arial', '', 10); // Restaurar fuente normal
$pdf->Cell(35, 5, '', 1);
$pdf->SetFont('Arial', '', 10); // Restaurar fuente normal
$pdf->Cell(55, 5, '', 1);
$pdf->SetFont('Arial', '', 10); // Restaurar fuente normal
$pdf->Cell(40, 5, '', 1);
$pdf->Ln(); // Salto de línea

// Lista 9

// $pension_formateado = number_format($pension, 0, ',', '.');
$pdf->SetFont('Arial', '', 10); // Restaurar fuente normal
$pdf->Cell(60, 5, 'DIAS VACACIONES', 1);
$pdf->SetFont('Arial', '', 10); // Restaurar fuente normal
$pdf->Cell(35, 5, ''.$diasVacaciones.'', 1);
$pdf->SetFont('Arial', '', 10); // Restaurar fuente normal
$pdf->Cell(55, 5, '', 1);
$pdf->SetFont('Arial', '', 10); // Restaurar fuente normal
$pdf->Cell(40, 5, '', 1);
$pdf->Ln(); // Salto de línea

// Lista 9
$valordiasVacaciones_formateado = number_format($valordiasVacaciones, 0, ',', '.');
// $pension_formateado = number_format($pension, 0, ',', '.');
$pdf->SetFont('Arial', '', 10); // Restaurar fuente normal
$pdf->Cell(60, 5, 'PAGO DIAS VACACIONES', 1);
$pdf->SetFont('Arial', '', 10); // Restaurar fuente normal
$pdf->Cell(35, 5, ''.$valordiasVacaciones_formateado.'', 1);
$pdf->SetFont('Arial', '', 10); // Restaurar fuente normal
$pdf->Cell(55, 5, '', 1);
$pdf->SetFont('Arial', '', 10); // Restaurar fuente normal
$pdf->Cell(40, 5, '', 1);
$pdf->Ln(); // Salto de línea

// Lista 10
$totaldeveng_formateado = number_format($totaldeveng, 0, ',', '.');
$totaldeduccion_formateado = number_format($totaldeduccion, 0, ',', '.');
$pdf->SetFont('Arial', '', 10); // Restaurar fuente normal
$pdf->Cell(60, 5, 'AJUSTES', 1);
$pdf->SetFont('Arial', '', 10); // Restaurar fuente normal
$pdf->Cell(35, 5, ''.$ajustessumB.'', 1);
$pdf->SetFont('Arial', '', 10); // Restaurar fuente normal
$pdf->Cell(55, 5, 'AJUSTES', 1);
$pdf->SetFont('Arial', '', 10); // Restaurar fuente normal
$pdf->Cell(40, 5, ''.$ajustesresB.'', 1);
$pdf->Ln(); // Salto de línea

$cedula=$_GET["cedula"];
if($cedula=="1073711329")
{// Lista 10
$totalotros = number_format($otros, 0, ',', '.');

$pdf->SetFont('Arial', '', 10); // Restaurar fuente normal
$pdf->Cell(60, 5, 'OTROS DEVENGOS', 1);
$pdf->SetFont('Arial', '', 10); // Restaurar fuente normal
$pdf->Cell(35, 5, ''.$totalotros.'', 1);
$pdf->SetFont('Arial', '', 10); // Restaurar fuente normal
$pdf->Cell(55, 5, '', 1);
$pdf->SetFont('Arial', '', 10); // Restaurar fuente normal
$pdf->Cell(40, 5, '', 1);
$pdf->Ln(); // Salto de línea

$totaldeveng_formateado = number_format($totaldeveng+$valorlicencias+$otros, 0, ',', '.');
$valorTotal=($totaldeveng+$ajustessumB+$valorlicencias+$otros)-($totaldeduccion+$ajustesresB);

}else{
$totaldeveng_formateado = number_format($totaldeveng+$valorlicencias, 0, ',', '.');
$valorTotal=($totaldeveng+$ajustessumB+$valorlicencias)-($totaldeduccion+$ajustesresB);


}



// if (isset($_GET["otros"])) {
//     $otrsodv=$_GET["otros"];
//     $otrsodv_formateado = number_format($otrsodv, 0, ',', '.');
//     // Lista 11
//     $pdf->SetFont('Arial', '', 10); // Restaurar fuente normal
//     $pdf->Cell(60, 5, 'OTROS DEVENGOS', 1);
//     $pdf->SetFont('Arial', '', 10); // Restaurar fuente normal
//     $pdf->Cell(35, 5, ''.$otrsodv_formateado.'', 1);
//     $pdf->SetFont('Arial', '', 10); // Restaurar fuente normal
//     $pdf->Cell(55, 5, '', 1);
//     $pdf->SetFont('Arial', '', 10); // Restaurar fuente normal
//     $pdf->Cell(40, 5, '', 1);
//     $pdf->Ln(); // Salto de línea

//     $pagoSemanal=200000*2;
//     $pagoSemanal_formateado = number_format($pagoSemanal, 0, ',', '.');
//     // Lista 11
//     $pdf->SetFont('Arial', '', 10); // Restaurar fuente normal
//     $pdf->Cell(60, 5, 'PAGO FIJO SEMANAL', 1);
//     $pdf->SetFont('Arial', '', 10); // Restaurar fuente normal
//     $pdf->Cell(35, 5, ''.$pagoSemanal_formateado.'', 1);
//     $pdf->SetFont('Arial', '', 10); // Restaurar fuente normal
//     $pdf->Cell(55, 5, '', 1);
//     $pdf->SetFont('Arial', '', 10); // Restaurar fuente normal
//     $pdf->Cell(40, 5, '', 1);
//     $pdf->Ln(); // Salto de línea

//     $totaldeveng_formateado = number_format($totaldeveng+$otrsodv+$pagoSemanal, 0, ',', '.');
//     $valorTotal=($totaldeveng+$ajustessumB+$otrsodv+$pagoSemanal+$valorlicencias)-($totaldeduccion+$ajustesresB);

// }




// Lista 11

$totaldeduccion_formateado = number_format($totaldeduccion, 0, ',', '.');
$pdf->SetFont('Arial', '', 10); // Restaurar fuente normal
$pdf->Cell(60, 10, 'TOTAL DEVENGADO', 1);
$pdf->SetFont('Arial', '', 10); // Restaurar fuente normal
$pdf->Cell(35, 10, ''.$totaldeveng_formateado.'', 1);
$pdf->SetFont('Arial', '', 10); // Restaurar fuente normal
$pdf->Cell(55, 10, 'TOTAL DEDUCCIONES', 1);
$pdf->SetFont('Arial', '', 10); // Restaurar fuente normal
$pdf->Cell(40, 10, ''.$totaldeduccion_formateado.'', 1);
$pdf->Ln(); // Salto de línea








$valorTotal_formateado = number_format($valorTotal, 0, ',', '.');
$pdf->SetFont('Arial', '', 10); // Restaurar fuente normal
$pdf->Cell(190, 10, 'TOTAL                                                                                                                    VALOR A PAGAR:  '.$valorTotal_formateado.'', 1);
// $pdf->SetFont('Arial', '', 10); // Restaurar fuente normal
// $pdf->Cell(95, 10, ' VALOR A PAGAR ', 1);


$locale = 'es_CO';
$fmt = new NumberFormatter($locale, NumberFormatter::SPELLOUT);

// ⚠️ SPELLOUT usa format(), NO formatCurrency()
$valorEnLetras = $fmt->format($valorTotal);

// Quitar decimales
$valorEnLetras = preg_replace('/\bcoma\b.*$/i', '', $valorEnLetras);

// Agregar moneda manualmente
$valorEnLetras .= ' pesos';

// Mayúsculas
$valorEnLetras_en_mayusculas = mb_strtoupper($valorEnLetras, 'UTF-8');

// 🔥 CONVERTIR A ISO-8859-1 PARA FPDF
$valorEnLetras_en_mayusculas = iconv('UTF-8', 'ISO-8859-1//TRANSLIT', $valorEnLetras_en_mayusculas);



$pdf->Ln(); // Salto de línea

$pdf->SetFont('Arial', '', 10); // Restaurar fuente normal
$pdf->Cell(190, 10, 'VALOR EN LETRAS:  '.$valorEnLetras_en_mayusculas.' PESOS', 1);
$pdf->Ln(); // Salto de línea
$pdf->SetTextColor(255, 0, 0); // Establece el color de texto a rojo
$pdf->SetFont('Arial', '', 10); // Restaurar fuente normal

$pdf->MultiCell(190, 5, 'SE DESCONTO '.$prestamos_formateado.' POR:  ' .$descriprestamos.'', 1);








$pdf->Ln(); // Salto de línea
$pdf->SetTextColor(0, 0, 0);

$pdf->SetY($pdf->GetY() +5);
$pdf->SetDrawColor(255, 255, 255); // Establecer color de borde en blanco
$pdf->SetFont('Arial', '', 10); // Restaurar fuente normal
$pdf->Cell(95, 10, '', 1);
$pdf->SetDrawColor(0,0,0); // Establecer color de borde en blanco
$pdf->SetFont('Arial', '', 7); // Restaurar fuente normal
if ($validado!="") {
    $ruta_imagen="imgHojasDeVida/".$firma."";
    if (file_exists($ruta_imagen)) {
        // Mostrar la imagen si existe
        $pdf->Image('imgHojasDeVida/'.$firma.'', $pdf->GetX() +5, $pdf->GetY() + 20, 40, 14);

    } else {
        // Mostrar un mensaje si la imagen no existe
        $pdf->Cell(0, 5, 'si no se ve la firma revisar foto y volver a cargar ', 0, 1);
    }



    }
$pdf->MultiCell(95, 25, 'RECIBI A SATISFACCION Y ACEPTO EN TODAS SUS PARTES ESTE PAGO     '.$validado.'', 1);

$pdf->Ln(); // Salto de línea




//           // Definir borde negro alrededor del encabezado
//           $pdf->SetLineWidth(0.5); // Establece el ancho de línea
//           $pdf->SetDrawColor(0, 0, 0); // Establece el color del borde (negro)
//           $pdf->Rect(10, 10, 190, 180, 'D'); // Dibuja un rectángulo con borde



// // Dibuja el segundo rectángulo
// $pdf->Ln(20);
// $pdf->SetY($pdf->GetY() -50); // Mueve hacia abajo
// $pdf->SetLineWidth(0.5); // Establece el ancho de línea
// $pdf->SetDrawColor(0, 0, 0); // Establece el color del borde (negro en este caso)
// $pdf->Rect(105, 195, 95,35); // Dibuja un rectángulo sin relleno



// Elimina la palabra "coma" y lo que le sigue


$pdf->Ln(10);

$formatofin=$formato;

// if ($formatofin==1){
// 	    $pdf->MultiCell(0, 6, utf8_decode('Que '.$nombre.', identificada con la cédula de ciudadanía N.º '.$cedula.' de Bogotá, laboró en nuestra empresa desde el '.date("d",$fechadelregi).' de '.date("m",$fechadelregi).' del '.date("Y",$fechadelregi).' con un CONTRATO A TERMINO INDEFINIDO, desempeñando el cargo de '.$cargo.'.'), 0,'J');
// }else if($formatofin==2){






$pdf->Ln(10);
// $pdf->MultiCell(0, 6, 'Puede certificarla veracidad de este certificado en los correos: castiblanco@bermudas.com.co y/o recursosh@bermudas.com.co 
// ', 0,'C');

$pdf->Output();
?>
