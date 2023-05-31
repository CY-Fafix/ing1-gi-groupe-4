<?php

    
    include('header.php');
    // include('../../src/classes/Etudiant.php');
    require_once __DIR__ . '/../../src/classes/Database.php';
    require_once __DIR__ . '/../../src/classes/Gestionnaire.php';
    require_once __DIR__ . '/../../src/controllers/gestionnaire_controller.php';
    require_once __DIR__ . '/../../src/controllers/user_controller.php';


    //Si l'utilisateur n'est pas connecté on ne va pas sur cette page
    if (!isset($_SESSION['user_id'])){
        if ($_SESSION['role']!= 'Gestionnaire'){  
            echo  
            header('Location: ../index.php');
            exit;
        }
    }

    // Créer une nouvelle instance de Database
    $db = new Database();
    $controller = new GestionnaireController();

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


    if (!empty($_POST)) {
        extract($_POST);
        $valid = true;

        if (isset($_POST['modification'])) {
            echo("set");
            $nom = htmlentities(trim($nom));
            $prenom = htmlentities(trim($prenom));
            $email = htmlentities(strtolower(trim($email)));
            $telephone = htmlentities(trim($telephone));
            $mdp = htmlentities(trim($mdp));
            $entreprise = htmlentities(trim($entreprise));
            $ville = htmlentities(trim($ville));
            
            
    
            if (empty($nom)) {
                $valid = false;
                $er_nom = "Il faut mettre un nom";
            }
    
            if (empty($prenom)) {
                $valid = false;
                $er_prenom = "Il faut mettre un prénom";
            }

            if(empty($telephone)){
                $valid = false;
                $er_telephone = "Il faut mettre un numéro de téléphone";
            }

            // $numbers = array("1","2","3","4","5","6","7","8","9","0");
            // $num = explode("",$telephone);
            // foreach($num as $n){
            //     if(!in_array($n,$numbers)){
            //         $valid = false;
            //         $er_telephone = "Numéro de téléphone non valide";
            //     }
            // }
            
            if(empty($mdp)){
                $valid = false;
                $er_mdp = "Il faut mettre un mot de passe";
            }
            
            if(empty($entreprise)){
                $valid = false;
                $er_entreprise = "Il faut mettre un entreprise";
            }
            
            
            
            if(empty($ville)){
                $valid = false;
                $er_ville = "Il faut mettre une ville";
            }
    
            if (empty($email)) {
                $valid = false;
                $er_mail = "Il faut mettre un mail";
            }
            // } elseif (!preg_match("/^[a-z0-9\-_.]+@[a-z]+\.[a-z]{2,3}$/i", $email)) {
            //     $valid = false;
            //     $er_mail = "Le mail n'est pas valide";
            // } else {
            //     $req_mail = $db->query("SELECT Email FROM Utilisateurs WHERE mail = ?", array($email));
            //     if ($req_mail === false) {
            //         die('query() failed: ' . htmlspecialchars($con->error));
            //     }
            //     $req_mail = $req_mail->fetch();
    
            //     if ($req_mail['Email'] <> "" && $_SESSION['email'] != $req_mail['Email']) {
            //         $valid = false;
            //         $er_mail = "Ce mail existe déjà";
            //     }
    
            //     $req_mail->close(); // Fermer le résultat de la requête
            // }
            if ($valid) {
                // Créer une instance de l'étudiant avec les nouvelles données
                $gestionnaire = new Gestionnaire($nom, $prenom, $entreprise, $telephone, $email, $dateDebut, $dateFin, $mdp, $role, $niveau, $ecole, $ville);
                $gestionnaire->setID($_SESSION['user_id']);
                $gestionnaire->setNom($nom);
                $gestionnaire->setPrenom($prenom);
                $gestionnaire->setTelephone($telephone);
                $gestionnaire->setEntreprise($entreprise);
                $gestionnaire->setVille($ville);
                $gestionnaire->setEmail($email);
                $gestionnaire->setRole("Gestionnaire");
                $gestionnaire->setMotDePasse($mdp);
                // Mettre à jour le profil de l'étudiant
                $valide = $controller->updateProfile($gestionnaire);
                if ($valide) {
                    header('Location: profile_gestionnaire.php');
                    exit;
                }
            }
        } else{
            echo("not set");
        }
    }

?>

<!DOCTYPE html >
<html xml:lang="fr" lang="fr">
        <meta charset="UTF-8">
<head>

    <link href="../css/stylle.css" rel="stylesheet" />
    <link href="../css/update_profile.css" rel="stylesheet" />
    <link
    href="https://fonts.googleapis.com/css?family=Open+Sans"
    rel="stylesheet" />

    <title>Modification profile</title>
</head>

    <body>      

        <h2 id="Modif">Modification</h2>

        <form action="update_gestionnaire.php" method="post">

            <?php

                if (isset($er_nom)){

                ?>

                    <div><?= $er_nom ?></div>

                <?php   

                }

            ?>
            <div class="row">
                <label>Nom :</label>
                <input type="text" placeholder="Votre nom" name="nom" value="<?php if(isset($nom)){ echo $nom; }else{ echo $stmt['nom'];}?>" required>   
            </div>
            <?php

                if (isset($er_prenom)){

                ?>

                    <div><?= $er_prenom ?></div>

                <?php   

                }

            ?>
            <div class="row">
                <label>Prénom :</label>
                <input type="text" placeholder="Votre prénom" name="prenom" value="<?php if(isset($prenom)){ echo $prenom; }else{ echo $stmt['prenom'];}?>" required> 
            </div>
            
            <?php
                if (isset($er_mail)){
                ?>
                    <div><?= $er_mail ?></div>
                <?php   
                }
            ?>
            <div class="row">
                <label>E-mail :</label>
                <input type="email" placeholder="Adresse mail" name="email" value="<?php if(isset($email)){ echo $email; }else{ echo $stmt['mail'];}?>" required>
            </div>
          


            <?php

                if (isset($er_telephone)){

                ?>

                    <div><?= $er_telephone ?></div>

                <?php   

                }

            ?>
            <div class="row">
                <label>Numéro de téléphone :</label>
                <input type="text" placeholder="Téléphone" name="telephone" value="<?php if(isset($telephone)){ echo $telephone; }else{ echo $stmt['telephone'];}?>" required>
            </div>

            <?php

                if (isset($er_mdp)){

                ?>

                    <div><?= $er_mdp ?></div>

                <?php   

                }

            ?>
            <div class="row">
                <label>Mot de passe :</label>
                <input type="password" placeholder="Mot de passe" name="mdp" value="<?php if(isset($mdp)){ echo $mdp; }else{ echo $stmt['mdp'];}?>" required>
            </div>


            <?php

                if (isset($er_entreprise)){

                ?>

                    <div><?= $er_entreprise?></div>

                <?php   

                }

            ?>
            <div class="row">
                <label>Entreprise :</label>
                <input type="text" placeholder="Entreprise" name="entreprise" value="<?php if(isset($entreprise)){ echo $entreprise; }else{ echo $stmt['entreprise'];}?>" required>
            </div>

            <?php

                if (isset($er_ville)){

                ?>

                    <div><?= $er_ville ?></div>

                <?php   

                }

            ?>
            <div class="row">
                <label>Ville :</label>
                <?php 
                if (isset($ville)){
                    echo('<input type="text" placeholder="Ville" name="ville" value='. $ville.' required>');

                }else{
                    echo('<input type="text" placeholder="Ville" name="ville" value="Entrer une ville" required>');
                }?>
            </div>

            <button onclick="window.location.href = 'profile_gestionnaire.php';" type="submit" name="modification" id="update">Modifier</button>

        </form>


        <?php

            include ('footer.php');
        ?> 

    </body>

</html>

