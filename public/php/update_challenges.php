<?php

require_once __DIR__ . '/../../src/controllers/admin_controller.php';
require_once __DIR__ . '/../../src/classes/DefiData.php';
require_once __DIR__ . '/../../src/classes/Ressource.php';

$adminController = new AdminController();

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    if(isset($_POST['delete'])){
        $id = $_POST['delete'];
        $defiData = new DefiData($id, null, null, null, null, null);
        $adminController->deleteDataChallenge($defiData);
    }

    if(isset($_POST['update'])){
        $id = $_POST['update'];
        $defiData = new DefiData($id, $_POST['libelle'], $_POST['dateDebut'], $_POST['dateFin'], $_POST['idAdmin'], null);
        $adminController->updateDataChallenge($defiData);
    }

    if(isset($_POST['create'])){
        $defiData = new DefiData(null, $_POST['libelle'], $_POST['dateDebut'], $_POST['dateFin'], $_POST['idAdmin'], null);
        $adminController->createDataChallenge($defiData);
    }
}

$challenges = $adminController->getAllDataChallenges();
$admins = $adminController->getAllAdmins();

?>
<!DOCTYPE html>
<html>
<head>
    <link href="/public/css/update_challenges.css" rel="stylesheet">
    <link href="/public/css/header.css" rel="stylesheet">
    <link href="https://fonts.googleapis.com/css?family=Open+Sans" rel="stylesheet">
    <title>Gestion des Data Challenges</title>
</head>
<body>
<header class="custom-header">
        <?php include('./header.php'); ?>
</header>
<main>

<h1>Gestion des Data Challenges</h1>

<table border="1">
    <tr>
        <th>ID</th>
        <th>Libellé</th>
        <th>Date début</th>
        <th>Date fin</th>
        <th>Admin</th>
        <th>Actions</th>
    </tr>
    <?php foreach ($challenges as $challenge) : ?>
        <form method="POST">
        <tr>
            <td><?= $challenge->getId(); ?></td>
            <td><input type="text" name="libelle" value="<?= $challenge->getLibelle(); ?>"></td>
            <td><input type="date" name="dateDebut" value="<?= $challenge->getDateDebut(); ?>"></td>
            <td><input type="date" name="dateFin" value="<?= $challenge->getDateFin(); ?>"></td>
            <td>
                <select name="idAdmin">
                    <?php foreach ($admins as $admin) : ?>
                        <option value="<?= $admin->getId(); ?>" <?= $challenge->getIdAdmin() == $admin->getId() ? 'selected' : '' ?>><?= $admin->getPrenom(); ?></option>
                    <?php endforeach; ?>
                </select>
            </td>
            <td>
                <button type="submit" name="delete" value="<?= $challenge->getId(); ?>">Supprimer</button>
                <button type="submit" name="update" value="<?= $challenge->getId(); ?>">Mettre à jour</button>
            </td>
        </tr>
        </form>
    <?php endforeach; ?>
</table>

<h2>Ajouter un nouveau challenge</h2>
<form method="POST">
    <input type="text" name="libelle" placeholder="Libellé">
    <input type="date" name="dateDebut" placeholder="Date de début">
    <input type="date" name="dateFin" placeholder="Date de fin">
    <select name="idAdmin">
        <?php foreach ($admins as $admin) : ?>
            <option value="<?= $admin->getId(); ?>"><?= $admin->getPrenom(); ?></option>
        <?php endforeach; ?>
    </select>
    <button type="submit" name="create">Créer</button>
</form>
        </main>

</body>
</html>
