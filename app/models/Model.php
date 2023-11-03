<?php
require_once './config.php';

class Model {
    protected $dataBase;

    function __construct() {
        $this->dataBase = new PDO('mysql:host='. DB_HOST .';charset=utf8', DB_USER, DB_PASSWORD);
        $this->deploy();
    }

    function deploy() {
        // Verificar si la base de datos "tpe_web2" existe
        $query = $this->dataBase->query('SHOW DATABASES LIKE "tpe_web2"');
        $databaseExists = $query->rowCount() > 0;

        if (!$databaseExists) {
            // Si la base de datos no existe, créala
            $this->dataBase->exec('CREATE DATABASE tpe_web2');
        }

        // Seleccionar la base de datos "tpe_web2"
        $this->dataBase->exec('USE tpe_web2');

        // A continuación, puedes agregar la creación de tablas y la inserción de datos para las tablas que necesites en la base de datos "tpe_web2".

        // Creación de la tabla "usuarios" (sin restricciones de clave externa)
        $this->dataBase->exec('
            CREATE TABLE IF NOT EXISTS `users` (
                `id` int(11) NOT NULL AUTO_INCREMENT,
                `userName` varchar(50) NOT NULL,
                `password` varchar(255) NOT NULL,
                PRIMARY KEY (`id`)
            )
        ');

        // Insertar datos en la tabla "usuarios" solo si no existen registros
        $query = $this->dataBase->query('SELECT * FROM `users`');
        if ($query->rowCount() == 0) {
            $password = 'admin'; // Cambia esto por la contraseña deseada
            $passwordEncriptada = password_hash($password, PASSWORD_DEFAULT);
            $this->dataBase->exec('
                INSERT INTO `users` (`userName`, `password`) VALUES
                ("webadmin", "' . $passwordEncriptada . '")
            ');
        }

        // Ejemplo de creación de la tabla "clubes"
        $this->dataBase->exec('
            CREATE TABLE IF NOT EXISTS `clubes` (
                `id_club` int(11) NOT NULL AUTO_INCREMENT,
                `nombre` varchar(45) NOT NULL,
                `fecha_creacion` date NOT NULL,
                `ubicacion` varchar(45) NOT NULL,
                `estadio` varchar(45) NOT NULL,
                `campeonatos_locales` int(11) NOT NULL,
                PRIMARY KEY (`id_club`)
            )
        ');

        // Insertar datos en la tabla "clubes" solo si no existen registros
        $query = $this->dataBase->query('SELECT * FROM `clubes`');
        if ($query->rowCount() == 0) {
            $this->dataBase->exec('
                INSERT INTO `clubes` (`nombre`, `fecha_creacion`, `ubicacion`, `estadio`, `campeonatos_locales`) VALUES
                ("Boca Juniors", "1905-04-03", "Buenos Aires", "La Bombonera", 35),
                ("River Plate", "1901-05-25", "Buenos Aires", "El Monumental", 38),
                ("Velez", "1910-01-01", "Buenos Aires", "José Amalfitani", 9)
            ');
        }

        // Añade más tablas y datos según sea necesario siguiendo el mismo patrón.

        // Ejemplo de creación de la tabla "jugadores"
        $this->dataBase->exec('
            CREATE TABLE IF NOT EXISTS `jugadores` (
                `id_jugador` int(11) NOT NULL AUTO_INCREMENT,
                `nombre` varchar(45) NOT NULL,
                `edad` int(11) NOT NULL,
                `nacionalidad` varchar(45) NOT NULL,
                `posicion` varchar(45) NOT NULL,
                `pie_habil` varchar(45) NOT NULL,
                `id_club` int(11) NOT NULL,
                PRIMARY KEY (`id_jugador`),
                KEY `id_club` (`id_club`),
                CONSTRAINT `jugadores_ibfk_1` FOREIGN KEY (`id_club`) REFERENCES `clubes` (`id_club`)
            )
        ');

        // Insertar datos en la tabla "jugadores" solo si no existen registros
        $query = $this->dataBase->query('SELECT * FROM `jugadores`');
        if ($query->rowCount() == 0) {
            $this->dataBase->exec('
                INSERT INTO `jugadores` (`nombre`, `edad`, `nacionalidad`, `posicion`, `pie_habil`, `id_club`) VALUES
                ("Sergio Romero", 36, "Argentina", "arquero", "derecho", 1),
                ("Marcelo Saracchi", 25, "Uruguay", "Defensor", "Diestro", 1),
                ("Leonardo Fabian Burian", 39, "Uruguay", "Arquero", "Diestro", 3),
                ("Braian Romero", 32, "Argentina", "Delantero", "Diestro", 3),
                ("Walter Bou", 30, "Argentina", "Delantero", "Diestro", 3),
                ("Facundo Colidio", 23, "Argentina", "Delantero", "Diestro", 2),
                ("Matias Suarez", 35, "Argentina", "Delantero", "Diestro", 2)
            ');
        }
    }
}