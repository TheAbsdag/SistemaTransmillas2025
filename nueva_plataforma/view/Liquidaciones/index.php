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
            <select id="filtroAnio" name="anio" class="form-control">
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

            <div class="col-md-4 text-end">
            <label class="form-label d-block invisible">Botón</label>
            <button class="btn btn-success text-white w-100" data-bs-toggle="modal" data-bs-target="#modalEscaneo">
                <i class="bi bi-qr-code-scan me-1"></i> boton 1
            </button>
            </div>
           
            <div class="col-md-4 text-end">
            <label class="form-label d-block invisible">Botón</label>
            <button class="btn btn-primary text-white w-100" onclick="imprimirCodigos()">
                <i class="bi bi-printer me-1"></i> Boton 2
            </button>
            </div>
           

        </div>

      <div class="table-responsive">
        <table id="tablaDescargasOficina" class="table table-hover table-bordered align-middle text-center">
          <thead class="table-primary">
            <tr>



                
                <th>Nombre</th>
                <th>Cédula</th>
                <th>Contrato</th>
                <th>Cargo</th>
                <th>Salario</th>
                <th>Auxilio</th>
                <th>Dias trabajados</th>
                <th>Dias no trabajados</th>
                <th>Días Prima</th>
                <th>Total Prima</th>
                <th>Confirmado</th>
                <th>Pagado</th>
                


            </tr>
          </thead>
          <tbody></tbody>
        </table>
      </div>
    </div>
    
  </div>

</div>

<!-- Modal Validar Peso -->
<div class="modal fade" id="modalValidarPeso" tabindex="-1" aria-labelledby="modalValidarPesoLabel" aria-hidden="true">
  <div class="modal-dialog modal-dialog-scrollable modal-fullscreen-lg-down">
    <form id="formValidarPeso" class="modal-content" enctype="multipart/form-data">
      <div class="modal-header mi-header">
        <h5 class="modal-title" id="modalValidarPesoLabel">Validar Peso</h5>
        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Cerrar"></button>
      </div>

      <div class="modal-body">
        <div id="alertaVerificado"></div>
        <!-- Peso -->
        <div class="mb-3">
          <label for="peso" class="form-label">Peso KG: (*)</label>
          <input type="number" step="0.01" id="peso" name="peso" class="form-control" required>
        </div>

        <!-- Volumen -->
        <div class="mb-3">
          <label for="volumen" class="form-label">Volumen</label>
          <input type="number" step="0.01" id="volumen" name="volumen" class="form-control">
        </div>

        <!-- Piezas -->
        <div class="mb-3">
          <label for="piezas" class="form-label">Piezas</label>
          <input type="number" step="0.01" id="piezas" name="piezas" class="form-control">
        </div>

        <!-- Estado paquete -->
        <div class="mb-3">
          <label for="estado" class="form-label">Estado paquete</label>
          <select id="estado" name="estado" class="form-select" required>
            <option value="Bueno">Bueno</option>
            <option value="Dañado">Dañado</option>
            <option value="Revisar">Revisar</option>
          </select>
        </div>

        <!-- Número de guía -->
        <div class="mb-3">
          <label for="guia" class="form-label"># Guía</label>
          <input type="text" id="guia" name="guia" class="form-control" required>
        </div>

        <!-- Foto de la guía -->
        <!-- <div class="mb-3 d-none">
        <label for="foto" class="form-label">Foto Guía</label>
        <input type="file" id="foto" name="foto" class="form-control" accept="image/*">
        </div> -->

        <!-- Verificado -->
        <div class="mb-3">
          <label for="verificado" class="form-label">Verificar (*)</label>
          <select id="verificado" name="verificado" class="form-select" required>
            <option value="">Seleccione...</option>
            <option value="1">Verificado</option>
          </select>
        </div>

        <input type="hidden" name="id_param" id="id_param" value="">
        <input type="hidden" name="id_param2" id="id_param2" value="">
        <input type="hidden" name="clasificacion" id="clasificacion" value="">
        <input type="hidden" name="caso" id="caso" value="2">
        <input type="hidden" name="param5" id="param5" value="">
        <input type="hidden" name="param16" id="param16" value="">
        <input type="hidden" name="tipoServicio" id="tipoServicio" value="">
        

        <!-- Galería de imágenes -->
        <div class="mb-3">
          <label class="form-label">Galería de imágenes</label>
            <div class="d-flex flex-wrap gap-3 align-items-start">
            <!-- Imagen 1 -->
            <img id="img1" src="" class="img-thumbnail" alt="" style="max-width: 150px;">

            <!-- Imagen 2 con botón actualizar -->
            <div class="d-flex flex-column align-items-center">
                <img id="img2" src="ruta/imagen2.jpg" class="img-thumbnail mb-2" alt="Imagen 2" style="max-width: 150px;">
                
                <!-- Botón que abre el input file oculto -->
                <label for="foto" class="btn btn-warning w-100 text-white" style="max-width: 150px;">
                <i class="bi bi-upload me-1"></i> Actualizar
                </label>
                <input type="file" name="foto" id="foto" class="d-none" accept="image/*">
            </div>
            </div>

        </div>
      </div>

      <div class="modal-footer">
        <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cerrar</button>
        <button type="submit" class="btn btn-primary">Guardar</button>
      </div>
    </form>
  </div>
</div>
<!-- Modal -->
<div id="modal">
  <img id="modal-img" src="">
</div>
<!-- Modal escaner -->
<div class="modal fade" id="modalEscaneo" tabindex="-1" aria-labelledby="modalEscaneoLabel" aria-hidden="true">
  <div class="modal-dialog modal-fullscreen"> <!-- 👈 Aquí está el truco -->
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

<script>
$(document).ready(function () {
//   const tabla = $('#tablaDescargasOficina').DataTable({
//     ajax: {
//       url: '/nueva_plataforma/controller/DescargasOficinaController.php',
//       type: 'POST',
//       data: function (d) {
//         d.ajax = true;
//         d.fecha = $('#filtroFecha').val();
//         d.ciudad = $('#filtroCiudad').val();
//         d.operador = $('#filtroOperador').val();
//         d.creditos = $('#filtroCreditos').val();
        
//       },
//       dataSrc: ''
//     },
//     columns: [
//         { data: 'ser_fechafinal' },
//         { data: 'cli_nombre' },
//         { data: 'cli_direccion' },
//         { data: 'ser_destinatario' },
//         { data: 'ciu_nombre' },
//         { data: 'ser_direccioncontacto' },
//         { data: 'ser_paquetedescripcion' },
//         { data: 'ser_piezas' },
//         { data: 'usu_nombre' },
//         { data: 'ser_clasificacion' },
//         { data: 'ser_consecutivo' },
//         { data: 'ser_estado' },
//         {
//             data: 'idservicios',
//             render: function (data) {
//             return `<button class="btn btn-sm btn-warning pesar-paquete" data-id="${data}">
//                 <i class="bi bi-box"></i> Pesar
//             </button>`;

//             }
//         }
        



        

 
//       ]
//   });
//    $('#filtroFecha, #filtroCiudad,#filtroOperador,#filtroCreditos').on('change', function () {
//     tabla.ajax.reload();
//   });


//   const tablaRemesas = $('#tablaRemesasOficina').DataTable({
//     ajax: {
//         url: '/nueva_plataforma/controller/DescargasOficinaController.php',
//         type: 'POST',
//         data: function (d) {
//         d.accion = 'buscarRemesas'; // 👈 ahora sí va por POST
//         d.fecha = $('#filtroFecha').val();
//         d.ciudad = $('#filtroCiudad').val();
//         d.operador = $('#filtroOperador').val();
//         },
//         dataSrc: function (json) {
//         console.log("Respuesta Remesas:", json);
//         return json;
//         }
//     },
//     columns: [
//         { data: 'sede_origen' },
//         { data: 'sede_destino' },
//         {
//         data: null,
//         render: function (data) {
//             return data.gas_empresa + ' ' + data.gas_bus;
//         }
//         },
//         { data: 'gas_telconductor' },
//         { data: 'gas_pagar' },
//         { data: 'gas_descripcion' },
//         { data: 'gas_peso' },
//         { data: 'gas_piezas' },
//         { data: 'gas_usucom' },
//         { data: 'gas_valor' },
        
//         { data: 'gas_feccom' },
//         { data: 'gas_cantcom' },
//         { data: 'gas_fecrecogida' },
//         { data: 'usuario_recoge' },
//         {
//         data: 'idgastos',
//         render: function (data) {
//             return `<button class="btn btn-sm btn-success validar-remesa" data-id="${data}">
//                     <i class="bi bi-check2-circle"></i> Validar
//                     </button>`;
//         }
//         }
//     ]
//     });
//     $('#filtroFecha, #filtroCiudad,#filtroOperador').on('change', function () {
//     tablaRemesas.ajax.reload();
//   });

 

});




$(document).on('click', '.pesar-paquete', function () {
    let id = $(this).data('id');

    $('#formValidarPeso')[0].reset();
    $('#modalValidarPeso').modal('show');

  $.ajax({
    url: '../controller/DescargasOficinaController.php',
    type: 'GET',
    data: { accion: 'buscarServicio', id: id },
    dataType: 'json',
    success: function (servicio) {
        

      if (servicio) {
        $('[name="peso"]').val(servicio.ser_peso);
        $('[name="volumen"]').val(servicio.ser_volumen);
        $('[name="estado"]').val(servicio.ser_descripcion);
        $('[name="guia"]').val(servicio.ser_guiare);
        $('[name="piezas"]').val(servicio.ser_piezas);

        $('[name="id_param"]').val(id);
        $('[name="id_param2"]').val(id);
        
        $('[name="caso"]').val(servicio.ser_guiare);
        $('[name="param5"]').val(servicio.cli_idciudad);
        $('[name="param16"]').val(servicio.ser_ciudadentrega);


        $('[name="verificado"]').val(servicio.ser_idverificadopeso);
        $('[name="tipoServicio"]').val(servicio.gui_tiposervicio);
        if (servicio.gui_tiposervicio==1000) {
          $('[name="peso"]').prop('required', false);
          $('label[for="peso"]').html(function(_, oldHtml) {
            return oldHtml.replace(
              /\(\*\)/g,
              '<span style="font-size:0.8em; color:#6c757d; font-style:italic;">(a convenir)</span>'
            );
          });
        }
        

            if (servicio.ser_clasificacion == 1 && servicio.ser_pendientecobrar == 0) {
                clasificacion = 1;
            } else if (servicio.ser_clasificacion == 2) {
                clasificacion = 2;
            } else {
                clasificacion = 0;
            }

        $('[name="clasificacion"]').val(clasificacion);


        let img1 = document.getElementById("img1");
        let img2 = document.getElementById("img2");
        let modal = document.getElementById("modal");
        let modalImg = document.getElementById("modal-img");

        // 🔹 Primero limpiamos cualquier src previo
        img1.src = "";
        img2.src = "";

        // 🔹 Ahora asignamos las rutas nuevas
        img1.src = '../../imagesguias/_Recogida.jpg';
        img2.src = "../../imgServicios/"+servicio.ser_img_recog;


        // 🔹 URL que abrirá la imagen 1
        let urlPagina = servicio.ima_ruta+'&vis=adm'; // aquí pones tu PHP/HTML real

        // 🔹 Click en img1 → abrir página en ventana emergente
        img1.addEventListener("click", () => {
            window.open(
            urlPagina,
            "VentanaEmergente",
            "width=800,height=600,top=100,left=100,resizable=yes,scrollbars=yes"
            );
        });

        // Click imagen 2
        img2.addEventListener("click", () => {
            if (img2.src) { // solo si tiene imagen
            modal.style.display = "flex";
            modalImg.src = img2.src;
            }
        });

        // Cerrar modal al hacer click en el fondo
        modal.addEventListener("click", () => {
            modal.style.display = "none";
            modalImg.src = ""; // 🔹 limpiar al cerrar
        });
        



      } else {
        alert('No se encontro la info del servicio.');
      }
    },
    error: function () {
      alert('Error al buscar la info del servicio.');
    }
  });
});



document.addEventListener("DOMContentLoaded", () => {
  const form = document.getElementById("formValidarPeso");

  form.addEventListener("submit", function(e){
    e.preventDefault(); // evita recarga

    // Capturar valores
    const peso   = document.getElementById("peso").value.trim();
    const piezas = document.getElementById("piezas").value.trim();
    const tipoServicio = document.getElementById("tipoServicio").value.trim();

    //  Validar antes de enviar
    if ((peso === "" || parseFloat(peso) <= 0 ) && tipoServicio != 1000 ) {
      alert("⚠️ Debe ingresar el peso ");
      document.getElementById("peso").focus();
      return; // no continúa
    }


    // Capturar los demás valores
    const volumen    = document.getElementById("volumen").value;
    const estado     = document.getElementById("estado").value;
    const guia       = document.getElementById("guia").value;
    const verificado = document.getElementById("verificado").value;
    const foto       = document.getElementById("foto").files[0]; // archivo
    const id_param   = document.getElementById("id_param").value;
    const id_param2  = document.getElementById("id_param2").value;
    const clasificacion  = document.getElementById("clasificacion").value;
    const caso  = document.getElementById("caso").value;
    const param5  = document.getElementById("param5").value;
    const param16  = document.getElementById("param16").value;

    // Armar FormData con NOMBRES PERSONALIZADOS
    let data = new FormData();
    data.append("param1", peso);
    data.append("param4", volumen);
    data.append("cantidad_piezas", piezas);
    data.append("param2", estado);
    data.append("param6", guia);
    data.append("param3", verificado);
    data.append("param10", foto); // archivo
    data.append("id_param", id_param);
    data.append("id_param2", id_param2);
    data.append("caso", "2"); // fijo
    data.append("clasificacion", clasificacion); // fijo
    data.append("param5", param5); // fijo
    data.append("param16", param16); // fijo
    data.append("tabla", "validapeso"); // fijo

    // Enviar por fetch
    fetch("../../PesarNv.php", {
      method: "POST",
      body: data
    })
    .then(res => res.text())
    .then(respuesta => {
        console.log("Respuesta servidor:", respuesta);
        
        form.reset(); // opcional
        // 🔄 Recargar DataTable inmediatamente
        $('#tablaDescargasOficina').DataTable().ajax.reload(null, false);
        // ✅ Cerrar el modal
        $('#modalValidarPeso').modal('hide');
        alert("Datos enviados correctamente");
    })
    .catch(err => console.error("Error:", err));
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
  $('#tablaDescargasOficina').DataTable().ajax.reload();
});
 
let lector;

document.addEventListener("DOMContentLoaded", () => {
  const modalEscaneo = document.getElementById('modalEscaneo');

  modalEscaneo.addEventListener('shown.bs.modal', () => {
    lector = new Html5Qrcode("lectorQR");
    lector.start(
      { facingMode: "environment" }, 
      { fps: 10, qrbox: { width: 250, height: 250 } },
        codigo => {
        console.log("Código leído:", codigo);
        document.getElementById("resultado").innerText = "Leído: " + codigo;

        // ✅ Extraer solo la guía del link
        let guia = null;
        try {
            const params = new URL(codigo).searchParams;
            guia = params.get("guia"); // ej: "BGT283634"
        } catch (e) {
            console.error("No es un link válido:", e);
        }

        if (guia) {
            // ✅ detener lectura
            lector.stop();

            // ✅ cerrar modal de escaneo
            const modalBootstrap = bootstrap.Modal.getInstance(modalEscaneo);
            modalBootstrap.hide();

            // ✅ ejecutar la lógica con la guía
            abrirModalValidarPeso(guia);
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
// 🔹 Función que replica la lógica del click en .pesar-paquete
function abrirModalValidarPeso(id) {
  $('#formValidarPeso')[0].reset();
  $('#modalValidarPeso').modal('show');

  $.ajax({
    url: '../controller/DescargasOficinaController.php',
    type: 'GET',
    data: { accion: 'buscarServicioPorGuia', id: id },
    dataType: 'json',
    success: function (servicio) {
            if (servicio.ser_idverificadopeso == 1) {
            // Desactivar todos los inputs, selects y textareas dentro del formulario
            // $("#formValidarPeso :input").prop("disabled", true);
            
        $('[name="peso"]').prop('disabled', true);
        $('[name="volumen"]').prop('disabled', true);
        $('[name="estado"]').prop('disabled', true);
        $('[name="guia"]').prop('disabled', true);
        $('[name="piezas"]').prop('disabled', true);
        $('[name="verificado"]').prop('disabled', true);
         $('button[type="submit"]').prop('disabled', true);
          // Mostrar alerta Bootstrap
        $('#alertaVerificado').html(`
            <div class="alert alert-warning alert-dismissible fade show" role="alert">
            <strong>Atención:</strong> Esta guía ya fue <b>pesada y verificada</b>, no se puede modificar.
            <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Cerrar"></button>
            </div>
        `);

            }
      if (servicio) {
        $('[name="peso"]').val(servicio.ser_peso);
        $('[name="volumen"]').val(servicio.ser_volumen);
        $('[name="estado"]').val(servicio.ser_descripcion);
        $('[name="guia"]').val(servicio.ser_guiare);
        $('[name="piezas"]').val(servicio.ser_piezas);

        $('[name="id_param"]').val(servicio.idservicios);
        $('[name="id_param2"]').val(servicio.idservicios);
        $('[name="caso"]').val(servicio.ser_guiare);
        $('[name="param5"]').val(servicio.cli_idciudad);
        $('[name="param16"]').val(servicio.ser_ciudadentrega);
        $('[name="verificado"]').val(servicio.ser_idverificadopeso);
        $('[name="tipoServicio"]').val(servicio.gui_tiposervicio);
        if (servicio.gui_tiposervicio==1000) {
          $('[name="peso"]').prop('required', false);
          $('label[for="peso"]').html(function(_, oldHtml) {
            return oldHtml.replace(
              /\(\*\)/g,
              '<span style="font-size:0.8em; color:#6c757d; font-style:italic;">(a convenir)</span>'
            );
          });
        }
        
        let clasificacion = 0;
        if (servicio.ser_clasificacion == 1 && servicio.ser_pendientecobrar == 0) {
          clasificacion = 1;
        } else if (servicio.ser_clasificacion == 2) {
          clasificacion = 2;
        }
        $('[name="clasificacion"]').val(clasificacion);

        // imágenes
        let img1 = document.getElementById("img1");
        let img2 = document.getElementById("img2");
        let modal = document.getElementById("modal");
        let modalImg = document.getElementById("modal-img");

        img1.src = "../../imagesguias/_Recogida.jpg";
        img2.src = "../../imgServicios/" + servicio.ser_img_recog;

        let urlPagina = servicio.ima_ruta+'&vis=adm';
        img1.addEventListener("click", () => {
          window.open(urlPagina,"VentanaEmergente",
            "width=800,height=600,top=100,left=100,resizable=yes,scrollbars=yes"
          );
        });
        img2.addEventListener("click", () => {
          if (img2.src) {
            modal.style.display = "flex";
            modalImg.src = img2.src;
          }
        });
        modal.addEventListener("click", () => {
          modal.style.display = "none";
          modalImg.src = "";
        });

      } else {
        alert('No se encontró la info del servicio.');
      }
    },
    error: function () {
      alert('Error al buscar la info del servicio.');
    }
  });
}

$(document).on('click', '.validar-remesa', function () {
    let id = $(this).data('id');

    $('#formValidarRemesas')[0].reset();
    $('#modalValidarRemesas').modal('show');
    $('[name="id_param"]').val(id);
    

});



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
      url: "/nueva_plataforma/controller/DescargasOficinaController.php",
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
  function imprimirCodigos() {
    // Capturar valores de los filtros
    let operario = document.getElementById("filtroOperador").value;
    let fecha = document.getElementById("filtroFecha").value;
    let ciudad = document.getElementById("filtroCiudad").value;
    
    
    let destino = "../../phpqrcode/ticket3.php?param33=" + operario + "&param34=" + fecha + "&param36=" + ciudad + "&modulo=5";
    
    // abrir en nueva pestaña
    window.open(destino, '_blank');
  }
</script>
</body>
</html>
