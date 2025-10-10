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
  <title>Validar Guías Enviadas</title>
  <meta name="viewport" content="width=device-width, initial-scale=1">
  <link rel="shortcut icon" href="../../images/Logo Google Nuevo.png">
  <!-- Bootstrap 5 CSS -->
  <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
  <!-- DataTables -->
  <link rel="stylesheet" href="https://cdn.datatables.net/1.13.6/css/dataTables.bootstrap5.min.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/5.15.4/css/all.min.css">

  <style>
    .mi-header {
      background-color: #00458D;
      color: white;
    }
    thead.azul-blanco th {
      background-color: #01468c;
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

    #lightboxOverlay {
    display: none; /* oculto por defecto */
    position: fixed;
    top: 0; left: 0;
    width: 100%; height: 100%;
    background: rgba(0,0,0,0.8);
    z-index: 9999;

    /* 👇 centrado con flexbox */
    display: flex;
    align-items: center;
    justify-content: center;
    }

    #lightboxImage {
    max-height: 90%;
    max-width: 90%;
    border-radius: 10px;
    box-shadow: 0 0 20px rgba(0,0,0,0.5);
    }
    /* Clase aplicada a cada TD de la fila alerta */
    .row-alerta-td {
      background-color: #f8d7da !important; /* rojo suave, agradable */
      color: #721c24 !important;            /* texto oscuro para contraste */
    }

    /* Por si quieres aplicar también al TR en otros casos */
    .row-alerta-td:hover {
      /* nada especial, pero evita que hover cambie el color a algo feo */
    }
  </style>
</head>
<body>
  <!-- Barra superior -->
  <nav class="navbar navbar-expand-lg topbar" style="background:#002f6c; color:white;">
    <div class="container-fluid">
      <button class="btn btn-light" onclick="history.back()">⬅ Volver</button>
      <span class="navbar-brand ms-3 text-white"></span>
    </div>
  </nav>

  <div class="container-fluid mt-4">
    <!-- FILTROS -->
    <div class="card shadow p-3 mb-4 bg-body rounded">
      <div class="card-header text-center mi-header">
        <h3 class="mb-0">Validar Guias</h3>
      </div>
      <div class="card-body">
        <div class="row g-3 align-items-end">
          <!-- <div class="col-md-3">
            <label class="form-label">📅 Fecha</label>
            <input type="date" id="fechaBusqueda" class="form-control" value="<?= date('Y-m-d') ?>">
          </div> -->

            <div class="col-md-4">
                <label class="form-label">Sede Destino (*)</label>
                <select name="sedeDestino" id="sedeDestino" class="form-select" >
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
          <div class="col-md-3">
            <label class="form-label">Sede Origen</label>
            <select id="sedeOrigen" class="form-select">
              <option>Seleccione...</option>
                <?php foreach($ciudador as $d): ?>
                    <?php $req ="";
                    if ($d['idsedes'] == $sede) {
                        $req="selected";
                    }?>
                    <option value="<?= $d['idsedes'] ?>" ><?= $d['sed_nombre'] ?></option>
                <?php endforeach; ?>
            </select>
          </div>


            <div class="col-md-4 text-end">
            <label class="form-label d-block invisible">Botón</label>
            <button class="btn btn-success text-white w-100" data-bs-toggle="modal" data-bs-target="#modalEscaneo">
                <i class="bi bi-qr-code-scan me-1"></i> Validar Guía
            </button>
            </div>
            <div class="col-md-4 text-end">
            <label class="form-label d-block invisible">Botón</label>
            <button class="btn btn-primary text-white w-100" data-bs-toggle="modal" data-bs-target="#modalEscaneo1">
                <i class="bi bi-qr-code-scan me-1"></i> Ratificar Guía
            </button>
            </div>
        </div>
      </div>
    </div>

    <!-- TABLAS -->
    <div class="row">
      <!-- Guías x Validar -->
        <div class="col-lg-4">
        <div class="card shadow mb-4">
            <div class="card-header d-flex justify-content-between align-items-center" style="background:#9da300; color:white;">
                <h5 class="mb-0">Guías X Validar</h5>

                <div class="d-flex align-items-center" style="gap:5px;">
                    <input type="text" id="inputGuia" class="form-control form-control-sm" placeholder="Número de guía..." style="width:180px;">
                    <input type="hidden" id="hiddenIdUsuario" value="<?= $_SESSION['id_usuario'] ?>">
                    <input type="hidden" id="hiddenIdNombre" value="<?= $_SESSION['id_nombre'] ?>">
                    <input type="hidden" id="hiddenTipoVehiculo" value="Bus">

                    <button id="btnTraerGuia" class="btn btn-sm btn-primary">
                    <i class="fas fa-plus"></i>
                    </button>
                    <!-- Botón para mostrar/ocultar tabla -->
                    <button class="btn btn-sm btn-light" id="toggleTabla">
                    <i id="iconToggle" class="fas fa-chevron-up"></i>
                    </button>

                </div>

            </div>

            <!-- Contenedor colapsable -->
            <div class="card-body table-responsive" id="tablaContainer">
            <table id="tablaXValidar" class="table table-hover table-bordered text-center align-middle">
                <thead class="azul-blanco">
                <tr>
                    <th>Fecha</th>
                    <th>Guía</th>
                    <th>Paquete</th>
                    <th>Descripción</th>
                    <th>Piezas</th>
                    <th>Llego</th>
                    
                </tr>
                </thead>
                <tbody>
                <!-- Aquí se agregan las filas dinámicamente -->
                </tbody>
            </table>
            </div>
        </div>
        </div>

      <!-- Guías Validadas -->
      <div class="col-lg-4">
        <div class="card shadow mb-4">
          <div class="card-header  d-flex justify-content-between align-items-center" style="background:#00a33a; color:white;">
            <div class="d-flex align-items-center" style="gap:5px;">
                <h5 class="mb-0">Guías Validadas</h5>
            </div>
                <!-- Botón para mostrar/ocultar tabla -->
            <button class="btn btn-sm btn-light" id="toggleTabla2">
            <i id="iconToggle2" class="fas fa-chevron-up"></i>
            </button>
          </div>
          <div class="card-body table-responsive" id="tablaContainer2">
            <table id="tablaValidadas" class="table table-hover table-bordered text-center align-middle">
              <thead class="azul-blanco">
                <tr>
                  <th>Guía</th>
                  <th>Paquete</th>
                  <th>Piezas</th>
                </tr>
              </thead>
              <tbody>
              </tbody>
            </table>
          </div>
        </div>
      </div>

      <!-- Guías Mal Enviadas -->
      <div class="col-lg-4">
        <div class="card shadow mb-4">
          <div class="card-header d-flex justify-content-between align-items-center" style="background:#0d6efd;  color:white;">
            <div class="d-flex align-items-center" style="gap:5px;">
                <h5 class="mb-0">Remesas</h5>
            </div>
            <!-- Botón para mostrar/ocultar tabla -->
            <button class="btn btn-sm btn-light" id="toggleTabla3">
            <i id="iconToggle3" class="fas fa-chevron-up"></i>
            </button>
          </div>
          <div class="card-body table-responsive" id="tablaContainer3">
            <table id="tablaRemesasOficina" class="table table-hover table-bordered text-center align-middle">
              <thead class="azul-blanco">
                <tr>
                  <th>ID</th>
                  <th>Origen</th>
                  <th>Destino</th>
                  <th>Piezas</th>
                  <th>Quien recoge</th>
                  <th>Validar</th>
                </tr>
              </thead>
              <tbody>

              </tbody>
            </table>
          </div>
        </div>
      </div>
    </div>
  </div>
<!-- Modal -->
<div id="modal">
  <img id="modal-img" src="">
</div>





<div class="modal fade" id="modalEscaneo" tabindex="-1" aria-labelledby="modalEscaneoLabel" aria-hidden="true">
  <div class="modal-dialog modal-fullscreen"> 
    <div class="modal-content">
      <div class="modal-header mi-header">
        <h5 class="modal-title" id="modalEscaneoLabel">Escanear Código</h5>
        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Cerrar"></button>
      </div>
      <div class="modal-body p-0 text-center">
        <!-- Contenedor del lector -->
        <div id="lectorQR" style="width: 100%; height: 100%;"></div>
        <p id="resultado" class="mt-3 fw-bold"></p>
      </div>
    </div>
  </div>
</div>

<!-- Modal Servicio Ya Validado (Advertencia) -->
<div class="modal fade" id="modalVerificado" tabindex="-1" aria-labelledby="modalVerificadoLabel" aria-hidden="true">
  <div class="modal-dialog modal-dialog-centered"> 
    <div class="modal-content shadow-lg border-0">
      
      <!-- Encabezado -->
      <div class="modal-header bg-warning text-dark">
        <h5 class="modal-title fw-bold" id="modalVerificadoLabel">⚠️ Servicio o Pieza Ya Validada</h5>
        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Cerrar"></button>
      </div>

      <!-- Cuerpo -->
      <div class="modal-body text-center" style="background:#fff8e1;">
        <i class="fas fa-exclamation-triangle fa-5x text-warning mb-3"></i>
        <h4 class="fw-bold text-warning">Este servicio o pieza ya fue validada previamente</h4>
        
      </div>

      <!-- Footer -->
      <div class="modal-footer justify-content-center" style="background:#fffde7;">
        <button type="button" class="btn btn-warning text-dark fw-bold" data-bs-dismiss="modal">Entendido</button>
      </div>
    </div>
  </div>
</div>


<!-- Modal Validación Guía -->
<div class="modal fade" id="modalValidarGuia" tabindex="-1" aria-labelledby="modalValidarGuiaLabel" aria-hidden="true">
  <div class="modal-dialog modal-lg modal-dialog-centered">
    <div class="modal-content shadow-lg border-0">
      
      <!-- Encabezado -->
      <div class="modal-header mi-header text-white">
        <h5 class="modal-title" id="modalValidarGuiaLabel">📦 Validar Guía</h5>
        <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal" aria-label="Cerrar"></button>
      </div>

      <!-- Cuerpo -->
      <div class="modal-body">
        <!-- Miniaturas -->
        <div class="mb-3 text-center">
        <!-- Aquí va el total de piezas -->
        <h4 id="cantidadPiezas" class="fw-bold text-danger" style="display:none;">
        </h4>
        </div>
        <!-- Miniaturas -->
        <div class="d-flex justify-content-center gap-3 mb-4">
          <img id="thumb1" src="" alt="Imagen guía 1" class="img-thumbnail shadow-sm" style="width: 120px; height: 120px; object-fit: cover; cursor: pointer; display: none;">
          <img id="thumb2" src="" alt="Imagen guía 2" class="img-thumbnail shadow-sm" style="width: 120px; height: 120px; object-fit: cover; cursor: pointer; display: none;">
        </div>



        <!-- Descripción -->
        <div class="mb-3">
          <label for="descripcion" class="form-label fw-bold">Comentario</label>
          <textarea id="hiddenDescripcion" name="hiddenDescripcion" class="form-control" rows="4"></textarea>
        </div>
                <!-- Subir imagen -->
        <div class="mb-3 text-center">
          <!-- Input oculto -->
          <input type="file" id="inputImagen" accept="image/*" style="display:none;">
          <!-- Botón visible -->
          <button type="button" class="btn btn-primary" id="btnSubirImagen">
            📷 Subir Imagen
          </button>
          <!-- Vista previa -->
          <div class="mt-3">
            <img id="previewImagen" src="" alt="Vista previa" class="img-thumbnail shadow-sm" style="width: 150px; height: 150px; object-fit: cover; display:none;">
          </div>
        </div>
      </div>

      <!-- Footer -->
      <div class="modal-footer">
        <!-- Inputs ocultos -->
        <input type="hidden" id="hiddenIdGuia">
        <input type="hidden" id="hiddenPieza">
        <input type="hidden" id="hiddenPiezas">
        <input type="hidden" id="hiddenGuia">

        <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">❌ Cancelar</button>
        <button type="button" id="btnConfirmarValidacion" class="btn btn-success">✅ Validar</button>
      </div>
    </div>
  </div>
</div>


<!-- Modal asignacion -->
<!-- Modal asignacion -->
<div class="modal fade" id="modalRatificar" tabindex="-1" aria-labelledby="modalRatificarLabel" aria-hidden="true">
  <div class="modal-dialog modal-md modal-dialog-centered">
    <div class="modal-content border-0 shadow-lg rounded-4 overflow-hidden">
      
      <!-- Header -->
      <div class="modal-header text-white py-3 justify-content-center" 
           style="background-color: #00458D;">
        <h5 class="modal-title fw-bold d-flex align-items-center m-0" id="modalRatificarLabel">
          <i class="bi bi-box-seam me-2"></i> Información de la Guía
        </h5>
        <!-- Botón de cierre a la derecha -->
        <button type="button" class="btn-close btn-close-white position-absolute end-0 me-3" data-bs-dismiss="modal"></button>
      </div>

      <!-- Body -->
      <div class="modal-body px-4 py-3">
        <div class="mb-3">
          <span class="fw-semibold text-secondary">Número de Guía:</span>
          <div class="fs-5 text-dark" id="modalNumeroGuia">--</div>
        </div>
        <div>
          <span class="fw-semibold text-secondary">Asignado a:</span>
          <div class="fs-5 text-dark" id="modalOperador">--</div>
        </div>
      </div>

      <!-- Footer -->
      <div class="modal-footer bg-light">
        <button class="btn btn-outline-secondary rounded-pill px-4" data-bs-dismiss="modal">
          <i class="bi bi-x-circle me-1"></i> Cerrar
        </button>
      </div>
    </div>
  </div>
</div>
<!-- Overlay para ver imagen en grande -->
<div id="lightboxOverlay" 
     style="display: none; position: fixed; top: 0; left: 0; width: 100%; height: 100%;
            background: rgba(0,0,0,0.8); z-index: 2000; align-items: center; justify-content: center;">
  <img id="lightboxImage" src="" class="img-fluid rounded shadow" style="max-height: 90%; max-width: 90%;">
</div>


<div class="modal fade" id="modalEscaneo1" tabindex="-1" aria-labelledby="modalEscaneoLabel" aria-hidden="true">
  <div class="modal-dialog modal-fullscreen">
    <div class="modal-content">
      <div class="modal-header mi-header">
        <h5 class="modal-title" id="modalEscaneoLabel">📷 Escanear Código</h5>
        <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
      </div>
      <div class="modal-body p-0 text-center">
        <div id="lectorQR1" style="width: 100%; height: 100%;"></div>
        <p id="resultado" class="mt-3 fw-bold"></p>
      </div>
    </div>
  </div>
</div>
                  <!--Modal Validar remesas -->
<div class="modal fade" id="modalValidarRemesas" tabindex="-1" aria-labelledby="modalValidarRemesasLabel" aria-hidden="true">
  <div class="modal-dialog modal-dialog-scrollable modal-fullscreen-lg-down">
    <form id="formValidarRemesas" class="modal-content" enctype="multipart/form-data">
      <div class="modal-header mi-header">
        <h5 class="modal-title" id="modalValidarRemesasLabel">Validar Remesa</h5>
        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Cerrar"></button>
      </div>

      <div class="modal-body">
        <div id="alertaVerificado"></div>


        <!-- Número Descripcion -->
        <div class="mb-3">
        <label for="descripcion" class="form-label">Descripción</label>
        <textarea id="descripcion" name="descripcion" class="form-control" rows="4" required></textarea>
        </div>

      

        <input type="hidden" name="id_param" id="id_param" value="">
        <input type="hidden" name="accion" id="accion" value="Verificar Remesa">
        <input type="hidden" name="usuario" id="usuario" value="<?=$_POST['usuario']?>">

      



      <div class="modal-footer">
        <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cerrar</button>
        <button type="submit" class="btn btn-primary">Guardar</button>
      </div>
    </form>
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
<link href="https://cdn.jsdelivr.net/npm/bootstrap-icons/font/bootstrap-icons.css" rel="stylesheet">
<script>
$(document).ready(function () {
  const tabla = $('#tablaXValidar').DataTable({
    ajax: {
      url: '/nueva_plataforma/controller/ValidarGuiasController.php',
      type: 'POST',
      data: function (d) {
        d.ajax = true;
        d.ciudadO = $('#sedeOrigen').val();
        d.ciudadD = $('#sedeDestino').val();
      },
      // para depurar puedes habilitar temporalmente este dataSrc y ver la respuesta en consola
      dataSrc: function(json) {
        console.log('AJAX response:', json); // <-- mira aquí la estructura y el campo tiene_piezas...
        return json;
      }
    },

    columns: [
        { data: 'ser_fechaguia' },
        { data: 'ser_consecutivo' },
        { data: 'ser_tipopaquete' },
        { data: 'ser_paquetedescripcion' },
        { data: 'numeropieza' },
        { 
            data: null,
            render: function(data, type, row) {
              return `
                <select class="form-select select-validar" 
                        data-id="${row.ser_consecutivo}" 
                        data-pieza="${row.numeropieza}">
                  <option value="">Seleccione...</option>
                  <option value="SI">Validar</option>
                </select>`;
            }
        }, 
        { data: 'tiene_piezas_llegadas', visible: false } // oculta columna
    ],

    // se ejecuta cada vez que se dibuja la fila
    rowCallback: function (row, data, index) {
        // normalizamos el valor a booleano (maneja "1", 1, true, "true")
        const flag = (String(data.tiene_piezas_llegadas).toLowerCase() === '1' ||
                      String(data.tiene_piezas_llegadas).toLowerCase() === 'true');

        if (flag) {
            // aplicamos la clase a los TD (más fiable que al TR si DataTables/Bootstrap pinta los TD)
            $('td', row).addClass('row-alerta-td');
        } else {
            $('td', row).removeClass('row-alerta-td');
        }

        // opcional: para depuración en consola
        // console.log('fila', index, 'consecutivo', data.ser_consecutivo, 'flag', flag);
    }
  });

  // recarga cuando cambias sedes
  $('#sedeOrigen,#sedeDestino').on('change', function () {
    tabla.ajax.reload();
  });


  const tablaValidadas = $('#tablaValidadas').DataTable({
    ajax: {
        url: '/nueva_plataforma/controller/ValidarGuiasController.php',
        type: 'POST',
        data: function (d) {
        d.accion = 'buscarValidadas'; // 👈 ahora sí va por POST
        d.ciudadO = $('#sedeOrigen').val();
        d.ciudadD = $('#sedeDestino').val();
        },
        dataSrc: function (json) {
        console.log("Respuesta Remesas:", json);
        return json;
        }
    },
    columns: [
        { data: 'ser_consecutivo' },
        { data: 'ser_tipopaquete' },
        { data: 'numeropieza' }

    ]
    });
   $(' #sedeOrigen,#sedeDestino').on('change', function () {
    tablaValidadas.ajax.reload();
  });


  const tablaRemesas = $('#tablaRemesasOficina').DataTable({
    ajax: {
        url: '/nueva_plataforma/controller/ValidarGuiasController.php',
        type: 'POST',
        data: function (d) {
        d.accion = 'buscarRemesas'; // 👈 ahora sí va por POST
        // d.fecha = $('#filtroFecha').val();
        d.ciudad = $('#sedeDestino').val();
        d.operador = $('#filtroOperador').val();
        },
        dataSrc: function (json) {
        console.log("Respuesta Remesas:", json);
        return json;
        }
    },
    columns: [
        { data: 'idgastos' },
        { data: 'sede_origen' },
        { data: 'sede_destino' },
        { data: 'gas_piezas' },
        { data: 'usuario_recoge' },
        {
            data: null,
            render: function (data, type, row) {
                const puedeValidar = (
                    Number(row.gas_iduserrecoge) > 0 &&
                    Number(row.gas_recogio) === 1 &&
                    String(row.gas_nomvalida).trim() === ''
                );

                if (puedeValidar) {
                    // ✅ Cumple condiciones → botón verde activo
                    return `
                        <button class="btn btn-sm btn-success validar-remesa" data-id="${row.idgastos}">
                            <i class="bi bi-check2-circle"></i> Validar
                        </button>
                    `;
                } else {
                    // 🚫 No cumple → botón rojo y deshabilitado
                    return `
                        <button class="btn btn-sm btn-danger validar-remesa" data-id="${row.idgastos}" disabled>
                            <i class="bi bi-x-circle"></i> No disponible
                        </button>
                    `;
                }
            }
        }
    ]
    });
    $('#sedeDestino,#filtroOperador').on('change', function () {
    tablaRemesas.ajax.reload();
  });
 

});






document.addEventListener("DOMContentLoaded", () => {
  const modalEscaneo = document.getElementById('modalEscaneo');
  let lector;

  modalEscaneo.addEventListener('shown.bs.modal', () => {
    lector = new Html5Qrcode("lectorQR");
    lector.start(
      { facingMode: "environment" }, 
      { fps: 10, qrbox: { width: 250, height: 250 } },
      codigo => {
        console.log("Código leído:", codigo);
        document.getElementById("resultado").innerText = "Leído: " + codigo;

        let guia = null;
        let pieza = null;

        try {
          const params = new URL(codigo).searchParams;
          guia = params.get("guia");   // ej: "BGT283634"
          pieza = params.get("pieza"); // ej: "2"
        } catch (e) {
          console.error("No es un link válido:", e);
        }

        if (guia) {
          // ✅ detener lectura
          lector.stop();

          // ✅ cerrar modal de escaneo
          const modalBootstrap = bootstrap.Modal.getInstance(modalEscaneo);
          modalBootstrap.hide();

          // ✅ ejecutar la lógica con la guía y pieza
          abrirModalValidarGuia(guia, pieza);
        } else {
          alert("No se pudo obtener la guía del código");
        }
      },
      error => {}
    ).catch(err => console.error("Error al iniciar cámara:", err));
  });

  modalEscaneo.addEventListener('hidden.bs.modal', () => {
    if (lector) {
      lector.stop().then(() => lector.clear()).catch(err => console.error(err));
    }
  });
});



$(document).on('click', '.validar-remesa', function () {
    let id = $(this).data('id');

    $('#formValidarRemesas')[0].reset();
    $('#modalValidarRemesas').modal('show');
    $('[name="id_param"]').val(id);
    

});




  function imprimirCodigos() {
    // Capturar valores de los filtros
    let operario = document.getElementById("filtroOperador").value;
    let fecha = document.getElementById("filtroFecha").value;
    let ciudad = document.getElementById("filtroCiudad").value;
    
    
    let destino = "../../phpqrcode/ticket3.php?param33=" + operario + "&param34=" + fecha + "&param36=" + ciudad + "&modulo=5";
    
    // abrir en nueva pestaña
    window.open(destino, '_blank');
  }

// 1. Abre el modal y carga la info de la guía con AJAX
function abrirModalValidarGuia(id,pieza) {
    $("#hiddenDescripcion").val(""); // textarea
  $("#inputImagen").val("");       // input file (si lo tienes)
  $.ajax({
    url: '../controller/ValidarGuiasController.php',
    type: 'GET',
    data: { accion: 'buscarServicioConGuia', id: id, pieza: pieza },
    dataType: 'json',
    success: function (servicio) {
      if (servicio) {


        actualizarTituloModal(id,pieza);
        $("#thumb1, #thumb2").hide();

        if (servicio.ser_img_recog) {
            $("#thumb1").attr("src", "../../imgServicios/" + servicio.ser_img_recog).show();
        }
        if (servicio.ser_img_recog1) { // suponiendo que hay un segundo campo en BD
            $("#thumb2").attr("src", "../../imgServicios/" + servicio.ser_img_recog1).show();
        }



        // Guardar datos en inputs ocultos para usarlos en Validar
        $("#hiddenIdGuia").val(servicio.idservicios);
        $("#hiddenPiezas").val(servicio.ser_piezas);
        
        $("#hiddenPieza").val(pieza);
        $("#hiddenGuia").val(id);
        //Para poner numero de piezas
        const cantidadPiezas = document.getElementById("cantidadPiezas");
        cantidadPiezas.textContent = " Servicio de " + servicio.ser_piezas + " piezas / Escaneadas "+ servicio.piezasEscaneadas;
        cantidadPiezas.style.display = "block";

        if (servicio.guiallega == 1) {
            $("#modalVerificado").modal("show");
        }else{
             // Mostrar modal
            $("#modalValidarGuia").modal("show");
            
            $("#previewImagen").hide().attr("src", "");
        }


      } else {
        alert("❌ No se encontró la información del servicio.");
      }
    },
    error: function () {
      alert("⚠️ Error al buscar la información del servicio.");
    }
  });
}

function actualizarTituloModal(guia,pieza) {


  // actualizo el título del modal
  const titulo = document.getElementById("modalValidarGuiaLabel");
  titulo.textContent = `📦 Validar Guía ${guia} / ${pieza}`;
}
// 2. Ejecutar validación al confirmar
$("#btnConfirmarValidacion").on("click", function () {
  let id = $("#hiddenIdGuia").val();
  let pieza = $("#hiddenPieza").val();
  let guia = $("#hiddenGuia").val();
  let piezasg = $("#hiddenPiezas").val();
  let descripcion = $("#hiddenDescripcion").val();

  // Capturamos el archivo
  let archivo = $("#inputImagen")[0].files[0];

  // Llamar la función original
  ValidaGuiaEscaner(id, pieza, descripcion, "SI", guia, archivo,piezasg);

  // Cerrar modal
  $("#modalValidarGuia").modal("hide");
});


// Enviar para efectuar escaneo
function ValidaGuiaEscaner(id, pieza, descripcion, llego, guia, archivo,piezasg) {
  console.log("ID:", id, "Pieza:", pieza);

  let id_usuario = "<?= $_POST['id_usuario'] ?>";
  let id_nombre  = "<?= $_POST['usuario'] ?>";

  // Usamos FormData para incluir la imagen
  let formData = new FormData();
  formData.append("idguia", id);
  formData.append("accion", "actualizarServicio");
  formData.append("descripcion", descripcion);
  formData.append("llego", llego);
  formData.append("piezasg", piezasg);
  formData.append("pieza", pieza);
  formData.append("guia", guia);
  formData.append("id_usuario", id_usuario);
  formData.append("id_nombre", id_nombre);

  // Adjuntar imagen solo si se seleccionó
  if (archivo) {
    formData.append("imagen", archivo);
  }

  $.ajax({
    url: "/nueva_plataforma/controller/ValidarGuiasController.php",
    type: "POST",
    data: formData,
    contentType: false, // necesario para FormData
    processData: false, // necesario para FormData
    dataType: "json",
    success: function (response) {
      console.log("Respuesta del servidor:", response);
      if (response.success) {
        alert("✅ " + response.message);
        // 🔄 Recargar DataTables
        $('#tablaValidadas').DataTable().ajax.reload(null, false);
        $('#tablaXValidar').DataTable().ajax.reload(null, false);
      } else {
        alert("❌ " + response.message);
      }
    },
    error: function (xhr, status, error) {
      console.error("Error AJAX:", error);
      alert("⚠️ Error al procesar la solicitud");
    }
  });
}

// Captura cualquier cambio en selects de la tabla
$('#tablaXValidar').on('change', '.select-validar', function () {
  const valor = $(this).val();

  if (valor === "SI") {
    const id = $(this).data("id");        // ser_consecutivo
    const pieza = $(this).data("pieza");  // numeropieza

    // Abrir modal con datos
    abrirModalValidarGuia(id, pieza);

    // Opcional: dejar el select vacío de nuevo
    $(this).val("");
  }
});



// Evento click para ampliar
$("#thumb1, #thumb2").on("click", function () {
  let src = $(this).attr("src");
  $("#lightboxImage").attr("src", src);
  $("#lightboxOverlay").fadeIn();
});

// Cerrar overlay al hacer click
$("#lightboxOverlay").on("click", function () {
  $(this).fadeOut();
});


//Funcion para traer la guia 
function traerGuia(guia, id_usuario, id_nombre, tipoVehiculo) {
  $.ajax({
    url: '../controller/ValidarGuiasController.php',
    type: 'GET', // o 'POST' si prefieres más seguro
    data: { 
      accion: 'validarGuiaYPiezas',
      guia: guia,
      id_usuario: id_usuario,
      id_nombre: id_nombre,
      tipoVehiculo: tipoVehiculo
    },
    dataType: 'json',
    success: function (respuesta) {
      if (respuesta.success) {
        alert("✅ Escaneo registrado correctamente.");
        // aquí puedes refrescar una tabla, limpiar inputs, etc.
      } else {
        alert(respuesta.msg || "❌ Ocurrió un error.");
      }
    },
    error: function () {
      alert("⚠️ Error al conectar con el servidor.");
    }
  });
}

$(document).ready(function () {
  $("#btnTraerGuia").on("click", function () {
    let guia = $("#inputGuia").val().trim();

    if (guia === "") {
      alert("⚠️ Por favor ingrese un número de guía.");
      return;
    }

    // Aquí tendrías los datos del usuario logueado (puedes cargarlos en inputs hidden)
    let id_usuario = $("#hiddenIdUsuario").val();
    let id_nombre = $("#hiddenIdNombre").val();
    let tipoVehiculo = $("#hiddenTipoVehiculo").val();

    traerGuia(guia, id_usuario, id_nombre, tipoVehiculo);
  });
});

$(document).ready(function () {
  // Detectar si es celular/tablet (ej: ancho menor a 992px → Bootstrap lg breakpoint)
  let esMovil = window.innerWidth < 992;

  // Si es móvil → ocultar tablas al inicio y poner icono hacia abajo
  if (esMovil) {
    $("#tablaContainer, #tablaContainer2, #tablaContainer3").hide();
    $("#iconToggle, #iconToggle2, #iconToggle3")
      .removeClass("fa-chevron-up")
      .addClass("fa-chevron-down");
  }

  // Función genérica para toggle
  function configurarToggle(boton, contenedor, icono) {
    $(boton).on("click", function () {
      $(contenedor).slideToggle("fast");
      $(icono).toggleClass("fa-chevron-up fa-chevron-down");
    });
  }

  // Asignar eventos
  configurarToggle("#toggleTabla", "#tablaContainer", "#iconToggle");
  configurarToggle("#toggleTabla2", "#tablaContainer2", "#iconToggle2");
  configurarToggle("#toggleTabla3", "#tablaContainer3", "#iconToggle3");
});

// para abrir input con boton 
$(document).ready(function () {
  // Cuando le das click al botón, abre el input file
  $("#btnSubirImagen").on("click", function () {
    $("#inputImagen").click();
  });

  // Mostrar vista previa al seleccionar imagen
  $("#inputImagen").on("change", function (event) {
    let file = event.target.files[0];
    if (file) {
      let reader = new FileReader();
      reader.onload = function (e) {
        $("#previewImagen").attr("src", e.target.result).show();
      };
      reader.readAsDataURL(file);
    }
  });
});


//Ratificar guia
document.addEventListener("DOMContentLoaded", () => {
  const modalEscaneo = document.getElementById('modalEscaneo1');
  let lector;

  modalEscaneo.addEventListener('shown.bs.modal', () => {
    lector = new Html5Qrcode("lectorQR1");
    lector.start(
      { facingMode: "environment" },
      { fps: 10, qrbox: { width: 250, height: 250 } },
      codigo => {
        console.log("Código leído:", codigo);
        document.getElementById("resultado").innerText = "Leído: " + codigo;

        let guia = null;
        try {
          const params = new URL(codigo).searchParams;
          guia = params.get("guia"); // ej: "BGT283634"
        } catch (e) {
          console.error("No es un link válido:", e);
        }

        if (guia) {
          // ✅ detener lector
          lector.stop();

          // ✅ cerrar modal de escaneo
          const modalBootstrap = bootstrap.Modal.getInstance(modalEscaneo);
          modalBootstrap.hide();

          // ✅ abrir modal Ratificar con AJAX
          abrirModalRatificar(guia);
        } else {
          alert("No se pudo obtener la guía del código");
        }
      },
      error => {}
    ).catch(err => console.error("Error al iniciar cámara:", err));
  });

  modalEscaneo.addEventListener('hidden.bs.modal', () => {
    if (lector) {
      lector.stop().then(() => lector.clear()).catch(err => console.error(err));
    }
  });
});


// 🚀 función para cargar datos de Ratificar
function abrirModalRatificar(guia) {
  $.ajax({
    url: '../controller/ValidarGuiasController.php',
    type: 'GET',
    data: { accion: 'buscarAsignacion', guia: guia },
    dataType: 'json',
    success: function (info) {
      if (info) {
        $("#modalNumeroGuia").text(info.ser_consecutivo || guia);
        $("#modalOperador").text(info.usu_nombre ? info.usu_nombre : "Sin asignar");

        // mostrar modal Ratificar
        $("#modalRatificar").modal("show");
      } else {
        alert("❌ No se encontró la información de la guía.");
      }
    },
    error: function () {
      alert("⚠️ Error al consultar la información de la guía.");
    }
  });
}

//Validacion de remesas
document.addEventListener("DOMContentLoaded", () => {
  const form = document.getElementById("formValidarRemesas");

  form.addEventListener("submit", function(e){
    e.preventDefault(); // evita recarga

    const formData = new FormData(form);

    // Validaciones opcionales
    const descripcion = formData.get("descripcion");
    if (!descripcion) {
      alert("Por favor debe escribir una descripción.");
      return;
    }

    $.ajax({
      url: "/nueva_plataforma/controller/ValidarGuiasController.php",
      type: "POST",
      data: formData,
      contentType: false,
      processData: false,
      dataType: "json",
      success: function(data) {
        // Reiniciar formulario
        form.reset();

        // 🔄 Recargar DataTable
        $('#tablaRemesasOficina').DataTable().ajax.reload(null, false);

        // ✅ Cerrar el modal
        $('#modalValidarRemesas').modal('hide');

        alert("Datos enviados correctamente");
      },
      error: function(xhr, status, error) {
        console.error("Error en la solicitud:", error);
        alert("Error inesperado al guardar el servicio.");
      }
    });

  });
});

</script>
</body>
</html>
