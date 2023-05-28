<?php
// Inclusion des fichiers nécessaires pour accéder à la base de données et gérer les utilisateurs
require_once '../classes/Database.php';
require_once '../classes/Utilisateur.php';
require_once 'user_controller.php';
require_once 'gestionnaire_controller.php';


class AdminController extends GestionnaireController{
    public function __construct() {
        $db = new Database();
        $this->conn = $db->connect();
        if ($this->conn) {
            echo "Database connection successful.";
        } else {
            echo "Database connection failed.";
        }
    }

    public function createProjectForDataChallenge(ProjetData $projetData, DefiData $defiData) {
        // Vérifier si le DefiData existe dans la base de données
        $sqlCheck = "SELECT ID FROM DataChallenges WHERE ID = ?";
        if ($stmtCheck = $this->conn->prepare($sqlCheck)) {
            $stmtCheck->bind_param("i", $defiData->getId());
            $stmtCheck->execute();
            $resultCheck = $stmtCheck->get_result();
            if ($resultCheck->num_rows == 0) {
                throw new Exception("Le défi de données fourni n'existe pas dans la base de données.");
            }
        } else {
            throw new Exception("Erreur lors de la préparation de la requête de vérification : " . $this->conn->error);
        }
        
        // Si le DefiData existe, alors créer le ProjetData
        $sql = "INSERT INTO Projets (Libelle, Description, ImageURL, ID_DataChallenge) VALUES (?, ?, ?, ?)";
        if ($stmt = $this->conn->prepare($sql)) {
            $stmt->bind_param("sssi", $projetData->getNom(), $projetData->getDescription(), $projetData->getImage(), $defiData->getId());
            if (!$stmt->execute()) {
                throw new Exception("Erreur lors de l'execution de la requête (createProject) : " . $stmt->error);
            }
        } else {
            throw new Exception("Erreur lors de la préparation de la requête : " . $this->conn->error);
        }
    }
    
    
    public function updateProjectForDataChallenge(ProjetData $projetData, DefiData $defiData) {
        $sql = "UPDATE Projets SET Libelle = ?, Description = ?, ImageURL = ?, ID_DataChallenge = ? WHERE ID = ?";
        if ($stmt = $this->conn->prepare($sql)) {
            $stmt->bind_param("sssii", $projetData->getNom(), $projetData->getDescription(), $projetData->getImage(), $defiData->getId(), $projetData->getId());
            if (!$stmt->execute()) {
                throw new Exception("Erreur lors de l'execution de la requête : " . $stmt->error);
            }
        } else {
            throw new Exception("Erreur lors de la préparation de la requête : " . $this->conn->error);
        }
    }
        
        /**
     * Supprime un projet et toutes ses données associées (équipes, membres d'équipe, messages, réponses et analyses de code).
     * 
     * Préconditions :
     * - L'objet ProjetData fourni doit être valide et doit exister dans la base de données.
     * - Les ID de projet et d'équipe dans les tables de la base de données doivent correspondre à ceux fournis dans l'objet ProjetData.
     * 
     * Postconditions :
     * - Toutes les données associées au projet (équipes, membres d'équipe, messages, réponses et analyses de code) sont supprimées de la base de données.
     * - Le projet lui-même est supprimé de la base de données.
     * - En cas d'erreur, une exception est lancée.
     *
     * @param ProjetData $projetData - Les données du projet à supprimer
     * @throws Exception - En cas d'erreur lors de la suppression des données
     */
    public function deleteProjectForDataChallenge(ProjetData $projetData) {
        // Récupère d'abord les équipes associées au projet
        $sql = "SELECT ID FROM Equipes WHERE ID_Projet = ?";
        if ($stmt = $this->conn->prepare($sql)) {
            $stmt->bind_param("i", $projetData->getId());
    
            if ($stmt->execute()) {
                $result = $stmt->get_result();
                $stmt->close(); // Fermer le statement précédent
    
                while ($row = $result->fetch_assoc()) {
                    $teamId = $row['ID'];
    
                    // Supprime d'abord les analyses de code associées à l'équipe
                    $sql = "DELETE FROM AnalysesCode WHERE ID_Equipe = ?";
                    if ($stmt = $this->conn->prepare($sql)) {
                        $stmt->bind_param("i", $teamId);
                        if (!$stmt->execute()) {
                            throw new Exception("Erreur lors de la suppression des analyses de code associées à l'équipe : " . $stmt->error);
                        }
                        $stmt->close(); // Fermer le statement précédent
                    } else {
                        echo "Erreur de préparation de la requête : " . $this->conn->error;
                        throw new Exception("Erreur lors de la préparation de la requête pour supprimer les analyses de code : " . $this->conn->error);
                    }
    
                    // Supprime ensuite les membres de l'équipe
                    $sql = "DELETE FROM MembresEquipe WHERE ID_Equipe = ?";
                    if ($stmt = $this->conn->prepare($sql)) {
                        $stmt->bind_param("i", $teamId);
                        if (!$stmt->execute()) {
                            throw new Exception("Erreur lors de la suppression des membres de l'équipe associés à l'équipe : " . $stmt->error);
                        }
                        $stmt->close(); // Fermer le statement précédent
                    } else {
                        echo "Erreur de préparation de la requête : " . $this->conn->error;
                        throw new Exception("Erreur lors de la préparation de la requête pour supprimer les membres de l'équipe : " . $this->conn->error);
                    }
    
                    // Supprime les messages associés à l'équipe
                    $sql = "DELETE FROM Messages WHERE ID_Equipe = ?";
                    if ($stmt = $this->conn->prepare($sql)) {
                        $stmt->bind_param("i", $teamId);
                        if (!$stmt->execute()) {
                            throw new Exception("Erreur lors de la suppression des messages associés à l'équipe : " . $stmt->error);
                        }
                        $stmt->close(); // Fermer le statement précédent
                    } else {
                        echo "Erreur de préparation de la requête : " . $this->conn->error;
                        throw new Exception("Erreur lors de la préparation de la requête pour supprimer les messages : " . $this->conn->error);
                    }
    
                    // Supprime les réponses associées à l'équipe
                    $sql = "DELETE FROM Reponses WHERE ID_Equipe = ?";
                    if ($stmt = $this->conn->prepare($sql)) {
                        $stmt->bind_param("i", $teamId);
                        if (!$stmt->execute()) {
                            throw new Exception("Erreur lors de la suppression des réponses associées à l'équipe : " . $stmt->error);
                        }
                        $stmt->close(); // Fermer le statement précédent
                    } else {
                        echo "Erreur de préparation de la requête : " . $this->conn->error;
                        throw new Exception("Erreur lors de la préparation de la requête pour supprimer les réponses : " . $this->conn->error);
                    }
                }
    
                // Supprime les équipes elles-mêmes
                $sql = "DELETE FROM Equipes WHERE ID_Projet = ?";
                if ($stmt = $this->conn->prepare($sql)) {
                    $stmt->bind_param("i", $projetData->getId());
                    if (!$stmt->execute()) {
                        throw new Exception("Erreur lors de la suppression des équipes associées au projet : " . $stmt->error);
                    }
                    $stmt->close(); // Fermer le statement précédent
                } else {
                    echo "Erreur de préparation de la requête : " . $this->conn->error;
                    throw new Exception("Erreur lors de la préparation de la requête pour supprimer les équipes : " . $this->conn->error);
                }
            } else {
                echo "Erreur de préparation de la requête : " . $this->conn->error;
                throw new Exception("Erreur lors de la récupération des équipes associées au projet : " . $stmt->error);
            }
        } else {
            echo "Erreur de préparation de la requête : " . $this->conn->error;
            throw new Exception("Erreur lors de la préparation de la requête pour récupérer les équipes : " . $this->conn->error);
        }
    
        // Enfin, supprime le projet lui-même
        $sql = "DELETE FROM Projets WHERE ID = ?";
        if ($stmt = $this->conn->prepare($sql)) {
            $stmt->bind_param("i", $projetData->getId());
            if (!$stmt->execute()) {
                throw new Exception("Erreur lors de l'exécution de la requête (deleteProject) : " . $stmt->error);
            }
            $stmt->close(); // Fermer le statement précédent
        } else {
            echo "Erreur de préparation de la requête : " . $this->conn->error;
            throw new Exception("Erreur lors de la préparation de la requête pour supprimer le projet : " . $this->conn->error);
        }
    }
    

    public function createDataChallenge(DefiData $defiData) {
        $sql = "INSERT INTO DataChallenges (Libelle, DateDebut, DateFin, ID_Admin) VALUES (?, ?, ?, ?)";
        if ($stmt = $this->conn->prepare($sql)) {
            $stmt->bind_param("sssi", $defiData->getLibelle(), $defiData->getDateDebut(), $defiData->getDateFin(), $defiData->getIdAdmin());
            if (!$stmt->execute()) {
                throw new Exception("Erreur lors de l'execution de la requête : " . $stmt->error);
            }
        } else {
            throw new Exception("Erreur lors de la préparation de la requête : " . $this->conn->error);
        }
    }
    
    public function updateDataChallenge(DefiData $defiData) {
        $sql = "UPDATE DataChallenges SET Libelle = ?, DateDebut = ?, DateFin = ?, ID_Admin = ? WHERE ID = ?";
        if ($stmt = $this->conn->prepare($sql)) {
            $stmt->bind_param("sssii", $defiData->getLibelle(), $defiData->getDateDebut(), $defiData->getDateFin(), $defiData->getIdAdmin(), $defiData->getId());
            if (!$stmt->execute()) {
                throw new Exception("Erreur lors de l'execution de la requête : " . $stmt->error);
            }
        } else {
            throw new Exception("Erreur lors de la préparation de la requête : " . $this->conn->error);
        }
    }
    
    public function deleteDataChallenge(DefiData $defiData) {
        // Récupère d'abord les projets associés au défi
        $sql = "SELECT ID FROM Projets WHERE ID_DataChallenge = ?";
        if ($stmt = $this->conn->prepare($sql)) {
            $stmt->bind_param("i", $defiData->getId());
            if ($stmt->execute()) {
                $result = $stmt->get_result();
                while ($row = $result->fetch_assoc()) {
                    $projetId = $row['ID'];
    
                    // Crée un nouvel objet ProjetData pour chaque projet trouvé
                    $projetData = new ProjetData(null, null, null, null, null, null);
                    $projetData->setId($projetId);
                    
                    // Utilise la méthode deleteProjectForDataChallenge pour supprimer chaque projet et ses dépendances
                    $this->deleteProjectForDataChallenge($projetData);
                }
            } else {
                throw new Exception("Erreur lors de la récupération des projets associés au défi : " . $stmt->error);
            }
        } else {
            throw new Exception("Erreur lors de la préparation de la requête pour récupérer les projets : " . $this->conn->error);
        }
    
        // Enfin, supprime le défi lui-même
        $sql = "DELETE FROM DataChallenges WHERE ID = ?";
        if ($stmt = $this->conn->prepare($sql)) {
            $stmt->bind_param("i", $defiData->getId());
            if (!$stmt->execute()) {
                throw new Exception("Erreur lors de l'execution de la requête (deleteDataChallenge) : " . $stmt->error);
            }
        } else {
            throw new Exception("Erreur lors de la préparation de la requête : " . $this->conn->error);
        }
    }
    

    public function createResource(Ressource $ressource, ProjetData $projetData) {
        $allowedTypes = ['Notebook', 'PDF', 'HTML', 'Video', 'etc'];
        $type = $ressource->getFormat();
    
        if (!in_array($type, $allowedTypes)) {
            throw new Exception("Format de ressource non valide : " . $type);
        }
    
        $sql = "INSERT INTO Ressources (URL, Type, ID_Projet) VALUES (?, ?, ?)";
        if ($stmt = $this->conn->prepare($sql)) {
            $stmt->bind_param("ssi", $ressource->getUrl(), $type, $projetData->getId());
            if (!$stmt->execute()) {
                throw new Exception("Erreur lors de l'execution de la requête : (createRessource) " . $stmt->error);
            }
        } else {
            throw new Exception("Erreur lors de la préparation de la requête : " . $this->conn->error);
        }
    }
    
    
    public function updateResource(Ressource $ressource) {
        $sql = "UPDATE Ressources SET URL = ?, Type = ? WHERE ID = ?";
        if ($stmt = $this->conn->prepare($sql)) {
            $stmt->bind_param("ssi", $ressource->getUrl(), $ressource->getFormat(), $ressource->getId());
            if (!$stmt->execute()) {
                throw new Exception("Erreur lors de l'execution de la requête : " . $stmt->error);
            }
        } else {
            throw new Exception("Erreur lors de la préparation de la requête : " . $this->conn->error);
        }
    }
    
    public function deleteResource(Ressource $ressource) {
        $sql = "DELETE FROM Ressources WHERE ID = ?";
        if ($stmt = $this->conn->prepare($sql)) {
            $stmt->bind_param("i", $ressource->getId());
            if (!$stmt->execute()) {
                throw new Exception("Erreur lors de l'execution de la requête : " . $stmt->error);
            }
        } else {
            throw new Exception("Erreur lors de la préparation de la requête : " . $this->conn->error);
        }
    }
    
}
?>