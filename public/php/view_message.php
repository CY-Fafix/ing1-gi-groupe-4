<?php
require_once __DIR__ . '/../../src/classes/Database.php';

session_start();

if (!isset($_SESSION['role']) || ($_SESSION['role'] !== 'Admin' && $_SESSION['role'] !== 'Gestionnaire')) {
    header('Location: /public/index.php');
    exit();
}
//Connexion à la BDD
$db = new Database();
$db->connect();
$user_id = $_SESSION['user_id'];
if ($_SESSION['role'] == 'Admin'){
    $sql1 = "SELECT Messages.ID as MessageID, Contenu, DateEnvoi, Nom FROM Messages INNER JOIN Equipes ON Messages.ID_Equipe = Equipes.ID";
    $result1 = $db->query($sql1);
    $messages = $result1->fetch_all(MYSQLI_ASSOC);
    $db->close();
}else {

    // On récupère les messages de l'utilisateur
    $sql = "SELECT Messages.ID as MessageID, Contenu, DateEnvoi, Nom FROM Messages INNER JOIN Equipes ON Messages.ID_Equipe = Equipes.ID WHERE ID_Emetteur = $user_id";
    $result = $db->query($sql);
    $messages = $result->fetch_all(MYSQLI_ASSOC);

    $db->close();
}
?>
<!DOCTYPE html>
<html>
<head>
    <link href="/public/css/view_message.css" rel="stylesheet">
    <link href="/public/css/header.css" rel="stylesheet">
    <link href="https://fonts.googleapis.com/css?family=Open+Sans" rel="stylesheet">
    <title>Messages</title>
</head>
<body>
<header class="custom-header">
        <?php include('./header.php'); ?>
</header>
    <h1>Vos Messages</h1>
    <?php foreach ($messages as $message) : ?>
        <div>
            <h2>Message ID: <?= $message['MessageID'] ?></h2>
            <p>Contenu: <?= $message['Contenu'] ?></p>
            <p>Date d'envoi: <?= $message['DateEnvoi'] ?></p>
            <p>Nom de l'équipe: <?= $message['Nom'] ?></p>
        </div>
    <?php endforeach; ?>
</body>
</html>
