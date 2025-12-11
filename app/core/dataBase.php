<?php

class Database {

    private static $instance = null;

    public static function getInstance()
    {
        if (!self::$instance) {

            $host = "mysql5036.site4now.net";
            $db   = "db_ac209d_reserva";
            $user = "ac209d_reserva";
            $pass = "willian1W";

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