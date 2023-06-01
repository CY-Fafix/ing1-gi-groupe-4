<?php
	ob_start();
	include('header.php');
	session_start();
	
	require_once '../../src/classes/Database.php';
	require_once '../../src/classes/Utilisateur.php';
	require_once '../../src/classes/Gestionnaire.php';
	require_once '../../src/classes/Questionnaire.php';
	require_once '../../src/controllers/gestionnaire_controller.php';
?>


<html lang="fr">
	
	<head>
		<meta charset="UTF-8">
		
		<link href="../css/stylle.css" rel="stylesheet" />
		<link href="../css/questionnaire.css" rel="stylesheet" />
		<link href="https://fonts.googleapis.com/css?family=Open+Sans" rel="stylesheet" />
	
		<title> Creation questionnaire </title>
	</head>
	
	
	
	<body>
		<div class="Main">
			
			<p id="Title"> <strong>Nouveau questionnaire</strong> </p>
			
			<form id="QuestionnaireFormulaire" name="questionnaireFormulaire" onsubmit="return verifDateUlterieure()" method="POST" action="">
			<!--<script type="text/javascript" src="../js/verificationDateUlterieure.js" defer> </script> --> 
				
				<label id="DateDebut"> Date de début :
					<input class="Verify" id="DateDebutZone" type="date" name="dateDebut" placeholder="Entrez la date de début" required>
					<span class="Erreur" id="FormatDate1" aria-live="polite"> <em>La date ne peut pas être antérieure à celle du jour (!)</em> </span>
					<br>
					<br>
				</label>
				
				<label id="DateFin"> Date de fin :
					<input class="Verify" id="DateFinZone" type="date" name="dateFin" placeholder="Entrez la date de fin" required>
					<span class="Erreur" id="FormatDate2" aria-live="polite"> <em>La date doit être ultérieure à celle du jour (!)</em> </span>
					<br>
					<br>
				</label>
				
				
				<br>
				
				
				<?php
					for ($i = 1; $i <= $_SESSION["nbQuestions"]; $i ++) {
						echo('
							<label id="Question"> Question n°'.$i. ' :
								<br>
								<textarea class="Verify" id="QuestionZone" name="question' .$i.'" rows="16" cols="80" wrap=hard placeholder="Entrez la question ici" spellcheck="False" required></textarea>
								<br>
							</label>
							
							<br>
						');
					}
				?>
				
				
				<input type="submit" id="Ok" name="ok" value="OK" />
				<input type="reset" id="Reset" name="reset" value="Annuler" />
				
				
				
				<?php
					$dateDebut = $dateFin = $titre = "";
					$contenu = [];
					
					if ($_SERVER["REQUEST_METHOD"] == "POST") {
						$controller = new GestionnaireController();
						$dateDebut = $_POST["dateDebut"];
						$dateFin = $_POST["dateFin"];
						$titre = $_POST["titre"];
						for ($i = 1; $i <= $_SESSION["nbQuestions"]; $i ++) {
							$contenu[$i] = $_POST["question$i"];
						}
						$id_gestionnaire = $_SESSION['user_id'];
						$idProjet= $controller->getIdProjetByIdGest($id_gestionnaire);
						$questionnaire = new Questionnaire(16,$contenu, $dateDebut, $dateFin, $idProjet);
						$emails = $controller->getEmailsByIdProjet($idProjet);
						$objet="Questionnaire";
						$date = date('Y-m-d');
						$id_questionnaire = $controller->createQuestionnaire($questionnaire, $id_gestionnaire);
						$contenu="voici le lien du Questionnaire : http://localhost:8080/public/php/affichageQuestionnaire.php?id=" . $id_questionnaire;
						$_SESSION['tout']=$emails;
						$controller->sendMessages($emails, $objet, $contenu, $id_gestionnaire, $date);


						header("Location:./affichageQuestionnaire.php?id=" . $id_questionnaire);
					}
				?>
				
			</form>
			
		</div>
	</body>
	
</html>


<?php
	include('./footer.php'); 
	ob_end_flush();
?>


