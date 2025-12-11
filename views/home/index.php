<!-- Hero Section -->
<div class="hero-section" style="background: url('img/fondoindex.jpg') center/cover no-repeat; color: white; padding: 80px 0; text-align: center; margin-bottom: 60px; text-shadow: 2px 2px 4px rgba(0,0,0,0.5);">
    <div class="container">
        <h1 class="display-4 fw-bold mb-3" style="font-size: 3rem; letter-spacing: 1px;">Bienvenido a Nuestro Hotel</h1>
        <p class="lead fs-5" style="opacity: 0.95; font-weight: 300;">Descubre nuestras habitaciones exclusivas y disfruta de una experiencia inolvidable</p>
    </div>
</div>

<!-- Carrusel de Habitaciones Destacadas -->
<?php 
    $habitaciones_disponibles = array_filter($habitaciones, function($h) { return isset($h['estado']) && $h['estado'] == '1'; });
    if (!empty($habitaciones_disponibles)) :
?>
<div class="container mb-5">
    <div class="mb-4">
        <h2 class="fw-bold" style="font-size: 2.2rem; color: #333; border-bottom: 3px solid #667eea; padding-bottom: 15px; display: inline-block;">✨ Habitaciones Destacadas</h2>
    </div>

    <div id="carouselHabitaciones" class="carousel slide shadow-lg" data-bs-ride="carousel" style="border-radius: 15px; overflow: hidden;">
        <div class="carousel-inner">
            <?php 
                $contador = 0;
                foreach ($habitaciones_disponibles as $h): 
                    $activa = ($contador === 0) ? 'active' : '';
            ?>
                <div class="carousel-item <?= $activa ?>">
                    <div style="display: grid; grid-template-columns: 1fr 1fr; gap: 0; align-items: stretch; min-height: 400px;">
                        <img src="img/<?= htmlspecialchars($h['imagen'] ?? 'default.jpeg'); ?>" 
                             alt="Habitación <?= htmlspecialchars($h['numero']); ?>"
                             style="width: 100%; height: 100%; object-fit: cover;">
                        <div style="background: white; padding: 40px; display: flex; flex-direction: column; justify-content: center;">
                            <h3 class="mb-2" style="color: #667eea; font-size: 1.8rem; font-weight: bold;">Habitación <?= htmlspecialchars($h['numero']) ?></h3>
                            <p style="color: #666; font-size: 0.95rem; margin-bottom: 10px;"><strong>Tipo:</strong> <span style="color: #333;"><?= htmlspecialchars($h['tipo']) ?></span></p>
                            <p style="color: #666; margin-bottom: 20px; line-height: 1.6;"><?= htmlspecialchars($h['descripcion'] ?? 'Habitación confortable y acogedora.') ?></p>
                            <p style="font-size: 1.5rem; color: #667eea; font-weight: bold; margin-bottom: 25px;">S/ <?= number_format($h['precio'], 2) ?> <span style="font-size: 0.9rem; color: #999;">(por noche)</span></p>
                            
                            <?php if ($logueado): ?>
                                <a href="/?action=reservar&id=<?= $h['id']; ?>" class="btn btn-primary btn-lg" style="background: linear-gradient(90deg,#667eea,#764ba2); border: none; padding: 12px 30px; border-radius: 8px; font-weight: 600;">
                                    Reservar ahora
                                </a>
                            <?php else: ?>
                                <a href="/?action=login&next=reservar&id=<?= $h['id']; ?>" class="btn btn-outline-primary btn-lg" style="padding: 12px 30px; border-radius: 8px; font-weight: 600; border-width: 2px;">
                                    Iniciar sesión para reservar
                                </a>
                            <?php endif; ?>
                        </div>
                    </div>
                </div>
            <?php 
                $contador++;
                endforeach; 
            ?>
        </div>

        <?php if (count($habitaciones_disponibles) > 1): ?>
            <button class="carousel-control-prev" type="button" data-bs-target="#carouselHabitaciones" data-bs-slide="prev">
                <span class="carousel-control-prev-icon" aria-hidden="true"></span>
            </button>
            <button class="carousel-control-next" type="button" data-bs-target="#carouselHabitaciones" data-bs-slide="next">
                <span class="carousel-control-next-icon" aria-hidden="true"></span>
            </button>
        <?php endif; ?>
    </div>
</div>
<?php endif; ?>

<!-- Todas las Habitaciones Disponibles -->
<div class="container" style="margin-bottom: 60px;">
    <div class="mb-4">
        <h2 class="fw-bold" style="font-size: 2.2rem; color: #333; border-bottom: 3px solid #667eea; padding-bottom: 15px; display: inline-block;">🏨 Todas Nuestras Habitaciones</h2>
    </div>

    <div class="row g-4">
        <?php 
            $hay_disponibles = false;
            foreach ($habitaciones as $h) : 
                if (isset($h['estado']) && $h['estado'] == '1'): 
                    $hay_disponibles = true;
        ?>
            <div class="col-md-4">
                <div class="card h-100 shadow-sm" style="border: none; border-radius: 12px; overflow: hidden; transition: transform 0.3s, box-shadow 0.3s;" 
                     onmouseover="this.style.transform='translateY(-8px)'; this.style.boxShadow='0 12px 24px rgba(0,0,0,0.15)';"
                     onmouseout="this.style.transform='translateY(0)'; this.style.boxShadow='0 2px 8px rgba(0,0,0,0.1)';">
                    
                    <img src="img/<?= htmlspecialchars($h['imagen'] ?? 'default.jpeg'); ?>"
                         class="card-img-top" 
                         alt="Habitación <?= htmlspecialchars($h['numero']); ?>"
                         style="width: 100%; height: 220px; object-fit: cover;">
                    
                    <div class="card-body d-flex flex-column" style="background: #fafafa;">
                        <div style="background: linear-gradient(135deg, #667eea, #764ba2); color: white; padding: 8px 12px; border-radius: 6px; display: inline-block; width: fit-content; margin-bottom: 15px; font-size: 0.85rem; font-weight: 600;">
                            Disponible
                        </div>
                        
                        <h5 class="card-title fw-bold" style="color: #333; font-size: 1.3rem;">Habitación <?= htmlspecialchars($h['numero']); ?></h5>
                        
                        <p class="card-text" style="color: #666; font-size: 0.95rem; flex-grow: 1;">
                            <strong style="color: #333;">Tipo:</strong> <?= htmlspecialchars($h['tipo']); ?><br>
                            <strong style="color: #333;">Descripción:</strong> <?= htmlspecialchars(substr($h['descripcion'] ?? '', 0, 50)) . '...'; ?>
                        </p>

                        <div style="margin-top: 15px; padding-top: 15px; border-top: 1px solid #ddd;">
                            <p style="font-size: 1.4rem; color: #667eea; font-weight: bold; margin: 0;">S/ <?= number_format($h['precio'], 2); ?></p>
                            <p style="color: #999; font-size: 0.85rem; margin-top: 5px;">por noche</p>
                        </div>

                        <div style="margin-top: 15px;">
                            <?php if ($logueado): ?>
                                <a href="/?action=reservar&id=<?= $h['id']; ?>"
                                   class="btn btn-primary w-100" style="background: linear-gradient(90deg,#667eea,#764ba2); border: none; font-weight: 600; border-radius: 8px;">
                                    Reservar
                                </a>
                            <?php else: ?>
                                <a href="/?action=login&next=reservar&id=<?= $h['id']; ?>"
                                   class="btn btn-outline-primary w-100" style="font-weight: 600; border-radius: 8px; border-width: 2px;">
                                    Iniciar sesión para reservar
                                </a>
                            <?php endif; ?>
                        </div>
                    </div>
                </div>
            </div>
        <?php 
                endif;
            endforeach; 
        ?>

        <?php if (!$hay_disponibles) : ?>
            <div class="col-12">
                <div class="alert alert-warning" style="border-radius: 12px; padding: 30px; text-align: center; background: linear-gradient(135deg, #fff3cd, #fffbea); border: 2px solid #ffc107;">
                    <h5 style="color: #856404; margin-bottom: 10px;">⚠️ Sin disponibilidad</h5>
                    <p style="color: #856404; margin: 0;">Lamentablemente, no hay habitaciones disponibles en este momento. Por favor, intenta más tarde.</p>
                </div>
            </div>
        <?php endif; ?>
    </div>
</div>

<style>
    .hero-section {
        box-shadow: 0 4px 15px rgba(0,0,0,0.1);
    }

    .carousel-item {
        min-height: 400px;
    }

    .carousel-control-prev, .carousel-control-next {
        opacity: 0.7;
        transition: opacity 0.3s;
    }

    .carousel-control-prev:hover, .carousel-control-next:hover {
        opacity: 1;
    }

    .carousel-control-prev-icon,
    .carousel-control-next-icon {
        background-image: url("data:image/svg+xml,%3csvg xmlns='http://www.w3.org/2000/svg' viewBox='0 0 16 16'%3e%3cpath d='M11.354 1.646a.5.5 0 0 1 0 .708L5.707 8l5.647 5.646a.5.5 0 0 1-.708.708l-6-6a.5.5 0 0 1 0-.708l6-6a.5.5 0 0 1 .708 0z' fill='%23000000'/%3e%3c/svg%3e") !important;
    }

    .carousel-control-next-icon {
        background-image: url("data:image/svg+xml,%3csvg xmlns='http://www.w3.org/2000/svg' viewBox='0 0 16 16'%3e%3cpath d='M4.646 1.646a.5.5 0 0 1 .708 0l6 6a.5.5 0 0 1 0 .708l-6 6a.5.5 0 0 1-.708-.708L10.293 8 4.646 2.354a.5.5 0 0 1 0-.708z' fill='%23000000'/%3e%3c/svg%3e") !important;
    }

    @media (max-width: 768px) {
        .carousel-item > div {
            grid-template-columns: 1fr !important;
            min-height: auto !important;
        }

        .carousel-item > div > img {
            min-height: 250px;
        }

        .carousel-item > div > div {
            padding: 25px !important;
        }

        .hero-section h1 {
            font-size: 2rem !important;
        }
    }
</style>