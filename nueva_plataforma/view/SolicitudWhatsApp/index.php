<!DOCTYPE html>
<html lang="es">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1">
  <link rel="shortcut icon" href="../../images/Logo Google Nuevo.png">

  <title>Solicitud de Servicio</title>

  <!-- Bootstrap 5 -->
  <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
  <!-- FontAwesome -->
  <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">

  <!-- Reutiliza el mismo estilo (o crea SolicitudWhatsApp.css basado en este) -->
  <link rel="stylesheet" href="../assets/css/RecogidasMovil.css">
</head>

<body>

<div class="container-fluid mt-3 px-2 px-md-4">
  <div class="card shadow rounded">

    <!-- HEADER -->
    <div class="card-header mi-header d-flex justify-content-between align-items-center px-3 py-2">
      <h5 class="mb-0 d-flex align-items-center gap-2 text-white">
        <i class="fas fa-box"></i> Solicitud de Servicio
      </h5>
      <div class="d-flex align-items-center gap-2">
        <button class="btn btn-light btn-icon" data-bs-toggle="modal" data-bs-target="#modalAyuda">
          <i class="fas fa-circle-question text-warning"></i>
        </button>
      </div>
    </div>

    <div class="card-body">
      <!-- IMPORTANTE:
           - Este form será público (WhatsApp).
           - El action lo define tu controller (POST por AJAX o normal).
      -->
      <form
        id="formSolicitud"
        name="formSolicitud"
        method="POST"
        action="SolicitudWhatsAppController.php"
        data-endpoint="SolicitudWhatsAppController.php"
        enctype="multipart/form-data">

        <!-- Token/Tracking (recomendado) -->
        <input type="hidden" id="token" name="token" value="<?= htmlspecialchars($_GET['token'] ?? '') ?>">
        <input type="hidden" id="canal" name="canal" value="WHATSAPP_FORM">

        <!-- ================= REMITENTE ================= -->
        <h5 class="text-primary">Remitente</h5>

        <div class="row g-3">
          <div class="col-12 col-md-4">
            <label>CC / NIT</label>
            <!-- Ajusta name/param según el chatbot -->
            <input type="text" id="param1" name="param1" class="form-control">
          </div>

          <div class="col-12 col-md-4 input-con-spinner">
            <label>Teléfono</label>
            <!-- Ajusta name/param según el chatbot -->
            <input type="text" id="param2" name="param2" class="form-control" required>
            <div id="spinner-remitente" class="spinner-busqueda"></div>
          </div>

          <div class="col-12 col-md-4">
            <label>Nombre Remitente</label>
            <!-- si usas id de cliente en chatbot, conserva hidden -->
            <input type="hidden" id="param61" name="param61">
            <input type="text" id="param6" name="param6" class="form-control mt-1" required>
          </div>

          <div class="col-12 col-md-4">
            <label>Ciudad Origen</label>
            <select id="param4" name="param4" class="form-select" required>
              <option value="">Seleccione...</option>
              <?php foreach (($ciudadesR ?? []) as $ciudad): ?>
                <option value="<?= $ciudad['idciudades'] ?>"><?= $ciudad['ciu_nombre'] ?></option>
              <?php endforeach; ?>
            </select>
          </div>

          <div class="col-12 col-md-8">
            <label class="form-label">Dirección Origen (*)</label>
            <div class="row g-2 align-items-end">
              <div class="col-12 col-md-2">
                <select id="param5" name="param5" class="form-select" required>
                  <option value="">Tipo vía...</option>
                  <?php foreach (($direcciones ?? []) as $direccion): ?>
                    <option value="<?= $direccion['dir_nombre'] ?>"><?= $direccion['dir_nombre'] ?></option>
                  <?php endforeach; ?>
                </select>
              </div>

              <div class="col-4 col-md-1"><input type="text" id="dir1R" name="dir1R" class="form-control" required></div>
              <div class="col-2 col-md-1 text-center">#</div>
              <div class="col-4 col-md-1"><input type="text" id="dir2R" name="dir2R" class="form-control" required></div>
              <div class="col-2 col-md-1 text-center">-</div>
              <div class="col-4 col-md-1"><input type="text" id="dir3R" name="dir3R" class="form-control" required></div>

              <div class="col-12 col-md-5">
                <label>Lugar</label>
                <!-- OJO: en tu vista original param19 tenía name="selectComplemento".
                     Mantengo esa misma forma para no romper tu JS.
                -->
                <select id="param19" name="selectComplemento" class="form-select" required>
                  <option value="">Seleccione...</option>
                  <?php foreach (($lugares ?? []) as $lugar): ?>
                    <option value="<?= $lugar['lug_nombre'] ?>"><?= $lugar['lug_nombre'] ?></option>
                  <?php endforeach; ?>
                </select>
              </div>

              <div id="camposComplemento" class="col-12"></div>
              <input type="hidden" name="complemento_detalle_final" id="complementoFinal">
            </div>
          </div>

          <div class="col-12 col-md-4">
            <label>Barrio (Origen)</label>
            <input type="text" id="param23" name="param23" class="form-control">
          </div>
        </div>

        <hr>

        <!-- ================= DESTINATARIO ================= -->
        <h5 class="text-primary">Destinatario</h5>

        <div class="row g-3">
          <div class="col-12 col-md-4 input-con-spinner">
            <label>Teléfono</label>
            <input type="text" id="param8" name="param8" class="form-control" required>
            <div id="spinner-destinatario" class="spinner-busqueda"></div>
          </div>

          <div class="col-12 col-md-4">
            <label>Nombre</label>
            <input type="text" id="param9" name="param9" class="form-control" required>
          </div>

          <div class="col-12 col-md-4">
            <label>Ciudad Destino</label>
            <select id="param11" name="param11" class="form-select" required>
              <option value="">Seleccione...</option>
              <?php foreach (($ciudades ?? []) as $ciudad): ?>
                <option value="<?= $ciudad['idciudades'] ?>"><?= $ciudad['ciu_nombre'] ?></option>
              <?php endforeach; ?>
            </select>
          </div>

          <div class="col-12 col-md-8">
            <label class="form-label">Dirección Destino (*)</label>
            <div class="row g-2 align-items-end">
              <div class="col-12 col-md-2">
                <select id="param10" name="param10" class="form-select" required>
                  <option value="">Tipo vía...</option>
                  <?php foreach (($direcciones ?? []) as $direccion): ?>
                    <option value="<?= $direccion['dir_nombre'] ?>"><?= $direccion['dir_nombre'] ?></option>
                  <?php endforeach; ?>
                </select>
              </div>

              <div class="col-4 col-md-1"><input type="text" id="dir1D" name="dir1D" class="form-control" required></div>
              <div class="col-2 col-md-1 text-center">#</div>
              <div class="col-4 col-md-1"><input type="text" id="dir2D" name="dir2D" class="form-control" required></div>
              <div class="col-2 col-md-1 text-center">-</div>
              <div class="col-4 col-md-1"><input type="text" id="dir3D" name="dir3D" class="form-control" required></div>

              <div class="col-12 col-md-5">
                <label>Lugar</label>
                <select id="param21" name="param21" class="form-select" required>
                  <option value="">Seleccione...</option>
                  <?php foreach (($lugares ?? []) as $lugar): ?>
                    <option value="<?= $lugar['lug_nombre'] ?>"><?= $lugar['lug_nombre'] ?></option>
                  <?php endforeach; ?>
                </select>
              </div>

              <div id="camposComplementoD" class="col-12"></div>
              <input type="hidden" id="complementoFinalD" name="complemento_detalle_finalD">
            </div>
          </div>

          <div class="col-12 col-md-4">
            <label>Barrio (Destino)</label>
            <input type="text" id="param24" name="param24" class="form-control">
          </div>
        </div>

        <hr>

        <!-- ================= SERVICIO ================= -->
        <h5 class="text-primary">Servicio</h5>

        <div class="row g-3">

          <!-- CONTIENE -->
          <div class="col-12">
            <label>¿Qué contiene el paquete? (*)</label>
            <textarea 
              id="param13" 
              name="param13" 
              class="form-control" 
              rows="3"
              placeholder="Ej: Ropa, documentos, repuestos, productos electrónicos..."
              required>
            </textarea>
          </div>

          <!-- FOTOS -->
          <div class="col-12">
            <label>Fotos del paquete (puede subir una o varias)</label>
            <input 
              type="file" 
              id="fotos_paquete" 
              name="fotos_paquete[]" 
              class="form-control"
              accept="image/*"
              multiple
              capture="environment">
              
              <small class="text-muted">
                Puedes tomar fotos directamente desde tu celular.
              </small>
          </div>

          <div class="col-12 d-none" id="bloqueCreditosAsociados">
            <div class="alert alert-info mb-2" id="mensajeCreditos">
              Este contacto tiene creditos asociados.
            </div>
            <label for="cliente_credito">Credito asociado</label>
            <select id="cliente_credito" name="cliente_credito" class="form-select">
              <option value="">Seleccione...</option>
            </select>
            <small class="text-muted">Si seleccionas un credito, la solicitud quedara vinculada a ese cliente credito.</small>
          </div>

        </div>

        <hr>
<!-- HIDDEN (mantengo tus hidden, pero OJO: sin sesión) -->
        <input type="hidden" name="param15" value="Solicitud WhatsApp">
        <input type="hidden" id="id_param" name="id_param">
        <input type="hidden" id="id_param0" name="id_param0">
        <input type="hidden" id="id_param1" name="id_param1" value="0">
        <input type="hidden" id="id_param2" name="id_param2">
        <input type="hidden" id="param113" name="param113">
        <input type="hidden" id="param111" name="param111" value="0">
        <input type="hidden" id="valorSinSeguro" name="valorSinSeguro">

        <!-- BOTON -->
        <div class="text-end mt-4">
          <button type="submit" class="btn btn-success btn-lg px-4">
            <i class="fas fa-paper-plane"></i> Enviar solicitud
          </button>
        </div>

      </form>
    </div>
  </div>
</div>


<!-- MODAL AYUDA -->
<div class="modal fade" id="modalAyuda" tabindex="-1">
  <div class="modal-dialog modal-lg modal-dialog-centered">
    <div class="modal-content">
      <div class="modal-header bg-warning">
        <h5 class="modal-title">
          <i class="fas fa-circle-question"></i> ¿Cómo solicitar un servicio?
        </h5>
        <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
      </div>

      <div class="modal-body text-center">
        <div class="ratio ratio-16x9">
          <iframe
            id="videoAyuda"
            src="https://www.youtube.com/embed/ID_DEL_VIDEO"
            title="Video de ayuda"
            allow="accelerometer; autoplay; clipboard-write; encrypted-media; gyroscope; picture-in-picture"
            allowfullscreen>
          </iframe>
        </div>

        <p class="mt-3 text-muted">
          Completa los datos y un asesor de Transmillas te contactará para confirmar y programar la recogida.
        </p>
      </div>
    </div>
  </div>
</div>


<!-- Bootstrap JS -->
<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>
<!-- SweetAlert2 -->
<script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>

<!-- JS propio del modulo Solicitud WhatsApp -->
<script src="../assets/js/SolicitudWhatsApp.js"></script>

</body>
</html>




