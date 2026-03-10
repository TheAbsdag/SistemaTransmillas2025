<form id="popupForm" method="post" action="">
    <input type="hidden" name="accion" value="guardar_zona">
    <input type="hidden" name="id" value="<?= $id_seguimiento ?>">
    <div class="mb-3">
        <label for="zona" class="form-label">Zona de trabajo</label>
        <select name="zona" id="zona" class="form-select" required>
            <option value="">Seleccione</option>
            <?php foreach ($zonas as $z): ?>
                <option value="<?= $z['idzonatrabajo'] ?>"><?= htmlspecialchars($z['zon_nombre']) ?></option>
            <?php endforeach; ?>
        </select>
    </div>
    <button type="submit" class="btn btn-primary">Guardar</button>
</form>