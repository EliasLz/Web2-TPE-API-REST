<?php

require_once './app/models/Model.php';

class ClubModel extends Model{
 
    function getClubes(){
        $query = $this->dataBase->prepare('SELECT * FROM clubes');
        $query->execute();

        $clubes = $query->fetchAll(PDO::FETCH_OBJ);
        return $clubes;
    }

    function getClubById($id){
        $query = $this->dataBase->prepare('SELECT * FROM clubes WHERE id_club = ?');
        $query->execute([$id]);

        $club = $query->fetch(PDO::FETCH_OBJ);
        return $club;
    }

    function insertClub($nombre, $fecha_creacion, $ubicacion, $estadio, $campeonatos_locales) {
        $query = $this->dataBase->prepare('INSERT INTO clubes (nombre, fecha_creacion, ubicacion, estadio, campeonatos_locales) VALUES(?,?,?,?,?)');
        $query->execute([$nombre, $fecha_creacion, $ubicacion, $estadio, $campeonatos_locales]);
        return $this->dataBase->lastInsertId();
    }

    function modificarClub($id, $nombre, $fecha_creacion, $ubicacion, $estadio, $campeonatos_locales){
        $query = $this->dataBase->prepare('UPDATE clubes SET nombre = ?, fecha_creacion = ?, ubicacion = ?, estadio = ?, campeonatos_locales = ? WHERE id_club = ?');
        $query->execute([$nombre, $fecha_creacion, $ubicacion, $estadio, $campeonatos_locales, $id]);
    }

    function borrarClubById($id){
        $query = $this->dataBase->prepare('DELETE FROM clubes WHERE id_club = ?');
        $query->execute([$id]);
    }
}