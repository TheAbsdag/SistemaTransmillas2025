<?php 
if (!isset($_POST['sede']) || !isset($_POST['acceso'])) {
    echo "<script>
            alert('No tiene acceso a esta página');
            window.close(); // cierra la pestaña
          </script>";
    exit;


}
 date_default_timezone_set('America/Bogota'); 
?>
<!DOCTYPE html>
<html lang="es">
<head>
  <meta charset="UTF-8">
  <title>Descargas de oficina</title>
  <meta name="viewport" content="width=device-width, initial-scale=1">
  <link rel="shortcut icon" href="../../images/Logo Google Nuevo.png">

<!-- Bootstrap 5 CSS desde CDN -->
<link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">

<!-- DataTables Bootstrap 5 CSS -->
<link rel="stylesheet" href="https://cdn.datatables.net/1.13.6/css/dataTables.bootstrap5.min.css">

<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
<link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.10.5/font/bootstrap-icons.css">
<!-- ✅ Librería de SweetAlert2 -->
<script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script></style>
<style>

.sidebar {
      background-color: #ffffff;
      border-right: 1px solid #dee2e6;
      min-height: 100vh;
    }
    .sidebar .nav-link {
      color: #002f6c;
      font-weight: 500;
    }
    .sidebar .nav-link.active {
      background-color: #002f6c;
      color: white;
    }
    .badge-notify {
      background-color: red;
      color: white;
      border-radius: 50px;
      font-size: 0.75rem;
      padding: 0.25rem 0.5rem;
      margin-left: auto;
    }
    .topbar {
      background-color: #002f6c;
      color: white;
    }
    .bottom-nav {
      background-color: white;
      border-top: 1px solid #dee2e6;
    }
    .bottom-nav .nav-link {
      color: #002f6c;
      font-size: 0.9rem;
    }
    .form-section {
      padding: 2rem;
    }
  
thead.azul-blanco th {
  background-color: #01468c; /* Tu azul exacto */
  color: white;
}
.mi-header {
        background-color: #00458D; /* Naranja por ejemplo */
        color: white;
}

  /* Fondo oscuro */
  #modal {
    display: none;
    position: fixed;
    z-index: 2000;
    left: 0; top: 0;
    width: 100%; height: 100%;
    background: rgba(0,0,0,0.7);
    justify-content: center;
    align-items: center;
  }
  #modal img {
    max-width: 90%;
    max-height: 90%;
    border: 5px solid #fff;
    border-radius: 10px;
  }
    .img-thumbnail {
        width: 90px;
        height: 90px;
        object-fit: cover; /* Para que no se deformen */
        cursor: pointer;
        transition: transform 0.3s ease, box-shadow 0.3s ease;
        border-radius: 10px;
    }

    /* Efecto al pasar el mouse */
    .img-thumbnail:hover {
        transform: scale(1.08);
        box-shadow: 0 4px 15px rgba(0, 0, 0, 0.3);
    }

    /* Efecto al hacer click */
    .img-thumbnail:active {
        transform: scale(0.95);
    }
    /* Mantener el área de escaneo cuadrada */
    #lectorQR {
    width: 100% !important;
    height: 100% !important;
    position: relative;
    }

    /* Ajusta el cuadrado de escaneo */
    #lectorQR video {
    object-fit: cover; /* Evita deformaciones */
    width: 100%;
    height: 100%;
    }

    #lectorQR::after {
    content: "";
    /* position: absolute; */
    top: 50%;
    left: 50%;
    width: 90vw;   /* 👈 más grande */
    height: 90vw;  /* 👈 mismo valor para que sea cuadrado */
    transform: translate(-50%, -50%);
    border: 3px solid white;
    border-radius: 8px;
    box-shadow: 0 0 10px rgba(0,0,0,0.6);
    pointer-events: none;
    }

/* MÓVIL/TABLET: hasta 991.98px (incluye la mayoría de tablets) */
@media (max-width: 991.98px) {
  /* Forzar fullscreen aunque tu Bootstrap no tenga modal-fullscreen-* */
  #modalValidarPeso .modal-dialog {
    margin: 0 !important;
    max-width: 100% !important;
    width: 100% !important;
    height: 100% !important;
  }
  #modalValidarPeso .modal-content {
    height: 100vh;           /* ocupa alto total */
    border-radius: 0;        /* look de app */
    font-size: 1.1rem;       /* texto base más grande */
  }
  #modalValidarPeso .modal-header,
  #modalValidarPeso .modal-footer {
    padding: 1rem 1.25rem;
  }
  #modalValidarPeso .modal-title {
    font-size: 1.4rem;
  }
  #modalValidarPeso .form-label {
    font-size: 1.1rem;
  }
  #modalValidarPeso .form-control,
  #modalValidarPeso .form-select,
  #modalValidarPeso .btn {
    font-size: 1.05rem;
    padding: 0.9rem 1rem;
  }
  /* que el contenido sea desplazable si se llena */
  #modalValidarPeso .modal-body {
    overflow: auto;
    -webkit-overflow-scrolling: touch;
    padding-bottom: 0.5rem;
  }
}

/* ESCRITORIO: modal normal (ajusta si quieres un ancho fijo) */
@media (min-width: 992px) {
  #modalValidarPeso .modal-dialog {
    max-width: 600px; /* o deja que Bootstrap decida */
  }
}



/* 🎨 Estilos personalizados para la tabla de liquidaciones */
#tablaLiquidaciones td:nth-child(2),
#tablaLiquidaciones td:nth-child(3),
#tablaLiquidaciones td:nth-child(4),
#tablaLiquidaciones td:nth-child(5) {
  background-color: #f8f9fa; /* Gris muy claro */
  color: #212529;
  font-weight: 500;
}

/* 💰 Salario y Auxilio */
#tablaLiquidaciones td:nth-child(6),
#tablaLiquidaciones td:nth-child(7) {
  background-color: #fff3cd; /* Verde suave */
  color: #664d03;
  font-weight: bold;
}

/* 📅 Fechas (Inicio y Corte) */
/* #tablaLiquidaciones td:nth-child(7),
#tablaLiquidaciones td:nth-child(8) {
  background-color: #fff3cd; 
  color: #664d03;
} */

/* 📆 Días Totales / No trabajados / Trabajados */
/* #tablaLiquidaciones td:nth-child(9),
#tablaLiquidaciones td:nth-child(10),
#tablaLiquidaciones td:nth-child(11) {
  background-color: #e7f1ff; 
  color: #084298;
} */

/* 🏦 Cesantías e Intereses */
#tablaLiquidaciones td:nth-child(13),
#tablaLiquidaciones td:nth-child(14) {
  background-color: #d1e7dd; /* Verde agua */
  color: #0f5132;
  
}

/* 🎁 Prima */
#tablaLiquidaciones td:nth-child(15), */
#tablaLiquidaciones td:nth-child(16) {
  background-color: #d1e7dd; /* Verde agua */
  color: #0f5132;
}

/* 🌴 Vacaciones */
/* #tablaLiquidaciones td:nth-child(16), */
/* #tablaLiquidaciones td:nth-child(17), */
#tablaLiquidaciones td:nth-child(17) {
  background-color: #d1e7dd; /* Verde agua */
  color: #0f5132;
}

/* 💸 Valor total a liquidar */
#tablaLiquidaciones td:nth-child(22) {
  background-color: #fde2e1; /* Rojo pálido */
  color: #7b1913;
  font-weight: bold;
}

/* 📎 Acciones (Desprendible, Liquidar, Comprobante, Pagado) */
#tablaLiquidaciones td:nth-child(23),
#tablaLiquidaciones td:nth-child(24),
#tablaLiquidaciones td:nth-child(25),
#tablaLiquidaciones td:nth-child(26) {
  background-color: #f1f1f1; /* Gris neutro */
  color: #333;
}
.fila-vencida td {
  background-color: #f8d7da !important;
  color: #721c24 !important;
}
</style>
<body>
            
        <!-- Barra superior -->
        <nav class="navbar navbar-expand-lg topbar">
        <div class="container-fluid">
            <!-- Botón de regreso -->
            <button class="btn btn-light" onclick="history.back()">⬅ Volver</button>
        </div>
        </nav>
<div class="container-fluid mt-4">
  <div class="card shadow p-3 mb-4 bg-body rounded">
    <div class="card-header text-center mi-header">
      <h3 class="mb-0">Liquidaciones</h3>
    </div>

    <div class="card-body">
        <div class="row mb-3 align-items-end">
        <div class="col-md-4">
            <label for="filtroAnio" class="form-label">📅 Año</label>
            <select id="filtroAnio" name="filtroAnio" class="form-control">
                <?php
                    $anioActual = date('Y');
                    $anioInicio = 2020; // puedes poner desde donde quieres que empiece el listado
                    $anioFin = $anioActual; // o puedes poner un rango fijo si lo prefieres
                    
                    for ($i = $anioInicio; $i <= $anioFin; $i++) {
                        $selected = ($i == $anioActual) ? 'selected' : '';
                        echo "<option value='$i' $selected>$i</option>";
                    }
                ?>
            </select>
        </div>

            <!-- Ciudad -->
            <div class="col-md-4">
                <label class="form-label">Sede (*)</label>
                <select name="filtroCiudad" id="filtroCiudad" class="form-select" >
                <option value="">Seleccione...</option>
                <?php foreach($ciudades as $c): ?>
                    <?php $req ="";
                    if ($c['idsedes'] == $sede) {
                        $req="selected";
                    }?>
                    <option value="<?= $c['idsedes'] ?>" <?=$req?>><?= $c['sed_nombre'] ?></option>
                <?php endforeach; ?>
                </select>
            </div>
            <div class="col-md-4">
                <label for="filtroOperador" class="form-label">Empleado</label>
                <select name="filtroOperador" id="filtroOperador" class="form-select" >
                <option value="">Seleccione...</option>
                <?php foreach($operadores as $c): ?>
                    <option value="<?= $c['idusuarios'] ?>"><?= $c['usu_nombre'] ?></option>
                <?php endforeach; ?>
                </select>
            </div>
            <div class="col-md-4">
                <label for="filtroEstado" class="form-label">Estado</label>
                <select name="filtroEstado" id="filtroEstado" class="form-select" >
                  <option value="">Seleccione...</option>
                  <option value="Sin liquidar" selected>Sin liquidar</option>
                  <option value="Liquidado">Liquidado</option>
                </select>
            </div>

            <!-- BOTÓN -->
            <div class="col-md-4 text-end">
              <label class="form-label d-block invisible">Botón</label>
              <button class="btn btn-success text-white w-100" data-bs-toggle="modal" data-bs-target="#modalComprobante">
                <i class="bi bi-qr-code-scan me-1"></i> Cargar comprobante
              </button>
            </div>
           

           

        </div>

      <div class="table-responsive">
        <table id="tablaLiquidaciones" class="table table-hover table-bordered align-middle text-center">
          <thead class="table-primary">
            <tr>



                
                <th>Nombre</th>
                <th>Cédula</th>
                <th>Contrato</th>
                <th>Cargo</th>
                <th>Salario</th>
                <th>Auxilio</th>
                <th>Inicio Contrato</th>
                <th>Fecha de corte</th>
                <th>Total dias</th>
                <th>Dias no trabajados</th>
                <th>Dias trabajados</th>
                <th>Total Cesantias</th>
                <th>Total Int. de cesantias</th>
                <th>Dias Prima1</th>
                <th>Dias Prima2</th>
                <th>Total Prima</th>
                <th>Dias Vacaciones </th>
                <th>Dias Vacaciones tomados</th>
                <th>Total Dias Vacaciones por pagar</th>
                <th>Deudas</th>
                <th>Valor total a liquidar</th>
                <th>Desprendible</th>      
                <th>Examenes medicos</th>
                <th>Liquidar</th>
                <th>Comprobante de pago</th>
                <th></th>

                


            </tr>
          </thead>
          <tbody></tbody>
        </table>
      </div>
    </div>
    
  </div>

</div>

<!-- MODAL -->
<div class="modal fade" id="modalComprobante" tabindex="-1" aria-labelledby="modalComprobanteLabel" aria-hidden="true">
  <div class="modal-dialog modal-dialog-centered">
    <div class="modal-content">
      
      <!-- Encabezado -->
      <div class="modal-header bg-success text-white">
        <h5 class="modal-title" id="modalComprobanteLabel">
          <i class="bi bi-upload me-2"></i> Subir comprobante de pago
        </h5>
        <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal" aria-label="Cerrar"></button>
      </div>
      
      <!-- Cuerpo -->
      <div class="modal-body">
        <form id="formComprobante" enctype="multipart/form-data">
          <div class="mb-3">
            <label for="comprobante" class="form-label">Selecciona el comprobante (PDF o imagen)</label>
            <input type="file" class="form-control" id="comprobante" name="comprobante" accept=".pdf, image/*" required>
          </div>
          <div class="text-end">
            <button type="submit" class="btn btn-success">
              <i class="bi bi-cloud-upload me-1"></i> Subir
            </button>
          </div>
        </form>
      </div>

    </div>
  </div>
</div>

<!-- Modal modalVerComprobante de pago  -->
<div class="modal fade" id="modalVerComprobante" tabindex="-1" aria-labelledby="modalComprobanteLabel" aria-hidden="true">
  <div class="modal-dialog modal-xl modal-dialog-centered">
    <div class="modal-content">
      <div class="modal-header">
        <h5 class="modal-title" id="modalComprobanteLabel">Comprobante de liquidación</h5>
        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Cerrar"></button>
      </div>
      <div class="modal-body p-0" style="height: 80vh;">
        <iframe id="iframeComprobante" src="" width="100%" height="100%" frameborder="0"></iframe>
      </div>
    </div>
  </div>
</div>

<!-- Modal Enviar Comprobante -->
<div class="modal fade" id="modalEnviarComprobante" tabindex="-1" aria-hidden="true">
  <div class="modal-dialog modal-dialog-centered">
    <div class="modal-content">
      <div class="modal-header bg-success text-white">
        <h5 class="modal-title"><i class="bi bi-send"></i> Enviar comprobante</h5>
        <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal"></button>
      </div>
      <div class="modal-body">
        <form id="formEnviarComprobante">
          <input type="hidden" id="idhojadevida" name="idhojadevida">

          <div class="mb-3">
            <label for="celular" class="form-label">Número de celular</label>
            <input type="text" class="form-control" id="celular" name="celular" required>
            <input type="hidden" id="jsonData" name="jsonData">
          </div>

          <div class="mb-3">
            <label for="correo" class="form-label">Correo del operador</label>
            <input type="email" class="form-control" id="correo" name="correo" required>
          </div>

          <div class="text-end">
            <button type="button" class="btn btn-outline-success" id="btnEnviarWhatsapp">
              <i class="bi bi-whatsapp"></i> Enviar por WhatsApp
            </button>
            <button type="submit" class="btn btn-primary">
              <i class="bi bi-envelope"></i> Enviar correo
            </button>
          </div>
        </form>
      </div>
    </div>
  </div>
</div>


<div class="modal fade" id="modalEnviarExamen" tabindex="-1">
  <div class="modal-dialog">
    <div class="modal-content">
      <form id="formEnviarExamen">
        <div class="modal-header">
          <h5 class="modal-title">Enviar examen médico</h5>
          <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
        </div>
        <div class="modal-body">
          <input type="hidden" id="idhojadevidaExamen">
          <div class="mb-3">
            <label for="correoExamen" class="form-label">Correo</label>
            <input type="email" id="correoExamen" class="form-control">
          </div>
          <div class="mb-3">
            <label for="celularExamen" class="form-label">Celular</label>
            <input type="text" id="celularExamen" class="form-control">
          </div>
        </div>
        <div class="modal-footer">
          <button type="submit" class="btn btn-primary">Enviar por correo</button>
          <button type="button" id="btnEnviarExamenWhatsapp" class="btn btn-success">Enviar por WhatsApp</button>
        </div>
      </form>
    </div>
  </div>
</div>

<!-- jQuery -->
<script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>

<!-- Bootstrap JS -->
<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>

<!-- DataTables -->
<script src="https://cdn.datatables.net/1.13.6/js/jquery.dataTables.min.js"></script>
<script src="https://cdn.datatables.net/1.13.6/js/dataTables.bootstrap5.min.js"></script>
  <!-- ✅ DataTables desde CDN -->
<script src="https://unpkg.com/html5-qrcode"></script>   

<script>
  let seleccionados = [];
$(document).ready(function () {
  // Array donde guardaremos las filas seleccionadas

  const tabla = $('#tablaLiquidaciones').DataTable({
    ajax: {
      url: '/nueva_plataforma/controller/LiquidacionesController.php',
      type: 'POST',
      data: function (d) {
        d.ajax = true;
        d.Anio = $('#filtroAnio').val();
        d.ciudad = $('#filtroCiudad').val();
        d.operador = $('#filtroOperador').val();
        d.estado = $('#filtroEstado').val();
      },
      dataSrc: ''
    },
    columns: [
      {
        data: null,
        orderable: false,
        className: 'text-center',
        render: function (data, type, row) {
          return `<input type="checkbox" class="chk-liquidacion" data-id="${row.idhojadevida}">`;
        }
      },
      { data: 'nombre_completo' },
      { data: 'hoj_cedula' },
      { data: 'hoj_tipocontrato' },
      { data: 'car_cargo' },
      { data: 'car_salario' },
      { data: 'car_Auxilio' },
      { data: 'hoj_fechaInicial' },
      { data: 'hoj_fechaFinal' },
      { data: 'diasDefechaaFecha' },
      { data: 'total_no_laborados' },
      { data: 'diasEfectivos' },
      { data: 'valor_cesantias' },
      { data: 'intereses_cesantias' },
      { data: 'diasEfectivosPrimas1' },
      { data: 'diasEfectivosPrimas2' },
      { data: 'valor_prima' },
      { data: 'diasAPagarVacaciones' },
      { data: 'dias_vacaciones' },
      { data: 'valor_vacaciones' },
      { data: 'valorDeudas' },
      { data: 'valorTotalLiquidar' },
      {
        data: null,
        render: function (data, type, row) {
          const info = {
            idLiquidado: row.idLiquidado || '',
            nombre: row.nombre_completo || '',
            cedula: row.hoj_cedula || '',
            fecha_ingreso: row.hoj_fechaInicial || '',
            fecha_retiro: row.hoj_fechaFinal || '',
            dias_trabajados: row.diasEfectivos || 0,
            dias_cesantias: row.diasEfectivos || 0,
            dias_prima1: row.diasEfectivosPrimas1 || 0,
            dias_prima2: row.diasEfectivosPrimas2 || 0,
            dias_vacaciones: row.diasAPagarVacaciones || 0,
            sueldobasico: row.car_salario || 0,
            transporte: row.car_Auxilio || 0,
            cesantias: row.valor_cesantias || 0,
            intereses: row.intereses_cesantias || 0,
            prima: row.valor_prima || 0,
            vacaciones: row.valor_vacaciones || 0,
            valorTotalDevengado: row.valorTotalDevengado || 0,
            valor_total: row.valorTotalLiquidar || 0,
            cargo: row.car_cargo || '',
            noTrabajados: row.dias_noTrabajados || 0,
            valorVacacionesCompletas: row.valorVacacionesCompletas || 0,
            valorDeudas: row.valorDeudas || 0,
            valorVacacionestomadas: row.valorVacacionestomadas || 0,
            dias_vacacionesTomadas: row.dias_vacaciones || 0,
            firma: row.firma || 0,
            cant_vacaciones_tomadas: row.cant_vacaciones_tomadas || 0,
            dias_sanciones: row.dias_sanciones || 0
          };

          const jsonData = encodeURIComponent(JSON.stringify(info));
          const envios = parseInt(row.liq_enviosDes || 0);
          const examenes = parseInt(row.liq_enviosEx || 0);

          // 🔴 Burbujita roja si hay envíos
          const badge = envios > 0
            ? `<span class="position-absolute top-0 start-100 translate-middle badge rounded-pill bg-danger" style="font-size: 0.7rem;">${envios}</span>`
            : '';

          // 💛 Burbuja amarilla con chulito si firma no está vacía
          const badgeConfi = row.firma !== ''
            ? `<span class="position-absolute top-0 start-100 translate-middle badge rounded-pill bg-warning text-success" style="font-size: 0.8rem;">
                <i class="bi bi-check-lg"></i>
              </span>`
            : '';

          // 🧩 Agregar espacio entre botones con gap y display flex
          return `
            <div class="d-flex justify-content-center align-items-center" style="gap: 5px;">
              <button class="btn btn-sm btn-primary btn-ver-pdf position-relative" data-json="${jsonData}">
                <i class="bi bi-file-earmark-pdf"></i> Ver
                ${badgeConfi}
              </button>
              <button class="btn btn-sm btn-success btn-enviar-comprobante position-relative"
                      data-id="${row.idhojadevida || ''}"
                      data-celular="${row.celular || ''}"
                      data-correo="${row.correo || ''}"
                      data-json="${jsonData}">
                <i class="bi bi-send"></i> Enviar
                ${badge}
              </button>
            </div>
          `;
        }
      },
      { 
        data: null,
        render: function (data, type, row) {
          let firmaExamen = '';
          let confiExamen = '';

          // ✅ Verificar si el campo existe y parsearlo
          if (row.liq_docLiqui) {
            try {
              const datosLiqui = JSON.parse(row.liq_docLiqui);
              firmaExamen = datosLiqui.firma_examenes || '';
              confiExamen = datosLiqui.examenes || '';
            } catch (e) {
              console.error('Error al parsear liq_docLiqui:', e);
            }
          }

          // ✅ Crear objeto infoExamen con datos combinados
          const infoExamen = {
            nombre: row.nombre_completo || '',
            cedula: row.hoj_cedula || '',
            fecha_examen: row.fecha_examen || '',
            tipo_examen: row.tipo_examen || '',
            resultado_examen: row.resultado_examen || '',
            observaciones: row.observaciones_examen || '',
            cargo: row.car_cargo || '',
            empresa: row.empresa || '',
            firma:  firmaExamen || '',
            confiExamen: confiExamen || ''
          };

          const jsonExamen = encodeURIComponent(JSON.stringify(infoExamen));
          const examenes = parseInt(row.liq_enviosEx || 0);

          // 🔴 Burbuja roja para "Enviar"
          const badge1 = examenes > 0
            ? `<span class="position-absolute top-0 start-100 translate-middle badge rounded-pill bg-danger" style="font-size: 0.7rem;">${examenes}</span>`
            : '';

          // 💛 Burbuja amarilla con chulito si confiExamen no está vacío
          const badgeConfi = confiExamen !== ''
            ? `<span class="position-absolute top-0 start-100 translate-middle badge rounded-pill bg-warning text-success" style="font-size: 0.8rem;">
                <i class="bi bi-check-lg"></i>
              </span>`
            : '';

          // ✅ Botones con separación
          return `
            <div class="d-flex align-items-center justify-content-center" style="gap: 6px;">
              <button class="btn btn-sm btn-primary btn-ver-examen position-relative" data-json="${jsonExamen}">
                <i class="bi bi-file-earmark-medical"></i> Ver Examen
                ${badgeConfi}
              </button>
              <button class="btn btn-sm btn-success btn-enviar-examen position-relative"
                      data-id="${row.idhojadevida || ''}"
                      data-celular="${row.celular || ''}"
                      data-correo="${row.correo || ''}">
                <i class="bi bi-send"></i> Enviar
                ${badge1}
              </button>
            </div>
          `;
        }
      },
      {
        data: null,
        render: function (data, type, row) {
          const id = row.idhojadevida;
          const valor = Number(row.EstadoLiquidacion);

          let selectHtml = '';
          if (valor === 1) {
            selectHtml = `
              <select class="form-select form-select-sm estado-liquidacion bg-success text-white" data-id="${id}">
                <option value="0">No</option>
                <option value="1" selected>Sí</option>
              </select>
            `;
          } else {
            selectHtml = `
              <select class="form-select form-select-sm estado-liquidacion bg-danger text-white" data-id="${id}">
                <option value="0" selected>No</option>
                <option value="1">Sí</option>
              </select>
            `;
          }

          return selectHtml;
        }
      },
      {
        data: 'comprobante',
        render: function (data, type, row) {
          if (!data) {
            return '';
          } else {
            return `
              <button 
                class="btn btn-sm btn-outline-primary" 
                onclick="verComprobante('${data}')">
                <i class="bi bi-eye"></i> Ver comprobante
              </button>
            `;
          }
        }
      }
    ],

    createdRow: function (row, data, dataIndex) {
      if (data.hoj_fechaFinal) {
        const fechaFinal = new Date(data.hoj_fechaFinal);
        const hoy = new Date();

        // Normalizar horas para comparar solo fechas
        hoy.setHours(0, 0, 0, 0);
        fechaFinal.setHours(0, 0, 0, 0);

        // Calcular diferencia en milisegundos y convertir a días
        const diferenciaDias = (hoy - fechaFinal) / (1000 * 60 * 60 * 24);

        // Si ya pasaron 3 días o más
        if (diferenciaDias >= 3 && data.EstadoLiquidacion != 1) {
          $(row).addClass('fila-vencida');
        }
      }
    }
  });

  // Filtros
  $('#filtroAnio, #filtroCiudad,#filtroOperador,#filtroEstado').on('change', function () {
    tabla.ajax.reload();
  });

  // 🟡 Manejar los checkboxes
  $('#tablaLiquidaciones tbody').on('change', '.chk-liquidacion', function () {
    const fila = tabla.row($(this).closest('tr')).data();
    const id = fila.idhojadevida;
    const checkbox = this;

    if (checkbox.checked) {
      // ✅ Verificar si el ID está liquidado antes de agregarlo
      $.ajax({
        url: '/nueva_plataforma/controller/LiquidacionesController.php',
        type: 'POST',
        data: {
          accion: 'verificarLiquidado',
          idhojadevida: id
        },
        dataType: 'json',
        success: function (response) {
          if (response.success && response.liquidado) {
            // Si está liquidado, agregarlo al array
            if (!seleccionados.includes(id)) {
              seleccionados.push(id);
            }
            console.log('Seleccionados:', seleccionados);
          } else {
            // ❌ No está liquidado
            alert('Esta persona aún no está liquidada.');
            checkbox.checked = false;
          }
        },
        error: function () {
          alert('Error al verificar el estado de la liquidación.');
          checkbox.checked = false;
        }
      });
    } else {
      // Si se desmarca, quitar del array
      seleccionados = seleccionados.filter(item => item !== id);
      console.log('Seleccionados:', seleccionados);
    }
  });


  // Agrega al inicio del <thead>
  $('#tablaLiquidaciones thead tr').prepend('<th><input type="checkbox" id="chk-todos"></th>');

  // Al cambiar el general, marcar todos los demás
  $('#tablaLiquidaciones thead').on('change', '#chk-todos', function() {
    const isChecked = this.checked;
    $('.chk-liquidacion', tabla.rows().nodes()).prop('checked', isChecked).trigger('change');
  });


  //para ver el Desprendible de liquidacion
  $(document).on('click', '.btn-ver-pdf', function() {
    const jsonData = $(this).data('json');
    const datos = JSON.parse(decodeURIComponent(jsonData));

    // Crear un objeto FormData y agregar los datos
    const formData = new FormData();
    for (const key in datos) {
      formData.append(key, datos[key]);
    }

    // Enviar por POST y abrir el PDF
    fetch('../view/Pdfs/DesprendibleLiquidacion.php', {
      method: 'POST',
      body: formData
    })
    .then(response => response.blob())
    .then(blob => {
      const url = URL.createObjectURL(blob);
      window.open(url, '_blank'); // abrir en nueva pestaña
    })
    .catch(error => console.error('Error al generar PDF:', error));
  });

  // Función para aplicar color al select según el valor
  function aplicarColorSelect($select, valor) {
    $select.removeClass('bg-success bg-danger text-white');
    if (Number(valor) === 1) {
      $select.addClass('bg-success text-white'); // verde: liquidado
    } else {
      $select.addClass('bg-danger text-white'); // rojo: por liquidar
    }
  }

  // Cada vez que se redibuja la tabla (por filtros o carga AJAX)
  $('#tablaLiquidaciones').on('draw.dt', function () {
    $('.estado-liquidacion').each(function () {
      aplicarColorSelect($(this), $(this).val());
    });
  });

  // ✅ Manejo del cambio de estado con envío de todos los datos
  $(document).on('change', '.estado-liquidacion', function () {
    const $select = $(this);
    const nuevoEstado = $select.val();
    const id = $select.data('id');
    const row = tabla.row($select.closest('tr')).data();

    aplicarColorSelect($select, nuevoEstado); // cambia color visual inmediatamente

    // Crear objeto con todos los datos del form
    const datos = {
      accion: 'actualizarEstadoLiquidacion',
      idLiquidado: row.idLiquidado,
      idQuienLiqd: row.idLiquidado,
      liquidado: nuevoEstado,
      idhojadevida: row.idhojadevida,
      nombre: row.nombre_completo || '',
      cedula: row.hoj_cedula || '',
      fecha_ingreso: row.hoj_fechaInicial || '',
      fecha_retiro: row.hoj_fechaFinal || '',
      dias_trabajados: row.diasEfectivos || 0,
      dias_cesantias: row.diasEfectivos || 0,
      dias_prima1: row.diasEfectivosPrimas1 || 0,
      dias_prima2: row.diasEfectivosPrimas2 || 0,
      dias_vacaciones: row.diasAPagarVacaciones || 0,
      sueldobasico: row.car_salario || 0,
      transporte: row.car_Auxilio || 0,
      cesantias: row.valor_cesantias || 0,
      intereses: row.intereses_cesantias || 0,
      prima: row.valor_prima || 0,
      vacaciones: row.valor_vacaciones || 0,
      valor_total: row.valorTotalLiquidar || 0,
      cargo: row.car_cargo || '',
      valorTotalDevengado: row.valorTotalDevengado || 0,
      dias_noTrabajados: row.dias_noTrabajados || 0,
      valorVacacionesCompletas: row.valorVacacionesCompletas || 0,
      valorDeudas: row.valorDeudas || 0,
      valorVacacionestomadas: row.valorVacacionestomadas || 0,
      dias_vacacionesTomadas: row.dias_vacaciones || 0,
      firma: row.firma || '',
      cant_vacaciones_tomadas: row.cant_vacaciones_tomadas || 0,
      dias_sanciones: row.dias_sanciones || 0


      
    };

    // Enviar al servidor
    $.ajax({
      url: '/nueva_plataforma/controller/LiquidacionesController.php',
      type: 'POST',
      data: datos,
      success: function (response) {
        console.log('✅ Estado y datos enviados:', response);
        // Mostrar alerta si quieres
        
        Swal.fire({ icon: 'success', title: 'Actualizado correctamente', timer: 1200, showConfirmButton: false });
        $('#tablaLiquidaciones').DataTable().ajax.reload();
      },
      error: function (xhr, status, error) {
        console.error('❌ Error al actualizar:', error);
        alert('Error al actualizar el estado');
        // opcional: revertir cambio visual si hay error
        $select.val(nuevoEstado === '1' ? '0' : '1');
        aplicarColorSelect($select, $select.val());
      }
    });
  });
});

$('#formComprobante').on('submit', function (e) {
  e.preventDefault();

  let formData = new FormData(this);

  // 🔹 Agregamos los IDs seleccionados
  formData.append('seleccionados', JSON.stringify(seleccionados));

  // 🔹 Agregamos una acción para que el controlador sepa qué método llamar
  formData.append('accion', 'subirComprobante');

  $.ajax({
    url: '/nueva_plataforma/controller/LiquidacionesController.php',
    type: 'POST',
    data: formData,
    contentType: false, // necesario para enviar archivos
    processData: false, // no procesar los datos (FormData se maneja crudo)
    success: function (response) {
      console.log('✅ Respuesta del servidor:', response);

      Swal.fire({
        icon: 'success',
        title: 'Comprobante subido correctamente',
        timer: 1500,
        showConfirmButton: false
      });

      // Cerrar modal si estás usando Bootstrap
      const modal = bootstrap.Modal.getInstance(document.getElementById('modalComprobante'));
      modal.hide();
      $('#tablaLiquidaciones').DataTable().ajax.reload();
    },
    error: function (xhr, status, error) {
      console.error('❌ Error al subir el comprobante:', error);
      Swal.fire({ icon: 'error', title: 'Error al subir el comprobante' });
    }
  });
});

$('#filtroCiudad').on('change', function () {
  let ciudadId = $(this).val();

  // Limpio los operadores
  $('#filtroOperador').html('<option value="">Cargando...</option>');

  if (ciudadId) {
    $.ajax({
      url: '/nueva_plataforma/controller/DescargasOficinaController.php',
      type: 'POST',
      data: {
        accion: 'listarOperadoresPorCiudad',
        ciudad: ciudadId
      },
      dataType: 'json',
      success: function (operadores) {
        let opciones = '<option value="">Seleccione...</option>';
        operadores.forEach(op => {
          opciones += `<option value="${op.idusuarios}">${op.usu_nombre}</option>`;
        });
        $('#filtroOperador').html(opciones);
      },
      error: function () {
        $('#filtroOperador').html('<option value="">Error cargando operadores</option>');
      }
    });
  } else {
    $('#filtroOperador').html('<option value="">Seleccione...</option>');
  }

  // recargo tabla también cuando cambia ciudad
  $('#tablaLiquidaciones').DataTable().ajax.reload();
});

function verComprobante(nombreArchivo) {
  const ruta = `/nueva_plataforma/uploads/comprobantesLiqui/${nombreArchivo}`;

  // Insertamos el archivo dentro del iframe del modal
  document.getElementById('iframeComprobante').src = ruta;

  // Mostramos el modal
  const modal = new bootstrap.Modal(document.getElementById('modalVerComprobante'));
  modal.show();
}


// 📦 Detectar clic en el botón "Enviar"
$(document).on('click', '.btn-enviar-comprobante', function () {
  const celular = $(this).data('celular') || '';
  const correo = $(this).data('correo') || '';
  const idhojadevida = $(this).data('id') || '';
  const jsonData = $(this).data('json') || '';

  // llenar el modal
  $('#celular').val(celular);
  $('#correo').val(correo);
  $('#idhojadevida').val(idhojadevida);
  $('#jsonData').val(jsonData); // ← añadimos esto

  // abrir modal
  $('#modalEnviarComprobante').modal('show');
});


// 📩 Enviar correo
$('#formEnviarComprobante').on('submit', function (e) {
  e.preventDefault();

  const datos = {
    ajax: true,
    accion: 'enviarComprobanteCorreo',
    idhojadevida: $('#idhojadevida').val(),
    celular: $('#celular').val(),
    correo: $('#correo').val(),
    jsonData: $('#jsonData').val() // ← se envía el JSON codificado
  };

  $.ajax({
    url: '/nueva_plataforma/controller/LiquidacionesController.php',
    type: 'POST',
    data: datos,
    success: function (response) {
      Swal.fire({
        icon: 'success',
        title: 'Comprobante enviado correctamente',
        timer: 1500,
        showConfirmButton: false
      });
      $('#modalEnviarComprobante').modal('hide');
    },
    error: function (xhr, status, error) {
      Swal.fire({
        icon: 'error',
        title: 'Error al enviar comprobante',
        text: error
      });
    }
  });
});

// 💬 Enviar por WhatsApp
$('#btnEnviarWhatsapp').on('click', function () {
  const datos = {
    ajax: true,
    accion: 'enviarComprobanteCelular',
    idhojadevida: $('#idhojadevida').val(),
    celular: $('#celular').val(),
    correo: $('#correo').val(),
    jsonData: $('#jsonData').val() // ← también se envía aquí
  };

  $.ajax({
    url: '/nueva_plataforma/controller/LiquidacionesController.php',
    type: 'POST',
    data: datos,
    success: function (response) {
      Swal.fire({
        icon: 'success',
        title: 'Comprobante enviado correctamente',
        timer: 1500,
        showConfirmButton: false
      });
      $('#modalEnviarComprobante').modal('hide');
    },
    error: function (xhr, status, error) {
      Swal.fire({
        icon: 'error',
        title: 'Error al enviar comprobante',
        text: error
      });
    }
  });
});
 


// 👀 Ver examen médico (generar y abrir el PDF)
$(document).on('click', '.btn-ver-examen', function () {
  const jsonData = $(this).data('json');
  const datos = JSON.parse(decodeURIComponent(jsonData));

  // Crear un objeto FormData con los datos
  const formData = new FormData();
  for (const key in datos) {
    formData.append(key, datos[key]);
  }

  // Enviar por POST y abrir el PDF
  fetch('../view/Pdfs/ExamenesMedicosLiqui.php', {
    method: 'POST',
    body: formData
  })
  .then(response => response.blob())
  .then(blob => {
    const url = URL.createObjectURL(blob);
    window.open(url, '_blank'); // Abrir el PDF en nueva pestaña
  })
  .catch(error => console.error('Error al generar PDF:', error));
});

// 📤 Enviar examen médico (abrir modal)
$(document).on('click', '.btn-enviar-examen', function () {
  const id = $(this).data('id');
  const correo = $(this).data('correo');
  const celular = $(this).data('celular');

  // Rellenamos los datos en el modal
  $('#idhojadevidaExamen').val(id);
  $('#correoExamen').val(correo);
  $('#celularExamen').val(celular);

  // Mostramos el modal
  $('#modalEnviarExamen').modal('show');
});




// 📩 Enviar examen médico por correo
$('#formEnviarExamen').on('submit', function (e) {
  e.preventDefault();

  const datos = {
    ajax: true,
    accion: 'enviarExamenesCorreo',
    idhojadevida: $('#idhojadevidaExamen').val(),
    celular: $('#celularExamen').val(),
    correo: $('#correoExamen').val()
  };

  $.ajax({
    url: '/nueva_plataforma/controller/LiquidacionesController.php',
    type: 'POST',
    data: datos,
    success: function (response) {
      Swal.fire({
        icon: 'success',
        title: 'Examen médico enviado correctamente por correo',
        timer: 1500,
        showConfirmButton: false
      });
      $('#modalEnviarExamen').modal('hide');
    },
    error: function (xhr, status, error) {
      Swal.fire({
        icon: 'error',
        title: 'Error al enviar examen médico por correo',
        text: error
      });
    }
  });
});


// 💬 Enviar examen médico por WhatsApp
$('#btnEnviarExamenWhatsapp').on('click', function () {
  const datos = {
    ajax: true,
    accion: 'enviarExamenesCelular',
    idhojadevida: $('#idhojadevidaExamen').val(),
    celular: $('#celularExamen').val(),
    correo: $('#correoExamen').val()
  };

  $.ajax({
    url: '/nueva_plataforma/controller/LiquidacionesController.php',
    type: 'POST',
    data: datos,
    success: function (response) {
      Swal.fire({
        icon: 'success',
        title: 'Examen médico enviado correctamente por WhatsApp',
        timer: 1500,
        showConfirmButton: false
      });
      $('#modalEnviarExamen').modal('hide');
    },
    error: function (xhr, status, error) {
      Swal.fire({
        icon: 'error',
        title: 'Error al enviar examen médico por WhatsApp',
        text: error
      });
    }
  });
});









</script>
</body>
</html>
