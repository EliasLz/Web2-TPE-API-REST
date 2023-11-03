<?php

require_once './app/controllers/ApiController.php';
require_once './app/models/JugadorModel.php';

class JugadorApiController extends ApiController{
    private $model;

    public function __construct(){
        parent::__construct();
        $this->model = new JugadorModel();
    }

    public function getJugadores($params = null){
        if(empty($params)){
            $jugadores = $this->model->getJugadoresConNombreDeClub();
            /* NO SE SI ESTA BIEN QUE TENGA EL NOMBRE DEL CLUB */
            return $this->view->response($jugadores, 200);
        }
        else{
            $jugador = $this->model->getJugadorById($params[':ID']);
            if(!empty($jugador)){
                $this->view->response($jugador, 200);
            }
            else{
                $this->view->response('El jugador con el id= '. $params[':ID'] . ' no existe', 404);
            }
        }
    }

    function updateJugador($params = null){
        $jugador_id = $params[':ID'];
        $jugador = $this->model->getJugadorById($jugador_id);

        if($jugador){
            $body = $this->getData();
            $nombre = $body->nombre;
            $edad = $body->edad;
            $nacionalidad = $body->nacionalidad;
            $posicion = $body->posicion;
            $pie_habil = $body->pie_habil;
            $club_id = $body->id_club;
            $this->model->modificarJugador($jugador_id,$nombre, $edad, $nacionalidad, $posicion, $pie_habil, $club_id);
            $this->view->response('El jugador con el id= '. $jugador_id . ' ha sido modificado', 200);
        }
        else{
            $this->view->response('El jugador con el id= '. $jugador_id . ' no existe', 404);
        }
    }

    function deleteJugador($params = 0){
        $jugador_id = $params[':ID'];
        $jugador = $this->model->getJugadorById($jugador_id);

        if($jugador){
            $this->model->borrarJugador($jugador_id);
            $this->view->response('El jugador con el id= '. $jugador_id . ' ha sido eliminado', 200);
        }
        else{
            $this->view->response('El jugador con el id= '. $jugador_id . ' no existe', 404);
        }
    }
}