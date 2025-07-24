<?php
require("login_autentica.php"); //coneccion bade de datos
$DB1 = new DB_mssql;
$DB1->conectar();
$DB = new DB_mssql;
$DB->conectar();

//Obtenemos los datos de los input

$vlores = $_POST["vlores"];
$tipo=$_POST["tipo"];
$cond="";
if($tipo=='documento'){ $cond=" cli_iddocumento='$vlores' and cli_principal=1 ";}
else if($tipo=='telefono'){  $cond=" cli_telefono='$vlores'"; }
else if($tipo=='cliente'){  $cond=" idclientesdir='$vlores'"; }

//   $sql="SELECT `idclientes`,`idclientesdir`, `cli_iddocumento`, `cli_nombre`, `cli_email`, `cli_direccion`, `cli_idciudad`, `cli_telefono`, `cli_clasificacion`, `cli_tipo`, `cli_fecharegistro`,`cli_valoraprobado`,`cli_valorprestado`  FROM `clientes`  inner join clientesdir on cli_idclientes=idclientes WHERE  $cond ";		
// 	$DB1->Execute($sql);
// 	$datos=mysqli_fetch_array($DB1->Consulta_ID,MYSQLI_ASSOC);

// //Seteamos el header de "content-type" como "JSON" para que jQuery lo reconozca como tal
// header('Content-Type: application/json');
// //Devolvemos el array pasado a JSON como objeto
// echo json_encode($datos, JSON_FORCE_OBJECT);



// Consulta de cliente
$sql = "SELECT `idclientes`,`idclientesdir`, `cli_iddocumento`, `cli_nombre`, `cli_email`, `cli_direccion`, `cli_idciudad`, `cli_telefono`, `cli_clasificacion`, `cli_tipo`, `cli_fecharegistro`,`cli_valoraprobado`,`cli_valorprestado`  
FROM `clientes`  
INNER JOIN clientesdir ON cli_idclientes = idclientes 
WHERE $cond";
$DB1->Execute($sql);
$cliente = mysqli_fetch_array($DB1->Consulta_ID, MYSQLI_ASSOC);

// Consulta de servicios del cliente en los últimos 2 días
$sql2 = "SELECT 
  s.ser_fecharegistro,
  cs.cli_nombre, 
  cs.cli_direccion,
  c1.ciu_nombre AS ciudad_destinatario,
  cs.cli_telefono,
  s.ser_destinatario,
  s.ser_direccioncontacto,
  c2.ciu_nombre AS ciudad_remitente,
  s.ser_telefonocontacto 
FROM clientesservicios cs
JOIN rel_sercli rsc ON rsc.ser_idclientes = cs.idclientesdir
JOIN servicios s ON s.idservicios = rsc.ser_idservicio
JOIN ciudades c1 ON c1.idciudades = s.ser_ciudadentrega
JOIN ciudades c2 ON c2.idciudades = cs.cli_idciudad
WHERE cs.cli_telefono = '{$cliente['cli_telefono']}'
  AND s.ser_fecharegistro >= CURDATE() - INTERVAL 1 DAY
  AND s.ser_fecharegistro < CURDATE() + INTERVAL 1 DAY
  AND s.ser_estado < 4
    ";
$DB1->Execute($sql2);
$servicios = [];
while ($row = mysqli_fetch_array($DB1->Consulta_ID, MYSQLI_ASSOC)) {
    $servicios[] = $row;
}

// Devolver ambos arrays como JSON
header('Content-Type: application/json');
echo json_encode([
    'cliente' => $cliente,
    'servicios' => $servicios
], JSON_UNESCAPED_UNICODE);

?>