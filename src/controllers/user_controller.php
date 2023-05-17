<?php
// Inclusion des fichiers nécessaires pour accéder à la base de données et gérer les utilisateurs
require_once '../classes/Database.php';
require_once '../classes/User.php';

// Définition de la classe UserController
class UserController {
    // Variable pour se connecter à la base de données
    private $conn;
    // Nom de la table dans la base de données
    private $table_name = "Utilisateurs";

    // Constructeur de la classe
    public function __construct() {
        // Création d'une nouvelle instance de la classe Database
        $db = new Database();
        // Connexion à la base de données
        $this->conn = $db->getConnection();
    }

    // Méthode pour créer un nouvel utilisateur
    public function createUser(User $user) {
        try {
            // Préparation de la requête SQL
            $sql = "INSERT INTO " . $this->table_name . " (firstName, lastName, email, phone, password, role, activationStart, activationEnd, class, city) VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?, ?)";
            $stmt = $this->conn->prepare($sql);

            // Liaison des paramètres de la requête avec les valeurs de l'objet utilisateur
            $stmt->bindParam(1, $user->getFirstName());
            $stmt->bindParam(2, $user->getLastName());
            $stmt->bindParam(3, $user->getEmail());
            $stmt->bindParam(4, $user->getPhone());
            $stmt->bindParam(5, $user->getPassword());
            $stmt->bindParam(6, $user->getRole());
            $stmt->bindParam(7, $user->getActivationStart());
            $stmt->bindParam(8, $user->getActivationEnd());
            $stmt->bindParam(9, $user->getClass());
            $stmt->bindParam(10, $user->getCity());

            // Exécution de la requête
            if ($stmt->execute()) {
                return true;
            } else {
                return false;
            }
        } catch(PDOException $e) {
            // En cas d'erreur, affichage du message d'erreur
            echo "Error: " . $e->getMessage();
        }
    }

    // Méthode pour mettre à jour un utilisateur existant
    public function updateUser(User $user) {
        try {
            // Préparation de la requête SQL
            $sql = "UPDATE " . $this->table_name . " SET firstName = ?, lastName = ?, email = ?, phone = ?, password = ?, role = ?, activationStart = ?, activationEnd = ?, class = ?, city = ? WHERE id = ?";
            $stmt = $this->conn->prepare($sql);

            // Liaison des paramètres de la requête avec les valeurs de l'objet utilisateur
            $stmt->bindParam(1, $user->getFirstName());
            $stmt->bindParam(2, $user->getLastName());
            $stmt->bindParam(3, $user->getEmail());
            $stmt->bindParam(4, $user->getPhone());
            $stmt->bindParam(5, $user->getPassword());
            $stmt->bindParam(6, $user->getRole());
            $stmt->bindParam(7, $user->getActivationStart());
            $stmt->bindParam(8, $user->getActivationEnd());
            $stmt->bindParam(9, $user->getClass());
            $stmt->bindParam(10, $user->getCity());
            $stmt->bindParam(11, $user->getId());

            // Exécution de la requête
            if ($stmt->execute()) {
                return true;
            } else {
                return false;
            }
        } catch(PDOException $e) {
            // En cas d'erreur, affichage du message d'erreur
            echo "Error: " . $e->getMessage();
        }
    }

    // Méthode pour supprimer un utilisateur
    public function deleteUser($id) {
        try {
            // Préparation de la requête SQL
            $sql = "DELETE FROM " . $this->table_name . " WHERE id = ?";
            $stmt = $this->conn->prepare($sql);

            // Liaison de l'ID de l'utilisateur à la requête
            $stmt->bindParam(1, $id);

            // Exécution de la requête
            if ($stmt->execute()) {
                return true;
            } else {
                return false;
            }
        } catch(PDOException $e) {
            // En cas d'erreur, affichage du message d'erreur
            echo "Error: " . $e->getMessage();
        }
    }
    public function connectionUser($identifiant, $password){
    // Vérifie si l'utilisateur est déjà connecté, s'il est redirigé vers la page d'accueil
    if(isset($_SESSION["loggedin"]) && $_SESSION["loggedin"] === true){
        header("location: connexionreussie.php");
        exit;
    }

    // Définit les variables et initialise avec des valeurs vides
    $username_us = $password_us = "";
    $username_err = $password_err = "";

    // Traite les données soumises lors de la tentative de connexion
    if($_SERVER["REQUEST_METHOD"] == "POST"){
    echo($identifiant.$password);
    $donnees = donnesUser($identifiant,$password);
        // Vérifie si le nom d'utilisateur est vide
        if(empty(trim($identifiant))){
            throw new InvalidArgumentException('Veuillez rentrer un identifiant')
        } else{
            $username_us = trim($identifiant);
        }

        // Vérifie si le mot de passe est vide
        if(empty(trim($identifiant))){
            throw new InvalidArgumentException('Veuillez entrez votre mot de passe')
        } else{
            $password_us = trim($password);
        }

        // Valide les informations de connexion
        if(empty($username_err) && empty($password_err)){
        if ($donnees==true){
                // Le mot de passe est correct, démarre une nouvelle session
                session_start();
                // Stocke les données de session
                $_SESSION["loggedin"] = true;
                $_SESSION["login"]=$_POST["username_get"];
                // Redirige l'utilisateur vers la page d'accueil
                return(true);
            } else{
                // Affiche un message d'erreur si le mot de passe est incorrect
                throw new InvalidArgumentException("Le mot de passe que vous avez entré n'est pas valide.")
            }
        } else{
        // Affiche un message d'erreur si le nom d'utilisateur n'existe pas
        throw new InvalidArgumentException("Aucun compte trouvé avec ce nom d'utilisateur.");
            }
        }
    }
}


/*Exemple d'utilisation (tu peux copier coller):
//Inclure les fichiers nécessaires
require_once '../classes/Database.php';
require_once '../classes/User.php';
require_once '../classes/UserController.php';

// Créer une nouvelle instance de UserController
$userController = new UserController();

// Créer un nouvel utilisateur
$newUser = new User(null, "John", "Doe", "john.doe@example.com", "123456789", "password123", "admin", date("Y-m-d H:i:s"), date("Y-m-d H:i:s", strtotime("+1 year")), "L1", "New York");

if ($userController->createUser($newUser)) {
    echo "User created successfully.";
} else {
    echo "Failed to create user.";
}

// Mettre à jour un utilisateur existant
// Supposons que l'utilisateur que nous voulons mettre à jour ait l'ID 1
$existingUser = new User(1, "John", "Smith", "john.smith@example.com", "123456789", "password123", "admin", date("Y-m-d H:i:s"), date("Y-m-d H:i:s", strtotime("+1 year")), "L1", "New York");

if ($userController->updateUser($existingUser)) {
    echo "User updated successfully.";
} else {
    echo "Failed to update user.";
}

// Supprimer un utilisateur
// Supposons que l'utilisateur que nous voulons supprimer ait l'ID 1
if ($userController->deleteUser(1)) {
    echo "User deleted successfully.";
} else {
    echo "Failed to delete user.";
}
?>

*/
?>
