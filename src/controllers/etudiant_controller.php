<?php
// Inclusion des fichiers nécessaires pour accéder à la base de données et gérer les utilisateurs
require_once '../classes/Database.php';
require_once '../classes/Utilisateur.php';
require_once '../classes/Etudiant.php';
require_once '../classes/ProjetData.php';
require_once '../classes/Equipe.php';
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
        $stmt->bind_param("ssi", $niveau, $ecole, $id);
    
        if ($stmt->execute()) {
            return true;
        } else {
            return false;
        }
    }
    

    /*viewDataChallenges() : Permet à l'étudiant de voir une liste de tous les défis de données disponibles. */



    /* registerToDataChallenge() : Permet à l'étudiant de s'inscrire à un défi de données particulier.*/
    /*Retourne l'équipe de l'objet Equipe de l'étudiant*/
    public function registerToDataChallenge(ProjetData $projetData, Etudiant $etudiant, $nomEquipe) 
    {
        // 1. Vérifier que l'équipe n'existe pas déjà
        $sql = "SELECT COUNT(*) FROM Equipes WHERE Nom = ?";
        $stmt = $this->conn->prepare($sql);
        if($stmt === false) {
            die('prepare() failed: ' . htmlspecialchars($this->conn->error));
        }
        $stmt->bind_param("s", $nomEquipe);
        $stmt->execute();
        $stmt->bind_result($count);
        $stmt->fetch();
        $stmt->close();
        if($count > 0) {
            // Le nom de l'équipe est déjà utilisé
            return false;
        }
    
        // 2. Vérifier que l'étudiant n'est pas déjà inscrit à un autre ProjetData dans ce DefiData
        // Obtenir le DataChallenge du projet
        $sql = "SELECT ID_DataChallenge FROM Projets WHERE ID = ?";
        $stmt = $this->conn->prepare($sql);
        $stmt->bind_param("i", $projetData->getId());
        $stmt->execute();
        $stmt->bind_result($dataChallengeID);
        $stmt->fetch();
        $stmt->close();
    
        // Vérifier si l'étudiant est déjà inscrit à un autre projet dans le même DataChallenge
        $sql = "SELECT COUNT(*) 
        FROM Utilisateurs user
        JOIN MembresEquipe ON user.ID = MembresEquipe.ID_Utilisateur
        JOIN Equipes equipe ON MembresEquipe.ID_Equipe = equipe.ID
        JOIN Projets projet ON equipe.ID_Projet = projet.ID
        WHERE user.ID = ? AND projet.ID_DataChallenge = ?";
        $stmt = $this->conn->prepare($sql);
        $stmt->bind_param("ii", $etudiant->getID(), $dataChallengeID);
        $stmt->execute();
        $stmt->bind_result($count);
        $stmt->fetch();
        $stmt->close();
        if($count > 0) {
            // L'étudiant est déjà inscrit à un autre projet dans ce DataChallenge
            return false;
        }
    
        // L'étudiant peut s'inscrire à ce projet
        // 3. Créer une nouvelle équipe
        $equipe = new Equipe(null, $nomEquipe, [], $etudiant->getID());
    
        // 4. Insérer la nouvelle équipe dans la base de données
        $sql = "INSERT INTO Equipes (Nom, ID_Projet, ID_Capitaine) VALUES (?, ?, ?)";
        $stmt = $this->conn->prepare($sql);
        $stmt->bind_param("sii", $equipe->getNom(), $projetData->getId(), $equipe->getChefEquipe());
        $stmt->execute();
        $equipeID = $stmt->insert_id;  // Obtenir l'ID de l'équipe nouvellement créée
        $stmt->close();
    
        // 5. Ajouter l'étudiant à cette équipe
        $sql = "INSERT INTO MembresEquipe (ID_Equipe, ID_Utilisateur) VALUES (?, ?)";
        $stmt = $this->conn->prepare($sql);
        $stmt->bind_param("ii", $equipeID, $etudiant->getID());
        $result = $stmt->execute();
        $stmt->close();
    
        if ($result) {
            // Mettre à jour l'ID de l'équipe et les membres de l'équipe dans l'objet Equipe
            $equipe->setId($equipeID);
            $equipe->setMembres(array($etudiant->getID()));
            // Retourner l'objet équipe en cas de succès
            return $equipe;
        } else {
            // Quelque chose s'est mal passé
            return false;
        }
    }    

    /* viewProjectDetails() : Permet à l'étudiant de voir les détails du projet de défi de données auquel il est inscrit.*/

    /*createTeam() : Permet à l'étudiant (en tant que capitaine) de créer une équipe pour le défi de données. */
    public function createTeam(Etudiant $capitaine, ProjetData $projet, $nomEquipe) 
    {
        // 1. Créer une nouvelle équipe pour ce défi de données.
        $equipe = new Equipe(null, $nomEquipe, [], $capitaine->getID());
    
        // 2. Insérer la nouvelle équipe dans la base de données
        $sql = "INSERT INTO Equipes (Nom, ID_Projet, ID_Capitaine) VALUES (?, ?, ?)";
        $stmt = $this->conn->prepare($sql);
        if($stmt === false) {
            throw new Exception('Erreur de préparation de la requête: ' . $this->conn->error);
        }
        $stmt->bind_param("sii", $equipe->getNom(), $projet->getId(), $equipe->getChefEquipe());
        $stmt->execute();
        $equipeID = $this->conn->insert_id;  // Obtenir l'ID de l'équipe nouvellement créée
        $stmt->close();
    
        // 3. Mettre à jour l'ID de l'équipe dans l'objet Equipe
        $equipe->setId($equipeID);
      
        // 4. Ajouter le capitaine à l'équipe
        $this->addMemberToTeam($equipe, $capitaine, $capitaine);
    
        return $equipe;
    }
    

    /*addMemberToTeam() : Permet au capitaine d'ajouter des membres à son équipe. */
    public function addMemberToTeam(Equipe $equipe, Etudiant $capitaine, Etudiant $newMember)
    {
        // 1. Vérifier que le capitaine est bien le chef de l'équipe
        if ($equipe->getChefEquipe() !== $capitaine->getID()) {
            // Le capitaine n'est pas le chef de l'équipe
            return false;
        }
    
        // 2. Vérifier que le nouveau membre n'est pas déjà dans l'équipe
        $membres = $equipe->getMembres(); // Tableau des ID des membres
        if (in_array($newMember->getID(), $membres)) {
            // Le nouveau membre est déjà dans l'équipe
            return false;
        }
    
        // 3. Ajouter le nouvel étudiant à l'équipe dans la base de données
        $sql = "INSERT INTO MembresEquipe (ID_Equipe, ID_Utilisateur) VALUES (?, ?)";
        $stmt = $this->conn->prepare($sql);
        if ($stmt === false) {
            throw new Exception('Erreur de préparation de la requête: ' . $this->conn->error);
        }
        $stmt->bind_param("ii", $equipe->getId(), $newMember->getID());
        $stmt->execute();
        $stmt->close();
    
        // 4. Ajouter le nouvel étudiant à l'équipe dans l'objet Equipe
        array_push($membres, $newMember->getID());
        $equipe->setMembres($membres);
    
        return true;
    }
    
    

    /*removeMemberFromTeam() : Permet au capitaine de retirer un membre de son équipe. */
    public function removeMemberFromTeam(Equipe $equipe, Etudiant $capitaine, Etudiant $member)
    {  
        // 1. Vérifier que le capitaine est bien le chef de l'équipe
        if ($equipe->getChefEquipe() !== $capitaine->getID()) {
            // Le capitaine n'est pas le chef de l'équipe
            return false;
        }

        // 2. Vérifier que le membre est dans l'équipe
        $membres = $equipe->getMembres(); // Tableau des ID des membres
        if (!in_array($member->getID(), $membres)) {
            // Le membre n'est pas dans l'équipe
            return false;
        }

        // 3. Retirer l'étudiant de l'équipe dans la base de données
        $sql = "DELETE FROM MembresEquipe WHERE ID_Equipe = ? AND ID_Utilisateur = ?";
        $stmt = $this->conn->prepare($sql);
        if ($stmt === false) {
            throw new Exception('Erreur de préparation de la requête: ' . $this->conn->error);
        }
        $stmt->bind_param("ii", $equipe->getId(), $member->getID());
        $stmt->execute();
        $stmt->close();

        // 4. Retirer l'étudiant de l'équipe dans l'objet Equipe
        $key = array_search($member->getID(), $membres);
        if ($key !== false) {
            unset($membres[$key]);
            $equipe->setMembres(array_values($membres)); // Ré-indexer les clés du tableau après avoir retiré un élément
        }

        return true;
    }

    /*deleteTeam() : Permet au capitaine de supprimer son équipe.*/
    public function deleteTeam(Equipe $equipe, Etudiant $capitaine)
    {
        // 1. Vérifier que le capitaine est bien le chef de l'équipe
        if ($equipe->getChefEquipe() !== $capitaine->getID()) {
            // Le capitaine n'est pas le chef de l'équipe
            return false;
        }
    
        // 2. Supprimer tous les membres de l'équipe dans la base de données
        $sql = "DELETE FROM MembresEquipe WHERE ID_Equipe = ?";
        $stmt = $this->conn->prepare($sql);
        if ($stmt === false) {
            throw new Exception('Erreur de préparation de la requête: ' . $this->conn->error);
        }
        $stmt->bind_param("i", $equipe->getId());
        $stmt->execute();
        $stmt->close();
    
        // 3. Supprimer l'équipe dans la base de données
        $sql = "DELETE FROM Equipes WHERE ID = ?";
        $stmt = $this->conn->prepare($sql);
        if ($stmt === false) {
            throw new Exception('Erreur de préparation de la requête: ' . $this->conn->error);
        }
        $stmt->bind_param("i", $equipe->getId());
        $stmt->execute();
        $stmt->close();
    
        return true;
    }
    

    /*answerQuestionnaire() : Permet au capitaine de répondre à un questionnaire pour le défi de données. */
    public function answerQuestionnaire(Equipe $equipe, Questionnaire $questionnaire, array $reponses)
    {
        // 1. Vérifier que le questionnaire est ouvert
        $currentDate = date('Y-m-d');
        if ($questionnaire->getDateDebut() > $currentDate || $questionnaire->getDateFin() < $currentDate) {
            // Le questionnaire n'est pas ouvert
            return false;
        }
    
        // 2. Pour chaque réponse, insérer une ligne dans la table Reponses
        $sql = "INSERT INTO Reponses (Contenu, Note, ID_Question, ID_Equipe) VALUES (?, ?, ?, ?)";
        $stmt = $this->conn->prepare($sql);
        if ($stmt === false) {
            throw new Exception('Erreur de préparation de la requête: ' . $this->conn->error);
        }
    
        foreach ($reponses as $reponse) {
            $contenu = $reponse['contenu'];
            $note = $reponse['note']; // Note peut être null si la réponse n'a pas encore été notée
            $idQuestion = $reponse['idQuestion'];
            
            $stmt->bind_param("siii", $contenu, $note, $idQuestion, $equipe->getId());
            $stmt->execute();
        }
    
        $stmt->close();
    
        return true;
    }
    
    /*submitCode() : Permet à l'étudiant de soumettre le lien de l'hébergement de son code (par exemple, un lien vers un référentiel GitLab). */
    public function submitCode(Equipe $equipe, ProjetData $projet, $link) 
    {
        // 1. Insérer le lien dans la table Ressources en tant que ressource 'Notebook' pour le projet de l'équipe
        $sql = "INSERT INTO Ressources (URL, Type, ID_Projet) VALUES (?, 'Notebook', ?)";
        $stmt = $this->conn->prepare($sql);
        if ($stmt === false) {
            throw new Exception('Erreur de préparation de la requête: ' . $this->conn->error);
        }
        $stmt->bind_param("si", $link, $projet->getId());
        $stmt->execute();
        $stmt->close();
        return true;
    }
    
    
}
?>
