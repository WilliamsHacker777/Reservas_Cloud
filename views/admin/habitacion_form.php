<?php require_once __DIR__ . '/../layout/header.php'; ?>

<div class="container py-5">
    <div class="row justify-content-center">
        <div class="col-md-8">
            <h1 class="mb-4">
                <?= $accion === 'crear' ? 'Nueva Habitación' : 'Editar Habitación' ?>
            </h1>

            <form method="POST" enctype="multipart/form-data" class="needs-validation">
                <!-- Número -->
                <div class="mb-3">
                    <label for="numero" class="form-label">Número de Habitación *</label>
                    <input type="text" class="form-control" id="numero" name="numero" 
                           value="<?= htmlspecialchars($habitacion['numero'] ?? '') ?>" required>
                </div>

                <!-- Tipo -->
                <div class="mb-3">
                    <label for="tipo" class="form-label">Tipo de Habitación *</label>
                    <select class="form-select" id="tipo" name="tipo" required>
                        <option value="Simple" <?= ($habitacion['tipo'] ?? '') === 'Simple' ? 'selected' : '' ?>>Simple</option>
                        <option value="Doble" <?= ($habitacion['tipo'] ?? '') === 'Doble' ? 'selected' : '' ?>>Doble</option>
                        <option value="Suite" <?= ($habitacion['tipo'] ?? '') === 'Suite' ? 'selected' : '' ?>>Suite</option>
                        <option value="Familiar" <?= ($habitacion['tipo'] ?? '') === 'Familiar' ? 'selected' : '' ?>>Familiar</option>
                    </select>
                </div>

                <!-- Precio -->
                <div class="mb-3">
                    <label for="precio" class="form-label">Precio por Noche (S/) *</label>
                    <input type="number" step="0.01" class="form-control" id="precio" name="precio" 
                           value="<?= htmlspecialchars($habitacion['precio'] ?? '0') ?>" required>
                </div>

                <!-- Descripción -->
                <div class="mb-3">
                    <label for="descripcion" class="form-label">Descripción</label>
                    <textarea class="form-control" id="descripcion" name="descripcion" rows="4"><?= htmlspecialchars($habitacion['descripcion'] ?? '') ?></textarea>
                </div>

                <!-- Estado -->
                <div class="mb-3">
                    <label for="estado" class="form-label">Estado</label>
                    <select class="form-select" id="estado" name="estado">
                        <option value="1" <?= ($habitacion['estado'] ?? 'disponible') === 'disponible' ? 'selected' : '' ?>>Disponible</option>
                        <option value="2" <?= ($habitacion['estado'] ?? '') === 'ocupada' ? 'selected' : '' ?>>Ocupada</option>
                        <option value="3" <?= ($habitacion['estado'] ?? '') === 'mantenimiento' ? 'selected' : '' ?>>Mantenimiento</option>
                    </select>
                </div>

                <!-- Imagen -->
                <div class="mb-3">
                    <label for="imagen" class="form-label">Imagen de la Habitación</label>
                    <input type="file" class="form-control" id="imagen" name="imagen" accept="image/*">
                    <small class="text-muted">Formatos: JPG, PNG, WebP. Máximo 5MB.</small>
                    
                    <?php if (!empty($habitacion['imagen']) && $accion === 'editar'): ?>
                        <div class="mt-3">
                            <p class="text-muted">Imagen actual:</p>
                            <img src="img/<?= htmlspecialchars($habitacion['imagen']) ?>" alt="Habitación" style="max-width: 200px; border-radius: 8px;">
                            <p class="text-muted small">Si subes una nueva imagen, esta será reemplazada.</p>
                        </div>
                    <?php endif; ?>
                </div>

                <!-- Botones -->
                <div class="d-grid gap-2 d-md-flex justify-content-md-end">
                    <a href="/?action=admin/habitaciones" class="btn btn-secondary">Cancelar</a>
                    
                    <?php if ($accion === 'crear'): ?>
                        <button type="submit" formaction="/?action=admin/habitacion/guardar" class="btn btn-success">
                            <i class="bi bi-check-circle"></i> Crear Habitación
                        </button>
                    <?php else: ?>
                        <input type="hidden" name="id" value="<?= htmlspecialchars($habitacion['id']) ?>">
                        <button type="submit" formaction="/?action=admin/habitacion/actualizar" class="btn btn-primary">
                            <i class="bi bi-check-circle"></i> Actualizar Habitación
                        </button>
                    <?php endif; ?>
                </div>
            </form>
        </div>
    </div>
</div>

<style>
    @media (max-width: 768px) {
        .container { padding: 1rem; }
        .d-grid { flex-direction: column; }
        .btn { width: 100%; }
    }
</style>

<?php require_once __DIR__ . '/../layout/footer.php'; ?>
