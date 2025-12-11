<?php require_once __DIR__ . '/../layout/header.php'; ?>

<div class="container py-5">
    <div class="row justify-content-center">
        <div class="col-lg-6 col-md-8">
            
            <div class="card border-0 shadow-lg rounded-4 overflow-hidden">
                
                <div class="card-header bg-dark text-white text-center py-4 position-relative">
                    <h4 class="mb-0 fw-bold">Mi Perfil</h4>
                    <p class="mb-0 opacity-75">Administra tu información personal</p>
                </div>

                <div class="card-body p-4">
                    
                    <div class="text-center mt-n5 mb-4 position-relative">
                        <div class="d-inline-flex align-items-center justify-content-center bg-white rounded-circle shadow-sm p-2" style="width: 100px; height: 100px; margin-top: -50px;">
                            <i class="bi bi-person-circle text-secondary" style="font-size: 4rem;"></i>
                        </div>
                        <h5 class="mt-3 fw-bold"><?= htmlspecialchars($user['email'] ?? 'Usuario') ?></h5>
                        
                        <?php if (($user['rol'] ?? '') === 'admin'): ?>
                            <span class="badge rounded-pill bg-danger">
                                <i class="bi bi-shield-lock-fill"></i> Administrador
                            </span>
                        <?php else: ?>
                            <span class="badge rounded-pill bg-success">
                                <i class="bi bi-person-check-fill"></i> Cliente
                            </span>
                        <?php endif; ?>
                    </div>

                    <div class="list-group list-group-flush rounded-3 border mb-4">
                        
                        <div class="list-group-item d-flex justify-content-between align-items-center p-3">
                            <div class="d-flex align-items-center">
                                <div class="bg-light rounded-circle p-2 me-3 text-primary">
                                    <i class="bi bi-envelope"></i>
                                </div>
                                <div>
                                    <small class="text-muted d-block">Correo Electrónico</small>
                                    <span class="fw-medium"><?= htmlspecialchars($user['email'] ?? 'Sin email') ?></span>
                                </div>
                            </div>
                        </div>

                        <div class="list-group-item d-flex justify-content-between align-items-center p-3">
                            <div class="d-flex align-items-center">
                                <div class="bg-light rounded-circle p-2 me-3 text-primary">
                                    <i class="bi bi-hash"></i>
                                </div>
                                <div>
                                    <small class="text-muted d-block">Identificador (ID)</small>
                                    <span class="font-monospace text-dark"><?= htmlspecialchars($user['id'] ?? '-') ?></span>
                                </div>
                            </div>
                        </div>

                        <div class="list-group-item d-flex justify-content-between align-items-center p-3">
                            <div class="d-flex align-items-center">
                                <div class="bg-light rounded-circle p-2 me-3 text-primary">
                                    <i class="bi bi-activity"></i>
                                </div>
                                <div>
                                    <small class="text-muted d-block">Estado de cuenta</small>
                                    <span class="text-success fw-bold">Activo</span>
                                </div>
                            </div>
                            <i class="bi bi-check-circle-fill text-success"></i>
                        </div>

                    </div>

                    <div class="d-grid gap-2">
                        <a href="/?action=misreservas" class="btn btn-primary py-2 rounded-3 shadow-sm">
                            <i class="bi bi-calendar-check me-2"></i> Ver mis reservas
                        </a>
                    </div>

                </div>
                
                <div class="card-footer bg-light text-center py-3 border-0">
                    
                <small class="text-muted">¿Necesitas ayuda? <a href="https://wa.link/kkgchc" class="text-decoration-none">Contactar soporte</a></small>
                </div>

            </div>
        </div>
    </div>
</div>

<?php require_once __DIR__ . '/../layout/footer.php'; ?>