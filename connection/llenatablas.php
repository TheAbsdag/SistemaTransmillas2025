<?php
/* Esta clase se encarga de realizar ciertas funciones que tienen que ver con la manipulaci&oacute;n de fechas, funciones de presentaci&oacute;n y otro tipo de procedimientos auxiliares */
class llenatablas
{
	function llenartablas(){} // Constructor vacio
	/* --------------------------------------- Funciones de formularios ---------------------------------------  */
	/* A partir de este punto se definen los m&eacute;todos que interactuan con los campos de formularios */
	/* Este m&eacute;todo se encarga de llenar un elemento tipo select de un formulario con datos a partir de una consulta ejecutada, los par�metros son: 
	$sql que indica la consulta, $valor indica la posici&oacute;n dentro de la consulta del campo valores dentro de la etiqueta option, $mostrar indica 
	la posici&oacute;n dentro de la consulta del campo label dentro de la dentro de la etiqueta option y $parama indica el valor actual del combo */
	function devuelvecampos($tabla, $tipo, $id_param)
	{
		$parames=""; $add=""; $sqls=""; $editades=0; $campos1=""; $redir="";
		switch($tabla)
		{
			
			case "Procesos":
			$redir="adm_tpreguntas.php"; $tabela="procesos"; $id_p="idprocesos"; $campos="idprocesos, areasgestion_idareasgestion, prc_nombre"; 
			$campos1="areasgestion_idareasgestion, prc_nombre"; 
			break;
			case "Componentes":
			$redir="adm_tpreguntas.php"; $tabela="componentes"; $id_p="idcomponentes"; $campos="idcomponentes, procesos_idprocesos, com_componente, com_aspectos, com_peso"; 
			$parames="param1-3"; $campos1="areasgestion_idareasgestion, procesos_idprocesos, com_componente, com_aspectos, com_peso";
			if($tipo==0){
				$campos="idcomponentes, areasgestion_idareasgestion, idprocesos, com_componente, com_aspectos, com_peso"; 			
				$tabela="componentes INNER JOIN procesos ON procesos_idprocesos=idprocesos";
			}
			break;

			case "Rol":
			//$campos="idroles, rol_nombre"; $campos1="rol_nombre"; $tabela="roles"; $id_p="idroles"; $redir="adm_roles.php";
			$campos="idroles, rol_nombre"; 
			$campos1="rol_nombre"; 
			$tabela="roles"; 
			$id_p="idroles"; 
			$redir="adm_roles.php";
			break;

			case "Dependencia":
			$campos="iddependencias, dep_predecesor, dep_nombre, dep_responsable"; $campos1="dep_predecesor, dep_nombre, dep_responsable"; 
			$tabela="dependencias"; $id_p="iddependencias"; $redir="adm_organigramas.php";
			break;

			case "Usuario": // nota los campos del usurio se guardan de acuerdo al orden en que se envian , al orden en que esta el html
			$campos="idusuarios, roles_idroles, usu_nombre, usu_usuario,usu_pass,usu_mail,usu_idtipodocumento,usu_identificacion, usu_genero, usu_fechanacimiento, usu_idsede, usu_telefono, usu_celular,usu_nivelacademico,usu_tipovehiculo,usu_vehiculo,usu_licencia,usu_fechalicencia,usu_tipocontrato,usu_estado,usu_idcredito"; 
			$campos1="roles_idroles, usu_nombre, usu_usuario,usu_pass,usu_mail,usu_idtipodocumento,usu_identificacion, usu_genero, usu_fechanacimiento, usu_idsede, usu_telefono, usu_celular,usu_nivelacademico,usu_tipovehiculo,usu_vehiculo,usu_licencia,usu_fechalicencia,usu_tipocontrato,usu_estado,usu_idcredito"; 
		
			$tabela="usuarios"; 
			$id_p="idusuarios"; 
			$redir="adm_usuarios.php"; 
			$parames="param4-1";
			break;	
			case "Vehiculos":
				$campos="idvehiculos, `veh_tipo`, `veh_marca`, `veh_placa`, `veh_modelo`,`veh_dueño`, `veh_fechaseguro`, `veh_fechategnomecanica`, `veh_fechamantenimiento`, `veh_kilactual`, `veh_aceitekil`, `veh_estado`, `veh_color`, `veh_tipov`, `veh_chasis`, `veh_motor`, `veh_cilidraje`, `veh_usuve`, `veh_observaciones`"; 
				$campos1="`veh_tipo`, `veh_marca`, `veh_placa`, `veh_modelo`,`veh_color`, `veh_tipov`, `veh_dueño`, `veh_fechaseguro`, `veh_fechategnomecanica`, `veh_fechamantenimiento`, `veh_kilactual`, `veh_aceitekil`,`veh_chasis`, `veh_motor`,  `veh_cilidraje`, `veh_usuve`, `veh_estado`, `veh_observaciones`"; 
				$tabela="vehiculos"; 
				$id_p="idvehiculos"; 
				$redir="adm_vehiculos.php"; 
			break;	

			case "Clientes":		
			$campos="`idclientes`, `cli_iddocumento`, `cli_nombre`, `cli_telefono`, `cli_idciudad`, `cli_direccion`, `cli_email`, `cli_clasificacion`, `cli_tipo`, `cli_fecharegistro`"; 
			$campos1="`idclientes`, `cli_iddocumento`, `cli_nombre`, `cli_telefono`, `cli_idciudad`, `cli_direccion`, `cli_email`, `cli_clasificacion`"; 
			$tabela="Clientes"; 
			$id_p="idclientes"; 
			$redir="clientes.php"; 
			break;	

			case "Precios":
			$campos="`idprecios`, `pre_idciudadori`, `pre_idciudaddes`, `pre_kilo`, `pre_adicional`,`pre_tiposervicio`"; 
			$campos1="`pre_idciudadori`, `pre_idciudaddes`, `pre_kilo`, `pre_adicional`,`pre_tiposervicio`"; 
			$tabela="precios"; 
			$id_p="idprecios"; 
			$redir="precios.php"; 
			
			break;
			case "reclamos":	
				$campos="`idreclamos`, `rec_numero`, `rec_fechaingreso`, `rec_fechaenvio`, `rec_tipo`, `rec_descripcion`, `rec_acuerdo`, `rec_valoracuerdo`, `rec_fechaacuerdo`, `rec_guia`, `rec_idservicio`, `rec_nombre`, `rec_telefono`, `rec_correo`, `rec_userregistra`, `rec_estado`, `rec_ciudadenvio`, `rec_direccion`"; 
				$campos1="`rec_numero`, `rec_fechaingreso`, `rec_fechaenvio`, `rec_tipo`, `rec_descripcion`, `rec_acuerdo`, `rec_valoracuerdo`, `rec_fechaacuerdo`, `rec_guia`, `rec_idservicio`, `rec_nombre`, `rec_telefono`, `rec_correo`, `rec_userregistra`, `rec_estado`, `rec_ciudadenvio`, `rec_direccion`"; 
				$tabela="reclamos"; 
				$id_p="idreclamos"; 
				$redir="reclamos.php"; 
			break;		
			case "pre-operacional":
				$campos="`idpreoperacinal`, `prevehiculo`, `pretipovehiculo`, `prefechaingreso`, `preidusuario`, `preencuesta`, `pre_observaciones`, `preestado`, `pre_obsevaciones`, `pre_correctiva`, `pre_responsable`, `prefechavalidacion`, `predatosvalidados`, `pre_descvalidada`, `pre_iduservalida`"; 
				$campos1="`prevehiculo`, `pretipovehiculo`, `prefechaingreso`, `preidusuario`, `preencuesta`, `pre_observaciones`, `preestado`, `pre_obsevaciones`, `pre_correctiva`, `pre_responsable`, `prefechavalidacion`, `predatosvalidados`, `pre_descvalidada`, `pre_iduservalida`"; 
				$tabela="`pre-operacional`"; 
				$id_p="idpreoperacinal"; 
				$redir="seguimientouser.php"; 

				break;
			case "Precios credito":

				$campos="`idprecioscredito`, `pre_idcredito`, `pre_idciudadori`, `pre_idciudades`, `pre_preciokilo`, `pre_precioadicional`, `pre_tiposervicio`"; 
				$campos1="`pre_idcredito`, `pre_idciudadori`, `pre_idciudades`, `pre_preciokilo`, `pre_precioadicional`, `pre_tiposervicio`"; 
				$tabela="precios_credito"; 
				$id_p="idprecioscredito"; 
				$redir="precios_creditos.php"; 
			break;	
			case "Porcentajes Sedes":

			   $campos="`idporcentajespaquetes`, `por_idsede`,por_idsededestino, `por_idpaquete`, `por_tiposervicio`, por_estadoservicio,`por_kilosgramosmin`, `por_kilogramosmaximo`, `por_porcentaje`,por_porcentajeempresa"; 
			   $campos1=" `por_idsede`,por_idsededestino, `por_idpaquete`, `por_tiposervicio`, por_estadoservicio, `por_kilosgramosmin`, `por_kilogramosmaximo`, `por_porcentaje`,por_porcentajeempresa"; 
			   $tabela="porcentajespaquetes"; 
			   $id_p="idporcentajespaquetes"; 
			   $redir="porcentaje_sedes.php"; 
			break;
			case "Tareas":

				$campos="`idtareas`, `tar_descripcion`, `tar_diassemana`, `tar_fecha`, `tar_idrol`, `tar_idsede`, `tar_idoperario`, `tar_hora`, `tar_estado`,`tar_usuario`"; 
				$campos1="`tar_descripcion`, `tar_diassemana`, `tar_fecha`, `tar_idrol`, `tar_idsede`, `tar_idoperario`, `tar_hora`, `tar_estado`,`tar_usuario`"; 
				$tabela="tareas"; 
				$id_p="idtareas"; 
				$redir="programartareas.php"; 
			 break;
			case "asignardinero":
			$campos="`idasignaciondinero`, `asi_idpromotor`, `asi_valor`, `asi_fecha`, `asi_idautoriza`,`asi_idciudad`,`asi_descripcion`,`asi_tipo`"; 
			$campos1="`asi_idpromotor`, `asi_valor`, `asi_fecha`, `asi_idautoriza`,`asi_idciudad`,`asi_descripcion`,`asi_tipo`"; 
			$tabela="asignaciondinero"; 
			$id_p="idasignaciondinero"; 
			$redir="asignardinero.php"; 

			break;	
			case "rel_crecli": 
				$campos="`idrelcrecli`, `rel_idcredito`, `rel_idcliente` "; 
				$campos1="`rel_idcredito`, `rel_idcliente` "; 
				$tabela="rel_crecli"; 
				$id_p="idrelcrecli"; 
				$redir="relacioncreditos.php"; 
			break;
			case "Abonos": 
				$campos="`idabono`, `abo_fecha`, `abo_valor`, `abo_idservicio`, `abo_iduser`, `abo_idsede`, `abo_estado` "; 
				$campos1=" `abo_fecha`, `abo_valor`, `abo_idservicio`, `abo_iduser`, `abo_idsede`, `abo_estado` "; 
				$tabela="abonosguias"; 
				$id_p="idabono"; 
				$redir="asignar_abonos.php"; 
			break;
			case "gastosoperador":
			$campos="`idasignaciondinero`, `asi_idpromotor`, `asi_valor`, `asi_fecha`, `asi_idautoriza`,`asi_idciudad`,`asi_descripcion`,`asi_tipo`"; 
			$campos1="`asi_idpromotor`, `asi_valor`, `asi_fecha`, `asi_idautoriza`,`asi_idciudad`,`asi_descripcion`,`asi_tipo`"; 
			$tabela="asignaciondinero"; 
			$id_p="idasignaciondinero"; 
			$redir="gastosoperador.php"; 

			break;	
			case "transpasodinero":
			$campos="`idasignaciondinero`, `asi_idpromotor`, `asi_valor`, `asi_fecha`, `asi_idautoriza`,`asi_idciudad`,`asi_descripcion`,`asi_tipo`"; 
			$campos1="`asi_idpromotor`, `asi_valor`, `asi_fecha`, `asi_idautoriza`,`asi_idciudad`,`asi_descripcion`,`asi_tipo`"; 
			$tabela="asignaciondinero"; 
			$id_p="idasignaciondinero"; 
			$redir="transpasodinero.php"; 

			break;
			case "deudaspro":
			$campos="`iddeudapromotor`, `deu_idpromotor`, `deu_valor`,`deu_fecha`,`deu_idautoriza`,  `deu_idciudad`,`due_descripcion`,`deu_tipo` "; 
			$campos1="`deu_idpromotor`, `deu_valor`,`deu_fecha`,`deu_idautoriza`,  `deu_idciudad`,`due_descripcion`,`deu_tipo`"; 
			$tabela="duedapromotor"; 
			$id_p="iddeudapromotor"; 
			$redir="deudaspro.php"; 

			break;	
			case "referenciasfamiliares":
				$campos="`idrefenciasfamiliares`, `ref_nombre`, `ref_parentesco`, `ref_ocupacion`, `ref_telefono`, `ref_idhojavida`, `ref_usuregistra`, `ref_fecharegistra`"; 
				$campos1="`ref_nombre`, `ref_parentesco`, `ref_ocupacion`, `ref_telefono`, `ref_idhojavida`, `ref_usuregistra`, `ref_fecharegistra`"; 
				$tabela="referenciasfamiliares"; 
				$id_p="idrefenciasfamiliares"; 
				$redir="newhojadevidaok.php"; 
	
				break;		
		
			case "cuentasentrega":
				$campos="`idasignaciondinero`, `asi_idpromotor`, `asi_valor`, `asi_fecha`, `asi_idautoriza`,`asi_idciudad`,`asi_descripcion`,`asi_tipo`"; 
				$campos1="`asi_idpromotor`, `asi_valor`, `asi_fecha`, `asi_idautoriza`,`asi_idciudad`,`asi_descripcion`,`asi_tipo`"; 
				$tabela="asignaciondinero"; 
				$id_p="idasignaciondinero"; 
				$redir="cuentasoper.php"; 
				break;

			case "cajamenor":
			$campos="`idcajamenor`, `caj_idciudadori`, `caj_idciudaddes`, `caj_tipotransacion`, `caj_valor`, `caj_idusuario`, `caj_fecharegistro` "; 
			$campos1="`caj_idciudadori`, `caj_idciudaddes`, `caj_tipotransacion`, `caj_valor`, `caj_idusuario`, `caj_fecharegistro`"; 
			$tabela="cajamenor"; 
			$id_p="idcajamenor"; 
			$redir="cajamenor.php"; 
			break;		
			case "facturascreditos":
				$tabela="facturascreditos"; 
				$id_p="idfacturascreditos"; 
				$redir="informecreditos.php"; 
			break;	
			case "remesas":
			$campos="`idgastos`, `gas_idciudadori`, `gas_idciudaddes`, `gas_tipotransacion`, `gas_valor`, `gas_idusuario`, `gas_fecharegistro` "; 
			$campos1="`gas_idciudadori`, `gas_idciudaddes`, `gas_tipotransacion`, `gas_valor`, `gas_idusuario`, `gas_fecharegistro`"; 
			$tabela="gastos"; 
			$id_p="idgastos"; 
			$redir="gastos.php"; 
			break;	
			case "datoslaborales":
				$campos="`idreferenciaslaborales`, `ref_empresa`, `ref_fehcainicio`, `ref_fechaterminacion`, `ref_telefono`, `ref_userregistra`, `ref_fechaingreso`"; 
				$campos1="`ref_empresa`, `ref_fehcainicio`, `ref_fechaterminacion`, `ref_telefono`, `ref_userregistra`, `ref_fechaingreso`"; 
				$tabela="referenciaslaborales"; 
				$id_p="idreferenciaslaborales"; 
				$redir="newhojadevidaok.php"; 
			break;		
			case "referenciasestudio":
				$campos="`idreferenciasestudio`, `ref_grado`, `ref_institucion`, `ref_ciudad`, `ref_fehcainicio`, `ref_fechaterminacion`, `ref_userregistra`, `ref_fechaingreso`, `ref_idhojavida`"; 
				$campos1="`ref_grado`, `ref_institucion`, `ref_ciudad`, `ref_fehcainicio`, `ref_fechaterminacion`, `ref_userregistra`, `ref_fechaingreso`, `ref_idhojavida`"; 
				$tabela="referenciasestudio"; 
				$id_p="idreferenciasestudio"; 
				$redir="newhojadevidaok.php"; 
			break;	
			case "hojadevida":
				$campos="`idhojadevida`, `hoj_fechaingreso`, `hoj_sede`, `hoj_nombre`, `hoj_apellido`, `hoj_fechanacimiento`, `hoj_cedula`, `hoj_licencia`, `hoj_tipolicencia`, `hoj_telefono`, `hoj_celular`, `hoj_direccion`, `hoj_tipovivienda`, `hoj_arrendador`, `hoj_conyuge`, `hoj_profesion`, `hoj_celularconyuge`, `hoj_tipoestudio`, `hoj_institucion`, `hoj_ciudades`, `hoj_fechagrado`, `hoj_eps`, `hoj_fechaeps`, `hoj_arl`, `hoj_fechaafi`, `hoj_pension`, `hoj_fechapen`, `hoj_cajacompensacion`, `hoj_fechacaja`, `hoj_estado`, `hoj_fechacontrato`, `hoj_tipocontrato`, `hoj_fechatermino`, `hoj_entregapuesto`, `hoj_pazysalvo`"; 
				$campos1="`hoj_fechaingreso`, `hoj_sede`, `hoj_nombre`, `hoj_apellido`, `hoj_fechanacimiento`, `hoj_cedula`, `hoj_licencia`, `hoj_tipolicencia`, `hoj_telefono`, `hoj_celular`, `hoj_direccion`, `hoj_tipovivienda`, `hoj_arrendador`, `hoj_conyuge`, `hoj_profesion`, `hoj_celularconyuge`, `hoj_tipoestudio`, `hoj_institucion`, `hoj_ciudades`, `hoj_fechagrado`, `hoj_eps`, `hoj_fechaeps`, `hoj_arl`, `hoj_fechaafi`, `hoj_pension`, `hoj_fechapen`, `hoj_cajacompensacion`, `hoj_fechacaja`, `hoj_estado`, `hoj_fechacontrato`, `hoj_tipocontrato`, `hoj_fechatermino`, `hoj_entregapuesto`, `hoj_pazysalvo`"; 
				$tabela="hojadevida"; 
				$id_p="idhojadevida"; 
				$redir="hojadevida.php"; 
			break;	
				case "Pais":
			
			$tabela="paises"; $campos="idpaises, pai_nombre, pai_codigo"; $campos1="pai_nombre, pai_codigo"; $redir="adm_paises.php"; $id_p="idpaises";
			break;
			case "Factura":
			$tabela="conf_fac"; $campos="idconfac, idconsecitivo, idresolucion"; $campos1="idconsecitivo, idresolucion";
			$redir="inicio1.php"; $id_p="idconfac";
			break;
			case "Region":
			$tabela="regiones"; $campos="idregiones, paises_idpaises, reg_nombre"; $id_p="idregiones"; $redir="adm_regiones.php"; $campos1="paises_idpaises, reg_nombre";
			break;
			case "Departamento":
			$tabela="departamentos"; $campos="iddepartamentos, dep_nombre"; $id_p="iddepartamentos";  $redir="adm_departamentos.php"; 
			$campos1="dep_nombre";
			break;
			case "General":
			$redir="adm_matriz.php"; 
			$tabela="matriz";
			$id_p="idmatriz"; 
			$campos="idmatriz, mat_fecha,mat_tipoid,mat_documento,mat_nombre, mat_edad, mat_ingreso, mat_examen, mat_aspecto, mat_color, mat_densidad, mat_ph, mat_leucositos, mat_nitridos, mat_uriboli, mat_sangre, mat_proteinas, mat_cetonas, mat_biliru, mat_glucosa, mat_celulas, mat_bacterias, mat_leocusitos2, mat_moco, mat_cristales, mat_hematites"; 
			$campos1="mat_fecha, mat_tipoid,mat_documento,mat_nombre, mat_edad, mat_ingreso, mat_examen, mat_aspecto, mat_color, mat_densidad, mat_ph, mat_leucositos, mat_nitridos, mat_uriboli, mat_sangre, mat_proteinas, mat_cetonas, mat_biliru, mat_glucosa, mat_celulas, mat_bacterias, mat_leocusitos2, mat_moco, mat_cristales, mat_hematites"; 
			break;
			
			case "Menu":
			if($tipo==1){
				$editades=1;
				$sqls="INSERT INTO menu (idmenu, men_nombre, men_url, men_predecesor, men_orden, men_principal, men_descripcion) VALUES ('',
				'".$_POST["param1"]."','".$_POST["param2"]."','".$_POST["param3"]."','".$_POST["param4"]."','".$_POST["param5"]."',
				'".$_POST["param6"]."')";
			}
			else if($tipo==2)
			{
				$editades=1;
				$sqls="UPDATE menu SET men_nombre='".$_POST["param1"]."', men_url='".$_POST["param2"]."', men_predecesor='".$_POST["param3"]."',
				men_orden='".$_POST["param4"]."', men_principal='".$_POST["param5"]."', men_descripcion='".$_POST["param6"]."' WHERE idmenu='$id_param'";
			}
			$tabela="menu"; $campos=""; $id_p="idmenu";  $redir="adm_menus.php"; $campos1="men_nombre, men_url, men_predecesor, men_orden, men_principal,
			 men_descripcion"; $campos="idmenu, men_nombre, men_url, men_predecesor, men_orden, men_principal, men_descripcion"; 
			break;
			case "Permiso":
			if(isset($_POST["param1"])){
				$editades=1;
				if($tipo==1){
					$sqls="INSERT INTO permisos (idpermisos, menu_idmenu, roles_idroles, per_crear, per_editar, per_eliminar, per_consultar) VALUES ('',
					'".$_POST["param2"]."','".$_POST["param3"]."','".$_POST["param4"]."','".$_POST["param5"]."','".$_POST["param6"]."',
					'".$_POST["param7"]."')";
				}
				else if($tipo==2){
					$sqls="UPDATE permisos SET menu_idmenu='".$_POST["param1"]."', roles_idroles='".$_POST["param2"]."', 
					per_crear='".$_POST["param3"]."', per_editar='".$_POST["param4"]."', per_eliminar='".$_POST["param5"]."', 
					per_consultar='".$_POST["param6"]."' WHERE idpermisos='$id_param' ";
				}
			}
			$redir="adm_permisos.php";  $tabela="permisos"; $campos="idpermisos, menu_idmenu, roles_idroles, per_crear,per_editar, per_eliminar, 
			per_consultar"; $id_p="idpermisos"; $campos1="menu_idmenu, roles_idroles, per_crear, per_editar, per_eliminar, per_consultar";
			break;
			case "Ciudad":
			$tabela="ciudades"; 
			$campos="idciudades, departamentos_iddepartamentos, ciu_nombre"; 
			$id_p="idciudades"; $redir="adm_ciudades.php"; 
			$campos1="idciudades,departamentos_iddepartamentos, ciu_nombre"; 
			$parames="param1-3";
			
			break;

			case "Tipos de Datos":
			$tabela="tiposindicadores"; $campos="idtiposindicadores, int_nombre, int_prefijo, int_sufijo, int_db"; 
			$id_p="idtiposindicadores"; $redir="adm_tiposind.php"; $campos1="int_nombre, int_prefijo, int_sufijo, int_db"; 
			break;
			case "Parametro":
			$tabela="indicadores"; $campos="idindicadores, tiposindicadores_idtiposindicadores, ind_codigo, ind_nombre, ind_descripcion, ind_array, ind_clase"; 
			$id_p="idindicadores"; $redir="adm_indicadores1.php"; $campos1="tiposindicadores_idtiposindicadores, ind_codigo, ind_nombre, ind_descripcion, ind_array,ind_clase"; 
			break;
			case "Campo de Cabezote de formularios":
			$tabela="indicadores"; $campos="idindicadores, tiposindicadores_idtiposindicadores, ind_codigo, ind_nombre, ind_descripcion, ind_array, ind_clase"; 
			$id_p="idindicadores"; $redir="adm_indicadores.php"; $campos1="tiposindicadores_idtiposindicadores, ind_codigo, ind_nombre, ind_descripcion, ind_array, ind_clase"; 
			break;
			case "Formulario":
			$tabela="actindgener"; $campos="idactindgener, proyectos_idproyectos, aci_nombre, poblacionobjetivo_idpoblacionobjetivo, aig_fuente"; 
			$id_p="idactindgener"; $campos1="programas_idprogramas, proyectos_idproyectos, aci_nombre, poblacionobjetivo_idpoblacionobjetivo, aig_fuente"; 
			$redir="formularios.php"; $parames="param1-3";
			if($tipo==0){
				$campos="idactindgener, programas_idprogramas, idproyectos, aci_nombre, poblacionobjetivo_idpoblacionobjetivo, aig_fuente"; 			
				$tabela="actindgener INNER JOIN proyectos ON idproyectos=proyectos_idproyectos";
			}
			break;
			case "Base de datos/Conusulta":
			$tabela="actindgener"; $campos="idactindgener, proyectos_idproyectos, aci_nombre, poblacionobjetivo_idpoblacionobjetivo, aig_fuente"; $id_p="idactindgener"; 
			$campos1="programas_idprogramas, proyectos_idproyectos, aci_nombre, poblacionobjetivo_idpoblacionobjetivo, aig_fuente"; 
			$redir="basesdedatos.php"; $parames="param1-3";
			if($tipo==0){
				$campos="idactindgener, programas_idprogramas, idproyectos, aci_nombre, poblacionobjetivo_idpoblacionobjetivo, aig_fuente"; 			
				$tabela="actindgener INNER JOIN proyectos ON idproyectos=proyectos_idproyectos";
			}
			break;
			default:
			$tabela=""; 
			$campos="";
			$id_p="";
			break;			
		}	
		return array($tabela, $campos, $campos1, $id_p, $redir, $add, $parames, $sqls, $editades);
	}
	function llenaselect($sql,$valor,$mostrar, $parama, $DB)
	{
		$DB->Execute($sql);
		while ($row = mysqli_fetch_array($DB->Consulta_ID)) 
		{
			$orden1=$row[$valor];
			$fes=explode("-",$mostrar);
			$mostra=$row[$fes[0]];
			if(isset($fes[1])){$mostra=$mostra." - ".$row[$fes[1]];}
			if($parama==$orden1){$condicion=" selected ";}
			else{$condicion="";}
			echo "<option  value='$orden1' ".$condicion.">".$mostra."</option>";
		}					
	}

	function llenaselect_datalist($sql,$valor,$mostrar, $parama, $DB)
	{
		$DB->Execute($sql);
		while ($row = mysqli_fetch_array($DB->Consulta_ID)) 
		{
			$orden1=$row[$valor];
			$fes=explode("-",$mostrar);
			$mostra=$row[$fes[0]];
			if(isset($fes[1])){$mostra=$mostra." - ".$row[$fes[1]];}
			if($parama==$orden1){$condicion=" selected ";}
			else{$condicion="";}
			echo "<option id='$orden1' value='$mostra' ".$condicion."/>";
		}					
	}

	function llenaselect_vardos($sql,$valor,$mostrar, $parama, $DB)
	{
		$DB->Execute($sql);
		while ($row = mysqli_fetch_array($DB->Consulta_ID)) 
		{
			$orden1=$row[$valor];
			$fes=explode("-",$mostrar);
			$mostra=$row[$fes[0]];
			if(isset($fes[1])){$mostra=$mostra." - ".$row[$fes[1]];}
			if($parama==$orden1){$condicion=" selected ";}
			else{$condicion="";}
			echo "<option  value='$orden1' ".$condicion.">".$mostra."</option>";
		}					
	}
	/* Este m&eacute;todo tiene exactamente la misma funcionalidad que el anterior, con la �nica diferencia que los datos se muestran codificados en UTF8*/
	function llenaselect_ajax($sql,$valor,$mostrar, $parama, $DB)
	{
		$DB->Execute($sql);
		while ($row = mysqli_fetch_array($DB->Consulta_ID)) 
		{
			$orden1=$row[$valor];
			$mostr=explode("-",$mostrar);
			if(isset($mostr[1])){ $mostra=utf8_encode($row[$mostr[0]]." - ".$row[$mostr[1]]); } else { $mostra=utf8_encode($row[$mostrar]); }
			if($parama==$orden1){$condicion=" selected ";}
			else{$condicion="";}
			echo "<option  value='$orden1' ".$condicion.">".$mostra."</option>";
		}					
	}
	
		function llenaselect_ajax2($sql,$valor,$mostrar, $parama, $DB)
	{
		$DB->Execute($sql);
		while ($row = mysqli_fetch_array($DB->Consulta_ID)) 
		{
			$orden1=$row[$valor];
			$mostr=explode("-",$mostrar);
			if(isset($mostr[1])){ $mostra=utf8_encode($row[$mostr[0]]." - ".$row[$mostr[1]]); } else { $mostra=utf8_encode($row[$mostrar]); }
			if($parama==$orden1){$condicion=" selected ";}
			else{$condicion="";}
			echo "<option  value='$mostra' ".$condicion.">".$mostra."</option>";
		}					
	}
	
	function trae_campo($id_ieducativa, $tabla, $pregunta, $DB)
	{
		$sql="SELECT res_respuesta FROM respuesta_$tabla WHERE preguntas1_idpreguntas1='$pregunta' AND res_idunico 
		IN (SELECT res_idunico FROM respuesta_$tabla WHERE preguntas1_idpreguntas1='26' AND res_respuesta='$id_ieducativa')  ";			
		$DB->Execute($sql);
		return $DB->recogedato(0);
	}
	
	/* Este m&eacute;todo se encarga de llenar los campos de un select de formulario a partir de un array previamente definido */
	function llenaselect_ar($parama,$ar)
	{
		
		for($i=0; $i<sizeof($ar); $i++)
		{
			$orden1=$ar[$i];
			if($parama==$orden1){$condicion=" selected ";}
			else{$condicion="";}
			echo "<option  value='$orden1' ".$condicion.">".$orden1."</option>";
		}					
	}

	function llenaselect_re($parama,$ar)
	{
		$arrcampo='';
		for($i=0; $i<sizeof($ar); $i++)
		{
			$orden1=$ar[$i];
			if($parama==$orden1){$condicion=" selected ";}
			else{$condicion="";}
			$arrcampo.= "<option  value='$orden1' ".$condicion.">".$orden1."</option>";
		}	
		return 	$arrcampo;			
	}


	function llenaselect_ac($parama,$ar)
	{
		$html="";
		for($i=0; $i<sizeof($ar); $i++)
		{
			$orden1=$ar[$i];
			if($parama==$orden1){$condicion=" selected ";}
			else{$condicion="";}
			$html.="<option  value='$orden1' ".$condicion.">".$orden1."</option>";
		}	
		return 	$html;			
	}
	
		function llenaselect_ar2($parama,$ar)
	{
		foreach($ar as $key=>$indice)
		{
			$orden1=$indice;
			$orden=$key;
			if($parama==$orden){$condicion="selected "; }
			else {$condicion="";}
			//echo "<option  value='$orden' ".$condicion.">".$orden1."</option>";	
			echo "<option  value='$orden' ".$condicion.">".utf8_decode($orden1)."</option>";
		}
	}

		function llenaselect_ar22($parama,$ar)
	{
		foreach($ar as $key=>$indice)
		{
			$orden1=$indice;
			$orden=$key;
			if($parama==$orden){$condicion="selected "; }
			else {$condicion="";}
			//$paquete=utf8_encode($paquete);
			//echo "<option  value='$orden' ".$condicion.">".$orden1."</option>";	
			echo "<option  value='$orden' ".$condicion.">".utf8_decode($orden1)."</option>";
		}
	}
	
	function get_nombredoc($id_p, $vers, $tabla, $DB1)
	{
		$ms=1;
		switch($tabla)
		{
			case "Investigacion":
			$tabela="investigaciones"; $campo="inv_nombre"; $idp="idinvestigaciones"; $ms=0; $nombre="investigaci&oacute;n";
			break; 	
			case "Datos de usuario":
			$tabela="usuarios"; $campo="usu_nombre"; $idp="idusuarios"; $ms=0; 
			if($vers==1){ $nombre="Certificaci&oacute;n bancaria"; } 
			else if ($vers==2){ $nombre="C&eacute;dula"; } 
			else if ($vers==3){ $nombre="RUT"; } 
			else if ($vers==4){ $nombre="Hoja de vida"; } 
			break; 	

		}
		if($ms==0){
			$sqls="SELECT $campo FROM $tabela WHERE $idp=$id_p ";
			$DB1->Execute($sqls);
			$nombre=$nombre." ".$DB1->recogedato(0);
		}
		return $nombre;
	}
	/* Este m&eacute;todo tiene exactamente la misma funcionalidad que el anterior, con la �nica diferencia que los datos se muestran codificados en UTF8*/
	function llenaselect_ar_ajax($parama,$ar)
	{
		for($i=0; $i<sizeof($ar); $i++)
		{
			$orden1=$ar[$i];
			if($parama==$orden1){$condicion=" selected ";}
			else{$condicion="";}
			echo "<option  value='$orden1' ".$condicion.">".utf8_decode($orden1)."</option>";
		}					
	}
	/* Este m&eacute;todo llena un campo select de formulario a partir de un array con sus posiciones y no con sus valores */
	function llenaselect_ardos($parama,$ar)
	{
		for($i=0; $i<sizeof($ar); $i++)
		{
			$orden1=$i;
			$orden=$ar[$i];
			if($parama==$i){$condicion=" selected ";}
			else{$condicion="";}
			echo "<option value='$orden1' ".$condicion.">$orden</option>";
		}					
	}
	/* --------------------------------------- Funciones de tablas ---------------------------------------  */
	
	/* A partir de este punto se definen los m&eacute;todos que se encargan de llenar tablas */

	/* Este m&eacute;todo se encarga de llenar una tabla en formato HTML a partir de una consulta SQL determinada. Los par�metros de este m&eacute;todo son:
	- $sql: es la consulta que se Est&aacute; ejecutando
	- $cu: cantidad de campos de la consulta que quiere mostrar en la tabla
	- $nom: es el nombre de la tabla, este ser� utilizado para las funciones de edici&oacute;n y borrado
	- $numero: al ingresar las posiciones de los campos separados por comas, les d� formato de n�mero
	- $plata: al ingresar las posiciones de los campos separados por comas, les d� formato de pesos
	- $link: al ingresar las posiciones de los campos separados por comas, les d� formato de link, en el caso de llevar una 
	  @ el campo remitir� a un correo electronico 
	- $imagen: al ingresar las posiciones de los campos separados por comas, les d� formato de imagen
	- $sin: este campo identifica se se debe hacer edici&oacute;n o no de los elementos de la tabla.
	*/
	/* Devuelve el orden predeterminado de una tabla al ejecutar una consulta */
	function orden()
	{
		if(!isset($dre)){$dre="ASC";}
		if($dre=="ASC"){$dre="DESC";}
		else{$dre="ASC";}
		return $dre;
	}
	/* Este m&eacute;todo tiene exactamente la misma funcionalidad que el anterior, con la �nica diferencia que los datos se muestran codificados en UTF8*/ 
	function llenatabla_ajax($sql, $cu, $nom, $p1, $numero, $plata, $link, $imagen, $porc, $desc, $sin, $DB, $DB1)
	{
		$DB->Execute($sql);
		$va=0;
		while($rw=mysqli_fetch_row($DB->Consulta_ID))
		{
			$va++; $o=1;
			$p=$va%2;
			$id_p=$rw[0];
			if($p==0){$color="#FFFFFF";}
			else{$color="#EFEFEF";}
			echo "<tr bgcolor='$color' class='text' onmouseover='this.style.backgroundColor=\"#C8C6F9\"' onmouseout='this.style.backgroundColor=\"$color\"'>";
			for ($i=1; $i<$cu; $i++)
			{
				$cond1=0;
				$corte=explode(",",$numero);
				for($a=0;$a<count($corte);$a++){
					if($corte[$a]==$i){	$cond2="align='right'"; break;}
					else{$cond2="";}
				}
				$corte=explode(",",$plata);
				for($a=0;$a<count($corte);$a++){
					if($corte[$a]==$i){	$cond1=1; break;}
					else{$cond1=0;}
				}
				$corte=explode(",",$link);
				for($a=0;$a<count($corte);$a++){
					if($corte[$a]==$i){	$cond1=2;}
					else{}
				}
				$corte=explode(",",$imagen);
				for($a=0;$a<count($corte);$a++){
					if($corte[$a]==$i){	$cond1=3;}
					else{}
				}
				$corte=explode(",",$porc);
				for($a=0;$a<count($corte);$a++){
					if($corte[$a]==$i){	$cond1=4;}
					else{}
				}
				$corte=explode(",",$desc);
				for($a=0;$a<count($corte);$a++){
					if($corte[$a]==$i){	$cond1=5;}
					else{}
				}
				if($cond1==1){ 
				echo "<td align='right'>$".number_format($rw[$i],0,".",".")."</td>";	}
				else if($cond1==2){ 
					$dd=explode("@",$rw[$i]);
					if(isset($dd[1])){ echo "<td align='center'><b><a href='mailto:".$rw[$i]."'><span class='a1'>".$rw[$i]."</span></b></a></td>"; }
					else { 
						if(strlen($dd[0])==1) { if($dd[0]==1) { echo "<td align='center'>Si</td>"; } else { 
						echo "<td align='center'>No</td>"; }  } 
						else { echo "<td align='center'><b><a href='http://$rw[$i]' target='_blank'><span class='a1'>".$rw[$i]."</span></a></b></td>"; } 
					}
				}
				else if($cond1==3){ 
					echo "<td align='center'>";
					$sql1="SELECT doc_ruta,iddocumentos,doc_fecha FROM documentos WHERE doc_idviene='$id_p' AND doc_tabla='$nom' AND doc_version='$o' ORDER BY doc_fecha DESC ";
					$DB1->Execute($sql1);
					
                                        $rw1=mysqli_fetch_row($DB1->Consulta_ID);
                                        $ruta=$rw1[0];
                                        
					$imag=$DB1->selimagen($ruta); $o++;
					if($imag!=""){ 
                                            
                                            // Verificar fecha para imagen
                                            if ($tabla === 'cajamenor' || $tabla === 'gastos') {
                                                $fecha_inicio_mes = strtotime(date("01-m-Y 00:00:00", time()));
                                                $fecha_image_entrada = strtotime($rw1[2]);

                                                if($fecha_image_entrada >= $fecha_inicio_mes) {
                                                    $urlDocumento = $rw1[0];
                                                } else {
                                                    $urlDocumento = VISUALIZADOR_IMAGENES . '?tipo=documentos&id=' . $rw1[1];
                                                }
                                            } else {
                                                $urlDocumento = $ruta;
                                            }
                            
                                            echo "<a href='{$urlDocumento}' target='_blank'><img src='$imag' width='20'></a></td>";
                                        }
				}
				else if($cond1==4){echo "<td align='right'>".number_format($rw[$i],2,".",",")."%</td>";}
				else if($cond1==5){ 
					echo "<td align='center'><a href='$rw[$i]'>"; 
					$imag=$this->selimagen($rw[$i]);
					echo "<img src='$imag'>";
					echo "</a></td>";	
				}
				else {echo "<td $cond2>".utf8_encode($rw[$i])."</td>";}
			}
			$DB->edites($id_p, $nom, $sin, $p1);
			echo "</tr>";
		}
		echo "<tr><td colspan='4' class='text'>&nbsp;&nbsp;</td></tr>";
		echo "<tr><td colspan='4' class='text'>&nbsp;&nbsp;<b>Total registros: ".$va."</b></td></tr>";
	}	

	function llenadocs($DB1, $tabla, $id_p, $version)
	{
		echo "<td align='center'>";
		$sql1="SELECT doc_ruta,iddocumentos,doc_fecha FROM documentos WHERE doc_idviene='$id_p' AND doc_tabla='$tabla' AND doc_version=$version ORDER BY doc_fecha DESC ";
		$DB1->Execute($sql1);
		
                $rw1=mysqli_fetch_row($DB1->Consulta_ID);
		$ruta=$rw1[0];
                
		if($ruta!=""){ 
			$imag=$DB1->selimagen($ruta);
			if($imag!=""){ 
                            
                            // Verificar fecha para imagen
                            if ($tabla === 'cajamenor' || $tabla === 'gastos') {
                                $fecha_inicio_mes = strtotime(date("01-m-Y 00:00:00", time()));
                                $fecha_image_entrada = strtotime($rw1[2]);

                                if($fecha_image_entrada >= $fecha_inicio_mes) {
                                    $urlDocumento = $rw1[0];
                                } else {
                                    $urlDocumento = VISUALIZADOR_IMAGENES . '?tipo=documentos&id=' . $rw1[1];
                                }
                            } else {
                                $urlDocumento = $ruta;
                            }
                    
                            echo "<a href='{$urlDocumento}' target='_blank'><img src='$imag' width='15'></a>";
                            
                        }
		}
		echo "</td>";
	}

	function llenadocs1($DB1, $tabla, $id_p, $version, $tam, $vas)
	{
		if($vas==1) {echo "<td align='center'>"; }
		$sql1="SELECT doc_ruta,iddocumentos,doc_fecha FROM documentos WHERE doc_idviene='$id_p' AND doc_tabla='$tabla' AND doc_version=$version ORDER BY doc_fecha DESC ";
		$DB1->Execute($sql1);
                
                $rw1=mysqli_fetch_row($DB1->Consulta_ID);
		$ruta=$rw1[0];
                
		if($ruta!=""){ 
                    
                    // Verificar fecha para imagen
                    if ($tabla === 'cajamenor' || $tabla === 'gastos') {
                        $fecha_inicio_mes = strtotime(date("01-m-Y 00:00:00", time()));
                        $fecha_image_entrada = strtotime($rw1[2]);

                        if($fecha_image_entrada >= $fecha_inicio_mes) {
                            $urlDocumento = $rw1[0];
                        } else {
                            $urlDocumento = VISUALIZADOR_IMAGENES . '?tipo=documentos&id=' . $rw1[1];
                        }
                    } else {
                        $urlDocumento = $ruta;
                    }
                            
                    echo "<img src='{$urlDocumento}' width='$tam'>"; 
                }
		if($vas==1) {echo "</td>"; }
	}
	function llenadocs2($DB1, $tabla, $id_p, $version, $tam, $vas)
	{
		echo "<td align='center'>";
		$sql1="SELECT doc_ruta,iddocumentos,doc_fecha FROM documentos WHERE doc_idviene='$id_p' AND doc_tabla='$tabla' AND doc_version=$version ORDER BY doc_fecha DESC ";
		$DB1->Execute($sql1);
		
                $rw1=mysqli_fetch_row($DB1->Consulta_ID);
		$ruta=$rw1[0];
		
                if($ruta!=""){ 
			$imag=$DB1->selimagen($ruta);
                        
			if($vas='Historico'){ $ruta='../'.$ruta; }else{ $ruta0='';}
                        
			if($imag!=""){ 
                            
                            // Verificar fecha para imagen
                            if ($tabla === 'cajamenor' || $tabla === 'gastos') {
                                $fecha_inicio_mes = strtotime(date("01-m-Y 00:00:00", time()));
                                $fecha_image_entrada = strtotime($rw1[2]);

                                if($fecha_image_entrada >= $fecha_inicio_mes) {
                                    $urlDocumento = $rw1[0];
                                } else {
                                    $urlDocumento = VISUALIZADOR_IMAGENES . '?tipo=documentos&id=' . $rw1[1];
                                }
                            } else {
                                $urlDocumento = $ruta;
                            }
                        
                            echo "<a href='{$urlDocumento}' target='_blank'><img src='{$urlDocumento}' width='$tam'></a>";
                            
                        }
		}
		echo "</td>";
	}
	function llenadocs4($DB1, $tabla, $id_p, $version, $tam, $valor,$color)
	{
		$imagen="<td align='center' bgcolor='$color' >";
		$sql1="SELECT doc_ruta,iddocumentos,doc_fecha FROM documentos WHERE doc_idviene='$id_p' AND doc_tabla='$tabla' AND doc_version=$version ORDER BY doc_fecha DESC ";
                $DB1->Execute($sql1);
		
                $rw1=mysqli_fetch_row($DB1->Consulta_ID);
		$ruta=$rw1[0];
                
		if($ruta!=""){ 
			$imag=$DB1->selimagen($ruta);
                        
			if($imag!=""){ 
                            
                            // Verificar fecha para imagen
                        if ($tabla === 'cajamenor' || $tabla === 'gastos') {
                            $fecha_inicio_mes = strtotime(date("01-m-Y 00:00:00", time()));
                            $fecha_image_entrada = strtotime($rw1[2]);

                            if($fecha_image_entrada >= $fecha_inicio_mes) {
                                $urlDocumento = $rw1[0];
                            } else {
                                $urlDocumento = VISUALIZADOR_IMAGENES . '?tipo=documentos&id=' . $rw1[1];
                            }
                        } else {
                            $urlDocumento = $ruta;
                        }
                        
                            $imagen.="<a href='{$urlDocumento}' target='_blank'>$valor</a>";
                        }else{ $imagen.=$valor; }
		}else{
			$imagen.=$valor; 
		}
		$imagen.="</td>";
		return $imagen;
	}
	function llenadocs3($DB1, $tabla, $idhojadevida, $version, $tam, $valor)
	{
		$imagen="<td align='center' >";
		$sql1="SELECT doc_ruta,iddocumentos,doc_fecha FROM documentos WHERE doc_idviene='$idhojadevida' AND doc_tabla='$tabla' AND doc_version=$version ORDER BY doc_fecha DESC ";
		$DB1->Execute($sql1);
                
                $rw1=mysqli_fetch_row($DB1->Consulta_ID);
		$ruta=$rw1[0];
                
		if($ruta!=""){ 
                    $imag=$DB1->selimagen($ruta);

                    if($imag!=""){ 

                        // Verificar fecha para imagen
                        if ($tabla === 'cajamenor' || $tabla === 'gastos') {
                            $fecha_inicio_mes = strtotime(date("01-m-Y 00:00:00", time()));
                            $fecha_image_entrada = strtotime($rw1[2]);

                            if($fecha_image_entrada >= $fecha_inicio_mes) {
                                $urlDocumento = $rw1[0];
                            } else {
								
								$urlDocumento = $rw1[0];
                                // $urlDocumento = VISUALIZADOR_IMAGENES . '?tipo=documentos&id=' . $rw1[1];
                            }
                        } else {
                            $urlDocumento = $ruta;
                        }

                        $imagen.="<a href='{$urlDocumento}' target='_blank'>{$valor}</a>";

                    }else{ $imagen.=$valor; }
		}else{
			$imagen.=$valor; 
		}
		$imagen.="</td>";
		return $imagen;
	}	
	function llenadocs31($DB1, $tabla, $id_p, $version, $tam, $valor)
	{
		$imagen="";
		// $sql1="SELECT doc_ruta,iddocumentos,doc_fecha FROM documentos WHERE doc_idviene='$id_p' AND doc_tabla='$tabla' AND doc_version=$version ORDER BY doc_fecha DESC ";
		$sql1="SELECT doc_ruta,iddocumentos,doc_fecha FROM documentos WHERE doc_idviene='$id_p' AND doc_tabla='$tabla' AND doc_version=$version ORDER BY iddocumentos DESC LIMIT 1 ";
		
		
		$DB1->Execute($sql1);
                
                $rw1=mysqli_fetch_row($DB1->Consulta_ID);
		$ruta=$rw1[0];
                
		if($ruta!=""){ 
                    $imag=$DB1->selimagen($ruta);

                    if($imag!=""){ 

                        // Verificar fecha para imagen
                        if ($tabla === 'cajamenor' || $tabla === 'gastos') {
                            $fecha_inicio_mes = strtotime(date("01-m-Y 00:00:00", time()));
                            $fecha_image_entrada = strtotime($rw1[2]);

                            if($fecha_image_entrada >= $fecha_inicio_mes) {
                                $urlDocumento = $rw1[0];
                            } else {
                                $urlDocumento = VISUALIZADOR_IMAGENES . '?tipo=documentos&id=' . $rw1[1];
                            }
                        } else {
                            $urlDocumento = $ruta;
                        }

                        $imagen.="<a href='{$urlDocumento}' target='_blank'>{$valor}</a>";

                    }else{ $imagen.=$valor; }
		}else{
			$imagen.=$valor; 
		}
		$imagen.="";
		return $imagen;
	}	
	function llenadocsimagen($DB1, $tabla, $id_p, $version, $tam, $valor)
	{
		$imagen="<td align='center' >";
		$sql1="SELECT doc_ruta FROM documentos WHERE doc_idviene='$id_p' AND doc_tabla='$tabla' AND doc_version=$version ORDER BY doc_fecha DESC ";
		$DB1->Execute($sql1);
		$ruta=$DB1->recogedato(0);
		if($ruta!=""){ 
		
			if(file_exists($ruta)) {
		
				$imagen.="<a href='$ruta' target='_blank'>&nbsp;<img src='$ruta' width='$tam'>$valor</a></td>";
			
			}else{ $imagen.='Sin Archivo'; }
		}else{
			$imagen.='Sin Archivo'; 
		}
		$imagen.="</td>";
		return $imagen;
	}
	/* Llena el t�tulo de los encabezados de la tabla, permite ingresar campo por el cual se quiere que se ordene y el orden ascendente o descendente */
	function llenatabla($sql, $cu, $nom, $p1, $numero, $plata, $link, $imagen, $porc, $desc, $sin, $DB, $DB1)
	{
		$DB->Execute($sql);
		$va=0;
		$p2=$p1;
		while($rw=mysqli_fetch_row($DB->Consulta_ID))
		{
			$va++;
			$p=$va%2;
			$id_p=$rw[0];
			if($p==0){$color="#FFFFFF";}
			else{$color="#EFEFEF";}
			echo "<tr bgcolor='$color' class='text' onmouseover='this.style.backgroundColor=\"#C8C6F9\"' onmouseout='this.style.backgroundColor=\"$color\"'>";
			for ($i=1; $i<$cu; $i++)
			{
				$cond1=0;
				$corte=explode(",",$numero);
				for($a=0;$a<count($corte);$a++){
					if($corte[$a]==$i){	$cond2="align='right'"; break;}
					else{$cond2="";}
				}
				$corte=explode(",",$plata);
				for($a=0;$a<count($corte);$a++){
					if($corte[$a]==$i){	$cond1=1; break;}
					else{$cond1=0;}
				}
				$corte=explode(",",$link);
				for($a=0;$a<count($corte);$a++){
					if($corte[$a]==$i){	$cond1=2;}
					else{}
				}
				$corte=explode(",",$imagen);
				for($a=0;$a<count($corte);$a++){
					if($corte[$a]==$i){	$cond1=3;}
					else{}
				}
				$corte=explode(",",$porc);
				for($a=0;$a<count($corte);$a++){
					if($corte[$a]==$i){	$cond1=4;}
					else{}
				}
				$corte=explode(",",$desc);
				for($a=0;$a<count($corte);$a++){
					if($corte[$a]==$i){	$cond1=5;}
					else{}
				}
				if($cond1==1){ 
				echo "<td align='right'>$".number_format($rw[$i],0,".",".")."</td>";	}
				else if($cond1==2){ 
					$dd=explode("@",$rw[$i]);
					if(isset($dd[1])){ echo "<td align='center'><b><a href='mailto:".$rw[$i]."'><span class='a1'>".$rw[$i]."</span></b></a></td>"; }
					else { 
						if(strlen($dd[0])==1) { if($dd[0]==1) { echo "<td align='center'>Si</td>"; } else { 
						echo "<td align='center'>No</td>"; }  } 
						else { echo "<td align='center'><b><a href='http://$rw[$i]' target='_blank'><span class='a1'>".$rw[$i]."</span></a></b></td>"; } 
					}
				}
				else if($cond1==3){ 
					echo "<td align='center'>";
					$sql1="SELECT doc_ruta FROM documentos WHERE doc_idviene='$id_p' AND doc_tabla='$nom' ORDER BY doc_fecha DESC ";
					$DB1->Execute($sql1);
					$ruta=$DB1->recogedato(0);
					if($rw[$i]=="img"){ echo "<a href='$ruta' target='_blank'><img src='$ruta' width='50'></a></td>"; }
					else {
						$imag=$DB1->selimagen($ruta);
						if($imag!=""){ echo "<a href='$ruta'><img src='$imag' width='20'></a></td>";}
					}
				}
				else if($cond1==4){echo "<td align='right'>".number_format($rw[$i],2,".",",")."%</td>";}
				else if($cond1==5){ 
					echo "<td align='center'><a href='$rw[$i]'>"; 
					$imag=$this->selimagen($rw[$i]);
					echo "<img src='$imag'>";
					echo "</a></td>";	
				}
				else {echo "<td $cond2>".$rw[$i]."</td>";}
			}
			if($imagen!=""){ 
				echo "<td align='center'>";
				$sql1="SELECT doc_ruta FROM documentos WHERE doc_idviene='$id_p' AND doc_tabla='$nom' ORDER BY doc_fecha DESC ";
				$DB1->Execute($sql1);
				$ruta=$DB1->recogedato(0);
				if($rw[$i]=="img"){ echo "<a href='$ruta' target='_blank'><img src='$ruta' width='50'></a></td>"; }
				else {
					$imag=$DB1->selimagen($ruta);
					if($imag!=""){ echo "<a href='$ruta'><img src='$imag' width='20'></a></td>";}
				}
				$p1=$p2.'&imagenes='.$imagen;
			}
			$DB->edites($id_p, $nom, $sin, $p1);
			echo "</tr>";
		}
		echo "<tr><td colspan='4' class='text'>&nbsp;&nbsp;<b>Total registros: ".$va."</b></td></tr></table>";
	}	

	function llenatabla_pop($sql, $cu, $nom, $p1, $numero, $plata, $link, $imagen, $porc, $desc, $sin, $DB, $DB1, $ajx)
	{
		$DB->Execute($sql);
		$va=0;
		while($rw=mysqli_fetch_row($DB->Consulta_ID))
		{
			$va++;
			$p=$va%2;
			$id_p=$rw[0];
			if($p==0){$color="#FFFFFF";}
			else{$color="#EFEFEF";}
			echo "<tr bgcolor='$color' class='text' onmouseover='this.style.backgroundColor=\"#C8C6F9\"' onmouseout='this.style.backgroundColor=\"$color\"'>";
			for ($i=1; $i<$cu; $i++)
			{
				$cond1=0;
				$corte=explode(",",$numero);
				for($a=0;$a<count($corte);$a++){
					if($corte[$a]==$i){	$cond2="align='right'"; break;}
					else{$cond2="";}
				}
				$corte=explode(",",$plata);
				for($a=0;$a<count($corte);$a++){
					if($corte[$a]==$i){	$cond1=1; break;}
					else{$cond1=0;}
				}
				$corte=explode(",",$link);
				for($a=0;$a<count($corte);$a++){
					if($corte[$a]==$i){	$cond1=2;}
					else{}
				}
				$corte=explode(",",$imagen);
				for($a=0;$a<count($corte);$a++){
					if($corte[$a]==$i){	$cond1=3;}
					else{}
				}
				$corte=explode(",",$porc);
				for($a=0;$a<count($corte);$a++){
					if($corte[$a]==$i){	$cond1=4;}
					else{}
				}
				$corte=explode(",",$desc);
				for($a=0;$a<count($corte);$a++){
					if($corte[$a]==$i){	$cond1=5;}
					else{}
				}
				if($cond1==1){ 
				echo "<td align='right'>$".number_format($rw[$i],0,".",".")."</td>";	}
				else if($cond1==2){ 
					$dd=explode("@",$rw[$i]);
					if(isset($dd[1])){ echo "<td align='center'><b><a href='mailto:".$rw[$i]."'><span class='a1'>".$rw[$i]."</span></b></a></td>"; }
					else { 
						if(strlen($dd[0])==1) { if($dd[0]==1) { echo "<td align='center'>Si</td>"; } else { 
						echo "<td align='center'>No</td>"; }  } 
						else { echo "<td align='center'><b><a href='http://$rw[$i]' target='_blank'><span class='a1'>".$rw[$i]."</span></a></b></td>"; } 
					}
				}
				else if($cond1==3){ 
					echo "<td align='center'>";
					$sql1="SELECT doc_ruta FROM documentos WHERE doc_idviene='$id_p' AND doc_tabla='$nom' ORDER BY doc_fecha DESC ";
					$DB1->Execute($sql1);
					$ruta=$DB1->recogedato(0);
					if($rw[$i]=="img"){ echo "<a href='$ruta' target='_blank'><img src='$ruta' width='50'></a></td>"; }
					else {
						$imag=$DB1->selimagen($ruta);
						if($imag!=""){ echo "<a href='$ruta'><img src='$imag' width='20'></a></td>";}
					}
				}
				else if($cond1==4){echo "<td align='right'>".number_format($rw[$i],2,".",",")."%</td>";}
				else if($cond1==5){ 
					echo "<td align='center'><a href='$rw[$i]'>"; 
					$imag=$this->selimagen($rw[$i]);
					echo "<img src='$imag'>";
					echo "</a></td>";	
				}
				else {
					echo "<td $cond2 onClick='pop_ver(\"$id_p\", \"$nom\");'>";
					if($ajx==1){
						echo utf8_encode($rw[$i]);} else {echo $rw[$i]; } 
						echo "</td>";
					}
			}
			$DB->edites($id_p, $nom, $sin, $p1);
			echo "</tr>";
		}
	}	
	
		function llenatabla_edit($sql, $cu, $nom, $p1, $numero, $plata, $link, $imagen, $porc, $desc, $sin, $DB, $DB1,$url)
	{
		$DB->Execute($sql);
		$va=0;
		while($rw=mysqli_fetch_row($DB->Consulta_ID))
		{
			$va++;
			$p=$va%2;
			$id_p=$rw[0];
			if($p==0){$color="#FFFFFF";}
			else{$color="#EFEFEF";}
			echo "<tr bgcolor='$color' class='text' onmouseover='this.style.backgroundColor=\"#C8C6F9\"' onmouseout='this.style.backgroundColor=\"$color\"'>";
			for ($i=1; $i<$cu; $i++)
			{
				$cond1=0;
				$corte=explode(",",$numero);
				for($a=0;$a<count($corte);$a++){
					if($corte[$a]==$i){	$cond2="align='right'"; break;}
					else{$cond2="";}
				}
				$corte=explode(",",$plata);
				for($a=0;$a<count($corte);$a++){
					if($corte[$a]==$i){	$cond1=1; break;}
					else{$cond1=0;}
				}
				$corte=explode(",",$link);
				for($a=0;$a<count($corte);$a++){
					if($corte[$a]==$i){	$cond1=2;}
					else{}
				}
				$corte=explode(",",$imagen);
				for($a=0;$a<count($corte);$a++){
					if($corte[$a]==$i){	$cond1=3;}
					else{}
				}
				$corte=explode(",",$porc);
				for($a=0;$a<count($corte);$a++){
					if($corte[$a]==$i){	$cond1=4;}
					else{}
				}
				$corte=explode(",",$desc);
				for($a=0;$a<count($corte);$a++){
					if($corte[$a]==$i){	$cond1=5;}
					else{}
				}
				if($cond1==1){ 
				echo "<td align='right'>$".number_format($rw[$i],0,".",".")."</td>";	}
				else if($cond1==2){ 
					$dd=explode("@",$rw[$i]);
					if(isset($dd[1])){ echo "<td align='center'><b><a href='mailto:".$rw[$i]."'><span class='a1'>".$rw[$i]."</span></b></a></td>"; }
					else { 
						if(strlen($dd[0])==1) { if($dd[0]==1) { echo "<td align='center'>Si</td>"; } else { 
						echo "<td align='center'>No</td>"; }  } 
						else { echo "<td align='center'><b><a href='http://$rw[$i]' target='_blank'><span class='a1'>".$rw[$i]."</span></a></b></td>"; } 
					}
				}
				else if($cond1==3){ 
					echo "<td align='center'>";
					$sql1="SELECT doc_ruta FROM documentos WHERE doc_idviene='$id_p' AND doc_tabla='$nom' ORDER BY doc_fecha DESC ";
					$DB1->Execute($sql1);
					$ruta=$DB1->recogedato(0);
					if($rw[$i]=="img"){ echo "<a href='$ruta' target='_blank'><img src='$ruta' width='50'></a></td>"; }
					else {
						$imag=$DB1->selimagen($ruta);
						if($imag!=""){ echo "<a href='$ruta'><img src='$imag' width='20'></a></td>";}
					}
				}
				else if($cond1==4){echo "<td align='right'>".number_format($rw[$i],2,".",",")."%</td>";}
				else if($cond1==5){ 
					echo "<td align='center'><a href='$rw[$i]'>"; 
					$imag=$this->selimagen($rw[$i]);
					echo "<img src='$imag'>";
					echo "</a></td>";	
				}
				else {echo "<td $cond2>".$rw[$i]."</td>";}
			}
		
			$DB->editar($url,$id_p, $nom, $sin, $p1);
			echo "</tr>";
		}
		echo "<tr><td colspan='4' class='text'>&nbsp;&nbsp;<b>Total registros: ".$va."</b></td></tr></table>";
	}	
	
	
	function AddDocumento($id_param, $tabla, $i, $DB)
	{			
		foreach($_FILES as $nom => $val)
		{
			$nomb=nombre_archivo($val);
			$ruta=subir_archivo1($val);
			if($nomb!=""){
				$sql_ins="INSERT INTO documentos (iddocumentos, doc_fecha, doc_nombre, doc_ruta, doc_tabla, doc_idviene, doc_version) VALUES 
				('', '".date("Y-m-d")."', '$nomb', '$ruta', '$tabla', '$id_param', '1')";
				$DB->Execute($sql_ins);
			}
			$i++;
		}
	}
}
?>