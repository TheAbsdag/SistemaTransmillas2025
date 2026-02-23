<?php
require("login_autentica.php");

$DB1 = new DB_mssql; $DB1->conectar();
$DB  = new DB_mssql; $DB->conectar();

$id_sedes     = $_SESSION['usu_idsede'];
$nivel_acceso = $_SESSION['usuario_rol'];
$tipo         = $_POST["tipo"];

date_default_timezone_set('America/Bogota');
$fechaactual = date('Y-m-d');
$inicioDia   = $fechaactual . " 00:00:00";
$finDia      = $fechaactual . " 23:59:59";

if($tipo==1){

    $numerocomfirmar = 0;
    $gatoscomfirmar  = 0;
    $remesascomfirmar= 0;
    $cajasciudades   = 0;
    $seguimiento     = 0;
    $pendientes      = 0;
    $faltantes       = 0;
    $alertassede     = 0;

    /* ================= GASTOS ADMIN ================= */
    if($nivel_acceso==1){

        $DB1->Execute("SELECT COUNT(*) FROM cajamenor WHERE caj_usucom=''");
        $numerocomfirmar = mysqli_fetch_row($DB1->Consulta_ID)[0];

        $DB1->Execute("SELECT COUNT(*) FROM asignaciondinero WHERE asi_usercom IS NULL AND asi_tipo='Gastos'");
        $gatoscomfirmar = mysqli_fetch_row($DB1->Consulta_ID)[0];

        $DB1->Execute("SELECT COUNT(*) FROM gastos WHERE gas_usucom='' AND gas_cantcom=''");
        $remesascomfirmar = mysqli_fetch_row($DB1->Consulta_ID)[0];

        // 🔥 OPTIMIZADO: NOT IN → LEFT JOIN
        $sql = "SELECT COUNT(s.idsedes)
                FROM sedes s
                LEFT JOIN cuentassede c
                  ON c.cus_idsede = s.idsedes
                  AND c.cus_fecha BETWEEN '$inicioDia' AND '$finDia'
                WHERE s.sed_principal='si' AND c.cus_idsede IS NULL";
        $DB1->Execute($sql);
        $cajasciudades = mysqli_fetch_row($DB1->Consulta_ID)[0];

        // 🔥 LIKE → rango de fecha
        $sql = "SELECT COUNT(*) FROM servicios
                WHERE ser_fechafinal BETWEEN '$inicioDia' AND '$finDia'
                AND ser_estado IN (4,6)
                AND ser_idverificadopeso != 1";
        $DB1->Execute($sql);
        $pendientes = mysqli_fetch_row($DB1->Consulta_ID)[0];
    }

    /* ================= CIERRE CAJA SOLO ADMIN SEDE ================= */
    if($nivel_acceso==10){
        $sql = "SELECT COUNT(s.idsedes)
                FROM sedes s
                LEFT JOIN cuentassede c
                  ON c.cus_idsede = s.idsedes
                  AND c.cus_fecha BETWEEN '$inicioDia' AND '$finDia'
                WHERE s.sed_principal='si' AND c.cus_idsede IS NULL";
        $DB1->Execute($sql);
        $cajasciudades = mysqli_fetch_row($DB1->Consulta_ID)[0];
    }

    /* ================= ALERTAS SEDE ================= */
    if($nivel_acceso==10 || $nivel_acceso==12){
        $sql="SELECT COUNT(*)
              FROM reportealertas r
              WHERE r.rep_idsede = $id_sedes
              AND r.rep_fechavencimiento <= '$finDia'";
        $DB1->Execute($sql);
        $alertassede = mysqli_fetch_row($DB1->Consulta_ID)[0];
    }

    /* ================= FALTANTES ================= */
    if($nivel_acceso==9 || $nivel_acceso==10 || $nivel_acceso==1){
        $fechaLimite = date('Y-m-d', strtotime('-3 days'));

        $sql="SELECT COUNT(*)
              FROM serviciosdia
              WHERE (ser_estado IN (7) OR (ser_estado > 4 AND ser_estado < 7))
              AND ser_llego != 'SI'
              AND ser_fechafinal BETWEEN '2025-01-01 00:00:00' AND '$fechaLimite 23:59:59'";
        $DB1->Execute($sql);
        $faltantes = mysqli_fetch_row($DB1->Consulta_ID)[0];
    }

    /* ================= PENDIENTES POR SEDE ================= */
    if($nivel_acceso==2 || $nivel_acceso==12 || $nivel_acceso==5){
        $sql="SELECT COUNT(*)
              FROM servicios s
              INNER JOIN usuarios u ON u.idusuarios=s.ser_idresponsable
              WHERE s.ser_fechafinal BETWEEN '$inicioDia' AND '$finDia'
              AND s.ser_estado IN (4,6)
              AND s.ser_idverificadopeso!=1
              AND u.usu_idsede=$id_sedes";
        $DB1->Execute($sql);
        $pendientes = mysqli_fetch_row($DB1->Consulta_ID)[0];
    }

    /* ================= SEGUIMIENTO ================= */
    if($nivel_acceso==1 || $nivel_acceso==2){
        $consda = ($nivel_acceso==2) ? "AND usu_idsede=$id_sedes" : "";

        // 🔥 EVITAMOS UNION + PHP LOOP
        $sql="SELECT COUNT(*)
              FROM usuarios u
              WHERE u.usu_estado=1
              AND u.roles_idroles=3
              $consda
              AND NOT EXISTS (
                    SELECT 1 FROM servicios s
                    WHERE (s.ser_estado IN (3,9))
                    AND (
                        s.ser_idresponsable=u.idusuarios
                        OR s.ser_idusuarioguia=u.idusuarios
                    )
                    AND s.ser_fechaasignacion BETWEEN '$inicioDia' AND '$finDia'
              )";
        $DB1->Execute($sql);
        $seguimiento = mysqli_fetch_row($DB1->Consulta_ID)[0];
    }

    $datos=array(
        "gastossede"     => $numerocomfirmar,
        "gastosoperador" => $gatoscomfirmar,
        "gastosremesas"  => $remesascomfirmar,
        "cierrecaja"     => $cajasciudades,
        "seguimiento"    => $seguimiento,
        "pendientes"     => $pendientes,
        "faltantes"      => $faltantes,
        "alertassede"    => $alertassede
    );
}

header('Content-Type: application/json');
echo json_encode($datos);
?>