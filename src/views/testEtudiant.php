<?php
require_once '../classes/Database.php';
require_once '../classes/Utilisateur.php';
require_once '../classes/Etudiant.php';
require_once '../controllers/etudiant_controller.php';

$controller = new EtudiantController();

//Création d'un nouvel utilisateur étudiant
$etudiant = new Etudiant(54, "Doe", "John", "tetetetete@example.com", "password", "0123456789", "Paris", "etudiant", "L1", "Ecole XYZ");

//L1 L2 L3 M1 M2 D
try {
    //Création de l'utilisateur
    $success = $controller->createUser($etudiant);
    if($success) {
        echo "Création de l'étudiant réussie.<br>";
    } else {
        echo "Echec de la création de l'étudiant.<br>";
    }
    //Mise à jour de l'utilisateur
    $etudiant->setNiveau("L2");
    $etudiant->setEcole("Ecole ABC");
    $success = $controller->updateProfile($etudiant);
    if($success) {
        echo "Mise à jour de l'étudiant réussie.<br>";
    } else {
        echo "Echec de la mise à jour de l'étudiant.<br>";
    }
    // Création d'un objet ProjetData
    // On crée l'équipe et on ajoute l'étudiant à l'équipe
    $projetData = new ProjetData(27, "Projet 1", "Ceci est une description de projet", "/path/to/image.png", [], []);
    $equipe = $controller->registerToDataChallenge($projetData, $etudiant,"Stp");
    if($equipe){
        echo "Succès de l'inscription de l'étudiant au projet Data Challenge.<br>";
        echo "Informations de l'équipe créée: Nom: " . $equipe->getNom() . ", Chef d'équipe: " . $equipe->getChefEquipe() . ", Membres: " . implode(",", $equipe->getMembres()) . "<br>";
    }else{
        echo "Echec de l'inscription de l'étudiant au projet Data Challenge.<br>";
    }

    // Création d'un nouvel utilisateur étudiant à ajouter à l'équipe
    $etudiant2 = new Etudiant(55, "Doe", "Jane", "deuxiemeEtu@example.com", "password", "0123456789", "Paris", "etudiant", "L1", "Ecole XYZ");
    $success = $controller->createUser($etudiant2);
    if($success) {
        echo "Création de l'étudiant 2 réussie.<br>";
    } else {
        echo "Echec de la création de l'étudiant 2.<br>";
    }

    $equipeTest = new Equipe(90, "test", [54, 55], 54);
    
    // Retirer l'étudiant 2 de l'équipe
    $success = $controller->removeMemberFromTeam($equipeTest, $etudiant, $etudiant2);
    if($success){
        echo "Retrait de l'étudiant 2 de l'équipe réussi !!<br>";
    }else{
        echo "Echec du retrait de l'étudiant 2 de l'équipe !!<br>";
    }

    /*
    // Ajouter le nouvel étudiant à l'équipe
    $success = $controller->addMemberToTeam($equipe, $etudiant, $etudiant2);
    if($success){
        echo "Ajout du nouvel étudiant à l'équipe réussi !!<br>";
    }else{
        echo "Echec de l'ajout du nouvel étudiant à l'équipe !!<br>";
    } */

    // Ajouter le nouvel étudiant à l'équipe
    $success = $controller->deleteTeam($equipeTest, $etudiant);
    if($success){
        echo "Equipe supprimée !!<br>";
    }else{
        echo "Echec de la suppression de l'équipe !!<br>";
    }

} catch (Exception $e) {
    echo 'Exception reçue : ',  $e->getMessage(), "\n";
}
?>
