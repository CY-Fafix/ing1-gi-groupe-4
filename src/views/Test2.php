<?php
	session_start();
	require_once '../classes/Database.php';
	require_once '../classes/Utilisateur.php';
	require_once '../classes/Gestionnaire.php';
	require_once '../classes/Questionnaire.php';
	require_once '../controllers/gestionnaire_controller.php';
?>


<html lang="fr">
	
	<head>
		<meta charset="UTF-8">
		
		<link href="https://fonts.googleapis.com/css?family=Open+Sans" rel="stylesheet" />
	
		<title> Questionnaire </title>
	</head>
	
	
	
	<body>
		<div class="Main">
			
			<p id="Title"> <strong>Nouveau questionnaire</strong> </p>
			
			<form id="QuestionnaireFormulaire" name="questionnaireFormulaire" onsubmit="return verifDateUlterieure()" method="POST" action="">
			<script type="text/javascript" src="../js/verificationDateUlterieure.js" defer> </script>
				
				<label id="DateDebut"> Date de début :
					<input class="Verify" id="DateDebutZone" type="date" name="dateDebut" placeholder="Entrez la date de début" required>
					<span class="Erreur" id="FormatDate1" aria-live="polite"> <em>La date ne peut pas être antérieure à celle du jour (!)</em> </span>
				</label>
				
				<label id="DateFin"> Date de fin :
					<input class="Verify" id="DateFinZone" type="date" name="dateFin" placeholder="Entrez la date de fin" required>
					<span class="Erreur" id="FormatDate2" aria-live="polite"> <em>La date doit être ultérieure à celle du jour (!)</em> </span>
				</label>
				
				<label id="TitreQuestionnaire"> Titre :
					<input class="Verify" id="TitreZone" type="text" name="titre" placeholder="Entrez le titre du questionnaire" required>
				</label>
				
				<br>
				
				
				<?php
					for ($i = 1; $i <= $_SESSION["nbQuestions"]; $i ++) {
						echo('
							<label id="Question"> Question n°'.$i. ' :
								<br>
								<textarea class="Verify" id="QuestionZone" name="question' .$i.'" rows="16" cols="80" wrap=hard placeholder="Entrez la question ici" spellcheck="False" required> </textarea>
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
					
					var_dump($_SERVER);
					
					if ($_SERVER["REQUEST_METHOD"] == "POST") {
						$dateDebut = $_POST["dateDebut"];
						$dateFin = $_POST["dateFin"];
						$titre = $_POST["titre"];
						for ($i = 1; $i <= $_SESSION["nbQuestions"]; $i ++) {
							$contenu[$i] = $_POST["question$i"];
						}
						
						$questionnaire = new Questionnaire(16, $titre, $contenu, $dateDebut, $dateFin);
						var_dump($_SESSION);
						$_SESSION["questionnaire"] = $questionnaire;
						$controller = new GestionnaireController();
						$success = $controller->createQuestionnaire($questionnaire, 2);
						$_SESSION["success"] = $success;
					}
				?>
				
			</form>
			
		</div>
	</body>
	
</html>


