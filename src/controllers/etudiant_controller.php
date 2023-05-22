<?php
// Inclusion des fichiers nécessaires pour accéder à la base de données et gérer les utilisateurs
require_once '../classes/Database.php';
require_once '../classes/Utilisateur.php';
require_once '../classes/Etudiant.php';
require_once 'user_controller.php';

class EtudiantController extends UserController{
    
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
            echo "Le succès est faux";
            return false;
        }
    
        // Ajouter des informations supplémentaires spécifiques aux étudiants dans la base de données
        $sql = "UPDATE " . $this->table_name . " SET Niveau = ?, Ecole = ? WHERE Email = ?";
        $stmt = $this->conn->prepare($sql);
        if($stmt === false) {
            die('prepare() failed: ' . htmlspecialchars($this->conn->error));
        }
    
        $niveau = $user->getNiveau();
        $ecole = $user->getEcole();
        $email = $user->getEmail();
        echo "Le niveau : $niveau ; lecole : $ecole; lemail : $email";
        $stmt->bind_param("sss", $niveau, $ecole, $email);
    
        if ($stmt->execute()) {
            return true;
        } else {
            echo "lexecution est fausse";
            return false;
        }
    }
    

    public function updateProfile(Utilisateur $user) {
        // Vérifier que l'utilisateur est un Etudiant
        if (!($user instanceof Etudiant)) {
            // Levez une exception ou retournez une erreur ici
            throw new InvalidArgumentException('Un Objet Etudiant est Attendu');
        }
        
        // Mise à jour de l'utilisateur
        $success = parent::updateProfile($user);
        if(!$success){
            return false;
        }
        
        // Mettre à jour les informations spécifiques aux étudiants dans la base de données
        //Passer par l'ID au lieu du mail ?
        $sql = "UPDATE " . $this->table_name . " SET Niveau = ?, Ecole = ? WHERE ID = ?";
        $stmt = $this->conn->prepare($sql);
        if($stmt === false) {
            die('prepare() failed: ' . htmlspecialchars($this->conn->error));
        }
    
        $niveau = $user->getNiveau();
        $ecole = $user->getEcole();
        $id = $user->getID();
        $stmt->bind_param("sss", $niveau, $ecole, $id);
    
        if ($stmt->execute()) {
            return true;
        } else {
            return false;
        }
    }
    
}
?>
