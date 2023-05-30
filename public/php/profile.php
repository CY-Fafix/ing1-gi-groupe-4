<?php

    
    include('header.php');
    // include('../../src/classes/Etudiant.php');
    require_once __DIR__ . '/../../src/classes/Database.php';

    //Si l'utilisateur n'est pas connecté on ne va pas sur cette page
    if (!isset($_SESSION['user_id'])){
        if ($_SESSION['role']!= 'Etudiant'){  
            echo  
            header('Location: ../index.php');
            exit;
        }
    }

    
    // Créer une nouvelle instance de Database
    $db = new Database();


    // Se connecter à la base de données
    $con = $db->connect();

    //On récupère les info de l'étudiant connécté
    $stmt = $con->prepare('SELECT * FROM Utilisateurs WHERE ID = ?');
    if($stmt === false) {
        die('prepare() failed: ' . htmlspecialchars($this->conn->error));
    }
    $id = $_SESSION['user_id'];
    $stmt->bind_param("i", $id);
    $stmt->execute();
    // Liez le résultat à des variables
    $stmt->bind_result($id, $nom, $prenom, $entreprise, $telephone, $email, $dateDebut, $dateFin, $mdp, $role, $niveau, $ecole, $ville);
    $stmt->fetch();
?>
    
<!DOCTYPE html >
<html xml:lang="fr" lang="fr">
<meta charset="UTF-8">
<head>

    <link href="../css/stylle.css" rel="stylesheet" />
    <link
    href="https://fonts.googleapis.com/css?family=Open+Sans"
    rel="stylesheet" />

    <title>Profil utilisateur</title>
</head>

<body>

    
    <h2>Voici le profil de <?= $nom.' '.$prenom?></h2>
    <div>Quelques informations sur vous : </div>
    <ul>
        <li>Votre id est : <?= $id ?></li>
        <li>Votre mail est : <?= $email ?></li>
        <li>Votre numéro de téléphone est : <?= $telephone ?></li>
        <li>Votre école est : <?= $ecole ?></li>
        <li>Votre niveau d'étude est : <?= $niveau ?></li>
        <li>Votre ville est : <?= $ville ?></li>
        <li>Votre compte a été crée le : <?= $dateDebut ?></li>
    </ul>

</body>
</html>