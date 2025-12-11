<?php

class Database {

    private static $instance = null;

    public static function getInstance()
    {
        if (!self::$instance) {

<<<<<<< HEAD
            $host = "mysql5036.site4now.net";
            $db   = "db_ac209d_reserva";
            $user = "ac209d_reserva";
            $pass = "willian1W";
=======
            // Ahora lee las variables del entorno del servidor (Render)
            //Arriba Alianza TLV
            $host = getenv('DB_HOST') ?: "MYSQL9001.site4now.net";
            $db   = getenv('DB_NAME') ?: "db_ac1074_sistema";
            $user = getenv('DB_USER') ?: "ac1074_sistema";
            $pass = getenv('DB_PASS') ?: "SNQqXqxx62X-mEJ";
>>>>>>> 4131f4c04a1090d01c13b4058ab4e30607a33ecb

            self::$instance = new PDO(
                "mysql:host=$host;dbname=$db;charset=utf8",
                $user,
                $pass
            );

            self::$instance->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
        }

        return self::$instance;
    }
}