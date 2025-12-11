<?php
// Vista: views/reservas/pago.php
// Variables esperadas: $habitacion
if (empty($habitacion)) {
    echo "<div class='container mt-4'><div class='alert alert-warning'>Habitación no encontrada.</div></div>";
    return;
}

// 1. GENERAMOS LA FECHA ACTUAL (YYYY-MM-DD)
$fechaHoy = date('Y-m-d');

// Obtener email del usuario si está logueado
$userEmail = '';
if (isset($_SESSION['user'])) {
  $u = $_SESSION['user'];
  if (is_array($u) && isset($u['email'])) {
    $userEmail = $u['email'];
  } elseif (is_object($u)) {
    $className = get_class($u);
    if ($className === '__PHP_Incomplete_Class') {
      $arr = (array)$u; 
      foreach ($arr as $k => $v) {
        if (stripos($k, 'email') !== false) {
          $userEmail = $v;
          break;
        }
      }
    } else {
      if (isset($u->email)) $userEmail = $u->email;
    }
  }
}
?>
<!doctype html>
<html lang="es">
<head>
<meta charset="utf-8">
<meta name="viewport" content="width=device-width,initial-scale=1">
<title>Pago — Habitación <?= htmlspecialchars($habitacion['numero']) ?></title>
<link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
<style>
.payment-panel { margin-top: 30px; }
.summary-card { border-radius:12px; box-shadow:0 8px 24px rgba(0,0,0,0.06); }
.qr-box { background:#fff; padding:16px; border-radius:8px; text-align:center; box-shadow:0 6px 18px rgba(0,0,0,0.04); }
.btn-primary-gradient { background: linear-gradient(90deg,#0066d6,#004aad); border: none; }
</style>
</head>
<body>
<div class="container payment-panel">
  <div class="row">
    <div class="col-lg-7">
      <div class="card p-4 summary-card">
        <h4 class="mb-3">Completa tus datos y selecciona método de pago</h4>

        <form id="paymentForm" action="/?action=reservar/guardarPago" method="POST" enctype="multipart/form-data">
          <input type="hidden" name="habitacion_id" value="<?= htmlspecialchars($habitacion['id']) ?>">
          <div class="row">
            <div class="col-md-6 mb-3">
              <label class="form-label">DNI</label>
              <input id="dniInput" name="dni" class="form-control" required pattern="\d{8}" maxlength="8" placeholder="Ej: 01234567">
            </div>

            <div class="col-md-6 mb-3">
              <label class="form-label">Teléfono</label>
              <input name="telefono" id="telefonoInput" class="form-control" 
                     required 
                     maxlength="9" 
                     pattern="[0-9]{9}"
                     placeholder="Ej: 999999999"
                     inputmode="numeric">
            </div>

            <div class="col-md-12 mb-3">
              <label class="form-label">Nombres</label>
              <input type="text" id="nombres" name="nombres" class="form-control" readonly>
            </div>

            <div class="col-md-12 mb-3">
              <label class="form-label">Apellidos</label>
              <input type="text" id="apellidos" name="apellidos" class="form-control" readonly>
            </div>

            <div class="col-md-12 mb-3">
              <label class="form-label">Correo</label>
              <input type="email" name="email" class="form-control" value="<?= htmlspecialchars($userEmail) ?>">
            </div>

            <div class="col-md-6 mb-3">
              <label class="form-label">Fecha inicio</label>
              <input type="date" id="fechaInicio" name="fecha_inicio" class="form-control" required min="<?= $fechaHoy ?>">
            </div>
            <div class="col-md-6 mb-3">
              <label class="form-label">Fecha fin</label>
              <input type="date" id="fechaFin" name="fecha_fin" class="form-control" required min="<?= $fechaHoy ?>">
            </div>
          </div>

          <hr>

          <h5 class="mb-2">Pago</h5>
          <div class="mb-3">
            <div class="form-check form-check-inline">
              <input class="form-check-input" type="radio" name="payment_method" id="pm_card" value="card" checked>
              <label class="form-check-label" for="pm_card">Tarjeta (simulado)</label>
            </div>
            <div class="form-check form-check-inline">
              <input class="form-check-input" type="radio" name="payment_method" id="pm_yape" value="yape">
              <label class="form-check-label" for="pm_yape">Yape</label>
            </div>
          </div>

          <div id="cardFields">
            <div class="row">
              <div class="col-md-12 mb-3">
                <label class="form-label">Nombre en la tarjeta</label>
                <input name="card_name" class="form-control">
              </div>
              <div class="col-md-8 mb-3">
                <label class="form-label">Número de tarjeta</label>
                <input name="card_number" class="form-control" placeholder="4111 1111 1111 1111">
              </div>
              <div class="col-md-2 mb-3">
                <label class="form-label">MM/AA</label>
                <input name="card_exp" class="form-control" placeholder="08/25">
              </div>
              <div class="col-md-2 mb-3">
                <label class="form-label">CVV</label>
                <input name="card_cvv" class="form-control" placeholder="123">
              </div>
            </div>
          </div>

          <div id="yapeFields" style="display:none;">
            <div class="mb-3">
              <p>Escanea el QR con Yape para realizar el pago. Luego sube la captura.</p>
              <div class="qr-box mb-3">
                <?php
                  $yapePhone = '959 467 673';
                  $amount = number_format($habitacion['precio'], 2, '.', '');
                  $qrData = "YAPE|{$yapePhone}|S/{$amount}";
                  $qrUrl = "https://chart.googleapis.com/chart?cht=qr&chs=220x220&chl=" . urlencode($qrData);
                ?>
                <img src="img/yape.jpeg" class="img-fluid" style="max-width:220px;height:auto;" alt="QR Yape">
                <div class="small text-muted mt-2">Número Yape: <?= htmlspecialchars($yapePhone) ?></div>
                <div class="small text-muted">Monto: S/ <?= number_format($habitacion['precio'], 2) ?></div>
              </div>

              <label class="form-label">Subir captura de pago (jpg/png)</label>
              <input type="file" name="payment_receipt" accept=".jpg,.jpeg,.png" class="form-control">
            </div>
          </div>

          <div class="mt-3">
            <button type="submit" class="btn btn-primary btn-primary-gradient">Confirmar y Guardar Reserva</button>
            <a href="/?action=reservar&id=<?= urlencode($habitacion['id']) ?>" class="btn btn-secondary ms-2">Volver</a>
          </div>
        </form>
      </div>
    </div>

    <div class="col-lg-5">
      <div class="card p-3 summary-card">
        <div class="d-flex gap-3 align-items-center">
          <img src="/img/<?= htmlspecialchars($habitacion['imagen'] ?? 'default.jpeg') ?>" style="width:120px;height:90px;object-fit:cover;border-radius:8px;">
          <div>
            <h5 class="mb-0">Habitación <?= htmlspecialchars($habitacion['numero']) ?></h5>
            <div class="text-muted small"><?= htmlspecialchars($habitacion['tipo']) ?></div>
            <div class="mt-2"><strong>S/ <?= number_format($habitacion['precio'],2) ?></strong> <span class="small text-muted">por noche</span></div>
          </div>
        </div>
        <hr>
        <div id="summaryPrices">
          <div class="d-flex justify-content-between"><div>Subtotal</div><div id="subtotal">S/ <?= number_format($habitacion['precio'],2) ?></div></div>
          <div class="d-flex justify-content-between"><div>Impuestos</div><div id="tax">S/ 0.00</div></div>
          <div class="d-flex justify-content-between fw-bold"><div>Total</div><div id="total">S/ <?= number_format($habitacion['precio'],2) ?></div></div>
        </div>
      </div>
    </div>
  </div>
</div>

<script>
const pmCard = document.getElementById('pm_card');
const pmYape = document.getElementById('pm_yape');
const cardFields = document.getElementById('cardFields');
const yapeFields = document.getElementById('yapeFields');

// DNI -> consulta RENIEC
const dniInputElem = document.getElementById('dniInput');
const nombresElem = document.getElementById('nombres');
const apellidosElem = document.getElementById('apellidos');

// Nuevo selector para el teléfono
const telefonoInputElem = document.getElementById('telefonoInput');

// 1. VALIDACIÓN DNI
dniInputElem.addEventListener('input', async (e) => {
  const val = (e.target.value || '').replace(/\D/g, '');
  if (val.length === 8) {
    try {
      const resp = await fetch('/consulta_dni.php', {
        method: 'POST',
        headers: { 'Content-Type': 'application/x-www-form-urlencoded' },
        body: `dni=${encodeURIComponent(val)}`
      });

      const text = await resp.text();
      const data = JSON.parse(text);
      if (data.nombres) {
        nombresElem.value = data.nombres;
        const ap = [(data.apellidoPaterno||''), (data.apellidoMaterno||'')].filter(Boolean).join(' ');
        apellidosElem.value = ap;
      } else if (data.error) {
        alert('No se encontraron datos: ' + data.error);
      } else {
        alert('No se encontraron datos para este DNI.');
      }
    } catch (err) {
      console.error(err);
      alert('Error al consultar RENIEC: ' + err.message);
    }
  } else {
    nombresElem.value = '';
    apellidosElem.value = '';
  }
});

// 2. VALIDACIÓN TELÉFONO EN TIEMPO REAL
// Esto borra cualquier caracter que no sea número mientras el usuario escribe
telefonoInputElem.addEventListener('input', function(e) {
    // Reemplaza todo lo que no sea dígito con vacío
    this.value = this.value.replace(/[^0-9]/g, '');
});

// 3. VALIDACIÓN FECHAS (Extra: Fecha Fin no puede ser menor a Fecha Inicio)
const fechaInicioElem = document.getElementById('fechaInicio');
const fechaFinElem = document.getElementById('fechaFin');

fechaInicioElem.addEventListener('change', function() {
    // Cuando cambia la fecha de inicio, actualizamos el mínimo de la fecha fin
    fechaFinElem.min = this.value;
});


function togglePayment() {
  if (pmYape.checked) {
    cardFields.style.display = 'none';
    yapeFields.style.display = 'block';
  } else {
    cardFields.style.display = 'block';
    yapeFields.style.display = 'none';
  }
}
pmCard.addEventListener('change', togglePayment);
pmYape.addEventListener('change', togglePayment);
togglePayment();
</script>
</body>
</html>