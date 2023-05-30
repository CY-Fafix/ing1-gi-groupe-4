<?php
session_start();

require_once __DIR__ . '/../../src/classes/Database.php';
require_once __DIR__ . '/../../src/classes/Utilisateur.php';
require_once __DIR__ . '/../../src/classes/Etudiant.php';
require_once __DIR__ . '/../../src/classes/ProjetData.php';
require_once __DIR__ . '/../../src/classes/Equipe.php';
require_once __DIR__ . '/../../src/classes/Reponse.php';
require_once __DIR__ .'/../../src/controllers/user_controller.php';
require_once __DIR__ .'/../../src/controllers/etudiant_controller.php';

// Vérifier si l'utilisateur est connecté en tant qu'étudiant
if (!isset($_SESSION['role']) || $_SESSION['role'] !== 'Etudiant') {
    die("Accès non autorisé. Vous devez être connecté en tant qu'étudiant.");
}

// initialisation du contrôleur
$etudiantController = new EtudiantController();

// récupération des équipes de l'étudiant
$equipes = $etudiantController->getTeamsByStudentId($_SESSION['user_id']);

?>
<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <title>Mes Équipes</title>
</head>
<body>
    <!-- !!! Inclure le header ici -->

    <main>
        <h1>Mes Équipes</h1>

        <?php if (empty($equipes)) : ?>
            <p>Vous n'avez aucune équipe. <a href="create_team.php">Créer une équipe</a></p>
        <?php else : ?>
            <?php foreach ($equipes as $equipe) : ?>
                <h2>Nom de l'équipe: <?= $equipe->getNom() ?></h2>
                <p>Membres de l'équipe: </p>
                <ul>
                <?php foreach ($equipe->getMembres() as $membre) : ?>
                    <li><?= $membre['prenom'] . ' ' . $membre['nom'] ?></li>
                <?php endforeach; ?>
                </ul>
                <form method="POST" action="add_member.php">
                    <input type="hidden" name="team_id" value="<?= $equipe->getId() ?>">
                    <input type="email" name="email" required>
                    <button type="submit">Ajouter un membre</button>
                </form>
                <form method="POST" action="delete_member.php">
                    <input type="hidden" name="team_id" value="<?= $equipe->getId() ?>">
                    <select name="member_id">
                        <?php foreach ($equipe->getMembres() as $membre) : ?>
                            <option value="<?= $membre['id'] ?>"><?= $membre['prenom'] . ' ' . $membre['nom'] ?></option>
                        <?php endforeach; ?>
                    </select>
                    <button type="submit">Supprimer un membre</button>
                </form>
                <form method="POST" action="delete_team.php">
                    <input type="hidden" name="team_id" value="<?= $equipe->getId() ?>">
                    <button type="submit">Supprimer l'équipe</button>
                </form>
            <?php endforeach; ?>
        <?php endif; ?>
    </main>
</body>
</html>
