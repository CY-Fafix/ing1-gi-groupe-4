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
					<input class="Verify" id="DateDebutZone" type="text" name="dateDebut" placeholder="Entrez la date de début" pattern="[0-9]{2}+_[0-9]{2}+_[0-9]{4}" required>
					<span class="Erreur" id="FormatDate1" aria-live="polite"> <em>La date ne peut pas être antérieure à celle du jour (!)</em> </span>
				</label>
				
				<label id="DateFin"> Date de fin :
					<input class="Verify" id="DateFinZone" type="text" name="dateFin" placeholder="Entrez la date de fin" pattern="[0-9]{2}+_[0-9]{2}+_[0-9]{4}" required>
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
				
				
				
				<input type="submit" id="Ok" name="ok" value="OK" />
				<input type="reset" id="Reset" name="reset" value="Annuler" />
				
			</form>
			
		</div>
	</body>
	
</html>


<?php include('./footer.php'); ?>


