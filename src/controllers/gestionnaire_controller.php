

<?php


// Inclusion des fichiers nécessaires pour accéder à la base de données et gérer les utilisateurs
require_once __DIR__.'/../classes/Database.php';
require_once __DIR__.'/../classes/Utilisateur.php';
require_once __DIR__.'/user_controller.php';

class GestionnaireController extends UserController{
    protected $conn;
    public $table_questionnaire = "Questionnaires";
    public $table_questions = "Questions";
    public $table_reponse = "Reponses";
    public $table_message = "Messages";

    public function __construct() {
        $db = new Database();
        $this->conn = $db->connect();
    }
    
    public function createUser(Utilisateur $user) {
        // Vérifier que l'utilisateur est un Gestionnaire
        if (!($user instanceof Gestionnaire)) {
            throw new InvalidArgumentException('Un Objet gestionnaire est Attendu');
        }
    
        //On crée un utilisateur en appellant la classe mère 
        $success = parent::createUser($user);
    
        if(!$success){
            return false;
        }
        
        //on actualise les infos dans la BDD
        $sql = "UPDATE Utilisateurs SET Entreprise = ?, DateDebut = ?, DateFin = ? WHERE Email = ?";
        $stmt = $this->conn->prepare($sql);
        if($stmt === false) {
            die('prepare() failed: ' . htmlspecialchars($this->conn->error));
        }
    
        $entreprise = $user->getEntreprise();
        $debut = $user->getDebut();
        $fin = $user->getFin();
        $email = $user->getEmail();
        $stmt->bind_param("ssss", $entreprise, $debut, $fin, $email);
    
        if ($stmt->execute()) {
            return true;
        } else {
            echo "lexecution est fausse";
            return false;
        }
    }
    public function createQuestionnaire(Questionnaire $questionnaire, $id_Gest){
        try {
            // Ajout des informations liées au questionnaire en base de données (dates, id)
            $sql = "INSERT INTO " . $this->table_questionnaire . " (DateDebut, DateFin, ID_Gestionnaire,ID_Projet) VALUES (?, ?, ?, ?)";
            $stmt = $this->conn->prepare($sql);
            if ($stmt === false) {
                die('prepare() failed: ' . htmlspecialchars($this->conn->error));
            }
            $dateDebut = $questionnaire->getDateDebut();
            $dateFin = $questionnaire->getDateFin();
            $ID_Projet= $questionnaire->getIdProjet();
            $_SESSION['id_projet']=$ID_Projet;
            $ID_Gestionnaire = $id_Gest;
            $stmt->bind_param("ssii", $dateDebut, $dateFin, $ID_Gestionnaire,$ID_Projet);
            if ($stmt->execute()) {
                $stmt->bind_result($result); // Stocker les résultats de la première requête
                $sql2 = "SELECT ID FROM " . $this->table_questionnaire . " WHERE ID = (SELECT MAX(ID) FROM " . $this->table_questionnaire . " ) ";
                $stmt2 = $this->conn->prepare($sql2);
                if($stmt2 === false) {
                    die('prepare() failed: ' . htmlspecialchars($this->conn->error));
                }
                $stmt2->execute();
                $stmt2->bind_result($result);
                $stmt2->fetch(); // Récupérer les résultats
                $stmt->close();
                $stmt2->close();
                $value = $this->createQuestion($questionnaire,$result);
                $stmt->free_result(); // Libérer les résultats de la première requête
                return $result;
            } else {
                return false;
            }
        } catch(Exception $e) {
            echo "Error: " . $e->getMessage();
        }
    }
    
    public function createQuestion(Questionnaire $questionnaire, $result){
        try{
            // ajout des questions dans la base de données et les lie au questionnaire correspondant
            $valReturn=true;
            $Contenu = $questionnaire->getQuestions();
            $ID_Questionnaire = $result; // Utiliser l'ID du questionnaire récemment inséré
            $_SESSION["compteur"] = 0;
    
            foreach($Contenu as $question){
                $sql = "INSERT INTO " . $this->table_questions . " (Contenu, ID_Questionnaire) VALUES (?, ?)";
                $stmt3 = $this->conn->prepare($sql);
                if($stmt3 === false) {
                    die('prepare() failed: ' . htmlspecialchars($this->conn->error));
                }
                $stmt3->bind_param("si",$question, $ID_Questionnaire);
                if ($stmt3->execute()) {
                } else {
                    $valReturn=false;
                }
                $stmt3->close(); // Fermer la requête préparée
                $stmt = null; // Réattribuer la variable pour préparer la prochaine requête
            }
            return $valReturn;
        } catch(Exception $e) {
            echo "Error: " . $e->getMessage();
        }
    }
    
    //fonction appelée par la fonction deleteQuestionnaire
    public function deleteQuestion($ID_Questionnaire) {
        try {
            // Suppression des questions de la base de données
            $sql = "DELETE * FROM " . $this->table_questions . " WHERE ID_Questionnaire = ?";
            $stmt = $this->conn->prepare($sql);
            if ($stmt === false) {
                die('prepare() failed: ' . htmlspecialchars($this->conn->error));
            }
            $stmt->bind_param("i", $ID_Questionnaire);
            if ($stmt->execute()) {
                return true;
            } else {
                return false;
            }
        } catch(Exception $e) {
            echo "Error: " . $e->getMessage();
        }   
    }
    public function deleteQuestionnaire($ID_Questionnaire){
        try {
            // Suppression des questions liées au questionnaire
            $value = $this->deleteQuestion($ID_Questionnaire);
            if (!$value) {
                return false;
            }
            
            // Suppression des informations du questionnaire de la base de données
            $sql = "DELETE * FROM Questionnaires WHERE ID = ?";
            $stmt = $this->conn->prepare($sql);
            if ($stmt === false) {
                die('prepare() failed: ' . htmlspecialchars($this->conn->error));
            }
            $stmt->bind_param("i", $ID_Questionnaire);
            if ($stmt->execute()) {
                return true;
            } else {
                return false;
            }
        } catch(Exception $e) {
            echo "Error: " . $e->getMessage();
            return false;
        }
    }
    public function updateScore($ID_Equipe, $ID_Question, $nouvelle_Note) {
        try {
            // Mise à jour des informations de score dans la base de données
            $sql = "UPDATE " . $this->table_reponse . " SET Note = ? WHERE ID_Equipe = ? AND ID_Question = ?";
            $stmt = $this->conn->prepare($sql);
            if ($stmt === false) {
                die('prepare() failed: ' . htmlspecialchars($this->conn->error));
            }
            $stmt->bind_param("iii", $nouvelle_Note, $ID_Equipe, $ID_Question);
            if ($stmt->execute()) {
                return true;
            } else {
                return false;
            }
        } catch (Exception $e) {
            echo "Error: " . $e->getMessage();
        }
    }
    

    public function viewResponses($ID_Question) {
        try {
            // Récupération des réponses dans un tableau en sortie
            $sql = "SELECT * FROM " . $this->table_reponse . " WHERE ID_Question = ?";
            $stmt = $this->conn->prepare($sql);
            if ($stmt === false) {
                die('prepare() failed: ' . htmlspecialchars($this->conn->error));
            }
            $stmt->bind_param("i", $ID_Question);
            if ($stmt->execute()) {
                $res = $stmt->get_result();
                $tableau = array();
                while ($row = $res->fetch_assoc()) {
                    $tableau[] = $row;
                }
                return $tableau;
            } else {
                return false;
            }
        } catch (Exception $e) {
            echo "Error: " . $e->getMessage();
        }
    }
    
    public function sendMessages($tousMail, $Objet, $Contenu, $ID_Gestionnaire, $dateEnvoi) {
            try {
                // ajout des questions dans la base de données et les lie au questionnaire correspondant
                $valReturn = true;
                $listemail = explode(";", $tousMail); // Utiliser l'ID du questionnaire récemment inséré
                $sql0 = "SELECT Email FROM Utilisateurs WHERE ID = ?";
                $stmt0 = $this->conn->prepare($sql0);
                if ($stmt0 === false) {
                    die('prepare() failed: ' . htmlspecialchars($this->conn->error));
                }
                $stmt0->bind_param("i", $ID_Gestionnaire);
                $stmt0->execute();
                $stmt0->bind_result($mail_gest);
                $stmt0->fetch();
                $stmt0->close();
                
        
                foreach ($listemail as $mail) {
                    $sql = "SELECT ID FROM Equipes WHERE ID_Capitaine IN (SELECT ID FROM Utilisateurs WHERE Email = ?)";
                    $stmt = $this->conn->prepare($sql);
                    if ($stmt === false) {
                        die('prepare() failed: ' . htmlspecialchars($this->conn->error));
                    }
                    $stmt->bind_param("s", $mail);
                    $stmt->execute();
                    $stmt->bind_result($ID_Equipe);
                    $stmt->fetch();
                    $stmt->close();
                    $headers = "From: " . $mail_gest . "\r\n";
        
                    $sql2 = "INSERT INTO " . $this->table_message . "(Contenu, DateEnvoi, ID_Emetteur, ID_Equipe) VALUES (?, ?, ?, ?)";
                    $stmt2 = $this->conn->prepare($sql2);
                    if ($stmt2 === false) {
                        die('prepare() failed: ' . htmlspecialchars($this->conn->error));
                    }
                    $stmt2->bind_param("ssii", $Contenu, $dateEnvoi, $ID_Gestionnaire, $ID_Equipe);
                    if ($stmt2->execute()) {
                        $mailSent = mail($mail, $Objet, $Contenu, $headers);
                        if ($mailSent) {
                            
                        } else {
                            echo 'Échec de l\'envoi de l\'e-mail.';
                        }
                    } else {
                        $valReturn = false;
                    }
                    $stmt2->close();
                    $stmt2 = null;
                }
                return $valReturn;
            } catch (Exception $e) {
                echo "Error: " . $e->getMessage();
            }
        }
    
    
    

    public function viewMessages($mail, $ID_Gestionnaire) {
        try {
            // Récupération des réponses dans un tableau en sortie
            $sql = "SELECT Contenu FROM Messages WHERE ID_Equipe IN ( SELECT ID FROM Equipes WHERE ID_Capitaine IN (SELECT ID FROM Utilisateurs WHERE Email = ?)) AND ID_Emetteur = ?";
            $stmt = $this->conn->prepare($sql);
            if ($stmt === false) {
                die('prepare() failed: ' . htmlspecialchars($this->conn->error));
            }
            $stmt->bind_param("si", $mail, $ID_Gestionnaire);
            if ($stmt->execute()) {
                $res = $stmt->get_result();
                $tableau = array();
                while ($row = $res->fetch_assoc()) {
                    $tableau[] = $row;
                }
                return $tableau;
            } else {
                return false;
            }
        } catch (PDOException $e) {
            echo "Error: " . $e->getMessage();
        }
    }
    
    public function getQuestionIdByIdGest($id_gestionnaire){
        try{
            $sql = "SELECT ID FROM Questions WHERE ID_Questionnaire IN ( SELECT ID FROM Questionnaires WHERE ID_Gestionnaire = ?)";
            $stmt = $this->conn->prepare($sql);
            if ($stmt === false) {
                die('prepare() failed: ' . htmlspecialchars($this->conn->error));
            }
            $stmt->bind_param("i", $id_gestionnaire);
            if ($stmt->execute()) {
                $res = $stmt->get_result();
                $tableau = array();
                while ($row = $res->fetch_assoc()) {
                    $tableau[] = $row;
                }
                return $tableau;
            } else {
                return false;
            }
        } catch (PDOException $e) {
            echo "Error: " . $e->getMessage();
        }
    }
    public function getQuestionContenuById($id_Question){
        try{
            $res=NULL;
            $_SESSION['id_ma']=$id_Question;
            $sql = "SELECT Contenu FROM Questions WHERE ID = ?";
            $stmt = $this->conn->prepare($sql);
            if ($stmt === false) {
                die('prepare() failed: ' . htmlspecialchars($this->conn->error));
            }
            $stmt->bind_param("i", $id_Question);
            if ($stmt->execute()) {
                $stmt->bind_result($res);
                $stmt->fetch();
                return $res;
            } else {
                return false;
            }
        } catch (PDOException $e) {
            echo "Error: " . $e->getMessage();
        }
    }
    public function getIdProjetByIdGest($id_Gestionnaire){
        try{
            $sql = "SELECT ID FROM Projets WHERE ID_Gestionnaire = ?";
            $stmt = $this->conn->prepare($sql);
            if ($stmt === false) {
                die('prepare() failed: ' . htmlspecialchars($this->conn->error));
            }
            $stmt->bind_param("i", $id_Gestionnaire);
            if ($stmt->execute()) {
                $stmt->bind_result($res);
                $stmt->fetch();
                return $res;
            } else {
                return false;
            }
        } catch (PDOException $e) {
            echo "Error: " . $e->getMessage();
        }
    }

   public function getEmailsByIdProjet($idProjet){
    try{
            $sql = "SELECT Email FROM Utilisateurs WHERE ID IN ( SELECT ID_Capitaine FROM Equipes WHERE ID_Projet = ?)";
            $stmt = $this->conn->prepare($sql);
            if ($stmt === false) {
                die('prepare() failed: ' . htmlspecialchars($this->conn->error));
            }
            $stmt->bind_param("i", $idProjet);
            if ($stmt->execute()) {
                $stmt->bind_result($res);
                $stmt->fetch();
                return $res;
            } else {
                return false;
            }
        } catch (PDOException $e) {
            echo "Error: " . $e->getMessage();
        }
   }
}

?>
