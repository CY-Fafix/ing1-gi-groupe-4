<?php 
     require_once __DIR__ . '/../../src/classes/Database.php';
     require_once __DIR__ . '/../../src/classes/Gestionnaire.php';
     require_once __DIR__ . '/../../src/controllers/gestionnaire_controller.php';
     require_once __DIR__ . '/../../src/controllers/user_controller.php';
     require_once __DIR__ . '/../../src/classes/Etudiant.php';
     require_once __DIR__ . '/../../src/controllers/etudiant_controller.php';
     require_once __DIR__ . '/../../src/classes/Administrateur.php';
     require_once __DIR__ . '/../../src/controllers/admin_controller.php';
 

    $etudiantController = new EtudiantController();
    $gestionnaireController = new GestionnaireController();
    $adminController = new AdminController();

    $error_register = "";

    try {
        if($_SERVER["REQUEST_METHOD"] == "POST") {
            $nom = $_POST['nom'];
            $prenom = $_POST['prenom'];
            $motDePasse = $_POST['motDePasse'];
            $telephone = $_POST['telephone'];
            $ville = $_POST['ville'];
            $ecole = $_POST['ecole'];
            $anneeEtudes = $_POST['anneeEtudes'];
            $email = $_POST['email'];
            $role = $_POST['role'];
            $entreprise = $_POST['entreprise'];
            $dateDebut = $_POST['debut'];
            $dateFin = $_POST['fin'];

            if ($role== "Etudiant"){
                $etudiant = new Etudiant(null, $nom, $prenom, $email, $motDePasse, $telephone, $ville, "Etudiant", $anneeEtudes, $ecole);
                $success = $etudiantController->createUser($etudiant);

            } elseif ($role=="Gestionnaire") {
                $user = new Gestionnaire(null, $nom, $prenom, $email, $motDePasse, $telephone, $ville, "Gestionnaire",$entreprise, $dateDebut,$dateFin);
                $success = $gestionnaireController->createUser($user);
            } else{
                $user = new Admin(null, $nom, $prenom, $email, $motDePasse, $telephone, $ville, $role);
                $success = $adminController->createUser($user);
            }

            
            
            if(!$success){
                $error_register = "Erreur lors de la création de l'utilisateur. Veuillez réessayer.";
            }else{
				$error_register = "Inscription réussie";
                header('Location: utilisateurs.php');
			}
        }
    } catch (Exception $e) {
        $error_register = "Une erreur est survenue : " . $e->getMessage();
    }
    ?>
    <?php include('./header.php'); ?>


    <html lang="fr">
        
        <head>
            <meta charset="UTF-8">
            
            <link href="/public/css/stylle.css" rel="stylesheet" />
            <link href="/public/css/inscription.css" rel="stylesheet" />
            <link href="https://fonts.googleapis.com/css?family=Open+Sans" rel="stylesheet" />

            <style>
                .alert {
                    padding: 20px;
                    background-color: #f44336; /* Red */
                    color: white;
                    margin-bottom: 15px;
                }
            </style>
        
            <title> Ajout d'utilisateur </title>
        </head>
        
        <body>
            <div class="Main">
                
                <p id="Title"> <strong>Plus on est de fous, plus on rit</strong> </p>
                
                <form id="AjoutFormulaire" name="ajoutFormulaire" method="POST" action="" style="background-color: white;border-radius: 30px;width:70%;margin-top:30px;box-shadow: rgba(0, 0, 0, 0.25) 0px 54px 55px, rgba(0, 0, 0, 0.12) 0px -12px 30px, rgba(0, 0, 0, 0.12) 0px 4px 6px, rgba(0, 0, 0, 0.17) 0px 12px 13px, rgba(0, 0, 0, 0.09) 0px -3px 5px;width: 70%;">
                    
                    <p><strong>Ajout d'utilisateur</strong></p>

                    <label id="Role" class="element"> Rôle :
                        <select class="Verify" id="RoleZone" name="role">
                            <option value="Admin"> Admin </option>
                            <option value="Gestionnaire"> Gestionnaire </option>
                            <option value="Etudiant"> Étudiant </option>
                        </select>
                    </label>
                    
                    <label id="Nom" class="element"> Nom :
                        <input class="Verify" id="NomZone" type="text" name="nom" placeholder="Entrez un nom" required="">
                    </label>
                    
                    <label id="Prenom" class="element"> Prénom :
                        <input class="Verify" id="PrenomZone" type="text" name="prenom" placeholder="Entrez un prénom" required="">
                    </label>

                    <label id="MotDePasse" class="element"> Mot de passe :
                        <input class="Verify" id="MotDePasseZone" type="password" name="motDePasse" placeholder="Entrez un mot de passe" required="">
                    </label>

                    <label id="Telephone" class="element"> Téléphone :
                        <input class="Verify" id="TelephoneZone" type="tel" name="telephone" placeholder="Entrez un numéro de téléphone" required="">
                    </label>

                    <label id="Ville" class="element"> Ville :
                        <input class="Verify" id="VilleZone" type="text" name="ville" placeholder="Entrez votre ville" required="">
                    </label>

                    <label id="Entreprise" class="element"> Entreprise :
                        <input class="Verify" id="EntrepriseZone" type="text" name="entreprise" placeholder="Entrez le nom de l'entreprise">
                    </label>

                    <label id="Ecole" class="element"> École :
                        <input class="Verify" id="EcoleZone" type="text" name="ecole" placeholder="Entrez le nom de l'école">
                    </label>
                    
                    <label id="AnneeEtude" class="element"> Année d'études :
                        <select class="Verify" id="AnneeEtudesZone" name="anneeEtudes">
                            <option value="L1"> L1 </option>
                            <option value="L2"> L2 </option>
                            <option value="L3"> L3 </option>
                            <option value="M1"> M1 </option>
                            <option value="M2"> M2 </option>
                            <option value="D"> D </option>
                            <option value="NULL"> Aucun </option>
                        </select>
                    </label>

                    <label id="Email" class="element"> E-mail :
                        <div>
                            <input class="Verify" id="EmailZone" type="mail" name="email" placeholder="Entrez votre mail" pattern="[a-zA-Z0-9._-]+@[a-zA-Z0-9_-]+.[a-zA-Z]{2,}" required="">
                        </div>
                        
                    </label>
                    <div>
                        <span class="Erreur" id="FormatMail1" aria-live="polite"> <em>Une adresse au format ___@___.__ est attendue</em> </span>
                    </div>

                    <label id="DateDebut" class="element"> Date de début:
                        <input type="datetime-local" class="Verify" id="DebutZone" type="text" name="debut" >
                    </label>

                    <label id="DateFin" class="element"> Date de fin:
                        <input type="date" class="Verify" id="FinZone" type="text" name="fin" >
                    </label>

                    <input type="submit" class="Verify" id="valider_inscription" name="valider_inscription" value="S'INSCRIRE">
            </form>
                    
                   
                
            </div>
        </body>
        
    </html>


    <?php include('./footer.php'); ?>