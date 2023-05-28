<?php
require_once '../controllers/admin_controller.php';
require_once '../classes/Ressource.php';
require_once '../classes/ProjetData.php';
require_once '../classes/DefiData.php';

// Créer une instance du controller
$adminController = new AdminController();

// Créer des objets fictifs
$ressource = new Ressource(1, "http://test.com/resource.pdf", "PDF", "Ressource Test", "Ceci est une description de test.");
$ressources = [$ressource];
$contacts = []; 
$projetData = new ProjetData(35, "Projet Test", "Ceci est une description de test.", "http://test.com/image.jpg", $contacts, $ressources);
$projets = [$projetData];
$defiData = new DefiData(2, "DefiNouveau", "2023-06-01", "2023-06-30", 1, $projets);

// Tester les méthodes du controller
try {
    // Test de la méthode createProjectForDataChallenge, defiData doit exister
    $result = $adminController->createProjectForDataChallenge($projetData, $defiData);
    assert($result, "Echec du test createProjectForDataChallenge");
    echo "Test createProjectForDataChallenge passé avec succès.<br>";

    $projetData->setDescription("Hola");
    // Test de la méthode updateProjectForDataChallenge, le projetData doit exister
    $result = $adminController->updateProjectForDataChallenge($projetData, $defiData);
    assert($result, "Echec du test updateProjectForDataChallenge");
    echo "Test updateProjectForDataChallenge passé avec succès.<br>";
    
    $projetData->setId(55);
    // Test de la méthode deleteProjectForDataChallenge
    try {
        // Supprimer un projet inexistant
        $adminController->deleteProjectForDataChallenge($projetData);
    } catch (Exception $e) {
        echo "Une erreur s'est produite : " . $e->getMessage();
    }
    
    
    // Test de la méthode createDataChallenge
    $result = $adminController->createDataChallenge($defiData);
    assert($result, "Echec du test createDataChallenge");
    echo "Test createDataChallenge passé avec succès.\n";

    $defiData->setId(7);
    $defiData->setLibelle("OZEKOPZe");
    // Test de la méthode updateDataChallenge
    $result = $adminController->updateDataChallenge($defiData);
    assert($result, "Echec du test updateDataChallenge");
    echo "Test updateDataChallenge passé avec succès.\n";
    
    // Test de la méthode deleteDataChallenge
    $result = $adminController->deleteDataChallenge($defiData);
    assert($result, "Echec du test deleteDataChallenge");
    echo "Test deleteDataChallenge passé avec succès.\n";
    
    $projetData->setId(68);
    // Test de la méthode createResource
    $result = $adminController->createResource($ressource, $projetData);
    assert($result, "Echec du test createResource");
    echo "Test createResource passé avec succès.\n";
    
    $ressource->setId(21);
    $ressource->setFormat("Notebook");
    // Test de la méthode updateResource
    $result = $adminController->updateResource($ressource);
    assert($result, "Echec du test updateResource");
    echo "Test updateResource passé avec succès.\n";
    
    // Test de la méthode deleteResource
    $result = $adminController->deleteResource($ressource);
    assert($result, "Echec du test deleteResource");
    echo "Test deleteResource passé avec succès.\n";
    
    echo "Tous les tests ont réussi.";
    
} catch (Exception $e) {
    echo "Une erreur s'est produite lors des tests : " . $e->getMessage();
}
?>
