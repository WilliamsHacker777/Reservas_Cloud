<?php include_once __DIR__ . '/../layout/header.php'; ?>

<div class="container mt-5">
    <div class="row justify-content-center">
        <div class="col-md-8">
            <div class="alert alert-danger" role="alert">
                <h4 class="alert-heading"><i class="bi bi-exclamation-triangle"></i> Error</h4>
                <p><?php echo isset($error) ? htmlspecialchars($error) : 'Ha ocurrido un error inesperado.'; ?></p>
                <hr>
                <p class="mb-0">
                    <a href="/?action=admin" class="btn btn-sm btn-danger">
                        <i class="bi bi-arrow-left"></i> Volver al Panel Admin
                    </a>
                </p>
            </div>
        </div>
    </div>
</div>

<?php include_once __DIR__ . '/../layout/footer.php'; ?>
