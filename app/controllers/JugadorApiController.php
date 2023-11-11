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

// ademas de restringir ciertas funciones solo a usuarios con token podemos hacer que alguna funcion solo este habilitada
// para usuarios con token y administradores

    public function getJugadores($params = null){
        if (empty($params)){
            $resultado='';
            $codigo=0;
            if (!empty($_GET['campo'])){
                $campo = $_GET['campo'];
                if ($campo == 'nombre' || $campo == 'edad' || $campo == 'nacionalidad' || $campo == 'posicion' || $campo == 'pie_habil' || $campo == 'id_club'){
                    if (!empty($_GET['orden'])){
                        $orden = $_GET['orden'];
                        if ($orden == 'ASC' || $orden == 'DESC'|| $orden == 'asc' || $orden == 'desc'){
                            //Se da por hecho que en el frontend "paginacion" es false por default y si el usurio pide paginacion se envia como un parametro get paginacion=true
                            if (!empty($_GET['paginacion']) && !empty($_GET['limite'])){
                                $paginacion = $_GET['paginacion'];
                                $limite = $_GET['limite'];
                                if ($paginacion==true && is_int($limite) && $limite<=250 && $limite>=1){
                                $resultado = $this->jugadorModel->getJugadoresOrdenados($campo, $orden, $limite);
                                $codigo=200;
                                } else{
                                    $resultado = 'Los valores no son los esperados';
                                    $codigo = 404;
                                }
                            } else {
                                $resultado = $this->jugadorModel->getJugadoresOrdenados($campo, $orden);
                                $codigo=200;
                            }
                        } else{
                            $resultado = 'Los valores no son los esperados';
                            $codigo = 404;
                        }
                    } else{ //repeti codigo como un infeliz
                        if (!empty($_GET['paginacion']) && !empty($_GET['limite'])){
                            $paginacion = $_GET['paginacion'];
                            $limite = $_GET['limite'];
                            if ($paginacion==true && is_int($limite) && $limite<=250 && $limite>=1){
                                $resultado = $this->jugadorModel->getJugadoresOrdenados($campo, 'ASC', $limite);
                                $codigo=200;
                            } else{
                                $resultado = 'Los valores no son los esperados';
                                $codigo = 404;
                            }
                        } else {
                            $resultado = $this->jugadorModel->getJugadoresOrdenados($campo, 'ASC');
                            $codigo=200;
                        }
                    } 
                } else{
                    $resultado = 'Los valores no son los esperados';
                    $codigo = 404;
                }
            } else{//repeti codigo como un infeliz x2
                if (!empty($_GET['paginacion']) && !empty($_GET['limite'])){
                    $paginacion = $_GET['paginacion'];
                    $limite = $_GET['limite'];
                    if ($paginacion==true && is_int($limite) && $limite<=250 && $limite>=1){
                        $resultado = $this->jugadorModel->getJugadores($limite);
                        $codigo=200;
                    } else{
                        $resultado = 'Los valores no son los esperados';
                        $codigo = 404;
                    }
                } else {
                    $resultado = $this->jugadorModel->getJugadores();
                    $codigo = 200;
                }
            }
            //el problema es que acá mezclamos entre hacerlo con funcion model compleja y hacerlo desde el controller
            //este filtrado manual rompe el objeto fetch por tanto el paginamiento de sql
            if (!empty($_GET['nacionalidad']) && ($codigo==200)){
                $nacionalidad = $_GET['nacionalidad'];
                $resultado_filtrado = [];
                foreach($resultado as $jugador){
                    if ($jugador->nacionalidad == $nacionalidad){
                        array_push($resultado_filtrado, $jugador);
                    }
                }
                //dejo que devuelva un arreglo vacio?
                if(empty($resultado_filtrado)){
                    $resultado = 'No existen jugadores con esa nacionalidad';
                    $codigo = 404;
                } else {
                    $resultado = $resultado_filtrado;  
                }
            }
            return $this->view->response($resultado, $codigo); 
            
        } else{
            $jugador = $this->jugadorModel->getJugadorById($params[':ID']);
            if ($jugador){
                $this->view->response($jugador, 200);
            } else{
                $this->view->response('El jugador con el id= '. $params[':ID'] . ' no existe', 404);
            }
        }
    }

    function updateJugador($params = null){
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
                    $this->jugadorModel->modificarJugador($jugador_id, $nombre, $edad, $nacionalidad, $posicion, $pie_habil, $club_id);
                    $this->view->response('El jugador con el id= '. $jugador_id . ' ha sido modificado', 200);
                } else{
                    $this->view->response("El club del id $club_id no existe", 404);
                }
            }   
        } else{
            $this->view->response('El jugador con el id= '. $jugador_id . ' no existe', 404);
        }
    }

    function deleteJugador($params = 0){
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

    function agregarJugador($params=null){
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