<?php
// public_html/config/database.php

class Database {
    private static $instance = null;

    public static function getConnection() {
        if (self::$instance === null) {
            $host = 'localhost';
            $db   = 'xxxxxxxxx';
            $user = 'xxxxxxxxxxxx';
            $pass = 'xxxxxxxxxxxxxxxxx'; // Insira sua senha do cPanel aqui
            $charset = 'utf8mb4';

            $dsn = "mysql:host=$host;dbname=$db;charset=$charset";
            $options = [
                PDO::ATTR_ERRMODE            => PDO::ERRMODE_EXCEPTION,
                PDO::ATTR_DEFAULT_FETCH_MODE => PDO::FETCH_ASSOC,
                PDO::ATTR_EMULATE_PREPARES   => false,
                PDO::ATTR_PERSISTENT         => false,
                PDO::MYSQL_ATTR_INIT_COMMAND => "SET NAMES utf8mb4"
            ];

            // Tentativa 1
            try {
                self::$instance = new PDO($dsn, $user, $pass, $options);
            } catch (\PDOException $e) {
                // Tentativa 2 imediata (evita o erro do primeiro clique no servidor)
                try {
                    usleep(100000); // aguarda 0.1s para o socket resetar
                    self::$instance = new PDO($dsn, $user, $pass, $options);
                } catch (\PDOException $e2) {
                    die("Falha de Conexão no Servidor: " . $e2->getMessage());
                }
            }
        }
        return self::$instance;
    }
}