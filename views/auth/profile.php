<?php require_once __DIR__ . '/../layout/header.php'; ?>

<div class="container py-5">
    <div class="row justify-content-center">
        <div class="col-md-8">
            <div class="card shadow-sm">
                <div class="card-body">
                    <h4 class="card-title">Mi Perfil</h4>
                    <p class="text-muted">Información básica de la cuenta</p>

                    <dl class="row mt-4">
                        <dt class="col-sm-3">Email</dt>
                        <dd class="col-sm-9"><?= htmlspecialchars($user['email'] ?? 'Sin email') ?></dd>

                        <dt class="col-sm-3">Rol</dt>
                        <dd class="col-sm-9"><?= htmlspecialchars($user['rol'] ?? 'Usuario') ?></dd>

                        <dt class="col-sm-3">ID</dt>
                        <dd class="col-sm-9"><?= htmlspecialchars($user['id'] ?? '-') ?></dd>
                    </dl>

                    <div class="mt-4">
                        <a href="/?action=misreservas" class="btn btn-outline-primary">Ver mis reservas</a>
                        <a href="/?action=logout" class="btn btn-outline-secondary ms-2">Cerrar sesión</a>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<?php require_once __DIR__ . '/../layout/footer.php'; ?>
