<?php
error_reporting(E_ALL);
require_once __DIR__ . '/../../src/classes/Database.php';
require_once __DIR__ . '/../../src/classes/ProjetData.php';
require_once __DIR__ . '/../../src/classes/DefiData.php';
require_once __DIR__ . '/../../src/classes/Equipe.php';
require_once __DIR__ . '/../../src/classes/Etudiant.php';
require_once __DIR__ . '/../../src/controllers/admin_controller.php';
require_once __DIR__ . '/../../src/controllers/DataChallengeController.php';

session_start();
if (!isset($_SESSION['role']) || $_SESSION['role'] !== 'Admin') {
    header('Location: /public/index.php');
    exit();
}

$adminController = new AdminController();
$dataChallengeController = new DataChallengeController();
$projets = $dataChallengeController->getAllProjects();
$dataChallenges = $dataChallengeController->getAllDataChallenges(); // Récupération de tous les Data Challenges

if (isset($_POST['nom'], $_POST['description'], $_POST['image_url'], $_POST['data_challenge_id'])) {
    $nom = $_POST['nom'];
    $description = $_POST['description'];
    $imageURL = $_POST['image_url'];
    $dataChallengeId = $_POST['data_challenge_id'];

    $dataChallenge = $dataChallengeController->getDataChallengeById($dataChallengeId);
    $projetData = new ProjetData(null, $nom, $description, $imageURL, null, null); // ID mis à 0 car il sera généré par la base de données

    $adminController->createProjectForDataChallenge($projetData, $dataChallenge);

    header('Location: projets.php');
    exit();
} 
if (isset($_GET['edit'])) {
    $projetId = $_GET['edit'];

    $projet = null;
    foreach ($projets as $projetData) {
        if ($projetData->getId() === $projetId) {
            $projet = $projetData;
            break;
        }
    }

    if ($projet) {
        if (isset($_POST['edit_project'])) {
            $nom = $_POST['nom'];
            $description = $_POST['description'];
            $imageURL = $_POST['image_url'];
            
            // Récupère l'ID du Data Challenge pour le projet
            $dataChallengeId = $dataChallengeController->getDataChallengeIdForProject($projet->getId());
    
            // Récupère l'objet DefiData correspondant
            $dataChallenge = $dataChallengeController->getDataChallengeById($dataChallengeId);
    
            // Mettre à jour les informations du projet
            $projet->setNom($nom);
            $projet->setDescription($description);
            $projet->setImage($imageURL);
    
            // Mettre à jour le projet dans la base de données
            $adminController->updateProjectForDataChallenge($projet, $dataChallenge);
    
            header('Location: projets.php');
            exit();
        }
    }
    
} elseif (isset($_GET['delete'])) {
    $projetId = $_GET['delete'];

    $projet = $dataChallengeController->getProjectById($projetId);
    $adminController->deleteProjectForDataChallenge($projet);

    header('Location: projets.php');
    exit();
}
?>


<!DOCTYPE html>
<html>
<head>
    <link href="/public/css/projets.css" rel="stylesheet">
    <link href="/public/css/header.css" rel="stylesheet">
    <title>Projets</title>
</head>
<body>
    <main>
    <header class="custom-header">
        <?php include('./header.php'); ?>
    </header>
    <h1>Liste des Projets</h1>

    <table>
    <tr>
        <th>ID</th>
        <th>Nom</th>
        <th>Description</th>
        <th>Image</th>
        <th>Data Challenge</th>
        <th>Actions</th>
    </tr>
    <?php foreach ($projets as $projet) : ?>
        <tr>
            <td><?= $projet->getId() ?></td>
            <td>
                <form method="POST" action="projets.php?edit=<?= $projet->getId() ?>">
                    <input type="text" name="nom" value="<?= $projet->getNom() ?>">
            </td>
            <td><textarea name="description"><?= $projet->getDescription() ?></textarea></td>
            <td><input type="text" name="image_url" value="<?= $projet->getImage() ?>"></td>
            <td>
    <?php
        $dataChallengeId = $dataChallengeController->getDataChallengeIdForProject($projet->getId());
        $dataChallenge = $dataChallengeController->getDataChallengeById($dataChallengeId);

        if ($dataChallenge) {
            echo $dataChallenge->getLibelle();
        } else {
            echo "Pas de défi associé";
        }
    ?>
</td>
            <td>
                    <button type="submit" name="edit_project">Modifier</button>
                </form>
                <button onclick="window.location.href = 'projets.php?delete=<?= $projet->getId() ?>'">Supprimer</button>
            </td>
        </tr>
    <?php endforeach; ?>
    </table>
    <h2>Ajouter un Projet</h2>
<form method="POST" action="projets.php">
    <label for="nom">Nom :</label>
    <input type="text" id="nom" name="nom" required><br>
    <label for="description">Description :</label>
    <textarea id="description" name="description" required></textarea><br>
    <label for="image_url">Image URL :</label>
    <input type="text" id="image_url" name="image_url" required><br>
    <label for="data_challenge_id">Data Challenge :</label>
    <select id="data_challenge_id" name="data_challenge_id" required>
        <?php foreach ($dataChallenges as $dataChallenge) : ?>
            <option value="<?= $dataChallenge->getId() ?>"><?= $dataChallenge->getLibelle() ?></option>
        <?php endforeach; ?>
    </select><br>
    <button type="submit">Ajouter</button>
</form>
    </main>
</body>
</html>
