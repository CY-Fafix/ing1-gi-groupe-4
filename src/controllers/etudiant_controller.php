<?php
// Inclusion des fichiers nécessaires pour accéder à la base de données et gérer les utilisateurs
require_once '../classes/Database.php';
require_once '../classes/Utilisateur.php';
require_once '../classes/Etudiant.php';
require_once '../classes/ProjetData.php';
require_once '../classes/Equipe.php';
require_once '../classes/Reponse.php';
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
        $stmt->bind_param("ssi", $niveau, $ecole, $id);
    
        if ($stmt->execute()) {
            return true;
        } else {
            return false;
        }
    }
    
    public function registerToDataChallenge(ProjetData $projetData, Etudiant $capitaine, $nomEquipe) 
    {
        $this->conn->autocommit(false);
        try {
            // 1. Vérifier que le nom de l'équipe n'existe pas déjà
            $sql = "SELECT COUNT(*) FROM Equipes WHERE Nom = ?";
            $count = $this->executeQuery($sql, "s", $nomEquipe);
            if($count > 0) {
                throw new Exception("nom equipe deja utilisé");
            }
    
            // 2. Vérifier que l'étudiant n'est pas déjà inscrit à un autre ProjetData dans ce DefiData
            // Obtenir le DataChallenge du projet
            $sql = "SELECT ID_DataChallenge FROM Projets WHERE ID = ?";
            $dataChallengeID = $this->executeQuery($sql, "i", $projetData->getId());
    
            // Vérifier si l'étudiant est déjà inscrit à un autre projet dans le même DataChallenge
            $sql = "SELECT COUNT(*) 
            FROM Utilisateurs user
            JOIN MembresEquipe ON user.ID = MembresEquipe.ID_Utilisateur
            JOIN Equipes equipe ON MembresEquipe.ID_Equipe = equipe.ID
            JOIN Projets projet ON equipe.ID_Projet = projet.ID
            WHERE user.ID = ? AND projet.ID_DataChallenge = ?";
            $count = $this->executeQuery($sql, "ii", $capitaine->getID(), $dataChallengeID);
            if($count > 0) {
                throw new Exception("deja inscris");
            }
    
            // L'étudiant peut s'inscrire à ce projet
            // 3. Créer une nouvelle équipe
            $equipe = new Equipe(null, $nomEquipe, [], $capitaine->getID());
    
            // Insérer la nouvelle équipe dans la base de données
            $sql = "INSERT INTO Equipes (Nom, ID_Projet, ID_Capitaine) VALUES (?, ?, ?)";
            $equipeID = $this->executeInsertQuery($sql, "sii", $equipe->getNom(), $projetData->getId(), $equipe->getChefEquipe());
    
            // Ajouter le capitaine à cette équipe
            $sql = "INSERT INTO MembresEquipe (ID_Equipe, ID_Utilisateur) VALUES (?, ?)";
            $this->executeInsertQuery($sql, "ii", $equipeID, $capitaine->getID());
    
            // Mettre à jour l'ID de l'équipe et les membres de l'équipe dans l'objet Equipe
            $equipe->setId($equipeID);
            $equipe->setMembres(array($capitaine->getID()));
    
            $this->conn->commit();
            return $equipe;
        } catch (Exception $e) {
            $this->conn->rollback();
            throw $e;
        } finally {
            $this->conn->autocommit(true);
        }
    }

    private function executeQuery($sql, $types, ...$params) {
        $stmt = $this->conn->prepare($sql);
        if($stmt === false) {
            throw new Exception('prepare() failed: ' . htmlspecialchars($this->conn->error));
        }
        $stmt->bind_param($types, ...$params);
        $stmt->execute();
        $stmt->bind_result($result); //Ligne 152
        $stmt->fetch();
        $stmt->close();
        return $result;
    }
    
    private function executeInsertQuery($sql, $types, ...$params) {
        $stmt = $this->conn->prepare($sql);
        if($stmt === false) {
            throw new Exception('prepare() failed: ' . htmlspecialchars($this->conn->error));
        }
        $stmt->bind_param($types, ...$params);
        $stmt->execute();
        $result = $stmt->insert_id;
        $stmt->close();
        return $result;
    }

    /* viewProjectDetails() : Permet à l'étudiant de voir les détails du projet de défi de données auquel il est inscrit.*/

    /*createTeam() : Permet à l'étudiant (en tant que capitaine) de créer une équipe pour le défi de données. */
    public function createTeam(Etudiant $capitaine, ProjetData $projet, $nomEquipe) 
    {
        $this->conn->autocommit(false);
        try {
            // 1. Créer une nouvelle équipe pour ce défi de données.
            $equipe = new Equipe(null, $nomEquipe, [], $capitaine->getID());
    
            // 2. Insérer la nouvelle équipe dans la base de données
            $sql = "INSERT INTO Equipes (Nom, ID_Projet, ID_Capitaine) VALUES (?, ?, ?)";
            $equipeID = $this->executeInsertQuery($sql, "sii", $equipe->getNom(), $projet->getId(), $equipe->getChefEquipe());
    
            // 3. Mettre à jour l'ID de l'équipe dans l'objet Equipe
            $equipe->setId($equipeID);
    
            // 4. Ajouter le capitaine à l'équipe
            $this->addMemberToTeam($equipe, $capitaine, $capitaine);
    
            $this->conn->commit();
            return $equipe;
        } catch (Exception $e) {
            $this->conn->rollback();
            throw $e;
        } finally {
            $this->conn->autocommit(true);
        }
    }
    
    

    public function addMemberToTeam(Equipe $equipe, Etudiant $capitaine, Etudiant $newMember)
    {
        // 1. Vérifier que le capitaine est bien le chef de l'équipe
        if ($equipe->getChefEquipe() !== $capitaine->getID()) {
            // Le capitaine n'est pas le chef de l'équipe
            throw new Exception("Le capitaine n'est pas le chef de l'équipe");
        }
        
        if($equipe == null){
            throw new Exception("L'équipe n'existe pas");
        }
        
        // 2. Vérifier que le nouveau membre n'est pas déjà dans l'équipe
        $membres = $equipe->getMembres(); // Tableau des ID des membres
        if (in_array($newMember->getID(), $membres)) {
            // Le nouveau membre est déjà dans l'équipe
            throw new Exception("Le nouveau membre est déjà dans l'équipe");
        }
        
        // Transaction
        $this->conn->autocommit(false);
        try {
            // 3. Ajouter le nouvel étudiant à l'équipe dans la base de données
            $sql = "INSERT INTO MembresEquipe (ID_Equipe, ID_Utilisateur) VALUES (?, ?)";
            $this->executeInsertQuery($sql, "ii", $equipe->getId(), $newMember->getID());
    
            // 4. Ajouter le nouvel étudiant à l'équipe dans l'objet Equipe
            array_push($membres, $newMember->getID());
            $equipe->setMembres($membres);
    
            $this->conn->commit();
            return true;
        } catch (Exception $e) {
            $this->conn->rollback();
            throw $e;
        } finally {
            $this->conn->autocommit(true);
        }
    }
    
    
    

    /*removeMemberFromTeam() : Permet au capitaine de retirer un membre de son équipe. */
    public function removeMemberFromTeam(Equipe $equipe, Etudiant $capitaine, Etudiant $member)
    {  
        // 1. Vérifier que le capitaine est bien le chef de l'équipe
        if ($equipe->getChefEquipe() !== $capitaine->getID()) {
            // Le capitaine n'est pas le chef de l'équipe
            throw new Exception("Le capitaine n'est pas le chef de l'équipe");
        }
    
        if($equipe == null){
            throw new Exception("L'équipe n'existe pas");
        }
    
        // 2. Vérifier que le membre est dans l'équipe
        $membres = $equipe->getMembres(); // Tableau des ID des membres
        if (!in_array($member->getID(), $membres)) {
            // Le membre n'est pas dans l'équipe
            throw new Exception("Le membre n'est pas dans l'équipe");
        }
    
        // Transaction
        $this->conn->autocommit(false);
        try {
            // 3. Retirer l'étudiant de l'équipe dans la base de données
            $sql = "DELETE FROM MembresEquipe WHERE ID_Equipe = ? AND ID_Utilisateur = ?";
            $this->executeQuery($sql, "ii", $equipe->getId(), $member->getID());
    
            // 4. Retirer l'étudiant de l'équipe dans l'objet Equipe
            $key = array_search($member->getID(), $membres);
            if ($key !== false) {
                unset($membres[$key]);
                $equipe->setMembres(array_values($membres)); // Ré-indexer les clés du tableau après avoir retiré un élément
            }
    
            $this->conn->commit();
            return true;
        } catch (Exception $e) {
            $this->conn->rollback();
            throw $e;
        } finally {
            $this->conn->autocommit(true);
        }
    }
    

    /*deleteTeam() : Permet au capitaine de supprimer son équipe.*/
    public function deleteTeam(Equipe $equipe, Etudiant $capitaine)
    {
        // 1. Vérifier que le capitaine est bien le chef de l'équipe
        if ($equipe->getChefEquipe() !== $capitaine->getID()) {
            // Le capitaine n'est pas le chef de l'équipe
            throw new Exception("Le capitaine n'est pas le chef de l'équipe");
        }
    
        if($equipe == null){
            throw new Exception("L'équipe n'existe pas");
        }
    
        // Transaction
        $this->conn->autocommit(false);
        try {
            // 2. Supprimer tous les membres de l'équipe dans la base de données
            $sql = "DELETE FROM MembresEquipe WHERE ID_Equipe = ?";
            $this->executeQuery($sql, "i", $equipe->getId());
    
            // 3. Supprimer l'équipe dans la base de données
            $sql = "DELETE FROM Equipes WHERE ID = ?";
            $this->executeQuery($sql, "i", $equipe->getId());
    
            $this->conn->commit();
            return true;
        } catch (Exception $e) {
            $this->conn->rollback();
            throw $e;
        } finally {
            $this->conn->autocommit(true);
        }
    }
    
    

    /*answerQuestionnaire() : Permet au capitaine de répondre à un questionnaire pour le défi de données. */
    /*On gère les transactions*/
    public function answerQuestionnaire(Equipe $equipe, Questionnaire $questionnaire, array $reponses)
    {
        // 1. Vérifier que le questionnaire est ouvert
        $currentDate = date('Y-m-d');
        if ($questionnaire->getDateDebut() > $currentDate || $questionnaire->getDateFin() < $currentDate) {
            // Le questionnaire n'est pas ouvert
            throw new Exception("Le questionnaire n'est pas ouvert");
        }
    
        if($equipe == null){
            throw new Exception("L'équipe n'existe pas");
        }
    
        // Transaction
        $this->conn->autocommit(false);
    
        try {
            // 2. Pour chaque réponse, insérer une ligne dans la table Reponses
            $sql = "INSERT INTO Reponses (Contenu, Note, ID_Question, ID_Equipe) VALUES (?, ?, ?, ?)";
            
            foreach ($reponses as $reponse) {
                $contenu = $reponse->getContenu();
                $note = $reponse->getNote(); // Note peut être null si la réponse n'a pas encore été notée
                $idQuestion = $reponse->getIdQuestion();
    
                $this->executeQuery($sql, "siii", $contenu, $note, $idQuestion, $equipe->getId());
            }
    
            $this->conn->commit();
            return true;
        } catch (Exception $e) {
            $this->conn->rollback();
            throw $e;
        } finally {
            $this->conn->autocommit(true);
        }
    }
    
    
    
    /*submitCode() : Permet à l'étudiant de soumettre le lien de l'hébergement de son code (par exemple, un lien vers un référentiel GitLab). */
    public function submitCode(ProjetData $projet, $link) 
    {
        // 1. Insérer le lien dans la table Ressources en tant que ressource 'Notebook' pour le projet de l'équipe
        $sql = "INSERT INTO Ressources (URL, Type, ID_Projet) VALUES (?, 'Notebook', ?)";
    
        $this->executeQuery($sql, "si", $link, $projet->getId());
    
        return true;
    }
    
    
    /*viewDataChallenges() : Permet à l'étudiant de voir une liste de tous les défis de données disponibles. */

}
?>
