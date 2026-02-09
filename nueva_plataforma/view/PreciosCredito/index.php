<?php require("../../login_autentica.php");
$id_sedes= $_SESSION['usu_idsede'];
$id_usuario= $_SESSION['usuario_id'];
$id_nombre=$_SESSION['usuario_nombre'];
$nivel_acceso=$_SESSION['usuario_rol'];
$precioinicialkilos=$_SESSION['precioinicial'];
if (isset($_GET['error_login']) && $_GET['error_login'] == 8) {
    header("Location: https://sistema.transmillas.com");
    exit();
}
?>
<!DOCTYPE html>
<html lang="es">
<head>
  <meta charset="UTF-8">
  <title>Precios créditos</title>

  <!-- Bootstrap 5 CSS -->
  <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">

  <!-- DataTables Bootstrap 5 CSS -->
  <link rel="stylesheet" href="https://cdn.datatables.net/1.13.6/css/dataTables.bootstrap5.min.css">

  <!-- FontAwesome -->
  <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">

  <!-- SweetAlert2 -->
  <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>

  <style>
    thead.azul-blanco th {
      background-color: #01468c;
      color: white;
    }
    .mi-header {
      background-color: #00458D;
      color: white;
    }
  </style>
</head>

<body>
<div class="container-fluid mt-4">
  <div class="card shadow p-3 mb-4 bg-body rounded">
    <div class="card-header mi-header">
      <div class="d-flex align-items-center justify-content-between">
          <!-- Botón de regreso -->
          <button class="btn btn-light" onclick="window.location.href='../../../inicio.php'">
              ⬅ Inicio
          </button>

          <!-- Título centrado -->
          <h3 class="mb-0 mx-auto">Precios créditos</h3>
      </div>

    </div>
    <div class="card-body">

      <!-- Filtros -->
      <div class="row mb-3 align-items-end">
        <div class="col-md-3">
          <label for="CiudadOr">Ciudad origen</label>
          <select id="CiudadOr" class="form-control">
            <option value="">Seleccionar...</option>
            <?php foreach ($Ciudades as $ciudad): ?>
              <option value="<?= $ciudad['idciudades'] ?>"><?= $ciudad['ciu_nombre'] ?></option>
            <?php endforeach; ?>
          </select>
        </div>

        <div class="col-md-3">
          <label for="CiudadDes">Ciudad destino</label>
          <select id="CiudadDes" class="form-control">
            <option value="">Seleccionar...</option>
            <?php foreach ($Ciudades as $ciudad): ?>
              <option value="<?= $ciudad['idciudades'] ?>"><?= $ciudad['ciu_nombre'] ?></option>
            <?php endforeach; ?>
          </select>
        </div>

        <div class="col-md-3">
          <label for="Creditos">Crédito</label>
          <select id="Creditos" class="form-control">
            <option value="">Seleccionar...</option>
            <?php foreach ($Creditos as $credito): ?>
              <option value="<?= $credito['idcreditos'] ?>"><?= $credito['cre_nombre'] ?></option>
            <?php endforeach; ?>
          </select>
        </div>

        <div class="col-md-3">
          <label for="Servicio">Tipo de servicio</label>
          <select id="Servicio" class="form-control">
            <option value="">Seleccionar...</option>
            <!-- <option value="0">Carga vía terrestre</option> -->
            <?php foreach ($TServicios as $servicios): ?>
              <option value="<?= $servicios['idtiposervicio'] ?>"><?= $servicios['tip_nom'] ?></option>
            <?php endforeach; ?>
          </select>
        </div>
        <div class="col-md-3">
          <label for="Estado">Estado</label>
          <select id="Estado" class="form-control">
            <option value="">Seleccionar...</option>
            <option value="0">Inactivo</option>
            <option value="1">Activo</option>
          </select>
        </div>
        <!-- <div class="mb-3 d-flex gap-2">
            <button class="btn btn-primary" id="btnNuevo">
                <i class="fas fa-plus"></i> Agregar Precio Crédito
            </button>

        </div> -->
      </div>

      <!-- Botón agregar -->
      <div class="mb-3">
        <button class="btn btn-primary" id="btnNuevo">
          <i class="fas fa-plus"></i> Agregar Precio Crédito
        </button>
        
        <button id="btnExcel" class="btn btn-success">
          <i class="fas fa-file-excel"></i> Descargar Excel
        </button>
        <button class="btn btn-success" id="btnEnviarComunicacion">
          <i class="fab fa-whatsapp"></i> Enviar Correo / WhatsApp
        </button>

      </div>

      <!-- Tabla -->
      <div class="table-responsive">
        <table id="tablaUsuarios" class="table table-hover table-bordered align-middle text-center">
          <thead class="table-primary azul-blanco">
          <tr>
            <th>Check</th>
            <th>Crédito</th>
            <th>Ciudad Origen</th>
            <th>Ciudad Destino</th>
            <th>Primeros Kg</th>
            <th>Precio Kg 6 a 20</th>
            <th>Precio Kg 21 a 50</th>
            <th>Precio Kg 51 a 100</th>
            <th>Precio Kg 101 a 150</th>
            <th>Precio Kg 151 a 200</th>
            <th>Precio Kg 201 a 250</th>
            <th>Servicio</th>
            <th>Estado</th>
            <th>Fecha Inicial</th>
            <th>Fecha Final</th>
            <th>Editar</th>
            <th>Eliminar</th>
          </tr>
          </thead>
          <tbody></tbody>
        </table>
      </div>

    </div>
  </div>
</div>

<!-- MODAL EDITAR -->
<div class="modal fade" id="modalEditar" tabindex="-1">
  <div class="modal-dialog modal-lg">
    <div class="modal-content">

      <div class="modal-header mi-header">
        <h5 class="modal-title">Editar Precio Crédito</h5>
        <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
      </div>

      <div class="modal-body">
        <form id="formEditar">
          <input type="hidden" id="edit_id" name="idprecioscredito">

          <div class="row mb-3">
            <div class="col-md-6">
              <label>Crédito</label>
              <select id="edit_credito" class="form-control">
                <option value="">Seleccione...</option>
                <?php foreach ($Creditos as $c): ?>
                  <option value="<?= $c['idcreditos'] ?>"><?= $c['cre_nombre'] ?></option>
                <?php endforeach; ?>
              </select>
            </div>
            <div class="col-md-4">
              <div class="form-check mt-4">
                <input 
                  class="form-check-input" 
                  type="checkbox" 
                  id="chk_editar_precio"
                  data-bs-toggle="tooltip"
                  data-bs-placement="right"
                  title="Marque si desea crear una nueva version de este precio credito lo cual mantendra registro del anterior"
                >
                <label class="form-check-label" for="chk_editar_precio">
                  Actualizar
                </label>
              </div>
            </div>
            <div class="row mb-3">
                <div class="col-md-12">
                <label>Servicio</label>
                <select id="edit_servicio" class="form-control">
                    <option value="">Seleccione...</option>
                    <option value="0">Carga vía terrestre</option>
                    <?php foreach ($TServicios as $s): ?>
                    <option value="<?= $s['idtiposervicio'] ?>"><?= $s['tip_nom'] ?></option>
                    <?php endforeach; ?>
                </select>
                </div>
            </div>
            <div class="col-md-6">
              <label>Ciudad Origen</label>
              <select id="edit_origen" class="form-control">
                <option value="">Seleccione...</option>
                <?php foreach ($Ciudades as $ci): ?>
                  <option value="<?= $ci['idciudades'] ?>"><?= $ci['ciu_nombre'] ?></option>
                <?php endforeach; ?>
              </select>
            </div>
          </div>

          <div class="row mb-3">
            <div class="col-md-6">
              <label>Ciudad Destino</label>
              <select id="edit_destino" class="form-control">
                <option value="">Seleccione...</option>
                <?php foreach ($Ciudades as $ci): ?>
                  <option value="<?= $ci['idciudades'] ?>"><?= $ci['ciu_nombre'] ?></option>
                <?php endforeach; ?>
              </select>
            </div>

            <div class="col-md-6">
              <label>Precio primeros Kg</label>
              <input type="text" id="edit_pre_preciokilo" class="form-control solo-numero">
            </div>
          </div>

          <!-- NUEVO: Tabla referencia EDITAR (arriba de rangos) -->
          <div id="tablaReferenciaEditar" class="mt-3" style="display:none;">
            <h6 class="text-primary">Precios existentes para esta combinación:</h6>
            <div class="table-responsive">
              <table class="table table-sm table-bordered text-center">
                <thead class="table-dark">
                  <tr>
                    <th>Crédito</th>
                    <th>1° Kg</th>
                    <th>6-20</th>
                    <th>21-50</th>
                    <th>51-100</th>
                    <th>101-150</th>
                    <th>151-200</th>
                    <th>201-250</th>
                  </tr>
                </thead>
                <tbody id="tbodyReferenciaEditar"></tbody>
              </table>
            </div>
          </div>
          <!-- FIN NUEVO -->

          <!-- RANGOS EDITAR -->

          <div class="row mb-3">
            <!-- <div class="col-md-6">
              <label id="label_add_rango_1">6 a 20 Kg</label>
              <input type="text" id="add_precio_6_20" class="form-control solo-numero">
            </div>  -->

          <div class="row mb-3 align-items-end">
            <div class="col-md-4">
              <label>Precio base (6 a 20 Kg)</label>
              <input type="text" id="edit_precio_6_20" class="form-control solo-numero">
            </div>

            <div class="col-md-4">
              <label>% Descuento por rango</label>
              <input type="number" id="edit_porcentaje_descuento" class="form-control form-control-sm" min="0" max="100" step="1">
            </div>

            <div class="col-md-4">
              <div class="form-check mt-4">
                <input class="form-check-input" type="checkbox" id="chk_aplicar_descuento_edit">
                <label class="form-check-label">
                  Aplicar descuento
                </label>
              </div>
            </div>


          </div>



          </div>
          <div class="row mb-3">
            <div class="col-md-6">
              <label id="label_edit_rango_2">21 a 50 Kg</label>
              <input type="text" id="edit_precio_21_50" class="form-control solo-numero">
            </div>
            <div class="col-md-6">
              <label id="label_edit_rango_3">51 a 100 Kg</label>
              <input type="text" id="edit_precio_51_100" class="form-control solo-numero">
            </div>
            <div class="col-md-6">
              <label id="label_edit_rango_4">101 a 150 Kg</label>
              <input type="text" id="edit_precio_101_150" class="form-control solo-numero">
            </div>
          </div>

          <div class="row mb-3">
            <div class="col-md-6">
              <label id="label_edit_rango_5">151 a 200 Kg</label>
              <input type="text" id="edit_precio_151_200" class="form-control solo-numero">
            </div>
            <div class="col-md-6">
              <label id="label_edit_rango_6">201 a 250 Kg</label>
              <input type="text" id="edit_precio_201_250" class="form-control solo-numero">
            </div>
          </div>
                    <div class="row mb-3">
            <div class="col-md-6">
                <label for="FechaInicial" class="form-label">📅 Fecha Inicial</label>
                <input type="date" id="FechaInicial" value="<?= date('Y-m-d') ?>" class="form-control" />
            </div>
            <div class="col-md-6">
                <label for="FechaFinal" class="form-label">📅 Fecha Final</label>
                <input type="date" id="FechaFinal" value="<?= date('Y-m-d') ?>" class="form-control" />
            </div>
          </div>

        </form>
      </div>

      <div class="modal-footer">
        <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancelar</button>
        <button type="button" id="btnGuardarCambios" class="btn btn-primary">Guardar</button>
      </div>

    </div>
  </div>
</div>

<!-- MODAL AGREGAR -->
<div class="modal fade" id="modalAgregar" tabindex="-1">
  <div class="modal-dialog modal-lg">
    <div class="modal-content">

      <div class="modal-header mi-header">
        <h5 class="modal-title">Agregar Precio Crédito</h5>
        <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
      </div>

      <div class="modal-body">
        <form id="formAgregar">

          <div class="row mb-3">
            <div class="col-md-6">
              <label>Crédito</label>
              <select id="add_credito" class="form-control">
                <option value="">Seleccione...</option>
                <?php foreach ($Creditos as $c): ?>
                  <option value="<?= $c['idcreditos'] ?>"><?= $c['cre_nombre'] ?></option>
                <?php endforeach; ?>
              </select>
            </div>
            <div class="row mb-3">
                <div class="col-md-12">
                <label>Servicio</label>
                <select id="add_servicio" class="form-control">
                    <option value="">Seleccione...</option>
                    <option value="0">Carga vía terrestre</option>
                    <?php foreach ($TServicios as $s): ?>
                    <option value="<?= $s['idtiposervicio'] ?>"><?= $s['tip_nom'] ?></option>
                    <?php endforeach; ?>
                </select>
                </div>
            </div>

            <div class="col-md-6">
              <label>Ciudad Origen</label>
              <select id="add_origen" class="form-control">
                <option value="">Seleccione...</option>
                <?php foreach ($Ciudades as $ci): ?>
                  <option value="<?= $ci['idciudades'] ?>"><?= $ci['ciu_nombre'] ?></option>
                <?php endforeach; ?>
              </select>
            </div>
          </div>

          <div class="row mb-3">
            <div class="col-md-6">
              <label>Ciudad Destino</label>
              <select id="add_destino" class="form-control">
                <option value="">Seleccione...</option>
                <?php foreach ($Ciudades as $ci): ?>
                  <option value="<?= $ci['idciudades'] ?>"><?= $ci['ciu_nombre'] ?></option>
                <?php endforeach; ?>
              </select>
            </div>

            <div class="col-md-6">
              <label>Precio primeros Kg</label>
              <input type="text" id="add_pre_preciokilo" class="form-control solo-numero">
            </div>
          </div>

          <!-- NUEVO: Tabla referencia AGREGAR (arriba de rangos) -->
          <div id="tablaReferenciaAgregar" class="mt-3" style="display:none;">
            <h6 class="text-primary">Precios existentes para esta combinación:</h6>
            <div class="table-responsive">
              <table class="table table-sm table-bordered text-center">
                <thead class="table-dark">
                  <tr>
                    <th>Crédito</th>
                    <th>1° Kg</th>
                    <th>6-20</th>
                    <th>21-50</th>
                    <th>51-100</th>
                    <th>101-150</th>
                    <th>151-200</th>
                    <th>201-250</th>
                  </tr>
                </thead>
                <tbody id="tbodyReferenciaAgregar"></tbody>
              </table>
            </div>
          </div>
          <!-- FIN NUEVO -->

          <!-- RANGOS AGREGAR -->
          <div class="row mb-3">
            <!-- <div class="col-md-6">
              <label id="label_add_rango_1">6 a 20 Kg</label>
              <input type="text" id="add_precio_6_20" class="form-control solo-numero">
            </div>  -->

          <div class="row mb-3 align-items-end">
            <div class="col-md-4">
              <label>Precio base (6 a 20 Kg)</label>
              <input type="text" id="add_precio_6_20" class="form-control solo-numero">
            </div>

            <div class="col-md-4">
              <label>% Descuento por rango</label>
              <input type="number" id="add_porcentaje_descuento" class="form-control form-control-sm" min="0" max="100" step="1">
            </div>

            <div class="col-md-4">
              <div class="form-check mt-4">
                <input class="form-check-input" type="checkbox" id="chk_aplicar_descuento">
                <label class="form-check-label">
                  Aplicar descuento
                </label>
              </div>
            </div>


          </div>



          </div>

          <div class="row mb-3">
            <div class="col-md-6">
              <label id="label_add_rango_2">21 a 50 Kg</label>
              <input type="text" id="add_precio_21_50" class="form-control solo-numero">
            </div>
            <div class="col-md-6">
              <label id="label_add_rango_3">51 a 100 Kg</label>
              <input type="text" id="add_precio_51_100" class="form-control solo-numero">
            </div>
          </div>

          <div class="row mb-3">
            <div class="col-md-6">
              <label id="label_add_rango_4">101 a 150 Kg</label>
              <input type="text" id="add_precio_101_150" class="form-control solo-numero">
            </div>
            <div class="col-md-6">
              <label id="label_add_rango_5">151 a 200 Kg</label>
              <input type="text" id="add_precio_151_200" class="form-control solo-numero">
            </div>
            <div class="col-md-6">
              <label id="label_add_rango_6">201 a 250 Kg</label>
              <input type="text" id="add_precio_201_250" class="form-control solo-numero">
            </div>
          </div>
          <div class="row mb-3">
            <div class="col-md-6">
                <label for="add_FechaInicial" class="form-label">📅 Fecha Inicial</label>
                <input type="date" id="add_FechaInicial" value="<?= date('Y-m-d') ?>" class="form-control" />
            </div>
            <div class="col-md-6">
                <label for="add_FechaFinal" class="form-label">📅 Fecha Final</label>
                <input type="date" id="add_FechaFinal" value="<?= date('Y-m-d') ?>" class="form-control" />
            </div>
          </div>

        </form>
      </div>

      <div class="modal-footer">
        <button class="btn btn-secondary" data-bs-dismiss="modal">Cancelar</button>
        <button id="btnGuardarNuevo" class="btn btn-primary" type="button">Guardar</button>
      </div>

    </div>
  </div>
</div>



<div class="modal fade" id="modalEnviar" tabindex="-1">
  <div class="modal-dialog modal-lg">
    <div class="modal-content">

      <div class="modal-header">
        <h5 class="modal-title">Enviar precios</h5>
        <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
      </div>

      <div class="modal-body">

        <!-- Crédito -->
        <div class="mb-3">
          <label>Crédito</label>
          <select id="env_credito" class="form-control">
            <option value="">Seleccione</option>
            <?php foreach ($Creditos as $c): ?>
              <option value="<?= $c['idcreditos'] ?>">
                <?= $c['cre_nombre'] ?>
              </option>
            <?php endforeach; ?>
          </select>
        </div>

        <!-- Correos -->
        <div class="mb-3">
          <label>Correos</label>
          <div id="listaCorreos"></div>
          <input type="email" id="correoExtra" class="form-control mt-2" placeholder="Correo adicional">
        </div>

        <!-- Teléfonos -->
        <div class="mb-3">
          <label>Teléfonos</label>
          <div id="listaTelefonos"></div>
          <input type="text" id="telefonoExtra" class="form-control mt-2" placeholder="Teléfono adicional">
        </div>

        <!-- Excel -->
        <div class="mb-3">
          <label>Adjuntar Excel</label>
          <input type="file" id="archivoExcel" name="archivoExcel" class="form-control" accept=".xls,.xlsx">

        </div>

      </div>

      <div class="modal-footer">
        <button class="btn btn-primary" id="btnEnviarTodo">Enviar</button>
      </div>

    </div>
  </div>
</div>

<!-- LOADER MODERNO -->
<div id="loaderOverlay">
  <div class="spinner"></div>
  <p class="text-white mt-3">Cargando datos...</p>
</div>
<style>
    /* Centrar spinner de DataTables */
    .dataTables_processing {
        position: fixed !important;
        top: 50% !important;
        left: 50% !important;
        transform: translate(-50%, -50%) !important;
        padding: 20px 40px !important;
        background: rgba(0, 0, 0, 0.7) !important;
        color: #fff !important;
        border-radius: 10px !important;
        font-size: 18px !important;
        z-index: 99999 !important;
    }

    /* Spinner interno */
    .dataTables_processing:before {
        content: "";
        display: block;
        margin: 0 auto 10px auto;
        width: 40px;
        height: 40px;
        border: 4px solid #fff;
        border-top-color: transparent;
        border-radius: 50%;
        animation: spinDT 0.9s linear infinite;
    }

    @keyframes spinDT {
        to { transform: rotate(360deg); }
    }
</style>

<!-- jQuery -->
<script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>

<!-- Bootstrap JS -->
<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>

<!-- DataTables -->
<script src="https://cdn.datatables.net/1.13.6/js/jquery.dataTables.min.js"></script>
<script src="https://cdn.datatables.net/1.13.6/js/dataTables.bootstrap5.min.js"></script>>
<script src="https://cdn.datatables.net/1.13.6/js/dataTables.bootstrap5.min.js"></script>

<script>
$(document).ready(function () {

  const rangos = {
    1: "6 a 20 Kg",
    2: "21 a 50 Kg",
    3: "51 a 100 Kg",
    4: "101 a 150 Kg",
    5: "151 a 200 Kg",
    6: "201 a 250 Kg"
  };

  // Etiquetas dinámicas (por si mañana cambian los rangos)
  $('#label_edit_rango_1').text(rangos[1]);
  $('#label_edit_rango_2').text(rangos[2]);
  $('#label_edit_rango_3').text(rangos[3]);
  $('#label_edit_rango_4').text(rangos[4]);
  $('#label_edit_rango_5').text(rangos[5]);
  $('#label_edit_rango_6').text(rangos[6]);

  $('#label_add_rango_1').text(rangos[1]);
  $('#label_add_rango_2').text(rangos[2]);
  $('#label_add_rango_3').text(rangos[3]);
  $('#label_add_rango_4').text(rangos[4]);
  $('#label_add_rango_5').text(rangos[5]);
  $('#label_add_rango_6').text(rangos[6]);

  // Solo números en precios
  $(document).on("input", ".solo-numero", function () {
    this.value = this.value.replace(/[^0-9]/g, "");
  });

  const tabla = $('#tablaUsuarios').DataTable({
    processing: true,
    language: {
        processing: "Cargando..."
    },
    ajax: {
      url: '/nueva_plataforma/controller/PreciosCreditoController.php',
      type: 'POST',
      data: function (d) {
        d.ajax = true;
        d.Origen = $('#CiudadOr').val();
        d.Destino = $('#CiudadDes').val();
        d.Creditos = $('#Creditos').val();
        d.Servicio = $('#Servicio').val();
        d.Estado = $('#Estado').val();

      },
      dataSrc: ''
    },
    columns: [
      {
        data: null,
        orderable: false,
        searchable: false,
        className: 'text-center',
        render: function (data, type, row) {
          return `
            <input type="checkbox"
                  class="row-check"
                  data-id="${row.idprecioscredito}"
                  data-campo="estado"
                  ${row.estado == 1 ? 'checked' : ''}>
          `;
        }
      },
      { data: 'cre_nombre' },
      { data: 'ciudad_origen' },
      { data: 'ciudad_destino' },
      { data: 'pre_preciokilo' },
      { data: 'precio_6_20' },
      { data: 'precio_21_50' },
      { data: 'precio_51_100' },
      { data: 'precio_101_150' },
      { data: 'precio_151_200' },
      { data: 'precio_201_250' },
      { data: 'tip_nom' },
      {
        data: 'pre_estado',
        render: function (data, type, row) {
          const clase = data == 1 ? 'bg-success text-white' : 'bg-danger text-white';
          return `
            <select class="form-select form-select-sm cambiar-campo ${clase}"
                    data-id="${row.idprecioscredito}"
                    data-campo="pre_estado">
              <option value="1" ${data == 1 ? 'selected' : ''}>Activo</option>
              <option value="0" ${data == 0 ? 'selected' : ''}>Inactivo</option>
            </select>
          `;
        }
      },
      { data: 'pre_fecha_inicial' },
      { data: 'pre_fecha_final' },
      {
        data: null,
        orderable: false,
        searchable: false,
        render: function (data, type, row) {
          return `
            <button class="btn btn-sm btn-outline-primary editar-registro"
                    data-id="${row.idprecioscredito}">
              <i class="fas fa-edit"></i>
            </button>
          `;
        }
      },
      {
        data: null,
        orderable: false,
        searchable: false,
        render: function (data, type, row) {
          return `
            <button class="btn btn-sm btn-danger eliminar-usuario"
                    title="Eliminar"
                    data-id="${row.idprecioscredito}">
              <i class="fas fa-trash-alt"></i>
            </button>
          `;
        }
      }
    ]
  });

  // Filtros recargan tabla
  $('#CiudadOr, #CiudadDes, #Creditos, #Servicio,#Estado').on('change', function () {
    tabla.ajax.reload();
  });

  // Abrir modal agregar
  $('#btnNuevo').on('click', function () {
    const modal = new bootstrap.Modal(document.getElementById('modalAgregar'));
    // limpiar tabla referencia agregar
    $('#tbodyReferenciaAgregar').html('');
    $('#tablaReferenciaAgregar').hide();
    $('#btnGuardarNuevo').prop('disabled', false);
    modal.show();
  });

  // Limpiar formularios al cerrar
  $('#modalAgregar').on('hidden.bs.modal', function () {
    $('#formAgregar')[0].reset();
    $('#tbodyReferenciaAgregar').html('');
    $('#tablaReferenciaAgregar').hide();
    $('#btnGuardarNuevo').prop('disabled', false);
  });
  $('#modalEditar').on('hidden.bs.modal', function () {
    $('#formEditar')[0].reset();
    $('#tbodyReferenciaEditar').html('');
    $('#tablaReferenciaEditar').hide();
    $('#btnGuardarCambios').prop('disabled', false);
  });

  // Validación + guardado nuevo (LÓGICA ORIGINAL, NO SE TOCA)
  $('#btnGuardarNuevo').on('click', function () {

    if ($('#add_credito').val() === "") {
      Swal.fire("Error", "Debe seleccionar un crédito", "error");
      return;
    }
    if ($('#add_origen').val() === "") {
      Swal.fire("Error", "Debe seleccionar una ciudad origen", "error");
      return;
    }
    if ($('#add_destino').val() === "") {
      Swal.fire("Error", "Debe seleccionar una ciudad destino", "error");
      return;
    }
    if ($('#add_pre_preciokilo').val() === "") {
      Swal.fire("Error", "Debe ingresar el precio de los primeros Kg", "error");
      return;
    }
    if ($('#add_servicio').val() === "") {
      Swal.fire("Error", "Debe seleccionar un servicio", "error");
      return;
    }

    const defaultVal = v => v === "" ? 0 : v;

    const datosBase = {
      credito: $('#add_credito').val(),
      origen: $('#add_origen').val(),
      destino: $('#add_destino').val(),
      servicio: $('#add_servicio').val(),
      FechaInicial: $('#add_FechaInicial').val(),
      FechaFinal: $('#add_FechaFinal').val()
      


    };

    // Primero validamos que no exista duplicado (BACKEND)
    $.ajax({
      url: '/nueva_plataforma/controller/PreciosCreditoController.php',
      type: 'POST',
      data: {
        validar_existencia: true,
        ...datosBase
      },
      success: function (res) {
        const data = JSON.parse(res);
        if (data.existe) {
          // OJO: ajustar controlador para que "existe" sea TRUE solo si incluye precios
          Swal.fire("Error", "Ya existe un registro con estos datos (Crédito + Origen + Destino + Servicio)", "error");
          return;
        }

        // Si no existe, guardamos
        const datos = {
          agregar_registro: true,
          ...datosBase,
          primeros: $('#add_pre_preciokilo').val(),
          precio_6_20: defaultVal($('#add_precio_6_20').val()),
          precio_21_50: defaultVal($('#add_precio_21_50').val()),
          precio_51_100: defaultVal($('#add_precio_51_100').val()),
          precio_101_150: defaultVal($('#add_precio_101_150').val()),
          precio_151_200: defaultVal($('#add_precio_151_200').val()),
          precio_201_250: defaultVal($('#add_precio_201_250').val())
        };

        $.ajax({
          url: '/nueva_plataforma/controller/PreciosCreditoController.php',
          type: 'POST',
          data: datos,
          success: function (res2) {
            const modal = bootstrap.Modal.getInstance(document.getElementById('modalAgregar'));
            modal.hide();
            tabla.ajax.reload(null, false);
            Swal.fire({
              icon: 'success',
              title: 'Registro agregado correctamente',
              timer: 1500,
              showConfirmButton: false
            });
          },
          error: function () {
            Swal.fire('Error', 'No se pudo guardar el registro', 'error');
          }
        });

      },
      error: function () {
        Swal.fire('Error', 'No se pudo validar la existencia', 'error');
      }
    });
  });

  // Editar: abrir modal y cargar datos
  $('#tablaUsuarios tbody').on('click', '.editar-registro', function () {
    const id = $(this).data('id');

    $.ajax({
      url: '/nueva_plataforma/controller/PreciosCreditoController.php',
      type: 'POST',
      data: { obtener_por_id: true, id: id },
      success: function (res) {
        const data = JSON.parse(res);

        $('#edit_id').val(data.idprecioscredito);
        $('#edit_credito').val(data.pre_idcredito);
        $('#edit_origen').val(data.pre_idciudadori);
        $('#edit_destino').val(data.pre_idciudades);
        $('#edit_pre_preciokilo').val(data.pre_preciokilo);

        $('#edit_precio_6_20').val(data.precio_6_20);
        $('#edit_precio_21_50').val(data.precio_21_50);
        $('#edit_precio_51_100').val(data.precio_51_100);
        $('#edit_precio_101_150').val(data.precio_101_150);
        $('#edit_precio_151_200').val(data.precio_151_200);
        $('#edit_precio_201_250').val(data.precio_201_250);
        $('#edit_servicio').val(data.pre_tiposervicio);

        $('#FechaInicial').val(data.pre_fecha_inicial);
        $('#FechaFinal').val(data.pre_fecha_final);

        

        // NUEVO: cargar referencia al abrir modal editar
        $('#tbodyReferenciaEditar').html('');
        $('#tablaReferenciaEditar').hide();
        $('#btnGuardarCambios').prop('disabled', false);
        cargarReferenciaPreciosEditar();

        const modal = new bootstrap.Modal(document.getElementById('modalEditar'));
        modal.show();
      },
      error: function () {
        Swal.fire('Error', 'No se pudo obtener la información', 'error');
      }
    });
  });

  // Guardar cambios edición (original)
$('#btnGuardarCambios').on('click', function () {

  if ($('#edit_credito').val() === "") {
    Swal.fire("Error", "Debe seleccionar un crédito", "error");
    return;
  }
  if ($('#edit_origen').val() === "") {
    Swal.fire("Error", "Debe seleccionar una ciudad origen", "error");
    return;
  }
  if ($('#edit_destino').val() === "") {
    Swal.fire("Error", "Debe seleccionar una ciudad destino", "error");
    return;
  }
  if ($('#edit_pre_preciokilo').val() === "") {
    Swal.fire("Error", "Debe ingresar el precio de los primeros Kg", "error");
    return;
  }
  if ($('#edit_servicio').val() === "") {
    Swal.fire("Error", "Debe seleccionar un servicio", "error");
    return;
  }

  const defaultVal = v => v === "" ? 0 : v;

  const datosBase = {
    credito: $('#edit_credito').val(),
    origen: $('#edit_origen').val(),
    destino: $('#edit_destino').val(),
    servicio: $('#edit_servicio').val(),
    FechaInicial: $('#FechaInicial').val(),
    FechaFinal: $('#FechaFinal').val()
  };

  const datos = {
    actualizar_registro: true,
    id: $('#edit_id').val(),
    pre_idcredito: $('#edit_credito').val(),
    pre_idciudadori: $('#edit_origen').val(),
    pre_idciudades: $('#edit_destino').val(),
    pre_preciokilo: $('#edit_pre_preciokilo').val(),
    pre_tiposervicio: $('#edit_servicio').val(),
    precio_6_20: defaultVal($('#edit_precio_6_20').val()),
    precio_21_50: defaultVal($('#edit_precio_21_50').val()),
    precio_51_100: defaultVal($('#edit_precio_51_100').val()),
    precio_101_150: defaultVal($('#edit_precio_101_150').val()),
    precio_151_200: defaultVal($('#edit_precio_151_200').val()),
    precio_201_250: defaultVal($('#edit_precio_201_250').val()),
    pre_fecha_ini: $('#FechaInicial').val(),
    pre_fecha_fin: $('#FechaFinal').val(),
    editar_precio: $('#chk_editar_precio').is(':checked') ? 1 : 0
  };

  // 🔹 SI NO está marcado → editar directo sin validar
  if (!$('#chk_editar_precio').is(':checked')) {
    actualizarRegistro(datos);
    return;
  }

  // 🔹 SI está marcado → validar duplicado antes de crear nueva versión
  $.ajax({
    url: '/nueva_plataforma/controller/PreciosCreditoController.php',
    type: 'POST',
    data: {
      validar_existencia: true,
      ...datosBase
    },
    success: function (res) {
      const data = JSON.parse(res);
      if (data.existe) {
        Swal.fire("Error", "Ya existe un registro con estos datos y rango de fechas", "error");
        return;
      }
      actualizarRegistro(datos);
    },
    error: function () {
      Swal.fire('Error', 'No se pudo validar la existencia', 'error');
    }
  });

});

// 🔹 Función reutilizable para actualizar
function actualizarRegistro(datos) {
  $.ajax({
    url: '/nueva_plataforma/controller/PreciosCreditoController.php',
    type: 'POST',
    data: datos,
    success: function (res) {
      const modal = bootstrap.Modal.getInstance(document.getElementById('modalEditar'));
      modal.hide();
      tabla.ajax.reload(null, false);
      Swal.fire({
        icon: 'success',
        title: 'Registro actualizado correctamente',
        timer: 1500,
        showConfirmButton: false
      });
    },
    error: function () {
      Swal.fire('Error', 'No se pudo actualizar', 'error');
    }
  });
}


  // Eliminar
  $('#tablaUsuarios tbody').on('click', '.eliminar-usuario', function () {
    const id = $(this).data('id');

    Swal.fire({
      title: '¿Está seguro?',
      text: 'Esta acción eliminará el registro',
      icon: 'warning',
      showCancelButton: true,
      confirmButtonText: 'Sí, eliminar',
      cancelButtonText: 'Cancelar'
    }).then((result) => {
      if (result.isConfirmed) {
        $.ajax({
          url: '/nueva_plataforma/controller/PreciosCreditoController.php',
          type: 'POST',
          data: { eliminar_usuario: true, id: id },
          success: function (res) {
            tabla.ajax.reload(null, false);
            Swal.fire('Eliminado', 'El registro ha sido eliminado', 'success');
          },
          error: function () {
            Swal.fire('Error', 'No se pudo eliminar el registro', 'error');
          }
        });
      }
    });

  });

  // =========================
  // NUEVO: FUNCIONES REFERENCIA PRECIOS
  // =========================

  function cargarReferenciaPreciosAgregar() {
    const origen   = $("#add_origen").val();
    const destino  = $("#add_destino").val();
    const servicio = $("#add_servicio").val();
    const credito  = $("#add_credito").val();

    if (origen === "" || destino === "" || servicio === "") {
      $("#tablaReferenciaAgregar").hide();
      $("#tbodyReferenciaAgregar").html("");
      $("#btnGuardarNuevo").prop("disabled", false);
      return;
    }

    $.ajax({
      url: "/nueva_plataforma/controller/PreciosCreditoController.php",
      type: "POST",
      data: {
        buscar_referencia: true,
        origen: origen,
        destino: destino,
        servicio: servicio
      },
      success: function(res) {
        let data = [];
        try {
          data = JSON.parse(res);
        } catch(e) {
          console.error("Error parse JSON referencia agregar:", e, res);
          return;
        }

        let html = "";
        let coincideTotal = false;

        if (!Array.isArray(data) || data.length === 0) {
          html = `<tr><td colspan="8" class="text-danger">No existen registros para esta combinación</td></tr>`;
        } else {
          data.forEach(r => {
            // Coincidencia completa: mismo crédito + mismos precios
            const mismoCredito =
              (credito !== "" && typeof r.pre_idcredito !== "undefined"
               && String(credito) === String(r.pre_idcredito));

            const matchPrecios =
              $("#add_pre_preciokilo").val() == r.pre_preciokilo &&
              ($("#add_precio_6_20").val()   || "0") == r.precio_6_20 &&
              ($("#add_precio_21_50").val()  || "0") == r.precio_21_50 &&
              ($("#add_precio_51_100").val() || "0") == r.precio_51_100 &&
              ($("#add_precio_101_150").val()|| "0") == r.precio_101_150 &&
              ($("#add_precio_151_200").val()|| "0") == r.precio_151_200 &&
              ($("#add_precio_201_250").val()|| "0") == r.precio_201_250;

            const matchTotal = mismoCredito && matchPrecios;
            if (matchTotal) coincideTotal = true;

            html += `
              <tr class="${matchTotal ? 'table-danger' : ''}">
                <td>${r.cre_nombre ?? ''}</td>
                <td>${r.pre_preciokilo}</td>
                <td>${r.precio_6_20}</td>
                <td>${r.precio_21_50}</td>
                <td>${r.precio_51_100}</td>
                <td>${r.precio_101_150}</td>
                <td>${r.precio_151_200}</td>
                <td>${r.precio_201_250}</td>
              </tr>`;
          });
        }

        $("#tbodyReferenciaAgregar").html(html);
        $("#tablaReferenciaAgregar").show();

        if (coincideTotal) {
          $("#btnGuardarNuevo").prop("disabled", true);
          Swal.fire({
            icon: "error",
            title: "Registro duplicado",
            text: "Ya existe un registro idéntico (Crédito + Origen + Destino + Servicio + precios). Modifique algún precio.",
          });
        } else {
          $("#btnGuardarNuevo").prop("disabled", false);
        }
      },
      error: function() {
        console.error("Error al cargar referencia de precios (agregar)");
      }
    });
  }

  function cargarReferenciaPreciosEditar() {
    const origen   = $("#edit_origen").val();
    const destino  = $("#edit_destino").val();
    const servicio = $("#edit_servicio").val();
    const credito  = $("#edit_credito").val();
    const idActual = $("#edit_id").val();

    if (origen === "" || destino === "" || servicio === "") {
      $("#tablaReferenciaEditar").hide();
      $("#tbodyReferenciaEditar").html("");
      $("#btnGuardarCambios").prop("disabled", false);
      return;
    }

    $.ajax({
      url: "/nueva_plataforma/controller/PreciosCreditoController.php",
      type: "POST",
      data: {
        buscar_referencia: true,
        origen: origen,
        destino: destino,
        servicio: servicio
      },
      success: function(res) {
        let data = [];
        try {
          data = JSON.parse(res);
        } catch(e) {
          console.error("Error parse JSON referencia editar:", e, res);
          return;
        }

        let html = "";
        let coincideTotal = false;

        if (!Array.isArray(data) || data.length === 0) {
          html = `<tr><td colspan="8" class="text-danger">No existen registros para esta combinación</td></tr>`;
        } else {
          data.forEach(r => {
            const mismoCredito =
              (credito !== "" && typeof r.pre_idcredito !== "undefined"
               && String(credito) === String(r.pre_idcredito));

            const mismoId = (typeof r.idprecioscredito !== "undefined"
                            && String(r.idprecioscredito) === String(idActual));

            const matchPrecios =
              $("#edit_pre_preciokilo").val() == r.pre_preciokilo &&
              ($("#edit_precio_6_20").val()   || "0") == r.precio_6_20 &&
              ($("#edit_precio_21_50").val()  || "0") == r.precio_21_50 &&
              ($("#edit_precio_51_100").val() || "0") == r.precio_51_100 &&
              ($("#edit_precio_101_150").val()|| "0") == r.precio_101_150 &&
              ($("#edit_precio_151_200").val()|| "0") == r.precio_151_200 &&
              ($("#edit_precio_201_250").val()|| "0") == r.precio_201_250;

            // Para editar, NO bloqueamos si el match es consigo mismo (mismo id)
            const matchTotal = !mismoId && mismoCredito && matchPrecios;
            if (matchTotal) coincideTotal = true;

            html += `
              <tr class="${matchTotal ? 'table-danger' : ''}">
                <td>${r.cre_nombre ?? ''}</td>
                <td>${r.pre_preciokilo}</td>
                <td>${r.precio_6_20}</td>
                <td>${r.precio_21_50}</td>
                <td>${r.precio_51_100}</td>
                <td>${r.precio_101_150}</td>
                <td>${r.precio_151_200}</td>
                <td>${r.precio_201_250}</td>
              </tr>`;
          });
        }

        $("#tbodyReferenciaEditar").html(html);
        $("#tablaReferenciaEditar").show();

        if (coincideTotal) {
          $("#btnGuardarCambios").prop("disabled", true);
          Swal.fire({
            icon: "error",
            title: "Registro duplicado",
            text: "Ya existe otro registro idéntico (Crédito + Origen + Destino + Servicio + precios). Modifique algún precio.",
          });
        } else {
          $("#btnGuardarCambios").prop("disabled", false);
        }
      },
      error: function() {
        console.error("Error al cargar referencia de precios (editar)");
      }
    });
  }

  // Binds para recargar referencia en AGREGAR
  $("#add_origen, #add_destino, #add_servicio, #add_credito").on("change", function() {
    cargarReferenciaPreciosAgregar();
  });

  $("#add_pre_preciokilo, #add_precio_6_20, #add_precio_21_50, #add_precio_51_100, #add_precio_101_150, #add_precio_151_200, #add_precio_201_250")
    .on("input", function() {
      cargarReferenciaPreciosAgregar();
    });

  // Binds para recargar referencia en EDITAR
  $("#edit_origen, #edit_destino, #edit_servicio, #edit_credito").on("change", function() {
    cargarReferenciaPreciosEditar();
  });

  $("#edit_pre_preciokilo, #edit_precio_6_20, #edit_precio_21_50, #edit_precio_51_100, #edit_precio_101_150, #edit_precio_151_200, #edit_precio_201_250")
    .on("input", function() {
      cargarReferenciaPreciosEditar();
    });

  // Click en filas de referencia AGREGAR para copiar precios
  $(document).on("click", "#tbodyReferenciaAgregar tr", function () {
    let tds = $(this).find("td");
    if (tds.length < 8) return;

    $("#add_pre_preciokilo").val(tds.eq(1).text());
    $("#add_precio_6_20").val(tds.eq(2).text());
    $("#add_precio_21_50").val(tds.eq(3).text());
    $("#add_precio_51_100").val(tds.eq(4).text());
    $("#add_precio_101_150").val(tds.eq(5).text());
    $("#add_precio_151_200").val(tds.eq(6).text());
    $("#add_precio_201_250").val(tds.eq(7).text());

    cargarReferenciaPreciosAgregar();
  });

  // Click en filas de referencia EDITAR para copiar precios
  $(document).on("click", "#tbodyReferenciaEditar tr", function () {
    let tds = $(this).find("td");
    if (tds.length < 8) return;

    $("#edit_pre_preciokilo").val(tds.eq(1).text());
    $("#edit_precio_6_20").val(tds.eq(2).text());
    $("#edit_precio_21_50").val(tds.eq(3).text());
    $("#edit_precio_51_100").val(tds.eq(4).text());
    $("#edit_precio_101_150").val(tds.eq(5).text());
    $("#edit_precio_151_200").val(tds.eq(6).text());
    $("#edit_precio_201_250").val(tds.eq(7).text());

    cargarReferenciaPreciosEditar();
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
    url: '/nueva_plataforma/controller/PreciosCreditoController.php',
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
});

  // document.addEventListener('DOMContentLoaded', function() {
  //   // Captura el campo "Precio primeros Kg"
  //   const precioPrimerosKg = document.getElementById('add_precio_6_20');
    

  //   // Captura los demás campos de precio
    
  //   const precio21_50 = document.getElementById('add_precio_21_50');
  //   const precio51_100 = document.getElementById('add_precio_51_100');
  //   const precio101_150 = document.getElementById('add_precio_101_150');
  //   const precio151_200 = document.getElementById('add_precio_151_200');
  //   const precio201_250 = document.getElementById('add_precio_201_250');
    
  //   // Añade un event listener para que se ejecute cuando se escriba en el campo de "Precio primeros Kg"
  //   precioPrimerosKg.addEventListener('input', function() {
  //     // Asigna el valor de "Precio primeros Kg" a los demás campos
  //     const valor = precioPrimerosKg.value;
  //     precio21_50.value = valor;
  //     precio51_100.value = valor;
  //     precio101_150.value = valor;
  //     precio151_200.value = valor;
  //     precio201_250.value = valor;
  //   });
  // });
document.addEventListener('DOMContentLoaded', function () {

  const precioBase = document.getElementById('add_precio_6_20');
  const porcentaje = document.getElementById('add_porcentaje_descuento');
  const chkDescuento = document.getElementById('chk_aplicar_descuento');

  const campos = [
    { el: document.getElementById('add_precio_21_50'), factor: 1 },
    { el: document.getElementById('add_precio_51_100'), factor: 2 },
    { el: document.getElementById('add_precio_101_150'), factor: 3 },
    { el: document.getElementById('add_precio_151_200'), factor: 4 },
    { el: document.getElementById('add_precio_201_250'), factor: 5 }
  ];

  function redondearCOP(valor) {
    return Math.round(valor / 50) * 50;
  }

  function recalcular() {
    const base = parseFloat(precioBase.value) || 0;
    const porc = parseFloat(porcentaje.value) || 0;

    campos.forEach(campo => {
      if (!chkDescuento.checked || porc <= 0) {
        campo.el.value = redondearCOP(base);
      } else {
        const descuentoTotal = (porc * campo.factor) / 100;
        const nuevoValor = base - (base * descuentoTotal);
        campo.el.value = redondearCOP(nuevoValor);
      }
    });
  }

  precioBase.addEventListener('input', recalcular);
  porcentaje.addEventListener('input', recalcular);
  chkDescuento.addEventListener('change', recalcular);

});

document.addEventListener('DOMContentLoaded', function () {

  const precioBase = document.getElementById('edit_precio_6_20');
  const porcentaje = document.getElementById('edit_porcentaje_descuento');
  const chkDescuento = document.getElementById('chk_aplicar_descuento_edit');

  const campos = [
    { el: document.getElementById('edit_precio_21_50'), factor: 1 },
    { el: document.getElementById('edit_precio_51_100'), factor: 2 },
    { el: document.getElementById('edit_precio_101_150'), factor: 3 },
    { el: document.getElementById('edit_precio_151_200'), factor: 4 },
    { el: document.getElementById('edit_precio_201_250'), factor: 5 }
  ];

  function redondearCOP(valor) {
    return Math.round(valor / 50) * 50;
  }

  function recalcular() {
    const base = parseFloat(precioBase.value) || 0;
    const porc = parseFloat(porcentaje.value) || 0;

    campos.forEach(campo => {
      if (!chkDescuento.checked || porc <= 0) {
        campo.el.value = redondearCOP(base);
      } else {
        const descuentoTotal = (porc * campo.factor) / 100;
        const nuevoValor = base - (base * descuentoTotal);
        campo.el.value = redondearCOP(nuevoValor);
      }
    });
  }

  precioBase.addEventListener('input', recalcular);
  porcentaje.addEventListener('input', recalcular);
  chkDescuento.addEventListener('change', recalcular);

});


$('#btnExcel').on('click', function () {

  const params = new URLSearchParams({
    excel: 1,
    Origen: $('#CiudadOr').val(),
    Destino: $('#CiudadDes').val(),
    Creditos: $('#Creditos').val(),
    Servicio: $('#Servicio').val(),
    Estado: $('#Estado').val()
  });

  window.location.href =
    '/nueva_plataforma/controller/PreciosCreditoController.php?' + params.toString();
});

$("#btnEnviarComunicacion").on("click", function () {
  $("#modalEnviar").modal("show");
});

$("#env_credito").on("change", function () {
  const credito = $(this).val();
  if (!credito) return;

  $.post("/nueva_plataforma/controller/PreciosCreditoController.php", {
    obtener_contactos: true,
    credito: credito
  }, function (res) {

    const data = JSON.parse(res);
    let correos = "", telefonos = "";

    data.forEach(r => {
      if (r.correo) {
        correos += `
          <label>
            <input type="checkbox" class="chkCorreo" value="${r.correo}">
            ${r.correo}
          </label><br>`;
      }
      if (r.telefono) {
        telefonos += `
          <label>
            <input type="checkbox" class="chkTelefono" value="${r.telefono}">
            ${r.telefono}
          </label><br>`;
      }
    });

    $("#listaCorreos").html(correos);
    $("#listaTelefonos").html(telefonos);
  });
});


$("#btnEnviarTodo").on("click", function () {

  let correos = [];
  let telefonos = [];

  $(".chkCorreo:checked").each(function () {
    correos.push($(this).val());
  });

  $(".chkTelefono:checked").each(function () {
    telefonos.push($(this).val());
  });

  if ($("#correoExtra").val() !== "") {
    correos.push($("#correoExtra").val());
  }

  if ($("#telefonoExtra").val() !== "") {
    telefonos.push($("#telefonoExtra").val());
  }

  if (correos.length === 0 && telefonos.length === 0) {
    Swal.fire("Error", "Debe seleccionar al menos un correo o un teléfono", "error");
    return;
  }

  let archivo = $("#archivoExcel")[0].files[0];

  if (!archivo) {
    Swal.fire("Error", "Debe adjuntar el archivo Excel", "error");
    return;
  }

  // 📦 FormData
  let formData = new FormData();
  formData.append("enviar_precios", 1);
  formData.append("credito", $("#env_credito").val());
  formData.append("archivoExcel", archivo);

  correos.forEach(c => formData.append("correos[]", c));
  telefonos.forEach(t => formData.append("telefonos[]", t));

  Swal.fire({
    title: "Enviando...",
    allowOutsideClick: false,
    didOpen: () => Swal.showLoading()
  });

  $.ajax({
    url: "/nueva_plataforma/controller/PreciosCreditoController.php",
    type: "POST",
    data: formData,
    processData: false, // 🔴 OBLIGATORIO
    contentType: false, // 🔴 OBLIGATORIO
    success: function (res) {
      Swal.close();
      const r = JSON.parse(res);

      if (r.success) {
        Swal.fire("Éxito", r.mensaje, "success");
        $("#modalEnviar").modal("hide");
      } else {
        Swal.fire("Error", r.mensaje, "error");
      }
    },
    error: function () {
      Swal.close();
      Swal.fire("Error", "No se pudo completar el envío", "error");
    }
  });
});


</script>
</body>
</html>

