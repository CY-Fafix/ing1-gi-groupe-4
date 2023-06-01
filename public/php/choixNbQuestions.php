<?php
	include('./header.php');
	
?>


<html lang="fr">
	
	<head>
		<meta charset="UTF-8">
		
		<link href="../css/stylle.css" rel="stylesheet" />
		<link href="../css/questionnaire.css" rel="stylesheet" />
		<link href="https://fonts.googleapis.com/css?family=Open+Sans" rel="stylesheet" />
	
		<title> Choix nombre questions </title>
	</head>
	
	
	
	<body>
		<div class="Main">
			
			<p id="Title"> <strong>Choix du nombre de questions</strong> </p>
			
			<form id="QuestionnaireFormulaire" name="questionnaireFormulaire" onsubmit="return verifDateUlterieure()" method="POST" action="">
			<script type="text/javascript" src="../js/verificationDateUlterieure.js" defer> </script>
				
				<label id="TitreQuestionnaire"> Nombre de questions :
					<input class="Verify" id="TitreZone" type="text" name="nbQuestions" placeholder="Entrez le nombre de questions" pattern="[0-9]{1,}" required>
				</label>
				
				<br>
				
				
				<input type="submit" id="Ok" name="ok" value="OK" />
				<input type="reset" id="Reset" name="reset" value="Annuler" />
				
				
				
				<?php
					if ($_SERVER["REQUEST_METHOD"] == "POST") {
						$_SESSION["nbQuestions"] = $_POST["nbQuestions"];
						header("Location:./creationQuestionnaire.php");
					}
				?>
				
			</form>
			
		</div>
	</body>
	
</html>


<?php include('./footer.php'); ?>


