<?php
require_once '../classes/Database.php';
require_once '../classes/Utilisateur.php';
require_once '../classes/Gestionnaire.php';
require_once '../classes/Questionnaire.php';
require_once '../controllers/gestionnaire_controller.php';

$controller = new GestionnaireController();

//Création d'un nouvel utilisateur étudiant
$gestionnaire = new Gestionnaire(5,'Smith', 'Jane', 'cocodingo@gmail.com', 'password2','0234567891','Paris', 'Gestionnaire','Company2', '2023-01-01', '2023-10-12');
//L1 L2 L3 M1 M2 D
try {
    //Création de l'utilisateur
    $success = $controller->createUser($gestionnaire);
    if($success) {
        echo "Création de l'étudiant réussie.<br>";
    } else {
        echo "Echec de la création de l'étudiant.<br>";
    }
    //Mise à jour de l'utilisateur
    $gestionnaire->setDebut("2023-03-01");
    $gestionnaire->setEntreprise("company 3");
    $success = $controller->updateProfile($gestionnaire);
    if($success) {
        echo "Mise à jour de l'étudiant réussie.<br>";
    } else {
        echo "Echec de la mise à jour de l'étudiant.<br>";
    }
    // On crée l'équipe et on ajoute l'étudiant à l'équipe
    $questionnaire = new Questionnaire(17,'questionnaire1',['Combien font 2+5','la racine de 9','qui est le président des Etats Unis'],'2023-01-01', '2023-01-01');
    $id_gest=3;
    $success = $controller->sendMessages('james.brown@example.com','mail test','Bonjour à tous , ceci est un test de mail',4,'2023-05-26');
    var_dump($success);
    if($success){
        echo "Succes d'inscription au projet data !!";
    }else{
        echo "Echec d'inscription du projet data !!";
    }
} catch (Exception $e) {
    echo 'Exception reçue : ',  $e->getMessage(), "\n";
}
?>