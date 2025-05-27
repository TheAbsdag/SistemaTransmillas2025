<?php
$id_usuario=$_SESSION['usuario_id'];
$id_nombre=$_SESSION['usuario_nombre'];
$nivel_acceso=$_SESSION['usuario_rol'];
$QL = new sql_transact;
$FB = new funciones_varias;
$LT = new llenatablas;
$conde="";
$date=date("Y-m-d");
$ahora=date("Y-m-d H:i:s");
$bandera=1;
$DB = new DB_mssql;
$DB->conectar();
$DB1 = new DB_mssql;
$DB1->conectar();
$DB2 = new DB_mssql;
$DB2->conectar();

?>
