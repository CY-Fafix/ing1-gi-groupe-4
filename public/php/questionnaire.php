<?php include('./header.php'); ?>


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
				
				<!-- Ici, faire soit une boucle necessitant un nombre de questions entre precedemment par le gestionnaire,
				soit un script permettant de generer une nouvelle question via un bouton -->
				<label id="Question"> Question :
					<textarea class="Verify" id="QuestionZone" name="question" rows="16" cols="80" wrap=hard placeholder="Entrez la question ici" spellcheck="False" required> </textarea>
				</label>
				
				<?php
					for ($i = 1; $i <= $_SESSION["nbQuestions"]; $i ++) {
						echo('
							<label id="Question"> Question n°'.$i. ' :
								<textarea class="Verify" id="QuestionZone" name="question" rows="16" cols="80" wrap=hard placeholder="Entrez la question ici" spellcheck="False" required> </textarea>
							</label>
						');
					}
				?>
				
				
				<input type="submit" id="Ok" name="ok" value="OK" />
				<input type="reset" id="Reset" name="reset" value="Annuler" />
				
				
				
				<?php
					$dateDebut = $dateFin = $titre = $contenu = "";
					
					if ($_SERVER["REQUEST_METHOD"] == "POST") {
						$dateDebut = $_POST["dateDebut"];
						$dateFin = $_POST["dateFin"];
						$titre = $_POST["titre"];
						for ($i = 0; $i < $_SESSION["nbQuestions"]; $i ++) {
							$contenu[$i] = $_POST["question$i"];
						}
						
						$questionnaire = new Questionnaire(16, $dateDebut, $dateFin, $titre, $contenu);
						var_dump($questionnaire);
						createQuestionnaire($questionnaire, 1);
					}
				?>
				
			</form>
			
		</div>
	</body>
	
</html>


<?php include('./footer.php'); ?>


