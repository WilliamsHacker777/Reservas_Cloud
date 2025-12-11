<?php

require_once __DIR__ . "/../core/Controller.php";
require_once __DIR__ . "/../DAO/habitacionDAO.php";
require_once __DIR__ . "/../core/Database.php";

class AdminController extends Controller {

    // Verificar si el usuario es admin
    private function requireAdmin() {
        if (!isset($_SESSION['user']) || $_SESSION['user']['rol'] !== 'admin') {
            header('Location: /?action=login');
            exit;
        }
    }

    // Dashboard del admin
    public function dashboard() {
        $this->requireAdmin();

        $conn = Database::getInstance();
        $habitacionDAO = new HabitacionDAO($conn);
        
        // Obtenemos todas las habitaciones una sola vez para optimizar
        $todasLasHabitaciones = $habitacionDAO->findAll();

        $totalHabitaciones = count($todasLasHabitaciones);
        
        // CORRECCIÓN: Filtramos buscando el número 1 (Disponible)
        // Usamos '==' por si la base de datos devuelve el 1 como string "1"
        $habitacionesDisponibles = count(array_filter($todasLasHabitaciones, 
            function($h) { return $h['estado'] == 1; }));

        $this->render('admin/dashboard', [
            'totalHabitaciones' => $totalHabitaciones,
            'habitacionesDisponibles' => $habitacionesDisponibles
        ]);
    }

    // Listar habitaciones
    public function habitaciones() {
        $this->requireAdmin();

        $conn = Database::getInstance();
        $habitacionDAO = new HabitacionDAO($conn);
        $habitaciones = $habitacionDAO->findAll();

        $this->render('admin/habitaciones', [
            'habitaciones' => $habitaciones
        ]);
    }

    // Ver formulario para crear habitación
    public function crearHabitacionView() {
        $this->requireAdmin();
        $this->render('admin/habitacion_form', [
            'accion' => 'crear',
            'habitacion' => null
        ]);
    }

    // Guardar nueva habitación
    public function guardarHabitacion() {
        $this->requireAdmin();

        if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
            $this->render('admin/error', ['mensaje' => 'Método no permitido']);
            return;
        }

        $numero = trim($_POST['numero'] ?? '');
        $tipo = trim($_POST['tipo'] ?? '');
        $precio = floatval($_POST['precio'] ?? 0);
        $descripcion = trim($_POST['descripcion'] ?? '');
        $estado = trim($_POST['estado'] ?? 'disponible');

        if (!$numero || !$tipo || $precio <= 0) {
            $this->render('admin/error', ['mensaje' => 'Faltan datos obligatorios o precio inválido']);
            return;
        }

        // Manejar upload de imagen
        $imagenPath = null;
        if (!empty($_FILES['imagen']) && $_FILES['imagen']['error'] === UPLOAD_ERR_OK) {
            $imagenPath = $this->guardarImagenHabitacion($_FILES['imagen']);
            if (!$imagenPath) {
                $this->render('admin/error', ['mensaje' => 'Error al guardar imagen']);
                return;
            }
        }

        // Crear habitación
        $conn = Database::getInstance();
        require_once __DIR__ . '/../entities/Habitacion.php';
        $habitacionDAO = new HabitacionDAO($conn);

        $habitacion = new Habitacion();
        $habitacion->numero = $numero;
        $habitacion->tipo = $tipo;
        $habitacion->precio = $precio;
        $habitacion->descripcion = $descripcion;
        $habitacion->estado = $estado;
        $habitacion->imagen = $imagenPath; // Aquí ahora se guarda solo "nombre.jpg"

        $id = $habitacionDAO->create($habitacion);

        if ($id) {
            header('Location: /?action=admin/habitaciones');
            exit;
        }

        $this->render('admin/error', ['mensaje' => 'No se pudo crear la habitación']);
    }

    // Ver formulario para editar habitación
    public function editarHabitacionView() {
        $this->requireAdmin();

        $id = $_GET['id'] ?? null;
        if (!$id) {
            $this->render('admin/error', ['mensaje' => 'Habitación no encontrada']);
            return;
        }

        $conn = Database::getInstance();
        $habitacionDAO = new HabitacionDAO($conn);
        $habitacion = $habitacionDAO->findById($id);

        if (!$habitacion) {
            $this->render('admin/error', ['mensaje' => 'Habitación no encontrada']);
            return;
        }

        $this->render('admin/habitacion_form', [
            'accion' => 'editar',
            'habitacion' => $habitacion
        ]);
    }

    // Actualizar habitación
    public function actualizarHabitacion() {
        $this->requireAdmin();

        if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
            $this->render('admin/error', ['mensaje' => 'Método no permitido']);
            return;
        }

        $id = $_POST['id'] ?? null;
        if (!$id) {
            $this->render('admin/error', ['mensaje' => 'Habitación no encontrada']);
            return;
        }

        $numero = trim($_POST['numero'] ?? '');
        $tipo = trim($_POST['tipo'] ?? '');
        $precio = floatval($_POST['precio'] ?? 0);
        $descripcion = trim($_POST['descripcion'] ?? '');
        $estado = trim($_POST['estado'] ?? 'disponible');

        if (!$numero || !$tipo || $precio <= 0) {
            $this->render('admin/error', ['mensaje' => 'Faltan datos obligatorios']);
            return;
        }

        $conn = Database::getInstance();
        $habitacionDAO = new HabitacionDAO($conn);
        $habitacion = $habitacionDAO->findById($id);

        if (!$habitacion) {
            $this->render('admin/error', ['mensaje' => 'Habitación no encontrada']);
            return;
        }

        // Manejar nueva imagen si se carga
        $imagenPath = $habitacion['imagen'];
        if (!empty($_FILES['imagen']) && $_FILES['imagen']['error'] === UPLOAD_ERR_OK) {
            
            // Eliminar imagen anterior si existe
            // *** CAMBIO: Se agregó /img/ a la ruta porque $imagenPath ya no lo incluye ***
            if ($imagenPath && file_exists(__DIR__ . '/../../public/img/' . $imagenPath)) {
                unlink(__DIR__ . '/../../public/img/' . $imagenPath);
            }
            
            $imagenPath = $this->guardarImagenHabitacion($_FILES['imagen']);
            if (!$imagenPath) {
                $this->render('admin/error', ['mensaje' => 'Error al guardar imagen']);
                return;
            }
        }

        require_once __DIR__ . '/../entities/Habitacion.php';
        $habitacionObj = new Habitacion();
        $habitacionObj->id = $id;
        $habitacionObj->numero = $numero;
        $habitacionObj->tipo = $tipo;
        $habitacionObj->precio = $precio;
        $habitacionObj->descripcion = $descripcion;
        $habitacionObj->estado = $estado;
        $habitacionObj->imagen = $imagenPath;

        if ($habitacionDAO->update($habitacionObj)) {
            header('Location: /?action=admin/habitaciones');
            exit;
        }

        $this->render('admin/error', ['mensaje' => 'No se pudo actualizar la habitación']);
    }

    // Eliminar habitación
    public function eliminarHabitacion() {
        $this->requireAdmin();

        $id = $_GET['id'] ?? null;
        if (!$id) {
            $this->render('admin/error', ['mensaje' => 'Habitación no encontrada']);
            return;
        }

        $conn = Database::getInstance();
        $habitacionDAO = new HabitacionDAO($conn);
        $habitacion = $habitacionDAO->findById($id);

        if (!$habitacion) {
            $this->render('admin/error', ['mensaje' => 'Habitación no encontrada']);
            return;
        }

        // Eliminar imagen si existe
        // *** CAMBIO: Se agregó /img/ a la ruta porque $habitacion['imagen'] ya no lo incluye ***
        if ($habitacion['imagen'] && file_exists(__DIR__ . '/../../public/img/' . $habitacion['imagen'])) {
            unlink(__DIR__ . '/../../public/img/' . $habitacion['imagen']);
        }

        if ($habitacionDAO->delete($id)) {
            header('Location: /?action=admin/habitaciones');
            exit;
        }

        $this->render('admin/error', ['mensaje' => 'No se pudo eliminar la habitación']);
    }

    // Guardar imagen de habitación en carpeta
    private function guardarImagenHabitacion($file) {
        // Validaciones
        $allowed = ['image/jpeg', 'image/png', 'image/jpg', 'image/webp'];
        $maxSize = 5 * 1024 * 1024; // 5MB

        if ($file['size'] > $maxSize) {
            return null;
        }

        $mime = mime_content_type($file['tmp_name']);
        if (!in_array($mime, $allowed)) {
            return null;
        }

        // Usar carpeta img existente
        $uploadDir = __DIR__ . '/../../public/img/';
        if (!is_dir($uploadDir)) {
            mkdir($uploadDir, 0755, true);
        }

        // Generar nombre único
        $originalName = basename($file['name']);
        $ext = pathinfo($originalName, PATHINFO_EXTENSION);
        $slug = preg_replace('/[^a-zA-Z0-9_\.-]/', '_', pathinfo($originalName, PATHINFO_FILENAME));
        $fileName = time() . '_' . $slug . '.' . $ext;
        $target = $uploadDir . $fileName;

        // Mover archivo
        if (move_uploaded_file($file['tmp_name'], $target)) {
            // *** CAMBIO: Ahora retornamos SOLO el nombre del archivo, sin 'img/' ***
            return $fileName;
        }

        return null;
    }
}