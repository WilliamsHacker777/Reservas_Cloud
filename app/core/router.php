<?php

require_once __DIR__ . "/controller.php";
require_once __DIR__ . "/../controllers/homeController.php";
require_once __DIR__ . "/../controllers/authController.php";
require_once __DIR__ . "/../controllers/habitacionController.php";
require_once __DIR__ . "/../controllers/reservaController.php";

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

            case 'logout':
                (new AuthController())->logout();
                break;
            default:
                (new HomeController())->index();
            

        }
    }
}
