<!DOCTYPE html>
<html lang="es">
<head>
<meta charset="UTF-8">
<meta name="viewport" content="width=device-width, initial-scale=1.0">
<title>Liquidación de Contrato</title>
<link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">

<style>
  html, body {
    height: 100%;
    margin: 0;
    padding: 0;
    background-color: #f8f9fa;
    font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;
  }

  .card-main {
    border-radius: 0; /* Para ocupar todo el espacio */
    box-shadow: none;
    background: #fff;
    height: 100vh; /* ✅ Ocupa todo el alto visible */
    display: flex;
    flex-direction: column;
  }

  h3 {
    font-weight: 600;
    color: #2c3e50;
    text-align: center;
    margin: 16px 0;
  }

  iframe {
    flex: 1; /* ✅ El iframe ocupa todo el espacio disponible */
    width: 100%;
    border: none;
  }

  #signature-pad {
    border: 2px dashed #6c757d;
    border-radius: 10px;
    width: 100%;
    height: 220px;
    background-color: #fff;
    cursor: crosshair;
    touch-action: none;
  }

  .modal-content {
    border-radius: 16px;
    box-shadow: 0 4px 25px rgba(0,0,0,0.15);
  }

  .btn-primary {
    background: linear-gradient(135deg, #007bff, #0056b3);
    border: none;
  }

  .btn-primary:hover {
    background: linear-gradient(135deg, #0056b3, #004094);
  }
</style>
</head>

<body>
  <div class="card-main">
    <h3>📄 Confirmacion Examenes Medicos</h3>

    <iframe id="iframeDesprendible" name="iframeDesprendible"></iframe>

    <form id="formDesprendible" method="POST" action="/nueva_plataforma/view/Pdfs/ExamenesMedicosLiqui.php" target="iframeDesprendible" style="display:none;">
      <?php foreach ($datos as $clave => $valor): ?>
          <input type="hidden" name="<?= htmlspecialchars($clave) ?>" value="<?= htmlspecialchars($valor) ?>">
      <?php endforeach; ?>
    </form>

    
    <div class="text-center p-3">
      <button class="btn btn-primary px-4 py-2" data-bs-toggle="modal" data-bs-target="#modalFirma">
        ✍️ Firmar Documento
      </button>
    </div>
    
  </div>

<!-- Modal de Firma -->
<div class="modal fade" id="modalFirma" tabindex="-1" aria-labelledby="modalFirmaLabel" aria-hidden="true">
  <div class="modal-dialog modal-dialog-centered modal-lg">
    <div class="modal-content">
      <div class="modal-header">
        <h5 class="modal-title" id="modalFirmaLabel">Firmar Documento</h5>
        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Cerrar"></button>
      </div>

      <div class="modal-body text-center">
        <p class="text-muted mb-2">Por favor firme dentro del recuadro:</p>
        <canvas id="signature-pad"></canvas>

        <div class="mt-3">
          <button id="clear" type="button" class="btn btn-outline-secondary btn-sm">🧽 Borrar</button>
        </div>

        <div class="mt-4 text-start">
          <label class="fw-semibold d-block mb-2">¿Desea realizarse los exámenes médicos de egreso?</label>
          <div class="form-check">
            <input class="form-check-input" type="radio" name="examenes" id="examenesSi" value="SI">
            <label class="form-check-label" for="examenesSi">Sí</label>
          </div>
          <div class="form-check">
            <input class="form-check-input" type="radio" name="examenes" id="examenesNo" value="NO">
            <label class="form-check-label" for="examenesNo">No</label>
          </div>
        </div>
      </div>

      <div class="modal-footer">
        <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancelar</button>
        <button type="button" id="guardarFirma" class="btn btn-primary">💾 Guardar Firma</button>
      </div>
    </div>
  </div>
</div>

<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>
<script>
  // === Lógica original intacta ===
  const canvas = document.getElementById('signature-pad');
  const ctx = canvas.getContext('2d');
  let drawing = false;

  function resizeCanvas() {
    const ratio = window.devicePixelRatio || 1;
    const rect = canvas.getBoundingClientRect();
    canvas.width = rect.width * ratio;
    canvas.height = rect.height * ratio;
    ctx.scale(ratio, ratio);
    ctx.lineWidth = 2;
    ctx.lineCap = 'round';
    ctx.strokeStyle = '#000';
  }

  const modalFirma = document.getElementById('modalFirma');
  modalFirma.addEventListener('shown.bs.modal', resizeCanvas);
  window.addEventListener('resize', () => {
    if (modalFirma.classList.contains('show')) resizeCanvas();
  });

  function getPos(e) {
    const rect = canvas.getBoundingClientRect();
    if (e.touches) {
      return { x: e.touches[0].clientX - rect.left, y: e.touches[0].clientY - rect.top };
    }
    return { x: e.clientX - rect.left, y: e.clientY - rect.top };
  }

  canvas.addEventListener('mousedown', e => {
    drawing = true;
    const pos = getPos(e);
    ctx.beginPath();
    ctx.moveTo(pos.x, pos.y);
  });

  canvas.addEventListener('mousemove', e => {
    if (!drawing) return;
    const pos = getPos(e);
    ctx.lineTo(pos.x, pos.y);
    ctx.stroke();
  });

  canvas.addEventListener('mouseup', () => drawing = false);
  canvas.addEventListener('mouseout', () => drawing = false);

  canvas.addEventListener('touchstart', e => {
    e.preventDefault();
    drawing = true;
    const pos = getPos(e);
    ctx.beginPath();
    ctx.moveTo(pos.x, pos.y);
  });

  canvas.addEventListener('touchmove', e => {
    e.preventDefault();
    if (!drawing) return;
    const pos = getPos(e);
    ctx.lineTo(pos.x, pos.y);
    ctx.stroke();
  });

  canvas.addEventListener('touchend', () => drawing = false);

  document.getElementById('clear').addEventListener('click', () => {
    ctx.clearRect(0, 0, canvas.width, canvas.height);
  });

  document.getElementById('guardarFirma').addEventListener('click', async () => {
    const dataURL = canvas.toDataURL('image/png');
    const examenes = document.querySelector('input[name="examenes"]:checked');
    if (!examenes) {
      alert('⚠️ Por favor seleccione si desea o no realizarse los exámenes.');
      return;
    }

    const formData = new FormData();
    formData.append('accion', 'guardarFirma');
    formData.append('idhojadevida', '<?php echo $id; ?>');
    formData.append('firma', dataURL);
    formData.append('examenes', examenes.value);

    const res = await fetch('/nueva_plataforma/controller/FirmarExamenesLiquiController.php', { 
      method: 'POST',
      body: formData 
    });
    
    const data = await res.json();

    if (data.success) {
      alert('✅ Firma guardada correctamente.');

      const modal = bootstrap.Modal.getInstance(modalFirma);
      modal.hide();

      // ⏳ Pequeño retraso para permitir que el usuario vea el mensaje antes de cerrar
      setTimeout(() => {
        window.close();
      }, 1000); // 1 segundo
    } else {
      alert('❌ Error al guardar la firma.');
    } 
  });

  document.getElementById('formDesprendible').submit();
</script>
</body>
</html>

