<?php

class Database {
    //Variables de la BDD
    private $host = "localhost";
    private $db_name = "nom_de_la_base_de_donnees";
    private $username = "username";
    private $password = "password";
    public $conn;

    public function getConnection() {
        $this->conn = null;

        try {
            $this->conn = new PDO("mysql:host=" . $this->host . ";dbname=" . $this->db_name, $this->username, $this->password);
            $this->conn->exec("set names utf8");
        } catch(PDOException $exception) {
            echo "Erreur de connexion : " . $exception->getMessage();
        }

        return $this->conn;
    }
}

/*
Exemple d'utilisation de la BDD : 
require_once 'Database.php';

$db = new Database();
$conn = $db->getConnection();



*/ 
?>
