<?php


$diastrabajados=$_GET["diastrabajados"];
$totaldeveng=$_GET["totaldeveng"];
$validado=$_GET["confirmado"];
$firma=$_GET["firma"];
$sueldobasico=$_GET["sueldobasico"];
$transporte=$_GET["transporte"];


$fechaini = strtotime($fechaini);
$fechafin = strtotime($fechafin);

$fechainidia=date("d",$fechaini);
$fechainimes=date("m",$fechaini);
$fechainiaño=date("Y",$fechaini);      


$fechafindia=date("d",$fechafin);
$fechafinmes=date("m",$fechafin);
$fechafinaño=date("Y",$fechafin);   

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

$semestre=$_GET["semestre"];
if ($semestre="Primera") {
    $semestre=1;
}else {
    $semestre=2;
}
          // Definir borde negro alrededor del encabezado
          $this->SetLineWidth(0.5); // Establece el ancho de línea
          $this->SetDrawColor(0, 0, 0); // Establece el color del borde (negro)
          $this->Rect(10, 10, 190, 60, 'D'); // Dibuja un rectángulo con borde

    $this->SetFont('Times','B',15);
   
    $this->Cell(82);
   
    $this->Ln(20);

    $this->SetFont('Arial', 'B', 25); // Establece la fuente, el estilo (negrita) y el tamaño (12 puntos)
    $this->SetY($this->GetY() -15); // Mueve hacia abajo
        $this->Cell(150, 10, 'LIQUIDACION DE PRIMA', 0, 1, 'C'); // Agrega un título centrado


        $imageWidth = 35; // Ancho de la imagen
        $textWidth = $this->GetStringWidth('LIQUIDACION DE PRIMA'); // Ancho del texto
        $availableWidth = $this->GetPageWidth() - $textWidth - $imageWidth; // Ancho disponible para la imagen
        $posX = $this->GetPageWidth() - $availableWidth; // Posición x para la imagen
        // Calcula la posición y para la imagen (subir un poco la imagen)
        $posY = $this->GetY() - 13; // Ajusta el valor según sea necesario

        // Agrega la imagen al lado derecho del encabezado
        $this->Image('images/logoDesprendible.jpg', $posX, $posY, $imageWidth); // Cambia 'ruta/a/tu/imagen.png' a la ruta de tu imagen y ajusta el tamaño si es necesario

        
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
        $this->Cell(100, 7, 'Semestre     '.$semestre.' ', 1);
        $this->SetFont('Arial', 'B', 10); // Establecer fuente en negrita
        $this->Cell(35, 7, '', 1);
        $this->Ln(); // Salto de línea

        $this->SetX(+20);
        $this->SetFont('Arial', 'B', 10); // Establecer fuente en negrita
        $this->Cell(100, 7, 'Fecha inicial:    '.$fechaini.'  Fecha corte:     '.$fechafin.' ', 1);
        $this->SetFont('Arial', 'B', 10); // Establecer fuente en negrita
        $this->Cell(35, 7, '', 1);
        $this->Ln(); // Salto de línea


        $this->SetX(+20);
        $this->SetFont('Arial', 'B', 10); // Establecer fuente en negrita
        $this->Cell(100, 7, 'No DAVIVIENDA    ', 1);
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
$pdf->Cell(190, 2, 'CALCULO PRIMA                                                                      BASE PARA El CALCULO', 0, 1, 'C');
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
$pdf->Cell(55, 5, 'Sueldo basico', 1);
$pdf->SetFont('Arial', '', 10); // Restaurar fuente normal
$pdf->Cell(40, 5, ''.$sueldobasico.'', 1);
$pdf->Ln(); // Salto de línea

// Lista 3
// $sueldo_formateado = number_format($sueldo, 0, ',', '.');
// $salud_formateado = number_format($salud, 0, ',', '.');
$pdf->SetFont('Arial', '', 10); // Restaurar fuente normal
$pdf->Cell(60, 5, '', 1);
$pdf->SetFont('Arial', '', 10); // Restaurar fuente normal
$pdf->Cell(35, 5, '', 1);
$pdf->SetFont('Arial', '', 10); // Restaurar fuente normal
$pdf->Cell(55, 5, 'Subsidio transporte', 1);
$pdf->SetFont('Arial', '', 10); // Restaurar fuente normal
$pdf->Cell(40, 5, ''.$transporte.'', 1);
$pdf->Ln(); // Salto de línea



// Lista 11
$totaldeveng_formateado = number_format($totaldeveng, 0, ',', '.');
$totaldeduccion_formateado = number_format($totaldeduccion, 0, ',', '.');
$pdf->SetFont('Arial', '', 10); // Restaurar fuente normal
$pdf->Cell(60, 10, 'TOTAL PRIMA', 1);
$pdf->SetFont('Arial', '', 10); // Restaurar fuente normal
$pdf->Cell(35, 10, ''.$totaldeveng_formateado.'', 1);
$pdf->SetFont('Arial', '', 10); // Restaurar fuente normal
$pdf->Cell(55, 10, '', 1);
$pdf->SetFont('Arial', '', 10); // Restaurar fuente normal
$pdf->Cell(40, 10, '', 1);
$pdf->Ln(); // Salto de línea







$valorTotal=$totaldeveng;
$valorTotal_formateado = number_format($valorTotal, 0, ',', '.');
$pdf->SetFont('Arial', '', 10); // Restaurar fuente normal
$pdf->Cell(190, 10, 'TOTAL                                                                                                                    VALOR A PAGAR:  '.$valorTotal_formateado.'', 1);


$locale = 'es_CO'; // Define el locale para el idioma y formato de moneda colombiano
$fmt = new NumberFormatter($locale, NumberFormatter::SPELLOUT); // Crea una instancia de NumberFormatter

$valorEnLetras = $fmt->formatCurrency($valorTotal, 'COP');


// Elimina la palabra "coma" y lo que le sigue
$valorEnLetras = preg_replace('/\bcoma\b.*$/i', '', $valorEnLetras);

$valorEnLetras_en_mayusculas = strtoupper($valorEnLetras);



$pdf->Ln(); // Salto de línea

$pdf->SetFont('Arial', '', 10); // Restaurar fuente normal
$pdf->Cell(190, 10, 'VALOR EN LETRAS:  '.$valorEnLetras_en_mayusculas.' PESOS', 1);
$pdf->Ln(); // Salto de línea
$pdf->SetTextColor(255, 0, 0); // Establece el color de texto a rojo
$pdf->SetFont('Arial', '', 10); // Restaurar fuente normal

// $pdf->MultiCell(190, 5, 'SE DESCONTO '.$prestamos_formateado.' POR:  ' .$descriprestamos.'', 1);








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




$pdf->Ln(10);

$formatofin=$formato;




$pdf->Ln(10);

$pdf->Output();
?>
