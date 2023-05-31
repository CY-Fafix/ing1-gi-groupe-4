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
    <header class="custom-header">
        <?php include('./header.php'); ?>
    </header>

    <div class="project-details-container">
        <?php
        //On charge les classes et les observers
        require_once __DIR__ . '/../../src/classes/Database.php';
        require_once __DIR__ . '/../../src/classes/ProjetData.php';
        require_once __DIR__ . '/../../src/classes/Equipe.php';
        require_once __DIR__ . '/../../src/classes/Etudiant.php';
        require_once __DIR__ . '/../../src/controllers/etudiant_controller.php';
        //On initialise la connexion à la BDD
        $db = new Database();
        $conn = $db->connect();

        // ON vérifie si ya bien l'ID dans l'url
        if (isset($_GET['id'])) {
            //Au cas ou on essaye de mettre n'importe quoi dans l'ID
            $projectId = intval($_GET['id']);

            //On recupère tous les projets
            $stmt = $conn->prepare("SELECT * FROM Projets WHERE ID = ?");
            $stmt->bind_param("i", $projectId);
            $stmt->execute();
            $result = $stmt->get_result();

            //Si on obtient un resultat
            if ($result->num_rows > 0) {
                //On recup le resultat sous forme d'un tableau associatif
                $projectData = $result->fetch_assoc();
                ?>
                <h1><?php echo htmlspecialchars($projectData['Libelle']); ?></h1>
                <p><?php echo htmlspecialchars($projectData['Description']); ?></p>
                <img src="<?php echo htmlspecialchars($projectData['ImageURL']); ?>" alt="Image du projet">

                <?php
                //on recupere l'id dans l'url
                $dataChallengeId = $projectData['ID_DataChallenge'];
                //On recupere l'admin du data challenge
                $stmtDataChallenge = $conn->prepare("SELECT ID_Admin FROM DataChallenges WHERE ID = ?");
                $stmtDataChallenge->bind_param("i", $dataChallengeId);
                $stmtDataChallenge->execute();
                $resultDataChallenge = $stmtDataChallenge->get_result();

                //Si on obtient un résultat
                if ($resultDataChallenge->num_rows > 0) {
                    $dataChallengeData = $resultDataChallenge->fetch_assoc();
                    $adminId = $dataChallengeData['ID_Admin'];
                    //On récupère l'utilisateur Admin dans la BDD
                    $stmtAdmin = $conn->prepare("SELECT * FROM Utilisateurs WHERE ID = ?");
                    $stmtAdmin->bind_param("i", $adminId);
                    $stmtAdmin->execute();
                    $resultAdmin = $stmtAdmin->get_result();

                    //Si on obtient bien le résultat
                    if ($resultAdmin->num_rows > 0) {
                        ?>
                        <h2>Contacts du porteur de projet :</h2>
                        <?php
                        //On boucle sur tous les admins du projet opur les afficher et les lignes sont recup dans adminDAta
                        while ($adminData = $resultAdmin->fetch_assoc()) {
                            // On affiche toutes les infos de l'admin
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

                //On récupère les ressources du projet
                $stmtRessources = $conn->prepare("SELECT * FROM Ressources WHERE ID_Projet = ?");
                $stmtRessources->bind_param("i", $projectId);
                $stmtRessources->execute();
                $resultRessources = $stmtRessources->get_result();
                //Si on a un résultat;
                if ($resultRessources->num_rows > 0) {
                    ?>
                    <h2>Ressources spécifiques au projet :</h2>
                    <ul>
                        <?php
                        //On boucle sur les résultats pour les afficher et les lignes sont recup dans ressourceData
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

                /* Partie concernant le cas ou c'est un étudiant qui consulte la page*/
                if (isset($_SESSION['role']) && $_SESSION['role'] === 'Etudiant') {
                    //On créer un Etudiant 
                    $etudiantController = new EtudiantController();
                    $userId = $_SESSION['user_id'];
                    $etudiant = new Etudiant($userId, "", "", "", "", "", "", "", "", "");
                
                    // Vérifier si l'étudiant est déjà inscrit à un projet dans le même Data Challenge
                    $dataChallengeId = $projectData['ID_DataChallenge'];
                    $isRegistered = $etudiantController->isStudentRegisteredInDataBattle($userId, $dataChallengeId);
                
                    if ($isRegistered) {
                        // L'étudiant est déjà inscrit à un projet dans ce Data Challenge
                        ?>
                        <p>Vous êtes déjà inscrit à un projet dans cette DataBattle.</p>
                        <?php
                    } else {
                        // L'étudiant n'est pas encore inscrit à un projet dans ce Data Challenge
                        $equipe = $etudiantController->getTeamByProjectId($userId, $projectId);
                
                        if ($equipe === null) {
                            // L'utilisateur n'est pas inscrit à ce projet, afficher le formulaire d'inscription
                            ?>
                            <form method="POST" action="register_team.php">
                                <input type="hidden" name="project_id" value="<?= $projectId ?>">
                                <label for="team_name">Nom de l'équipe :</label>
                                <input type="text" id="team_name" name="team_name" required>
                                <button type="submit">S'inscrire à la DataBattle</button>
                            </form>
                            <?php
                        }
                         else {
                            // L'utilisateur est déjà inscrit à ce projet
                            ?>
                            <p>Vous êtes déjà inscrit à ce ProjetData.</p>
                            <?php
                        }
                    }
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
