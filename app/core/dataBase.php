<?php

class Database {

    private static $instance = null;

    public static function getInstance()
    {
        if (!self::$instance) {

            $host = "MYSQL9001.site4now.net";
            $db   = "db_ac1074_sistema";
            $user = "ac1074_sistema";
            $pass = "SNQqXqxx62X-mEJ";

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
