<?php
    require_once 'connect.php';

    class Connection {
        private static $instance = null;
        private static $connection;

        private function __construct() {
            try {
                self::$connection = new PDO("mysql:host=".SERVEUR.";dbname=".BDD, USER, PASSWORD);
                self::$connection->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
            } catch (PDOException $e) {
                $msg = "Erreur : " . $e->getMessage();
                exit($msg);
            }
        }

        public static function getInstance() {
            if (is_null(self::$instance)) {
                self::$instance = new Connection();
            }

            return self::$instance;
        }

        public function getConnection() {
            return self::$connection;
        }
    }