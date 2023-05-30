<?php
session_start();

require_once __DIR__ . '/../../src/classes/Database.php';
require_once __DIR__ . '/../../src/classes/Utilisateur.php';
require_once __DIR__ . '/../../src/classes/Etudiant.php';
require_once __DIR__ . '/../../src/classes/Equipe.php';
require_once __DIR__ .'/../../src/controllers/etudiant_controller.php';

// Vérifier si l'utilisateur est connecté
if (!isset($_SESSION['user_id']) || !isset($_SESSION['role']) || $_SESSION['role'] !== 'Etudiant') {
    die("Accès non autorisé. Vous devez être connecté en tant qu'étudiant.");
}

// initialisation du contrôleur
$etudiantController = new EtudiantController();

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $team_id = $_POST['team_id'];
    $member_id = $_POST['member_id'];

    // Récupérer l'objet Equipe
    $db = new Database();
    $conn = $db->connect();
    $stmt = $conn->prepare("SELECT * FROM Equipes WHERE ID = ?");
    $stmt->bind_param("i", $team_id);
    $stmt->execute();
    $teamData = $stmt->get_result()->fetch_assoc();
    $stmt->close();

    // Récupérer les membres de l'équipe
    $stmt = $conn->prepare("SELECT ID_Utilisateur FROM MembresEquipe WHERE ID_Equipe = ?");
    $stmt->bind_param("i", $team_id);
    $stmt->execute();
    $membreResult = $stmt->get_result();
    $membres = [];
    while ($membreRow = $membreResult->fetch_assoc()) {
        $membres[] = $membreRow['ID_Utilisateur'];
    }
    $stmt->close();

    // Créer l'objet Equipe
    $equipe = new Equipe($teamData['ID'], $teamData['Nom'], $membres, $teamData['ID_Capitaine']);

    // Récupérer l'objet Etudiant pour le capitaine
    $stmt = $conn->prepare("SELECT * FROM Utilisateurs WHERE ID = ?");
    $stmt->bind_param("i", $_SESSION['user_id']);
    $stmt->execute();
    $capitaineData = $stmt->get_result()->fetch_assoc();
    $stmt->close();

    $capitaine = new Etudiant($capitaineData['ID'], $capitaineData['Nom'], $capitaineData['Prenom'], $capitaineData['Email'], $capitaineData['MotDePasse'], $capitaineData['Telephone'], $capitaineData['Ville'], $capitaineData['Role'], $capitaineData['Niveau'], $capitaineData['Ecole']);

    // Récupérer l'objet Etudiant pour le membre à supprimer
    $stmt = $conn->prepare("SELECT * FROM Utilisateurs WHERE ID = ?");
    $stmt->bind_param("i", $member_id);
    $stmt->execute();
    $memberData = $stmt->get_result()->fetch_assoc();
    $stmt->close();

    $member = new Etudiant($memberData['ID'], $memberData['Nom'], $memberData['Prenom'], $memberData['Email'], $memberData['MotDePasse'], $memberData['Telephone'], $memberData['Ville'], $memberData['Role'], $memberData['Niveau'], $memberData['Ecole']);

    // Supprimer le membre de l'équipe
    try {
        $etudiantController->removeMemberFromTeam($equipe, $capitaine, $member);
        echo "Le membre a été supprimé avec succès.";
    } catch (Exception $e) {
        echo "Erreur: " . $e->getMessage();
    }

    $db->close();
} else {
    echo "Erreur: Requête invalide.";
}
?>
