<?php

namespace Framework\Models;

final class DatabaseSingleton {
    public static \mysqli $instance;

    public static function getInstance() : \mysqli {
        if (!isset(self::$instance)) {
            $env = parse_ini_file('Framework/Config/php.ini');

            if (!$env) {
                throw new \Exception("Could not find php.ini file");
            }

            if (!isset($env['DB_HOST']) || !isset($env['DB_USER']) || !isset($env['DB_PASSWORD']) || !isset($env['DB_NAME'])) {
                throw new \Exception("Missing database credentials in php.ini file");
            }

            $host = $env['DB_HOST'];
            $user = $env['DB_USER'];
            $password = $env['DB_PASSWORD'];
            $dbName = $env['DB_NAME'];
            $db = new Database($host, $user, $password, $dbName);
            $conn = $db->connect();
            self::$instance = $conn;
        }
        return self::$instance;
    }
}

