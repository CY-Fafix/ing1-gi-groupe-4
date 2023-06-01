
<?php
class DataChallengeController extends AdminController {

public function __construct() {
    $db = new Database();
    $this->conn = $db->connect();
}

public function getAllProjects() {
    $projects = array();

    $sql = "SELECT p.ID, p.Libelle, p.Description, p.ImageURL, d.ID AS defi_data_id, d.Libelle AS defi_data_libelle
            FROM Projets AS p
            INNER JOIN DataChallenges AS d ON p.ID_DataChallenge = d.ID";

    $result = $this->conn->query($sql);
    if (!$result) {
        echo "Erreur dans la requête : " . $this->conn->error;
        return $projects;
    }

    if ($result->num_rows > 0) {
        while ($row = $result->fetch_assoc()) {
            $defiData = new DefiData($row['defi_data_id'], $row['defi_data_libelle'], '', '', '', array());
            $projetData = new ProjetData($row['ID'], $row['Libelle'], $row['Description'], $row['ImageURL'], array(), array(),$row['ID_Gestionnaire']);
            $projetData->setDefiData($defiData); // associez defiData avec projetData
            $projetData->setDefiData($defiData);
if ($projetData->getDefiData() == null) {
    echo "Erreur: defiData n'a pas été défini correctement";
}

            $projects[] = $projetData;            
        }
    }

    return $projects;
}

public function getProjectById($projectId) {
    $sql = "SELECT p.ID, p.Libelle, p.Description, p.ImageURL, d.ID, p.ID_Gestionnaire AS defi_data_id, d.Libelle AS defi_data_libelle
            FROM Projets AS p
            INNER JOIN DataChallenges AS d ON p.ID_DataChallenge = d.ID
            WHERE p.ID = ?";

    $stmt = $this->conn->prepare($sql);
    $stmt->bind_param("i", $projectId);
    $stmt->execute();
    $result = $stmt->get_result();

    if (!$result) {
        echo "Erreur dans la requête : " . $stmt->error;
        return null;
    }

    if ($result->num_rows > 0) {
        $row = $result->fetch_assoc();
        $defiData = new DefiData($row['defi_data_id'], $row['defi_data_libelle'], '', '', '', array());
        $projetData = new ProjetData($row['ID'], $row['Libelle'], $row['Description'], $row['ImageURL'], array(), array(),$row['ID_Gestionnaire']);
        $projetData->setDefiData($defiData); // associez defiData avec projetData
        $projetData->setDefiData($defiData);
if ($projetData->getDefiData() == null) {
    echo "Erreur: defiData n'a pas été défini correctement";
}

        return $projetData;
        
    }

    return null;
}

public function getDataChallengeById($dataChallengeId) {
    $sql = "SELECT ID, Libelle FROM DataChallenges WHERE ID = ?";
    $stmt = $this->conn->prepare($sql);
    if (!$stmt) {
        // Erreur lors de la préparation de la requête
        throw new Exception("Erreur lors de la préparation de la requête : " . $this->conn->error);
    }

    $stmt->bind_param("i", $dataChallengeId);
    $success = $stmt->execute();
    if (!$success) {
        // Erreur lors de l'exécution de la requête
        throw new Exception("Erreur lors de l'exécution de la requête : " . $stmt->error);
    }

    $result = $stmt->get_result();
    if ($result->num_rows > 0) {
        $row = $result->fetch_assoc();
        $defiData = new DefiData($row['ID'], $row['Libelle'], '', '', '', '', '', array());
        return $defiData;
    }

    return null;
}

}
?>