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
        /* NO SE SI ESTA BIEN QUE TENGA EL NOMBRE DEL CLUB */ //y si te piden por orden asc de 'nombre_club'?
        if (empty($params)){
            $resultado='';
            $codigo=0;
            //esto lo tengo q pensar un poco mas// si pongo orden 
            if ($_GET['columna'] && $_GET['orden']){
                $columna = $_GET['columna'];
                $orden = $_GET['orden'];
                if (($columna == 'nombre' || $columna == 'edad' || $columna == 'nacionalidad' || $columna == 'posicion' || $columna == 'pie_habil' || $columna == 'id_club')
                 && ($orden == 'ASC' || $orden == 'DESC')){
                /* 9) El servicio que obtiene una colección entera debe poder ordenarse por cualquiera de los campos de la tabla
                 de manera ascendente o descendente. (A diferencia del obligatorio que es solo por un campo a elección). */
                //aca va un switch en base al valor de $columna con una funcion del model por columna (6)...
                //mando fruta en la funcion jugadorModel getJugadoresOrdenados() y que tenga con una misma funcion
                //filtro para todos los campos
                    $resultado = $this->model->getJugadoresOrdenados($columna, $orden);
                    $codigo=200;
                } else {
                    $resultado = 'Los valores no son los esperados';
                    $codigo = 404;
                }
            } else {
                $resultado = $this->model->getJugadoresConNombreDeClub();
                $codigo = 200;
            }

            if ($_GET['nacionalidad']){
                $nacionalidad = $_GET['nacionalidad'];
                if (is_string($resultado)){
                    $resultado = $this->model->getJugadoresByNacionalidad($nacionalidad);
                    if (empty($resultado)){
                        $resultado = 'No existe un jugador con esa nacionalidad en nuestro sistema';
                        $codigo = 404;
                    } else {
                        $codigo = 200;
                    }
                } else {
                    $resultado_filtrado = [];
                    foreach($resultado as $jugador){
                        if ($jugador->nacionalidad == $nacionalidad){
                            array_push($resultado_filtrado, $jugador);
                        }
                    }
                    $resultado = $resultado_filtrado;  
                }
            }
            return $this->view->response($resultado, $codigo); 
            
        } else {
            $jugador = $this->model->getJugadorById($params[':ID']);
            if (!empty($jugador)){
                $this->view->response($jugador, 200);
            } else {
                $this->view->response('El jugador con el id= '. $params[':ID'] . ' no existe', 404);
            }
        }
    }

    function updateJugador($params = null){
        $jugador_id = $params[':ID'];
        $jugador = $this->model->getJugadorById($jugador_id);

        if ($jugador){
            $body = $this->getData();
            $nombre = $body->nombre;
            $edad = $body->edad;
            $nacionalidad = $body->nacionalidad;
            $posicion = $body->posicion;
            $pie_habil = $body->pie_habil;
            $club_id = $body->id_club;
            $this->model->modificarJugador($jugador_id, $nombre, $edad, $nacionalidad, $posicion, $pie_habil, $club_id);
            //chequear si se modificaron los campos es una bardo, lo hacemos?
            /* $sentencia->rowCount() nos dice cuántas filas fueron afectadas en la última ejecución. (También aplicable en INSERT o DELETE)
            */
            $this->view->response('El jugador con el id= '. $jugador_id . ' ha sido modificado', 200);
        } else {
            $this->view->response('El jugador con el id= '. $jugador_id . ' no existe', 404);
        }
    }

    function deleteJugador($params = 0){
        $jugador_id = $params[':ID'];
        $jugador = $this->model->getJugadorById($jugador_id);

        if ($jugador){
            //hacer esto agregando un return a la funcion borrar jugador-aunque mire la slide de PDO en "eliminar" y ni dice nada de return
            $this->model->borrarJugador($jugador_id);
            $jugadorEliminado = $this->model->getJugadorById($jugador_id);
            if ($jugadorEliminado){
                //elias
                $this->view->response('El jugador con el id= '. $jugador_id . ' no pudo ser elimiano', 404);
            } else {
                $this->view->response('El jugador con el id= '. $jugador_id . ' ha sido eliminado', 200);
            }
        } else {
            $this->view->response('El jugador con el id= '. $jugador_id . ' no existe', 404);
        }
    }

    function agregarJugador($params=null){

        $body = $this -> getData();
        $nombre = $body->nombre;
        $edad = $body->edad;
        $nacionalidad = $body->nacionalidad;
        $posicion = $body->posicion;
        $pie_habil = $body->pie_habil;
        $club_id = $body->id_club;

        if (empty($nombre) || empty($edad) || empty($nacionalidad) || empty($posicion) || empty($pie_habil) || empty($club_id)) {
            $this->view->response("Complete los datos", 400);
        } else {
            $id = $this->model->agregarJugador($nombre, $edad, $nacionalidad, $posicion, $pie_habil, $club_id);
            if ($id){
                $jugador = $this->model->getJugadorById($id);
                $this->view->response($jugador, 201);
            } else {
                $this->view->response("La carga falló", 404);
            }
        }
    }
}