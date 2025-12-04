<?php 
    // Si viene un mensaje de error, ya está disponible en $error
?>

<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Iniciar Sesión - Hotel Reservas</title>

    <!-- Bootstrap -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">

    <style>
        body {
            background: url("https://images.unsplash.com/photo-1551882547-ff40c63fe5fa?auto=format&fit=crop&w=1400&q=80") 
                        no-repeat center center fixed;
            background-size: cover;
            height: 100vh;
            display: flex;
            justify-content: center;
            align-items: center;
            backdrop-filter: blur(2px);
        }

        .login-box {
            width: 380px;
            background: rgba(255, 255, 255, 0.85);
            padding: 35px;
            border-radius: 12px;
            box-shadow: 0 0 25px rgba(0,0,0,0.25);
        }

        .login-title {
            font-weight: bold;
            font-size: 24px;
            text-align: center;
            margin-bottom: 15px;
            color: #333;
        }

        .login-sub {
            text-align: center;
            margin-bottom: 25px;
            font-size: 14px;
            color: #555;
        }

        .btn-primary {
            background-color: #004aad;
            border-color: #004aad;
        }

        .btn-primary:hover {
            background-color: #003B8A;
        }

        a {
            text-decoration: none;
        }
    </style>

</head>
<body>

    <div class="login-box">

        <div class="login-title">Bienvenido</div>
        <div class="login-sub">Accede a tu cuenta para continuar</div>

        <?php if (!empty($error)): ?>
            <div class="alert alert-danger text-center"><?= $error ?></div>
        <?php endif; ?>

        <form action="/?action=login/process" method="POST">

            <div class="mb-3">
                <label class="form-label">Correo Electrónico</label>
                <input type="email" name="email" class="form-control" placeholder="usuario@correo.com" required>
            </div>

            <div class="mb-3">
                <label class="form-label">Contraseña</label>
                <input type="password" name="password" class="form-control" placeholder="********" required>
            </div>

            <button class="btn btn-primary w-100 mt-2">Iniciar Sesión</button>

            <?php if (!empty($next)): ?>
                <input type="hidden" name="next" value="<?= htmlspecialchars($next) ?>">
            <?php endif; ?>

            <?php if (!empty($id)): ?>
                <input type="hidden" name="id" value="<?= htmlspecialchars($id) ?>">
            <?php endif; ?>

            <div class="mt-3 text-center">
                <a href="/?action=register" class="text-primary">Crear una cuenta</a>
            </div>
            <!-- Ojo con esto en despliegue de nube -->
            <div class="mt-2 text-center">
                <a href="/" class="text-secondary">Volver al inicio</a>
            </div>

        </form>

    </div>

</body>
</html>
