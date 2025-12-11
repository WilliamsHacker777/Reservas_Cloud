<?php require_once __DIR__ . '/../layout/header.php'; ?>

<?php
    // Cálculos rápidos para el dashboard
    $habitacionesOcupadas = $totalHabitaciones - $habitacionesDisponibles;
    if ($habitacionesOcupadas < 0) {
        $habitacionesOcupadas = 0; // por seguridad
    }

    $porcentajeOcupadas = ($totalHabitaciones > 0)
        ? round(($habitacionesOcupadas / $totalHabitaciones) * 100, 1)
        : 0;

    $porcentajeDisponibles = ($totalHabitaciones > 0)
        ? round(($habitacionesDisponibles / $totalHabitaciones) * 100, 1)
        : 0;
?>

<div class="container py-5">
    <div class="row">
        <div class="col-md-12">
            <h1 class="mb-4">
                <i class="bi bi-speedometer2"></i> Panel Administrativo
            </h1>
            
            <!-- Tarjetas de resumen -->
            <div class="row g-4">
                <div class="col-md-3">
                    <div class="card bg-primary text-white shadow-sm">
                        <div class="card-body">
                            <h6 class="card-title text-uppercase mb-2">
                                Total de Habitaciones
                            </h6>
                            <p class="card-text display-6 mb-0">
                                <?= $totalHabitaciones ?>
                            </p>
                        </div>
                    </div>
                </div>

                <div class="col-md-3">
                    <div class="card bg-success text-white shadow-sm">
                        <div class="card-body">
                            <h6 class="card-title text-uppercase mb-2">
                                Disponibles
                            </h6>
                            <p class="card-text display-6 mb-0">
                                <?= $habitacionesDisponibles ?>
                            </p>
                            <small class="d-block mt-1">
                                <?= $porcentajeDisponibles ?>% del total
                            </small>
                        </div>
                    </div>
                </div>

                <div class="col-md-3">
                    <div class="card bg-danger text-white shadow-sm">
                        <div class="card-body">
                            <h6 class="card-title text-uppercase mb-2">
                                Ocupadas
                            </h6>
                            <p class="card-text display-6 mb-0">
                                <?= $habitacionesOcupadas ?>
                            </p>
                            <small class="d-block mt-1">
                                <?= $porcentajeOcupadas ?>% del total
                            </small>
                        </div>
                    </div>
                </div>

                <div class="col-md-3">
                    <div class="card bg-dark text-white shadow-sm">
                        <div class="card-body">
                            <h6 class="card-title text-uppercase mb-2">
                                Estado de Ocupación
                            </h6>
                            <p class="mb-2">
                                Ocupación actual
                            </p>
                            <div class="progress" style="height: 10px;">
                                <div 
                                    class="progress-bar bg-warning" 
                                    role="progressbar" 
                                    style="width: <?= $porcentajeOcupadas ?>%;" 
                                    aria-valuenow="<?= $porcentajeOcupadas ?>" 
                                    aria-valuemin="0" 
                                    aria-valuemax="100">
                                </div>
                            </div>
                            <small class="d-block mt-2">
                                <?= $porcentajeOcupadas ?>% ocupadas / <?= $porcentajeDisponibles ?>% disponibles
                            </small>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Sección de detalle/resumen -->
            <div class="row mt-5">
                <div class="col-md-6">
                    <h3>Acciones Rápidas</h3>
                    <a href="/?action=admin/habitaciones" class="btn btn-primary me-2 mb-2">
                        <i class="bi bi-door-open"></i> Gestionar Habitaciones
                    </a>
                    <!-- Puedes ir agregando más acciones -->
                    <a href="/?action=admin/reservas" class="btn btn-outline-secondary mb-2">
                        <i class="bi bi-calendar-check"></i> Ver Reservas
                    </a>
                </div>

                <div class="col-md-6">
                    <h3>Resumen</h3>
                    <ul class="list-group">
                        <li class="list-group-item d-flex justify-content-between">
                            <span>Total de habitaciones</span>
                            <span class="fw-bold"><?= $totalHabitaciones ?></span>
                        </li>
                        <li class="list-group-item d-flex justify-content-between">
                            <span>Habitaciones disponibles</span>
                            <span class="fw-bold text-success"><?= $habitacionesDisponibles ?> (<?= $porcentajeDisponibles ?>%)</span>
                        </li>
                        <li class="list-group-item d-flex justify-content-between">
                            <span>Habitaciones ocupadas</span>
                            <span class="fw-bold text-danger"><?= $habitacionesOcupadas ?> (<?= $porcentajeOcupadas ?>%)</span>
                        </li>
                    </ul>
                </div>
            </div>

        </div>
    </div>
</div>

<?php require_once __DIR__ . '/../layout/footer.php'; ?>
