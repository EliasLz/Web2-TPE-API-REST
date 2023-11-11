<?php

require_once './app/models/Model.php';

class JugadorModel extends Model{
    // la solucion sería hacer todo desde pedidos sql solo con con esta funcion
    function getJugadores($limite=null){
        if ($limite!=null){ //if ($limite)  if (!empty($limite)) wherever...
            $query = $this->dataBase->prepare("SELECT * FROM jugadores jugadores LIMIT $limite");
        } else{
            $query = $this->dataBase->prepare('SELECT * FROM jugadores jugadores');
        }
        $query->execute();

        $jugadores = $query->fetchAll(PDO::FETCH_OBJ);
        return $jugadores;
    }

    function getJugadoresOrdenados($campo, $orden, $limite=null){
        if ($limite!=null){
            $query = $this->dataBase->prepare("SELECT * FROM jugadores ORDER BY $campo $orden LIMIT $limite");
        } else {
            $query = $this->dataBase->prepare("SELECT * FROM jugadores ORDER BY $campo $orden");
        }
        $query->execute();

        $jugadores = $query->fetchAll(PDO::FETCH_OBJ);
        return $jugadores;
    }
    //usamos esta funcion en algun momento? la puedo borrar?
    function getJugadoresByNacionalidad($nacionalidad){
        $query = $this->dataBase->prepare('SELECT * FROM jugadores WHERE nacionalidad = ?');
        $query->execute([$nacionalidad]);

        $jugadores = $query->fetchAll(PDO::FETCH_OBJ);
        return $jugadores;
    }


    function getJugadorById($id){
        $query = $this->dataBase->prepare('SELECT jugadores.*, clubes.nombre AS nombre_club FROM jugadores INNER JOIN clubes ON jugadores.id_club = clubes.id_club WHERE id_jugador = ?');
        $query->execute([$id]);

        $jugador = $query->fetch(PDO::FETCH_OBJ);
        return $jugador;
    }

    function agregarJugador($nombre, $edad, $nacionalidad, $posicion, $pie_habil, $club_id){
        $query = $this->dataBase->prepare('INSERT INTO jugadores (nombre, edad, nacionalidad, posicion, pie_habil, id_club) VALUES (?,?,?,?,?,?)');
        $query->execute([$nombre, $edad, $nacionalidad, $posicion, $pie_habil, $club_id]);
        
        return $this->dataBase->lastInsertId();
    }

    function modificarJugador($id,$nombre, $edad, $nacionalidad, $posicion, $pie_habil, $club_id){
        $query = $this->dataBase->prepare('UPDATE jugadores SET nombre = ?, edad = ?, nacionalidad = ?, posicion = ?, pie_habil = ?, id_club = ? WHERE id_jugador = ?');
        $query->execute([$nombre, $edad, $nacionalidad, $posicion, $pie_habil, $club_id, $id]);
    }

    function borrarJugador($id){
        $query = $this->dataBase->prepare('DELETE FROM jugadores WHERE id_jugador = ?');
        $query->execute([$id]);
    }

    //Funciones que sirven para el ClubController
    
    function borrarJugadoresByIdClub($id){
        $query = $this->dataBase->prepare('DELETE FROM jugadores WHERE id_club = ?');
        $query->execute([$id]);
    }

    function getJugadoresConNombreDeClubByClubId($id){
        $query = $this->dataBase->prepare('SELECT jugadores.*, clubes.nombre AS nombre_club FROM jugadores INNER JOIN clubes ON jugadores.id_club = clubes.id_club WHERE jugadores.id_club = ?');
        $query->execute([$id]);

        $jugadores = $query->fetchAll(PDO::FETCH_OBJ);
        return $jugadores;
    }

}