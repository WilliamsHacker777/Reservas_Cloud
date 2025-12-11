<?php
/**
 * Vista: reservar.php
 * Variables esperadas:
 * - $habitacion : array con datos de la habitación (id, numero, tipo, precio, descripcion, imagen)
 */

// Seguridad mínima: si no hay habitación, mostrar mensaje
if (empty($habitacion)) {
	echo "<div class=\"container mt-4\"><div class=\"alert alert-warning\">Habitación no encontrada.</div></div>";
	return;
}

$logueado = isset($_SESSION['user']);

// Cargar recomendaciones (3 habitaciones distintas)
require_once __DIR__ . "/../../app/services/habitacionService.php";
$serv = new HabitacionService();
$all = $serv->listarDisponibles();
$recommend = [];
foreach ($all as $a) {
	if ($a['id'] == $habitacion['id']) continue;
	$recommend[] = $a;
}
// tomar hasta 3 recomendaciones
$recommend = array_slice($recommend, 0, 3);

?>

<!DOCTYPE html>
<html lang="es">
<head>
	<meta charset="utf-8">
	<meta name="viewport" content="width=device-width, initial-scale=1">
	<title>Reservar - Habitación <?= htmlspecialchars($habitacion['numero']) ?></title>
	<link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
	<style>
		.hero {
			display: grid;
			grid-template-columns: 1fr 420px;
			gap: 30px;
			align-items: center;
			margin-top: 30px;
		}

		.hero-image {
			width: 100%;
			height: 520px;
			border-radius: 12px;
			overflow: hidden;
			box-shadow: 0 10px 30px rgba(0,0,0,0.15);
		}

		.hero-image img {
			width: 100%;
			height: 100%;
			object-fit: cover;
			display: block;
		}

		.hero-card {
			background: #ffffff;
			padding: 28px;
			border-radius: 12px;
			box-shadow: 0 8px 24px rgba(0,0,0,0.08);
		}

		.price {
			font-size: 28px;
			font-weight: 700;
			color: #0d6efd;
		}

		.btn-reservar {
			background: linear-gradient(90deg,#0066d6,#004aad);
			color: #fff;
			border: none;
			padding: 12px 18px;
			border-radius: 8px;
			font-weight: 600;
			width: 100%;
		}

		.recommendations .card {
			border: none;
			border-radius: 10px;
			overflow: hidden;
		}

		.recommendations img {
			width: 100%;
			height: 150px;
			object-fit: cover;
		}

		/* Responsive adjustments */

		@media (max-width: 991px) {
			.hero {
				grid-template-columns: 1fr 340px;
				gap: 20px;
			}
			.hero-image { height: 420px; }
		}

		@media (max-width: 767px) {
			.hero {
				grid-template-columns: 1fr;
			}
			.hero-image { height: 320px; }
			.hero-card { padding: 20px; }
			.recommendations img { height: 140px; }
		}

		@media (max-width: 480px) {
			.hero-image { height: 220px; border-radius: 10px; }
			.hero-card { padding: 16px; }
			.price { font-size: 20px; }
			.btn-reservar { padding: 14px 12px; font-size: 1rem; }
			.recommendations img { height: 110px; }
			.container { padding-left: 12px; padding-right: 12px; }
		}
	</style>
</head>
<body>

<div class="container">

	<div class="hero">

		<div class="hero-image">
			<img src="/img/<?= htmlspecialchars($habitacion['imagen'] ?? 'default.jpeg') ?>" alt="Habitación <?= htmlspecialchars($habitacion['numero']) ?>">
		</div>

		<div class="hero-card">
			<h2>Habitación <?= htmlspecialchars($habitacion['numero']) ?> <small class="text-muted">· <?= htmlspecialchars($habitacion['tipo']) ?></small></h2>
			<p class="mt-3 text-muted"><?= nl2br(htmlspecialchars($habitacion['descripcion'] ?? 'Sin descripción disponible.')) ?></p>

			<div class="d-flex align-items-center justify-content-between mt-4 mb-3">
				<div>
					<div class="price">S/ <?= number_format($habitacion['precio'], 2) ?></div>
					<div class="text-muted small">Precio por noche</div>
				</div>
			</div>

			<div class="mt-4">
				<!-- Botón que redirige al formulario de pago -->
				<a href="/?action=reservar/pago&id=<?= urlencode($habitacion['id']) ?>" class="btn-reservar btn d-block text-center">Ir al formulario de reserva</a>
			</div>

			<div class="mt-3 text-center text-muted small">
				Se mostrará un formulario para completar los datos y validar DNI.
			</div>
		</div>

	</div>

	<!-- Recomendaciones -->
	<?php if (!empty($recommend)): ?>
		<div class="recommendations mt-5">
			<h4 class="mb-3">También te pueden interesar</h4>

			<div class="row">
				<?php foreach ($recommend as $r): ?>
					<div class="col-md-4 mb-3">
						<div class="card shadow-sm">
							<img src="/img/<?= htmlspecialchars($r['imagen'] ?? 'default.jpeg') ?>" alt="Habitación <?= htmlspecialchars($r['numero']) ?>">
							<div class="card-body">
								<h6 class="card-title mb-1">Habitación <?= htmlspecialchars($r['numero']) ?></h6>
								<p class="card-text small text-muted mb-2"><?= htmlspecialchars($r['tipo']) ?></p>
								<div class="d-flex justify-content-between align-items-center">
									<div class="text-primary">S/ <?= number_format($r['precio'], 2) ?></div>
									<a href="/?action=reservar&id=<?= urlencode($r['id']) ?>" class="btn btn-outline-primary btn-sm">Ver</a>
								</div>
							</div>
						</div>
					</div>
				<?php endforeach; ?>
			</div>
		</div>
	<?php endif; ?>

</div>

</body>
</html>

