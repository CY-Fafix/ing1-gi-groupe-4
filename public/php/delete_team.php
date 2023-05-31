<?php
session_start();

require_once __DIR__ . '/../../src/classes/Database.php';
require_once __DIR__ . '/../../src/classes/Utilisateur.php';
require_once __DIR__ . '/../../src/classes/Etudiant.php';
require_once __DIR__ . '/../../src/classes/ProjetData.php';
require_once __DIR__ . '/../../src/classes/Equipe.php';
require_once __DIR__ . '/../../src/classes/Reponse.php';
require_once __DIR__ .'/../../src/controllers/user_controller.php';
require_once __DIR__ .'/../../src/controllers/etudiant_controller.php';

// Vérifier si l'utilisateur est connecté en tant qu'étudiant
if (!isset($_SESSION['role']) || $_SESSION['role'] !== 'Etudiant') {
    die("Accès non autorisé. Vous devez être connecté en tant qu'étudiant.");
}

// Initialiser le contrôleur
$etudiantController = new EtudiantController();

// Récupérer l'ID de l'équipe à supprimer
if (isset($_POST['team_id'])) {
    $teamId = $_POST['team_id'];

    // Récupérer l'étudiant connecté
    $etudiantId = $_SESSION['user_id'];
    $capitaine = new Etudiant($etudiantId, "", "", "", "", "", "", "", "", "");

    try {
        // Récupérer l'ID du chef d'équipe depuis la base de données
        $db = new Database();
        $conn = $db->connect();

        $stmt = $conn->prepare("SELECT ID_Capitaine FROM Equipes WHERE ID = ?");
        if ($stmt === false) {
            die('prepare() failed: ' . htmlspecialchars($conn->error));
        }
        $stmt->bind_param("i", $teamId);
        $stmt->execute();
        $stmt->bind_result($chefEquipeId);
        $stmt->fetch();
        $stmt->close();

        // Vérifier si l'ID du chef d'équipe a été récupéré
        if (!$chefEquipeId) {
            throw new Exception("Impossible de récupérer l'ID du chef d'équipe.");
        }

        echo "ID du chef d'équipe : " . $chefEquipeId;
        // Supprimer l'équipe
        $equipe = new Equipe($teamId, null, null, $chefEquipeId);
        $equipeSupprimee = $etudiantController->deleteTeam($equipe, $capitaine);

        if ($equipeSupprimee) {
            echo "L'équipe a été supprimée avec succès.";
        } else {
            echo "Impossible de supprimer l'équipe.";
        }
    } catch (Exception $e) {
        echo "Erreur lors de la suppression de l'équipe : " . $e->getMessage();
    }
} else {
    echo "ID de l'équipe non spécifié.";
}
?>
