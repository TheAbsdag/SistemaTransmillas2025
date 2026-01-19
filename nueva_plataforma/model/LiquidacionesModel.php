<?php
require_once "../config/database.php";
require_once '../../PHPMailer/src/PHPMailer.php';
require_once '../../PHPMailer/src/SMTP.php';
require_once '../../PHPMailer/src/Exception.php';

// Importar clases
use PHPMailer\PHPMailer\PHPMailer;
use PHPMailer\PHPMailer\Exception;
class LiquidacionesModel{
    private $db;

    public function __construct() {
        $this->db = (new Database())->connect();
    }

     // ✅ Función base: trae la información de la hoja de vida
public function datosHojaDeVida($filtroAno = '', $filtroSede = '', $filtroOperador = '', $filtroEstado = '') {
    date_default_timezone_set('America/Bogota');
    $hoy = date("Y-m-d");
    $conde1 = $conde2 = $conde4 = '';
    $joinEstado = ''; // <- aquí irá el ajuste en el JOIN

    // 🔹 Filtro por operador
    if ($filtroOperador != '') {
        $identificacion = $this->obtenerIdentificacion($filtroOperador);
        $conde1 = "AND hv.hoj_cedula = '$identificacion'";
    }

    // 🔹 Filtro por año
    if ($filtroAno != '') {
        if (date("Y") == $filtroAno) {
            $conde2 = "AND (YEAR(hv.hoj_fechatermino) = $filtroAno OR hv.hoj_fechatermino = '0000-00-00' OR hv.hoj_fechatermino IS NULL)";
        } else {
            $conde2 = "AND YEAR(hv.hoj_fechatermino) = $filtroAno";
        }
    }

    // 🔹 Filtro por sede
    if ($filtroSede != '') {
        $conde4 = "AND hv.hoj_sede = $filtroSede";
    }

    // 🔹 Condición dinámica del estado dentro del JOIN
        if ($filtroEstado === 'Sin liquidar') {
            // 👉 Trae todas las HV aunque no tengan liquidación (LEFT JOIN)
            $joinEstado = "LEFT JOIN liquidaciones l 
                        ON hv.idhojadevida = l.liq_idHv";

            // 👉 Filtro: solo las que no tienen liquidación o tienen confirmación No o vacía
            $condicionEstado = "AND (l.liq_idHv IS NULL OR l.liq_confirma = '' OR l.liq_confirma = 'No')";
            
        } elseif ($filtroEstado === 'Liquidado') {
            // 👉 Solo las que sí tienen liquidación confirmada
            $joinEstado = "INNER JOIN liquidaciones l 
                        ON hv.idhojadevida = l.liq_idHv 
                        AND l.liq_confirma = 'Si'";
            $condicionEstado = "";

        } else {
            // 👉 Si no se filtra por estado, une sin condición
            $joinEstado = "LEFT JOIN liquidaciones l ON hv.idhojadevida = l.liq_idHv";
            $condicionEstado = "";
        }

    // 🔹 Consulta principal
    $sql = "
        SELECT *,
            (dias_transcurridos - IFNULL(dias_noTrabajados, 0)) AS dias_efectivos
        FROM (
            SELECT 
                hv.idhojadevida,  
                CONCAT(hv.hoj_nombre, ' ', hv.hoj_apellido) AS nombre_completo,
                hv.hoj_cargo,
                hv.hoj_tipocontrato,
                hv.hoj_cedula,
                hv.hoj_fechaingreso, 
                s.sed_nombre,
                hv.hoj_fechanacimiento, 
                hv.hoj_direccion, 
                hv.hoj_celular,
                hv.hoj_estado,
                hv.hoj_sede,
                hv.hoj_fechatermino,
                IFNULL(hv.hoj_fechatermino, '$hoy') AS hoj_fechaFinal,
                hv.hoj_cuen,
                hv.hoj_tcuenta,
                hv.hoj_firma,
                hv.hoj_banco,
                hv.hoj_fech_año_act,
                CASE
                    WHEN hv.hoj_fech_año_act IS NULL
                        OR TRIM(hv.hoj_fech_año_act) = ''
                        OR hv.hoj_fech_año_act = '0000-00-00'
                        OR hv.hoj_fech_año_act = '0000-00-00 00:00:00'
                    THEN hv.hoj_fechaingreso
                    ELSE hv.hoj_fech_año_act
                END AS hoj_fechaInicial,
                c.car_cargo,
                c.car_salario,
                c.car_Auxilio,

                CASE
                    WHEN YEAR(IF(hv.hoj_fech_año_act IS NULL OR hv.hoj_fech_año_act = '', hv.hoj_fechaingreso, hv.hoj_fech_año_act)) = YEAR(CURDATE()) THEN 
                        LEAST(DATEDIFF(IFNULL(hv.hoj_fechatermino, '$hoy'), IF(hv.hoj_fech_año_act IS NULL OR hv.hoj_fech_año_act = '', hv.hoj_fechaingreso, hv.hoj_fech_año_act)), 360)
                    ELSE 
                        DATEDIFF(IFNULL(hv.hoj_fechatermino, '$hoy'), IF(hv.hoj_fech_año_act IS NULL OR hv.hoj_fech_año_act = '', hv.hoj_fechaingreso, hv.hoj_fech_año_act))
                END AS dias_transcurridos,

                (
                    SELECT COUNT(*)
                    FROM seguimiento_user su
                    INNER JOIN usuarios u ON u.idusuarios = su.seg_idusuario
                    WHERE su.seg_motivo = 'Vacaciones'
                    AND u.usu_identificacion = hv.hoj_cedula
                    AND su.seg_fechaingreso BETWEEN IFNULL(hv.hoj_fech_año_act, hv.hoj_fechaingreso) AND IFNULL(hv.hoj_fechatermino, '$hoy')
                ) AS dias_vacaciones,

                (
                    SELECT COUNT(*)
                    FROM seguimiento_user su
                    INNER JOIN usuarios u ON u.idusuarios = su.seg_idusuario
                    WHERE su.seg_motivo in ('No trabajo')
                    AND u.usu_identificacion = hv.hoj_cedula
                    AND su.seg_fechaingreso BETWEEN IFNULL(hv.hoj_fech_año_act, hv.hoj_fechaingreso) AND IFNULL(hv.hoj_fechatermino, '$hoy')
                ) AS dias_noTrabajados,
                 (
                    SELECT COUNT(*)
                    FROM seguimiento_user su
                    INNER JOIN usuarios u ON u.idusuarios = su.seg_idusuario
                    WHERE su.seg_motivo in ('Reposicion por falla','Sancionado')
                    AND u.usu_identificacion = hv.hoj_cedula
                    AND su.seg_fechaingreso BETWEEN IFNULL(hv.hoj_fech_año_act, hv.hoj_fechaingreso) AND IFNULL(hv.hoj_fechatermino, '$hoy')
                ) AS dias_sanciones,
                
                (
                    SELECT COUNT(*)
                    FROM primas su
                    INNER JOIN usuarios u ON u.idusuarios = su.pri_idusu
                    WHERE
                    u.usu_identificacion = hv.hoj_cedula
                    AND su.pri_confirma = 'Si'
                    AND DATE(su.pri_fecha_fin) BETWEEN IFNULL(hv.hoj_fech_año_act, hv.hoj_fechaingreso) AND IFNULL(hv.hoj_fechatermino, '$hoy')
                ) AS Primas_pagas
            FROM hojadevida hv
            INNER JOIN sedes s ON hv.hoj_sede = s.idsedes
            INNER JOIN cargo c ON hv.hoj_cargo = c.idcargo
            $joinEstado
            WHERE hv.idhojadevida > 0 
            AND hv.hoj_estado = 'Activo'
            AND hv.hoj_tipocontrato = 'Empresa'
            $conde4 $conde1 $conde2 $condicionEstado
        ) AS sub
        ORDER BY nombre_completo ASC
    ";


    $result = $this->db->query($sql);
    return $result ? $result->fetch_all(MYSQLI_ASSOC) : [];
}


    // ✅ Nueva función: cálculos en PHP
   public function calcularValoresLiquidacion($empleado,$anio) {
        $salario = floatval($empleado['car_salario']);
        $fechaFinalContrato = $empleado['hoj_fechaFinal'];
        $meses31 = $this->diasRestantesMeses31($empleado['hoj_fechaInicial'], $fechaFinalContrato);
        $diasDefechaaFecha = intval($empleado['dias_transcurridos']-$meses31);
        
        $diasEfectivos = intval($empleado['dias_efectivos']-$meses31);
        $auxilio = floatval($empleado['car_Auxilio']);
        $dias_noTrabajados=$empleado['dias_noTrabajados']+$empleado['dias_sanciones'];
        
        
        $cedula = $empleado['hoj_cedula']; // o el campo correcto que identifica al usuario
        $idEmpleado = $this->obtenerIdUser($cedula);
        $diasEfectivosPrimas = "";
        if ($salario <= 0 || $diasEfectivos <= 0) {
            return [
                'valor_cesantias' => 0,
                'intereses_cesantias' => 0,
                'valor_prima' => 0,
                'valor_vacaciones' => 0,
                'diasEfectivosPrimas1' => 0,
                'diasEfectivosPrimas2' => 0,
                'diasAPagarVacaciones' => 0,
                'diasEfectivos'=> 0,
                'diasDefechaaFecha'=> 0,
                'valorTotalDevengado'=> 0,
                'valorTotalLiquidar'=>0,
                'idLiquidado'=>0,
                'EstadoLiquidacion'=>0,
                'comprobante'=> 0,
                'valorDeudas'=>0,
                'firma'=>'',
                'cant_vacaciones_tomadas'=> 0,
                'liq_docLiqui' => 0,
                'liq_enviosDes' => 0,
                'liq_enviosEx' => 0,
                'total_no_laborados' => 0,

                




            ];
        }




        $resultado = $this->calcularDiasPrima($idEmpleado, $empleado['hoj_fechaInicial'], $fechaFinalContrato,$anio);
            $diasEfectivosPrimas1=$resultado['primer_semestre'];
            $diasEfectivosPrimas2=$resultado['segundo_semestre'];

        //  Cálculos de liquidación
        $valorCesantias = (($salario + $auxilio) * $diasEfectivos) / 360;
        $interesesCesantias = ($valorCesantias * 0.12 * $diasEfectivos) / 360;
        $valorPrima = (($salario+ $auxilio) * ($diasEfectivosPrimas1+$diasEfectivosPrimas2)) / 360;
        $valorVacaciones = ($salario * $diasEfectivos) / 720;
        

       


        $diasDerecho = (15 * $diasEfectivos) / 360;  // proporción de vacaciones ganadas
        $diasPendientes = $diasDerecho ;
        if ($diasPendientes < 0) $diasPendientes = 0; // por si acaso

        $valorDia = $salario / 30;
        $valorVacacionestomadas = $empleado['dias_vacaciones'] * $valorDia;
        $valorVacacionesPendientes = ($diasPendientes-$empleado['dias_vacaciones']) * $valorDia;
        $valorVacacionesCompletas = $diasDerecho * $valorDia;



        $Liquidacion = $this->obtenerEstadoLiquidacion($empleado['idhojadevida']);
        

        $EstadoLiquidacion = $Liquidacion['liquidado'];     // "Si" o "No"
        $comprobante = $Liquidacion['comprobante'];  // ruta o vacío

        //Deudas
        $Deudas = $this->obtenerDeudaPromotor($idEmpleado);

        $valorDeudas=$Deudas['totalDebe'];

        //TotalDevengado
        $valorTotalDevengado = $valorCesantias + $interesesCesantias + $valorPrima + $valorVacacionesCompletas;
        //Total Liquidacion 
        $valorTotal = ($valorCesantias + $interesesCesantias + $valorPrima + $valorVacacionesPendientes)-$valorDeudas;

        $firma = $Liquidacion['firma'];  // ruta o vacío

        // Formatear en pesos colombianos
        return [
            'valor_cesantias' => '$' . number_format($valorCesantias, 0, ',', '.'),
            'intereses_cesantias' => '$' . number_format($interesesCesantias, 0, ',', '.'),
            'valor_prima' => '$' . number_format($valorPrima, 0, ',', '.'),
            'valor_vacaciones' => '$' . number_format($valorVacacionesPendientes, 0, ',', '.'),
            'valor_total' => '$' . number_format($valorTotal, 0, ',', '.'),
            'diasEfectivosPrimas1' => $diasEfectivosPrimas1,
            'diasEfectivosPrimas2' => $diasEfectivosPrimas2,
            'diasAPagarVacaciones' => round($diasDerecho ),
            'diasDefechaaFecha'=> $diasDefechaaFecha,
            'diasEfectivos'=> $diasEfectivos,
            'valorTotalDevengado'=> '$' . number_format(($valorTotalDevengado), 0, ',', '.'),
            'valorTotalLiquidar'=> '$' . number_format(($valorTotal), 0, ',', '.'),
            'idLiquidado'=>$idEmpleado,
            'EstadoLiquidacion'=>$EstadoLiquidacion,
            'valorVacacionesCompletas'=>'$' . number_format(($valorVacacionesCompletas), 0, ',', '.'),
            'comprobante'=> $comprobante,
            'valorDeudas'=>'$' . number_format(($valorDeudas), 0, ',', '.'),
            'cant_vacaciones_tomadas'=> $empleado['dias_vacaciones'],
            'valorVacacionestomadas'=>'$' . number_format(($valorVacacionestomadas), 0, ',', '.'),
            'firma'=>$firma,
            'liq_docLiqui' => $Liquidacion['liq_docLiqui'],
            'liq_enviosDes' => $Liquidacion['liq_enviosDes'],
            'liq_enviosEx' => $Liquidacion['liq_enviosEx'],
            'total_no_laborados' => $dias_noTrabajados,

        ];
    }
    public function obtenerPrimasPagadas($idEmpleado) {
        $sql = "SELECT pri_semestre, pri_fecha_inicio, pri_fecha_fin 
                FROM primas 
                INNER JOIN usuarios u ON u.idusuarios = primas.pri_idusu
                WHERE u.usu_identificacion = ? AND pri_confirma = 'Si'";

        $stmt = $this->db->prepare($sql);
        $stmt->bind_param('s', $idEmpleado);
        $stmt->execute();
        $result = $stmt->get_result();

        return $result->fetch_all(MYSQLI_ASSOC);
    }
    public function diasRestantesMeses31($fechaInicio, $fechaFin) {
        $inicio = new DateTime($fechaInicio);
        $fin = new DateTime($fechaFin);

        // Primer y último mes completos dentro del rango
        $primerMesCompleto = (clone $inicio)->modify('first day of next month');
        $ultimoMesCompleto = (clone $fin)->modify('last day of previous month');

        if ($primerMesCompleto > $ultimoMesCompleto) {
            return 0; // no hay meses completos
        }

        $iterador = (clone $primerMesCompleto);
        $meses31 = 0;
        $diasParaFebrero = 0;

        while ($iterador <= $ultimoMesCompleto) {
            $diasDelMes = (int)$iterador->format('t');
            $mes = (int)$iterador->format('m');
            $anio = (int)$iterador->format('Y');

            if ($diasDelMes == 31) {
                $meses31++;
            } elseif ($mes == 2) {
                // febrero completo dentro del rango
                $diasParaFebrero = ($diasDelMes == 29) ? 1 : 2;
            }

            $iterador->modify('first day of next month');
        }

        // Restar los días que febrero "usa"
        $diasRestantes = ($meses31 - $diasParaFebrero);

        // Si febrero usa más días de los que hay en meses de 31, no puede ser negativo
        return max(0, $diasRestantes);
    }

    // ✅ Función final: une datos + cálculos
    public function obtenerLiquidacionesCalculadas($anio = '', $sede = '', $operador = '', $estado = '') {
        $empleados = $this->datosHojaDeVida($anio, $sede, $operador, $estado);
        $resultadoFinal = [];

        foreach ($empleados as $emp) {
            $calculos = $this->calcularValoresLiquidacion($emp,$anio);
            $resultadoFinal[] = array_merge($emp, $calculos);
        }

        return $resultadoFinal;
    }

    // ✅ Esta ya la tenías, se deja igual

    public function obtenerCiudades($conde='') {


        $sql = "SELECT `idsedes`,`sed_nombre` FROM sedes where idsedes>0 $conde ";
        $result = $this->db->query($sql);
        return $result ? $result->fetch_all(MYSQLI_ASSOC) : [];
    }

    public function obtenerRoles() {
        $sql = "SELECT idroles, rol_nombre FROM roles ORDER BY rol_nombre";
        $result = $this->db->query($sql);
        return $result ? $result->fetch_all(MYSQLI_ASSOC) : [];
    }

    public function obtenerIdentificacion($idUser) {
        $sql = "SELECT usu_identificacion FROM usuarios WHERE idusuarios = '$idUser' LIMIT 1";
        $result = $this->db->query($sql);

        if ($result && $row = $result->fetch_assoc()) {
            return $row['usu_identificacion']; // devuelve solo el número
        }

        return null; // si no existe el usuario
    }
    public function obtenerEstadoLiquidacion($idHv) {
        $sql = "SELECT liq_confirma, liq_img_compro, firma,liq_docLiqui,liq_enviosDes,liq_enviosEx
                FROM liquidaciones 
                WHERE liq_idHv = $idHv ";
            //✅ valida remesa
        // $logPath = __DIR__ . '/log_LIQUIDACIONES.txt'; // puedes cambiar el nombre/ruta si quieres
        // $logMessage = "[" . date("Y-m-d H:i:s") . "] SQL111: $sql\n ";
        // file_put_contents($logPath, $logMessage, FILE_APPEND);
        $stmt = $this->db->prepare($sql);
    
        $stmt->execute();
        $result = $stmt->get_result();
        $row = $result->fetch_assoc();

        if ($row['liq_confirma']== 'Si') {
            return [
                'liquidado' => 1,
                'comprobante' => $row['liq_img_compro'] ?? '',
                'firma' => $row['firma'] ?? '',
                'liq_docLiqui' => $row['liq_docLiqui'] ?? '',
                'liq_enviosDes' => $row['liq_enviosDes'] ?? '',
                'liq_enviosEx' => $row['liq_enviosEx'] ?? ''

                

                
                
            ];
        }else {
            return [
                'liquidado' => 0,
                'comprobante' => $row['liq_img_compro'] ?? '',
                'firma' => $row['firma'] ?? '',
                'liq_docLiqui' => $row['liq_docLiqui'] ?? '',
                'liq_enviosDes' => $row['liq_enviosDes'] ?? '',
                'liq_enviosEx' => $row['liq_enviosEx'] ?? ''


            ];
        }


    }
    public function obtenerIdUser($identificacion) {
        $sql = "SELECT idusuarios FROM usuarios WHERE usu_identificacion = '$identificacion'  LIMIT 1";
        $result = $this->db->query($sql);

        if ($result && $row = $result->fetch_assoc()) {
            return $row['idusuarios']; // devuelve solo el número
        }

        return null; // si no existe el usuario
    }



    
    public function obtenerOperadores($ciudad="") {
        $cond="";
        if ($ciudad!="") {
            $cond="and `usu_idsede`='$ciudad'"; 
        }
        $sql = "SELECT `idusuarios`,`usu_nombre` FROM `usuarios` WHERE  (usu_estado=1 or usu_filtro=1) $cond  ";
        $result = $this->db->query($sql);
        return $result ? $result->fetch_all(MYSQLI_ASSOC) : [];
    }


  
    public function actualizarEstadoLiquidacion($id_param, $estado, $usuario, $datosExtras = [], $idhojadevida,$tipo)
    {
        date_default_timezone_set('America/Bogota');
        $fechatiempo = date('Y-m-d H:i:s');

        $jsonDatos = json_encode($datosExtras, JSON_UNESCAPED_UNICODE | JSON_PRETTY_PRINT);

        $fecha_ingreso = $datosExtras['fecha_ingreso'] ?? '0000-00-00';
        $fecha_retiro = $datosExtras['fecha_retiro'] ?? '0000-00-00';
        $confirma = ($estado == 1) ? 'Si' : 'No';

        $sql = "
            INSERT INTO liquidaciones (
                liq_confirma,
                liq_fecha_inicio,
                liq_fecha_fin,
                liq_idusu,
                liq_fechaconfirmausu,
                liq_idadminconfi,
                liq_fechaadminconfi,
                liq_docLiqui,
                liq_confirmaUsus,
                liq_confiAdmin,
                liq_img_compro,
                liq_idHv,
                liq_enviosDes
            )
            VALUES (
                '$confirma',
                '$fecha_ingreso',
                '$fecha_retiro',
                '$id_param',
                '',
                '$usuario',
                '$fechatiempo',
                '$jsonDatos',
                '',
                '$confirma',
                '',
                '$idhojadevida',
                $tipo
            )
            ON DUPLICATE KEY UPDATE
                liq_confirma = VALUES(liq_confirma),
                liq_fecha_inicio = VALUES(liq_fecha_inicio),
                liq_fecha_fin = VALUES(liq_fecha_fin),
                liq_idusu = VALUES(liq_idusu),
                liq_idadminconfi = VALUES(liq_idadminconfi),
                liq_fechaadminconfi = VALUES(liq_fechaadminconfi),
                liq_docLiqui = VALUES(liq_docLiqui),
                liq_confiAdmin = VALUES(liq_confiAdmin),
                liq_enviosDes = liq_enviosDes + VALUES(liq_enviosDes)
        ";

        return $this->db->query($sql) ? true : false;
    }

    public function calcularDiasPrima($idEmpleado, $fechaIngreso, $fechaRetiro = null,$anio)
    {
        date_default_timezone_set('America/Bogota');
        // $anio = date('Y');
        $hoy = new DateTime();

        // 🔎 ENTRADA
        $this->logPrima("INICIO", [
            'idEmpleado' => $idEmpleado,
            'fechaIngreso' => $fechaIngreso,
            'fechaRetiro' => $fechaRetiro,
            'anio' => $anio
        ]);

        // 📆 Fecha final
        $fechaFinalEmpleado = $fechaRetiro ? new DateTime($fechaRetiro) : $hoy;
        $this->logPrima("Fecha final calculada", $fechaFinalEmpleado->format('Y-m-d'));

        // 🔍 Primas ya pagadas
        $sql = "SELECT pri_semestre, pri_fecha_inicio, pri_fecha_fin 
                FROM primas 
                WHERE pri_idusu = ? 
                AND pri_confirma = 'Si' 
                AND YEAR(pri_fecha_inicio) = ?";

        $inicioTiempo = microtime(true);

        $stmt = $this->db->prepare($sql);

        if (!$stmt) {
            $this->logSQL("ERROR PREPARE", $sql, [], null, $this->db->error);
            return ['primer_semestre' => 0, 'segundo_semestre' => 0];
        }

        $params = [
            'pri_idusu' => $idEmpleado,
            'anio' => $anio
        ];

        $stmt->bind_param('ss', $idEmpleado, $anio);

        if (!$stmt->execute()) {
            $this->logSQL("ERROR EXECUTE", $sql, $params, null, $stmt->error);
        }

        $result = $stmt->get_result();
        $primasPagadas = $result->fetch_all(MYSQLI_ASSOC);

        $tiempo = round((microtime(true) - $inicioTiempo) * 1000, 2);

        $this->logSQL(
            "CONSULTA PRIMAS PAGADAS ({$tiempo} ms)",
            $sql,
            $params,
            $primasPagadas
        );


        $stmt = $this->db->prepare($sql);
        $stmt->bind_param('ss', $idEmpleado, $anio);
        $stmt->execute();
        $result = $stmt->get_result();
        $primasPagadas = $result->fetch_all(MYSQLI_ASSOC);

        $this->logPrima("Primas pagadas", $primasPagadas);

        // 📅 Semestres
        $inicioPrimerSem = new DateTime("$anio-01-01");
        $finPrimerSem = new DateTime("$anio-06-30");
        $inicioSegundoSem = new DateTime("$anio-07-01");
        $finSegundoSem = new DateTime("$anio-12-31");

        $this->logPrima("Semestres", [
            'primer' => [$inicioPrimerSem->format('Y-m-d'), $finPrimerSem->format('Y-m-d')],
            'segundo' => [$inicioSegundoSem->format('Y-m-d'), $finSegundoSem->format('Y-m-d')]
        ]);

        // 📆 Fecha ingreso
        $fechaInicioEmpleado = new DateTime($fechaIngreso);

        // 🎯 Días efectivos
        $diasPrimerSem = $this->calcularDiasEfectivos(
            $idEmpleado,
            $fechaInicioEmpleado,
            $fechaFinalEmpleado,
            $inicioPrimerSem,
            $finPrimerSem
        );

        $this->logPrima("Días primer semestre", $diasPrimerSem);

        $diasSegundoSem = $this->calcularDiasEfectivos(
            $idEmpleado,
            $fechaInicioEmpleado,
            $fechaFinalEmpleado,
            $inicioSegundoSem,
            $finSegundoSem
        );

        $this->logPrima("Días segundo semestre", $diasSegundoSem);

        // 📉 Ajustes por primas ya pagadas
        foreach ($primasPagadas as $prima) {
            if ($prima['pri_semestre'] === "Primera") {
                $diasPrimerSem = 0;
                $this->logPrima("Primer semestre YA PAGADO → días = 0");
            }

            if ($prima['pri_semestre'] === "Segunda") {
                $diasSegundoSem = 0;
                $this->logPrima("Segundo semestre YA PAGADO → días = 0");
            }
        }

        $resultado = [
            'primer_semestre' => $diasPrimerSem,
            'segundo_semestre' => $diasSegundoSem
        ];

        // ✅ RESULTADO FINAL
        $this->logPrima("FIN cálculo", $resultado);

        return $resultado;
    }


    private function calcularDiasEfectivos($idEmpleado, $fechaIngreso, $fechaRetiro, $inicioSem, $finSem)
    {
        

        // Calcular intersección de rangos
        $inicio = max($fechaIngreso->getTimestamp(), $inicioSem->getTimestamp());
        $fin = min($fechaRetiro->getTimestamp(), $finSem->getTimestamp());

        // Si no hay intersección
        if ($inicio > $fin) {
            return 0;
        }

        $fechaInicioRango = (new DateTime())->setTimestamp($inicio);
        $fechaFinRango = (new DateTime())->setTimestamp($fin);

        $dias31s = $this->diasRestantesMeses31($fechaInicioRango->format('Y-m-d'), $fechaFinRango->format('Y-m-d'));

        $diasNaturales = $fechaFinRango->diff($fechaInicioRango)->days ;

        $fechaInicioStr = $fechaInicioRango->format('Y-m-d');
        $fechaFinStr = $fechaFinRango->format('Y-m-d');
        //Dias no trabajados
        $diasNoTrabajados = $this->obtenerDiasNoTrabajados($idEmpleado, $fechaInicioStr, $fechaFinStr);

        // 📉 Días efectivos = días naturales - días no trabajados
        $diasEfectivos = max(0, $diasNaturales - $diasNoTrabajados);
        
        

        return $diasEfectivos-($dias31s);
    }

    public function obtenerDiasNoTrabajados($idEmpleado, $fechaInicio, $fechaFin)
    {
        try {
            // Asegurar que las fechas estén en formato correcto
            $fechaInicioStr = (new DateTime($fechaInicio))->format('Y-m-d');
            $fechaFinStr = (new DateTime($fechaFin))->format('Y-m-d');

            // 🔍 Consultar días no trabajados dentro del rango
            $sql = "SELECT COUNT(*) AS total 
                    FROM seguimiento_user su
                    WHERE su.seg_motivo IN ('No trabajo', 'Reposicion por falla', 'Sancionado')
                    AND su.seg_idusuario = ?
                    AND su.seg_fechaingreso BETWEEN ? AND ?";

            $stmt = $this->db->prepare($sql);
            $stmt->bind_param('sss', $idEmpleado, $fechaInicioStr, $fechaFinStr);
            $stmt->execute();
            $result = $stmt->get_result();
            $data = $result->fetch_assoc();

            // Retornar el número de días (o 0 si no hay resultados)
            return isset($data['total']) ? (int)$data['total'] : 0;

        } catch (Exception $e) {
            // Registrar error o manejar según tus necesidades
            error_log("Error en obtenerDiasNoTrabajados: " . $e->getMessage());
            return 0;
        }
    }

    public function traerDesprendible($id) {
        $sql = "SELECT 
        `idLiquidaciones`, 
        `liq_confirma`, 
        `liq_docLiqui`
        FROM `liquidaciones` 
        WHERE liq_idHv=?";

        $stmt = $this->db->prepare($sql);
        $stmt->bind_param('i', $id);
        $stmt->execute();
        $result = $stmt->get_result();

        return $result->fetch_all(MYSQLI_ASSOC);
    }


    public function subirComprobantePago($seleccionados, $archivo)
    {
        $carpeta = "../uploads/comprobantesLiqui/";

        // Crear carpeta si no existe
        if (!file_exists($carpeta)) {
            mkdir($carpeta, 0777, true);
        }

        // Crear nombre único para el archivo
        $nombreArchivo = time() . '_' . basename($archivo['name']);
        $rutaDestino = $carpeta . $nombreArchivo;

        // Mover el archivo al servidor
        if (move_uploaded_file($archivo['tmp_name'], $rutaDestino)) {
            // Guardar en BD la referencia del archivo para cada ID seleccionado
            $contador = 0;
            foreach ($seleccionados as $idhojadevida) {
                $sql = "UPDATE liquidaciones SET liq_img_compro = ? WHERE liq_idHv = ?";
                $stmt = $this->db->prepare($sql);
                $stmt->bind_param("si", $nombreArchivo, $idhojadevida);
                if ($stmt->execute()) {
                    $contador++;
                }
            }

            return [
                'status' => 'success',
                'message' => "Comprobante subido correctamente y vinculado a {$contador} registro(s).",
                'archivo' => $nombreArchivo
            ];
        } else {
            return [
                'status' => 'error',
                'message' => 'Error al mover el archivo al servidor.'
            ];
        }
    }

        // 🟢 Verificar si el registro está liquidado
    public function verificarSiEstaLiquidado($idhojadevida)
    {
        $sql = "SELECT COUNT(*) AS total 
                FROM liquidaciones 
                WHERE liq_idHv = ?";
            //✅ valida remesa
        $logPath = __DIR__ . '/log_LIQUIDACIONES.txt'; // puedes cambiar el nombre/ruta si quieres
        $logMessage = "[" . date("Y-m-d H:i:s") . "] verificarSiEstaLiquidado: $sql\n ";
        file_put_contents($logPath, $logMessage, FILE_APPEND);
        $stmt = $this->db->prepare($sql);
        $stmt->bind_param('i', $idhojadevida);
        $stmt->execute();
        $result = $stmt->get_result();
        $row = $result->fetch_assoc();

        // Si el total > 0, está liquidado
        return ($row['total'] > 0);
    }

    public function obtenerDeudaPromotor($idusuario)
    {
        // ✅ Consulta
        $sql = "SELECT deu_tipo, deu_valor 
                FROM duedapromotor 
                WHERE deu_idpromotor = ?";

        // ✅ Log de depuración
        // $logPath = __DIR__ . '/log_DEUDAPROMOTOR.txt';
        // $logMessage = "[" . date("Y-m-d H:i:s") . "] obtenerDeudaPromotor: $sql (ID: $idusuario)\n";
        // file_put_contents($logPath, $logMessage, FILE_APPEND);

        // ✅ Preparar y ejecutar
        $stmt = $this->db->prepare($sql);
        $stmt->bind_param('i', $idusuario);
        $stmt->execute();
        $result = $stmt->get_result();

        // ✅ Inicializar variables
        $prestamo = 0;
        $descuadre = 0;
        $pago = 0;
        $malenviados = 0;

        // ✅ Recorrer los resultados
        while ($row = $result->fetch_assoc()) {
            switch ($row['deu_tipo']) {
                case 'Prestamos':
                    $prestamo += $row['deu_valor'];
                    break;
                case 'Descuadre':
                    $descuadre += $row['deu_valor'];
                    break;
                case 'Pagos':
                    $pago += $row['deu_valor'];
                    break;
                case 'MalEnviados':
                    $malenviados += $row['deu_valor'];
                    break;
            }
        }

        // ✅ Calcular totales
        $prestamoTotal = $prestamo + $descuadre + $malenviados;
        $totalDebe = $prestamoTotal - $pago;

        // ✅ Retornar resultado en formato numérico (sin strings)
        return [
            'prestamos' => $prestamo,
            'descuadre' => $descuadre,
            'malenviados' => $malenviados,
            'pagos' => $pago,
            'totalDebe' => $totalDebe
        ];
    }

    /**
     * 📩 Enviar comprobante por correo
     */
    public function enviarComprobanteCorreo($idhojadevida, $correo, $celular, $info = [])
    {



        try {

            // 📊 Datos del JSON (según lo que envíes)
                // $idhojadevida = $info['idhojadevida'] ?? 'Colaborador';
                $idLiquidado = $info['idLiquidado'] ?? '';
                $fecha_examen = $info['fecha_examen'] ?? '';
                $resultado = $info['resultado_examen'] ?? '';

                $this->actualizarEstadoLiquidacion($idLiquidado, '', '', $info, $idhojadevida,1);
            // 📎 Enlace del comprobante
            $linkDesprendible = "https://sistema.transmillas.com/nueva_plataforma/controller/FirmarLiquidacionController.php?id=" . $idhojadevida;

            // ✉️ Configurar PHPMailer
            $mail = new PHPMailer(true);
            // $mail->SMTPDebug = 2; // Para depurar
            // $mail->Debugoutput = 'html';

            $mail->isSMTP();
            $mail->Host       = 'smtp.gmail.com';
            $mail->SMTPAuth   = true;
            $mail->Username   = 'ventastransmillas@gmail.com';
            $mail->Password   = 'tpwv clpk qqdo dbgx'; // Contraseña de aplicación Gmail
            $mail->SMTPSecure = PHPMailer::ENCRYPTION_STARTTLS;
            $mail->Port       = 587;

            // Remitente y destinatario
            $mail->setFrom('ventastransmillas@gmail.com', 'TRANSMILLAS LOGISTICA Y TRANSPORTADORA S.A.S.');
            $mail->addAddress($correo);

            // Asunto y contenido
            $asunto = "Comprobante de Liquidación";
            $contenido = "
            <p>Estimado colaborador,</p>
            <p>Le enviamos el enlace para consultar y firmar su desprendible de liquidación:</p>
            <p><a href='$linkDesprendible' style='color:#007bff;font-weight:bold;'>Ver comprobante de liquidación</a></p>
            <p>Por favor ingrese al enlace para revisarlo y firmarlo.</p>
            <br>
            <p>Gracias,</p>
            <p><b>TRANSMILLAS LOGISTICA Y TRANSPORTADORA S.A.S.</b></p>
            <p>Carrera 20 # 56-26 Galerías<br>PBX: 3103122</p>
            ";

            // Logo de la empresa
            $rutaLogo = __DIR__ . '/../../images/logoCorreo.jpg';
            if (file_exists($rutaLogo)) {
                $mail->AddEmbeddedImage($rutaLogo, 'empresa_logo');
            }

            // Configurar formato HTML
            $mail->isHTML(true);
            $mail->Subject = $asunto;

            $mail->Body = "
            <html>
            <body>
                <div>
                    <img src='cid:empresa_logo' alt='Logo' style='width:300px;'>
                    <br><br>
                    $contenido
                    <hr>
                    <p style='font-size:12px;color:#777;'>Este mensaje fue generado automáticamente, por favor no responda.</p>
                </div>
            </body>
            </html>";

            $mail->AltBody = strip_tags($contenido);

            // Enviar correo
            $mail->send();

            // Registrar log
            $log = "[" . date('Y-m-d H:i:s') . "] 📩 Comprobante enviado a $correo (ID HV: $idhojadevida)\n";
            file_put_contents(__DIR__ . '/log_envioCorreo.txt', $log, FILE_APPEND);

            return ['success' => true, 'mensaje' => "Correo enviado correctamente a $correo"];

        } catch (Exception $e) {
            error_log("❌ Error al enviar correo: " . $e->getMessage());
            return ['success' => false, 'mensaje' => 'Error al enviar el correo: ' . $e->getMessage()];
        }
    }

    /**
     * 💬 Enviar comprobante por WhatsApp
     */
    public function enviarComprobanteCelular($idhojadevida, $celular, $correo, $info = [])
    {

        // 📊 Datos del JSON (según lo que envíes)
                // $idhojadevida = $info['idhojadevida'] ?? 'Colaborador';
                $idLiquidado = $info['idLiquidado'] ?? '';
                $fecha_examen = $info['fecha_examen'] ?? '';
                $resultado = $info['resultado_examen'] ?? '';

                $this->actualizarEstadoLiquidacion($idLiquidado, '', '', $info, $idhojadevida,1);

        $logPath = __DIR__ . '/log_envioWhats.txt';
        $logMessage = "[" . date("Y-m-d H:i:s") . "] WhatsApp enviado a $celular (ID HV: $idhojadevida)\n";
        file_put_contents($logPath, $logMessage, FILE_APPEND);

        // 2️⃣ Enviar mensaje de WhatsApp (reutilizando tu función de alertas)
        $this->enviarAlertaWhat($celular, '39',$idhojadevida);



        return true;
    }

    /**
     * 🔁 Tu función existente para enviar alertas por WhatsApp
     */
    public function enviarAlertaWhat($telefono, $tipo,$comprobante)
    {
        $url = "https://www.transmillas.com/ChatbotTransmillas/alertas.php";

        $data = array(
            "telefono" => $telefono,
            "tipo_alerta" => $tipo,
            "id" => $comprobante,

        );

        $data_json = json_encode($data);

        $curl = curl_init();
        curl_setopt_array($curl, array(
            CURLOPT_URL => $url,
            CURLOPT_RETURNTRANSFER => true,
            CURLOPT_POST => true,
            CURLOPT_POSTFIELDS => $data_json,
            CURLOPT_HTTPHEADER => array(
                'Content-Type: application/json',
                'Authorization: Bearer MiSuperToken123'
            ),
        ));

        $response = curl_exec($curl);

        if ($response === false) {
            $error = curl_error($curl);
            error_log("Error en alerta WhatsApp: $error");
        } else {
            $response_data = json_decode($response, true);
            error_log("WhatsApp enviado: " . print_r($response_data, true));
        }

        curl_close($curl);
    }


    public function guardarFirmaLiquidacion($idhojadevida, $firmaBase64)
    {
        try {
            // Convertir base64 a imagen
            $firmaData = str_replace('data:image/png;base64,', '', $firmaBase64);
            $firmaData = str_replace(' ', '+', $firmaData);
            $imagen = base64_decode($firmaData);

            // Crear carpeta si no existe
            $rutaCarpeta = __DIR__ . '../../uploads/firmasLiquidaciones/';
            if (!file_exists($rutaCarpeta)) {
                mkdir($rutaCarpeta, 0777, true);
            }

            // Nombre único para la imagen
            $nombreArchivo = 'firma_' . $idhojadevida . '_' . time() . '.png';
            $rutaArchivo = $rutaCarpeta . $nombreArchivo;

            // Guardar archivo en el servidor
            file_put_contents($rutaArchivo, $imagen);

            // 🔹 1️⃣ Consultar el JSON actual
            $sqlSelect = "SELECT liq_docLiqui FROM liquidaciones WHERE liq_idHv = ?";
            $stmtSelect = $this->db->prepare($sqlSelect);
            $stmtSelect->bind_param('i', $idhojadevida);
            $stmtSelect->execute();
            $resultado = $stmtSelect->get_result();

            if ($resultado->num_rows === 0) {
                throw new Exception("No se encontró liquidación para el ID $idhojadevida");
            }

            $fila = $resultado->fetch_assoc();
            $jsonActual = json_decode($fila['liq_docLiqui'], true);

            // 🔹 2️⃣ Agregar la nueva firma al JSON
            $jsonActual['firma'] = $nombreArchivo;

            // 🔹 3️⃣ Volver a codificar a JSON
            $nuevoJson = json_encode($jsonActual, JSON_UNESCAPED_UNICODE);

            // 🔹 4️⃣ Guardar el nuevo JSON en la BD
            $sqlUpdate = "UPDATE liquidaciones SET liq_docLiqui = ? , firma = ? WHERE liq_idHv = ?";
            $stmtUpdate = $this->db->prepare($sqlUpdate);
            $stmtUpdate->bind_param('ssi', $nuevoJson,$nombreArchivo, $idhojadevida);
            $stmtUpdate->execute();

            if ($stmtUpdate->affected_rows > 0) {
                return true;
            } else {
                error_log("⚠️ No se actualizó ninguna fila para id $idhojadevida");
                return false;
            }

        } catch (Exception $e) {
            error_log("❌ Error al guardar la firma: " . $e->getMessage());
            return false;
        }
    }

    public function guardarFirmaExamenesLiquidacion($idhojadevida, $firmaBase64, $examenes)
    {
        try {
            // Convertir base64 a imagen
            $firmaData = str_replace('data:image/png;base64,', '', $firmaBase64);
            $firmaData = str_replace(' ', '+', $firmaData);
            $imagen = base64_decode($firmaData);

            // Crear carpeta si no existe
            $rutaCarpeta = __DIR__ . '../../uploads/firmasLiquidaciones/';
            if (!file_exists($rutaCarpeta)) {
                mkdir($rutaCarpeta, 0777, true);
            }

            // Nombre único de la imagen
            $nombreArchivo = 'firma_' . $idhojadevida . '_' . time() . '.png';
            $rutaArchivo = $rutaCarpeta . $nombreArchivo;
            file_put_contents($rutaArchivo, $imagen);

            // 🔹 Consultar el JSON actual
            $sqlSelect = "SELECT liq_docLiqui FROM liquidaciones WHERE liq_idHv = ?";
            $stmtSelect = $this->db->prepare($sqlSelect);
            $stmtSelect->bind_param('i', $idhojadevida);
            $stmtSelect->execute();
            $resultado = $stmtSelect->get_result();

            if ($resultado->num_rows === 0) {
                throw new Exception("No se encontró liquidación para el ID $idhojadevida");
            }

            $fila = $resultado->fetch_assoc();
            $jsonString = $fila['liq_docLiqui'];

            // ✅ Limpiar el JSON en caso de estar escapado o envuelto en comillas
            $jsonString = trim($jsonString, "\"");
            $jsonString = stripslashes($jsonString);

            $jsonActual = json_decode($jsonString, true);

            // Si aún no se decodifica bien, intentar forzarlo
            if (!is_array($jsonActual)) {
                $jsonActual = [];
            }

            // 🔹 Agregar o actualizar los campos nuevos
            $jsonActual['firma_examenes'] = $nombreArchivo;
            $jsonActual['examenes'] = strtoupper(trim($examenes)); // "SI" o "NO"

            // 🔹 Guardar el JSON actualizado
            $nuevoJson = json_encode($jsonActual, JSON_UNESCAPED_UNICODE | JSON_UNESCAPED_SLASHES);
            $sqlUpdate = "UPDATE liquidaciones SET liq_docLiqui = ? WHERE liq_idHv = ?";
            $stmtUpdate = $this->db->prepare($sqlUpdate);
            $stmtUpdate->bind_param('si', $nuevoJson, $idhojadevida);
            $stmtUpdate->execute();

            // Log para verificar qué se está guardando realmente
            error_log("✅ JSON actualizado para ID $idhojadevida: $nuevoJson");

            return true;
        } catch (Exception $e) {
            error_log("❌ Error al guardar la firma y exámenes: " . $e->getMessage());
            return false;
        }
    }


     public function enviarExamenesCorreo($idhojadevida, $correo, $celular)
    {



        try {
            // 📎 Enlace del comprobante
            $linkDesprendible = "https://sistema.transmillas.com/nueva_plataforma/controller/FirmarExamenesLiquiController.php?id=" . $idhojadevida;

            // ✉️ Configurar PHPMailer
            $mail = new PHPMailer(true);
            // $mail->SMTPDebug = 2; // Para depurar
            // $mail->Debugoutput = 'html';

            $mail->isSMTP();
            $mail->Host       = 'smtp.gmail.com';
            $mail->SMTPAuth   = true;
            $mail->Username   = 'ventastransmillas@gmail.com';
            $mail->Password   = 'tpwv clpk qqdo dbgx'; // Contraseña de aplicación Gmail
            $mail->SMTPSecure = PHPMailer::ENCRYPTION_STARTTLS;
            $mail->Port       = 587;

            // Remitente y destinatario
            $mail->setFrom('ventastransmillas@gmail.com', 'TRANSMILLAS LOGISTICA Y TRANSPORTADORA S.A.S.');
            $mail->addAddress($correo);

            // Asunto y contenido
            $asunto = "Comprobante de Liquidación";
            $contenido = "
            <p>Estimado colaborador,</p>
            <p>Le enviamos el enlace para consultar y firmar su desprendible de liquidación:</p>
            <p><a href='$linkDesprendible' style='color:#007bff;font-weight:bold;'>Ver comprobante de liquidación</a></p>
            <p>Por favor ingrese al enlace para revisarlo y firmarlo.</p>
            <br>
            <p>Gracias,</p>
            <p><b>TRANSMILLAS LOGISTICA Y TRANSPORTADORA S.A.S.</b></p>
            <p>Carrera 20 # 56-26 Galerías<br>PBX: 3103122</p>
            ";

            // Logo de la empresa
            $rutaLogo = __DIR__ . '/../../images/logoCorreo.jpg';
            if (file_exists($rutaLogo)) {
                $mail->AddEmbeddedImage($rutaLogo, 'empresa_logo');
            }

            // Configurar formato HTML
            $mail->isHTML(true);
            $mail->Subject = $asunto;

            $mail->Body = "
            <html>
            <body>
                <div>
                    <img src='cid:empresa_logo' alt='Logo' style='width:300px;'>
                    <br><br>
                    $contenido
                    <hr>
                    <p style='font-size:12px;color:#777;'>Este mensaje fue generado automáticamente, por favor no responda.</p>
                </div>
            </body>
            </html>";

            $mail->AltBody = strip_tags($contenido);

            // Enviar correo
            $mail->send();
            $val=1;
            $sqlUpdate = "UPDATE liquidaciones SET liq_enviosEx = ? WHERE liq_idHv = ?";
            $stmtUpdate = $this->db->prepare($sqlUpdate);
            $stmtUpdate->bind_param('ii', $val, $idhojadevida);
            $stmtUpdate->execute();

            // Registrar log
            $log = "[" . date('Y-m-d H:i:s') . "] 📩 Comprobante enviado a $correo (ID HV: $idhojadevida)\n";
            file_put_contents(__DIR__ . '/log_envioCorreo.txt', $log, FILE_APPEND);

            return ['success' => true, 'mensaje' => "Correo enviado correctamente a $correo"];

        } catch (Exception $e) {
            error_log("❌ Error al enviar correo: " . $e->getMessage());
            return ['success' => false, 'mensaje' => 'Error al enviar el correo: ' . $e->getMessage()];
        }
    }

    /**
     * 💬 Enviar comprobante por WhatsApp
     */
    public function enviarExamenesCelular($idhojadevida, $celular, $correo)
    {



        $logPath = __DIR__ . '/log_envioWhats.txt';
        $logMessage = "[" . date("Y-m-d H:i:s") . "] WhatsApp enviado a $celular (ID HV: $idhojadevida)\n";
        file_put_contents($logPath, $logMessage, FILE_APPEND);

        // 2️⃣ Enviar mensaje de WhatsApp (reutilizando tu función de alertas)
        $this->enviarAlertaWhat($celular, '40',$idhojadevida);
            $val=1;
            $sqlUpdate = "UPDATE liquidaciones SET liq_enviosEx = ? WHERE liq_idHv = ?";
            $stmtUpdate = $this->db->prepare($sqlUpdate);
            $stmtUpdate->bind_param('ii', $val, $idhojadevida);
            $stmtUpdate->execute();


        return true;
    }


    public function logPrima($mensaje, $data = null)
    {
        $rutaLog = __DIR__ . '/logs/primas.log';

        // Crear carpeta si no existe
        if (!is_dir(dirname($rutaLog))) {
            mkdir(dirname($rutaLog), 0777, true);
        }

        $fecha = date('Y-m-d H:i:s');

        $linea = "[PRIMA][$fecha] $mensaje";

        if ($data !== null) {
            $linea .= " | DATA: " . json_encode($data, JSON_UNESCAPED_UNICODE);
        }

        $linea .= PHP_EOL;

        file_put_contents($rutaLog, $linea, FILE_APPEND);
    }
    private function logSQL($titulo, $sql, $params = [], $resultado = null, $error = null)
{
    $rutaLog = __DIR__ . '/logs/primas_sql.log';

    if (!is_dir(dirname($rutaLog))) {
        mkdir(dirname($rutaLog), 0777, true);
    }

    $fecha = date('Y-m-d H:i:s');

    $log  = "==============================\n";
    $log .= "[SQL][$fecha] $titulo\n";
    $log .= "QUERY:\n$sql\n";

    if (!empty($params)) {
        $log .= "PARAMS:\n" . json_encode($params, JSON_UNESCAPED_UNICODE) . "\n";
    }

    if ($resultado !== null) {
        $log .= "RESULTADO:\n" . json_encode($resultado, JSON_UNESCAPED_UNICODE) . "\n";
    }

    if ($error !== null) {
        $log .= "ERROR:\n$error\n";
    }

    $log .= "==============================\n\n";

    file_put_contents($rutaLog, $log, FILE_APPEND);
}

 
}
