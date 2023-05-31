<?php
// Inclusion des fichiers nécessaires pour accéder à la base de données et gérer les utilisateurs
require_once __DIR__ . '/../classes/Database.php';
require_once __DIR__ . '/../classes/Utilisateur.php';
require_once __DIR__ . '/../classes/Etudiant.php';
require_once __DIR__ .'/../classes/ProjetData.php';
require_once __DIR__ . '/../classes/Equipe.php';
require_once __DIR__ . '/../classes/Reponse.php';
require_once __DIR__ .'/user_controller.php';

class EtudiantController extends UserController{
    public function __construct() {
        $db = new Database();
        $this->conn = $db->connect();
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
            echo("Probleme du parent");
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
    
        /**
     * Méthode pour enregistrer un étudiant à un DataChallenge en tant que capitaine d'une nouvelle équipe.
     *
     * Préconditions : 
     * - $projetData doit exister dans Projet.
     * - $capitaine doit exister dans les utilisateurs.
     * - $nomEquipe doit être une chaîne de caractères.
     * - L'étudiant représenté par $capitaine ne doit pas être déjà inscrit à un autre projet dans le même DataChallenge.
     * - Le nom d'équipe spécifié par $nomEquipe doit être unique.
     *
     * Postconditions : 
     * - Une nouvelle équipe est créée avec le nom spécifié par $nomEquipe, associée au ProjetData spécifié et avec le capitaine spécifié.
     * - Le capitaine est inscrit en tant que membre de la nouvelle équipe.
     * - L'objet Equipe retourné reflète l'état de la nouvelle équipe dans la base de données (ID correct et liste de membres correcte).
     *
     * @param ProjetData $projetData Le projet de DataChallenge auquel l'étudiant souhaite s'inscrire.
     * @param Etudiant $capitaine L'étudiant qui souhaite s'inscrire en tant que capitaine de la nouvelle équipe.
     * @param string $nomEquipe Le nom souhaité pour la nouvelle équipe.
     * @return Equipe L'objet Equipe qui représente la nouvelle équipe dans la base de données.
     * @throws Exception Si le nom de l'équipe est déjà utilisé ou si l'étudiant est déjà inscrit à un autre projet dans le même DataChallenge.
     */
    public function registerToDataChallenge(ProjetData $projetData, Etudiant $capitaine, $nomEquipe)
    {
        $this->conn->autocommit(false);
        try {
            // 1. Vérifier que le nom de l'équipe n'existe pas déjà
            $sql = "SELECT COUNT(*) FROM Equipes WHERE Nom = ?";
            $stmt = $this->conn->prepare($sql);
            if ($stmt === false) {
                throw new Exception('prepare() failed: ' . htmlspecialchars($this->conn->error));
            }
            $stmt->bind_param("s", $nomEquipe);
            $stmt->execute();
            $stmt->bind_result($count);
            $stmt->fetch();
            $stmt->close();
    
            if ($count > 0) {
                throw new Exception("nom equipe deja utilisé");
            }
    
            // 2. Vérifier que l'étudiant n'est pas déjà inscrit à un autre ProjetData dans ce DefiData
            // Obtenir le DataChallenge du projet
            $sql = "SELECT ID_DataChallenge FROM Projets WHERE ID = ?";
            $stmt = $this->conn->prepare($sql);
            if ($stmt === false) {
                throw new Exception('prepare() failed: ' . htmlspecialchars($this->conn->error));
            }
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
            if ($stmt === false) {
                throw new Exception('prepare() failed: ' . htmlspecialchars($this->conn->error));
            }
            $stmt->bind_param("ii", $capitaine->getID(), $dataChallengeID);
            $stmt->execute();
            $stmt->bind_result($count);
            $stmt->fetch();
            $stmt->close();
    
            if ($count > 0) {
                throw new Exception("deja inscris");
            }
    
            // L'étudiant peut s'inscrire à ce projet
            // 3. Créer une nouvelle équipe
            $equipe = new Equipe(null, $nomEquipe, [], $capitaine->getID());
    
            // Insérer la nouvelle équipe dans la base de données
            $sql = "INSERT INTO Equipes (Nom, ID_Projet, ID_Capitaine) VALUES (?, ?, ?)";
            $stmt = $this->conn->prepare($sql);
            if ($stmt === false) {
                throw new Exception('prepare() failed: ' . htmlspecialchars($this->conn->error));
            }
            $stmt->bind_param("sii", $equipe->getNom(), $projetData->getId(), $equipe->getChefEquipe());
            $stmt->execute();
            $equipeID = $stmt->insert_id;
            $stmt->close();
    
            // Ajouter le capitaine à cette équipe
            $sql = "INSERT INTO MembresEquipe (ID_Equipe, ID_Utilisateur) VALUES (?, ?)";
            $stmt = $this->conn->prepare($sql);
            if ($stmt === false) {
                throw new Exception('prepare() failed: ' . htmlspecialchars($this->conn->error));
            }
            $stmt->bind_param("ii", $equipeID, $capitaine->getID());
            $stmt->execute();
            $stmt->close();
    
            // Mettre à jour l'ID de l'équipe et les membres de l'équipe dans l'objet Equipe
            $equipe->setId($equipeID);
            $equipe->setMembres(array($capitaine->getID()));
    
            $this->conn->commit();
            return $equipe;
        } catch (Exception $e) {
            $this->conn->rollback();
            error_log($e->getMessage()); // Log the error message
            return null; // Return null on exception
        } finally {
            $this->conn->autocommit(true);
        }
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
        if ($equipe->getChefEquipe() != $capitaine->getID()) {
            // Le capitaine n'est pas le chef de l'équipe
            throw new Exception("Le capitaine n'est pas le chef de l'équipe");
        }
    
        // 2. Vérifier que l'équipe existe
        if ($equipe === null) {
            throw new Exception("L'équipe n'existe pas");
        }
    
        // 3. Vérifier que le nouveau membre n'est pas déjà dans l'équipe
        $membres = $equipe->getMembres(); // Tableau des ID des membres
        if (in_array($newMember->getID(), $membres)) {
            // Le nouveau membre est déjà dans l'équipe
            throw new Exception("Le nouveau membre est déjà dans l'équipe");
        }
    
        // Transaction
        $this->conn->autocommit(false);
        try {
            // 4. Ajouter le nouvel étudiant à l'équipe dans la base de données
            $sql = "INSERT INTO MembresEquipe (ID_Equipe, ID_Utilisateur) VALUES (?, ?)";
            $this->executeInsertQuery($sql, "ii", $equipe->getId(), $newMember->getID());
    
            // 5. Ajouter le nouvel étudiant à l'équipe dans l'objet Equipe
            $membres[] = $newMember->getID();
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
        if ($equipe->getChefEquipe() != $capitaine->getID()) {
            throw new Exception("Le capitaine n'est pas le chef de l'équipe");
        }
    
        if ($equipe == null) {
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
            $stmt = $this->conn->prepare($sql);
            if ($stmt === false) {
                throw new Exception('prepare() failed: ' . htmlspecialchars($this->conn->error));
            }
            $id_equipe = $equipe->getId();
            $id_member = $member->getID();
            $stmt->bind_param("ii", $id_equipe, $id_member);
            $stmt->execute();
            $stmt->close();
    
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
    
    public function deleteTeam(Equipe $equipe, Etudiant $capitaine)
    {
        // 1. Vérifier que le capitaine est bien le chef de l'équipe
        if ($equipe->getChefEquipe() != $capitaine->getID()) {
            // Le capitaine n'est pas le chef de l'équipe
            throw new Exception("Le capitaine n'est pas le chef de l'équipe");
        }
    
        if ($equipe == null) {
            throw new Exception("L'équipe n'existe pas");
        }
    
        // Transaction
        $this->conn->autocommit(false);
        try {
            // 2. Supprimer tous les membres de l'équipe dans la base de données
            $sql = "DELETE FROM MembresEquipe WHERE ID_Equipe = ?";
            $stmt = $this->conn->prepare($sql);
            if ($stmt === false) {
                throw new Exception('prepare() failed: ' . htmlspecialchars($this->conn->error));
            }
            $stmt->bind_param("i", $equipe->getId());
            $stmt->execute();
            $stmt->close();
    
            // 3. Supprimer l'équipe dans la base de données
            $sql = "DELETE FROM Equipes WHERE ID = ?";
            $stmt = $this->conn->prepare($sql);
            if ($stmt === false) {
                throw new Exception('prepare() failed: ' . htmlspecialchars($this->conn->error));
            }
            $stmt->bind_param("i", $equipe->getId());
            $stmt->execute();
            $stmt->close();
    
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
    
        if ($equipe == null) {
            throw new Exception("L'équipe n'existe pas");
        }
    
        // Transaction
        $this->conn->autocommit(false);
    
        try {
            // 2. Pour chaque réponse, insérer une ligne dans la table Reponses
            $sql = "INSERT INTO Reponses (Contenu, Note, ID_Question, ID_Equipe) VALUES (?, ?, ?, ?)";
            $stmt = $this->conn->prepare($sql);
            if ($stmt === false) {
                throw new Exception('prepare() failed: ' . htmlspecialchars($this->conn->error));
            }
            
            foreach ($reponses as $reponse) {
                $contenu = $reponse->getContenu();
                $note = $reponse->getNote(); // Note peut être null si la réponse n'a pas encore été notée
                $idQuestion = $reponse->getIdQuestion();
                
                $stmt->bind_param("siii", $contenu, $note, $idQuestion, $equipe->getId());
                $stmt->execute();
            }
            
            $stmt->close();
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
        $stmt = $this->conn->prepare($sql);
        if ($stmt === false) {
            throw new Exception('prepare() failed: ' . htmlspecialchars($this->conn->error));
        }
        $stmt->bind_param("si", $link, $projet->getId());
        $stmt->execute();
        $stmt->close();
    
        return true;
    }

    // Permet d'afficher le questionnaire sur la page qui y est liée
    public function showQuestionnaire($id_Questionnaire){
        try{
        $sql= "SELECT Contenu FROM Questions WHERE id_Questionnaire = ?";
        $stmt = $this->conn->prepare($sql);
            if ($stmt === false) {
                throw new Exception('prepare() failed: ' . htmlspecialchars($this->conn->error));
            }

        $stmt->bind_param("i",$id_Questionnaire);;
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

    public function showDateDebut($id_Questionnaire){
        try{
        $sql= "SELECT DateDebut FROM Questionnaires WHERE ID = ?";
        $stmt = $this->conn->prepare($sql);
            if ($stmt === false) {
                throw new Exception('prepare() failed: ' . htmlspecialchars($this->conn->error));
            }

        $stmt->bind_param("i",$id_Questionnaire);;
        if ($stmt->execute()) {
            $res = $stmt->get_result();
            return $res;
        } else {
            return false;
        }
    } catch (Exception $e) {
        echo "Error: " . $e->getMessage();
    }
}
    public function showDateFin($id_Questionnaire){
        try{
        $sql= "SELECT DateFin FROM Questionnaires WHERE ID = ?";
        $stmt = $this->conn->prepare($sql);
            if ($stmt === false) {
                throw new Exception('prepare() failed: ' . htmlspecialchars($this->conn->error));
            }

        $stmt->bind_param("i",$id_Questionnaire);;
        if ($stmt->execute()) {
            $res = $stmt->get_result();
            return $res;
        } else {
            return false;
        }
    } catch (Exception $e) {
        echo "Error: " . $e->getMessage();
    }
}
    
//Enregistre les analyses du code dans la base de donnée
public function submitAnalyse(AnalyseurCode $analyse) {
    $valReturn = true;
    
    $nbLignes = $analyse->getNbLignes();
    $nbFonc = $analyse->getNbFonc();
    $nbMin = $analyse->getNbMin();
    $nbMax = $analyse->getNbMax();
    $nbMoy = $analyse->getNbMoy();
    $idEquipe = $analyse->getIdEquipe();

    $sql1 = "DELETE FROM AnalysesCode WHERE ID_Equipe = ".$idEquipe;
    $stmt1 = $this->conn->prepare($sql1);
    if ($stmt1 === false) {
        throw new Exception('prepare() failed: ' . htmlspecialchars($this->conn->error));
    }
    if (!$stmt1->execute()) {
        $valReturn = false;
    }
    $stmt1->close();

    $sql2 = "INSERT INTO AnalysesCode (NombreLignes, NombreFonctions, LignesMinFonction, LignesMaxFonction, LignesMoyennesFonction, ID_Equipe)
     VALUES (?, ?, ?, ?, ?, ?)";
   
    $stmt2 = $this->conn->prepare($sql2);
    if ($stmt2 === false) {
        throw new Exception('prepare() failed: ' . htmlspecialchars($this->conn->error));
    }

    
    $stmt2->bind_param("iiiidi", $nbLignes, $nbFonc, $nbMin, $nbMax, $nbMoy, $idEquipe);
    if (!$stmt2->execute()) {
        $valReturn = false;
    }
    $stmt2->close();

    return $valReturn;
}

public function getTeamsByStudentId($etudiant_id) {
    $db = new Database();
    $db->connect();
    $equipes = [];

    // Récupérer les équipes de l'étudiant
    $sql = "SELECT * FROM MembresEquipe WHERE ID_Utilisateur = " . $etudiant_id;
    $result = $db->query($sql);

    // Parcourir les équipes et créer un objet Equipe pour chaque
    while ($row = $result->fetch_assoc()) {
        $equipe_id = $row['ID_Equipe'];

        // Récupérer les détails de l'équipe
        $sql = "SELECT * FROM Equipes WHERE ID = " . $equipe_id;
        $equipeResult = $db->query($sql);

        if ($equipeRow = $equipeResult->fetch_assoc()) {
            $nom = $equipeRow['Nom'];
            $chefEquipe = $equipeRow['ID_Capitaine'];

            // Récupérer les membres de l'équipe
            $sql = "SELECT Utilisateurs.ID, Utilisateurs.Nom, Utilisateurs.Prenom FROM MembresEquipe JOIN Utilisateurs ON MembresEquipe.ID_Utilisateur = Utilisateurs.ID WHERE MembresEquipe.ID_Equipe = " . $equipe_id;
            $membreResult = $db->query($sql);
            $membres = [];
            while ($membreRow = $membreResult->fetch_assoc()) {
                $membres[] = [
                    'id' => $membreRow['ID'],
                    'nom' => $membreRow['Nom'],
                    'prenom' => $membreRow['Prenom']
                ];
            }

            // Créer l'objet Equipe
            $equipe = new Equipe($equipe_id, $nom, $membres, $chefEquipe);

            // Ajouter l'équipe à la liste
            $equipes[] = $equipe;
        }
    }

    $db->close();

    return $equipes;
}
    
    /*viewDataChallenges() : Permet à l'étudiant de voir une liste de tous les défis de données disponibles. */

}
?>
