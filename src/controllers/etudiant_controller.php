<?php
// Inclusion des fichiers nécessaires pour accéder à la base de données et gérer les utilisateurs
require_once '../classes/Database.php';
require_once '../classes/Utilisateur.php';
require_once '../classes/Etudiant.php';
require_once 'user_controller.php';

class EtudiantController extends UserController{
    private $conn;
    private $table_name = "Utilisateurs";

    public function __construct() {
        $db = new Database();
        $this->conn = $db->connect();
        if ($this->conn) {
            echo "Database connection successful.";
        } else {
            echo "Database connection failed.";
        }
    }
    
    public function createUser(Utilisateur $user) {
        // Vérifier que l'utilisateur est un Etudiant
        if (!($user instanceof Etudiant)) {
            // Levez une exception ou retournez une erreur ici
            throw new InvalidArgumentException('Un Objet Etudiant est Attendu');
        }

        //On crée un utilisateur
        $success = parent::createUser($user);

        if(!$success){
            return false;
        }

        // Ajouter des informations supplémentaires spécifiques aux étudiants dans la base de données
        $sql = "INSERT INTO " . $this->table_name . " (Niveau, Ecole) VALUES (?, ?) WHERE Email = ?";
        $stmt = $this->conn->prepare($sql);
        if($stmt === false) {
            die('prepare() failed: ' . htmlspecialchars($this->conn->error));
        }
        
        $niveau = $user->getNiveau();
        $ecole = $user->getEcole();
        $email = $user->getEmail();
        $stmt->bind_param("sss", $niveau, $ecole, $email);

        if ($stmt->execute()) {
            return true;
        } else {
            return false;
        }
    }
}
?>
