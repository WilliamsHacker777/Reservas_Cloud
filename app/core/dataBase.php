<?php

class Database {

    private static $instance = null;

    public static function getInstance()
    {
        if (!self::$instance) {

            // Ahora lee las variables del entorno del servidor (Render)
            //Arriba Alianza TLV
            $host = getenv('DB_HOST') ?: "MYSQL9001.site4now.net";
            $db   = getenv('DB_NAME') ?: "db_ac1074_sistema";
            $user = getenv('DB_USER') ?: "ac1074_sistema";
            $pass = getenv('DB_PASS') ?: "SNQqXqxx62X-mEJ";

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
