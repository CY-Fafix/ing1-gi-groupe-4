<?php
	/*include('./header.php');*/
	session_start();
	
	require_once '../../src/classes/Database.php';
	require_once '../../src/classes/Utilisateur.php';
	require_once '../../src/classes/Gestionnaire.php';
	require_once '../../src/classes/Questionnaire.php';
	require_once '../../src/controllers/gestionnaire_controller.php';
	
	
	if !(isset($_SESSION["role"])) {
		header("Location:./connexion.php");
	}
	
	
	$etudiantController = new EtudiantController();
?>


<html lang="fr">
	
	<head>
		<meta charset="UTF-8">
		
		<link href="../css/stylle.css" rel="stylesheet" />
		<link href="../css/questionnaire.css" rel="stylesheet" />
		<link href="https://fonts.googleapis.com/css?family=Open+Sans" rel="stylesheet" />
	
		<title> Questionnaire </title>
	</head>
	
	
	
	<body>
		<div class="Main">
			
			<p id="Title"> <strong>Votre questionnaire</strong> </p>
			
			<form id="QuestionnaireFormulaire" name="questionnaireFormulaire" onsubmit="return verifDateUlterieure()" method="POST" action="">
			<script type="text/javascript" src="../js/verificationDateUlterieure.js" defer> </script>
				
				<label id="DateDebut"> Date de début :
					<!-- <input class="Verify" id="DateDebutZone" type="date" name="dateDebut" placeholder="Entrez la date de début" required> -->
					<br>
					<br>
				</label>
				
				<?php
					$dateDebut = $etudiantController->showDateDebut($id_Questionnaire);
					
					echo('
						<p> Date de début : ' .$dateDebut. ' </p>
						<br>
					');
				?>
				
				
				<label id="DateFin"> Date de fin :
					<!-- <input class="Verify" id="DateFinZone" type="date" name="dateFin" placeholder="Entrez la date de fin" required> -->
					<br>
					<br>
				</label>
				
				
				<?php
					$dateFin = $etudiantController->showDateFin($id_Questionnaire);
					
					echo('
						<p> Date de fin : ' .$dateFin. ' </p>
						<br>
					');
				?>
				
			</form>
			
			
			
			<table>
				<tbody>
					
					<?php
						$tabQuestions = $etudiantController->showQuestionnaire($id_Questionnaire);
						$i = 1;
						
						foreach ($tabQuestions as $contenuQuestion) {
							echo('
								<p> Question n° ' .$i. ' </p>
								<br>
								<p> ' .$contenuQuestion. ' </p>
							');
							
							$i += 1;
						}
						
					?>
				
				</tbody>
			</table>
		</div>
	</body>
	
</html>


<?php include('./footer.php'); ?>


