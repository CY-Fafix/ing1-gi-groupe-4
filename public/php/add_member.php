<?php
session_start();

require_once __DIR__ . '/../../src/classes/Database.php';
require_once __DIR__ . '/../../src/classes/Utilisateur.php';
require_once __DIR__ . '/../../src/classes/Etudiant.php';
require_once __DIR__ . '/../../src/classes/Equipe.php';
require_once __DIR__ .'/../../src/controllers/etudiant_controller.php';

// On check si l'utilisateur est connecté
if (!isset($_SESSION['user_id']) || !isset($_SESSION['role']) || $_SESSION['role'] !== 'Etudiant') {
    die("Accès non autorisé. Vous devez être connecté en tant qu'étudiant !!");
}

$etudiantController = new EtudiantController();

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $email = $_POST['email'];
    $team_id = $_POST['team_id'];

    // On cherche le nouvel utilisateur par son adresse e-mail
    $db = new Database();
    $conn = $db->connect();
    $stmt = $conn->prepare("SELECT * FROM Utilisateurs WHERE email = ?");
    $stmt->bind_param("s", $email);
    $stmt->execute();
    $userData = $stmt->get_result()->fetch_assoc();
    $stmt->close();

    // On va Créer l'objet Etudiant pour le nouvel utilisateur
    $newMember = new Etudiant($userData['ID'], $userData['Nom'], $userData['Prenom'], $userData['Email'], $userData['MotDePasse'], $userData['Telephone'], $userData['Ville'], $userData['Role'], $userData['Niveau'], $userData['Ecole']);

    // ON récupère l'objet Equipe
    $stmt = $conn->prepare("SELECT * FROM Equipes WHERE ID = ?");
    $stmt->bind_param("i", $team_id);
    $stmt->execute();
    $teamData = $stmt->get_result()->fetch_assoc();
    $stmt->close();

    // On récupère les membres de l'équipe
    $stmt = $conn->prepare("SELECT Utilisateurs.ID, Utilisateurs.Nom, Utilisateurs.Prenom FROM MembresEquipe JOIN Utilisateurs ON MembresEquipe.ID_Utilisateur = Utilisateurs.ID WHERE MembresEquipe.ID_Equipe = ?");
    $stmt->bind_param("i", $team_id);
    $stmt->execute();
    $membreResult = $stmt->get_result();
    $membres = [];
    while ($membreRow = $membreResult->fetch_assoc()) {
        $membres[] = [
            'id' => $membreRow['ID'],
            'nom' => $membreRow['Nom'],
            'prenom' => $membreRow['Prenom']
        ];
    }
    $stmt->close();

    // On va créer l'objet Equipe
    $equipe = new Equipe($teamData['ID'], $teamData['Nom'], $membres, $teamData['ID_Capitaine']);

    // Récupérer l'objet Etudiant pour le capitaine
    $stmt = $conn->prepare("SELECT * FROM Utilisateurs WHERE ID = ?");
    $stmt->bind_param("i", $_SESSION['user_id']);
    $stmt->execute();
    $capitaineData = $stmt->get_result()->fetch_assoc();
    $stmt->close();

    $capitaine = new Etudiant($capitaineData['ID'], $capitaineData['Nom'], $capitaineData['Prenom'], $capitaineData['Email'], $capitaineData['MotDePasse'], $capitaineData['Telephone'], $capitaineData['Ville'], $capitaineData['Role'], $capitaineData['Niveau'], $capitaineData['Ecole']);

    // Ajouter le nouveau membre à l'équipe
    try {
        $etudiantController->addMemberToTeam($equipe, $capitaine, $newMember);
        echo "Le membre a bien été ajouté avec succès.";
    } catch (Exception $e) {
        echo "Erreur: " . $e->getMessage();
    }

    $db->close();
} else {
    echo "Erreur: Requête invalide.";
}
?>
