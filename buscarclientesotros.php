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

  $sql="SELECT `idclientes`,`idclientesdir`, `cli_iddocumento`, `cli_nombre`, `cli_email`, `cli_direccion`, `cli_idciudad`, `cli_telefono`, `cli_clasificacion`, `cli_tipo`, `cli_fecharegistro`,`cli_valoraprobado`,`cli_valorprestado`  FROM `clientes`  inner join clientesdir on cli_idclientes=idclientes WHERE  $cond ";		
	$DB1->Execute($sql);
	$datos=mysqli_fetch_array($DB1->Consulta_ID,MYSQLI_ASSOC);

//Seteamos el header de "content-type" como "JSON" para que jQuery lo reconozca como tal
header('Content-Type: application/json');
//Devolvemos el array pasado a JSON como objeto
echo json_encode($datos, JSON_FORCE_OBJECT);