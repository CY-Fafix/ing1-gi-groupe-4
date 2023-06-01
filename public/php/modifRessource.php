<?php

require_once __DIR__ . '/../../src/controllers/admin_controller.php';
require_once __DIR__ . '/../../src/classes/Database.php';
require_once __DIR__ . '/../../src/classes/Ressource.php';

function getProjectIdForRessource($ressourceId) {
    $db = new Database();

    $db->connect();

    $sql = "SELECT ID_Projet FROM Ressources WHERE ID = $ressourceId";
    $result = $db->query($sql);

    // Vérifier si la requête a retourné des résultats
    if ($result->num_rows > 0) {
        // Si oui, retourner l'ID du projet
        $row = $result->fetch_assoc();
        return $row['ID_Projet'];
    } else {
        // Si non, retourner null
        return null;
    }

    $db->close();
}
//Ce qui nous intéresse c'est plus spécialement le libellé du projet, c'est pour ça que ya null à la fin
function getAllProjects() {
    $db = new Database();
    $db->connect();

    $sql = "SELECT * FROM Projets";
    $result = $db->query($sql);

    $projects = array();
    if ($result->num_rows > 0) {
        while($row = $result->fetch_assoc()) {
            $projects[] = new ProjetData($row["ID"], $row["Libelle"], $row["Description"], $row["ImageURL"], $row["ID_DataChallenge"], null);
        }
    }

    $db->close();

    return $projects;
}


$adminController = new AdminController();

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $projects = getAllProjects();
    if(isset($_POST['delete'])){
        $id = $_POST['delete'];
        $ressource = new Ressource($id, null, null, null, null);
        $adminController->deleteResource($ressource);
    }

    if(isset($_POST['update'])){
        $id = $_POST['update'];
        $ressource = $adminController->getResourceById($id);
        // Ici, vous pouvez modifier les propriétés de la ressource avec des valeurs POST
        $ressource->setUrl($_POST['url']);
        $ressource->setFormat($_POST['type']);
        $adminController->updateResource($ressource);
    }

    if(isset($_POST['create'])){
        $ressource = new Ressource(null, $_POST['url'], $_POST['type'], null, null);
        $projetData = $adminController->getProjectById($_POST['projetId']);
        $adminController->createResource($ressource, $projetData);
    }

}

$ressources = $adminController->getAllResources();

?>
<!DOCTYPE html>
<html>
<head>
    <link href="/public/css/modifRessource.css" rel="stylesheet">
    <link href="/public/css/header.css" rel="stylesheet">
    <link href="https://fonts.googleapis.com/css?family=Open+Sans" rel="stylesheet">
    <title>Gestion des Ressources</title>
</head>
<body>
<header class="custom-header">
        <?php include('./header.php'); ?>
</header>
<main>
<h1>Gestion des Ressources</h1>

<table border="1">
    <tr>
        <th>ID</th>
        <th>URL</th>
        <th>Type</th>
        <th>Projet</th>
        <th>Actions</th>
    </tr>
    <?php foreach ($ressources as $ressource) : ?>
        <form method="POST">
        <tr>
            <td><?= $ressource->getId(); ?></td>
            <td><input type="text" name="url" value="<?= $ressource->getUrl(); ?>"></td>
            <td>
                <select name="type">
                    <option value="Notebook" <?= $ressource->getFormat() == 'Notebook' ? 'selected' : '' ?>>Notebook</option>
                    <option value="PDF" <?= $ressource->getFormat() == 'PDF' ? 'selected' : '' ?>>PDF</option>
                    <option value="HTML" <?= $ressource->getFormat() == 'HTML' ? 'selected' : '' ?>>HTML</option>
                    <option value="Video" <?= $ressource->getFormat() == 'Video' ? 'selected' : '' ?>>Video</option>
                </select>
            </td>
            <td>
                <?= getProjectIdForRessource($ressource->getId()); ?>
            </td>
            <td>
                <button type="submit" name="delete" value="<?= $ressource->getId(); ?>">Supprimer</button>
                <button type="submit" name="update" value="<?= $ressource->getId(); ?>">Mettre à jour</button>
            </td>
        </tr>
        </form>
    <?php endforeach; ?>
</table>

<h2>Ajouter une nouvelle ressource</h2>
<form method="POST">
    <input type="text" name="url" placeholder="URL">
    <select name="type">
        <option value="Notebook">Notebook</option>
        <option value="PDF">PDF</option>
        <option value="HTML">HTML</option>
        <option value="Video">Video</option>
    </select>
    <select name="projetId">
        <?php foreach ($projects as $project) : ?>
            <option value="<?= $project->getId(); ?>"><?= $project->getNom(); ?></option>
        <?php endforeach; ?>
    </select>
    <button type="submit" name="create">Ajouter</button>
</form>
</main>
</body>
</html>
