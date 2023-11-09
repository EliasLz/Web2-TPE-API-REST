<?php

require_once './app/models/Model.php';

class JugadorModel extends Model{

    function getJugadoresConNombreDeClub(){
        $query = $this->dataBase->prepare('SELECT jugadores.*, clubes.nombre AS nombre_club FROM jugadores INNER JOIN clubes ON jugadores.id_club = clubes.id_club');
        $query->execute();

        $jugadores = $query->fetchAll(PDO::FETCH_OBJ);
        return $jugadores;
    }

    function getJugadoresOrdenados($columna, $orden){
        //                                                                                                                                                     y si quiere por clubes.nombre que hago???
        $query = $this->dataBase->prepare('SELECT jugadores.*, clubes.nombre AS nombre_club FROM jugadores INNER JOIN clubes ON jugadores.id_club = clubes.id_club ORDER BY jugadores.' .$columna .$orden);
        $query->execute();

        $jugadores = $query->fetchAll(PDO::FETCH_OBJ);
        return $jugadores;
    }

    function getJugadoresByNacionalidad($nacionalidad){
        $query = $this->dataBase->prepare('SELECT jugadores.*, clubes.nombre AS nombre_club FROM jugadores INNER JOIN clubes ON jugadores.id_club = clubes.id_club WHERE nacionalidad = ?');
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