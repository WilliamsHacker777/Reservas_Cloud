<?php

require_once __DIR__ . "/controller.php";
require_once __DIR__ . "/../controllers/homeController.php";
require_once __DIR__ . "/../controllers/authController.php";
require_once __DIR__ . "/../controllers/habitacionController.php";
require_once __DIR__ . "/../controllers/reservaController.php";
<<<<<<< HEAD
require_once __DIR__ . "/../controllers/AdminController.php";
=======
>>>>>>> 4131f4c04a1090d01c13b4058ab4e30607a33ecb

class Router {

    public function handle()
    {
        $action = $_GET['action'] ?? 'home';

        switch ($action)
        {
            case 'login':
                (new AuthController())->loginView();
                break;

            case 'login/process':
                (new AuthController())->process();
                break;
    

            case 'register':
                (new AuthController())->registerView();
                break;

            case 'register/process':
                (new AuthController())->registerProcess();
                break;

            case 'reservar':
                (new ReservaController())->reservar();
                break;

            case 'reservar/pago':
                (new ReservaController())->pagoView();
                break;

            case 'reservar/guardar':
                (new ReservaController())->guardar();
                break;

            case 'reservar/guardarPago':
                (new ReservaController())->guardarPago();
                break;

            case 'misreservas':
                (new ReservaController())->misReservas();
                break;

            case 'perfil':
                (new AuthController())->profileView();
                break;

<<<<<<< HEAD
            // Rutas de administrador
            case 'admin':
                (new AdminController())->dashboard();
                break;

            case 'admin/habitaciones':
                (new AdminController())->habitaciones();
                break;

            case 'admin/habitacion/crear':
                (new AdminController())->crearHabitacionView();
                break;

            case 'admin/habitacion/guardar':
                (new AdminController())->guardarHabitacion();
                break;

            case 'admin/habitacion/editar':
                (new AdminController())->editarHabitacionView();
                break;

            case 'admin/habitacion/actualizar':
                (new AdminController())->actualizarHabitacion();
                break;

            case 'admin/habitacion/eliminar':
                (new AdminController())->eliminarHabitacion();
                break;

=======
>>>>>>> 4131f4c04a1090d01c13b4058ab4e30607a33ecb
            case 'logout':
                (new AuthController())->logout();
                break;
            default:
                (new HomeController())->index();
            

        }
    }
}
