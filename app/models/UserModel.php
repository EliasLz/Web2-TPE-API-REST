<?php

require_once './app/models/Model.php';

class UserModel extends Model{

    function getUserByUsername($username){
        $query = $this->dataBase->prepare('SELECT * FROM users WHERE username = ?');
        $query->execute([$username]);

        return $query->fetch(PDO::FETCH_OBJ);
    }

}