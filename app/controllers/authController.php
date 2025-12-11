<?php
require_once __DIR__ . "/../core/database.php";
require_once __DIR__ . "/../core/Controller.php";
require_once __DIR__ . "/../services/UsuarioService.php";

class AuthController extends Controller
{
    private $service;

    public function __construct()
    {
        $conn = Database::getInstance();
        $this->service = new UsuarioService($conn);
    }

    
    public function loginView()
    {
        // Pasar parámetros opcionales para redirección posterior (next, id)
        $next = $_GET['next'] ?? null;
        $id = $_GET['id'] ?? null;

        $this->render("auth/login", [
            "next" => $next,
            "id" => $id
        ], false);
    }

    
    public function process()
    {
        $email    = $_POST["email"] ?? null;
        $password = $_POST["password"] ?? null;

        $usuario = $this->service->login($email, $password);

        if ($usuario) {
            // Guardar usuario como array para evitar problemas de serialización de sesión
            $_SESSION["user"] = [
                'id' => $usuario->id,
                'email' => $usuario->email,
                'rol' => $usuario->rol,
                'activo' => $usuario->activo
            ];
            // Si se solicitó redirección posterior (por ejemplo reservar), respetarla
            $next = $_POST['next'] ?? null;
            $id = $_POST['id'] ?? null;

            if ($next) {
                $location = "/?action=" . urlencode($next);
                if ($id) {
                    $location .= "&id=" . urlencode($id);
                }
                header("Location: " . $location);
            } else {
                header("Location: /");
            }
            exit;
        }

        // Mostrar error dentro del login
        $this->render("auth/login", ["error" => "Credenciales incorrectas"], false);
    }

    
    public function registerView()
    {
        $this->render("auth/register", [], false);
    }

    // Mostrar perfil del usuario
    public function profileView()
    {
        if (!isset($_SESSION['user'])) {
            header('Location: /?action=login');
            exit;
        }

        $user = $_SESSION['user'];
        // Asegurar que sea array
        if (is_object($user) && property_exists($user, 'email')) {
            $user = [
                'id' => $user->id ?? null,
                'email' => $user->email ?? null,
                'rol' => $user->rol ?? null
            ];
        }

        $this->render('auth/profile', ['user' => $user], false);
    }

    public function registerProcess()
    {
        $email     = $_POST["email"]     ?? null;
        $password  = $_POST["password"]  ?? null;
        $password2 = $_POST["password2"] ?? null;

        // Validación básica
        if (!$email || !$password || !$password2) {
            return $this->render("auth/register", ["error" => "Todos los campos son obligatorios."], false);
        }

        if ($password !== $password2) {
            return $this->render("auth/register", ["error" => "Las contraseñas no coinciden."], false);
        }

        // Registrar
        $resultado = $this->service->registrar($email, $password);

        if ($resultado === false) {
            return $this->render("auth/register", ["error" => "El correo ya está registrado."], false);
        }

        // Redirigir al login
        header("Location: /?action=login");
        exit;
    }
    public function logout()
    {
        session_unset();
        session_destroy();
        
        // Reiniciar sesión después de destruirla
        session_start();
        
        header("Location: /?action=login");
        exit;
    }

}
