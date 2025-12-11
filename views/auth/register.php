<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Crear Cuenta</title>

    <!-- Bootstrap -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">

    <style>
        :root { --card-bg: rgba(255,255,255,0.94); --accent: #ffb199; }

        body {
            background: url('https://images.unsplash.com/photo-1501785888041-af3ef285b470?auto=format&fit=crop&w=1400&q=80') no-repeat center center/cover;
            min-height: 100vh;
            margin: 0;
            display: flex;
            justify-content: center;
            align-items: center;
            font-family: 'Poppins', sans-serif;
            padding: 20px; /* allow breathing room on very small screens */
        }

        .register-container {
            width: 100%;
            max-width: 420px;
            background: var(--card-bg);
            padding: 28px;
            border-radius: 12px;
            backdrop-filter: blur(6px);
            box-shadow: 0 6px 30px rgba(0,0,0,0.18);
            animation: fadeIn .45s ease;
            box-sizing: border-box;
        }

        h3 {
            font-weight: 700;
            text-align: center;
            margin-bottom: 18px;
            color: #222;
            font-size: 1.25rem;
        }

        .btn-custom {
            background-color: var(--accent);
            border: none;
            font-weight: 600;
            padding: 0.85rem 1rem;
            font-size: 1rem;
        }

        .btn-custom:hover { background-color: #ff9777; }

        .small-link a { text-decoration: none; font-size: 0.95rem; color: #444; }
        .small-link a:hover { color: #000; }

        input.form-control { padding: 0.6rem 0.75rem; font-size: 0.95rem; }

        @media (max-width: 480px) {
            .register-container { padding: 18px; border-radius: 10px; }
            h3 { font-size: 1.1rem; }
            .btn-custom { padding: 0.9rem; font-size: 1rem; }
        }

        @keyframes fadeIn { from { opacity: 0; transform: translateY(-6px);} to { opacity:1; transform: none;} }
    </style>
</head>

<body>

<div class="register-container">

    <h3>Crear Cuenta</h3>

    <?php if (!empty($error)): ?>
        <div class="alert alert-danger text-center"><?= $error ?></div>
    <?php endif; ?>

    <form action="/?action=register/process" method="POST">

        <div class="mb-3">
            <label class="form-label">Correo Electrónico</label>
            <input type="email" name="email" class="form-control" required>
        </div>

        <div class="mb-3">
            <label class="form-label">Contraseña</label>
            <input type="password" name="password" class="form-control" required>
        </div>

        <div class="mb-3">
            <label class="form-label">Repetir Contraseña</label>
            <input type="password" name="password2" class="form-control" required>
        </div>

        <button class="btn btn-custom w-100">Registrar</button>

        <div class="text-center mt-3 small-link">
            <a href="/?action=login">Ya tengo cuenta</a>
        </div>

        <div class="text-center small-link mt-2">
            <a href="/">← Volver al inicio</a>
        </div>

    </form>

</div>

</body>
</html>
