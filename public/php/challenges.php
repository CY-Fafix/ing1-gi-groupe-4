<?php
session_start();

if (!isset($_SESSION['role']) || $_SESSION['role'] !== 'Etudiant') {
    die("Accès non autorisé. Vous devez être connecté en tant qu'étudiant.");
}

require_once __DIR__ . '/../../src/classes/Database.php';

$db = new Database();
$conn = $db->connect();

$user_id = $_SESSION['user_id'];

$stmt = $conn->prepare("SELECT Projets.ID, Projets.Libelle, Projets.Description, Projets.ImageURL 
                        FROM Projets 
                        JOIN Equipes ON Projets.ID = Equipes.ID_Projet 
                        JOIN MembresEquipe ON Equipes.ID = MembresEquipe.ID_Equipe 
                        WHERE MembresEquipe.ID_Utilisateur = ?");


$stmt->bind_param("i", $user_id);
$stmt->execute();
$result = $stmt->get_result();

$projects = [];
if ($result->num_rows > 0) {
    while ($projectData = $result->fetch_assoc()) {
        $projects[] = $projectData;
    }
}

$stmt->close();
$conn->close();
?>
<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <title>Détails du Projet</title>
    <link href="/public/css/challenges.css" rel="stylesheet">
    <link href="/public/css/header.css" rel="stylesheet">

    <link href="https://fonts.googleapis.com/css?family=Open+Sans" rel="stylesheet">
</head>
<body>
    <header class="custom-header">
        <?php include('./header.php'); ?>
    </header>

    <main>
        <h1>Mes Projets</h1>

        <?php if (empty($projects)) : ?>
            <p>Vous n'êtes inscrit à aucun projet.</p>
        <?php else : ?>
            <?php foreach ($projects as $projectData) : ?>
                <a href="project_details.php?id=<?= $projectData['ID'] ?>" class="project-link">
                    <div class="project">
                        <h2><?= htmlspecialchars($projectData['Libelle']) ?></h2>
                        <p><?= htmlspecialchars($projectData['Description']) ?></p>
                        <img src="<?= htmlspecialchars($projectData['ImageURL']) ?>" alt="Image du projet">
                    </div>
                </a>
            <?php endforeach; ?>
        <?php endif; ?>
    </main>
</body>
</html>
