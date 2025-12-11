<?php require_once __DIR__ . '/../layout/header.php'; ?>

<?php
// DEFINICIÓN DE ESTADOS Y COLORES
// Esto traduce los números de la BD a Texto y Colores visuales
$mapaEstados = [
    1 => 'Disponible',
    2 => 'Ocupado',
    3 => 'Mantenimiento'
];

$mapaColores = [
    1 => 'bg-success',   // Verde para disponible
    2 => 'bg-danger',    // Rojo para ocupado
    3 => 'bg-warning text-dark'   // Amarillo para mantenimiento
];
?>

<div class="container py-5">
    <div class="row">
        <div class="col-md-12">
            <div class="d-flex justify-content-between align-items-center mb-4">
                <h1><i class="bi bi-door-open"></i> Gestionar Habitaciones</h1>
                <a href="/?action=admin/habitacion/crear" class="btn btn-success">
                    <i class="bi bi-plus-circle"></i> Nueva Habitación
                </a>
            </div>

            <?php if (empty($habitaciones)): ?>
                <div class="alert alert-info">No hay habitaciones registradas. <a href="/?action=admin/habitacion/crear">Crear una.</a></div>
            <?php else: ?>
                <div class="table-responsive">
                    <table class="table table-striped table-hover">
                        <thead class="table-dark">
                            <tr>
                                <th>Número</th>
                                <th>Tipo</th>
                                <th>Precio</th>
                                <th>Estado</th>
                                <th>Acciones</th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php foreach ($habitaciones as $h): ?>
                                <?php 
                                    // Obtenemos el ID del estado (1, 2 o 3)
                                    $estadoId = $h['estado']; 
                                    
                                    // Buscamos el texto, si no existe ponemos 'Desconocido'
                                    $textoEstado = $mapaEstados[$estadoId] ?? 'Desconocido';
                                    
                                    // Buscamos el color, si no existe ponemos gris (secondary)
                                    $claseColor = $mapaColores[$estadoId] ?? 'bg-secondary';
                                ?>
                                <tr>
                                    <td><?= htmlspecialchars($h['numero']) ?></td>
                                    <td><?= htmlspecialchars($h['tipo']) ?></td>
                                    <td>S/ <?= number_format($h['precio'], 2) ?></td>
                                    
                                    <td>
                                        <span class="badge <?= $claseColor ?>">
                                            <?= htmlspecialchars($textoEstado) ?>
                                        </span>
                                    </td>
                                    
                                    <td>
                                        <a href="/?action=admin/habitacion/editar&id=<?= urlencode($h['id']) ?>" class="btn btn-sm btn-primary">
                                            <i class="bi bi-pencil"></i>
                                        </a>
                                        <a href="/?action=admin/habitacion/eliminar&id=<?= urlencode($h['id']) ?>" class="btn btn-sm btn-danger" onclick="return confirm('¿Estás seguro?')">
                                            <i class="bi bi-trash"></i>
                                        </a>
                                    </td>
                                </tr>
                            <?php endforeach; ?>
                        </tbody>
                    </table>
                </div>
            <?php endif; ?>

            <a href="/?action=admin" class="btn btn-secondary mt-3">
                <i class="bi bi-arrow-left"></i> Volver al Panel
            </a>
        </div>
    </div>
</div>

<style>
    @media (max-width: 768px) {
        .table-responsive {
            font-size: 0.85rem;
        }
        .btn-sm {
            padding: 0.25rem 0.5rem;
            font-size: 0.75rem;
        }
    }
</style>

<?php require_once __DIR__ . '/../layout/footer.php'; ?>