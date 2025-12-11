<?php 
if (session_status() === PHP_SESSION_NONE) {
    session_start();
}

$usuario = $_SESSION["user"] ?? null;
?>

<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Sistema de Reservas</title>

    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.css" rel="stylesheet">
</head>

<body class="d-flex flex-column min-vh-100">

<nav class="navbar navbar-expand-lg navbar-dark bg-dark shadow-sm">
    <div class="container">
        <a class="navbar-brand" href="/">
            <i class="bi bi-building"></i> Hotel Ica Reservas
        </a>

        <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#menu">
            <span class="navbar-toggler-icon"></span>
        </button>

        <div class="collapse navbar-collapse" id="menu">
            <ul class="navbar-nav ms-auto">

                <li class="nav-item">
                    <a class="nav-link" href="/?action=home">
                        <i class="bi bi-house"></i> Inicio
                    </a>
                </li>


                <?php if ($usuario): ?>

                    <li class="nav-item dropdown">
                        <a class="nav-link dropdown-toggle" href="#" data-bs-toggle="dropdown">
                            <i class="bi bi-person-circle"></i> Mi Cuenta
                        </a>
                        <ul class="dropdown-menu dropdown-menu-end">
                            <?php if ($usuario['rol'] === 'admin'): ?>
                                <li><a class="dropdown-item" href="/?action=admin"><i class="bi bi-speedometer2"></i> Panel Admin</a></li>
                                <li><hr class="dropdown-divider"></li>
                            <?php endif; ?>
                            <li><a class="dropdown-item" href="/?action=perfil"><i class="bi bi-person-badge"></i> Mi perfil</a></li>
                            <li><a class="dropdown-item" href="/?action=misreservas"><i class="bi bi-bookmark"></i> Mis reservas</a></li>
                            <li><hr class="dropdown-divider"></li>
                            <li><a class="dropdown-item" href="/?action=logout"><i class="bi bi-box-arrow-right"></i> Cerrar sesión</a></li>
                        </ul>
                    </li>

                <?php else: ?>

                    <li class="nav-item">
                        <a class="nav-link" href="/?action=login">
                            <i class="bi bi-person-circle"></i> Ingresar
                        </a>
                    </li>

                    <li class="nav-item">
                        <a class="nav-link" href="/?action=register">
                            <i class="bi bi-person-plus"></i> Crear Cuenta
                        </a>
                    </li>

                <?php endif; ?>

            </ul>
        </div>
    </div>
</nav>

<div class="container mt-4 flex-grow-1">