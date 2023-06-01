<?php
class Database {
    private $host = "localhost";
    private $db_name = "datachallenge_db";
    private $username = "cyfafix";
    private $password = "Xx&Yd9@7deEhee";
    private $conn;

    public function connect() {
        $this->conn = null;
        try {
            $this->conn = new mysqli($this->host, $this->username, $this->password, $this->db_name);
            if ($this->conn->connect_error) {
                throw new Exception("Connection failed: " . $this->conn->connect_error);
            }
        } catch(Exception $e) {
            echo 'Connection Error: ' . $e->getMessage();
            exit(1);  // arrête l'exécution du script
        }
        return $this->conn;
    }
    

    public function query($sql) {
        $result = $this->conn->query($sql);
        if ($this->conn->error) {
            echo 'Erreur SQL: ' . $this->conn->error . '<br>';
            echo 'Requête: ' . $sql;
        }
        return $result;
    }
    

    public function close() {
        $this->conn->close();
    }
}

/*
require 'Database.php';

// Créer une nouvelle instance de Database
$db = new Database();

// Se connecter à la base de données
$db->connect();

// Exécuter une requête SQL
$result = $db->query("SELECT * FROM users");

// Vérifier si la requête a retourné des résultats
if ($result->num_rows > 0) {
    // Parcourir les lignes de résultat et les afficher
    while($row = $result->fetch_assoc()) {
        echo "id: " . $row["id"]. " - Name: " . $row["name"]. " - Email: " . $row["email"]. "<br>";
    }
} else {
    echo "0 results";
}

// Fermer la connexion à la base de données
$db->close();

*/
?>

