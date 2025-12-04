<?php
session_start();

// 1. Cargar router antes de instanciarlo
require_once __DIR__ . '/../app/core/router.php';

// 2. Ejecutar enrutador
$router = new Router();
$router->handle();
