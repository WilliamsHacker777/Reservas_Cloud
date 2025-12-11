<?php require_once __DIR__ . "/../layout/header.php"; ?>

<div class="container py-5">
    <div class="row mb-4">
        <div class="col-md-8">
            <h2 class="fw-bold" style="font-size: 2.2rem; color: #333; border-bottom: 3px solid #667eea; padding-bottom: 15px; display: inline-block;">Historial de reservas</h2>
        </div>
        <div class="col-md-4 text-md-end">
            <a href="/?action=home" class="btn btn-primary">
                <i class="bi bi-plus-circle"></i> Nueva Reserva
            </a>
        </div>
    </div>

    <?php if (empty($reservas)): ?>
        <div class="alert alert-info text-center py-5" role="alert">
            <i class="bi bi-inbox" style="font-size: 3rem;"></i>
            <h5 class="mt-3">No tienes reservas aún</h5>
            <p class="mb-0">¡Haz tu primera reserva ahora!</p>
            <a href="/?action=home" class="btn btn-primary mt-3">Buscar Habitaciones</a>
        </div>
    <?php else: ?>
        <div class="row">
            <?php foreach ($reservas as $reserva): ?>
                <div class="col-lg-6 mb-4">
                    <div class="card shadow-sm border-0 h-100 reserva-card">
                        <div class="card-body">
                            <!-- Encabezado con estado -->
                            <div class="d-flex justify-content-between align-items-start mb-3">
                                <h5 class="card-title mb-0">
                                    <?= htmlspecialchars($reserva['habitacion']['nombre'] ?? 'Habitación') ?>
                                </h5>
                                <span class="badge <?= $reserva['payment_status'] === 'received' ? 'bg-success' : ($reserva['payment_status'] === 'pending' ? 'bg-warning' : 'bg-secondary') ?>">
                                    <?= $reserva['payment_status'] === 'received' ? 'Confirmada' : ($reserva['payment_status'] === 'pending' ? 'Pendiente' : 'Cancelada') ?>
                                </span>
                            </div>

                            <!-- Descripción breve -->
                            <p class="text-muted small mb-3">
                                <?= htmlspecialchars(substr($reserva['habitacion']['descripcion'] ?? '', 0, 80)) ?>...
                            </p>

                            <!-- Detalles -->
                            <div class="reserva-details mb-3">
                                <div class="detail-row mb-2">
                                    <span class="detail-label">Check-in:</span>
                                    <span class="detail-value"><?= date('d/m/Y', strtotime($reserva['fecha_inicio'])) ?></span>
                                </div>
                                <div class="detail-row mb-2">
                                    <span class="detail-label">Check-out:</span>
                                    <span class="detail-value"><?= date('d/m/Y', strtotime($reserva['fecha_fin'])) ?></span>
                                </div>

                                <?php 
                                    $inicio = new DateTime($reserva['fecha_inicio']);
                                    $fin = new DateTime($reserva['fecha_fin']);
                                    $noches = $fin->diff($inicio)->days;
                                ?>

                                <div class="detail-row mb-2">
                                    <span class="detail-label">Noches:</span>
                                    <span class="detail-value"><?= $noches ?> noche<?= $noches !== 1 ? 's' : '' ?></span>
                                </div>
                                <div class="detail-row border-top pt-2 mt-2">
                                    <span class="detail-label fw-bold">Total:</span>
                                    <span class="detail-value fw-bold text-primary">S/ <?= number_format($reserva['total'], 2) ?></span>
                                </div>
                            </div>

                            <!-- Método de pago -->
                            <div class="payment-method mb-3">
                                <small class="text-muted">Método de pago: 
                                    <strong>
                                        <?= $reserva['payment_method'] === 'card' ? 'Tarjeta de Crédito' : ($reserva['payment_method'] === 'yape' ? 'Yape' : 'Otro') ?>
                                    </strong>
                                </small>
                            </div>

                            <!-- Acciones -->
                            <div class="d-grid gap-2">
                                <?php if ($reserva['payment_status'] === 'pending'): ?>
                                    <button class="btn btn-outline-warning btn-sm" disabled>
                                        <i class="bi bi-clock-history"></i> Pago Pendiente de Confirmación
                                    </button>
                                <?php elseif ($reserva['payment_status'] === 'received'): ?>
                                    <button class="btn btn-outline-success btn-sm" disabled>
                                        <i class="bi bi-check-circle"></i> Reserva Confirmada
                                    </button>
                                <?php endif; ?>
                            </div>
                        </div>

                        
                    </div>
                </div>
            <?php endforeach; ?>
        </div>
    <?php endif; ?>
</div>

<style>
    .reserva-card {
        transition: transform 0.2s, box-shadow 0.2s;
    }

    .reserva-card:hover {
        transform: translateY(-4px);
        box-shadow: 0 8px 16px rgba(0, 0, 0, 0.15) !important;
    }

    .reserva-details {
        background-color: #f8f9fa;
        padding: 12px;
        border-radius: 8px;
    }

    .detail-row {
        display: flex;
        justify-content: space-between;
        font-size: 0.9rem;
    }

    .detail-label {
        color: #6c757d;
    }

    .detail-value {
        font-weight: 500;
        color: #333;
    }

    @media (max-width: 768px) {
        .col-lg-6 {
            margin-bottom: 1.5rem;
        }

        .card-body {
            padding: 1rem;
        }

        .reserva-details {
            padding: 10px;
            font-size: 0.85rem;
        }
    }
</style>

<?php require_once __DIR__ . "/../layout/footer.php"; ?>
