<?php
	ob_start();
	include('./header.php');
	//session_start();
	
	require_once '../../src/classes/Database.php';
	require_once '../../src/classes/Utilisateur.php';
	require_once '../../src/classes/Gestionnaire.php';
	require_once '../../src/classes/Questionnaire.php';
	require_once '../../src/controllers/etudiant_controller.php';
	
	
	if (!isset($_SESSION["role"])) {
		header("Location:./connexion.php");
	}
	$id_Questionnaire=$_GET['id'];
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
				
				
				<?php
					$dateDebut = $etudiantController->showDateDebut($id_Questionnaire);
					echo('
						<p> Date de début : ' .$dateDebut. ' </p>
						<br>
					');

					$dateFin = $etudiantController->showDateFin($id_Questionnaire);
					
					echo('
						<p> Date de fin : ' .$dateFin. ' </p>
						<br>
					');
				?>
			
			
			
				<table>
					<tbody>
						
						<?php
							$tabQuestions = $etudiantController->showQuestionnaire($id_Questionnaire);
							$i = 1;
							foreach ($tabQuestions as $contenuQuestion) {
							echo('
								<label id="Question"> Question n° ' .$i. ' :
								<p> ' .$contenuQuestion. ' </p>								
									<textarea class="Verify" id="QuestionZone" name="question' .$i.'" rows="16" cols="80" wrap=hard placeholder="Entrez la question ici" spellcheck="False" required> </textarea>
									<br>
								</label>
								
								<br>
							');
								
								$i += 1;
							}
							
						?>
					
					</tbody>
				</table>
				<input type="submit" id="Ok" name="ok" value="OK" />
				<input type="reset" id="Reset" name="reset" value="Annuler" />

				<?php
					$j=1;
					if ($_SERVER["REQUEST_METHOD"] == "POST") {
						$reponses=array();
						$id_etudiant = $_SESSION["user_id"];
						$note=NULL;
						$teamID = $etudiantController->getTeamId($id_etudiant);
						foreach($tabQuestions as $contenuQuestion) {
							$idQuestion  = $etudiantController->getQuestionId($contenuQuestion);
							$contenu = $_POST["question$j"];
							$reponse = new Reponse(16,$idQuestion,$id_etudiant,$contenu,$note);
							array_push($reponses,$reponse);
							$j +=1 ;
						}

						$id_Gestionnaire =$etudiantController->getGestionnaireIdByQuestionnaireId($id_Questionnaire);
						$success = $etudiantController->answerQuestionnaire($teamID,$dateDebut,$dateFin,$reponses);
						$_SESSION['successp']=$success;
						echo header('Location: ../index.php');
					}
				?>
			</form>
		</div>
	</body>
	
</html>


<?php include('./footer.php');
ob_end_flush(); ?>


