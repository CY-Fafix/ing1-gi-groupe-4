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

    //On récupère les info de l'étudiant connecté
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
    <link href="../css/profile.css" rel="stylesheet" />
    <link
    href="https://fonts.googleapis.com/css?family=Open+Sans"
    rel="stylesheet" />

    <title>Profil utilisateur</title>
</head>

<body>
    <img src="https://img.freepik.com/vecteurs-libre/homme-affaires-caractere-avatar-isole_24877-60111.jpg?w=360" class="Avatar" alt="bonbon miel">


    <div class="card_profil">

        <h2 id="identite"> <?= $prenom.' '.$nom?></h2>

        <div id="info">Informations :</div>
        <div id="Information">
            <div class="row">
                <div class="label_profil">
                    <label>User ID :</label>
                </div>
                <div class="info_profil">
                    <p><?= $id ?></p>
                </div>
            </div>
            <div class="row">
                <div class="label_profil">
                    <label>E-mail :</label>
                </div>
                <div class="info_profil">
                    <p><?= $email ?></p>
                </div>
            </div>
            <div class="row">
                <div class="label_profil">
                    <label>Numéro de téléphone :</label>
                </div>
                <div class="info_profil">
                    <p><?= $telephone ?></p>
                </div>
            </div>
            <div class="row">
                <div class="label_profil">
                    <label>École :</label>
                </div>
                <div class="info_profil">
                    <p><?= $ecole ?></p>
                </div>
            </div>
            <div class="row">
                <div class="label_profil">
                    <label>Niveau d'étude :</label>
                </div>
                <div class="info_profil">
                    <p><?= $niveau ?></p>
                </div>
            </div>
            <div class="row">
                <div class="label_profil">
                    <label>Ville :</label>
                </div>
                <div class="info_profil">
                    <p><?= $ville ?></p>
                </div>
            </div>
            <div class="row">
                <div class="label_profil">
                    <label>Date de création de compte :</label>
                </div>
                <div class="info_profil">
                    <p> <?= $dateDebut ?></p>
                </div>
            </div>

        </div>

        <button onclick="window.location.href = 'update_profile.php';" id="update">Modifier le profil</button>
    </div>

    <?php

	include ('footer.php');
	?> 


</body>
</html>