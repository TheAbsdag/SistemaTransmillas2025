<?php
require("connection/conectarse.php");
require("connection/arrays.php");
require("connection/funciones.php");
require("connection/funciones_clases.php");
require("connection/sql_transact.php");
require("connection/llenatablas.php");
require("connection/PasswordHash.php");
require("definirvar.php");
date_default_timezone_set("America/Bogota");


echo $huella_digital = $_POST['huella_digital'];
if (isset($_POST["user"]) && isset($_POST["pass"])) 
{
	
	$DBss = new DB_mssql;
	$DBss->conectar();
	

	$passw=md5($_POST["pass"]);
 	$usuario=mysqli_real_escape_string($DBss->Conexion_ID,$_POST['user']);
	if($usuario==''){
			$usuario=$_POST['user'];
	} 

	 //echo $_POST['user'];
 	  $query = "SELECT idusuarios, idroles, usu_usuario,usu_usuario, usu_pass, usu_nombre, rol_nombre,usu_idsede FROM usuarios INNER JOIN roles ON roles_idroles=idroles AND usu_usuario='$usuario' AND 
	usu_pass='$passw' AND usu_estado=1";
	$DBss->Execute($query); 
	/* printf("Errormessage: %s\n", $DBss->Execute($query));
	$row = mysqli_fetch_array($DBss->Consulta_ID); 
	echo "joseli".$row["rol_nombre"]; */
	 $numusu=$DBss->numregistros();            
	
	if($numusu>0){

		$estadoDispositivo = 'NO_VINCULADO';

		if ($deviceId) {

			$deviceIdEscaped = mysqli_real_escape_string(
				$DBss->Conexion_ID,
				$deviceId
			);

			$idUsuario = (int)$row['idusuarios'];

			$sqlDispositivo = "
				SELECT authorized, active
				FROM user_devices
				WHERE user_id = $idUsuario
				AND device_id = '$deviceIdEscaped'
				LIMIT 1
			";

			$DBss->Execute($sqlDispositivo);

			if ($DBss->numregistros() > 0) {

				$disp = mysqli_fetch_array($DBss->Consulta_ID);

				if ((int)$disp['active'] === 0) {
					$estadoDispositivo = 'BLOQUEADO';
				} elseif ((int)$disp['authorized'] === 0) {
					$estadoDispositivo = 'PENDIENTE';
				} else {
					$estadoDispositivo = 'AUTORIZADO';
				}
			}
		}

		switch ($estadoDispositivo) {

			case 'NO_VINCULADO':
				header("Location: index.php?error_login=DISPOSITIVO_NO_VINCULADO");
				exit;

			case 'PENDIENTE':
				header("Location: index.php?error_login=DISPOSITIVO_PENDIENTE");
				exit;

			case 'BLOQUEADO':
				header("Location: index.php?error_login=DISPOSITIVO_BLOQUEADO");
				exit;

			case 'AUTORIZADO':
				break;
		}


		$row = mysqli_fetch_array($DBss->Consulta_ID); 
		$storedPassword = $row['usu_pass'];
		$salt = "citas2344fsdfd";
		$postedPassword=mysqli_real_escape_string($DBss->Conexion_ID,md5($_POST['pass']));
		$saltedPostedPassword = $salt . $postedPassword;
		$hasher = new PasswordHash(8,false);
		$saltedPostedPassword = $hasher->HashPassword($saltedPostedPassword);
		$pass=md5($_POST['pass']);
		$check = $hasher->CheckPassword($saltedPostedPassword, $storedPassword);
		//header ("Location: seleccionar_destino.php");
//		if($pass==$storedPassword){
//		if($check){
	

			session_name("projecst2344fsdfd");
			session_start(); 
			$id_ses=session_id();		
			session_cache_limiter('nocache,private');
			$_SESSION['usuario_rol']=$row["idroles"];
			$_SESSION['usuario_login']=$_POST['user'];
			$_SESSION['token'] = md5(rand().$_SESSION['usuario_login']);
			$_SESSION['usuario_nombre']=$row["usu_nombre"];
			$_SESSION['usuario_id']=$row["idusuarios"];
			$_SESSION['rol_nombre']=$row["rol_nombre"];
			$_SESSION['usu_idsede']=$row["usu_idsede"];
			$_SESSION['inicio']='1';
			$_SESSION['iniciobd']=$iniciobd;
			$act="UPDATE usuarios SET usu_token='".$_SESSION['token']."' WHERE usu_usuario='".$_SESSION['usuario_login']."'";
			$DBss->Execute($act);

			$sqlprecio = "SELECT  (`pre_inicial`-1) as precio FROM `precioskilos` order by pre_inicial asc limit 1";
			$DBss->Execute($sqlprecio);
			$row2 = mysqli_fetch_array($DBss->Consulta_ID); 
			$_SESSION['precioinicial'] = $row2['precio'];
			$precioinicialkilos=$_SESSION['precioinicial'];
			//if($numusu>1){ 
				//header ("Location: seleccionar_rol.php"); }
			//else {
				
		// Set parameters and execute
		$idname = $row["idusuarios"];
		$username = $row["usu_nombre"];
		$rolname=$row["rol_nombre"];
		$ip = $_SERVER['REMOTE_ADDR'];
		$login_date = date('Y-m-d H:i:s');
		$device_type = $_SERVER['HTTP_USER_AGENT'];	

		// Prepare and bind
		$stmt = "INSERT INTO userslogin  (idusername,username,rolname,mac, ip, login_date, device_type) VALUES ($idname,'$username', '$rolname','$huella_digital','$ip',' $login_date', '$device_type')";
		$DBss->Execute($stmt);
		$DBss->cerrarconsulta();


			header ("Location: seleccionar_destino.php"); 
				//}
//		}
	}
	else{ 
		//echo "dos";
		header ("Location: index.php?error_login=2");
	}
}	
else 
{

	session_name("projecst2344fsdfd");
	session_start();
	if(!empty($_SESSION['usuario_login']) && !empty($_SESSION['token'])) {
		$DBss = new DB_mssql;
		$DBss->conectar();
		$_SESSION['usuario_login']=mysqli_real_escape_string($DBss->Conexion_ID,$_SESSION['usuario_login']);
		$_SESSION['token']=mysqli_real_escape_string($DBss->Conexion_ID,$_SESSION['token']);
		$query="SELECT idusuarios FROM usuarios WHERE usu_usuario='".$_SESSION['usuario_login']."' AND usu_token='".$_SESSION['token']."'";
		$DBss->execute($query);
		if($DBss->numregistros()<20){
			$_SESSION['token']= md5(rand().$_SESSION['usuario_login']);
			$act="UPDATE usuarios SET usu_token='".$_SESSION['token']."' WHERE usu_usuario='".$_SESSION['usuario_login']."'";
			$DBss->Execute($act);
			$DBss->cerrarconsulta();
		} else {
			$DBss->cerrarconsulta();
			session_destroy();
			header("Location:index.php?error_login=8");
			exit;
		}
	}
	else {		
		session_destroy();


		if (isset($redirectIfNotLogged)) {
			header("Location: " . $redirectIfNotLogged);
		} else {
			header("Location:index.php?error_login=8");
			exit;
		}
	}
}
?>