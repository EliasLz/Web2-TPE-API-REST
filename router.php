<?php

require_once './libs/router.php';
require_once './config.php';

require_once './app/controllers/JugadorApiController.php';
require_once './app/controllers/UserApiController.php';

$router = new Router();

$router->addRoute('jugadores','GET','JugadorApiController','getJugadores');
$router->addRoute('jugadores/:ID','GET','JugadorApiController','getJugadores');
$router->addRoute('jugadores/:ID','DELETE','JugadorApiController','deleteJugador');
$router->addRoute('jugadores/:ID', 'PUT', 'jugadorApiController', 'updateJugador');
$router->addRoute('jugadores', 'POST', 'jugadorApiController', 'agregarJugador');

$router->addRoute('user/token', 'GET', 'UserApiController', 'getToken');

$router->route($_GET['resource'], $_SERVER['REQUEST_METHOD']);
    
 ?>