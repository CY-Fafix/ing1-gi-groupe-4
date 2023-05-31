<?php
require_once __DIR__ . '/../../src/classes/Database.php';
require_once __DIR__ . '/../../src/controllers/etudiant_controller.php';

$db = new Database();
$conn = $db->connect();


if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    // Vérifie si l'utilisateur est connecté en tant qu'étudiant
    session_start();
    if (isset($_SESSION['role']) && $_SESSION['role'] === 'Etudiant') {
        $userId = $_SESSION['user_id'];
        $projectId = $_POST['project_id'];

        // Créer une instance d'EtudiantController
        $etudiantController = new EtudiantController($conn);

        // Obtenir les informations du projet
        $stmt = $conn->prepare("SELECT * FROM Projets WHERE ID = ?");
        $stmt->bind_param("i", $projectId);
        $stmt->execute();
        $result = $stmt->get_result();

        if ($result->num_rows > 0) {
            $projectData = $result->fetch_assoc();

            // Obtenir l'objet ProjetData
            $projetData = new ProjetData(
                $projectData['ID'],
                $projectData['Libelle'],
                $projectData['Description'],
                $projectData['ImageURL'],
                [], // Remplacer par le tableau d'objets Contact correspondant
                []  // Remplacer par le tableau d'objets Ressource correspondant
            );

            // Obtenir l'objet Etudiant du capitaine
            $capitaine = $etudiantController->getEtudiantById($userId);

            // Nom de l'équipe souhaité
            $nomEquipe = "Nom de l'équipe souhaité";

            // Appeler la méthode registerToDataChallenge pour inscrire l'étudiant à la DataBattle
            $equipe = $etudiantController->registerToDataChallenge($projetData, $capitaine, $nomEquipe);

            if ($equipe !== null) {
                echo "Inscription réussie à la DataBattle.";
            } else {
                echo "Erreur lors de l'inscription à la DataBattle.";
            }
        } else {
            echo "Projet non trouvé.";
        }
    } else {
        echo "Accès refusé. Veuillez vous connecter en tant qu'étudiant.";
    }
} else {
    echo "Méthode non autorisée.";
}
?>
