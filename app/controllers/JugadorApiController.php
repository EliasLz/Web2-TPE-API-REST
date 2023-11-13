<?php

require_once './app/controllers/ApiController.php';
require_once './app/models/JugadorModel.php';
require_once './app/models/ClubModel.php';
require_once './app/helpers/AuthApiHelper.php';

class JugadorApiController extends ApiController{
    private $jugadorModel;
    private $clubModel;
    private $authApiHelper;

    public function __construct(){
        parent::__construct();
        $this->jugadorModel = new JugadorModel();
        $this->clubModel = new ClubModel();
        $this->authApiHelper = new AuthApiHelper();
    }

    public function getJugadores($params = []){
        if (empty($params)){ // VARIOS ITEMS
            // Defino variables y controlo parametros GET primero.
            // PAGINAMIENTO:            
            $inicio = 0;
            $limite = $this->jugadorModel->getCantidadJugadores(); //por default el limite es la cantidad de jugadores que tenemos
            //checkear que el valor de 'pagina' no sea negativo
            //checkeear que no inyecten sql en pagina y en limite
            if (!empty($_GET['pagina']) && $_GET['pagina'] >= 1 && $_GET['pagina'] <= $limite && !empty($_GET['limite']) && $_GET['limite'] >= 1 && $_GET['limite'] <= $limite){
                $pagina = intval($_GET['pagina']);
                $limite = intval($_GET['limite']);
                if ($pagina>1){
                    $inicio = ($pagina * $limite) - ($limite);
                }
            } else if (empty($_GET['pagina'])/*  && !empty($_GET['limite']) && $_GET['limite'] >= 1 && $_GET['limite'] <= 50 */){
                $this->view->response('Falta el valor de la pagina', 404);
                return;
            } else if (empty($_GET['limite'])){
                $this->view->response('Falta el valor de el limite', 404);
                return;
            }else{
                $this->view->response('Los valores para el limite o el pagina no son los correctos', 404);
                return;
            }

            // ORDENAMIENTO:
            // Por default los jugadores se devuelven ordenados por nombre en orden ascendente.
            $campo = 'nombre'; 
            $orden = 'ASC';
            if(!empty($_GET['campo']) && $_GET['campo'] == 'nombre' || $_GET['campo'] == 'edad' || $_GET['campo'] == 'nacionalidad' || $_GET['campo'] == 'posicion' || $_GET['campo'] == 'pie_habil' || $_GET['campo'] == 'id_club'){
                $campo = $_GET['campo'];
                if (!empty($_GET['orden']) && $_GET['orden'] == 'ASC' || $_GET['orden'] == 'DESC'|| $_GET['orden'] == 'asc' || $_GET['orden'] == 'desc'){
                    $orden = $_GET['orden'];
                // Si se manipula la url para enviar parametroGet orden=DESC sin enviar orden, se devolverán los jugadores por default. Es decir, por nombre ascendete.
                } else if(!empty($_GET['orden'])){
                    $this->view->response('El valor para el tipo de orden no es el esperado', 404);
                    return;
                } 
            } else{
                $this->view->response('El valor para el campo de orden no es el esperado', 404);
                return;
            }

            // FILTRADO:
            $nacionalidad = null;
            // No existen controles para los valores parametroGET 'nacionalidad', si el valor no corresponde con un pais de un jugador simplemente devuelve un arreglo vacio.
            if (!empty($_GET['nacionalidad'])){
                $nacionalidad = $_GET['nacionalidad'];
            }
            $jugadores = $this->jugadorModel->getJugadores($nacionalidad, $campo, $orden, $inicio, $limite);
            $this->view->response($jugadores, 200);

        // ITEM ESPECIFICO    
        } else{
            $jugador = $this->jugadorModel->getJugadorById($params[':ID']);
            if ($jugador){
                $this->view->response($jugador, 200);
            } else{
                $this->view->response('El jugador con el id= '. $params[':ID'] . ' no existe', 404);
            }
        }
    }

    function updateJugador($params = []){
        $user = $this->authApiHelper->currentUser();
        if(!$user){
            $this->view->response('Unauthorized', 404); //seria 401
            return;
        }
        $jugador_id = $params[':ID'];
        $jugador = $this->jugadorModel->getJugadorById($jugador_id);

        if ($jugador){
            $body = $this->getData();
            $nombre = $body->nombre;
            $edad = $body->edad;
            $nacionalidad = $body->nacionalidad;
            $posicion = $body->posicion;
            $pie_habil = $body->pie_habil;
            $club_id = $body->id_club;

            if (empty($nombre) || empty($edad) || empty($nacionalidad) || empty($posicion) || empty($pie_habil) || empty($club_id)) {
                $this->view->response("Complete todos los datos", 400);
            } else{          
                $club = $this->clubModel->getClubById($club_id);
                if($club){
                    $filasAfectadas = $this->jugadorModel->modificarJugador($jugador_id, $nombre, $edad, $nacionalidad, $posicion, $pie_habil, $club_id);
                    if($filasAfectadas>0){
                        $this->view->response('El jugador con el id= '. $jugador_id . ' ha sido modificado', 200);
                    }
                    else{
                        $this->view->response("El jugador no fue modificado", 400);
                    }
                } else{
                    $this->view->response("El club del id $club_id no existe", 404);
                }
            }   
        } else{
            $this->view->response('El jugador con el id= '. $jugador_id . ' no existe', 404);
        }
    }

    function deleteJugador($params = []){
        $jugador_id = $params[':ID'];
        $jugador = $this->jugadorModel->getJugadorById($jugador_id);

        if ($jugador){
            $this->jugadorModel->borrarJugador($jugador_id);
            $jugadorAEliminar = $this->jugadorModel->getJugadorById($jugador_id);
            if ($jugadorAEliminar){
                $this->view->response('El jugador con el id= '. $jugador_id . ' no pudo ser elimiano', 404);
            } else{
                $this->view->response('El jugador con el id= '. $jugador_id . ' ha sido eliminado', 200);
            }
        } else{
            $this->view->response('El jugador con el id= '. $jugador_id . ' no existe', 404);
        }
    }

    function agregarJugador($params = []){
        $user = $this->authApiHelper->currentUser();
        if(!$user){
            $this->view->response('Unauthorized', 404); //seria 401
            return;
        }
        $body = $this->getData();
        $nombre = $body->nombre;
        $edad = $body->edad;
        $nacionalidad = $body->nacionalidad;
        $posicion = $body->posicion;
        $pie_habil = $body->pie_habil;
        $club_id = $body->id_club;

        if (empty($nombre) || empty($edad) || empty($nacionalidad) || empty($posicion) || empty($pie_habil) || empty($club_id)) {
            $this->view->response("Complete los datos", 400);
        } else{
            $club = $this->clubModel->getClubById($club_id);
            if($club){
                $id = $this->jugadorModel->agregarJugador($nombre, $edad, $nacionalidad, $posicion, $pie_habil, $club_id);
                if ($id){
                    $jugador = $this->jugadorModel->getJugadorById($id);
                    $this->view->response($jugador, 201);
                } else{
                    $this->view->response("La carga falló", 404);
                }
            } else{
                $this->view->response("El club del id $club_id no existe", 404);
            }
        }
    }
}