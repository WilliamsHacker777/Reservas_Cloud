<?php

require_once __DIR__ . "/../core/Controller.php";
require_once __DIR__ . "/../services/ReservaService.php";
require_once __DIR__ . "/../services/ReniecService.php";
require_once __DIR__ . "/../DAO/PersonaDAO.php";
require_once __DIR__ . "/../core/Database.php";
require_once __DIR__ . "/../services/ReniecService.php";


class ReservaController extends Controller {

    // Mostrar formulario de reserva
    public function reservar()
    {
        if (!isset($_SESSION['user'])) {
            $habitacionId = $_GET['id'] ?? null;
            $location = "/?action=login&next=reservar";
            if ($habitacionId) {
                $location .= "&id=" . urlencode($habitacionId);
            }
            header("Location: " . $location);
            exit;
        }

    $habitacionId = $_GET['id'] ?? null;

    if (!$habitacionId) {
        echo "Habitación no encontrada";
        return;
    }

    $habitacionService = new HabitacionService();
    $habitacion = $habitacionService->buscarPorId($habitacionId);

    $this->render("reservas/reservar", [
        "habitacion" => $habitacion
    ]);
}


    // Procesar reserva (POST)
    public function guardar()
    {
        if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
            echo "Método no permitido";
            return;
        }

        $dni = $_POST['dni'];
        $inicio = $_POST['inicio'];
        $fin = $_POST['fin'];
        $habitacionId = $_POST['habitacion_id'];

        $conn = Database::getInstance();

        // ----- VALIDAR DNI -----
        $reniecService = new ReniecService();
        $dniInfo = $reniecService->validarDni($dni);

        if (!$dniInfo) {
            $this->render("reservas/error", [
                "mensaje" => "DNI inválido o no encontrado."
            ]);
            return;
        }

        // ----- Registrar persona si no existe -----
        $personaDAO = new PersonaDAO($conn);
        $persona = $personaDAO->findByDni($dni);

        if (!$persona) {
            $p = new Persona();
            $p->dni = $dni;
            $p->nombres = $dniInfo["nombres"];
            $p->apellidos = $dniInfo["apellidos"];
            $p->telefono = null;
            // Si el usuario está logueado, usar su email para vincular la persona
            $p->email = isset($_SESSION['user']['email']) ? $_SESSION['user']['email'] : null;

            $personaDAO->create($p);
            $persona = $personaDAO->findByDni($dni);
        }

        // ----- LÓGICA DE RESERVA -----
        $reservaService = new ReservaService($conn);
        $resultado = $reservaService->crearReserva($persona->id, $habitacionId, $inicio, $fin);

        if (!$resultado["success"]) {
            $this->render("reservas/error", [
                "mensaje" => $resultado["message"]
            ]);
            return;
        }

        // ----- Mostrar confirmación -----
        $this->render("reservas/confirmar", [
            "total" => $resultado["total"],
            "inicio" => $inicio,
            "fin" => $fin
        ]);
    }

    // Mostrar formulario de pago/reserva (pago.php)
    public function pagoView()
    {
        $habitacionId = $_GET['id'] ?? null;

        if (!$habitacionId) {
            echo "Habitación no encontrada";
            return;
        }

        $conn = Database::getInstance();
        require_once __DIR__ . '/../DAO/habitacionDAO.php';
        $habitacionDAO = new HabitacionDAO($conn);
        $habitacion = $habitacionDAO->findById($habitacionId);

        $this->render('reservas/pago', ['habitacion' => $habitacion]);
    }

    // Guardar pago y crear reserva (POST)
    public function guardarPago()
    {
        if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
            echo "Método no permitido";
            return;
        }

        $habitacionId = $_POST['habitacion_id'] ?? null;
        $dni = trim($_POST['dni'] ?? '');
        $telefono = trim($_POST['telefono'] ?? '');
        $email = trim($_POST['email'] ?? '');
        $inicio = $_POST['fecha_inicio'] ?? null;
        $fin = $_POST['fecha_fin'] ?? null;
        $payment_method = $_POST['payment_method'] ?? 'card';

        if (!$habitacionId || !$dni || !$inicio || !$fin) {
            $this->render('reservas/error', ['mensaje' => 'Faltan datos obligatorios.']);
            return;
        }

        $conn = Database::getInstance();

        require_once __DIR__ . '/../DAO/PersonaDAO.php';
        require_once __DIR__ . '/../DAO/ReservaDAO.php';
        require_once __DIR__ . '/../DAO/habitacionDAO.php';

        // Validar rango de reserva
        $reservaDAO = new ReservaDAO($conn);
        if ($reservaDAO->existeReservaEnRango($habitacionId, $inicio, $fin)) {
            $this->render('reservas/error', ['mensaje' => 'La habitación ya está reservada en esas fechas.']);
            return;
        }

        // Registrar persona si no existe
        $personaDAO = new PersonaDAO($conn);
        $persona = $personaDAO->findByDni($dni);
        if (!$persona) {
            $p = new Persona();
            $p->dni = $dni;
            $p->nombres = $_POST['nombres'] ?? '';
            $p->apellidos = $_POST['apellidos'] ?? '';
            $p->telefono = $telefono;
            // Preferir el email proporcionado en el formulario; si está vacío y hay usuario en sesión, usar ese email
            $p->email = !empty($email) ? $email : (isset($_SESSION['user']['email']) ? $_SESSION['user']['email'] : null);
            $personaDAO->create($p);
            $persona = $personaDAO->findByDni($dni);
        }

        // Calcular total (precio * noches)
        $habitacionDAO = new HabitacionDAO($conn);
        $h = $habitacionDAO->findById($habitacionId);
        $precio = floatval($h['precio'] ?? 0);
        $d1 = new DateTime($inicio);
        $d2 = new DateTime($fin);
        $diff = $d2->diff($d1)->days;
        if ($diff <= 0) $diff = 1;
        $total = $precio * $diff;

        // Manejar subida de comprobante (para Yape)
        $receiptPath = null;
        if (!empty($_FILES['payment_receipt']) && $_FILES['payment_receipt']['error'] === UPLOAD_ERR_OK) {
            $tmp = $_FILES['payment_receipt']['tmp_name'];
            $name = time() . '_' . preg_replace('/[^a-zA-Z0-9_\.-]/','_', basename($_FILES['payment_receipt']['name']));
            $uploadDir = __DIR__ . '/../../public/uploads/payments/';
            if (!is_dir($uploadDir)) mkdir($uploadDir, 0755, true);
            $target = $uploadDir . $name;
            $allowed = ['image/jpeg','image/png','image/jpg'];
            $mime = mime_content_type($tmp);
            if (!in_array($mime, $allowed)) {
                $this->render('reservas/error', ['mensaje' => 'Formato de imagen no permitido.']);
                return;
            }
            if (move_uploaded_file($tmp, $target)) {
                $receiptPath = 'uploads/payments/' . $name;
            }
        }

        // Crear reserva
        $reserva = new Reserva();
        $reserva->persona_id = $persona->id;
        $reserva->habitacion_id = $habitacionId;
        $reserva->fecha_inicio = $inicio;
        $reserva->fecha_fin = $fin;
        $reserva->total = $total;
        $reserva->estado = 'pendiente';
        $reserva->payment_method = $payment_method;
        $reserva->payment_receipt = $receiptPath;
        // marcar recibido automáticamente para tarjeta (simulado), y pendiente para yape
        $reserva->payment_status = ($payment_method === 'card' ? 'received' : 'pending');

        $insertId = $reservaDAO->create($reserva);

        if ($insertId) {
            // Si pago recibido, marcar habitación ocupada
            if ($reserva->payment_status === 'received') {
                $habitacionDAO->updateEstado($habitacionId, '2');
            }

            $this->render('reservas/confirmar', ['total' => $total, 'inicio' => $inicio, 'fin' => $fin]);
            return;
        }

        $this->render('reservas/error', ['mensaje' => 'No se pudo guardar la reserva.']);
        return;
    }

    // Mostrar reservas del usuario
    public function misReservas()
    {
        if (!isset($_SESSION['user'])) {
            header('Location: /?action=login');
            exit;
        }

        // Verificar si es un objeto incompleto (sesión corrupta)
        $user = $_SESSION['user'];
        if (is_object($user) && get_class($user) === '__PHP_Incomplete_Class') {
            // Sesión corrupta, limpiar
            session_unset();
            session_destroy();
            header('Location: /public/clear-session.php');
            exit;
        }

        // Extraer email (si es array o objeto)
        $userEmail = null;
        if (is_array($user)) {
            $userEmail = $user['email'] ?? null;
        } elseif (is_object($user) && property_exists($user, 'email')) {
            $userEmail = $user->email;
        }

        if (!$userEmail) {
            header('Location: /?action=login');
            exit;
        }

        $conn = Database::getInstance();
        require_once __DIR__ . '/../DAO/ReservaDAO.php';
        require_once __DIR__ . '/../DAO/habitacionDAO.php';
        require_once __DIR__ . '/../DAO/PersonaDAO.php';

        $reservaDAO = new ReservaDAO($conn);
        $habitacionDAO = new HabitacionDAO($conn);
        $personaDAO = new PersonaDAO($conn);

        // Obtener solo las reservas asociadas al email del usuario
        $reservas = $reservaDAO->findByUserEmail($userEmail);

        // Enriquecer reservas con datos de habitación
        foreach ($reservas as &$r) {
            $h = $habitacionDAO->findById($r['habitacion_id']);
            $r['habitacion'] = $h;
        }

        $this->render('reservas/misreservas', ['reservas' => $reservas]);
    }
}
