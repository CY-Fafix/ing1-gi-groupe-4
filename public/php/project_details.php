<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <title>Détails du Projet</title>
    <link href="/public/css/project_details.css" rel="stylesheet">
    <link href="/public/css/header.css" rel="stylesheet">

    <link href="https://fonts.googleapis.com/css?family=Open+Sans" rel="stylesheet">
</head>
<body>

    <<header class="custom-header">
        <?php include('./header.php'); ?>
    </header>


    <div class="project-details-container">
        <?php
        require_once __DIR__ . '/../../src/classes/Database.php';
        require_once __DIR__ . '/../../src/classes/ProjetData.php';

        $db = new Database();
        $conn = $db->connect();

        // Check if the project ID is provided in the URL
        if (isset($_GET['id'])) {
            $projectId = intval($_GET['id']);

            $stmt = $conn->prepare("SELECT * FROM Projets WHERE ID = ?");
            $stmt->bind_param("i", $projectId);
            $stmt->execute();
            $result = $stmt->get_result();

            if ($result->num_rows > 0) {
                $projectData = $result->fetch_assoc();
                ?>
                <h1><?php echo htmlspecialchars($projectData['Libelle']); ?></h1>
                <p><?php echo htmlspecialchars($projectData['Description']); ?></p>
                <img src="<?php echo htmlspecialchars($projectData['ImageURL']); ?>" alt="Image du projet">

                <?php
                $dataChallengeId = $projectData['ID_DataChallenge'];
                $stmtDataChallenge = $conn->prepare("SELECT ID_Admin FROM DataChallenges WHERE ID = ?");
                $stmtDataChallenge->bind_param("i", $dataChallengeId);
                $stmtDataChallenge->execute();
                $resultDataChallenge = $stmtDataChallenge->get_result();

                if ($resultDataChallenge->num_rows > 0) {
                    $dataChallengeData = $resultDataChallenge->fetch_assoc();
                    $adminId = $dataChallengeData['ID_Admin'];
                    $stmtAdmin = $conn->prepare("SELECT * FROM Utilisateurs WHERE ID = ?");
                    $stmtAdmin->bind_param("i", $adminId);
                    $stmtAdmin->execute();
                    $resultAdmin = $stmtAdmin->get_result();

                    if ($resultAdmin->num_rows > 0) {
                        ?>
                        <h2>Contacts du porteur de projet :</h2>
                        <?php
                        while ($adminData = $resultAdmin->fetch_assoc()) {
                            ?>
                            <p>Nom : <?php echo htmlspecialchars($adminData['Nom']); ?></p>
                            <p>Prénom : <?php echo htmlspecialchars($adminData['Prenom']); ?></p>
                            <p>Email : <?php echo htmlspecialchars($adminData['Email']); ?></p>
                            <p>Téléphone : <?php echo htmlspecialchars($adminData['Telephone']); ?></p>
                            <?php
                        }
                    } else {
                        ?>
                        <p>Coordonnées du porteur de projet non disponibles</p>
                        <?php
                    }
                } else {
                    ?>
                    <p>Données du DataChallenge non disponibles</p>
                    <?php
                }

                $stmtRessources = $conn->prepare("SELECT * FROM Ressources WHERE ID_Projet = ?");
                $stmtRessources->bind_param("i", $projectId);
                $stmtRessources->execute();
                $resultRessources = $stmtRessources->get_result();

                if ($resultRessources->num_rows > 0) {
                    ?>
                    <h2>Ressources spécifiques au projet :</h2>
                    <ul>
                        <?php
                        while ($ressourceData = $resultRessources->fetch_assoc()) {
                            ?>
                            <li>
                                Type : <?php echo htmlspecialchars($ressourceData['Type']); ?> -
                                URL : <a href="<?php echo htmlspecialchars($ressourceData['URL']); ?>" target="_blank"><?php echo htmlspecialchars($ressourceData['URL']); ?></a>
                            </li>
                            <?php
                        }
                        ?>
                    </ul>
                    <?php
                } else {
                    ?>
                    <p>Aucune ressource spécifique au projet trouvée</p>
                    <?php
                }
            } else {
                ?>
                <p>Projet non trouvé</p>
                <?php
            }
        } else {
            ?>
            <p>ID du projet non fourni</p>
            <?php
        }
        ?>
    </div>
</body>
</html>
