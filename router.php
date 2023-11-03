<?php

require_once './libs/router.php';
require_once './config.php';

require_once './app/controllers/JugadorApiController.php';

$router = new Router();

$router->addRoute('jugadores','GET','JugadorApiController','getJugadores');
$router->addRoute('jugadores/:ID','GET','JugadorApiController','getJugadores');
$router->addRoute('jugadores/:ID','DELETE','JugadorApiController','deleteJugador');
$router->addRoute('jugadores/:ID', 'PUT', 'jugadorApiController', 'updateJugador');

$router->route($_GET['resource'], $_SERVER['REQUEST_METHOD']);
    
 ?>