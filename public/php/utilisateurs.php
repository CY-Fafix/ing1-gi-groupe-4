<?php

    
    include('header.php');
    require_once __DIR__ . '/../../src/classes/Database.php';

    //Si l'utilisateur n'est pas connecté ou n'est pas admin il ne va pas sur cette page
    if (!isset($_SESSION['user_id'])){
        if ($_SESSION['role']!= 'Admin'){  

            header('Location: ../index.php');
            exit;
        }
    }


    // Créer une nouvelle instance de Database
    $db = new Database();


    // Se connecter à la base de données
    $con = $db->connect();
 
    //On récupère les info de l'étudiant connecté
    $stmt = $con->prepare('SELECT * FROM Utilisateurs WHERE ID <> ?');

    

    if($stmt === false) {

         die('prepare() failed: ' . htmlspecialchars($this->conn->error));

    }

    $id = $_SESSION['user_id'];
    $stmt->bind_param("i", $id);
    $stmt->execute();

    $resultSet = $stmt->get_result();
    $data = $resultSet->fetch_all(MYSQLI_ASSOC);

    // Liez le résultat à des variables
    //$stmt->bind_result($id, $nom, $prenom, $entreprise, $telephone, $email, $dateDebut, $dateFin, $mdp, $role, $niveau, $ecole, $ville);

   

    
    
 


?>


<!DOCTYPE html >
<html xml:lang="fr" lang="fr">
<meta charset="UTF-8">
    <head>

        <link href="../css/stylle.css" rel="stylesheet" />
        <link href="../css/utilisateurs.css" rel="stylesheet" />
        <link
        href="https://fonts.googleapis.com/css?family=Open+Sans"
        rel="stylesheet" />

        <title>Profil utilisateur</title>
    </head>

    <body>      

        <h2>Utilisateurs</h2>

        <div id="main">
        <?php
            $sortedData = array();
            foreach ($data as $ap) {
                $role = $ap['Role'];
                if (!isset($sortedData[$role])) {
                    $sortedData[$role] = array();
                }
                $sortedData[$role][] = $ap;
            }

            ksort($sortedData);

            $currentRole = null;

            foreach ($sortedData as $role => $users) {
                foreach ($users as $ap) {
                    if ($currentRole !== $ap['Role']) {
                        if ($currentRole !== null) {
                            echo '</div>'; // Fermer la ligne précédente
                        }
                        echo '<p class="role">'.$ap['Role'].'</p>';
                        echo '<div class="user-row">'; // Nouvelle ligne
                        $currentRole = $ap['Role'];
                    }
        ?>
            

            <div class="user">

                <h3 id="identite"> <?= $ap['Nom'].' '.$ap['Prenom'] ?></h3>

                <!-- <div id="info">Informations :</div> -->
                <div id="Information">
                    <div class="row">
                        <div class="label_profil">
                            <label>User ID :</label>
                        </div>
                        <div class="info_profil">
                            <p><?= $ap['ID'] ?></p>
                        </div>
                    </div>
                    <div class="row">
                        <div class="label_profil">
                            <label>Rôle :</label>
                        </div>
                        <div class="info_profil">
                            <p><?= $ap['Role'] ?></p>
                        </div>
                    </div>
                    <div class="row">
                        <div class="label_profil">
                            <label>E-mail :</label>
                        </div>
                        <div class="info_profil">
                            <p><?= $ap['Email'] ?></p>
                        </div>
                    </div>
                    <div class="row">
                        <div class="label_profil">
                            <label>Numéro de téléphone :</label>
                        </div>
                        <div class="info_profil">
                            <p><?= $ap['Telephone'] ?></p>
                        </div>
                    </div>
                    <div class="row">
                        <div class="label_profil">
                            <label>École :</label>
                        </div>
                        <div class="info_profil">
                            <p><?= $ap['Ecole'] ?></p>
                        </div>
                    </div>
                    <div class="row">
                        <div class="label_profil">
                            <label>Entreprise :</label>
                        </div>
                        <div class="info_profil">
                            <p><?= $ap['Entreprise'] ?></p>
                        </div>
                    </div>
                    <div class="row">
                        <div class="label_profil">
                            <label>Niveau d'étude :</label>
                        </div>
                        <div class="info_profil">
                            <p><?= $ap['Niveau'] ?></p>
                        </div>
                    </div>
                    <div class="row">
                        <div class="label_profil">
                            <label>Ville :</label>
                        </div>
                        <div class="info_profil">
                            <p><?= $ap['Ville'] ?></p>
                        </div>
                    </div>
                    <div class="row">
                        <div class="label_profil">
                            <label>Date de création de compte :</label>
                        </div>
                        <div class="info_profil">
                            <p> <?= $ap['DateDebut'] ?></p>
                        </div>
                    </div>
                    <div class="row">
                        <div class="label_profil">
                            <label>Date de destruction du compte :</label>
                        </div>
                        <div class="info_profil">
                            <p><?= $ap['DateFin'] ?></p>
                        </div>
                    </div>

                </div>

                <button onclick="window.location.href = 'update_profile.php';" id="update">Modifier</button>
                <button onclick="window.location.href = 'Supprimer.php';" id="update">Supprimer</button>

            </div>


            <?php
    }
}

if ($currentRole !== null) {
    echo '</div>'; // Fermer la dernière ligne
}
?>

<button onclick="window.location.href = 'add_user.php';" id="update">Ajouter un nouvel utilisateur</button>

        </div>

        <?php

            include ('footer.php');
        ?> 
    </body>

</html>