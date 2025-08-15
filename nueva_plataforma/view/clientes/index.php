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

</style>
<body>
<div class="container-fluid mt-4">
  <div class="card shadow p-3 mb-4 bg-body rounded">
    <div class="card-header text-center mi-header">
      <h3 class="mb-0">Servicios automaticos</h3>
    </div>

    <div class="card-body">
        <div class="row mb-3 align-items-end">
            <div class="col-md-4">
                <label for="filtroFecha" class="form-label">📅 Fecha</label>
                <input type="date" id="filtroFecha" class="form-control" />
            </div>

            <div class="col-md-4">
                <label for="filtroTipoMensaje" class="form-label">👤 Cliente</label>
                <select id="filtroTipoMensaje" class="form-select">
                <option value="">Seleccione un cliente</option>
                
                <?php foreach ($creditos as $credito): ?>
                <option value="<?= $credito['idcreditos'] ?>"><?= $credito['cre_nombre'] ?></option>
                <?php endforeach; ?>
                </select>
            </div>

            <div class="col-md-4 text-end">
                <label class="form-label d-block invisible">Botón</label>
                <button class="btn btn-primary text-white w-100" data-bs-toggle="modal" data-bs-target="#modalServicioAuto">
                <i class="bi bi-plus-circle me-1"></i> Nuevo Servicio Automático
                </button>
            </div>
        </div>

      <div class="table-responsive">
        <table id="tablaUsuarios" class="table table-hover table-bordered align-middle text-center">
          <thead class="table-primary">
            <tr>
                <th>Cliente</th>
                <th>📆 Dias</th>
                <th>Ciudad de Recogida</th>
                <th>Telefonos</th>
                <th>Direccion</th>
                <th>Hora de la alerta</th>
                <th>Eliminar</th>

            </tr>
          </thead>
          <tbody></tbody>
        </table>
      </div>
    </div>
  </div>
</div>

<!-- Modal para crear nuevo servicio automático -->
<div class="modal fade" id="modalServicioAuto" tabindex="-1" aria-labelledby="modalServicioAutoLabel" aria-hidden="true">
  <div class="modal-dialog">
    <form id="formNuevoServicioAuto" class="modal-content">
      <div class="modal-header">
        <h5 class="modal-title" id="modalServicioAutoLabel">Nuevo Servicio Automático</h5>
        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Cerrar"></button>
      </div>
      <div class="modal-body">
        <!-- Select de clientes -->
        <div class="mb-3">
          <label for="selectCliente" class="form-label">👤 Cliente</label>
          <select id="selectCliente" name="cliente" class="form-select" required>
            <option value="">Seleccione un cliente</option>
            <option value="EXTERNOS">EXTERNOS</option>
            <!-- Puedes llenar esto dinámicamente con PHP -->
            <?php foreach ($creditos as $credito): ?>
            <option value="<?= $credito['idcreditos'] ?>"><?= $credito['cre_nombre'] ?></option>
            <?php endforeach; ?>
          </select>
        </div>

        <!-- Ciudad de recogida -->
        <div class="mb-3">
          <label for="ciudadRecogida" class="form-label">🏙️ Ciudad de recogida</label>
          <select id="ciudadRecogida" name="ciudadRecogida" class="form-select" required>
            <?php foreach ($ciudades as $ciudad): ?>
            <option value="<?= $ciudad['idciudades'] ?>"><?= $ciudad['ciu_nombre'] ?></option>
            <?php endforeach; ?>
          </select>


        </div>

        <!-- Días de la semana -->
        <div class="mb-3">
          <label class="form-label">📅 Días de programación</label>
          <div class="form-check">
            <input class="form-check-input" type="checkbox" name="dias[]" value="Lunes" id="diaLunes">
            <label class="form-check-label" for="diaLunes">Lunes</label>
          </div>
          <div class="form-check">
            <input class="form-check-input" type="checkbox" name="dias[]" value="Martes" id="diaMartes">
            <label class="form-check-label" for="diaMartes">Martes</label>
          </div>
          <div class="form-check">
            <input class="form-check-input" type="checkbox" name="dias[]" value="Miércoles" id="diaMiercoles">
            <label class="form-check-label" for="diaMiercoles">Miércoles</label>
          </div>
          <div class="form-check">
            <input class="form-check-input" type="checkbox" name="dias[]" value="Jueves" id="diaJueves">
            <label class="form-check-label" for="diaJueves">Jueves</label>
          </div>
          <div class="form-check">
            <input class="form-check-input" type="checkbox" name="dias[]" value="Viernes" id="diaViernes">
            <label class="form-check-label" for="diaViernes">Viernes</label>
          </div>
          <div class="form-check">
            <input class="form-check-input" type="checkbox" name="dias[]" value="Sábado" id="diaSabado">
            <label class="form-check-label" for="diaSabado">Sábado</label>
          </div>
          <div class="form-check">
            <input class="form-check-input" type="checkbox" name="dias[]" value="Domingo" id="diaDomingo">
            <label class="form-check-label" for="diaDomingo">Domingo</label>
          </div>
        </div>
        <!-- Campos extra para EXTERNOS -->
        <div id="camposExternos" class="mb-3">
        <label class="form-label">📞 Teléfonos del cliente</label>
        <div id="contenedorTelefonos">
            <div class="input-group mb-2">
            <input type="text" name="telefono_externo[]" class="form-control" placeholder="Ej: 3123456789" required>
            <button type="button" class="btn btn-outline-success" id="btnAgregarTelefono">+</button>
            </div>
        </div>
        </div>
        <div class="mb-3">
            <label for="direccion" class="form-label">📍 Dirección</label>
            <input type="text" id="direccion" name="direccion" class="form-control" placeholder="Escriba la dirección" required>
        </div>
        <div class="mb-3">
            <label for="hora" class="form-label">⏰ Hora</label>
            <input type="time" id="hora" name="hora" class="form-control" required>
        </div>
      </div>

      <div class="modal-footer">
        <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancelar</button>
        <button type="submit" class="btn btn-primary">Guardar servicio</button>
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
   

<script>
$(document).ready(function () {
  const tabla = $('#tablaUsuarios').DataTable({
    ajax: {
      url: '/nueva_plataforma/controller/ClientesController.php',
      type: 'POST',
      data: function (d) {
        d.ajax = true;
        d.fecha = $('#filtroFecha').val();
        d.tipo = $('#filtroTipoMensaje').val();
      },
      dataSrc: ''
    },
    columns: [
        { data: 'cliente' },
        {
            data: 'aut_dias',
            render: function (data, type, row) {
            try {
                const dias = JSON.parse(data); // intenta parsear el JSON
                if (Array.isArray(dias)) {
                return dias.join(', '); // convierte array a string separado por coma
                } else {
                return data; // si no es array, muestra tal cual
                }
            } catch (e) {
                return data; // si no se puede parsear, muestra el contenido original
            }
            }
        },
        { data: 'ciudad_origen' },
        {
            data: 'aut_telefono',
            render: function (data, type, row) {
            try {
                const dias = JSON.parse(data); // intenta parsear el JSON
                if (Array.isArray(dias)) {
                return dias.join(', '); // convierte array a string separado por coma
                } else {
                return data; // si no es array, muestra tal cual
                }
            } catch (e) {
                return data; // si no se puede parsear, muestra el contenido original
            }
            }
        },
        { data: 'aut_direccion' },
        { data: 'aut_fecha' },
        {
            data: 'aut_id', // ← El ID del registro para eliminar
            render: function (data, type, row) {
            return `
                <button class="btn btn-sm btn-danger eliminar-usuario" data-id="${data}">
                <i class="bi bi-trash"></i>
                </button>
            `;
            }
        }
      
 
      ]
  });

  $('#filtroFecha, #filtroTipoMensaje').on('change', function () {
    tabla.ajax.reload();
  });
});

// 🔁 Detectar cambios en cualquier campo editable
$('#tablaUsuarios tbody').on('change', '.cambiar-campo', function () {
  const id = $(this).data('id');
  const campo = $(this).data('campo');
  const valor = $(this).val();

  // if(id == "usu_estado" and valor==0){
  //   alert('Está apunto de desactivar al usuario, recuerde colocar fecha de finalizacion en la hoja de vida si aun no lo ha hecho');

  // }

  $.ajax({
    url: '/nueva_plataforma/controller/ClientesController.php',
    type: 'POST',
    data: {
      actualizar_campo: true,
      id: id,
      campo: campo,
      valor: valor
    },
    success: function (res) {
      $('#tablaUsuarios').DataTable().ajax.reload(null, false);
    },
    error: function () {
      alert("Hubo un error al actualizar.");
    }
  });
});
$('#tablaUsuarios tbody').on('click', '.eliminar-usuario', function () {
  const id = $(this).data('id');

  if (confirm('¿Estás seguro de que deseas eliminar este usuario?')) {
    $.ajax({
      url: '/nueva_plataforma/controller/ClientesController.php',
      type: 'POST',
      data: {
        eliminar_usuario: true,
        id: id
      },
      success: function (res) {
        $('#tablaUsuarios').DataTable().ajax.reload(null, false);
      },
      error: function () {
        alert('Error al eliminar el usuario.');
      }
    });
  }
});



document.getElementById("formNuevoServicioAuto").addEventListener("submit", function(e) {
  e.preventDefault();

  const form = document.getElementById("formNuevoServicioAuto");
  const formData = new FormData(form);

  // Validaciones opcionales
  const cliente = formData.get("cliente");
  const ciudad = formData.get("cliente"); // Cuidado: aquí hay dos con mismo name
  const diasSeleccionados = formData.getAll("dias[]");

  if (!cliente || !ciudad || diasSeleccionados.length === 0) {
    alert("Por favor completa todos los campos.");
    return;
  }

  fetch("/nueva_plataforma/controller/ClientesController.php", {
    method: "POST",
    body: formData
  })
  .then(response => response.json())
  .then(data => {
        if (data.ok) {
        alert("Servicio automático creado correctamente.");
        form.reset();
        const modal = bootstrap.Modal.getInstance(document.getElementById("modalServicioAuto"));
        modal.hide();
        $('#tablaUsuarios').DataTable().ajax.reload(null, false); // ← Agrega esta línea
        }
  })
  .catch(err => {
    console.error("Error en la solicitud:", err);
    alert("Error inesperado al guardar el servicio.");
  });
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



  
</script>
</body>
</html>
