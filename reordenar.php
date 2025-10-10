<?php
$fechaactual = date("Y-m-d");
$id_usuario = $_SESSION['usuario_id'];

?>
<!DOCTYPE html>
<html lang="es">

<head>
    <style>
        #sortable {
            list-style-type: none;
            margin: 0;
            padding: 0;
            width: 60%;
        }

        #sortable li {
            margin: 0 3px 3px 3px;
            padding: 0.4em;
            padding-left: 1.5em;
            font-size: 1.4em;
            height: 18px;
        }

        .alertper {
            padding: 1px;
            margin-bottom: 2px;
            font-size: 20px;
            border: 1px solid transparent;
            border-radius: 4px;
            color: #444;
        }

        .container {
            display: flex;
            flex-flow: row wrap-reverse
        }

        .container>* {
            width: 100%
        }

        .numeros {
            font-size: 2rem;
            color: #263fbf;
        }

        .hora {
            font-size: 1rem;
            color: #263fbf;
        }

        .orden {
            font-size: 1rem;
            color: #d71930;
        }
        #toggleSortable {
            padding: 10px 15px;
            border: none;
            color: white;
            font-size: 16px;
            cursor: pointer;
            border-radius: 5px;
            transition: background 0.3s;
        }
        .activo {
            background-color: green;
        }
        .inactivo {
            background-color: red;
        }
        .orden-boton {
        display: inline-block;
        padding: 4px 10px;
        background-color: #17a2b8; /* celeste/azul suave */
        color: white;
        font-size: 13px;
        font-weight: 600;
        border-radius: 20px; /* tipo pastilla */
        }
    </style>
   


        <script>
            // window.onload = function() {
            //     if (!window.location.href.includes("nocache")) {
            //         window.location.href = window.location.href + "?nocache=" + new Date().getTime();
            //     }
            // };

            function cambio_sede(value1){

                var url = "reordenarguias.php?sede=" + encodeURIComponent(value1);
                window.location.href = url;
            }

    </script>
</head>

<body>

    <div class="container">
        <div class="uno">
            <?php
            $FB->nuevo("diligencia", $condecion, "");
            ?>
            <button id="toggleSortable" class="inactivo">Inactivo</button>
            <table class="table table-hover">
                <tr bgcolor="#074F91" class="tittle3">

                    <td colspan=2 align='center'>Enrutamiento Entregas y Recogidas</td>
                </tr>

                <?php
                if ($nivel_acceso == 1 or $nivel_acceso == 12 or $nivel_acceso == 10 or $nivel_acceso == 2 or $nivel_acceso == 5) {
                    if ($param33 != '') {
                        $id_usuario = $param33;
                    }

                    //  $id_usuario=14;   
                    //   $fechaactual='2021-11-02';
                    if(isset($_GET['sede'])){

                        $id_sedes=$_GET['sede'];
                    }
	                $FB->llena_texto("Sede :",35,2,$DB,"(SELECT `idsedes`,`sed_nombre` FROM sedes where idsedes>0 )", "cambio_sede(this.value)", "$id_sedes", 4, 0);

                    $sqlo = "SELECT `idusuarios`,concat_ws(' ',usu_nombre,'--',zon_nombre) as nombre FROM  seguimiento_user inner join zonatrabajo on seg_idzona=idzonatrabajo  inner join  `usuarios` on idusuarios=seg_idusuario inner join sedes on idsedes=usu_idsede WHERE `roles_idroles` in (2,3,5) and seg_fechaalcohol='$fechaactual' and (usu_estado=1 or usu_filtro=1)  and `seg_motivo`='Ingreso' and usu_idsede = '$id_sedes' order by usu_nombre ";
                    $FB->llena_texto("Operario:", 33, 2, $DB, "$sqlo", "cambio22(this.value,\"reordenarguias.php\",\"ordernar\",\"param33\")", "$param33", 1, 0);
                }
                $compañero="SELECT seg_compañero from seguimiento_user where seg_fechaalcohol like '$fechaactual%'  and seg_idusuario='$id_usuario'  ";
                    $DB1->Execute($compañero); 
                    $rwcom=mysqli_fetch_row($DB1->Consulta_ID);
                    echo$compa=$rwcom[0];
                ?>

            </table>
        </div>
    </div>
    <?php

    $sles4 = "SELECT max(orden) from ord_recoentregas WHERE orden_iduserencargado =$id_usuario and orden_fechadiaejecucion='$fechaactual'";
    $DB_m1->Execute($sles4);
    $ordenn = $DB_m1->recogedato(0);

    echo "<tr>";
    if ($ordenn == '' or $ordenn == 0) {
        $ordenultimo = 1;
    } else {
        $ordenultimo = $ordenn + 1;
    }

    $conde3 = "and ((ser_idresponsable='$id_usuario' and ser_fechaasignacion like '$fechaactual%' and ser_estado=3 ) or ( ser_idusuarioguia='$id_usuario' and ser_fechaguia like '$fechaactual%' and ser_estado=9))";
    $conde1 = "";
    //entregas y recogidas asignadas al usuario

 $sql = "SELECT `idservicios`,`ser_fechaasignacion`,ser_estado,cli_direccion,ser_direccioncontacto
 FROM serviciosdia inner join usuarios on ser_idresponsable=idusuarios where ser_estado in (3,9) 
 and idservicios not in (SELECT  orden_idservicio FROM ord_recoentregas WHERE orden_iduserencargado='$id_usuario' 
 and orden_fechadiaejecucion='$fechaactual' and ord_tipo in ('Recogida','Entrega'))  $conde3  ORDER BY ser_fechaentrega $asc ";

    $DB->Execute($sql);
    $va = 0;
    $cont = 0;
    while ($rw1 = mysqli_fetch_row($DB->Consulta_ID)) {
        $estado = $rw1[2];
        if ($estado == 3) {

            $tipo = 'Recogida';
            $dir = str_replace("&", " ", $rw1[3]);
            $dir2 = str_replace("&", " ", $rw1[3]);
           
    
            $hora = '';
         
        } else {
            $tipo = 'Entrega';
            $dir = str_replace("&", " ", $rw1[4]);
            $dir2 = str_replace("&", " ", $rw1[4]);
       
        }

        $sql1 = "INSERT INTO `ord_recoentregas`(`orden_idservicio`, `orden_iduserencargado`,  `orden_fecha`, `orden` , `orden_fechadiaejecucion`,`ord_tipo`,`ord_direccion`) VALUES ('$rw1[0]','$id_usuario','$rw1[1]','$ordenultimo','$fechaactual','$tipo','$dir2')";
        $DB1->Execute($sql1);
        $ordenultimo++;
        $cont++;
    }

    if($cont>0){
    echo '<div class="alert alert-danger" role="alert">';
    echo '<strong>Por Favor Enrute sus Guias.</strong>';
    echo '</div>';
    }

    //remesas del usuaio asignado.

    $conde22 = " and ((gas_iduserrecoge='$id_usuario' and gas_recogio=0 ) or (gas_iduserremesa='$id_usuario' and gas_entrego=0))";
    $conde11 = "and ((date(gas_fecharegistro)<='$fechaactual' and gas_usucom<=0 ) OR (date(gas_feccom)>='$fechaactual' ))";

   // $fecha = date("Y-m-d", strtotime($fecha_actual . "- 10 days"));
    $fecha = date("Y-m-d", strtotime($fecha_actual));

    $sql7 = "SELECT  orden_id FROM ord_recoentregas WHERE orden_iduserencargado='$id_usuario' and  orden_fechadiaejecucion>='$fecha' and orden_fechadiaejecucion<'$fechaactual'  and ord_tipo='Remesa'";
    $DB1->Execute($sql7);
    while ($rw21 = mysqli_fetch_row($DB1->Consulta_ID)) {
        $idelimi = $rw21[0];
        $sql7 = "Delete from ord_recoentregas WHERE  orden_id=$idelimi";
        $DB_m2->Execute($sql7);
    }

    $sql = "SELECT `idgastos`, `gas_fecharegistro`,gas_descripcion
   FROM `gastos` inner join usuarios on gas_idusuario=idusuarios inner join sedes on idsedes=gas_idciudaddes
    WHERE idgastos>0   and idgastos  not in (SELECT  orden_idservicio FROM ord_recoentregas WHERE orden_iduserencargado='$id_usuario' and orden_fechadiaejecucion >= '$fechaactual'  and ord_tipo='Remesa') $conde11 $conde22 and (gas_fecharegistro>='$fechaactual' or gas_fecrecogida>='$fechaactual') ORDER BY gas_fecharegistro desc";

    $DB1->Execute($sql);
    while ($rw2 = mysqli_fetch_row($DB1->Consulta_ID)) {
        $tipo = 'Remesa';
        $sql1 = "INSERT INTO `ord_recoentregas`(`orden_idservicio`, `orden_iduserencargado`,  `orden_fecha`, `orden` , `orden_fechadiaejecucion`,`ord_tipo`,`ord_direccion`) VALUES ('$rw2[0]','$id_usuario','$rw2[1]','$ordenultimo','$fechaactual','$tipo','$rw2[2]')";
        $DB_m2->Execute($sql1);
        $ordenultimo++;
    }

    $sql3 = "SELECT `iddiligencias`,`dil_ruta`, `dil_user`, `dil_fecha`, `dil_estado`
    FROM `diligencias` 
    WHERE dil_user='$id_usuario'  and dil_fecha like '$fechaactual%' and `dil_estado`='Activo' and iddiligencias not in (SELECT  orden_idservicio FROM ord_recoentregas WHERE orden_iduserencargado='$id_usuario' and orden_fechadiaejecucion='$fechaactual' and ord_tipo='diligencia' )  ORDER BY dil_fecha desc";

    $DB1->Execute($sql3);
    while ($rw3 = mysqli_fetch_row($DB1->Consulta_ID)) {
        $tipo = 'diligencia';
        $sql1 = "INSERT INTO `ord_recoentregas`(`orden_idservicio`, `orden_iduserencargado`,  `orden_fecha`, `orden` , `orden_fechadiaejecucion`,`ord_tipo`,`ord_direccion`) VALUES ('$rw3[0]','$id_usuario','$rw3[3]','$ordenultimo','$fechaactual','$tipo','$rw3[1]')";
        $DB_m2->Execute($sql1);
        $ordenultimo++;
    }

    $Querydrag_drop = ("SELECT  orden_idservicio,orden_iduserencargado,orden_id,orden,ord_estado,ord_tipo, ser_consecutivo, ser_piezas FROM ord_recoentregas INNER JOIN servicios on idservicios=orden_idservicio WHERE orden_iduserencargado =$id_usuario and orden_fechadiaejecucion ='$fechaactual' order by orden asc ");
    $DB->Execute($Querydrag_drop);

    // if ($nivel_acceso==1) {
    //      echo$Querydrag_drop;
    // }
    ?>


    <div class="dos sortable" id="drop-items">

        <?php

        $orden1 = 0;
        while ($dataDrag_Drop = mysqli_fetch_assoc($DB->Consulta_ID)) {

            $imprimir = 0;
            $dataDrag_Drop['ord_tipo'];
            $idservicio = $dataDrag_Drop['orden_idservicio'];
            if ($dataDrag_Drop['ord_tipo'] == 'Recogida' or $dataDrag_Drop['ord_tipo'] == 'Entrega') {

                $sql6 = "SELECT `idservicios`,`ser_fechaentrega`,`cli_nombre`,`cli_telefono`,`cli_direccion`, `ser_destinatario`, `ser_telefonocontacto`,
            `ser_direccioncontacto`,`ciu_nombre`,`ser_prioridad`,ser_estado,ser_visto,usu_nombre,`ser_fechaasignacion`,`ser_consecutivo`,ser_valorprestamo,ser_guiare,cli_idciudad
            FROM serviciosdia inner join usuarios on ser_idresponsable=idusuarios   where
            ser_estado in (3,9) and  idservicios='$idservicio' $conde3 ";
            } elseif ($dataDrag_Drop['ord_tipo'] == 'Remesa') {


                $sql6 = "SELECT `idgastos`, `gas_fecharegistro`, `usu_nombre`, `gas_idciudadori`, `sed_nombre`, `gas_empresa`, `gas_bus`, `gas_telconductor`,`gas_pagar`,`gas_iduserremesa`,
                `gas_nomremesa`,`gas_descripcion`,`gas_peso`,`gas_piezas`,`gas_valor`,gas_usucom, gas_cantcom,gas_feccom ,gas_idciudaddes,gas_iduserrecoge,gas_recogio,gas_entrego,gas_fecrecogida,'2000' as ser_estado 
                FROM `gastos` inner join usuarios on gas_idusuario=idusuarios inner join sedes on idsedes=gas_idciudaddes
                WHERE idgastos='$idservicio' $conde11 $conde22";
            } elseif ($dataDrag_Drop['ord_tipo'] == 'diligencia') {

                $sql6 = "SELECT iddiligencias,`dil_ruta`, `dil_user`, `dil_fecha`, `dil_estado`,'2001' as ser_estado from diligencias where  iddiligencias=$idservicio and `dil_estado`='Activo'";
            }


            $orden1++;
            $DB_m2->Execute($sql6);
            $datosfinales = mysqli_fetch_assoc($DB_m2->Consulta_ID);

            $estados = $datosfinales['ser_estado'];
            if ($estados != '') {

                if ($dataDrag_Drop['ord_tipo'] == 'Recogida') {
                    $oriidciudad = $datosfinales['cli_idciudad'];
                    $sqls1 = "SELECT ciu_nombre FROM ciudades WHERE idciudades=$oriidciudad";
                    $DB1->Execute($sqls1);
                    $ciudad = $DB1->recogedato(0);
                } else {
                    $ciudad = $datosfinales['ciu_nombre'];
                }


                if ($estados == 3) {
                    $hora = $datosfinales['ser_fechaentrega'];
                    $dir = str_replace("&", " ", $datosfinales['cli_direccion']);
                    $dir2 = str_replace("&", "+", $datosfinales['cli_direccion']);
                    $dir2 = str_replace("#", "+", $dir2);
                } elseif ($estados == 9) { //cambiar a 9
                    $hora = '';
                    $dir = str_replace("&", " ", $datosfinales['ser_direccioncontacto']);
                    $dir2 = str_replace("&", "+", $datosfinales['ser_direccioncontacto']);
                    $dir2 = str_replace("#", "+", $dir2);
                } elseif ($estados == '2000') {
                    $hora = '';
                    $dir = "Empresa TR: " . $datosfinales['gas_empresa'] . " -
                    # BUS:" . $datosfinales['gas_bus'] . "-
                    Tel Conductor:" . $datosfinales['gas_telconductor'];
                } elseif ($estados == '2001') {
                    $hora = '';
                    $dir = "Ruta: " . $datosfinales['dil_ruta'];
                }

                if ($dataDrag_Drop['ord_estado'] == 'Sin Ordenar') {

                    $clasesse = 'alertper alert-danger';
                } else {
                    $clasesse = 'alertper alert-success';
                }


        ?>



                <div id="<?php echo $dataDrag_Drop['orden_id']; ?>" class="<?php echo $clasesse; ?>" data-index="<?php echo $dataDrag_Drop['orden_id']; ?>" data-position="<?php echo $orden1; ?>">
                    <li class="ui-state-default"><?php echo "<span class='numeros'>$orden1</span>";
                                                    if ($dataDrag_Drop['ord_tipo'] == 'Entrega') {
                                                        echo "<span class='orden-boton'>" . $dataDrag_Drop['ser_consecutivo']. " / Piezas".$dataDrag_Drop['ser_piezas']."</span>";
                                                    }
                                                    echo "<span class='hora'>" . $dataDrag_Drop['ord_tipo'] . $hora . "</span>";
                                                    echo "<span class='orden'> " . $dataDrag_Drop['ord_estado'] . "</span>";
                                                    echo $dir;
                                                    $destino = urlencode($dir2.$ciudad."colombia"); // Dirección destino
                                                    $url = "https://www.google.com/maps/dir/?api=1&destination=$destino&travelmode=driving";
                                                    // echo "<span class='mapa'><a href='https://www.google.com/maps/place/$dir2+$ciudad+colombia'  target='_blank'><img src='img/mapa.png' class='iconmap'></a> </span>"  
                                                    echo "<span class='mapa'><a href='$url'  target='_blank'>📍ir</a> </span>"  ?></li>
                                                    
                                                    </li>
                                                    </div><?php

?>


        <?php } else {
                $idelimi = $dataDrag_Drop['orden_id'];
                $sql7 = "Delete from ord_recoentregas WHERE  orden_id=$idelimi";
                $DB_m2->Execute($sql7);
            }
        }
        echo "</tr></table>";
        ?>



    </div>

    <script type="text/javascript" charset="utf-8" src="js/jquery-3.6.3.min.js"></script>
    <script type="text/javascript" charset="utf-8" src="js/jquery-ui.min.js"></script>
    <script type="text/javascript" charset="utf-8" src="js/jquery.ui.touch-punch.min.js"></script>
    <script language="JavaScript" type="text/javascript" src="js/bootstrap.min.js"></script>
    <script>
        $(function() {
            console.log("inicia");
            $('#sortable').sortable();
            $('#sortable').disableSelection();
        });
    </script>
    <script type="text/javascript">
        $(document).ready(function() {
            console.log("bien");
            $('.sortable').sortable({
             
                update: function(event, ui) {
                    var idx = 0;
                    var end_pos = ui.item.index();
                    console.log("doss");
                    $(this).children().each(function(index) {

                        if (index == end_pos) {
                            console.log($(this).attr('data-index'));
                            idx = $(this).attr('data-index');
                            var elemento = document.getElementById(idx);
                            elemento.className = "alertper alert-success";
                        }

                        if ($(this).attr('data-position') != (index + 1)) {
                            console.log($(this).attr('data-index'));
                            $(this).attr('data-position', (index + 1)).addClass('updated');
                        }
                    });

                    guardandoPosiciones(idx);
                }
            });
        });

        function guardandoPosiciones(pos) {
            var positions = [];
            $('.updated').each(function() {
                positions.push([$(this).attr('data-index'), $(this).attr('data-position')]);
                $(this).removeClass('updated');
            });

            $.ajax({
                url: 'ajaxordenar.php',
                method: 'POST',
                dataType: 'text',
                data: {
                    posinicial: pos,
                    update: 1,
                    positions: positions
                },
                success: function(response) {
                    console.log(response);

                }
            });
        }
    </script>
    <!-- <iframe src="https://sistema.transmillas.com/reordenarguiasCompa.php?param33=<?php echo$compa;?>&tabla=ordernar" frameborder="0" style="width: 100%; height: 500px;"></iframe> -->
        <script>
        $(function () {
            let activo = false; // Estado inicial desactivado

            // Habilita el ordenamiento pero lo desactiva al inicio
            $("#drop-items").sortable().sortable("disable");
            $("#drop-items").disableSelection();

            // Botón para activar/desactivar
            $("#toggleSortable").click(function () {
                if (activo) {
                    $("#drop-items").sortable("enable"); // Desactivar drag & drop
                    $(this).text("Activo").removeClass("inactivo").addClass("activo");
                } else {
                    $("#drop-items").sortable("disable"); // Activar drag & drop
                    $(this).text("Inactivo").removeClass("activo").addClass("inactivo");
                }
                activo = !activo; // Alternar estado
            });
        });
</script>
</body>

</html>