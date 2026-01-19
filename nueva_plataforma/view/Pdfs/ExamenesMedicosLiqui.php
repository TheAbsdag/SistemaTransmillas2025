<?php
require('../../../fpdf/fpdf.php');

class PDF extends FPDF
{
    function Header()
    {

        // Marco exterior
        $this->SetLineWidth(0.5);
        $this->Rect(10, 10, 190, 270, 'D');

        // Logo
        if (file_exists('../../../images/logoDesprendible.jpg')) {
            $this->Image('../../../images/logoDesprendible.jpg', 12, 12, 40);
        }

        // Encabezado derecho
        $this->SetFont('Arial', '', 10);
        $this->SetXY(140, 12);
        $this->Cell(60, 6, utf8_decode('CÓDIGO: F-064'), 1, 2, 'C');
        $this->Cell(60, 6, utf8_decode('FECHA: 17-12-2024'), 1, 2, 'C');
        $this->Cell(60, 6, utf8_decode('VERSIÓN: 2'), 1, 2, 'C');

        // Título
        $this->SetXY(60, 22);
        $this->SetFont('Arial', 'B', 12);
        $this->Cell(80, 10, utf8_decode('EXAMEN MÉDICO DE RETIRO'), 1, 1, 'C');
        $this->Ln(10);
    }

    function cuerpoFormulario($data = [])
    {
        $confrima='';
        if ($_POST['confiExamen'] == 'NO') {
            $confrima='X';
        }
        $fecha = $data['fecha'] ?? '';
        $nombre = $data['nombre'] ?? '';
        $cargo = $data['cargo'] ?? '';
        $cedula = $data['cedula'] ?? '';

        // Campos iniciales
        $this->SetFont('Arial', '', 10);
        $this->Cell(95, 8, utf8_decode('FECHA DE DILIGENCIAMIENTO:'), 1);
        $this->Cell(95, 8, $fecha, 1, 1);

        $this->Cell(95, 8, utf8_decode('NOMBRE Y APELLIDOS DEL TRABAJADOR:'), 1);
        $this->Cell(95, 8, utf8_decode($nombre), 1, 1);

        $this->Cell(95, 8, utf8_decode('CARGO:'), 1);
        $this->Cell(95, 8, utf8_decode($cargo), 1, 1);

        $this->Cell(95, 8, utf8_decode('CÉDULA:'), 1);
        $this->Cell(95, 8, utf8_decode($cedula), 1, 1);
        
        
        $this->Ln(12);

        // Texto del cuerpo
        $texto = utf8_decode("
        Respetados Señores, por medio de la presente me permito remitir al Sr. $nombre, identificado con C.C. N° $cedula, con el cargo de $cargo, para realizar el examen médico de retiro.

        Nota: Cuando el examen es de retiro, según el código sustantivo del trabajo artículo 57 numeral 7, el trabajador tiene 5 días hábiles para asistir a realizarse los mismos, si no lo hace se entenderá que eludió o dilató la realización del mismo.

        Confirmo que por voluntad, no deseo ir a realizarme el examen médico de retiro _".$confrima."_
        ");
        $this->MultiCell(190, 7, $texto, 1, 'J');
        $this->Ln(6);

        // Cuadro de firmas
        $this->SetFont('Arial', '', 10);

        // --- NOMBRE DEL TRABAJADOR ---
        $this->Cell(190, 8, utf8_decode('NOMBRE DEL TRABAJADOR:  '.$nombre), 1, 1);

        // Guardar posición actual antes de dibujar la firma
        $yFirmaTrabajador = $this->GetY(); // posición Y actual

        // --- FIRMA Y CÉDULA DEL TRABAJADOR ---
        $this->Cell(95, 15, utf8_decode('FIRMA:'), 1, 0);
        $this->Cell(95, 15, utf8_decode('CC: '.$cedula), 1, 1);
        $firmaArchivo = $_POST['firma']; // nombre del archivo
        $rutaFirma = 'https://sistema.transmillas.com/nueva_plataforma/uploads/firmasLiquidaciones/' . $firmaArchivo; // ajusta la ruta según tu estructura
        // Si existe la firma enviada por POST
        if (!empty($firmaArchivo)) {
            
            

            // if (file_exists($rutaFirma)) {
                // Ajustar posición para colocar la imagen dentro de la celda
                $xFirma = 25; // posición X donde inicia el recuadro "FIRMA:"
                $yFirma = $yFirmaTrabajador + 2; // un poco más abajo del borde superior
                $anchoFirma = 55; // ancho de la firma en mm (ajusta según tamaño)
                $this->Image($rutaFirma, $xFirma, $yFirma, $anchoFirma);
            // }
        }

        // --- NOMBRE DEL JEFE INMEDIATO ---
        $this->Cell(190, 8, utf8_decode('NOMBRE DEL JEFE INMEDIATO:'), 1, 1);
        $this->Cell(95, 15, utf8_decode('FIRMA:'), 1, 0);
        $this->Cell(95, 15, utf8_decode('CC:'), 1, 1);

        // --- NOMBRE DEL RESPONSABLE DE SG-SST ---
        $this->Cell(190, 8, utf8_decode('NOMBRE DEL RESPONSABLE DE SG-SST:'), 1, 1);
        $this->Cell(95, 15, utf8_decode('FIRMA:'), 1, 0);
        $this->Cell(95, 15, utf8_decode('CC:'), 1, 1);
    }

    function Footer()
    {
        $this->SetY(-20);
        $this->SetFont('Arial', 'I', 8);
        $this->Cell(0, 10, utf8_decode('TRANSMILLAS S.A.S - Empresa de carga y logística'), 0, 0, 'C');
    }
}

// Crear PDF
$pdf = new PDF();
$pdf->AddPage();

// Ejemplo de datos dinámicos
$data = [
    'fecha' => date('d/m/Y'),
    'nombre' => $_POST['nombre'] ?? '______________________',
    'cargo' => $_POST['cargo'] ?? '______________________',
    'cedula' => $_POST['cedula'] ?? '______________________'
];

$pdf->cuerpoFormulario($data);
$pdf->Output();
?>