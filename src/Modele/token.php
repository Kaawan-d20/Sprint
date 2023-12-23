<?php
require_once 'connect.php';

class Connection {
    private static $instance = null;
    private static $connection;

    /**
     * Constructeur de la classe
     * C'est lui qui crée l'unique instance de Connection
     * @return void
     */
    private function __construct() {
        try {
            self::$connection = new PDO("mysql:host=".SERVEUR.";dbname=".BDD, USER, PASSWORD);
            self::$connection->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
        } catch (PDOException $e) {
            $msg = "Erreur : " . $e->getMessage();
            exit($msg);
        }
    }

    /**
     * Méthode qui crée l'unique instance de la classe
     * si elle n'existe pas encore puis la retourne.
     * @return Connection::$instance l'unique instance de la classe
     */
    public static function getInstance() {
        if (is_null(self::$instance)) {
            self::$instance = new Connection();
        }

        return self::$instance;
    }

    /**
     * Méthode qui retourne la connexion à la base de données
     * @return PDO la connexion à la base de données
     */
    public function getConnection() {
        return self::$connection;
    }
}