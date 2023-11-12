<?php

require_once './app/models/Model.php';

class JugadorModel extends Model{
    // la solucion sería hacer todo desde pedidos sql solo con con esta funcion
    function getJugadores($nacionalidad=null, $campo, $orden, $inicio, $limite){

        if ($nacionalidad){
            $query = $this->dataBase->prepare("SELECT * FROM jugadores WHERE nacionalidad = ? ORDER BY $campo $orden LIMIT $inicio,$limite");
            $query->execute([$nacionalidad]);
        } else if ($nacionalidad == null){
            $query = $this->dataBase->prepare("SELECT * FROM jugadores ORDER BY $campo $orden LIMIT $inicio,$limite");
            $query->execute();
        } else { //este es para evitar algun posible fallo
            $query = $this->dataBase->prepare('SELECT * FROM jugadores');
            $query->execute();
        }
        return $query->fetchAll(PDO::FETCH_OBJ);
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

}