<?php include_once __DIR__ . "/../layout/header.php"; ?>

<div class="container text-center">

    <div class="alert alert-success mt-4 shadow">
        <h3 class="fw-bold"><i class="bi bi-check-circle-fill"></i> Reserva registrada</h3>
        <p class="mb-1">Tu reserva se realizó con éxito.</p>
        <p><strong>Del:</strong> <?= $inicio ?> <strong>al:</strong> <?= $fin ?></p>
        <p class="fs-4"><strong>Total a pagar: S/.<?= $total ?></strong></p>
    </div>

    <a href="/" class="btn btn-primary mt-3">
        <i class="bi bi-house-door"></i> Volver al inicio
    </a>

</div>

<?php include_once __DIR__ . "/../layout/footer.php"; ?>
