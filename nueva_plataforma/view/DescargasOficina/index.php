<!DOCTYPE html>
<html lang="es">
<head>
  <meta charset="UTF-8">
  <title>Usuarios</title>
<!-- Bootstrap 5 CSS desde CDN -->
<link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">

<!-- DataTables Bootstrap 5 CSS -->
<link rel="stylesheet" href="https://cdn.datatables.net/1.13.6/css/dataTables.bootstrap5.min.css">

<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
<link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.10.5/font/bootstrap-icons.css">
<style>
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
</style>
<body>
<div class="container-fluid mt-4">
  <div class="card shadow p-3 mb-4 bg-body rounded">
    <div class="card-header text-center mi-header">
      <h3 class="mb-0">Descargas de Oficina</h3>
    </div>

    <div class="card-body">
        <div class="row mb-3 align-items-end">
            <div class="col-md-4">
                <label for="filtroFecha" class="form-label">📅 Fecha</label>
                <input type="date" id="filtroFecha" class="form-control" />
            </div>

            <!-- Ciudad -->
            <div class="col-md-4">
                <label class="form-label">Ciudad (*)</label>
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
                <label for="filtroOperador" class="form-label">Operador</label>
                <select name="filtroOperador" id="filtroOperador" class="form-select" >
                <option value="">Seleccione...</option>
                <?php foreach($operadores as $c): ?>
                    <option value="<?= $c['idusuarios'] ?>"><?= $c['usu_nombre'] ?></option>
                <?php endforeach; ?>
                </select>
            </div>


        </div>

      <div class="table-responsive">
        <table id="tablaDescargasOficina" class="table table-hover table-bordered align-middle text-center">
          <thead class="table-primary">
            <tr>



                
                <th>📆 Fecha</th>
                <th>Remitente</th>
                <th>Direcci&oacute;n</th>
                <th>Destinatario</th>
                <th>Ciudad</th>
                <th>Direcci&oacute;n</th>
                <th>Descripci&oacute;n</th>
                <th>Piezas</th>
                <th>Mensajero</th>
                <th>Pago</th>
                <th># Guia</th>
                <th>Estado</th>
                <th>Pesar</th>
                


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
  <div class="modal-dialog modal-lg">
    <form id="formValidarPeso" class="modal-content" enctype="multipart/form-data">
      <div class="modal-header">
        <h5 class="modal-title" id="modalValidarPesoLabel">Validar Peso</h5>
        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Cerrar"></button>
      </div>

      <div class="modal-body">
        <!-- Peso -->
        <div class="mb-3">
          <label for="peso" class="form-label">Peso KG: (*)</label>
          <input type="number" step="0.01" id="peso" name="peso" class="form-control" placeholder="" required>
        </div>

        <!-- Volumen -->
        <div class="mb-3">
          <label for="volumen" class="form-label">Volumen</label>
          <input type="number" step="0.01" id="volumen" name="volumen" class="form-control" placeholder="">
        </div>
        <!-- Piezas -->
        <div class="mb-3">
          <label for="piezas" class="form-label">piezas</label>
          <input type="number" step="0.01" id="piezas" name="piezas" class="form-control" placeholder="">
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
          <input type="text" id="guia" name="guia" class="form-control" placeholder="" required>
        </div>

        <!-- Foto de la guía -->
        <div class="mb-3">
          <label for="foto" class="form-label">Foto Guía</label>
          <input type="file" id="foto" name="foto" class="form-control" accept="image/*">
        </div>

        <!-- Verificado -->
        <div class="form-check mb-3">
          <label for="estado" class="form-label">Verificar (*)</label>
          <select id="verificado" name="estado" class="form-select" required>
            <option value="">seleccione...</option>
            <option value="1">Verifica</option>
          </select>
        </div>
        <input type="hidden" name="id_param" id="id_param" value="">
        <input type="hidden" name="id_param2" id="id_param2" value="">
        <input type="hidden" name="clasificacion" id="clasificacion" value="">
        <input type="hidden" name="caso" id="caso" value="2">
        <input type="hidden" name="param5" id="param5" value="">
        
        <input type="hidden" name="param16" id="param16" value="">
        <!-- Galería de imágenes -->
        <div class="mb-3">
          <label class="form-label">Galería de imágenes</label>
            <div class="d-flex flex-wrap gap-2">
                <img id="img1" src="https://via.placeholder.com/150" class="img-thumbnail">
                <img id="img2" src="https://via.placeholder.com/150" class="img-thumbnail">
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
<!-- jQuery -->
<script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>

<!-- Bootstrap JS -->
<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>

<!-- DataTables -->
<script src="https://cdn.datatables.net/1.13.6/js/jquery.dataTables.min.js"></script>
<script src="https://cdn.datatables.net/1.13.6/js/dataTables.bootstrap5.min.js"></script>
  <!-- ✅ DataTables desde CDN -->
   

<script>
$(document).ready(function () {
  const tabla = $('#tablaDescargasOficina').DataTable({
    ajax: {
      url: '/nueva_plataforma/controller/DescargasOficinaController.php',
      type: 'POST',
      data: function (d) {
        d.ajax = true;
        d.fecha = $('#filtroFecha').val();
        d.ciudad = $('#filtroCiudad').val();
        d.operador = $('#filtroOperador').val();
      },
      dataSrc: ''
    },
    columns: [
        { data: 'ser_fechafinal' },
        { data: 'cli_nombre' },
        { data: 'cli_direccion' },
        { data: 'ser_destinatario' },
        { data: 'ciu_nombre' },
        { data: 'ser_direccioncontacto' },
        { data: 'ser_paquetedescripcion' },
        { data: 'ser_piezas' },
        { data: 'usu_nombre' },
        { data: 'ser_clasificacion' },
        { data: 'ser_consecutivo' },
        { data: 'ser_estado' },
        {
            data: 'idservicios',
            render: function (data) {
            return `<button class="btn btn-sm btn-warning pesar-paquete" data-id="${data}">
                <i class="bi bi-box"></i> Pesar
            </button>`;

            }
        }
        



        

 
      ]
  });

  $('#filtroFecha, #filtroCiudad,#filtroOperador').on('change', function () {
    tabla.ajax.reload();
  });
});

// 🔁 Detectar cambios en cualquier campo editable
$('#tablaDescargasOficina tbody').on('change', '.cambiar-campo', function () {
  const id = $(this).data('id');
  const campo = $(this).data('campo');
  const valor = $(this).val();



  $.ajax({
    url: '/nueva_plataforma/controller/DescargasOficinaController.php',
    type: 'POST',
    data: {
      actualizar_campo: true,
      id: id,
      campo: campo,
      valor: valor
    },
    success: function (res) {
      $('#tablaDescargasOficina').DataTable().ajax.reload(null, false);
    },
    error: function () {
      alert("Hubo un error al actualizar.");
    }
  });
});
$('#tablaDescargasOficina tbody').on('click', '.eliminar-usuario', function () {
  const id = $(this).data('id');

  if (confirm('¿Estás seguro de que deseas eliminar este usuario?')) {
    $.ajax({
      url: '/nueva_plataforma/controller/DescargasOficinaController.php',
      type: 'POST',
      data: {
        eliminar_usuario: true,
        id: id
      },
      success: function (res) {
        $('#tablaDescargasOficina').DataTable().ajax.reload(null, false);
      },
      error: function () {
        alert('Error al eliminar el usuario.');
      }
    });
  }
});


  
document.addEventListener("DOMContentLoaded", function() {
  const contenedor = document.getElementById("contenedorTelefonos");
  const btnAgregar = document.getElementById("btnAgregarTelefono");

  btnAgregar.addEventListener("click", function() {
    const nuevoCampo = document.createElement("div");
    nuevoCampo.classList.add("input-group", "mb-2");
    nuevoCampo.innerHTML = `
      <input type="text" name="telefono_externo[]" class="form-control" placeholder="Ej: 3123456789">
      <button type="button" class="btn btn-outline-danger btnQuitarTelefono">−</button>
    `;

    contenedor.appendChild(nuevoCampo);
  });

  // Delegar evento para quitar campos
  contenedor.addEventListener("click", function(e) {
    if (e.target.classList.contains("btnQuitarTelefono")) {
      e.target.parentElement.remove();
    }
  });
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
    const peso       = document.getElementById("peso").value;
    const volumen    = document.getElementById("volumen").value;
    const piezas     = document.getElementById("piezas").value;
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
  
</script>
</body>
</html>
