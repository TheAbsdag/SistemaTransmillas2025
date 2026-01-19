<?php
// view/Recoger/index.php
// Este archivo se incluye desde RecogerController.php?accion=vista&idServicio=...
$sede    = $_GET['sede']  ?? '';
$acceso  = $_GET['acceso'] ?? '';
$usuario = $_GET['usuario'] ?? '';
$nombre = $_GET['nombre'] ?? '';
$idServicio = isset($_GET['idServicio']) ? (int)$_GET['idServicio'] : 0;
?>
<!DOCTYPE html>
<html lang="es">
<head>
  <meta charset="UTF-8" />
  <title>Recoger Servicio</title>
  <meta name="viewport" content="width=device-width, initial-scale=1" />
  <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet" />
  <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css" rel="stylesheet" />
  <style>
    .mi-header { background:#00458D; color:white; }
    .section-title { background:#01468C; color:#fff; padding:.5rem .75rem; font-weight:600; margin-bottom:1rem; border-radius:.25rem; }
    .readonly-input { background:#f2f2f2; }
  </style>
</head>
<body>
<div class="container-fluid mt-4">
  <div class="card shadow p-3 mb-4 bg-body rounded">
    <div class="card-header text-center mi-header">
      <h3 class="mb-0">Recoger Servicio</h3>
    </div>

    <div class="card-body">

      <!-- SELECTOR DE ACCIÓN -->
      <div class="row mb-4">
        <div class="col-md-4">
          <label class="form-label fw-bold">Seleccione acción</label>
          <select id="tipoAccion" class="form-select">
            <option value="">Seleccione...</option>
            <option value="recogido">RECOGIDO</option>
            <option value="norecogido">NO RECOGIDO</option>
          </select>
        </div>
      </div>

      <!-- ====================== FORMULARIO NO RECOGIDO ========================= -->
      <div id="formNoRecogido" style="display:none;">
        <div class="section-title">No Recogido</div>

        <div class="row mb-3">
          <div class="col-md-8">
            <label class="form-label fw-bold">Motivo *</label>
            <input type="text" id="motivo_no_recogido" class="form-control" placeholder="Escriba el motivo" />
          </div>
        </div>

        <div class="row mb-3">
          <div class="col-md-8">
            <label class="form-label fw-bold">Foto evidencia *</label>
            <input type="file" id="foto_evidencia" class="form-control" accept="image/*" />
          </div>
        </div>

        <button type="button" class="btn btn-danger" onclick="guardarNoRecogido()">
          <i class="fa fa-times"></i> Guardar No Recogido
        </button>
        <hr>
      </div>

      <!-- ====================== FORMULARIO RECOGIDO ========================= -->
      <form id="formRecogido" enctype="multipart/form-data" style="display:none;">
        <input type="hidden" name="accion" value="guardarRecogido" />
        <input type="hidden" name="idservicio" id="idservicio" />
        <input type="hidden" name="precioinicialkilos" id="precioinicialkilos" />
        
        <!-- param27 (planilla) oculto: se llena con ser_consecutivo -->
        <input type="hidden" name="param27" id="param27" />
        <input type="hidden" name="param13" id="param13" />
        <input type="hidden" name="param17" id="param17"  value="0"/>
        <input type="hidden" name="param18" id="param18"  />
        <input type="hidden" name="param11" id="param11"  />
        <input type="hidden" name="param9" id="param9"  />
        <input type="hidden" name="param53" id="param53"  />
        <input type="hidden" id="rel_nom_credito" name="rel_nom_credito">
        <input type="hidden" name="param34" id="param34"  />
        <input type="hidden" name="usuario" value="<?php echo htmlspecialchars($usuario); ?>">
        <input type="hidden" name="sede" value="<?php echo htmlspecialchars($sede); ?>">
        <input type="hidden" name="nombre" value="<?php echo htmlspecialchars($nombre); ?>">
        <input type="hidden" name="acceso" value="<?php echo htmlspecialchars($acceso); ?>">

        




        

        <div class="section-title">Datos del servicio</div>

        <div class="row mb-3">
          <div class="col-md-4">
            <label class="form-label fw-bold">Fecha Recogida</label>
            <input type="date" id="fecha_recogida" name="param28" class="form-control"
                   value="<?php echo date('Y-m-d'); ?>" />
          </div>

          <div class="col-md-4">
            <label class="form-label fw-bold">Servicio(*)</label>
            <input type="text" id="ser_prioridad" class="form-control readonly-input" readonly />
            <!-- Texto servicio para backend -->
            <input type="hidden" id="param15" name="param15" />
          </div>

          <div class="col-md-4">
            <label class="form-label fw-bold">Número de piezas</label>
            <input type="number" id="ser_piezas" name="param2" class="form-control" min="1" />
          </div>
        </div>

        <div class="row mb-3">
          <div class="col-md-4">
            <label class="form-label fw-bold">¿Verificado? (*)</label>
            <select id="param19" name="param19" class="form-select" required>
                <option value="">Seleccione...</option>
                <option value="0">No</option>
                <option value="1">Si</option>

            </select>
          </div>

          <div class="col-md-4">
            <label class="form-label fw-bold">Tipo(*)</label>
            <!-- param21: se llena con SELECT desde tabla tipo -->
            <select id="param21" name="param21" class="form-select" required>
              <option value="">Cargando tipos...</option>
            </select>
          </div>

          <div class="col-md-4">
            <label class="form-label fw-bold">Dice contener</label>
            <input type="text" id="ser_paquetedescripcion" name="param3" class="form-control" />
          </div>
        </div>

        <div class="row mb-3">
          <div class="col-md-4">
            <label class="form-label fw-bold">Valor Préstamo</label>
            <input type="text" id="ser_valorprestamo" name="param16"
                   class="form-control readonly-input" readonly />
          </div>

          <div class="col-md-4">
            <label class="form-label fw-bold">Seguro(*)</label>
            <input type="text" id="ser_valorseguro" name="param6" class="form-control" required/>
          </div>

        <div class="col-md-4">
            <label class="form-label fw-bold">Hora Recogida</label>
            <input type="time" id="param7" name="param7" class="form-control" />
        </div>
        </div>

        <div class="row mb-3">
            <div class="col-md-4">
                <label class="form-label fw-bold">Devolver Recibido(*)</label>
                <select id="ser_devolverreci" name="param29" class="form-select">
                <option value="">Seleccione...</option>
                <option value="0">No</option>
                <option value="1">Si</option>

                </select>
            </div>

          <div class="col-md-4">
            <label class="form-label fw-bold"># Guía</label>
            <input type="text" id="ser_guiare" name="param25" class="form-control readonly-input" readonly />
          </div>

          <div class="col-md-4">
            <label class="form-label fw-bold">Estado Paquete(*)</label>
            <!-- param26: estado paquete (editable) -->
            <input type="text" id="param26" name="param26" class="form-control" required />
            <!-- ser_estado queda solo interno en hidden si lo necesitas -->
            <input type="hidden" id="ser_estado" />
          </div>
        </div>

        <div class="row mb-3">
          <div class="col-md-4">
            <label class="form-label fw-bold">Abono</label>
            <input type="text" id="ser_valorabono" name="param54"
                   class="form-control readonly-input" readonly />
          </div>

          <div class="col-md-4">
            <label class="form-label fw-bold">Peso</label>
            <input type="number" id="ser_peso" name="param10" class="form-control" step="0.1" />
          </div>

          <div class="col-md-4">
            <label class="form-label fw-bold">Volumen</label>
            <input type="number" id="ser_volumen" name="param20" class="form-control" step="0.1" />
          </div>
        </div>

        <div class="row mb-3">
          <div class="col-md-4">
            <label class="form-label fw-bold">VALOR TOTAL</label>
            <input type="text" id="param112" name="param112"
                   class="form-control readonly-input" readonly />
          </div>
        </div>

        <div class="row mb-3">
          <div class="col-md-4">
            <label class="form-label fw-bold">Tipo de Pago(*)</label>
            <!-- param8, con comportamiento especial para Crédito (2) -->
            <select id="param8" name="param8" class="form-select" required>
              <option value="">Seleccione...</option>
              <option value="1">Contado</option>
              <option value="2">Crédito</option>
              <option value="3">Al Cobro</option>
              <!-- <option value="4">Pendiente x Cobrar</option> -->
            </select>
          </div>
        </div>

        <!-- =================== BLOQUE "DE CONTADO" (solo si param8 = 1) =================== -->
        <div id="bloqueContado" style="display:none;">
          <div class="section-title mt-4">De Contado</div>

          <div class="row mb-3">
            <div class="col-md-6">
              <label class="form-label fw-bold">Método de Pago</label>
              <!-- param30: idtipospagos|cuenta|nombre -->
              <select id="param30" name="param30" class="form-select">
                <option value="">Cargando métodos...</option>
              </select>
            </div>
            <div class="col-md-6">
              <label class="form-label fw-bold">Imagen transacción</label>
              <!-- param40: imagen de transacción -->
              <input type="file" id="param40" name="param40" class="form-control" accept="image/*" />
            </div>
          </div>
        </div>
        
        <!-- =================== BLOQUE "DE CREDITO" (solo si param8 = 1) =================== -->
        <div id="bloqueCredito" style="display:none;">
          <div class="section-title mt-4">Credito</div>

          <div class="row mb-3">
            <div class="col-md-6">
              <label class="form-label fw-bold">Credito</label>
              <!-- param30: idtipospagos|cuenta|nombre -->
              <select id="credito" name="credito" class="form-select">
                <option value="">Cargando creditos...</option>
              </select>
            </div>

          </div>
        </div>

        <div class="section-title mt-4">Datos quien entrega</div>

        <div class="row mb-3">
          <div class="col-md-6">
            <label class="form-label fw-bold">Foto (*)</label>
            <input type="file" id="param87" name="param87" accept="image/*"
                   capture="environment" class="form-control" required />
          </div>
        </div>

        <div class="row mb-3">
          <div class="col-md-6">
            <label class="form-label fw-bold">Nombre completo</label>
            <input type="text" id="param82" name="param82" class="form-control" required/>
          </div>
          <div class="col-md-6">
            <label class="form-label fw-bold">Teléfono</label>
            <input type="text" id="param85" name="param85" class="form-control" value="+57" required/>
          </div>
        </div>

        <div class="section-title mt-4">Firma Recogida</div>
        <div style="width:100%;height:60vh;">
          <iframe id="iframeFirma" src="" style="width:100%;height:100%;border:0;"></iframe>
        </div>


        <button type="button" class="btn btn-success mt-4" onclick="guardarRecogido()">
          <i class="fa fa-save"></i> Guardar
        </button>
      </form>

    </div>
  </div>
</div>
<script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
<script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
<script>
// Cambiar formulario según acción
document.getElementById('tipoAccion').addEventListener('change', function(){
  const v = this.value;
  document.getElementById('formRecogido').style.display   = (v === 'recogido')   ? 'block' : 'none';
  document.getElementById('formNoRecogido').style.display = (v === 'norecogido') ? 'block' : 'none';
});

// ====== Mostrar / ocultar bloque "De Contado" según tipo de pago ======
function actualizarBloqueContado() {
  const tipo = parseInt(document.getElementById('param8').value || 0);
  const bloque = document.getElementById('bloqueContado');
  const bloqueCredito = document.getElementById('bloqueCredito');
  const campo = document.getElementById('ser_peso'); // <-- tu campo dentro del bloque

  if (tipo === 1) {
    bloque.style.display = 'block';
    campo.setAttribute('required', 'required');   // vuelve el campo obligatorio
  } else {
    bloque.style.display = 'none';
    campo.removeAttribute('required');            // deja de ser obligatorio
  }

  if (tipo === 2) {
    bloqueCredito.style.display = 'block';
    campo.setAttribute('required', 'required');   // vuelve el campo obligatorio
  } else {
    bloqueCredito.style.display = 'none';
    campo.removeAttribute('required');            // deja de ser obligatorio
  }
}

document.getElementById('param8').addEventListener('change', actualizarBloqueContado);

// Cargar datos del servicio al entrar
async function cargarServicio() {
  const params = new URLSearchParams(window.location.search);
  const id = params.get('idServicio');
  const prekilo = params.get('precioskiloini');
   
  if (!id) return;

  try {
    const resp = await fetch(`../controller/RecogerController.php?accion=buscarRecogida&id=${id}`);
    const s = await resp.json();
    if (!s) return;

    document.getElementById('idservicio').value = id;
    document.getElementById('precioinicialkilos').value = prekilo;

    // Campos directos
    document.getElementById('ser_prioridad').value           = s.ser_prioridad ?? '';
    document.getElementById('ser_piezas').value              = s.ser_piezas ?? '';
    // document.getElementById('ser_verificado').value          = s.ser_verificado ?? '';
    document.getElementById('param19').value                 = s.ser_verificado ?? '';
    document.getElementById('ser_paquetedescripcion').value  = s.ser_paquetedescripcion ?? '';
    document.getElementById('ser_valorprestamo').value       = s.ser_valorprestamo ?? '';
    document.getElementById('ser_valorseguro').value         = s.ser_valorseguro ?? '';
    // document.getElementById('ser_devolverreci').value        = (s.ser_devolverreci == 1) ? 'SI' : 'NO';
    document.getElementById('ser_devolverreci').value                 = s.ser_devolverreci ?? 0;
    document.getElementById('ser_guiare').value              = s.ser_guiare ?? '';
    document.getElementById('ser_estado').value              = s.ser_estado ?? '';
    document.getElementById('ser_valorabono').value          = s.ser_valorabono ?? '';
    document.getElementById('ser_peso').value                = s.ser_peso ?? '';
    document.getElementById('ser_volumen').value             = s.ser_volumen ?? '';
    document.getElementById('param15').value                 = s.ser_prioridad ?? '';
    document.getElementById('param26').value                 = s.ser_descripcion ?? ''; // si lo tienes, opcional
    document.getElementById('param27').value                 = s.ser_consecutivo ?? '';
    document.getElementById('param11').value                 = s.ser_valor ?? '';
    document.getElementById('param13').value                 = s.cli_idciudad ?? '';
    document.getElementById('param9').value                 = s.ser_ciudadentrega ?? '';
    document.getElementById('param18').value                 = s.ser_idresponsable ?? '';
    document.getElementById('ser_guiare').value                 = s.ser_consecutivo ?? '';
    document.getElementById('param112').value                 = s.ser_valor ?? '';
    document.getElementById('rel_nom_credito').value                 = s.rel_nom_credito ?? '';
    document.getElementById('credito').value                 = s.rel_nom_credito ?? '';
    document.getElementById('param34').value                 = s.gui_tiposervicio ?? '';
    document.getElementById('ser_paquetedescripcion').value                 = s.ser_paquetedescripcion ?? '';
    document.getElementById('param21').value                 = s.ser_tipopaq ?? '';

    


  

    // document.getElementById('rel_nom_credito').checked = (s.rel_nom_credito == 1);

    


    
    


    // Tipo (param21) se seteará cuando carguemos los tipos (cargarTipos)
    // Tipo de pago (param8) y bloque "De Contado"
    aplicarReglasTipoPago(s);

    // iframe de firma
    const iframe = document.getElementById('iframeFirma');
    iframe.src = `/nueva_plataforma/view/recogerEntregar/firmar.php?para=${encodeURIComponent(id)}&accion=guardarFirmaRecogida`;

  } catch (e) {
    console.error(e);
    alert("Error cargando el servicio para recogida.");
  }
}

// Cargar SELECT Tipo (tabla tipo)
async function cargarTipos() {
  try {
    const resp = await fetch('../controller/RecogerController.php?accion=listarTipos');
    const tipos = await resp.json();

    const select = document.getElementById('param21');
    const valorInicial = select.getAttribute('data-value') || '';

    select.innerHTML = '<option value="">Seleccione...</option>';

    tipos.forEach(t => {
      const op = document.createElement('option');
      op.value = t.tip_nombre;
      op.textContent = t.tip_nombre;
      select.appendChild(op);
    });

    // Si ya tenemos tipo desde el servicio, lo intentará colocar
    if (window._tipoDesdeServicio) {
      select.value = window._tipoDesdeServicio;
    }

  } catch (e) {
    console.error(e);
    alert("Error cargando tipos.");
  }
}

// Cargar SELECT Método de pago (tipospagos) para Contado
async function cargarMetodosPago() {
  try {
    const resp = await fetch('../controller/RecogerController.php?accion=listarMetodosPago');
    const metodos = await resp.json();

    const select = document.getElementById('param30');
    select.innerHTML = '<option value="">Seleccione...</option>';

    metodos.forEach(m => {
      // Se espera que el controlador ya devuelva:
      // idtipospagos, pag_numerocuenta, pag_nombre
      const value = `${m.id}`;
      const op = document.createElement('option');
      op.value = value;
      op.textContent = m.pag_nombre;
      select.appendChild(op);
    });

  } catch (e) {
    console.error(e);
    alert("Error cargando métodos de pago.");
  }
}

// Cargar SELECT credito  
async function cargarCreditos() {
  try {
    const resp = await fetch('../controller/RecogerController.php?accion=listarCreditos');
    const creditos = await resp.json();

    const select = document.getElementById('credito');

    // Obtengo el valor que debe quedar seleccionado (si existe)
    const relNomCredito = document.getElementById('rel_nom_credito') 
                           ? document.getElementById('rel_nom_credito').value 
                           : "";

    console.log('Credito que llega '+relNomCredito );                       
    select.innerHTML = '<option value="">Seleccione...</option>';

    creditos.forEach(c => {
      const op = document.createElement('option');
      op.value = c.cre_nombre;
      op.textContent = c.cre_nombre;

      // Si coincide con el valor que se está editando → seleccionar
      // if (relNomCredito !== "" && relNomCredito === c.cre_nombre) {
      //   op.selected = true;
      // }

      select.appendChild(op);
    });

  } catch (e) {
    console.error(e);
    alert("Error cargando Creditos.");
  }
}

// Lógica de tipo de pago
function aplicarReglasTipoPago(s) {
  const clasificacion = parseInt(s.ser_clasificacion);
  const select = document.getElementById('param8');

  if (clasificacion === 2) {
      // crédito forzado
      select.innerHTML = '<option value="2" selected>Crédito</option>';
      
      // select.readonly = true;
      document.getElementById("param8").style.pointerEvents = "none";
  } else {
      // opciones normales
      select.innerHTML = `
        <option value="">Seleccione...</option>
        <option value="1">Contado</option>
        <option value="2">Crédito</option>
        <option value="3">Al Cobro</option>
        <option value="4">Pendiente x Cobrar</option>
      `;
      select.disabled = false;
      if (s.ser_clasificacion) {
        select.value = s.ser_clasificacion;
      }
  }

  actualizarBloqueContado();
}

// GUARDAR RECOGIDO
async function guardarRecogido() {

    if (document.getElementById('tipoAccion').value !== 'recogido') {
        Swal.fire({
            icon: 'warning',
            title: 'Acción requerida',
            text: 'Debe seleccionar la acción RECOGIDO.',
        });
        return;
    }

    const id = document.getElementById('idservicio').value;
    if (!id) {
        Swal.fire({
            icon: 'error',
            title: 'Sin servicio',
            text: 'No hay servicio cargado.',
        });
        return;
    }

    const form = document.getElementById('formRecogido');

    // ✅ Validación nativa del navegador
    if (!form.reportValidity()) {
        return; // Si algo falta, no sigue
    }

          // Validar nombre
      if (!validarNombreCompleto()) {
        alert('Verifique el nombre de quien entrega.');
        $('#param82').focus();
        return;
      }
    const data = new FormData(form);

    try {

        // ⏳ Mostrar carga
        Swal.fire({
            title: 'Guardando...',
            text: 'Por favor espere',
            allowOutsideClick: false,
            allowEscapeKey: false,
            didOpen: () => {
                Swal.showLoading();
            }
        });

        const resp = await fetch("../controller/RecogerController.php", {
            method: "POST",
            body: data
        });

        const r = await resp.json();
        
        Swal.close(); // ❗ Cerrar “cargando”
        

          if (r.ok) {
              Swal.fire({
                  icon: 'success',
                  title: 'Recogida guardada',
                  html: 'La recogida se guardó correctamente con número de guía <b>' + r.numeroGuia + '</b>',
                  showConfirmButton: true,
                  confirmButtonText: 'Cerrar'
              }).then(() => {

                  // 🟢 Recargar TODO el sitio (incluye cerrar popup e iframe)
                  window.parent.location.reload();

              });
          } else {
            Swal.fire({
                icon: 'error',
                title: 'Error al guardar',
                text: r.msg || 'No se pudo guardar la recogida.',
            });
          }

    } catch (e) {
        console.error(e);
        Swal.close();

        Swal.fire({
            icon: 'error',
            title: 'Error de conexión',
            text: 'Error al enviar la recogida.',
        });
    }
}



// GUARDAR NO RECOGIDO
async function guardarNoRecogido() {
  if (document.getElementById('tipoAccion').value !== 'norecogido') {
    alert("Debe seleccionar la acción NO RECOGIDO.");
    return;
  }

  const id = document.getElementById('idservicio').value;
  if (!id) {
    alert("No hay servicio cargado.");
    return;
  }

  const motivo = document.getElementById('motivo_no_recogido').value.trim();
  const fotoInput = document.getElementById('foto_evidencia');

  if (!motivo) {
    alert("Debe escribir un motivo.");
    return;
  }

  if (!fotoInput.files[0]) {
    alert("Debe adjuntar una foto evidencia.");
    return;
  }

  const data = new FormData();
  data.append("accion", "guardarNoRecogido");
  data.append("idservicio", id);
  data.append("motivo", motivo);
  data.append("foto_evidencia", fotoInput.files[0]);

  try {
    const resp = await fetch("../controller/RecogerController.php", {
      method: "POST",
      body: data
    });
    const r = await resp.json();
    if (r.ok) {
      alert("Guardado como NO RECOGIDO correctamente.");
    } else {
      alert("No se pudo guardar NO RECOGIDO: " + (r.msg || ""));
    }
  } catch (e) {
    console.error(e);
    alert("Error al enviar NO RECOGIDO.");
  }
}

// Cargar el servicio y los selects al cargar la página
document.addEventListener("DOMContentLoaded", () => {
  cargarServicio();
  cargarTipos();
  cargarMetodosPago();
  cargarCreditos();
});

// Obtener la hora actual en formato HH:MM
const ahora = new Date();
const horas  = String(ahora.getHours()).padStart(2, '0');
const minutos = String(ahora.getMinutes()).padStart(2, '0');

document.getElementById("param7").value = `${horas}:${minutos}`;

// Exponer funciones globales
window.guardarRecogido = guardarRecogido;
window.guardarNoRecogido = guardarNoRecogido;

// === Auto listeners for Peso & Volumen ===
document.addEventListener("DOMContentLoaded", () => {
  const peso = document.getElementById("ser_peso");
  const volumen = document.getElementById("ser_volumen");
  if (peso) peso.addEventListener("input", calcularValorAutomatico);
  if (volumen) volumen.addEventListener("input", calcularValorAutomatico);
});

// ============================
// CALCULAR VALOR AUTOMÁTICO
// ============================
async function calcularValorAutomatico() {
    const peso = document.getElementById("ser_peso") ? document.getElementById("ser_peso").value || 0 : 0;
    const volumen = document.getElementById("ser_volumen") ? document.getElementById("ser_volumen").value || 0 : 0;
    const ciudadOri = document.getElementById("param13") ? document.getElementById("param13").value : "";
    const ciudadDes = document.getElementById("param9") ? document.getElementById("param9").value : "";
    const tipoServ = document.getElementById("param34") ? document.getElementById("param34").value : "";
    const pordeclarado = document.getElementById("ser_valorseguro") ? document.getElementById("ser_valorseguro").value : 0;
    const tipoPago = document.getElementById("param8") ? document.getElementById("param8").value : 0;

    const rel_nom_credito = document.getElementById("rel_nom_credito")
    ? document.getElementById("rel_nom_credito").value
    : "";

    const tipocliente = rel_nom_credito ? 1 : 0;


    console.log("calcularValorAutomatico()", {peso, volumen, ciudadOri, ciudadDes, tipoServ,rel_nom_credito,tipocliente});

        // ===============================
    // 🚫 SI tipoServ ES 1000 → SALIR
    // ===============================
    if (tipoServ == 1000) {
        console.warn("Servicio 1000 → No se ejecuta cálculo.");
        return; // 🔥 importante: se termina aquí
    }

    // if (!tipoServ || (!peso && !volumen)) {
    //     return;
    // }

    const fd = new FormData();
    fd.append("accion", "calcularValorTotal");
    fd.append("peso", peso);
    fd.append("volumen", volumen);
    fd.append("ciudadOri", ciudadOri);
    fd.append("ciudadDes", ciudadDes);
    fd.append("tipoServ", tipoServ);

    fd.append("pordeclarado", pordeclarado);
    fd.append("tipoPago", tipoPago);
    // fd.append("abono", param55);
    // fd.append("porprestamo", param77);
      // fd.append("precioinicialkilos", param53);
      fd.append("rel_nom_credito", rel_nom_credito);
      fd.append("tipocliente", tipocliente);




    try {
        const resp = await fetch("../controller/RecogerController.php", {
            method: "POST",
            body: fd
        });
        const data = await resp.json();
        console.log("Respuesta calcularValorTotal:", data);
        if (data.ok && document.getElementById("param112")) {
            document.getElementById("param112").value = data.total;
            document.getElementById("param11").value = data.valorsinseguro;
        }
    } catch (e) {
        console.error("Error en calcularValorAutomatico:", e);
    }
}

    // ============================ VALIDAR NOMBRE COMPLETO ============================
    function validarNombreCompleto() {
      const nombre = $('#param82').val().trim();
      const partes = nombre.split(' ').filter(p => p.length > 0);
      if (partes.length < 2) {
        $('#errorNombre').show();
        return false;
      }
      $('#errorNombre').hide();
      return true;
    }
    $('#param82').on('blur keyup', validarNombreCompleto);
</script>
</body>
</html>


