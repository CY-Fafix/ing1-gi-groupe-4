<?php
require_once __DIR__ . '/../../src/classes/Database.php';
require_once __DIR__ .'/../../src/controllers/etudiant_controller.php';

$db = new Database();
$conn = $db->connect();

//On filtre que les requetes POST
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    session_start();
        // On check si c'est bien un etudiant

    if (isset($_SESSION['role']) && $_SESSION['role'] === 'Etudiant') {
        $userId = $_SESSION['user_id'];
        $projectId = $_POST['project_id'];
        $team_name = $_POST['team_name'];

        $etudiantController = new EtudiantController($conn);

        // On recup les infos du projet 
        $stmt = $conn->prepare("SELECT * FROM Projets WHERE ID = ?");
        $stmt->bind_param("i", $projectId);
        $stmt->execute();
        $result = $stmt->get_result();

        if ($result->num_rows > 0) {
            $projectData = $result->fetch_assoc();

            $projetData = new ProjetData($projectData['ID'],$projectData['Libelle'],$projectData['Description'],$projectData['ImageURL'],[],[]);

            // Obtenir l'objet Etudiant du chef d'équipe
            $capitaine = $etudiantController->getEtudiantById($userId);
            $nomEquipe = $team_name;
            // On inscrit l'étudiant à la DataBattle
            $equipe = $etudiantController->registerToDataChallenge($projetData, $capitaine, $nomEquipe);
            if ($equipe !== null) {
                echo "Inscription réussie à la DataBattle.";
            } else {
                echo "Erreur lors de l'inscription à la DataBattle.";
            }
        } else {
            echo "Le projet n'a pas été trouvé.";
        }
    } else {
        echo "Accès refusé. Veuillez vous connecter en tant qu'étudiant en retournant sur la page d'accueil des projets";
    }
} else {
    echo "Erreur : Méthode non autorisée";
}
?>
