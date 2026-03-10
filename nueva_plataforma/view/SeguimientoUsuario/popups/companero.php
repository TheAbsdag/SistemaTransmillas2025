<form id="popupForm" method="post">
    <input type="hidden" name="accion" value="guardar_companero">
    <input type="hidden" name="id" value="<?= $id ?>">
    <div class="mb-3">
        <label for="companero" class="form-label">Compañero</label>
        <select name="companero" id="companero" class="form-select" required>
            <option value="">Seleccione</option>
            <?php foreach ($operarios as $o): ?>
                <option value="<?= $o['idusuarios'] ?>">
                    <?= htmlspecialchars($o['usu_nombre']) ?>
                </option>
            <?php endforeach; ?>
        </select>
    </div>
    <button type="submit" class="btn btn-primary">Guardar</button>
</form>