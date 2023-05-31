<?php
// Inclusion des fichiers nécessaires pour accéder à la base de données et gérer les utilisateurs
require_once __DIR__. '/../classes/Database.php';
require_once __DIR__ . '/../classes/Utilisateur.php';

/*Fichier qui contient toutes les méthodes liés à un Utilisateur */
class UserController {
    protected $conn;
    protected $table_name = "Utilisateurs";

    public function __construct() {
        $db = new Database();
        $this->conn = $db->connect();
    }
    
    //Méthode qui permet de créer un utilisateur (pas de HASH !)
    public function createUser(Utilisateur $user) {
        try {
            // Vérifier si l'utilisateur existe déjà
            $sql = "SELECT * FROM " . $this->table_name . " WHERE Email = ?";
            $stmt = $this->conn->prepare($sql);
            if($stmt === false) {
                die('prepare() failed: ' . htmlspecialchars($this->conn->error));
            }
            $email = $user->getEmail();
            $stmt->bind_param("s", $email);
            $stmt->execute();
            $stmt->store_result();
            
            if($stmt->num_rows > 0) {
                // L'utilisateur existe déjà
                echo "L'utilisateur existe deja";
                return false;
            }
            
            $stmt->free_result();
            
            // Créer l'utilisateur
            $sql = "INSERT INTO " . $this->table_name . " (Nom, Prenom, Email, MotDePasse, Telephone, Ville, Role) VALUES (?, ?, ?, ?, ?, ?, ?)";
            $stmt = $this->conn->prepare($sql);
            if($stmt === false) {
                echo "Mauvais éxécution de la querry";
                die('prepare() failed: ' . htmlspecialchars($this->conn->error));
            }
            
            $nom = $user->getNom();
            $prenom = $user->getPrenom();
            $motDePasse = $user->getMotDePasse();
            $telephone = $user->getTelephone();
            $ville = $user->getVille();
            $role = $user->getRole();
    
            $stmt->bind_param("sssssss", $nom, $prenom, $email, $motDePasse, $telephone, $ville, $role);
    
            if ($stmt->execute()) {
                return true;
            } else {
                echo "Mauvais éxécution de la querry";
                return false;
            }
        } catch(Exception $e) {
            echo "Error: " . $e->getMessage();
        }
    }
    
    //Méthode qui permet de login un utilisateur (!Pas de Hash)
    //Postcondition : retourne les sessions de l'utilisateur Login !
    public function login($email, $password) {
        // Préparez la requête SQL
        $stmt = $this->conn->prepare("SELECT * FROM Utilisateurs WHERE Email = ?");

        // Liez les paramètres
        $stmt->bind_param("s", $email);

        // Exécutez la requête
        $stmt->execute();

        // Liez le résultat à des variables
        $stmt->bind_result($id, $nom, $prenom, $entreprise, $telephone, $email, $dateDebut, $dateFin, $hashedPassword, $role, $niveau, $ecole, $ville);

        // Récupérez le premier résultat
        $userFound = $stmt->fetch();

        //if ($userFound && password_verify($password, $hashedPassword)) {
        // Si un utilisateur avec cet email a été trouvé et que le mot de passe correspond
        if ($userFound && $password == $hashedPassword) {
            // Initialisez les informations de session
            $_SESSION['user_id'] = $id;
            $_SESSION['role'] = $role;

            // Retournez l'utilisateur pour utilisation ultérieure
            return new Utilisateur(
                $id,
                $nom,
                $prenom,
                $email,
                $hashedPassword,
                $telephone,
                $ville,
                $role
            );
        }

        // Si les identifiants sont incorrects, retournez null
        return null;
    }

    //Méthode qui permet d'update le profile d'un utilisateur
    //Attention : Cette méthode ne permet pas de modifier l'email d'un utilisateur !!!!
    public function updateProfile(Utilisateur $user) {
        try {
            // Vérifier si l'utilisateur existe
            $sql = "SELECT * FROM " . $this->table_name . " WHERE Email = ?";
            $stmt = $this->conn->prepare($sql);
            if($stmt === false) {
                die('prepare() failed: ' . htmlspecialchars($this->conn->error));
            }
            $email = $user->getEmail();
            $stmt->bind_param("s", $email);
            $stmt->execute();
            $stmt->store_result();
            
            if($stmt->num_rows == 0) {
                // L'utilisateur n'existe pas
                return false;
            }
            $stmt->free_result();
            
            // Mettre à jour l'utilisateur
            $sql = "UPDATE " . $this->table_name . " SET Nom = ?, Prenom = ?, MotDePasse = ?, Telephone = ?, Ville = ?, Role = ? WHERE Email = ?";
            $stmt = $this->conn->prepare($sql);
            if($stmt === false) {
                die('prepare() failed: ' . htmlspecialchars($this->conn->error));
            }
            
            $nom = $user->getNom();
            $prenom = $user->getPrenom();
            $motDePasse = $user->getMotDePasse();
            $telephone = $user->getTelephone();
            $ville = $user->getVille();
            $role = $user->getRole();
            $stmt->bind_param("sssssss", $nom, $prenom, $motDePasse, $telephone, $ville, $role, $email);
            mysqli_report(MYSQLI_REPORT_ERROR | MYSQLI_REPORT_STRICT);
            if ($stmt->execute()) {
                return true;
            } else {
                return false;
            }
        } catch(Exception $e) {
            echo "Error: " . $e->getMessage();
        }
    }

    //Cette méthode permet à n'importe qui avec un email inscrit de pouvoir changer son mdp
    public function changePassword($email, $oldPassword, $newPassword) {
        try {
            // Chercher l'utilisateur avec l'email donné
            $sql = "SELECT * FROM " . $this->table_name . " WHERE Email = ?";
            $stmt = $this->conn->prepare($sql);
            if($stmt === false) {
                die('prepare() failed: ' . htmlspecialchars($this->conn->error));
            }
            $stmt->bind_param("s", $email);
            $stmt->execute();
            $stmt->bind_result($id, $nom, $prenom, $entreprise, $telephone, $email, $dateDebut, $dateFin, $hashedPassword, $role, $niveau, $ecole, $ville);
            $userFound = $stmt->fetch();
    
            if (!$userFound) {
                // Utilisateur non trouvé
                return false;
            }
    
            // Vérifier l'ancien mot de passe
            if (!password_verify($oldPassword, $hashedPassword)) {
                // L'ancien mot de passe est incorrect
                return false;
            }
            $stmt->free_result();
            // Hasher le nouveau mot de passe
            $newHashedPassword = password_hash($newPassword, PASSWORD_DEFAULT);
    
            // Mettre à jour le mot de passe dans la base de données
            $sql = "UPDATE " . $this->table_name . " SET MotDePasse = ? WHERE Email = ?";
            $stmt = $this->conn->prepare($sql);
            if($stmt === false) {
                die('prepare() failed: ' . htmlspecialchars($this->conn->error));
            }
            $stmt->bind_param("ss", $newHashedPassword, $email);
            $stmt->execute();
    
            // Retourner true si la mise à jour a réussi
            if($stmt->affected_rows === 0) return false;
            return true;
        } catch(Exception $e) {
            return false;
        }
    }

    //Permet de supprimer un compte en donnant son adresse email
    public function deleteAccount($email) {
        try {
            // Chercher l'utilisateur avec l'email donné
            $sql = "SELECT * FROM " . $this->table_name . " WHERE Email = ?";
            $stmt = $this->conn->prepare($sql);
            if($stmt === false) {
                die('prepare() failed: ' . htmlspecialchars($this->conn->error));
            }
            $stmt->bind_param("s", $email);
            $stmt->execute();
            $userFound = $stmt->fetch();
    
            if (!$userFound) {
                // Utilisateur non trouvé
                return false;
            }
            $stmt->free_result();
            // Supprimer l'utilisateur de la base de données
            $sql = "DELETE FROM " . $this->table_name . " WHERE Email = ?";
            $stmt = $this->conn->prepare($sql);
            if($stmt === false) {
                die('prepare() failed: ' . htmlspecialchars($this->conn->error));
            }
            $stmt->bind_param("s", $email);
    
            if ($stmt->execute()) {
                // Le compte a été supprimé avec succès
    
                // Terminez la session
                session_start();
                $_SESSION = array();
                session_destroy();
                
                return true;
            } else {
                // Erreur lors de la suppression du compte
                return false;
            }
        } catch(Exception $e) {
            echo "Error: " . $e->getMessage();
        }
    }
    
    
    public function connectionUser($identifiant, $password){
        // Vérifie si l'utilisateur est déjà connecté, s'il est redirigé vers la page d'accueil
        if(isset($_SESSION["loggedin"]) && $_SESSION["loggedin"] === true){
            header("location: connexionreussie.php");
            exit;
        }
    }


    //Permet de logout un utilisateur en supprimant touts ses cookies de sessino
    public function logout() {
        // Démarrer la session
        session_start();
    
        // Unset all of the session variables
        $_SESSION = array();
    
        //détruira la session, et non seulement les données de session !
        if (ini_get("session.use_cookies")) {
            $params = session_get_cookie_params();
            setcookie(session_name(), '', time() - 42000,
                $params["path"], $params["domain"],
                $params["secure"], $params["httponly"]
            );
        }
    
        // Enfin, détruisez la session.
        session_destroy();
    }

    public function recuperData($idDc) {
        $valRetour = true;

        //On récupère les id des projets
        $idProjets = array();
        $sql1 = "SELECT ID FROM Projets WHERE ID_DataChallenge = ".$idDc;
        $stmt1 = $this->conn->prepare($sql1);
        if ($stmt1 === false) {
            throw new Exception('prepare() failed: ' . htmlspecialchars($this->conn->error));
        }
        if (!$stmt1->execute()) {
            $valReturn = false;
        }
        $stmt1->bind_result($result);

        while($stmt1->fetch()) {
            array_push($idProjets, $result);
        }
        $stmt1->close();

        //On récupère les id des équipes à partir de l'id de projet
        $idEquipes = array();
        foreach($idProjets as $idProjet) {
            $sql2 = "SELECT ID FROM Equipes WHERE ID_Projet = ".$idProjet;
            $stmt2 = $this->conn->prepare($sql2);
            if ($stmt2 === false) {
                throw new Exception('prepare() failed: ' . htmlspecialchars($this->conn->error));
            }
            if (!$stmt2->execute()) {
                $valReturn = false;
            }
            $stmt2->bind_result($idEquipe);
            while($stmt2->fetch()) {

                array_push($idEquipes, $idEquipe);
            }
            $stmt2->close();
        }

        

        //On récupère les analyses à partir de l'id de projet
        $analyses = array();
        foreach($idEquipes as $idEquipe) {
            $sql3 = "SELECT NombreLignes, NombreFonctions, LignesMinFonction, LignesMaxFonction, LignesMoyennesFonction, Nom FROM AnalysesCode
             JOIN Equipes ON AnalysesCode.ID_Equipe = Equipes.ID
             WHERE ID_Equipe = ".$idEquipe;
            $stmt3 = $this->conn->prepare($sql3);
            if ($stmt3 === false) {
                throw new Exception('prepare() failed: ' . htmlspecialchars($this->conn->error));
            }
            if (!$stmt3->execute()) {
                $valReturn = false;
            }
            $stmt3->bind_result($a,$b,$c,$d,$e,$f);
             while($stmt3->fetch()) {
                array_push($analyses, array($a,$b,$c,$d,$e,$f));
             }
            $stmt3->close();
        }
        return $analyses;
    }
    public function contact($email, $objet, $contenu){
        try {
            $email_admin = "projeting1pafa@gmail.com";
            $contenu .= "\r\nFrom: " . $email;
            $mailSent = mail($email_admin, $objet, $contenu);
            if ($mailSent) {
            } else {
                echo 'Échec de l\'envoi de l\'e-mail.';
            }
        } catch (Exception $e) {
            echo "Error: " . $e->getMessage();
        }
    }
    
}
    
?>
