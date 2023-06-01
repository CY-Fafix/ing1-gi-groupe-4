<?php

    
    include('header.php');
    require_once __DIR__ . '/../../src/classes/Database.php';
    require_once __DIR__ . '/../../src/classes/Database.php';
    require_once __DIR__ . '/../../src/classes/Gestionnaire.php';
    require_once __DIR__ . '/../../src/controllers/gestionnaire_controller.php';
    require_once __DIR__ . '/../../src/controllers/user_controller.php';
    require_once __DIR__ . '/../../src/classes/Etudiant.php';
    require_once __DIR__ . '/../../src/controllers/etudiant_controller.php';


    //Si l'utilisateur n'est pas connecté ou n'est pas admin il ne va pas sur cette page
    if (!isset($_SESSION['user_id'])){
        if ($_SESSION['role']!= 'Admin'){  

            header('Location: ../index.php');
            exit;
        }
    }


    // Créer une nouvelle instance de Database
    $db = new Database();
    $userController= new UserController();


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

    if (!empty($_POST)) {
        extract($_POST);
        $valid = true;

        if (isset($_POST['edit_user'])) {
            
            $nom = htmlentities(trim($nom));
            $prenom = htmlentities(trim($prenom));
            $email = htmlentities(strtolower(trim($email)));
            $telephone = htmlentities(trim($telephone));
            $entreprise = htmlentities(trim($entreprise));
            $niveau = htmlentities(trim($niveau));
            $ecole = htmlentities(trim($ecole));
            $ville = htmlentities(trim($ville));
            $dateFin = htmlentities(trim($dateFin));
            echo "$nom";
            

        }

        if ($valid) {
            if ($role=="Etudiant"){
                $controller = new EtudiantController();
                // Créer une instance de l'étudiant avec les nouvelles données
                $etudiant = new Etudiant($nom, $prenom, $entreprise, $telephone, $email, $dateDebut, $dateFin, $mdp, $role, $niveau, $ecole, $ville);
                $etudiant->setID($_SESSION['user_id']);
                $etudiant->setNom($nom);
                $etudiant->setPrenom($prenom);
                $etudiant->setTelephone($telephone);
                $etudiant->setEcole($ecole);
                $etudiant->setVille($ville);
                $etudiant->setNiveau($niveau);
                $etudiant->setEmail($email);
                $etudiant->setRole("Etudiant");
                $etudiant->setMotDePasse($mdp);
                // Mettre à jour le profil de l'étudiant
                $valide = $controller->updateProfile($etudiant);
                if ($valide) {
                    header('Location: utilisateurs.php');
                    exit;
                }
            }elseif($role == "Gestionnaire"){
                $controller = new GestionnaireController();
                // Créer une instance du gestionnaire avec les nouvelles données
                $gestionnaire = new Gestionnaire($nom, $prenom, $entreprise, $telephone, $email, $dateDebut, $dateFin, $role, $niveau, $ecole, $ville);
                $gestionnaire->setID($_SESSION['user_id']);
                $gestionnaire->setNom($nom);
                $gestionnaire->setPrenom($prenom);
                $gestionnaire->setTelephone($telephone);
                $gestionnaire->setEntreprise($entreprise);
                $gestionnaire->setVille($ville);
                $gestionnaire->setEmail($email);
                $gestionnaire->setRole("Gestionnaire");
                // Mettre à jour le profil de l'étudiant
                $valide = $controller->updateProfile($gestionnaire);
                if ($valide) {
                    header('Location: utilisateurs.php');
                    exit;
                }

            }else {
                echo "problème";

            }
                    

                
        }
    }elseif (isset($_GET['delete'])) {
        $email = $_GET['delete'];
    
        // $projet = $dataChallengeController->getProjectById($projetId);
        $userController->deleteAccount($email);
    
        header('Location: utilisateurs.php');
        exit();
    }
    

    
    
 


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
        <div id="main">   

            <h2>Utilisateurs</h2>
            <div class="info" style="overflow-x:auto;">
            <table>
                <tr>
                    <th>ID</th>
                    <th>Role</th>
                    <th>Nom</th>
                    <th>Prenom</th>
                    <th>E-mail</th>
                    <th>Telephone</th>
                    <th>Entreprise</th>
                    <th>Ecole</th>
                    <th>Niveau d'étude</th>
                    <th>Ville</th>
                    <th>Date de début</th>
                    <th>Date de fin</th>
                </tr>

            
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
                            echo '<div class="user-row">'; // Nouvelle ligne
                            $currentRole = $ap['Role'];
                        }
            ?>


                

            <div class="user">
                <tr>          
                    <td><?= $ap['ID'] ?></td>

                    <td>
                        <form method="POST" action="utilisateurs.php?edit=<?= $ap['ID'] ?>">
                        <input type="text" name="role" value="<?= $ap['Role'] ?>">
                    </td>

                    <td>
                        <input type="text" name="nom" value="<?= $ap['Nom'] ?>">
                    </td>

                    <td><input type="text" name="prenom" value="<?= $ap['Prenom'] ?>"></td>

                    <td><input type="text" name="email" value="<?= $ap['Email'] ?>"></td>

                    <td><input type="text" name="telephone" value="<?= $ap['Telephone'] ?>"></td>

                    <td><input type="text" name="entreprise" value="<?= $ap['Entreprise'] ?>"></td>

                    <td><input type="text" name="ecole" value="<?= $ap['Ecole'] ?>"></td>

                    <td>
                        <p>Current :<?= $ap['Niveau'] ?></p>
                        <br>
                        <input type="radio" id="html" name="niveau" value="L1">
                        <label for="html">L1</label><br>
                        <input type="radio" id="css" name="niveau" value="L2">
                        <label for="css">L2</label><br>
                        <input type="radio" id="javascript" name="niveau" value="L3">
                        <label for="javascript">L3</label>
                        <input type="radio" id="javascript" name="niveau" value="M1">
                        <label for="javascript">M1</label>
                        <input type="radio" id="javascript" name="niveau" value="M2">
                        <label for="javascript">M2</label>
                        <input type="radio" id="javascript" name="niveau" value="D">
                        <label for="javascript">D</label>
                    
                    </td>

                    <td><input type="text" name="ville" value="<?= $ap['Ville'] ?>"></td>

                    <td><input type="text" name="dateDebut" value="<?= $ap['DateDebut'] ?>"></td>

                    <td><input type="text" name="dateFin" value="<?= $ap['DateFin'] ?>"></td>

                    <td><button type="submit" name="edit_user">Modifier</button></form></td>

                    <td><button onclick="window.location.href = 'utilisateurs.php?delete=<?= $ap['Email']?>'">Supprimer</button></td>

                </tr>
                
            

            

            </div>
        


            <?php
                    }
                }

                if ($currentRole !== null) {
                    echo '</div>'; // Fermer la dernière ligne
                }
            ?>
            </table>
                </div>

            <button onclick="window.location.href = 'add_user.php';" id="update">Ajouter un nouvel utilisateur</button>

        </div>
        </body>

        <?php

            include ('footer.php');
        ?> 
    

</html>